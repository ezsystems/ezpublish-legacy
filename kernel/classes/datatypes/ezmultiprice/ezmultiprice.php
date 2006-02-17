<?php
//
// Definition of eZMultiPrice class
//
// Created on: <04-Nov-2005 12:26:52 dl>
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

/*! \file ezmultiprice.php
*/

/*!
  \class eZMultiPrice ezmultiprice.php
  \ingroup eZDatatype
  \brief Handles prices in different currencies with VAT and discounts.

  The available attributes are:
  - vat_type
  - current_user
  - is_vat_included
  - selected_vat_type
  - vat_percent
  - inc_vat_price
  - ex_vat_price
  - discount_percent
  - discount_price_inc_vat
  - discount_price_ex_vat
  - has_discount
  - price
*/

include_once( "kernel/shop/classes/ezsimpleprice.php" );
include_once( 'kernel/shop/classes/ezmultipricedata.php' );

define( 'EZ_MULTIPRICE_CALCULATION_TYPE_VAT_INCLUDE', 1 );
define( 'EZ_MULTIPRICE_CALCULATION_TYPE_VAT_EXCLUDE', 2 );
define( 'EZ_MULTIPRICE_CALCULATION_TYPE_DISCOUNT_INCLUDE', 3 );
define( 'EZ_MULTIPRICE_CALCULATION_TYPE_DISCOUNT_EXCLUDE', 4 );

class eZMultiPrice extends eZSimplePrice
{
    /*!
     Constructor
    */
    function eZMultiPrice( &$classAttribute, &$contentObjectAttribute, $storedPrice = null )
    {
        eZSimplePrice::eZSimplePrice( $classAttribute, $contentObjectAttribute, $storedPrice );

        $isVatIncluded = ( $classAttribute->attribute( EZ_DATATYPESTRING_MULTIPRICE_INCLUDE_VAT_FIELD ) == 1 );
        $VATID =& $classAttribute->attribute( EZ_DATATYPESTRING_MULTIPRICE_VAT_ID_FIELD );

        $this->setVatIncluded( $isVatIncluded );
        $this->setVatType( $VATID );

        $this->IsDataDirty = false;
        $this->ContentObjectAttribute =& $contentObjectAttribute;
    }

    /*!
     \return An array with attributes that is available.
    */
    function attributes()
    {
        return array( 'currency_list',
                      'auto_currency_list',
                      'price_list',
                      'auto_price_list',
                      'custom_price_list',
                      'inc_vat_price_list',
                      'ex_vat_price_list',
                      'discount_inc_vat_price_list',
                      'discount_ex_vat_price_list' );
    }

    /*!
     \return \c true if the attribute named \a $attr exists.
    */
    function hasAttribute( $attr )
    {
        $hasAttribute = in_array( $attr, eZMultiPrice::attributes() );
        if ( !$hasAttribute )
            $hasAttribute = eZSimplePrice::attributes( $attr );

        return $hasAttribute;
    }

    /*!
      Sets the attribute named \a $attr to value \a $value.
    */
    function setAttribute( $attr, $value )
    {
        switch ( $attr )
        {
            case 'currency_list':
            {
            } break;

            case 'auto_currency_list':
            {
            } break;

            case 'price_list':
            {
            } break;

            case 'auto_price_list':
            {
            } break;

            case 'custom_price_list':
            {
            } break;

            default:
            {
                eZSimplePrice::setAttribute( $attr, $value );
            } break;
        }
    }

    /*!
     \return The value of the attribute named \a $attr or \c null if it doesn't exist.
    */
    function &attribute( $attr )
    {
        switch ( $attr )
        {
            case 'currency_list':
            {
                return $this->currencyList();
            } break;

            case 'auto_currency_list':
            {
                return $this->autoCurrencyList();
            } break;

            case 'price_list':
            {
                return $this->priceList();
            } break;

            case 'inc_vat_price_list':
            {
                return $this->incVATPriceList();
            } break;

            case 'ex_vat_price_list':
            {
                return $this->exVATPriceList();
            } break;

            case 'discount_inc_vat_price_list':
            {
                return $this->discountIncVATPriceList();
            } break;

            case 'discount_ex_vat_price_list':
            {
                return $this->discountExVATPriceList();
            } break;

            case 'auto_price_list':
            {
                return $this->autoPriceList();
            } break;

            case 'custom_price_list':
            {
                return $this->customPriceList();
            } break;

            default :
            {
                return eZSimplePrice::attribute( $attr );
            } break;
        }
    }

    /*!
     functional attribute
    */

    function &preferredCurrency()
    {
        include_once( 'kernel/shop/classes/ezshopfunctions.php' );
        $currency = eZShopFunctions::preferredCurrency();
        return $currency;
    }

    function &currencyList()
    {
        if ( !isset( $this->CurrencyList ) )
        {
            include_once( 'kernel/shop/classes/ezcurrencydata.php' );
            $this->CurrencyList = eZCurrencyData::fetchList();
        }

        return $this->CurrencyList;
    }

