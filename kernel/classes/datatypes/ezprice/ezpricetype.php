<?php
//
// Definition of eZPriceType class
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
  \class eZPriceType ezpricetype.php
  \ingroup eZDatatype
  \brief Stores a price (float)

*/

class eZPriceType extends eZDataType
{
    const DATA_TYPE_STRING = "ezprice";
    const INCLUDE_VAT_FIELD = 'data_int1';
    const INCLUDE_VAT_VARIABLE = '_ezprice_include_vat_';
    const VAT_ID_FIELD = 'data_float1';
    const VAT_ID_VARIABLE = '_ezprice_vat_id_';
    const INCLUDED_VAT = 1;
    const EXCLUDED_VAT = 2;

    function eZPriceType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Price", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_float' => 'price' ) ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        // Check "price inc/ex VAT" and "VAT type" fields.
        $vatTypeID = $http->postVariable( $base . '_ezprice_vat_id_' . $contentObjectAttribute->attribute( 'id' ) );
        $vatExInc = $http->postVariable( $base . '_ezprice_inc_ex_vat_' . $contentObjectAttribute->attribute( 'id' ) );
        if ( $vatExInc == 1 && $vatTypeID == -1 )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Dynamic VAT cannot be included.' ) );
            return eZInputValidator::STATE_INVALID;
        }

        // Check price.
        if ( $http->hasPostVariable( $base . "_data_price_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_price_" . $contentObjectAttribute->attribute( "id" ) );

            $locale = eZLocale::instance();
            $data = $locale->internalCurrency( $data );
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            if( !$contentObjectAttribute->validateIsRequired() && ( $data == "" ) )
            {
                return eZInputValidator::STATE_ACCEPTED;
            }
            if ( preg_match( "#^[0-9]+(.){0,1}[0-9]{0,2}$#", $data ) )
                return eZInputValidator::STATE_ACCEPTED;

            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Invalid price.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        else if ( $contentObjectAttribute->validateIsRequired() )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'Input required.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        else
        {
            return eZInputValidator::STATE_ACCEPTED;
        }
    }

    function storeObjectAttribute( $attribute )
    {
    }

    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_float" );
    }

    /*!
     reimp
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
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
    function initializeClassAttribute( $classAttribute )
    {
        if ( $classAttribute->attribute( self::INCLUDE_VAT_FIELD ) == 0 )
            $classAttribute->setAttribute( self::INCLUDE_VAT_FIELD, self::INCLUDED_VAT );
        $classAttribute->store();
    }
    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $isVatIncludedVariable = $base . self::INCLUDE_VAT_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $isVatIncludedVariable ) )
        {
            $isVatIncluded = $http->postVariable( $isVatIncludedVariable );
            $classAttribute->setAttribute( self::INCLUDE_VAT_FIELD, $isVatIncluded );
        }
        $vatIDVariable = $base . self::VAT_ID_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $vatIDVariable  ) )
        {
            $vatID = $http->postVariable( $vatIDVariable  );
            $classAttribute->setAttribute( self::VAT_ID_FIELD, $vatID );
        }
        return true;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $data = $http->postVariable( $base . "_data_price_" . $contentObjectAttribute->attribute( "id" ) );
        $vatType = $http->postVariable( $base . '_ezprice_vat_id_' . $contentObjectAttribute->attribute( 'id' ) );
        $vatExInc = $http->postVariable( $base . '_ezprice_inc_ex_vat_' . $contentObjectAttribute->attribute( 'id' ) );

        $locale = eZLocale::instance();
        $data = $locale->internalCurrency( $data );

        $data_text = $vatType . ',' . $vatExInc;

        $contentObjectAttribute->setAttribute( "data_float", $data );
        $contentObjectAttribute->setAttribute( 'data_text', $data_text );

        return true;
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
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
    function classAttributeContent( $classAttribute )
    {
        $contentObjectAttribute = false;
        $price = new eZPrice( $classAttribute, $contentObjectAttribute );
        return $price;
    }

    function contentActionList( $classAttribute )
    {
        return array( array( 'name' => ezi18n( 'kernel/classes/datatypes', 'Add to basket' ),
                             'action' => 'ActionAddToBasket'
                             ),
                      array( 'name' => ezi18n( 'kernel/classes/datatypes', 'Add to wish list' ),
                             'action' => 'ActionAddToWishList'
                             ) );
    }

    function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute( "data_float" );
    }

    function sortKey( $contentObjectAttribute )
    {
        $intPrice = (int)($contentObjectAttribute->attribute( 'data_float' ) * 100.00);
        return $intPrice;
    }

    function sortKeyType()
    {
        return 'int';
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
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


    function fromString( $contentObjectAttribute, $string )
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

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $price = $classAttribute->content();
        if ( $price )
        {
            $vatIncluded = $price->attribute( 'is_vat_included' );
            $vatTypes = $price->attribute( 'vat_type' );

            $dom = $attributeParametersNode->ownerDocument;
            $vatIncludedNode = $dom->createElement( 'vat-included' );
            $vatIncludedNode->setAttribute( 'is-set', $vatIncluded ? 'true' : 'false' );
            $attributeParametersNode->appendChild( $vatIncludedNode );
            $vatTypeNode = $dom->createElement( 'vat-type' );
            $chosenVatType = $classAttribute->attribute( 'data_float1' );
            $gotVat = false;
            foreach ( $vatTypes as $vatType )
            {
                $id = $vatType->attribute( 'id' );
                if ( $id == $chosenVatType )
                {
                    $vatTypeNode->setAttribute( 'name', $vatType->attribute( 'name' ) );
                    $vatTypeNode->setAttribute( 'percentage', $vatType->attribute( 'percentage' ) );
                    $gotVat = true;
                    break;
                }
            }
            if ( $gotVat )
                $attributeParametersNode->appendChild( $vatTypeNode );
        }
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $vatNode = $attributeParametersNode->getElementsByTagName( 'vat-included' )->item( 0 );
        $vatIncluded = strtolower( $vatNode->getAttribute( 'is-set' ) ) == 'true';
        if ( $vatIncluded )
            $vatIncluded = self::INCLUDED_VAT;
        else
            $vatIncluded = self::EXCLUDED_VAT;

        $classAttribute->setAttribute( self::INCLUDE_VAT_FIELD, $vatIncluded );
        $vatTypeNode = $attributeParametersNode->getElementsByTagName( 'vat-type' )->item( 0 );
        $vatName = $vatTypeNode->getAttribute( 'name' );
        $vatPercentage = $vatTypeNode->getAttribute( 'percentage' );
        $vatID = false;
        $vatTypes = eZVatType::fetchList();
        foreach ( $vatTypes as $vatType )
        {
            if ( $vatType->attribute( 'name' ) == $vatName and
                 $vatType->attribute( 'percentage' ) == $vatPercentage )
            {
                $vatID = $vatType->attribute( 'id' );
                break;
            }
        }
        if ( !$vatID )
        {
            $vatType = eZVatType::create();
            $vatType->setAttribute( 'name', $vatName );
            $vatType->setAttribute( 'percentage', $vatPercentage );
            $vatType->store();
            $vatID = $vatType->attribute( 'id' );
        }
        $classAttribute->setAttribute( self::VAT_ID_FIELD, $vatID );
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }
}

eZDataType::register( eZPriceType::DATA_TYPE_STRING, "eZPriceType" );

?>
