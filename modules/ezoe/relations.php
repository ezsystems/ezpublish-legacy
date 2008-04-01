<?php
//
// Created on: <5-Jul-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE extension for eZ Publish
// SOFTWARE RELEASE: 1.0
// COPYRIGHT NOTICE: Copyright (C) 2007 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

include_once( 'kernel/common/template.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'extension/ezoe/classes/ezajaxcontent.php' );

$objectID        = isset( $Params['ObjectID'] ) ? (int) $Params['ObjectID'] : 0;
$objectVersion   = isset( $Params['ObjectVersion'] ) ? (int) $Params['ObjectVersion'] : 0;
$embedInline     = isset( $Params['EmbedInline'] ) ? $Params['EmbedInline'] === 'true' : false;
$embedSize       = isset( $Params['EmbedSize'] ) ? $Params['EmbedSize'] : '';
$embedObjectJSON = 'false';
$embedId         = 0;

// Supported content types: image, file, object and auto
// file is not used, auto will decide according to site.ini rules
$contentType   = 'object';
if ( isset( $Params['ContentType'] ) && $Params['ContentType'] !== '' )
{
    $contentType = $Params['ContentType'];
}


if ( $objectID === 0  || $objectVersion === 0 )
{
   echo ezi18n( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'ObjectID/ObjectVersion' ) );
   eZExecution::cleanExit();
}


$object    = eZContentObject::fetch( $objectID );
$imageIni  = eZINI::instance( 'image.ini' );
$params    = array('dataMap' => array('image'), 'imagePreGenerateSizes' => array('small') );

if ( !$object )
{
   echo ezi18n( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ObjectId', '%value' => $objectID ) );
   eZExecution::cleanExit();
}


if ( isset( $Params['EmbedID'] )  && $Params['EmbedID'])
{
    $embedType = 'eZObject';
    if (  is_numeric( $Params['EmbedID'] ) )
        $embedId = $Params['EmbedID'];
    else
        list($embedType, $embedId) = explode('_', $Params['EmbedID']);
    if ( $embedType === 'eZNode' )
        $embedObject = eZContentObject::fetchByNodeID( $embedId );
    else
        $embedObject = eZContentObject::fetch( $embedId );
        
}


if ( !$embedObject )
{
   echo ezi18n( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'EmbedID', '%value' => $Params['EmbedID'] ) );
   eZExecution::cleanExit();
}


$imageSizeArray = $imageIni->variable( 'AliasSettings', 'AliasList' );
$siteIni        = eZINI::instance( 'site.ini' );
$contentIni     = eZINI::instance( 'content.ini' );
$sizeTypeArray  = array();


if ( $contentType === 'auto' )
{
    $imageClassIDArray     = $siteIni->variable('MediaClassSettings', 'ImageClassID' );
    $imageClassIdentifiers = $siteIni->variable( 'MediaClassSettings', 'ImageClassIdentifiers' );
    $classIdentifier       = $embedObject->attribute( 'class_identifier' );
    $classID               = $embedObject->attribute( 'contentclass_id' );

    if ( in_array( $classID, $imageClassIDArray ) || in_array( $classIdentifier, $imageClassIdentifiers ) )
        $contentType = 'image';
    else
        $contentType = 'object';
}


if ( $embedSize && $contentType === 'image' )
{
    $params['imagePreGenerateSizes'][] = $embedSize;
}

foreach( $imageSizeArray as $size )
{
    if ( $imageIni->hasVariable( $size, 'HideFromRelations' )
         && $imageIni->variable( $size, 'HideFromRelations' ) === 'enabled'  ) continue;
    if ( $imageIni->hasVariable( $size, 'GUIName' ) )
        $sizeTypeArray[$size] = $imageIni->variable( $size, 'GUIName' );
    else
        $sizeTypeArray[$size] = ucfirst( $size );
    $imagePixelSize = '';
    foreach( $imageIni->variable( $size, 'Filters' ) as $filter )
    {
        if ( strpos( $filter, 'geometry/scale' ) !== false or strpos( $filter, 'geometry/crop' ) !== false )
        {
            $filter = explode( '=', $filter );
            $filter = $filter[1];
            $filter = explode( ';', $filter );
            // Only support scale and crop that uses both width and height for now
            if ( isset( $filter[1] ) ) $imagePixelSize   = $filter[0] . 'x' . $filter[1];
            else $imagePixelSize = '';
        }
    }
    $sizeTypeArray[$size] .= ' ' . $imagePixelSize;
}

