#!/usr/bin/env php
<?php
//
// Definition of updateiscontainer
//
// Created on: <1-Oct-2004 14:42:43 fh>
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

/*! \file updateiscontainer.php
*/

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );


$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish is_container update script\n\n" .
                                                         "This script will set the is_container attribute on known eZ publish classes\n" .
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
    $cli->output( "Unsuitable eZ publish version: " );
    $cli->output( eZPublishSDK::version() );
    $script->shutdown( 1 );
}

$db =& eZDB::instance();

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
