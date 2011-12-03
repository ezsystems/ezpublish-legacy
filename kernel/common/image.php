<?php
/**
 * File containing the imageInit() function.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Image manager instance
 *
 * @package kernel
 * @deprecated Deprecated as of 4.3, use {@link eZImageManager::factory()} instead.
 */

function imageInit()
{
    eZDebug::writeStrict( 'Function imageInit() has been deprecated in 4.3 in favor of eZImageManager::factory()', 'Deprecation' );
    return eZImageManager::factory();
}

?>
