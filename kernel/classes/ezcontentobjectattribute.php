<?php
//
// Definition of eZContentDataInstace class
//
// Created on: <22-Apr-2002 09:31:57 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

/*!
  \class eZContentObjectAttribute ezcontentobjectattribute.php
  \ingroup eZKernel
  \brief handles the document data for the current instance

  \sa eZContentObject eZContentClass eZContentClassAttribute
*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );

class eZContentObjectAttribute extends eZPersistentObject
{
    /*!
     \todo fetch the class attribute and datatype object directly and cache them
    */
    function eZContentObjectAttribute( $row )
    {
        $this->eZPersistentObject( $row );

        $this->Content = null;
        $this->ValidationError = null;
        $this->ContentClassAttributeCatch = null;
        $ContentClassAttributeIdentifier = null;
    }

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "contentobject_id" => "ContentObjectID",
                                         "version" => "Version",
                                         "language_code" => "LanguageCode",
                                         "contentclassattribute_id" => "ContentClassAttributeID",
                                         "data_text" => "DataText",
                                         "data_int" => "DataInt",
                                         "data_float" => "DataFloat" ),
                      "keys" => array( "id", "contentobject_id", "version", "language_code" ),
                      "function_attributes" => array( "contentclass_attribute" => "contentClassAttribute",
                                                      "contentclass_attribute_identifier" => "contentClassAttributeIdentifier",
                                                      "content" => "content",
                                                      "xml" => "xml",
                                                      "input_xml" => "inputXML",
                                                      "validation_error" => "validationError",
                                                      "language" => "language"
                                                      ),
                      "increment_key" => "id",
                      "class_name" => "eZContentObjectAttribute",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentobject_attribute" );
    }

    function &fetch( $id, $version, $as_object = true, $field_filters = null )
    {
        return eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(),
                                                $field_filters,
                                                array( "id" => $id,
                                                       "version" => $version ),
                                                $as_object );
    }

    function create( $contentclassAttributeID, $contentobjectID, $version = 1 )
    {
        include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $row = array(
            "id" => null,
            "contentobject_id" => $contentobjectID,
            "version" => $version,
            "language_code" => eZContentObject::defaultLanguage(),
            "contentclassattribute_id" => $contentclassAttributeID,
            'data_text' => '',
            'data_int' => 0,
            'data_float' => 0.0 );
        return new eZContentObjectAttribute( $row );
    }

    /*!

    */
    function store()
    {
        $classAttr =& $this->contentClassAttribute();
        $dataType =& $classAttr->dataType();

        // store the content data for this attribute
        $dataType->storeObjectAttribute( $this );

        return eZPersistentObject::store();
    }

    function attribute( $attr )
    {
        if ( $attr == "contentclass_attribute" )
            return $this->contentClassAttribute();
        if ( $attr == "contentclass_attribute" )
            return $this->contentClassAttributeIdentifier();
        else if ( $attr == "content" )
            return $this->content( );
        else if ( $attr == "xml" )
            return $this->xml( );
        else if ( $attr == "input_xml" )
            return $this->inputXML( );
        else if ( $attr == "validation_error" )
            return $this->validationError( );
        else if  ( $attr == "language" )
            return $this->language( );
        else
            return eZPersistentObject::attribute( $attr );
    }

    function &language( $languageCode = "no_NO", $as_object=true )
    {
        return eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(),
                                                $field_filters,
                                                array( "contentclassattribute_id" => $this->ContentClassAttributeID ,
                                                       "version" => $this->Version ,
                                                       "language_code" => $languageCode
                                                       ),
                                                $as_object );
    }

    /*!
      Returns the attribute  for the current data attribute instance
      \todo read from cached information
    */
    function &contentClassAttribute()
    {
        if ( $this->ContentClassAttributeCatch === null ){
            $this->ContentClassAttributeCatch = eZContentClassAttribute::fetch( $this->ContentClassAttributeID );
            return $this->ContentClassAttributeCatch;
        }
        else
        {
            return $this->ContentClassAttributeCatch;
        }
    }

    /*!
      \todo read from cached information
    */
    function contentClassAttributeName()
    {
        $classAttribute =& eZContentClassAttribute::fetch( $this->ContentClassAttributeID );
        return $classAttribute->attribute( "name" );
    }

    /*!
     Sets the cached content class attribute identifier
    */
    function setContentClassAttributeIdentifier( $identifier )
    {
        $this->ContentClassAttributeIdentifier = $identifier;
    }

    /*!
     \return the idenfifier for the content class attribute
    */
    function contentClassAttributeIdentifier()
    {
        if ( $this->ContentClassAttributeIdentifier === null )
        {
            $classAttribute =& eZContentClassAttribute::fetch( $this->ContentClassAttributeID );
            $this->ContentClassAttributeIdentifier = $classAttribute->attribute( 'idenfifier' );
            eZDebug::writeNotice( "Identifier not cached, fetching from db", "eZContentClassAttribute::contentClassAttributeIdentifier()" );
        }
        return $this->ContentClassAttributeIdentifier;
    }

    /*!
      Validates the data contents, returns true on success false if the data
      does not validate.
     */
    function validateInput( &$http, $base )
    {
        $classAttribute =& $this->contentClassAttribute();
        $definition =& $classAttribute->dataType();
        return $definition->validateObjectAttributeHTTPInput( $http, $base, $this );
    }

    /*!
      Tries to fixup the input text to be acceptable.
     */
    function fixupInput( &$http, $base )
    {
        $classAttribute =& $this->contentClassAttribute();
        $definition =& $classAttribute->dataType();
        $definition->fixupObjectAttributeHTTPInput( $http, $base, $this );
    }

    /*!
      Fetches the data from http post vars and sets them correctly.
    */
    function fetchInput( &$http, $base )
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        return $dataType->fetchObjectAttributeHTTPInput( $http, $base, $this );
    }

    /*!
     Executes the custom HTTP action
    */
    function customHTTPAction( &$http, $action )
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        $dataType->customObjectAttributeHTTPAction( $http, $action, $this );
    }

    /*!
     Initialized the attribute by using the datatype.
    */
    function initialize( $currentVersion = null )
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        $dataType->initializeObjectAttribute( $this, $currentVersion );
    }

    /*!
     Returns the data type class for the current attribute.
    */
    function &dataType()
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();

        return $dataType;
    }

    /*!
      Fetches the title of the data instance which is to form the title of the object.
    */
    function title()
    {
        $classAttribute =& $this->contentClassAttribute();
        $definition =& $classAttribute->dataType();
        return $definition->title( $this );
    }

    /*!
     Returns the content for this attribute.
     \todo instantiate the data type instance directly
    */
    function content()
    {
        if ( $this->Content === null )
        {
            $attribute =& $this->contentClassAttribute();
            $dataType =& $attribute->dataType();
            $this->Content =& $dataType->objectAttributeContent( $this );
        }

        return $this->Content;
    }

    /*!
     Returns the XML for this attribute
     \todo instantiate the data type instance directly
    */
    function xml()
    {
        if ( $this->XML === null )
        {
            $attribute =& $this->contentClassAttribute();
            $dataType =& $attribute->dataType();
            $this->XML =& $dataType->xml( $this );
        }

        return $this->XML;
    }

    /*!
     Returns the input XML for this attribute.
     \todo instantiate the data type instance directly
    */
    function inputXML()
    {
        if ( $this->InputXML === null )
        {
            $attribute =& $this->contentClassAttribute();
            $dataType =& $attribute->dataType();
            $this->InputXML =& $dataType->inputXML( $this );
        }

        return $this->InputXML;
    }

    /*!
     Returns the metadata. This is the pure content of the attribute used for
     indexing data for search engines.
     */
    function metaData()
    {
        $classAttribute =& $this->contentClassAttribute();
        $definition =& $classAttribute->dataType();
        return $definition->metaData( $this );
    }


    /*!
     Sets the content for the current attribute
    */
    function setContent( $content )
    {
        $this->Content =& $content;
    }

    function setValidationError()
    {
        $numargs = func_num_args();
        if ( $numargs < 1 )
        {
            trigger_error( 'Function must take at least one parameter', WARNING );
            return;
        }
        $argList =& func_get_args();
        $text = $argList[0];
        for ( $i = 1; $i < $numargs; ++$i )
        {
            $arg = $argList[$i];
            $text =& str_replace( "%$i", $arg, $text );
        }
        $this->ValidationError = $text;
    }

    function validationError()
    {
        return $this->ValidationError;
    }

    /// Contains the content for this attribute
    var $Content;

    /// Contains the XML for this attribute
    var $XML;

    /// Contains the input XML for this attribute
    var $InputXML;

    /// Contains the content for this attribute
    var $Content;
    /// Contains the last validation error
    var $ValidationError;

    var $ContentClassAttributeCatch;

    ///
    var $ContentClassAttributeIdentifier;
}

?>
