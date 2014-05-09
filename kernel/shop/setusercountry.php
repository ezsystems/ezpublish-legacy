<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
