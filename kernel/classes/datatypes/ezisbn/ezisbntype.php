<?php
//
// Definition of eZISBNType class
//
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
  \class eZISBNType ezisbntype.php
  \brief Handles ISBN type strings
  \ingroup eZDatatype

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
        $isbn = $field1 . '-' . $field2 . '-' . $field3 . '-' . $field4;

        $isbn = strtoupper( $isbn );
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        if ( !$contentObjectAttribute->validateIsRequired() and $isbn == "---" )
        {
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        }

        if ( preg_match( "#^[0-9]{1,2}\-[0-9]+\-[0-9]+\-[0-9X]{1}$#", $isbn ) )
        {
            $digits = str_replace( "-", "", $isbn );
            $valid = $this->validateISBNChecksum ( $digits );
            if ( $valid )
            {
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'The ISBN number is not correct. Please check the input for mistakes.' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
        }
        else
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'The ISBN number is not correct. Please check the input for mistakes.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     \private
     Validates the ISBN number \a $isbnNr.
     \param $isbnNr A string containing the number without any dashes.
     \return \c true if it is valid.
    */
    function validateISBNChecksum ( $isbnNr )
    {
        $result = 0;
        for ( $i = 10; $i > 0; $i-- )
        {
            if ( ( $i == 1 ) and ( $isbnNr{9} == 'X' ) )
            {
                $result += 10 * $i;
            }
            else
            {
                $result += $isbnNr{10-$i} * $i;
            }
        }
        return ( $result % 11 == 0 );
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
        $isbn = $field1 . '-' . $field2 . '-' . $field3 . '-' . $field4;
        $isbn = strtoupper( $isbn );
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
        // The array_merge makes sure missing elements gets an empty string instead of NULL
        list ( $field1, $field2, $field3, $field4 ) = array_merge( preg_split( '#-#', $data ),
                                                                   array( 0 => '', 1 => '', 2 => '', 3 => '' ) );
        $isbn = array( "field1" => $field1, "field2" => $field2,
                       "field3" => $field3, "field4" => $field4 );
        return $isbn;
    }

    /*!
     \reimp
     ISBN numbers are indexable, returns \c true.
    */
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
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function fromString( &$contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_text', $string );
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
