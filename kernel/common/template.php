<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Function to get template instance, load autoloads (operators) and set default settings.
 *
 * @deprecated Since 4.3, superseded by {@link eZTemplate::factory()}
 *             Will be kept for compatability in 4.x.
 * @param string $name (Not supported as it was prevoisly set on same instance anyway)
 * @return eZTemplate
 */
function templateInit( $name = false )
{
    eZDebug::writeStrict( 'Function templateInit() has been deprecated in 4.3 in favor of eZTemplate::factory()', 'Deprecation' );
    return eZTemplate::factory();
}


?>
