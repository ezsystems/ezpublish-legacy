<?php
//
// Created on: <24-Mar-2006 15:36:53 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file internal_drafts_cleanup.php
*/

if ( !$isQuiet )
    $cli->output( "Cleaning up internal drafts..." );

// Remove all temporary internal drafts
$ini = eZINI::instance( 'content.ini' );
$internalDraftsCleanUpLimit = $ini->hasVariable( 'VersionManagement', 'InternalDraftsCleanUpLimit' ) ?
                                 $ini->variable( 'VersionManagement', 'InternalDraftsCleanUpLimit' ) : 0;
$durationSetting = $ini->hasVariable( 'VersionManagement', 'InternalDraftsDuration' ) ?
                      $ini->variable( 'VersionManagement', 'InternalDraftsDuration' ) : array( 'hours' => 24 ); // by default, only remove drafts older than 1 day

$isDurationSet = false;
$duration = 0;
if ( is_array( $durationSetting ) )
{
    if ( isset( $durationSetting[ 'days' ] ) and is_numeric( $durationSetting[ 'days' ] ) )
    {
        $duration += $durationSetting[ 'days' ] * 60 * 60 * 24;
        $isDurationSet = true;
    }
    if ( isset( $durationSetting[ 'hours' ] ) and is_numeric( $durationSetting[ 'hours' ] ) )
    {
        $duration += $durationSetting[ 'hours' ] * 60 * 60;
        $isDurationSet = true;
    }
    if ( isset( $durationSetting[ 'minutes' ] ) and is_numeric( $durationSetting[ 'minutes' ] ) )
    {
        $duration += $durationSetting[ 'minutes' ] * 60;
        $isDurationSet = true;
    }
    if ( isset( $durationSetting[ 'seconds' ] ) and is_numeric( $durationSetting[ 'seconds' ] ) )
    {
        $duration += $durationSetting[ 'seconds' ];
        $isDurationSet = true;
    }
}

if ( $isDurationSet )
{
    $expiryTime = time() - $duration;
    $processedCount = eZContentObjectVersion::removeVersions( eZContentObjectVersion::STATUS_INTERNAL_DRAFT, $internalDraftsCleanUpLimit, $expiryTime );

    if ( !$isQuiet )
        $cli->output( "Cleaned up " . $processedCount . " internal drafts" );
}
else
{
    if ( !$isQuiet )
        $cli->output( "Lifetime is not set for internal drafts (see your ini-settings, content.ini, VersionManagement section)." );
}

?>
