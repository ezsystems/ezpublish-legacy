#!/usr/bin/env php
<?php
//
// Created on: <21-Apr-2004 09:51:56 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
$script = eZScript::instance( array( 'description' => ( "eZ Publish SQL Schema dump\n\n" .
                                                        "Dump sql schema to specified file or standard output\n".
                                                        "ezsqldumpschema.php --type=mysql --user=root stable33 schema.sql" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[type:][user:][host:][password;][port:][socket:][output-array][output-serialized][output-sql]" .
                                "[diff-friendly][meta-data][table-type:][table-charset:][compatible-sql][no-sort]" .
                                "[format:]" .
                                "[output-types:][allow-multi-insert][schema-file:]",
                                "[database][filename]",
                                array( 'type' => ( "Which database type to use for source, can be one of:\n" .
                                                          "mysql, postgresql, oracle" ),
                                       'host' => "Connect to host source database",
                                       'user' => "User for login to source database",
                                       'password' => "Password to use when connecting to source database",
                                       'port' => 'Port to connect to source database',
                                       'socket' => 'Socket to connect to match and source database (only for MySQL)',
                                       'output-array' => 'Create file with array structures (Human readable)',
                                       'output-serialized' => 'Create file with serialized data (Saves space)',
                                       'output-sql' => 'Create file with SQL data (DB friendly)',
                                       'compatible-sql' => 'Will turn SQL to be more compatible to existing dumps',
                                       'no-sort' => 'Do not sort table columns in the dumped data structure',
                                       'table-type' => ( "The table storage type to use for SQL output when creating tables.\n" .
                                                         "MySQL: bdb, innodb and myisam\n" .
                                                         "PostgreSQL: \n" .
                                                         "Oracle: " ),
                                       'table-charset' => 'Defines the charset to use on tables, the names of the charset depends on database type',
                                       'schema-file' => 'The schema file to use when dumping data structures, is only required when dumping from files',
                                       'format' => ( "The output format (default is generic)\n" .
                                                     "generic - Format which suits all databases\n" .
                                                     "local - Format which suits only the database it was dumped from." ),
                                       'meta-data' => 'Will include extra meta-data information specific to the database.',
                                       'diff-friendly' => 'Will make the output friendlier towards the diff command (applies to SQL output only)',
                                       'allow-multi-insert' => ( 'Will create INSERT statements with multiple data entries (applies to data output only)' . "\n" .
                                                                 'Multi-inserts will only be created for databases that support it' ),
                                       'output-types' => ( "A comma separated list of types to include in dump (default is schema only):\n" .
                                                           "schema - Table schema\n" .
                                                           "data - Table data\n" .
                                                           "all - Both table schema and data" )
                                       ) );
$script->initialize();

$type = $options['type'];
$host = $options['host'];
$user = $options['user'];
$port = $options['port'];
$socket = $options['socket'];
$password = $options['password'];

if ( !is_string( $password ) )
    $password = '';

switch ( count( $options['arguments'] ) )
{
    case 0:
        $cli->error( "Missing match database and/or filename" );
        $script->shutdown( 1 );
        break;
    case 1:
        $database = $options['arguments'][0];
        $filename = 'php://stdout';
        break;
    case 2:
        $database = $options['arguments'][0];
        $filename = $options['arguments'][1];
        break;
    case 3:
        $cli->error( "Too many arguments" );
        $script->shutdown( 1 );
        break;
}

$includeSchema = true;
$includeData = false;

if ( $options['output-types'] )
{
    $includeSchema = false;
    $includeData = false;
    $includeTypes = explode( ',', $options['output-types'] );
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
                             'format' => $options['format'] ? $options['format'] : 'generic',
                             'meta_data' => $options['meta-data'],
                             'table_type' => $options['table-type'],
                             'table_charset' => $options['table-charset'],
                             'compatible_sql' => $options['compatible-sql'],
                             'allow_multi_insert' => $options['allow-multi-insert'],
                             'diff_friendly' => $options['diff-friendly'] );
if ( $options['no-sort'] )
{
    $dbschemaParameters['sort_columns'] = false;
}

$outputType = 'serialized';
if ( $options['output-array'] )
    $outputType = 'array';
if ( $options['output-serialized'] )
    $outputType = 'serialized';
if ( $options['output-sql'] )
    $outputType = 'sql';

if ( strlen( trim( $type ) ) == 0)
{
    $cli->error( "No database type chosen" );
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

if ( file_exists( $database ) and is_file( $database ) )
{
    $schemaArray = eZDbSchema::read( $database, true );

    if ( $includeData and !isset( $schemaArray['data'] ) )
    {
        $cli->error( "The specified data file '$database' contains no data" );
        $script->shutdown( 1 );
    }

    if ( $includeData and !$options['schema-file'] )
    {
        $cli->error( "Cannot dump data without a schema file, please specify with --schema-file" );
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
        $cli->error( "No schema was found in file $database" );
        $cli->error( "Specify --output-types=data if you are interested in data only" );
        $script->shutdown( 1 );
    }

    if ( $schemaArray === false )
    {
        eZDebug::writeError( "Error reading schema from file $database" );
        $script->shutdown( 1 );
    }
    $schemaArray['type'] = $type;
    $dbSchema = eZDbSchema::instance( $schemaArray );
}
else
{
    if ( strlen( trim( $user ) ) == 0)
    {
        $cli->error( "No database user chosen" );
        $script->shutdown( 1 );
    }

    $parameters = array( 'use_defaults' => false,
                         'server' => $host,
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

    $dbSchema = eZDbSchema::instance( $db );
}

if ( $dbSchema === false )
{
    $cli->error( "Error instantiating the appropriate schema handler" );
    $script->shutdown( 1 );
}

if ( $outputType == 'serialized' )
{
    $dbSchema->writeSerializedSchemaFile( $filename,
                                          $dbschemaParameters );
}
else if ( $outputType == 'array' )
{
    $dbSchema->writeArraySchemaFile( $filename,
                                     $dbschemaParameters );
}
else if ( $outputType == 'sql' )
{
    $dbSchema->writeSQLSchemaFile( $filename,
                                   $dbschemaParameters );
}

$script->shutdown();

?>