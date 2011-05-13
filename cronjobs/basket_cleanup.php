<?php
/**
 * File containing the basket_cleanup.php cronjob
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$ini = eZINI::instance();

// Check if this should be run in a cronjob
$useCronjob = $ini->variable( 'Session', 'BasketCleanup' ) == 'cronjob';
if ( !$useCronjob )
    return;

// Only do basket cleanup once in a while
$freq = $ini->variable( 'Session', 'BasketCleanupAverageFrequency' );
if ( mt_rand( 1, max( $freq, 1 ) ) != 1 )
    return;

$maxTime = $ini->variable( 'Session', 'BasketCleanupTime' );
$idleTime = $ini->variable( 'Session', 'BasketCleanupIdleTime' );
$fetchLimit = $ini->variable( 'Session', 'BasketCleanupFetchLimit' );

$cli->output( "Cleaning up expired baskets" );
eZDBGarbageCollector::collectBaskets( $maxTime, $idleTime, $fetchLimit );

?>
