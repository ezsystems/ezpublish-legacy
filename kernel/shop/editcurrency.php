<?php
//
// Created on: <08-Nov-2005 13:06:15 dl>
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

/*! \file editcurrency.php
*/

$module = $Params['Module'];

$ini = eZINI::instance( 'site.ini' );

$error = false;
$originalCurrencyCode = $Params['Currency'];
$currencyParams = array( 'code' => false,
                         'symbol' => false,
                         'locale' => $ini->variable( 'RegionalSettings', 'Locale' ),
                         'custom_rate_value' => '0.0000',
                         'rate_factor' => '1.0000' );

if ( $module->isCurrentAction( 'Cancel' ) )
{
    return $module->redirectTo( $module->functionURI( 'currencylist' ) );
}
else if ( $module->isCurrentAction( 'Create' ) )
{
    if ( $module->hasActionParameter( 'CurrencyData' ) )
        $currencyParams = $module->actionParameter( 'CurrencyData' );

    if ( $errCode = eZCurrencyData::canCreate( $currencyParams['code'] ) )
    {
        $error = eZCurrencyData::errorMessage( $errCode );
    }
    else
    {
        eZShopFunctions::createCurrency( $currencyParams );
        eZContentCacheManager::clearAllContentCache();

        return $module->redirectTo( $module->functionURI( 'currencylist' ) );
    }
}
else if ( $module->isCurrentAction( 'StoreChanges' ) )
{
    $originalCurrencyCode = $module->hasActionParameter( 'OriginalCurrencyCode' ) ? $module->actionParameter( 'OriginalCurrencyCode' ) : '';
    if ( $module->hasActionParameter( 'CurrencyData' ) )
        $currencyParams = $module->actionParameter( 'CurrencyData' );

    $errCode = eZShopFunctions::changeCurrency( $originalCurrencyCode, $currencyParams['code'] );
    if ( $errCode === eZCurrencyData::ERROR_OK )
    {
        $currency = eZCurrencyData::fetch( $currencyParams['code'] );
        if ( is_object( $currency ) )
        {
            $currency->setAttribute( 'symbol', $currencyParams['symbol'] );
            $currency->setAttribute( 'locale', $currencyParams['locale'] );
            $currency->setAttribute( 'custom_rate_value', $currencyParams['custom_rate_value'] );
            $currency->setAttribute( 'rate_factor', $currencyParams['rate_factor'] );

            $db = eZDB::instance();
            $db->begin();
            $currency->sync();
            $db->commit();

            eZContentCacheManager::clearAllContentCache();

            return $module->redirectTo( $module->functionURI( 'currencylist' ) );
        }
        else
        {
            $error = eZCurrencyData::errorMessage( $currency );
        }
    }
    else
    {
        $error = eZCurrencyData::errorMessage( $errCode );
    }
}

$canEdit = true;
$pathText = '';
if ( strlen( $originalCurrencyCode ) > 0 )
{
    // going to edit existing currency
    $pathText = ezi18n( 'kernel/shop', 'Edit currency' );

    if ( $currencyParams['code'] === false )
    {
        // first time in 'edit' mode? => initialize template variables
        // with existing data.
        $currency = eZCurrencyData::fetch( $originalCurrencyCode );
        if ( is_object( $currency ) )
        {
            $currencyParams['code'] = $currency->attribute( 'code' );
            $currencyParams['symbol'] = $currency->attribute( 'symbol' );
            $currencyParams['locale'] = $currency->attribute( 'locale' );
            $currencyParams['custom_rate_value'] = $currency->attribute( 'custom_rate_value' );
            $currencyParams['rate_factor'] = $currency->attribute( 'rate_factor' );
        }
        else
        {
            $error = "'$originalCurrencyCode' currency  doesn't exist.";
            $canEdit = false;
        }
    }
}
else
{
    // going to create new currency
    $pathText = ezi18n( 'kernel/shop', 'Create new currency' );
}

require_once( 'kernel/common/template.php' );
$tpl = templateInit();

$tpl->setVariable( 'error', $error );
$tpl->setVariable( 'can_edit', $canEdit );
$tpl->setVariable( 'original_currency_code', $originalCurrencyCode );
$tpl->setVariable( 'currency_data', $currencyParams );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/editcurrency.tpl" );
$Result['path'] = array( array( 'text' => $pathText,
                                'url' => false ) );

?>
