<?php
/**
 * File containing the eZContentObject class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Encapsulates data about and methods to work with content objects
 *
 * @package kernel
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

    /**
     * Initializes the object with $row.
     *
     * If $row is an integer, it will try to fetch it from the database using it as the unique ID.
     *
     * @param int|array $row
     */
    public function __construct( $row )
    {
        parent::__construct( $row );
        $this->ClassIdentifier = false;
        if ( isset( $row['contentclass_identifier'] ) )
            $this->ClassIdentifier = $row['contentclass_identifier'];
        if ( isset( $row['class_identifier'] ) )
            $this->ClassIdentifier = $row['class_identifier'];
        $this->ClassName = false;
        // Depending on how the information is retrieved, the "serialized_name_list" is sometimes available in "class_serialized_name_list" key
        if ( isset( $row['class_serialized_name_list'] ) )
            $row['serialized_name_list'] = $row['class_serialized_name_list'];
        // Depending on how the information is retrieved, the "contentclass_name" is sometimes available in "class_name" key
        if ( isset( $row['class_name'] ) )
            $row['contentclass_name'] = $row['class_name'];
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

        // Initialize the permission array cache
        $this->Permissions = array();
    }

    static function definition()
    {
        static $definition = array( "fields" => array( "id" => array( 'name' => 'ID',
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
                                                      "published_version" => "publishedVersion",
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
                                                      "grouped_data_map" => "groupedDataMap",
                                                      "main_parent_node_id" => "mainParentNodeID",
                                                      "assigned_nodes" => "assignedNodes",
                                                      "visible_nodes" => "visibleNodes",
                                                      "has_visible_node" => "hasVisibleNode",
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
                                                      'state_identifier_array' => 'stateIdentifierArray',
                                                      'section_identifier' => 'sectionIdentifier' ),
                      "increment_key" => "id",
                      "class_name" => "eZContentObject",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentobject" );
        return $definition;
    }

    /**
     * Get class groups this object's class belongs to if match for class groups is enabled, otherwise false
     *
     * @return array|bool
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

    /**
     * Stores the object
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param array $fieldFilters
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
        parent::store( $fieldFilters );
        $db->commit();
    }

    /**
     * Clear in-memory caches.
     *
     * If $idArray is ommitted, the caches are cleared for all objects.
     *
     * @param array $idArray Objects to clear caches for.
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

    /**
     * Update all nodes to set modified_subnode value
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
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

    /**
     * Returns an object's name for the version given by $version in the language given by $lang
     *
     * @param int|bool $version If omitted, the current version will be used
     * @param bool $lang If omitted, the initial object language will be used
     * @return bool|string
     */
    function name( $version = false , $lang = false )
    {
        // if the object id is null, we can't read data from the database
        // and return the locally known name
        if ( $this->attribute( 'id' ) === null )
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

    /**
     * Returns all translations of the current object's name
     *
     * @return string[]
     */
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

    /**
     * Returns the object name for version $version in the language $lang
     *
     * @param int $version
     * @param string|bool $lang If omitted, the initial language of the object is used
     * @return string|bool
     */
    function versionLanguageName( $version, $lang = false )
    {
        $name = false;
        if ( !$version > 0 )
        {
            eZDebug::writeNotice( "There is no object name for version($version) of the content object ($contentObjectID) in language($lang)", __METHOD__ );
            return $name;
        }
        $db = eZDB::instance();
        $contentObjectID = $this->attribute( 'id' );
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

        $languageID = $this->attribute( 'initial_language_id' );
        if ( $this->attribute( 'always_available' ) )
        {
            $languageID = (int) $languageID | 1;
        }

        $query= "SELECT name, content_translation
                 FROM ezcontentobject_name
                 WHERE contentobject_id = '$contentObjectID'
                       AND content_version = '$version'
                       AND ( content_translation = '$lang' OR language_id = '$languageID' )";
        $result = $db->arrayQuery( $query );

        $resCount = count( $result );
        if( $resCount < 1 )
        {
            eZDebug::writeNotice( "There is no object name for version($version) of the content object ($contentObjectID) in language($lang)", __METHOD__ );
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

    /**
     * Sets the name of the object, in memory only. Use {@see setName()} to change it permanently
     *
     * @param string $name
     */
    function setCachedName( $name )
    {
        $this->Name = $name;
    }

    /**
     * Sets the name of the object in all translations.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param string $objectName
     * @param int|bool $versionNum
     * @param int|bool $languageCode
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

    /**
     * Return a map with all the content object attributes where the keys are the attribute identifiers.
     *
     * @return eZContentObjectAttribute[]
     */
    function dataMap()
    {
        return $this->fetchDataMap();
    }

    /**
     * Generates a map with all the content object attributes where the keys are
     * the attribute identifiers grouped by class attribute category.
     *
     * Result is not cached, so make sure you don't call this over and over.
     *
     * @return array
     */
    public function groupedDataMap()
    {
        return self::createGroupedDataMap( $this->fetchDataMap() );
    }

    /**
     * Generates a map with all the content object attributes where the keys are
     * the attribute identifiers grouped by class attribute category.
     *
     * Result is not cached, so make sure you don't call this over and over.
     *
     * @param eZContentObjectAttribute[] $contentObjectAttributes Array of eZContentObjectAttribute objects
     * @return array
     */
    public static function createGroupedDataMap( $contentObjectAttributes )
    {
        $groupedDataMap  = array();
        $contentINI      = eZINI::instance( 'content.ini' );
        $categorys       = $contentINI->variable( 'ClassAttributeSettings', 'CategoryList' );
        $defaultCategory = $contentINI->variable( 'ClassAttributeSettings', 'DefaultCategory' );
        foreach( $contentObjectAttributes as $attribute )
        {
            $classAttribute      = $attribute->contentClassAttribute();
            $attributeCategory   = $classAttribute->attribute('category');
            $attributeIdentifier = $classAttribute->attribute( 'identifier' );
            if ( !isset( $categorys[ $attributeCategory ] ) || !$attributeCategory )
                $attributeCategory = $defaultCategory;

            if ( !isset( $groupedDataMap[ $attributeCategory ] ) )
                $groupedDataMap[ $attributeCategory ] = array();

            $groupedDataMap[ $attributeCategory ][$attributeIdentifier] = $attribute;

        }
        return $groupedDataMap;
    }

    /**
     * Returns a map with all the content object attributes where the keys are the attribute identifiers.
     *
     * @param int|bool $version
     * @param string|bool $language
     * @return eZContentObjectAttribute[]
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
            /* @var eZContentObjectAttribute[] $data */
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

    /**
     * Resets (empties) the current object's data map
     *
     * @return array
     */
    function resetDataMap()
    {
        $this->ContentObjectAttributeArray = array();
        $this->ContentObjectAttributes = array();
        $this->DataMap = array();
        return $this->DataMap;
    }

    /**
     * Fetches a set of content object attributes by their class identifiers.
     *
     * @param string[] $identifierArray
     * @param int|bool $version
     * @param string[]|bool $languageArray
     * @param bool $asObject If true, returns an array of eZContentObjectAttributes, a normal array otherwise
     *
     * @return eZContentObjectAttribute[]|array|null
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
                $languageText = "AND ";
                $languageText .= $db->generateSQLINStatement( $langCodeQuotedString, 'ezcontentobject_attribute.language_code' );
            }
        }

        if ( !isset( $languageText ) )
        {
            $languageText = "AND " . eZContentLanguage::sqlFilter( 'ezcontentobject_attribute', 'ezcontentobject_version' );
        }

        $versionText = "AND ezcontentobject_attribute.version = '$version'";

        $query = "SELECT ezcontentobject_attribute.*, ezcontentclass_attribute.identifier as identifier
            FROM ezcontentobject_attribute, ezcontentclass_attribute, ezcontentobject_version
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

    /**
     * Returns the owner of the object as a content object.
     *
     * @return eZContentObject|null
     */
    function owner()
    {
        if ( $this->OwnerID != 0 )
        {
            return eZContentObject::fetch( $this->OwnerID );
        }
        return null;
    }

    /**
     * Returns the content class group identifiers for the current content object
     *
     * @return array
     */
    function contentClassGroupIDList()
    {
        $contentClass = $this->contentClass();
        return $contentClass->attribute( 'ingroup_id_list' );
    }

    /**
     * Returns the content class identifer for the current content object
     *
     * The object will cache the class name information so multiple calls will be fast.
     *
     * @return string|bool|null
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

    /**
     * Returns the content class for the current content object
     *
     * @return eZContentClass|null
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

    /**
     * Returns the remote id of the current content object
     *
     * @return string
     */
    function remoteID()
    {
        $remoteID = $this->attribute( 'remote_id', true );

        if ( !$remoteID )
        {
            $this->setAttribute( 'remote_id', eZRemoteIdUtility::generate( 'object' ) );
            if ( $this->attribute( 'id' ) !== null )
                $this->sync( array( 'remote_id' ) );
            $remoteID = $this->attribute( 'remote_id', true );
        }

        return $remoteID;
    }

    /**
     * Returns the ID of the the current object's main node
     *
     * @return int|null
     */
    function mainParentNodeID()
    {
        $list = eZContentObjectTreeNode::getParentNodeIdListByContentObjectID( $this->ID, false, true );
        return isset( $list[0] ) ? $list[0] : null;
    }

    /**
     * Returns a contentobject by remote ID
     *
     * @param string $remoteID
     * @param bool $asObject
     * @return eZContentObject|array|null
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

    /**
     * Fetches a content object by ID
     *
     * @param int $id ID of the content object to fetch
     * @param bool $asObject Return the result as an object (true) or an assoc. array (false)
     *
     * @return eZContentObject
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
                eZDebug::writeError( "Object not found ($id)", __METHOD__ );
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

    /**
     * Fetches a content object by ID, using SELECT ... FOR UPDATE
     *
     * Ensures the table row is locked, blocking other transactions from locking it (read or write).
     * Usage must be within a transaction, or it will be locked for the current session.
     *
     * @param int $id ID of the content object to fetch
     * @param bool $asObject Return the result as an object (true) or an assoc. array (false)
     *
     * @return eZContentObject|mixed|null
     */
    static function fetchForUpdate( $id, $asObject = true )
    {
        global $eZContentObjectContentObjectCache;

        $db = eZDB::instance();
        $id = (int) $id;

        // Select for update, to lock the row
        $resArray = $db->arrayQuery( "SELECT * FROM ezcontentobject WHERE id='$id' FOR UPDATE" );

        if ( !is_array( $resArray ) || count( $resArray ) !== 1 )
        {
            eZDebug::writeError( "Object not found ($id)", __METHOD__ );
            return null;
        }

        $objectArray = $resArray[0];
        $classId = $objectArray['contentclass_id'];
        $contentClassResArray = $db->arrayQuery(
            "SELECT
                ezcontentclass.serialized_name_list as serialized_name_list,
                ezcontentclass.identifier as contentclass_identifier,
                ezcontentclass.is_container as is_container
            FROM
                ezcontentclass
            WHERE
                ezcontentclass.id='$classId' AND
                ezcontentclass.version=0"
        );
        $objectArray = array_merge($objectArray, $contentClassResArray[0]);

        if ( !$asObject )
        {
            return $objectArray;
        }

        $obj = new eZContentObject( $objectArray );
        $eZContentObjectContentObjectCache[$id] = $obj;

        return $obj;
    }

    /**
     * Returns true, if a content object with the ID $id exists, false otherwise
     *
     * @param int $id
     * @return bool
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

    /**
     * Creates the SQL for fetching the object with ID $id and returns the string.
     *
     * @param int $id
     * @return string
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

    /**
     * Fetches the contentobject which has a node with ID $nodeID
     *
     * $nodeID can also be an array of NodeIDs. In this case, an array of content objects will be returned
     *
     * @param int|array $nodeID Single nodeID or array of NodeIDs
     * @param bool $asObject If results have to be returned as eZContentObject instances or not
     * @return eZContentObject|eZContentObject[]|array|null Content object or array of content objects.
     *               Content objects can be eZContentObject instances or array result sets
     */
    static function fetchByNodeID( $nodeID, $asObject = true )
    {
        global $eZContentObjectContentObjectCache;
        $resultAsArray = is_array( $nodeID );
        $nodeID = (array)$nodeID;

        $db = eZDB::instance();

        $resArray = $db->arrayQuery(
            "SELECT co.*, con.name as name, con.real_translation, cot.node_id
             FROM ezcontentobject co
             JOIN ezcontentobject_tree cot ON co.id = cot.contentobject_id AND co.current_version = cot.contentobject_version
             JOIN ezcontentobject_name con ON co.id = con.contentobject_id AND co.current_version = con.content_version
             WHERE " .
                $db->generateSQLINStatement( $nodeID, 'cot.node_id', false, true, 'int' ) . " AND " .
                eZContentLanguage::sqlFilter( 'con', 'co' )
        );

        if ( $resArray === false || empty( $resArray ) )
        {
            eZDebug::writeError( 'A problem occured while fetching objects with following NodeIDs : ' . implode( ', ', $nodeID ), __METHOD__ );
            return $resultAsArray ? array() : null;
        }

        $objectArray = array();
        if ( $asObject )
        {
            foreach ( $resArray as $res )
            {
                $objectArray[$res['node_id']] = $eZContentObjectContentObjectCache[$res['id']] = new self( $res );
            }
        }
        else
        {
            foreach ( $resArray as $res )
            {
                $objectArray[$res['node_id']] = $res;
            }
        }

        if ( !$resultAsArray )
            return $objectArray[$res['node_id']];

        return $objectArray;
    }

    /**
     * Fetches a content object list based on an array of content object ids
     *
     * @param array $idArray array of content object ids
     * @param bool $asObject
     *        Wether to get the result as an array of eZContentObject or an
     *        array of associative arrays
     * @param bool|string $lang A language code to put at the top of the prioritized
     *        languages list.
     *
     * @return array(contentObjectID => eZContentObject|array)
     *         array of eZContentObject (if $asObject = true) or array of
     *         associative arrays (if $asObject = false)
     */
    static function fetchIDArray( $idArray, $asObject = true , $lang = false )
    {
        global $eZContentObjectContentObjectCache;

        $db = eZDB::instance();

        $resRowArray = $db->arrayQuery(
            "SELECT ezcontentclass.serialized_name_list as class_serialized_name_list, ezcontentobject.*, ezcontentobject_name.name as name,  ezcontentobject_name.real_translation
             FROM
                ezcontentclass,
                ezcontentobject,
                ezcontentobject_name
             WHERE
                ezcontentclass.id=ezcontentobject.contentclass_id AND " .
                // All elements from $idArray should be casted to (int)
                $db->generateSQLINStatement( $idArray, 'ezcontentobject.id', false, true, 'int' ) . " AND
                ezcontentobject.id = ezcontentobject_name.contentobject_id AND
                ezcontentobject.current_version = ezcontentobject_name.content_version AND " .
                eZContentLanguage::sqlFilter( 'ezcontentobject_name', 'ezcontentobject', 'language_id', 'language_mask', $lang )
        );

        $objectRetArray = array();
        foreach ( $resRowArray as $resRow )
        {
            $objectID = $resRow['id'];
            $resRow['class_name'] = eZContentClass::nameFromSerializedString( $resRow['class_serialized_name_list'] );
            if ( $asObject )
            {
                $obj = new eZContentObject( $resRow );
                $obj->ClassName = $resRow['class_name'];
                if ( $lang !== false )
                {
                    $eZContentObjectContentObjectCache[$objectID] = $obj;
                }
                $objectRetArray[$objectID] = $obj;
            }
            else
            {
                $objectRetArray[$objectID] = $resRow;
            }
        }
        return $objectRetArray;
    }

    /**
     * Returns an array of content objects.
     *
     * @param bool $asObject Whether to return objects or not
     * @param array|null $conditions Optional conditions to limit the fetch
     * @param int|bool $offset Where to start fetch from, set to false to skip it.
     * @param int|bool $limit Maximum number of objects to fetch, set false to skip it.
     * @return eZContentObject[]|array|null
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

    /**
     * Returns a filtered array of content objects.
     *
     * @param array|null $conditions Optional conditions to limit the fetch
     * @param int|bool $offset Where to start fetch from, set to false to skip it.
     * @param int|bool $limit Maximum number of objects to fetch, set false to skip it.
     * @param bool $asObject Whether to return objects or not
     * @return array|eZPersistentObject[]|null
     */
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

    /**
     * Returns the number of objects in the database. Optionally \a $conditions can be used to limit the list count.
     * @param array|null $conditions
     * @return int
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

    /**
     * Returns an array of content objects with the content class id $contentClassID
     *
     * @param int $contentClassID
     * @param bool $asObject Whether to return objects or not
     * @param int|bool $offset Where to start fetch from, set to false to skip it.
     * @param int|bool $limit Maximum number of objects to fetch, set false to skip it.

     * @return eZContentObject[]|array|null
     */
    static function fetchSameClassList( $contentClassID, $asObject = true, $offset = false, $limit = false )
    {
        $conditions = array( 'contentclass_id' => $contentClassID );
        return eZContentObject::fetchFilteredList( $conditions, $offset, $limit, $asObject );
    }

    /**
     * Returns the number of content objects with the content class id $contentClassID
     *
     * @param int $contentClassID
     * @return int
     */
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

    /**
     * Returns the current version of this document.
     *
     * @param bool $asObject If true, returns an eZContentObjectVersion; if false, returns an array
     * @return eZContentObjectVersion|array|bool
     */
    function currentVersion( $asObject = true )
    {
        return eZContentObjectVersion::fetchVersion( $this->attribute( "current_version" ), $this->ID, $asObject );
    }

    /**
     * Returns the published version of the object, or null if not published yet.
     *
     * @return  int|null
     */
    public function publishedVersion()
    {
        $params = array(
            'conditions' => array(
                'status' => eZContentObjectVersion::STATUS_PUBLISHED
            )
        );
        $versions = $this->versions( false, $params );

        if ( !empty( $versions ) )
        {
            return $versions[0]['version'];
        }
        return null;
    }

    /**
     * Returns the given object version. False is returned if the versions does not exist.
     *
     * @param int $version
     * @param bool $asObject If true, returns an eZContentObjectVersion; if false, returns an array
     * @return eZContentObjectVersion|array|bool
     */
    function version( $version, $asObject = true )
    {
        if ( $asObject )
        {
            global $eZContentObjectVersionCache;

            if ( !isset( $eZContentObjectVersionCache ) ) // prevent PHP warning below
                $eZContentObjectVersionCache = array();

            if ( isset( $eZContentObjectVersionCache[$this->ID][$version] ) )
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

    /**
     * Returns an array of eZContentObjectVersion for the current object according to the conditions in $parameters.
     *
     * @param bool $asObject If true, returns an eZContentObjectVersion; if false, returns an array
     * @param array $parameters
     * @return eZContentObjectVersion[]|array
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
        $sort = isset( $parameters['sort'] ) ? $parameters['sort'] : null;
        $limit = isset( $parameters['limit'] ) ? $parameters['limit'] : null;

        return eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                    null, $conditions,
                                                    $sort, $limit,
                                                    $asObject );
    }

    /**
     * Returns true if the object has any versions remaining.
     *
     * @return bool
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

    /**
     * Creates an initial content object version
     *
     * @param int $userID
     * @param string|bool $initialLanguageCode
     * @return eZContentObjectVersion
     */
    function createInitialVersion( $userID, $initialLanguageCode = false )
    {
        return eZContentObjectVersion::create( $this->attribute( "id" ), $userID, 1, $initialLanguageCode );
    }

    /**
     * Creates a new version for the language $languageCode
     *
     * @param string $languageCode
     * @param bool $copyFromLanguageCode
     * @param int|bool $copyFromVersion
     * @param bool $versionCheck
     * @param int $status
     *
     * @return eZContentObjectVersion
     */
    function createNewVersionIn( $languageCode, $copyFromLanguageCode = false, $copyFromVersion = false, $versionCheck = true, $status = eZContentObjectVersion::STATUS_DRAFT )
    {
        return $this->createNewVersion( $copyFromVersion, $versionCheck, $languageCode, $copyFromLanguageCode, $status );
    }

    /**
     * Creates a new version and returns it as an eZContentObjectVersion object.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param int|bool $copyFromVersion If given, that version is used to create a copy.
     * @param bool $versionCheck If \c true it will check if there are too many version and remove some of them to make room for a new.
     * @param string|bool $languageCode
     * @param string|bool $copyFromLanguageCode
     * @param int $status
     *
     * @return eZContentObjectVersion
     */
    function createNewVersion( $copyFromVersion = false, $versionCheck = true, $languageCode = false, $copyFromLanguageCode = false, $status = eZContentObjectVersion::STATUS_DRAFT )
    {
        $db = eZDB::instance();
        $db->begin();

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
                                                               $remoteID, $node->attribute( 'remote_id' ) );
            // Reset execution bit
            $newNodeAssignment->setAttribute( 'op_code', $newNodeAssignment->attribute( 'op_code' ) & ~1 );
            $newNodeAssignment->store();
        }

        // Removing last item if we don't have enough space in version list
        if ( $versionCheck )
        {
            $versionlimit = eZContentClass::versionHistoryLimit( $this->attribute( 'contentclass_id' ) );
            $versionCount = $this->getVersionCount();
            if ( $versionCount > $versionlimit )
            {
                // Remove oldest archived version
                $params = array( 'conditions'=> array( 'status' => eZContentObjectVersion::STATUS_ARCHIVED ) );
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

        $db->commit();
        return $copiedVersion;
    }

    /**
     * Creates a new version and returns it as an eZContentObjectVersion object.
     *
     * If version number is given as argument that version is used to create a copy.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param eZContentObject $newObject
     * @param eZContentObjectVersion $version
     * @param int $newVersionNumber
     * @param int|bool $contentObjectID
     * @param int $status
     * @param string|bool $languageCode If false all languages will be copied, otherwise only specified by the locale code string or an array of the locale code strings.
     * @param string|bool $copyFromLanguageCode
     *
     * @return eZContentObjectVersion
     */
    function copyVersion( &$newObject, &$version, $newVersionNumber, $contentObjectID = false, $status = eZContentObjectVersion::STATUS_DRAFT, $languageCode = false, $copyFromLanguageCode = false )
    {
        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        $nodeAssignmentList = $version->attribute( 'node_assignments' );

        $db = eZDB::instance();
        $db->begin();

        // This is part of the new 3.8 code.
        foreach ( $nodeAssignmentList as $nodeAssignment )
        {
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

    /**
     * Creates a new content object instance and stores it.
     *
     * @param string $name
     * @param int $contentclassID
     * @param int $userID
     * @param int $sectionID
     * @param int $version
     * @param string|bool $languageCode
     * @return eZContentObject
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
            'remote_id' => eZRemoteIdUtility::generate( 'object' ) );

        return new eZContentObject( $row );
    }

    /**
     * Resets certain attributes of the content object on clone and resets the data map
     */
    function __clone()
    {
        $this->setAttribute( 'id', null );
        $this->setAttribute( 'published', time() );
        $this->setAttribute( 'modified', time() );
        $this->resetDataMap();
    }

    /**
     * Makes a copy of the object which is stored and then returns it.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param bool $allVersions If true, all versions are copied. If false, only the latest version is copied
     * @return eZContentObject
     */
    function copy( $allVersions = true )
    {
        eZDebugSetting::writeDebug( 'kernel-content-object-copy', 'Copy start, all versions=' . ( $allVersions ? 'true' : 'false' ), 'copy' );
        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        $contentObject = clone $this;
        $contentObject->setAttribute( 'current_version', 1 );
        $contentObject->setAttribute( 'owner_id', $userID );

        $contentObject->setAttribute( 'remote_id', eZRemoteIdUtility::generate( 'object' ) );

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
                    $remoteID = eZRemoteIdUtility::generate( 'object' );
                    // Remove assignments which conflicts with existing nodes, but keep remote_id
                    if ( isset( $parentMap[$node->attribute( 'parent_node_id' )] ) )
                    {
                        $copiedNodeAssignment = $parentMap[$node->attribute( 'parent_node_id' )];
                        unset( $parentMap[$node->attribute( 'parent_node_id' )] );
                        $remoteID = $copiedNodeAssignment->attribute( 'remote_id' );
                        $copiedNodeAssignment->purge();
                    }
                    $contentObjectVersion->assignToNode(
                        $node->attribute( 'parent_node_id' ),
                        $node->attribute( 'is_main' ),
                        0,
                        $node->attribute( 'sort_field' ),
                        $node->attribute( 'sort_order' ),
                        $remoteID
                    );
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

    /**
     * Copies the given version of the object and creates a new current version.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param int $version
     * @param string|bool $language
     *
     * @return int The new version number
     */
    function copyRevertTo( $version, $language = false )
    {
        $versionObject = $this->createNewVersionIn( $language, false, $version );

        return $versionObject->attribute( 'version' );
    }

    /**
     * Fixes reverse relations
     *
     * @see eZObjectRelationListType::fixRelatedObjectItem()
     *
     * @param int $objectID
     * @param string|bool $mode See eZObjectRelationListType::fixRelatedObjectItem() for valid modes
     */
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
            $objectIDList = array();
            foreach( $result as $row )
            {
                $attr = new eZContentObjectAttribute( $row );
                $dataType = $attr->dataType();
                $dataType->fixRelatedObjectItem( $attr, $objectID, $mode );
                $objectIDList[] = $attr->attribute( 'contentobject_id' );
            }
            if ( eZINI::instance()->variable( 'ContentSettings', 'ViewCaching' ) === 'enabled' )
                eZContentCacheManager::clearObjectViewCacheArray( $objectIDList );
        }
    }

    /**
     * Deletes the current object, all versions and translations, and corresponding tree nodes from the database
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     */
    function purge()
    {
        $delID = $this->ID;
        // Who deletes which content should be logged.
        eZAudit::writeAudit( 'content-delete', array( 'Object ID' => $delID, 'Content Name' => $this->attribute( 'name' ),
                                                      'Comment' => 'Purged the current object: eZContentObject::purge()' ) );

        $db = eZDB::instance();

        $db->begin();

        $attrOffset = 0;
        $attrLimit = 20;
        while (
            $contentobjectAttributes = $this->allContentObjectAttributes(
                $delID, true, array( 'limit' => $attrLimit, 'offset' => $attrOffset )
            )
        )
        {
            foreach ( $contentobjectAttributes as $contentobjectAttribute )
            {
                $dataType = $contentobjectAttribute->dataType();
                if ( !$dataType )
                    continue;
                $dataType->deleteStoredObjectAttribute( $contentobjectAttribute );
            }
            $attrOffset += $attrLimit;
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

        eZSearch::removeObjectById( $delID );

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

    /**
     * Archives the current object and removes assigned nodes
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param int $nodeID
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
            eZSearch::removeObjectById( $delID );
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
                    foreach ( $nodes as $additionalNode )
                    {
                        if ( $additionalNode->attribute( 'node_id' ) != $node->attribute( 'main_node_id' ) )
                        {
                            $additionalNode->remove();
                        }
                    }

                    $node->removeNodeFromTree( true );
                    $this->setAttribute( 'status', eZContentObject::STATUS_ARCHIVED );
                    eZSearch::removeObjectById( $delID );
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

    /**
     * Removes old internal drafts by the specified user associated for the past time span given by $timeDuration
     *
     * @param int|bool $userID The ID of the user to cleanup for, if false it will use the current user.
     * @param int $timeDuration default time duration for internal drafts 60*60*24 seconds (1 day)
     */
    function cleanupInternalDrafts( $userID = false, $timeDuration = 86400 )
    {
        if ( !is_numeric( $timeDuration ) ||
             $timeDuration < 0 )
        {
            eZDebug::writeError( "The time duration must be a positive numeric value (timeDuration = $timeDuration)", __METHOD__ );
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

    /**
     * Removes all old internal drafts by the specified user for the past time span given by $timeDuration
     *
     * @param int|bool $userID The ID of the user to cleanup for, if false it will use the current user.
     * @param int $timeDuration default time duration for internal drafts 60*60*24 seconds (1 day)
     */
    static function cleanupAllInternalDrafts( $userID = false, $timeDuration = 86400 ) //
    {
        if ( !is_numeric( $timeDuration ) ||
             $timeDuration < 0 )
        {
            eZDebug::writeError( "The time duration must be a positive numeric value (timeDuration = $timeDuration)", __METHOD__ );
            return;
        }


        if ( $userID === false )
        {
            $userID = eZUser::currentUserID();
        }
        // Remove all internal drafts
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

    /**
     * Fetches all attributes from any versions of the content object
     *
     * @param int $contentObjectID
     * @param bool $asObject
     * @param array|null $limit the limit array passed to {@see eZPersistentObject::fetchObjectList}
     * @return eZContentObjectAttribute[]|array|null
     */
    function allContentObjectAttributes( $contentObjectID, $asObject = true, $limit = null )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                    null,
                                                    array("contentobject_id" => $contentObjectID ),
                                                    null,
                                                    $limit,
                                                    $asObject );
    }

    /**
     * Fetches the attributes for the current published version of the object.
     *
     * @todo fix using of $asObject parameter
     * @todo fix condition for getting attribute from cache
     * @todo probably need to move method to eZContentObjectVersion class
     *
     * @param bool $asObject
     * @param int|bool $version
     * @param string|bool $language
     * @param int|bool $contentObjectAttributeID
     * @param bool $distinctItemsOnly
     * @return eZContentObjectAttribute[]|array
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

        if ( !$language || !isset( $this->ContentObjectAttributes[$version][$language] ) )
        {
            $versionText = "AND                    ezcontentobject_attribute.version = '$version'";
            if ( $language )
            {
                $languageText = "AND                    ezcontentobject_attribute.language_code = '$language'";
            }
            else
            {
                $languageText = "AND                    ".eZContentLanguage::sqlFilter( 'ezcontentobject_attribute', 'ezcontentobject_version' );
            }
            $attributeIDText = false;
            if ( $contentObjectAttributeID )
                $attributeIDText = "AND                    ezcontentobject_attribute.id = '$contentObjectAttributeID'";
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
            $returnAttributeArray = $this->ContentObjectAttributes[$version][$language];
        }

        return $returnAttributeArray;
    }

    /**
     * Initializes the cached copy of the content object attributes for the given version and language
     *
     * @param eZContentObjectAttribute[] $attributes
     * @param int $version
     * @param string $language
     */
    function setContentObjectAttributes( &$attributes, $version, $language )
    {
        $this->ContentObjectAttributes[$version][$language] = $attributes;
    }

    /**
     * Fetches the attributes for an array of objects or nodes
     *
     * @param eZContentObject[]|eZContentObjectTreeNode[] $objList
     * @param bool $asObject
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

    /**
     * Resets the object's input relation list
     */
    function resetInputRelationList()
    {
        $this->InputRelationList = array( eZContentObject::RELATION_EMBED => array(),
                                          eZContentObject::RELATION_LINK =>  array() );
    }

    /**
     * @param int[] $addingIDList
     * @param int $relationType
     */
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

    /**
     * @param int|bool $editVersion
     * @return bool
     */
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

    /**
     * @param eZContentObjectAttribute[] $contentObjectAttributes
     * @param string $attributeDataBaseName
     * @param array|bool $inputParameters
     * @param array $parameters
     * @return array
     */
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

    /**
     * @param eZContentObjectAttribute[] $contentObjectAttributes
     * @param string $attributeDataBaseName
     */
    function fixupInput( $contentObjectAttributes, $attributeDataBaseName )
    {
        $http = eZHTTPTool::instance();
        foreach ( $contentObjectAttributes as $contentObjectAttribute )
        {
            $contentObjectAttribute->fixupInput( $http, $attributeDataBaseName );
        }
    }

    /**
     * @param eZContentObjectAttribute[] $contentObjectAttributes
     * @param string $attributeDataBaseName
     * @param array $customActionAttributeArray
     * @param array $customActionParameters
     * @return array
     */
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

    /**
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param string $attributeDataBaseName
     * @param array $customActionAttributeArray
     * @param array $customActionParameters
     */
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

    /**
     * @param string $attributeDataBaseName
     * @param array $customActionAttributeArray
     * @param array $customActionParameters
     * @param int|bool $objectVersion
     */
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

    /**
     *
     */
    static function recursionProtectionStart()
    {
        $GLOBALS["ez_content_object_recursion_protect"] = array();
    }

    /**
     * @param int $id
     * @return bool
     */
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

    /**
     *
     */
    static function recursionProtectionEnd()
    {
        unset( $GLOBALS["ez_content_object_recursion_protect"] );
    }

    /**
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param eZContentObjectAttribute[] $contentObjectAttributes
     * @param array $attributeInputMap
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

    /**
     * Returns the next available version number for this object.
     *
     * @return int
     */
    function nextVersion()
    {
        $db = eZDB::instance();
        $versions = $db->arrayQuery( "SELECT ( MAX( version ) + 1 ) AS next_id FROM ezcontentobject_version
                       WHERE contentobject_id='$this->ID'" );
        return $versions[0]["next_id"];

    }

    /**
     * Returns the previous available version number for this object, if existing, false otherwise ( if the object has only one version )
     *
     * @return int|bool
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

    /**
     * Returns the number of existing versions.
     *
     * @return mixed
     */
    function getVersionCount()
    {
        $db = eZDB::instance();
        $versionCount = $db->arrayQuery( "SELECT ( COUNT( version ) ) AS version_count FROM ezcontentobject_version
                       WHERE contentobject_id='$this->ID'" );
        return $versionCount[0]["version_count"];

    }

    /**
     * Returns the current object's language
     *
     * @return bool|string
     */
    function currentLanguage()
    {
        return $this->CurrentLanguage;
    }

    /**
     * Returns the language object of the current object's language
     *
     * @return eZContentLanguage|bool|
     */
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

    /**
     * Sets the current object's language and resets the name
     *
     * @param string $lang
     */
    function setCurrentLanguage( $lang )
    {
        $this->CurrentLanguage = $lang;
        $this->Name = null;
    }

    /**
     * Returns the current object's initial language object, or false if not set
     *
     * @return eZContentLanguage|bool
     */
    function initialLanguage()
    {
        return isset( $this->InitialLanguageID ) ? eZContentLanguage::fetch( $this->InitialLanguageID ) : false;
    }

    /**
     * Returns the current object's initial language code, or false if not set
     *
     * @return string|bool
     */
    function initialLanguageCode()
    {
        $initialLanguage = $this->initialLanguage();
        // If current contentobject is "Top Level Nodes" than it doesn't have "initial Language" and "locale".
        return ( $initialLanguage !== false ) ?  $initialLanguage->attribute( 'locale' ) : false;
    }

    /**
     * Adds a new location (node) to the current object.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param int $parentNodeID The id of the node to use as parent.
     * @param bool $asObject If true it will return the new child-node as an object, if not it returns the ID.
     *
     * @return eZContentObjectTreeNode|int
     */
    function addLocation( $parentNodeID, $asObject = false )
    {
        $node = eZContentObjectTreeNode::addChildTo( $this->ID, $parentNodeID, true, $this->CurrentVersion );

        $data = array( 'contentobject_id' => $this->ID,
                       'contentobject_version' => $this->attribute( 'current_version' ),
                       'parent_node' => $parentNodeID,
                       // parent_remote_id in node assignment holds remote id of the added location,
                       // not of the parent location or of the node assignment itself
                       'parent_remote_id' => $node->attribute( 'remote_id' ),
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

    /**
     * Adds a link to the given content object id.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param int $toObjectID
     * @param int|bool $fromObjectVersion
     * @param int $attributeID
     * @param int $relationType
     * @return bool|void
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
            eZDebug::writeWarning( "Object relation type conflict", __METHOD__ );
        }

        $db = eZDB::instance();

        if ( !$fromObjectVersion )
            $fromObjectVersion = $this->CurrentVersion;

        $fromObjectID = $this->ID;

        if ( !is_numeric( $toObjectID ) )
        {
            eZDebug::writeError( "Related object ID (toObjectID): '$toObjectID', is not a numeric value.", __METHOD__ );
            return false;
        }

        if ( !eZContentObject::exists( $toObjectID ) )
        {
            eZDebug::writeError( "Related object ID (toObjectID): '$toObjectID', does not refer to any existing object.", __METHOD__ );
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
                         contentclassattribute_id=$attributeID";
        $count = $db->arrayQuery( $query );
        // if current relation does not exist
        if ( !isset( $count[0]['count'] ) ||  $count[0]['count'] == '0'  )
        {
            $db->begin();
            $db->query( "INSERT INTO ezcontentobject_link ( from_contentobject_id, from_contentobject_version, to_contentobject_id, contentclassattribute_id, relation_type )
                         VALUES ( $fromObjectID, $fromObjectVersion, $toObjectID, $attributeID, $relationType )" );
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
                                contentclassattribute_id=$attributeID" );
            $db->commit();
        }
    }

    /**
     * Removes a link to the given content object id.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param int|bool $toObjectID If false it will delete relations to all the objects.
     * @param bool $fromObjectVersion
     * @param int $attributeID  ID of class attribute.
     *                          If it is > 0 we remove relations created by a specific objectrelation[list] attribute.
     *                          If it is set to 0 we remove relations created without using of objectrelation[list] attribute.
     *                          If it is set to false, we remove all relations, no matter how were they created:
     *                          using objectrelation[list] attribute or using "Add related objects" functionality in obect editing mode.
     * @param int $relationType
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
        if ( !$attributeID && ( $fromObjectVersion != $this->CurrentVersion || $this->CurrentVersion == 1 ) )
        {
            // Querying only for object level relations.
            $rows = $db->arrayQuery( "SELECT * FROM ezcontentobject_link
                                      WHERE from_contentobject_id=$fromObjectID
                                        AND from_contentobject_version=$fromObjectVersion
                                        AND contentclassattribute_id='0'
                                        $toObjectCondition" );
            if ( !empty( $rows ) )
            {
                $lastRow = end( $rows );
                // Remember type in order to later compare with given $relationType and determine if it is composite.
                $lastRelationType = (int) $lastRow['relation_type'];
            }
        }

        if ( 0 !== ( eZContentObject::RELATION_ATTRIBUTE & $relationType ) ||
             0 != $attributeID ||
             $relationType == $lastRelationType )
        {
            $db->query( "DELETE FROM ezcontentobject_link
                         WHERE       from_contentobject_id=$fromObjectID AND
                                     from_contentobject_version=$fromObjectVersion $classAttributeCondition $toObjectCondition" );
        }
        else
        {
            if ( $db->databaseName() == 'oracle' )
            {
                $notRelationType = - ( $relationType + 1 );
                $db->query( "UPDATE ezcontentobject_link
                             SET    relation_type = " . $db->bitAnd( 'relation_type', $notRelationType ) . "
                             WHERE  from_contentobject_id=$fromObjectID AND
                                    from_contentobject_version=$fromObjectVersion $classAttributeCondition $toObjectCondition" );
            }
            else
            {
                $db->query( "UPDATE ezcontentobject_link
                             SET    relation_type = ( relation_type & ".(~$relationType)." )
                             WHERE  from_contentobject_id=$fromObjectID AND
                                    from_contentobject_version=$fromObjectVersion $classAttributeCondition $toObjectCondition" );
            }
        }

        $db->commit();
    }

    /**
     * @param int $currentVersion
     * @param int $newVersion
     * @param int|bool $newObjectID
     */
    function copyContentObjectRelations( $currentVersion, $newVersion, $newObjectID = false )
    {
        $objectID = $this->ID;
        if ( !$newObjectID )
        {
            $newObjectID = $objectID;
        }

        $db = eZDB::instance();
        $db->begin();

        $relations = $db->arrayQuery( "SELECT to_contentobject_id, relation_type FROM ezcontentobject_link
                                       WHERE contentclassattribute_id='0'
                                         AND from_contentobject_id='$objectID'
                                         AND from_contentobject_version='$currentVersion'" );
        foreach ( $relations as $relation )
        {
            $toContentObjectID = $relation['to_contentobject_id'];
            $relationType = $relation['relation_type'];
            $db->query( "INSERT INTO ezcontentobject_link( contentclassattribute_id,
                                                           from_contentobject_id,
                                                           from_contentobject_version,
                                                           to_contentobject_id,
                                                           relation_type )
                         VALUES ( '0', '$newObjectID', '$newVersion', '$toContentObjectID', '$relationType' )" );
        }

        $db->commit();
    }

    /**
     * @return bool
     */
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

    /**
     * @param bool $allRelations
     * @return int
     */
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

    /**
     * Returns related or reverse related objects
     *
     * @param int|bool $fromObjectVersion   If omitted, the current version will be used
     * @param int|bool $objectID        If omitted, the current object will be used
     * @param int|bool $attributeID     makes sense only when $params['AllRelations'] not set or eZContentObject::RELATION_ATTRIBUTE
     *                                  $attributeID = 0|false ( $params['AllRelations'] is eZContentObject::RELATION_ATTRIBUTE )
     *                                      - return relations made with any attributes
     *                                  $attributeID > 0
     *                                      - return relations made with attribute ID ( "related object(s)" datatype )
     *                                  $attributeID = false ( $params['AllRelations'] not set )
     *                                      - return ALL relations (deprecated, use "$params['AllRelations'] = true" instead)
     * @param bool $groupByAttribute    This parameter makes sense only when $attributeID == false or $params['AllRelations'] = true
     *                                  $groupByAttribute = false
     *                                      - return all relations as an array of content objects
     *                                  $groupByAttribute = true
     *                                      - return all relations groupped by attribute ID
     * @param array|bool $params        Other parameters from template fetch function
     *                                  $params['AllRelations'] = true
     *                                      - return ALL relations, including attribute-level
     *                                  $params['AllRelations'] = false
     *                                      - return objec level relations only
     *                                  $params['AllRelations'] = int > 0
     *                                      - bit mask of EZ_CONTENT_OBJECT_RELATION_* values
     *                                  $params['SortBy']
     *                                      - Possible values:
     *                                          "class_identifier", "class_name", "modified",
     *                                          "name", "published", "section"
     *                                  $params['IgnoreVisibility'] = true
     *                                      - Include related objects with a 'hidden' state
     *                                  $params['IgnoreVisibility'] = false
     *                                      - Exclude related objects with a 'hidden' state
     *                                  $params['RelatedClassIdentifiers'] = array
     *                                      - limit returned relations to objects of the specified class identifiers
     * @param bool $reverseRelatedObjects   true -> returns reverse related objects
     *                                      false -> returns related objects
     * @return eZContentObject[]|array eZContentObject[], if $params['AsObject'] is set to true (default), array otherwise
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
                              'attributeWhereSQL' => '',
                              'attributeTargetSQL' => '' );
        $relatedClassIdentifiersSQL = '';
        $showInvisibleNodesCond = '';
        // process params (only SortBy and IgnoreVisibility currently supported):
        // Supported sort_by modes:
        //   class_identifier, class_name, modified, name, published, section
        if ( is_array( $params ) )
        {
            if ( isset( $params['SortBy'] ) )
            {
                $validSortBy = array( 'class_identifier', 'class_name', 'modified', 'name', 'published', 'section' );
                $sortByParam = array();
                if ( is_array( $params['SortBy'] ) )
                {
                    // only one SortBy, as a simple array
                    if ( !is_array( $params['SortBy'][0] ) )
                    {
                        if ( !in_array( $params['SortBy'][0], $validSortBy ) )
                            eZDebug::writeWarning( "Unsupported sort_by parameter {$params['SortBy'][0]}; check the online documentation for the list of supported sort types", __METHOD__ );
                        else
                            $sortByParam[] = $params['SortBy'];
                    }
                    // multiple SortBy, check each of them one by one, and keep valid ones
                    else
                    {
                        $invalidSortBy = array();
                        foreach( $params['SortBy'] as $sortByTuple )
                        {
                            if ( !in_array( $sortByTuple[0], $validSortBy ) )
                                $invalidSortBy[] = $sortByTuple[0];
                            else
                                $sortByParam[] = $sortByTuple;
                        }
                        if ( count( $invalidSortBy ) > 0 )
                        {
                            eZDebug::writeWarning( "Unsupported sort_by parameter(s) " . implode( ', ', $invalidSortBy ) . "; check the online documentation for the list of supported sort types", __METHOD__ );
                        }
                    }
                }
                if ( count( $sortByParam ) > 0 )
                {
                    $sortingInfo = eZContentObjectTreeNode::createSortingSQLStrings( $sortByParam );
                    $sortingString = ' ORDER BY ' . $sortingInfo['sortingFields'];
                }
            }
            if ( isset( $params['IgnoreVisibility'] ) )
            {
                $showInvisibleNodesCond = self::createFilterByVisibilitySQLString( $params['IgnoreVisibility'] );
            }

            // related class identifier filter
            $relatedClassIdentifiersSQL = '';
            if ( isset( $params['RelatedClassIdentifiers'] ) && is_array( $params['RelatedClassIdentifiers'] ) )
            {
                $relatedClassIdentifiers = array();
                foreach( $params['RelatedClassIdentifiers'] as $classIdentifier )
                {
                    $relatedClassIdentifiers[] = "'" . $db->escapeString( $classIdentifier ) . "'";
                }
                $relatedClassIdentifiersSQL = $db->generateSQLINStatement( $relatedClassIdentifiers, 'ezcontentclass.identifier', false, true, 'string' ). " AND";
                unset( $classIdentifier, $relatedClassIdentifiers );
            }
        }

        $relationTypeMasking = '';
        $relationTypeMask = isset( $params['AllRelations'] ) ? $params['AllRelations'] : ( $attributeID === false );
        if ( $attributeID && ( $relationTypeMask === false || $relationTypeMask === eZContentObject::RELATION_ATTRIBUTE ) )
        {
            $attributeID =(int) $attributeID;
            $relationTypeMasking .= " contentclassattribute_id=$attributeID AND ";
            $relationTypeMask = eZContentObject::RELATION_ATTRIBUTE;
        }
        elseif ( is_bool( $relationTypeMask ) )
        {
            $relationTypeMask = eZContentObject::relationTypeMask( $relationTypeMask );
        }

        if ( $db->databaseName() == 'oracle' )
        {
            $relationTypeMasking .= " bitand( relation_type, $relationTypeMask ) <> 0 ";
        }
        else
        {
            $relationTypeMasking .= " ( relation_type & $relationTypeMask ) <> 0 ";
        }

        // Create SQL
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
                        ezcontentobject.*, ezcontentobject_name.name as name, ezcontentobject_name.real_translation
                        {$sortingInfo['attributeTargetSQL']}
                     FROM
                        ezcontentclass,
                        ezcontentobject,
                        ezcontentobject_link,
                        ezcontentobject_name
                        {$sortingInfo['attributeFromSQL']}
                     WHERE
                        ezcontentclass.id=ezcontentobject.contentclass_id AND
                        ezcontentclass.version=0 AND
                        ezcontentobject.status=" . eZContentObject::STATUS_PUBLISHED . " AND
                        {$sortingInfo['attributeWhereSQL']}
                        {$relatedClassIdentifiersSQL}
                        {$relationTypeMasking}
                        {$fromOrToContentObjectID}
                        {$showInvisibleNodesCond} AND
                        ezcontentobject.id = ezcontentobject_name.contentobject_id AND
                        ezcontentobject.current_version = ezcontentobject_name.content_version AND
                        " . eZContentLanguage::sqlFilter( 'ezcontentobject_name', 'ezcontentobject' ) . "
                        {$sortingString}";
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

    /**
     * Returns related objects.
     *
     * @see relatedObjects()
     *
     * @param int|bool $fromObjectVersion
     * @param int|bool $fromObjectID
     * @param int|bool $attributeID
     * @param bool $groupByAttribute
     * @param array|bool $params
     * @return array|eZContentObject[]
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

    /**
     * Returns the xml-linked objects.
     *
     * @see relatedObjects()
     *
     * @param int|bool $fromObjectVersion
     * @param int|bool $fromObjectID
     * @return array|eZContentObject[]
     */
    function linkedContentObjectList( $fromObjectVersion = false, $fromObjectID = false )
    {
        return $this->relatedObjects( $fromObjectVersion,
                                      $fromObjectID,
                                      0,
                                      false,
                                      array( 'AllRelations' => eZContentObject::RELATION_LINK ) );
    }

    /**
     * Returns the xml-embedded objects.
     *
     * @see relatedObjects()
     *
     * @param int|bool $fromObjectVersion
     * @param int|bool $fromObjectID
     * @return array|eZContentObject[]
     */
    function embeddedContentObjectList( $fromObjectVersion = false, $fromObjectID = false )
    {
        return $this->relatedObjects( $fromObjectVersion,
                                      $fromObjectID,
                                      0,
                                      false,
                                      array( 'AllRelations' => eZContentObject::RELATION_EMBED ) );
    }

    /**
     * Returns the reverse xml-linked objects.
     *
     * @see relatedObjects()
     *
     * @param int|bool $fromObjectVersion
     * @param int|bool $fromObjectID
     * @return array|eZContentObject[]
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

    /**
     * Returns the reverse xml-embedded objects.
     *
     * @see relatedObjects()
     *
     * @param int|bool $fromObjectVersion
     * @param int|bool $fromObjectID
     * @return array|eZContentObject[]
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

    /**
     * Left for compatibility
     *
     * @see relatedObjects()
     *
     * @deprecated
     * @param bool $fromObjectVersion
     * @param bool $fromObjectID
     * @param int $attributeID
     * @param bool $params
     * @return array
     */
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

    /**
     * Returns the number of related objects
     *
     * @see relatedObjectCount()
     *
     * @param int|bool $fromObjectVersion
     * @param int|bool $attributeID
     * @param array|bool $params
     * @return int
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

    /**
     * Returns the objects to which this object are related .
     *
     * @see relatedObjects()
     *
     * @param int|bool $version
     * @param int|bool $attributeID
     * @param bool $groupByAttribute
     * @param array|bool $params
     * @return array|eZContentObject[]
     */
    function reverseRelatedObjectList( $version = false,
                                       $attributeID = 0,
                                       $groupByAttribute = false,
                                       $params = false )
    {
        return $this->relatedObjects( $version, $this->ID, $attributeID, $groupByAttribute, $params, true );
    }

    /**
     * Returns the xml-linked objects count.
     *
     * @see relatedObjectCount()
     *
     * @param int|bool $fromObjectVersion
     * @return int
     */
    function linkedContentObjectCount( $fromObjectVersion = false )
    {
        return $this->relatedObjectCount( $fromObjectVersion,
                                          0,
                                          false,
                                          array( 'AllRelations' => eZContentObject::RELATION_LINK ) );
    }

    /**
     * Returns the xml-embedded objects count.
     *
     * @see relatedObjectCount()
     *
     * @param int|bool $fromObjectVersion
     * @return int
     */
    function embeddedContentObjectCount( $fromObjectVersion = false )
    {
        return $this->relatedObjectCount( $fromObjectVersion,
                                          0,
                                          false,
                                          array( 'AllRelations' => eZContentObject::RELATION_EMBED ) );
    }

    /**
     * Returns the reverse xml-linked objects count.
     *
     * @see relatedObjectCount()
     *
     * @param int|bool $fromObjectVersion
     * @return int
     */
    function reverseLinkedObjectCount( $fromObjectVersion = false )
    {
        return $this->relatedObjectCount( $fromObjectVersion,
                                          0,
                                          true,
                                          array( 'AllRelations' => eZContentObject::RELATION_LINK ) );
    }

    /**
     * Returns the reverse xml-embedded objects count.
     *
     * @see relatedObjectCount()
     *
     * @param int|bool $fromObjectVersion
     * @return int
     */
    function reverseEmbeddedObjectCount( $fromObjectVersion = false )
    {
        return $this->relatedObjectCount( $fromObjectVersion,
                                          0,
                                          true,
                                          array( 'AllRelations' => eZContentObject::RELATION_EMBED ) );
    }

    /**
     * Fetch the number of (reverse) related objects
     *
     * @param bool|int $version
     * @param int $attributeID
     *        This parameter only makes sense if $params[AllRelations] is unset,
     *        set to false, or matches eZContentObject::RELATION_ATTRIBUTE
     *        Possible values:
     *        - 0 or false:
     *          Count relations made with any attribute
     *        - >0
     *          Count relations made with attribute $attributeID
     * @param int|bool $reverseRelatedObjects
     *        Wether to count related objects (false) or reverse related
     *        objects (false)
     * @param array|bool $params
     *        Various params, as an associative array.
     *        Possible values:
     *        - AllRelations (bool|int)
     *          true: count ALL relations, object and attribute level
     *          false: only count object level relations
     *          other: bit mask of eZContentObject::RELATION_* constants
     *        - IgnoreVisibility (bool)
     *          If true, 'hidden' status will be ignored
     *
     * @return int The number of (reverse) related objects for the object
     */
    function relatedObjectCount( $version = false, $attributeID = 0, $reverseRelatedObjects = false, $params = false )
    {
        $objectID = $this->ID;
        if ( $version == false )
            $version = isset( $this->CurrentVersion ) ? $this->CurrentVersion : false;
        $version = (int) $version;

        $db = eZDB::instance();
        $showInvisibleNodesCond = '';

        // process params (only IgnoreVisibility currently supported):
        if ( is_array( $params ) )
        {
            if ( isset( $params['IgnoreVisibility'] ) )
            {
                $showInvisibleNodesCond = self::createFilterByVisibilitySQLString(
                    $params['IgnoreVisibility'],
                    // inner_object represents the source of relation while outer_object represents the target
                    $reverseRelatedObjects ? 'inner_object' : 'outer_object'
                );
            }
        }

        $relationTypeMasking = '';
        $relationTypeMask = isset( $params['AllRelations'] ) ? $params['AllRelations'] : ( $attributeID === false );
        if ( $attributeID && ( $relationTypeMask === false || $relationTypeMask === eZContentObject::RELATION_ATTRIBUTE ) )
        {
            $attributeID =(int) $attributeID;
            $relationTypeMasking .= " AND inner_link.contentclassattribute_id = $attributeID ";
            $relationTypeMask = eZContentObject::RELATION_ATTRIBUTE;
        }
        elseif ( is_bool( $relationTypeMask ) )
        {
            $relationTypeMask = eZContentObject::relationTypeMask( $relationTypeMask );
        }

        if ( $db->databaseName() == 'oracle' )
        {
            $relationTypeMasking .= " AND bitand( inner_link.relation_type, $relationTypeMask ) <> 0 ";
        }
        else
        {
            $relationTypeMasking .= " AND ( inner_link.relation_type & $relationTypeMask ) <> 0 ";
        }

        if ( $reverseRelatedObjects )
        {
            $outerObjectIDSQL = 'outer_object.id = outer_link.from_contentobject_id';
            if ( is_array( $objectID ) )
            {
                if ( count( $objectID ) > 0 )
                {
                    $objectIDSQL = ' AND ' . $db->generateSQLINStatement( $objectID, 'inner_link.to_contentobject_id', false, false, 'int' ) . ' AND
                                     inner_link.from_contentobject_version = inner_object.current_version';
                }
                else
                {
                    $objectIDSQL = '';
                }
            }
            else
            {
                $objectID = (int) $objectID;
                $objectIDSQL = " AND inner_link.to_contentobject_id = $objectID
                                 AND inner_link.from_contentobject_version = inner_object.current_version";
            }
        }
        else
        {
            $outerObjectIDSQL = 'outer_object.id = outer_link.to_contentobject_id';
            $objectIDSQL = " AND inner_link.from_contentobject_id = $objectID
                             AND inner_link.from_contentobject_version = $version";
        }

        $query = "SELECT
                    COUNT( outer_object.id ) AS count
                  FROM
                    ezcontentobject outer_object, ezcontentobject inner_object, ezcontentobject_link outer_link
                  INNER JOIN
                    ezcontentobject_link inner_link ON outer_link.id = inner_link.id
                  WHERE
                    $outerObjectIDSQL
                    AND outer_object.status = " . eZContentObject::STATUS_PUBLISHED . "
                    AND inner_object.id = inner_link.from_contentobject_id
                    AND inner_object.status = " . eZContentObject::STATUS_PUBLISHED . "
                    $objectIDSQL
                    $relationTypeMasking
                    $showInvisibleNodesCond";

        $rows = $db->arrayQuery( $query );
        return $rows[0]['count'];
    }

    /**
     * Returns the number of objects to which this object is related.
     *
     * @see relatedObjectCount()
     *
     * @param int|bool $version
     * @param int|bool $attributeID
     * @param array|bool $params
     * @return int
     */
    function reverseRelatedObjectCount( $version = false, $attributeID = 0, $params = false )
    {
        return $this->relatedObjectCount( $version, $attributeID, true, $params );
    }

    /**
     * Returns the related objects.
     *
     * @see reverseRelatedObjectList()
     *
     * @deprecated This function is a duplicate of reverseRelatedObjectList(), use that function instead.
     * @param int|bool $version
     * @return array|eZContentObject[]
     */
    function contentObjectListRelatingThis( $version = false )
    {
        return $this->reverseRelatedObjectList( $version );
    }

    /**
     * Returns an array of parent node IDs
     *
     * @return int[]
     */
    function parentNodeIDArray()
    {
        return $this->parentNodes( true, false );
    }

    /**
     * Returns the parent nodes for the current object.
     *
     * @param int|bool $version No longer in use, published nodes are used instead.
     * @param bool $asObject If true it fetches PHP objects, otherwise it fetches IDs.
     * @return eZContentObjectTreeNode[]|int[]
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

    /**
     * Creates and returns a new node assignment that will place the object as child of node $nodeID.
     *
     * The returned assignment will already be stored in the database
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param int $parentNodeID The node ID of the parent node
     * @param bool $isMain True if the created node is the main node of the object
     * @param string|bool $remoteID A string denoting the unique remote ID of the assignment or \c false for no remote id.
     * @param int $sortField
     * @param int $sortOrder
     * @return eZNodeAssignment|null
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

    /**
     * Creates object with nodeAssignment from given parent Node, class ID and language code.
     *
     * @param eZContentObjectTreeNode $parentNode
     * @param int $contentClassID
     * @param string $languageCode
     * @param string|bool $remoteID
     *
     * @return eZContentObject|null
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

            $db = eZDB::instance();
            $db->begin();
            $contentObject = $class->instantiateIn( $languageCode, false, $sectionID, false, eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
            $nodeAssignment = $contentObject->createNodeAssignment( $parentNode->attribute( 'node_id' ),
                                                                    true, $remoteID,
                                                                    $class->attribute( 'sort_field' ),
                                                                    $class->attribute( 'sort_order' ) );
            $db->commit();
            return $contentObject;
        }
        return null;
    }

    /**
     * Returns the visible nodes of the current object. The main node is the
     * first element in the returned value (if it is considered visible).
     *
     * The setting site.ini/[SiteAccessSettings]/ShowHiddenNodes is taken into
     * account, ie an hidden (or hidden by superior) node is considered visible
     * if ShowHiddenNodes is true.
     *
     * @see eZContentObject::assignedNodes
     * @return eZContentObjectTreeNode[]
     */
    function visibleNodes()
    {
        $result = array();
        foreach ( $this->assignedNodes( true, true ) as $node )
        {
            if ( $node->attribute( 'node_id' ) == $this->attribute( 'main_node_id' ) )
            {
                array_unshift( $result, $node );
            }
            else
            {
                $result[] = $node;
            }
        }
        return $result;
    }

    /**
     * Returns whether the current object has at least one visible node.
     *
     * The setting site.ini/[SiteAccessSettings]/ShowHiddenNodes is taken into
     * account, ie an hidden (or hidden by superior) node is considered visible
     * if ShowHiddenNodes is true.
     *
     * @return boolean
     */
    function hasVisibleNode()
    {
        $contentobjectID = $this->attribute( 'id' );
        if ( $contentobjectID == null )
        {
            return false;
        }
        $visibilitySQL = '';
        if ( eZINI::instance()->variable( 'SiteAccessSettings', 'ShowHiddenNodes' ) !== 'true' )
        {
            $visibilitySQL = "AND ezcontentobject_tree.is_invisible = 0 ";
        }
        $rows = eZDB::instance()->arrayQuery(
            "SELECT COUNT(ezcontentobject_tree.node_id) AS node_count " .
            "FROM ezcontentobject_tree " .
            "INNER JOIN ezcontentobject ON (ezcontentobject_tree.contentobject_id = ezcontentobject.id) " .
            "WHERE contentobject_id = $contentobjectID " . $visibilitySQL
        );
        return ( $rows[0]['node_count'] > 0 );
    }

    /**
     * Returns the node assignments for the current object.
     *
     * @param boolean $asObject
     * @param boolean $checkVisibility if true, the visibility and the setting
     * site.ini/[SiteAccessSettings]/ShowHiddenNodes are taken into account.
     * @return eZContentObjectTreeNode[]|array[]
     */
    function assignedNodes( $asObject = true, $checkVisibility = false )
    {
        $contentobjectID = $this->attribute( 'id' );
        if ( $contentobjectID == null )
        {
            return array();
        }
        $visibilitySQL = '';
        if (
            $checkVisibility === true
            && eZINI::instance()->variable( 'SiteAccessSettings', 'ShowHiddenNodes' ) !== 'true'
        )
        {
            $visibilitySQL = "AND ezcontentobject_tree.is_invisible = 0 ";
        }
        $nodesListArray = eZDB::instance()->arrayQuery(
            "SELECT " .
            "ezcontentobject.contentclass_id, ezcontentobject.current_version, ezcontentobject.initial_language_id, ezcontentobject.language_mask, " .
            "ezcontentobject.modified, ezcontentobject.name, ezcontentobject.owner_id, ezcontentobject.published, ezcontentobject.remote_id AS object_remote_id, ezcontentobject.section_id, " .
            "ezcontentobject.status, ezcontentobject_tree.contentobject_is_published, ezcontentobject_tree.contentobject_version, ezcontentobject_tree.depth, " .
            "ezcontentobject_tree.is_hidden, ezcontentobject_tree.is_invisible, ezcontentobject_tree.main_node_id, ezcontentobject_tree.modified_subnode, ezcontentobject_tree.node_id, " .
            "ezcontentobject_tree.parent_node_id, ezcontentobject_tree.path_identification_string, ezcontentobject_tree.path_string, ezcontentobject_tree.priority, ezcontentobject_tree.remote_id, " .
            "ezcontentobject_tree.sort_field, ezcontentobject_tree.sort_order, ezcontentclass.serialized_name_list as class_serialized_name_list, " .
            "ezcontentclass.identifier as class_identifier, " .
            "ezcontentclass.is_container as is_container " .
            "FROM ezcontentobject_tree " .
            "INNER JOIN ezcontentobject ON (ezcontentobject_tree.contentobject_id = ezcontentobject.id) " .
            "INNER JOIN ezcontentclass ON (ezcontentclass.version = 0 AND ezcontentclass.id = ezcontentobject.contentclass_id) " .
            "WHERE contentobject_id = $contentobjectID " .
            $visibilitySQL .
            "ORDER BY path_string"
        );
        if ( $asObject == true )
        {
            return eZContentObjectTreeNode::makeObjectsArray( $nodesListArray, true, array( "id" => $contentobjectID ) );
        }
        else
            return $nodesListArray;
    }

    /**
     * Returns the main node id for the current object and
     * sets the attribute MainNodeID in the current object
     *
     * @return int|null
     */
    public function mainNodeID()
    {
        if ( !is_numeric( $this->MainNodeID ) )
        {
            $mainNodeID = eZContentObjectTreeNode::findMainNode( $this->attribute( 'id' ) );
            $this->MainNodeID = $mainNodeID;
        }
        return $this->MainNodeID;
    }

    /**
     * Returns the main node for the current object.
     *
     * @return eZContentObjectTreeNode|null
     */
    public function mainNode()
    {
        return eZContentObjectTreeNode::findMainNode( $this->attribute( 'id' ), true );
    }

    /**
     * Sets the permissions for this object.
     *
     * @param array $permissionArray
     */
    function setPermissions( $permissionArray )
    {
        $this->Permissions =& $permissionArray;
    }

    /**
     * Returns the permission for the current object.
     *
     * @return array
     */
    function permissions( )
    {
        return $this->Permissions;
    }

    /**
     * Returns a list of languages of the current object that the current user can edit
     *
     * @return eZContentLanguage[]
     */
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

    /**
     * Returns a list of languages of the current object that the current user can create
     *
     * @return eZContentLanguage[]
     */
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

    /**
     * @param array $limitationValueList
     * @param int $userID
     * @param int|bool $contentObjectID
     * @return string Returns "denied" or "allowed"
     */
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

    /**
     * Check access for the current object
     *
     * @param string $functionName Function name ( edit, read, remove, etc. )
     * @param int|bool $originalClassID Used to check access for object creation
     * @param int|bool $parentClassID Used to check access for object creation
     * @param bool $returnAccessList If true, returns access list instead of access result
     * @param string|bool $language
     * @return array|int 1 if has access, 0 if not, array if $returnAccessList is true
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
                    if ( $parentObj instanceof eZContentObject )
                    {
                        $result = $parentObj->checkAccess( 'create', $this->attribute( 'contentclass_id' ),
                                                           $parentObj->attribute( 'contentclass_id' ), false, $originalLanguage );
                        return $result;
                    }
                    else
                    {
                        eZDebug::writeError( "Error retrieving parent object of main node for object id: " . $this->attribute( 'id' ), __METHOD__ );
                    }
                }

                return 0;
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

                        if ( $parentObj instanceof eZContentObject )
                        {
                            $result = $parentObj->checkAccess( 'create', $this->attribute( 'contentclass_id' ),
                                                               $parentObj->attribute( 'contentclass_id' ), false, $originalLanguage );
                        }
                        else
                        {
                            eZDebug::writeError( "Error retrieving parent object of main node for object id: " . $this->attribute( 'id' ), __METHOD__ );
                            $result = 0;
                        }

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

    /**
     * Returns an array of classes the current user has access to
     *
     * @param array $policy
     * @param array|bool $allowedLanguageCodes
     * @return array
     */
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
            else if ( $this->attribute( 'owner_id' ) != $userID &&
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

    /**
     * Finds all classes that the current user can create objects from and returns.
     * It is also possible to filter the list event more with $includeFilter and $groupList.
     *
     * @param bool $asObject If true then it return eZContentClass objects, if not it will be an associative array
     * @param bool $includeFilter If true then it will include only from class groups defined in $groupList, if not it will exclude those groups.
     * @param bool $groupList An array with class group IDs that should be used in filtering, use false if you do not wish to filter at all.
     * @param bool $fetchID A unique name for the current fetch, this must be supplied when filtering is used if you want caching to work.
     * @return array|eZPersistentObject[]
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
            // Cannot create any objects, return empty list.
            return $classList;
        }
        else
        {
            $policies = $accessResult['policies'];
            foreach ( $policies as $policyKey => $policy )
            {
                $policyArray = $this->classListFromPolicy( $policy, $languageCodeList );
                if ( empty( $policyArray ) )
                {
                    continue;
                }
                $classIDArrayPart = $policyArray['classes'];
                $languageCodeArrayPart = $policyArray['language_codes'];
                // No class limitation for this policy AND no previous limitation(s)
                if ( $classIDArrayPart == '*' && empty( $classIDArray ) )
                {
                    $fetchAll = true;
                    $allowedLanguages['*'] = array_unique( array_merge( $allowedLanguages['*'], $languageCodeArrayPart ) );
                }
                else if ( is_array( $classIDArrayPart ) )
                {
                    $fetchAll = false;
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
            $filterSQL = ( " AND" .
                           "      cc.id = ccg.contentclass_id AND" .
                           "      " );
            $filterSQL .= $db->generateSQLINStatement( $groupList, 'ccg.group_id', !$includeFilter, true, 'int' );
        }

        $classNameFilter = eZContentClassName::sqlFilter( 'cc' );

        if ( $fetchAll )
        {
            // If $asObject is true we fetch all fields in class
            $fields = $asObject ? "cc.*, {$classNameFilter['nameField']}" : "cc.id, {$classNameFilter['nameField']}";
            $rows = $db->arrayQuery( "SELECT DISTINCT {$fields} " .
                                     "FROM ezcontentclass cc{$filterTableSQL}, {$classNameFilter['from']} " .
                                     "WHERE cc.version = " . eZContentClass::VERSION_STATUS_DEFINED . " {$filterSQL} AND {$classNameFilter['where']} " .
                                     "ORDER BY {$classNameFilter['nameField']} ASC" );
            $classList = eZPersistentObject::handleRows( $rows, 'eZContentClass', $asObject );
        }
        else
        {
            // If the constrained class list is empty we are not allowed to create any class
            if ( count( $classIDArray ) == 0 )
            {
                return $classList;
            }

            $classIDCondition = $db->generateSQLINStatement( $classIDArray, 'cc.id' );
            // If $asObject is true we fetch all fields in class
            $fields = $asObject ? "cc.*, {$classNameFilter['nameField']}" : "cc.id, {$classNameFilter['nameField']}";
            $rows = $db->arrayQuery( "SELECT DISTINCT {$fields} " .
                                     "FROM ezcontentclass cc{$filterTableSQL}, {$classNameFilter['from']} " .
                                     "WHERE {$classIDCondition} AND" .
                                     "      cc.version = " . eZContentClass::VERSION_STATUS_DEFINED . " {$filterSQL} AND {$classNameFilter['where']} " .
                                     "ORDER BY {$classNameFilter['nameField']} ASC" );
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

    /**
     * Get accesslist for specified function
     *
     * @param string $function
     * @return array|int
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

    /**
     * Returns true if the current user can read this content object.
     *
     * @return bool
     */
    function canRead( )
    {
        if ( !isset( $this->Permissions["can_read"] ) )
        {
            $this->Permissions["can_read"] = $this->checkAccess( 'read' );
        }
        return ( $this->Permissions["can_read"] == 1 );
    }

    /**
     * Returns true if the current user can create a pdf of this content object.
     *
     * @return bool
     */
    function canPdf( )
    {
        if ( !isset( $this->Permissions["can_pdf"] ) )
        {
            $this->Permissions["can_pdf"] = $this->checkAccess( 'pdf' );
        }
        return ( $this->Permissions["can_pdf"] == 1 );
    }

    /**
     * Returns true if the node can be viewed as embeded object by the current user.
     *
     * @return bool
     */
    function canViewEmbed( )
    {
        if ( !isset( $this->Permissions["can_view_embed"] ) )
        {
            $this->Permissions["can_view_embed"] = $this->checkAccess( 'view_embed' );
        }
        return ( $this->Permissions["can_view_embed"] == 1 );
    }

    /**
     * Returns true if the current user can diff this content object.
     *
     * @return bool
     */
    function canDiff( )
    {
        if ( !isset( $this->Permissions["can_diff"] ) )
        {
            $this->Permissions["can_diff"] = $this->checkAccess( 'diff' );
        }
        return ( $this->Permissions["can_diff"] == 1 );
    }

    /**
     * Returns true if the current user can create a content object like this one.
     *
     * @return bool
     */
    function canCreate( )
    {
        if ( !isset( $this->Permissions["can_create"] ) )
        {
            $this->Permissions["can_create"] = $this->checkAccess( 'create' );
        }
        return ( $this->Permissions["can_create"] == 1 );
    }

    /**
     * Returns true if the current user can edit this content object.
     *
     * @param int|bool $originalClassID
     * @param int|bool $parentClassID
     * @param bool $returnAccessList
     * @param string|bool $language
     * @return bool
     */
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

    /**
     * Returns true if the current user can translate this content object.
     *
     * @return bool
     */
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

    /**
     * Returns true if the current user can remove this content object.
     *
     * @return bool
     */
    function canRemove( )
    {

        if ( !isset( $this->Permissions["can_remove"] ) )
        {
            $this->Permissions["can_remove"] = $this->checkAccess( 'remove' );
        }
        return ( $this->Permissions["can_remove"] == 1 );
    }

    /**
     * Returns true if the current user can move this content object.
     *
     * @return bool
     */
    function canMoveFrom( )
    {

        if ( !isset( $this->Permissions['can_move_from'] ) )
        {
            $this->Permissions['can_move_from'] = $this->checkAccess( 'edit' ) && $this->checkAccess( 'remove' );
        }
        return ( $this->Permissions['can_move_from'] == 1 );
    }

    /**
     * Returns the name of the class which this object was created from.
     *
     * The object will cache the class name information so multiple calls will be fast.
     *
     * @return string|bool|null
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

    /**
     * Returns an array of the content actions which can be performed on the current object.
     *
     * @return array|bool
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
            foreach ( $attributeList as $attribute )
            {
                $contentActionList = $attribute->contentActionList();
                if ( is_array( $contentActionList ) && !empty( $contentActionList ) )
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
        return $this->ContentActionList;
    }

    /**
     * Returns true if the given content action is in the content action list
     *
     * @param string $name Name of the content action
     * @return bool
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

    /**
     * Returns the languages the object has been translated into/exists in.
     *
     * @return array An array with the language codes.
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

    /**
     * Returns the languages the object has been translated into/exists in as a JSON string
     *
     * @return bool|string
     */
    function availableLanguagesJsArray()
    {
        return eZContentLanguage::jsArrayByMask( $this->LanguageMask );
    }

    /**
     * @return eZContentLanguage[]|array
     */
    function languages()
    {
        return isset( $this->LanguageMask ) ?
            eZContentLanguage::prioritizedLanguagesByMask( $this->LanguageMask ) :
            array();
    }

    /**
     * @return eZContentLanguage[]|array
     */
    function allLanguages()
    {
        $languages = isset( $this->LanguageMask ) ? eZContentLanguage::languagesByMask( $this->LanguageMask ) : array();
        return $languages;
    }

    /**
     * @return string|bool
     */
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

    /**
     * Set default language. Checks if default language is valid.
     *
     * @deprecated
     * @param string $lang Default language.
     * @return bool
     */
    static function setDefaultLanguage( $lang )
    {
        return false;
    }

    /**
     * @param string $name
     */
    function setClassName( $name )
    {
        $this->ClassName = $name;
    }

    /**
     * Returns an array with locale strings, these strings represents the languages which content objects
     * are allowed to be translated into.
     *
     * The setting ContentSettings/TranslationList in site.ini determines the array.
     *
     * @return string[]
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

    /**
     * Returns an array with locale objects, these objects represents the languages the content objects are
     * allowed to be translated into.
     *
     * The setting ContentSettings/TranslationList in site.ini determines the array.
     *
     * @return eZLocale[]
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

    /**
     * Returns the attributes for the content object version \a $version and content object \a $contentObjectID.
     *
     * @param int $version
     * @param bool $asObject
     * @return eZContentClassAttribute[]|array|null
     */
    function fetchClassAttributes( $version = 0, $asObject = true )
    {
        return eZContentClassAttribute::fetchListByClassID( $this->attribute( 'contentclass_id' ), $version, $asObject );
    }

    /**
     * Maps input lange to another one if defined in $options['language_map'].
     *
     * If it cannot map it returns the original language.
     *
     * @param string $language
     * @param array $options
     * @return string
     */
    static function mapLanguage( $language, $options )
    {
        if ( isset( $options['language_map'][$language] ) )
        {
            return $options['language_map'][$language];
        }
        return $language;
    }

    /**
     * Unserialize xml structure. Creates an object from xml input.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param mixed $package
     * @param DOMElement $domNode
     * @param array $options
     * @param int|bool $ownerID Override owner ID, null to use XML owner id (optional)
     * @param string $handlerType
     * @return array|bool|eZContentObject|null created object, false if could not create object/xml invalid
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

        /** @var DOMElement $versionListNode */
        $versionListNode = $domNode->getElementsByTagName( 'version-list' )->item( 0 );

        $importedLanguages = array();
        foreach( $versionListNode->getElementsByTagName( 'version' ) as $versionDOMNode )
        {
            /** @var DOMElement $versionDOMNode */
            foreach ( $versionDOMNode->getElementsByTagName( 'object-translation' ) as $versionDOMNodeChild )
            {
                /** @var DOMElement $versionDOMNodeChild */
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
                    $contentObject->setAttribute( 'remote_id', eZRemoteIdUtility::generate( 'object' ) );
                    $contentObject->store();
                    unset( $contentObject );
                    $contentObject = $contentClass->instantiateIn( $initialLanguage, $ownerID, $sectionID );
                    $firstVersion = true;
                } break;

                default:
                {
                    $options['error'] = array( 'error_code' => self::PACKAGE_ERROR_EXISTS,
                                               'element_id' => $remoteID,
                                               'description' => $description,
                                               'actions' => array( self::PACKAGE_REPLACE => ezpI18n::tr( 'kernel/classes', 'Replace existing object' ),
                                                                   self::PACKAGE_SKIP    => ezpI18n::tr( 'kernel/classes', 'Skip object' ),
                                                                   self::PACKAGE_NEW     => ezpI18n::tr( 'kernel/classes', 'Keep existing and create a new one' ),
                                                                   self::PACKAGE_UPDATE  => ezpI18n::tr( 'kernel/classes', 'Update existing object' ) ) );
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

        $sectionObject = eZSection::fetch( $sectionID );
        if ( $sectionObject instanceof eZSection )
        {
            $updateWithParentSection = false;
        }
        else
        {
            $updateWithParentSection = true;
        }

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
                                                                         $package,
                                                                         'ezcontentobject',
                                                                         $initialLanguage );

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
                    eZContentObjectTreeNode::updateMainNodeID( $existingMainNode['node_id'],
                                                               $mainNodeInfo['contentobject_id'],
                                                               $mainNodeInfo['contentobject_version'],
                                                               $mainNodeInfo['parent_node'],
                                                               $updateWithParentSection );
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
                    eZDebug::writeNotice( "Setting name '$objectName' for version ($versionNum) of the content object ($objectID) in language($translation)", __METHOD__ );
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

    /**
     * Performs additional unserialization actions that need to be performed when all
     * objects contained in the package are already installed. (maintain objects' cross-relations)
     *
     * @param mixed $package
     */
    function postUnserialize( $package )
    {
        foreach( $this->versions() as $version )
        {
            $version->postUnserialize( $package );
        }
    }

    /**
     * Returns a DOM structure of the content object and it's attributes.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param mixed $package
     * @param int|bool $specificVersion Content object version, true for current version, false for all, else array containing specific versions.
     * @param array|bool $options Package options or false
     * @param int[]|bool $contentNodeIDArray Array of allowed nodes or false
     * @param int[]|bool $topNodeIDArray Array of top nodes in current package export or false
     * @return bool|DOMElement
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
            $this->setAttribute( 'remote_id', eZRemoteIdUtility::generate( 'object' ) );
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

    /**
     * Returns a structure with information required for caching.
     *
     * @param $Params array
     * @return array
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

    /**
     * Sets all view cache files to be expired
     */
    static function expireAllViewCache()
    {
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-view-cache', time() );
        $handler->store();
    }

    /**
     * Sets all content cache files to be expired. Both view cache and cache blocks are expired.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * Note: This is considered a internal function, instead use {@see eZContentCacheManager::clearAllContentCache}
     */
    static function expireAllCache()
    {
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-view-cache', time() );
        $handler->setTimestamp( 'template-block-cache', time() );
        $handler->setTimestamp( 'content-tree-menu', time() );
        $handler->store();
    }

    /**
     * Expires all template block cache. This should be expired anytime any content is published/modified or removed.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function expireTemplateBlockCache()
    {
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'template-block-cache', time() );
        $handler->store();
    }

    /**
     * Sets all complex viewmode content cache files to be expired.
     *
     * Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function expireComplexViewModeCache()
    {
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-complex-viewmode-cache', time() );
        $handler->store();
    }

    /**
     * Returns true if the content cache timestamp $timestamp is expired.
     *
     * @param int $timestamp UNIX Timestamp
     * @return bool
     */
    static function isCacheExpired( $timestamp )
    {
        $handler = eZExpiryHandler::instance();
        if ( !$handler->hasTimestamp( 'content-view-cache' ) )
            return false;
        $expiryTime = $handler->timestamp( 'content-view-cache' );
        if ( $expiryTime > $timestamp )
            return true;
        return false;
    }

    /**
     * Returns true if the viewmode is a complex viewmode.
     *
     * @param string $viewMode
     * @return bool
     */
    static function isComplexViewMode( $viewMode )
    {
        $ini = eZINI::instance();
        $viewModes = $ini->variableArray( 'ContentSettings', 'ComplexDisplayViewModes' );
        return in_array( $viewMode, $viewModes );
    }

    /**
     * Returns true if the viewmode is a complex viewmode and the viewmode timestamp is expired.
     *
     * @param string $viewMode
     * @param int $timestamp UNIX Timestamp
     * @return bool
     */
    static function isComplexViewModeCacheExpired( $viewMode, $timestamp )
    {
        if ( !eZContentObject::isComplexViewMode( $viewMode ) )
            return false;
        $handler = eZExpiryHandler::instance();
        if ( !$handler->hasTimestamp( 'content-complex-viewmode-cache' ) )
            return false;
        $expiryTime = $handler->timestamp( 'content-complex-viewmode-cache' );
        if ( $expiryTime > $timestamp )
            return true;
        return false;
    }

    /**
     * Returns a list of all the authors for this object
     *
     * @return eZUser[]
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

    /**
     * Returns the number of objects of the given class is created by the given user.
     *
     * @param int $classID
     * @param int $userID
     * @param int|bool $status
     * @return int
     */
    static function fetchObjectCountByUserID( $classID, $userID, $status = false )
    {
        $count = 0;
        if ( is_numeric( $classID ) and is_numeric( $userID ) )
        {
            $db         = eZDB::instance();
            $classID    = (int) $classID;
            $userID     = (int) $userID;
            $statusSQL  = $status !== false ? ( ' AND status=' . (int) $status ) : '';
            $countArray = $db->arrayQuery( "SELECT count(*) AS count FROM ezcontentobject WHERE contentclass_id=$classID AND owner_id=$userID$statusSQL" );
            $count = $countArray[0]['count'];
        }
        return $count;
    }

    /**
     * @param int|bool $versionStatus
     * @deprecated This method is left here only for backward compatibility. Use eZContentObjectVersion::removeVersions() method instead.
     */
    static function removeVersions( $versionStatus = false )
    {
        eZContentObjectVersion::removeVersions( $versionStatus );
    }

    /**
     * Sets the object's name to $newName: tries to find attributes that are in 'object pattern name' and updates them
     *
     * @param string $newName
     * @return bool true if object's name was changed, otherwise false.
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

    /**
     * Removes a translation from the current object
     *
     * @param int $languageID
     * @return bool
     */
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

    /**
     * @return int
     */
    function isAlwaysAvailable()
    {
        return ( $this->attribute( 'language_mask' ) & 1 );
    }

    /**
     * @param int $languageID
     * @param int|bool $version
     */
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

    /**
     * @return eZSection[]|null
     */
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
        $sql = 'SELECT ezcobj_state.id
                FROM   ezcobj_state, ezcobj_state_group
                WHERE  ezcobj_state.group_id = ezcobj_state_group.id
                    AND ezcobj_state_group.identifier NOT LIKE \'ez%\'';
        if ( $access['accessWord'] == 'yes' )
        {
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

                        //This case is based on the similar if statement in the method : classListFromPolicy
                        case 'User_Subtree':
                        {
                            $allowed = false;
                            foreach ( $this->attribute( 'assigned_nodes' ) as $assignedNode )
                            {
                                $path = $assignedNode->attribute( 'path_string' );
                                foreach ( $policy['User_Subtree'] as $subtreeString )
                                {
                                    if ( strpos( $path, $subtreeString ) !== false )
                                    {
                                        $allowed = true;
                                        break;
                                    }
                                }
                            }
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

    /**
     * @param eZUser|null $user
     * @return array
     */
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
     * @param bool $refreshCache if the cache in the member variable needs to be refreshed
     * @return array an associative array with state group id => state id pairs
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

    /**
     * @return array
     */
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

        $currentStateIDArray = $this->stateIDArray( true );
        $currentStateID = $currentStateIDArray[$groupID];

        if ( $currentStateID == $stateID )
        {
            return false;
        }

        $sql = "UPDATE ezcobj_state_link
                SET contentobject_state_id=$stateID
                WHERE contentobject_state_id=$currentStateID AND
                      contentobject_id=$contentObjectID";
        eZDB::instance()->query( $sql );

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

    /**
     * Restores attributes for current content object when it's being restored from trash
     */
    public function restoreObjectAttributes()
    {
        $db = eZDB::instance();
        $db->begin();

        foreach ( $this->allContentObjectAttributes( $this->attribute( "id" ) ) as $contentObjectAttribute )
        {
            $datatype = $contentObjectAttribute->dataType();
            if ( !$datatype instanceof eZDataType )
            {
                eZDebug::writeError( "Attribute #{$contentObjectAttribute->attribute( "id" )} from contentobject #{$this->attribute( "id" )} isn't valid", __METHOD__ );
                continue;
            }

            $datatype->restoreTrashedObjectAttribute( $contentObjectAttribute );
        }

        $db->commit();
    }

    /**
     * Returns object's section identifier
     *
     * @return string|bool
     */
    public function sectionIdentifier()
    {
        $section = eZSection::fetch( $this->attribute( 'section_id' ) );
        if( $section instanceof eZSection )
        {
            return $section->attribute( 'identifier' );
        }
        return false;
    }

    /**
     * @var int Object ID
     */
    public $ID;

    /**
     * @var string Object name
     */
    public $Name;

    /**
     * @var bool|string Stores the current language
     */
    public $CurrentLanguage;

    /**
     * @var bool|string Stores the current class name
     */
    public $ClassName;

    /**
     * @var string The object's class identifier
     */
    public $ClassIdentifier;

    /**
     * @var eZContentObjectAttribute[] The datamap for content object attributes
     */
    public $DataMap = array();

    /**
     * @var array|bool Contains an array of the content object actions for the current object
     */
    public $ContentActionList = false;

    /**
     * @var eZContentObjectAttribute[] Contains a cached version of the content object attributes for the given version and language
     */
    public $ContentObjectAttributes = array();

    /**
     * @var int|bool Contains the main node id for this object
     */
    public $MainNodeID = false;

    /**
     * @var array Contains the arrays of relatedobject id by fetching input for this object
     */
    public $InputRelationList = array();

    /**
     * Cache for the state ID array
     *
     * @var array|bool
     * @see stateIDArray()
     */
    private $StateIDArray = false;
}

?>
