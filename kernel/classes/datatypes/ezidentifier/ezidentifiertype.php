<?php
//
// Definition of eZIdentifierType class
//
// Created on: <28-Aug-2003 11:43:09 br>
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

/*! \file ezidentifiertype.php
*/

/*!
  \class eZIdentifierType ezidentifiertype.php
  \ingroup eZDatatype
  \brief The class eZIdentifierType does

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezutils/classes/ezintegervalidator.php" );

define( "EZ_DATATYPESTRING_PRETEXT_FIELD", "data_text1" );
define( "EZ_DATATYPESTRING_PRETEXT_VARIABLE", "_ezidentifier_pretext_value_" );

define( "EZ_DATATYPESTRING_POSTTEXT_FIELD", "data_text2" );
define( "EZ_DATATYPESTRING_POSTTEXT_VARIABLE", "_ezidentifier_posttext_value_" );

define( "EZ_DATATYPESTRING_START_VALUE_FIELD", "data_int1" );
define( "EZ_DATATYPESTRING_START_VALUE_VARIABLE", "_ezidentifier_start_integer_value_" );

define( "EZ_DATATYPESTRING_DIGITS_FIELD", "data_int2" );
define( "EZ_DATATYPESTRING_DIGITS_VARIABLE", "_ezidentifier_digits_integer_value_" );

define( "EZ_DATATYPESTRING_IDENTIFIER_FIELD", "data_int3" );
define( "EZ_DATATYPESTRING_IDENTIFIER_VARIABLE", "_ezidentifier_identifier_value_" );

define( "EZ_DATATYPESTRING_IDENTIFIER", "ezidentifier" );

class eZIdentifierType extends eZDataType
{
    /*!
     Constructor
    */
    function eZIdentifierType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_IDENTIFIER,
                           ezi18n( 'kernel/classes/datatypes', "Identifier", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_text' => 'identifier',
                                                                   'data_int' => 'number' ) ) );
        $this->IntegerValidator = new eZIntegerValidator( 1 );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
    }

    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
    }

    /*!
     Store the content. Since the content has been stored in function fetchObjectAttributeHTTPInput(),
     this function is with empty code.
    */
    function storeObjectAttribute( &$contentObjectattribute )
    {
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $content = $contentObjectAttribute->attribute( "data_text" );
        if ( trim( $content ) == '' )
        {
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
            $content = eZIdentifierType::generateIdentifierString( $contentClassAttribute, false );
        }
        return $content;
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        $content = $contentObjectAttribute->attribute( "data_text" );
        return ( trim( $content ) != '' );
    }

    function initializeClassAttribute( &$classAttribute )
    {
        if ( $classAttribute->attribute( EZ_DATATYPESTRING_START_VALUE_FIELD ) == null
          && $classAttribute->attribute( EZ_DATATYPESTRING_DIGITS_FIELD ) == null 
          && $classAttribute->attribute( EZ_DATATYPESTRING_IDENTIFIER_FIELD ) == null )
        {
            $classAttribute->setAttribute( EZ_DATATYPESTRING_START_VALUE_FIELD, 1 );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_IDENTIFIER_FIELD, 1 );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_DIGITS_FIELD, 1 );
        }
    }

    /*!
      Validates the input and returns true if the input was
      valid for this datatype.
    */
	function validateClassAttributeHTTPInput( &$http, $base, &$classAttribute )
	{
        $startValueName = $base . EZ_DATATYPESTRING_START_VALUE_VARIABLE . $classAttribute->attribute( "id" );
        $digitsName = $base . EZ_DATATYPESTRING_DIGITS_VARIABLE . $classAttribute->attribute( "id" );

        if ( $http->hasPostVariable( $startValueName ) and
             $http->hasPostVariable( $digitsName ) )
        {
            $startValueValue = str_replace( " ", "", $http->postVariable( $startValueName ) );
            $digitsValue = str_replace( " ", "", $http->postVariable( $digitsName ) );

            $startValueValueState = $this->IntegerValidator->validate( $startValueValue );
            $digitsValueState = $this->IntegerValidator->validate( $digitsValue );

            if ( ( $startValueValueState == EZ_INPUT_VALIDATOR_STATE_ACCEPTED ) and
                 ( $digitsValueState == EZ_INPUT_VALIDATOR_STATE_ACCEPTED ) )
            {
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            return EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE;
        }
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

	/*!
	 \reimp
	*/
	function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
	{
        $startValueName = $base . EZ_DATATYPESTRING_START_VALUE_VARIABLE . $classAttribute->attribute( "id" );
        $digitsName = $base . EZ_DATATYPESTRING_DIGITS_VARIABLE . $classAttribute->attribute( "id" );
		$preTextName = $base . EZ_DATATYPESTRING_PRETEXT_VARIABLE . $classAttribute->attribute( "id" );
		$postTextName = $base . EZ_DATATYPESTRING_POSTTEXT_VARIABLE . $classAttribute->attribute( "id" );

        if ( $http->hasPostVariable( $startValueName ) and
             $http->hasPostVariable( $digitsName ) and
             $http->hasPostVariable( $preTextName ) and
             $http->hasPostVariable( $postTextName ) )
        {
            $startValueValue = str_replace( " ", "", $http->postVariable( $startValueName ) );
            $startValueValue = ( int ) $startValueValue;
            if ( $startValueValue < 1 )
            {
                $startValueValue = 1;
            }
            $digitsValue = str_replace( " ", "", $http->postVariable( $digitsName ) );
            $digitsValue = ( int ) $digitsValue;
            if ( $digitsValue < 1 )
            {
                $digitsValue = 1;
            }

            $preTextValue =  $http->postVariable( $preTextName );
            $postTextValue = $http->postVariable( $postTextName );

            $classAttribute->setAttribute( EZ_DATATYPESTRING_DIGITS_FIELD, $digitsValue );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_PRETEXT_FIELD, $preTextValue );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_POSTTEXT_FIELD, $postTextValue );

            $classAttribute->setAttribute( EZ_DATATYPESTRING_START_VALUE_FIELD, $startValueValue );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_IDENTIFIER_FIELD,
                                           $classAttribute->attribute( EZ_DATATYPESTRING_START_VALUE_FIELD ) );

            $originalClassAttribute = eZContentClassAttribute::fetch( $classAttribute->attribute( 'id' ), true, 0 );
            if ( $originalClassAttribute )
            {
                if ( $originalClassAttribute->attribute( EZ_DATATYPESTRING_DIGITS_FIELD ) == $digitsValue
                  && $originalClassAttribute->attribute( EZ_DATATYPESTRING_PRETEXT_FIELD ) == $preTextValue
                  && $originalClassAttribute->attribute( EZ_DATATYPESTRING_POSTTEXT_FIELD ) == $postTextValue
                  && $originalClassAttribute->attribute( EZ_DATATYPESTRING_IDENTIFIER_FIELD ) >= $startValueValue )
                {
                    $classAttribute->setAttribute( EZ_DATATYPESTRING_START_VALUE_FIELD, $originalClassAttribute->attribute( EZ_DATATYPESTRING_START_VALUE_FIELD ) );
                    $classAttribute->setAttribute( EZ_DATATYPESTRING_IDENTIFIER_FIELD, $originalClassAttribute->attribute( EZ_DATATYPESTRING_IDENTIFIER_FIELD ) );
                }
            }
        }
        return true;
    }

    /*!
     Returns the meta data used for storing search indices.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     Returns the text.
    */
    function title( &$contentObjectAttribute )
    {
        return  $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }


    /*!
      The value should be created and incremented when the object is published.
      No id will be generated for draft. ID should also only be generated for
      the first published version.
    */
    function onPublish( &$contentObjectAttribute, &$contentObject, &$publishedNodes )
    {
        $contentClassAttribute = $contentObjectAttribute->attribute( 'contentclass_attribute' );

        // fetch the root node
        $ret = eZIdentifierType::assignValue( $contentClassAttribute, $contentObjectAttribute );

        $contentObjectAttribute->store();
        return $ret;
    }

    /*!
      \private
      Assigns the identifiervalue for the first version of the current attribute.
    */
    function assignValue( &$contentClassAttribute, &$contentObjectAttribute )
    {

        $retValue = true;
        $ret = array();
        $version = $contentObjectAttribute->attribute( 'version' );
        $contentClassID = $contentClassAttribute->attribute( 'id' );
        if ( $version == 1 )
        {
            $db =& eZDB::instance();
            $db->begin();

            // Ensure that we don't get another identifier with the same id.
            $db->lock( array( array( "table" => "ezcontentobject_attribute" ),
                              array( "table" => "ezcontentclass_attribute" ) ) );

            $selectQuery = "SELECT data_int3 FROM ezcontentclass_attribute WHERE " .
                 "id='$contentClassID' AND version='0'";
            $result = $db->arrayQuery( $selectQuery );
            $identifierValue = $result[0]['data_int3'];

            // should only increment when we don't have the first version
            $updateQuery = "UPDATE ezcontentclass_attribute SET data_int3=data_int3 + 1 WHERE " .
                  "id='$contentClassID' AND version='0'";

            $ret[] = $db->query( $updateQuery );
            $ret[] = eZIdentifierType::storeIdentifierValue( $contentClassAttribute, $contentObjectAttribute, $identifierValue );

            if ( !in_array( false, $ret ) )
                $db->commit();
            else
                $db->rollback();

            $db->unlock();
        }

        if ( !in_array( false, $ret ) )
            $retValue = true;

        return $retValue;
    }

    /*!
     \reimp
    */
    function sortKey( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
      \private
      Store the new value to the attribute.
    */
    function storeIdentifierValue( &$contentClassAttribute, &$contentObjectAttribute, $identifierValue )
    {
        $value = eZIdentifierType::generateIdentifierString( $contentClassAttribute, $identifierValue );
        $contentObjectAttribute->setAttribute( 'data_text', $value );
        $contentObjectAttribute->setAttribute( 'data_int', $identifierValue );
        return true;
    }

    function generateIdentifierString( &$contentClassAttribute, $identifierValue = false )
    {
        $preText = $contentClassAttribute->attribute( EZ_DATATYPESTRING_PRETEXT_FIELD );
        $postText = $contentClassAttribute->attribute( EZ_DATATYPESTRING_POSTTEXT_FIELD );
        $digits = $contentClassAttribute->attribute( EZ_DATATYPESTRING_DIGITS_FIELD );

        if ( $identifierValue !== false )
            $midText = str_pad( $identifierValue, $digits, '0', STR_PAD_LEFT );
        else
            $midText = str_repeat( 'x', $digits );

        $value = $preText . $midText . $postText;
        return $value;
    }

    function customClassAttributeHTTPAction( &$http, $action, &$contentClassAttribute )
    {
    }

    function preStoreClassAttribute( &$classAttribute, $version )
    {
    }

    function preStoreDefinedClassAttribute( &$classAttribute )
    {
    }

    var $IntegerValidator;
}

eZDataType::register( EZ_DATATYPESTRING_IDENTIFIER, "ezidentifiertype" );

?>
