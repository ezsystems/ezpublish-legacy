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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
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
include_once( "kernel/classes/datatypes/ezenum/ezenumvalue.php" );
include_once( "kernel/classes/datatypes/ezenum/ezenumobjectvalue.php" );

/*!
  \class eZEnum ezenum.php
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
        $this->Enumerations =& eZEnumValue::fetchAllElements( $this->ClassAttributeID, $this->ClassAttributeVersion );
        $this->IsmultipleEnum = null;
        $this->IsoptionEnum = null;
        $this->ObjectEnumerations = null;
    }

    function hasAttribute( $attr )
    {
        if ( $attr == "contentclass_attributeid" )
            return true;
        elseif ( $attr == "contentclass_attributeversion" )
            return true;
        elseif ( $attr == "enum_list" )
            return true;
        elseif ( $attr == "enumobject_list" )
            return true;
        elseif ( $attr == "enum_ismultiple" )
            return true;
        elseif ( $attr == "enum_isoption" )
            return true;
        else
            return false;
    }

    function &attribute( $attr )
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
                eZDebug::writeError( "Unknown attribute: " . $attr );
            }break;
        }
    }

    function setObjectEnumValue( $contentObjectAttributeID, $contentObjectAttributeVersion ){
        $this->ObjectEnumerations =& eZEnumObjectValue::fetchAllElements( $contentObjectAttributeID, $contentObjectAttributeVersion );
    }

    function removeObjectEnumerations( $contentObjectAttributeID, $contentObjectAttributeVersion )
    {
         eZEnumObjectValue::removeAllElements( $contentObjectAttributeID, $contentObjectAttributeVersion );
    }

    function storeObjectEnumeration( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumID, $enumElement, $enumValue )
    {
        $enumobjectvalue =& eZEnumObjectValue::create( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumID, $enumElement, $enumValue );
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
        for ($i=0;$i<count( $array_enumid );$i++ )
        {
            $enumvalue =& eZEnumValue::fetch( $array_enumid[$i], $version );
            $enumvalue->setAttribute( "enumelement", $array_enumelement[$i] );
            $enumvalue->setAttribute( "enumvalue", $array_enumvalue[$i] );
            $enumvalue->store();
            $this->Enumerations =& eZEnumValue::fetchAllElements( $this->ClassAttributeID, $this->ClassAttributeVersion );
        }
    }

    function setVersion( $version )
    {
        if ( $version == $this->ClassAttributeVersion )
            return;
        eZEnumValue::removeAllElements( $this->ClassAttributeID, 0 );
        for ( $i=0;$i<count( $this->Enumerations );$i++ )
        {
            $enum = $this->Enumerations[$i];
            $oldversion = $enum->attribute ( "contentclass_attribute_version" );
            $id = $enum->attribute( "id" );
            $contentClassAttributeID = $enum->attribute( "contentclass_attribute_id" );
            $element = $enum->attribute( "enumelement" );
            $value = $enum->attribute( "enumvalue" );
            $placement = $enum->attribute( "placement" );
            $enumCopy =& eZEnumValue::createCopy( $id,
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
    }

    function removeOldVersion( $id, $version )
    {
        eZEnumValue::removeAllElements( $id, $version );
    }

    /*!
     Adds an enumeration
    */
    function addEnumeration( $element )
    {
        $enumvalue =& eZEnumValue::create( $this->ClassAttributeID, $this->ClassAttributeVersion, $element );
        $enumvalue->store();
        $this->Enumerations =& eZEnumValue::fetchAllElements( $this->ClassAttributeID, $this->ClassAttributeVersion );
    }

    /*!
     Adds the enumeration value object \a $enumValue to the enumeration list.
    */
    function addEnumerationValue( &$enumValue )
    {
        $this->Enumerations[] = $enumValue;
    }

    function removeEnumeration( $id, $enumid, $version )
    {
       eZEnumValue::remove( $enumid, $version );
       $this->Enumerations =& eZEnumValue::fetchAllElements( $id, $version );
    }

    var $Enumerations;
    var $ObjectEnumerations;
    var $ClassAttributeID;
    var $ClassAttributeVersion;
    var $IsmultipleEnum;
    var $IsoptionEnum;
}

?>
