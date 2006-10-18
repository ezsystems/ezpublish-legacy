<?php
//
// Definition of eZISBNType class
//
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        $classContent = $classAttribute->content();
        if ( isset( $classContent['ISBN13'] ) and $classContent['ISBN13'] )
        {
            $number13 = $http->hasPostVariable( $base . "_isbn_13_" . $contentObjectAttribute->attribute( "id" ) )
                        ? $http->postVariable( $base . "_isbn_13_" . $contentObjectAttribute->attribute( "id" ) )
                        : false;
            if ( !$contentObjectAttribute->validateIsRequired() and ( !$number13 or $number13 == '' ) )
            {
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            $number13 = str_replace( "-", "", $number13 );
            $number13 = str_replace( " ", "", $number13 );
            $error = '';
            $valid = $this->validateISBN13Checksum ( $number13, $error );

            if ( $valid )
            {
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'The ISBN number is not correct. ' ) . $error );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }

            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }

        $field1 = $http->postVariable( $base . "_isbn_field1_" . $contentObjectAttribute->attribute( "id" ) );
        $field2 = $http->postVariable( $base . "_isbn_field2_" . $contentObjectAttribute->attribute( "id" ) );
        $field3 = $http->postVariable( $base . "_isbn_field3_" . $contentObjectAttribute->attribute( "id" ) );
        $field4 = $http->postVariable( $base . "_isbn_field4_" . $contentObjectAttribute->attribute( "id" ) );
        $isbn = $field1 . '-' . $field2 . '-' . $field3 . '-' . $field4;

        $isbn = strtoupper( $isbn );

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
     \private
     Validates the ISBN-13 number \a $isbnNr.
     \param $isbnNr A string containing the number without any dashes.
     \return \c true if it is valid.
    */
    function validateISBN13Checksum ( $isbnNr, &$error )
    {
        if ( !$isbnNr )
            return false;

        if ( substr( $isbnNr, 0, 3 ) != '978' && substr( $isbnNr, 0, 3 ) != '979' )
        {
            $error = ezi18n( 'kernel/classes/datatypes',
                             '13 digit ISBN must start with 978 or 979' );
            return false;
        }
        $isbnNr = strtoupper( $isbnNr );
        $checksum13 = 0;
        $weight13 = 1;
        if ( strlen( $isbnNr ) != 13 )
        {
            $error = ezi18n( 'kernel/classes/datatypes', 'ISBN length is invalid' );
            return false;
        }

        //compute checksum
        $val = 0;
        for ( $i = 0; $i < 13; $i++ )
        {
            $val = $isbnNr{$i};
            if ( $isbnNr{$i} == 'X' )
            {
                $error = ezi18n( 'kernel/classes/datatypes', 'X not valid in ISBN 13' );
                return false;
            }
            $checksum13 = $checksum13 + $weight13 * $val;
            $weight13 = ( $weight13 + 2 ) % 4;
        }
        if ( ( $checksum13 % 10 ) != 0 )
        {
            //bad checksum
            $error = ezi18n( 'kernel/classes/datatypes', 'Bad checksum' );
            return false;
        }

        return true;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        $classContent = $classAttribute->content();
        if ( isset( $classContent['ISBN13'] ) and $classContent['ISBN13'] )
        {
            $number13 = $http->hasPostVariable( $base . "_isbn_13_" . $contentObjectAttribute->attribute( "id" ) )
                        ? $http->postVariable( $base . "_isbn_13_" . $contentObjectAttribute->attribute( "id" ) )
                        : false;
            if ( !$number13 )
                return true;

            $isbn = strtoupper( $number13 );
            $isbn = preg_replace( "# +#", " ", $isbn );
            $isbn = preg_replace( "#-+#", "-", $isbn );
            $contentObjectAttribute->setAttribute( "data_text", $isbn );
            return true;
        }

        $field1 = $http->postVariable( $base . "_isbn_field1_" . $contentObjectAttribute->attribute( "id" ) );
        $field2 = $http->postVariable( $base . "_isbn_field2_" . $contentObjectAttribute->attribute( "id" ) );
        $field3 = $http->postVariable( $base . "_isbn_field3_" . $contentObjectAttribute->attribute( "id" ) );
        $field4 = $http->postVariable( $base . "_isbn_field4_" . $contentObjectAttribute->attribute( "id" ) );
        // If $fields are empty if should not store empty content to db.
        if ( !$field1 and !$field2 and !$field3 and !$field4 )
            return true;

        $isbn = $field1 . '-' . $field2 . '-' . $field3 . '-' . $field4;
        $isbn = strtoupper( $isbn );
        $contentObjectAttribute->setAttribute( "data_text", $isbn );
        return true;
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $classAttributeID = $classAttribute->attribute( 'id' );
        $content = $classAttribute->content();

        if ( $http->hasPostVariable( $base . '_ezisbn_13_value_' . $classAttributeID . '_exists' ) )
        {
             $content['ISBN13'] = $http->hasPostVariable( $base . '_ezisbn_13_value_' . $classAttributeID ) ? 1 : 0;
        }
        $classAttribute->setContent( $content );
        $classAttribute->store();
        return true;
    }

    /*!
     Store the content.
    */
    function storeObjectAttribute( &$attribute )
    {
    }

    /*!
     \reimp
    */
    function preStoreClassAttribute( &$classAttribute, $version )
    {
        $content = $classAttribute->content();
        return eZISBNType::storeClassAttributeContent( $classAttribute, $content );
    }

    function storeClassAttributeContent( &$classAttribute, $content )
    {
        if ( is_array( $content ) )
        {
            $ISBN_13 = $content['ISBN13'];
            $classAttribute->setAttribute( 'data_int1', $ISBN_13 );
        }
        return false;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $data = $contentObjectAttribute->attribute( "data_text" );
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        $classContent = $classAttribute->content();
        if ( isset( $classContent['ISBN13'] ) and $classContent['ISBN13'] )
        {
            return $data;
        }

        // The array_merge makes sure missing elements gets an empty string instead of NULL
        list ( $field1, $field2, $field3, $field4 ) = array_merge( preg_split( '#-#', $data ),
                                                                   array( 0 => '', 1 => '', 2 => '', 3 => '' ) );
        $isbn = array( "field1" => $field1, "field2" => $field2,
                       "field3" => $field3, "field4" => $field4 );
        return $isbn;
    }

    /*!
     \reimp
    */
    function &classAttributeContent( &$classAttribute )
    {
        $ISBN_13 = $classAttribute->attribute( 'data_int1' );
        $content = array( 'ISBN13' => $ISBN_13 );
        return $content;
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
