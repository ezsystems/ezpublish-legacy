<?php
//
// Definition of eZContentDataInstace class
//
// Created on: <22-Apr-2002 09:31:57 bf>
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
        $this->Content = null;
        $this->ValidationError = null;
        $this->ValidationLog = null;
        $this->ContentClassAttributeIdentifier = null;
        $this->ContentClassAttributeID = null;
        $this->eZPersistentObject( $row );
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
                                                      "object" => "object",
                                                      "validation_error" => "validationError",
                                                      "validation_log" => "validationLog",
                                                      "language" => "language",
                                                      "is_a" => "isA"
                                                      ),
                      "increment_key" => "id",
                      "class_name" => "eZContentObjectAttribute",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentobject_attribute" );
    }

    function &fetch( $id, $version, $asObject = true, $field_filters = null )
    {
        return eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(),
                                                $field_filters,
                                                array( "id" => $id,
                                                       "version" => $version ),
                                                $asObject );
    }

    function &fetchSameClassAttributeIDList( $contentClassAttributeID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                    null,
                                                    array( "contentclassattribute_id" => $contentClassAttributeID ),
                                                    null,
                                                    null,
                                                    $asObject);
    }

    function &create( $contentclassAttributeID, $contentobjectID, $version = 1 )
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

    /*!
     Store one row into content attribute table
    */
    function storeNewRow()
    {
        return eZPersistentObject::store();
    }

    function &attribute( $attr )
    {
        if ( $attr == "contentclass_attribute" )
            return $this->contentClassAttribute();
        if ( $attr == "contentclass_attribute" )
            return $this->contentClassAttributeIdentifier();
        else if ( $attr == "content" )
            return $this->content( );
        else if ( $attr == "object" )
            return $this->object( );
        else if ( $attr == "xml" )
            return $this->xml( );
        else if ( $attr == "xml_editor" )
            return $this->xmlEditor( );
        else if ( $attr == "validation_error" )
            return $this->validationError( );
        else if ( $attr == "validation_log" )
            return $this->validationLog( );
        else if  ( $attr == "language" )
            return $this->language( );
        else if  ( $attr == "is_a" )
            return $this->isA( );
        else
            return eZPersistentObject::attribute( $attr );
    }

    function &language( $languageCode = false, $asObject=true )
    {
        $languageCode = eZContentObject::defaultLanguage();
        return eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(),
                                                $field_filters,
                                                array( "contentclassattribute_id" => $this->ContentClassAttributeID ,
                                                       "version" => $this->Version ,
                                                       "language_code" => $languageCode
                                                       ),
                                                $asObject );
    }

    /*!
     \todo read from cached/static object
    */
    function &object()
    {
        return eZContentObject::fetch( $this->ContentObjectID );
    }

    /*!
      Returns the attribute  for the current data attribute instance
      \todo read from cached information
    */
    function &contentClassAttribute()
    {
        $classAttribute =& eZContentClassAttribute::fetch( $this->ContentClassAttributeID );
        return $classAttribute;
    }

    /*!
      \todo read from cached information
    */
    function &contentClassAttributeName()
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
            $this->ContentClassAttributeIdentifier = $classAttribute->attribute( 'identifier' );
//             eZDebug::writeDebug( "Identifier not cached '" . $this->ContentClassAttributeIdentifier . "', fetching from db", "eZContentClassAttribute::contentClassAttributeIdentifier()" );
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
        $this->IsValid = $definition->validateObjectAttributeHTTPInput( $http, $base, $this );
        return $this->IsValid;
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
        // TMP code
        $column = $base . "_data_text_" . $this->attribute( 'id' );
        if ( $http->hasPostVariable( $column ) )
        {
            $columnValue = $http->postVariable( $column );
            $this->OriginalInput = $columnValue;
        }

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
    function initialize( $currentVersion = null, $originalContentObjectAttribute = null )
    {
        if ( $originalContentObjectAttribute === null )
            $originalContentObjectAttribute = $this;
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        $dataType->initializeObjectAttribute( $this, $currentVersion, $originalContentObjectAttribute );
    }

    /*!
     Remove the attribute by using the datatype.
    */
    function &remove( $id, $currentVersion = null )
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        if ( !$dataType )
            return false;
        $dataType->deleteStoredObjectAttribute( $this, $currentVersion );
        if( $currentVersion == null )
        {
            eZPersistentObject::removeObject( eZContentObjectAttribute::definition(),
                                              array( "id" => $id ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZContentObjectAttribute::definition(),
                                              array( "id" => $id,
                                                     "version" => $currentVersion ) );
        }
    }

    /*!
     Clones the attribute with new version \a $newVersionNumber and old version \a $currentVersionNumber.
     \note The cloned attribute is not stored.
    */
    function &clone( $newVersionNumber, $currentVersionNumber, $contentObjectID = false )
    {
        $tmp = $this;
        $tmp->setAttribute( "version", $newVersionNumber );
        if ( $contentObjectID !== false )
        {
            if ( $contentObjectID != $tmp->attribute( 'contentobject_id' ) )
                $tmp->setAttribute( 'id', null );
            $tmp->setAttribute( 'contentobject_id', $contentObjectID );
        }
        $tmp->sync();
        $tmp->initialize( $currentVersionNumber, $this );
        return $tmp;
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
     Returns the metadata. This is the pure content of the attribute used for
     indexing data for search engines.
     */
    function metaData()
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        return $dataType->metaData( $this );
    }


    /*!
     Sets the content for the current attribute
    */
    function setContent( $content )
    {
        $this->Content =& $content;
    }

    /*!
     Returns the content action(s) for this attribute
    */
    function &contentActionList()
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        return $dataType->contentActionList( $classAttribute );
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

    function setValidationLog( $text )
    {
        $this->ValidationLog = $text;
    }

    function validationError()
    {
        return $this->ValidationError;
    }

    function validationLog()
    {
        return $this->ValidationLog;
    }

    /*!
    */
    function &serialize()
    {
        $attribute =& $this->contentClassAttribute();
        $dataType =& $attribute->dataType();
        return $dataType->serializeContentObjectAttribute( $this );
    }

    /*!
    */
    function &isA()
    {
        $attribute =& $this->contentClassAttribute();
        $dataType =& $attribute->dataType();
        return $dataType->isA();
    }

    /// Contains the content for this attribute
    var $Content;

    /// Stores the is valid
    var $IsValid;

    var $ContentClassAttributeID;

    /// Contains the last validation error
    var $ValidationError;

    /// Contains the last validation error
    var $ValidationLog;

    ///
    var $ContentClassAttributeIdentifier;
}

?>
