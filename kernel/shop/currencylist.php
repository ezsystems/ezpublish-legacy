<?php
//
// Created on: <08-Nov-2005 13:06:15 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*! \file
*/

require_once( 'kernel/common/template.php' );
$module = $Params['Module'];
$offset = $Params['Offset'];

$error = false;

if ( $module->hasActionParameter( 'Offset' ) )
{
    $offset = $module->actionParameter( 'Offset' );
}

if ( $module->isCurrentAction( 'NewCurrency' ) )
{
    $module->redirectTo( $module->functionURI( 'editcurrency' ) );
}
else if ( $module->isCurrentAction( 'RemoveCurrency' ) )
{
    $currencyList = $module->hasActionParameter( 'DeleteCurrencyList' ) ? $module->actionParameter( 'DeleteCurrencyList' ) : array();

    eZShopFunctions::removeCurrency( $currencyList );

    eZContentCacheManager::clearAllContentCache();
}
else if ( $module->isCurrentAction( 'ApplyChanges' ) )
{
    $updateDataList = $module->hasActionParameter( 'CurrencyList' ) ? $module->actionParameter( 'CurrencyList' ) : array();

    $currencyList = eZCurrencyData::fetchList();
    $db = eZDB::instance();
    $db->begin();
    foreach ( $currencyList as $currency )
    {
        $currencyCode = $currency->attribute( 'code' );
        if ( isset( $updateDataList[$currencyCode] ) )
        {
            $updateData = $updateDataList[$currencyCode];

            if ( isset( $updateData['status'] ) )
                $currency->setStatus( $updateData['status'] );

            if ( is_numeric( $updateData['custom_rate_value'] ) )
                $currency->setAttribute( 'custom_rate_value', $updateData['custom_rate_value'] );
            else if ( $updateData['custom_rate_value'] == '' )
                $currency->setAttribute( 'custom_rate_value', 0 );

            if ( is_numeric( $updateData['rate_factor'] ) )
                $currency->setAttribute( 'rate_factor', $updateData['rate_factor'] );
            else if ( $updateData['rate_factor'] == '' )
                $currency->setAttribute( 'rate_factor', 0 );

            $currency->sync();
        }
    }
    $db->commit();

    $error = array( 'code' => 0,
                    'description' => ezi18n( 'kernel/shop', 'Changes were stored successfully.' ) );
}
else if ( $module->isCurrentAction( 'UpdateAutoprices' ) )
{
    $error = eZShopFunctions::updateAutoprices();

    eZContentCacheManager::clearAllContentCache();
}
else if ( $module->isCurrentAction( 'UpdateAutoRates' ) )
{
    $error = eZShopFunctions::updateAutoRates();
}

if ( $error !== false )
{
    if ( $error['code'] != 0 )
        $error['style'] = 'message-error';
    else
        $error['style'] = 'message-feedback';
}

switch ( eZPreferences::value( 'currencies_list_limit' ) )
{
    case '2': { $limit = 25; } break;
    case '3': { $limit = 50; } break;
    default:  { $limit = 10; } break;
}

// fetch currencies
$currencyList = eZCurrencyData::fetchList( null, true, $offset, $limit );
$currencyCount = eZCurrencyData::fetchListCount();

$viewParameters = array( 'offset' => $offset );

$tpl = templateInit();

$tpl->setVariable( 'currency_list', $currencyList );
$tpl->setVariable( 'currency_list_count', $currencyCount );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'show_error_message', $error !== false );
$tpl->setVariable( 'error', $error );

$Result = array();
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/shop', 'Available currency list' ),
                                'url' => false ) );
$Result['content'] = $tpl->fetch( "design:shop/currencylist.tpl" );



?>
