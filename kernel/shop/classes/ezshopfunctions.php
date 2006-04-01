<?php
//
// Definition of ezshopfunctions class
//
// Created on: <04-Nov-2005 12:26:52 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

/*! \file ezshopfunctions.php
*/

class eZShopFunctions
{
    function eZShopFunctions()
    {
    }

    /*!
     \static
    */
    function isProductClass( &$contentClass )
    {
        $type = eZShopFunctions::productTypeByClass( $contentClass );
        return ( $type !== false );
    }

    /*!
     \static
    */
    function isProductObject( &$contentObject )
    {
        $type = eZShopFunctions::productTypeByObject( $contentObject );
        return ( $type !== false );
    }

    function isSimplePriceClass( &$contentClass )
    {
        $type = eZShopFunctions::productTypeByClass( $contentClass );
        return eZShopFunctions::isSimplePriceProductType( $type );

    }

    function isSimplePriceProductType( $type )
    {
        return ( $type === 'ezprice' );
    }

    function isMultiPriceClass( &$contentClass )
    {
        $type = eZShopFunctions::productTypeByClass( $contentClass );
        return eZShopFunctions::isMultiPriceProductType( $type );
    }

    function isMultiPriceProductType( $type )
    {
        return ( $type === 'ezmultiprice' );
    }

    /*!
     \static
    */
    function productTypeByClass( &$contentClass )
    {
        $type = false;

        if ( is_object( $contentClass ) )
        {
            $classAttributes =& $contentClass->fetchAttributes();
            $keys = array_keys( $classAttributes );
            foreach ( $keys as $key )
            {
                $classAttribute =& $classAttributes[$key];
                $dataType = $classAttribute->attribute( 'data_type_string' );
                if ( eZShopFunctions::isProductDatatype( $dataType ) )
                {
                    $type = $dataType;
                    break;
                }
            }
        }

        return $type;
    }

    /*!
     \static
    */
    function productTypeByObject( &$contentObject )
    {
        $type = false;

        if ( is_object( $contentObject ) )
        {
            $attributes =& $contentObject->contentObjectAttributes();
            $keys = array_keys( $attributes );
            foreach ( $keys as $key )
            {
                $attribute =& $attributes[$key];
                $dataType = $attribute->dataType();
                if ( eZShopFunctions::isProductDatatype( $dataType->isA() ) )
                {
                    $type = $dataType->isA();
                    break;
                }
            }
        }

        return $type;
    }

    /*!
     \static
    */
    function isProductDatatype( $dataTypeString )
    {
        return in_array( $dataTypeString, eZShopFunctions::productDatatypeStringList() );
    }

    /*!
     \static
    */
    function productDatatypeStringList()
    {
        return array( 'ezprice',
                      'ezmultiprice' );
    }

    function productClassList()
    {
        include_once( 'kernel/classes/ezcontentclass.php' );
        $productClassList = array();
        $classList = eZContentClass::fetchList();
        $keys = array_keys( $classList );
        foreach ( $keys as $key )
        {
            $class =& $classList[$key];
            if ( eZShopFunctions::isProductClass( $class ) )
                $productClassList[] = $class;

            unset( $class );
        }

        return $productClassList;
    }

    function priceAttributeIdentifier( &$productClass )
    {
        $identifier = '';
        $classAttribute = eZShopFunctions::priceAttribute( $productClass );
        if ( is_object( $classAttribute ) )
            $identifier = $classAttribute->attribute( 'identifier' );

        return $identifier;
    }

    function priceAttribute( &$productClass )
    {
        if ( is_object( $productClass ) )
        {
            $classAttributes =& $productClass->fetchAttributes();
            $keys = array_keys( $classAttributes );
            foreach ( $keys as $key )
            {
                $classAttribute =& $classAttributes[$key];
                $dataType = $classAttribute->attribute( 'data_type_string' );
                if ( eZShopFunctions::isProductDatatype( $dataType ) )
                {
                    return $classAttribute;
                }
            }
        }

        return false;
    }

    /*!
     \static
    */
    function preferredCurrencyCode()
    {
        include_once( 'kernel/classes/ezpreferences.php' );
        if( !$currencyCode = eZPreferences::value( 'user_preferred_currency' ) )
        {
            include_once( 'lib/ezutils/classes/ezini.php' );
            $ini =& eZINI::instance( 'shop.ini' );
            $currencyCode = $ini->variable( 'CurrencySettings', 'PreferredCurrency' );
        }
        return $currencyCode;
    }

    /*!
     \static
    */
    function setPreferredCurrencyCode( $currencyCode )
    {
        include_once( 'kernel/shop/errors.php' );

        $error = eZShopFunctions::isPreferredCurrencyValid( $currencyCode );
        if ( $error === EZ_ERROR_SHOP_OK )
        {
            include_once( 'kernel/classes/ezpreferences.php' );
            eZPreferences::setValue( 'user_preferred_currency', $currencyCode );
        }

        return $error;
    }

