<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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
