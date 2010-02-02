<?php
//
// Definition of eZContentClass class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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
  \class eZContentClass ezcontentclass.php
  \ingroup eZKernel
  \brief Handles eZ Publish content classes

  \sa eZContentObject
*/

require_once( "kernel/common/i18n.php" );

class eZContentClass extends eZPersistentObject
{
    const VERSION_STATUS_DEFINED = 0;
    const VERSION_STATUS_TEMPORARY = 1;
    const VERSION_STATUS_MODIFIED = 2;

    function eZContentClass( $row )
    {
        if ( is_array( $row ) )
        {
            $this->eZPersistentObject( $row );
            $this->VersionCount = false;
            $this->InGroups = null;
            $this->AllGroups = null;
            if ( isset( $row["version_count"] ) )
                $this->VersionCount = $row["version_count"];

            $this->NameList = new eZContentClassNameList();
            if ( isset( $row['serialized_name_list'] ) )
                $this->NameList->initFromSerializedList( $row['serialized_name_list'] );
            else
                $this->NameList->initDefault();

            $this->DescriptionList = new eZSerializedObjectNameList();
            if ( isset( $row['serialized_description_list'] ) )
                $this->DescriptionList->initFromSerializedList( $row['serialized_description_list'] );
            else
                $this->DescriptionList->initDefault();
        }
        $this->DataMap = false;
    }

