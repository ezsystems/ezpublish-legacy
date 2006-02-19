<?php
//
// Created on: <08-Nov-2005 13:06:15 dl>
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

/*! \file currencylist.php
*/

include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezpreferences.php' );
include_once( 'kernel/shop/classes/ezcurrencydata.php' );
include_once( 'kernel/shop/classes/ezshopfunctions.php' );

function reloadWithOffset( &$module )
{
    $offset = $module->hasActionParameter( 'Offset' ) ? $module->actionParameter( 'Offset' ) : false;
    if ( $offset )
        $module->redirectTo( $module->functionURI( 'currencylist' ) . "/(offset)/$offset" );
}

$module =& $Params['Module'];
$offset = $Params['Offset'];

if ( $module->isCurrentAction( 'AddCurrency' ) )
{
    $module->redirectTo( $module->functionURI( 'editcurrency' ) );
}
else if ( $module->isCurrentAction( 'RemoveCurrency' ) )
{
    $currencyList = $module->hasActionParameter( 'DeleteCurrencyList' ) ? $module->actionParameter( 'DeleteCurrencyList' ) : array();

    eZShopFunctions::removeCurrency( $currencyList );

    include_once( 'kernel/classes/ezcontentcachemanager.php' );
    eZContentCacheManager::clearAllContentCache();
}
else if ( $module->isCurrentAction( 'SetRates' ) ||
          $module->isCurrentAction( 'UpdateStatus' ) )
{
    $updateDataList = $module->hasActionParameter( 'CurrencyList' ) ? $module->actionParameter( 'CurrencyList' ) : array();

    $currencyList = eZCurrencyData::fetchList();
    $db =& eZDB::instance();
    $db->begin();
    foreach ( $currencyList as $currency )
    {
        $currencyCode = $currency->attribute( 'code' );
        if ( isset( $updateDataList[$currencyCode] ) )
        {
            $updateData = $updateDataList[$currencyCode];

            if ( $module->isCurrentAction( 'UpdateStatus' ) )
            {
                if ( isset( $updateData['status'] ) )
                    $currency->setStatus( $updateData['status'] );
            }
            if ( $module->isCurrentAction( 'SetRates' ) )
            {
                if ( is_numeric( $updateData['custom_rate_value'] ) )
                    $currency->setAttribute( 'custom_rate_value', $updateData['custom_rate_value'] );
                if ( is_numeric( $updateData['rate_factor'] ) )
                    $currency->setAttribute( 'rate_factor', $updateData['rate_factor'] );
            }

            $currency->sync();
        }
    }
    $db->commit();

    reloadWithOffset( $module );
}
else if ( $module->isCurrentAction( 'UpdateAutoprices' ) )
{
    eZShopFunctions::updateAutoprices();

    include_once( 'kernel/classes/ezcontentcachemanager.php' );
    eZContentCacheManager::clearAllContentCache();

    reloadWithOffset( $module );
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

$tpl =& templateInit();

$tpl->setVariable( 'currency_list', $currencyList );
$tpl->setVariable( 'currency_list_count', $currencyCount );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/shop', 'Available currency list' ),
                                'url' => false ) );
$Result['content'] =& $tpl->fetch( "design:shop/currencylist.tpl" );



?>