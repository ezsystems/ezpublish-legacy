#!/usr/bin/env php
<?php
//
// Created on: <21-Apr-2004 09:51:56 kk>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
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
$script =& eZScript::instance( array( 'description' => ( "eZ publish SQL Schema dump\n\n" .
                                                         "Dump sql schema to soecified file\n".
                                                         "ezsqldumpschema.php --type=mysql --user=root stable33 schema.txt" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[type:][user:][host:][password:]",
                                "[database][filename]",
                                array( 'type' => ( "Which database type to use for source, can be one of:\n" .
                                                          "mysql, postgresql" ),
                                       'host' => "Connect to host source database",
                                       'user' => "User for login to source database",
                                       'password' => "Password to use when connecting to source database",
                                       ) );
$script->initialize();

if ( count( $options['arguments'] ) < 2 )
{
    $cli->error( "Missing match database and/or filename" );
    $script->shutdown( 1 );
}

$type = $options['type'];
$host = $options['host'];
$user = $options['user'];
$password = $options['password'];
$database = $options['arguments'][0];
$filename = $options['arguments'][1];

if ( strlen( trim( $type ) ) == 0)
{
    $cli->error( "No database type chosen" );
    $script->shutdown( 1 );
}

if ( strlen( trim( $user ) ) == 0)
{
    $cli->error( "No database user chosen" );
    $script->shutdown( 1 );
}

include_once( 'lib/ezdb/classes/ezdb.php' );
$db =& eZDB::instance( $type,
                       array( 'server' => $host,
                              'user' => $user,
                              'password' => $password,
                              'database' => $database ),
                       true );

if ( !$db )
{
    $cli->error( 'Could not initialize database' );
    $script->shutdown( 1 );
}

include_once( 'lib/ezdbschema/classes/ezdbschema.php' );
$dbSchema = eZDBSchema::instance( $db );
$dbSchema->writeSerializedSchemaFile( $filename );

$script->shutdown();

?>


