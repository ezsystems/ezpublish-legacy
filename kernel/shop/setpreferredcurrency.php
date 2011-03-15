<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/*! \file
*/

$module = $Params['Module'];

$preferredCurrency = $Params['Currency'];

if ( $module->isCurrentAction( 'Set' ) )
{
    if ( $module->hasActionParameter( 'Currency' ) )
        $preferredCurrency = $module->actionParameter( 'Currency' );
}

if ( $preferredCurrency )
    eZShopFunctions::setPreferredCurrencyCode( $preferredCurrency );

eZRedirectManager::redirectTo( $module, false );

?>
