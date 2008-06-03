<?php
//
// Definition of eZOptionType class
//
// Created on: <29-Jul-2004 15:52:24 gv>
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

//include_once( "kernel/classes/ezdatatype.php" );
//include_once( "kernel/classes/datatypes/ezmultioption/ezmultioption.php" );
//include_once( 'lib/ezutils/classes/ezstringutils.php' );

class eZMultiOptionType extends eZDataType
{
    const DEFAULT_NAME_VARIABLE = "_ezmultioption_default_name_";
    const DATA_TYPE_STRING = "ezmultioption";

    /*!
     Constructor to initialize the datatype.
    */
    function eZMultiOptionType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Multi-option", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates the input for this datatype.
     \return True if input is valid.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $count = 0;
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        if ( $http->hasPostVariable( $base . "_data_multioption_id_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            $multioptionIDArray = $http->postVariable( $base . "_data_multioption_id_" . $contentObjectAttribute->attribute( "id" ) );

            foreach ( $multioptionIDArray as $id )
            {
                $multioptionName = $http->postVariable( $base . "_data_multioption_name_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id );
                $optionIDArray = $http->hasPostVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                 ? $http->postVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                 : array();
                $optionCountArray = $http->hasPostVariable( $base . "_data_option_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                    ? $http->postVariable( $base . "_data_option_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                    : array();
                $optionValueArray = $http->hasPostVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                    ? $http->postVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                    : array();
                $optionAdditionalPriceArray = $http->hasPostVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                              ? $http->postVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                              : array();
                for ( $i = 0; $i < count( $optionIDArray ); $i++ )
                {
                    if ( $contentObjectAttribute->validateIsRequired() and !$classAttribute->attribute( 'is_information_collector' ) )
                    {
                        if ( trim( $optionValueArray[$i] ) == "" )
                        {
                            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                                 'The option value must be provided.' ) );
                            return eZInputValidator::STATE_INVALID;
                        }
                        else
                            ++$count;
                    }

                    if ( trim( $optionValueArray[$i] ) != "" )
                    {
                        if ( strlen( $optionAdditionalPriceArray[$i] ) && !preg_match( "#^[-|+]?[0-9]+(\.){0,1}[0-9]{0,2}$#", $optionAdditionalPriceArray[$i] ) )
                        {
                            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                                 'The additional price for the multioption value is not valid.' ) );
                            return eZInputValidator::STATE_INVALID;
                        }
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

            $optionSetName = $http->hasPostVariable( $base . "_data_optionset_name_" . $contentObjectAttribute->attribute( "id" ) )
                             ? $http->postVariable( $base . "_data_optionset_name_" . $contentObjectAttribute->attribute( "id" ) )
                             : '';
            if ( trim( $optionSetName ) == '' )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'Option set name is required.' ) );
                return eZInputValidator::STATE_INVALID;
            }
        }


        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     This function calles xmlString function to create xml string and then store the content.
    */
    function storeObjectAttribute( $contentObjectAttribute )
    {
        $multioption = $contentObjectAttribute->content();
        $contentObjectAttribute->setAttribute( "data_text", $multioption->xmlString() );
    }

    /*!
     \return An eZMultiOption object which contains all the option data
    */
    function objectAttributeContent( $contentObjectAttribute )
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
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $multioptionIDArray = $http->hasPostVariable( $base . "_data_multioption_id_" . $contentObjectAttribute->attribute( "id" ) )
                              ? $http->postVariable( $base . "_data_multioption_id_" . $contentObjectAttribute->attribute( "id" ) )
                              : array();
        $optionSetName = $http->postVariable( $base . "_data_optionset_name_" . $contentObjectAttribute->attribute( "id" ) );
        $multioption = new eZMultiOption( $optionSetName );
        foreach ( $multioptionIDArray as $id )
        {
            $multioptionName = $http->postVariable( $base . "_data_multioption_name_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id );
            $optionIDArray = $http->hasPostVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                             ? $http->postVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                             : array();

            $optionPriority = $http->postVariable( $base . "_data_multioption_priority_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id );
            // check to prevent PHP warning if the default choice is specified (no radio button selected)
            if ( $http->hasPostVariable( $base . "_data_radio_checked_" . $contentObjectAttribute->attribute("id") . '_' . $id ) )
                $optionDefaultValue = $http->postVariable( $base . "_data_radio_checked_" . $contentObjectAttribute->attribute("id") . '_' . $id );
            else
                $optionDefaultValue = '';
            $newID = $multioption->addMultiOption( $multioptionName,$optionPriority, $optionDefaultValue );

            $optionCountArray = $http->hasPostVariable( $base . "_data_option_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                ? $http->postVariable( $base . "_data_option_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                : array();
            $optionValueArray = $http->hasPostVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                ? $http->postVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                : array();
            $optionAdditionalPriceArray = $http->hasPostVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                          ? $http->postVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                          : array();

            for ( $i = 0; $i < count( $optionIDArray ); $i++ )
                $multioption->addOption( $newID, $optionCountArray[$i], $optionValueArray[$i], $optionAdditionalPriceArray[$i] );
        }

        $multioption->sortMultiOptions();
        $multioption->resetOptionCounter();
        $contentObjectAttribute->setContent( $multioption );
        return true;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        $multioptionValue = $http->postVariable( $base . "_data_multioption_value_" . $contentObjectAttribute->attribute( "id" ) );
        $collectionAttribute->setAttribute( 'data_int', $multioptionValue );
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
    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
    {
        $actionlist = explode( "_", $action );
        if ( $actionlist[0] == "new-option" )
        {
            $multioption = $contentObjectAttribute->content();

            $multioption->addOption( ( $actionlist[1] - 1 ), "", "", "");
            $contentObjectAttribute->setContent( $multioption );
            $contentObjectAttribute->store();
        }
        else if ( $actionlist[0] == "remove-selected-option" )
        {
            $multioption = $contentObjectAttribute->content();
            $postvarname = "ContentObjectAttribute" . "_data_option_remove_" . $contentObjectAttribute->attribute( "id" ) . "_" . $actionlist[1];
            $array_remove = $http->hasPostVariable( $postvarname ) ? $http->postVariable( $postvarname ) : array();
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
                    $multioption = $contentObjectAttribute->content();
                    $newID = $multioption->addMultiOption( "" ,0,false );
                    $multioption->addOption( $newID, "", "", "" );
                    $multioption->addOption( $newID, "" ,"", "" );
                    $contentObjectAttribute->setContent( $multioption );
                    $contentObjectAttribute->store();
                } break;

                case "remove_selected_multioption":
                {
                    $multioption = $contentObjectAttribute->content();
                    $postvarname = "ContentObjectAttribute" . "_data_multioption_remove_" . $contentObjectAttribute->attribute( "id" );
                    $array_remove = $http->hasPostVariable( $postvarname )? $http->postVariable( $postvarname ) : array();
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
    function productOptionInformation( $objectAttribute, $optionID, $productItem )
    {
        $multioption = $objectAttribute->attribute( 'content' );

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
    function title( $contentObjectAttribute, $name = "name" )
    {
        $multioption = $contentObjectAttribute->content();
        return $multioption->attribute( $name );
    }

    /*!
      \reimp
      \return \c true if there are more than one multioption in the list.
    */
    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $multioption = $contentObjectAttribute->content();
        $multioptions = $multioption->attribute( 'multioption_list' );
        return count( $multioptions ) > 0;
    }

    /*!
     Sets default multioption values.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion == false )
        {
            $multioption = $contentObjectAttribute->content();
            if ( $multioption )
            {
                $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
                $multioption->setName( $contentClassAttribute->attribute( 'data_text1' ) );
                $contentObjectAttribute->setAttribute( "data_text", $multioption->xmlString() );
                $contentObjectAttribute->setContent( $multioption );
            }
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

            if ( $defaultValueValue == "" )
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

        $content = $contentObjectAttribute->attribute( 'content' );

        $multioptionArray = array();

        $setName = $content->attribute( 'name' );
        $multioptionArray[] = $setName;

        $multioptionList = $content->attribute( 'multioption_list' );

        foreach ( $multioptionList as $key => $option )
        {
            $optionArray = array();
            $optionArray[] = $option['name'];
            $optionArray[] = $option['default_option_id'];
            foreach ( $option['optionlist'] as $key => $value )
            {
                $optionArray[] = $value['value'];
                $optionArray[] = $value['additional_price'];
            }
            $multioptionArray[] = eZStringUtils::implodeStr( $optionArray, '|' );
        }
        return eZStringUtils::implodeStr( $multioptionArray, "&" );
    }


    function fromString( $contentObjectAttribute, $string )
    {
        if ( $string == '' )
            return true;

        $multioptionArray = eZStringUtils::explodeStr( $string, '&' );

        $multioption = new eZMultiOption( "" );

        $multioption->OptionCounter = 0;
        $multioption->Options = array();
        $multioption->Name = array_shift( $multioptionArray );
        $priority = 1;
        foreach ( $multioptionArray as $multioptionStr )
        {
            $optionArray = eZStringUtils::explodeStr( $multioptionStr, '|' );


            $newID = $multioption->addMultiOption( array_shift( $optionArray ),
                                            $priority,
                                            array_shift( $optionArray ) );
            $optionID = 0;
            $count = count( $optionArray );
            for ( $i = 0; $i < $count; $i +=2 )
            {
                $multioption->addOption( $newID, $optionID, array_shift( $optionArray ), array_shift( $optionArray ) );
                $optionID++;
            }
            $priority++;
        }

        $contentObjectAttribute->setAttribute( "data_text", $multioption->xmlString() );

        return $multioption;

    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( 'data_text1' );
        $dom = $attributeParametersNode->ownerDocument;
        $defaultValueNode = $dom->createElement( 'default-value', $defaultValue );
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

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $success = $dom->loadXML( $objectAttribute->attribute( 'data_text' ) );

        $importedRoot = $node->ownerDocument->importNode( $dom->documentElement, true );
        $node->appendChild( $importedRoot );

        return $node;
    }

    /*!
     \reimp
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $rootNode = $attributeNode->getElementsByTagName( 'ezmultioption' )->item( 0 );
        $xmlString = $rootNode ? $rootNode->ownerDocument->saveXML( $rootNode ) : '';
        $objectAttribute->setAttribute( 'data_text', $xmlString );
    }
}

eZDataType::register( eZMultiOptionType::DATA_TYPE_STRING, "eZMultiOptionType" );

?>
