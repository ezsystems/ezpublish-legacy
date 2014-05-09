<?php
//
// Definition of Session_GC Cronjob
/**
 * File containing the session_gc.php cronjob
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Cronjob to garbage collect expired sessions as defined by site.ini[Session]SessionTimeout
 * (the expiry time is calculated when session is created / updated)
 * These are normally automatically removed by the session gc in php, but on some linux distroes
 * based on debian this does not work because the custom way session gc is handled.
 *
 * Also make sure you run basket_cleanup if you use the shop!
 *
 * @package eZCronjob
 * @see eZsession
 */


// Functions for session to make sure baskets are cleaned up
function eZSessionBasketGarbageCollector( $db, $time )
{
    eZBasket::cleanupExpired( $time );
}

// Fill in hooks
eZSession::addCallback( 'gc_pre', 'eZSessionBasketGarbageCollector');

eZSession::garbageCollector();

?>
