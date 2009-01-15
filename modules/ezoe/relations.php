<?php
//
// Created on: <25-Des-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor extension for eZ Publish
// SOFTWARE RELEASE: 5.0
// COPYRIGHT NOTICE: Copyright (C) 2008 eZ Systems AS
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
include_once( 'extension/ezoe/classes/ezoeajaxcontent.php' );
include_once( 'extension/ezoe/ezxmltext/handlers/input/ezoexmlinput.php' );

$objectID        = isset( $Params['ObjectID'] ) ? (int) $Params['ObjectID'] : 0;
$objectVersion   = isset( $Params['ObjectVersion'] ) ? (int) $Params['ObjectVersion'] : 0;
$embedInline     = isset( $Params['EmbedInline'] ) ? $Params['EmbedInline'] === 'true' : false;
$embedSize       = isset( $Params['EmbedSize'] ) ? $Params['EmbedSize'] : '';
$embedObjectJSON = 'false';
$embedId         = 0;

// Supported content types: image, file, object and auto
// file is not used, auto will decide according to site.ini rules
$contentType   = 'objects';
if ( isset( $Params['ContentType'] ) && $Params['ContentType'] !== '' )
{
    $contentType = $Params['ContentType'];
}


if ( $objectID === 0  || $objectVersion === 0 )
{
   echo ezi18n( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'ObjectID/ObjectVersion' ) );
   eZExecution::cleanExit();
}


$user = eZUser::currentUser();
if ( $user instanceOf eZUser )
{
    $result = $user->hasAccessTo( 'ezoe', 'relations' );
}
else
{
    $result = array('accessWord' => 'no');
}

if ( $result['accessWord'] === 'no' )
{
   echo ezi18n( 'design/standard/error/kernel', 'Your current user does not have the proper privileges to access this page.' );
   eZExecution::cleanExit();
}


$object    = eZContentObject::fetch( $objectID );
$imageIni  = eZINI::instance( 'image.ini' );
$params    = array('loadImages' => true, 'imagePreGenerateSizes' => array('small') );

if ( !$object )
{
   echo ezi18n( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ObjectId', '%value' => $objectID ) );
   eZExecution::cleanExit();
}


if ( isset( $Params['EmbedID'] )  && $Params['EmbedID'])
{
    $embedType = 'eZObject';
    if ( is_numeric( $Params['EmbedID'] ) )
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


$imageSizeArray  = $imageIni->variable( 'AliasSettings', 'AliasList' );
$ini             = eZINI::instance( 'site.ini' );
$contentIni      = eZINI::instance( 'content.ini' );
$ezoeIni         = eZINI::instance( 'ezoe.ini' );
$embedClassIdentifier = $embedObject->attribute( 'class_identifier' );
$embedClassID    = $embedObject->attribute( 'contentclass_id' );
$sizeTypeArray   = array();


if ( $contentType === 'auto' )
{
    // figgure out what content type group this class is in
    $contentType = eZOEXMLInput::embedTagContentType( $embedClassIdentifier, $embedClassID );
}


if ( $embedSize && $contentType === 'images' )
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
            if ( isset( $filter[1] ) ) $imagePixelSize = $filter[0] . 'x' . $filter[1];
            else $imagePixelSize = '';
        }
    }
    $sizeTypeArray[$size] .= ' ' . $imagePixelSize;
}

$sizeTypeArray['original'] = 'Original';


// Get list of classes for embed and embed inline tags
// use specific class list this embed class type if it exists
if ( $contentIni->hasVariable( 'embed_' . $embedClassIdentifier, 'AvailableClasses' ) )
    $classListData = $contentIni->variable( 'embed_' . $embedClassIdentifier, 'AvailableClasses' );
else if ( $contentIni->hasVariable( 'embed-type_' . $contentType, 'AvailableClasses' ) )
    $classListData = $contentIni->variable( 'embed-type_' . $contentType, 'AvailableClasses' );
else if ( $contentIni->hasVariable( 'embed', 'AvailableClasses' ) )
    $classListData = $contentIni->variable( 'embed', 'AvailableClasses' );

// same for embed-inline
if ( $contentIni->hasVariable( 'embed-inline_' . $embedClassIdentifier, 'AvailableClasses' ) )
    $classListInlineData = $contentIni->variable( 'embed-inline_' . $embedClassIdentifier, 'AvailableClasses' );
else if ( $contentIni->hasVariable( 'embed-inline-type_' . $contentType, 'AvailableClasses' ) )
    $classListInlineData = $contentIni->variable( 'embed-inline-type_' . $contentType, 'AvailableClasses' );
else if ( $contentIni->hasVariable( 'embed-inline', 'AvailableClasses' ) )
    $classListInlineData = $contentIni->variable( 'embed-inline', 'AvailableClasses' );

// Get human readable class names
if ( $contentIni->hasVariable( 'embed', 'ClassDescription' ) )
    $classListDescription = $contentIni->variable( 'embed', 'ClassDescription' );
else
    $classListDescription = array();

if ( $contentIni->hasVariable( 'embed-inline', 'ClassDescription' ) )
    $classListDescriptionInline = $contentIni->variable( 'embed-inline', 'ClassDescription' );
