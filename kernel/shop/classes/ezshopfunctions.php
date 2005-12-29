<?php
//
// Definition of ezshopfunctions class
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
        $isProduct = false;

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

}

?>