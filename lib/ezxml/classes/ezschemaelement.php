<?php
//
// $Id$
//
// Definition of eZSchemaElement class
//
// Bård Farstad <bf@ez.no>
// Created on: <13-Feb-2002 10:58:53 bf>
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
    var $Reference = false;

    /// The name of the element
    var $Name = "";

    /// The minimum number of occurances of the element
    var $MinOccurs = 1;

    /// The maximum number of occurances of the element
    var $MaxOccurs = 1;

    /// The schema type
    var $Type = "simpleType";

    /// The next element in the schema
    var $NextElement = false;

    /// The datatype of the element
    var $DataType = false;

    /// The parent element
    var $ParentElement = false;

    /// The sub elements of this element
    var $Children = array();
}

?>
