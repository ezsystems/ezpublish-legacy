#!/usr/bin/env php
<?php
//
// Created on: <12-Nov-2004 14:13:19 jb>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
$script =& eZScript::instance( array( 'description' => ( "eZ publish SQL Schema insert\n\n" .
                                                         "Insert database schema and data to specified database\n".
                                                         "ezsqlinsertschema.php --type=mysql --user=root share/db_schema.dba ezp35stable" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[type:][user:][host:][password;]" .
                                "[table-type:][table-charset:]" .
                                "[insert-types:][allow-multi-insert][schema-file:]",
                                "[filename][database]",
                                array( 'type' => ( "Which database type to use, can be one of:\n" .
                                                   "mysql, postgresql or any other supported by extensions" ),
                                       'host' => "Connect to host source database",
                                       'user' => "User for login to source database",
                                       'password' => "Password to use when connecting to source database",
                                       'table-type' => ( "The table storage type to use for SQL output when creating tables.\n" .
                                                         "MySQL: bdb, innodb and myisam\n" .
                                                         "PostgreSQL: \n" .
                                                         "Oracle: " ),
                                       'table-charset' => 'Defines the charset to use on tables, the names of the charset depends on database type',
                                       'schema-file' => 'The schema file to use when dumping data structures, is only required when dumping from files',
                                       'allow-multi-insert' => ( 'Will create INSERT statements with multiple data entries (applies to data output only)' . "\n" .
                                                                 'Multi-inserts will only be created for databases that support it' ),
                                       'insert-types' => ( "A comma separated list of types to include in dump (default is schema only):\n" .
                                                           "schema - Table schema\n" .
                                                           "data - Table data\n" .
                                                           "all - Both table schema and data" )
                                       ) );
$script->initialize();

$type = $options['type'];
$host = $options['host'];
$user = $options['user'];
$password = $options['password'];

if ( !is_string( $password ) )
    $password = '';

switch ( count( $options['arguments'] ) )
{
    case 0:
        $cli->error( "Missing filename and database" );
        $script->shutdown( 1 );
        break;
    case 1:
        $cli->error( "Missing database" );
        $script->shutdown( 1 );
        break;
    case 2:
        $filename = $options['arguments'][0];
        $database = $options['arguments'][1];
        break;
    case 3:
        $cli->error( "Too many arguments" );
        $script->shutdown( 1 );
        break;
}

$includeSchema = true;
$includeData = false;

if ( $options['insert-types'] )
{
    $includeSchema = false;
    $includeData = false;
    $includeTypes = explode( ',', $options['insert-types'] );
    foreach ( $includeTypes as $includeType )
    {
        switch ( $includeType )
        {
            case 'all':
            {
                $includeSchema = true;
                $includeData = true;
            } break;

            case 'schema':
            {
                $includeSchema = true;
            } break;

            case 'data':
            {
                $includeData = true;
            } break;
        }
    }
}

$dbschemaParameters = array( 'schema' => $includeSchema,
                             'data' => $includeData,
                             'format' => 'local',
                             'table_type' => $options['table-type'],
                             'table_charset' => $options['table-charset'],
                             'allow_multi_insert' => $options['allow-multi-insert'] );


if ( strlen( trim( $type ) ) == 0 )
{
    $cli->error( "No database type chosen" );
    $script->shutdown( 1 );
}

if ( !file_exists( $filename ) or !is_file( $filename ) )
{
    $cli->error( "Filename $file does not exist" );
    $script->shutdown( 1 );
}

if ( strlen( trim( $user ) ) == 0)
{
    $cli->error( "No database user chosen" );
    $script->shutdown( 1 );
}

// Connect to database

include_once( 'lib/ezdb/classes/ezdb.php' );
$db =& eZDB::instance( $type,
                       array( 'server' => $host,
                              'user' => $user,
                              'password' => $password,
                              'database' => $database ) );

if ( !is_object( $db ) )
{
    $cli->error( 'Could not initialize database:' );
    $cli->error( '* No database handler was found for $type' );
    $script->shutdown( 1 );
}
if ( !$db or !$db->isConnected() )
{
    $cli->error( "Could not initialize database:" );
    $msg = "* Tried database '$database'";
    if ( strlen( $host ) > 0 )
    {
        $msg .= " at host '$host'";
    }
    else
    {
        $msg .= " locally";
    }
    if ( strlen( $user ) > 0 )
    {
        $msg .= " with user '$user'";
    }
    if ( strlen( $password ) > 0 )
        $msg .= " and with a password";
    $cli->error( $msg );

    // Fetch the database error message if there is one
    // It will give more feedback to the user what is wrong
    $msg = $db->errorMessage();
    if ( $msg )
    {
        $number = $db->errorNumber();
        if ( $number > 0 )
            $msg .= '(' . $number . ')';
        $cli->error( '* ' . $msg );
    }
    $script->shutdown( 1 );
}

// Load in schema/data files

include_once( 'lib/ezdbschema/classes/ezdbschema.php' );
$schemaArray =& eZDBSchema::read( $filename, true );
if ( $includeData and !$options['schema-file'] )
{
    $cli->error( "Cannot insert data without a schema file, please specify with --schema-file" );
    $script->shutdown( 1 );
}

if ( $options['schema-file'] )
{
    if ( !file_exists( $options['schema-file'] ) or !is_file( $options['schema-file'] ) )
    {
        $cli->error( "Schema file " . $options['schema-file'] . " does not exist" );
        $script->shutdown( 1 );
    }
    $schema =& eZDBSchema::read( $options['schema-file'], false );
    $schemaArray['schema'] = $schema;
}

if ( $includeSchema and
     ( !isset( $schemaArray['schema'] ) or
       !$schemaArray['schema'] ) )
{
    $cli->error( "No schema was found in file $filename" );
    $cli->error( "Specify --insert-types=data if you are interested in data only" );
    $script->shutdown( 1 );
}

if ( $schemaArray === false )
{
    eZDebug::writeError( "Error reading schema from file $filename" );
    $script->shutdown( 1 );
    exit( 1 );
}
$schemaArray['type'] = $type;

// Prepare schema handler

$schemaArray['instance'] =& $db;
$dbSchema = eZDBSchema::instance( $schemaArray );

if ( $dbSchema === false )
{
    $cli->error( "Error instantiating the appropriate schema handler" );
    $script->shutdown( 1 );
    exit( 1 );
}

// Insert schema/data by running SQL statements to database
$dbSchema->insertSchema( $dbschemaParameters );

$script->shutdown();

?>
