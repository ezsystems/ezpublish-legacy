<?php
//
// Definition of eZEnumtype class
//
// Created on: <24-ßÂ-2002 14:33:53 wy>
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


/*! \file ezenumtype.php
*/

/*!
  \class eZEnumType ezenumtype.php
  \ingroup eZKernel

*/

include_once( 'kernel/classes/ezdatatype.php' );
include_once( "kernel/classes/datatypes/ezenum/ezenum.php" );
include_once( "kernel/classes/ezcontentobjectattribute.php" );
define( "EZ_DATATYPESTRING_ENUM", "ezenum" );
define( 'EZ_DATATYPESTRING_ENUM_ISMULTIPLE_FIELD', 'data_int1' );
define( 'EZ_DATATYPESTRING_ENUM_ISMULTIPLE_VARIABLE', '_ezenum_ismultiple_value_' );
define( 'EZ_DATATYPESTRING_ENUM_ISOPTION_FIELD', 'data_int2' );
define( 'EZ_DATATYPESTRING_ENUM_ISOPTION_VARIABLE', '_ezenum_isoption_value_' );

class eZEnumType extends eZDataType
{
    /*!
     Constructor
    */
    function eZEnumType()
    {
         $this->eZDataType( EZ_DATATYPESTRING_ENUM, ezi18n( 'kernel/classes/datatypes', "Enum", 'Datatype name' ),
                            array( 'serialize_supported' => true ) );
    }

    function hasAttribute( $name )
    {
        return eZDataType::hasAttribute( $name );
    }

    function &attribute( $name )
    {
        return eZDataType::attribute( $name );
    }

    /*!
     Sets value according to current version
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion !== false )
        {
            $contentObjectAttributeID = $originalContentObjectAttribute->attribute( "id" );
            $contentObjectAttributeVersion = $contentObjectAttribute->attribute( "version" );
            $newVersionEnumObject =& eZEnumObjectValue::fetchAllElements( $contentObjectAttributeID, $currentVersion );

            for ( $i = 0; $i < count( $newVersionEnumObject ); ++$i )
            {
                $enumobjectvalue =  $newVersionEnumObject[$i];
                $enumobjectvalue->setAttribute( 'contentobject_attribute_id', $contentObjectAttribute->attribute( 'id' ) );
                $enumobjectvalue->setAttribute( "contentobject_attribute_version",  $contentObjectAttributeVersion );
                $enumobjectvalue->store();
            }
        }
    }

    /*!
     \reimp
    */
    function cloneClassAttribute( &$oldClassAttribute, &$newClassAttribute )
    {
        $oldContentClassAttributeID = $oldClassAttribute->attribute( "id" );
        $oldEnums =& eZEnumValue::fetchAllElements( $oldContentClassAttributeID, 0 );

        foreach ( $oldEnums as $oldEnum )
        {
            $enum =& $oldEnum->clone();
            $enum->setAttribute( "contentclass_attribute_id", $newClassAttribute->attribute( 'id' ) );
            $enum->setAttribute( "contentclass_attribute_version", $newClassAttribute->attribute( 'version' ) );
            $enum->store();
        }
    }

    /*!
     Set class attribute value for template version
    */
    function initializeClassAttribute( &$classAttribute )
    {
        $contentClassAttributeID = $classAttribute->attribute( "id" );
        $enums =& eZEnumValue::fetchAllElements( $contentClassAttributeID, 0 );

        foreach ( $enums as $enum )
        {
            $enum->setAttribute( "contentclass_attribute_version", 1 );
            $enum->store();
        }
    }

