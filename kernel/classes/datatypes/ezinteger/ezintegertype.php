<?php
//
// Definition of eZIntegerType class
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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
  \class eZIntegerType ezintegertype.php
  \ingroup eZDatatype
  \brief A content datatype which handles integers

  It provides the functionality to work as an integer and handles
  class definition input, object definition input and object viewing.

  It uses the spare field data_int in a content object attribute for storing
  the attribute data.
*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezutils/classes/ezintegervalidator.php" );

define( "EZ_DATATYPESTRING_INTEGER", "ezinteger" );
define( "EZ_DATATYPESTRING_MIN_VALUE_FIELD", "data_int1" );
define( "EZ_DATATYPESTRING_MIN_VALUE_VARIABLE", "_ezinteger_min_integer_value_" );
define( "EZ_DATATYPESTRING_MAX_VALUE_FIELD", "data_int2" );
define( "EZ_DATATYPESTRING_MAX_VALUE_VARIABLE", "_ezinteger_max_integer_value_" );
define( "EZ_DATATYPESTRING_DEFAULT_VALUE_FIELD", "data_int3" );
define( "EZ_DATATYPESTRING_DEFAULT_VALUE_VARIABLE", "_ezinteger_default_value_" );
define( "EZ_DATATYPESTRING_INTEGER_INPUT_STATE_FIELD", "data_int4" );
define( "EZ_INTEGER_NO_MIN_MAX_VALUE", 0 );
define( "EZ_INTEGER_HAS_MIN_VALUE", 1 );
define( "EZ_INTEGER_HAS_MAX_VALUE", 2 );
define( "EZ_INTEGER_HAS_MIN_MAX_VALUE", 3 );


class eZIntegerType extends eZDataType
{
    function eZIntegerType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_INTEGER, ezi18n( 'kernel/classes/datatypes', "Integer", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_int' => 'value' ) ) );
        $this->IntegerValidator = new eZIntegerValidator();
    }

