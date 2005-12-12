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
include_once( 'kernel/shop/classes/ezcurrencyrate.php' );
include_once( 'kernel/classes/datatypes/ezmultiprice/ezmultipricedata.php' );

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
    eZCurrencyData::removeCurrencyList( $currencyList );
}
else if ( $module->isCurrentAction( 'SetRates' ) )
{
    $rateList = $module->hasActionParameter( 'RateList' ) ? $module->actionParameter( 'RateList' ) : array();
    eZCurrencyRate::setRates( $rateList );
    reloadWithOffset( $module );
}
else if ( $module->isCurrentAction( 'UpdateStatus' ) )
{
    $currencyList = $module->hasActionParameter( 'CurrencyList' ) ? $module->actionParameter( 'CurrencyList' ) : array();
    eZCurrencyData::setStatusList( $currencyList );
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

// fetch rates
$codeList = array();
$currencyRates = array();
if ( is_array( $currencyList ) && count( $currencyList ) > 0 )
{
    foreach ( $currencyList as $currency )
        $codeList[] = $currency->attribute( 'code' );

    $rates = eZCurrencyRate::fetchList( array( 'code' => array( $codeList ) ), true );
    if ( is_array( $rates ) )
    {
        foreach( $rates as $rate )
            $currencyRates[$rate->attribute( 'code' )] = $rate;
    }
}

$viewParameters = array( 'offset' => $offset );

$tpl =& templateInit();

$tpl->setVariable( 'currency_list', $currencyList );
$tpl->setVariable( 'currency_list_count', $currencyCount );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'currency_rates', $currencyRates );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/shop', 'Available currency list' ),
                                'url' => false ) );
$Result['content'] =& $tpl->fetch( "design:shop/currencylist.tpl" );



?>