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

/*! \file preferredcurrency.php
*/

include_once( 'kernel/common/template.php' );
//include_once( 'kernel/classes/ezpreferences.php' );
//include_once( 'lib/ezutils/classes/ezhttptool.php' );
//include_once( 'kernel/shop/classes/ezcurrencydata.php' );

//$module =& $Params['Module'];

//$currencyList = eZCurrencyData::fetchList();
/*
$preferredCurrency = $Params['Currency'];
if ( $module->isCurrentAction( 'Set' ) )
{
    if ( $module->hasActionParameter( 'Currency' ) )
        $preferredCurrency = $module->actionParameter( 'Currency' );
}
*/

/*

setPreferredCurrency, preferredCurrency should be in eZCurrencyManager ?????????
                                                  or in eZUser ????


eZUser::setPreferredCurrency( $currency, $userID = false )
{
    if ( eZCurrencyData::isCurrencyAvailable( $currency )
    {
        if ( $userID == false )
            $userID = eZUser::currentUser();

        if ( $userID == $anonymoisUserID )
            setToPHPSession;
        else
            setToPreferences;

        return true;
    }

    return false;
}

eZUser::preferredCurrency( $userID = false )
{
    $currency = false;

    if ( $userID == false )
        $userID = eZUser::currentUser();

    if ( $userID == $anonymoisUserID && hasCurrencyInPHPSession)
    {
        $currency = currencyInPHPSession();
    }
    else if( hasCurrencyInPreferences( $userID )
    {
        $currency = currencyInPreferences( $userID );
    }

    if ( !$currency )
    {
        $currency = INI::DefaultBaseCurrency();
    }

    return $currency;
}


if ( $preferredCurrency )
    eZCurrencyData::setPreferredCurrency( $preferredCurrency );
else
    $preferredCurrency = eZCurrencyData::preferredCurrency();
*/

/*
if ( !$preferredCurrency )
{
    $preferredCurrency = 'USD';
}
*/

//$preferredCurrency = 'USD';

$tpl =& templateInit();
//$tpl->setVariable( 'currency_list', $currencyList );
//$tpl->setVariable( 'preferred_currency', $preferredCurrency );

$Result = array();
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/shop', 'Preferred currency' ),
                                'url' => false ) );
$Result['content'] =& $tpl->fetch( "design:shop/preferredcurrency.tpl" );


?>
