<?php
//
// Created on: <23-Aug-2006 11:00:00 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file remove_old_drafts.php
*/


include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );

if ( !$isQuiet )
    $cli->output( "Cleaning up usual drafts..." );

// Cleaning up usual drafts
$ini =& eZINI::instance();
$draftsCleanUpLimit = $ini->hasVariable( 'DraftsSettings', 'DraftsCleanUpLimit' ) ?
                                    $ini->variable( 'DraftsSettings', 'DraftsCleanUpLimit' ) : 0;
$durationSetting = $ini->hasVariable( 'DraftsSettings', 'DraftsDuration' ) ?
                                    $ini->variable( 'DraftsSettings', 'DraftsDuration' ) : array( 'days' => 90 );

$duration = 0;
if ( isset( $durationSetting[ 'days' ] ) and is_numeric( $durationSetting[ 'days' ] ) )
{
    $duration += $durationSetting[ 'days' ] * 60 * 60 * 24;
}
if ( isset( $durationSetting[ 'hours' ] ) and is_numeric( $durationSetting[ 'hours' ] ) )
{
    $duration += $durationSetting[ 'hours' ] * 60 * 60;
}
if ( isset( $durationSetting[ 'minutes' ] ) and is_numeric( $durationSetting[ 'minutes' ] ) )
{
    $duration += $durationSetting[ 'minutes' ] * 60;
}
if ( isset( $durationSetting[ 'seconds' ] ) and is_numeric( $durationSetting[ 'seconds' ] ) )
{
    $duration += $durationSetting[ 'seconds' ];
}

$expiryTime = mktime() - $duration;
$processedCount = eZContentObjectVersion::removeVersions( EZ_VERSION_STATUS_DRAFT, $draftsCleanUpLimit, $expiryTime );

if ( !$isQuiet )
    $cli->output( "Cleaned up " . $processedCount . " internal drafts" );

?>
