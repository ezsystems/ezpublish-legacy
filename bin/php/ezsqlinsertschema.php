#!/usr/bin/env php
<?php
//
// Created on: <12-Nov-2004 14:13:19 jb>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish SQL Schema insert\n\n" .
                                                        "Insert database schema and data to specified database\n".
                                                        "ezsqlinsertschema.php --type=mysql --user=root share/db_schema.dba ezp35stable" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[type:][user:][host:][password;][port:][socket:]" .
                                "[table-type:][table-charset:]" .
                                "[insert-types:][allow-multi-insert][schema-file:][clean-existing]",
                                "[filename][database]",
                                array( 'type' => ( "Which database type to use, can be one of:\n" .
                                                   "mysql, postgresql or any other supported by extensions" ),
                                       'host' => "Connect to host source database",
                                       'user' => "User for login to source database",
                                       'password' => "Password to use when connecting to source database",
                                       'port' => 'Port to connect to source database',
                                       'socket' => 'Socket to connect to match and source database (only for MySQL)',
                                       'table-type' => ( "The table storage type to use for SQL output when creating tables.\n" .
                                                         "MySQL: bdb, innodb and myisam\n" .
                                                         "PostgreSQL: \n" .
                                                         "Oracle: " ),
                                       'clean-existing' => 'Clean up existing schema (remove all database objects)',
                                       'table-charset' => 'Defines the charset to use on tables, the names of the charset depends on database type',
                                       'schema-file' => 'The schema file to use when dumping data structures, is only required when dumping from files',
                                       'allow-multi-insert' => ( 'Will create INSERT statements with multiple data entries (applies to data output only)' . "\n" .
                                                                 'Multi-inserts will only be created for databases that support it' ),
                                       'insert-types' => ( "A comma separated list of types to include in dump (default is schema only):\n" .
                                                           "schema - Table schema\n" .
                                                           "data - Table data\n" .
                                                           "all - Both table schema and data\n" .
                                                           "none - Insert nothing (useful if you want to clean up schema only)" )
                                       ) );
$script->initialize();

$type = $options['type'];
$host = $options['host'];
$user = $options['user'];
$socket = $options['socket'];
$password = $options['password'];
$port = $options['port'];

if ( !is_string( $password ) )
    $password = '';

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

            case 'none':
            {
                $includeSchema = false;
                $includeData   = false;
            } break;
        }
    }
}

$onlyCleanupSchema = $options['clean-existing'] && !$includeSchema && !$includeData;

switch ( count( $options['arguments'] ) )
{
    case 0:
        $cli->error( "Missing filename and database" );
        $script->shutdown( 1 );
        break;
    case 1:
        if ( $onlyCleanupSchema )
        {
            $database = $options['arguments'][0];
            $filename  = '';
        }
        else
        {
            $cli->error( "Missing database" );
            $script->shutdown( 1 );
        }
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

if ( !$onlyCleanupSchema and ( !file_exists( $filename ) or !is_file( $filename ) ) )
{
    $cli->error( "File '$filename' does not exist" );
    $script->shutdown( 1 );
}

if ( strlen( trim( $user ) ) == 0)
{
    $cli->error( "No database user chosen" );
    $script->shutdown( 1 );
}

// Creates a displayable string for the end-user explaining
// which database, host, user and password which were tried
function eZTriedDatabaseString( $database, $host, $user, $password, $socket )
{
    $msg = "'$database'";
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
    if ( strlen( $socket ) > 0 )
        $msg .= " and with socket '$socket'";
    return $msg;
}

// Connect to database

$parameters = array( 'server' => $host,
                     'user' => $user,
                     'password' => $password,
                     'database' => $database );
if ( $socket )
    $parameters['socket'] = $socket;
if ( $port )
    $parameters['port'] = $port;
$db = eZDB::instance( $type,
                       $parameters,
                       true );

if ( !is_object( $db ) )
{
    $cli->error( 'Could not initialize database:' );
    $cli->error( '* No database handler was found for $type' );
    $script->shutdown( 1 );
}
if ( !$db or !$db->isConnected() )
{
    $cli->error( "Could not initialize database:" );
    $cli->error( "* Tried database " . eZTriedDatabaseString( $database, $host, $user, $password, $socket ) );

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

$schemaArray = eZDbSchema::read( $filename, true );
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
    $schema = eZDbSchema::read( $options['schema-file'], false );
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
}
$schemaArray['type'] = $type;

// Clean elements if specified

if ( $options['clean-existing'] )
{
    $status = eZDBTool::cleanup( $db );
    if ( !$status )
    {
        $cli->error( "Failed cleaning up existing database elements" );
        $cli->error( "* Tried database " . eZTriedDatabaseString( $database, $host, $user, $password, $socket ) );
        $cli->error( "Error(" . $db->errorNumber() . "): " . $db->errorMessage() );
        $script->shutdown( 1 );
    }
}

// Prepare schema handler

$schemaArray['instance'] =& $db;
$dbSchema = eZDbSchema::instance( $schemaArray );

if ( $dbSchema === false )
{
    $cli->error( "Error instantiating the appropriate schema handler" );
    $script->shutdown( 1 );
}

// Insert schema/data by running SQL statements to database
$status = ( $includeSchema or $includeData ) ? $dbSchema->insertSchema( $dbschemaParameters ) : true;
if ( !$status )
{
    $cli->error( "Failed insert schema/data to database" );
    $cli->error( "* Tried database " . eZTriedDatabaseString( $database, $host, $user, $password, $socket ) );
    $script->shutdown( 1 );
}

$script->shutdown();

?>
