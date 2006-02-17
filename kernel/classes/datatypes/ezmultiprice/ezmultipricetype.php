<?php
//
// Definition of eZMultiPriceType class
//
// Created on: <04-Nov-2005 16:54:35 dl>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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
  \class eZMultiPriceType ezmultipricetype.php
  \ingroup eZMultiDatatype
  \brief Stores a price in multicurrency.

*/

include_once( 'kernel/classes/ezdatatype.php' );
include_once( 'kernel/classes/datatypes/ezmultiprice/ezmultiprice.php' );

define( 'EZ_DATATYPESTRING_MULTIPRICE', 'ezmultiprice' );
define( 'EZ_DATATYPESTRING_CURRENCY_CODE_FIELD', 'data_text1' );
define( 'EZ_DATATYPESTRING_CURRENCY_CODE_VARIABLE', '_ezmultiprice_currency_code_' );
define( 'EZ_DATATYPESTRING_MULTIPRICE_INCLUDE_VAT_FIELD', 'data_int1' );
define( 'EZ_DATATYPESTRING_MULTIPRICE_INCLUDE_VAT_VARIABLE', '_ezmultiprice_include_vat_' );
define( 'EZ_DATATYPESTRING_MULTIPRICE_VAT_ID_FIELD', 'data_float1' );
define( 'EZ_DATATYPESTRING_MULTIPRICE_VAT_ID_VARIABLE', '_ezmultiprice_vat_id_' );
define( 'EZ_MULTIPRICE_INCLUDED_VAT', 1 );
define( 'EZ_MULTIPRICE_EXCLUDED_VAT', 2 );

