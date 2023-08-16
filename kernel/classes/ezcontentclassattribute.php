<?php
/**
 * File containing the eZContentClassAttribute class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZContentClassAttribute ezcontentclassattribute.php
  \ingroup eZKernel
  \brief Encapsulates data for a class attribute

*/

class eZContentClassAttribute extends eZPersistentObject
{
    public $DataTextI18nList;
    public function __construct( $row )
    {
        parent::__construct($row);

        $this->Content = null;
        $this->DisplayInfo = null;
        $this->Module = null;

        $this->NameList = new eZSerializedObjectNameList();
        if ( isset( $row['serialized_name_list'] ) )
            $this->NameList->initFromSerializedList( $row['serialized_name_list'] );
        else
            $this->NameList->initDefault();

        $this->DescriptionList = new eZSerializedObjectNameList();
        if ( isset( $row['serialized_description_list'] ) )
            $this->DescriptionList->initFromSerializedList( $row['serialized_description_list'] );
        else
            $this->DescriptionList->initDefault();

        $this->DataTextI18nList = new eZSerializedObjectNameList();
        if ( isset( $row['serialized_data_text'] ) )
            $this->DataTextI18nList->initFromSerializedList( $row['serialized_data_text'] );
        else
            $this->DataTextI18nList->initDefault();

        // Make sure datatype gets final say if attribute should be translatable
        if ( isset( $row['can_translate'] ) && $row['can_translate'] )
        {
            $datatype = $this->dataType();
            if ( $datatype instanceof eZDataType )
            {
                if ( !$datatype->isTranslatable() )
                    $this->setAttribute('can_translate', 0);
            }
            else
            {
                eZDebug::writeError( 'Could not get instance of datatype: ' . $row['data_type_string'], __METHOD__ );
            }
        }
    }

