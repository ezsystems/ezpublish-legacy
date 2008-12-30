<?php
//
// Definition of eZMultiPrice class
//
// Created on: <04-Nov-2005 12:26:52 dl>
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

class eZMultiPrice extends eZSimplePrice
{
    const CALCULATION_TYPE_VAT_INCLUDE = 1;
    const CALCULATION_TYPE_VAT_EXCLUDE = 2;
    const CALCULATION_TYPE_DISCOUNT_INCLUDE = 3;
    const CALCULATION_TYPE_DISCOUNT_EXCLUDE = 4;

    /*!
     Constructor
    */
    function eZMultiPrice( $classAttribute, $contentObjectAttribute, $storedPrice = null )
    {
        eZSimplePrice::eZSimplePrice( $classAttribute, $contentObjectAttribute, $storedPrice );

        $isVatIncluded = ( $classAttribute->attribute( eZMultiPriceType::INCLUDE_VAT_FIELD ) == 1 );
        $VATID = $classAttribute->attribute( eZMultiPriceType::VAT_ID_FIELD );

        $this->setVatIncluded( $isVatIncluded );
        $this->setVatType( $VATID );

        $this->IsDataDirty = false;
        $this->ContentObjectAttribute = $contentObjectAttribute;
    }

    /*!
     \return An array with attributes that is available.
    */
    function attributes()
    {
        return array_unique( array_merge( array( 'currency_list',
                                                 'auto_currency_list',
                                                 'price_list',
                                                 'auto_price_list',
                                                 'custom_price_list',
                                                 'inc_vat_price_list',
                                                 'ex_vat_price_list',
                                                 'discount_inc_vat_price_list',
                                                 'discount_ex_vat_price_list' ),
                                          eZSimplePrice::attributes() ) );
    }

