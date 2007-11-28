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

/*! \file ezenum.php
*/

//include_once( "lib/ezdb/classes/ezdb.php" );
//include_once( "kernel/classes/datatypes/ezenum/ezenumvalue.php" );
//include_once( "kernel/classes/datatypes/ezenum/ezenumobjectvalue.php" );

/*!
  \class eZEnum ezenum.php
  \ingroup eZDatatype
  \brief The class eZEnum does

*/

class eZEnum
{
    /*!
     Constructor
    */
    function eZEnum( $id, $version )
    {
        $this->ClassAttributeID = $id;
        $this->ClassAttributeVersion = $version;
        $this->Enumerations = eZEnumValue::fetchAllElements( $this->ClassAttributeID, $this->ClassAttributeVersion );
        $this->IsmultipleEnum = null;
        $this->IsoptionEnum = null;
        $this->ObjectEnumerations = null;
    }

    function attributes()
    {
        return array( 'contentclass_attributeid',
                      'contentclass_attributeversion',
                      'enum_list',
                      'enumobject_list',
                      'enum_ismultiple',
                      'enum_isoption' );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        switch ( $attr )
        {
            case "contentclass_attributeid" :
            {
                return $this->ClassAttributeID;
            }break;
            case "contentclass_attributeversion" :
            {
                return $this->ClassAttributeVersion;
            }break;
            case "enum_list" :
            {
                return $this->Enumerations;
            }break;
            case "enumobject_list" :
            {
                return $this->ObjectEnumerations;
            }break;
            case "enum_ismultiple" :
            {
                return $this->IsmultipleEnum;
            }break;
            case "enum_isoption" :
            {
                return $this->IsoptionEnum;
            }break;
            default :
            {
                eZDebug::writeError( "Attribute '$attr' does not exist", 'eZEnum::attribute' );
                return null;
            }break;
        }
    }

    function setObjectEnumValue( $contentObjectAttributeID, $contentObjectAttributeVersion ){
        $this->ObjectEnumerations = eZEnumObjectValue::fetchAllElements( $contentObjectAttributeID, $contentObjectAttributeVersion );
    }

    static function removeObjectEnumerations( $contentObjectAttributeID, $contentObjectAttributeVersion )
    {
         eZEnumObjectValue::removeAllElements( $contentObjectAttributeID, $contentObjectAttributeVersion );
    }

    static function storeObjectEnumeration( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumID, $enumElement, $enumValue )
    {
        $enumobjectvalue = eZEnumObjectValue::create( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumID, $enumElement, $enumValue );
        $enumobjectvalue->store();
    }

    function setIsmultipleValue( $value )
    {
        $this->IsmultipleEnum = $value;
    }

    function setIsoptionValue( $value )
    {
        $this->IsoptionEnum = $value;
    }

    function setValue( $array_enumid, $array_enumelement, $array_enumvalue, $version )
    {
        $db = eZDB::instance();
        $db->begin();

        for ($i=0;$i<count( $array_enumid );$i++ )
        {
            $enumvalue = eZEnumValue::fetch( $array_enumid[$i], $version );
            $enumvalue->setAttribute( "enumelement", $array_enumelement[$i] );
            $enumvalue->setAttribute( "enumvalue", $array_enumvalue[$i] );
            $enumvalue->store();
            $this->Enumerations = eZEnumValue::fetchAllElements( $this->ClassAttributeID, $this->ClassAttributeVersion );
        }
        $db->commit();
    }

    function setVersion( $version )
    {
        if ( $version == $this->ClassAttributeVersion )
            return;

        $db = eZDB::instance();
        $db->begin();

        eZEnumValue::removeAllElements( $this->ClassAttributeID, 0 );
        foreach( $this->Enumerations as $enum )
        {
            $oldversion = $enum->attribute ( "contentclass_attribute_version" );
            $id = $enum->attribute( "id" );
            $contentClassAttributeID = $enum->attribute( "contentclass_attribute_id" );
            $element = $enum->attribute( "enumelement" );
            $value = $enum->attribute( "enumvalue" );
            $placement = $enum->attribute( "placement" );
            $enumCopy = eZEnumValue::createCopy( $id,
                                                 $contentClassAttributeID,
                                                 0,
                                                 $element,
                                                 $value,
                                                 $placement );
            $enumCopy->store();
            if ( $oldversion != $version )
            {
                $enum->setAttribute("contentclass_attribute_version", $version );
                $enum->store();
            }
        }

        $this->Enumerations = eZEnumValue::fetchAllElements( $this->ClassAttributeID, $this->ClassAttributeVersion );

        $db->commit();
    }

    static function removeOldVersion( $id, $version )
    {
        eZEnumValue::removeAllElements( $id, $version );
    }

    /*!
     Adds an enumeration
    */
    function addEnumeration( $element )
    {
        $enumvalue = eZEnumValue::create( $this->ClassAttributeID, $this->ClassAttributeVersion, $element );
        $enumvalue->store();
        $this->Enumerations = eZEnumValue::fetchAllElements( $this->ClassAttributeID, $this->ClassAttributeVersion );
    }

    /*!
     Adds the enumeration value object \a $enumValue to the enumeration list.
    */
    function addEnumerationValue( $enumValue )
    {
        $this->Enumerations[] = $enumValue;
    }

    function removeEnumeration( $id, $enumid, $version )
    {
       eZEnumValue::removeByID( $enumid, $version );
       $this->Enumerations = eZEnumValue::fetchAllElements( $id, $version );
    }

    public $Enumerations;
    public $ObjectEnumerations;
    public $ClassAttributeID;
    public $ClassAttributeVersion;
    public $IsmultipleEnum;
    public $IsoptionEnum;
}

?>
