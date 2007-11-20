#!/usr/bin/env php
<?php
//
// Definition of updateiscontainer
//
// Created on: <1-Oct-2004 14:42:43 fh>
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

/*! \file updateiscontainer.php
*/

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );


$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish is_container update script\n\n" .
                                                        "This script will set the is_container attribute on known eZ Publish classes\n" .
                                                        "\n" .
                                                        "Note: The script must be run for each siteaccess" .
                                                        "\n" .
                                                        "updateiscontainer.php -sSITEACCESS" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true,
                                     'min_version'  => '3.4.2',
                                     'max_version' => '3.5.0' ) );

$script->startup();

$options = $script->getOptions( "", "",
                                array() );

$script->initialize();

if ( !$script->validateVersion() )
{
    $cli->output( "Unsuitable eZ Publish version: " );
    $cli->output( eZPublishSDK::version() );
    $script->shutdown( 1 );
}

$db = eZDB::instance();

$classList = array( 'folder', 'article', 'user_group', 'forum', 'forum_topic', 'gallery', 'weblog' );
$idText = "'" . implode( "', '", $classList ) . "'";

$sql = "UPDATE ezcontentclass SET is_container='1' WHERE identifier IN ( $idText )";
if ( !$db->query( $sql ) )
{
    $cli->error( "Failed to run update query" );
    $cli->error( $db->errorMessage() );
    $script->shutdown( 1 );
}

$cli->output( "Updated classes: $idText" );

$script->shutdown();

?>