    /*!
     Private method, only for using inside this class.
    */
    function validateIntegerHTTPInput( $data, &$contentObjectAttribute, &$classAttribute )
    {
        $min = $classAttribute->attribute( EZ_DATATYPESTRING_MIN_VALUE_FIELD );
        $max = $classAttribute->attribute( EZ_DATATYPESTRING_MAX_VALUE_FIELD );
        $input_state = $classAttribute->attribute( EZ_DATATYPESTRING_INTEGER_INPUT_STATE_FIELD );

        switch( $input_state )
        {
            case EZ_INTEGER_NO_MIN_MAX_VALUE:
            {
                $this->IntegerValidator->setRange( false, false );
                $state = $this->IntegerValidator->validate( $data );
                if( $state === EZ_INPUT_VALIDATOR_STATE_INVALID || $state === EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'The input is not a valid integer.' ) );
                else
                    return $state;
            } break;
            case EZ_INTEGER_HAS_MIN_VALUE:
            {
                $this->IntegerValidator->setRange( $min, false );
                $state = $this->IntegerValidator->validate( $data );
                if( $state === EZ_INPUT_VALIDATOR_STATE_ACCEPTED )
                    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
                else
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'The number must be greater than %1' ),
                                                                 $min );
            } break;
            case EZ_INTEGER_HAS_MAX_VALUE:
            {
                $this->IntegerValidator->setRange( false, $max );
                $state = $this->IntegerValidator->validate( $data );
                if( $state===1 )
                    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
                else
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'The number must be less than %1' ),
                                                                 $max );
            } break;
            case EZ_INTEGER_HAS_MIN_MAX_VALUE:
            {
                $this->IntegerValidator->setRange( $min, $max );
                $state = $this->IntegerValidator->validate( $data );
                if( $state===1 )
                    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
                else
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'The number is not within the required range %1 - %2' ),
                                                                 $min, $max );
            } break;
        }

        return EZ_INPUT_VALIDATOR_STATE_INVALID;

    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_integer_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_integer_" . $contentObjectAttribute->attribute( "id" ) );
            $data = str_replace(" ", "", $data );
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();

            if ( $data == "" )
            {
                if ( !$classAttribute->attribute( 'is_information_collector' ) and
                     $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Input required.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
                else
                    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else
            {
                return $this->validateIntegerHTTPInput( $data, $contentObjectAttribute, $classAttribute );
            }
        }
        else
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    function fixupObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
//             $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
//             $currentObjectAttribute = eZContentObjectAttribute::fetch( $contentObjectAttributeID,
//                                                                         $currentVersion );
            $dataInt = $originalContentObjectAttribute->attribute( "data_int" );
            $contentObjectAttribute->setAttribute( "data_int", $dataInt );
        }
        else
        {
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
            $default = $contentClassAttribute->attribute( "data_int3" );
            if ( $default !== 0 )
            {
                $contentObjectAttribute->setAttribute( "data_int", $default );
            }
        }
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_integer_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_integer_" . $contentObjectAttribute->attribute( "id" ) );
            $data = trim( $data ) != '' ? $data : null;
            $data = str_replace(" ", "", $data);
            $contentObjectAttribute->setAttribute( "data_int", $data );
            return true;
        }
        return false;
    }

    /*!
     \reimp
    */
    function validateCollectionAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_integer_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_integer_" . $contentObjectAttribute->attribute( "id" ) );
            $data = str_replace(" ", "", $data );
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();

            if ( $data == "" )
            {
                if ( $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Input required.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
                else
                    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else
            {
                return $this->validateIntegerHTTPInput( $data, $contentObjectAttribute, $classAttribute );
            }
        }
        else
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( &$collection, &$collectionAttribute, &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_integer_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_integer_" . $contentObjectAttribute->attribute( "id" ) );
            $data = trim( $data ) != '' ? $data : null;
            $data = str_replace(" ", "", $data);
            $collectionAttribute->setAttribute( "data_int", $data );
            return true;
        }
        return false;
    }

    /*!
     Does nothing, the data is already present in the attribute.
    */
    function storeObjectAttribute( &$object_attribute )
    {
    }

    function storeClassAttribute( &$attribute, $version )
    {
    }

    /*!
     \reimp
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $minValueName = $base . EZ_DATATYPESTRING_MIN_VALUE_VARIABLE . $classAttribute->attribute( "id" );
        $maxValueName = $base . EZ_DATATYPESTRING_MAX_VALUE_VARIABLE . $classAttribute->attribute( "id" );
        $defaultValueName = $base . EZ_DATATYPESTRING_DEFAULT_VALUE_VARIABLE . $classAttribute->attribute( "id" );

        if ( $http->hasPostVariable( $minValueName ) and
             $http->hasPostVariable( $maxValueName ) and
             $http->hasPostVariable( $defaultValueName ) )
        {
            $minValueValue = $http->postVariable( $minValueName );
            $minValueValue = str_replace(" ", "", $minValueValue );
            $maxValueValue = $http->postVariable( $maxValueName );
            $maxValueValue = str_replace(" ", "", $maxValueValue );
            $defaultValueValue = $http->postVariable( $defaultValueName );
            $defaultValueValue = str_replace(" ", "", $defaultValueValue );

            if ( ( $minValueValue == "" ) && ( $maxValueValue == "") ){
                return  EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else if ( ( $minValueValue == "" ) && ( $maxValueValue !== "") )
            {
                $max_state = $this->IntegerValidator->validate( $maxValueValue );
                return  $max_state;
            }
            else if ( ( $minValueValue !== "" ) && ( $maxValueValue == "") )
            {
                $min_state = $this->IntegerValidator->validate( $minValueValue );
                return  $min_state;
            }
            else
            {
                $min_state = $this->IntegerValidator->validate( $minValueValue );
                $max_state = $this->IntegerValidator->validate( $maxValueValue );
                if ( ( $min_state == EZ_INPUT_VALIDATOR_STATE_ACCEPTED ) and
                     ( $max_state == EZ_INPUT_VALIDATOR_STATE_ACCEPTED ) )
                {
                    if ($minValueValue <= $maxValueValue)
                        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
                    else
                    {
                        $state = EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE;
                        eZDebug::writeNotice( "Integer minimum value great than maximum value." );
                        return $state;
                    }
                }
            }

            if ($defaultValueValue == ""){
                $default_state =  EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else
                $default_state = $this->IntegerValidator->validate( $defaultValueValue );
        }

        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     \reimp
    */
    function fixupClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $minValueName = $base . EZ_DATATYPESTRING_MIN_VALUE_VARIABLE . $classAttribute->attribute( "id" );
        $maxValueName = $base . EZ_DATATYPESTRING_MAX_VALUE_VARIABLE . $classAttribute->attribute( "id" );
        if ( $http->hasPostVariable( $minValueName ) and $http->hasPostVariable( $maxValueName ) )
        {
            $minValueValue = $http->postVariable( $minValueName );
            $minValueValue = $this->IntegerValidator->fixup( $minValueValue );
            $http->setPostVariable( $minValueName, $minValueValue );

            $maxValueValue = $http->postVariable( $maxValueName );
            $maxValueValue = $this->IntegerValidator->fixup( $maxValueValue );
            $http->setPostVariable( $maxValueName, $maxValueValue );

            if ($minValueValue > $maxValueValue)
            {
                $this->IntegerValidator->setRange( $minValueValue, false );
                $maxValueValue = $this->IntegerValidator->fixup( $maxValueValue );
                $http->setPostVariable( $maxValueName, $maxValueValue );
            }
        }
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $minValueName = $base . EZ_DATATYPESTRING_MIN_VALUE_VARIABLE . $classAttribute->attribute( "id" );
        $maxValueName = $base . EZ_DATATYPESTRING_MAX_VALUE_VARIABLE . $classAttribute->attribute( "id" );
        $defaultValueName = $base . EZ_DATATYPESTRING_DEFAULT_VALUE_VARIABLE . $classAttribute->attribute( "id" );

        if ( $http->hasPostVariable( $minValueName ) and
             $http->hasPostVariable( $maxValueName ) and
             $http->hasPostVariable( $defaultValueName ) )
        {
            $minValueValue = $http->postVariable( $minValueName );
            $minValueValue = str_replace(" ", "", $minValueValue );
            $maxValueValue = $http->postVariable( $maxValueName );
            $maxValueValue = str_replace(" ", "", $maxValueValue );
            $defaultValueValue = $http->postVariable( $defaultValueName );
            $defaultValueValue = str_replace(" ", "", $defaultValueValue );

            $classAttribute->setAttribute( EZ_DATATYPESTRING_MIN_VALUE_FIELD, $minValueValue );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_MAX_VALUE_FIELD, $maxValueValue );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_DEFAULT_VALUE_FIELD, $defaultValueValue );

            if ( ( $minValueValue == "" ) && ( $maxValueValue == "") ){
                $input_state =  EZ_INTEGER_NO_MIN_MAX_VALUE;
                $classAttribute->setAttribute( EZ_DATATYPESTRING_INTEGER_INPUT_STATE_FIELD, $input_state );
            }
            else if ( ( $minValueValue == "" ) && ( $maxValueValue !== "") )
            {
                $input_state = EZ_INTEGER_HAS_MAX_VALUE;
                $classAttribute->setAttribute( EZ_DATATYPESTRING_INTEGER_INPUT_STATE_FIELD, $input_state );
            }
            else if ( ( $minValueValue !== "" ) && ( $maxValueValue == "") )
            {
                $input_state = EZ_INTEGER_HAS_MIN_VALUE;
                $classAttribute->setAttribute( EZ_DATATYPESTRING_INTEGER_INPUT_STATE_FIELD, $input_state );
            }
            else
            {
                $input_state = EZ_INTEGER_HAS_MIN_MAX_VALUE;
                $classAttribute->setAttribute( EZ_DATATYPESTRING_INTEGER_INPUT_STATE_FIELD, $input_state );
            }
            return true;
        }
        return false;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }


    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }
    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    function fromString( &$contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_int', $string );
    }

    /*!
     Returns the integer value.
    */
    function title( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return true;
    }

    /*!
     \reimp
    */
    function isInformationCollector()
    {
        return true;
    }

    /*!
     \return true if the datatype can be indexed
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     \reimp
    */
    function sortKey( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    /*!
     \reimp
    */
    function sortKeyType()
    {
        return 'int';
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( EZ_DATATYPESTRING_DEFAULT_VALUE_FIELD );
        $minValue = $classAttribute->attribute( EZ_DATATYPESTRING_MIN_VALUE_FIELD );
        $maxValue = $classAttribute->attribute( EZ_DATATYPESTRING_MAX_VALUE_FIELD );
        $minMaxState = $classAttribute->attribute( EZ_DATATYPESTRING_INTEGER_INPUT_STATE_FIELD );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'default-value', $defaultValue ) );
        if ( $minMaxState == EZ_INTEGER_HAS_MIN_VALUE or $minMaxState == EZ_INTEGER_HAS_MIN_MAX_VALUE )
            $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'min-value', $minValue ) );
        if ( $minMaxState == EZ_INTEGER_HAS_MAX_VALUE or $minMaxState == EZ_INTEGER_HAS_MIN_MAX_VALUE )
            $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'max-value', $maxValue ) );
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $defaultValue = $attributeParametersNode->elementTextContentByName( 'default-value' );
        $minValue = $attributeParametersNode->elementTextContentByName( 'min-value' );
        $maxValue = $attributeParametersNode->elementTextContentByName( 'max-value' );

        if ( strlen( $minValue ) > 0 and strlen( $maxValue ) > 0 )
            $minMaxState = EZ_INTEGER_HAS_MIN_MAX_VALUE;
        else if ( strlen( $minValue ) > 0 )
            $minMaxState = EZ_INTEGER_HAS_MIN_VALUE;
        else if ( strlen( $maxValue ) > 0 )
            $minMaxState = EZ_INTEGER_HAS_MAX_VALUE;
        else
            $minMaxState = EZ_INTEGER_NO_MIN_MAX_VALUE;

        $classAttribute->setAttribute( EZ_DATATYPESTRING_DEFAULT_VALUE_FIELD, $defaultValue );
        $classAttribute->setAttribute( EZ_DATATYPESTRING_MIN_VALUE_FIELD, $minValue );
        $classAttribute->setAttribute( EZ_DATATYPESTRING_MAX_VALUE_FIELD, $maxValue );
        $classAttribute->setAttribute( EZ_DATATYPESTRING_INTEGER_INPUT_STATE_FIELD, $minMaxState );
    }

    /// \privatesection
    /// The integer value validator
    var $IntegerValidator;
}

eZDataType::register( EZ_DATATYPESTRING_INTEGER, "ezintegertype" );

?>
