#!/usr/bin/env php
<?php
//
// Created on: <19-Apr-2004 11:42:29 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish Session Updater\n\n" .
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

$db =& eZDB::instance();

include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

include_once( 'lib/ezutils/classes/ezsession.php' );

$db =& eZDB::instance();

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
