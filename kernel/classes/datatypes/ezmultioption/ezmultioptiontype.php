<?php
//
// Definition of eZOptionType class
//
// Created on: <29-Jul-2004 15:52:24 gv>
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

/*!
  \class eZMultiOptionType ezmultioptiontype.php
  \ingroup eZDatatype
  \brief A datatype which works with multiple options.

  This allows the user to add several option choices almost as if he
  was adding attributes with option datatypes.

  This class implements the interface for a datatype but passes
  most of the work over to the eZMultiOption class which handles
  parsing, storing and manipulation of multioptions and options.

  This datatype supports:
  - fetch and validation of HTTP data
  - search indexing
  - product option information
  - class title
  - class serialization

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "kernel/classes/datatypes/ezmultioption/ezmultioption.php" );
define( "EZ_MULTIOPTION_DEFAULT_NAME_VARIABLE", "_ezmultioption_default_name_" );
define( "EZ_DATATYPESTRING_MULTIOPTION", "ezmultioption" );

class eZMultiOptionType extends eZDataType
{
    /*!
     Constructor to initialize the datatype.
    */
    function eZMultiOptionType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_MULTIOPTION, ezi18n( 'kernel/classes/datatypes', "MultiOption", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates the input for this datatype.
     \return True if input is valid.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_multioption_id_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();
            $idList = $http->postVariable( $base . "_data_multioption_id_" . $contentObjectAttribute->attribute( "id" ) );
            $valueList = $http->postVariable( $base . "_data_multioption_value_" . $contentObjectAttribute->attribute( "id" ) );
            $multioptionAdditionalPriceList =& $http->postVariable( $base . "_data_multioption_additional_price_" . $contentObjectAttribute->attribute( "id" ) );
            if ( $classAttribute->attribute( "is_required" ) and !$classAttribute->attribute( 'is_information_collector' ) )
            {
                if ( trim( $valueList[0] ) == "" )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'At least one multioption is required.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
            }
            if ( trim( $valueList[0] ) != "" )
            {
                for ( $i = 0; $i < count( $idList ); $i++ )
                {
                    $value =  $valueList[$i];
                    if ( trim( $value ) == "" )
                    {
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'The option value must be provided.' ) );
                        return EZ_INPUT_VALIDATOR_STATE_INVALID;
                    }

                    if ( strlen( $multioptionAdditionalPriceList[$i] ) && !preg_match( "#^[-|+]?[0-9]+(\.){0,1}[0-9]{0,2}$#", $multioptionAdditionalPriceList[$i] ) )
                    {
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'The additional price for the multioption value is not valid.' ) );
                        return EZ_INPUT_VALIDATOR_STATE_INVALID;
                    }
                }
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     This function calles xmlString function to create xml string and then store the content.
    */
    function storeObjectAttribute( &$contentObjectAttribute )
    {
        $multioption =& $contentObjectAttribute->content();
        $contentObjectAttribute->setAttribute( "data_text", $multioption->xmlString() );
    }

    /*!
     \return An eZMultiOption object which contains all the option data
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $multioption = new eZMultiOption( "" );
        $multioption->decodeXML( $contentObjectAttribute->attribute( "data_text" ) );
        return $multioption;
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     \return The internal XML text.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $multioptionIDArray =& $http->postVariable( $base . "_data_multioption_id_" . $contentObjectAttribute->attribute( "id" ) );
        $multioption = new eZMultiOption( $multioptionName );
        foreach ( $multioptionIDArray as $id )
        {
            $multioptionName =& $http->postVariable( $base . "_data_multioption_name_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id );
            $optionIDArray =& $http->postVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id );
            $optionPriority =& $http->postVariable( $base . "_data_multioption_priority_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id );
            $optionDefaultValue =& $http->postVariable( $base . "_data_radio_checked_" . $contentObjectAttribute->attribute("id") . '_' . $id );
            $newID = $multioption->addMultiOption( $multioptionName,$optionPriority, $optionDefaultValue );
            $i = 0;
            foreach ( $optionIDArray as $optionID )
            {
                $optionCountArray =& $http->postVariable( $base . "_data_option_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id );
                $optionValueArray =& $http->postVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id );
                $optionAdditionalPriceArray =& $http->postVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id );
        $multioption->addOption( $newID, $optionCountArray[$i], $optionValueArray[$i], $optionAdditionalPriceArray[$i] );
                $i++;
            }
        }
        $multioption->sortMultiOptions();
        $multioption->resetOptionCounter();
        $contentObjectAttribute->setContent( $multioption );
        return true;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( &$collection, &$collectionAttribute, &$http, $base, &$contentObjectAttribute )
    {
        $multioptionValue =& $http->postVariable( $base . "_data_multioption_value_" . $contentObjectAttribute->attribute( "id" ) );
        $collectionAttribute->setAttribute( 'data_int', $multioptionValue );
        $attr =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
        return true;
    }

    /*!
     This function performs specific actions.

     It has some special actions with parameters which is done by exploding
     $action into several parts with delimeter '_'.
     The first element is the name of specific action to perform.
     The second element will contain the key value or id.

     The various operation's that is performed by this function are as follow.
     - new-option - A new option is added to a multioption.
     - remove-selected-option - Removes a selected option.
     - new_multioption - Adds a new multioption.
     - remove_selected_multioption - Removes all multioptions given by a selection list
    */
    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute )
    {
        $actionlist = explode( "_", $action );
        if ( $actionlist[0] == "new-option" )
        {
            $multioption =& $contentObjectAttribute->content();

            $multioption->addOption( ( $actionlist[1] - 1 ), "", "", "");
            $contentObjectAttribute->setContent( $multioption );
            $contentObjectAttribute->store();
        }
        else if ( $actionlist[0] == "remove-selected-option" )
        {
            $multioption =& $contentObjectAttribute->content();
            $postvarname = "ContentObjectAttribute" . "_data_option_remove_" . $contentObjectAttribute->attribute( "id" ) . "_" . $actionlist[1];
            $array_remove = $http->postVariable( $postvarname );
            $multioption->removeOptions( $array_remove, $actionlist[1] - 1 );
            $contentObjectAttribute->setContent( $multioption );
            $contentObjectAttribute->store();
        }
        else
        {
            switch ( $action )
            {
                case "new_multioption" :
                {
                    $multioption =& $contentObjectAttribute->content();
                    $newID = $multioption->addMultiOption( "" ,0,false );
                    $multioption->addOption( $newID, "", "", "" );
                    $multioption->addOption( $newID, "" ,"", "" );
                    $contentObjectAttribute->setContent( $multioption );
                    $contentObjectAttribute->store();
                } break;

                case "remove_selected_multioption":
                {
                    $multioption =& $contentObjectAttribute->content();
                    $postvarname = "ContentObjectAttribute" . "_data_multioption_remove_" . $contentObjectAttribute->attribute( "id" );
                    $array_remove = $http->postVariable( $postvarname );
                    $multioption->removeMultiOptions( $array_remove );
                    $contentObjectAttribute->setContent( $multioption );
                    $contentObjectAttribute->store();
                } break;

                default:
                {
                    eZDebug::writeError( "Unknown custom HTTP action: " . $action, "eZMultiOptionType" );
                } break;
            }
        }
    }

    /*!
     \reimp
     Finds the option which has the correct ID , if found it returns an option structure.

     \param $optionString must contain the multioption ID an underscore (_) and a the option ID.
    */
    function productOptionInformation( &$objectAttribute, $optionID, &$productItem )
    {
        $multioption =& $objectAttribute->attribute( 'content' );

        foreach ( $multioption->attribute( 'multioption_list' ) as $multioptionElement )
        {
            foreach ( $multioptionElement['optionlist'] as $option )
            {
                if ( $option['option_id'] != $optionID )
                    continue;

                return array( 'id' => $option['option_id'],
                              'name' => $multioptionElement['name'],
                              'value' => $option['value'],
                              'additional_price' => $option['additional_price'] );
            }
        }
    }

    /*!
     \reimp
    */
    function title( &$contentObjectAttribute, $name = "name" )
    {
        $multioption =& $contentObjectAttribute->content();
        $value = $multioption->attribute( $name );
        return $value;
    }

    /*!
      \reimp
      \return \c true if there are more than one multioption in the list.
    */
    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        $multioption =& $contentObjectAttribute->content();
        $multioptions = $multioption->attribute( 'multioption_list' );
        return count( $multioptions ) > 0;
    }

    /*!
     Sets default multioption values.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion == false )
        {
            $multioption =& $contentObjectAttribute->content();
            if ( $multioption )
            {
                $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
                $multioption->setName( $contentClassAttribute->attribute( 'data_text1' ) );
                $contentObjectAttribute->setAttribute( "data_text", $multioption->xmlString() );
                $contentObjectAttribute->setContent( $multioption );
            }
        }
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $defaultValueName = $base . EZ_MULTIOPTION_DEFAULT_NAME_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $defaultValueName ) )
        {
            $defaultValueValue = $http->postVariable( $defaultValueName );

            if ( $defaultValueValue == "" )
            {
                $defaultValueValue = "";
            }
            $classAttribute->setAttribute( 'data_text1', $defaultValueValue );
            return true;
        }
        return false;
    }

    /*!
     \reimp
    */
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( 'data_text1' );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'default-value', $defaultValue ) );
    }

    /*!
     \reimp
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $defaultValue = $attributeParametersNode->elementTextContentByName( 'default-value' );
        $classAttribute->setAttribute( 'data_text1', $defaultValue );
    }

}

eZDataType::register( EZ_DATATYPESTRING_MULTIOPTION, "ezmultioptiontype" );

?>
