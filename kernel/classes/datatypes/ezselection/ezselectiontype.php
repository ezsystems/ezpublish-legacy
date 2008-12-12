<?php
//
// Definition of eZSelectionType class
//
// Created on: <23-Jul-2003 12:51:27 bf>
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
  \class   ezselectiontype ezselectiontype.php
  \ingroup eZDatatype
  \brief   Handles the single and multiple selections.
  \date    Wednesday 23 July 2003 12:48:45 pm
  \author  Bård Farstad

*/

class eZSelectionType extends eZDataType
{
    const DATA_TYPE_STRING = "ezselection";

    /*!
      Constructor
    */
    function eZSelectionType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Selection", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates all variables given on content class level
     \return eZInputValidator::STATE_ACCEPTED or eZInputValidator::STATE_INVALID if
             the values are accepted or not
    */
    function validateClassAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Fetches all variables inputed on content class level
     \return true if fetching of class attributes are successfull, false if not
    */
    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $attributeContent = $this->classAttributeContent( $classAttribute );
        $classAttributeID = $classAttribute->attribute( 'id' );
        $isMultipleSelection = false;

        if ( $http->hasPostVariable( $base . "_ezselection_ismultiple_value_" . $classAttributeID ) )
        {
            if( $http->postVariable( $base . "_ezselection_ismultiple_value_" . $classAttributeID ) != 0 )
            {
                $isMultipleSelection = true;
            }
        }

        $currentOptions = $attributeContent['options'];
        $hasPostData = false;

        if ( $http->hasPostVariable( $base . "_ezselection_option_name_array_" . $classAttributeID ) )
        {
            $nameArray = $http->postVariable( $base . "_ezselection_option_name_array_" . $classAttributeID );

            // Fill in new names for options
            foreach ( array_keys( $currentOptions ) as $key )
            {
                $currentOptions[$key]['name'] = $nameArray[$currentOptions[$key]['id']];
            }
            $hasPostData = true;

        }

        if ( $http->hasPostVariable( $base . "_ezselection_newoption_button_" . $classAttributeID ) )
        {
            $currentCount = 0;
            foreach ( $currentOptions as $option )
            {
                $currentCount = max( $currentCount, $option['id'] );
            }
            $currentCount += 1;
            $currentOptions[] = array( 'id' => $currentCount,
                                       'name' => '' );
            $hasPostData = true;

        }

        if ( $http->hasPostVariable( $base . "_ezselection_removeoption_button_" . $classAttributeID ) )
        {
            if ( $http->hasPostVariable( $base . "_ezselection_option_remove_array_". $classAttributeID ) )
            {
                $removeArray = $http->postVariable( $base . "_ezselection_option_remove_array_". $classAttributeID );

                foreach ( array_keys( $currentOptions ) as $key )
                {
                    if ( isset( $removeArray[$currentOptions[$key]['id']] ) and
                         $removeArray[$currentOptions[$key]['id']] )
                        unset( $currentOptions[$key] );
                }
                $hasPostData = true;
            }
        }

