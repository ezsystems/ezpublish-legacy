<?php
//
// Definition of eZFloatType class
//
// Created on: <26-Apr-2002 16:54:35 bf>
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

//!! eZKernel
//! The class eZFloatType
/*!

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezutils/classes/ezfloatvalidator.php" );

define( "EZ_DATATYPESTRING_FLOAT", "ezfloat" );
define( "EZ_DATATYPESTRING_MIN_FLOAT_FIELD", "data_float1" );
define( "EZ_DATATYPESTRING_MIN_FLOAT_VARIABLE", "_ezfloat_min_float_value_" );
define( "EZ_DATATYPESTRING_MAX_FLOAT_FIELD", "data_float2" );
define( "EZ_DATATYPESTRING_MAX_FLOAT_VARIABLE", "_ezfloat_max_float_value_" );
define( "EZ_DATATYPESTRING_DEFAULT_FLOAT_FIELD", "data_float3" );
define( "EZ_DATATYPESTRING_DEFAULT_FLOAT_VARIABLE", "_ezfloat_default_value_" );
define( "EZ_DATATYPESTRING_FLOAT_INPUT_STATE_FIELD", "data_float4" );
define( "EZ_FLOAT_NO_MIN_MAX_VALUE", 0 );
define( "EZ_FLOAT_HAS_MIN_VALUE", 1 );
define( "EZ_FLOAT_HAS_MAX_VALUE", 2 );
define( "EZ_FLOAT_HAS_MIN_MAX_VALUE", 3 );

class eZFloatType extends eZDataType
{
    function eZFloatType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_FLOAT, ezi18n( 'kernel/classes/datatypes', "Float", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_float' => 'value' ) ) );
        $this->FloatValidator = new eZFloatValidator();
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
//             $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
//             $currentObjectAttribute =& eZContentObjectAttribute::fetch( $contentObjectAttributeID,
//                                                                         $currentVersion );
            $dataFloat = $originalContentObjectAttribute->attribute( "data_float" );
            $contentObjectAttribute->setAttribute( "data_float", $dataFloat );
        }
        else
        {
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
            $default = $contentClassAttribute->attribute( "data_float3" );
            if ( $default !== 0 )
            {
                $contentObjectAttribute->setAttribute( "data_float", $default );
            }
        }
    }

    /*!
     Fetches the http post var float input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_float_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_float_" . $contentObjectAttribute->attribute( "id" ) );
            $contentObjectAttribute->setHTTPValue( $data );

            include_once( 'lib/ezlocale/classes/ezlocale.php' );
            $locale =& eZLocale::instance();
            $data =& $locale->internalNumber( $data );

            $contentObjectAttribute->setAttribute( "data_float", $data );
            return true;
        }
        return false;
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_float_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_float_" . $contentObjectAttribute->attribute( "id" ) );
            $data = str_replace(" ", "", $data );
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();
            $min = $classAttribute->attribute( EZ_DATATYPESTRING_MIN_FLOAT_FIELD );
            $max = $classAttribute->attribute( EZ_DATATYPESTRING_MAX_FLOAT_FIELD );
            $input_state = $classAttribute->attribute( EZ_DATATYPESTRING_FLOAT_INPUT_STATE_FIELD );
            if( ( $classAttribute->attribute( "is_required" ) == false ) &&  ( $data == "" ) )
            {
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }

            include_once( 'lib/ezlocale/classes/ezlocale.php' );
            $locale =& eZLocale::instance();
            $data =& $locale->internalNumber( $data );

            switch( $input_state )
            {
                case EZ_FLOAT_NO_MIN_MAX_VALUE:
                {
                    $state = $this->FloatValidator->validate( $data );
                    if( $state===1 )
                        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
                    else
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'Input is not float.' ) );
                } break;
                case EZ_FLOAT_HAS_MIN_VALUE:
                {
                    $this->FloatValidator->setRange( $min, false );
                    $state = $this->FloatValidator->validate( $data );
                    if( $state===1 )
                        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
                    else
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'Input must be greater than %1' ),
                                                                     $min );
                } break;
                case EZ_FLOAT_HAS_MAX_VALUE:
                {
                    $this->FloatValidator->setRange( false, $max );
                    $state = $this->FloatValidator->validate( $data );
                    if( $state===1 )
                        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
                    else
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'Input must be less than %1' ),
                                                                     $max );
                } break;
                case EZ_FLOAT_HAS_MIN_MAX_VALUE:
                {
                    $this->FloatValidator->setRange( $min, $max );
                    $state = $this->FloatValidator->validate( $data );
                    if( $state===1 )
                        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
                    else
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'Input is not in defined range %1 - %2' ),
                                                                     $min, $max );
                } break;
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    function fixupObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
    }

    function storeObjectAttribute( &$attribute )
    {
    }

    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
	{
		$minValueName = $base . EZ_DATATYPESTRING_MIN_FLOAT_VARIABLE . $classAttribute->attribute( "id" );
		$maxValueName = $base . EZ_DATATYPESTRING_MAX_FLOAT_VARIABLE . $classAttribute->attribute( "id" );
        $defaultValueName =  $base . EZ_DATATYPESTRING_DEFAULT_FLOAT_VARIABLE . $classAttribute->attribute( "id" );

        if ( $http->hasPostVariable( $minValueName ) and
             $http->hasPostVariable( $maxValueName ) and
             $http->hasPostVariable( $defaultValueName ) )
		{
            include_once( 'lib/ezlocale/classes/ezlocale.php' );
            $locale =& eZLocale::instance();

            $minValueValue = $http->postVariable( $minValueName );
            $minValueValue = str_replace(" ", "", $minValueValue );
            $minValueValue = $locale->internalNumber( $minValueValue );
            $maxValueValue = $http->postVariable( $maxValueName );
            $maxValueValue = str_replace(" ", "", $maxValueValue );
            $maxValueValue = $locale->internalNumber( $maxValueValue );
            $defaultValueValue = $http->postVariable( $defaultValueName );
            $defaultValueValue = str_replace(" ", "", $defaultValueValue );
            $defaultValueValue = $locale->internalNumber( $defaultValueValue );

            $classAttribute->setAttribute( EZ_DATATYPESTRING_MIN_FLOAT_FIELD, $minValueValue );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_MAX_FLOAT_FIELD, $maxValueValue );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_DEFAULT_FLOAT_FIELD, $defaultValueValue );

            if ( ( $minValueValue == "" ) && ( $maxValueValue == "") ){
                $input_state =  EZ_FLOAT_NO_MIN_MAX_VALUE;
                $classAttribute->setAttribute( EZ_DATATYPESTRING_FLOAT_INPUT_STATE_FIELD, $input_state );
            }
            else if ( ( $minValueValue == "" ) && ( $maxValueValue !== "") )
            {
                $input_state = EZ_FLOAT_HAS_MAX_VALUE;
                $classAttribute->setAttribute( EZ_DATATYPESTRING_FLOAT_INPUT_STATE_FIELD, $input_state );
            }
            else if ( ( $minValueValue !== "" ) && ( $maxValueValue == "") )
            {
                $input_state = EZ_FLOAT_HAS_MIN_VALUE;
                $classAttribute->setAttribute( EZ_DATATYPESTRING_FLOAT_INPUT_STATE_FIELD, $input_state );
            }
            else
            {
                $input_state = EZ_FLOAT_HAS_MIN_MAX_VALUE;
                $classAttribute->setAttribute( EZ_DATATYPESTRING_FLOAT_INPUT_STATE_FIELD, $input_state );
            }
            return true;
		}
        return false;
	}

    function validateClassAttributeHTTPInput( &$http, $base, &$classAttribute )
	{
		$minValueName = $base . EZ_DATATYPESTRING_MIN_FLOAT_VARIABLE . $classAttribute->attribute( "id" );
		$maxValueName = $base . EZ_DATATYPESTRING_MAX_FLOAT_VARIABLE . $classAttribute->attribute( "id" );
        $defaultValueName =  $base . EZ_DATATYPESTRING_DEFAULT_FLOAT_VARIABLE . $classAttribute->attribute( "id" );

        if ( $http->hasPostVariable( $minValueName ) and
             $http->hasPostVariable( $maxValueName ) and
             $http->hasPostVariable( $defaultValueName ) )
		{
            include_once( 'lib/ezlocale/classes/ezlocale.php' );
            $locale =& eZLocale::instance();

            $minValueValue = $http->postVariable( $minValueName );
            $minValueValue = str_replace(" ", "", $minValueValue );
            $minValueValue = $locale->internalNumber( $minValueValue );
            $maxValueValue = $http->postVariable( $maxValueName );
            $maxValueValue = str_replace(" ", "", $maxValueValue );
            $maxValueValue = $locale->internalNumber( $maxValueValue );
            $defaultValueValue = $http->postVariable( $defaultValueName );
            $defaultValueValue = str_replace(" ", "", $defaultValueValue );
            $defaultValueValue = $locale->internalNumber( $defaultValueValue );

            if ( ( $minValueValue == "" ) && ( $maxValueValue == "") ){
                return  EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else if ( ( $minValueValue == "" ) && ( $maxValueValue !== "") )
            {
                $max_state = $this->FloatValidator->validate( $maxValueValue );
                return  $max_state;
            }
            else if ( ( $minValueValue !== "" ) && ( $maxValueValue == "") )
            {
                $min_state = $this->FloatValidator->validate( $minValueValue );
                return  $min_state;
            }
            else
            {
                $min_state = $this->FloatValidator->validate( $minValueValue );
                $max_state = $this->FloatValidator->validate( $maxValueValue );
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
                $default_state = $this->FloatValidator->validate( $defaultValueValue );
		}
		return EZ_INPUT_VALIDATOR_STATE_INVALID;
	}

    function fixupClassAttributeHTTPInput( &$http, $base, &$classAttribute )
	{
		$minValueName = $base . EZ_DATATYPESTRING_MIN_FLOAT_VARIABLE . $classAttribute->attribute( "id" );
		$maxValueName = $base . EZ_DATATYPESTRING_MAX_FLOAT_VARIABLE . $classAttribute->attribute( "id" );
		if ( $http->hasPostVariable( $minValueName ) and $http->hasPostVariable( $maxValueName ) )
		{
            include_once( 'lib/ezlocale/classes/ezlocale.php' );
            $locale =& eZLocale::instance();

            $minValueValue = $http->postVariable( $minValueName );
            $minValueValue = str_replace(" ", "", $minValueValue );
            $minValueValue = $locale->internalNumber( $minValueValue );
            $maxValueValue = $http->postVariable( $maxValueName );
            $maxValueValue = str_replace(" ", "", $maxValueValue );
            $maxValueValue = $locale->internalNumber( $maxValueValue );

            if ($minValueValue > $maxValueValue)
			{
                $this->FloatValidator->setRange( $minValueValue, false );
                $maxValueValue = $this->FloatValidator->fixup( $maxValueValue );
				$http->setPostVariable( $maxValueName, $maxValueValue );
			}
		}
	}

    function storeClassAttribute( &$attribute, $version )
    {
    }

    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_float" );
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        return  $contentObjectAttribute->attribute( 'data_float' );
    }

    /*!
     Returns the float value.
    */

    function title( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_float" );
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return true;
    }

    /*!
     \reimp
    */
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( EZ_DATATYPESTRING_DEFAULT_FLOAT_FIELD );
        $minValue = $classAttribute->attribute( EZ_DATATYPESTRING_MIN_FLOAT_FIELD );
        $maxValue = $classAttribute->attribute( EZ_DATATYPESTRING_MAX_FLOAT_FIELD );
        $minMaxState = $classAttribute->attribute( EZ_DATATYPESTRING_FLOAT_INPUT_STATE_FIELD );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'default-value', $defaultValue ) );
        if ( $minMaxState == EZ_FLOAT_HAS_MIN_VALUE or $minMaxState == EZ_FLOAT_HAS_MIN_MAX_VALUE )
            $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'min-value', $minValue ) );
        if ( $minMaxState == EZ_FLOAT_HAS_MAX_VALUE or $minMaxState == EZ_FLOAT_HAS_MIN_MAX_VALUE )
            $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'max-value', $maxValue ) );
    }

    /*!
     \reimp
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $defaultValue = $attributeParametersNode->elementTextContentByName( 'default-value' );
        $minValue = $attributeParametersNode->elementTextContentByName( 'min-value' );
        $maxValue = $attributeParametersNode->elementTextContentByName( 'max-value' );

        if ( strlen( $minValue ) > 0 and strlen( $maxValue ) > 0 )
            $minMaxState = EZ_FLOAT_HAS_MIN_MAX_VALUE;
        else if ( strlen( $minValue ) > 0 )
            $minMaxState = EZ_FLOAT_HAS_MIN_VALUE;
        else if ( strlen( $maxValue ) > 0 )
            $minMaxState = EZ_FLOAT_HAS_MAX_VALUE;
        else
            $minMaxState = EZ_FLOAT_NO_MIN_MAX_VALUE;

        $classAttribute->setAttribute( EZ_DATATYPESTRING_DEFAULT_FLOAT_FIELD, $defaultValue );
        $classAttribute->setAttribute( EZ_DATATYPESTRING_MIN_FLOAT_FIELD, $minValue );
        $classAttribute->setAttribute( EZ_DATATYPESTRING_MAX_FLOAT_FIELD, $maxValue );
        $classAttribute->setAttribute( EZ_DATATYPESTRING_FLOAT_INPUT_STATE_FIELD, $minMaxState );
    }

    /// \privatesection
    /// The float value validator
    var $FloatValidator;
}

eZDataType::register( EZ_DATATYPESTRING_FLOAT, "ezfloattype" );

?>
