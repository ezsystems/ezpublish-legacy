<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$module = $Params['Module'];

if ( $module->isCurrentAction( 'Set' ) && $module->hasActionParameter( 'Country' ) )
{
    $country = $module->actionParameter( 'Country' );
}
elseif ( isset( $Params['Country'] ) )
{
    $country = $Params['Country'];
}
else
{
    $country = null;
}

if ( $country !== null )
{
    eZShopFunctions::setPreferredUserCountry( $country );
    eZDebug::writeNotice( "Set user country to <$country>" );
}
else
{
    eZDebug::writeWarning( "No country chosen to set." );
}

eZRedirectManager::redirectTo( $module, false );

?>
