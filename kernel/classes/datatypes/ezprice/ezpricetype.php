<?php
//
// Definition of eZPriceType class
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
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZPriceType ezpricetype.php
  \ingroup eZDatatype
  \brief Stores a price (float)

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "kernel/classes/datatypes/ezprice/ezprice.php" );

define( "EZ_DATATYPESTRING_PRICE", "ezprice" );
define( 'EZ_DATATYPESTRING_INCLUDE_VAT_FIELD', 'data_int1' );
define( 'EZ_DATATYPESTRING_INCLUDE_VAT_VARIABLE', '_ezprice_include_vat_' );
define( 'EZ_DATATYPESTRING_VAT_ID_FIELD', 'data_float1' );
define( 'EZ_DATATYPESTRING_VAT_ID_VARIABLE', '_ezprice_vat_id_' );
define( "EZ_PRICE_INCLUDED_VAT", 1 );
define( "EZ_PRICE_EXCLUDED_VAT", 2 );

class eZPriceType extends eZDataType
{
    function eZPriceType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_PRICE, ezi18n( 'kernel/classes/datatypes', "Price", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_float' => 'price' ) ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_price_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_price_" . $contentObjectAttribute->attribute( "id" ) );

            include_once( 'lib/ezlocale/classes/ezlocale.php' );
            $locale =& eZLocale::instance();
            $data =& $locale->internalCurrency( $data );

            $classAttribute =& $contentObjectAttribute->contentClassAttribute();
            if( ( $classAttribute->attribute( "is_required" ) == false ) &&  ( $data == "" ) )
            {
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            if ( preg_match( "#^[0-9]+(.){0,1}[0-9]{0,2}$#", $data ) )
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;

            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        else
        {
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        }
    }

    function storeObjectAttribute( &$attribute )
    {
    }

    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_float" );
    }

    /*!
     Set default class attribute value
    */
    function initializeClassAttribute( &$classAttribute )
    {
        if ( $classAttribute->attribute( EZ_DATATYPESTRING_INCLUDE_VAT_FIELD ) == 0 )
            $classAttribute->setAttribute( EZ_DATATYPESTRING_INCLUDE_VAT_FIELD, EZ_PRICE_INCLUDED_VAT );
        $classAttribute->store();
    }
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $isVatIncludedVariable = $base . EZ_DATATYPESTRING_INCLUDE_VAT_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $isVatIncludedVariable ) )
        {
            $isVatIncluded = $http->postVariable( $isVatIncludedVariable );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_INCLUDE_VAT_FIELD, $isVatIncluded );
        }
        $vatIDVariable = $base . EZ_DATATYPESTRING_VAT_ID_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $vatIDVariable  ) )
        {
            $vatID = $http->postVariable( $vatIDVariable  );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_VAT_ID_FIELD, $vatID );
        }
        return true;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $data = $http->postVariable( $base . "_data_price_" . $contentObjectAttribute->attribute( "id" ) );

        include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $locale =& eZLocale::instance();
        $data =& $locale->internalCurrency( $data );

        $contentObjectAttribute->setAttribute( "data_float", $data );
        return true;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        // return $contentObjectAttribute->attribute( "data_float" );
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        $storedPrice = $contentObjectAttribute->attribute( "data_float" );
        $price = new eZPrice( $classAttribute, $contentObjectAttribute, $storedPrice );
        return $price;
    }

    /*!
     Returns class content.
    */
    function &classAttributeContent( &$classAttribute )
    {
        $contentObjectAttribute = false;
        $price = new eZPrice( $classAttribute, $contentObjectAttribute );
        return $price;
    }

    function contentActionList( )
    {
        return array( array( 'name' => ezi18n( 'kernel/classes/datatypes', 'Add to basket' ),
                             'action' => 'ActionAddToBasket'
                             ),
                      array( 'name' => ezi18n( 'kernel/classes/datatypes', 'Add to wish list' ),
                             'action' => 'ActionAddToWishList'
                             ) );
    }

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
        $price =& $classAttribute->content();
        if ( $price )
        {
            $vatIncluded = $price->attribute( 'is_vat_included' );
            $vatTypes = $price->attribute( 'vat_type' );
            $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'vat-included',
                                                                                     array( 'is-set' => $vatIncluded ? 'true' : 'false' ) ) );
            $vatTypeNode =& eZDOMDocument::createElementNode( 'vat-type' );
            $chosenVatType = $classAttribute->attribute( 'data_float1' );
            $gotVat = false;
            foreach ( $vatTypes as $vatType )
            {
                $id = $vatType->attribute( 'id' );
                if ( $id == $chosenVatType )
                {
                    $vatTypeNode->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $vatType->attribute( 'name' ) ) );
                    $vatTypeNode->appendAttribute( eZDOMDocument::createAttributeNode( 'percentage', $vatType->attribute( 'percentage' ) ) );
                    $gotVat = true;
                    break;
                }
            }
            if ( $gotVat )
                $attributeParametersNode->appendChild( $vatTypeNode );
        }
    }

    /*!
     \reimp
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $vatNode =& $attributeParametersNode->elementByName( 'vat-included' );
        $vatIncluded = strtolower( $vatNode->attributeValue( 'is-set' ) ) == 'true';
        $classAttribute->setAttribute( EZ_DATATYPESTRING_INCLUDE_VAT_FIELD, $vatIncluded );
        $vatTypeNode =& $attributeParametersNode->elementByName( 'vat-type' );
        $vatName = $vatTypeNode->attributeValue( 'name' );
        $vatPercentage = $vatTypeNode->attributeValue( 'percentage' );
        $vatID = false;
        $vatTypes =& eZVATType::fetchList();
        foreach ( array_keys( $vatTypes ) as $vatTypeKey )
        {
            $vatType =& $vatTypes[$vatTypeKey];
            if ( $vatType->attribute( 'name' ) == $vatName and
                 $vatType->attribute( 'percentage' ) == $vatPercentage )
            {
                $vatID = $vatType->attribute( 'id' );
                break;
            }
        }
        if ( !$vatID )
        {
            $vatType =& eZVATType::create();
            $vatType->setAttribute( 'name', $vatName );
            $vatType->setAttribute( 'percentage', $vatPercentage );
            $vatType->store();
            $vatID = $vatType->attribute( 'id' );
        }
        $classAttribute->setAttribute( EZ_DATATYPESTRING_VAT_ID_FIELD, $vatID );
    }
}

eZDataType::register( EZ_DATATYPESTRING_PRICE, "ezpricetype" );

?>
