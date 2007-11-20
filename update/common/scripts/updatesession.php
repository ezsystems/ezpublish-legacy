#!/usr/bin/env php
<?php
//
// Created on: <19-Apr-2004 11:42:29 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Session Updater\n\n" .
                                                        "This script will update entries in the session table*.\n" .
                                                        "* This is only required when updating from 3.3 or lower" .
                                                        "\n" .
                                                        "updatesession.php" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "", "",
                                array() );
$script->initialize();

$db = eZDB::instance();

//include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

require_once( 'lib/ezutils/classes/ezsession.php' );

$db = eZDB::instance();

$sql = "SELECT count( session_key ) as count FROM ezsession";
$rows = $db->arrayQuery( $sql );
$count = $rows[0]['count'];

$script->setIterationData( '.', '~' );
$script->resetIteration( $count );

$limit = 50;
$index = 0;
session_start();
while ( $index < $count )
{
    $sql = "SELECT session_key, user_id, data FROM ezsession";
    $rows = $db->arrayQuery( $sql, array( 'limit' => $limit, 'offset' => $index ) );
    foreach ( $rows as $row )
    {
        $_SESSION = array();
        $status = session_decode( $row['data'] );
        if ( $status and isset( $_SESSION['eZUserLoggedInID'] ) )
        {
            $userID = $_SESSION['eZUserLoggedInID'];
            if ( $userID != $row['user_id'] )
            {
                $sql = "UPDATE ezsession SET user_id='" . (int)$userID . "' WHERE session_key='" . $db->escapeString( $row['session_key'] ) . "'";
                $db->query( $sql );
                $status = true;
            }
            else
            {
                $status = false;
            }
        }
        $script->iterate( $cli, $status, '' );
    }
    $index += count( $rows );
}

$script->shutdown();

?>
