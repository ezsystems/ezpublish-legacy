<?php
//
// Definition of eZPriceType class
//
// Created on: <26-Apr-2002 16:54:35 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
        // Check "price inc/ex VAT" and "VAT type" fields.
        $vatTypeID = $http->postVariable( $base . '_ezprice_vat_id_' . $contentObjectAttribute->attribute( 'id' ) );
        $vatExInc = $http->postVariable( $base . '_ezprice_inc_ex_vat_' . $contentObjectAttribute->attribute( 'id' ) );
        if ( $vatExInc == 1 && $vatTypeID == -1 )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Dynamic VAT cannot be included.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }

        // Check price.
        if ( $http->hasPostVariable( $base . "_data_price_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_price_" . $contentObjectAttribute->attribute( "id" ) );

            include_once( 'lib/ezlocale/classes/ezlocale.php' );
            $locale =& eZLocale::instance();
            $data = $locale->internalCurrency( $data );
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();
            if( !$contentObjectAttribute->validateIsRequired() && ( $data == "" ) )
            {
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            if ( preg_match( "#^[0-9]+(.){0,1}[0-9]{0,2}$#", $data ) )
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;

            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Invalid price.' ) );
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
     reimp
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $dataFloat = $originalContentObjectAttribute->attribute( "data_float" );
            $contentObjectAttribute->setAttribute( "data_float", $dataFloat );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
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
        $vatType = $http->postVariable( $base . '_ezprice_vat_id_' . $contentObjectAttribute->attribute( 'id' ) );
        $vatExInc = $http->postVariable( $base . '_ezprice_inc_ex_vat_' . $contentObjectAttribute->attribute( 'id' ) );

        include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $locale =& eZLocale::instance();
        $data = $locale->internalCurrency( $data );

        $data_text = $vatType . ',' . $vatExInc;

        $contentObjectAttribute->setAttribute( "data_float", $data );
        $contentObjectAttribute->setAttribute( 'data_text', $data_text );

        return true;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        $storedPrice = $contentObjectAttribute->attribute( "data_float" );
        $price = new eZPrice( $classAttribute, $contentObjectAttribute, $storedPrice );

        if ( $contentObjectAttribute->attribute( 'data_text' ) != '' )
        {
            list( $vatType, $vatExInc ) = explode( ',', $contentObjectAttribute->attribute( "data_text" ), 2 );

            $price->setAttribute( 'selected_vat_type', $vatType );
            $price->setAttribute( 'is_vat_included', $vatExInc );
        }

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

    /*!
     \reimp
    */
    function sortKey( &$contentObjectAttribute )
    {
        $intPrice = (int)($contentObjectAttribute->attribute( 'data_float' ) * 100.00);
        return $intPrice;
    }

    /*!
     \reimp
    */
    function sortKeyType()
    {
        return 'int';
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return true;
    }

    function toString( $contentObjectAttribute )
    {

        $price = $contentObjectAttribute->attribute( 'content' );
        $vatType =$price->attribute( 'selected_vat_type' );

        $priceStr = implode( '|', array( $price->attribute( 'price' ), $vatType->attribute( 'id' ) , ($price->attribute( 'is_vat_included' ) )? 1:0 ) );
        return $priceStr;
    }


    function fromString( &$contentObjectAttribute, $string )
    {
        if ( $string == '' )
            return true;

        $priceData = explode( '|', $string );
        if ( count( $priceData ) != 3 )
            return false;

        $dataText = $priceData[1] . ',' . $priceData[2];
        $price = $priceData[0];

        $contentObjectAttribute->setAttribute( "data_float", $price );
        $contentObjectAttribute->setAttribute( 'data_text', $dataText );

        return true;
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $price =& $classAttribute->content();
        if ( $price )
        {
            $vatIncluded = $price->attribute( 'is_vat_included' );
            $vatTypes = $price->attribute( 'vat_type' );
            $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'vat-included',
                                                                                     array( 'is-set' => $vatIncluded ? 'true' : 'false' ) ) );
            $vatTypeNode = eZDOMDocument::createElementNode( 'vat-type' );
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
    function unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $vatNode =& $attributeParametersNode->elementByName( 'vat-included' );
        $vatIncluded = strtolower( $vatNode->attributeValue( 'is-set' ) ) == 'true';
        if ( $vatIncluded )
            $vatIncluded = EZ_PRICE_INCLUDED_VAT;
        else
            $vatIncluded = EZ_PRICE_EXCLUDED_VAT;

        $classAttribute->setAttribute( EZ_DATATYPESTRING_INCLUDE_VAT_FIELD, $vatIncluded );
        $vatTypeNode =& $attributeParametersNode->elementByName( 'vat-type' );
        $vatName = $vatTypeNode->attributeValue( 'name' );
        $vatPercentage = $vatTypeNode->attributeValue( 'percentage' );
        $vatID = false;
        $vatTypes = eZVATType::fetchList();
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
            $vatType = eZVATType::create();
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
