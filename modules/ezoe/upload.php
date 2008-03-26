<?php
//
// Created on: <5-Jul-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE extension for eZ Publish
// SOFTWARE RELEASE: 1.0
// COPYRIGHT NOTICE: Copyright (C) 2008 eZ systems AS
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

$objectID        = isset( $Params['ObjectID'] ) ? (int) $Params['ObjectID'] : 0;
$objectVersion   = isset( $Params['ObjectVersion'] ) ? (int) $Params['ObjectVersion'] : 0;
$forcedUpload    = isset( $Params['ForcedUpload'] ) ? (int) $Params['ForcedUpload'] : 0;

// Supported content types: image, media and file
// Media is threated as file for now
$contentType   = 'object';

if ( isset( $Params['ContentType'] ) && $Params['ContentType'] !== '' )
{
    $contentType   = $Params['ContentType'];
}

    
if ( $objectID === 0  || $objectVersion === 0 )
{
   echo ezi18n( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'ObjectID/ObjectVersion' ) );
   eZExecution::cleanExit();
}



$object    = eZContentObject::fetch( $objectID );
$http      = eZHTTPTool::instance();
$imageIni  = eZINI::instance( 'image.ini' );
$params    = array('dataMap' => array('image'));


if ( !$object )
{
   echo ezi18n( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ObjectId', '%value' => $objectID ) );
   eZExecution::cleanExit();
}


// is this a upload?
// forcedUpload is needed since hasPostVariable returns false if post size exceeds
// allowed size set in max_post_size in php.ini
if ( $http->hasPostVariable( 'uploadButton' ) || $forcedUpload )
{
    //include_once( 'kernel/classes/ezcontentupload.php' );
    $upload = new eZContentUpload();

    $location = false;
    if ( $http->hasPostVariable( 'location' ) )
    {
        $location = $http->postVariable( 'location' );
        if ( $location === 'auto' || trim( $location ) === '' ) $location = false;
    }

    $objectName = '';
    if ( $http->hasPostVariable( 'objectName' ) )
    {
        $objectName = trim( $http->postVariable( 'objectName' ) );
    }

    $uploadedOk = $upload->handleUpload( $result, 'fileName', $location, false, $objectName );


    if ( $uploadedOk )
    {
        $newObject = $result['contentobject'];
        $newObjectID = $newObject->attribute( 'id' );
        
        // edit attributes
        $newVersionObject  = $newObject->attribute( 'current' );
        $newObjectDataMap  = $newVersionObject->attribute('data_map');
        
        foreach ( array_keys( $newObjectDataMap ) as $key )
        {
            //post pattern: ContentObjectAttribute_attribute-identifier
            $base = 'ContentObjectAttribute_'. $key;
            if ( $http->hasPostVariable( $base ) && $http->postVariable( $base ) !== '' )
            {
                switch ( $newObjectDataMap[$key]->attribute( 'data_type_string' ) )
                {
                    case 'eztext':
                    case 'ezstring':
                        // TODO: Validate input ( max lenght )
                        $newObjectDataMap[$key]->setAttribute("data_text", trim( $http->postVariable( $base ) ) );
                        $newObjectDataMap[$key]->store();
                        break;
                    case 'ezfloat':
                        // TODO: Validate input ( max / min values )
                        $newObjectDataMap[$key]->setAttribute("data_float", (float) str_replace(',', '.', $http->postVariable( $base ) ) );
                        $newObjectDataMap[$key]->store();
                        break;
                    case 'ezinteger':
                        // TODO: Validate input ( max / min values )
                    case 'ezboolean':
                        $newObjectDataMap[$key]->setAttribute("data_int", (int) $http->postVariable( $base ) );
                        $newObjectDataMap[$key]->store();
                        break;
                    case 'ezxmltext':
                        $text = strip_tags( trim( $http->postVariable( $base ) ) );
                        include_once( 'extension/ezoe/ezxmltext/handlers/input/ezoeinputparser.php' );
                        $parser = new eZOEInputParser();
                        $document = $parser->process( $text );
                        $xmlString = eZXMLTextType::domString( $document );
                        $newObjectDataMap[$key]->setAttribute( 'data_text', $xmlString );
                        $newObjectDataMap[$key]->store();
                        break;
                }
            }
        }        
        
        $object->addContentObjectRelation( $newObjectID, $objectVersion, false, 0, eZContentObject::RELATION_EMBED );
        echo '<html><head><title>HiddenUploadFrame</title><script type="text/javascript">';
        echo 'window.parent.eZOEPopupUtils.selectByEmbedId(' . $newObjectID . ');';
        echo '</script></head><body></body></html>';
    }
    else
    {
        echo '<html><head><title>HiddenUploadFrame</title><script type="text/javascript">';
        echo 'window.parent.ez.$("upload_in_progress").hide();';
        echo '</script></head><body><div style="position:absolute; top: 0px; left: 0px;background-color: white; width: 100%;">';
        foreach( $result['errors'] as $err )
            echo '<p style="margin: 0; padding: 3px; color: red">' . $err['description'] . '</p>';
        echo '</div></body></html>';
    }
    eZExecution::cleanExit();
}


$siteIni       = eZINI::instance( 'site.ini' );
$contentIni    = eZINI::instance( 'content.ini' );

$groups             = $contentIni->variable( 'RelationGroupSettings', 'Groups' );
$defaultGroup       = $contentIni->variable( 'RelationGroupSettings', 'DefaultGroup' );
$imageDatatypeArray = $siteIni->variable( 'ImageDataTypeSettings', 'AvailableImageDataTypes' );

$classGroupMap         = array();
$groupClassLists       = array();
$groupedRelatedObjects = array();
$relatedObjects        = $object->relatedContentObjectArray( $objectVersion );
// $hasContentTypeGroup   = false;
// $contentTypeGroupName  = $contentType . 's';

foreach ( $groups as $groupName )
{
    $groupedRelatedObjects[$groupName] = array();
    $setting                     = strtoupper( $groupName[0] ) . substr( $groupName, 1 ) . 'ClassList';
    $groupClassLists[$groupName] = $contentIni->variable( 'RelationGroupSettings', $setting );
    foreach ( $groupClassLists[$groupName] as $classIdentifier )
    {
        $classGroupMap[$classIdentifier] = $groupName;
        // if ( $contentTypeGroupName  === $groupName ) $hasContentTypeGroup = true;
    }
}

$groupedRelatedObjects[$defaultGroup] = array();

foreach ( $relatedObjects as $relatedObjectKey => $relatedObject )
{
    $srcString        = '';
    $relID            = $relatedObject->attribute( 'id' );
    $classIdentifier  = $relatedObject->attribute( 'class_identifier' );
    $groupName        = isset( $classGroupMap[$classIdentifier] ) ? $classGroupMap[$classIdentifier] : $defaultGroup;
    
    // if ( $hasContentTypeGroup === true && $contentTypeGroupName !== $groupName ) continue;
    
    if ( $groupName === 'images' )
    {
        $contentObjectAttributes = $relatedObject->contentObjectAttributes();
        
        foreach ( $contentObjectAttributes as $contentObjectAttribute )
        {
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            if ( in_array ( $classAttribute->attribute( 'data_type_string' ), $imageDatatypeArray ) )
            {
                $content = $contentObjectAttribute->content();
                if ( $content != null )
                {
                    $srcString = $content->imageAlias( 'small' );
                    break;
                }
            }
        }
    }
    $item = array( 'object' => $relatedObjects[$relatedObjectKey],
                   'id' => 'eZObject_' . $relID,
                   'img_alias' => $srcString,
                   'selected' => false );
    $groupedRelatedObjects[$groupName][] = $item;
}

$tpl = templateInit();
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'object_id', $objectID );
$tpl->setVariable( 'object_version', $objectVersion );
$tpl->setVariable( 'related_contentobjects', $relatedObjects );
$tpl->setVariable( 'grouped_related_contentobjects', $groupedRelatedObjects );
$tpl->setVariable( 'content_type', $contentType );

$contentTypeCase = ucfirst( $contentType );
if ( $siteIni->hasVariable( 'MediaClassSettings', $contentTypeCase . 'ClassIdentifiers' ) )
    $tpl->setVariable( 'class_filter_array', implode(',', $siteIni->variable( 'MediaClassSettings', $contentTypeCase . 'ClassIdentifiers' ) ) );
else
    $tpl->setVariable( 'class_filter_array', false );

$tpl->setVariable( 'persistent_variable', array() );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:ezoe/upload.tpl' );
$Result['pagelayout'] = 'design:ezoe/popup_pagelayout.tpl';
$Result['persistent_variable'] = $tpl->variable( 'persistent_variable' );

return $Result;


eZExecution::cleanExit();
//$GLOBALS['show_page_layout'] = false;

?>