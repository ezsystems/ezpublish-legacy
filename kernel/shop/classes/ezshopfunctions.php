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
        $isProduct = false;

        if ( is_object( $contentClass ) )
        {
            $classAttributes =& $contentClass->fetchAttributes();
            $keys = array_keys( $classAttributes );
            foreach ( $keys as  $key )
            {
                $classAttribute =& $classAttributes[$key];
                $dataType = $classAttribute->attribute( 'data_type_string' );
                if ( eZShopFunctions::isProductDatatype( $dataType ) )
                {
                    $isProduct = true;
                    break;
                }
            }
        }

        return $isProduct;
    }

    /*!
     \static
    */
    function isProductObject( &$contentObject )
    {
        $type = eZShopFunctions::productType( $contentObject );
        return ( $type !== false );
    }

    /*!
     \static
    */
    function productType( &$contentObject )
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

    function priceAttributeIdentifier( $productClass )
    {
        $identifier = '';
        if ( is_object( $productClass ) )
        {
            $classAttributes =& $productClass->fetchAttributes();
            $keys = array_keys( $classAttributes );
            foreach ( $keys as  $key )
            {
                $classAttribute =& $classAttributes[$key];
                $dataType = $classAttribute->attribute( 'data_type_string' );
                if ( eZShopFunctions::isProductDatatype( $dataType ) )
                {
                    $identifier = $classAttribute->attribute( 'identifier' );
                    break;
                }
            }
        }

        return $identifier;
    }

    /*!
     \static
    */
    function preferredCurrency()
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
    function setPreferredCurrency( $currencyCode )
    {
        include_once( 'kernel/classes/ezpreferences.php' );
        eZPreferences::setValue( 'user_preferred_currency', $currencyCode );
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
}

?>