else
    $classListDescriptionInline = array();

// generate class hash
$classList = array();
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

$classListInline = array();
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
if ( $contentIni->hasVariable( 'embed', 'Defaults' ) )
    $attributeDefaults = $contentIni->variable( 'embed', 'Defaults' );
else
    $attributeDefaults = array();

if ( $contentIni->hasVariable( 'embed-inline', 'Defaults' ) )
    $attributeDefaultsInline = $contentIni->variable( 'embed-inline', 'Defaults' );
else
    $attributeDefaultsInline = array();


// view mode list
if ( $contentIni->hasVariable( 'embed', 'AvailableViewModes' ) )
    $viewList = array_unique( $contentIni->variable( 'embed', 'AvailableViewModes' ) );
else
    $viewList = array();

if ( $contentIni->hasVariable( 'embed-inline', 'AvailableViewModes' ) )
    $viewListInline = array_unique( $contentIni->variable( 'embed-inline', 'AvailableViewModes' ) );
else
    $viewListInline = array();

// custom attributes
$customAttributes = array( 'embed' => array(), 'embed-inline' => array() );

if ( $contentIni->hasVariable( 'embed_' . $embedClassIdentifier, 'CustomAttributes' ) )
    $customAttributes['embed'] = $contentIni->variable( 'embed_' . $embedClassIdentifier, 'CustomAttributes' );
else if ( $contentIni->hasVariable( 'embed-type_' . $contentType, 'CustomAttributes' ) )
    $customAttributes['embed'] = $contentIni->variable( 'embed-type_' . $contentType, 'CustomAttributes' );
else if ( $contentIni->hasVariable( 'embed', 'CustomAttributes' ) )
    $customAttributes['embed'] = $contentIni->variable( 'embed', 'CustomAttributes' );

if ( $contentIni->hasVariable( 'embed-inline_' . $embedClassIdentifier, 'CustomAttributes' ) )
    $customAttributes['embed-inline'] = $contentIni->variable( 'embed-inline_' . $embedClassIdentifier, 'CustomAttributes' );
else if ( $contentIni->hasVariable( 'embed-inline-type_' . $contentType, 'CustomAttributes' ) )
    $customAttributes['embed-inline'] = $contentIni->variable( 'embed-inline-type_' . $contentType, 'CustomAttributes' );
else if ( $contentIni->hasVariable( 'embed-inline', 'CustomAttributes' ) )
    $customAttributes['embed-inline'] = $contentIni->variable( 'embed-inline', 'CustomAttributes' );

$tpl = templateInit();
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'object_id', $objectID );
$tpl->setVariable( 'object_version', $objectVersion );

$tpl->setVariable( 'embed_id', $embedId );
$tpl->setVariable( 'embed_type', $embedType );
$tpl->setVariable( 'embed_object', $embedObject );
$tpl->setVariable( 'embed_data', eZOEAjaxContent::encode( $embedObject, $params ) );
$tpl->setVariable( 'content_type', $contentType );
$tpl->setVariable( 'content_type_name', ucfirst( rtrim( $contentTypeCase, 's' ) ) );
$tpl->setVariable( 'compatibility_mode', $ezoeIni->variable('EditorSettings', 'CompatibilityMode' ) );

$tpl->setVariable( 'tag_name', $embedInline ? 'embed-inline' : 'embed' );

$tpl->setVariable( 'view_list', eZOEAjaxContent::jsonEncode( array( 'embed' => $viewList, 'embed-inline' => $viewListInline ) ) );
$tpl->setVariable( 'class_list', eZOEAjaxContent::jsonEncode( array( 'embed' => $classList, 'embed-inline' => $classListInline ) ) );
$tpl->setVariable( 'attribute_defaults', eZOEAjaxContent::jsonEncode( array( 'embed' => $attributeDefaults, 'embed-inline' => $attributeDefaultsInline ) ) );


$tpl->setVariable( 'custom_attributes', $customAttributes );
$tpl->setVariable( 'size_list', $sizeTypeArray );

$defaultSize = $contentIni->variable( 'ImageSettings', 'DefaultEmbedAlias' );
$tpl->setVariable( 'default_size', $defaultSize );

if ( $contentIni->hasVariable( 'ImageSettings', 'DefaultCropAlias' ) )
    $tpl->setVariable( 'default_crop_size', $contentIni->variable( 'ImageSettings', 'DefaultCropAlias' ) );
else
    $tpl->setVariable( 'default_crop_size', $defaultSize );

$tpl->setVariable( 'custom_attribute_style_map', eZOEAjaxContent::jsonEncode( $ezoeIni->variable('EditorSettings', 'CustomAttributeStyleMap' ) ) );

$tpl->setVariable( 'persistent_variable', array() );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:ezoe/tag_embed_' . $contentType . '.tpl' );
$Result['pagelayout'] = 'design:ezoe/popup_pagelayout.tpl';
$Result['persistent_variable'] = $tpl->variable( 'persistent_variable' );

return $Result;


//eZExecution::cleanExit();
//$GLOBALS['show_page_layout'] = false;

?>