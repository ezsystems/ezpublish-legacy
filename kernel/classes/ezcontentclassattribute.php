<?php
//
// Definition of eZContentClassAttribute class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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
  \class eZContentClassAttribute ezcontentclassattribute.php
  \ingroup eZKernel
  \brief Encapsulates data for a class attribute

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );

class eZContentClassAttribute extends eZPersistentObject
{
    function eZContentClassAttribute( $row )
    {
        $this->eZPersistentObject( $row );

        $this->Content = null;
        $this->Module = null;
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'name' => array( 'name' => 'Name',
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
                                                                     'required' => true ),
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
                                                      'data_type' => 'dataType' ),
                      'increment_key' => 'id',
                      'sort' => array( 'placement' => 'asc' ),
                      'class_name' => 'eZContentClassAttribute',
                      'name' => 'ezcontentclass_attribute' );
    }

    function &clone()
    {
        $row = array(
            'id' => null,
            'version' => $this->attribute( 'version' ),
            'contentclass_id' => $this->attribute( 'contentclass_id' ),
            'identifier' => $this->attribute( 'identifier' ),
            'name' => $this->attribute( 'name' ),
            'is_searchable' => $this->attribute( 'is_searchable' ),
            'is_required' => $this->attribute( 'is_required' ),
            'can_translate' => $this->attribute( 'can_translate' ),
            'is_information_collector' => $this->attribute( 'is_information_collector' ),
            'data_type_string' => $this->attribute( 'data_type_string' ),
            'placement' => $this->attribute( 'placement' ),
            'data_int1' => $this->attribute( 'data_int1' ),
            'data_int2' => $this->attribute( 'data_int2' ),
            'data_int3' => $this->attribute( 'data_int3' ),
            'data_int4' => $this->attribute( 'data_int4' ),
            'data_float1' => $this->attribute( 'data_float1' ),
            'data_float2' => $this->attribute( 'data_float2' ),
            'data_float3' => $this->attribute( 'data_float3' ),
            'data_float4' => $this->attribute( 'data_float4' ),
            'data_text1' => $this->attribute( 'data_text1' ),
            'data_text2' => $this->attribute( 'data_text1' ),
            'data_text3' => $this->attribute( 'data_text3' ),
            'data_text4' => $this->attribute( 'data_text4' ),
            'data_text5' => $this->attribute( 'data_text5' ) );
        return new eZContentClassAttribute( $row );
    }

    function &create( $class_id, $data_type_string, $optionalValues = array() )
    {
        $row = array(
            'id' => null,
            'version' => EZ_CLASS_VERSION_STATUS_TEMPORARY,
            'contentclass_id' => $class_id,
            'identifier' => '',
            'name' => '',
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
        return new eZContentClassAttribute( $row );
    }

    function instantiate( $contentobjectID )
    {
        $attribute =& eZContentObjectAttribute::create( $this->attribute( 'id' ), $contentobjectID );
        $attribute->initialize();
        $attribute->store();
        $attribute->postInitialize();
    }

    function instantiateTemporary( $contentobjectID = false )
    {
        $attribute =& eZContentObjectAttribute::create( $this->attribute( 'id' ), $contentobjectID );
        return $attribute;
    }

    function store()
    {
        $dataType =& $this->dataType();
        $dataType->preStoreClassAttribute( $this, $this->attribute( 'version' ) );
        $stored = eZPersistentObject::store();

        // store the content data for this attribute
        $info = $dataType->attribute( "information" );
        $dataType->storeClassAttribute( $this, $this->attribute( 'version' ) );

        return $stored;
    }

    function storeDefined()
    {
        $dataType =& $this->dataType();
        $dataType->preStoreDefinedClassAttribute( $this );
        $stored = eZPersistentObject::store();

        // store the content data for this attribute
        $info = $dataType->attribute( "information" );
        $dataType->storeDefinedClassAttribute( $this );

        return $stored;
    }

    function remove()
    {
        $dataType =& $this->dataType();
        $version = $this->Version;
        if ( $dataType->isClassAttributeRemovable( $this ) )
        {
            $dataType->deleteStoredClassAttribute( $this, $version );
            eZPersistentObject::remove();
        }
        else
        {
            eZDebug::writeError( 'Datatype [' . $dataType->Name . '] can not be deleted to avoid system crash' );
        }
    }

    function &fetch( $id, $asObject = true, $version = EZ_CLASS_VERSION_STATUS_DEFINED, $field_filters = null )
    {
        $object = null;
        if ( $field_filters === null and $asObject )
        {
            $object =& $GLOBALS['eZContentClassAttributeCache'][$id][$version];
        }
        if ( !isset( $object ) or
             $object === null )
        {
            $object = eZPersistentObject::fetchObject( eZContentClassAttribute::definition(),
                                                       $field_filters,
                                                       array( 'id' => $id,
                                                              'version' => $version ),
                                                       $asObject );
        }
        return $object;
    }

    function &fetchList( $asObject = true, $parameters = array() )
    {
        $parameters = array_merge( array( 'data_type' => false,
                                          'version' => false ),
                                   $parameters );
        $dataType = $parameters['data_type'];
        $version = $parameters['version'];
        $objects = null;
        if ( $asObject and $dataType === false and $version === false )
        {
            $objects =& $GLOBALS['eZContentClassAttributeCacheListFull'];
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
            $objectList =& eZPersistentObject::fetchObjectList( eZContentClassAttribute::definition(),
                                                                null, $conditions, null, null,
                                                                $asObject );
            foreach ( array_keys( $objectList ) as $objectKey )
            {
                $objectItem =& $objectList[$objectKey];
                $objectID = $objectItem->ID;
                $objectVersion = $objectItem->Version;
                $GLOBALS['eZContentClassAttributeCache'][$objectID][$objectVersion] =& $objectItem;
            }
            $objects = $objectList;
        }
        return $objects;
    }

    function &fetchListByClassID( $classID, $version = EZ_CLASS_VERSION_STATUS_DEFINED, $asObject = true )
    {
        $objects = null;
        if ( $asObject )
        {
            $objects =& $GLOBALS['eZContentClassAttributeCacheList'][$classID][$version];
        }
        if ( !isset( $objects ) or
             $objects === null )
        {
            $cond = array( 'contentclass_id' => $classID,
                           'version' => $version );
            $objectList =& eZPersistentObject::fetchObjectList( eZContentClassAttribute::definition(),
                                                                null, $cond, null, null,
                                                                $asObject );
            foreach ( array_keys( $objectList ) as $objectKey )
            {
                $objectItem =& $objectList[$objectKey];
                $objectID = $objectItem->ID;
                $objectVersion = $objectItem->Version;
                if ( !isset( $GLOBALS['eZContentClassAttributeCache'][$objectID][$objectVersion] ) )
                    $GLOBALS['eZContentClassAttributeCache'][$objectID][$objectVersion] =& $objectItem;
            }
            $objects = $objectList;
        }
        return $objects;
    }

    function &fetchFilteredList( $cond, $asObject = true )
    {
        $objectList =& eZPersistentObject::fetchObjectList( eZContentClassAttribute::definition(),
                                                            null, $cond, null, null,
                                                            $asObject );
        foreach ( array_keys( $objectList ) as $objectKey )
        {
            $objectItem =& $objectList[$objectKey];
            $objectID = $objectItem->ID;
            $objectVersion = $objectItem->Version;
            if ( !isset( $GLOBALS['eZContentClassAttributeCache'][$objectID][$objectVersion] ) )
                $GLOBALS['eZContentClassAttributeCache'][$objectID][$objectVersion] =& $objectItem;
        }
        return $objectList;
    }

    /*!
     Moves the object down if $down is true, otherwise up.
     If object is at either top or bottom it is wrapped around.
    */
    function &move( $down, $params = null )
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
        return eZPersistentObject::reorderObject( eZContentClassAttribute::definition(),
                                                  array( 'placement' => $pos ),
                                                  array( 'contentclass_id' => $cid,
                                                         'version' => $version ),
                                                  $down );
    }

    function &dataType()
    {
        include_once( 'kernel/classes/ezdatatype.php' );
        $datatype =& eZDataType::create( $this->DataTypeString );
        return $datatype;
    }

    /*!
     Returns the content for this attribute.
    */
    function content()
    {
        if ( $this->Content === null )
        {
            $dataType =& $this->dataType();
            $this->Content =& $dataType->classAttributeContent( $this );
        }

        return $this->Content;
    }

    /*!
     Sets the content for the current attribute
    */
    function setContent( $content )
    {
        $this->Content =& $content;
    }

    /*!
     Executes the custom HTTP action
    */
    function customHTTPAction( &$module, &$http, $action )
    {
        $dataType =& $this->dataType();
        $this->Module =& $module;
        $dataType->customClassAttributeHTTPAction( $http, $action, $this );
        unset( $this->Module );
        $this->Module = null;
    }

    /*!
     \return the module which uses this attribute or \c null if no module set.
     \note Currently only customHTTPAction sets this.
    */
    function &currentModule()
    {
        return $this->Module;
    }

    /// \privatesection
    /// Contains the content for this attribute
    var $Content;
    var $ID;
    var $Version;
    var $ContentClassID;
    var $Identifier;
    var $Name;
    var $DataTypeString;
    var $Position;
    var $IsSearchable;
    var $IsRequired;
    var $IsInformationCollector;
    var $Module;
}

?>