    /*!
     \return \c true if the attribute named \a $attr exists.
    */
    function hasAttribute( $attr )
    {
        $hasAttribute = in_array( $attr, $this->attributes() );
        if ( !$hasAttribute )
        {
            $hasAttribute = eZSimplePrice::attributes( $attr );
        }

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
    function attribute( $attr )
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

    function preferredCurrencyCode()
    {
        return eZShopFunctions::preferredCurrencyCode();
    }

    function currencyList()
    {
        if ( !isset( $this->CurrencyList ) )
        {
            $this->CurrencyList = eZCurrencyData::fetchList();
        }

        return $this->CurrencyList;
    }

    /*!
     functional attribute
    */
    function autoCurrencyList()
    {
        // 'auto currencies' are the currencies used for 'auto' prices.
        // 'auto currencies' = 'all currencies' - 'currencies of custom prices'

        $autoCurrecyList = $this->currencyList();
        $customPriceList = $this->customPriceList();
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
    function customPriceList()
    {
        return $this->priceList( eZMultiPriceData::VALUE_TYPE_CUSTOM );
    }

    function autoPriceList()
    {
        return $this->priceList( eZMultiPriceData::VALUE_TYPE_AUTO );
    }

    function priceList( $type = false )
    {
        if ( !isset( $this->PriceList ) )
        {
            if ( is_object( $this->ContentObjectAttribute ) )
            {
                $this->PriceList = eZMultiPriceData::fetch( $this->ContentObjectAttribute->attribute( 'id' ),
                                                            $this->ContentObjectAttribute->attribute( 'version' ) );
            }

            if ( !$this->PriceList )
            {
                $this->PriceList = array();
            }
        }

        $priceList = array();
        if ( $type !== false )
        {
            foreach ( $this->priceList() as $currencyCode => $price )
            {
                if ( $price->attribute( 'type' ) == $type )
                {
                    $priceList[$currencyCode] = $price;
                }
            }
        }
        else
        {
            $priceList = $this->PriceList;
        }

        return $priceList;
    }

    function incVATPriceList( $type = false )
    {
        return $this->calcPriceList( self::CALCULATION_TYPE_VAT_INCLUDE, $type );
    }

    function exVATPriceList( $type = false )
    {
        return $this->calcPriceList( self::CALCULATION_TYPE_VAT_EXCLUDE, $type );
    }

    function discountIncVATPriceList( $type = false )
    {
        return $this->calcPriceList( self::CALCULATION_TYPE_DISCOUNT_INCLUDE, $type );
    }

    function discountExVATPriceList( $type = false )
    {
        return $this->calcPriceList( self::CALCULATION_TYPE_DISCOUNT_EXCLUDE, $type );
    }

    function calcPriceList( $calculationType, $priceType )
    {
        $priceList = $this->priceList( $priceType );

        $calculatedPriceList = array();
        foreach ( $priceList as $key => $price )
        {
            switch ( $calculationType )
            {
                case self::CALCULATION_TYPE_VAT_INCLUDE :
                {
                    $value = $this->calcIncVATPrice( $price->attribute( 'value' ) );
                } break;

                case self::CALCULATION_TYPE_VAT_EXCLUDE :
                {
                    $value = $this->calcExVATPrice( $price->attribute( 'value' ) );
                } break;

                case self::CALCULATION_TYPE_DISCOUNT_INCLUDE :
                {
                    $value = $this->calcDiscountIncVATPrice( $price->attribute( 'value' ) );
                } break;

                case self::CALCULATION_TYPE_DISCOUNT_EXCLUDE :
                {
                    $value = $this->calcDiscountIncVATPrice( $price->attribute( 'value' ) );
                } break;

                default:
                {
                    // do nothing
                } break;
            }

            $calculatedPrice = clone $price;
            $calculatedPrice->setAttribute( 'value', $value );
            $calculatedPriceList[$key] = $calculatedPrice;
        }

        return $calculatedPriceList;
    }

    static function removeByID( $objectAttributeID, $objectAttributeVersion = null )
    {
        eZMultiPriceData::removeByOAID( $objectAttributeID, $objectAttributeVersion );
    }

    function removePriceByCurrency( $currencyCode )
    {
        $price = $this->priceByCurrency( $currencyCode );
        if ( $price )
        {
            $price->remove();
            $priceList = $this->priceList();
            unset( $priceList[$currencyCode] );
        }
    }

    function setCustomPrice( $currencyCode, $value )
    {
        $this->setPriceByCurrency( $currencyCode, $value, eZMultiPriceData::VALUE_TYPE_CUSTOM );
    }

    function setAutoPrice( $currencyCode, $value )
    {
        $this->setPriceByCurrency( $currencyCode, $value, eZMultiPriceData::VALUE_TYPE_AUTO );
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
        $converter = eZCurrencyConverter::instance();

        $basePrice = $this->basePrice();
        $basePriceValue = $basePrice ? $basePrice->attribute( 'value' ) : 0;
        $baseCurrencyCode = $basePrice ? $basePrice->attribute( 'currency_code' ) : false;

        $autoCurrencyList = $this->autoCurrencyList();
        foreach( $autoCurrencyList as $currencyCode => $currency )
        {
            $autoValue = $converter->convert( $baseCurrencyCode, $currencyCode, $basePriceValue );
            $this->setAutoPrice( $currencyCode, $autoValue );
        }
    }

    function createPrice( $currencyCode, $value, $type )
    {
        if ( is_object( $this->ContentObjectAttribute ) && $this->currencyByCode( $currencyCode ) )
        {
            return eZMultiPriceData::create( $this->ContentObjectAttribute->attribute( 'id' ),
                                             $this->ContentObjectAttribute->attribute( 'version' ),
                                             $currencyCode,
                                             $value,
                                             $type );
        }
        return false;
    }


    function addPrice( $currencyCode, $value, $type )
    {
        $price = $this->createPrice( $currencyCode, $value, $type );
        if( $price )
        {
            if ( $value === false )
                $price->setAttribute( 'value', '0.00' );

            $this->PriceList[$price->attribute( 'currency_code' )] = $price;
            $this->setHasDirtyData( true );
        }

        return $price;
    }

    function updatePrice( $currencyCode, $value, $type )
    {
        $price = $this->priceByCurrency( $currencyCode );
        if( $price )
        {
            if ( $value !== false )
            {
                $price->setAttribute( 'value', $value );
            }

            if ( $type !== false )
            {
                $price->setAttribute( 'type', $type );
            }

            $this->setHasDirtyData( true );
        }

        return $price;
    }

    function customPrice( $currencyCode )
    {
        return $this->priceByCurrency( $currencyCode, eZMultiPriceData::VALUE_TYPE_CUSTOM );
    }

    function autoPrice( $currencyCode )
    {
        return $this->priceByCurrency( $currencyCode, eZMultiPriceData::VALUE_TYPE_AUTO );
    }

    function priceByCurrency( $currencyCode, $type = false )
    {
        $price = false;
        $priceList = $this->priceList();

        if ( isset( $priceList[$currencyCode] ) )
        {
            if( $type === false || $priceList[$currencyCode]->attribute( 'type' ) == $type )
            {
                $price = $priceList[$currencyCode];
            }
        }

        return $price;
    }

    function price()
    {
        $value = '0.0';
        if ( $currencyCode = $this->preferredCurrencyCode() )
        {
            $price = $this->priceByCurrency( $currencyCode );
            if ( $price )
            {
                $value = $price->attribute( 'value' );
            }
        }

        return $value;
    }

    function currencyByCode( $currencyCode )
    {
        $currency = false;
        $currencyList = $this->currencyList();
        if ( isset( $currencyList[$currencyCode] ) )
        {
            $currency = $currencyList[$currencyCode];
        }

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
            $priceList = $this->priceList();
            foreach ( $priceList as $price )
            {
                $price->store();
            }
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
        $customPriceList = $this->customPriceList();
        $currencies = array_keys( $customPriceList );
        if ( count( $currencies ) > 0 )
        {
            $baseCurrency = $currencies[0];
        }

        return $baseCurrency;
    }

    function basePrice()
    {
        $baseCurrencyCode = $this->baseCurrency();
        return $this->priceByCurrency( $baseCurrencyCode );
    }

    function currency()
    {
        return $this->preferredCurrencyCode();
    }

    function DOMDocument()
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $root = $doc->createElement( 'ezmultiprice' );
        $doc->appendChild( $root );

        $priceListNode = $doc->createElement( 'price-list' );

        $priceList = $this->attribute( 'price_list' );
        foreach ( $priceList as $price )
        {
            $currencyCode = $price->attribute( 'currency_code' );
            $value = $price->attribute( 'value' );
            $type = $price->attribute( 'type' );

            $priceNode = $doc->createElement( 'price' );

            $priceNode->setAttribute( 'currency-code', $currencyCode );
            $priceNode->setAttribute( 'value', $value );
            $priceNode->setAttribute( 'type', $type );

            $priceListNode->appendChild( $priceNode );
            unset( $priceNode );
        }

        $root->appendChild( $priceListNode );

        return $doc;
    }

    function decodeDOMTree( $rootNode )
    {
        $priceNode = $rootNode->getElementsByTagName( 'price-list' )->item( 0 );
        $priceNodes = $priceNode->getElementsByTagName( 'price' );
        if ( $priceNodes->length > 0 )
        {
            foreach ( $priceNodes as $priceNode )
            {
                $currencyCode = $priceNode->getAttribute( 'currency-code' );
                $value = $priceNode->getAttribute( 'value' );
                $type = $priceNode->getAttribute( 'type' );

                $this->setPriceByCurrency( $currencyCode, $value, $type );
            }
        }
    }

    /// \privatesection
    public $PriceList;
    public $CurrencyList;
    public $HasDataDirty;
    public $ContentObjectAttribute;
}

?>
