<?php
//
// Definition of eZContentObject class
//
// Created on: <17-Apr-2002 09:15:27 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.

//

/*!
  \class eZContentObject ezcontentobject.php
  \ingroup eZKernel
  \brief Handles eZ publish content objects

  It encapsulates the date for an object and provides lots of functions
  for dealing with versions, translations and attributes.

  \sa eZContentClass
*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( 'lib/ezlocale/classes/ezlocale.php' );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentobjectversion.php" );
include_once( "kernel/classes/ezcontentobjectattribute.php" );
include_once( "kernel/classes/ezcontentclass.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( "kernel/classes/ezcontenttranslation.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

define( "EZ_CONTENT_OBJECT_STATUS_DRAFT", 0 );
define( "EZ_CONTENT_OBJECT_STATUS_PUBLISHED", 1 );
define( "EZ_CONTENT_OBJECT_STATUS_ARCHIVED", 2 );


class eZContentObject extends eZPersistentObject
{
    function eZContentObject( $row )
    {
        $this->eZPersistentObject( $row );
        $this->CurrentLanguage = eZContentObject::defaultLanguage();
    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "section_id" => array( 'name' => "SectionID",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         "owner_id" => array( 'name' => "OwnerID",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         "contentclass_id" => array( 'name' => "ClassID",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "is_published" => array( 'name' => "IsPublished",
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         "published" => array( 'name' => "Published",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "modified" => array( 'name' => "Modified",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         "current_version" => array( 'name' => "CurrentVersion",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                         "status" => array( 'name' => "Status",
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         'remote_id' => array( 'name' => "RemoteID",
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( "current" => "currentVersion",
                                                      'versions' => 'versions',
                                                      'author_array' => 'authorArray',
                                                      "class_name" => "className",
                                                      "content_class" => "contentClass",
                                                      "contentobject_attributes" => "contentObjectAttributes",
                                                      "owner" => "owner",
                                                      "related_contentobject_array" => "relatedContentObjectArray",
                                                      "related_contentobject_count" => "relatedContentObjectCount",
                                                      "can_read" => "canRead",
                                                      "can_create" => "canCreate",
                                                      "can_create_class_list" => "canCreateClassList",
                                                      "can_edit" => "canEdit",
                                                      "can_translate" => "canTranslate",
                                                      "can_remove" => "canRemove",
                                                      "data_map" => "dataMap",
                                                      "main_parent_node_id" => "mainParentNodeID",
                                                      "assigned_nodes" => "assignedNodes",
                                                      "parent_nodes" => "parentNodes",
                                                      "main_node_id" => "mainNodeID",
                                                      "main_node" => "mainNode",
                                                      "default_language" => "defaultLanguage",
                                                      "content_action_list" => "contentActionList",
                                                      "class_identifier" => "contentClassIdentifier",
                                                      "name" => "Name" ),
                      "increment_key" => "id",
                      "class_name" => "eZContentObject",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentobject" );
    }

    function &attribute( $attr )
    {
        if ( $attr == 'assigned_nodes' )
        {
            return $this->assignedNodes( true );
        }
        else if ( $attr == 'parent_nodes' )
        {
            return $this->parentNodes( true, false );
        }
        else if ( $attr == 'remote_id' )
        {
            return $this->remoteID();
        }
        else
            return eZPersistentObject::attribute( $attr );
    }

    /*!
     Store the object
    */
    function store()
    {
        // Unset the cache
        global $eZContentObjectContentObjectCache;
        unset( $eZContentObjectContentObjectCache[$this->ID] );
        global $eZContentObjectDataMapCache;
        unset( $eZContentObjectDataMapCache[$this->ID] );

        $this->storeNodeModified();

        eZPersistentObject::store();
    }

    /*!
     Update all nodes to set modified_subnode value
    */
    function storeNodeModified()
    {
        if ( is_numeric( $this->ID ) )
        {
            $nodeArray =& $this->assignedNodes();

            foreach ( array_keys( $nodeArray ) as $key )
            {
                $nodeArray[$key]->updateAndStoreModified();
            }
        }
    }

    function &name( $version = false , $lang = false )
    {
        if ( isset( $this->Name ) && !$version && !$lang )
        {
            return $this->Name;
        }
        if ( !$version )
        {
            $version = $this->attribute( 'current_version' );
        }
        $objectID = $this->attribute( 'id' );
        return $this->versionLanguageName( $objectID, $version, $lang );
    }

    function &versionLanguageName( $contentObjectID, $version, $lang = false )
    {
        if ( !$lang )
        {
            $lang = eZContentObject::defaultLanguage();
        }
        $db =& eZDb::instance();
        $query= "select name,real_translation from ezcontentobject_name where contentobject_id = '$contentObjectID' and content_version = '$version'  and content_translation = '$lang'";
        $result =& $db->arrayQuery( $query );
        if ( count( $result ) < 1 )
        {
            eZDebug::writeNotice( "There is no object name for version($version) of the content object ($contentObjectID) in language($lang)", 'eZContentObject::versionLanguageName' );
            $name = false;
            return $name;
        }
        return $result[0]['name'];
    }

    /*!
     Sets the name of the object, in memory only. Use setName() to change it.
    */
    function setCachedName( $name )
    {
        $this->Name = $name;
    }

    /*!
     Sets the name of the object in all translations.
    */
    function setName( $objectName, $versionNum = false, $translation = false )
    {
        $objectName = substr( $objectName, 0, 255 );

        $this->Name = $objectName;

        $db =& eZDB::instance();
        $objectName = $db->escapeString( $objectName );

        if ( !$versionNum )
        {
            $versionNum = $this->attribute( 'current_version' );
        }

        if ( !$translation )
        {
            $translation = $this->defaultLanguage();
        }

        $ini =& eZINI::instance();
//        $needTranslations = $ini->variableArray( "ContentSettings", "TranslationList" );
        $needTranslations =& eZContentTranslation::fetchLocaleList();
        $default = false;
        if ( $translation == $this->defaultLanguage() )
        {
            $default = true;
        }

        $objectID = $this->attribute( 'id' );
        if ( !$default || count( $needTranslations ) == 1 )
        {
            $query = "DELETE FROM ezcontentobject_name WHERE contentobject_id = $objectID and content_version = $versionNum and content_translation ='$translation' ";
            $db->query( $query );
            $query = "INSERT INTO ezcontentobject_name( contentobject_id,
                                                        name,
                                                        content_version,
                                                        content_translation,
                                                        real_translation )
                              VALUES( '$objectID',
                                      '$objectName',
                                      '$versionNum',
                                      '$translation',
                                      '$translation' )";
            $db->query( $query );
            return;
        }
        else
        {
            $existingTranslationNamesResult = $db->arrayQuery( "select * from ezcontentobject_name where contentobject_id = $objectID and content_version = $versionNum" );
            $existingTranslationList = array();
            foreach ( $existingTranslationNamesResult as $existingTranslation )
            {
                $existingTranslationList[] = $existingTranslation['content_translation'];
            }
            $realTranslation =  $translation;
            foreach ( $needTranslations as $needTranslation )
            {
                if ( $translation == $needTranslation )
                {
                    $query = "delete from ezcontentobject_name where contentobject_id = $objectID and content_version = $versionNum and content_translation ='$translation' ";
                    $db->query( $query );
                    $query = "insert into ezcontentobject_name( contentobject_id,name,content_version,content_translation,real_translation )
                              values( $objectID,
                                      '$objectName',
                                      $versionNum,
                                      '$translation',
                                      '$translation' )";
                    $db->query( $query );
                }
                else if ( ! in_array( $needTranslation, $existingTranslationList ) )
                {
                    $query = "insert into ezcontentobject_name( contentobject_id,name,content_version,content_translation,real_translation )
                              values( $objectID,
                                      '$objectName',
                                      $versionNum,
                                      '$needTranslation',
                                      '$translation' )";
                    $db->query( $query );
                }
                else
                {
                    // Update non-translated names
                    $query = "UPDATE ezcontentobject_name SET
                                      name = '$objectName'
                              WHERE contentobject_id = $objectID and content_version = $versionNum and real_translation ='$translation'";
                    $db->query( $query );
                }
            }
        }
    }

    /*!
	 \return a map with all the content object attributes where the keys are the
             attribute identifiers.
    */
    function &dataMap()
    {
        return $this->fetchDataMap();
    }

    /*!
	 \return a map with all the content object attributes where the keys are the
             attribute identifiers.
     \sa eZContentObjectTreeNode::dataMap
    */
    function &fetchDataMap( $version = false, $language = false )
    {
        // Global variable to cache datamaps
        global $eZContentObjectDataMapCache;

        if ( $version == false )
            $version = $this->attribute( 'current_version' );
        if ( $language == false )
        {
            if ( $this->CurrentLanguage != false )
            {
                $language = $this->CurrentLanguage;
            }
            else
            {
                $language = $this->defaultLanguage();
            }
        }

        if ( !isset( $eZContentObjectDataMapCache[$this->ID][$version][$language] ) )
        {
            $data =& $this->contentObjectAttributes( true, $version, $language );
            // Store the attributes for later use
            $this->ContentObjectAttributeArray[$version][$language] =& $data;

            $eZContentObjectDataMapCache[$this->ID][$version][$language] =& $data;
        }
        else
        {
            $data =& $eZContentObjectDataMapCache[$this->ID][$version][$language];
        }

        if ( !isset( $this->DataMap[$version][$language] ) )
        {
            $ret = array();
            reset( $data );
            while( ( $key = key( $data ) ) !== null )
            {
                $item =& $data[$key];

                $identifier = $item->contentClassAttributeIdentifier();

                $ret[$identifier] =& $item;

                next( $data );
            }
            $this->DataMap[$version][$language] =& $ret;
        }
        else
        {
            $ret =& $this->DataMap[$version][$language];
        }
        return $ret;
    }

    function resetDataMap()
    {
        $this->ContentObjectAttributeArray = array();
        $this->DataMap = array();
        return $this->DataMap;
    }

    /*!
     Returns the owner of the object as a content object.
    */
    function &owner()
    {
        return eZContentObject::fetch( $this->OwnerID );
    }

    /*!
     \return the content class identifier for the current content object
    */
    function &contentClassIdentifier()
    {
        $contentClass =& $this->contentClass();
        return $contentClass->attribute( 'identifier' );
    }

    /*!
     \return the content class for the current content object
    */
    function &contentClass()
    {
        if ( !is_numeric( $this->ClassID ) )
        {
            return null;
        }

        return eZContentClass::fetch( $this->ClassID );
    }

    /*!
     Get remote id of content node
    */
    function remoteID()
    {
        $remoteID = eZPersistentObject::attribute( 'remote_id' );
        if ( !$remoteID )
        {
            $this->setAttribute( 'remote_id', md5( (string)mt_rand() . (string)mktime() ) );
            $this->sync( array( 'remote_id' ) );
            $remoteID = eZPersistentObject::attribute( 'remote_id' );
        }

        return $remoteID;
    }

    function &mainParentNodeID()
    {
        $temp = eZContentObjectTreeNode::getParentNodeId( $this->attribute( 'main_node_id' ) );

        return $temp;
    }

    /*!
     Fetches contentobject by remote ID, returns null if none exist
    */
    function fetchByRemoteID( $remoteID, $asObject = true )
    {
        $db =& eZDB::instance();
        $resultArray = $db->arrayQuery( 'SELECT id FROM ezcontentobject WHERE remote_id=\'' . $remoteID . '\'' );
        if ( count( $resultArray ) != 1 )
        {
            return null;
        }

        return eZContentObject::fetch( $resultArray[0]['id'], $asObject );
    }

    /*!
     Fetches the content object with the given ID
    */
    function &fetch( $id, $asObject = true )
    {
        global $eZContentObjectContentObjectCache;

        if ( !isset( $eZContentObjectContentObjectCache[$id] ) and $asObject )
        {
            $language = eZContentObject::defaultLanguage();

            $useVersionName = true;
            if ( $useVersionName )
            {
                $versionNameTables = ', ezcontentobject_name ';
                $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

                $versionNameJoins = " and  ezcontentobject.id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject.current_version = ezcontentobject_name.content_version and
                                  ezcontentobject_name.content_translation = '$language' ";
            }

            $db =& eZDB::instance();

            $query = "SELECT ezcontentobject.* $versionNameTargets
                      FROM
                         ezcontentobject
                         $versionNameTables
                      WHERE
                         ezcontentobject.id='$id'
                         $versionNameJoins";

            $resArray =& $db->arrayQuery( $query );

            $objectArray = array();
            if ( count( $resArray ) == 1 && $resArray !== false )
            {
                $objectArray =& $resArray[0];
            }
            else
            {
                eZDebug::writeError( 'Object not found', 'eZContentObject::fetch()' );
                return null;
            }

            if ( $asObject )
            {
                $obj = new eZContentObject( $objectArray );
                $obj->CurrentLanguage = $objectArray['real_translation'];
                $eZContentObjectContentObjectCache[$id] =& $obj;
            }
            else
            {
                return $objectArray;
            }

            return $obj;
        }
        else
        {
            return $eZContentObjectContentObjectCache[$id];
        }
    }

    /*!
     Fetches the content object from the ID array
    */
    function &fetchIDArray( $idArray, $asObject = true )
    {
        $uniqueIDArray = array_unique( $idArray );

        $language = eZContentObject::defaultLanguage();

        $useVersionName = true;
        if ( $useVersionName )
        {
            $versionNameTables = ', ezcontentobject_name ';
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

            $versionNameJoins = " and  ezcontentobject.id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject.current_version = ezcontentobject_name.content_version and
                                  ezcontentobject_name.content_translation = '$language' ";
        }

        $db =& eZDB::instance();

        $objectInSQL = implode( ', ', $uniqueIDArray );

        $query = "SELECT ezcontentobject.* $versionNameTargets
                      FROM
                         ezcontentobject
                         $versionNameTables
                      WHERE
                         ezcontentobject.id IN ( $objectInSQL )
                         $versionNameJoins";

        $resRowArray =& $db->arrayQuery( $query );

        $objectRetArray = array();
        foreach ( $resRowArray as $resRow )
        {
            $objectID = $resRow['id'];
            if ( $asObject )
            {
                $obj = new eZContentObject( $resRow );
                $obj->CurrentLanguage = $resRow['real_translation'];
                $eZContentObjectContentObjectCache[$objectID] = $obj;
                $objectRetArray[$objectID] = $obj;
            }
            else
            {
                $objectRetArray[$objectID] =& $resRow;
            }
        }
        return $objectRetArray;
    }

    /*!
     \return An array with content objects.
     \param $asObject Whether to return objects or not
     \param $conditions Optional conditions to limit the fetch, set to \c null to skip it.
     \param $offset Where to start fetch from, set to \c false to skip it.
     \param $limit Maximum number of objects to fetch, set \c false to skip it.
     \sa fetchListCount
    */
    function &fetchList( $asObject = true, $conditions = null, $offset = false, $limit = false )
    {
        $limitation = null;
        if ( $offset !== false or
             $limit !== false )
            $limitation = array( 'offset' => $offset,
                                 'length' => $limit );
        return eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                    null,
                                                    $conditions, null, $limitation,
                                                    $asObject );
    }

    function &fetchFilteredList( $conditions = null, $offset = false, $limit = false, $asObject = true )
    {
        $limits = null;
        if ( $offset or $limit )
            $limits = array( 'offset' => $offset,
                             'length' => $limit );
        return eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                    null,
                                                    $conditions, null, $limits,
                                                    $asObject );
    }

    /*!
     \return The number of objects in the database. Optionally \a $conditions can be used to limit the list count.
     \sa fetchList
    */
    function fetchListCount( $conditions = null )
    {
        $rows =  eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                      array(),
                                                      $conditions, null, null,
                                                      false, false,
                                                      array( array( 'operation' => 'count( * )',
                                                                    'name' => 'count' ) ) );
        return $rows[0]['count'];
    }

    function &fetchSameClassList( $contentClassID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                    null,
                                                    array( "contentclass_id" => $contentClassID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    function &fetchSameClassListCount( $contentClassID )
    {
        return eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                      array(),
                                                      array( "contentclass_id" => $contentClassID ),
                                                      array(), null,
                                                      false,false,
                                                      array( array( 'operation' => 'count( * )',
                                                                    'name' => 'count' ) ) );
    }
    /*!
      Returns the current version of this document.
    */
    function &currentVersion( $asObject = true )
    {
        return eZContentObjectVersion::fetchVersion( $this->attribute( "current_version" ), $this->ID, $asObject );
    }

    /*!
      Returns the given object version. False is returned if the versions does not exist.
    */
    function version( $version, $asObject = true )
    {
        return eZContentObjectVersion::fetchVersion( $version, $this->ID, $asObject );
    }

    /*!
      \return an array of versions for the current object.
    */
    function versions( $asObject = true, $parameters = array() )
    {
        $conditions = array( "contentobject_id" => $this->ID );
        if ( isset( $parameters['conditions'] ) )
        {
            if ( isset( $parameters['conditions']['status'] ) )
                $conditions['status'] = $parameters['conditions']['status'];
            if ( isset( $parameters['conditions']['creator_id'] ) )
                $conditions['creator_id'] = $parameters['conditions']['creator_id'];
        }
        return eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                    null, $conditions,
                                                    null, null,
                                                    $asObject );
    }

    /*!
     \return \c true if the object has any versions remaining.
    */
    function hasRemainingVersions()
    {
        $remainingVersions = $this->versions( false );
        if ( !is_array( $remainingVersions ) or
             count( $remainingVersions ) == 0 )
        {
            return false;
        }
        return true;
    }

    function &createInitialVersion( $userID )
    {
        return eZContentObjectVersion::create( $this->attribute( "id" ), $userID, 1 );
    }

    /*!
     Creates a new version and returns it as an eZContentObjectVersion object.
     If version number is given as argument that version is used to create a copy.
    */
    function &createNewVersion( $copyFromVersion = false )
    {
        // get the next available version number
        $nextVersionNumber = $this->nextVersion();

        if ( $copyFromVersion == false )
            $version =& $this->currentVersion();
        else
            $version =& $this->version( $copyFromVersion );

       return $this->copyVersion( $this, $version, $nextVersionNumber );
    }

    /*!
     Creates a new version and returns it as an eZContentObjectVersion object.
     If version number is given as argument that version is used to create a copy.
    */
    function &copyVersion( &$object, &$version, $newVersionNumber, $contentObjectID = false, $status = EZ_VERSION_STATUS_DRAFT )
    {
        $user =& eZUser::currentUser();
        $userID =& $user->attribute( 'contentobject_id' );

        $nodeAssignmentList =& $version->attribute( 'node_assignments' );
        foreach ( array_keys( $nodeAssignmentList ) as $key )
        {
            $nodeAssignment =& $nodeAssignmentList[$key];
            $clonedAssignment =& $nodeAssignment->clone( $newVersionNumber, $contentObjectID );
            $clonedAssignment->store();
            eZDebugSetting::writeDebug( 'kernel-content-object-copy', $clonedAssignment, 'copyVersion:Copied assignment' );
        }

        $currentVersionNumber = $version->attribute( "version" );
        $contentObjectTranslations =& $version->translations();

        $clonedVersion = $version->clone( $newVersionNumber, $userID, $contentObjectID, $status );
        if ( $contentObjectID !== false )
        {
            if ( $clonedVersion->attribute( 'status' ) == EZ_VERSION_STATUS_PUBLISHED )
                $clonedVersion->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
        }
        eZDebugSetting::writeDebug( 'kernel-content-object-copy', $clonedVersion, 'copyVersion:cloned version' );

        $clonedVersion->store();

        foreach ( array_keys( $contentObjectTranslations ) as $contentObjectTranslationKey )
        {
            $contentObjectTranslation =& $contentObjectTranslations[$contentObjectTranslationKey];
            $contentObjectAttributes =& $contentObjectTranslation->objectAttributes();
            foreach ( array_keys( $contentObjectAttributes ) as $attributeKey )
            {
                $attribute =& $contentObjectAttributes[$attributeKey];
                $clonedAttribute =& $attribute->clone( $newVersionNumber, $currentVersionNumber, $contentObjectID );
                $clonedAttribute->sync();
                eZDebugSetting::writeDebug( 'kernel-content-object-copy', $clonedAttribute, 'copyVersion:cloned attribute' );
            }
        }

        $relatedObjects =& $object->relatedContentObjectArray( $currentVersionNumber );
        foreach ( array_keys( $relatedObjects ) as $key )
        {
            $relatedObject =& $relatedObjects[$key];
            $objectID = $relatedObject->attribute( 'id' );
            $object->addContentObjectRelation( $objectID, $newVersionNumber );
            eZDebugSetting::writeDebug( 'kernel-content-object-copy', 'Add object relation', 'copyVersion' );
        }

        return $version;
//        return $clonedVersion;

    }

    /*!
     Creates a new content object instance and stores it.
    */
    function &create( $name, $contentclassID, $userID, $sectionID = 1, $version = 1 )
    {
        $row = array(
            "name" => $name,
            "current_version" => $version,
            "contentclass_id" => $contentclassID,
            "permission_id" => 1,
            "parent_id" => 0,
            "main_node_id" => 0,
            "owner_id" => $userID,
            "section_id" => $sectionID,
            'remote_id' => md5( (string)mt_rand() . (string)mktime() ) );
        return new eZContentObject( $row );
    }

    /*!
     \return a new clone of the current object which has is
             ready to be stored with a new ID.
    */
    function &clone()
    {
        $contentObject = $this;
        $contentObject->setAttribute( 'id', null );
        $contentObject->setAttribute( 'published', time() );
        $contentObject->setAttribute( 'modified', time() );
        return $contentObject;
    }

    /*!
     Makes a copy of the object which is stored and then returns it.
    */
    function &copy( $allVersions = true )
    {
        eZDebugSetting::writeDebug( 'kernel-content-object-copy', 'Copy start, all versions=' . $allVersions ? 'true' : 'false', 'copy' );
        $user =& eZUser::currentUser();
        $userID =& $user->attribute( 'contentobject_id' );

        $contentObject =& $this->clone();
        $contentObject->setAttribute( 'current_version', 1 );
        $contentObject->setAttribute( 'owner_id', $userID );
        $contentObject->store();

        $contentObject->setName( $this->attribute('name') );
        eZDebugSetting::writeDebug( 'kernel-content-object-copy', $contentObject, 'contentObject' );


        $versionList = array();
        if ( $allVersions )
        {
            $versions =& $this->versions();
            for ( $i = 0; $i < count( $versions ); ++$i )
            {
                $versionID = $versions[$i]->attribute( 'version' );
                $versionList[$versionID] =& $versions[$i];
            }
        }
        else
        {
            $versionList[1] =& $this->currentVersion();
        }

        $versionKeys = array_keys( $versionList );
        foreach ( $versionKeys as $versionNumber )
        {
            $currentContentObjectVersion =& $versionList[$versionNumber];
            $contentObjectVersion =& $contentObject->copyVersion( $contentObject, $currentContentObjectVersion,
                                                                  $versionNumber, $contentObject->attribute( 'id' ),
                                                                  false );

            $contentObject->setName( $contentObjectVersion->name(), $versionNumber );
            eZDebugSetting::writeDebug( 'kernel-content-object-copy', $contentObjectVersion, 'Copied version' );
        }

        // Set version number
        $contentObject->setAttribute( 'current_version', count( $versionList ) );
        $contentObject->store();

        eZDebugSetting::writeDebug( 'kernel-content-object-copy', 'Copy done', 'copy' );
        return $contentObject;
    }

    /*!
      Reverts the object to the given version. All versions newer then the given version will
      be deleted.
    */
    function revertTo( $version )
    {
        $db =& eZDB::instance();

        // Delete stored attribute from other tables
        $contentobjectAttributes =& $this->allContentObjectAttributes( $this->ID );
        foreach (  $contentobjectAttributes as $contentobjectAttribute )
        {
            $contentobjectAttributeVersion = $contentobjectAttribute->attribute("version");
            if( $contentobjectAttributeVersion > $version )
            {
                $classAttribute =& $contentobjectAttribute->contentClassAttribute();
                $dataType =& $classAttribute->dataType();
                $dataType->deleteStoredObjectAttribute( $contentobjectAttribute, $contentobjectAttributeVersion );
            }
        }

        $db->query( "DELETE FROM ezcontentobject_attribute
					      WHERE contentobject_id='$this->ID' AND version>'$version'" );

        $db->query( "DELETE FROM ezcontentobject_version
					      WHERE contentobject_id='$this->ID' AND version>'$version'" );

        $db->query( "DELETE FROM eznode_assignment
					      WHERE contentobject_id='$this->ID' AND contentobject_version > '$version'" );

        $this->CurrentVersion = $version;
        $this->store();
    }

    /*!
     Copies the given version of the object and creates a new current version.
    */
    function copyRevertTo( $version )
    {
        $versionObject =& $this->createNewVersion( $version );

        return $versionObject->attribute( 'version' );
    }

    /*!
      If nodeID is not given, this function will remove object from database. All versions and translations of this object will be lost.
      Otherwise, it will check node assignment and only delete the object from this node if it was assigned to other nodes as well.
    */
    function purge( $id = false )
    {
        if ( is_numeric( $id ) )
        {
            $delID = $id;
            $contentobject =& eZContentObject::fetch( $delID );
        }
        else
        {
            $delID = $this->ID;
            $contentobject =& $this;
        }
        $db =& eZDB::instance();

//         $contentobjectAttributes =& $contentobject->attribute( 'contentobject_attributes' );
//         $contentobjectAttributes =& $contentobject->contentObjectAttributes( true, null, null, false, true );
        $contentobjectAttributes =& $contentobject->allContentObjectAttributes( $delID );

        foreach (  $contentobjectAttributes as $contentobjectAttribute )
        {
            $classAttribute =& $contentobjectAttribute->contentClassAttribute();
            if ( !$classAttribute )
                continue;
            $dataType =& $classAttribute->dataType();
            if ( !$dataType )
                continue;
            $dataType->deleteStoredObjectAttribute( $contentobjectAttribute );
        }

        include_once( 'kernel/classes/ezinformationcollection.php' );
        eZInformationCollection::removeContentObject( $delID );

        $db->query( "DELETE FROM ezcontentobject_attribute
		     WHERE contentobject_id='$delID'" );

        $db->query( "DELETE FROM ezcontentobject_version
		     WHERE contentobject_id='$delID'" );

        $db->query( "DELETE FROM ezcontentobject_name
		     WHERE contentobject_id='$delID'" );

        $db->query( "DELETE FROM ezcontentobject
		     WHERE id='$delID'" );

        $db->query( "DELETE FROM eznode_assignment
             WHERE contentobject_id = '$delID'" );

        $db->query( "DELETE FROM ezcontentobject_link
             WHERE from_contentobject_id = '$delID' OR to_contentobject_id = '$delID'" );
    }

    function remove( $id = false, $nodeID = null )
    {
        $delID = $this->ID;
        if ( is_numeric( $id ) )
        {
            $delID = $id;
            $contentobject =& eZContentObject::fetch( $delID );
        }
        else
        {
            $contentobject =& $this;
        }

        $nodes = $contentobject->attribute( 'assigned_nodes' );

        include_once( "kernel/classes/ezsearch.php" );
        if ( $nodeID === null  or count( $nodes ) <= 1 )
        {
            foreach ( $nodes as $node )
            {
                $node->remove();
            }
//            $db =& eZDB::instance();

            $contentobject->setAttribute( 'status', EZ_CONTENT_OBJECT_STATUS_ARCHIVED );
            eZSearch::removeObject( $contentobject );
            $contentobject->store();
            // Delete stored attribute from other tables

        }
        else if ( $nodeID !== null )
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
            if ( $node->attribute( 'main_node_id' )  == $nodeID )
            {
                foreach ( array_keys( $nodes ) as $key )
                {
                    $node =& $nodes[$key];
                    $node->remove();
                }
                $contentobject->setAttribute( 'status', EZ_CONTENT_OBJECT_STATUS_ARCHIVED );
                eZSearch::removeObject( $contentobject );
                $contentobject->store();
            }
            else
            {
                eZContentObjectTreeNode::remove( $nodeID );
            }
        }
        else
        {
            eZContentObjectTreeNode::remove( $nodeID );
        }
    }

    /*
     Fetch all attributes of all versions belongs to a contentObject.
    */
    function &allContentObjectAttributes( $contentObjectID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                    null,
                                                    array("contentobject_id" => $contentObjectID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    /*!
      Fetches the attributes for the current published version of the object.
    */
    function &contentObjectAttributes( $asObject = true, $version = false, $language = false, $contentObjectAttributeID = false, $distinctItemsOnly = false )
    {
        $db =& eZDB::instance();
        if ( $version === false )
            $version = $this->CurrentVersion;
        if ( $language === false )
        {
            if ( $this->CurrentLanguage != false )
            {
                $language = $this->CurrentLanguage;
            }
            else
            {
                $language = eZContentObject::defaultLanguage();
            }
        }

//         print( "Attributes fetch $this->ID, $version" );

        if ( !isset( $this->ContentObjectAttributes[$version][$language] ) )
        {
//             print( "uncached<br>" );
            $versionText = false;
            if ( $version !== null )
                $versionText = "AND\n                    ezcontentobject_attribute.version = '$version'";
            $languageText = false;
            if ( $language !== null )
                $languageText = "AND\n                    ezcontentobject_attribute.language_code = '$language'";
            $attributeIDText = false;
            if ( $contentObjectAttributeID )
                $attributeIDText = "AND\n                    ezcontentobject_attribute.id = '$contentObjectAttributeID'";
            $distinctText = false;
            if ( $distinctItemsOnly )
                $distinctText = "GROUP BY ezcontentobject_attribute.id";
            $query = "SELECT ezcontentobject_attribute.*, ezcontentclass_attribute.identifier as identifier FROM
                    ezcontentobject_attribute, ezcontentclass_attribute
                  WHERE
                    ezcontentclass_attribute.version = '0' AND
                    ezcontentclass_attribute.id = ezcontentobject_attribute.contentclassattribute_id AND
                    ezcontentobject_attribute.contentobject_id = '$this->ID' $versionText $languageText $attributeIDText
                  $distinctText
                  ORDER BY
                    ezcontentclass_attribute.placement ASC";

            $attributeArray =& $db->arrayQuery( $query );

            $returnAttributeArray = array();
            foreach ( $attributeArray as $attribute )
            {
                $attr = new eZContentObjectAttribute( $attribute );
                $attr->setContentClassAttributeIdentifier( $attribute['identifier'] );
                $returnAttributeArray[] = $attr;
            }

            if ( $language !== null and $version !== null )
                $this->ContentObjectAttributes[$version][$language] =& $returnAttributeArray;
        }
        else
        {
//             print( "Cached<br>" );
            $returnAttributeArray =& $this->ContentObjectAttributes[$version][$language];
        }

        return $returnAttributeArray;
    }

    /*!
     Initializes the cached copy of the content object attributes for the given version and language
    */
    function setContentObjectAttributes( &$attributes, $version, $language )
    {
        $this->ContentObjectAttributes[$version][$language] =& $attributes;
    }

    /*!
      \static
      Fetches the attributes for an array of objects. The objectList parameter
      contains an array of object id's , versions and language to fetch attributes from.
    */
    function &fillNodeListAttributes( &$nodeList, $asObject = true )
    {
        $db =& eZDB::instance();

        if ( count( $nodeList ) > 0 )
        {
            $keys = array_keys( $nodeList );
            $objectArray = array();
            $whereSQL = '';
            $count = count( $nodeList );
            $i = 0;
            foreach ( $keys as $key )
            {
                $object =& $nodeList[$key]->attribute( 'object' );

                $objectArray = array( 'id' => $object->attribute( 'id' ),
                                      'language' => eZContentObject::defaultLanguage(),
                                      'version' => $nodeList[$key]->attribute( 'contentobject_version' ) );

                $whereSQL .= "( ezcontentobject_attribute.version = '" . $nodeList[$key]->attribute( 'contentobject_version' ) . "' AND
                    ezcontentobject_attribute.contentobject_id = '" . $object->attribute( 'id' ) . "' AND
                    ezcontentobject_attribute.language_code = '" . eZContentObject::defaultLanguage() . "' ) ";

                $i++;
                if ( $i < $count )
                    $whereSQL .= ' OR ';
            }

            $query = "SELECT ezcontentobject_attribute.*, ezcontentclass_attribute.identifier as identifier FROM
                    ezcontentobject_attribute, ezcontentclass_attribute
                  WHERE
                    ezcontentclass_attribute.version = '0' AND
                    ezcontentclass_attribute.id = ezcontentobject_attribute.contentclassattribute_id AND
                    ( $whereSQL )
                  ORDER BY
                    ezcontentobject_attribute.contentobject_id, ezcontentclass_attribute.placement ASC";

            $attributeArray =& $db->arrayQuery( $query );

            $tmpAttributeObjectList = array();
            $returnAttributeArray = array();
            foreach ( $attributeArray as $attribute )
            {
                unset( $attr );
                $attr = new eZContentObjectAttribute( $attribute );
                $attr->setContentClassAttributeIdentifier( $attribute['identifier'] );

                $tmpAttributeObjectList[$attr->attribute( 'contentobject_id' )][] = $attr;
            }

            $keys = array_keys( $nodeList );
            foreach ( $keys as $key )
            {
                unset( $node );
                $node = $nodeList[$key];

                unset( $object );
                $object = $node->attribute( 'object' );
                $attributes =& $tmpAttributeObjectList[$object->attribute( 'id' )];
                $object->setContentObjectAttributes( $attributes, $node->attribute( 'contentobject_version' ), eZContentObject::defaultLanguage() );
                $node->setContentObject( $object );

                $nodeList[$key] =& $node;
            }
        }
    }

    function validateInput( &$contentObjectAttributes, $attributeDataBaseName,
                            $inputParameters = false, $parameters = array() )
    {
        $result = array( 'unvalidated-attributes' => array(),
                         'validated-attributes' => array(),
                         'status-map' => array(),
                         'require-fixup' => false,
                         'input-validated' => true );
        $parameters = array_merge( array( 'prefix-name' => false ),
                                   $parameters );
        if ( $inputParameters )
        {
            $result['unvalidated-attributes'] =& $inputParameters['unvalidated-attributes'];
            $result['validated-attributes'] =& $inputParameters['validated-attributes'];
        }
        $unvalidatedAttributes =& $result['unvalidated-attributes'];
        $validatedAttributes =& $result['validated-attributes'];
        $statusMap =& $result['status-map'];
        if ( !$inputParameters )
            $inputParameters = array( 'unvalidated-attributes' => &$unvalidatedAttributes,
                                      'validated-attributes' => &$validatedAttributes );
        $requireFixup =& $result['require-fixup'];
        $inputValidated =& $result['input-validated'];
        $http =& eZHTTPTool::instance();

        $defaultLanguage = $this->defaultLanguage();
        foreach( array_keys( $contentObjectAttributes ) as $key )
        {
            $contentObjectAttribute =& $contentObjectAttributes[$key];
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();

            // Check if this is a translation
            $currentLanguage = $contentObjectAttribute->attribute( 'language_code' );

            $isTranslation = false;
            if ( $currentLanguage != $defaultLanguage )
                $isTranslation = true;

            // If current attribute is a translation
            // Check if this attribute can be translated
            // If not do not validate, since the input will be copyed from the original
            $doNotValidate = false;
            if ( $isTranslation )
            {
                if ( !$contentClassAttribute->attribute( 'can_translate' ) )
                    $doNotValidate = true;
            }

            if ( $doNotValidate == true )
            {
                $status = EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else
            {
                $status = $contentObjectAttribute->validateInput( $http, $attributeDataBaseName,
                                                                  $inputParameters, $parameters );
            }
            $statusMap[$contentObjectAttribute->attribute( 'id' )] = array( 'value' => $status,
                                                                            'attribute' => &$contentObjectAttribute );

            if ( $status == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
                $requireFixup = true;
            else if ( $status == EZ_INPUT_VALIDATOR_STATE_INVALID )
            {
                $inputValidated = false;
                $dataType =& $contentObjectAttribute->dataType();
                $attributeName = $dataType->attribute( 'information' );
                $attributeName = $attributeName['name'];
                $description = $contentObjectAttribute->attribute( 'validation_error' );
                $hasValidationError = $contentObjectAttribute->attribute( 'has_validation_error' );
                if ( $hasValidationError )
                {
                    if ( !$description )
                        $description = false;
                    $validationNameArray = array();
                    if ( $parameters['prefix-name'] )
                        $validationNameArray = $parameters['prefix-name'];
                    $validationNameArray[] = $contentClassAttribute->attribute( 'name' );
                    $validationName = implode( '->', $validationNameArray );
                    $unvalidatedAttributes[] = array( 'id' => $contentObjectAttribute->attribute( 'id' ),
                                                      'identifier' => $contentClassAttribute->attribute( 'identifier' ),
                                                      'name' => $validationName,
                                                      'description' => $description );
                }
            }
            else if ( $status == EZ_INPUT_VALIDATOR_STATE_ACCEPTED )
            {
                $dataType =& $contentObjectAttribute->dataType();
                $attributeName = $dataType->attribute( 'information' );
                $attributeName = $attributeName['name'];
                if ( $contentObjectAttribute->attribute( 'validation_log' ) != null )
                {
                    $description = $contentObjectAttribute->attribute( 'validation_log' );
                    if ( !$description )
                        $description = false;
                    $validationName = $contentClassAttribute->attribute( 'name' );
                    if ( $parameters['prefix-name'] )
                        $validationName = $parameters['prefix-name'] . '->' . $validationName;
                    $validatedAttributes[] = array(  'id' => $contentObjectAttribute->attribute( 'id' ),
                                                     'identifier' => $contentClassAttribute->attribute( 'identifier' ),
                                                     'name' => $validationName,
                                                     'description' => $description );
                }
            }
        }
        return $result;
    }

    function fixupInput( &$contentObjectAttributes, $attributeDataBaseName )
    {
        $http =& eZHTTPTool::instance();
        foreach ( array_keys( $contentObjectAttributes ) as $key )
        {
            $contentObjectAttribute =& $contentObjectAttributes[$key];
            $contentObjectAttribute->fixupInput( $http, $attributeDataBaseName );
        }
    }

    function fetchInput( &$contentObjectAttributes, $attributeDataBaseName,
                         $customActionAttributeArray, $customActionParameters )
    {
        $result = array( 'attribute-input-map' => array() );
        $attributeInputMap =& $result['attribute-input-map'];
        $http =& eZHTTPTool::instance();

        $defaultLanguage = $this->defaultLanguage();

        $dataMap =& $this->attribute( 'data_map' );
        foreach ( array_keys( $contentObjectAttributes ) as $key )
        {
            $contentObjectAttribute =& $contentObjectAttributes[$key];
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();

            // Check if this is a translation
            $currentLanguage = $contentObjectAttribute->attribute( 'language_code' );

            $isTranslation = false;
            if ( $currentLanguage != $defaultLanguage )
                $isTranslation = true;

            // If current attribute is an un-translateable translation, input should not be fetched
            $fetchInput = true;
            if ( $isTranslation == true )
            {
                if ( !$contentClassAttribute->attribute( 'can_translate' ) )
                {
                    $fetchInput = false;
                }
            }

            // Do not handle input for non-translateable attributes.
            // Input will be copyed from the std. translation on storage
            if ( $fetchInput )
            {
                if ( $contentObjectAttribute->fetchInput( $http, $attributeDataBaseName ) )
                {
                    $dataMap[$contentObjectAttribute->attribute( 'contentclass_attribute_identifier' )] =& $contentObjectAttribute;
                    $attributeInputMap[$contentObjectAttribute->attribute('id')] = true;
                }

                // Custom Action Code
                $this->handleCustomHTTPActions( $contentObjectAttribute, $attributeDataBaseName,
                                                $customActionAttributeArray, $customActionParameters );
            }

        }
        return $result;
    }

    function handleCustomHTTPActions( &$contentObjectAttribute, $attributeDataBaseName,
                                      $customActionAttributeArray, $customActionParameters )
    {
        $http =& eZHTTPTool::instance();
        $customActionParameters['base_name'] = $attributeDataBaseName;
        if ( isset( $customActionAttributeArray[$contentObjectAttribute->attribute( 'id' )] ) )
        {
            $customActionAttributeID = $customActionAttributeArray[$contentObjectAttribute->attribute( 'id' )]['id'];
            $customAction = $customActionAttributeArray[$contentObjectAttribute->attribute( 'id' )]['value'];
            $contentObjectAttribute->customHTTPAction( $http, $customAction, $customActionParameters );
        }

        $contentObjectAttribute->handleCustomHTTPActions( $http, $attributeDataBaseName,
                                                          $customActionAttributeArray, $customActionParameters );
    }

    function handleAllCustomHTTPActions( $attributeDataBaseName,
                                         $customActionAttributeArray, $customActionParameters,
                                         $objectVersion = false )
    {
        $http =& eZHTTPTool::instance();
        $contentObjectAttributes =& $this->contentObjectAttributes( true, $objectVersion );
        $oldAttributeDataBaseName = $customActionParameters['base_name'];
        $customActionParameters['base_name'] = $attributeDataBaseName;
        foreach( array_keys( $contentObjectAttributes ) as $key )
        {
            $contentObjectAttribute =& $contentObjectAttributes[$key];
            if ( isset( $customActionAttributeArray[$contentObjectAttribute->attribute( 'id' )] ) )
            {
                $customActionAttributeID = $customActionAttributeArray[$contentObjectAttribute->attribute( 'id' )]['id'];
                $customAction = $customActionAttributeArray[$contentObjectAttribute->attribute( 'id' )]['value'];
                $contentObjectAttribute->customHTTPAction( $http, $customAction, $customActionParameters );
            }

            $contentObjectAttribute->handleCustomHTTPActions( $http, $attributeDataBaseName,
                                                              $customActionAttributeArray, $customActionParameters );
        }
        $customActionParameters['base_name'] = $oldAttributeDataBaseName;
    }

    function storeInput( &$contentObjectAttributes,
                         $attributeInputMap )
    {
        $defaultLanguage = $this->defaultLanguage();

        foreach ( array_keys( $contentObjectAttributes ) as $key )
        {
            $contentObjectAttribute =& $contentObjectAttributes[$key];
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();

            // Check if this is a translation
            $currentLanguage = $contentObjectAttribute->attribute( 'language_code' );

            $isTranslation = false;
            if ( $currentLanguage != $defaultLanguage )
                $isTranslation = true;

            // Check if current attribute is the original language
            // If so, update the non-translateable attributes
            $updateTranslations = false;
            if ( $isTranslation == false )
            {
                if ( !$contentClassAttribute->attribute( 'can_translate' ) )
                {
                    $updateTranslations = true;
                }
            }

            if ( isset( $attributeInputMap[$contentObjectAttribute->attribute('id')] ) )
            {
                $contentObjectAttribute->store();
                if ( $updateTranslations )
                {
                    $translations =& $contentObjectAttribute->fetchAttributeTranslations();
                    foreach ( $translations as $translationAttribute )
                    {
                        if ( $translationAttribute->attribute( 'language_code' ) != $currentLanguage )
                        {
                            $translationVersion = $translationAttribute->attribute( 'version' );
                            $translationID = $translationAttribute->attribute( 'id' );
                            $translationLanguage = $translationAttribute->attribute( 'language_code' );

                            // Copy attribute
                            unset( $tmp );
                            $tmp = $translationAttribute;
                            $tmp->initialize( $translationVersion, $contentObjectAttribute );

                            $tmp->setAttribute( 'id', $translationID );
                            $tmp->setAttribute( 'language_code', $translationLanguage );

                            // Set reference ID
                            $tmp->setAttribute( 'attribute_original_id', $contentObjectAttribute->attribute( 'id' ) );
                            $tmp->store();
                        }
                    }
                }
            }
        }
    }

    /*!
	  Returns the parent objects.
    */
    function &parents( )
    {
        $objectID = $this->ID;

        $parents = array();

        $parentID = $this->ParentID;

        $parent =& eZContentObject::fetch( $parentID );

        if ( $parentID > 0 )
            while ( ( $parentID > 0 ) )
            {
                $parents = array_merge( array( $parent ), $parents );
                $parentID = $parent->attribute( "parent_id" );
                $parent =& eZContentObject::fetch( $parentID );
            }
        return $parents;
    }

    /*!
	 Returns the next available version number for this object.
    */
    function nextVersion()
    {
        $db =& eZDB::instance();
        $versions =& $db->arrayQuery( "SELECT ( MAX( version ) + 1 ) AS next_id FROM ezcontentobject_version
				       WHERE contentobject_id='$this->ID'" );
        return $versions[0]["next_id"];

    }

    /*!
	 Returns number of exist versions.
    */
    function getVersionCount()
    {
        $db =& eZDB::instance();
        $versionCount =& $db->arrayQuery( "SELECT ( COUNT( version ) ) AS version_count FROM ezcontentobject_version
				       WHERE contentobject_id='$this->ID'" );
        return $versionCount[0]["version_count"];

    }
    function setCurrentLanguage( $lang )
    {
        $this->CurrentLanguage = $lang;
    }

    /*!
     Adds a link to the given content object id.
    */
    function addContentObjectRelation( $objectID, $version )
    {
        $db =& eZDB::instance();
        $db->query( "INSERT INTO ezcontentobject_link ( from_contentobject_id, from_contentobject_version, to_contentobject_id )
		     VALUES ( '$this->ID', '$version', '$objectID' )" );
    }

    /*!
     Removes a link to the given content object id.
    */
    function removeContentObjectRelation( $objectID, $version = null )
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezcontentobject_link WHERE from_contentobject_id='$this->ID' AND  from_contentobject_version='$version' AND to_contentobject_id='$objectID'" );
    }

    /*!
     \return the number of related objects
    */
    function &relatedContentObjectCount( $version = false, $objectID = false )
    {
        eZDebugSetting::writeDebug( 'kernel-content-object-related-objects', $objectID, "relatedContentObjectArray::objectID" );
        if ( $version == false )
            $version = $this->CurrentVersion;
        if( !$objectID )
            $objectID = $this->ID;
        $db =& eZDB::instance();
        $relatedObjectArray =& $db->arrayQuery( "SELECT
					       count( ezcontentobject_link.from_contentobject_id ) as count
					     FROM
					       ezcontentobject,
					       ezcontentobject_link
					     WHERE
					       ezcontentobject.id=ezcontentobject_link.to_contentobject_id AND
					       ezcontentobject.status=" . EZ_CONTENT_OBJECT_STATUS_PUBLISHED . " AND
					       ezcontentobject_link.from_contentobject_id='$objectID' AND
					       ezcontentobject_link.from_contentobject_version='$version'" );

        return $relatedObjectArray[0]['count'];
    }

    /*!
     Returns the related objects.
    */
    function &relatedContentObjectArray( $version = false, $objectID = false )
    {
        eZDebugSetting::writeDebug( 'kernel-content-object-related-objects', $objectID, "objectID" );
        if ( $version == false )
            $version = $this->CurrentVersion;
        if( ! $objectID )
        {
            $objectID = $this->ID;
        }
        $db =& eZDB::instance();

        $language = eZContentObject::defaultLanguage();

        $useVersionName = true;
        if ( $useVersionName )
        {
            $versionNameTables = ', ezcontentobject_name ';
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

            $versionNameJoins = " and  ezcontentobject.id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject.current_version = ezcontentobject_name.content_version and
                                  ezcontentobject_name.content_translation = '$language' ";
        }

        $relatedObjects =& $db->arrayQuery( "SELECT
					       ezcontentobject.* $versionNameTargets
					     FROM
					       ezcontentobject,
                           ezcontentobject_link
                           $versionNameTables
					     WHERE
					       ezcontentobject.id=ezcontentobject_link.to_contentobject_id AND
					       ezcontentobject.status=" . EZ_CONTENT_OBJECT_STATUS_PUBLISHED . " AND
					       ezcontentobject_link.from_contentobject_id='$objectID' AND
					       ezcontentobject_link.from_contentobject_version='$version'
                           $versionNameJoins" );

        $return = array();
        foreach ( $relatedObjects as $object )
        {
            $obj = new eZContentObject( $object );
            $obj->CurrentLanguage = $object['real_translation'];

            $return[] = $obj;
        }
        return $return;
    }

    /*!
     Returns objects to which this object is related
    */
    function &reverseRelatedObjectList( $version = false, $objectID = false )
    {
        if ( $version == false )
            $version = $this->CurrentVersion;
        if( ! $objectID )
        {
            $objectID = $this->ID;
        }
        $db =& eZDB::instance();
        $relatedObjects =& $db->arrayQuery( "SELECT distinct
					       ezcontentobject.*
					     FROM
					       ezcontentobject, ezcontentobject_link
					     WHERE
					       ezcontentobject.id=ezcontentobject_link.from_contentobject_id AND
					       ezcontentobject.status=" . EZ_CONTENT_OBJECT_STATUS_PUBLISHED . " AND
					       ezcontentobject_link.to_contentobject_id='$objectID' AND
					       ezcontentobject_link.from_contentobject_version=ezcontentobject.current_version" );

        $return = array();
        foreach ( $relatedObjects as $object )
        {
            $return[] = new eZContentObject( $object );
        }
        return $return;
    }

    /*!
     Returns the related objects.
     \note This function is a duplicate of reverseRelatedObjectList(), use that function instead.
    */
    function &contentObjectListRelatingThis( $version = false, $objectID = false )
    {
        return $this->reverseRelatedObjectList( $version, $objectID );
    }

    /*!
     \return the parnet nodes for the current object.
    */
    function &parentNodes( $version = false, $asObject = true )
    {
        $retNodes = array();
        if ( $version )
        {
            include_once( "kernel/classes/eznodeassignment.php" );
            if( is_numeric( $version ) )
            {
                $nodeAssignmentList =& eZNodeAssignment::fetchForObject( $this->attribute( 'id' ), $version );
            }
            else
            {
                $nodeAssignmentList =& eZNodeAssignment::fetchForObject( $this->attribute( 'id' ), $this->attribute( 'current_version' ) );
            }
            foreach ( array_keys( $nodeAssignmentList ) as $key )
            {
                $nodeAssignment =& $nodeAssignmentList[$key];
                if ( $asObject )
                {
                    $retNodes[] =& $nodeAssignment->attribute( 'parent_node_obj' );
                }
                else
                {
                    $retNodes[] =& $nodeAssignment->attribute( 'parent_node' );
                }
            }
            return $retNodes;
        }
    }

    /*!
     Returns the node assignments for the current object.
    */
    function &assignedNodes( $asObject = true)
    {
        $contentobjectID = $this->attribute( 'id' );
        $query = "SELECT ezcontentobject.*,
			 ezcontentobject_tree.*,
			 ezcontentclass.name as class_name
		  FROM   ezcontentobject_tree,
			 ezcontentobject,
			 ezcontentclass
		  WHERE  contentobject_id=$contentobjectID AND
			 ezcontentobject_tree.contentobject_id=ezcontentobject.id  AND
			 ezcontentclass.version=0 AND
			 ezcontentclass.id = ezcontentobject.contentclass_id
		  ORDER BY path_string";
        $db =& eZDB::instance();
        $nodesListArray =& $db->arrayQuery( $query );
        if ( $asObject == true )
        {
            $nodes =& eZContentObjectTreeNode::makeObjectsArray( $nodesListArray );
            return $nodes;
        }
        else
            return $nodesListArray;
    }

    /*!
     Returns the main node id for the current object.
    */
    function mainNodeID()
    {
        if ( !is_numeric( $this->MainNodeID ) )
        {
            $mainNodeID = eZContentObjectTreeNode::findMainNode( $this->attribute( 'id' ) );
            $this->MainNodeID = $mainNodeID;
            return $mainNodeID;
        }
        else
            return $this->MainNodeID;
    }

    function mainNode()
    {
        return eZContentObjectTreeNode::findMainNode( $this->attribute( 'id' ), true );
    }

    /*!
     Sets the permissions for this object.
    */
    function setPermissions( $permissionArray )
    {
        $this->Permissions =& $permissionArray;
    }

    /*!
     Returns the permission for the current object.
    */
    function permissions( )
    {
        return $this->Permissions;
    }

    /*!
     Check access for the current object

     \param function name ( edit, read, remove, etc. )
     \param original class ID ( used to check access for object creation ), default false
     \param parent class id ( used to check access for object creation ), default false
     \param return access list instead of access result (optional, default false )

     \return 1 if has access, 0 if not.
             If returnAccessList is set to true, access list is returned
    */
    function checkAccess( $functionName, $originalClassID = false, $parentClassID = false, $returnAccessList = false )
    {
        $classID = $originalClassID;
        $user =& eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );
        $accessResult = $user->hasAccessTo( 'content' , $functionName );
        $accessWord = $accessResult['accessWord'];
        if ( $classID === false )
        {
            $classID = $this->attribute( 'contentclass_id' );
        }
        if ( $accessWord == 'yes' )
        {
            return 1;
        }
        else if ( $accessWord == 'no' )
        {
            if ( $returnAccessList === false )
            {
                return 0;
            }
            else
            {
                return $accessResult['accessList'];
            }
        }
        else
        {
            $policies  =& $accessResult['policies'];
            $access = 'denied';

            foreach ( array_keys( $policies ) as $pkey  )
            {
                $limitationArray =& $policies[ $pkey ];
                if ( $access == 'allowed' )
                {
                    break;
                }

                $limitationList = array();
                foreach ( array_keys( $limitationArray ) as $key  )
                {
                    $access = 'denied';
                    switch( $key )
                    {
                        case 'Class':
                        {
                            if ( $functionName == 'create' and
                                 !$originalClassID )
                            {
                                $access = 'allowed';
                            }
                            else if ( $functionName == 'create' and
                                      in_array( $classID, $limitationArray[$key] ) )
                            {
                                $access = 'allowed';
                            }
                            else if ( in_array( $this->attribute( 'contentclass_id' ), $limitationArray[$key] )  )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                        } break;

                        case 'ParentClass':
                        {

                            if (  in_array( $this->attribute( 'contentclass_id' ), $limitationArray[$key]  ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                        } break;

                        case 'Section':
                        {
                            if ( in_array( $this->attribute( 'section_id' ), $limitationArray[$key]  ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                        } break;

                        case 'Owner':
                        {
                            if ( $this->attribute( 'owner_id' ) == $userID || $this->ID == $userID )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array ( 'Limitation' => $key );
                            }
                        } break;

                        case 'Node':
                        {
                            $mainNodeID = $this->attribute( 'main_node_id' );
                            foreach ( $limitationArray[$key] as $nodeID )
                            {
                                $node = eZContentObjectTreeNode::fetch( $nodeID );
                                $limitationNodeID = $node->attribute( 'main_node_id' );
                                if ( $mainNodeID == $limitationNodeID )
                                {
                                    $access = 'allowed';
                                    break;
                                }
                            }
                            if ( $access != 'allowed' )
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                                break;
                            }
                        } break;

                        case 'Subtree':
                        {
                            $assignedNodes = $this->attribute( 'assigned_nodes' );
                            if ( count(  $assignedNodes ) != 0 )
                            {
                                foreach (  $assignedNodes as  $assignedNode )
                                {
                                    $path = $assignedNode->attribute( 'path_string' );
                                    $subtreeArray = $limitationArray[$key];
                                    foreach ( $subtreeArray as $subtreeString )
                                    {
                                        if ( strstr( $path, $subtreeString ) )
                                        {
                                            $access = 'allowed';
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $parentNodes = $this->attribute( 'parent_nodes' );
                                if ( count( $parentNodes ) == 0 )
                                {
                                    if ( $this->attribute( 'owner_id' ) == $userID || $this->ID == $userID )
                                    {
                                        $access = 'allowed';
                                    }
                                }
                                else
                                {
                                    foreach ( $parentNodes as $parentNode )
                                    {
                                        $parentNode =& eZContentObjectTreeNode::fetch( $parentNode );
                                        $path = $parentNode->attribute( 'path_string' );

                                        $subtreeArray = $limitationArray[$key];
                                        foreach ( $subtreeArray as $subtreeString )
                                        {
                                            if ( strstr( $path, $subtreeString ) )
                                            {
                                                $access = 'allowed';
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            if ( $access != 'allowed' )
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                        } break;
                    }

                    if ( $access == 'denied' )
                    {
                        break;
                    }
                }

                $policyList[] = array( 'PolicyID' => $pkey,
                                       'LimitationList' => $limitationList );
            }
            if ( $access == 'denied' )
            {
                if ( $returnAccessList === false )
                {
                    return 0;
                }
                else
                {
                    return array( 'FunctionRequired' => array ( 'Module' => 'content',
                                                                'Function' => $functionName,
                                                                'ClassID' => $classID,
                                                                'MainNodeID' => $this->attribute( 'main_node_id' ) ),
                                  'PolicyList' => $policyList );
                }
            }
            else
            {
                return 1;
            }
        }
    }

    function classListFromPolicy( $policy )
    {
        $canCreateClassIDListPart = array();
        $hasClassIDLimitation = false;
        if ( isset( $policy['Class'] ) )
        {
            $canCreateClassIDListPart =& $policy['Class'];
            $hasClassIDLimitation = true;
        }

        if ( isset( $policy['Section'] ) )
        {
            if ( !in_array( $this->attribute( 'section_id' ),  $policy['Section']  ) )
            {
                return array();
            }
        }

        if ( isset( $policy['ParentClass'] ) )
        {
            if ( !in_array( $this->attribute( 'contentclass_id' ), $policy['ParentClass']  ) )
            {
                return array();
            }
        }

        if ( isset( $policy['Assigned'] ) )
        {
            if ( $this->attribute( 'owner_id' ) != $user->attribute( 'contentobject_id' )  )
            {
                return array();
            }
        }

        if ( isset( $policy['Node'] ) )
        {
            $allowed = false;
            foreach( $policy['Node'] as $nodeID )
            {
                $mainNodeID = $this->attribute( 'main_node_id' );
                $node = eZContentObjectTreeNode::fetch( $nodeID );
                if ( $mainNodeID == $node->attribute( 'main_node_id' ) )
                {
                    $allowed = true;
                    break;
                }
            }
            if ( !$allowed )
            {
                return array();
            }
        }

        if( isset( $policy['Subtree'] ) )
        {
            $allowed = false;
            $assignedNodes = $this->attribute( 'assigned_nodes' );
            foreach ( $assignedNodes as  $assignedNode )
            {
                $path = $assignedNode->attribute( 'path_string' );
                foreach ( $policy['Subtree'] as $subtreeString )
                {
                    if (  strstr( $path, $subtreeString ) )
                    {
                        $allowed = true;
                        break;
                    }
                }
            }
            if( !$allowed )
            {
                return array();
            }
        }

        if ( $hasClassIDLimitation )
        {
            return $canCreateClassIDListPart;
        }
        return '*';
    }

    function &canCreateClassList()
    {
        $user =& eZUser::currentUser();
        $accessResult = $user->hasAccessTo( 'content' , 'create' );
        $accessWord = $accessResult['accessWord'];

        if ( $accessWord == 'yes' )
        {
            return eZContentClass::fetchList( 0, false,false, null, array( 'id', 'name' ) );
        }
        elseif ( $accessWord == 'no' )
        {
            return array();
        }
        else
        {
            $policies  =& $accessResult['policies'];
            $classIDArray = array();
            foreach ( $policies as $policy )
            {
                $classIDArrayPart = $this->classListFromPolicy( $policy );
                if ( $classIDArrayPart == '*' )
                {
                    $classList =& eZContentClass::fetchList( 0, false,false, null, array( 'id', 'name' ) );
                    return $classList;
                }else
                {
                    $classIDArray = array_merge( $classIDArray, array_diff( $classIDArrayPart, $classIDArray ) );
                    unset( $classIDArrayPart );
                }
            }
        }
        if( count( $classIDArray ) == 0  )
        {
            return array();
        }
        $classList = array();
        // needs to be optimized
        $db = eZDb::instance();
        $classString = implode( ',', $classIDArray );
        $classList =& $db->arrayQuery( "select id, name from ezcontentclass where id in ( $classString  )  and version = 0" );
//        eZDebugSetting::writeDebug( 'kernel-content-object-limitation', $classList, 'can create some classes' );
        return $classList;
    }

    /*!
     Get accesslist for specified function

     \param function

     \return AccessList
    */
    function accessList( $function )
    {
        switch( $function )
        {
            case 'read':
            {
                return $this->checkAccess( 'read', false, false, true );
            } break;

            case 'edit':
            {
                return $this->checkAccess( 'edit', false, false, true );
            } break;
        }
        return 0;
    }

    /*!
     Returns true if the current
    */
    function canRead( )
    {
        if ( !isset( $this->Permissions["can_read"] ) )
        {
            $this->Permissions["can_read"] = $this->checkAccess( 'read' );
        }
        $p = ( $this->Permissions["can_read"] == 1 );
        return $p;
    }

    function canCreate( )
    {
        if ( !isset( $this->Permissions["can_create"] ) )
        {
            $this->Permissions["can_create"] = $this->checkAccess( 'create' );
        }
        $p = ( $this->Permissions["can_create"] == 1 );
        return $p;
    }


    function canEdit( )
    {
        if ( !isset( $this->Permissions["can_edit"] ) )
        {
            $this->Permissions["can_edit"] = $this->checkAccess( 'edit' );
            if ( $this->Permissions["can_edit"] != 1 )
            {
                 $user =& eZUser::currentUser();
                 if ( $user->id() == $this->attribute( 'id' ) )
                 {
                     $access = $user->hasAccessTo( 'user', 'selfedit' );
                     if ( $access['accessWord'] == 'yes' )
                     {
                         $this->Permissions["can_edit"] = 1;
                     }
                 }
            }
        }
        $p = ( $this->Permissions["can_edit"] == 1 );
        return $p;
    }

    function canTranslate( )
    {
        if ( !isset( $this->Permissions["can_translate"] ) )
        {
            $this->Permissions["can_translate"] = $this->checkAccess( 'translate' );
            if ( $this->Permissions["can_translate"] != 1 )
            {
                 $user =& eZUser::currentUser();
                 if ( $user->id() == $this->attribute( 'id' ) )
                 {
                     $access = $user->hasAccessTo( 'user', 'selfedit' );
                     if ( $access['accessWord'] == 'yes' )
                     {
                         $this->Permissions["can_translate"] = 1;
                     }
                 }
            }
        }
        $p = ( $this->Permissions["can_translate"] == 1 );
        return $p;
    }

    function canRemove( )
    {

        if ( !isset( $this->Permissions["can_remove"] ) )
        {
            $this->Permissions["can_remove"] = $this->checkAccess( 'remove' );
        }
        $p = ( $this->Permissions["can_remove"] == 1 );
        return $p;
    }

    function &className()
    {
        return $this->ClassName;
    }

    /*!
     Returns an array of the content actions which can be performed on
     the current object.
    */
    function &contentActionList()
    {
        $version = $this->attribute( 'current_version' );
        $language = $this->defaultLanguage();
        if ( !isset( $this->ContentObjectAttributeArray[$version][$language] ) )
        {
            $attributeList =& $this->contentObjectAttributes();
            $this->ContentObjectAttributeArray[$version][$language] =& $attributeList;
        }
        else
            $attributeList =& $this->ContentObjectAttributeArray[$version][$language];

        // Fetch content actions if not already fetched
        if ( $this->ContentActionList === false )
        {

            $contentActionList = array();
            foreach ( $attributeList as $attribute )
            {
                $contentActions =& $attribute->contentActionList();
                if ( count( $contentActions ) > 0 )
                {
                    $contentActionList =& $attribute->contentActionList();


                    foreach ( $contentActionList as $action )
                    {
                        if ( !$this->hasContentAction( $action['action'] ) )
                        {
                            $this->ContentActionList[] = $action;
                        }
                    }
                }
            }
        }
        return $this->ContentActionList;
    }

    /*!
     \return true if the content action is in the content action list
    */
    function hasContentAction( $name )
    {
        $return = false;
        if ( is_array ( $this->ContentActionList ) )
        {
            foreach ( $this->ContentActionList as $action )
            {
                if ( $action['action'] == $name )
                {
                    $return = true;
                }
            }
        }
        return $return;
    }

    function &defaultLanguage()
    {
        if ( ! isset( $GLOBALS['eZContentObjectDefaultLanguage'] ) )
        {
            $ini =& eZINI::instance();
            $GLOBALS['eZContentObjectDefaultLanguage'] =& $ini->variable( 'RegionalSettings', 'ContentObjectLocale' );
        }

        return $GLOBALS['eZContentObjectDefaultLanguage'];
    }

    /*!
     \static
     Set default language. Checks if default language is valid.

     \param default language.
    */
    function setDefaultLanguage( $lang )
    {
        include_once( 'kernel/classes/ezcontenttranslation.php' );
        if ( in_array( $lang, eZContentTranslation::fetchLocaleList() ) )
        {
            $GLOBALS['eZContentObjectDefaultLanguage'] = $lang;
        }
    }

    /*!

    */
    function setClassName( $name )
    {
        $this->ClassName = $name;
    }

    /*!
     \returns an array with locale strings, these strings represents the languages which content objects are allowed to be translated into.
     \note the setting ContentSettings/TranslationList in site.ini determines the array.
     \sa translationList
    */
    function translationStringList()
    {
        $translationList =& $GLOBALS['eZContentTranslationStringList'];
        if ( isset( $translationList ) )
            return $translationList;
        $translationList = eZContentTranslation::fetchLocaleList();
/*
        $ini =& eZINI::instance();
        $translationList = $ini->variableArray( 'ContentSettings', 'TranslationList' );
*/
        return $translationList;
    }

    /*!
     \returns an array with locale objects, these objects represents the languages the content objects are allowed to be translated into.
     \note the setting ContentSettings/TranslationList in site.ini determines the array.
     \sa translationStringList
    */
    function &translationList()
    {
        $translationList =& $GLOBALS['eZContentTranslationList'];
        if ( isset( $translationList ) )
            return $translationList;
        $translationList = array();
        $translationStringList = eZContentObject::translationStringList();
        foreach ( $translationStringList as $translationString )
        {
            $translationList[] =& eZLocale::instance( $translationString );
        }
        return $translationList;
    }

    /*!
     Returns the attributes for the content object version \a $version and content object \a $contentObjectID.
     \a $language defines the language to fetch.
     \static
     \sa attributes
    */
    function &fetchClassAttributes( $version = 0, $asObject = true )
    {
        return eZContentClassAttribute::fetchListByClassID( $this->attribute( 'contentclass_id' ), $version, $asObject );
    }

    /*!
     \static
     Unserialize xml structure. Create object from xml input.

     \param package
     \param XML DOM Node
     \param parent node object.
     \param Options
     \param owner ID, override owner ID, null to use XML owner id (optional)

     \returns created object, false if could not create object/xml invalid
    */
    function &unserialize( &$package, &$domNode, $options, $ownerID = false )
    {
        if ( $domNode->name() != 'object' )
        {
            return false;
        }

        $sectionID =& $domNode->attributeValue( 'section_id' );
        if ( $ownerID === false )
        {
            $ownerID =& $domNode->attributeValue( 'owner_id' );
        }
        $remoteID =& $domNode->attributeValue( 'remote_id' );
        $name =& $domNode->attributeValue( 'name' );
        $classRemoteID =& $domNode->attributeValue( 'class_remote_id' );
        $classIdentifier =& $domNode->attributeValue( 'class_identifier' );

        $contentClass =& eZContentClass::fetchByRemoteID( $classRemoteID );
        if ( !$contentClass )
        {
            $contentClass =& eZContentClass::fetchByIdentifier( $classIdentifier );
        }

        if ( !$contentClass )
        {
            eZDebug::writeError( 'Could not fetch class ' . $classIdentifier . ', remote_id: ' . $classRemoteID, 'eZContentObject::unserialize()' );
            return false;
        }

        $contentObject =& eZContentObject::fetchByRemoteID( $remoteID );
        if ( !$contentObject )
        {
            $contentObject =& $contentClass->instantiate( $ownerID, $sectionID );
        }

        $versionListNode =& $domNode->elementByName( 'version-list' );
        $contentObject->store();
        $activeVersion = 1;
        $firstVersion = true;

        $versionList = array();
        foreach( $versionListNode->elementsByName( 'version' ) as $versionDOMNode )
        {
            unset( $nodeList );
            $nodeList = array();
            $contentObjectVersion = eZContentObjectVersion::unserialize( $versionDOMNode,
                                                                         $contentObject,
                                                                         $ownerID,
                                                                         $sectionID,
                                                                         $versionListNode->attributeValue( 'active_version' ),
                                                                         $firstVersion,
                                                                         $nodeList,
                                                                         $options,
                                                                         $package );
            $versionList[$versionDOMNode->attributeValue( 'version' )] = array( 'node_list' => $nodeList );

            $firstVersion = false;
            if ( $versionDOMNode->attributeValue( 'version' ) == $versionListNode->attributeValue( 'active_version' ) )
            {
                $activeVersion = $contentObjectVersion->attribute( 'version' );
            }
        }

        if ( !isset( $options['restore_dates'] ) or $options['restore_dates'] )
        {
            include_once( 'lib/ezlocale/classes/ezdateutils.php' );
            $modified = eZDateUtils::textToDate( $domNode->attributeValue( 'modified' ) );
            $contentObject->setAttribute( 'modified', $modified );
        }
        $contentObject->setAttribute( 'remote_id', $remoteID );
        $contentObject->setAttribute( 'current_version', $activeVersion );
        $contentObject->setAttribute( 'contentclass_id', $contentClass->attribute( 'id' ) );
        $contentObject->setAttribute( 'name', $name );
        $contentObject->store();

        include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
        eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObject->attribute( 'id' ),
                                                                  'version' => $activeVersion ) );

        foreach ( $versionList[$activeVersion]['node_list'] as $nodeInfo )
        {
            unset( $parentNode );
            $parentNode =& eZContentObjectTreeNode::fetchNode( $contentObject->attribute( 'id' ),
                                                               $nodeInfo['parent_node'] );
            if ( is_object( $parentNode ) )
            {
                $parentNode->setAttribute( 'priority', $nodeInfo['priority'] );
                $parentNode->store( array( 'priority' ) );
            }
        }

        if ( !isset( $options['restore_dates'] ) or $options['restore_dates'] )
        {
            include_once( 'lib/ezlocale/classes/ezdateutils.php' );
            $published = eZDateUtils::textToDate( $domNode->attributeValue( 'published' ) );
            $contentObject =& eZContentObject::fetch( $contentObject->attribute( 'id' ) );
            $contentObject->setAttribute( 'published', $published );
            $contentObject->store( array( 'published' ) );
        }
        return $contentObject;
    }

    /*!
     \return a DOM structure of the content object and it's attributes.

     \param package
     \param Content object version, true for current version, false for all, else array containing specific versions.
     \param package options ( optianal )
     \param array of allowed nodes ( optional )
     \param array of top nodes in current package export (optional )
    */
    function &serialize( &$package, $specificVersion = false, $options = false, $contentNodeIDArray = false, $topNodeIDArray = false )
    {
        if ( $options['node_assignment'] == 'main' )
        {
            if ( !isset( $contentNodeIDArray[$this->attribute( 'main_node_id' )] ) )
            {
                return false;
            }
        }

        include_once( 'lib/ezlocale/classes/ezdateutils.php' );
        include_once( 'lib/ezxml/classes/ezdomdocument.php' );
        include_once( 'lib/ezxml/classes/ezdomnode.php' );
        $objectNode = new eZDOMNode();

        $objectNode->setName( 'object' );
        $objectNode->appendAttribute( eZDOMDocument::createAttributeNode( 'ezremote', 'http://ez.no/ezobject', 'xmlns' ) );
        $objectNode->appendAttribute( eZDOMDocument::createAttributeNode( 'id', $this->ID, 'ezremote' ) );
        $objectNode->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $this->Name ) );
        $objectNode->appendAttribute( eZDOMDocument::createAttributeNode( 'section_id', $this->SectionID, 'ezremote' ) );
        $objectNode->appendAttribute( eZDOMDocument::createAttributeNode( 'owner_id', $this->OwnerID, 'ezremote' ) );
        $objectNode->appendAttribute( eZDOMDocument::createAttributeNode( 'class_id', $this->ClassID, 'ezremote' ) );
        $objectNode->appendAttribute( eZDOMDocument::createAttributeNode( 'published', eZDateUtils::rfc1123Date( $this->attribute( 'published' ) ), 'ezremote' ) );
        $objectNode->appendAttribute( eZDOMDocument::createAttributeNode( 'modified', eZDateUtils::rfc1123Date( $this->attribute( 'modified' ) ), 'ezremote' ) );
        if ( !$this->attribute( 'remote_id' ) )
        {
            $this->setAttribute( 'remote_id', md5( (string)mt_rand() ) . (string)mktime() );
            $this->store();
        }
        $objectNode->appendAttribute( eZDOMDocument::createAttributeNode( 'remote_id', $this->attribute( 'remote_id' ) ) );
        $contentClass =& $this->attribute( 'content_class' );
        $objectNode->appendAttribute( eZDOMDocument::createAttributeNode( 'class_remote_id', $contentClass->attribute( 'remote_id' ) ) );
        $objectNode->appendAttribute( eZDOMDocument::createAttributeNode( 'class_identifier', $contentClass->attribute( 'identifier' ), 'ezremote' ) );

        $versions = array();
        if ( $specificVersion === false )
        {
            $versions =& $this->versions();
        }
        else if ( $specificVersion === true )
        {
            $versions[] = $this->currentVersion();
        }
        else
        {
            $versions[] = $this->version( $specificVersion );
        }

        $this->fetchClassAttributes();

        $versionsNode = new eZDOMNode();
        $versionsNode->setName( 'version-list' );
        $versionsNode->appendAttribute( eZDOMDocument::createAttributeNode( 'active_version', $this->CurrentVersion ) );
        $versionsNode->appendAttribute( eZDOMDocument::createAttributeNamespaceDefNode( "ezobject", "http://ez.no/object/" ) );
        foreach ( array_keys( $versions ) as $versionKey )
        {
            $version =& $versions[$versionKey];
            $versionNode =& $version->serialize( $package, $options, $contentNodeIDArray, $topNodeIDArray );
            $versionsNode->appendChild( $versionNode );
        }
        $objectNode->appendChild( $versionsNode );
        return $objectNode;
    }

    /*!
     \return a structure with information required for caching.
    */
    function cacheInfo( $Params )
    {
        $contentCacheInfo =& $GLOBALS['eZContentCacheInfo'];
        if ( !isset( $contentCacheInfo ) )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            include_once( 'kernel/classes/ezuserdiscountrule.php' );
            $user =& eZUser::currentUser();
            $languageCode = $Params['Language'];
            $language = $languageCode;
            if ( $language == '' )
                $language = eZContentObject::defaultLanguage();
            $roleList = $user->roleIDList();
            $discountList =& eZUserDiscountRule::fetchIDListByUserID( $user->attribute( 'contentobject_id' ) );
            $contentCacheInfo = array( 'language' => $language,
                                       'role_list' => $roleList,
                                       'discount_list' => $discountList );
        }
        return $contentCacheInfo;
    }

    /*!
     Sets all content cache files to be expired.
    */
    function expireAllCache()
    {
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-cache', mktime() );
        $handler->store();
    }

    /*!
     Sets all complex viewmode content cache files to be expired.
    */
    function expireComplexViewModeCache()
    {
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-complex-viewmode-cache', mktime() );
        $handler->store();
    }

    /*!
     \return if the content cache timestamp \a $timestamp is expired.
    */
    function isCacheExpired( $timestamp )
    {
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        if ( !$handler->hasTimestamp( 'content-cache' ) )
            return false;
        $expiryTime = $handler->timestamp( 'content-cache' );
        if ( $expiryTime > $timestamp )
            return true;
        return false;
    }

    /*!
     \return true if the viewmode is a complex viewmode.
    */
    function isComplexViewMode( $viewMode )
    {
        $ini =& eZINI::instance();
        $viewModes = $ini->variableArray( 'ContentSettings', 'ComplexDisplayViewModes' );
        return in_array( $viewMode, $viewModes );
    }

    /*!
     \return true if the viewmode is a complex viewmode and the viewmode timestamp is expired.
    */
    function isComplexViewModeCacheExpired( $viewMode, $timestamp )
    {
        if ( !eZContentObject::isComplexViewMode( $viewMode ) )
            return false;
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        if ( !$handler->hasTimestamp( 'content-complex-viewmode-cache' ) )
            return false;
        $expiryTime = $handler->timestamp( 'content-complex-viewmode-cache' );
        if ( $expiryTime > $timestamp )
            return true;
        return false;
    }

    /*!
     Returns a list of all the authors for this object. The returned value is an
     array of eZ user objects.
    */
    function &authorArray()
    {
        $db =& eZDB::instance();

        $userArray = $db->arrayQuery( "SELECT DISTINCT ezuser.contentobject_id, ezuser.login, ezuser.email, ezuser.password_hash, ezuser.password_hash_type
                                       FROM ezcontentobject_version, ezuser where ezcontentobject_version.contentobject_id='$this->ID'
                                       AND ezcontentobject_version.creator_id=ezuser.contentobject_id" );

        $return = array();

        foreach ( $userArray as $userRow )
        {
            $return[] = new eZUser( $userRow );
        }
        return $return;
    }

    /*!
     \return the number of objects of the given class is created by the given user.
    */
    function fetchObjectCountByUserID( $classID, $userID )
    {
        $count = 0;
        if ( is_numeric( $classID ) and is_numeric( $userID ) )
        {
            $db =& eZDB::instance();
            $countArray = $db->arrayQuery( "SELECT count(*) AS count FROM ezcontentobject WHERE contentclass_id=$classID AND owner_id=$userID" );
            $count = $countArray[0]['count'];
        }
        return $count;
    }

    /*!
     \static
     Will remove all version that match the status set in \a $versionStatus.
     \param $versionStatus can either be a single value or an array with values,
                           if \c false the function will remove all status except published.
    */
    function removeVersions( $versionStatus = false )
    {
        if ( $versionStatus === false )
            $versionStatus = array( EZ_VERSION_STATUS_DRAFT,
                                    EZ_VERSION_STATUS_PENDING,
                                    EZ_VERSION_STATUS_ARCHIVED,
                                    EZ_VERSION_STATUS_REJECTED );
        $max = 20;
        $offset = 0;
        $hasVersions = true;
        while ( $hasVersions )
        {
            $versions =& eZContentObjectVersion::fetchFiltered( array( 'status' => array( $versionStatus ) ),
                                                                $offset, $max );
            $hasVersions = count( $versions ) > 0;
            foreach ( array_keys( $versions ) as $versionKey )
            {
                $version =& $versions[$versionKey];
                $version->remove();
            }
            $offset += count( $versions );
        }
    }

    var $ID;
    var $Name;

    /// Stores the current language
    var $CurrentLanguage;

    /// Stores the current class name
    var $ClassName;

    /// Contains the datamap for content object attributes
    var $DataMap = array();

    /// Contains an array of the content object actions for the current object
    var $ContentActionList = false;

    /// Contains a cached version of the content object attributes for the given version and language
    var $ContentObjectAttributes = array();

    /// Contains the main node id for this object
    var $MainNodeID = false;
}

?>