        if ( $hasPostData )
        {

            // Serialize XML
            $doc = new DOMDocument( '1.0', 'utf-8' );
            $root = $doc->createElement( "ezselection" );
            $doc->appendChild( $root );

            $options = $doc->createElement( "options" );

            $root->appendChild( $options );
            foreach ( $currentOptions as $optionArray )
            {
                unset( $optionNode );
                $optionNode = $doc->createElement( "option" );
                $optionNode->setAttribute( 'id', $optionArray['id'] );
                $optionNode->setAttribute( 'name', $optionArray['name'] );

                $options->appendChild( $optionNode );
            }

            $xml = $doc->saveXML();

            $classAttribute->setAttribute( "data_text5", $xml );

            if ( $isMultipleSelection == true )
                $classAttribute->setAttribute( "data_int1", 1 );
            else
                $classAttribute->setAttribute( "data_int1", 0 );
        }
        return true;
    }
    /*!
     Validates input on content object level
     \return eZInputValidator::STATE_ACCEPTED or eZInputValidator::STATE_INVALID if
             the values are accepted or not
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezselect_selected_array_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_ezselect_selected_array_' . $contentObjectAttribute->attribute( 'id' ) );
            $classAttribute = $contentObjectAttribute->contentClassAttribute();

            if ( $data == "" )
            {
                if ( !$classAttribute->attribute( 'is_information_collector' ) &&
                     $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Input required.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Fetches all variables from the object
     \return true if fetching of class attributes are successfull, false if not
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezselect_selected_array_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $selectOptions = $http->postVariable( $base . '_ezselect_selected_array_' . $contentObjectAttribute->attribute( 'id' ) );
            $idString = ( is_array( $selectOptions ) ? implode( '-', $selectOptions ) : "" );
            $contentObjectAttribute->setAttribute( 'data_text', $idString );
            return true;
        }
        return false;
    }

    /*!
     \reimp
    */
    function validateCollectionAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezselect_selected_array_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_ezselect_selected_array_' . $contentObjectAttribute->attribute( 'id' ) );

            if ( $data == "" && $contentObjectAttribute->validateIsRequired() )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'Input required.' ) );
                return eZInputValidator::STATE_INVALID;
            }
            else
            {
                return eZInputValidator::STATE_ACCEPTED;
            }
        }
        else
        {
            return eZInputValidator::STATE_INVALID;
        }
    }

   /*!
    \reimp
    Fetches the http post variables for collected information
   */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezselect_selected_array_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $selectOptions = $http->postVariable( $base . '_ezselect_selected_array_' . $contentObjectAttribute->attribute( 'id' ) );
            $idString = ( is_array( $selectOptions ) ? implode( '-', $selectOptions ) : "" );
            $collectionAttribute->setAttribute( 'data_text', $idString );
            return true;
        }
        return false;
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $idString = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $idString );
            $contentObjectAttribute->store();
        }
    }

    /*!
     Returns the selected options by id.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $idString = explode( '-', $contentObjectAttribute->attribute( 'data_text' ) );
        return $idString;
    }

    /*!
     Returns the content data for the given content class attribute.
    */
    function classAttributeContent( $classAttribute )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $xmlString = $classAttribute->attribute( 'data_text5' );
        $optionArray = array();
        if ( $xmlString != '' )
        {
            $success = $dom->loadXML( $xmlString );
            if ( $success )
            {
                $options = $dom->getElementsByTagName( 'option' );

                foreach ( $options as $optionNode )
                {
                    $optionArray[] = array( 'id' => $optionNode->getAttribute( 'id' ),
                                            'name' => $optionNode->getAttribute( 'name' ) );
                }
            }
        }

        if ( count( $optionArray ) == 0 )
        {
            $optionArray[] = array( 'id' => 0,
                                    'name' => '' );
        }
        $attrValue = array( 'options' => $optionArray,
                            'is_multiselect' => $classAttribute->attribute( 'data_int1' ) );
        return $attrValue;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        $selected = $this->objectAttributeContent( $contentObjectAttribute );
        $classContent = $this->classAttributeContent( $contentObjectAttribute->attribute( 'contentclass_attribute' ) );
        $return = '';
        if ( count( $selected ) == 0)
        {
            return '';
        }

        $count = 0;
        $optionArray = $classContent['options'];
        foreach ( $selected as $id )
        {
            if ( $count++ != 0 )
                $return .= ' ';
            foreach ( $optionArray as $option )
            {
                $optionID = $option['id'];
                if ( $optionID == $id )
                    $return .= $option['name'];
            }
        }
        return $return;
    }

    function toString( $contentObjectAttribute )
    {
        $selected = $this->objectAttributeContent( $contentObjectAttribute );
        $classContent = $this->classAttributeContent( $contentObjectAttribute->attribute( 'contentclass_attribute' ) );

        if ( count( $selected ) )
        {
            $optionArray = $classContent['options'];
            foreach ( $selected as $id )
            {
                foreach ( $optionArray as $option )
                {
                    $optionID = $option['id'];
                    if ( $optionID == $id )
                        $returnData[] = $option['name'];
                }
            }
            return eZStringUtils::implodeStr( $returnData, '|' );
        }
        return '';
    }


    function fromString( $contentObjectAttribute, $string )
    {
        if ( $string == '' )
            return true;
        $selectedNames = eZStringUtils::explodeStr( $string, '|' );
        $selectedIDList = array();
        $classContent = $this->classAttributeContent( $contentObjectAttribute->attribute( 'contentclass_attribute' ) );
        $optionArray = $classContent['options'];
        foreach ( $selectedNames as $name )
        {
            foreach ( $optionArray as $option )
            {
                $optionName = $option['name'];
                if ( $optionName == $name )
                    $selectedIDList[] = $option['id'];
            }
        }
        $idString = ( is_array( $selectedIDList ) ? implode( '-', $selectedIDList ) : "" );
        $contentObjectAttribute->setAttribute( 'data_text', $idString );
        return true;
    }

    /*!
     Returns the value as it will be shown if this attribute is used in the object name pattern.
    */
    function title( $contentObjectAttribute, $name = null )
    {
        $selected = $this->objectAttributeContent( $contentObjectAttribute );
        $classContent = $this->classAttributeContent( $contentObjectAttribute->attribute( 'contentclass_attribute' ) );
        $return = '';
        if ( count( $selected ) )
        {
            $selectedNames = array();
            foreach ( $classContent['options'] as $option )
            {
                if ( in_array( $option['id'], $selected ) )
                    $selectedNames[] = $option['name'];
            }
            $return = implode( ', ', $selectedNames );
        }
        return $return;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return true;
    }

    /*!
     \reimp
    */
    function sortKey( $contentObjectAttribute )
    {
        return strtolower( $contentObjectAttribute->attribute( 'data_text' ) );
    }

    /*!
     \reimp
    */
    function sortKeyType()
    {
        return 'string';
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
    function isInformationCollector()
    {
        return true;
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $isMultipleSelection = $classAttribute->attribute( 'data_int1'  );
        $xmlString = $classAttribute->attribute( 'data_text5' );

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $success = $dom->loadXML( $xmlString );
        $domRoot = $dom->documentElement;
        $options = $domRoot->getElementsByTagName( 'options' )->item( 0 );

        $importedOptionsNode = $attributeParametersNode->ownerDocument->importNode( $options, true );
        $attributeParametersNode->appendChild( $importedOptionsNode );
        $isMultiSelectNode = $attributeParametersNode->ownerDocument->createElement( 'is-multiselect', $isMultipleSelection );
        $attributeParametersNode->appendChild( $isMultiSelectNode );
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $options = $attributeParametersNode->getElementsByTagName( 'options' )->item( 0 );

        $doc = new DOMDocument( '1.0', 'utf-8' );
        $root = $doc->createElement( 'ezselection' );
        $doc->appendChild( $root );

        $importedOptions = $doc->importNode( $options, true );
        $root->appendChild( $importedOptions );

        $xml = $doc->saveXML();
        $classAttribute->setAttribute( 'data_text5', $xml );

        if ( $attributeParametersNode->getElementsByTagName( 'is-multiselect' )->item( 0 )->textContent == 0 )
            $classAttribute->setAttribute( 'data_int1', 0 );
        else
            $classAttribute->setAttribute( 'data_int1', 1 );
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }
}

eZDataType::register( eZSelectionType::DATA_TYPE_STRING, "eZSelectionType" );
?>
