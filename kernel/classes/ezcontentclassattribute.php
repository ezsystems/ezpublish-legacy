<?php
//
// Definition of eZContentClassAttribute class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZContentClassAttribute ezcontentclassattribute.php
  \ingroup eZKernel
  \brief Encapsulates data for a class attribute

*/

class eZContentClassAttribute extends eZPersistentObject
{
    function eZContentClassAttribute( $row )
    {
        $this->eZPersistentObject( $row );

        $this->Content = null;
        $this->DisplayInfo = null;
        $this->Module = null;

        $this->NameList = new eZContentClassNameList();
        if ( isset( $row['serialized_name_list'] ) )
            $this->NameList = new eZContentClassAttributeNameList( $row['serialized_name_list'] );
        else
            $this->NameList->initDefault();
    }

    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'serialized_name_list' => array( 'name' => 'SerializedNameList',
                                                                          'datatype' => 'string',
                                                                          'default' => '',
                                                                          'required' => true ),
                                         'version' => array( 'name' => 'Version',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'contentclass_id' => array( 'name' => 'ContentClassID',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true,
                                                                     'foreign_class' => 'eZContentClass',
                                                                     'foreign_attribute' => 'id',
                                                                     'multiplicity' => '1..*' ),
                                         'identifier' => array( 'name' => 'Identifier',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         'placement' => array( 'name' => 'Position',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'is_searchable' => array( 'name' => 'IsSearchable',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0
                                                                   ),
                                         'is_required' => array( 'name' => 'IsRequired',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'can_translate' => array( 'name' => 'CanTranslate',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0
                                                                   ),
                                         'is_information_collector' => array( 'name' => 'IsInformationCollector',
                                                                              'datatype' => 'integer',
                                                                              'default' => 0,
                                                                              'required' => true ),
                                         'data_type_string' => array( 'name' => 'DataTypeString',
                                                                      'datatype' => 'string',
                                                                      'default' => '',
                                                                      'required' => true ),
                                         'data_int1' => array( 'name' => 'DataInt1',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'data_int2' => array( 'name' => 'DataInt2',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'data_int3' => array( 'name' => 'DataInt3',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'data_int4' => array( 'name' => 'DataInt4',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'data_float1' => array( 'name' => 'DataFloat1',
                                                                 'datatype' => 'float',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'data_float2' => array( 'name' => 'DataFloat2',
                                                                 'datatype' => 'float',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'data_float3' => array( 'name' => 'DataFloat3',
                                                                 'datatype' => 'float',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'data_float4' => array( 'name' => 'DataFloat4',
                                                                 'datatype' => 'float',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'data_text1' => array( 'name' => 'DataText1',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'data_text2' => array( 'name' => 'DataText2',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'data_text3' => array( 'name' => 'DataText3',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'data_text4' => array( 'name' => 'DataText4',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'data_text5' => array( 'name' => 'DataText5',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ) ),
                      'keys' => array( 'id', 'version' ),
                      "function_attributes" => array( "content" => "content",
                                                      'temporary_object_attribute' => 'instantiateTemporary',
                                                      'data_type' => 'dataType',
                                                      'display_info' => 'displayInfo',
                                                      'name' => 'name',
                                                      'nameList' => 'nameList' ),
                      'set_functions' => array( 'name' => 'setName' ),
                      'increment_key' => 'id',
                      'sort' => array( 'placement' => 'asc' ),
                      'class_name' => 'eZContentClassAttribute',
                      'name' => 'ezcontentclass_attribute' );
    }

    function __clone()
    {
        $this->ID = null;
    }

    /*!
     Creates an 'eZContentClassAttribute' object.

     To specify contentclassattribute name use either $optionalValues['serialized_name_list'] or
     combination of $optionalValues['name'] and/or $languageLocale.

     In case of conflict(when both 'serialized_name_list' and 'name' with/without $languageLocale
     are specified) 'serialized_name_list' has top priority. This means that 'name' and
     $languageLocale will be ingnored because 'serialized_name_list' already has all needed info
     about names and languages.

     If 'name' is specified then the contentclassattribute will have a name in $languageLocale(if specified) or
     in default language.

     If neither of 'serialized_name_list' or 'name' isn't specified then the contentclassattribute will have an empty
     name in 'languageLocale'(if specified) or in default language.

     \return 'eZContentClassAttribute' object.
    */
    static function create( $class_id, $data_type_string, $optionalValues = array(), $languageLocale = false )
    {
        $nameList = new eZContentClassAttributeNameList();
        if ( isset( $optionalValues['serialized_name_list'] ) )
            $nameList->initFromSerializedList( $optionalValues['serialized_name_list'] );
        else if ( isset( $optionalValues['name'] ) )
            $nameList->initFromString( $optionalValues['name'], $languageLocale );
        else
            $nameList->initFromString( '', $languageLocale );

        $row = array(
            'id' => null,
            'version' => eZContentClass::VERSION_STATUS_TEMPORARY,
            'contentclass_id' => $class_id,
            'identifier' => '',
            'serialized_name_list' => $nameList->serializeNames(),
            'is_searchable' => 1,
            'is_required' => 0,
            'can_translate' => 1,
            'is_information_collector' => 0,
            'data_type_string' => $data_type_string,
            'placement' => eZPersistentObject::newObjectOrder( eZContentClassAttribute::definition(),
                                                               'placement',
                                                               array( 'version' => 1,
                                                                      'contentclass_id' => $class_id ) ) );
        $row = array_merge( $row, $optionalValues );
        $attribute = new eZContentClassAttribute( $row );

        return $attribute;
    }

    function instantiate( $contentobjectID, $languageCode = false, $version = 1 )
    {
        $attribute = eZContentObjectAttribute::create( $this->attribute( 'id' ), $contentobjectID, $version, $languageCode );
        $attribute->initialize();
        $attribute->store();
        $attribute->postInitialize();
    }

    /*!
    */
    function instantiateTemporary( $contentobjectID = false )
    {
        return eZContentObjectAttribute::create( $this->attribute( 'id' ), $contentobjectID );
    }

    function store( $fieldFilters = null )
    {
        $dataType = $this->dataType();
        if ( !$dataType )
        {
            return false;
        }

        global $eZContentClassAttributeCacheListFull;
        unset( $eZContentClassAttributeCacheListFull );
        global $eZContentClassAttributeCacheList;
        unset( $eZContentClassAttributeCacheList[$this->attribute( 'contentclass_id' )] );
        global $eZContentClassAttributeCache;
        unset( $eZContentClassAttributeCache[$this->ID] );

        $dataType->preStoreClassAttribute( $this, $this->attribute( 'version' ) );

        $this->setAttribute( 'serialized_name_list', $this->NameList->serializeNames() );

        $stored = eZPersistentObject::store( $fieldFilters );

        // store the content data for this attribute
        $info = $dataType->attribute( "information" );
        $dataType->storeClassAttribute( $this, $this->attribute( 'version' ) );

        return $stored;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function storeDefined()
    {
        $dataType = $this->dataType();
        if ( !$dataType )
        {
            return false;
        }

        global $eZContentClassAttributeCacheListFull;
        unset( $eZContentClassAttributeCacheListFull );
        global $eZContentClassAttributeCacheList;
        unset( $eZContentClassAttributeCacheList[$this->attribute( 'contentclass_id' )] );
        global $eZContentClassAttributeCache;
        unset( $eZContentClassAttributeCache[$this->ID] );

        $db = eZDB::instance();
        $db->begin();
        $dataType->preStoreDefinedClassAttribute( $this );

        $this->setAttribute( 'serialized_name_list', $this->NameList->serializeNames() );

        $stored = eZPersistentObject::store();

        // store the content data for this attribute
        $info = $dataType->attribute( "information" );
        $dataType->storeDefinedClassAttribute( $this );
        $db->commit();

        return $stored;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeThis( $quiet = false )
    {
        $dataType = $this->dataType();
        if ( $dataType->isClassAttributeRemovable( $this ) )
        {
            global $eZContentClassAttributeCacheListFull;
            unset( $eZContentClassAttributeCacheListFull );
            global $eZContentClassAttributeCacheList;
            unset( $eZContentClassAttributeCacheList[$this->attribute( 'contentclass_id' )] );
            global $eZContentClassAttributeCache;
            unset( $eZContentClassAttributeCache[$this->ID] );

            $db = eZDB::instance();
            $db->begin();
            $dataType->deleteStoredClassAttribute( $this, $this->Version );
            eZPersistentObject::remove();
            $db->commit();
            return true;
        }
        else
        {
            if ( !$quiet )
            {
                eZDebug::writeError( 'Datatype [' . $dataType->attribute( 'name' ) . '] can not be deleted to avoid system crash' );
            }
            return false;
        }
    }

    static function fetch( $id, $asObject = true, $version = eZContentClass::VERSION_STATUS_DEFINED, $field_filters = null )
    {
        $object = null;
        if ( $field_filters === null and $asObject and
             isset( $GLOBALS['eZContentClassAttributeCache'][$id][$version] ) )
        {
            $object = $GLOBALS['eZContentClassAttributeCache'][$id][$version];
        }
        if ( $object === null )
        {
            $object = eZPersistentObject::fetchObject( eZContentClassAttribute::definition(),
                                                       $field_filters,
                                                       array( 'id' => $id,
                                                              'version' => $version ),
                                                       $asObject );
            if ( $field_filters === null and $asObject )
            {
                $GLOBALS['eZContentClassAttributeCache'][$id][$version] = $object;
            }
        }
        return $object;
    }

    static function fetchList( $asObject = true, $parameters = array() )
    {
        $parameters = array_merge( array( 'data_type' => false,
                                          'version' => false ),
                                   $parameters );
        $dataType = $parameters['data_type'];
        $version = $parameters['version'];
        $objects = null;
        if ( $asObject &&
             $dataType === false &&
             $version === false )
        {
            $objects = $GLOBALS['eZContentClassAttributeCacheListFull'];
        }
        if ( !isset( $objects ) or
             $objects === null )
        {
            $conditions = null;
            if ( $dataType !== false or
                 $version !== false )
            {
                $conditions = array();
                if ( $dataType !== false )
                    $conditions['data_type_string'] = $dataType;
                if ( $version !== false )
                    $conditions['version'] = $version;
            }
            $objects = eZPersistentObject::fetchObjectList( eZContentClassAttribute::definition(),
                                                                null, $conditions, null, null,
                                                                $asObject );
            if ( $asObject )
            {
                foreach ( $objects as $objectItem )
                {
                    $objectID = $objectItem->ID;
                    $objectVersion = $objectItem->Version;
                    $GLOBALS['eZContentClassAttributeCache'][$objectID][$objectVersion] = $objectItem;
                }
                if (  $dataType === false && $version === false )
                {
                    $GLOBALS['eZContentClassAttributeCacheListFull'] = $objects;
                }
            }
        }
        return $objects;
    }

    static function fetchListByClassID( $classID, $version = eZContentClass::VERSION_STATUS_DEFINED, $asObject = true )
    {
        $objects = null;
        if ( $asObject )
        {
            if ( isset( $GLOBALS['eZContentClassAttributeCacheList'][$classID][$version] ) )
                $objects = $GLOBALS['eZContentClassAttributeCacheList'][$classID][$version];
        }
        if ( !isset( $objects ) or
             $objects === null )
        {
            $cond = array( 'contentclass_id' => $classID,
                           'version' => $version );
            $objects = eZPersistentObject::fetchObjectList( eZContentClassAttribute::definition(),
                                                                null, $cond, null, null,
                                                                $asObject );
            if ( $asObject )
            {
                foreach ( $objects as $objectItem )
                {
                    $objectID = $objectItem->ID;
                    $objectVersion = $objectItem->Version;
                    if ( !isset( $GLOBALS['eZContentClassAttributeCache'][$objectID][$objectVersion] ) )
                        $GLOBALS['eZContentClassAttributeCache'][$objectID][$objectVersion] = $objectItem;
                }
                $GLOBALS['eZContentClassAttributeCacheList'][$classID][$version] = $objects;
            }
        }
        return $objects;
    }

    static function fetchFilteredList( $cond, $asObject = true )
    {
        $objectList = eZPersistentObject::fetchObjectList( eZContentClassAttribute::definition(),
                                                           null, $cond, null, null,
                                                           $asObject );
        if ( $asObject )
        {
            foreach ( $objectList as $objectItem )
            {
                $objectID = $objectItem->ID;
                $objectVersion = $objectItem->Version;
                if ( !isset( $GLOBALS['eZContentClassAttributeCache'][$objectID][$objectVersion] ) )
                    $GLOBALS['eZContentClassAttributeCache'][$objectID][$objectVersion] = $objectItem;
            }
        }
        return $objectList;
    }

    /*!
     Moves the object down if $down is true, otherwise up.
     If object is at either top or bottom it is wrapped around.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function move( $down, $params = null )
    {
        if ( is_array( $params ) )
        {
            $pos = $params['placement'];
            $cid = $params['contentclass_id'];
            $version = $params['version'];
        }
        else
        {
            $pos = $this->Position;
            $cid = $this->ContentClassID;
            $version = $this->Version;
        }
        eZPersistentObject::reorderObject( eZContentClassAttribute::definition(),
                                           array( 'placement' => $pos ),
                                           array( 'contentclass_id' => $cid,
                                                  'version' => $version ),
                                           $down );
    }

    function dataType()
    {
        return eZDataType::create( $this->DataTypeString );
    }

    /*!
     \return The content for this attribute.
    */
    function content()
    {
        if ( $this->Content === null )
        {
            $dataType = $this->dataType();
            $this->Content = $dataType->classAttributeContent( $this );
        }

        return $this->Content;
    }

    /*!
     Sets the content for the current attribute
    */
    function setContent( $content )
    {
        $this->Content = $content;
    }

    /*!
     \return Information on how to display the class attribute.
             See eZDataType::classDisplayInformation() for more information on what is returned.
    */
    function displayInfo()
    {
        if ( !$this->DisplayInfo )
        {
            $dataType = $this->dataType();
            if ( is_object( $dataType ) )
            {
                $this->DisplayInfo = $dataType->classDisplayInformation( $this, false );
            }
        }
        return $this->DisplayInfo;
    }

    /*!
     Executes the custom HTTP action
    */
    function customHTTPAction( $module, $http, $action )
    {
        $dataType = $this->dataType();
        $this->Module = $module;
        $dataType->customClassAttributeHTTPAction( $http, $action, $this );
        unset( $this->Module );
        $this->Module = null;
    }

    /*!
     \return the module which uses this attribute or \c null if no module set.
     \note Currently only customHTTPAction sets this.
    */
    function currentModule()
    {
        return $this->Module;
    }

    static function cachedInfo()
    {
        eZExpiryHandler::registerShutdownFunction();

        $info = array();
        $db = eZDB::instance();
        $dbName = md5( $db->DB );

        $cacheDir = eZSys::cacheDirectory();
        $phpCache = new eZPHPCreator( "$cacheDir", "sortkey_$dbName.php", '', array( 'clustering' => 'sortkey' ) );
        $handler = eZExpiryHandler::instance();
        $expiryTime = 0;

        if ( $handler->hasTimestamp( 'sort-key-cache' ) )
        {
            $expiryTime = $handler->timestamp( 'sort-key-cache' );
        }

        if ( $phpCache->canRestore( $expiryTime ) )
        {
            $info = $phpCache->restore( array( 'sortkey_type_array' => 'sortKeyTypeArray',
                                               'attribute_type_array' => 'attributeTypeArray' ) );
        }
        else
        {
            // Fetch all datatypes and id's used
            $query = "SELECT id, data_type_string FROM ezcontentclass_attribute";
            $attributeArray = $db->arrayQuery( $query );

            $attributeTypeArray = array();
            $sortKeyTypeArray = array();
            foreach ( $attributeArray as $attribute )
            {
                $attributeTypeArray[$attribute['id']] = $attribute['data_type_string'];
                $sortKeyTypeArray[$attribute['data_type_string']] = 0;
            }

            // Fetch datatype for every unique datatype
            foreach ( array_keys( $sortKeyTypeArray ) as $key )
            {
                unset( $dataType );
                $dataType = eZDataType::create( $key );
                if( is_object( $dataType ) )
                    $sortKeyTypeArray[$key] = $dataType->sortKeyType();
            }
            unset( $dataType );

            // Store identifier list to cache file
            $phpCache->addVariable( 'sortKeyTypeArray', $sortKeyTypeArray );
            $phpCache->addVariable( 'attributeTypeArray', $attributeTypeArray );
            $phpCache->store();

            $info['sortkey_type_array'] = $sortKeyTypeArray;
            $info['attribute_type_array'] = $attributeTypeArray;
        }

        return $info;
    }

    /*!
     \static
    */
    static function sortKeyTypeByID( $classAttributeID )
    {
        $sortKeyType = false;

        $info = eZContentClassAttribute::cachedInfo();
        if ( isset( $info['attribute_type_array'][$classAttributeID] ) )
        {
            $classAttributeType = $info['attribute_type_array'][$classAttributeID];
            $sortKeyType = $info['sortkey_type_array'][$classAttributeType];
        }

        return $sortKeyType;
    }

    /*!
     \static
    */
    static function dataTypeByID( $classAttributeID )
    {
        $dataTypeString = false;
        $info = eZContentClassAttribute::cachedInfo();

        if ( isset( $info['attribute_type_array'][$classAttributeID] ) )
            $dataTypeString = $info['attribute_type_array'][$classAttributeID];

        return $dataTypeString;
    }

    /*!
      This methods relay calls to the diff method inside the datatype.
    */
    function diff( $old, $new )
    {
        $datatype = $this->dataType();
        $result = $datatype->diff( $old, $new );
        return $result;
    }

    /*!
     \static
    */
    static function nameFromSerializedString( $serializedNameList, $languageLocale = false )
    {
        return eZContentClassAttributeNameList::nameFromSerializedString( $serializedNameList, $languageLocale );
    }

    function name( $languageLocale = false )
    {
        return $this->NameList->name( $languageLocale );
    }

    function setName( $name, $languageLocale = false )
    {
        $this->NameList->setName( $name, $languageLocale );
    }

    function nameList()
    {
        return $this->NameList->nameList();
    }

    function setAlwaysAvailableLanguage( $languageLocale )
    {
        if ( $languageLocale )
        {
            $this->NameList->setAlwaysAvailableLanguage( $languageLocale );
        }
        else
        {
            $this->NameList->setAlwaysAvailableLanguage( false );
        }
    }

    function removeTranslation( $languageLocale )
    {
        $this->NameList->removeName( $languageLocale );
    }

    /**
     * Resolves the string class attribute identifier $identifier to its numeric value
     * Use {@link eZContentObjectTreeNode::classAttributeIDByIdentifier()} for < 4.1.
     * If multiple classes have the same identifier, the first found is returned.
     * 
     * @static
     * @since Version 4.1
     * @return int|false Returns classattributeid or false
     */
    public static function classAttributeIDByIdentifier( $identifier )
    {
        $identifierHash = self::classAttributeIdentifiersHash();

        if ( isset( $identifierHash[$identifier] ) )
            return $identifierHash[$identifier];
        else
            return false;
    }

    /**
     * Resolves the numeric class attribute identifier $id to its string value
     * 
     * @static
     * @since Version 4.1
     * @return string|false Returns classattributeidentifier or false
     */
    public static function classAttributeIdentifierByID( $id )
    {
        $identifierHash = array_flip( self::classAttributeIdentifiersHash() );

        if ( isset( $identifierHash[$id] ) )
        {
            $classAndClassAttributeIdentifier = explode( '/', $identifierHash[$id] );
            return $classAndClassAttributeIdentifier[1];
        }
        else
            return false;
    }

    /**
     * Returns the class attribute identifier hash for the current database.
     * If it is outdated or non-existent, the method updates/generates the file
     * 
     * @static
     * @since Version 4.1
     * @access protected
     * @return array Returns hash of classattributeidentifier => classattributeid
     */
    protected static function classAttributeIdentifiersHash()
    {
        static $identifierHash = null;

        if ( $identifierHash === null )
        {
            $db = eZDB::instance();
            $dbName = md5( $db->DB );

            $cacheDir = eZSys::cacheDirectory();
            $phpCache = new eZPHPCreator( $cacheDir,
                                          'classattributeidentifiers_' . $dbName . '.php',
                                          '',
                                          array( 'clustering' => 'classattributeidentifiers' ) );

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
                $identifierHash = $var['identifierHash'];
            }
            else
            {
                // Fetch identifier/id pair from db
                $query = "SELECT ezcontentclass_attribute.id as attribute_id, ezcontentclass_attribute.identifier as attribute_identifier, ezcontentclass.identifier as class_identifier
                          FROM ezcontentclass_attribute, ezcontentclass
                          WHERE ezcontentclass.id=ezcontentclass_attribute.contentclass_id";
                $identifierArray = $db->arrayQuery( $query );

                $identifierHash = array();
                foreach ( $identifierArray as $identifierRow )
                {
                    $combinedIdentifier = $identifierRow['class_identifier'] . '/' . $identifierRow['attribute_identifier'];
                    $identifierHash[$combinedIdentifier] = (int) $identifierRow['attribute_id'];
                }

                // Store identifier list to cache file
                $phpCache->addVariable( 'identifier_hash', $identifierHash );
                $phpCache->store();
            }
        }
        return $identifierHash;
    }

    function initializeObjectAttributes( &$objects = null )
    {
        $classAttributeID = $this->ID;
        $classID = $this->ContentClassID;
        $dataType = $this->attribute( 'data_type' );
        if ( $dataType->supportsBatchInitializeObjectAttribute() )
        {
            $db = eZDB::instance();

            $data = array( 'contentobject_id'         => 'a.contentobject_id',
                           'version'                  => 'a.version',
                           'contentclassattribute_id' => $classAttributeID,
                           'data_type_string'         => "'" . $db->escapeString( $this->DataTypeString ) . "'",
                           'language_code'            => 'a.language_code',
                           'language_id'              => 'MAX(a.language_id)' );

            $datatypeData = $dataType->batchInitializeObjectAttributeData( $this );
            $data = array_merge( $data, $datatypeData );

            $cols = implode( ', ', array_keys( $data ) );
            $values = implode( ', ', $data );

            $sql = "INSERT INTO ezcontentobject_attribute( $cols )
            SELECT $values
            FROM ezcontentobject_attribute a, ezcontentobject o
            WHERE o.id = a.contentobject_id AND
                  o.contentclass_id=$classID
            GROUP BY contentobject_id,
                     version,
                     language_code";

            $db->query( $sql );
        }
        else
        {
            if ( !is_array( $objects ) )
            {
                $objects = eZContentObject::fetchSameClassList( $classID );
            }

            foreach ( $objects as $object )
            {
                $contentobjectID = $object->attribute( 'id' );
                $objectVersions = $object->versions();
                foreach ( $objectVersions as $objectVersion )
                {
                    $translations = $objectVersion->translations( false );
                    $version = $objectVersion->attribute( 'version' );
                    foreach ( $translations as $translation )
                    {
                        $objectAttribute = eZContentObjectAttribute::create( $classAttributeID, $contentobjectID, $version, $translation );
                        $objectAttribute->setAttribute( 'language_code', $translation );
                        $objectAttribute->initialize();
                        $objectAttribute->store();
                        $objectAttribute->postInitialize();
                    }
                }
            }
        }
    }

    /// \privatesection
    /// Contains the content for this attribute
    public $Content;
    /// Contains information on how to display the current attribute in various viewmodes
    public $DisplayInfo;
    public $ID;
    public $Version;
    public $ContentClassID;
    public $Identifier;
    // serialized array of translated names
    public $SerializedNameList;
    // unserialized attribute names
    public $NameList;
    public $DataTypeString;
    public $Position;
    public $IsSearchable;
    public $IsRequired;
    public $IsInformationCollector;
    public $Module;
}

?>
