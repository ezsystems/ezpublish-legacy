<?php
//
// $Id$
//
// Definition of eZSchemaElement class
//
// Bård Farstad <bf@ez.no>
// Created on: <13-Feb-2002 10:58:53 bf>
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

/*!
  \class eZSchemaElement ezschemaelement.php
  \ingroup eZXML
  \brief eZSchemaElement handles schema validation elements

*/

class eZSchemaElement
{
    /*!
      Constructs a new eZSchemaElement object.
    */
    function eZSchemaElement()
    {
    }

    /*!
      Sets the elment name
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
      Returns the name of the schema element.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Sets the type of the element, can either be simpleType, complexType or reference.
    */
    function setType( $type )
    {
        if ( $type == "complexType" )
            $this->Type = "complexType";
        else if ( $type == "reference" )
            $this->Type = "reference";
        else
            $this->Type = "simpleType";
    }

    /*!
      Returns the schema element type.
    */
    function type()
    {
        return $this->Type;
    }

    /*!
      Sets the datatype of the element.
    */
    function setDataType( $type )
    {
        $this->DataType = $type;
    }

    /*!
      The data type for simple types. False if not set.
    */
    function dataType()
    {
        return $this->DataType;
    }

    /*!
      Returns true if the type is complex, false if it's a simpletpe.
    */
    function isComplex()
    {
        if ( $this->Type == "complexType" )
            return true;
        else
            return false;
    }

    /*!
      Returns true if the element is a reference.
    */
    function isReference()
    {
        if ( $this->Type == "reference" )
            return true;
        else
            return false;
    }

    /*!
      Returns true if the type is a simple type, false if it's a complex type.
    */
    function isSimple()
    {
        if ( $this->Type == "simpleType" )
            return true;
        else
            return false;
    }

    /*!
      Sets the minum number of occurances for this element
    */
    function setMinOccurs( $value )
    {
        $this->MinOccurs = $value;
    }

    /*!
      Returns the minimum number of occurances of this element.
    */
    function minOccurs()
    {
        return $this->MinOccurs;
    }

    /*!
      Sets the maximum number of occurances for this element
    */
    function setMaxOccurs( $value )
    {
        $this->MaxOccurs = $value;
    }

    /*!
      Returns the maximum number of occurances of this element.
    */
    function maxOccurs()
    {
        return $this->MaxOccurs;
    }

    /*!
      Returns the children nodes for this schema element.
    */
    function children()
    {
        return $this->Children;
    }

    /*!
      Sets the reference identifier.
    */
    function setReference( $value )
    {
        $this->Reference = $value;
        $this->Type = "reference";
    }

    /*!
      Sets the parent element
    */
    function setParent( $element )
    {
        $this->ParentElement = $element;
    }

    /*!
      Returns the parent element. False
    */
    function parentElement()
    {
        return $this->ParentElement;
    }

    /*!
      Sets the next element
    */
    function setNext( $element )
    {
        $this->NextElement = $element;
    }

    /*!
      Returns the next element. False
    */
    function nextElement()
    {
        return $this->NextElement;
    }

    /// Reference to element
    public $Reference = false;

    /// The name of the element
    public $Name = "";

    /// The minimum number of occurances of the element
    public $MinOccurs = 1;

    /// The maximum number of occurances of the element
    public $MaxOccurs = 1;

    /// The schema type
    public $Type = "simpleType";

    /// The next element in the schema
    public $NextElement = false;

    /// The datatype of the element
    public $DataType = false;

    /// The parent element
    public $ParentElement = false;

    /// The sub elements of this element
    public $Children = array();
}

?>