    /*!
     functional attribute
    */
    function &autoCurrencyList()
    {
        // 'auto currencies' are the currencies used for 'auto' prices.
        // 'auto currencies' = 'all currencies' - 'currencies of custom prices'

        $autoCurrecyList = $this->currencyList();
        $customPriceList =& $this->customPriceList();
        foreach ( $customPriceList as $price )
        {
            if ( $price )
            {
                $currencyCode = $price->attribute( 'currency_code' );
                unset( $autoCurrecyList[$currencyCode] );
            }
        }

        return $autoCurrecyList;
    }

    /*!
     functional attribute
    */
    function &customPriceList()
    {
        return $this->priceList( EZ_MULTIPRICEDATA_VALUE_TYPE_CUSTOM );
    }

    function &autoPriceList()
    {
        return $this->priceList( EZ_MULTIPRICEDATA_VALUE_TYPE_AUTO );
    }

    function &priceList( $type = false )
    {
        if ( !isset( $this->PriceList ) )
        {
            if ( is_object( $this->ContentObjectAttribute ) )
                $this->PriceList = eZMultiPriceData::fetch( $this->ContentObjectAttribute->attribute( 'id' ), $this->ContentObjectAttribute->attribute( 'version' ) );

            if ( !$this->PriceList )
                $this->PriceList = array();
        }

        $priceList = array();
        if ( $type !== false )
        {
            $prices =& $this->priceList();
            $currencyCodeList = array_keys( $prices );
            foreach ( $currencyCodeList as $currencyCode )
            {
                if ( $prices[$currencyCode]->attribute( 'type' ) == $type )
                    $priceList[$currencyCode] =& $prices[$currencyCode];
            }
        }
        else
        {
            $priceList =& $this->PriceList;
        }

        return $priceList;
    }

    function &incVATPriceList( $type = false )
    {
        return $this->calcPriceList( EZ_MULTIPRICE_CALCULATION_TYPE_VAT_INCLUDE, $type );
    }

    function &exVATPriceList( $type = false )
    {
        return $this->calcPriceList( EZ_MULTIPRICE_CALCULATION_TYPE_VAT_EXCLUDE, $type );
    }

    function &discountIncVATPriceList( $type = false )
    {
        return $this->calcPriceList( EZ_MULTIPRICE_CALCULATION_TYPE_DISCOUNT_INCLUDE, $type );
    }

    function &discountExVATPriceList( $type = false )
    {
        return $this->calcPriceList( EZ_MULTIPRICE_CALCULATION_TYPE_DISCOUNT_EXCLUDE, $type );
    }

    function &calcPriceList( $calculationType, $priceType )
    {
        $priceList = $this->priceList( $priceType );

        $currencyCodeList = array_keys( $priceList );
        foreach ( $currencyCodeList as $currencyCode )
        {
            $price =& $priceList[$currencyCode];
            switch ( $calculationType )
            {
                case EZ_MULTIPRICE_CALCULATION_TYPE_VAT_INCLUDE :
                {
                    $value = $this->calcIncVATPrice( $price->attribute( 'value' ) );
                } break;

                case EZ_MULTIPRICE_CALCULATION_TYPE_VAT_EXCLUDE :
                {
                    $value = $this->calcExVATPrice( $price->attribute( 'value' ) );
                } break;

                case EZ_MULTIPRICE_CALCULATION_TYPE_DISCOUNT_INCLUDE :
                {
                    $value = $this->calcDiscountIncVATPrice( $price->attribute( 'value' ) );
                } break;

                case EZ_MULTIPRICE_CALCULATION_TYPE_DISCOUNT_EXCLUDE :
                {
                    $value = $this->calcDiscountIncVATPrice( $price->attribute( 'value' ) );
                } break;

                default:
                {
                    // do nothing
                } break;
            }

            $price->setAttribute( 'value', $value );
        }

        return $priceList;
    }

    function remove( $objectAttributeID, $objectAttributeVersion = null )
    {
        eZMultiPriceData::remove( $objectAttributeID, $objectAttributeVersion );
    }

    function removePriceByCurrency( $currencyCode )
    {
        $price =& $this->priceByCurrency( $currencyCode );
        if ( $price )
        {
            $price->removeByID();
            $priceList =& $this->priceList();
            unset( $priceList[$currencyCode] );
        }
    }

    function setCustomPrice( $currencyCode, $value )
    {
        $this->setPriceByCurrency( $currencyCode, $value, EZ_MULTIPRICEDATA_VALUE_TYPE_CUSTOM );
    }

    function setAutoPrice( $currencyCode, $value )
    {
        $this->setPriceByCurrency( $currencyCode, $value, EZ_MULTIPRICEDATA_VALUE_TYPE_AUTO );
    }

    function setPriceByCurrency( $currencyCode, $value, $type )
    {
        if ( !$this->updatePrice( $currencyCode, $value, $type ) &&
             !$this->addPrice( $currencyCode, $value, $type ) )
        {
            eZDebug::writeWarning( "Unable to set price in '$currencyCode'", 'eZMultiPrice::setPrice' );
            return false;
        }

        return true;
    }