$sizeTypeArray['original'] = 'Original';


// class list with description
$classList = array();
$classListInline = array();

if ( $contentIni->hasVariable( 'embed', 'AvailableClasses' ) )
    $classListData = $contentIni->variable( 'embed', 'AvailableClasses' );

if ( $contentIni->hasVariable( 'embed-inline', 'AvailableClasses' ) )
    $classListInlineData = $contentIni->variable( 'embed-inline', 'AvailableClasses' );

if ( $contentIni->hasVariable( 'embed', 'ClassDescription' ) )
    $classListDescription = $contentIni->variable( 'embed', 'ClassDescription' );
    
if ( $contentIni->hasVariable( 'embed-inline', 'ClassDescription' ) )
    $classListDescriptionInline = $contentIni->variable( 'embed-inline', 'ClassDescription' );

if ( $classListData )
{
    $classList['0'] = 'None';
    foreach ( $classListData as $class )
    {
        if ( isset( $classListDescription[$class] ) )
            $classList[$class] = $classListDescription[$class];
        else
            $classList[$class] = $class;
    }
}

if ( $classListInlineData )
{
    $classListInline['0'] = 'None';
    foreach ( $classListInlineData as $class )
    {
        if ( isset( $classListDescriptionInline[$class] ) )
            $classListInline[$class] = $classListDescriptionInline[$class];
        else
            $classListInline[$class] = $class;
    }
}



// attribute defaults
$attributeDefaults = array();
$attributeDefaultsInline = array();

if ( $contentIni->hasVariable( 'embed', 'Defaults' ) )
    $attributeDefaults = $contentIni->variable( 'embed', 'Defaults' );

if ( $contentIni->hasVariable( 'embed-inline', 'Defaults' ) )
    $attributeDefaultsInline = $contentIni->variable( 'embed-inline', 'Defaults' );



// view mode list
if ( $siteIni->hasVariable( 'EmbedViewModeSettings', 'AvailableViewModes' ) )
    $viewList = array_unique( $siteIni->variable( 'EmbedViewModeSettings', 'AvailableViewModes' ) );
else
    $viewList = array();

if ( $siteIni->hasVariable( 'EmbedViewModeSettings', 'InlineViewModes' ) )
    $viewListInline = array_unique( $siteIni->variable( 'EmbedViewModeSettings', 'InlineViewModes' ) );
else
    $viewListInline = array();


$tpl = templateInit();
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'object_id', $objectID );
$tpl->setVariable( 'object_version', $objectVersion );

$tpl->setVariable( 'embed_id', $embedId );
$tpl->setVariable( 'embed_object', $embedObject );
$tpl->setVariable( 'embed_data', eZAjaxContent::encode( $embedObject, $params ) );
$tpl->setVariable( 'content_type', $contentType );
$tpl->setVariable( 'tag_name', $embedInline ? 'embed-inline' : 'embed' );

$tpl->setVariable( 'view_list', eZAjaxContent::jsonEncode( array( 'embed' => $viewList, 'embed-inline' => $viewListInline ) ) );
$tpl->setVariable( 'class_list', eZAjaxContent::jsonEncode( array( 'embed' => $classList, 'embed-inline' => $classListInline ) ) );
$tpl->setVariable( 'attribute_defaults', eZAjaxContent::jsonEncode( array( 'embed' => $attributeDefaults, 'embed-inline' => $attributeDefaultsInline ) ) );

$tpl->setVariable( 'size_list', $sizeTypeArray );

$defaultSize = $contentIni->variable( 'ImageSettings', 'DefaultEmbedAlias' );
$tpl->setVariable( 'default_size', $defaultSize );

if ( $contentIni->hasVariable( 'ImageSettings', 'DefaultCropAlias' ) )
    $tpl->setVariable( 'default_crop_size', $contentIni->variable( 'ImageSettings', 'DefaultCropAlias' ) );
else
    $tpl->setVariable( 'default_crop_size', $defaultSize );

    
$tpl->setVariable( 'persistent_variable', array() );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:ezoe/tag_embed.tpl' );
$Result['pagelayout'] = 'design:ezoe/popup_pagelayout.tpl';
$Result['persistent_variable'] = $tpl->variable( 'persistent_variable' );

return $Result;


//eZExecution::cleanExit();
//$GLOBALS['show_page_layout'] = false;

?>