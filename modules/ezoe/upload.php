<?php
//
// Created on: <10-Feb-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor extension for eZ Publish
// SOFTWARE RELEASE: 5.0
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
$contentType   = 'objects';

if ( isset( $Params['ContentType'] ) && $Params['ContentType'] !== '' )
{
    $contentType   = $Params['ContentType'];
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
        $newObjectName = $newObject->attribute( 'name' );
        $newObjectNodeID = (int) $newObject->attribute( 'main_node_id' ); // this will be empty if object is stopped by approve workflow

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
                        $newObjectDataMap[$key]->setAttribute('data_text', trim( $http->postVariable( $base ) ) );
                        $newObjectDataMap[$key]->store();
                        break;
                    case 'ezfloat':
                        // TODO: Validate input ( max / min values )
                        $newObjectDataMap[$key]->setAttribute('data_float', (float) str_replace(',', '.', $http->postVariable( $base ) ) );
                        $newObjectDataMap[$key]->store();
                        break;
                    case 'ezinteger':
                        // TODO: Validate input ( max / min values )
                    case 'ezboolean':
                        $newObjectDataMap[$key]->setAttribute('data_int', (int) $http->postVariable( $base ) );
                        $newObjectDataMap[$key]->store();
                        break;
                    case 'ezimage':
                        $content = $newObjectDataMap[$key]->attribute('content');
                        $content->setAttribute( 'alternative_text', trim( $http->postVariable( $base ) ) );
                        $content->store( $newObjectDataMap[$key] );
                        break;
                    case 'ezkeyword':
                        $newObjectDataMap[$key]->fromString( $http->postVariable( $base ) );
                        $newObjectDataMap[$key]->store();
                        break;
                    case 'ezxmltext':
                        $text = trim( $http->postVariable( $base ) );
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
        
        $object->addContentObjectRelation( $newObjectID, $objectVersion, 0, eZContentObject::RELATION_EMBED );
        echo '<html><head><title>HiddenUploadFrame</title><script type="text/javascript">';
        echo 'window.parent.eZOEPopupUtils.selectByEmbedId( ' . $newObjectID . ', ' . $newObjectNodeID . ', "' . $newObjectName . '" );';
        echo '</script></head><body></body></html>';
    }
    else
    {
        echo '<html><head><title>HiddenUploadFrame</title><script type="text/javascript">';
        echo 'window.parent.document.getElementById("upload_in_progress").style.display = "none";';
        echo '</script></head><body><div style="position:absolute; top: 0px; left: 0px;background-color: white; width: 100%;">';
        foreach( $result['errors'] as $err )
            echo '<p style="margin: 0; padding: 3px; color: red">' . $err['description'] . '</p>';
        echo '</div></body></html>';
    }
    eZDB::checkTransactionCounter();
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
    $setting                     = ucfirst( $groupName ) . 'ClassList';
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
    $imageAttribute   = false;
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
                    $imageAttribute = $classAttribute->attribute('identifier');
                    break;
                }
            }
        }
    }
    $item = array( 'object' => $relatedObjects[$relatedObjectKey],
                   'id' => 'eZObject_' . $relID,
                   'image_alias' => $srcString,
                   'image_attribute' => $imageAttribute,
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
if ( $contentIni->hasVariable( 'RelationGroupSettings', $contentTypeCase . 'ClassList' ) )
    $tpl->setVariable( 'class_filter_array', $contentIni->variable( 'RelationGroupSettings', $contentTypeCase . 'ClassList' ) );
else
    $tpl->setVariable( 'class_filter_array', array() );

$tpl->setVariable( 'content_type_name', rtrim( $contentTypeCase, 's' ) );

$tpl->setVariable( 'persistent_variable', array() );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:ezoe/upload_' . $contentType . '.tpl' );
$Result['pagelayout'] = 'design:ezoe/popup_pagelayout.tpl';
$Result['persistent_variable'] = $tpl->variable( 'persistent_variable' );

return $Result;

?>