    static function definition()
    {
        static $definition = array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'serialized_name_list' => array( 'name' => 'SerializedNameList',
                                                                          'datatype' => 'string',
                                                                          'default' => '',
                                                                          'required' => true ),
                                         'serialized_description_list' => array( 'name' => 'SerializedDescriptionList',
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
                                                                'required' => true,
                                                                'max_length' => 50 ),
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
                                                                'required' => true ),
                                         'serialized_data_text' => array( 'name' => 'SerializedDataText',
                                                                          'datatype' => 'string',
                                                                          'default' => '',
                                                                          'required' => true ),
                                         'category' => array( 'name' => 'Category',
                                                               'datatype' => 'text',
                                                               'default' => '',
                                                               'required' => true )),
                      'keys' => array( 'id', 'version' ),
                      "function_attributes" => array( "content" => "content",
                                                      'temporary_object_attribute' => 'instantiateTemporary',
                                                      'data_type' => 'dataType',
                                                      'display_info' => 'displayInfo',
                                                      'name' => 'name',
                                                      'nameList' => 'nameList',
                                                      'description' => 'description',
                                                      'descriptionList' => 'descriptionList',
                                                      'data_text_i18n' => 'dataTextI18n',
                                                      'data_text_i18n_list' => 'dataTextI18nList' ),
                      'set_functions' => array( 'name' => 'setName',
                                                'description' => 'setDescription',
                                                'data_text_i18n' => 'setDataTextI18n' ),
                      'increment_key' => 'id',
                      'sort' => array( 'placement' => 'asc' ),
                      'class_name' => 'eZContentClassAttribute',
                      'name' => 'ezcontentclass_attribute' );
        return $definition;
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
        $nameList = new eZSerializedObjectNameList();
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

        if ( isset( $optionalValues['data_text_i18n'] ) )
        {
            $dataTextI18nList = new eZSerializedObjectNameList( $optionalValues['data_text_i18n'] );
            $optionalValues['serialized_data_text'] = $dataTextI18nList->serializeNames();
        }

        $row = array(
            'id' => null,
            'version' => eZContentClass::VERSION_STATUS_TEMPORARY,
            'contentclass_id' => $class_id,
            'identifier' => '',
            'serialized_name_list' => $nameList->serializeNames(),
            'serialized_description_list' => $descriptionList->serializeNames(),
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

        self::expireCache( $this->ID, $this->attribute( 'contentclass_id' ) );

        $dataType->preStoreClassAttribute( $this, $this->attribute( 'version' ) );

        $this->setAttribute( 'serialized_name_list', $this->NameList->serializeNames() );
        $this->setAttribute( 'serialized_description_list', $this->DescriptionList->serializeNames() );
        $this->setAttribute( 'serialized_data_text', $this->DataTextI18nList->serializeNames() );

        $stored = parent::store( $fieldFilters );

        // store the content data for this attribute
        $dataType->storeClassAttribute( $this, $this->attribute( 'version' ) );

        return $stored;
    }

    /**
     * Store the content class in the version status "defined".
     *
     * @note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     *       the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @return null|false false if the operation failed
     */
    function storeDefined()
    {
        return $this->storeVersioned( eZContentClass::VERSION_STATUS_DEFINED );
    }

    /**
     * Store the content class in the specified version status.
     *
     * @note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     *       the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param int $version version status
     * @since Version 4.3
     * @return null|false false if the operation failed
     */
    function storeVersioned( $version )
    {
        $dataType = $this->dataType();
        if ( !$dataType )
        {
            return false;
        }

        self::expireCache( $this->ID, $this->attribute( 'contentclass_id' ) );

        $db = eZDB::instance();
        $db->begin();
        $dataType->preStoreVersionedClassAttribute( $this, $version );

        $this->setAttribute( 'serialized_name_list', $this->NameList->serializeNames() );
        $this->setAttribute( 'serialized_description_list', $this->DescriptionList->serializeNames() );
        $this->setAttribute( 'serialized_data_text', $this->DataTextI18nList->serializeNames() );

        parent::store();

        // store the content data for this attribute
        $dataType->storeVersionedClassAttribute( $this, $version );
        $db->commit();
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
            self::expireCache( $this->ID, $this->attribute( 'contentclass_id' ) );

            $db = eZDB::instance();
            $db->begin();
            $dataType->deleteStoredClassAttribute( $this, $this->Version );
            $this->remove();
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

    /**
     * @return eZDataType
     */
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

    /**
     * Returns name from serialized string, can be used for serialized description and data_text as well.
     *
     * @param string $serializedNameList
     * @param string|false $languageLocale Uses AlwaysAvailable language if false
     * @return string
     */
    static function nameFromSerializedString( $serializedNameList, $languageLocale = false )
    {
        return eZSerializedObjectNameList::nameFromSerializedString( $serializedNameList, $languageLocale );
    }

    /**
     * Returns name of attribute based on serialized_name_list
     *
     * @param string|false $languageLocale Uses AlwaysAvailable language if false
     * @return string
     */
    function name( $languageLocale = false )
    {
        return $this->NameList->name( $languageLocale );
    }

    /**
     * Sets name of attribute, store() will take care of writing back to serialized_name_list
     *
     * @param string $name
     * @param string|false $languageLocale Uses AlwaysAvailable language if false
     * @return string Return old value
     */
    function setName( $name, $languageLocale = false )
    {
        return $this->NameList->setName( $name, $languageLocale );
    }

    /**
     * Returns name list for all locales for attribute
     *
     * @return array
     */
    function nameList()
    {
        return $this->NameList->nameList();
    }

    /**
     * Returns description of attribute based on serialized_description_list
     *
     * @param string|false $languageLocale Uses AlwaysAvailable language if false
     * @return string
     */
    function description( $languageLocale = false )
    {
        return $this->DescriptionList->name( $languageLocale );
    }

    /**
     * Sets description of attribute, store() will take care of writing back to serialized_description_list
     *
     * @param string $description
     * @param string|false $languageLocale Uses AlwaysAvailable language if false
     * @return string Return old value
     */
    function setDescription( $description, $languageLocale = false )
    {
        return $this->DescriptionList->setName( $description, $languageLocale );
    }

    /**
     * Returns description list for all locales for attribute
     *
     * @return array
     */
    function descriptionList()
    {
        return $this->DescriptionList->nameList();
    }

    /**
     * Returns data_text_i18n of attribute based on serialized_data_text
     *
     * @param string|false $languageLocale Uses AlwaysAvailable language if false
     * @return string
     */
    function dataTextI18n( $languageLocale = false )
    {
        return $this->DataTextI18nList->name( $languageLocale );
    }

    /**
     * Sets data_text_i18n of attribute, store() will take care of writing back to serialized_data_text
     *
     * @param string $string
     * @param string|false $languageLocale Uses AlwaysAvailable language if false
     * @return string Return old value
     */
    function setDataTextI18n( $string, $languageLocale = false )
    {
        return $this->DataTextI18nList->setName( $string, $languageLocale );
    }

    /**
     * Returns data_text_i18n list for all locales for attribute
     *
     * @return array
     */
    function dataTextI18nList()
    {
        return $this->DataTextI18nList->nameList();
    }

    /**
     * Returns locale code as set with {@link self::setEditLocale()}
     *
     * @return string|false
     */
    function editLocale()
    {
        return $this->EditLocale;
    }

    /**
     * Sets locale code of attribute for use by datatypes in class/edit storing process.
     *
     * @param string|false $languageLocale Uses AlwaysAvailable language if false
     */
    function setEditLocale( $languageLocale = false )
    {
        $this->EditLocale = $languageLocale;
    }

    /**
     * Specify AlwaysAvailableLanguage (for name, description or data_text_i18n)
     *
     * @param string|false $languageLocale
     */
    function setAlwaysAvailableLanguage( $languageLocale )
    {
        if ( $languageLocale )
        {
            $this->NameList->setAlwaysAvailableLanguage( $languageLocale );
            $this->DescriptionList->setAlwaysAvailableLanguage( $languageLocale );
            $this->DataTextI18nList->setAlwaysAvailableLanguage( $languageLocale );
        }
        else
        {
            $this->NameList->setAlwaysAvailableLanguage( false );
            $this->DescriptionList->setAlwaysAvailableLanguage( false );
            $this->DataTextI18nList->setAlwaysAvailableLanguage( false );
        }
    }

    /**
     * Removes an translation (as in the serilized strings for name, description or data_text_i18n)
     *
     * @param string $languageLocale
     */
    function removeTranslation( $languageLocale )
    {
        $this->NameList->removeName( $languageLocale );
        $this->DescriptionList->removeName( $languageLocale );
        $this->DataTextI18nList->removeName( $languageLocale );
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
        if ( self::$identifierHash === null )
        {
            $db = eZDB::instance();
            $dbName = md5( $db->DB );

            $cacheDir = eZSys::cacheDirectory();
            $phpCache = new eZPHPCreator( $cacheDir,
                                          'classattributeidentifiers_' . $dbName . '.php',
                                          '',
                                          array( 'clustering' => 'classattridentifiers' ) );

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
                $query = "SELECT ezcontentclass_attribute.id as attribute_id, ezcontentclass_attribute.identifier as attribute_identifier, ezcontentclass.identifier as class_identifier
                          FROM ezcontentclass_attribute, ezcontentclass
                          WHERE ezcontentclass.id=ezcontentclass_attribute.contentclass_id";
                $identifierArray = $db->arrayQuery( $query );

                self::$identifierHash = array();
                foreach ( $identifierArray as $identifierRow )
                {
                    $combinedIdentifier = $identifierRow['class_identifier'] . '/' . $identifierRow['attribute_identifier'];
                    self::$identifierHash[$combinedIdentifier] = (int) $identifierRow['attribute_id'];
                }

                // Store identifier list to cache file
                $phpCache->addVariable( 'identifier_hash', self::$identifierHash );
                $phpCache->store();
            }
        }
        return self::$identifierHash;
    }

    /**
     * Initialize the attribute in the existing objects.
     *
     * @param mixed $objects not used, the existing objects are fetched if
     *        necessary (depending on the datatype of the attribute).
     */
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
            // update ids to keep them same with one attribute for different versions
            if( $db->databaseName() == 'mysql' )
            {
                $updateSql = "UPDATE ezcontentobject_attribute,
                                 ( SELECT contentobject_id, language_code, version, contentclassattribute_id, MIN( id ) AS minid
                                 FROM ezcontentobject_attribute WHERE contentclassattribute_id = $classAttributeID
                              GROUP BY contentobject_id, language_code, version, contentclassattribute_id ) t
                              SET ezcontentobject_attribute.id = t.minid
                              WHERE ezcontentobject_attribute.contentobject_id = t.contentobject_id
                              AND ezcontentobject_attribute.language_code = t.language_code
                              AND ezcontentobject_attribute.contentclassattribute_id = $classAttributeID";
            }
            else if( $db->databaseName() == 'postgresql' )
            {
                $updateSql = "UPDATE ezcontentobject_attribute
                              SET id=t.minid FROM
                                ( SELECT contentobject_id, language_code, version, contentclassattribute_id, MIN( id ) AS minid
                                 FROM ezcontentobject_attribute WHERE contentclassattribute_id = $classAttributeID
                                 GROUP BY contentobject_id, language_code, version, contentclassattribute_id ) t
                              WHERE ezcontentobject_attribute.contentobject_id = t.contentobject_id
                              AND ezcontentobject_attribute.language_code = t.language_code
                              AND ezcontentobject_attribute.contentclassattribute_id = $classAttributeID";
            }
            else if( $db->databaseName() == 'oracle' )
            {
                $updateSql = "UPDATE ezcontentobject_attribute a SET a.id = (
                                 SELECT MIN( id ) FROM ezcontentobject_attribute b
                                 WHERE b.contentclassattribute_id = $classAttributeID
                                       AND b.contentobject_id = a.contentobject_id
                                       AND b.language_code = a.language_code )
                                WHERE a.contentclassattribute_id = $classAttributeID";
            }
            else
            {
                $updateSql = "";
            }
            $db->query( $updateSql );
        }
        else
        {
            $limit = 100;
            $offset = 0;
            while ( true )
            {
                $contentObjects = eZContentObject::fetchSameClassList( $classID, true, $offset, $limit );
                if ( empty( $contentObjects ) )
                {
                    break;
                }

                foreach ( $contentObjects as $object )
                {
                    $contentobjectID = $object->attribute( 'id' );
                    $objectVersions = $object->versions();
                    // the start version ID, to make sure one attribute in different version has same id.
                    $startAttributeID = array();
                    foreach ( $objectVersions as $objectVersion )
                    {
                        $translations = $objectVersion->translations( false );
                        $version = $objectVersion->attribute( 'version' );
                        foreach ( $translations as $translation )
                        {
                            $objectAttribute = eZContentObjectAttribute::create( $classAttributeID, $contentobjectID, $version, $translation );
                            if( array_key_exists( $translation, $startAttributeID ) )
                            {
                                $objectAttribute->setAttribute( 'id', $startAttributeID[$translation] );
                            }
                            $objectAttribute->setAttribute( 'language_code', $translation );
                            $objectAttribute->initialize();
                            $objectAttribute->store();
                            if( !array_key_exists( $translation, $startAttributeID ) )
                            {
                                $startAttributeID[$translation] = $objectAttribute->attribute( 'id' );
                            }
                            $objectAttribute->postInitialize();
                        }
                    }
                }

                $offset += $limit;
                eZContentObject::clearCache();
            }
        }
    }

    /**
     * Clears all content class attribute related caches
     *
     * @param int $contentClassAttributeID Specific attribute ID to clear cache for
     * @param int $contentClassID Specific attribute ID to clear cache for
     *
     * @return void
     * @since 4.2
     */
    public static function expireCache( $contentClassAttributeID = false, $contentClassID = false)
    {
        unset( $GLOBALS['eZContentClassAttributeCacheListFull'] );
        if ( $contentClassID !== false )
        {
            if ( isset( $GLOBALS['eZContentClassAttributeCacheList'][$contentClassID] ) )
            {
                unset( $GLOBALS['eZContentClassAttributeCacheList'][$contentClassID] );
            }
        }
        else
        {
            unset( $GLOBALS['eZContentClassAttributeCacheList'] );
        }
        if ( $contentClassAttributeID !== false )
        {
            if ( isset( $GLOBALS['eZContentClassAttributeCache'][$contentClassAttributeID] ) )
            {
                unset( $GLOBALS['eZContentClassAttributeCache'][$contentClassAttributeID] );
            }
        }
        else
        {
            unset( $GLOBALS['eZContentClassAttributeCache'] );
        }

        // expire cache file by timestamp
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'class-identifier-cache', time() + 1 );
        $handler->store();

        // expire local, in-memory cache
        self::$identifierHash = null;
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
    // unserialized attribute description
    public $DescriptionList;
    public $DataTypeString;
    public $Position;
    public $IsSearchable;
    public $IsRequired;
    public $IsInformationCollector;
    public $Module;
    // Used locale for use by datatypes in class/edit when storing data
    protected $EditLocale = false;

    /**
     * In-memory cache for class attributes identifiers / id matching
     */
    private static $identifierHash = null;
}

?>
