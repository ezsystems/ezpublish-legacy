#!/usr/bin/env php
<?php
/**
 * File containing the ezsessiongc.php script.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

set_time_limit( 0 );

require_once 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Session Garbage Collector\n\n" .
                                                        "Allows manual cleaning up expired sessions as defined by site.ini[Session]SessionTimeout\n" .
                                                        "\n" .
                                                        "./bin/php/ezsessiongc.php" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "",
                                "[]",
                                array() );
$script->initialize();

$cli->output( "Cleaning up expired sessions." );

// Functions for session to make sure baskets are cleaned up
function eZSessionBasketGarbageCollector( $db, $time )
{
    eZBasket::cleanupExpired( $time );
}

// Fill in hooks
eZSession::addCallback( 'gc_pre', 'eZSessionBasketGarbageCollector');

eZSession::garbageCollector();

$script->shutdown();

?>
