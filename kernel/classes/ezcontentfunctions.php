<?php

//
// Created on: <13-Nov-2006 15:00:00 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

class eZContentFunctions
{
    /**
     * Creates and publishes a new content object.
     *
     * This function takes all the variables passes in the $params
     * argument and creates a new content object out of it.
     *
     * Here is an example
     * <code>
     * <?php
     *
     * // admin user
     * $creatorID    = 14;
     *
     * // folder content class
     * $classIdentifier = 'folder';
     *
     * // root node
     * $parentNodeID = 2;
     *
     * // have a look at the folder content class' definition ;)
     * // basically the array is the following :
     * // key : attribute identifier ( not attribute ID !! )
     * // value : value for this attribute
     * //
     * // Please refer to each fromString/toString function to see
     * // how to organize your data
     *
     * $xmlDeclaration = '<?xml version="1.0" encoding="utf-8"?>
     *                    <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
     *                             xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
     *                             xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">';
     *
     * $attributeList = array( 'name'              => 'A newly created folder object',
     *                         'short_name'        => 'A new folder',
     *                         'short_description' => $xmlDeclaration .'<paragraph>This is the short description</paragraph></section>',
     *                         'description'       => $xmlDeclaration . '<section><section><header>Some header</header><paragraph>Some paragraph
     *                                                                   with a <link target="_blank" url_id="1">link</link></paragraph>
     *                                                                   </section></section></section>',
     *                         'show_children'     => true);
     *
     * // Creates the data import array
     * $params                     = array();
     * $params['creator_id']       = $creatorID;
     * $params['class_identifier'] = $classIdentifier;
     * $params['parent_node_id']   = $parentNodeID;
     * $params['attributes']       = $attributeList;
     *
     * $contentObject = eZContentFunctions::createAndPublishObject( $params );
     *
     * if( $contentObject )
     * {
     *     // do anything you want here
     * }
     *
     * ?>
     * </code>
     *
     * @param array $params An array with all the informations to store.
     *                      This array must contains a strict list of key/value pairs.
     *                      The possible keys are the following :
     *                      - 'parent_node_id'   : The parentNodeID for this new object.
     *                      - 'class_identifier' : The classIdentifier for this new object.
     *                                             using the classID is not possible.
     *                      - 'creator_id'       : The eZUser::contentObjectID to use as creator
     *                                             of this new eZContentObject
     *                      - 'attributes'       : The list of attributes to store, in order to now
     *                                             which values you can use for this key, you have to
     *                                             read the code of the fromString and toString functions
     *                                             of the attribute's datatype you use
     *                      - 'storage_dir'      :
     *                      - 'remote_id'        : The value for the remoteID  (optional)
     *                      - 'section_id'       : The value for the sectionID (optional)
     * @static
     * @return an eZContentObject object if success, false otherwise
     */
    static function createAndPublishObject( $params )
    {
        $parentNodeID = $params['parent_node_id'];
        $classIdentifier = $params['class_identifier'];
        $creatorID = isset( $params['creator_id'] ) ? $params['creator_id'] : false;
        $attributesData = isset( $params['attributes'] ) ? $params['attributes'] : false;
        $storageDir = isset( $params['storage_dir'] ) ? $params['storage_dir'] : '';

        $contentObject = false;

        $parentNode = eZContentObjectTreeNode::fetch( $parentNodeID, false, false );

        if ( is_array( $parentNode ) )
        {
            $contentClass = eZContentClass::fetchByIdentifier( $classIdentifier );
            if ( is_object( $contentClass ) )
            {
                $db = eZDB::instance();
                $db->begin();

                $contentObject = $contentClass->instantiate( $creatorID );

                if ( array_key_exists( 'remote_id', $params ) )
                    $contentObject->setAttribute( 'remote_id', $params['remote_id'] );

                if ( array_key_exists( 'section_id', $params ) )
                    $contentObject->setAttribute( 'section_id', $params['section_id'] );

                $contentObject->store();

                $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $contentObject->attribute( 'id' ),
                                                                   'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                                   'parent_node' => $parentNodeID,
                                                                   'is_main' => 1,
                                                                   'sort_field' => $contentClass->attribute( 'sort_field' ),
                                                                   'sort_order' => $contentClass->attribute( 'sort_order' ) ) );
                $nodeAssignment->store();

                $version = $contentObject->version( 1 );
                $version->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
                $version->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
                $version->store();

                if ( is_array( $attributesData ) && count( $attributesData ) > 0 )
                {
                    $attributes = $contentObject->attribute( 'contentobject_attributes' );

                    foreach( $attributes as $attribute )
                    {
                        $attributeIdentifier = $attribute->attribute( 'contentclass_attribute_identifier' );
                        if ( isset( $attributesData[$attributeIdentifier] ) )
                        {
                            $dataString = $attributesData[$attributeIdentifier];
                            switch ( $datatypeString = $attribute->attribute( 'data_type_string' ) )
                            {
                                case 'ezimage':
                                case 'ezbinaryfile':
                                case 'ezmedia':
                                {
                                    $dataString = $storageDir . $dataString;
                                    break;
                                }
                                default:
                            }

                            $attribute->fromString( $dataString );
                            $attribute->store();
                        }
                    }
                }

                $db->commit();

                $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObject->attribute( 'id' ),
                                                                                             'version' => 1 ) );
            }
            else
            {
                eZDebug::writeError( "Content class with identifier '$classIdentifier' doesn't exist.", 'eZContentFunctions::createAndPublishObject' );
            }
        }
        else
        {
            eZDebug::writeError( "Node with id '$parentNodeID' doesn't exist.", 'eZContentFunctions::createAndPublishObject' );
        }

        return $contentObject;
    }

    /**
     * Updates an existing content object.
     *
     * This function works like createAndPublishObject
     *
     * Here is an example
     * <code>
     *
     * <?php
     * $contentObjectID = 1;
     * $contentObject = eZContentObject::fetch( $contentObjectID );
     *
     * if( $contentObject instanceof eZContentObject )
     * {
     *     $xmlDeclaration = '<?xml version="1.0" encoding="utf-8"?>
     *                         <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
     *                                  xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
     *                                  xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">';
     *
     *     $now = $now = date( 'Y/m/d H:i:s', time() );
     *     $xmlDeclaration = '<?xml version="1.0" encoding="utf-8"?>
     *                     <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
     *                                 xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
     *                                 xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">';
     *
     *     $attributeList = array( 'name'              => 'Name ' . $now,
     *                             'short_name'        => 'Short name ' . $now,
     *                             'short_description' => $xmlDeclaration . '<paragraph>Short description '. $now . '</paragraph></section>',
     *                             'description'       => $xmlDeclaration . '<paragraph>Description '. $now . '</paragraph></section>',
     *                             'show_children'     => false);
     *
     *     $params = array();
     *     $params['attributes'] = $attributeList;
     *     // $params['remote_id'] = $now;
     *     // $params['section_id'] = 3;
     *     // $params['language']  = 'ger-DE';
     *
     *     $result = eZContentFunctions::updateAndPublishObject( $contentObject, $params );
     *
     *     if( $result )
     *         print( 'Update OK' );
     *     else
     *         print( 'Failed' );
     * }
     * ?>
     * </code>
     * @param eZContentObject an eZContentObject object
     * @param array an array with the attributes to update
     * @static
     * @return bool true if the object has been successfully updated, false otherwise
     */
    public static function updateAndPublishObject( eZContentObject $object, array $params )
    {
        if ( !array_key_exists( 'attributes', $params ) and !is_array( $params['attributes'] ) and count( $params['attributes'] ) > 0 )
        {
            eZDebug::writeError( 'No attributes specified for object' . $object->attribute( 'id' ),
                                 'eZContentFunctions::updateAndPublishObject' );
            return false;
        }

        $storageDir   = '';
        $languageCode = false;
        $mustStore    = false;

        if ( array_key_exists( 'remote_id', $params ) )
        {
            $object->setAttribute( 'remote_id', $params['remote_id'] );
            $mustStore = true;
        }

        if ( array_key_exists( 'section_id', $params ) )
        {
            $object->setAttribute( 'section_id', $params['section_id'] );
            $mustStore = true;
        }

        if ( $mustStore )
            $object->store();

        if ( array_key_exists( 'storage_dir', $params ) )
            $storageDir = $params['storage_dir'];

        if ( array_key_exists( 'language', $params ) and $params['language'] != false )
        {
            $languageCode = $params['language'];
        }
        else
        {
            $initialLanguageID = $object->attribute( 'initial_language_id' );
            $language = eZContentLanguage::fetch( $initialLanguageID );
            $languageCode = $language->attribute( 'locale' );
        }

        $db = eZDB::instance();
        $db->begin();

        $newVersion = $object->createNewVersion( false, true, $languageCode );

        if ( !$newVersion instanceof eZContentObjectVersion )
        {
            eZDebug::writeError( 'Unable to create a new version for object ' . $object->attribute( 'id' ),
                                 'eZContentFunctions::updateAndPublishObject' );

            $db->rollback();

            return false;
        }

        $newVersion->setAttribute( 'modified', time() );
        $newVersion->store();

        $attributeList = $newVersion->attribute( 'contentobject_attributes' );

        $attributesData = $params['attributes'];

        foreach( $attributeList as $attribute )
        {
            $attributeIdentifier = $attribute->attribute( 'contentclass_attribute_identifier' );
            if ( array_key_exists( $attributeIdentifier, $attributesData ) )
            {
                $dataString = $attributesData[$attributeIdentifier];
                switch ( $datatypeString = $attribute->attribute( 'data_type_string' ) )
                {
                    case 'ezimage':
                    case 'ezbinaryfile':
                    case 'ezmedia':
                    {
                        $dataString = $storageDir . $dataString;
                        break;
                    }
                    default:
                }

                $attribute->fromString( $dataString );
                $attribute->store();
            }
        }

        $db->commit();

        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newVersion->attribute( 'contentobject_id' ),
                                                                                     'version'   => $newVersion->attribute( 'version' ) ) );

        if( $operationResult['status'] == eZModuleOperationInfo::STATUS_CONTINUE )
            return true;

        return false;
    }
}

?>