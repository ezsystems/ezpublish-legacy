<?php
//
// Definition of eZEnum class
//
// Created on: <24-ßÂ-2002 16:07:05 wy>
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

/*! \file ezenum.php
*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );

/*!
  \class eZEnumValue ezenumvalue.php
  \ingroup eZDatatype
  \brief The class eZEnumValue does

*/

class eZEnumValue extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZEnumValue( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "contentclass_attribute_id" => array( 'name' => "ContentClassAttributeID",
                                                                               'datatype' => 'integer',
                                                                               'default' => 0,
                                                                               'required' => true ),
                                         "contentclass_attribute_version" => array( 'name' => "ContentClassAttributeVersion",
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
                                                               'required' => true ),
                                         "placement" => array( 'name' => "Placement",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      "keys" => array( "id", "contentclass_attribute_id", "contentclass_attribute_version" ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZEnumValue",
                      "name" => "ezenumvalue" );
    }

    function &clone()
    {
        $row = array( "id" => null,
                      "contentclass_attribute_id" => $this->attribute( 'contentclass_attribute_id' ),
                      "contentclass_attribute_version" => $this->attribute( 'contentclass_attribute_version' ),
                      "enumvalue" => $this->attribute( 'enumvalue' ),
                      "enumelement" => $this->attribute( 'enumelement' ),
                      "placement" => $this->attribute( 'placement' ) );
        return new eZEnumValue( $row );
    }

    function &create( $contentClassAttributeID, $contentClassAttributeVersion, $element )
    {
        $row = array( "id" => null,
                      "contentclass_attribute_id" => $contentClassAttributeID,
                      "contentclass_attribute_version" => $contentClassAttributeVersion,
                      "enumvalue" => "",
                      "enumelement" => $element,
                      "placement" => eZPersistentObject::newObjectOrder( eZEnumValue::definition(),
                                                                         "placement",
                                                                         array( "contentclass_attribute_id" => $contentClassAttributeID,
                                                                                "contentclass_attribute_version" => $contentClassAttributeVersion ) ) );
        return new eZEnumValue( $row );
    }

    function &createCopy( $id, $contentClassAttributeID, $contentClassAttributeVersion, $element, $value, $placement )
    {
        $row = array( "id" => $id,
                      "contentclass_attribute_id" => $contentClassAttributeID,
                      "contentclass_attribute_version" => $contentClassAttributeVersion,
                      "enumvalue" => $value,
                      "enumelement" => $element,
                      "placement" => $placement );
        return new eZEnumValue( $row );
    }

    function &removeAllElements( $contentClassAttributeID, $version )
    {
        eZPersistentObject::removeObject( eZEnumValue::definition(),
                                          array( "contentclass_attribute_id" => $contentClassAttributeID,
                                                 "contentclass_attribute_version" => $version) );
    }

    function &remove( $id , $version )
    {
        eZPersistentObject::removeObject( eZEnumValue::definition(),
                                          array( "id" => $id,
                                                 "contentclass_attribute_version" => $version) );
    }

    function &fetch( $id, $version, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZEnumValue::definition(),
                                                null,
                                                array( "id" => $id,
                                                       "contentclass_attribute_version" => $version),
                                                $asObject );
    }

    function &fetchAllElements( $classAttributeID, $version, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZEnumValue::definition(),
                                                    null,
                                                    array( "contentclass_attribute_id" => $classAttributeID,
                                                           "contentclass_attribute_version" => $version ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    var $ID;
    var $ContentClassAttributeID;
    var $ContentClassAttributeVersion;
    var $EnumElement;
    var $EnumValue;
    var $Placement;
}

?>