    /*!
     Delete stored object attribute
    */
    function deleteStoredObjectAttribute( &$contentObjectAttribute, $version = null )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
        eZEnumObjectValue::removeAllElements( $contentObjectAttributeID, $version );

    }

    /*!
     Delete stored class attribute
    */
    function deleteStoredClassAttribute( &$contentClassAttribute, $version = null )
    {
        $contentClassAttributeID = $contentClassAttribute->attribute( "id" );
        eZEnumValue::removeAllElements( $contentClassAttributeID, $version );

    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
        $contentObjectAttributeVersion = $contentObjectAttribute->attribute( "version" );
        $enumID =  $base . "_data_enumid_" . $contentObjectAttributeID;
        $enumElement = $base . "_data_enumelement_" . $contentObjectAttributeID;
        $enumValue = $base . "_data_enumvalue_" . $contentObjectAttributeID;
        $selectedEnumElement = $base . "_select_data_enumelement_" . $contentObjectAttributeID;
        if ( $http->hasPostVariable( $enumID ) &&
             $http->hasPostVariable( $enumElement ) &&
             $http->hasPostVariable( $enumValue ) )
        {
            $array_enumID = $http->postVariable( $enumID );
            $array_enumElement = $http->postVariable( $enumElement );
            $array_enumValue = $http->postVariable( $enumValue );

            if ( $http->hasPostVariable( $selectedEnumElement ) )
                $array_selectedEnumElement = $http->postVariable( $selectedEnumElement );
            else
                $array_selectedEnumElement = null;

            // Remove stored enumerations before we store new enumerations
            eZEnum::removeObjectEnumerations( $contentObjectAttributeID, $contentObjectAttributeVersion );
            for ( $i=0;$i<count( $array_enumElement );$i++ )
            {
                for ( $j=0;$j<count( $array_selectedEnumElement );$j++ )
                {
                    if ( $array_enumElement[$i] === $array_selectedEnumElement[$j] )
                    {
                        $eID = $array_enumID[$i];
                        $eElement = $array_enumElement[$i];
                        $eValue = $array_enumValue[$i];
                        eZEnum::storeObjectEnumeration( $contentObjectAttributeID,
                                                        $contentObjectAttributeVersion,
                                                        $eID,
                                                        $eElement,
                                                        $eValue );
                    }
                }
            }
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
        if ( $http->hasPostVariable( $base . "_data_enumid_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $array_enumID = $http->postVariable( $base . "_data_enumid_" . $contentObjectAttribute->attribute( "id" ) );
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();
            if ( ( $classAttribute->attribute( "is_required" ) == true )  &&
                 ( !$http->hasPostVariable( $base . "_select_data_enumelement_" . $contentObjectAttribute->attribute( "id" ) ) ) )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'At least one field should be chosen.' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Does nothing since it has been stored.
     See fetchObjectAttributeHTTPInput for the actual storing.
    */
    function storeObjectAttribute( &$contentObjectAttribute )
    {
    }

    /*!
	 Returns actual the class attribute content.
	*/
	function &objectAttributeContent( &$contentObjectAttribute )
	{
        $contentObjectAttributeID =& $contentObjectAttribute->attribute( "id" );
        $contentObjectAttributeVersion =& $contentObjectAttribute->attribute( "version" );
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
        $id = $contentClassAttribute->attribute( "id" );
        $version = $contentClassAttribute->attribute( "version" );
        $ismultiple = $contentClassAttribute->attribute( "data_int1" );
        $isoption = $contentClassAttribute->attribute( "data_int2" );
        $enum = new eZEnum( $id, $version );
        $enum->setIsmultipleValue( $ismultiple );
        $enum->setIsoptionValue( $isoption );
        $enum->setObjectEnumValue( $contentObjectAttributeID, $contentObjectAttributeVersion );
        return $enum;
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$contentClassAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$contentClassAttribute )
    {
        $ismultiple = $base . EZ_DATATYPESTRING_ENUM_ISMULTIPLE_VARIABLE . $contentClassAttribute->attribute( 'id' );
        $isoption = $base . EZ_DATATYPESTRING_ENUM_ISOPTION_VARIABLE . $contentClassAttribute->attribute( 'id' );
        $enumID =  $base . "_data_enumid_" . $contentClassAttribute->attribute( "id" );
        $enumElement = $base . "_data_enumelement_" . $contentClassAttribute->attribute( "id" );
        $enumValue = $base . "_data_enumvalue_" . $contentClassAttribute->attribute( "id" );
        $enumRemove = $base . "_data_enumremove_" . $contentClassAttribute->attribute( "id" );
        $version = $contentClassAttribute->attribute( "version" );
        if ( $http->hasPostVariable( $enumID ) &&
             $http->hasPostVariable( $enumElement ) &&
             $http->hasPostVariable( $enumValue ) &&
             !($http->hasPostVariable( $enumRemove ) ) )
        {
            if ( $http->hasPostVariable( $ismultiple ) )
            {
                $contentClassAttribute->setAttribute( EZ_DATATYPESTRING_ENUM_ISMULTIPLE_FIELD, "1" );
            }else{
                $contentClassAttribute->setAttribute( EZ_DATATYPESTRING_ENUM_ISMULTIPLE_FIELD, "0" );
            }
            if ( $http->hasPostVariable( $isoption ) )
            {
                 $optionValue = $http->postVariable( $isoption );
                 if( $optionValue == 1 )
                 {
                     $contentClassAttribute->setAttribute( EZ_DATATYPESTRING_ENUM_ISOPTION_FIELD, "1" );
                 }
                 else
                 {
                     $contentClassAttribute->setAttribute( EZ_DATATYPESTRING_ENUM_ISOPTION_FIELD, "0" );
                 }
            }
            $array_enumID = $http->postVariable(  $enumID );
            $array_enumElement = $http->postVariable( $enumElement );
            $array_enumValue = $http->postVariable( $enumValue );
            $enum =& $contentClassAttribute->content();
            $enum->setValue( $array_enumID, $array_enumElement, $array_enumValue, $version );
            $contentClassAttribute->setContent( $enum );
        }
    }

    function storeClassAttribute( &$contentClassAttribute, $version )
    {
        $enum =& $contentClassAttribute->content();
        $enum->setVersion( $version );
    }

    function storeDefinedClassAttribute( &$contentClassAttribute )
    {
        $id = $contentClassAttribute->attribute( "id" );
        $version = 1;
        $enumVersion1 = new eZEnum( $id, $version );
        $enumVersion1->setVersion( 0 );
        eZEnumValue::removeAllElements( $id, 1 );
    }

    /*!
     Returns the content.
    */
    function &classAttributeContent( &$contentClassAttribute )
    {
        $id = $contentClassAttribute->attribute( "id" );
        $version = $contentClassAttribute->attribute( "version" );
        $enum = new eZEnum( $id, $version );
        return $enum;
    }

    /*!
    */
    function customClassAttributeHTTPAction( $http, $action, &$contentClassAttribute )
    {
        $id = $contentClassAttribute->attribute( "id" );
        switch ( $action )
        {
            case "new_enumelement" :
            {
                $enum =& $contentClassAttribute->content( );
                $enum->addEnumeration("");
                $contentClassAttribute->setContent( $enum );
            }break;
            case "remove_selected" :
            {
                $version = $contentClassAttribute->attribute( "version" );
                $postvarname = "ContentClass" . "_data_enumremove_" . $contentClassAttribute->attribute( "id" );
                $array_remove = $http->postVariable( $postvarname );
                foreach( $array_remove as $enumid )
                {
                    eZEnum::removeEnumeration( $id, $enumid, $version );
                }
            }break;
            default :
            {
                eZDebug::writeError( "Unknown custom HTTP action: " . $action, "eZEnumType" );
            }break;
        }
    }

    /*!
     Returns the object attribute title.
    */
    function title( &$contentObjectAttribute, $name )
    {
        $enum = $this->objectAttributeContent( $contentObjectAttribute );

        $enumObjectList = $enum->attribute( 'enumobject_list' );

        $value = '';

        foreach ( $enumObjectList as $count => $enumObjectValue )
        {
            if ( $count != 0 )
                $value .= ", ";
            $value .= $enumObjectValue->attribute( 'enumelement' );
        }

        return $value;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        $contentObjectAttributeID =& $contentObjectAttribute->attribute( "id" );
        $contentObjectAttributeVersion =& $contentObjectAttribute->attribute( "version" );
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
        $id = $contentClassAttribute->attribute( "id" );
        $version = $contentClassAttribute->attribute( "version" );
        $ismultiple = $contentClassAttribute->attribute( "data_int1" );
        $isoption = $contentClassAttribute->attribute( "data_int2" );

        $enum = new eZEnum( $id, $version );
        $enum->setIsmultipleValue( $ismultiple );
        $enum->setIsoptionValue( $isoption );
        $enum->setObjectEnumValue( $contentObjectAttributeID, $contentObjectAttributeVersion );

        $return = "";
        foreach ( $enum->attribute( 'enumobject_list' ) as $enumElement )
        {
            $return .= $enumElement->attribute( 'enumvalue' ) . " ";
            $return .= $enumElement->attribute( 'enumelement' ) . " ";
        }
        return $return;
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     \return a DOM representation of the content object attribute
    */
    function &serializeContentObjectAttribute( $contentObjectAttribute )
    {
        include_once( 'lib/ezxml/classes/ezdomdocument.php' );
        include_once( 'lib/ezxml/classes/ezdomnode.php' );

        $contentObjectAttributeID =& $contentObjectAttribute->attribute( "id" );
        $contentObjectAttributeVersion =& $contentObjectAttribute->attribute( "version" );
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
        $id = $contentClassAttribute->attribute( "id" );
        $version = $contentClassAttribute->attribute( "version" );
        $ismultiple = $contentClassAttribute->attribute( "data_int1" );
        $isoption = $contentClassAttribute->attribute( "data_int2" );

        $enum = new eZEnum( $id, $version );
        $enum->setIsmultipleValue( $ismultiple );
        $enum->setIsoptionValue( $isoption );
        $enum->setObjectEnumValue( $contentObjectAttributeID, $contentObjectAttributeVersion );

        $node = new eZDOMNode();
        $node->setName( 'attribute' );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $contentObjectAttribute->contentClassAttributeName() ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'type', 'ezenum' ) );

        foreach ( $enum->attribute( 'enumobject_list' ) as $enumElement )
        {
            unset( $elementNode );
            $elementNode = new eZDOMNode();
            $elementNode->setName( 'enum-element' );

            $elementNode->appendAttribute( eZDOMDocument::createAttributeNode( 'id', $enumElement->attribute( 'enumid' ) ) );
            $elementNode->appendAttribute( eZDOMDocument::createAttributeNode( 'value', $enumElement->attribute( 'enumvalue' ) ) );
            $node->appendChild( $elementNode );
        }


        return $node;
    }

    /*!
     \reimp
    */
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $isOption = $classAttribute->attribute( EZ_DATATYPESTRING_ENUM_ISOPTION_FIELD );
        $isMultiple = $classAttribute->attribute( EZ_DATATYPESTRING_ENUM_ISMULTIPLE_FIELD );
        $content =& $classAttribute->attribute( 'content' );
        $enumList =& $content->attribute( 'enum_list' );
        $attributeParametersNode->appendAttribute( eZDOMDocument::createAttributeNode( 'is-option', $isOption ? 'true' : 'false' ) );
        $attributeParametersNode->appendAttribute( eZDOMDocument::createAttributeNode( 'is-multiple', $isMultiple ? 'true' : 'false' ) );
        $elementListNode =& eZDOMDocument::createElementNode( 'elements' );
        $attributeParametersNode->appendChild( $elementListNode );
        for ( $i = 0; $i < count( $enumList ); ++$i )
        {
            $enumElement =& $enumList[$i];
            $elementNode =& eZDOMDocument::createElementNode( 'element',
                                                              array( 'id' => $enumElement->attribute( 'id' ),
                                                                     'name' => $enumElement->attribute( 'enumelement' ),
                                                                     'value' => $enumElement->attribute( 'enumvalue' ) ) );
            $elementListNode->appendChild( $elementNode );
        }
    }

    /*!
     \reimp
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $isOption = strtolower( $attributeParametersNode->attributeValue( 'is-option' ) ) == 'true';
        $isMultiple = strtolower( $attributeParametersNode->attributeValue( 'is-multiple' ) ) == 'true';
        $classAttribute->setAttribute( EZ_DATATYPESTRING_ENUM_ISOPTION_FIELD, $isOption );
        $classAttribute->setAttribute( EZ_DATATYPESTRING_ENUM_ISMULTIPLE_FIELD, $isMultiple );

        $enum = new eZEnum( $classAttribute->attribute( 'id' ), $classAttribute->attribute( 'version' ) );
        $elementListNode =& $attributeParametersNode->elementByName( 'elements' );
        $elementList =& $elementListNode->children();
        foreach ( array_keys( $elementList ) as $elementKey )
        {
            $element =& $elementList[$elementKey];
            $elementID = $element->attributeValue( 'id' );
            $elementName = $element->attributeValue( 'name' );
            $elementValue = $element->attributeValue( 'value' );
            $value =& eZEnumValue::create( $classAttribute->attribute( 'id' ),
                                           $classAttribute->attribute( 'version' ),
                                           $elementName );
            $value->setAttribute( 'enumvalue', $elementValue );
            $value->store();
            $enum->addEnumerationValue( $value );
        }
    }
}
eZDataType::register( EZ_DATATYPESTRING_ENUM, "ezenumtype" );

?>