    function isPreferredCurrencyValid( $currencyCode = false )
    {
        include_once( 'kernel/shop/errors.php' );

        $error = EZ_ERROR_SHOP_OK;
        if ( $currencyCode === false )
            $currencyCode = eZShopFunctions::preferredCurrencyCode();

        include_once( 'kernel/shop/classes/ezcurrencydata.php' );
        $currency = eZCurrencyData::fetch( $currencyCode );
        if ( $currency )
        {
            if ( !$currency->isActive() )
            {
                $error = EZ_ERROR_SHOP_PREFERRED_CURRENCY_INACTIVE;
                eZDebug::writeWarning( "Currency '$currencyCode' is inactive.", 'eZShopFunctions::isPreferredCurrencyValid' );
            }
        }
        else
        {
            $error = EZ_ERROR_SHOP_PREFERRED_CURRENCY_DOESNOT_EXIST;
            eZDebug::writeWarning( "Currency '$currencyCode' doesn't exist", 'eZShopFunctions::isPreferredCurrencyValid' );
        }

        return $error;
    }

    /*!
     \static
    */
    function createCurrency( $currencyParams )
    {
        include_once( 'kernel/shop/classes/ezcurrencydata.php' );
        include_once( 'kernel/shop/classes/ezmultipricedata.php' );

        $currency = eZCurrencyData::create( $currencyParams['code'],
                                            $currencyParams['symbol'],
                                            $currencyParams['locale'],
                                            '0.0000',
                                            $currencyParams['custom_rate_value'],
                                            $currencyParams['rate_factor'] );
        if ( is_object( $currency ) )
        {
            $db =& eZDB::instance();
            $db->begin();

            $currency->store();
            eZMultiPriceData::createPriceListForCurrency( $currencyParams['code'] );

            $db->commit();
        }
    }

    function removeCurrency( $currencyCodeList )
    {
        include_once( 'kernel/shop/classes/ezcurrencydata.php' );
        include_once( 'kernel/shop/classes/ezmultipricedata.php' );

        $db =& eZDB::instance();
        $db->begin();

        eZCurrencyData::removeCurrencyList( $currencyCodeList );
        eZMultiPriceData::removePriceListForCurrency( $currencyCodeList );

        $db->commit();
    }

    function changeCurrency( $oldCurrencyCode, $newCurrencyCode )
    {
        $errCode = EZ_CURRENCYDATA_ERROR_OK;

        if ( strcmp( $oldCurrencyCode, $newCurrencyCode ) !== 0 )
        {
            include_once( 'kernel/shop/classes/ezcurrencydata.php' );
            include_once( 'kernel/shop/classes/ezmultipricedata.php' );

            $errCode = eZCurrencyData::canCreate( $newCurrencyCode );
            if ( $errCode === EZ_CURRENCYDATA_ERROR_OK )
            {
                $currency = eZCurrencyData::fetch( $oldCurrencyCode );
                if ( is_object( $currency ) )
                {
                    $db =& eZDB::instance();
                    $db->begin();

                    $currency->setAttribute( 'code', $newCurrencyCode );
                    $currency->sync();

                    eZMultiPriceData::changeCurrency( $oldCurrencyCode, $newCurrencyCode );

                    $db->commit();
                }
                else
                {
                    $errCode = EZ_CURRENCYDATA_ERROR_UNKNOWN;
                }
            }
        }

        return $errCode;
    }

    function updateAutoprices()
    {
        include_once( 'kernel/shop/classes/ezmultipricedata.php' );
        eZMultiPriceData::updateAutoprices();
    }

    function convertAdditionalPrice( $toCurrency, $value )
    {
        if ( $toCurrency == false )
            return $value;

        include_once( 'kernel/shop/classes/ezcurrencyconverter.php' );
        $converter =& eZCurrencyConverter::instance();
        $converter->setRoundingType( EZ_CURRENCY_CONVERTER_ROUNDING_TYPE_ROUND );
        $converter->setRoundingPrecision( 2 );
        $converter->setRoundingTarget( false );

        return $converter->convertFromLocaleCurrency( $toCurrency, $value, true );
    }

    function updateAutoRates()
    {
        include_once( 'kernel/shop/classes/exchangeratesupdatehandlers/ezexchangeratesupdatehandler.php' );
        $handler = eZExchangeRatesUpdateHandler::create();
        if ( $handler )
        {
            $error = false;
            if ( $handler->requestRates( $error ) )
            {
                $rateList = $handler->rateList();
                if ( is_array( $rateList ) && count( $rateList ) > 0 )
                {
                    // update rates for existing currencies
                    $baseCurrencyCode = $handler->baseCurrency();

                    include_once( 'kernel/shop/classes/ezcurrencydata.php' );

                    $currencyList = eZCurrencyData::fetchList();
                    if ( is_array( $currencyList ) && count( $currencyList ) > 0 )
                    {
                        foreach ( $currencyList as $currency )
                        {
                            $currencyCode = $currency->attribute( 'code' );
                            if ( isset( $rateList[$currencyCode] ) )
                                $rateValue = $rateList[$currencyCode];
                            else if ( $currencyCode === $baseCurrencyCode )
                                $rateValue = '1.0000';
                            else
                                $rateValue = false;

                            if ( $rateValue !== false )
                            {
                                $currency->setAttribute( 'auto_rate_value', $rateValue );
                                $currency->store();
                            }
                        }

                        return true;
                    }
                }
            }
            else
            {
                eZDebug::writeError( "$error",
                                     'eZShopFunctions::updateAutoRates' );
            }
        }
        else
        {
            eZDebug::writeError( 'Unable to create handler to update auto rates',
                                 'eZShopFunctions::updateAutoRates' );
        }

        return false;
    }
}

?>