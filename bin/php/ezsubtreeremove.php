#!/usr/bin/env php
<?php
//
// Created on: <27-Jul-2006 15:00:00 vd>
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

// Subtree Remove Script
// file  bin/php/ezsubtreeremove.php

// script initializing
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "\n" .
                                                         "This script will make a remove of a content object subtrees.\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true,
                                      'user' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "[nodes-id:][ignore-trash]",
                                      "",
                                      array( 'nodes-id' => "Subtree nodes ID (separated by comma ',').",
                                             'ignore-trash' => "Ignore trash ('move to trash' by default)."
                                             ),
                                      false,
                                      array( 'user' => true )
                                     );
$script->initialize();
$srcNodesID  = $scriptOptions[ 'nodes-id' ] ? trim( $scriptOptions[ 'nodes-id' ] ) : false;
$moveToTrash = $scriptOptions[ 'ignore-trash' ] ? false : true;
$deleteIDArray = $srcNodesID ? explode( ',', $srcNodesID ) : false;

if ( !$deleteIDArray )
{
    $cli->error( "Subtree remove Error!\nCannot get subtree nodes. Please check nodes-id argument and try again." );
    $script->showHelp();
    $script->shutdown( 1 );
}

$ini =& eZINI::instance();
// Get user's ID who can remove subtrees. (Admin by default with userID = 14)
$userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
$user = eZUser::fetch( $userCreatorID );
if ( !$user )
{
    $cli->error( "Subtree remove Error!\nCannot get user object by userID = '$userCreatorID'.\n(See site.ini[UserSettings].UserCreatorID)" );
    $script->shutdown( 1 );
}
eZUser::setCurrentlyLoggedInUser( $user, $userCreatorID );

$cli->output( "Removing subtrees:" );

foreach ( $deleteIDArray as $nodeID )
{
    $node = eZContentObjectTreeNode::fetch( $nodeID );
    if ( $node === null )
    {
        $cli->error( "\nSubtree remove Error!\nCannot find subtree with nodeID = '$nodeID'." );
        continue;
    }
    $cli->output( "\n-with nodeID = '$nodeID'" );
    eZContentObjectTreeNode::removeSubtrees( array( $nodeID ), $moveToTrash );
    $cli->output( "Done." );
}

$script->shutdown();

?>
