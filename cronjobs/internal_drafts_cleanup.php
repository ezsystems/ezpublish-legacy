<?php
//
// Created on: <24-Mar-2006 15:36:53 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

/*! \file internal_drafts_cleanup.php
*/

include_once( 'lib/ezutils/classes/ezini.php' );

$ini =& eZINI::instance();

if ( !$isQuiet )
    $cli->output( "Cleaning up internal drafts" );

// Remove all temporary drafts
include_once( 'kernel/classes/ezcontentobjectversion.php' );
$expiryTime = mktime() - 60*60*24; // only remove drafts older than 1 day
$filters = array( 'status' => EZ_VERSION_STATUS_INTERNAL_DRAFT,
                  'modified' => array( '<', $expiryTime ) );
$untouchedDrafts = eZContentObjectVersion::fetchFiltered( $filters, 0, 100 ); // fetch 100 at a time
foreach ( $untouchedDrafts as $untouchedDraft )
{
    $untouchedDraft->remove();
}
if ( !$isQuiet )
    $cli->output( "Cleaned up " . count( $untouchedDrafts ) . " internal drafts" );

?>