    static function definition()
    {
        static $definition = array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "version" => array( 'name' => 'Version',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "serialized_name_list" => array( 'name' => 'SerializedNameList',
                                                                          'datatype' => 'string',
                                                                          'default' => '',
                                                                          'required' => true ),
                                         'serialized_description_list' => array( 'name' => 'SerializedDescriptionList',
                                                                          'datatype' => 'string',
                                                                          'default' => '',
                                                                          'required' => true ),
                                         "identifier" => array( 'name' => "Identifier",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         "contentobject_name" => array( 'name' => "ContentObjectName",
                                                                        'datatype' => 'string',
                                                                        'default' => '',
                                                                        'required' => true ),
                                         "url_alias_name" => array( 'name' => "URLAliasName",
                                                                    'datatype' => 'string',
                                                                    'default' => '',
                                                                    'required' => false ),
                                         "creator_id" => array( 'name' => "CreatorID",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true,
                                                                'foreign_class' => 'eZUser',
                                                                'foreign_attribute' => 'contentobject_id',
                                                                'multiplicity' => '1..*' ),
                                         "modifier_id" => array( 'name' => "ModifierID",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true,
                                                                 'foreign_class' => 'eZUser',
                                                                 'foreign_attribute' => 'contentobject_id',
                                                                 'multiplicity' => '1..*' ),
                                         "created" => array( 'name' => "Created",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "remote_id" => array( 'name' => "RemoteID",
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         "modified" => array( 'name' => "Modified",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         "is_container" => array( 'name' => "IsContainer",
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         'always_available' => array( 'name' => "AlwaysAvailable",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         'language_mask' => array( 'name' => "LanguageMask",
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true ),
                                         'initial_language_id' => array( 'name' => "InitialLanguageID",
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true,
                                                                         'foreign_class' => 'eZContentLanguage',
                                                                         'foreign_attribute' => 'id',
                                                                         'multiplicity' => '1..*' ),
                                         'sort_field' => array( 'name' => 'SortField',
                                                                'datatype' => 'integer',
                                                                'default' => 1,
                                                                'required' => true ),
                                         'sort_order' => array( 'name' => 'SortOrder',
                                                                'datatype' => 'integer',
                                                                'default' => 1,
                                                                'required' => true ) ),
                      "keys" => array( "id", "version" ),
                      "function_attributes" => array( "data_map" => "dataMap",
                                                      'object_count' => 'objectCount',
                                                      'object_list' => 'objectList',
                                                      'version_count' => 'versionCount',
                                                      'version_status' => 'versionStatus',
                                                      'remote_id' => 'remoteID', // Note: This overrides remote_id field
                                                      'ingroup_list' => 'fetchGroupList',
                                                      'ingroup_id_list' => 'fetchGroupIDList',
                                                      'match_ingroup_id_list' => 'fetchMatchGroupIDList',
                                                      'group_list' => 'fetchAllGroups',
                                                      'creator' => 'creator',
                                                      'modifier' => 'modifier',
                                                      'can_instantiate_languages' => 'canInstantiateLanguages',
                                                      'name' => 'name',
                                                      'nameList' => 'nameList',
                                                      'description' => 'description',
                                                      'descriptionList' => 'descriptionList',
                                                      'languages' => 'languages',
                                                      'prioritized_languages' => 'prioritizedLanguages',
                                                      'prioritized_languages_js_array' => 'prioritizedLanguagesJsArray',
                                                      'can_create_languages' => 'canCreateLanguages',
                                                      'top_priority_language_locale' => 'topPriorityLanguageLocale',
                                                      'always_available_language' => 'alwaysAvailableLanguage' ),
                      'set_functions' => array( 'name' => 'setName' ),
                      "increment_key" => "id",
                      "class_name" => "eZContentClass",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentclass" );
        return $definition;
    }

    function __clone()
    {
        unset( $this->Version );
        unset( $this->InGroups );
        unset( $this->AllGroups );
        unset( $this->CanInstantiateLanguages );
        unset( $this->VersionCount );
        $this->ID = null;
        $this->RemoteID = md5( (string)mt_rand() . (string)time() );
    }

    /*!
     Creates an 'eZContentClass' object.

     To specify contentclass name use either $optionalValues['serialized_name_list'] or
     combination of $optionalValues['name'] and/or $languageLocale.

     In case of conflict(when both 'serialized_name_list' and 'name' with/without $languageLocale
     are specified) 'serialized_name_list' has top priority. This means that 'name' and
     $languageLocale will be ingnored because 'serialized_name_list' already has all needed info
     about names and languages.

     If 'name' is specified then the contentclass will have a name in $languageLocale(if specified) or
     in default language.

     If neither of 'serialized_name_list' or 'name' isn't specified then the contentclass will have an empty
     name in 'languageLocale'(if specified) or in default language.

     'language_mask' and 'initial_language_id' attributes will be set according to specified(either
     in 'serialized_name_list' or by $languageLocale) languages.

     \return 'eZContentClass' object.
    */
    static function create( $userID = false, $optionalValues = array(), $languageLocale = false )
    {
        $dateTime = time();
        if ( !$userID )
            $userID = eZUser::currentUserID();

        $nameList = new eZContentClassNameList();
        if ( isset( $optionalValues['serialized_name_list'] ) )
            $nameList->initFromSerializedList( $optionalValues['serialized_name_list'] );
        else if ( isset( $optionalValues['name'] ) )
            $nameList->initFromString( $optionalValues['name'], $languageLocale );
        else
            $nameList->initFromString( '', $languageLocale );
            
        $descriptionList = new eZSerializedObjectNameList();
        if ( isset( $optionalValues['serialized_description_list'] ) )
            $descriptionList->initFromSerializedList( $optionalValues['serialized_description_list'] );
        else if ( isset( $optionalValues['description'] ) )
            $descriptionList->initFromString( $optionalValues['description'], $languageLocale );
        else
            $descriptionList->initFromString( '', $languageLocale );

        $languageMask = $nameList->languageMask();
        $initialLanguageID = $nameList->alwaysAvailableLanguageID();

        $contentClassDefinition = eZContentClass::definition();
        $row = array(
            "id" => null,
            "version" => 1,
            "serialized_name_list" => $nameList->serializeNames(),
            'serialized_description_list' => $descriptionList->serializeNames(),
            "identifier" => "",
            "contentobject_name" => "",
            "creator_id" => $userID,
            "modifier_id" => $userID,
            "created" => $dateTime,
            'remote_id' => md5( (string)mt_rand() . (string)time() ),
            "modified" => $dateTime,
            "is_container" => $contentClassDefinition[ 'fields' ][ 'is_container' ][ 'default' ],
            "always_available" => $contentClassDefinition[ 'fields' ][ 'always_available' ][ 'default' ],
            'language_mask' => $languageMask,
            'initial_language_id' => $initialLanguageID,
            "sort_field" => $contentClassDefinition[ 'fields' ][ 'sort_field' ][ 'default' ],
            "sort_order" => $contentClassDefinition[ 'fields' ][ 'sort_order' ][ 'default' ] );

        $row = array_merge( $row, $optionalValues );

        $contentClass = new eZContentClass( $row );

        // setting 'dirtyData' to make sure the 'NameList' will be stored into db.
        $contentClass->NameList->setHasDirtyData( true );

        return $contentClass;
    }

    function instantiateIn( $lang, $userID = false, $sectionID = 0, $versionNumber = false, $versionStatus = eZContentObjectVersion::STATUS_INTERNAL_DRAFT )
    {
        return eZContentClass::instantiate( $userID, $sectionID, $versionNumber, $lang, $versionStatus );
    }

    /*!
     Creates a new content object instance and stores it.

     \param userID user ID (optional), current user if not set (also store object id in session if $userID = false)
     \param sectionID section ID (optional), 0 if not set
     \param versionNumber version number, create initial version if not set
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function instantiate( $userID = false, $sectionID = 0, $versionNumber = false, $languageCode = false, $versionStatus = eZContentObjectVersion::STATUS_INTERNAL_DRAFT )
    {
        $attributes = $this->fetchAttributes();

        if ( $userID === false )
        {
            $user   = eZUser::currentUser();
            $userID = $user->attribute( 'contentobject_id' );
        }

        if ( $languageCode == false )
        {
            $languageCode = eZContentObject::defaultLanguage();
        }

        $object = eZContentObject::create( ezi18n( "kernel/contentclass", "New %1", null, array( $this->name( $languageCode ) ) ),
                                           $this->attribute( "id" ),
                                           $userID,
                                           $sectionID,
                                           1,
                                           $languageCode );

        if ( $this->attribute( 'always_available' ) )
        {
            $object->setAttribute( 'language_mask', (int)$object->attribute( 'language_mask') | 1 );
        }

        $db = eZDB::instance();
        $db->begin();

        $object->store();
        $object->assignDefaultStates();
        $object->setName( ezi18n( "kernel/contentclass", "New %1", null, array( $this->name( $languageCode ) ) ), false, $languageCode );

        if ( !$versionNumber )
        {
            $version = $object->createInitialVersion( $userID, $languageCode );
        }
        else
        {
            $version = eZContentObjectVersion::create( $object->attribute( "id" ), $userID, $versionNumber, $languageCode );
        }
        if ( $versionStatus !== false )
        {
            $version->setAttribute( 'status', $versionStatus );
        }

        $version->store();

        foreach ( $attributes as $attribute )
        {
            $attribute->instantiate( $object->attribute( 'id' ), $languageCode );
        }

        if ( isset( $user ) && $user instanceof eZUser && $user->isAnonymous() )
        {
            $createdObjectIDList = eZPreferences::value( 'ObjectCreationIDList' );
            if ( !$createdObjectIDList )
            {
                $createdObjectIDList = array( $object->attribute( 'id' ) );
            }
            else
            {
                $createdObjectIDList = unserialize( $createdObjectIDList );
                $createdObjectIDList[] = $object->attribute( 'id' );
            }
            eZPreferences::setValue( 'ObjectCreationIDList', serialize( $createdObjectIDList ) );
        }

        $db->commit();
        return $object;
    }

    function canInstantiateClasses()
    {
        $ini = eZINI::instance();
        $enableCaching = $ini->variable( 'RoleSettings', 'EnableCaching' );

        if ( $enableCaching == 'true' )
        {
            $http = eZHTTPTool::instance();

            eZExpiryHandler::registerShutdownFunction();
            $handler = eZExpiryHandler::instance();
            $expiredTimeStamp = 0;
            if ( $handler->hasTimestamp( 'user-class-cache' ) )
                $expiredTimeStamp = $handler->timestamp( 'user-class-cache' );

            $classesCachedForUser = $http->sessionVariable( 'CanInstantiateClassesCachedForUser' );
            $classesCachedTimestamp = $http->sessionVariable( 'ClassesCachedTimestamp' );
            $user = eZUser::currentUser();
            $userID = $user->id();

            if ( ( $classesCachedTimestamp >= $expiredTimeStamp ) && $classesCachedForUser == $userID )
            {
                if ( $http->hasSessionVariable( 'CanInstantiateClasses' ) )
                {
                    return $http->sessionVariable( 'CanInstantiateClasses' );
                }
            }
            else
            {
                // store cache
                $http->setSessionVariable( 'CanInstantiateClassesCachedForUser', $userID );
            }
        }
        $user = eZUser::currentUser();
        $accessResult = $user->hasAccessTo( 'content' , 'create' );
        $accessWord = $accessResult['accessWord'];
        $canInstantiateClasses = 1;
        if ( $accessWord == 'no' )
        {
            $canInstantiateClasses = 0;
        }

        if ( $enableCaching == 'true' )
        {
            $http->setSessionVariable( 'CanInstantiateClasses', $canInstantiateClasses );
        }
        return $canInstantiateClasses;
    }

    // code-template::create-block: can-instantiate-class-list, group-filter, role-caching, class-policy-list, name-instantiate, object-creation, class-sql-creation, static-method
    // code-template::auto-generated:START can-instantiate-class-list
    // This code is automatically generated from templates/classcreatelist.ctpl
    // DO NOT EDIT THIS CODE DIRECTLY, CHANGE THE TEMPLATE FILE INSTEAD

    /*!
     \static
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
    static function canInstantiateClassList( $asObject = false, $includeFilter = true, $groupList = false, $fetchID = false )
    {
        $ini = eZINI::instance();
        $groupArray = array();

        $enableCaching = ( $ini->variable( 'RoleSettings', 'EnableCaching' ) == 'true' );
        if ( is_array( $groupList ) )
        {
            if ( $fetchID == false )
                $enableCaching = false;
        }

        if ( $enableCaching )
        {
            $http = eZHTTPTool::instance();
            eZExpiryHandler::registerShutdownFunction();
            $handler = eZExpiryHandler::instance();
            $expiredTimeStamp = 0;
            if ( $handler->hasTimestamp( 'user-class-cache' ) )
                $expiredTimeStamp = $handler->timestamp( 'user-class-cache' );

            $classesCachedForUser = $http->sessionVariable( 'ClassesCachedForUser' );
            $classesCachedTimestamp = $http->sessionVariable( 'ClassesCachedTimestamp' );

            $cacheVar = 'CanInstantiateClassList';
            if ( is_array( $groupList ) and $fetchID !== false )
            {
                $cacheVar = 'CanInstantiateClassListGroup';
            }

            $user = eZUser::currentUser();
            $userID = $user->id();
            if ( ( $classesCachedTimestamp >= $expiredTimeStamp ) && $classesCachedForUser == $userID )
            {
                if ( $http->hasSessionVariable( $cacheVar ) )
                {
                    if ( $fetchID !== false )
                    {
                        // Check if the group contains our ID, if not we need to fetch from DB
                        $groupArray = $http->sessionVariable( $cacheVar );
                        if ( isset( $groupArray[$fetchID] ) )
                        {
                            return $groupArray[$fetchID];
                        }
                    }
                    else
                    {
                        return $http->sessionVariable( $cacheVar );
                    }
                }
            }
            else
            {
                $http->setSessionVariable( 'ClassesCachedForUser' , $userID );
                $http->setSessionVariable( 'ClassesCachedTimestamp', time() );
            }
        }

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
                $classIDArrayPart = '*';
                if ( isset( $policy['Class'] ) )
                {
                    $classIDArrayPart = $policy['Class'];
                }
                $languageCodeArrayPart = $languageCodeList;
                if ( isset( $policy['Language'] ) )
                {
                    $languageCodeArrayPart = array_intersect( $policy['Language'], $languageCodeList );
                }

                if ( $classIDArrayPart == '*' )
                {
                    $fetchAll = true;
                    $allowedLanguages['*'] = array_unique( array_merge( $allowedLanguages['*'], $languageCodeArrayPart ), SORT_STRING );
                }
                else
                {
                    foreach( $classIDArrayPart as $class )
                    {
                        if ( isset( $allowedLanguages[$class] ) )
                        {
                            $allowedLanguages[$class] = array_unique( array_merge( $allowedLanguages[$class], $languageCodeArrayPart ), SORT_STRING );
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
        $filterSQL .= " AND\n      cc.id=" . $classNameFilter['from'] . ".contentclass_id";

        if ( $fetchAll )
        {
            // If $asObject is true we fetch all fields in class
            $fields = $asObject ? "cc.*, $classNameFilter[nameField]" : "cc.id, $classNameFilter[nameField]";
            $rows = $db->arrayQuery( "SELECT DISTINCT $fields\n" .
                                     "FROM ezcontentclass cc$filterTableSQL, $classNameFilter[from]\n" .
                                     "WHERE cc.version = " . eZContentClass::VERSION_STATUS_DEFINED . " $filterSQL\n" .
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
                                     "      cc.version = " . eZContentClass::VERSION_STATUS_DEFINED . " $filterSQL\n" .
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
                    $languageCodes = array_unique( array_merge( $allowedLanguages['*'], $allowedLanguages[$id] ), SORT_STRING );
                }
                else
                {
                    $languageCodes = $allowedLanguages['*'];
                }
                $classList[$key]->setCanInstantiateLanguages( $languageCodes );
            }
        }

        eZDebugSetting::writeDebug( 'kernel-content-class', $classList, "class list fetched from db" );
        if ( $enableCaching )
        {
            if ( $fetchID !== false )
            {
                $groupArray[$fetchID] = $classList;
                $http->setSessionVariable( $cacheVar, $groupArray );
            }
            else
            {
                $http->setSessionVariable( $cacheVar, $classList );
            }
        }

        return $classList;
    }

    // This code is automatically generated from templates/classcreatelist.ctpl
    // code-template::auto-generated:END can-instantiate-class-list

    /*!
     \return The creator of the class as an eZUser object by using the $CreatorID as user ID.
    */
    function creator()
    {
        if ( isset( $this->CreatorID ) and $this->CreatorID )
        {
            return eZUser::fetch( $this->CreatorID );
        }
        return null;
    }

    /*!
     \return The modifier of the class as an eZUser object by using the $ModifierID as user ID.
    */
    function modifier()
    {
        if ( isset( $this->ModifierID ) and $this->ModifierID )
        {
            return eZUser::fetch( $this->ModifierID );
        }
        return null;
    }

    /*!
     Find all groups the current class is placed in and returns a list of group objects.
     \return An array with eZContentClassGroup objects.
     \sa fetchGroupIDList()
    */
    function fetchGroupList()
    {
        $this->InGroups = eZContentClassClassGroup::fetchGroupList( $this->attribute( "id" ),
                                                                     $this->attribute( "version" ),
                                                                     true );
        return $this->InGroups;
    }

    /*!
     Find all groups the current class is placed in and returns a list of group IDs.
     \return An array with integers (ids).
     \sa fetchGroupList()
    */
    function fetchGroupIDList()
    {
        $list = eZContentClassClassGroup::fetchGroupList( $this->attribute( "id" ),
                                                          $this->attribute( "version" ),
                                                          false );
        $this->InGroupIDs = array();
        foreach ( $list as $item )
        {
            $this->InGroupIDs[] = $item['group_id'];
        }
        return $this->InGroupIDs;
    }

    /*!
     Returns the result from fetchGroupIDList() if class group overrides is
     enabled in content.ini.
     \return An array with eZContentClassGroup objects or \c false if disabled.
     \note \c EnableClassGroupOverride in group \c ContentOverrideSettings from INI file content.ini
           controls this behaviour.
    */
    function fetchMatchGroupIDList()
    {
        $contentINI = eZINI::instance( 'content.ini' );
        if( $contentINI->variable( 'ContentOverrideSettings', 'EnableClassGroupOverride' ) == 'true' )
        {
            return $this->attribute( 'ingroup_id_list' );
        }

        return false;
    }

    /*!
     Finds all Classes in the system and returns them.
     \return An array with eZContentClass objects.
    */
    static function fetchAllClasses( $asObject = true, $includeFilter = true, $groupList = false )
    {
        $filterTableSQL = '';
        $filterSQL = '';
        if ( is_array( $groupList ) )
        {
            $filterTableSQL = ', ezcontentclass_classgroup ccg';
            $filterSQL = ( " AND\n" .
                           "      cc.id = ccg.contentclass_id AND\n" .
                           "      ccg.group_id " );
            $groupText = implode( ', ', $groupList );
            if ( $includeFilter )
                $filterSQL .= "IN ( $groupText )";
            else
                $filterSQL .= "NOT IN ( $groupText )";
        }

        $classNameFilter = eZContentClassName::sqlFilter( 'cc' );

        $classList = array();
        $db = eZDb::instance();
        // If $asObject is true we fetch all fields in class
        $fields = $asObject ? "cc.*" : "cc.id, $classNameFilter[nameField]";
        $rows = $db->arrayQuery( "SELECT DISTINCT $fields\n" .
                                 "FROM ezcontentclass cc$filterTableSQL, $classNameFilter[from]\n" .
                                 "WHERE cc.version = " . eZContentClass::VERSION_STATUS_DEFINED . "$filterSQL AND $classNameFilter[where]\n" .
                                 "ORDER BY $classNameFilter[nameField] ASC" );

        $classList = eZPersistentObject::handleRows( $rows, 'eZContentClass', $asObject );
        return $classList;
    }

    /*!
     Finds all Class groups in the system and returns them.
     \return An array with eZContentClassGroup objects.
     \sa fetchGroupList(), fetchGroupIDList()
    */
    function fetchAllGroups()
    {
        $this->AllGroups = eZContentClassGroup::fetchList();
        return $this->AllGroups;
    }

    /*!
     \return true if the class is part of the group \a $groupID
    */
    function inGroup( $groupID )
    {
        return eZContentClassClassGroup::classInGroup( $this->attribute( 'id' ),
                                                       $this->attribute( 'version' ),
                                                       $groupID );
    }

    /*!
     \static
     Will remove all temporary classes from the database.
    */
    static function removeTemporary()
    {
        $version = eZContentClass::VERSION_STATUS_TEMPORARY;
        $temporaryClasses = eZContentClass::fetchList( $version, true );
        $db = eZDB::instance();
        $db->begin();
        foreach ( $temporaryClasses as $class )
        {
            $class->remove( true, $version );
        }
        eZPersistentObject::removeObject( eZContentClassAttribute::definition(),
                                          array( 'version' => $version ) );

        $db->commit();
    }

    /*!
     Get remote id of content node
    */
    function remoteID()
    {
        $remoteID = eZPersistentObject::attribute( 'remote_id', true );
        if ( !$remoteID &&
             $this->Version == eZContentClass::VERSION_STATUS_DEFINED )
        {
            $this->setAttribute( 'remote_id', md5( (string)mt_rand() . (string)time() ) );
            $this->sync( array( 'remote_id' ) );
            $remoteID = eZPersistentObject::attribute( 'remote_id', true );
        }

        return $remoteID;
    }

    /*!
     \note If you want to remove a class with all data associated with it (objects/classMembers)
           you should use eZContentClassOperations::remove()
    */
    function remove( $removeAttributes = false, $version = eZContentClass::VERSION_STATUS_DEFINED )
    {
        // If we are not allowed to remove just return false
        if ( $this->Version != eZContentClass::VERSION_STATUS_TEMPORARY && !$this->isRemovable() )
            return false;

        if ( is_array( $removeAttributes ) or $removeAttributes )
            $this->removeAttributes( $removeAttributes );

        $this->NameList->remove( $this );
        eZPersistentObject::remove();
    }

    /*!
     Checks if the class can be removed and returns \c true if it can, \c false otherwise.
     \sa removableInformation()
    */
    function isRemovable()
    {
        $info = $this->removableInformation( false );
        return count( $info['list'] ) == 0;
    }

    /*!
     Returns information on why the class cannot be removed,
     it does the same checks as in isRemovable() but generates
     some text in the return array.
     \return An array which contains:
             - text - Plain text description why this cannot be removed
             - list - An array with reasons why this failed, each entry contains:
                      - text - Plain text description of the reason.
                      - list - A sublist of reason (e.g from an attribute), is optional.
     \param $includeAll Controls whether the returned information will contain all
                        sources for not being to remove or just the first that it finds.
    */
    function removableInformation( $includeAll = true )
    {
        $result  = array( 'text' => ezi18n( 'kernel/contentclass', "Cannot remove class '%class_name':",
                                         null, array( '%class_name' => $this->attribute( 'name' ) ) ),
                       'list' => array() );
        $db      = eZDB::instance();

        // Check top-level nodes
        $rows = $db->arrayQuery( "SELECT ezcot.node_id
FROM ezcontentobject_tree ezcot, ezcontentobject ezco
WHERE ezcot.depth = 1 AND
      ezco.contentclass_id = $this->ID AND
      ezco.id=ezcot.contentobject_id" );
        if ( count( $rows ) > 0 )
        {
            $result['list'][] = array( 'text' => ezi18n( 'kernel/contentclass', 'The class is used by a top-level node and cannot be removed.
You will need to change the class of the node by using the swap functionality.' ) );
            if ( !$includeAll )
                return $result;
        }

        // Check class attributes
        foreach ( $this->fetchAttributes() as $attribute )
        {
            $dataType = $attribute->dataType();
            if ( !$dataType->isClassAttributeRemovable( $attribute ) )
            {
                $info = $dataType->classAttributeRemovableInformation( $attribute, $includeAll );
                $result['list'][] = $info;
                if ( !$includeAll )
                    return $result;
            }
        }

        return $result;
    }

    /*!
     \note Removes class attributes

     \param removeAtttributes Array of attributes to remove
     \param version Version to remove( optional )
    */
    function removeAttributes( $removeAttributes = false, $version = false )
    {
        if ( is_array( $removeAttributes ) )
        {
            $db = eZDB::instance();
            $db->begin();
            foreach( $removeAttributes as $attribute )
            {
                $attribute->removeThis();
            }
            $db->commit();
        }
        else
        {
            $contentClassID = $this->ID;

            if ( $version === false )
            {
                $version = $this->Version;
            }
            $classAttributes = $this->fetchAttributes( );

            $db = eZDB::instance();
            $db->begin();
            foreach ( $classAttributes as $classAttribute )
            {
                $dataType = $classAttribute->dataType();
                $dataType->deleteStoredClassAttribute( $classAttribute, $version );
            }
            eZPersistentObject::removeObject( eZContentClassAttribute::definition(),
                                              array( 'contentclass_id' => $contentClassID,
                                                     'version' => $version ) );
            $db->commit();
        }
    }

    function compareAttributes( $attr1, $attr2 )
    {
        return  ( $attr1->attribute( "placement" ) > $attr2->attribute( "placement" )  ) ? 1 : -1;
    }

    function adjustAttributePlacements( $attributes )
    {
        if ( !is_array( $attributes ) )
            return;
        usort( $attributes, array( $this, "compareAttributes" ) );
        $i = 0;
        foreach( $attributes as $attribute )
        {
            $attribute->setAttribute( "placement", ++$i );
        }
    }

    function store( $store_childs = false, $fieldFilters = null )
    {

        self::expireCache();

        $db = eZDB::instance();
        $db->begin();

        if ( is_array( $store_childs ) ||
             $store_childs )
        {
            if ( is_array( $store_childs ) )
            {
                $attributes = $store_childs;
            }
            else
            {
                $attributes = $this->fetchAttributes();
            }

           foreach( $attributes as $attribute )
           {
               if ( is_object ( $attribute ) )
                   $attribute->store();
           }
        }

        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-class-cache', time() );
        $handler->store();

        $this->setAttribute( 'serialized_name_list', $this->NameList->serializeNames() );
        $this->setAttribute( 'serialized_description_list', $this->DescriptionList->serializeNames() );

        eZPersistentObject::store( $fieldFilters );

        $this->NameList->store( $this );

        $db->commit();
    }

    function sync( $fieldFilters = null )
    {
        if ( $this->hasDirtyData() )
            $this->store( false, $fieldFilters );
    }

    /*!
     Initializes this class as a copy of \a $originalClass by
     creating new a new name and identifier.
     It will check if there are other classes already with this name
     in which case it will append a unique number to the name and identifier.
    */
    function initializeCopy( &$originalClass )
    {
        $name = ezi18n( 'kernel/class', 'Copy of %class_name', null,
                        array( '%class_name' => $originalClass->attribute( 'name' ) ) );
        $identifier = 'copy_of_' . $originalClass->attribute( 'identifier' );
        $db = eZDB::instance();
        $sql = "SELECT count( ezcontentclass_name.name ) AS count FROM ezcontentclass, ezcontentclass_name WHERE ezcontentclass.id = ezcontentclass_name.contentclass_id AND ezcontentclass_name.name like '" . $db->escapeString( $name ) . "%'";
        $rows = $db->arrayQuery( $sql );
        $count = $rows[0]['count'];
        if ( $count > 0 )
        {
            ++$count;
            $name .= $count;
            $identifier .= $count;
        }
        $this->setName( $name );
        $this->setAttribute( 'identifier', $identifier );
        $this->setAttribute( 'created', time() );
        $user = eZUser::currentUser();
        $userID = $user->attribute( "contentobject_id" );
        $this->setAttribute( 'creator_id', $userID );
    }

    /*!
     Stores the current class as a defined version, updates the contentobject_name
     attribute and recreates the class group entries.
     \note It will remove any existing temporary or defined classes before storing.
    */
    function storeDefined( $attributes )
    {
        $db = eZDB::instance();
        $db->begin();

        $this->removeAttributes( false, eZContentClass::VERSION_STATUS_DEFINED );
        $this->removeAttributes( false, eZContentClass::VERSION_STATUS_TEMPORARY );
        $this->remove( false );
        $this->setVersion( eZContentClass::VERSION_STATUS_DEFINED, $attributes );
        // include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
        $user = eZUser::currentUser();
        $user_id = $user->attribute( "contentobject_id" );
        $this->setAttribute( "modifier_id", $user_id );
        $this->setAttribute( "modified", time() );
        $this->adjustAttributePlacements( $attributes );
        foreach( $attributes as $attribute )
        {
            $attribute->storeDefined();
        }

        // Set contentobject_name to something sensible if it is missing
        if ( count( $attributes ) > 0 )
        {
            $identifier = $attributes[0]->attribute( 'identifier' );
            $identifier = '<' . $identifier . '>';
            if ( trim( $this->attribute( 'contentobject_name' ) ) == '' )
            {
                $this->setAttribute( 'contentobject_name', $identifier );
            }
        }

        // Recreate class member entries
        eZContentClassClassGroup::removeClassMembers( $this->ID, eZContentClass::VERSION_STATUS_DEFINED );
        $classgroups = eZContentClassClassGroup::fetchGroupList( $this->ID, eZContentClass::VERSION_STATUS_TEMPORARY );
        foreach( $classgroups as $classgroup )
        {
            $classgroup->setAttribute( 'contentclass_version', eZContentClass::VERSION_STATUS_DEFINED );
            $classgroup->store();
        }
        eZContentClassClassGroup::removeClassMembers( $this->ID, eZContentClass::VERSION_STATUS_TEMPORARY );

        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $time = time();
        $handler->setTimestamp( 'user-class-cache', $time );
        $handler->setTimestamp( 'class-identifier-cache', $time );
        $handler->setTimestamp( 'sort-key-cache', $time );
        $handler->store();

        eZContentCacheManager::clearAllContentCache();

        $this->setAttribute( 'serialized_name_list', $this->NameList->serializeNames() );
        $this->setAttribute( 'serialized_description_list', $this->DescriptionList->serializeNames() );
        eZPersistentObject::store();
        $this->NameList->store( $this );

        $db->commit();
    }

    function setVersion( $version, $set_childs = false )
    {
        if ( is_array( $set_childs ) or $set_childs )
        {
            if ( is_array( $set_childs ) )
            {
                $attributes = $set_childs;
            }
            else
            {
                $attributes = $this->fetchAttributes();
            }
            foreach( $attributes as $attribute )
            {
                $attribute->setAttribute( "version", $version );
            }
        }

        if ( $this->Version != $version )
            $this->NameList->setHasDirtyData();

        $this->setAttribute( "version", $version );
    }

    static function exists( $id, $version = eZContentClass::VERSION_STATUS_DEFINED, $userID = false, $useIdentifier = false )
    {
        $conds = array( "version" => $version );
        if ( $useIdentifier )
            $conds["identifier"] = $id;
        else
            $conds["id"] = $id;
        if ( $userID !== false and is_numeric( $userID ) )
            $conds["creator_id"] = $userID;
        $version_sort = "desc";
        if ( $version == eZContentClass::VERSION_STATUS_DEFINED )
            $conds['version'] = $version;
        $rows = eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                      null,
                                                      $conds,
                                                      null,
                                                      array( "offset" => 0,
                                                             "length" => 1 ),
                                                      false );
        if ( count( $rows ) > 0 )
            return $rows[0]['id'];
        return false;
    }

    static function fetch( $id, $asObject = true, $version = eZContentClass::VERSION_STATUS_DEFINED, $user_id = false ,$parent_id = null )
    {
        global $eZContentClassObjectCache;

        // If the object given by its id is not cached or should be returned as array
        // then we fetch it from the DB (objects are always cached as arrays).
        if ( !isset( $eZContentClassObjectCache[$id] ) or $asObject === false or $version != eZContentClass::VERSION_STATUS_DEFINED )
        {
            $conds = array( "id" => $id,
                        "version" => $version );

            if ( $user_id !== false and is_numeric( $user_id ) )
                $conds["creator_id"] = $user_id;

            $version_sort = "desc";
            if ( $version == eZContentClass::VERSION_STATUS_DEFINED )
                $version_sort = "asc";
            $rows = eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                      null,
                                                      $conds,
                                                      array( "version" => $version_sort ),
                                                      array( "offset" => 0,
                                                             "length" => 2 ),
                                                      false );

            if ( count( $rows ) == 0 )
            {
                $contentClass = null;
                return $contentClass;
            }

            $row = $rows[0];
            $row["version_count"] = count( $rows );

            if ( $asObject )
            {
                $contentClass = new eZContentClass( $row );
                if ( $version == eZContentClass::VERSION_STATUS_DEFINED )
                {
                    $eZContentClassObjectCache[$id] = $contentClass;
                }
                return $contentClass;
            }
            else
                $contentClass = $row;
        }
        else
        {
            $contentClass = $eZContentClassObjectCache[$id];
        }

        return $contentClass;
    }

    static function fetchByRemoteID( $remoteID, $asObject = true, $version = eZContentClass::VERSION_STATUS_DEFINED, $user_id = false ,$parent_id = null )
    {
        $conds = array( "remote_id" => $remoteID,
                        "version" => $version );
        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;
        $version_sort = "desc";
        if ( $version == eZContentClass::VERSION_STATUS_DEFINED )
            $version_sort = "asc";
        $rows = eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                      null,
                                                      $conds,
                                                      array( "version" => $version_sort ),
                                                      array( "offset" => 0,
                                                             "length" => 2 ),
                                                      false );
        if ( count( $rows ) == 0 )
        {
            $contentClass = null;
            return $contentClass;
        }

        $row = $rows[0];
        $row["version_count"] = count( $rows );

        if ( $asObject )
        {
            return new eZContentClass( $row );
        }

        return $row;
    }

    static function fetchByIdentifier( $identifier, $asObject = true, $version = eZContentClass::VERSION_STATUS_DEFINED, $user_id = false ,$parent_id = null )
    {
        $conds = array( "identifier" => $identifier,
                        "version" => $version );
        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;
        $version_sort = "desc";
        if ( $version == eZContentClass::VERSION_STATUS_DEFINED )
            $version_sort = "asc";
        $rows = eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                      null,
                                                      $conds,
                                                      array( "version" => $version_sort ),
                                                      array( "offset" => 0,
                                                             "length" => 2 ),
                                                      false );
        if ( count( $rows ) == 0 )
        {
            $contentClass = null;
            return $contentClass;
        }

        $row = $rows[0];
        $row["version_count"] = count( $rows );

        if ( $asObject )
        {
            return new eZContentClass( $row );
        }
        return $row;
    }

    /*!
     \static
    */
    static function fetchList( $version = eZContentClass::VERSION_STATUS_DEFINED, $asObject = true, $user_id = false,
                         $sorts = null, $fields = null, $classFilter = false, $limit = null )
    {
        $conds = array();
        $custom_fields = null;
        $custom_tables = null;
        $custom_conds = null;

        if ( is_numeric( $version ) )
            $conds["version"] = $version;
        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;
        if ( $classFilter )
        {
            $classIDCount = 0;
            $classIdentifierCount = 0;

            $classIDFilter = array();
            $classIdentifierFilter = array();
            foreach ( $classFilter as $classType )
            {
                if ( is_numeric( $classType ) )
                {
                    $classIDFilter[] = $classType;
                    $classIDCount++;
                }
                else
                {
                    $classIdentifierFilter[] = $classType;
                    $classIdentifierCount++;
                }
            }

            if ( $classIDCount > 1 )
                $conds['id'] = array( $classIDFilter );
            else if ( $classIDCount == 1 )
                $conds['id'] = $classIDFilter[0];
            if ( $classIdentifierCount > 1 )
                $conds['identifier'] = array( $classIdentifierFilter );
            else if ( $classIdentifierCount == 1 )
                $conds['identifier'] = $classIdentifierFilter[0];
        }

        if ( $sorts && isset( $sorts['name'] ) )
        {
            $nameFiler = eZContentClassName::sqlFilter( 'ezcontentclass' );
            $custom_tables = array( $nameFiler['from'] );
            $custom_conds = "AND " . $nameFiler['where'];
            $custom_fields = array( $nameFiler['nameField'] );

            $sorts[$nameFiler['orderBy']] = $sorts['name'];
            unset( $sorts['name'] );
        }

        return eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                            $fields,
                                                            $conds,
                                                            $sorts,
                                                            $limit,
                                                            $asObject,
                                                            false,
                                                            $custom_fields,
                                                            $custom_tables,
                                                            $custom_conds );
    }

    /*!
     Returns all attributes as an associative array with the key taken from the attribute identifier.
    */
    function dataMap()
    {
        if ( !isset( $this->DataMap[$this->Version] ) )
        {
            $attributes = $this->fetchAttributes( false, true, $this->Version );
            foreach ( $attributes as $attribute )
            {
                $this->DataMap[$this->Version][$attribute->attribute( 'identifier' )] = $attribute;
            }
        }
        return $this->DataMap[$this->Version];
    }

    function fetchAttributes( $id = false, $asObject = true, $version = eZContentClass::VERSION_STATUS_DEFINED )
    {
        if ( $id === false )
        {
            $id = $this->ID;
            $version = $this->Version;
        }

        return eZContentClassAttribute::fetchFilteredList( array( "contentclass_id" => $id,
                                                                  "version" => $version ),
                                                           $asObject );
    }

    /*!
     Fetch class attribute by identifier, return null if none exist.

     \param attribute identifier.

     \return Class Attribute, null if none matched
    */
    function fetchAttributeByIdentifier( $identifier, $asObject = true )
    {
        $attributeArray = eZContentClassAttribute::fetchFilteredList( array( 'contentclass_id' => $this->ID,
                                                                             'version' => $this->Version,
                                                                             'identifier' => $identifier ), $asObject );
        if ( count( $attributeArray ) > 0 )
        {
            return $attributeArray[0];
        }
        return null;
    }

    function fetchSearchableAttributes( $id = false, $asObject = true, $version = eZContentClass::VERSION_STATUS_DEFINED )
    {
        if ( $id === false )
        {
            $id = $this->ID;
            $version = $this->Version;
        }

        return eZContentClassAttribute::fetchFilteredList( array( "contentclass_id" => $id,
                                                                  "is_searchable" => 1,
                                                                  "version" => $version ), $asObject );
    }

    function versionStatus()
    {

        if ( $this->VersionCount == 1 )
        {
            if ( $this->Version == eZContentClass::VERSION_STATUS_TEMPORARY )
            {
                return eZContentClass::VERSION_STATUS_TEMPORARY;
            }
            return eZContentClass::VERSION_STATUS_DEFINED;
        }
        return eZContentClass::VERSION_STATUS_MODIFIED;
    }

    /*!
     \deprecated
     \return The version count for the class if has been determined.
    */
    function versionCount()
    {
        return $this->VersionCount;
    }

    /*!
     Will generate a name for the content object based on the class
     settings for content object.
    */
    function contentObjectName( $contentObject, $version = false, $translation = false )
    {
        $contentObjectNamePattern = $this->ContentObjectName;

        $nameResolver = new eZNamePatternResolver( $contentObjectNamePattern, $contentObject, $version, $translation );
        $contentObjectName = $nameResolver->resolveNamePattern();

        return $contentObjectName;
    }

    /*
     Will generate a name for the url alias based on the class
     settings for content object.
    */
    function urlAliasName( $contentObject, $version = false, $translation = false )
    {
        if ( $this->URLAliasName )
        {
            $urlAliasNamePattern = $this->URLAliasName;
        }
        else
        {
            $urlAliasNamePattern = $this->ContentObjectName;
        }

        $nameResolver = new eZNamePatternResolver( $urlAliasNamePattern, $contentObject, $version, $translation );
        $urlAliasName = $nameResolver->resolveNamePattern();

        return $urlAliasName;
    }

    /*!
     Generates a name for the content object based on the content object name pattern
     and data map of an object.
    */
    function buildContentObjectName( $contentObjectName, $dataMap, $tmpTags = false )
    {
        preg_match_all( "|<[^>]+>|U",
                        $contentObjectName,
                        $tagMatchArray );

        foreach ( $tagMatchArray[0] as $tag )
        {
            $tagName = str_replace( "<", "", $tag );
            $tagName = str_replace( ">", "", $tagName );

            $tagParts = explode( '|', $tagName );

            $namePart = "";
            foreach ( $tagParts as $name )
            {
                // get the value of the attribute to use in name
                if ( isset( $dataMap[$name] ) )
                {
                    $namePart = $dataMap[$name]->title();
                    if ( $namePart != "" )
                        break;
                }
                elseif ( $tmpTags && isset( $tmpTags[$name] ) && $tmpTags[$name] != '' )
                {
                    $namePart = $tmpTags[$name];
                    break;
                }

            }

            // replace tag with object name part
            $contentObjectName = str_replace( $tag, $namePart, $contentObjectName );
        }
        return $contentObjectName;
    }

    /*!
     \return will return the number of objects published by this class.
    */
    function objectCount()
    {
        $db = eZDB::instance();

        $countRow = $db->arrayQuery( 'SELECT count(*) AS count FROM ezcontentobject '.
                                     'WHERE contentclass_id='.$this->ID ." and status = " . eZContentObject::STATUS_PUBLISHED );

        return $countRow[0]['count'];
    }

    /*!
     \return will return the list of objects published by this class.
    */
    function objectList()
    {
        return eZContentObject::fetchSameClassList( $this->ID );
    }

    /*!
     \return Sets the languages which are allowed to be instantiated for the class.
     Used only for the content/ fetch function.
    */
    function setCanInstantiateLanguages( $languageCodes )
    {
        $this->CanInstantiateLanguages = $languageCodes;
    }

    function canInstantiateLanguages()
    {
        if ( is_array( $this->CanInstantiateLanguages ) )
        {
            return array_intersect( eZContentLanguage::prioritizedLanguageCodes(), $this->CanInstantiateLanguages );
        }
        return array();
    }

    /*!
     \static
     Returns a contentclass name from serialized array \a $serializedNameList using
     top language from siteaccess language list or 'always available' name
     from \a $serializedNameList.

     \return string with contentclass name.
    */
    static function nameFromSerializedString( $serializedNameList )
    {
        return eZContentClassNameList::nameFromSerializedString( $serializedNameList );
    }

    /*!
     \static
     Returns a contentclass description from serialized array \a $serializedNameList using
     top language from siteaccess language list or 'always available' name
     from \a $serializedNameList.

     \return string with contentclass description.
    */
    static function descriptionFromSerializedString( $serializedDescriptionList )
    {
        return eZSerializedObjectNameList::nameFromSerializedString( $serializedNameList );
    }

    function hasNameInLanguage( $languageLocale )
    {
        $hasName = $this->NameList->hasNameInLocale( $languageLocale );
        return $hasName;
    }

    /*!
     Returns a contentclass name in \a $languageLocale language.
     Uses siteaccess language list or 'always available' language if \a $languageLocale is 'false'.

     \return string with contentclass name.
    */
    function name( $languageLocale = false )
    {
        return $this->NameList->name( $languageLocale );
    }

    function setName( $name, $languageLocale = false )
    {
        if ( !$languageLocale )
            $languageLocale = $this->topPriorityLanguageLocale();
        $this->NameList->setNameByLanguageLocale( $name, $languageLocale );

        $languageID = eZContentLanguage::idByLocale( $languageLocale );
        $languageMask = $this->attribute( 'language_mask' );
        $this->setAttribute( 'language_mask', $languageMask | $languageID );
    }

    function nameList()
    {
        return $this->NameList->nameList();
    }

    /*!
     Returns a contentclass description in \a $languageLocale language.
     Uses siteaccess language list or 'always available' language if \a $languageLocale is 'false'.

     \return string with contentclass name.
    */
    function description( $languageLocale = false )
    {
        return $this->DescriptionList->name( $languageLocale );
    }

    function setDescription( $description, $languageLocale = false )
    {
        $this->DescriptionList->setName( $description, $languageLocale );
    }

    function descriptionList()
    {
        return $this->DescriptionList->nameList();
    }

    function setAlwaysAvailableLanguageID( $languageID, $updateChilds = true )
    {
        $db = eZDB::instance();
        $db->begin();

        $languageLocale = false;
        if ( $languageID )
        {
            $language = eZContentLanguage::fetch( $languageID );
            $languageLocale = $language->attribute( 'locale' );
        }

        if ( $languageID )
        {
            $this->setAttribute( 'language_mask', (int)$this->attribute( 'language_mask' ) | 1 );
            $this->NameList->setAlwaysAvailableLanguage( $languageLocale );
            $this->DescriptionList->setAlwaysAvailableLanguage( $languageLocale );
        }
        else
        {
            $this->setAttribute( 'language_mask', (int)$this->attribute( 'language_mask' ) & ~1 );
            $this->NameList->setAlwaysAvailableLanguage( false );
            $this->DescriptionList->setAlwaysAvailableLanguage( false );
        }
        $this->store();

        $classID = $this->attribute( 'id' );
        $version = $this->attribute( 'version' );

        $attributes = $this->fetchAttributes();
        foreach( $attributes as $attribute )
        {
            $attribute->setAlwaysAvailableLanguage( $languageLocale );
            $attribute->store();
        }

        // reset 'always available' flag
        $sql = "UPDATE ezcontentclass_name SET language_id=";
        if ( $db->databaseName() == 'oracle' )
        {
            $sql .= "bitand( language_id, -2 )";
        }
        else
        {
            $sql .= "language_id & ~1";
        }
        $sql .= " WHERE contentclass_id = '$classID' AND contentclass_version = '$version'";
        $db->query( $sql );

        if ( $languageID != false )
        {
            $newLanguageID = $languageID | 1;

            $sql = "UPDATE ezcontentclass_name
                    SET language_id='$newLanguageID'
                WHERE language_id='$languageID' AND contentclass_id = '$classID' AND contentclass_version = '$version'";
            $db->query( $sql );
        }

        $db->commit();
    }

    function clearAlwaysAvailableLanguageID()
    {
        $this->setAlwaysAvailableLanguageID( false );
    }

    /*!
     Wrapper for eZContentClassNameList::languages.
    */
    function languages()
    {
        return $this->NameList->languages();
    }

    /*!
     Wrapper for eZContentClassNameList::prioritizedLanguages.
    */
    function prioritizedLanguages()
    {
        return $this->NameList->prioritizedLanguages();
    }

    function prioritizedLanguagesJsArray()
    {
        return $this->NameList->prioritizedLanguagesJsArray();
    }

    /*!
     Wrapper for eZContentClassNameList::untranslatedLanguages.
    */
    function canCreateLanguages()
    {
        return $this->NameList->untranslatedLanguages();
    }

    /*!
     Wrapper for eZContentClassNameList::topPriorityLanguageLocale.
    */
    function topPriorityLanguageLocale()
    {
        return $this->NameList->topPriorityLanguageLocale();
    }

    /*!
     Wrapper for eZContentClassNameList::alwaysAvailableLanguage.

     \return 'language' object.
    */
    function alwaysAvailableLanguage()
    {
        return $this->NameList->alwaysAvailableLanguage();
    }

    /*!
     Wrapper for eZContentClassNameList::alwaysAvailableLanguageLocale.

     \return 'language' object.
    */
    function alwaysAvailableLanguageLocale()
    {
        $language = $this->NameList->alwaysAvailableLanguageLocale();
        return $language;
    }

    /*!
     Removes translated name for specified by \a $languageID language.
    */
    function removeTranslation( $languageID )
    {
        $language = eZContentLanguage::fetch( $languageID );

        if ( !$language )
        {
            return false;
        }

        // check if it is not the initial language
        $classInitialLanguageID = $this->attribute( 'initial_language_id' );
        if ( $classInitialLanguageID == $languageID )
        {
            return false;
        }

        $db = eZDB::instance();
        $db->begin();

        $classID = $this->attribute( 'id' );
        $languageID = $language->attribute( 'id' );

        // change language_mask of the object
        $languageMask = (int) $this->attribute( 'language_mask' );
        $languageMask = (int) $languageMask & ~ (int) $languageID;
        $this->setAttribute( 'language_mask', $languageMask );

        // Remove all names in the language
        $db->query( "DELETE FROM ezcontentclass_name
                     WHERE contentclass_id='$classID'
                       AND language_id='$languageID'" );

        $languageLocale = $language->attribute( 'locale' );
        $this->NameList->removeName( $languageLocale );
        $this->DescriptionList->removeName( $languageLocale );

        $this->store();

        // Remove names for attributes in the language
        $attributes = $this->fetchAttributes();
        foreach ( $attributes as $attribute )
        {
            $attribute->removeTranslation( $languageLocale );
            $attribute->store();
        }

        $db->commit();

        return true;
    }

    /**
     * Resolves the string class identifier $identifier to its numeric value
     * Use {@link eZContentObjectTreeNode::classIDByIdentifier()} for < 4.1.
     * If multiple classes have the same identifier, the first found is returned.
     *
     * @static
     * @since Version 4.1
     * @param string|array $identifier identifier string or array of identifiers (array support added in 4.1.1)
     * @return int|false Returns classid or false
     */
    public static function classIDByIdentifier( $identifier )
    {
        $identifierHash = self::classIdentifiersHash();

        if ( is_array( $identifier ) )
        {
            $idList = array();
            foreach( $identifier as $identifierItem )
            {
                if ( isset( $identifierHash[$identifierItem] )  )
                    $idList[] = $identifierHash[$identifierItem];
                else if ( is_numeric( $identifierItem ) ) // to be able to pass mixed arrays
                    $idList[] = $identifierItem;
            }
            return $idList;
        }
        else if ( isset( $identifierHash[$identifier] ) )
            return $identifierHash[$identifier];
        else
            return false;
    }

    /**
     * Resolves the numeric class identifier $id to its string value
     *
     * @static
     * @since Version 4.1
     * @return string|false Returns classidentifier or false
     */
    public static function classIdentifierByID( $id )
    {
        $identifierHash = array_flip( self::classIdentifiersHash() );

        if ( isset( $identifierHash[$id] ) )
            return $identifierHash[$id];
        else
            return false;
    }

    /**
     * Returns the class identifier hash for the current database.
     * If it is outdated or non-existent, the method updates/generates the file
     *
     * @static
     * @since Version 4.1
     * @access protected
     * @return array Returns hash of classidentifier => classid
     */
    protected static function classIdentifiersHash()
    {
        if ( self::$identifierHash === null )
        {
            $db = eZDB::instance();
            $dbName = md5( $db->DB );

            $cacheDir = eZSys::cacheDirectory();
            $phpCache = new eZPHPCreator( $cacheDir,
                                          'classidentifiers_' . $dbName . '.php',
                                          '',
                                          array( 'clustering' => 'classidentifiers' ) );

            eZExpiryHandler::registerShutdownFunction();
            $handler = eZExpiryHandler::instance();
            $expiryTime = 0;
            if ( $handler->hasTimestamp( 'class-identifier-cache' ) )
            {
                $expiryTime = $handler->timestamp( 'class-identifier-cache' );
            }

            if ( $phpCache->canRestore( $expiryTime ) )
            {
                $var = $phpCache->restore( array( 'identifierHash' => 'identifier_hash' ) );
                self::$identifierHash = $var['identifierHash'];
            }
            else
            {
                // Fetch identifier/id pair from db
                $query = "SELECT id, identifier FROM ezcontentclass where version=0";
                $identifierArray = $db->arrayQuery( $query );

                self::$identifierHash = array();
                foreach ( $identifierArray as $identifierRow )
                {
                    self::$identifierHash[$identifierRow['identifier']] = $identifierRow['id'];
                }

                // Store identifier list to cache file
                $phpCache->addVariable( 'identifier_hash', self::$identifierHash );
                $phpCache->store();
            }
        }
        return self::$identifierHash;
    }

    /*!
     Returns an array of IDs of classes containing a specified datatype

     \param $dataTypeString a datatype identification string
    */
    static function fetchIDListContainingDatatype( $dataTypeString )
    {
        $db = eZDB::instance();

        $version = self::VERSION_STATUS_DEFINED;
        $escapedDataTypeString = $db->escapeString( $dataTypeString );

        $sql = "SELECT DISTINCT contentclass_id
                FROM ezcontentclass_attribute
                WHERE version=$version
                AND data_type_string='$escapedDataTypeString'";

        $classIDArray = $db->arrayQuery( $sql, array( 'column' => 'contentclass_id' ) );
        return $classIDArray;
    }

    public static function expireCache()
    {
        unset( $GLOBALS['eZContentClassObjectCache'] );
        self::$identifierHash = null;
        eZContentClassAttribute::expireCache();
    }

    /**
     * Computes the version history limit for a content class
     *
     * @param mixed $class
     *        Content class ID, content class identifier or content class object
     * @return int
     * @since 4.2
     */
    public static function versionHistoryLimit( $class )
    {
        // default version limit
        $contentINI = eZINI::instance( 'content.ini' );
        $versionLimit = $contentINI->variable( 'VersionManagement', 'DefaultVersionHistoryLimit' );

        // version limit can't be < 2
        if ( $versionLimit < 2 )
        {
            eZDebug::writeWarning( "Global version history limit must be equal to or higher than 2", __METHOD__ );
            $versionLimit = 2;
        }

        // we need to take $class down to a class ID
        if ( is_numeric( $class ) )
        {
            if (!eZContentClass::classIdentifierByID( $class ) )
            {
                eZDebug::writeWarning( "class integer parameter doesn't match any content class ID", __METHOD__ );
                return $versionLimit;
            }
            $classID = (int)$class;
        }
        // literal identifier
        elseif ( is_string( $class ) )
        {
            $classID = eZContentClass::classIDByIdentifier( $class );
            if ( !$classID )
            {
                eZDebug::writeWarning( "class string parameter doesn't match any content class identifier", __METHOD__ );
                return $versionLimit;
            }
        }
        // eZContentClass object
        elseif ( is_object( $class ) )
        {
            if ( !$class instanceof eZContentClass )
            {
                eZDebug::writeWarning( "class object parameter is not an eZContentClass", __METHOD__ );
                return  $versionLimit;
            }
            else
            {
                $classID = $class->attribute( 'id' );
            }
        }

        $classLimitSetting = $contentINI->variable( 'VersionManagement', 'VersionHistoryClass' );
        $classArray = array_keys( $classLimitSetting );
        $limitsArray = array_values( $classLimitSetting );

        $classArray = eZContentClass::classIDByIdentifier( $classArray );

        foreach ( $classArray as $index => $id )
        {
            if ( $id == $classID )
            {
                $limit = $limitsArray[$index];
                // version limit can't be < 2
                if ( $limit < 2 )
                {
                    $classIdentifier = eZContentClass::classIdentifierByID( $classID );
                    eZDebug::writeWarning( "Version history limit for class {$classIdentifier} must be equal to or higher than 2", __METHOD__ );
                    $limit = 2;
                }
                $versionLimit = $limit;
            }
        }

        return $versionLimit;
    }

    /// \privatesection
    public $ID;
    // serialized array of translated class names
    public $SerializedNameList;
    // unserialized class names
    public $NameList;
    // unserialized class description
    public $DescriptionList;
    public $Identifier;
    public $ContentObjectName;
    public $Version;
    public $VersionCount;
    public $CreatorID;
    public $ModifierID;
    public $Created;
    public $Modified;
    public $InGroups;
    public $AllGroups;
    public $IsContainer;
    public $CanInstantiateLanguages;
    public $LanguageMask;

    /**
     * In-memory cache for class identifiers / id matching
     **/
    private static $identifierHash = null;
}

?>