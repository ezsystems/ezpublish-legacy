<?php
//
// Definition of eZFloatType class
//
// Created on: <26-Apr-2002 16:54:35 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
  \class eZFloatType ezfloattype.php
  \ingroup eZDatatype
  \brief Stores a float value

*/

class eZFloatType extends eZDataType
{
    const DATA_TYPE_STRING = "ezfloat";
    const MIN_FIELD = "data_float1";
    const MIN_VARIABLE = "_ezfloat_min_float_value_";
    const MAX_FIELD = "data_float2";
    const MAX_VARIABLE = "_ezfloat_max_float_value_";
    const DEFAULT_FIELD = "data_float3";
    const DEFAULT_VARIABLE = "_ezfloat_default_value_";
    const INPUT_STATE_FIELD = "data_float4";
    const NO_MIN_MAX_VALUE = 0;
    const HAS_MIN_VALUE = 1;
    const HAS_MAX_VALUE = 2;
    const HAS_MIN_MAX_VALUE = 3;

    function eZFloatType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Float", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_float' => 'value' ) ) );
        $this->FloatValidator = new eZFloatValidator();
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
//             $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
//             $currentObjectAttribute = eZContentObjectAttribute::fetch( $contentObjectAttributeID,
//                                                                         $currentVersion );
            $dataFloat = $originalContentObjectAttribute->attribute( "data_float" );
            $contentObjectAttribute->setAttribute( "data_float", $dataFloat );
        }
        else
        {
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
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
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_float_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_float_" . $contentObjectAttribute->attribute( "id" ) );
            $contentObjectAttribute->setHTTPValue( $data );

            $locale = eZLocale::instance();
            $data = $locale->internalNumber( $data );

            $data = str_replace(" ", "", $data);

            $contentObjectAttribute->setAttribute( "data_float", $data );
            return true;
        }
        return false;
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_float_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_float_" . $contentObjectAttribute->attribute( "id" ) );
            $data = str_replace(" ", "", $data );
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            $min = $classAttribute->attribute( self::MIN_FIELD );
            $max = $classAttribute->attribute( self::MAX_FIELD );
            $input_state = $classAttribute->attribute( self::INPUT_STATE_FIELD );

            if ( !$contentObjectAttribute->validateIsRequired() &&  ( $data == "" ) )
            {
                return eZInputValidator::STATE_ACCEPTED;
            }

            $locale = eZLocale::instance();
            $data = $locale->internalNumber( $data );

            switch( $input_state )
            {
                case self::NO_MIN_MAX_VALUE:
                {
                    $state = $this->FloatValidator->validate( $data );
                    if( $state===1 )
                        return eZInputValidator::STATE_ACCEPTED;
                    else
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'The given input is not a floating point number.' ) );
                } break;
                case self::HAS_MIN_VALUE:
                {
                    $this->FloatValidator->setRange( $min, false );
                    $state = $this->FloatValidator->validate( $data );
                    if( $state===1 )
                        return eZInputValidator::STATE_ACCEPTED;
                    else
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'The input must be greater than %1' ),
                                                                     $min );
                } break;
                case self::HAS_MAX_VALUE:
                {
                    $this->FloatValidator->setRange( false, $max );
                    $state = $this->FloatValidator->validate( $data );
                    if( $state===1 )
                        return eZInputValidator::STATE_ACCEPTED;
                    else
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'The input must be less than %1' ),
                                                                     $max );
                } break;
                case self::HAS_MIN_MAX_VALUE:
                {
                    $this->FloatValidator->setRange( $min, $max );
                    $state = $this->FloatValidator->validate( $data );
                    if( $state===1 )
                        return eZInputValidator::STATE_ACCEPTED;
                    else
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'The input is not in defined range %1 - %2' ),
                                                                     $min, $max );
                } break;
            }
        }
        return eZInputValidator::STATE_INVALID;
    }

    function fixupObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
    }

    function storeObjectAttribute( $attribute )
    {
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $minValueName = $base . self::MIN_VARIABLE . $classAttribute->attribute( "id" );
        $maxValueName = $base . self::MAX_VARIABLE . $classAttribute->attribute( "id" );
        $defaultValueName =  $base . self::DEFAULT_VARIABLE . $classAttribute->attribute( "id" );

        if ( $http->hasPostVariable( $minValueName ) and
             $http->hasPostVariable( $maxValueName ) and
             $http->hasPostVariable( $defaultValueName ) )
        {
            $locale = eZLocale::instance();

            $minValueValue = $http->postVariable( $minValueName );
            $minValueValue = str_replace(" ", "", $minValueValue );
            $minValueValue = $locale->internalNumber( $minValueValue );
            $maxValueValue = $http->postVariable( $maxValueName );
            $maxValueValue = str_replace(" ", "", $maxValueValue );
            $maxValueValue = $locale->internalNumber( $maxValueValue );
            $defaultValueValue = $http->postVariable( $defaultValueName );
            $defaultValueValue = str_replace(" ", "", $defaultValueValue );
            $defaultValueValue = $locale->internalNumber( $defaultValueValue );

            $classAttribute->setAttribute( self::MIN_FIELD, $minValueValue );
            $classAttribute->setAttribute( self::MAX_FIELD, $maxValueValue );
            $classAttribute->setAttribute( self::DEFAULT_FIELD, $defaultValueValue );

            if ( ( $minValueValue == "" ) && ( $maxValueValue == "") ){
                $input_state = self::NO_MIN_MAX_VALUE;
                $classAttribute->setAttribute( self::INPUT_STATE_FIELD, $input_state );
            }
            else if ( ( $minValueValue == "" ) && ( $maxValueValue !== "") )
            {
                $input_state = self::HAS_MAX_VALUE;
                $classAttribute->setAttribute( self::INPUT_STATE_FIELD, $input_state );
            }
            else if ( ( $minValueValue !== "" ) && ( $maxValueValue == "") )
            {
                $input_state = self::HAS_MIN_VALUE;
                $classAttribute->setAttribute( self::INPUT_STATE_FIELD, $input_state );
            }
            else
            {
                $input_state = self::HAS_MIN_MAX_VALUE;
                $classAttribute->setAttribute( self::INPUT_STATE_FIELD, $input_state );
            }
            return true;
        }
        return false;
    }

    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $minValueName = $base . self::MIN_VARIABLE . $classAttribute->attribute( "id" );
        $maxValueName = $base . self::MAX_VARIABLE . $classAttribute->attribute( "id" );
        $defaultValueName =  $base . self::DEFAULT_VARIABLE . $classAttribute->attribute( "id" );

        if ( $http->hasPostVariable( $minValueName ) and
             $http->hasPostVariable( $maxValueName ) and
             $http->hasPostVariable( $defaultValueName ) )
        {
            $locale = eZLocale::instance();

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
                return  eZInputValidator::STATE_ACCEPTED;
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
                if ( ( $min_state == eZInputValidator::STATE_ACCEPTED ) and
                     ( $max_state == eZInputValidator::STATE_ACCEPTED ) )
                {
                    if ($minValueValue <= $maxValueValue)
                        return eZInputValidator::STATE_ACCEPTED;
                    else
                    {
                        $state = eZInputValidator::STATE_INTERMEDIATE;
                        eZDebug::writeNotice( "Integer minimum value great than maximum value." );
                        return $state;
                    }
                }
            }

            if ($defaultValueValue == ""){
                $default_state =  eZInputValidator::STATE_ACCEPTED;
            }
            else
                $default_state = $this->FloatValidator->validate( $defaultValueValue );
        }
        return eZInputValidator::STATE_INVALID;
    }

    function fixupClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $minValueName = $base . self::MIN_VARIABLE . $classAttribute->attribute( "id" );
        $maxValueName = $base . self::MAX_VARIABLE . $classAttribute->attribute( "id" );
        if ( $http->hasPostVariable( $minValueName ) and $http->hasPostVariable( $maxValueName ) )
        {
            $locale = eZLocale::instance();

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

    function storeClassAttribute( $attribute, $version )
    {
    }

    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_float" );
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_float' );
    }

    /*!
     Returns the float value.
    */

    function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute( "data_float" );
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return !is_null( $contentObjectAttribute->attribute( 'data_float' ) );
    }
    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_float' );
    }

    function fromString( $contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_float', $string );
    }

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( self::DEFAULT_FIELD );
        $minValue = $classAttribute->attribute( self::MIN_FIELD );
        $maxValue = $classAttribute->attribute( self::MAX_FIELD );
        $minMaxState = $classAttribute->attribute( self::INPUT_STATE_FIELD );

        $dom = $attributeParametersNode->ownerDocument;
        $defaultValueNode = $dom->createElement( 'default-value' );
        $defaultValueNode->appendChild( $dom->createTextNode( $defaultValue ) );
        $attributeParametersNode->appendChild( $defaultValueNode );
        if ( $minMaxState == self::HAS_MIN_VALUE or $minMaxState == self::HAS_MIN_MAX_VALUE )
        {
            $minValueNode = $dom->createElement( 'min-value' );
            $minValueNode->appendChild( $dom->createTextNode( $minValue ) );
            $attributeParametersNode->appendChild( $minValueNode );
        }
        if ( $minMaxState == self::HAS_MAX_VALUE or $minMaxState == self::HAS_MIN_MAX_VALUE )
        {
            $maxValueNode = $dom->createElement( 'max-value' );
            $maxValueNode->appendChild( $dom->createTextNode( $maxValue ) );
            $attributeParametersNode->appendChild( $maxValueNode );
        }
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $defaultValue = $attributeParametersNode->getElementsByTagName( 'default-value' )->item( 0 )->textContent;
        $minValue = $attributeParametersNode->getElementsByTagName( 'min-value' )->item( 0 )->textContent;
        $maxValue = $attributeParametersNode->getElementsByTagName( 'max-value' )->item( 0 )->textContent;

        if ( strlen( $minValue ) > 0 and strlen( $maxValue ) > 0 )
            $minMaxState = self::HAS_MIN_MAX_VALUE;
        else if ( strlen( $minValue ) > 0 )
            $minMaxState = self::HAS_MIN_VALUE;
        else if ( strlen( $maxValue ) > 0 )
            $minMaxState = self::HAS_MAX_VALUE;
        else
            $minMaxState = self::NO_MIN_MAX_VALUE;

        $classAttribute->setAttribute( self::DEFAULT_FIELD, $defaultValue );
        $classAttribute->setAttribute( self::MIN_FIELD, $minValue );
        $classAttribute->setAttribute( self::MAX_FIELD, $maxValue );
        $classAttribute->setAttribute( self::INPUT_STATE_FIELD, $minMaxState );
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }

    function batchInitializeObjectAttributeData( $classAttribute )
    {
        $default = $classAttribute->attribute( 'data_float3' );
        if ( $default !== 0 )
        {
            return array( 'data_float' => $default );
        }

        return array();
    }

    /// \privatesection
    /// The float value validator
    public $FloatValidator;
}

eZDataType::register( eZFloatType::DATA_TYPE_STRING, "eZFloatType" );

?>