    function setPrice( $value )
    {
    }

    function updateAutoPriceList()
    {
        include_once( 'kernel/shop/classes/ezcurrencyconverter.php' );
        $converter =& eZCurrencyConverter::instance();

        $basePrice =& $this->basePrice();
        $basePriceValue = $basePrice ? $basePrice->attribute( 'value' ) : 0;
        $baseCurrencyCode = $basePrice ? $basePrice->attribute( 'currency_code' ) : false;

        $autoCurrencyList =& $this->autoCurrencyList();
        foreach( $autoCurrencyList as $currencyCode => $currency )
        {
            $autoValue = $converter->convert( $baseCurrencyCode, $currencyCode, $basePriceValue );
            $this->setAutoPrice( $currencyCode, $autoValue );
        }
    }

    function &createPrice( $currencyCode, $value, $type )
    {
        $price = false;
        if ( is_object( $this->ContentObjectAttribute ) && $this->currencyByCode( $currencyCode ) )
        {
            $price = new eZMultiPriceData( array( 'contentobject_attribute_id' => $this->ContentObjectAttribute->attribute( 'id' ),
                                                  'contentobject_attribute_version' => $this->ContentObjectAttribute->attribute( 'version' ),
                                                  'currency_code' => $currencyCode,
                                                  'value' => $value,
                                                  'type' => $type ) );
        }
        return $price;
    }


    function &addPrice( $currencyCode, $value, $type )
    {
        $price =& $this->createPrice( $currencyCode, $value, $type );
        if( $price )
        {
            if ( $value === false )
                $price->setAttribute( 'value', '0.00' );

            $priceList =& $this->priceList();
            $priceList[$price->attribute( 'currency_code' )] =& $price;

            $this->setHasDirtyData( true );
        }

        return $price;
    }

    function &updatePrice( $currencyCode, $value, $type )
    {
        $price =& $this->priceByCurrency( $currencyCode );
        if( $price )
        {
            if ( $value !== false )
                $price->setAttribute( 'value', $value );

            if ( $type !== false )
                $price->setAttribute( 'type', $type );

            $this->setHasDirtyData( true );
        }

        return $price;
    }

    function &customPrice( $currencyCode )
    {
        return $this->priceByCurrency( $currencyCode, EZ_MULTIPRICEDATA_VALUE_TYPE_CUSTOM );
    }

    function &autoPrice( $currencyCode )
    {
        return $this->priceByCurrency( $currencyCode, EZ_MULTIPRICEDATA_VALUE_TYPE_AUTO );
    }

    /*!
    */
    function &priceByCurrency( $currencyCode, $type = false )
    {
        $price = false;
        $priceList =& $this->priceList();

        if ( isset( $priceList[$currencyCode] ) )
        {
            if( $type === false || $priceList[$currencyCode]->attribute( 'type' ) == $type )
                $price =& $priceList[$currencyCode];
        }

        return $price;
    }

    function &price()
    {
        $value = '0.0';
        if ( $currencyCode = $this->preferredCurrency() )
        {
            $price =& $this->priceByCurrency( $currencyCode );
            if ( $price )
                $value = $price->attribute( 'value' );
        }

        return $value;
    }

    function &currencyByCode( $currencyCode )
    {
        $currnecy = false;
        $currencyList =& $this->currencyList();
        if ( isset( $currencyList[$currencyCode] ) )
            $currency =& $currencyList[$currencyCode];

        return $currency;
    }

    function store()
    {
        if ( $this->hasDirtyData() )
        {
            $this->storePriceList();
            $this->setHasDirtyData( false );
        }
    }

    function storePriceList()
    {
        if ( isset( $this->PriceList ) && count( $this->PriceList ) > 0 )
        {
            $priceList =& $this->priceList();
            foreach ( $priceList as $price )
                $price->store();
        }
    }

    function hasDirtyData()
    {
        return $this->HasDataDirty;
    }

    function setHasDirtyData( $hasDirtyData )
    {
        $this->HasDataDirty = $hasDirtyData;
    }

    /*!
     Returns a currency code of the first custom price.
    */
    function baseCurrency()
    {
        // use value of the first custom price as
        // base price and base currency.

        $baseCurrency = false;
        $customPriceList =& $this->customPriceList();
        $currencies = array_keys( $customPriceList );
        if ( count( $currencies ) > 0 )
            $baseCurrency = $currencies[0];

        return $baseCurrency;
    }

    function basePrice()
    {
        $baseCurrencyCode = $this->baseCurrency();
        $basePrice =& $this->priceByCurrency( $baseCurrencyCode );
        return $basePrice;
    }

    function &currency()
    {
        return $this->preferredCurrency();
    }

    /// \privatesection
    var $PriceList;
    var $CurrencyList;
    var $HasDataDirty;
    var $ContentObjectAttribute;
}

?>
