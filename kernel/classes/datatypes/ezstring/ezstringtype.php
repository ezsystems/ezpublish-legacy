<?php
//
// Definition of eZStringType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZStringType ezstringtype.php
  \ingroup eZDatatype
  \brief A content datatype which handles text lines

  It provides the functionality to work as a text line and handles
  class definition input, object definition input and object viewing.

  It uses the spare field data_text in a content object attribute for storing
  the attribute data.

*/

require_once( 'kernel/common/i18n.php' );

class eZStringType extends eZDataType
{
    const DATA_TYPE_STRING = 'ezstring';
    const MAX_LEN_FIELD = 'data_int1';
    const MAX_LEN_VARIABLE = '_ezstring_max_string_length_';
    const DEFAULT_STRING_FIELD = "data_text1";
    const DEFAULT_STRING_VARIABLE = "_ezstring_default_value_";

    /*!
     Initializes with a string id and a description.
    */
    function eZStringType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', 'Text line', 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_text' => 'text' ) ) );
        $this->MaxLenValidator = new eZIntegerValidator();
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
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
        else
        {
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
            $default = $contentClassAttribute->attribute( "data_text1" );
            if ( $default !== "" )
            {
                $contentObjectAttribute->setAttribute( "data_text", $default );
            }
        }
    }

    /*
     Private method, only for using inside this class.
    */
    function validateStringHTTPInput( $data, $contentObjectAttribute, $classAttribute )
    {
        $maxLen = $classAttribute->attribute( self::MAX_LEN_FIELD );
        $textCodec = eZTextCodec::instance( false );
        if ( $textCodec->strlen( $data ) > $maxLen and
             $maxLen > 0 )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'The input text is too long. The maximum number of characters allowed is %1.' ),
                                                         $maxLen );
            return eZInputValidator::STATE_INVALID;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }


    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();

        if ( $http->hasPostVariable( $base . '_ezstring_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_ezstring_data_text_' . $contentObjectAttribute->attribute( 'id' ) );

            if ( $data == "" )
            {
                if ( !$classAttribute->attribute( 'is_information_collector' ) and
                     $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Input required.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }
            else
            {
                return $this->validateStringHTTPInput( $data, $contentObjectAttribute, $classAttribute );
            }
        }
        else if ( !$classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'Input required.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    function validateCollectionAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezstring_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_ezstring_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
            $classAttribute = $contentObjectAttribute->contentClassAttribute();

            if ( $data == "" )
            {
                if ( $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Input required.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
                else
                    return eZInputValidator::STATE_ACCEPTED;
            }
            else
            {
                return $this->validateStringHTTPInput( $data, $contentObjectAttribute, $classAttribute );
            }
        }
        else
            return eZInputValidator::STATE_INVALID;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezstring_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_ezstring_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
            $contentObjectAttribute->setAttribute( 'data_text', $data );
            return true;
        }
        return false;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_ezstring_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $dataText = $http->postVariable( $base . "_ezstring_data_text_" . $contentObjectAttribute->attribute( "id" ) );
            $collectionAttribute->setAttribute( 'data_text', $dataText );
            return true;
        }
        return false;
    }

    /*!
     Does nothing since it uses the data_text field in the content object attribute.
     See fetchObjectAttributeHTTPInput for the actual storing.
    */
    function storeObjectAttribute( $attribute )
    {
    }

    /*!
     Simple string insertion is supported.
    */
    function isSimpleStringInsertionSupported()
    {
        return true;
    }

    /*!
     Inserts the string \a $string in the \c 'data_text' database field.
    */
    function insertSimpleString( $object, $objectVersion, $objectLanguage,
                                 $objectAttribute, $string,
                                 &$result )
    {
        $result = array( 'errors' => array(),
                         'require_storage' => true );
        $objectAttribute->setContent( $string );
        $objectAttribute->setAttribute( 'data_text', $string );
        return true;
    }

    function storeClassAttribute( $attribute, $version )
    {
    }

    function storeDefinedClassAttribute( $attribute )
    {
    }

    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $maxLenName = $base . self::MAX_LEN_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $maxLenName ) )
        {
            $maxLenValue = $http->postVariable( $maxLenName );
            $maxLenValue = str_replace(" ", "", $maxLenValue );
            if( ( $maxLenValue == "" ) ||  ( $maxLenValue == 0 ) )
            {
                $maxLenValue = 0;
                $http->setPostVariable( $maxLenName, $maxLenValue );
                return eZInputValidator::STATE_ACCEPTED;
            }
            else
            {
                $this->MaxLenValidator->setRange( 1, false );
                return $this->MaxLenValidator->validate( $maxLenValue );
            }
        }
        return eZInputValidator::STATE_INVALID;
    }

    function fixupClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $maxLenName = $base . self::MAX_LEN_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $maxLenName ) )
        {
            $maxLenValue = $http->postVariable( $maxLenName );
            $this->MaxLenValidator->setRange( 1, false );
            $maxLenValue = $this->MaxLenValidator->fixup( $maxLenValue );
            $http->setPostVariable( $maxLenName, $maxLenValue );
        }
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $maxLenName = $base . self::MAX_LEN_VARIABLE . $classAttribute->attribute( 'id' );
        $defaultValueName = $base . self::DEFAULT_STRING_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $maxLenName ) )
        {
            $maxLenValue = $http->postVariable( $maxLenName );
            $classAttribute->setAttribute( self::MAX_LEN_FIELD, $maxLenValue );
        }
        if ( $http->hasPostVariable( $defaultValueName ) )
        {
            $defaultValueValue = $http->postVariable( $defaultValueName );

            $classAttribute->setAttribute( self::DEFAULT_STRING_FIELD, $defaultValueValue );
        }
        return true;
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }
    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function fromString( $contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_text', $string );
    }


    /*!
     Returns the content of the string for use as a title
    */
    function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return trim( $contentObjectAttribute->attribute( 'data_text' ) ) != '';
    }

    function isIndexable()
    {
        return true;
    }

    function isInformationCollector()
    {
        return true;
    }

    function sortKey( $contentObjectAttribute )
    {
        $trans = eZCharTransform::instance();
        return $trans->transformByGroup( $contentObjectAttribute->attribute( 'data_text' ), 'lowercase' );
    }

    function sortKeyType()
    {
        return 'string';
    }

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $maxLength = $classAttribute->attribute( self::MAX_LEN_FIELD );
        $defaultString = $classAttribute->attribute( self::DEFAULT_STRING_FIELD );
        $dom = $attributeParametersNode->ownerDocument;
        $maxLengthNode = $dom->createElement( 'max-length' );
        $maxLengthNode->appendChild( $dom->createTextNode( $maxLength ) );
        $attributeParametersNode->appendChild( $maxLengthNode );
        $defaultStringNode = $dom->createElement( 'default-string' );
        if ( $defaultString )
        {
            $defaultStringNode->appendChild( $dom->createTextNode( $defaultString ) );
        }
        $attributeParametersNode->appendChild( $defaultStringNode );
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $maxLength = $attributeParametersNode->getElementsByTagName( 'max-length' )->item( 0 )->textContent;
        $defaultString = $attributeParametersNode->getElementsByTagName( 'default-string' )->item( 0 )->textContent;
        $classAttribute->setAttribute( self::MAX_LEN_FIELD, $maxLength );
        $classAttribute->setAttribute( self::DEFAULT_STRING_FIELD, $defaultString );
    }

    function diff( $old, $new, $options = false )
    {
        $diff = new eZDiff();
        $diff->setDiffEngineType( $diff->engineType( 'text' ) );
        $diff->initDiffEngine();
        $diffObject = $diff->diff( $old->content(), $new->content() );
        return $diffObject;
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }

    function batchInitializeObjectAttributeData( $classAttribute )
    {
        $default = $classAttribute->attribute( 'data_text1' );
        if ( $default !== '' )
        {
            $trans = eZCharTransform::instance();
            $lowerCasedDefault = $trans->transformByGroup( $default, 'lowercase' );
            return array( 'data_text' => $default, 'sort_key_string' => $lowerCasedDefault );
        }

        return array();
    }

    /// \privatesection
    /// The max len validator
    public $MaxLenValidator;
}

eZDataType::register( eZStringType::DATA_TYPE_STRING, 'eZStringType' );

?>
