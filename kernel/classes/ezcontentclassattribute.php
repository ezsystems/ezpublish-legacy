<?php
//
// Definition of eZContentClassAttribute class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

//!! eZKernel
//! The class eZContentClassAttribute does
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );

class eZContentClassAttribute extends eZPersistentObject
{
    function eZContentClassAttribute( $row )
    {
        $this->eZPersistentObject( $row );

        $this->Content = null;
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => 'ID',
                                         'name' => 'Name',
                                         'version' => 'Version',
                                         'contentclass_id' => 'ContentClassID',
                                         'identifier' => 'Identifier',
                                         'placement' => 'Position',
                                         'is_searchable' => 'IsSearchable',
                                         'is_required' => 'IsRequired',
                                         'is_information_collector' => 'IsInformationCollector',
                                         'data_type_string' => 'DataTypeString',
                                         'data_int1' => 'DataInt1',
                                         'data_int2' => 'DataInt2',
                                         'data_int3' => 'DataInt3',
                                         'data_int4' => 'DataInt4',
                                         'data_float1' => 'DataFloat1',
                                         'data_float2' => 'DataFloat2',
                                         'data_float3' => 'DataFloat3',
                                         'data_float4' => 'DataFloat4',
                                         'data_text1' => 'DataText1',
                                         'data_text2' => 'DataText2',
                                         'data_text3' => 'DataText3',
                                         'data_text4' => 'DataText4' ),
                      'keys' => array( 'id', 'version' ),
                      "function_attributes" => array( "content" => "content",
                                                      "contentclass_attribute_identifier" ),
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
            'data_text4' => $this->attribute( 'data_text4' ) );
        return new eZContentClassAttribute( $row );
    }

    function &create( $class_id, $data_type_string )
    {
        $row = array(
            'id' => null,
            'version' => 1,
            'contentclass_id' => $class_id,
            'identifier' => '',
            'name' => '',
            'is_searchable' => true,
            'is_required' => false,
            'is_information_collector' => false,
            'data_type_string' => $data_type_string,
            'placement' => eZPersistentObject::newObjectOrder( eZContentClassAttribute::definition(),
                                                              'placement',
                                                              array( 'version' => 1,
                                                                     'contentclass_id' => $class_id ) ) );
        return new eZContentClassAttribute( $row );
    }

    function instantiate( $contentobjectID )
    {
        $attribute =& eZContentObjectAttribute::create( $this->attribute( 'id' ), $contentobjectID );
        $attribute->initialize();
        $attribute->store();
    }

    function store()
    {
        $stored = eZPersistentObject::store();

        $dataType =& $this->dataType();
        // store the content data for this attribute
        $info = $dataType->attribute( "information" );
        $dataType->storeClassAttribute( $this, $this->attribute( 'version' ) );

        return $stored;
    }

    function storeDefined()
    {
        $stored = eZPersistentObject::store();

        $dataType =& $this->dataType();
        // store the content data for this attribute
        $info = $dataType->attribute( "information" );
        $dataType->storeDefinedClassAttribute( $this );

        return $stored;
    }

    function remove()
    {
        $dataType =& $this->dataType();
        $version = $this->Version;
        $dataType->deleteStoredClassAttribute( $this, $version );
        eZPersistentObject::remove();
    }


    function &fetch( $id, $asObject = true, $version = 0, $field_filters = null )
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

    function &fetchList( $asObject = true )
    {
        $objects = null;
        if ( $asObject )
        {
            $objects =& $GLOBALS['eZContentClassAttributeCacheListFull'];
        }
        if ( !isset( $objects ) or
             $objects === null )
        {
            $objectList =& eZPersistentObject::fetchObjectList( eZContentClassAttribute::definition(),
                                                                null, null, null, null,
                                                                $asObject );
            foreach ( array_keys( $objectList ) as $objectKey )
            {
                $objectItem =& $objectList[$objectKey];
                $objectID = $objectItem->ID;
                $objectVersion = $objectItem->Version;
                $GLOBALS['eZContentClassAttributeCache'][$id][$version] =& $objectItem;
            }
            $objects = $objectList;
        }
        return $objects;
    }

    function &fetchListByClassID( $classID, $version = 0, $asObject = true )
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

    function attributes()
    {
        return array_merge( eZPersistentObject::attributes(), array( 'data_type' ) );
    }

    function hasAttribute( $attr )
    {
        return $attr == 'data_type' or eZPersistentObject::hasAttribute( $attr);
    }

    function &attribute( $attr )
    {
        if ( $attr == 'data_type' )
        {
            $datatype =& $this->dataType();
            return $datatype;
        }
        else if ( $attr == "content" )
            return $this->content( );
        else
            return eZPersistentObject::attribute( $attr );
    }

    function &dataType()
    {
        include_once( 'kernel/classes/ezdatatype.php' );
        $datatype =& eZDataType::create( $this->DataTypeString );
        return $datatype;
    }

    /*!
     Returns the content for this attribute.
     \todo instantiate the data type instance directly
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
    function customHTTPAction( &$http, $action )
    {
        $dataType =& $this->dataType();
        $dataType->customClassAttributeHTTPAction( $http, $action, $this );
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
}

?>
