<?php
//
// Definition of eZISBNType class
//
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

/*!
  \class eZISBNType ezusertype.php
  \brief The class eZISBNType
  \ingroup eZKernel

*/

include_once( "kernel/classes/ezdatatype.php" );

define( "EZ_DATATYPESTRING_ISBN", "ezisbn" );

class eZISBNType extends eZDataType
{
    function eZISBNType( )
    {
        $this->eZDataType( EZ_DATATYPESTRING_ISBN, ezi18n( 'kernel/classes/datatypes', "ISBN", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_text' => 'isbn' ) ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $field1 = $http->postVariable( $base . "_isbn_field1_" . $contentObjectAttribute->attribute( "id" ) );
        $field2 = $http->postVariable( $base . "_isbn_field2_" . $contentObjectAttribute->attribute( "id" ) );
        $field3 = $http->postVariable( $base . "_isbn_field3_" . $contentObjectAttribute->attribute( "id" ) );
        $field4 = $http->postVariable( $base . "_isbn_field4_" . $contentObjectAttribute->attribute( "id" ) );
        $isbn = $field1.'-'.$field2.'-'.$field3.'-'.$field4;
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        if( ( $classAttribute->attribute( "is_required" ) == false ) &&  ( $isbn == "---" ) )
        {
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        }
        if ( preg_match( "#^[0-9]{1,2}\-[0-9]+\-[0-9]+\-[0-9X]{1}$#", $isbn ) )
        {
            $digits = str_replace("-", "", $isbn );
            $valid = $this->validateISBNChecksum ( $digits );
            if ( $valid )
            {
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }else
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'The ISBN number is not correct. Please recheck the input' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
        }
        else
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'The ISBN format is not valid.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    function validateISBNChecksum ( $isbnNr )
    {
        $result=0;
        for ( $i = 10; $i > 0; $i-- )
        {
            if ( ( $i == 1 ) and ( $isbnNr{9} == 'X' ) )
                $result += 10 * $i;
            else
                $result += $isbnNr{10-$i} * $i;
        }
        if (  $result % 11 == 0 )
            return true;
        else
            return false;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $field1 = $http->postVariable( $base . "_isbn_field1_" . $contentObjectAttribute->attribute( "id" ) );
        $field2 = $http->postVariable( $base . "_isbn_field2_" . $contentObjectAttribute->attribute( "id" ) );
        $field3 = $http->postVariable( $base . "_isbn_field3_" . $contentObjectAttribute->attribute( "id" ) );
        $field4 = $http->postVariable( $base . "_isbn_field4_" . $contentObjectAttribute->attribute( "id" ) );
        $isbn = $field1.'-'.$field2.'-'.$field3.'-'.$field4;
        $contentObjectAttribute->setAttribute( "data_text", $isbn );
        return true;
    }

    /*!
     Store the content.
    */
    function storeObjectAttribute( &$attribute )
    {
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $data = $contentObjectAttribute->attribute( "data_text" );
        list ( $field1, $field2, $field3, $field4 ) = split ('[-]', $data );
        $isbn = array( "field1" => $field1, "field2" => $field2, "field3" => $field3,
                       "field4" => $field4 );
        return $isbn;
    }

    function isIndexable()
    {
        return true;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     Returns the text.
    */
    function title( &$data_instance )
    {
        return $data_instance->attribute( "data_text" );
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return trim( $contentObjectAttribute->attribute( "data_text" ) ) != '';
    }
}

eZDataType::register( EZ_DATATYPESTRING_ISBN, "ezisbntype" );

?>
