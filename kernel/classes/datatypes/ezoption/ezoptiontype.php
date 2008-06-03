<?php
//
// Definition of eZOptionType class
//
//Created on: <28-Jun-2002 11:12:51 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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
  \class eZOptionType ezoptiontype.php
  \ingroup eZDatatype
  \brief Stores option values

*/

//include_once( "kernel/classes/ezdatatype.php" );

//include_once( "kernel/classes/datatypes/ezoption/ezoption.php" );
//include_once( 'lib/ezutils/classes/ezstringutils.php' );

class eZOptionType extends eZDataType
{
    const DEFAULT_NAME_VARIABLE = "_ezoption_default_name_";

    const DATA_TYPE_STRING = "ezoption";

    function eZOptionType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Option", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
    */
    function validateCollectionAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        if ( $http->hasPostVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $value = $http->hasPostVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) );

            if ( $contentObjectAttribute->validateIsRequired() and !$value )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'Input required.' ) );
                return eZInputValidator::STATE_INVALID;
            }
        }
        else
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Input required.' ) );
            return eZInputValidator::STATE_INVALID;
        }
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $count = 0;
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        if ( $http->hasPostVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $idList = $http->postVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) );
            $valueList = $http->postVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) );
            $dataName = $http->postVariable( $base . "_data_option_name_" . $contentObjectAttribute->attribute( "id" ) );

            if ( $http->hasPostVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) ) )
                $optionAdditionalPriceList = $http->postVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) );
            else
                $optionAdditionalPriceList = array();

            for ( $i = 0; $i < count( $valueList ); ++$i )
                if ( trim( $valueList[$i] ) <> '' )
                {
                    ++$count;
                    break;
                }
            if ( $contentObjectAttribute->validateIsRequired() and trim( $dataName ) == '' )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'NAME is required.' ) );
                return eZInputValidator::STATE_INVALID;
            }
            if ( $count != 0 )
            {
                for ( $i=0;$i<count( $idList );$i++ )
                {
                    $value =  $valueList[$i];
                    if ( trim( $value )== "" )
                    {
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'The option value must be provided.' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                    if ( isset( $optionAdditionalPriceList[$i] ) &&
                         strlen( $optionAdditionalPriceList[$i] ) &&
                         !preg_match( "#^[-|+]?[0-9]+(\.){0,1}[0-9]{0,2}$#", $optionAdditionalPriceList[$i] ) )
                    {
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'The Additional price value is not valid.' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                }
            }
        }
        if ( $contentObjectAttribute->validateIsRequired() and
             !$classAttribute->attribute( 'is_information_collector' ) )
        {
            if ( $count == 0 )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'At least one option is required.' ) );
                return eZInputValidator::STATE_INVALID;
            }
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Store content
    */
    function storeObjectAttribute( $contentObjectAttribute )
    {
        $option = $contentObjectAttribute->content();
        $contentObjectAttribute->setAttribute( "data_text", $option->xmlString() );
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $option = new eZOption( "" );

        $option->decodeXML( $contentObjectAttribute->attribute( "data_text" ) );

        return $option;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $optionName = $http->postVariable( $base . "_data_option_name_" . $contentObjectAttribute->attribute( "id" ) );
        if ( $http->hasPostVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) ) )
            $optionIDArray = $http->postVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) );
        else
            $optionIDArray = array();
        if ( $http->hasPostVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) ) )
            $optionValueArray = $http->postVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) );
        else
            $optionValueArray = array();
        if ( $http->hasPostVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) ) )
            $optionAdditionalPriceArray = $http->postVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) );
        else
            $optionAdditionalPriceArray = array();

        $option = new eZOption( $optionName );

        $i = 0;
        foreach ( $optionIDArray as $id )
        {
            $option->addOption( array( 'value' => $optionValueArray[$i],
                                       'additional_price' => ( isset( $optionAdditionalPriceArray[$i] ) ? $optionAdditionalPriceArray[$i] : 0 ) ) );
            $i++;
        }
        $contentObjectAttribute->setContent( $option );
        return true;
    }


    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $optionValue = $http->postVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) );

            $collectionAttribute->setAttribute( 'data_int', $optionValue );
            $attr = $contentObjectAttribute->attribute( 'contentclass_attribute' );

            return true;
        }
        return false;
    }

    /*!
    */
    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
    {
        switch ( $action )
        {
            case "new_option" :
            {
                $option = $contentObjectAttribute->content( );

                $postvarname = "ContentObjectAttribute" . "_data_option_remove_" . $contentObjectAttribute->attribute( "id" );
                if ( $http->hasPostVariable( $postvarname ) )
                {
                    $idArray = $http->postVariable( $postvarname );
                    $beforeID = array_shift( $idArray );
                    if ( $beforeID >= 0 )
                    {
                        $option->insertOption( array(), $beforeID );
                        $contentObjectAttribute->setContent( $option );
                        $contentObjectAttribute->store();
                        $option = new eZOption( "" );
                        $option->decodeXML( $contentObjectAttribute->attribute( "data_text" ) );
                        $contentObjectAttribute->setContent( $option );
                        return;
                    }
                }
                $option->addOption( "" );
                $contentObjectAttribute->setContent( $option );
                $contentObjectAttribute->store();
            }break;
            case "remove_selected" :
            {
                $option = $contentObjectAttribute->content( );
                $postvarname = "ContentObjectAttribute" . "_data_option_remove_" . $contentObjectAttribute->attribute( "id" );
                $array_remove = $http->postVariable( $postvarname );
                $option->removeOptions( $array_remove );
                $contentObjectAttribute->setContent( $option );
                $contentObjectAttribute->store();
                $option = new eZOption( "" );
                $option->decodeXML( $contentObjectAttribute->attribute( "data_text" ) );
                $contentObjectAttribute->setContent( $option );
            }break;
            default :
            {
                eZDebug::writeError( "Unknown custom HTTP action: " . $action, "eZOptionType" );
            }break;
        }
    }

    /*!
     Finds the option which has the ID that matches \a $optionID, if found it returns
     an option structure.
    */
    function productOptionInformation( $objectAttribute, $optionID, $productItem )
    {
        $option = $objectAttribute->attribute( 'content' );
        foreach( $option->attribute( 'option_list' ) as $optionArray )
        {
            if ( $optionArray['id'] == $optionID )
            {
                return array( 'id' => $optionArray['id'],
                              'name' => $option->attribute( 'name' ),
                              'value' => $optionArray['value'],
                              'additional_price' => $optionArray['additional_price'] );
            }
        }
        return false;
    }

    /*!
     Returns the integer value.
    */
    function title( $contentObjectAttribute, $name = "name" )
    {
        $option = $contentObjectAttribute->content( );

        $value = $option->attribute( $name );

        return $value;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $option = $contentObjectAttribute->content( );
        $options = $option->attribute( 'option_list' );
        return count( $options ) > 0;
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion == false )
        {
            $option = $contentObjectAttribute->content();
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
            if ( !$option )
            {
                $option = new eZOption( $contentClassAttribute->attribute( 'data_text1' ) );
            }
            else
            {
                $option->setName( $contentClassAttribute->attribute( 'data_text1' ) );
            }
            $contentObjectAttribute->setAttribute( "data_text", $option->xmlString() );
            $contentObjectAttribute->setContent( $option );
        }
        else
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $defaultValueName = $base . self::DEFAULT_NAME_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $defaultValueName ) )
        {
            $defaultValueValue = $http->postVariable( $defaultValueName );

            if ($defaultValueValue == "")
            {
                $defaultValueValue = "";
            }
            $classAttribute->setAttribute( 'data_text1', $defaultValueValue );
            return true;
        }
        return false;
    }

    function toString( $contentObjectAttribute )
    {

        $option = $contentObjectAttribute->attribute( 'content' );
        $optionArray = array();
        $optionArray[] = $option->attribute( 'name' );

        $optionList = $option->attribute( 'option_list' );

        foreach ( $optionList as $key => $value )
        {
            $optionArray[] = $value['value'];
            $optionArray[] = $value['additional_price'];
        }
        return eZStringUtils::implodeStr( $optionArray, "|" );
    }


    function fromString( $contentObjectAttribute, $string )
    {
        if ( $string == '' )
            return true;

        $optionArray = eZStringUtils::explodeStr( $string, '|' );

        $option = new eZOption( );

        $option->OptionCount = 0;
        $option->Options = array();
        $option->Name = array_shift( $optionArray );
        $count = count( $optionArray );
        for ( $i = 0; $i < $count; $i +=2 )
        {

            $option->addOption( array( 'value' => array_shift( $optionArray ),
                                       'additional_price' => array_shift( $optionArray ) ) );
        }


        $contentObjectAttribute->setAttribute( "data_text", $option->xmlString() );

        return $option;

    }
    /*!
     \reimp
    */
    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( 'data_text1' );
        $defaultValueNode = $attributeParametersNode->ownerDocument->createElement( 'default-value', $defaultValue );
        $attributeParametersNode->appendChild( $defaultValueNode );
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $defaultValue = $attributeParametersNode->getElementsByTagName( 'default-value' )->item( 0 )->textContent;
        $classAttribute->setAttribute( 'data_text1', $defaultValue );
    }

    /*!
     \reimp
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );

        $domDocument = new DOMDocument( '1.0', 'utf-8' );
        $success = $domDocument->loadXML( $objectAttribute->attribute( 'data_text' ) );

        $importedRoot = $node->ownerDocument->importNode( $domDocument->documentElement, true );
        $node->appendChild( $importedRoot );

        return $node;
    }

    /*!
     \reimp
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $xmlString = '';
        $optionNode = $attributeNode->getElementsByTagName( 'ezoption' )->item( 0 );

        if ( $optionNode )
        {
            $xmlString = $optionNode->ownerDocument->saveXML( $optionNode );
        }
        else
        {
            // backward compatibility
            $optionNode = $attributeNode->getElementsByTagName( 'data-text' )->item( 0 );
            if ( $optionNode )
            {
                $xmlString = $optionNode->textContent;
            }
            else
            {
                // dl: unknown case. Probably should be removed at all.
                $optionNode = $attributeNode->firstChild;
                $xmlString = $optionNode->getAttribute( 'local_name' ) == 'data-text' ? '' : $optionNode->textContent;
            }
        }

        $objectAttribute->setAttribute( 'data_text', $xmlString );
    }

    /*!
     \reimp
    */
    function isInformationCollector()
    {
        return true;
    }
}

eZDataType::register( eZOptionType::DATA_TYPE_STRING, "eZOptionType" );

?>
