<?php
//
// Created on: <10-Feb-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor extension for eZ Publish
// SOFTWARE RELEASE: 5.0
// COPYRIGHT NOTICE: Copyright (C) 1999-2014 eZ Systems AS
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
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'ObjectID/ObjectVersion' ) );
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
   echo ezpI18n::tr( 'design/standard/error/kernel', 'Your current user does not have the proper privileges to access this page.' );
   eZExecution::cleanExit();
}



$object    = eZContentObject::fetch( $objectID );
$http      = eZHTTPTool::instance();
$imageIni  = eZINI::instance( 'image.ini' );
$params    = array('dataMap' => array('image'));


if ( !$object instanceof eZContentObject || !$object->canEdit() )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ObjectId', '%value' => $objectID ) );
   eZExecution::cleanExit();
}


// is this a upload?
// forcedUpload is needed since hasPostVariable returns false if post size exceeds
// allowed size set in max_post_size in php.ini
if ( $http->hasPostVariable( 'uploadButton' ) || $forcedUpload )
{
    $version   = eZContentObjectVersion::fetchVersion( $objectVersion, $objectID );
    if ( !$version )
    {
        echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ObjectVersion', '%value' => $objectVersion ) );
        eZExecution::cleanExit();
    }
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

    try
    {
        $uploadedOk = $upload->handleUpload(
            $result,
            'fileName',
            $location,
            false,
            $objectName,
            $version->attribute( 'initial_language' )->attribute( 'locale' ),
            false
        );
        if ( !$uploadedOk )
        {
            throw new RuntimeException( "Upload failed" );
        }

        $uploadVersion = $uploadedOk['contentobject']->currentVersion();
        $newObjectID = (int)$uploadedOk['contentobject']->attribute( 'id' );

        foreach ( $uploadVersion->dataMap() as $key => $attr )
        {
            //post pattern: ContentObjectAttribute_attribute-identifier
            $base = 'ContentObjectAttribute_'. $key;
            $postVar = trim( $http->postVariable( $base, '' ) );
            if ( $postVar !== '' )
            {
                switch ( $attr->attribute( 'data_type_string' ) )
                {
                    case 'ezstring':
                        $classAttr = $attr->attribute( 'contentclass_attribute' );
                        $dataType = $classAttr->attribute( 'data_type' );
                        if ( $dataType->validateStringHTTPInput( $postVar, $attr, $classAttr ) !== eZInputValidator::STATE_ACCEPTED )
                        {
                            throw new InvalidArgumentException( $attr->validationError() );
                        }
                    case 'eztext':
                    case 'ezkeyword':
                        $attr->fromString( $postVar );
                        $attr->store();
                        break;
                    case 'ezfloat':
                        $floatValue = (float)str_replace( ',', '.', $postVar );
                        $classAttr = $attr->attribute( 'contentclass_attribute' );
                        $dataType = $classAttr->attribute( 'data_type' );
                        if ( $dataType->validateFloatHTTPInput( $floatValue, $attr, $classAttr ) !== eZInputValidator::STATE_ACCEPTED )
                        {
                            throw new InvalidArgumentException( $attr->validationError() );
                        }
                        $attr->setAttribute( 'data_float', $floatValue );
                        $attr->store();
                        break;
                    case 'ezinteger':
                        $classAttr = $attr->attribute( 'contentclass_attribute' );
                        $dataType = $classAttr->attribute( 'data_type' );
                        if ( $dataType->validateIntegerHTTPInput( $postVar, $attr, $classAttr ) !== eZInputValidator::STATE_ACCEPTED )
                        {
                            throw new InvalidArgumentException( $attr->validationError() );
                        }
                    case 'ezboolean':
                        $attr->setAttribute( 'data_int', (int)$postVar );
                        $attr->store();
                        break;
                    case 'ezimage':
                        // validation has been done by eZContentUpload
                        $content = $attr->attribute( 'content' );
                        $content->setAttribute( 'alternative_text', $postVar );
                        $content->store( $attr );
                        break;
                    case 'ezxmltext':
                        $parser = new eZOEInputParser();
                        $document = $parser->process( $postVar );
                        $xmlString = eZXMLTextType::domString( $document );
                        $attr->setAttribute( 'data_text', $xmlString );
                        $attr->store();
                        break;
                }
            }
        }

        $operationResult = eZOperationHandler::execute(
            'content', 'publish',
            array(
                'object_id' => $newObjectID,
                'version' => $uploadVersion->attribute( 'version' )
            )
        );
        $newObject = eZContentObject::fetch( $newObjectID );
        $newObjectName = $newObject->attribute( 'name' );
        $newObjectNodeID = (int)$newObject->attribute( 'main_node_id' );

        $object->addContentObjectRelation(
            $newObjectID,
            $uploadVersion->attribute( 'version' ),
            0,
            eZContentObject::RELATION_EMBED
        );
        echo '<html><head><title>HiddenUploadFrame</title><script type="text/javascript">';
        echo 'window.parent.eZOEPopupUtils.selectByEmbedId( ' . $newObjectID . ', ' . $newObjectNodeID . ', ' . json_encode( $newObjectName ) . ' );';
        echo '</script></head><body></body></html>';
    }
    catch ( InvalidArgumentException $e )
    {
        $uploadedOk['contentobject']->purge();
        echo '<html><head><title>HiddenUploadFrame</title><script type="text/javascript">';
        echo 'window.parent.document.getElementById("upload_in_progress").style.display = "none";';
        echo '</script></head><body><div style="position:absolute; top: 0px; left: 0px;background-color: white; width: 100%;">';
        echo '<p style="margin: 0; padding: 3px; color: red">' . htmlspecialchars( $e->getMessage() ) . '</p>';
        echo '</div></body></html>';
    }
    catch ( RuntimeException $e )
    {
        echo '<html><head><title>HiddenUploadFrame</title><script type="text/javascript">';
        echo 'window.parent.document.getElementById("upload_in_progress").style.display = "none";';
        echo '</script></head><body><div style="position:absolute; top: 0px; left: 0px;background-color: white; width: 100%;">';
        foreach( $result['errors'] as $err )
            echo '<p style="margin: 0; padding: 3px; color: red">' . htmlspecialchars( $err['description'] ) . '</p>';
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
        $objectAttributes = $relatedObject->contentObjectAttributes();
        foreach ( $objectAttributes as $objectAttribute )
        {
            $classAttribute = $objectAttribute->contentClassAttribute();
            $dataTypeString = $classAttribute->attribute( 'data_type_string' );
            if ( in_array ( $dataTypeString, $imageDatatypeArray ) && $objectAttribute->hasContent() )
            {
                $content = $objectAttribute->content();
                if ( $content == null )
                    continue;

                if ( $content->hasAttribute( 'small' ) )
                {
                    $srcString = $content->imageAlias( 'small' );
                    $imageAttribute = $classAttribute->attribute('identifier');
                    break;
                }
                else
                {
                    eZDebug::writeError( "Image alias does not exist: small, missing from image.ini?",
                        __METHOD__ );
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

$tpl = eZTemplate::factory();
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
