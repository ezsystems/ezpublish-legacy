<?php
//
// Definition of eZSelectionType class
//
// Created on: <23-Jul-2003 12:51:27 bf>
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

/*!
  \class   ezselectiontype ezselectiontype.php
  \ingroup eZDatatype
  \brief   Handles the single and multiple selections.
  \date    Wednesday 23 July 2003 12:48:45 pm
  \author  Bård Farstad

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezxml/classes/ezxml.php" );

define( "EZ_DATATYPESTRING_EZ_SELECTION", "ezselection" );

class eZSelectionType extends eZDataType
{
    /*!
      Constructor
    */
    function eZSelectionType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_EZ_SELECTION, ezi18n( 'kernel/classes/datatypes', "Selection", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates all variables given on content class level
     \return EZ_INPUT_VALIDATOR_STATE_ACCEPTED or EZ_INPUT_VALIDATOR_STATE_INVALID if
             the values are accepted or not
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches all variables inputed on content class level
     \return true if fetching of class attributes are successfull, false if not
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $attributeContent =& $this->classAttributeContent( $classAttribute );
        $classAttributeID = $classAttribute->attribute( 'id' );
        $isMultipleSelection = false;

        if ( $http->hasPostVariable( $base . "_ezselection_ismultiple_value_" . $classAttributeID ) )
        {
            $isMultipleSelection = true;
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
                    if ( $removeArray[$currentOptions[$key]['id']] )
                        unset( $currentOptions[$key] );
                }
                $hasPostData = true;
            }
        }

        if ( $hasPostData )
        {

            // Serialize XML
            $doc = new eZDOMDocument( "selection" );
            $root =& $doc->createElementNode( "ezselection" );
            $doc->setRoot( $root );

            $options =& $doc->createElementNode( "options" );

            $root->appendChild( $options );
            foreach ( $currentOptions as $optionArray )
            {
                $optionNode =& $doc->createElementNode( "option" );
                $optionNode->appendAttribute( $doc->createAttributeNode( "id", $optionArray['id'] ) );
                $optionNode->appendAttribute( $doc->createAttributeNode( 'name', $optionArray['name'] ) );

                $options->appendChild( $optionNode );
            }

            $xml =& $doc->toString();

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
     \return EZ_INPUT_VALIDATOR_STATE_ACCEPTED or EZ_INPUT_VALIDATOR_STATE_INVALID if
             the values are accepted or not
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches all variables from the object
     \return true if fetching of class attributes are successfull, false if not
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezselect_selected_array_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $selectOptions =& $http->postVariable( $base . '_ezselect_selected_array_' . $contentObjectAttribute->attribute( 'id' ) );
            $idString = implode( '-', $selectOptions );
            $contentObjectAttribute->setAttribute( 'data_text', $idString );
            return true;
        }
        return false;
    }

    /*!
     Returns the selected options by id.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $idString = $contentObjectAttribute->attribute( 'data_text' );
        return explode( '-', $idString );
    }

    /*!
     Returns the content data for the given content class attribute.
    */
    function &classAttributeContent( &$classAttribute )
    {
        $xml = new eZXML();
        $xmlString =& $classAttribute->attribute( 'data_text5' );
        $dom =& $xml->domTree( $xmlString );
        if ( $dom )
        {
            $options =& $dom->elementsByName( 'option' );
            $optionArray = array();
            foreach ( $options as $optionNode )
            {
                $optionArray[] = array( 'id' => $optionNode->attributeValue( 'id' ),
                                        'name' => $optionNode->attributeValue( 'name' ) );
            }
        }
        else
        {
            $optionArray[] = array( 'id' => 0,
                                    'name' => '' );
        }
        return  array( 'options' => $optionArray,
                       'is_multiselect' => $classAttribute->attribute( 'data_int1' )
                       );
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

    /*!
     Returns the value as it will be shown if this attribute is used in the object name pattern.
    */
    function title( &$contentObjectAttribute )
    {
        $selected = $this->objectAttributeContent( $contentObjectAttribute );
        $classContent = $this->classAttributeContent( $contentObjectAttribute->attribute( 'contentclass_attribute' ) );
        $return = "";
        if ( count( $selected ) == 0)
        {
            return "";
        }

        $count = 0;
        foreach ( $selected as $id )
        {
            /*if ( $id == 0 ) // first object gets id==0, while rest of objects get id with offset from 1
                $id++;
            if ( $count++ != 0 )
                $return .= ', ';
            $return .= $classContent['options'][$id-1]['name'];*/
            if ( $count != 0 )
                $return .= ', ';
            $return .= $classContent['options'][$id]['name'];
            $count++;
        }
        return $return;
    }

    /*!
     \reimp
    */
    function &sortKey( &$contentObjectAttribute )
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
}

eZDataType::register( EZ_DATATYPESTRING_EZ_SELECTION, "ezselectiontype" );
?>
