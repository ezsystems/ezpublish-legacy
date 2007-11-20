<?php
//
// Definition of eZEnum class
//
// Created on: <24-ßÂ-2002 16:07:05 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezenumobjectvalue.php
*/

//include_once( "lib/ezdb/classes/ezdb.php" );
//include_once( "kernel/classes/ezpersistentobject.php" );
//include_once( "kernel/classes/ezcontentclassattribute.php" );

/*!
  \class eZEnumObjectValue ezenumobjectvalue.php
  \brief The class eZEnumObjectValue stores chosen enum values of an object attribute

*/

class eZEnumObjectValue extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZEnumObjectValue( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "contentobject_attribute_id" => array( 'name' => "ContentObjectAttributeID",
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true,
                                                                                'foreign_class' => 'eZContentObjectAttribute',
                                                                                'foreign_attribute' => 'id',
                                                                                'multiplicity' => '1..*' ),
                                         "contentobject_attribute_version" => array( 'name' => "ContentObjectAttributeVersion",
                                                                                     'datatype' => 'integer',
                                                                                     'default' => 0,
                                                                                     'required' => true,
                                                                                     'short_name' => 'contentobject_attr_version' ),
                                         "enumid" => array( 'name' => "EnumID",
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         "enumelement" => array( 'name' => "EnumElement",
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         "enumvalue" => array( 'name' => "EnumValue",
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ) ),
                      "keys" => array( "contentobject_attribute_id", "contentobject_attribute_version", "enumid" ),
                      "sort" => array( "contentobject_attribute_id" => "asc" ),
                      "class_name" => "eZEnumObjectValue",
                      "name" => "ezenumobjectvalue" );
    }

    static function create( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumID, $enumElement, $enumValue )
    {
        $row = array( "contentobject_attribute_id" => $contentObjectAttributeID,
                      "contentobject_attribute_version" => $contentObjectAttributeVersion,
                      "enumid" => $enumID,
                      "enumelement" =>  $enumElement,
                      "enumvalue" => $enumValue );
        return new eZEnumObjectValue( $row );
    }

    static function removeAllElements( $contentObjectAttributeID, $contentObjectAttributeVersion )
    {
        if( $contentObjectAttributeVersion == null )
        {
            eZPersistentObject::removeObject( eZEnumObjectValue::definition(),
                                              array( "contentobject_attribute_id" => $contentObjectAttributeID ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZEnumObjectValue::definition(),
                                              array( "contentobject_attribute_id" => $contentObjectAttributeID,
                                                     "contentobject_attribute_version" => $contentObjectAttributeVersion ) );
        }
    }

    function removeByOAID( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumid )
    {
        eZPersistentObject::removeObject( eZEnumObjectValue::definition(),
                                          array( "enumid" => $enumid,
                                                 "contentobject_attribute_id" => $contentObjectAttributeID,
                                                 "contentobject_attribute_version" => $contentObjectAttributeVersion ) );
    }

    static function fetch( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumid, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZEnumObjectValue::definition(),
                                                null,
                                                array(  "contentobject_attribute_id" => $contentObjectAttributeID,
                                                        "contentobject_attribute_version" => $contentObjectAttributeVersion,
                                                        "enumid" => $enumid ),
                                                $asObject );
    }

    static function fetchAllElements( $contentObjectAttributeID, $contentObjectAttributeVersion, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZEnumObjectValue::definition(),
                                                    null,
                                                    array( "contentobject_attribute_id" => $contentObjectAttributeID,
                                                           "contentobject_attribute_version" => $contentObjectAttributeVersion ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    public $ContentObjectAttributeID;
    public $ContentObjectAttributeVersion;
    public $EnumID;
    public $EnumElement;
    public $EnumValue;
}

?>