class eZMultiPriceType extends eZDataType
{
    function eZMultiPriceType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_MULTIPRICE, ezi18n( 'kernel/classes/datatypes', 'Multi-price', 'Datatype name' ),
                           array() );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_price_array_' . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $customPriceList = $http->postVariable( $base . '_price_array_' . $contentObjectAttribute->attribute( "id" ) );
            foreach ( $customPriceList as $currencyCode => $value )
            {
                if( $contentObjectAttribute->validateIsRequired() || ( $value != '' ) )
                {
                    if ( !preg_match( "#^[0-9]+(.){0,1}[0-9]{0,2}$#", $value ) )
                    {
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             "Invalid price for '%currencyCode' currency ",
                                                                             false,
                                                                             array( '%currencyCode' => $currencyCode ) ) );
                        return EZ_INPUT_VALIDATOR_STATE_INVALID;
                    }
                }
            }
        }

        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    function storeObjectAttribute( &$attribute )
    {
        $multiprice =& $attribute->attribute( 'content' );
        $multiprice->store();
    }

    /* ???????????????
    function metaData( $contentObjectAttribute )
    {
        return eZPriceType::metaData( $contentObjectAttribute );
        //return $contentObjectAttribute->attribute( "data_float" );
    }
    */

    /*!
     Set default class attribute value
    */
    function initializeClassAttribute( &$classAttribute )
    {
        if ( $classAttribute->attribute( EZ_DATATYPESTRING_MULTIPRICE_INCLUDE_VAT_FIELD ) == 0 )
            $classAttribute->setAttribute( EZ_DATATYPESTRING_MULTIPRICE_INCLUDE_VAT_FIELD, EZ_MULTIPRICE_INCLUDED_VAT );
        $classAttribute->store();
    }

    /*!
     Set default object attribute value.
    */
    function postInitializeObjectAttribute( &$objectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        $contentClassAttribute =& $objectAttribute->contentClassAttribute();
        $multiprice = new eZMultiPrice( $contentClassAttribute, $objectAttribute );

        if ( $currentVersion == false )
        {
            $defaultCurrency = $contentClassAttribute->attribute( EZ_DATATYPESTRING_CURRENCY_CODE_FIELD );
            $multiprice->setCustomPrice( $defaultCurrency, '0.00' );
            $multiprice->updateAutoPriceList();
            $multiprice->store();
        }
        else
        {
            $originalMultiprice =& $originalContentObjectAttribute->content();
            $multiprice = new eZMultiPrice( $contentClassAttribute, $objectAttribute );

            $priceList =& $originalMultiprice->priceList();
            foreach ( $priceList as $price )
                $multiprice->setPriceByCurrency( $price->attribute( 'currency_code' ), $price->attribute( 'value' ), $price->attribute( 'type') );

            $multiprice->store();
        }
    }

    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $currencyCodeVariable = $base . EZ_DATATYPESTRING_CURRENCY_CODE_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $currencyCodeVariable ) )
        {
            $currencyCode = $http->postVariable( $currencyCodeVariable );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_CURRENCY_CODE_FIELD, $currencyCode );
        }

        $isVatIncludedVariable = $base . EZ_DATATYPESTRING_MULTIPRICE_INCLUDE_VAT_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $isVatIncludedVariable ) )
        {
            $isVatIncluded = $http->postVariable( $isVatIncludedVariable );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_MULTIPRICE_INCLUDE_VAT_FIELD, $isVatIncluded );
        }
        $vatIDVariable = $base . EZ_DATATYPESTRING_MULTIPRICE_VAT_ID_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $vatIDVariable  ) )
        {
            $vatID = $http->postVariable( $vatIDVariable  );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_MULTIPRICE_VAT_ID_FIELD, $vatID );
        }
        return true;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $multiprice =& $contentObjectAttribute->attribute( 'content' );

        $priceArrayName = $base . "_price_array_" . $contentObjectAttribute->attribute( "id" );
        if ( $http->hasPostVariable( $priceArrayName ) )
        {
            $customPriceList = $http->postVariable( $priceArrayName );

            foreach ( $customPriceList as $currencyCode => $value )
                $multiprice->setCustomPrice( $currencyCode, $value );
        }

        $multiprice->updateAutoPriceList();

        $vatType = $http->postVariable( $base . '_ezmultiprice_vat_id_' . $contentObjectAttribute->attribute( 'id' ) );
        $vatExInc = $http->postVariable( $base . '_ezmultiprice_inc_ex_vat_' . $contentObjectAttribute->attribute( 'id' ) );
        $multiprice->setAttribute( 'selected_vat_type', $vatType );
        $multiprice->setAttribute( 'is_vat_included', $vatExInc );

        $data_text = $vatType . ',' . $vatExInc;
        $contentObjectAttribute->setAttribute( 'data_text', $data_text );

        return true;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        $multiprice = new eZMultiPrice( $classAttribute, $contentObjectAttribute );

        if ( $contentObjectAttribute->attribute( 'data_text' ) != '' )
        {
            list( $vatType, $vatExInc ) = explode( ',', $contentObjectAttribute->attribute( 'data_text' ), 2 );

            $multiprice->setAttribute( 'selected_vat_type', $vatType );
            $multiprice->setAttribute( 'is_vat_included', $vatExInc );
        }

        return $multiprice;
    }

    /*!
     Returns class content.
    */
    function &classAttributeContent( &$classAttribute )
    {
        $contentObjectAttribute = false;
        $multiprice = new eZMultiPrice( $classAttribute, $contentObjectAttribute );
        return $multiprice;
    }

    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute )
    {
        switch ( $action )
        {
            case 'set_custom_price' :
            {
                $selectedCurrencyName = 'ContentObjectAttribute' . '_selected_currency_' . $contentObjectAttribute->attribute( 'id' );
                if ( $http->hasPostVariable( $selectedCurrencyName ) )
                {
                    $selectedCurrency = $http->postVariable( $selectedCurrencyName );
                    $multiprice =& $contentObjectAttribute->content();

                    // to keep right order of currency after adding we do 'remove' and 'add'
                    // instead of just '$multiprice->setCustomPrice( $currencyCode, false )'
                    $price =& $multiprice->priceByCurrency( $selectedCurrency );
                    $multiprice->removePriceByCurrency( $selectedCurrency );
                    $multiprice->setCustomPrice( $selectedCurrency, $price->attribute( 'value' ) );

                    $multiprice->store();
                }
            }break;

            case 'remove_prices' :
            {
                $removePriceArrayName = 'ContentObjectAttribute' . '_remove_price_array_' . $contentObjectAttribute->attribute( 'id' );
                if ( $http->hasPostVariable( $removePriceArrayName ) )
                {
                    $removePriceArray = $http->postVariable( $removePriceArrayName );
                    $multiprice =& $contentObjectAttribute->content();

                    foreach( $removePriceArray as $currencyCode => $value )
                        $multiprice->setAutoPrice( $currencyCode, false );

                    $multiprice->updateAutoPriceList();
                    $multiprice->store();
                }
            }break;

            default :
            {
                eZDebug::writeError( 'Unknown custom HTTP action: ' . $action, 'eZMultiPriceType' );
            }break;
        }
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

    /*!
     Clean up stored object attribute
    */
    function deleteStoredObjectAttribute( &$objectAttribute, $version = null )
    {
        $multiprice =& $objectAttribute->content();
        $multiprice->remove( $objectAttribute->attribute( 'id' ), $version );
    }

    function title( &$contentObjectAttribute )
    {
        return '';
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return true;
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        /*
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
        */
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        /*
        $vatNode =& $attributeParametersNode->elementByName( 'vat-included' );
        $vatIncluded = strtolower( $vatNode->attributeValue( 'is-set' ) ) == 'true';
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
        $classAttribute->setAttribute( EZ_DATATYPESTRING_MULTIPRICE_VAT_ID_FIELD, $vatID );
        */
    }

    function customSorting()
    {
        return true;
    }

    function customSortingSQL( $params )
    {
        $multipriceTableAlias = "mp";

        if ( isset( $params['table_alias_suffix'] ) )
            $multipriceTableAlias .= $params['table_alias_suffix'];

        $sql = array( 'from' => '',
                      'where' => '',
                      'sorting_field' => '' );

        $sql['from'] =  "ezmultipricedata $multipriceTableAlias";

        $and = '';
        if ( isset( $params['contentobject_attr_id'] ) )
        {
            $sql['where'] = "
                     $multipriceTableAlias.contentobject_attr_id = {$params['contentobject_attr_id']}";
            $and = ' AND';
        }

        if ( isset( $params['contentobject_attr_version'] ) )
        {
            $sql['where'] .= "
                    $and $multipriceTableAlias.contentobject_attr_version = {$params['contentobject_attr_version']}";
            $and = ' AND';
        }

        if ( !isset( $params['currency_code'] ) )
        {
            include_once( 'kernel/shop/classes/ezshopfunctions.php' );
            $params['currency_code'] = eZShopFunctions::preferredCurrency();
        }

        if ( $params['currency_code'] !== false )
        {
            $sql['where'] .= "
                    $and $multipriceTableAlias.currency_code = '{$params['currency_code']}'";
            $and = ' AND';
        }

        $sql['sorting_field'] = "$multipriceTableAlias.value";

        return $sql;
    }
}

eZDataType::register( EZ_DATATYPESTRING_MULTIPRICE, "ezmultipricetype" );

?>
