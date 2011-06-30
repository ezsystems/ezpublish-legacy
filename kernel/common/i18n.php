<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * @deprecated Since 4.3, superseded by {@link ezpI18n::tr()}
 *             Will be kept for compatability in 4.x.
 */
function ezi18n( $context, $source, $comment = null, $arguments = null )
{
    eZDebug::writeStrict( 'Function ezi18n() has been deprecated in 4.3 in favor of ezpI18n::tr()', 'Deprecation' );
    return ezpI18n::tr( $context, $source, $comment, $arguments );
}

/**
 * @deprecated Since 4.3, superseded by {@link ezpI18n::tr()} instead
 *             Will be kept for compatability in 4.x.
 */
function ezx18n( $extension, $context, $source, $comment = null, $arguments = null )
{
    eZDebug::writeStrict( 'Function ezx18n() has been deprecated in 4.3 in favor of ezpI18n::tr()', 'Deprecation' );
    return ezpI18n::tr( $context, $source, $comment, $arguments );
}

?>
