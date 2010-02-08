<?php
//
// Definition of Basket_cleanup class
//
// Created on: <14-Jun-2005 14:44:49 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file
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

if ( !$isQuiet )
    $cli->output( "Cleaning up expired baskets" );
eZDBGarbageCollector::collectBaskets( $maxTime, $idleTime, $fetchLimit );

?>
