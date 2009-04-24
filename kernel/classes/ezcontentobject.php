<?php
//
// Definition of eZContentObject class
//
// Created on: <17-Apr-2002 09:15:27 bf>
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

/*!
  \class eZContentObject ezcontentobject.php
  \ingroup eZKernel
  \brief Handles eZ Publish content objects

  It encapsulates the data for an object and provides lots of functions
  for dealing with versions, translations and attributes.

  \sa eZContentClass
*/

class eZContentObject extends eZPersistentObject
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_ARCHIVED = 2;

    const PACKAGE_ERROR_NO_CLASS = 1;
    const PACKAGE_ERROR_EXISTS = 2;
    const PACKAGE_ERROR_NODE_EXISTS = 3;
    const PACKAGE_ERROR_MODIFIED = 101;
    const PACKAGE_ERROR_HAS_CHILDREN = 102;

    const PACKAGE_REPLACE = 1;
    const PACKAGE_SKIP = 2;
    const PACKAGE_NEW = 3;
    const PACKAGE_UPDATE = 6;

    const PACKAGE_DELETE = 4;
    const PACKAGE_KEEP = 5;

    const RELATION_COMMON = 1;
    const RELATION_EMBED = 2;
    const RELATION_LINK = 4;
    const RELATION_ATTRIBUTE = 8;

    function eZContentObject( $row )
    {
        $this->eZPersistentObject( $row );
        $this->ClassIdentifier = false;
        if ( isset( $row['contentclass_identifier'] ) )
            $this->ClassIdentifier = $row['contentclass_identifier'];
        $this->ClassName = false;
        if ( isset( $row['contentclass_name'] ) )
            $this->ClassName = $row['contentclass_name'];
        if ( isset( $row['serialized_name_list'] ) )
            $this->ClassName = eZContentClass::nameFromSerializedString( $row['serialized_name_list'] );

        $this->CurrentLanguage = false;
        if ( isset( $row['content_translation'] ) )
        {
            $this->CurrentLanguage = $row['content_translation'];
        }
        else if ( isset( $row['real_translation'] ) )
        {
            $this->CurrentLanguage = $row['real_translation'];
        }
        else if ( isset( $row['language_mask'] ) )
        {
            $topPriorityLanguage = eZContentLanguage::topPriorityLanguageByMask( $row['language_mask'] );
            if ( $topPriorityLanguage )
            {
               $this->CurrentLanguage = $topPriorityLanguage->attribute( 'locale' );
            }
        }
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "section_id" => array( 'name' => "SectionID",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true,
                                                                'foreign_class' => 'eZSection',
                                                                'foreign_attribute' => 'id',
                                                                'multiplicity' => '1..*' ),
                                         "owner_id" => array( 'name' => "OwnerID",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true,
                                                              'foreign_class' => 'eZUser',
                                                              'foreign_attribute' => 'contentobject_id',
                                                              'multiplicity' => '1..*'),
                                         "contentclass_id" => array( 'name' => "ClassID",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true,
                                                                     'foreign_class' => 'eZContentClass',
                                                                     'foreign_attribute' => 'id',
                                                                     'multiplicity' => '1..*' ),
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
                                                               'required' => true ),
                                         'language_mask' => array( 'name' => 'LanguageMask',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true ),
                                         'initial_language_id' => array( 'name' => 'InitialLanguageID',
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true,
                                                                         'foreign_class' => 'eZContentLanguage',
                                                                         'foreign_attribute' => 'id',
                                                                         'multiplicity' => '1..*' ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( "current" => "currentVersion",
                                                      'versions' => 'versions',
                                                      'author_array' => 'authorArray',
                                                      "class_name" => "className",
                                                      "content_class" => "contentClass",
                                                      "contentobject_attributes" => "contentObjectAttributes",
                                                      "owner" => "owner",
                                                      "related_contentobject_array" => "relatedContentObjectList",
                                                      "related_contentobject_count" => "relatedContentObjectCount",
                                                      'reverse_related_contentobject_array' => 'reverseRelatedObjectList',
                                                      'reverse_related_contentobject_count' => 'reverseRelatedObjectCount',
                                                      "linked_contentobject_array" => "linkedContentObjectList",
                                                      "linked_contentobject_count" => "linkedContentObjectCount",
                                                      'reverse_linked_contentobject_array' => 'reverseLinkedObjectList',
                                                      'reverse_linked_contentobject_count' => 'reverseLinkedObjectCount',
                                                      "embedded_contentobject_array" => "embeddedContentObjectList",
                                                      "embedded_contentobject_count" => "embeddedContentObjectCount",
                                                      'reverse_embedded_contentobject_array' => 'reverseEmbeddedObjectList',
                                                      'reverse_embedded_contentobject_count' => 'reverseEmbeddedObjectCount',
                                                      "can_read" => "canRead",
                                                      "can_pdf" => "canPdf",
                                                      "can_diff" => "canDiff",
                                                      "can_create" => "canCreate",
                                                      "can_create_class_list" => "canCreateClassList",
                                                      "can_edit" => "canEdit",
                                                      "can_translate" => "canTranslate",
                                                      "can_remove" => "canRemove",
                                                      "can_move" => "canMoveFrom",
                                                      "can_move_from" => "canMoveFrom",
                                                      'can_view_embed' => 'canViewEmbed',
                                                      "data_map" => "dataMap",
                                                      "main_parent_node_id" => "mainParentNodeID",
                                                      "assigned_nodes" => "assignedNodes",
                                                      "parent_nodes" => "parentNodeIDArray",
                                                      "main_node_id" => "mainNodeID",
                                                      "main_node" => "mainNode",
                                                      "default_language" => "defaultLanguage",
                                                      "content_action_list" => "contentActionList",
                                                      "class_identifier" => "contentClassIdentifier",
                                                      'class_group_id_list' => 'contentClassGroupIDList',
                                                      'name' => 'name',
                                                      'match_ingroup_id_list' => 'matchIngroupIDList',
                                                      'remote_id' => 'remoteID',
                                                      'current_language' => 'currentLanguage',
                                                      'current_language_object' => 'currentLanguageObject',
                                                      'initial_language' => 'initialLanguage',
                                                      'initial_language_code' => 'initialLanguageCode',
                                                      'available_languages' => 'availableLanguages',
                                                      'language_codes' => 'availableLanguages',
                                                      'language_js_array' => 'availableLanguagesJsArray',
                                                      'languages' => 'languages',
                                                      'all_languages' => 'allLanguages',
                                                      'can_edit_languages' => 'canEditLanguages',
                                                      'can_create_languages' => 'canCreateLanguages',
                                                      'always_available' => 'isAlwaysAvailable',
                                                      'allowed_assign_section_list' => 'allowedAssignSectionList',
                                                      'allowed_assign_state_id_list' => 'allowedAssignStateIDList',
                                                      'allowed_assign_state_list' => 'allowedAssignStateList',
                                                      'state_id_array' => 'stateIDArray',
                                                      'state_identifier_array' => 'stateIdentifierArray' ),
                      "increment_key" => "id",
                      "class_name" => "eZContentObject",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentobject" );
    }

    /*!
     Get class groups this object's class belongs to if match for class groups is enabled.

     \return array of class group ids. False if match is disabled.
    */
    function matchIngroupIDList()
    {
        $contentINI = eZINI::instance( 'content.ini' );
        $inList = false;
        if( $contentINI->variable( 'ContentOverrideSettings', 'EnableClassGroupOverride' ) == 'true' )
        {
            $contentClass = $this->contentClass();
            $inList = $contentClass->attribute( 'ingroup_id_list' );
        }
        return $inList;
    }

    /*!
     Store the object
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function store( $fieldFilters = null )
    {
        // Unset the cache
        global $eZContentObjectContentObjectCache;
        unset( $eZContentObjectContentObjectCache[$this->ID] );
        global $eZContentObjectDataMapCache;
        unset( $eZContentObjectDataMapCache[$this->ID] );
        global $eZContentObjectVersionCache;
        unset( $eZContentObjectVersionCache[$this->ID] );

        $db = eZDB::instance();
        $db->begin();
        $this->storeNodeModified();
        eZPersistentObject::store( $fieldFilters );
        $db->commit();
    }

    /*!
     Clear in-memory caches.
     \param  $idArray  objects to clear caches for.

     If the parameter is ommitted the caches are cleared for all objects.
    */

    static function clearCache( $idArray = array() )
    {
        if ( is_numeric( $idArray ) )
            $idArray = array( $idArray );

        // clear in-memory cache for all objects
        if ( count( $idArray ) == 0 )
        {
            unset( $GLOBALS['eZContentObjectContentObjectCache'] );
            unset( $GLOBALS['eZContentObjectDataMapCache'] );
            unset( $GLOBALS['eZContentObjectVersionCache'] );

            return;
        }

        // clear in-memory cache for specified object(s)
        global $eZContentObjectContentObjectCache;
        global $eZContentObjectDataMapCache;
        global $eZContentObjectVersionCache;
        foreach ( $idArray as $objectID )
        {
            unset( $eZContentObjectContentObjectCache[$objectID] );
            unset( $eZContentObjectDataMapCache[$objectID] );
            unset( $eZContentObjectVersionCache[$objectID] );
        }
    }

    /*!
     Update all nodes to set modified_subnode value
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function storeNodeModified()
    {
        if ( is_numeric( $this->ID ) )
        {
            $nodeArray = $this->assignedNodes();

            $db = eZDB::instance();
            $db->begin();
            foreach ( array_keys( $nodeArray ) as $key )
            {
                $nodeArray[$key]->updateAndStoreModified();
            }
            $db->commit();
        }
    }

    function name( $version = false , $lang = false )
    {
        // if the object id is null, we can't read data from the database
        // and return the locally known name
        if ( is_null( $this->attribute( 'id' ) ) )
        {
            return $this->Name;
        }
        if ( !$version )
        {
            $version = $this->attribute( 'current_version' );
        }
        if ( !$lang && $this->CurrentLanguage )
        {
            $lang = $this->CurrentLanguage;
        }

        return $this->versionLanguageName( $version, $lang );
    }

    function names()
    {
        $version = $this->attribute( 'current_version' );
        $id = $this->attribute( 'id' );

        $db = eZDB::instance();
        $rows = $db->arrayQuery( "SELECT name, real_translation FROM ezcontentobject_name WHERE contentobject_id = '$id' AND content_version='$version'" );
        $names = array();
        foreach ( $rows as $row )
        {
            $names[$row['real_translation']] = $row['name'];
        }

        return $names;
    }

    function versionLanguageName( $version, $lang = false )
    {
        $contentObjectID = $this->attribute( 'id' );
        $name = false;
        if ( !$version > 0 )
        {
            eZDebug::writeNotice( "There is no object name for version($version) of the content object ($contentObjectID) in language($lang)",
                                  'eZContentObject::versionLanguageName' );
            return $name;
        }
        $db = eZDb::instance();
        if ( !$lang )
        {
            // If $lang not given we will use the initial language of the object
            $query = "SELECT initial_language_id FROM ezcontentobject WHERE id='$contentObjectID'";
            $rows = $db->arrayQuery( $query );
            if ( $rows )
            {
                $languageID = $rows[0]['initial_language_id'];
                $language = eZContentLanguage::fetch( $languageID );
                if ( $language )
                {
                    $lang = $language->attribute( 'locale' );
                }
                else
                {
                    return $name;
                }
            }
            else
            {
                return $name;
            }
        }
        $lang = $db->escapeString( $lang );
        $version = (int) $version;

        $initialLanguage = $this->attribute( 'initial_language_id' );

        $query= "SELECT name, content_translation
                 FROM ezcontentobject_name
                 WHERE contentobject_id = '$contentObjectID'
                       AND content_version = '$version'
                       AND ( content_translation = '$lang' OR language_id = '$initialLanguage' )";
        $result = $db->arrayQuery( $query );

        $resCount = count( $result );
        if( $resCount < 1 )
        {
            eZDebug::writeNotice( "There is no object name for version($version) of the content object ($contentObjectID) in language($lang)",
                                  'eZContentObject::versionLanguageName' );
        }
        else if( $resCount > 1 )
        {
            // we have name in requested language => find and return it
            foreach( $result as $row )
            {
                if( $row['content_translation'] == $lang )
                {
                    $name = $row['name'];
                    break;
                }
            }
        }
        else
        {
            // we don't have name in requested language(or requested language is the same as initial language) => use name in initial language
            $name = $result[0]['name'];
        }

        return $name;
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
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function setName( $objectName, $versionNum = false, $languageCode = false )
    {
        $initialLanguageCode = false;
        if ( $initialLanguage = $this->initialLanguage() )
        {
            $initialLanguageCode = $initialLanguage->attribute( 'locale' );
        }
        $db = eZDB::instance();

        if ( $languageCode == false )
        {
            $languageCode = $initialLanguageCode;
        }
        $languageCode = $db->escapeString( $languageCode );
        if ( $languageCode == $initialLanguageCode )
        {
            $this->Name = $objectName;
        }

        if ( !$versionNum )
        {
            $versionNum = $this->attribute( 'current_version' );
        }
        $objectID =(int) $this->attribute( 'id' );
        $versionNum =(int) $versionNum;

        $languageID =(int) eZContentLanguage::idByLocale( $languageCode );

        $objectName = $db->escapeString( $objectName );

        $db->begin();

        // Check if name is already set before setting/changing it.
        // This helps to avoid deadlocks in mysql: a pair of DELETE/INSERT might cause deadlock here
        // in case of concurrent execution.
        $rows = $db->arrayQuery( "SELECT COUNT(*) AS count FROM ezcontentobject_name WHERE contentobject_id = '$objectID'
                                 AND content_version = '$versionNum' AND content_translation ='$languageCode'" );
        if ( $rows[0]['count'] )
        {
            $db->query( "UPDATE ezcontentobject_name SET name='$objectName'
                         WHERE
                         contentobject_id = '$objectID'  AND
                         content_version = '$versionNum' AND
                         content_translation ='$languageCode'" );
        }
        else
        {
            $db->query( "INSERT INTO ezcontentobject_name( contentobject_id,
                                                           name,
                                                           content_version,
                                                           content_translation,
                                                           real_translation,
                                                           language_id )
                                VALUES( '$objectID',
                                        '$objectName',
                                        '$versionNum',
                                        '$languageCode',
                                        '$languageCode',
                                        '$languageID' )" );
        }

        $db->commit();
    }

    /*!
     \return a map with all the content object attributes where the keys are the
             attribute identifiers.
    */
    function dataMap()
    {
        return $this->fetchDataMap();
    }

    /*!
     \return a map with all the content object attributes where the keys are the
             attribute identifiers.
     \sa eZContentObjectTreeNode::dataMap
    */
    function fetchDataMap( $version = false, $language = false )
    {
        // Global variable to cache datamaps
        global $eZContentObjectDataMapCache;

        if ( $version == false )
            $version = $this->attribute( 'current_version' );

        if ( $language == false )
        {
            $language = $this->CurrentLanguage;
        }

        if ( !$language || !isset( $eZContentObjectDataMapCache[$this->ID][$version][$language] ) )
        {
            $data = $this->contentObjectAttributes( true, $version, $language );

            if ( !$language )
            {
                $language = $this->CurrentLanguage;
            }

            // Store the attributes for later use
            $this->ContentObjectAttributeArray[$version][$language] = $data;
            $eZContentObjectDataMapCache[$this->ID][$version][$language] = $data;
        }
        else
        {
            $data = $eZContentObjectDataMapCache[$this->ID][$version][$language];
        }

        if ( !isset( $this->DataMap[$version][$language] ) )
        {
            $ret = array();
            foreach( $data as $key => $item )
            {
                $identifier = $item->contentClassAttributeIdentifier();
                $ret[$identifier] = $data[$key];
            }
            $this->DataMap[$version][$language] = $ret;
        }
        else
        {
            $ret = $this->DataMap[$version][$language];
        }
        return $ret;
    }

    function resetDataMap()
    {
        $this->ContentObjectAttributeArray = array();
        $this->ContentObjectAttributes = array();
        $this->DataMap = array();
        return $this->DataMap;
    }

    /*!
      Fetch a set of content object attributes by their class identifiers.
    */
    function fetchAttributesByIdentifier( $identifierArray, $version = false, $languageArray = false, $asObject = true )
    {
        if ( count( $identifierArray ) === 0 )
        {
            return null;
        }

        $db  = eZDB::instance();

        $identifierQuotedString = array();
        foreach ( $identifierArray as $identifier )
        {
            $identifierQuotedString[] = "'$identifier'";
        }

        if ( !$version or !is_numeric( $version ) )
        {
            $version = $this->CurrentVersion;
        }

        if ( is_array( $languageArray ) )
        {
            $langCodeQuotedString = array();
            foreach ( $languageArray as $langCode )
            {
                if ( is_string( $langCode ) )
                    $langCodeQuotedString[] = "'$langCode'";
            }

            if ( !empty( $langCodeQuotedString ) )
            {
                $languageText = "AND\n\t\t";
                $languageText .= $db->generateSQLINStatement( $langCodeQuotedString, 'ezcontentobject_attribute.language_code' );
            }
        }

        if ( !isset( $languageText ) )
        {
            $languageText = "AND\n\t\t\t" . eZContentLanguage::sqlFilter( 'ezcontentobject_attribute', 'ezcontentobject_version' );
        }

        $versionText = "AND\n\t\t ezcontentobject_attribute.version = '$version'";

        $query = "SELECT\t ezcontentobject_attribute.*, ezcontentclass_attribute.identifier as identifier
            FROM\t ezcontentobject_attribute, ezcontentclass_attribute, ezcontentobject_version
            WHERE
                ezcontentclass_attribute.version = ". eZContentClass::VERSION_STATUS_DEFINED . " AND
                ezcontentclass_attribute.id = ezcontentobject_attribute.contentclassattribute_id AND
                ezcontentobject_version.contentobject_id = {$this->ID} AND
                ezcontentobject_version.version = {$version} AND
                ezcontentobject_attribute.contentobject_id = {$this->ID}

                {$languageText}

                {$versionText}

                AND
                ";

        $query .= $db->generateSQLINStatement( $identifierQuotedString, 'identifier' );

        $rows = $db->arrayQuery( $query );

        if ( count( $rows ) > 0 )
        {
            if ( $asObject )
            {
                $returnArray = array();
                foreach( $rows as $row )
                {
                    $returnArray[$row['id']] = new eZContentObjectAttribute( $row );
                }
                return $returnArray;
            }
            else
            {
                return $rows;
            }
        }
        return null;
    }

    /*!
     Returns the owner of the object as a content object.
    */
    function owner()
    {
        if ( $this->OwnerID != 0 )
        {
            return eZContentObject::fetch( $this->OwnerID );
        }
        return null;
    }

    /*!
     \return the content class group identifiers for the current content object
    */
    function contentClassGroupIDList()
    {
        $contentClass = $this->contentClass();
        return $contentClass->attribute( 'ingroup_id_list' );
    }

    /*!
     \return the content class identifer for the current content object

     \note The object will cache the class name information so multiple calls will be fast.
    */
    function contentClassIdentifier()
    {
        if ( !is_numeric( $this->ClassID ) )
        {
            $retValue = null;
            return $retValue;
        }

        if ( $this->ClassIdentifier !== false )
            return $this->ClassIdentifier;

        $this->ClassIdentifier = eZContentClass::classIdentifierByID( $this->ClassID );

        return $this->ClassIdentifier;
    }

    /*!
     \return the content class for the current content object
    */
    function contentClass()
    {
        if ( !is_numeric( $this->ClassID ) )
        {
            $retValue = null;
            return $retValue;
        }

        return eZContentClass::fetch( $this->ClassID );
    }

    /*!
     Get remote id of content object
    */
    function remoteID()
    {
        $remoteID = eZPersistentObject::attribute( 'remote_id', true );

        // Ensures that we provide the correct remote_id if we have one in the database
        if ( $remoteID === null and $this->attribute( 'id' ) )
        {
            $db = eZDB::instance();
            $resultArray = $db->arrayQuery( "SELECT remote_id FROM ezcontentobject WHERE id = '" . $this->attribute( 'id' ) . "'" );
            if ( count( $resultArray ) == 1 )
            {
                $remoteID = $resultArray[0]['remote_id'];
                $this->setAttribute( 'remote_id',  $remoteID );
            }
        }

        if ( !$remoteID )
        {
            $this->setAttribute( 'remote_id', md5( (string)mt_rand() . (string)time() ) );
            if ( $this->attribute( 'id' ) !== null )
                $this->sync( array( 'remote_id' ) );
            $remoteID = eZPersistentObject::attribute( 'remote_id', true );
        }

        return $remoteID;
    }

    function mainParentNodeID()
    {
        $list = eZContentObjectTreeNode::getParentNodeIdListByContentObjectID( $this->ID, false, true );
        return isset( $list[0] ) ? $list[0] : null;
    }

    /*!
     Fetches contentobject by remote ID, returns null if none exist
    */
    static function fetchByRemoteID( $remoteID, $asObject = true )
    {
        $db = eZDB::instance();
        $remoteID =$db->escapeString( $remoteID );
        $resultArray = $db->arrayQuery( 'SELECT id FROM ezcontentobject WHERE remote_id=\'' . $remoteID . '\'' );
        if ( count( $resultArray ) != 1 )
            $object = null;
        else
            $object = eZContentObject::fetch( $resultArray[0]['id'], $asObject );
        return $object;
    }

    /*!
     Fetches the content object with the given ID
     \note Uses the static function createFetchSQLString() to generate the SQL
    */
    static function fetch( $id, $asObject = true )
    {
        global $eZContentObjectContentObjectCache;

        // If the object given by its id is not cached or should be returned as array
        // then we fetch it from the DB (objects are always cached as arrays).
        if ( !isset( $eZContentObjectContentObjectCache[$id] ) or $asObject === false )
        {
            $db = eZDB::instance();

            $resArray = $db->arrayQuery( eZContentObject::createFetchSQLString( $id ) );

            $objectArray = array();
            if ( count( $resArray ) == 1 && $resArray !== false )
            {
                $objectArray = $resArray[0];
            }
            else
            {
                eZDebug::writeError( "Object not found ($id)", 'eZContentObject::fetch()' );
                $retValue = null;
                return $retValue;
            }

            if ( $asObject )
            {
                $obj = new eZContentObject( $objectArray );
                $eZContentObjectContentObjectCache[$id] = $obj;
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
     \static
     Tests for the existance of a content object by using the ID \a $id.
     \return \c true if the object exists, \c false otherwise.
     \note Uses the static function createFetchSQLString() to generate the SQL
    */
    static function exists( $id )
    {
        global $eZContentObjectContentObjectCache;

        // Check the global cache
        if ( isset( $eZContentObjectContentObjectCache[$id] ) )
            return true;

        // If the object is not cached we need to check the DB
        $db = eZDB::instance();

        $resArray = $db->arrayQuery( eZContentObject::createFetchSQLString( $id ) );

        if ( $resArray !== false and count( $resArray ) == 1 )
        {
            return true;
        }

        return false;

    }

    /*!
      \static
      Creates the SQL for fetching the object with ID \a $id and returns the string.
    */
    static function createFetchSQLString( $id )
    {
        $id = (int) $id;

        $fetchSQLString = "SELECT ezcontentobject.*,
                               ezcontentclass.serialized_name_list as serialized_name_list,
                               ezcontentclass.identifier as contentclass_identifier,
                               ezcontentclass.is_container as is_container
                           FROM
                               ezcontentobject,
                               ezcontentclass
                           WHERE
                               ezcontentobject.id='$id' AND
                               ezcontentclass.id = ezcontentobject.contentclass_id AND
                               ezcontentclass.version=0";

        return $fetchSQLString;
    }

    /**
     * Creates the SQL for filtering objects by visibility, used by IgnoreVisibility on some fetches.
     * The object is visible if 1 or more assigned nodes are visible.
     *
     * @static
     * @since Version 4.1
     * @param bool $IgnoreVisibility ignores visibility if true
     * @param string $ezcontentobjectTable name of ezcontentobject table used in sql
     * @return string with sql condition for node filtering by visibility
     */
    static function createFilterByVisibilitySQLString( $IgnoreVisibility = false, $ezcontentobjectTable = 'ezcontentobject' )
    {
        if ( $IgnoreVisibility )
            return '';
        return " AND ( SELECT MIN( ezct.is_invisible ) FROM ezcontentobject_tree ezct WHERE ezct.contentobject_id = $ezcontentobjectTable.id ) = 0 ";
    }

    /*!
     Fetches the contentobject which has a node with the ID \a $nodeID
     \param $asObject If \c true return the as a PHP object, if \c false return the raw database data.
    */
    static function fetchByNodeID( $nodeID, $asObject = true )
    {
        global $eZContentObjectContentObjectCache;
        $nodeID = (int)$nodeID;

        $useVersionName = true;
        if ( $useVersionName )
        {
            $versionNameTables = ', ezcontentobject_name ';
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

            $versionNameJoins = " and  ezcontentobject.id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject.current_version = ezcontentobject_name.content_version and ".
                                  eZContentLanguage::sqlFilter( 'ezcontentobject_name', 'ezcontentobject' );
        }

        $db = eZDB::instance();

        $query = "SELECT ezcontentobject.* $versionNameTargets
                      FROM
                         ezcontentobject,
                         ezcontentobject_tree
                         $versionNameTables
                      WHERE
                         ezcontentobject_tree.node_id=$nodeID AND
                         ezcontentobject.id=ezcontentobject_tree.contentobject_id AND
                         ezcontentobject.current_version=ezcontentobject_tree.contentobject_version
                         $versionNameJoins";

        $resArray = $db->arrayQuery( $query );

        $objectArray = array();
        if ( count( $resArray ) == 1 && $resArray !== false )
        {
            $objectArray = $resArray[0];
        }
        else
        {
            eZDebug::writeError( 'Object not found with node id ' . $nodeID, 'eZContentObject::fetchByNodeID()' );
            $retValue = null;
            return $retValue;
        }

        if ( $asObject )
        {
            $obj = new eZContentObject( $objectArray );
            $eZContentObjectContentObjectCache[$objectArray['id']] = $obj;
        }
        else
        {
            return $objectArray;
        }

        return $obj;
    }

    /*!
     Fetches the content object from the ID array
    */
    static function fetchIDArray( $idArray, $asObject = true )
    {
        global $eZContentObjectContentObjectCache;

        $uniqueIDArray = array_unique( $idArray );

        $useVersionName = true;
        if ( $useVersionName )
        {
            $versionNameTables = ', ezcontentobject_name ';
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

            $versionNameJoins = " and  ezcontentobject.id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject.current_version = ezcontentobject_name.content_version and ".
                                  eZContentLanguage::sqlFilter( 'ezcontentobject_name', 'ezcontentobject' );
        }

        $db = eZDB::instance();
        // All elements from $uniqueIDArray should be casted to (int)
        $objectInSQL = $db->implodeWithTypeCast( ', ', $uniqueIDArray, 'int' );
        $query = "SELECT ezcontentclass.serialized_name_list as class_serialized_name_list, ezcontentobject.* $versionNameTargets
                      FROM
                         ezcontentclass,
                         ezcontentobject
                         $versionNameTables
                      WHERE
                         ezcontentclass.id=ezcontentobject.contentclass_id AND
                         ezcontentobject.id IN ( $objectInSQL )
                         $versionNameJoins";

        $resRowArray = $db->arrayQuery( $query );

        $objectRetArray = array();
        foreach ( $resRowArray as $resRow )
        {
            $objectID = $resRow['id'];
            $resRow['class_name'] = eZContentClass::nameFromSerializedString( $resRow['class_serialized_name_list'] );
            if ( $asObject )
            {
                $obj = new eZContentObject( $resRow );
                $obj->ClassName = $resRow['class_name'];
                $eZContentObjectContentObjectCache[$objectID] = $obj;
                $objectRetArray[$objectID] = $obj;
            }
            else
            {
                $objectRetArray[$objectID] = $resRow;
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
    static function fetchList( $asObject = true, $conditions = null, $offset = false, $limit = false )
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

    static function fetchFilteredList( $conditions = null, $offset = false, $limit = false, $asObject = true )
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
    static function fetchListCount( $conditions = null )
    {
        $rows =  eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                      array(),
                                                      $conditions,
                                                      false/* we don't want any sorting when counting. Sorting leads to error on postgresql 8.x */,
                                                      null,
                                                      false, false,
                                                      array( array( 'operation' => 'count( * )',
                                                                    'name' => 'count' ) ) );
        return $rows[0]['count'];
    }

    static function fetchSameClassList( $contentClassID, $asObject = true, $offset = false, $limit = false )
    {
        $conditions = array( 'contentclass_id' => $contentClassID );
        return eZContentObject::fetchFilteredList( $conditions, $offset, $limit, $asObject );
    }

    static function fetchSameClassListCount( $contentClassID )
    {
        $result = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                       array(),
                                                       array( "contentclass_id" => $contentClassID ),
                                                       false, null,
                                                       false, false,
                                                       array( array( 'operation' => 'count( * )',
                                                                     'name' => 'count' ) ) );
        return $result[0]['count'];
    }

    /*!
      Returns the current version of this document.
    */
    function currentVersion( $asObject = true )
    {
        return eZContentObjectVersion::fetchVersion( $this->attribute( "current_version" ), $this->ID, $asObject );
    }

    /*!
      Returns the given object version. False is returned if the versions does not exist.
    */
    function version( $version, $asObject = true )
    {
        if ( $asObject )
        {
            global $eZContentObjectVersionCache;

            if ( !isset( $eZContentObjectVersionCache ) ) // prevent PHP warning below
                $eZContentObjectVersionCache = array();

            if ( array_key_exists( $this->ID, $eZContentObjectVersionCache ) &&
                 array_key_exists( $version, $eZContentObjectVersionCache[$this->ID] ) )
            {
                return $eZContentObjectVersionCache[$this->ID][$version];
            }
            else
            {
                $eZContentObjectVersionCache[$this->ID][$version] = eZContentObjectVersion::fetchVersion( $version, $this->ID, $asObject );
                return $eZContentObjectVersionCache[$this->ID][$version];
            }
        }
        else
        {
            return eZContentObjectVersion::fetchVersion( $version, $this->ID, $asObject );
        }
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
            if ( isset( $parameters['conditions']['language_code'] ) )
            {
                $conditions['initial_language_id'] = eZContentLanguage::idByLocale( $parameters['conditions']['language_code'] );
            }
            if ( isset( $parameters['conditions']['initial_language_id'] ) )
            {
                $conditions['initial_language_id'] = $parameters['conditions']['initial_language_id'];
            }
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

    function createInitialVersion( $userID, $initialLanguageCode = false )
    {
        return eZContentObjectVersion::create( $this->attribute( "id" ), $userID, 1, $initialLanguageCode );
    }

    function createNewVersionIn( $languageCode, $copyFromLanguageCode = false, $copyFromVersion = false, $versionCheck = true, $status = eZContentObjectVersion::STATUS_DRAFT )
    {
        return $this->createNewVersion( $copyFromVersion, $versionCheck, $languageCode, $copyFromLanguageCode, $status );
    }

    /*!
     Creates a new version and returns it as an eZContentObjectVersion object.
     If version number is given as argument that version is used to create a copy.
     \param $versionCheck If \c true it will check if there are too many version and
                          remove some of them to make room for a new.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function createNewVersion( $copyFromVersion = false, $versionCheck = true, $languageCode = false, $copyFromLanguageCode = false, $status = eZContentObjectVersion::STATUS_DRAFT )
    {
        $db = eZDB::instance();
        $db->begin();
        // Check if we have enough space in version list
        if ( $versionCheck )
        {
            $contentINI = eZINI::instance( 'content.ini' );
            $versionlimit = $contentINI->variable( 'VersionManagement', 'DefaultVersionHistoryLimit' );
            $limitList =  eZContentClass::classIDByIdentifier( $contentINI->variable( 'VersionManagement', 'VersionHistoryClass' ) );
            $classID = $this->attribute( 'contentclass_id' );
            foreach ( $limitList as $key => $value )
            {
                if ( $classID == $key )
                {
                    $versionlimit = $value;
                }
            }
            if ( $versionlimit < 2 )
            {
                $versionlimit = 2;
            }
            $versionCount = $this->getVersionCount();
            if ( $versionCount >= $versionlimit )
            {
                // Remove oldest archived version
                $params = array( 'conditions'=> array( 'status' => 3 ) );
                $versions = $this->versions( true, $params );
                if ( count( $versions ) > 0 )
                {
                    $modified = $versions[0]->attribute( 'modified' );
                    $removeVersion = $versions[0];
                    foreach ( $versions as $version )
                    {
                        $currentModified = $version->attribute( 'modified' );
                        if ( $currentModified < $modified )
                        {
                            $modified = $currentModified;
                            $removeVersion = $version;
                        }
                    }
                    $removeVersion->removeThis();
                }
            }
        }

        // get the next available version number
        $nextVersionNumber = $this->nextVersion();

        if ( $copyFromVersion == false )
        {
            $version = $this->currentVersion();
        }
        else
        {
            $version = $this->version( $copyFromVersion );
        }

        if ( !$languageCode )
        {
            $initialLanguage = $version->initialLanguage();
            if ( !$initialLanguage )
            {
                $initialLanguage = $this->initialLanguage();
            }

            if ( $initialLanguage )
            {
                $languageCode = $initialLanguage->attribute( 'locale' );
            }
        }

        $copiedVersion = $this->copyVersion( $this, $version, $nextVersionNumber, false, $status, $languageCode, $copyFromLanguageCode );

        // We need to make sure the copied version contains node-assignment for the existing nodes.
        // This is required for BC since scripts might traverse the node-assignments and mark
        // some of them for removal.
        $parentMap = array();
        $copiedNodeAssignmentList = $copiedVersion->attribute( 'node_assignments' );
        foreach ( $copiedNodeAssignmentList as $copiedNodeAssignment )
        {
            $parentMap[$copiedNodeAssignment->attribute( 'parent_node' )] = $copiedNodeAssignment;
        }
        $nodes = $this->assignedNodes();
        foreach ( $nodes as $node )
        {
            $remoteID = 0;
            // Remove assignments which conflicts with existing nodes, but keep remote_id
            if ( isset( $parentMap[$node->attribute( 'parent_node_id' )] ) )
            {
                $copiedNodeAssignment = $parentMap[$node->attribute( 'parent_node_id' )];
                $remoteID = $copiedNodeAssignment->attribute( 'remote_id' );
                $copiedNodeAssignment->purge();
            }
            $newNodeAssignment = $copiedVersion->assignToNode( $node->attribute( 'parent_node_id' ), $node->attribute( 'is_main' ), 0,
                                                               $node->attribute( 'sort_field' ), $node->attribute( 'sort_order' ),
                                                               $remoteID );
            // Reset execution bit
            $newNodeAssignment->setAttribute( 'op_code', $newNodeAssignment->attribute( 'op_code' ) & ~1 );
            $newNodeAssignment->store();
        }

        $db->commit();
        return $copiedVersion;
    }

    /*!
     Creates a new version and returns it as an eZContentObjectVersion object.
     If version number is given as argument that version is used to create a copy.
     \param $languageCode If \c false all languages will be copied, otherwise
                          only specified by the locale code string or an array
                          of the locale code strings.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function copyVersion( &$newObject, &$version, $newVersionNumber, $contentObjectID = false, $status = eZContentObjectVersion::STATUS_DRAFT, $languageCode = false, $copyFromLanguageCode = false )
    {
        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        $nodeAssignmentList = $version->attribute( 'node_assignments' );

        $db = eZDB::instance();
        $db->begin();

        // This is part of the new 3.8 code.
        foreach ( array_keys( $nodeAssignmentList ) as $key )
        {
            $nodeAssignment = $nodeAssignmentList[$key];
            // Only copy assignments which has a remote_id since it will be used in template code.
            if ( $nodeAssignment->attribute( 'remote_id' ) == 0 )
            {
                continue;
            }
            $clonedAssignment = $nodeAssignment->cloneNodeAssignment( $newVersionNumber, $contentObjectID );
            $clonedAssignment->setAttribute( 'op_code', eZNodeAssignment::OP_CODE_SET ); // Make sure op_code is marked to 'set' the data.
            $clonedAssignment->store();
        }

        $currentVersionNumber = $version->attribute( "version" );
        $contentObjectTranslations = $version->translations();

        $clonedVersion = $version->cloneVersion( $newVersionNumber, $userID, $contentObjectID, $status );

        if ( $contentObjectID != false )
        {
            if ( $clonedVersion->attribute( 'status' ) == eZContentObjectVersion::STATUS_PUBLISHED )
                $clonedVersion->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
        }

        $clonedVersion->store();

        // We copy related objects before the attributes, this means that the related objects
        // are available once the datatype code is run.
        $this->copyContentObjectRelations( $currentVersionNumber, $newVersionNumber, $contentObjectID );

        $languageCodeToCopy = false;
        if ( $languageCode && in_array( $languageCode, $this->availableLanguages() ) )
        {
            $languageCodeToCopy = $languageCode;
        }
        if ( $copyFromLanguageCode && in_array( $copyFromLanguageCode, $this->availableLanguages() ) )
        {
            $languageCodeToCopy = $copyFromLanguageCode;
        }

        $haveCopied = false;
        if ( !$languageCode || $languageCodeToCopy )
        {
            foreach ( $contentObjectTranslations as $contentObjectTranslation )
            {
                if ( $languageCode != false && $contentObjectTranslation->attribute( 'language_code' ) != $languageCodeToCopy )
                {
                    continue;
                }

                $contentObjectAttributes = $contentObjectTranslation->objectAttributes();

                foreach ( $contentObjectAttributes as $attribute )
                {
                    $clonedAttribute = $attribute->cloneContentObjectAttribute( $newVersionNumber, $currentVersionNumber, $contentObjectID, $languageCode );
                    $clonedAttribute->sync();
                    eZDebugSetting::writeDebug( 'kernel-content-object-copy', $clonedAttribute, 'copyVersion:cloned attribute' );
                }

                $haveCopied = true;
            }
        }

        if ( !$haveCopied && $languageCode )
        {
            $class = $this->contentClass();
            $classAttributes = $class->fetchAttributes();
            foreach ( $classAttributes as $classAttribute )
            {
                if ( $classAttribute->attribute( 'can_translate' ) == 1 )
                {
                    $classAttribute->instantiate( $contentObjectID? $contentObjectID: $this->attribute( 'id' ), $languageCode, $newVersionNumber );
                }
                else
                {
                    // If attribute is NOT Translatable we should check isAlwaysAvailable(),
                    // For example,
                    // if initial_language_id is 4 and the attribute is always available
                    // language_id will be 5 in ezcontentobject_version/ezcontentobject_attribute,
                    // this means it uses language ID 4 but also has the bit 0 set to 1 (a reservered bit),
                    // You can read about this in the document in doc/features/3.8/.
                    $initialLangID = !$this->isAlwaysAvailable() ? $this->attribute( 'initial_language_id' ) : $this->attribute( 'initial_language_id' ) | 1;
                    $contentAttribute = eZContentObjectAttribute::fetchByClassAttributeID( $classAttribute->attribute( 'id' ),
                                                                                           $this->attribute( 'id' ),
                                                                                           $this->attribute( 'current_version' ),
                                                                                           $initialLangID );
                    if ( $contentAttribute )
                    {
                        $newAttribute = $contentAttribute->cloneContentObjectAttribute( $newVersionNumber, $currentVersionNumber, $contentObjectID, $languageCode );
                        $newAttribute->sync();
                    }
                    else
                    {
                        $classAttribute->instantiate( $contentObjectID? $contentObjectID: $this->attribute( 'id' ), $languageCode, $newVersionNumber );
                    }
                }
            }
        }

        if ( $languageCode )
        {
            $clonedVersion->setAttribute( 'initial_language_id', eZContentLanguage::idByLocale( $languageCode ) );
            $clonedVersion->updateLanguageMask();
        }

        $db->commit();

        return $clonedVersion;
    }

    /*!
     Creates a new content object instance and stores it.
    */
    static function create( $name, $contentclassID, $userID, $sectionID = 1, $version = 1, $languageCode = false )
    {
        if ( $languageCode == false )
        {
            $languageCode = eZContentObject::defaultLanguage();
        }

        $languageID = eZContentLanguage::idByLocale( $languageCode );

        $row = array(
            "name" => $name,
            "current_version" => $version,
            'initial_language_id' => $languageID,
            'language_mask' => $languageID,
            "contentclass_id" => $contentclassID,
            "permission_id" => 1,
            "parent_id" => 0,
            "main_node_id" => 0,
            "owner_id" => $userID,
            "section_id" => $sectionID,
            'remote_id' => md5( (string)mt_rand() . (string)time() ) );

        return new eZContentObject( $row );
    }

    function __clone()
    {
        $this->setAttribute( 'id', null );
        $this->setAttribute( 'published', time() );
        $this->setAttribute( 'modified', time() );
        $this->resetDataMap();
    }

    /*!
     Makes a copy of the object which is stored and then returns it.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function copy( $allVersions = true )
    {
        eZDebugSetting::writeDebug( 'kernel-content-object-copy', 'Copy start, all versions=' . ( $allVersions ? 'true' : 'false' ), 'copy' );
        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        $contentObject = clone $this;
        $contentObject->setAttribute( 'current_version', 1 );
        $contentObject->setAttribute( 'owner_id', $userID );

        // Set new unique remote_id
        $newRemoteID = md5( (string)mt_rand() . (string)time() );
        $contentObject->setAttribute( 'remote_id', $newRemoteID );

        $db = eZDB::instance();
        $db->begin();
        $contentObject->store();

        $originalObjectID = $this->attribute( 'id' );
        $contentObjectID = $contentObject->attribute( 'id' );

        $db->query( "INSERT INTO ezcobj_state_link (contentobject_state_id, contentobject_id)
                     SELECT contentobject_state_id, $contentObjectID FROM ezcobj_state_link WHERE contentobject_id = $originalObjectID" );

        $contentObject->setName( $this->attribute('name') );
        eZDebugSetting::writeDebug( 'kernel-content-object-copy', $contentObject, 'contentObject' );


        $versionList = array();
        if ( $allVersions )
        {
            $versions = $this->versions();
            foreach( $versions as $version )
            {
                $versionList[$version->attribute( 'version' )] = $version;
            }
        }
        else
        {
            $versionList[1] = $this->currentVersion();
        }

        foreach ( $versionList as $versionNumber => $currentContentObjectVersion )
        {
            $currentVersionNumber = $currentContentObjectVersion->attribute( 'version' );
            $contentObject->setName( $currentContentObjectVersion->name(), $versionNumber );
            foreach( $contentObject->translationStringList() as $languageCode )
            {
                $contentObject->setName( $currentContentObjectVersion->name( false, $languageCode ), $versionNumber, $languageCode );
            }

            $contentObjectVersion = $this->copyVersion( $contentObject, $currentContentObjectVersion,
                                                        $versionNumber, $contentObject->attribute( 'id' ),
                                                        false );

            if ( $currentVersionNumber == $this->attribute( 'current_version' ) )
            {
                $parentMap = array();
                $copiedNodeAssignmentList = $contentObjectVersion->attribute( 'node_assignments' );
                foreach ( $copiedNodeAssignmentList as $copiedNodeAssignment )
                {
                    $parentMap[$copiedNodeAssignment->attribute( 'parent_node' )] = $copiedNodeAssignment;
                }
                // Create node-assignment from all current published nodes
                $nodes = $this->assignedNodes();
                foreach( $nodes as $node )
                {
                    $remoteID = 0;
                    // Remove assignments which conflicts with existing nodes, but keep remote_id
                    if ( isset( $parentMap[$node->attribute( 'parent_node_id' )] ) )
                    {
                        $copiedNodeAssignment = $parentMap[$node->attribute( 'parent_node_id' )];
                        unset( $parentMap[$node->attribute( 'parent_node_id' )] );
                        $remoteID = $copiedNodeAssignment->attribute( 'remote_id' );
                        $copiedNodeAssignment->purge();
                    }
                    $newNodeAssignment = $contentObjectVersion->assignToNode( $node->attribute( 'parent_node_id' ), $node->attribute( 'is_main' ), 0,
                                                                              $node->attribute( 'sort_field' ), $node->attribute( 'sort_order' ),
                                                                              $remoteID );
                }
            }

            eZDebugSetting::writeDebug( 'kernel-content-object-copy', $contentObjectVersion, 'Copied version' );
        }

        // Set version number
        if ( $allVersions )
            $contentObject->setAttribute( 'current_version', $this->attribute( 'current_version' ) );

        $contentObject->setAttribute( 'status', eZContentObject::STATUS_DRAFT );

        $contentObject->store();

        $db->commit();

        eZDebugSetting::writeDebug( 'kernel-content-object-copy', 'Copy done', 'copy' );
        return $contentObject;
    }

    /*!
      Reverts the object to the given version. All versions newer then the given version will
      be deleted.
      \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function revertTo( $version )
    {
        $db = eZDB::instance();
        $db->begin();

        // Delete stored attribute from other tables
        $contentobjectAttributes = $this->allContentObjectAttributes( $this->ID );
        foreach (  $contentobjectAttributes as $contentobjectAttribute )
        {
            $contentobjectAttributeVersion = $contentobjectAttribute->attribute("version");
            if( $contentobjectAttributeVersion > $version )
            {
                $classAttribute = $contentobjectAttribute->contentClassAttribute();
                $dataType = $classAttribute->dataType();
                $dataType->deleteStoredObjectAttribute( $contentobjectAttribute, $contentobjectAttributeVersion );
            }
        }
        $version =(int) $version;
        $db->query( "DELETE FROM ezcontentobject_attribute
                          WHERE contentobject_id='$this->ID' AND version>'$version'" );

        $db->query( "DELETE FROM ezcontentobject_version
                          WHERE contentobject_id='$this->ID' AND version>'$version'" );

        $db->query( "DELETE FROM eznode_assignment
                          WHERE contentobject_id='$this->ID' AND contentobject_version > '$version'" );

        $this->CurrentVersion = $version;
        $this->store();
        $db->commit();
    }

    /*!
     Copies the given version of the object and creates a new current version.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function copyRevertTo( $version, $language = false )
    {
        $versionObject = $this->createNewVersionIn( $language, false, $version );

        return $versionObject->attribute( 'version' );
    }

    static function fixReverseRelations( $objectID, $mode = false )
    {
        $db = eZDB::instance();
        $objectID = (int) $objectID;

        // Finds all the attributes that store relations to the given object.
        $result = $db->arrayQuery( "SELECT attr.*
                                    FROM ezcontentobject_link link,
                                         ezcontentobject_attribute attr
                                    WHERE link.from_contentobject_id=attr.contentobject_id AND
                                          link.from_contentobject_version=attr.version AND
                                          link.contentclassattribute_id=attr.contentclassattribute_id AND
                                          link.to_contentobject_id=$objectID" );
        if ( count( $result ) > 0 )
        {
            foreach( $result as $row )
            {
                $attr = new eZContentObjectAttribute( $row );
                $dataType = $attr->dataType();
                $dataType->fixRelatedObjectItem( $attr, $objectID, $mode );
                eZContentCacheManager::clearObjectViewCache( $attr->attribute( 'contentobject_id' ), true );
            }
        }
    }

    function removeReverseRelations( $objectID )
    {
        $db = eZDB::instance();
        $objectID = (int) $objectID;
        // Get list of objects referring to this one.
        $relatingObjects = $this->reverseRelatedObjectList( false, 0, false, array( 'AllRelations' => true ) );

        // Finds all the attributes that store relations to the given object.

        $result = $db->arrayQuery( "SELECT attr.*
                                    FROM ezcontentobject_link link,
                                         ezcontentobject_attribute attr
                                    WHERE link.from_contentobject_id=attr.contentobject_id AND
                                          link.from_contentobject_version=attr.version AND
                                          link.contentclassattribute_id=attr.contentclassattribute_id AND
                                          link.to_contentobject_id=$objectID" );

        // Remove references from XML.
        if ( count( $result ) > 0 )
        {
            foreach( $result as $row )
            {
                $attr = new eZContentObjectAttribute( $row );
                $dataType = $attr->dataType();
                $dataType->removeRelatedObjectItem( $attr, $objectID );
                eZContentCacheManager::clearObjectViewCache( $attr->attribute( 'contentobject_id' ), true );
                $attr->storeData();
            }
        }

        // Remove references in ezcontentobject_link.
        foreach ( $relatingObjects as $fromObject )
        {
            $fromObject->removeContentObjectRelation( $this->attribute( 'id' ), false, false );
        }
    }

    /*!
      If nodeID is not given, this function will remove object from database. All versions and translations of this object will be lost.
      Otherwise, it will check node assignment and only delete the object from this node if it was assigned to other nodes as well.
      \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function purge()
    {
        $delID = $this->ID;
        // Who deletes which content should be logged.
        eZAudit::writeAudit( 'content-delete', array( 'Object ID' => $delID, 'Content Name' => $this->attribute( 'name' ),
                                                      'Comment' => 'Purged the current object: eZContentObject::purge()' ) );

        $db = eZDB::instance();

        $db->begin();

        $contentobjectAttributes = $this->allContentObjectAttributes( $delID );

        foreach ( $contentobjectAttributes as $contentobjectAttribute )
        {
            $dataType = $contentobjectAttribute->dataType();
            if ( !$dataType )
                continue;
            $dataType->deleteStoredObjectAttribute( $contentobjectAttribute );
        }

        eZInformationCollection::removeContentObject( $delID );

        eZContentObjectTrashNode::purgeForObject( $delID );

        $db->query( "DELETE FROM ezcontentobject_tree
             WHERE contentobject_id='$delID'" );

        $db->query( "DELETE FROM ezcontentobject_attribute
             WHERE contentobject_id='$delID'" );

        $db->query( "DELETE FROM ezcontentobject_version
             WHERE contentobject_id='$delID'" );

        $db->query( "DELETE FROM ezcontentobject_name
             WHERE contentobject_id='$delID'" );

        $db->query( "DELETE FROM ezcobj_state_link WHERE contentobject_id=$delID" );

        $db->query( "DELETE FROM ezcontentobject
             WHERE id='$delID'" );

        $db->query( "DELETE FROM eznode_assignment
             WHERE contentobject_id = '$delID'" );

        $db->query( "DELETE FROM ezuser_role
             WHERE contentobject_id = '$delID'" );

        $db->query( "DELETE FROM ezuser_discountrule
             WHERE contentobject_id = '$delID'" );

        eZContentObject::fixReverseRelations( $delID, 'remove' );

        eZSearch::removeObject( $this );

        // Check if deleted object is in basket/wishlist
        $sql = 'SELECT DISTINCT ezproductcollection_item.productcollection_id
                FROM   ezbasket, ezwishlist, ezproductcollection_item
                WHERE  ( ezproductcollection_item.productcollection_id=ezbasket.productcollection_id OR
                         ezproductcollection_item.productcollection_id=ezwishlist.productcollection_id ) AND
                       ezproductcollection_item.contentobject_id=' . $delID;
        $rows = $db->arrayQuery( $sql );
        if ( count( $rows ) > 0 )
        {
            $countElements = 50;
            $deletedArray = array();
            // Create array of productCollectionID will be removed from ezwishlist and ezproductcollection_item
            foreach ( $rows as $row )
            {
                $deletedArray[] = $row['productcollection_id'];
            }
            // Split $deletedArray into several arrays with $countElements values
            $splitted = array_chunk( $deletedArray, $countElements );
            // Remove eZProductCollectionItem and eZWishList
            foreach ( $splitted as $value )
            {
                eZPersistentObject::removeObject( eZProductCollectionItem::definition(), array( 'productcollection_id' => array( $value, '' ) ) );
                eZPersistentObject::removeObject( eZWishList::definition(), array( 'productcollection_id' => array( $value, '' ) ) );
            }
        }
        $db->query( 'UPDATE ezproductcollection_item
                     SET contentobject_id = 0
                     WHERE  contentobject_id = ' . $delID );

        // Cleanup relations in two steps to avoid locking table for to long
        $db->query( "DELETE FROM ezcontentobject_link
                     WHERE from_contentobject_id = '$delID'" );

        $db->query( "DELETE FROM ezcontentobject_link
                     WHERE to_contentobject_id = '$delID'" );

        // Cleanup properties: LastVisit, Creator, Owner
        $db->query( "DELETE FROM ezuservisit
             WHERE user_id = '$delID'" );

        $db->query( "UPDATE ezcontentobject_version
             SET creator_id = 0
             WHERE creator_id = '$delID'" );

        $db->query( "UPDATE ezcontentobject
             SET owner_id = 0
             WHERE owner_id = '$delID'" );

        if ( isset( $GLOBALS["eZWorkflowTypeObjects"] ) and is_array( $GLOBALS["eZWorkflowTypeObjects"] ) )
        {
            $registeredTypes =& $GLOBALS["eZWorkflowTypeObjects"];
        }
        else
        {
            $registeredTypes = eZWorkflowType::fetchRegisteredTypes();
        }

        // Cleanup ezworkflow_event etc...
        foreach ( array_keys( $registeredTypes ) as $registeredTypeKey )
        {
            $registeredType = $registeredTypes[$registeredTypeKey];
            $registeredType->cleanupAfterRemoving( array( 'DeleteContentObject' => $delID ) );
        }

        $db->commit();
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeThis( $nodeID = null )
    {
        $delID = $this->ID;

        // Who deletes which content should be logged.
        eZAudit::writeAudit( 'content-delete', array( 'Object ID' => $delID, 'Content Name' => $this->attribute( 'name' ),
                                                      'Comment' => 'Setted archived status for the current object: eZContentObject::remove()' ) );

        $nodes = $this->attribute( 'assigned_nodes' );

        if ( $nodeID === null or count( $nodes ) <= 1 )
        {
            $db = eZDB::instance();
            $db->begin();
            $mainNodeKey = false;
            foreach ( $nodes as $key => $node )
            {
                if ( $node->attribute( 'main_node_id' ) == $node->attribute( 'node_id' ) )
                {
                    $mainNodeKey = $key;
                }
                else
                {
                    $node->removeThis();
                }
            }

            if ( $mainNodeKey !== false )
            {
                $nodes[$mainNodeKey]->removeNodeFromTree( true );
            }


            $this->setAttribute( 'status', eZContentObject::STATUS_ARCHIVED );
            eZSearch::removeObject( $this );
            $this->store();
            eZContentObject::fixReverseRelations( $delID, 'trash' );
            // Delete stored attribute from other tables
            $db->commit();

        }
        else if ( $nodeID !== null )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID , false );
            if ( is_object( $node ) )
            {
                if ( $node->attribute( 'main_node_id' ) == $nodeID )
                {
                    $db = eZDB::instance();
                    $db->begin();
                    foreach ( $additionalNodes as $additionalNode )
                    {
                        if ( $additionalNode->attribute( 'node_id' ) != $node->attribute( 'main_node_id' ) )
                        {
                            $additionalNode->remove();
                        }
                    }

                    $node->removeNodeFromTree( true );
                    $this->setAttribute( 'status', eZContentObject::STATUS_ARCHIVED );
                    eZSearch::removeObject( $this );
                    $this->store();
                    eZContentObject::fixReverseRelations( $delID, 'trash' );
                    $db->commit();
                }
                else
                {
                    eZContentObjectTreeNode::removeNode( $nodeID );
                }
            }
        }
        else
        {
            eZContentObjectTreeNode::removeNode( $nodeID );
        }
    }

    /*!
     Removes old internal drafts by the specified user associated with this content object.
     Only internal drafts older than 1 day will be considered.
     \param $userID The ID of the user to cleanup for, if \c false it will use the current user.
     */
    function cleanupInternalDrafts( $userID = false, $timeDuration = 86400 ) // default time duration for internal drafts 60*60*24 seconds (1 day)
    {
        if ( !is_numeric( $timeDuration ) ||
             $timeDuration < 0 )
        {
            eZDebug::writeError( "The time duration must be a positive numeric value (timeDuration = $timeDuration)",
                                 'eZContentObject::cleanupInternalDrafts()' );
            return;
        }

        if ( $userID === false )
        {
            $userID = eZUser::currentUserID();
        }
        // Fetch all draft/temporary versions by specified user
        $parameters = array( 'conditions' => array( 'status' => eZContentObjectVersion::STATUS_INTERNAL_DRAFT,
                                                    'creator_id' => $userID ) );
        // Remove temporary drafts which are old.
        $expiryTime = time() - $timeDuration; // only remove drafts older than time duration (default is 1 day)
        foreach ( $this->versions( true, $parameters ) as $possibleVersion )
        {
            if ( $possibleVersion->attribute( 'modified' ) < $expiryTime )
            {
                $possibleVersion->removeThis();
            }
        }
    }

    /*!
     \static
     Removes all old internal drafts by the specified user.
     Only internal drafts older than 1 day will be considered.
     \param $userID The ID of the user to cleanup for, if \c false it will use the current user.
     */
    static function cleanupAllInternalDrafts( $userID = false, $timeDuration = 86400 ) // default time duration for internal drafts 60*60*24 seconds (1 day)
    {
        if ( !is_numeric( $timeDuration ) ||
             $timeDuration < 0 )
        {
            eZDebug::writeError( "The time duration must be a positive numeric value (timeDuration = $timeDuration)",
                                 'eZContentObject::cleanupAllInternalDrafts()' );
            return;
        }


        if ( $userID === false )
        {
            $userID = eZUser::currentUserID();
        }
        // Remove all internal drafts
        // include_once( 'kernel/classes/ezcontentobjectversion.php' );
        $untouchedDrafts = eZContentObjectVersion::fetchForUser( $userID, eZContentObjectVersion::STATUS_INTERNAL_DRAFT );

        $expiryTime = time() - $timeDuration; // only remove drafts older than time duration (default is 1 day)
        foreach ( $untouchedDrafts as $untouchedDraft )
        {
            if ( $untouchedDraft->attribute( 'modified' ) < $expiryTime )
            {
                $untouchedDraft->removeThis();
            }
        }
    }

    /*
     Fetch all attributes of all versions belongs to a contentObject.
    */
    function allContentObjectAttributes( $contentObjectID, $asObject = true )
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
      TODO: fix using of $asObject parameter,
            fix condition for getting attribute from cache,
            probably need to move method to eZContentObjectVersion class
    */
    function contentObjectAttributes( $asObject = true, $version = false, $language = false, $contentObjectAttributeID = false, $distinctItemsOnly = false )
    {
        $db = eZDB::instance();
        if ( $version == false )
        {
            $version = $this->CurrentVersion;
        }
        else
        {
            $version = (int) $version;
        }

        if ( $language === false )
        {
            $language = $this->CurrentLanguage;
        }

        if ( is_string( $language ) )
            $language = $db->escapeString( $language );

        if ( $contentObjectAttributeID !== false )
            $contentObjectAttributeID =(int) $contentObjectAttributeID;
//         print( "Attributes fetch $this->ID, $version" );

        if ( !$language || !isset( $this->ContentObjectAttributes[$version][$language] ) )
        {
//             print( "uncached<br>" );
            $versionText = "AND\n                    ezcontentobject_attribute.version = '$version'";
            if ( $language )
            {
                $languageText = "AND\n                    ezcontentobject_attribute.language_code = '$language'";
            }
            else
            {
                $languageText = "AND\n                    ".eZContentLanguage::sqlFilter( 'ezcontentobject_attribute', 'ezcontentobject_version' );
            }
            $attributeIDText = false;
            if ( $contentObjectAttributeID )
                $attributeIDText = "AND\n                    ezcontentobject_attribute.id = '$contentObjectAttributeID'";
            $distinctText = false;
            if ( $distinctItemsOnly )
                $distinctText = "GROUP BY ezcontentobject_attribute.id";
            $query = "SELECT ezcontentobject_attribute.*, ezcontentclass_attribute.identifier as identifier FROM
                    ezcontentobject_attribute, ezcontentclass_attribute, ezcontentobject_version
                  WHERE
                    ezcontentclass_attribute.version = '0' AND
                    ezcontentclass_attribute.id = ezcontentobject_attribute.contentclassattribute_id AND
                    ezcontentobject_version.contentobject_id = '$this->ID' AND
                    ezcontentobject_version.version = '$version' AND
                    ezcontentobject_attribute.contentobject_id = '$this->ID' $versionText $languageText $attributeIDText
                  $distinctText
                  ORDER BY
                    ezcontentclass_attribute.placement ASC,
                    ezcontentobject_attribute.language_code ASC";

            $attributeArray = $db->arrayQuery( $query );

            if ( !$language && $attributeArray )
            {
                $language = $attributeArray[0]['language_code'];
                $this->CurrentLanguage = $language;
            }

            $returnAttributeArray = array();
            foreach ( $attributeArray as $attribute )
            {
                $attr = new eZContentObjectAttribute( $attribute );
                $attr->setContentClassAttributeIdentifier( $attribute['identifier'] );
                $returnAttributeArray[] = $attr;
            }

            if ( $language !== null and $version !== null )
            {
                $this->ContentObjectAttributes[$version][$language] = $returnAttributeArray;
            }
        }
        else
        {
//             print( "Cached<br>" );
            $returnAttributeArray = $this->ContentObjectAttributes[$version][$language];
        }

        return $returnAttributeArray;
    }

    /*!
     Initializes the cached copy of the content object attributes for the given version and language
    */
    function setContentObjectAttributes( &$attributes, $version, $language )
    {
        $this->ContentObjectAttributes[$version][$language] = $attributes;
    }

    /*!
      \static
      Fetches the attributes for an array of objects. The objList parameter
      contains an array of objects ( instanceOf eZContentObject or a object that is or
      extends eZContentObjectTreeNode ) to fetch attributes from.
    */
    static function fillNodeListAttributes( $objList, $asObject = true )
    {
        $db = eZDB::instance();

        if ( count( $objList ) > 0 )
        {
            $objectArray = array();
            $tmpLanguageObjectList = array();
            $whereSQL = '';
            $count = count( $objList );
            $i = 0;
            foreach ( $objList as $obj )
            {
                if ( $obj instanceOf eZContentObject )
                    $object = $obj;
                else
                    $object = $obj->attribute( 'object' );

                $language = $object->currentLanguage();
                $tmpLanguageObjectList[$object->attribute( 'id' )] = $language;
                $objectArray = array( 'id' => $object->attribute( 'id' ),
                                      'language' => $language,
                                      'version' => $object->attribute( 'current_version' ) );

                $whereSQL .= "( ezcontentobject_attribute.version = '" . $object->attribute( 'current_version' ) . "' AND
                    ezcontentobject_attribute.contentobject_id = '" . $object->attribute( 'id' ) . "' AND
                    ezcontentobject_attribute.language_code = '" . $language . "' ) ";

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

            $attributeArray = $db->arrayQuery( $query );

            $tmpAttributeObjectList = array();
            $returnAttributeArray = array();
            foreach ( $attributeArray as $attribute )
            {
                $attr = new eZContentObjectAttribute( $attribute );
                $attr->setContentClassAttributeIdentifier( $attribute['identifier'] );

                $tmpAttributeObjectList[$attr->attribute( 'contentobject_id' )][] = $attr;
            }

            foreach ( $objList as $obj )
            {
                if ( $obj instanceOf eZContentObject )
                {
                    $obj->setContentObjectAttributes( $tmpAttributeObjectList[$obj->attribute( 'id' )],
                                         $obj->attribute( 'current_version' ),
                                         $tmpLanguageObjectList[$obj->attribute( 'id' )] );
                }
                else
                {
                    $object = $obj->attribute( 'object' );
                    $object->setContentObjectAttributes( $tmpAttributeObjectList[$object->attribute( 'id' )],
                                                         $object->attribute( 'current_version' ),
                                                         $tmpLanguageObjectList[$object->attribute( 'id' )] );
                    $obj->setContentObject( $object );
                }
            }
        }
    }

    function resetInputRelationList()
    {
        $this->InputRelationList = array( eZContentObject::RELATION_EMBED => array(),
                                          eZContentObject::RELATION_LINK =>  array() );
    }

    function appendInputRelationList( $addingIDList, $relationType )
    {
        if ( !is_array( $addingIDList ) )
        {
            $addingIDList = array( ( int ) $addingIDList );
        }
        elseif ( !count( $addingIDList ) )
        {
            return;
        }
        $relationType = ( int ) $relationType;
        if ( !$this->InputRelationList )
        {
            $this->resetInputRelationList();
        }

        foreach ( array_keys( $this->InputRelationList ) as $inputRelationType )
        {
            if ( $inputRelationType & $relationType )
            {
                $this->InputRelationList[$inputRelationType] = array_merge( $this->InputRelationList[$inputRelationType], $addingIDList );
            }
        }
    }

    function commitInputRelations( $editVersion )
    {
        foreach ( $this->InputRelationList as $relationType => $relatedObjectIDArray )
        {
            $oldRelatedObjectArray = $this->relatedObjects( $editVersion, false, 0, false, array( 'AllRelations' => $relationType ) );

            foreach ( $oldRelatedObjectArray as $oldRelatedObject )
            {
                $oldRelatedObjectID = $oldRelatedObject->ID;
                if ( !in_array( $oldRelatedObjectID, $relatedObjectIDArray ) )
                {
                    $this->removeContentObjectRelation( $oldRelatedObjectID, $editVersion, 0, $relationType );
                }
                $relatedObjectIDArray = array_diff( $relatedObjectIDArray, array( $oldRelatedObjectID ) );
            }

            foreach ( $relatedObjectIDArray as $relatedObjectID )
            {
                $this->addContentObjectRelation( $relatedObjectID, $editVersion, 0, $relationType );
            }
        }
        return true;
    }

    function validateInput( $contentObjectAttributes, $attributeDataBaseName,
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
        $http = eZHTTPTool::instance();

        $this->resetInputRelationList();

        $editVersion = null;
        $defaultLanguage = $this->initialLanguageCode();
        foreach( $contentObjectAttributes as $contentObjectAttribute )
        {
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
            $editVersion = $contentObjectAttribute->attribute('version');

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
                $status = eZInputValidator::STATE_ACCEPTED;
            }
            else
            {
                $status = $contentObjectAttribute->validateInput( $http, $attributeDataBaseName,
                                                                  $inputParameters, $parameters );
            }
            $statusMap[$contentObjectAttribute->attribute( 'id' )] = array( 'value' => $status,
                                                                            'attribute' => $contentObjectAttribute );

            if ( $status == eZInputValidator::STATE_INTERMEDIATE )
                $requireFixup = true;
            else if ( $status == eZInputValidator::STATE_INVALID )
            {
                $inputValidated = false;
                $dataType = $contentObjectAttribute->dataType();
                $attributeName = $dataType->attribute( 'information' );
                $attributeName = $attributeName['name'];
                $description = $contentObjectAttribute->attribute( 'validation_error' );
                $validationNameArray[] = $contentClassAttribute->attribute( 'name' );
                $validationName = implode( '->', $validationNameArray );
                $hasValidationError = $contentObjectAttribute->attribute( 'has_validation_error' );
                if ( $hasValidationError )
                {
                    if ( !$description )
                        $description = false;
                    $validationNameArray = array();
                    if ( $parameters['prefix-name'] )
                        $validationNameArray = $parameters['prefix-name'];
                }
                else
                {
                    if ( !$description )
                        $description = 'unknown error';
                }
                $unvalidatedAttributes[] = array( 'id' => $contentObjectAttribute->attribute( 'id' ),
                                                  'identifier' => $contentClassAttribute->attribute( 'identifier' ),
                                                  'name' => $validationName,
                                                  'description' => $description );
            }
            else if ( $status == eZInputValidator::STATE_ACCEPTED )
            {
                $dataType = $contentObjectAttribute->dataType();
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

        if ( $editVersion !== null )
        {
            $this->commitInputRelations( $editVersion );
        }
        $this->resetInputRelationList();

        return $result;
    }

    function fixupInput( $contentObjectAttributes, $attributeDataBaseName )
    {
        $http = eZHTTPTool::instance();
        foreach ( $contentObjectAttributes as $contentObjectAttribute )
        {
            $contentObjectAttribute->fixupInput( $http, $attributeDataBaseName );
        }
    }

    function fetchInput( $contentObjectAttributes, $attributeDataBaseName,
                         $customActionAttributeArray, $customActionParameters )
    {
        // Global variable to cache datamaps
        global $eZContentObjectDataMapCache;

        $result = array( 'attribute-input-map' => array() );
        $attributeInputMap =& $result['attribute-input-map'];
        $http = eZHTTPTool::instance();

        $defaultLanguage = $this->initialLanguageCode();

        $this->fetchDataMap();
        foreach ( $contentObjectAttributes as $contentObjectAttribute )
        {
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();

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
                    $attributeInputMap[$contentObjectAttribute->attribute('id')] = true;

                    // we fill the internal data map cache for the current version here with the attributes of the new version
                    // this will make the data map cache inconsistent, but this is required to make it possible to use $object.data_map
                    // in content/edit templates
                    $attributeIdentifier = $contentObjectAttribute->attribute( 'contentclass_attribute_identifier' );
                    $eZContentObjectDataMapCache[$this->ID][$this->CurrentVersion][$currentLanguage][$attributeIdentifier] = $contentObjectAttribute;
                    $this->DataMap[$this->CurrentVersion][$currentLanguage][$attributeIdentifier] = $contentObjectAttribute;
                }

                // Custom Action Code
                $this->handleCustomHTTPActions( $contentObjectAttribute, $attributeDataBaseName,
                                                $customActionAttributeArray, $customActionParameters );
            }

        }
        return $result;
    }

    function handleCustomHTTPActions( $contentObjectAttribute, $attributeDataBaseName,
                                      $customActionAttributeArray, $customActionParameters )
    {
        $http = eZHTTPTool::instance();
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
        $http = eZHTTPTool::instance();
        $contentObjectAttributes = $this->contentObjectAttributes( true, $objectVersion );
        $oldAttributeDataBaseName = $customActionParameters['base_name'];
        $customActionParameters['base_name'] = $attributeDataBaseName;
        foreach( $contentObjectAttributes as $contentObjectAttribute )
        {
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

    static function recursionProtectionStart()
    {
        $GLOBALS["ez_content_object_recursion_protect"] = array();
    }

    static function recursionProtect( $id )
    {
        if ( isset( $GLOBALS["ez_content_object_recursion_protect"][$id] ) )
        {
            return false;
        }
        else
        {
             $GLOBALS["ez_content_object_recursion_protect"][$id] = true;
             return true;
        }
    }

    static function recursionProtectionEnd()
    {
        unset( $GLOBALS["ez_content_object_recursion_protect"] );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function storeInput( $contentObjectAttributes,
                         $attributeInputMap )
    {
        $db = eZDB::instance();
        $db->begin();
        foreach ( $contentObjectAttributes as $contentObjectAttribute )
        {
            if ( isset( $attributeInputMap[$contentObjectAttribute->attribute('id')] ) )
            {
                $contentObjectAttribute->store();
            }
        }
        $db->commit();
        unset( $this->ContentObjectAttributes );
    }

    /*!
     Returns the next available version number for this object.
    */
    function nextVersion()
    {
        $db = eZDB::instance();
        $versions = $db->arrayQuery( "SELECT ( MAX( version ) + 1 ) AS next_id FROM ezcontentobject_version
                       WHERE contentobject_id='$this->ID'" );
        return $versions[0]["next_id"];

    }

    /*!
     Returns the previous available version number for this object, if existing, false otherwise ( if the object has only one version )
    */
    function previousVersion()
    {
        $db = eZDB::instance();
        $versions = $db->arrayQuery( "SELECT version FROM ezcontentobject_version
                                      WHERE contentobject_id='$this->ID'
                                      ORDER BY version DESC", array( 'limit' => 2 ) );
        if ( count( $versions ) > 1 and isset( $versions[1]['version'] ) )
        {
            return $versions[1]['version'];
        }
        else
        {
            return false;
        }
    }

    /*!
     Returns number of exist versions.
    */
    function getVersionCount()
    {
        $db = eZDB::instance();
        $versionCount = $db->arrayQuery( "SELECT ( COUNT( version ) ) AS version_count FROM ezcontentobject_version
                       WHERE contentobject_id='$this->ID'" );
        return $versionCount[0]["version_count"];

    }

    function currentLanguage()
    {
        return $this->CurrentLanguage;
    }

    function currentLanguageObject()
    {
        if ( $this->CurrentLanguage )
        {
            $language = eZContentLanguage::fetchByLocale( $this->CurrentLanguage );
        }
        else
        {
            $language = false;
        }

        return $language;
    }

    function setCurrentLanguage( $lang )
    {
        $this->CurrentLanguage = $lang;
        $this->Name = null;
    }

    function initialLanguage()
    {
        return isset( $this->InitialLanguageID ) ? eZContentLanguage::fetch( $this->InitialLanguageID ) : false;
    }

    function initialLanguageCode()
    {
        $initialLanguage = $this->initialLanguage();
        // If current contentobject is "Top Level Nodes" than it doesn't have "initial Language" and "locale".
        return ( $initialLanguage !== false ) ?  $initialLanguage->attribute( 'locale' ) : false;
    }

    /*!
     Adds a new location (node) to the current object.
     \param $parenNodeID The id of the node to use as parent.
     \param $asObject    If true it will return the new child-node as an object, if not it returns the ID.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
      the calls within a db transaction; thus within db->begin and db->commit.
      */
    function addLocation( $parentNodeID, $asObject = false )
    {
        $node = eZContentObjectTreeNode::addChildTo( $this->ID, $parentNodeID, true, $this->CurrentVersion );

        $data = array( 'contentobject_id' => $this->ID,
                       'contentobject_version' => $this->attribute( 'current_version' ),
                       'parent_node' => $parentNodeID,
                       'is_main' => 0 );
        $nodeAssignment = eZNodeAssignment::create( $data );
        $nodeAssignment->setAttribute( 'op_code', eZNodeAssignment::OP_CODE_CREATE_NOP );
        $nodeAssignment->store();

        if ( $asObject )
        {
            return $node;
        }
        else
        {
            return $node->attribute( 'node_id' );
        }
    }

    /*!
     Adds a link to the given content object id.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function addContentObjectRelation( $toObjectID,
                                       $fromObjectVersion = false,
                                       $attributeID = 0,
                                       $relationType = eZContentObject::RELATION_COMMON )
    {
        if ( $attributeID !== 0 )
        {
            $relationType = eZContentObject::RELATION_ATTRIBUTE;
        }

        $relationType =(int) $relationType;
        if ( ( $relationType & eZContentObject::RELATION_ATTRIBUTE ) != 0 &&
             $relationType != eZContentObject::RELATION_ATTRIBUTE )
        {
            eZDebug::writeWarning( "Object relation type conflict", "eZContentObject::addContentObjectRelation");
        }

        $db = eZDB::instance();

        if ( !$fromObjectVersion )
            $fromObjectVersion = $this->CurrentVersion;

        $fromObjectID = $this->ID;

        if ( !is_numeric( $toObjectID ) )
        {
            eZDebug::writeError( "Related object ID (toObjectID): '$toObjectID', is not a numeric value.",
                                 "eZContentObject::addContentObjectRelation" );
            return false;
        }
        $fromObjectID =(int) $fromObjectID;
        $attributeID =(int) $attributeID;
        $fromObjectVersion =(int) $fromObjectVersion;
        $relationBaseType = ( $relationType & eZContentObject::RELATION_ATTRIBUTE ) ?
                                eZContentObject::RELATION_ATTRIBUTE :
                                eZContentObject::RELATION_COMMON | eZContentObject::RELATION_EMBED | eZContentObject::RELATION_LINK;
        $relationTypeMatch = $db->bitAnd( 'relation_type', $relationBaseType );
        $query = "SELECT count(*) AS count
                  FROM   ezcontentobject_link
                  WHERE  from_contentobject_id=$fromObjectID AND
                         from_contentobject_version=$fromObjectVersion AND
                         to_contentobject_id=$toObjectID AND
                         $relationTypeMatch != 0 AND
                         contentclassattribute_id=$attributeID AND
                         op_code='0'";
        $count = $db->arrayQuery( $query );
        // if current relation does not exist
        if ( !isset( $count[0]['count'] ) ||  $count[0]['count'] == '0'  )
        {
            $db->begin();
            $db->query( "INSERT INTO ezcontentobject_link ( from_contentobject_id, from_contentobject_version, to_contentobject_id, contentclassattribute_id, relation_type )
                         VALUES ( $fromObjectID, $fromObjectVersion, $toObjectID, $attributeID, $relationType )" );
            // if an object relation is being added and it is in draft, add the row with op_code 1
            if ( $attributeID == 0 && $fromObjectVersion != $this->CurrentVersion )
            {
                $db->query( "INSERT INTO ezcontentobject_link ( from_contentobject_id, from_contentobject_version, to_contentobject_id, contentclassattribute_id, op_code, relation_type )
                             VALUES ( $fromObjectID, $fromObjectVersion, $toObjectID, $attributeID, '1', $relationType )" );
            }
            $db->commit();
        }
        elseif ( isset( $count[0]['count'] ) &&
                 $count[0]['count'] != '0' &&
                 $attributeID == 0 &&
                 (eZContentObject::RELATION_ATTRIBUTE & $relationType) == 0 )
        {
            $db->begin();
            $newRelationType = $db->bitOr( 'relation_type', $relationType );
            $db->query( "UPDATE ezcontentobject_link
                         SET    relation_type = $newRelationType
                         WHERE  from_contentobject_id=$fromObjectID AND
                                from_contentobject_version=$fromObjectVersion AND
                                to_contentobject_id=$toObjectID AND
                                contentclassattribute_id=$attributeID AND
                                op_code='0'" );
            // if an object relation is being added and it is in draft, add the row with op_code 1
            if ( $attributeID == 0 && $fromObjectVersion != $this->CurrentVersion )
            {
                $db->query( "INSERT INTO ezcontentobject_link ( from_contentobject_id, from_contentobject_version, to_contentobject_id, contentclassattribute_id, op_code, relation_type )
                             VALUES ( $fromObjectID, $fromObjectVersion, $toObjectID, $attributeID, '1', $relationType )" );
            }
            $db->commit();
        }
    }

    /*!
     Removes a link to the given content object id.
     \param $toObjectID If \c false it will delete relations to all the objects.
     \param $attributeID ID of class attribute.
                         IF it is > 0 we remove relations created by a specific objectrelation[list] attribute.
                         If it is set to 0 we remove relations created without using of objectrelation[list] attribute.
                         If it is set to false, we remove all relations, no matter how were they created:
                         using objectrelation[list] attribute or using "Add related objects" functionality in obect editing mode.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function removeContentObjectRelation( $toObjectID = false, $fromObjectVersion = false, $attributeID = 0, $relationType = eZContentObject::RELATION_COMMON )
    {
        $db = eZDB::instance();

        if ( !$fromObjectVersion )
            $fromObjectVersion = $this->CurrentVersion;
        $fromObjectVersion = (int) $fromObjectVersion;
        $fromObjectID = $this->ID;

        if ( $toObjectID !== false )
        {
            $toObjectID =(int) $toObjectID;
            $toObjectCondition = "AND to_contentobject_id=$toObjectID";
        }
        else
            $toObjectCondition = '';

        if ( $attributeID !== false )
        {
            $attributeID =(int) $attributeID;
            $classAttributeCondition = "AND contentclassattribute_id=$attributeID";
        }
        else
            $classAttributeCondition = '';

        $lastRelationType = 0;
        $db->begin();
        // if an object relation is being removed from the draft, add the row with op_code -1
        if ( !$attributeID && $fromObjectVersion != $this->CurrentVersion )
        {
            $rows = $db->arrayQuery( "SELECT * FROM ezcontentobject_link
                                      WHERE from_contentobject_id=$fromObjectID
                                        AND from_contentobject_version=$fromObjectVersion
                                        AND contentclassattribute_id='0'
                                        $toObjectCondition
                                        AND op_code='0'" );
            foreach ( $rows as $row )
            {
                $db->query( "INSERT INTO ezcontentobject_link ( from_contentobject_id, from_contentobject_version, to_contentobject_id, contentclassattribute_id, op_code, relation_type )
                             VALUES ( $fromObjectID, $fromObjectVersion, " . $row['to_contentobject_id'] . ", '0', '-1', $relationType )" );
                $lastRelationType = (int) $row['relation_type'];
            }
        }

        if ( 0 !== ( eZContentObject::RELATION_ATTRIBUTE & $relationType ) ||
             0 != $attributeID ||
             $relationType == $lastRelationType )
        {
            $db->query( "DELETE FROM ezcontentobject_link
                         WHERE       from_contentobject_id=$fromObjectID AND
                                     from_contentobject_version=$fromObjectVersion $classAttributeCondition $toObjectCondition AND
                                     op_code='0'" );
        }
        else
        {
            if ( $db->databaseName() == 'oracle' )
            {
                $notRelationType = - ( $relationType + 1 );
                $db->query( "UPDATE ezcontentobject_link
                             SET    relation_type = " . $db->bitAnd( 'relation_type', $notRelationType ) . "
                             WHERE  from_contentobject_id=$fromObjectID AND
                                    from_contentobject_version=$fromObjectVersion $classAttributeCondition $toObjectCondition AND
                                    op_code='0'" );
            }
            else
            {
                $db->query( "UPDATE ezcontentobject_link
                             SET    relation_type = ( relation_type & ".(~$relationType)." )
                             WHERE  from_contentobject_id=$fromObjectID AND
                                    from_contentobject_version=$fromObjectVersion $classAttributeCondition $toObjectCondition AND
                                    op_code='0'" );
            }
        }

        $db->commit();

    }

    function copyContentObjectRelations( $currentVersion, $newVersion, $newObjectID = false )
    {
        $objectID = $this->ID;
        if ( !$newObjectID )
        {
            $newObjectID = $objectID;
        }

        $db = eZDB::instance();
        $db->begin();

        $relations = $db->arrayQuery( "SELECT to_contentobject_id, op_code, relation_type FROM ezcontentobject_link
                                       WHERE contentclassattribute_id='0'
                                         AND from_contentobject_id='$objectID'
                                         AND from_contentobject_version='$currentVersion'" );
        foreach ( $relations as $relation )
        {
            $toContentObjectID = $relation['to_contentobject_id'];
            $opCode = $relation['op_code'];
            $relationType = $relation['relation_type'];
            $db->query( "INSERT INTO ezcontentobject_link( contentclassattribute_id,
                                                           from_contentobject_id,
                                                           from_contentobject_version,
                                                           to_contentobject_id,
                                                           op_code,
                                                           relation_type )
                         VALUES ( '0', '$newObjectID', '$newVersion', '$toContentObjectID', '$opCode', '$relationType' )" );
        }

        $db->commit();
    }

    static function isObjectRelationTyped()
    {
        $siteIni = eZINI::instance( 'site.ini' );
        if ( $siteIni->hasVariable( 'BackwardCompatibilitySettings', 'ObjectRelationTyped' ) )
        {
            if ( 'enabled' == $siteIni->variable( 'BackwardCompatibilitySettings', 'ObjectRelationTyped' ) )
            {
                return true;
            }
        }
        return false;
    }

    static function relationTypeMask( $allRelations = false )
    {
        $relationTypeMask = eZContentObject::RELATION_COMMON |
                            eZContentObject::RELATION_EMBED;

        if ( eZContentObject::isObjectRelationTyped() )
        {
            $relationTypeMask |= eZContentObject::RELATION_LINK;
        }

        if ( $allRelations )
        {
            $relationTypeMask |= eZContentObject::RELATION_ATTRIBUTE;
        }

        return $relationTypeMask;
    }

    /*!
     Returns the related or reverse related objects:
     \param $attributeID :  ( makes sense only when $params['AllRelations'] not set or eZContentObject::RELATION_ATTRIBUTE )
                            >0              - return relations made with attribute ID ( "related object(s)" datatype )
                            0 or false  ( $params['AllRelations'] is eZContentObject::RELATION_ATTRIBUTE )
                                            - return relations made with any attributes
                            false       ( $params['AllRelations'] not set )
                                            - return ALL relations (deprecated, use "$params['AllRelations'] = true" instead)
     \param $groupByAttribute : false - return all relations as an array of content objects
                                true  - return all relations groupped by attribute ID
                                This parameter makes sense only when $attributeID == false or $params['AllRelations'] = true
     \param $params : other parameters from template fetch function :
                $params['AllRelations']     - relation type filter :
                            true    - return ALL relations, including attribute-level
                            false   - return object-level relations
                            >0      - bit mask of EZ_CONTENT_OBJECT_RELATION_* values
                $params['SortBy']           - related objects sorting mode.
                            Supported modes: class_identifier, class_name, modified, name, published, section
                $params['IgnoreVisibility'] - ignores 'hidden' state of related objects if true
     \param $reverseRelatedObjects : if "true" returns reverse related contentObjects
                                     if "false" returns related contentObjects
    */
    function relatedObjects( $fromObjectVersion = false,
                             $objectID = false,
                             $attributeID = 0,
                             $groupByAttribute = false,
                             $params = false,
                             $reverseRelatedObjects = false )
    {
        if ( $fromObjectVersion == false )
            $fromObjectVersion = isset( $this->CurrentVersion ) ? $this->CurrentVersion : false;
        $fromObjectVersion =(int) $fromObjectVersion;
        if( !$objectID )
            $objectID = $this->ID;
        $objectID =(int) $objectID;

        $limit            = ( isset( $params['Limit']  ) && is_numeric( $params['Limit']  ) ) ? $params['Limit']              : false;
        $offset           = ( isset( $params['Offset'] ) && is_numeric( $params['Offset'] ) ) ? $params['Offset']             : false;
        $asObject         = ( isset( $params['AsObject']          ) )                         ? $params['AsObject']           : true;
        $loadDataMap      = ( isset( $params['LoadDataMap'] ) )                               ? $params['LoadDataMap']        : false;


        $db = eZDB::instance();
        $sortingString = '';
        $sortingInfo = array( 'attributeFromSQL' => '',
                              'attributeWhereSQL' => '' );

        $showInvisibleNodesCond = '';
        // process params (only SortBy and IgnoreVisibility currently supported):
        // Supported sort_by modes:
        //   class_identifier, class_name, modified, name, published, section
        if ( is_array( $params ) )
        {
            if ( isset( $params['SortBy'] ) )
            {
                $sortingInfo = eZContentObjectTreeNode::createSortingSQLStrings( $params['SortBy'] );
                $sortingString = ' ORDER BY ' . $sortingInfo['sortingFields'];
            }
            if ( isset( $params['IgnoreVisibility'] ) )
            {
                $showInvisibleNodesCond = self::createFilterByVisibilitySQLString( $params['IgnoreVisibility'] );
            }
        }

        $relationTypeMasking = '';
        $relationTypeMask = isset( $params['AllRelations'] ) ? $params['AllRelations'] : ( $attributeID === false );
        if ( $attributeID && ( $relationTypeMask === false || $relationTypeMask === eZContentObject::RELATION_ATTRIBUTE ) )
        {
            $attributeID =(int) $attributeID;
            $relationTypeMasking .= " AND contentclassattribute_id=$attributeID ";
            $relationTypeMask = eZContentObject::RELATION_ATTRIBUTE;
        }
        elseif ( is_bool( $relationTypeMask ) )
        {
            $relationTypeMask = eZContentObject::relationTypeMask( $relationTypeMask );
        }

        if ( $db->databaseName() == 'oracle' )
        {
            $relationTypeMasking .= " AND bitand( relation_type, $relationTypeMask ) <> 0 ";
        }
        else
        {
            $relationTypeMasking .= " AND ( relation_type & $relationTypeMask ) <> 0 ";
        }

        // Create SQL
        $versionNameTables = ', ezcontentobject_name ';
        $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

        $versionNameJoins = " AND ezcontentobject.id = ezcontentobject_name.contentobject_id AND
                                 ezcontentobject.current_version = ezcontentobject_name.content_version AND ";
        $versionNameJoins .= eZContentLanguage::sqlFilter( 'ezcontentobject_name', 'ezcontentobject' );

        $fromOrToContentObjectID = $reverseRelatedObjects == false ? " AND ezcontentobject.id=ezcontentobject_link.to_contentobject_id AND
                                                                      ezcontentobject_link.from_contentobject_id='$objectID' AND
                                                                      ezcontentobject_link.from_contentobject_version='$fromObjectVersion' "
                                                                   : " AND ezcontentobject.id=ezcontentobject_link.from_contentobject_id AND
                                                                      ezcontentobject_link.to_contentobject_id=$objectID AND
                                                                      ezcontentobject_link.from_contentobject_version=ezcontentobject.current_version ";
            $query = "SELECT ";

            if ( $groupByAttribute )
            {
                $query .= "ezcontentobject_link.contentclassattribute_id, ";
            }
            $query .= "
                        ezcontentclass.serialized_name_list AS class_serialized_name_list,
                        ezcontentclass.identifier as contentclass_identifier,
                        ezcontentclass.is_container as is_container,
                        ezcontentobject.* $versionNameTargets
                     FROM
                        ezcontentclass,
                        ezcontentobject,
                        ezcontentobject_link
                        $versionNameTables
                        $sortingInfo[attributeFromSQL]
                     WHERE
                        ezcontentclass.id=ezcontentobject.contentclass_id AND
                        ezcontentclass.version=0 AND
                        ezcontentobject.status=" . eZContentObject::STATUS_PUBLISHED . " AND
                        $sortingInfo[attributeWhereSQL]
                        ezcontentobject_link.op_code='0'
                        $relationTypeMasking
                        $fromOrToContentObjectID
                        $showInvisibleNodesCond
                        $versionNameJoins
                        $sortingString";
        if ( !$offset && !$limit )
        {
            $relatedObjects = $db->arrayQuery( $query );
        }
        else
        {
            $relatedObjects = $db->arrayQuery( $query, array( 'offset' => $offset,
                                                             'limit'  => $limit ) );
        }

        $ret = array();
        $tmp = array();
        foreach ( $relatedObjects as $object )
        {
            if ( $asObject )
            {
                $obj = new eZContentObject( $object );
                $obj->ClassName = eZContentClass::nameFromSerializedString( $object['class_serialized_name_list'] );
            }
            else
            {
                $obj = $object;
            }

            $tmp[] = $obj;

            if ( !$groupByAttribute )
            {
                $ret[] = $obj;
            }
            else
            {
                $classAttrID = $object['contentclassattribute_id'];

                if ( !isset( $ret[$classAttrID] ) )
                    $ret[$classAttrID] = array();

                $ret[$classAttrID][] = $obj;
            }
        }
        if ( $loadDataMap && $asObject )
            eZContentObject::fillNodeListAttributes( $tmp );
        return $ret;
    }

    /*!
     Returns the related objects.
    \param $attributeID :  ( makes sense only when $params['AllRelations'] not set or eZContentObject::RELATION_ATTRIBUTE )
                           >0           - return relations made with attribute ID ( "related object(s)" datatype )
                           0 or false  ( $params['AllRelations'] is eZContentObject::RELATION_ATTRIBUTE )
                                           - return relations made with any attributes
                           false        ( $params['AllRelations'] not set )
                                           - return ALL relations (deprecated, use "$params['AllRelations'] = true" instead)
    \param $groupByAttribute : false - return all relations as an array of content objects
                               true  - return all relations groupped by attribute ID
                               This parameter makes sense only when $attributeID == false or $params['AllRelations'] = true
    \param $params : other parameters from template fetch function :
               $params['AllRelations'] - relation type filter :
                           true - return ALL relations, including attribute-level
                           false    - return object-level relations
                           >0       - bit mask of EZ_CONTENT_OBJECT_RELATION_* values
                $params['SortBy']           - related objects sorting mode.
                            Supported modes: class_identifier, class_name, modified, name, published, section
                $params['IgnoreVisibility'] - ignores 'hidden' state of related objects if true
    */
    function relatedContentObjectList( $fromObjectVersion = false,
                                       $fromObjectID = false,
                                       $attributeID = 0,
                                       $groupByAttribute = false,
                                       $params = false )
    {
        eZDebugSetting::writeDebug( 'kernel-content-object-related-objects', $fromObjectID, "objectID" );
        return $this->relatedObjects( $fromObjectVersion, $fromObjectID, $attributeID, $groupByAttribute, $params );
    }

    /*!
     Returns the xml-linked objects.
    */
    function linkedContentObjectList( $fromObjectVersion = false, $fromObjectID = false )
    {
        return $this->relatedObjects( $fromObjectVersion,
                                      $fromObjectID,
                                      0,
                                      false,
                                      array( 'AllRelations' => eZContentObject::RELATION_LINK ) );
    }

    /*!
     Returns the xml-embedded objects.
    */
    function embeddedContentObjectList( $fromObjectVersion = false, $fromObjectID = false )
    {
        return $this->relatedObjects( $fromObjectVersion,
                                      $fromObjectID,
                                      0,
                                      false,
                                      array( 'AllRelations' => eZContentObject::RELATION_EMBED ) );
    }

    /*!
     Returns the reverse xml-linked objects.
    */
    function reverseLinkedObjectList( $fromObjectVersion = false, $fromObjectID = false )
    {
        return $this->relatedObjects( $fromObjectVersion,
                                      $fromObjectID,
                                      0,
                                      false,
                                      array( 'AllRelations' => eZContentObject::RELATION_LINK ),
                                      true );
    }

    /*!
     Returns the reverse xml-embedded objects.
    */
    function reverseEmbeddedObjectList( $fromObjectVersion = false, $fromObjectID = false )
    {
        return $this->relatedObjects( $fromObjectVersion,
                                      $fromObjectID,
                                      0,
                                      false,
                                      array( 'AllRelations' => eZContentObject::RELATION_EMBED ),
                                      true );
    }

    // left for compatibility
    function relatedContentObjectArray( $fromObjectVersion = false,
                                        $fromObjectID = false,
                                        $attributeID = 0,
                                        $params = false )
    {
        return eZContentObject::relatedContentObjectList( $fromObjectVersion,
                                                          $fromObjectID,
                                                          $attributeID,
                                                          false,
                                                          $params );
    }

    /*!
     \return the number of related objects
    \param $attributeID :  ( makes sense only when $params['AllRelations'] not set or eZContentObject::RELATION_ATTRIBUTE )
                           >0           - return relations made with attribute ID ( "related object(s)" datatype )
                           0 or false  ( $params['AllRelations'] is eZContentObject::RELATION_ATTRIBUTE )
                                           - return relations made with any attributes
                           false        ( $params['AllRelations'] not set )
                                           - return ALL relations (deprecated, use "$params['AllRelations'] = true" instead)
    \param $params : other parameters from template fetch function :
               $params['AllRelations'] - relation type filter :
                           true - return ALL relations, including attribute-level
                           false    - return object-level relations
                           >0       - bit mask of EZ_CONTENT_OBJECT_RELATION_* values
                $params['SortBy']           - related objects sorting mode.
                            Supported modes: class_identifier, class_name, modified, name, published, section
                $params['IgnoreVisibility'] - ignores 'hidden' state of related objects if true
    */
    function relatedContentObjectCount( $fromObjectVersion = false,
                                        $attributeID = 0,
                                        $params = false )
    {
        eZDebugSetting::writeDebug( 'kernel-content-object-related-objects', $this->ID, "relatedContentObjectCount::objectID" );
        return $this->relatedObjectCount( $fromObjectVersion,
                                          $attributeID,
                                          false,
                                          $params );
    }

    /*!
     Returns the objects to which this object are related .
    \param $attributeID :  ( makes sense only when $params['AllRelations'] not set or eZContentObject::RELATION_ATTRIBUTE )
                           >0           - return relations made with attribute ID ( "related object(s)" datatype )
                           0 or false  ( $params['AllRelations'] is eZContentObject::RELATION_ATTRIBUTE )
                                           - return relations made with any attributes
                           false        ( $params['AllRelations'] not set )
                                           - return ALL relations (deprecated, use "$params['AllRelations'] = true" instead)
    \param $groupByAttribute : false - return all relations as an array of content objects
                               true  - return all relations groupped by attribute ID
                               This parameter makes sense only when $attributeID == false or $params['AllRelations'] = true
    \param $params : other parameters from template fetch function :
               $params['AllRelations'] - relation type filter :
                           true - return ALL relations, including attribute-level
                           false    - return object-level relations
                           >0       - bit mask of EZ_CONTENT_OBJECT_RELATION_* values
                $params['SortBy']           - related objects sorting mode.
                            Supported modes: class_identifier, class_name, modified, name, published, section
                $params['IgnoreVisibility'] - ignores 'hidden' state of related objects if true
    */
    function reverseRelatedObjectList( $version = false,
                                       $attributeID = 0,
                                       $groupByAttribute = false,
                                       $params = false )
    {
        return $this->relatedObjects( $version, $this->ID, $attributeID, $groupByAttribute, $params, true );
    }

    /*!
     Returns the xml-linked objects count.
    */
    function linkedContentObjectCount( $fromObjectVersion = false )
    {
        return $this->relatedObjectCount( $fromObjectVersion,
                                          0,
                                          false,
                                          array( 'AllRelations' => eZContentObject::RELATION_LINK ) );
    }

    /*!
     Returns the xml-embedded objects count.
    */
    function embeddedContentObjectCount( $fromObjectVersion = false )
    {
        return $this->relatedObjectCount( $fromObjectVersion,
                                          0,
                                          false,
                                          array( 'AllRelations' => eZContentObject::RELATION_EMBED ) );
    }

    /*!
     Returns the reverse xml-linked objects count.
    */
    function reverseLinkedObjectCount( $fromObjectVersion = false )
    {
        return $this->relatedObjectCount( $fromObjectVersion,
                                          0,
                                          true,
                                          array( 'AllRelations' => eZContentObject::RELATION_LINK ) );
    }

    /*!
     Returns the reverse xml-embedded objects count.
    */
    function reverseEmbeddedObjectCount( $fromObjectVersion = false )
    {
        return $this->relatedObjectCount( $fromObjectVersion,
                                          0,
                                          true,
                                          array( 'AllRelations' => eZContentObject::RELATION_EMBED ) );
    }

    /*!
     \return the number of related or reverse related objects
     \param $attributeID :  ( makes sense only when $params['AllRelations'] not set or eZContentObject::RELATION_ATTRIBUTE )
                            >0              - return relations made with attribute ID ( "related object(s)" datatype )
                            0 or false  ( $params['AllRelations'] is eZContentObject::RELATION_ATTRIBUTE )
                                            - return relations made with any attributes
                            false       ( $params['AllRelations'] not set )
                                            - return ALL relations (deprecated, use "$params['AllRelations'] = true" instead)
     \param $params : other parameters from template fetch function :
                $params['AllRelations'] - relation type filter :
                            true    - return ALL relations, including attribute-level
                            false   - return object-level relations
                            >0      - bit mask of EZ_CONTENT_OBJECT_RELATION_* values
                $params['SortBy']           - related objects sorting mode.
                            Supported modes: class_identifier, class_name, modified, name, published, section
                $params['IgnoreVisibility'] - ignores 'hidden' state of related objects if true
     \param $reverseRelatedObjects : if "true" returns reverse related contentObjects
                                     if "false" returns related contentObjects
    */
    function relatedObjectCount( $version = false, $attributeID = 0, $reverseRelatedObjects = false, $params = false )
    {
        $objectID = $this->ID;
        if ( $version == false )
            $version = isset( $this->CurrentVersion ) ? $this->CurrentVersion : false;
        $version == (int) $version;

        $db = eZDB::instance();
        $showInvisibleNodesCond = '';

        // process params (only IgnoreVisibility currently supported):
        if ( is_array( $params ) )
        {
            if ( isset( $params['IgnoreVisibility'] ) )
            {
                $showInvisibleNodesCond = self::createFilterByVisibilitySQLString( $params['IgnoreVisibility'] );
            }
        }

        $relationTypeMasking = '';
        $relationTypeMask = isset( $params['AllRelations'] ) ? $params['AllRelations'] : ( $attributeID === false );
        if ( $attributeID && ( $relationTypeMask === false || $relationTypeMask === eZContentObject::RELATION_ATTRIBUTE ) )
        {
            $attributeID =(int) $attributeID;
            $relationTypeMasking .= " AND contentclassattribute_id=$attributeID ";
            $relationTypeMask = eZContentObject::RELATION_ATTRIBUTE;
        }
        elseif ( is_bool( $relationTypeMask ) )
        {
            $relationTypeMask = eZContentObject::relationTypeMask( $relationTypeMask );
        }

        if ( $db->databaseName() == 'oracle' )
        {
            $relationTypeMasking .= " AND bitand( relation_type, $relationTypeMask ) <> 0 ";
        }
        else
        {
            $relationTypeMasking .= " AND ( relation_type & $relationTypeMask ) <> 0 ";
        }

        if ( $reverseRelatedObjects )
        {
            if ( is_array( $objectID ) )
            {
                if ( count( $objectID ) > 0 )
                {
                    $objectIDSQL = ' AND ezcontentobject_link.to_contentobject_id in (' . $db->implodeWithTypeCast( ', ', $objectID, 'int' ) . ') AND
                                    ezcontentobject_link.from_contentobject_version=ezcontentobject.current_version';
                }
                else
                {
                    $objectIDSQL = '';
                }
            }
            else
            {
                $objectID = (int) $objectID;
                $objectIDSQL = ' AND ezcontentobject_link.to_contentobject_id = ' .  $objectID . ' AND
                                ezcontentobject_link.from_contentobject_version=ezcontentobject.current_version';
            }
            $select = " count( DISTINCT ezcontentobject.id ) AS count";
        }
        else
        {
            $select = " count( ezcontentobject_link.from_contentobject_id ) as count ";
            $objectIDSQL = " AND ezcontentobject_link.from_contentobject_id='$objectID'
                                AND ezcontentobject_link.from_contentobject_version='$version'";
        }
        $query = "SELECT $select
                  FROM
                    ezcontentobject, ezcontentobject_link
                  WHERE
                    ezcontentobject.id=ezcontentobject_link.from_contentobject_id AND
                    ezcontentobject.status=" . eZContentObject::STATUS_PUBLISHED . " AND
                    ezcontentobject_link.op_code='0'
                    $objectIDSQL
                    $relationTypeMasking
                    $showInvisibleNodesCond";

        $rows = $db->arrayQuery( $query );
        return $rows[0]['count'];
    }

    /*!
     Returns the number of objects to which this object is related.
    \param $attributeID :  ( makes sense only when $params['AllRelations'] not set or eZContentObject::RELATION_ATTRIBUTE )
                           >0           - return relations made with attribute ID ( "related object(s)" datatype )
                           0 or false  ( $params['AllRelations'] is eZContentObject::RELATION_ATTRIBUTE )
                                           - return relations made with any attributes
                           false        ( $params['AllRelations'] not set )
                                           - return ALL relations (deprecated, use "$params['AllRelations'] = true" instead)
    \param $params : other parameters from template fetch function :
               $params['AllRelations'] - relation type filter :
                           true - return ALL relations, including attribute-level
                           false    - return object-level relations
                           >0       - bit mask of EZ_CONTENT_OBJECT_RELATION_* values
    */
    function reverseRelatedObjectCount( $version = false, $attributeID = 0, $params = false )
    {
        return $this->relatedObjectCount( $version, $attributeID, true, $params );
    }

    /*!
     Returns the related objects.
     \note This function is a duplicate of reverseRelatedObjectList(), use that function instead.
    */
    function contentObjectListRelatingThis( $version = false )
    {
        return $this->reverseRelatedObjectList( $version );
    }

    function publishContentObjectRelations( $version )
    {
        $objectID = $this->ID;
        $currentVersion = $this->CurrentVersion;
        $version =(int) $version;
        $db = eZDB::instance();
        $db->begin();

        $toContentObjectIDs = array();
        $relationTypesArray = array();
        $publishedRelations = $db->arrayQuery( "SELECT to_contentobject_id, relation_type FROM ezcontentobject_link
                                                WHERE contentclassattribute_id='0'
                                                  AND from_contentobject_id='$objectID'
                                                  AND from_contentobject_version='$currentVersion'
                                                  AND op_code='0'" );

        foreach ( $publishedRelations as $relation )
        {
            $toContentObjectIDs[] = $relation['to_contentobject_id'];
            $relationTypesArray[$relation['to_contentobject_id']] = (int) $relation['relation_type'];
        }
        $toContentObjectIDs = array_unique( $toContentObjectIDs );

        $addedOrRemovedRelations = $db->arrayQuery( "SELECT to_contentobject_id, op_code, relation_type FROM ezcontentobject_link
                                                     WHERE contentclassattribute_id='0'
                                                       AND from_contentobject_id='$objectID'
                                                       AND from_contentobject_version='$version'
                                                       AND op_code!='0'
                                                     ORDER BY id ASC" );

        foreach ( $addedOrRemovedRelations as $relation )
        {
            $relationType = (int) $relation['relation_type'];
            if ( !isset( $relationTypesArray[$relation['to_contentobject_id']] ) )
            {
                $relationTypesArray[$relation['to_contentobject_id']] = 0;
            }
            if ( $relation['op_code'] == 1 )
            {
                if ( !in_array( $relation['to_contentobject_id'], $toContentObjectIDs ) )
                {
                    $toContentObjectIDs[] = $relation['to_contentobject_id'];
                }
                $relationTypesArray[$relation['to_contentobject_id']] |= $relationType;
             }
            else
            {
                $relationTypesArray[$relation['to_contentobject_id']] &= ~$relationType;
                if ( 0 === $relationTypesArray[$relation['to_contentobject_id']] )
                {
                    $toContentObjectIDs = array_diff( $toContentObjectIDs, array( $relation['to_contentobject_id'] ) );
                }
            }
        }

        $db->query( "DELETE FROM ezcontentobject_link
                     WHERE contentclassattribute_id='0'
                       AND from_contentobject_id='$objectID'
                       AND from_contentobject_version='$version'" );

        foreach( $toContentObjectIDs as $toContentObjectID )
        {
            $db->query( "INSERT INTO ezcontentobject_link( contentclassattribute_id,
                                                           from_contentobject_id,
                                                           from_contentobject_version,
                                                           to_contentobject_id,
                                                           op_code,
                                                           relation_type )
                         VALUES ( '0', '$objectID', '$version', '$toContentObjectID', '0', '{$relationTypesArray[$toContentObjectID]}' )" );
        }

        $db->commit();
    }

    /*!
     Get parent node IDs
    */
    function parentNodeIDArray()
    {
        return $this->parentNodes( true, false );
    }

    /*!
     \param $version No longer in use, published nodes are used instead.
     \param $asObject If true it fetches PHP objects, otherwise it fetches IDs.
     \return the parnet nodes for the current object.
    */
    function parentNodes( $version = false, $asObject = true )
    {
        // We no longer use node-assignment table to find the parents but uses
        // the 'published' tree structure.
        $retNodes = array();

        $parentNodeIDs = eZContentObjectTreeNode::getParentNodeIdListByContentObjectID( $this->ID );
        if ( !$parentNodeIDs )
        {
          return $retNodes;
        }
        if ( $asObject )
        {
            $retNodes = eZContentObjectTreeNode::fetch( $parentNodeIDs );
            if ( !is_array( $retNodes ) )
            {
                $retNodes = array( $retNodes );
            }
        }
        else
        {
            $retNodes = $parentNodeIDs;
        }

        return $retNodes;
    }

    /*!
     Creates a new node assignment that will place the object as child of node \a $nodeID.
     \return The eZNodeAssignment object it created
     \param $parentNodeID The node ID of the parent node
     \param $isMain \c true if the created node is the main node of the object
     \param $remoteID A string denoting the unique remote ID of the assignment or \c false for no remote id.
     \param $sortField
     \param $sortOrder
     \note The returned assignment will already be stored in the database
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function createNodeAssignment( $parentNodeID, $isMain, $remoteID = false, $sortField = eZContentObjectTreeNode::SORT_FIELD_PUBLISHED, $sortOrder = eZContentObjectTreeNode::SORT_ORDER_DESC )
    {
        $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $this->attribute( 'id' ),
                                                           'contentobject_version' => $this->attribute( 'current_version' ),
                                                           'parent_node' => $parentNodeID,
                                                           'is_main' => ( $isMain ? 1 : 0 ),
                                                           'sort_field' => $sortField,
                                                           'sort_order' => $sortOrder ) );
        if ( $remoteID !== false )
        {
            $nodeAssignment->setAttribute( 'remote_id', $remoteID );
        }
        $nodeAssignment->store();
        return $nodeAssignment;
    }

    /*
     * Creates object with nodeAssignment from given parent Node, class ID and language code.
     */
    static function createWithNodeAssignment( $parentNode, $contentClassID, $languageCode, $remoteID = false )
    {
        $class = eZContentClass::fetch( $contentClassID );
        $parentObject = $parentNode->attribute( 'object' );

        // Check if the user has access to create a folder here
        if ( $class instanceof eZContentClass and
             $parentObject->checkAccess( 'create', $contentClassID, false, false, $languageCode ) == '1' )
        {
            // Set section of the newly created object to the section's value of it's parent object
            $sectionID = $parentObject->attribute( 'section_id' );

            $userID = eZUser::currentUserID();

            $db = eZDB::instance();
            $db->begin();
            $contentObject = $class->instantiateIn( $languageCode, $userID, $sectionID, false, eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
            $nodeAssignment = $contentObject->createNodeAssignment( $parentNode->attribute( 'node_id' ),
                                                                    true, $remoteID,
                                                                    $class->attribute( 'sort_field' ),
                                                                    $class->attribute( 'sort_order' ) );
            $db->commit();
            return $contentObject;
        }
        return null;
    }


    /*!
     Returns the node assignments for the current object.
    */
    function assignedNodes( $asObject = true )
    {
        $contentobjectID = $this->attribute( 'id' );
        if ( $contentobjectID == null )
        {
            $retValue = array();
            return $retValue;
        }
        $query = "SELECT ezcontentobject.*,
             ezcontentobject_tree.*,
             ezcontentclass.serialized_name_list as class_serialized_name_list,
             ezcontentclass.identifier as class_identifier,
             ezcontentclass.is_container as is_container
          FROM   ezcontentobject_tree,
             ezcontentobject,
             ezcontentclass
          WHERE  contentobject_id=$contentobjectID AND
             ezcontentobject_tree.contentobject_id=ezcontentobject.id  AND
             ezcontentclass.version=0 AND
             ezcontentclass.id = ezcontentobject.contentclass_id
          ORDER BY path_string";
        $db = eZDB::instance();
        $nodesListArray = $db->arrayQuery( $query );
        if ( $asObject == true )
        {
            $nodes = eZContentObjectTreeNode::makeObjectsArray( $nodesListArray );
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
        }
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

    function canEditLanguages()
    {
        $availableLanguages = $this->availableLanguages();
        $languages = array();

        foreach ( eZContentLanguage::prioritizedLanguages() as $language )
        {
            $languageCode = $language->attribute( 'locale' );
            if ( in_array( $languageCode, $availableLanguages ) &&
                 $this->canEdit( false, false, false, $languageCode ) )
            {
                $languages[] = $language;
            }
        }

        return $languages;
    }

    function canCreateLanguages()
    {
        $availableLanguages = $this->availableLanguages();
        $languages = array();
        foreach ( eZContentLanguage::prioritizedLanguages() as $language )
        {
            $languageCode = $language->attribute( 'locale' );
            if ( !in_array( $languageCode, $availableLanguages ) &&
                 $this->checkAccess( 'edit', false, false, false, $languageCode ) )
            {
                $languages[] = $language;
            }
        }

        return $languages;
    }

    function checkGroupLimitationAccess( $limitationValueList, $userID, $contentObjectID = false )
    {
        $access = 'denied';

        if ( is_array( $limitationValueList ) && is_numeric( $userID ) )
        {
            if ( $contentObjectID !== false )
            {
                $contentObject = eZContentObject::fetch( $contentObjectID );
            }
            else
            {
                $contentObject = $this;
            }

            if ( is_object( $contentObject ) )
            {
                // limitation value == 1, means "self group"
                if ( in_array( 1, $limitationValueList ) )
                {
                    // no need to check groups if user ownes this object
                    $ownerID = $contentObject->attribute( 'owner_id' );
                    if ( $ownerID == $userID || $contentObject->attribute( 'id' ) == $userID )
                    {
                        $access = 'allowed';
                    }
                    else
                    {
                        // get parent node ids for 'user' and 'owner'
                        $groupList = eZContentObjectTreeNode::getParentNodeIdListByContentObjectID( array( $userID, $ownerID ), true );

                        // find group(s) which is common for 'user' and 'owner'
                        $commonGroup = array_intersect( $groupList[$userID], $groupList[$ownerID] );

                        if ( count( $commonGroup ) > 0 )
                        {
                            // ok, we have at least 1 common group
                            $access = 'allowed';
                        }
                    }
                }
            }
        }

        return $access;
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
    function checkAccess( $functionName, $originalClassID = false, $parentClassID = false, $returnAccessList = false, $language = false )
    {
        $classID = $originalClassID;
        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );
        $origFunctionName = $functionName;

        // Fetch the ID of the language if we get a string with a language code
        // e.g. 'eng-GB'
        $originalLanguage = $language;
        if ( is_string( $language ) && strlen( $language ) > 0 )
        {
            $language = eZContentLanguage::idByLocale( $language );
        }
        else
        {
            $language = false;
        }

        // This will be filled in with the available languages of the object
        // if a Language check is performed.
        $languageList = false;

        // The 'move' function simply reuses 'edit' for generic access
        // but adds another top-level check below
        // The original function is still available in $origFunctionName
        if ( $functionName == 'move' )
            $functionName = 'edit';

        $accessResult = $user->hasAccessTo( 'content' , $functionName );
        $accessWord = $accessResult['accessWord'];

        /*
        // Uncomment this part if 'create' permissions should become implied 'edit'.
        // Merges in 'create' policies with 'edit'
        if ( $functionName == 'edit' &&
             !in_array( $accessWord, array( 'yes', 'no' ) ) )
        {
            // Add in create policies.
            $accessExtraResult = $user->hasAccessTo( 'content', 'create' );
            if ( $accessExtraResult['accessWord'] != 'no' )
            {
                $accessWord = $accessExtraResult['accessWord'];
                if ( isset( $accessExtraResult['policies'] ) )
                {
                    $accessResult['policies'] = array_merge( $accessResult['policies'],
                                                             $accessExtraResult['policies'] );
                }
                if ( isset( $accessExtraResult['accessList'] ) )
                {
                    $accessResult['accessList'] = array_merge( $accessResult['accessList'],
                                                               $accessExtraResult['accessList'] );
                }
            }
        }
        */

        if ( $origFunctionName == 'remove' or
             $origFunctionName == 'move' )
        {
            $mainNode = $this->attribute( 'main_node' );
            // We do not allow these actions on objects placed at top-level
            // - remove
            // - move
            if ( $mainNode and $mainNode->attribute( 'parent_node_id' ) <= 1 )
            {
                return 0;
            }
        }

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
            if ( $functionName == 'edit' )
            {
                // Check if we have 'create' access under the main parent
                if ( $this->attribute( 'current_version' ) == 1 && !$this->attribute( 'status' ) )
                {
                    $mainNode = eZNodeAssignment::fetchForObject( $this->attribute( 'id' ), $this->attribute( 'current_version' ) );
                    $parentObj = $mainNode[0]->attribute( 'parent_contentobject' );
                    $result = $parentObj->checkAccess( 'create', $this->attribute( 'contentclass_id' ),
                                                       $parentObj->attribute( 'contentclass_id' ), false, $originalLanguage );
                    return $result;
                }
                else
                {
                    return 0;
                }
            }

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
                if ( isset( $limitationArray['Subtree' ] ) )
                {
                    $checkedSubtree = false;
                }
                else
                {
                    $checkedSubtree = true;
                    $accessSubtree = false;
                }
                if ( isset( $limitationArray['Node'] ) )
                {
                    $checkedNode = false;
                }
                else
                {
                    $checkedNode = true;
                    $accessNode = false;
                }
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
                            else if ( $functionName != 'create' and
                                      in_array( $this->attribute( 'contentclass_id' ), $limitationArray[$key] )  )
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

                        case 'ParentDepth':
                        {
                            $assignedNodes = $this->attribute( 'assigned_nodes' );
                            if ( count( $assignedNodes ) > 0 )
                            {
                                foreach ( $assignedNodes as  $assignedNode )
                                {
                                    $depth = $assignedNode->attribute( 'depth' );
                                    if ( in_array( $depth, $limitationArray[$key] ) )
                                    {
                                        $access = 'allowed';
                                        break;
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

                        case 'Section':
                        case 'User_Section':
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

                        case 'Language':
                        {
                            $languageMask = 0;
                            // If we don't have a language list yet we need to fetch it
                            // and optionally filter out based on $language.

                            if ( $functionName == 'create' )
                            {
                                // If the function is 'create' we do not use the language_mask for matching.
                                if ( $language !== false )
                                {
                                    $languageMask = $language;
                                }
                                else
                                {
                                    // If the create is used and no language specified then
                                    // we need to match against all possible languages (which
                                    // is all bits set, ie. -1).
                                    $languageMask = -1;
                                }
                            }
                            else
                            {
                                if ( $language !== false )
                                {
                                    if ( $languageList === false )
                                    {
                                        $languageMask = (int)$this->attribute( 'language_mask' );
                                        // We are restricting language check to just one language
                                        $languageMask &= (int)$language;
                                        // If the resulting mask is 0 it means that the user is trying to
                                        // edit a language which does not exist, ie. translating.
                                        // The mask will then become the language trying to edit.
                                        if ( $languageMask == 0 )
                                        {
                                            $languageMask = $language;
                                        }
                                    }
                                }
                                else
                                {
                                    $languageMask = -1;
                                }
                            }
                            // Fetch limit mask for limitation list
                            $limitMask = eZContentLanguage::maskByLocale( $limitationArray[$key] );
                            if ( ( $languageMask & $limitMask ) != 0 )
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
                        case 'ParentOwner':
                        {
                            // if limitation value == 2, anonymous limited to current session.
                            if ( in_array( 2, $limitationArray[$key] ) &&
                                 $user->isAnonymous() )
                            {
                                $createdObjectIDList = eZPreferences::value( 'ObjectCreationIDList' );
                                if ( $createdObjectIDList &&
                                     in_array( $this->ID, unserialize( $createdObjectIDList ) ) )
                                {
                                    $access = 'allowed';
                                }
                            }
                            else if ( $this->attribute( 'owner_id' ) == $userID || $this->ID == $userID )
                            {
                                $access = 'allowed';
                            }
                            if ( $access != 'allowed' )
                            {
                                $access = 'denied';
                                $limitationList = array ( 'Limitation' => $key, 'Required' => $limitationArray[$key] );
                            }
                        } break;

                        case 'Group':
                        case 'ParentGroup':
                        {
                            $access = $this->checkGroupLimitationAccess( $limitationArray[$key], $userID );

                            if ( $access != 'allowed' )
                            {
                                $access = 'denied';
                                $limitationList = array ( 'Limitation' => $key,
                                                          'Required' => $limitationArray[$key] );
                            }
                        } break;

                        case 'State':
                        {
                            if ( count( array_intersect( $limitationArray[$key], $this->attribute( 'state_id_array' ) ) ) == 0 )
                            {
                                $access = 'denied';
                                $limitationList = array ( 'Limitation' => $key,
                                                          'Required' => $limitationArray[$key] );
                            }
                            else
                            {
                                $access = 'allowed';
                            }
                        } break;

                        case 'Node':
                        {
                            $accessNode = false;
                            $mainNodeID = $this->attribute( 'main_node_id' );
                            foreach ( $limitationArray[$key] as $nodeID )
                            {
                                $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                                $limitationNodeID = $node['main_node_id'];
                                if ( $mainNodeID == $limitationNodeID )
                                {
                                    $access = 'allowed';
                                    $accessNode = true;
                                    break;
                                }
                            }
                            if ( $access != 'allowed' && $checkedSubtree && !$accessSubtree )
                            {
                                $access = 'denied';
                                // ??? TODO: if there is a limitation on Subtree, return two limitations?
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                            else
                            {
                                $access = 'allowed';
                            }
                            $checkedNode = true;
                        } break;

                        case 'Subtree':
                        {
                            $accessSubtree = false;
                            $assignedNodes = $this->attribute( 'assigned_nodes' );
                            if ( count( $assignedNodes ) != 0 )
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
                                            $accessSubtree = true;
                                            break;
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
                                        $accessSubtree = true;
                                    }
                                }
                                else
                                {
                                    foreach ( $parentNodes as $parentNode )
                                    {
                                        $parentNode = eZContentObjectTreeNode::fetch( $parentNode, false, false );
                                        $path = $parentNode['path_string'];

                                        $subtreeArray = $limitationArray[$key];
                                        foreach ( $subtreeArray as $subtreeString )
                                        {
                                            if ( strstr( $path, $subtreeString ) )
                                            {
                                                $access = 'allowed';
                                                $accessSubtree = true;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            if ( $access != 'allowed' && $checkedNode && !$accessNode )
                            {
                                $access = 'denied';
                                // ??? TODO: if there is a limitation on Node, return two limitations?
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                            else
                            {
                                $access = 'allowed';
                            }
                            $checkedSubtree = true;
                        } break;

                        case 'User_Subtree':
                        {
                            $assignedNodes = $this->attribute( 'assigned_nodes' );
                            if ( count( $assignedNodes ) != 0 )
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
                                        $parentNode = eZContentObjectTreeNode::fetch( $parentNode, false, false );
                                        $path = $parentNode['path_string'];

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

                        default:
                        {
                            if ( strncmp( $key, 'StateGroup_', 11 ) === 0 )
                            {
                                if ( count( array_intersect( $limitationArray[$key],
                                                             $this->attribute( 'state_id_array' ) ) ) == 0 )
                                {
                                    $access = 'denied';
                                    $limitationList = array ( 'Limitation' => $key,
                                                              'Required' => $limitationArray[$key] );
                                }
                                else
                                {
                                    $access = 'allowed';
                                }
                            }
                        }
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
                if ( $functionName == 'edit' )
                {
                    // Check if we have 'create' access under the main parent
                    if ( $this->attribute( 'current_version' ) == 1 && !$this->attribute( 'status' ) )
                    {
                        $mainNode = eZNodeAssignment::fetchForObject( $this->attribute( 'id' ), $this->attribute( 'current_version' ) );
                        $parentObj = $mainNode[0]->attribute( 'parent_contentobject' );
                        $result = $parentObj->checkAccess( 'create', $this->attribute( 'contentclass_id' ),
                                                           $parentObj->attribute( 'contentclass_id' ), false, $originalLanguage );
                        if ( $result )
                        {
                            $access = 'allowed';
                        }
                        return $result;
                    }
                }
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
                                                                'Function' => $origFunctionName,
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

    // code-template::create-block: class-list-from-policy, is-object
    // code-template::auto-generated:START class-list-from-policy
    // This code is automatically generated from templates/classlistfrompolicy.ctpl
    // DO NOT EDIT THIS CODE DIRECTLY, CHANGE THE TEMPLATE FILE INSTEAD

    function classListFromPolicy( $policy, $allowedLanguageCodes = false )
    {
        $canCreateClassIDListPart = array();
        $hasClassIDLimitation = false;
        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        if ( isset( $policy['ParentOwner'] ) )
        {
            // if limitation value == 2, anonymous limited to current session.
            if ( in_array( 2, $policy['ParentOwner'] ) && $user->isAnonymous() )
            {
                $createdObjectIDList = eZPreferences::value( 'ObjectCreationIDList' );
                if ( !$createdObjectIDList ||
                     !in_array( $this->ID, unserialize( $createdObjectIDList ) ) )
                {
                    return array();
                }
            }
            else if ( $object->attribute( 'owner_id' ) != $userID &&
                      $this->ID != $userID )
            {
                return array();
            }
        }

        if ( isset( $policy['ParentGroup'] ) )
        {
            $access = $this->checkGroupLimitationAccess( $policy['ParentGroup'], $userID );
            if ( $access !== 'allowed' )
            {
                return array();
            }
        }

        if ( isset( $policy['Class'] ) )
        {
            $canCreateClassIDListPart = $policy['Class'];
            $hasClassIDLimitation = true;
        }

        if ( isset( $policy['User_Section'] ) )
        {
            if ( !in_array( $this->attribute( 'section_id' ), $policy['User_Section'] ) )
            {
                return array();
            }
        }

        if ( isset( $policy['User_Subtree'] ) )
        {
            $allowed = false;
            $assignedNodes = $this->attribute( 'assigned_nodes' );
            foreach ( $assignedNodes as $assignedNode )
            {
                $path = $assignedNode->attribute( 'path_string' );
                foreach ( $policy['User_Subtree'] as $subtreeString )
                {
                    if ( strstr( $path, $subtreeString ) )
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

        if ( isset( $policy['Section'] ) )
        {
            if ( !in_array( $this->attribute( 'section_id' ), $policy['Section'] ) )
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
            if ( $this->attribute( 'owner_id' ) != $userID )
            {
                return array();
            }
        }

        $allowedNode = false;
        if ( isset( $policy['Node'] ) )
        {
            $allowed = false;
            foreach( $policy['Node'] as $nodeID )
            {
                $mainNodeID = $this->attribute( 'main_node_id' );
                $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                if ( $mainNodeID == $node['main_node_id'] )
                {
                    $allowed = true;
                    $allowedNode = true;
                    break;
                }
            }
            if ( !$allowed && !isset( $policy['Subtree'] ) )
            {
                return array();
            }
        }

        if ( isset( $policy['Subtree'] ) )
        {
            $allowed = false;
            $assignedNodes = $this->attribute( 'assigned_nodes' );
            foreach ( $assignedNodes as $assignedNode )
            {
                $path = $assignedNode->attribute( 'path_string' );
                foreach ( $policy['Subtree'] as $subtreeString )
                {
                    if ( strstr( $path, $subtreeString ) )
                    {
                        $allowed = true;
                        break;
                    }
                }
            }
            if ( !$allowed && !$allowedNode )
            {
                return array();
            }
        }

        if ( isset( $policy['Language'] ) )
        {
            if ( $allowedLanguageCodes )
            {
                $allowedLanguageCodes = array_intersect( $allowedLanguageCodes, $policy['Language'] );
            }
            else
            {
                $allowedLanguageCodes = $policy['Language'];
            }
        }

        if ( $hasClassIDLimitation )
        {
            return array( 'classes' => $canCreateClassIDListPart, 'language_codes' => $allowedLanguageCodes );
        }
        return array( 'classes' => '*', 'language_codes' => $allowedLanguageCodes );
    }

    // This code is automatically generated from templates/classlistfrompolicy.ctpl
    // code-template::auto-generated:END class-list-from-policy

    // code-template::create-block: can-instantiate-class-list, group-filter, object-policy-list, name-create, object-creation, object-sql-creation
    // code-template::auto-generated:START can-instantiate-class-list
    // This code is automatically generated from templates/classcreatelist.ctpl
    // DO NOT EDIT THIS CODE DIRECTLY, CHANGE THE TEMPLATE FILE INSTEAD

    /*!
     Finds all classes that the current user can create objects from and returns.
     It is also possible to filter the list event more with \a $includeFilter and \a $groupList.

     \param $asObject If \c true then it return eZContentClass objects, if not it will
                      be an associative array with \c name and \c id keys.
     \param $includeFilter If \c true then it will include only from class groups defined in
                           \a $groupList, if not it will exclude those groups.
     \param $groupList An array with class group IDs that should be used in filtering, use
                       \c false if you do not wish to filter at all.
     \param $fetchID A unique name for the current fetch, this must be supplied when filtering is
                     used if you want caching to work.
    */
    function canCreateClassList( $asObject = false, $includeFilter = true, $groupList = false, $fetchID = false )
    {
        $ini = eZINI::instance();
        $groupArray = array();
        $languageCodeList = eZContentLanguage::fetchLocaleList();
        $allowedLanguages = array( '*' => array() );

        $user = eZUser::currentUser();
        $accessResult = $user->hasAccessTo( 'content' , 'create' );
        $accessWord = $accessResult['accessWord'];

        $classIDArray = array();
        $classList = array();
        $fetchAll = false;
        if ( $accessWord == 'yes' )
        {
            $fetchAll = true;
            $allowedLanguages['*'] = $languageCodeList;
        }
        else if ( $accessWord == 'no' )
        {
            // Cannnot create any objects, return empty list.
            return $classList;
        }
        else
        {
            $policies = $accessResult['policies'];
            foreach ( $policies as $policyKey => $policy )
            {
                $policyArray = $this->classListFromPolicy( $policy, $languageCodeList );
                if ( count( $policyArray ) == 0 )
                {
                    continue;
                }
                $classIDArrayPart = $policyArray['classes'];
                $languageCodeArrayPart = $policyArray['language_codes'];
                if ( $classIDArrayPart == '*' )
                {
                    $fetchAll = true;
                    $allowedLanguages['*'] = array_unique( array_merge( $allowedLanguages['*'], $languageCodeArrayPart ) );
                }
                else
                {
                    foreach( $classIDArrayPart as $class )
                    {
                        if ( isset( $allowedLanguages[$class] ) )
                        {
                            $allowedLanguages[$class] = array_unique( array_merge( $allowedLanguages[$class], $languageCodeArrayPart ) );
                        }
                        else
                        {
                            $allowedLanguages[$class] = $languageCodeArrayPart;
                        }
                    }
                    $classIDArray = array_merge( $classIDArray, array_diff( $classIDArrayPart, $classIDArray ) );
                }
            }
        }

        $db = eZDB::instance();

        $filterTableSQL = '';
        $filterSQL = '';
        // Create extra SQL statements for the class group filters.
        if ( is_array( $groupList ) )
        {
            if ( count( $groupList ) == 0 )
            {
                return $classList;
            }

            $filterTableSQL = ', ezcontentclass_classgroup ccg';
            $filterSQL = ( " AND\n" .
                           "      cc.id = ccg.contentclass_id AND\n" .
                           "      " );
            $filterSQL .= $db->generateSQLINStatement( $groupList, 'ccg.group_id', !$includeFilter, true, 'int' );
        }

        $classNameFilter = eZContentClassName::sqlFilter( 'cc' );

        if ( $fetchAll )
        {
            // If $asObject is true we fetch all fields in class
            $fields = $asObject ? "cc.*, $classNameFilter[nameField]" : "cc.id, $classNameFilter[nameField]";
            $rows = $db->arrayQuery( "SELECT DISTINCT $fields\n" .
                                     "FROM ezcontentclass cc$filterTableSQL, $classNameFilter[from]\n" .
                                     "WHERE cc.version = " . eZContentClass::VERSION_STATUS_DEFINED . " $filterSQL AND $classNameFilter[where]\n" .
                                     "ORDER BY $classNameFilter[nameField] ASC" );
            $classList = eZPersistentObject::handleRows( $rows, 'eZContentClass', $asObject );
        }
        else
        {
            // If the constrained class list is empty we are not allowed to create any class
            if ( count( $classIDArray ) == 0 )
            {
                return $classList;
            }

            $classIDCondition = $db->generateSQLInStatement( $classIDArray, 'cc.id' );
            // If $asObject is true we fetch all fields in class
            $fields = $asObject ? "cc.*, $classNameFilter[nameField]" : "cc.id, $classNameFilter[nameField]";
            $rows = $db->arrayQuery( "SELECT DISTINCT $fields\n" .
                                     "FROM ezcontentclass cc$filterTableSQL, $classNameFilter[from]\n" .
                                     "WHERE $classIDCondition AND\n" .
                                     "      cc.version = " . eZContentClass::VERSION_STATUS_DEFINED . " $filterSQL AND $classNameFilter[where]\n" .
                                     "ORDER BY $classNameFilter[nameField] ASC" );
            $classList = eZPersistentObject::handleRows( $rows, 'eZContentClass', $asObject );
        }

        if ( $asObject )
        {
            foreach ( $classList as $key => $class )
            {
                $id = $class->attribute( 'id' );
                if ( isset( $allowedLanguages[$id] ) )
                {
                    $languageCodes = array_unique( array_merge( $allowedLanguages['*'], $allowedLanguages[$id] ) );
                }
                else
                {
                    $languageCodes = $allowedLanguages['*'];
                }
                $classList[$key]->setCanInstantiateLanguages( $languageCodes );
            }
        }

        eZDebugSetting::writeDebug( 'kernel-content-class', $classList, "class list fetched from db" );
        return $classList;
    }

    // This code is automatically generated from templates/classcreatelist.ctpl
    // code-template::auto-generated:END can-instantiate-class-list

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
     \return \c true if the current user can read this content object.
    */
    function canRead( )
    {
        if ( !isset( $this->Permissions["can_read"] ) )
        {
            $this->Permissions["can_read"] = $this->checkAccess( 'read' );
        }
        return ( $this->Permissions["can_read"] == 1 );
    }

    /*!
     \return \c true if the current user can create a pdf of this content object.
    */
    function canPdf( )
    {
        if ( !isset( $this->Permissions["can_pdf"] ) )
        {
            $this->Permissions["can_pdf"] = $this->checkAccess( 'pdf' );
        }
        return ( $this->Permissions["can_pdf"] == 1 );
    }

    /*!
     \return \c true if the node can be viewed as embeded object by the current user.
     \sa checkAccess().
    */
    function canViewEmbed( )
    {
        if ( !isset( $this->Permissions["can_view_embed"] ) )
        {
            $this->Permissions["can_view_embed"] = $this->checkAccess( 'view_embed' );
        }
        return ( $this->Permissions["can_view_embed"] == 1 );
    }

    /*!
     \return \c true if the current user can diff this content object.
    */
    function canDiff( )
    {
        if ( !isset( $this->Permissions["can_diff"] ) )
        {
            $this->Permissions["can_diff"] = $this->checkAccess( 'diff' );
        }
        return ( $this->Permissions["can_diff"] == 1 );
    }

    function canCreate( )
    {
        if ( !isset( $this->Permissions["can_create"] ) )
        {
            $this->Permissions["can_create"] = $this->checkAccess( 'create' );
        }
        return ( $this->Permissions["can_create"] == 1 );
    }

    function canEdit( $originalClassID = false, $parentClassID = false, $returnAccessList = false, $language = false )
    {
        $isCalledClean = ( func_num_args() == 0 );
        if ( isset( $this->Permissions["can_edit"] ) && $isCalledClean )
        {
            $canEdit = $this->Permissions["can_edit"];
        }
        else
        {
            $canEdit = $this->checkAccess( 'edit', $originalClassID, $parentClassID, $returnAccessList, $language );
            if ( $canEdit != 1 )
            {
                 $user = eZUser::currentUser();
                 if ( $user->attribute( 'contentobject_id' ) === $this->attribute( 'id' ) )
                 {
                     $access = $user->hasAccessTo( 'user', 'selfedit' );
                     if ( $access['accessWord'] == 'yes' )
                     {
                         $canEdit = 1;
                     }
                 }
            }

            if ( $isCalledClean )
            {
                $this->Permissions["can_edit"] = $canEdit;
            }
        }
        return ( $canEdit == 1 );
    }

    function canTranslate( )
    {
        if ( !isset( $this->Permissions["can_translate"] ) )
        {
            $this->Permissions["can_translate"] = $this->checkAccess( 'translate' );
            if ( $this->Permissions["can_translate"] != 1 )
            {
                 $user = eZUser::currentUser();
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
        return ( $this->Permissions["can_translate"] == 1 );
    }

    function canRemove( )
    {

        if ( !isset( $this->Permissions["can_remove"] ) )
        {
            $this->Permissions["can_remove"] = $this->checkAccess( 'remove' );
        }
        return ( $this->Permissions["can_remove"] == 1 );
    }

    /*!
     Check if the object can be moved. (actually checks 'edit' and 'remove' permissions)
     \return \c true if the object can be moved by the current user.
     \sa checkAccess().
     \deprecated The function canMove() is preferred since its naming is clearer.
    */
    function canMove( )
    {
        return $this->canMoveFrom();
    }

    /*!
     Check if the object can be moved. (actually checks 'edit' and 'remove' permissions)
     \return \c true if the object can be moved by the current user.
     \sa checkAccess().
    */
    function canMoveFrom( )
    {

        if ( !isset( $this->Permissions['can_move_from'] ) )
        {
            $this->Permissions['can_move_from'] = $this->checkAccess( 'edit' ) && $this->checkAccess( 'remove' );
        }
        return ( $this->Permissions['can_move_from'] == 1 );
    }

    /*!
     \return The name of the class which this object was created from.

     \note The object will cache the class name information so multiple calls will be fast.
    */
    function className()
    {
        if ( !is_numeric( $this->ClassID ) )
        {
            return null;
        }

        if ( $this->ClassName !== false )
            return $this->ClassName;

        $db = eZDB::instance();
        $id = (int)$this->ClassID;
        $sql = "SELECT serialized_name_list FROM ezcontentclass WHERE id=$id and version=0";
        $rows = $db->arrayQuery( $sql );
        if ( count( $rows ) > 0 )
        {
            $this->ClassName = eZContentClass::nameFromSerializedString( $rows[0]['serialized_name_list'] );
        }
        return $this->ClassName;
    }

    /*!
     Returns an array of the content actions which can be performed on
     the current object.
    */
    function contentActionList()
    {
        $version = $this->attribute( 'current_version' );
        $language = $this->initialLanguageCode();
        if ( !isset( $this->ContentObjectAttributeArray[$version][$language] ) )
        {
            $attributeList = $this->contentObjectAttributes();
            $this->ContentObjectAttributeArray[$version][$language] =& $attributeList;
        }
        else
            $attributeList = $this->ContentObjectAttributeArray[$version][$language];

        // Fetch content actions if not already fetched
        if ( $this->ContentActionList === false )
        {

            $contentActionList = array();
            foreach ( $attributeList as $attribute )
            {
                $contentActions = $attribute->contentActionList();
                if ( count( $contentActions ) > 0 )
                {
                    $contentActionList = $attribute->contentActionList();

                    if ( is_array( $contentActionList ) )
                    {
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

    /*!
     \return the languages the object has been translated into/exists in.

     Returns an array with the language codes.

     It uses the attribute \c avail_lang as the source for the language list.
     */
    function availableLanguages()
    {
        $languages = array();
        $languageObjects = $this->languages();

        foreach ( $languageObjects as $languageObject )
        {
            $languages[] = $languageObject->attribute( 'locale' );
        }

        return $languages;
    }

    function availableLanguagesJsArray()
    {
        return eZContentLanguage::jsArrayByMask( $this->LanguageMask );
    }

    function languages()
    {
        return isset( $this->LanguageMask ) ?
            eZContentLanguage::prioritizedLanguagesByMask( $this->LanguageMask ) :
            array();
    }

    function allLanguages()
    {
        $languages = isset( $this->LanguageMask ) ? eZContentLanguage::languagesByMask( $this->LanguageMask ) : array();
        return $languages;
    }

    static function defaultLanguage()
    {
        if ( ! isset( $GLOBALS['eZContentObjectDefaultLanguage'] ) )
        {
            $defaultLanguage = false;
            $language = eZContentLanguage::topPriorityLanguage();
            if ( $language )
            {
                $defaultLanguage = $language->attribute( 'locale' );
            }
            else
            {
                $ini = eZINI::instance();
                if ( $ini->hasVariable( 'RegionalSettings', 'ContentObjectLocale' ) )
                {
                    $defaultLanguage = $ini->variable( 'RegionalSettings', 'ContentObjectLocale' );
                    eZContentLanguage::fetchByLocale( $defaultLanguage, true );
                }
            }
            $GLOBALS['eZContentObjectDefaultLanguage'] = $defaultLanguage;
        }

        return $GLOBALS['eZContentObjectDefaultLanguage'];
    }

    /*!
     \static
     Set default language. Checks if default language is valid.

     \param default language.
     \note Deprecated.
    */
    static function setDefaultLanguage( $lang )
    {
        return false;
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
    static function translationStringList()
    {
        $translationList = array();
        $languageList = eZContentLanguage::fetchList();

        foreach ( $languageList as $language )
        {
            $translationList[] = $language->attribute( 'locale' );
        }

        return $translationList;
    }

    /*!
     \returns an array with locale objects, these objects represents the languages the content objects are allowed to be translated into.
     \note the setting ContentSettings/TranslationList in site.ini determines the array.
     \sa translationStringList
    */
    static function translationList()
    {
        $translationList = array();
        $languageList = eZContentLanguage::fetchList();

        foreach ( $languageList as $language )
        {
            $translationList[] = $language->localeObject();
        }

        return $translationList;
    }

    /*!
     Returns the attributes for the content object version \a $version and content object \a $contentObjectID.
     \a $language defines the language to fetch.
     \sa attributes
    */
    function fetchClassAttributes( $version = 0, $asObject = true )
    {
        return eZContentClassAttribute::fetchListByClassID( $this->attribute( 'contentclass_id' ), $version, $asObject );
    }

    /*!
     \private
     Maps input lange to another one if defined in $options['language_map'].
     If it cannot map it returns the original language.
     \returns string
     */
    static function mapLanguage( $language, $options )
    {
        if ( isset( $options['language_map'][$language] ) )
        {
            return $options['language_map'][$language];
        }
        return $language;
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
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function unserialize( $package, $domNode, &$options, $ownerID = false, $handlerType = 'ezcontentobject' )
    {
        if ( $domNode->localName != 'object' )
        {
            $retValue = false;
            return $retValue;
        }

        $initialLanguage = eZContentObject::mapLanguage( $domNode->getAttribute( 'initial_language' ), $options );
        if( $initialLanguage === 'skip' )
        {
            $retValue = true;
            return $retValue;
        }

        $sectionID = $domNode->getAttributeNS( 'http://ez.no/ezobject', 'section_id' );
        if ( $ownerID === false )
        {
            $ownerID = $domNode->getAttributeNS( 'http://ez.no/ezobject', 'owner_id' );
        }
        $remoteID = $domNode->getAttribute( 'remote_id' );
        $name = $domNode->getAttribute( 'name' );
        $classRemoteID = $domNode->getAttribute( 'class_remote_id' );
        $classIdentifier = $domNode->getAttributeNS( 'http://ez.no/ezobject', 'class_identifier' );
        $alwaysAvailable = ( $domNode->getAttributeNS( 'http://ez.no/ezobject', 'always_available' ) == '1' );

        $contentClass = eZContentClass::fetchByRemoteID( $classRemoteID );
        if ( !$contentClass )
        {
            $contentClass = eZContentClass::fetchByIdentifier( $classIdentifier );
        }

        if ( !$contentClass )
        {
            $options['error'] = array( 'error_code' => self::PACKAGE_ERROR_NO_CLASS,
                                       'element_id' => $remoteID,
                                       'description' => "Can't install object '$name': Unable to fetch class with remoteID: $classRemoteID." );
            $retValue = false;
            return $retValue;
        }

        $versionListNode = $domNode->getElementsByTagName( 'version-list' )->item( 0 );

        $importedLanguages = array();
        foreach( $versionListNode->getElementsByTagName( 'version' ) as $versionDOMNode )
        {
            foreach ( $versionDOMNode->getElementsByTagName( 'object-translation' ) as $versionDOMNodeChild )
            {
                $importedLanguage = eZContentObject::mapLanguage( $versionDOMNodeChild->getAttribute( 'language' ), $options );
                $language = eZContentLanguage::fetchByLocale( $importedLanguage );
                // Check if the language is allowed in this setup.
                if ( $language )
                {
                    $hasTranslation = true;
                }
                else
                {
                    if ( $importedLanguage == 'skip' )
                        continue;

                    // if there is no needed translation in system then add it
                    $locale = eZLocale::instance( $importedLanguage );
                    $translationName = $locale->internationalLanguageName();
                    $translationLocale = $locale->localeCode();

                    if ( $locale->isValid() )
                    {
                        eZContentLanguage::addLanguage( $locale->localeCode(), $locale->internationalLanguageName() );
                        $hasTranslation = true;
                    }
                    else
                        $hasTranslation = false;
                }
                if ( $hasTranslation )
                {
                    $importedLanguages[] = $importedLanguage;
                    $importedLanguages = array_unique( $importedLanguages );
                }
            }
        }

        // If object exists we return a error.
        // Minimum install element is an object now.

        $contentObject = eZContentObject::fetchByRemoteID( $remoteID );
        // Figure out initial language
        if ( !$initialLanguage ||
             !in_array( $initialLanguage, $importedLanguages ) )
        {
            $initialLanguage = false;
            foreach ( eZContentLanguage::prioritizedLanguages() as $language )
            {
                if ( in_array( $language->attribute( 'locale' ), $importedLanguages ) )
                {
                    $initialLanguage = $language->attribute( 'locale' );
                    break;
                }
            }
        }
        if ( !$contentObject )
        {
            $firstVersion = true;
            $contentObject = $contentClass->instantiateIn( $initialLanguage, $ownerID, $sectionID );
        }
        else
        {
            $firstVersion = false;
            $description = "Object '$name' already exists.";

            // include_once( 'kernel/classes/ezpackagehandler.php' );
            $choosenAction = eZPackageHandler::errorChoosenAction( self::PACKAGE_ERROR_EXISTS,
                                                                   $options, $description, $handlerType, false );

            switch( $choosenAction )
            {
                case eZPackage::NON_INTERACTIVE:
                case self::PACKAGE_UPDATE:
                {
                    // Keep existing contentobject.
                } break;

                case self::PACKAGE_REPLACE:
                {
                    eZContentObjectOperations::remove( $contentObject->attribute( 'id' ) );

                    unset( $contentObject );
                    $contentObject = $contentClass->instantiateIn( $initialLanguage, $ownerID, $sectionID );
                    $firstVersion = true;
                } break;

                case self::PACKAGE_SKIP:
                {
                    $retValue = true;
                    return $retValue;
                } break;

                case self::PACKAGE_NEW:
                {
                    $contentObject->setAttribute( 'remote_id', md5( (string)mt_rand() . (string)time() ) );
                    $contentObject->store();
                    unset( $contentObject );
                    $contentObject = $contentClass->instantiate( $ownerID, $sectionID );
                    $firstVersion = true;
                } break;

                default:
                {
                    $options['error'] = array( 'error_code' => self::PACKAGE_ERROR_EXISTS,
                                               'element_id' => $remoteID,
                                               'description' => $description,
                                               'actions' => array( self::PACKAGE_REPLACE => ezi18n( 'kernel/classes', 'Replace existing object' ),
                                                                   self::PACKAGE_SKIP    => ezi18n( 'kernel/classes', 'Skip object' ),
                                                                   self::PACKAGE_NEW     => ezi18n( 'kernel/classes', 'Keep existing and create a new one' ),
                                                                   self::PACKAGE_UPDATE  => ezi18n( 'kernel/classes', 'Update existing object' ) ) );
                    $retValue = false;
                    return $retValue;
                } break;
            }
        }

        $db = eZDB::instance();
        $db->begin();

        if ( $alwaysAvailable )
        {
            // Make sure always available bit is set.
            $contentObject->setAttribute( 'language_mask', (int)$contentObject->attribute( 'language_mask' ) | 1 );
        }

        $contentObject->setAttribute( 'section_id', $sectionID );
        $contentObject->store();
        $activeVersion = false;
        $lastVersion = false;
        $versionListActiveVersion = $versionListNode->getAttribute( 'active_version' );

        $contentObject->setAttribute( 'remote_id', $remoteID );
        $contentObject->setAttribute( 'contentclass_id', $contentClass->attribute( 'id' ) );
        $contentObject->store();

        $options['language_array'] = $importedLanguages;
        $versionList = array();
        foreach( $versionListNode->getElementsByTagName( 'version' ) as $versionDOMNode )
        {
            unset( $nodeList );
            $nodeList = array();
            $contentObjectVersion = eZContentObjectVersion::unserialize( $versionDOMNode,
                                                                         $contentObject,
                                                                         $ownerID,
                                                                         $sectionID,
                                                                         $versionListActiveVersion,
                                                                         $firstVersion,
                                                                         $nodeList,
                                                                         $options,
                                                                         $package );

            if ( !$contentObjectVersion )
            {
                $db->commit();

                $retValue = false;
                return $retValue;
            }

            $versionStatus = $versionDOMNode->getAttributeNS( 'http://ez.no/ezobject', 'status' );
            $versionList[$versionDOMNode->getAttributeNS( 'http://ez.no/ezobject', 'version' )] = array( 'node_list' => $nodeList,
                                                                                                         'status' =>    $versionStatus );
            unset( $versionStatus );

            $firstVersion = false;
            $lastVersion = $contentObjectVersion->attribute( 'version' );
            if ( $versionDOMNode->getAttribute( 'version' ) == $versionListActiveVersion )
            {
                $activeVersion = $contentObjectVersion->attribute( 'version' );
            }
            eZNodeAssignment::setNewMainAssignment( $contentObject->attribute( 'id' ), $lastVersion );

            eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObject->attribute( 'id' ),
                                                                      'version' => $lastVersion ) );

            $mainNodeInfo = null;
            foreach ( $nodeList as $nodeInfo )
            {
                if ( $nodeInfo['is_main'] )
                {
                    $mainNodeInfo =& $nodeInfo;
                    break;
                }
            }
            if ( $mainNodeInfo )
            {
                $existingMainNode = eZContentObjectTreeNode::fetchByRemoteID( $mainNodeInfo['parent_remote_id'], false );
                if ( $existingMainNode )
                {
                    $updateSection = false;
                    eZContentObjectTreeNode::updateMainNodeID( $existingMainNode['node_id'],
                                                               $mainNodeInfo['contentobject_id'],
                                                               $mainNodeInfo['contentobject_version'],
                                                               $mainNodeInfo['parent_node'],
                                                               $updateSection );
                }
            }
            unset( $mainNodeInfo );
            // Refresh $contentObject from DB.
            $contentObject = eZContentObject::fetch( $contentObject->attribute( 'id' ) );
        }
        if ( !$activeVersion )
        {
            $activeVersion = $lastVersion;
        }

        /*
        $contentObject->setAttribute( 'current_version', $activeVersion );
        */
        $contentObject->setAttribute( 'name', $name );
        if ( isset( $options['use_dates_from_package'] ) && $options['use_dates_from_package'] )
        {
            $contentObject->setAttribute( 'published', eZDateUtils::textToDate( $domNode->getAttributeNS( 'http://ez.no/ezobject', 'published' ) ) );
            $contentObject->setAttribute( 'modified', eZDateUtils::textToDate( $domNode->getAttributeNS( 'http://ez.no/ezobject', 'modified' ) ) );
        }
        $contentObject->store();

        $versions   = $contentObject->versions();
        $objectName = $contentObject->name();
        $objectID   = $contentObject->attribute( 'id' );
        foreach ( $versions as $version )
        {
            $versionNum       = $version->attribute( 'version' );
            $oldVersionStatus = $version->attribute( 'status' );
            $newVersionStatus = isset( $versionList[$versionNum] ) ? $versionList[$versionNum]['status'] : null;

            // set the correct status for non-published versions
            if ( isset( $newVersionStatus ) && $oldVersionStatus != $newVersionStatus && $newVersionStatus != eZContentObjectVersion::STATUS_PUBLISHED )
            {
                $version->setAttribute( 'status', $newVersionStatus );
                $version->store( array( 'status' ) );
            }

            // when translation does not have object name set then we copy object name from the current object version
            $translations = $version->translations( false );
            if ( !$translations )
                continue;
            foreach ( $translations as $translation )
            {
                if ( ! $contentObject->name( $versionNum, $translation ) )
                {
                    eZDebug::writeNotice( "Setting name '$objectName' for version ($versionNum) of the content object ($objectID) in language($translation)" );
                    $contentObject->setName( $objectName, $versionNum, $translation );
                }
            }
        }

        foreach ( $versionList[$versionListActiveVersion]['node_list'] as $nodeInfo )
        {
            unset( $parentNode );
            $parentNode = eZContentObjectTreeNode::fetchNode( $contentObject->attribute( 'id' ),
                                                               $nodeInfo['parent_node'] );
            if ( is_object( $parentNode ) )
            {
                $parentNode->setAttribute( 'priority', $nodeInfo['priority'] );
                $parentNode->store( array( 'priority' ) );
            }
        }

        $db->commit();

        return $contentObject;
    }

    /*!
      Performs additional unserialization actions that need to be performed when all
      objects contained in the package are already installed. (maintain objects' cross-relations)
    */

    function postUnserialize( $package )
    {
        foreach( $this->versions() as $version )
        {
            $version->postUnserialize( $package );
        }
    }

    /*!
     \return a DOM structure of the content object and it's attributes.

     \param package
     \param Content object version, true for current version, false for all, else array containing specific versions.
     \param package options ( optianal )
     \param array of allowed nodes ( optional )
     \param array of top nodes in current package export (optional )

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function serialize( $package, $specificVersion = false, $options = false, $contentNodeIDArray = false, $topNodeIDArray = false )
    {
        if ( $options &&
             $options['node_assignment'] == 'main' )
        {
            if ( !isset( $contentNodeIDArray[$this->attribute( 'main_node_id' )] ) )
            {
                return false;
            }
        }

        $dom = new DomDocument();
        $objectNode = $dom->createElementNS( 'http://ez.no/ezobject', 'ezremote:object' );

        $objectNode->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:id', $this->ID );
        $objectNode->setAttribute( 'name', $this->Name );
        $objectNode->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:section_id', $this->SectionID );
        $objectNode->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:owner_id', $this->OwnerID );
        $objectNode->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:class_id', $this->ClassID );
        $objectNode->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:published', eZDateUtils::rfc1123Date( $this->attribute( 'published' ) ) );
        $objectNode->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:modified', eZDateUtils::rfc1123Date( $this->attribute( 'modified' ) ) );
        if ( !$this->attribute( 'remote_id' ) )
        {
            $this->setAttribute( 'remote_id', md5( (string)mt_rand() ) . (string)time() );
            $this->store();
        }
        $objectNode->setAttribute( 'remote_id', $this->attribute( 'remote_id' ) );
        $contentClass = $this->attribute( 'content_class' );
        $objectNode->setAttribute( 'class_remote_id', $contentClass->attribute( 'remote_id' ) );
        $objectNode->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:class_identifier', $contentClass->attribute( 'identifier' ) );
        $alwaysAvailableText = '0';
        if ( (int)$this->attribute( 'language_mask' ) & 1 )
        {
            $alwaysAvailableText = '1';
        }
        $objectNode->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:always_available', $alwaysAvailableText );

        $versions = array();
        $oneLanguagePerVersion = false;
        if ( $specificVersion === false )
        {
            $versions = $this->versions();
            // Since we are exporting all versions it should only contain
            // one language per version
            //$oneLanguagePerVersion = true; // uncomment to get one language per version
        }
        else if ( $specificVersion === true )
        {
            $versions[] = $this->currentVersion();
        }
        else
        {
            $versions[] = $this->version( $specificVersion );
            // Since we are exporting a specific version it should only contain
            // one language per version?
            $oneLanguagePerVersion = true;
        }

        $this->fetchClassAttributes();

        $exportedLanguages = array();

        $versionsNode = $dom->createElementNS( 'http://ez.no/object/', 'ezobject:version-list' );
        $versionsNode->setAttribute( 'active_version', $this->CurrentVersion );
        foreach ( $versions as $version )
        {
            if ( !$version )
            {
                continue;
            }
            $options['only_initial_language'] = $oneLanguagePerVersion;
            $versionNode = $version->serialize( $package, $options, $contentNodeIDArray, $topNodeIDArray );
            if ( $versionNode )
            {
                $importedVersionNode = $dom->importNode( $versionNode, true );
                $versionsNode->appendChild( $importedVersionNode );
                foreach ( $versionNode->getElementsByTagName( 'object-translation' ) as $versionNodeChild )
                {
                    $exportedLanguage = $versionNodeChild->getAttribute( 'language' );
                    $exportedLanguages[] = $exportedLanguage;
                    $exportedLanguages = array_unique( $exportedLanguages );
                }
            }
            unset( $versionNode );
            unset( $versionNode );
        }
        $initialLanguageCode = $this->attribute( 'initial_language_code' );
        if ( in_array( $initialLanguageCode, $exportedLanguages ) )
        {
            $objectNode->setAttribute( 'initial_language', $initialLanguageCode );
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
            $user = eZUser::currentUser();
            $language = false;
            if ( isset( $Params['Language'] ) )
            {
                $language = $Params['Language'];
            }
            $roleList = $user->roleIDList();
            $discountList = eZUserDiscountRule::fetchIDListByUserID( $user->attribute( 'contentobject_id' ) );
            $contentCacheInfo = array( 'language' => $language,
                                       'role_list' => $roleList,
                                       'discount_list' => $discountList );
        }
        return $contentCacheInfo;
    }

    /*!
     Sets all view cache files to be expired
    */
    static function expireAllViewCache()
    {
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-view-cache', time() );
        $handler->store();
    }

    /*!
     Sets all content cache files to be expired. Both view cache and cache blocks are expired.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function expireAllCache()
    {
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-view-cache', time() );
        $handler->setTimestamp( 'template-block-cache', time() );
        $handler->setTimestamp( 'content-tree-menu', time() );
        $handler->store();
    }

    /*!
     Expires all template block cache. This should be expired anytime any content
     is published/modified or removed.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function expireTemplateBlockCache()
    {
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'template-block-cache', time() );
        $handler->store();
    }

    /*!
    \static
     Callse eZContentObject::xpireTemplateBlockCache() unless template caching is disabled.
     */
    static function expireTemplateBlockCacheIfNeeded()
    {
        $ini = eZINI::instance();
        if ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) == 'enabled' )
            eZContentObject::expireTemplateBlockCache();
    }

    /*!
     Sets all complex viewmode content cache files to be expired.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function expireComplexViewModeCache()
    {
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-complex-viewmode-cache', time() );
        $handler->store();
    }

    /*!
     \return if the content cache timestamp \a $timestamp is expired.
    */
    static function isCacheExpired( $timestamp )
    {
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        if ( !$handler->hasTimestamp( 'content-view-cache' ) )
            return false;
        $expiryTime = $handler->timestamp( 'content-view-cache' );
        if ( $expiryTime > $timestamp )
            return true;
        return false;
    }

    /*!
     \return true if the viewmode is a complex viewmode.
    */
    static function isComplexViewMode( $viewMode )
    {
        $ini = eZINI::instance();
        $viewModes = $ini->variableArray( 'ContentSettings', 'ComplexDisplayViewModes' );
        return in_array( $viewMode, $viewModes );
    }

    /*!
     \return true if the viewmode is a complex viewmode and the viewmode timestamp is expired.
    */
    static function isComplexViewModeCacheExpired( $viewMode, $timestamp )
    {
        if ( !eZContentObject::isComplexViewMode( $viewMode ) )
            return false;
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
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
    function authorArray()
    {
        $db = eZDB::instance();

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
    static function fetchObjectCountByUserID( $classID, $userID )
    {
        $count = 0;
        if ( is_numeric( $classID ) and is_numeric( $userID ) )
        {
            $db = eZDB::instance();
            $classID =(int) $classID;
            $userID =(int) $userID;
            $countArray = $db->arrayQuery( "SELECT count(*) AS count FROM ezcontentobject WHERE contentclass_id=$classID AND owner_id=$userID" );
            $count = $countArray[0]['count'];
        }
        return $count;
    }

     /*!
     \static
      \deprecated This method is left here only for backward compatibility.
                  Use eZContentObjectVersion::removeVersions() method instead.
     */
     static function removeVersions( $versionStatus = false )
     {
         eZContentObjectVersion::removeVersions( $versionStatus );
     }

    /*!
     Sets the object's name to $newName: tries to find attributes that are in 'object pattern name'
     and updates them.
     \return \c true if object's name was changed, otherwise \c false.
    */
    function rename( $newName )
    {
        // get 'object name pattern'
        $objectNamePattern = '';
        $contentClass = $this->contentClass();
        if ( is_object( $contentClass ) )
            $objectNamePattern = $contentClass->ContentObjectName;

        if ( $objectNamePattern == '' )
            return false;

        // get parts of object's name pattern( like <attr1|attr2>, <attr3> )
        $objectNamePatternPartsPattern = '/<([^>]+)>/U';
        preg_match_all( $objectNamePatternPartsPattern, $objectNamePattern, $objectNamePatternParts );

        if( count( $objectNamePatternParts ) === 0 || count( $objectNamePatternParts[1] ) == 0 )
            return false;

        $objectNamePatternParts = $objectNamePatternParts[1];

        // replace all <...> with (.*)
        $newNamePattern = preg_replace( $objectNamePatternPartsPattern, '(.*)', $objectNamePattern );
        // add terminators
        $newNamePattern = '/' . $newNamePattern . '/';

        // find parts of $newName
        preg_match_all( $newNamePattern, $newName, $newNameParts );

        // looks ok, we can create new version of object
        $contentObjectVersion = $this->createNewVersion();
        // get contentObjectAttributes
        $dataMap = $contentObjectVersion->attribute( 'data_map' );
        if ( count( $dataMap ) === 0 )
            return false;

        // assign parts of $newName to the object's attributes.
        $pos = 0;
        while( $pos < count( $objectNamePatternParts ) )
        {
            $attributes = $objectNamePatternParts[$pos];

            // if we have something like <attr1|attr2> then
            // 'attr1' will be updated only.
            $attributes = explode( '|', $attributes );
            $attribute = $attributes[0];

            $newNamePart = $newNameParts[$pos+1];
            if ( count( $newNamePart ) === 0 )
            {
                if( $pos === 0 )
                {
                    // whole $newName goes into the first attribute
                    $attributeValue = $newName;
                }
                else
                {
                    // all other attibutes will be set to ''
                    $attributeValue = '';
                }
            }
            else
            {
                $attributeValue = $newNamePart[0];
            }

            $contentAttribute =& $dataMap[$attribute];
            $dataType = $contentAttribute->dataType();
            if( is_object( $dataType ) && $dataType->isSimpleStringInsertionSupported() )
            {
                $result = '';
                $dataType->insertSimpleString( $this, $contentObjectVersion, false, $contentAttribute, $attributeValue, $result );
                $contentAttribute->store();
            }

            ++$pos;
        }

        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $this->attribute( 'id' ),
                                                                                     'version' => $contentObjectVersion->attribute( 'version') ) );
        return ($operationResult != null ? true : false);
    }

    function removeTranslation( $languageID )
    {
        $language = eZContentLanguage::fetch( $languageID );

        if ( !$language )
        {
            return false;
        }

        // check permissions for editing
        if ( !$this->checkAccess( 'edit', false, false, false, $languageID ) )
        {
            return false;
        }

        // check if it is not the initial language
        $objectInitialLanguageID = $this->attribute( 'initial_language_id' );
        if ( $objectInitialLanguageID == $languageID )
        {
            return false;
        }

        // change language_mask of the object
        $languageMask = (int) $this->attribute( 'language_mask' );
        $languageMask = (int) $languageMask & ~ (int) $languageID;
        $this->setAttribute( 'language_mask', $languageMask );

        $db = eZDB::instance();
        $db->begin();

        $this->store();

        $objectID = $this->ID;

        // If the current version has initial_language_id $languageID, change it to the initial_language_id of the object.
        $currentVersion = $this->currentVersion();
        if ( $currentVersion->attribute( 'initial_language_id' ) == $languageID )
        {
            $currentVersion->setAttribute( 'initial_language_id', $objectInitialLanguageID );
            $currentVersion->store();
        }

        // Remove all versions which had the language as its initial ID. Because of previous checks, it is sure we will not remove the published version.
        $versionsToRemove = $this->versions( true, array( 'conditions' => array( 'initial_language_id' => $languageID ) ) );
        foreach ( $versionsToRemove as $version )
        {
            $version->removeThis();
        }

        $altLanguageID = $languageID++;

        // Remove all attributes in the language
        $attributes = $db->arrayQuery( "SELECT * FROM ezcontentobject_attribute
                                        WHERE contentobject_id='$objectID'
                                          AND ( language_id='$languageID' OR language_id='$altLanguageID' )" );
        foreach ( $attributes as $attribute )
        {
            $attributeObject = new eZContentObjectAttribute( $attribute );
            $attributeObject->remove( $attributeObject->attribute( 'id' ), $attributeObject->attribute( 'version' ) );
            unset( $attributeObject );
        }

        // Remove all names in the language
        $db->query( "DELETE FROM ezcontentobject_name
                     WHERE contentobject_id='$objectID'
                       AND ( language_id='$languageID' OR language_id='$altLanguageID' )" );

        // Update masks of the objects
        $mask = eZContentLanguage::maskForRealLanguages() - (int) $languageID;

        if ( $db->databaseName() == 'oracle' )
        {
            $db->query( "UPDATE ezcontentobject_version SET language_mask = bitand( language_mask, $mask )
                         WHERE contentobject_id='$objectID'" );
        }
        else
        {
            $db->query( "UPDATE ezcontentobject_version SET language_mask = language_mask & $mask
                         WHERE contentobject_id='$objectID'" );
        }

        $urlElementfilter = new eZURLAliasQuery();
        $urlElementfilter->type = 'name';
        // We want all languages present here, so we are turning off
        // language filtering
        $urlElementfilter->languages = false;
        $urlElementfilter->limit = false;

        $nodes = $this->assignedNodes();

        foreach ( $nodes as $node )
        {
            $parent = null;
            $textMD5 = null;

            $urlElementfilter->actions = array( 'eznode:' . $node->attribute( 'node_id' ) );
            $urlElementfilter->prepare();
            $urlElements = $urlElementfilter->fetchAll();

            foreach ($urlElements as $url )
            {
                if ( $url->attribute( 'lang_mask' ) === (int)$languageID or
                     $url->attribute( 'lang_mask') === (int)$altLanguageID )
                {
                    $parent = $url->attribute( 'parent');
                    $textMD5 = $url->attribute( 'text_md5' );
                    break;
                }
            }

            if ( $parent !== null and $textMD5 !== null )
                eZURLAliasML::removeSingleEntry( $parent, $textMD5, $language );
        }
        $db->commit();

        return true;
    }

    function isAlwaysAvailable()
    {
        return ( $this->attribute( 'language_mask' ) & 1 );
    }

    function setAlwaysAvailableLanguageID( $languageID, $version = false )
    {
        $db = eZDB::instance();
        $db->begin();

        if ( $version == false )
        {
            $version = $this->currentVersion();
            if ( $languageID )
            {
                $this->setAttribute( 'language_mask', (int)$this->attribute( 'language_mask' ) | 1 );
            }
            else
            {
                $this->setAttribute( 'language_mask', (int)$this->attribute( 'language_mask' ) & ~1 );
            }
            $this->store();
        }

        $objectID = $this->attribute( 'id' );
        $versionID = $version->attribute( 'version' );

        // reset 'always available' flag
        $sql = "UPDATE ezcontentobject_name SET language_id=";
        if ( $db->databaseName() == 'oracle' )
        {
            $sql .= "bitand( language_id, -2 )";
        }
        else
        {
            $sql .= "language_id & ~1";
        }
        $sql .= " WHERE contentobject_id = '$objectID' AND content_version = '$versionID'";
        $db->query( $sql );

        if ( $languageID != false )
        {
            $newLanguageID = $languageID | 1;
            $sql = "UPDATE ezcontentobject_name
                    SET language_id='$newLanguageID'
                    WHERE language_id='$languageID' AND contentobject_id = '$objectID' AND content_version = '$versionID'";
            $db->query( $sql );
        }

        $version->setAlwaysAvailableLanguageID( $languageID );

        // Update url alias for all locations
        $nodeRows = eZContentObjectTreeNode::fetchByContentObjectID( $objectID, false );
        $actions = array();
        foreach ( $nodeRows as $nodeRow )
        {
            $nodeID = (int)$nodeRow['node_id'];
            $actions[] = array( 'eznode', $nodeID );
        }
        eZURLAliasML::setLangMaskAlwaysAvailable( $languageID, $actions, null );

        $db->commit();
    }

    function allowedAssignSectionList()
    {
        $currentUser = eZUser::currentUser();
        $sectionIDList = $currentUser->canAssignToObjectSectionList( $this );

        $sectionList = array();
        if ( in_array( '*', $sectionIDList ) )
        {
            $sectionList = eZSection::fetchList( false );
        }
        else
        {
            $sectionIDList[] = $this->attribute( 'section_id' );
            $sectionList = eZSection::fetchFilteredList( array( 'id' => array( $sectionIDList ) ), false, false, false );
        }
        return $sectionList;
    }

    /**
     * Gets a list of states a user is allowed to put the content object in.
     *
     * @return array the IDs of all states we are allowed to set
     * @param eZUser $user the user to check the policies of, when omitted the currently logged in user will be used
     */
    function allowedAssignStateIDList( eZUser $user = null )
    {
        if ( !$user instanceof eZUser )
        {
            $user = eZUser::currentUser();
        }

        $access = $user->hasAccessTo( 'state', 'assign' );

        $db = eZDB::instance();
        if ( $access['accessWord'] == 'yes' )
        {
            $sql = 'SELECT ezcobj_state.id
                    FROM   ezcobj_state, ezcobj_state_group
                    WHERE  ezcobj_state.group_id = ezcobj_state_group.id
                       AND ezcobj_state_group.identifier NOT LIKE \'ez%\'';

            $allowedStateIDList = $db->arrayQuery( $sql, array( 'column' => 'id' ) );
        }
        else if ( $access['accessWord'] == 'limited' )
        {
            $userID = $user->attribute( 'contentobject_id' );
            $classID = $this->attribute( 'contentclass_id' );
            $ownerID = $this->attribute( 'owner_id' );
            $sectionID = $this->attribute( 'section_id' );
            $stateIDArray = $this->attribute( 'state_id_array' );

            $allowedStateIDList = array();
            foreach ( $access['policies'] as $policy )
            {
                foreach ( $policy as $ident => $values )
                {
                    $allowed = true;

                    switch ( $ident )
                    {
                        case 'Class':
                        {
                            $allowed = in_array( $classID, $values );
                        } break;

                        case 'Owner':
                        {
                            $allowed = in_array( 1, $values ) and $userID != $ownerID;
                        } break;

                        case 'Group':
                        {
                            $allowed = $this->checkGroupLimitationAccess( $values, $userID ) === 'allowed';
                        } break;

                        case 'Section':
                        case 'User_Section':
                        {
                            $allowed = in_array( $sectionID, $values );
                        } break;

                        default:
                        {
                            if ( strncmp( $ident, 'StateGroup_', 11 ) === 0 )
                            {
                                $allowed = count( array_intersect( $values, $stateIDArray ) ) > 0;
                            }
                        }
                    }

                    if ( !$allowed )
                    {
                        continue 2;
                    }
                }

                if ( isset( $policy['NewState'] ) and count( $policy['NewState'] ) > 0 )
                {
                    $allowedStateIDList = array_merge( $allowedStateIDList, $policy['NewState'] );
                }
                else
                {
                    $allowedStateIDList = $db->arrayQuery( $sql, array( 'column' => 'id' ) );
                    break;
                }
            }

            $allowedStateIDList = array_merge( $allowedStateIDList, $stateIDArray );
        }
        else
        {
            $stateIDArray = $this->attribute( 'state_id_array' );
            $allowedStateIDList = $stateIDArray;
        }

        $allowedStateIDList = array_unique( $allowedStateIDList );

        return $allowedStateIDList;
    }

    function allowedAssignStateList( eZUser $user = null )
    {
        $allowedStateIDList = $this->allowedAssignStateIDList( $user );

        // retrieve state groups, and for each state group the allowed states (including the current state)
        $groups = eZContentObjectStateGroup::fetchByOffset( false, false );

        $allowedAssignList = array();
        foreach ( $groups as $group )
        {
            // we do not return any internal state
            // all internal states are prepended with the string : "ez_"
            if( strpos( $group->attribute( 'identifier' ), 'ez' ) === 0 )
                continue;

            $states = array();
            $groupStates = $group->attribute( 'states' );

            $currentStateIDArray = $this->attribute( 'state_id_array' );

            $current = false;
            foreach ( $groupStates as $groupState )
            {
                $stateID = $groupState->attribute( 'id' );
                if ( in_array( $stateID, $allowedStateIDList ) )
                {
                    $states[] = $groupState;
                }

                if ( in_array( $stateID, $currentStateIDArray ) )
                {
                    $current = $groupState;
                }
            }

            $allowedAssignList[] = array( 'group' => $group, 'states' => $states, 'current' => $current );
        }
        return $allowedAssignList;
    }

    /**
     * Gets the current states of the content object.
     *
     * Uses a member variable that caches the result.
     *
     * @return array an associative array with state group id => state id pairs
     * @param boolean $refreshCache if the cache in the member variable needs to be refreshed
     */
    function stateIDArray( $refreshCache = false )
    {
        if ( !$this->ID )
            return array();

        if ( $refreshCache || !is_array( $this->StateIDArray ) )
        {
            $this->StateIDArray = array();
            $contentObjectID = $this->ID;
            eZDebug::accumulatorStart( 'state_id_array', 'states' );
            $sql = "SELECT contentobject_state_id, group_id FROM ezcobj_state_link, ezcobj_state
                    WHERE ezcobj_state.id=ezcobj_state_link.contentobject_state_id AND
                          contentobject_id=$contentObjectID";
            $db = eZDB::instance();
            $rows = $db->arrayQuery( $sql );
            foreach ( $rows as $row )
            {
                $this->StateIDArray[$row['group_id']] = $row['contentobject_state_id'];
            }
            eZDebug::accumulatorStop( 'state_id_array' );
        }

        return $this->StateIDArray;
    }

    function stateIdentifierArray()
    {
        if ( !$this->ID )
            return array();

        eZDebug::accumulatorStart( 'state_identifier_array', 'states' );
        $return = array();
        $sql = "SELECT l.contentobject_state_id, s.identifier AS state_identifier, g.identifier AS state_group_identifier
                FROM ezcobj_state_link l, ezcobj_state s, ezcobj_state_group g
                WHERE l.contentobject_id={$this->ID} AND
                      s.id=l.contentobject_state_id AND
                      g.id=s.group_id";
        $db = eZDB::instance();
        $rows = $db->arrayQuery( $sql );
        foreach ( $rows as $row )
        {
            $return[] = $row['state_group_identifier'] . '/' . $row['state_identifier'];
        }
        eZDebug::accumulatorStop( 'state_identifier_array' );
        return $return;
    }

    /**
     * Sets the state of a content object.
     *
     * Changes are stored immediately in the database, does not require a store() of the content object.
     * Should only be called on instances of eZContentObject that have a ID (that were stored already before).
     *
     * @param eZContentObjectState $state
     * @return boolean true when the state was set, false if the state equals the current state
     */
    function assignState( eZContentObjectState $state )
    {
        $groupID = $state->attribute( 'group_id' );
        $stateID = $state->attribute( 'id' );
        $contentObjectID = $this->ID;

        $db = eZDB::instance();
        $db->begin();

        $currentStateIDArray = $this->stateIDArray( true );
        $currentStateID = $currentStateIDArray[$groupID];

        if ( $currentStateID == $stateID )
        {
            $db->rollback();
            return false;
        }

        $sql = "UPDATE ezcobj_state_link
                SET contentobject_state_id=$stateID
                WHERE contentobject_state_id=$currentStateID AND
                      contentobject_id=$contentObjectID";
        $db->query( $sql );

        $db->commit();

        $this->StateIDArray[$groupID] = $stateID;

        return true;
    }

    /**
     * Sets the default states of a content object.
     *
     * This function is called upon instantiating a content object with {@link eZContentClass::instantiate()}, so
     * should normally not be called by any other code.
     */
    function assignDefaultStates()
    {
        $db = eZDB::instance();
        $db->begin();
        $defaultStates = eZContentObjectState::defaults();
        $contentObjectID = $this->ID;
        foreach ( $defaultStates as $state )
        {
            $stateID = $state->attribute( 'id' );
            $db->query( "INSERT INTO ezcobj_state_link (contentobject_state_id, contentobject_id)
                         VALUES($stateID, $contentObjectID)" );
        }
        $db->commit();
    }

    public $ID;
    public $Name;

    /// Stores the current language
    public $CurrentLanguage;

    /// Stores the current class name
    public $ClassName;

    /// Cached class identifier
    public $ClassIdentifier;

    /// Contains the datamap for content object attributes
    public $DataMap = array();

    /// Contains an array of the content object actions for the current object
    public $ContentActionList = false;

    /// Contains a cached version of the content object attributes for the given version and language
    public $ContentObjectAttributes = array();

    /// Contains the main node id for this object
    public $MainNodeID = false;

    /// Contains the arrays of relatedobject id by fetching input for this object
    public $InputRelationList = array();

    /**
     * Cache for the state ID array
     *
     * @var array
     * @see eZContentObject::stateIDArray()
     */
    private $StateIDArray = false;
}

?>