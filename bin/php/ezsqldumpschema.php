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
$script =& eZScript::instance( array( 'description' => ( "eZ publish SQL Schema dump\n\n" .
                                                         "Dump sql schema to soecified file\n".
                                                         "ezsqldumpschema.php --type=mysql --user=root stable33 schema.txt" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[type:][user:][host:][password;][output-array][output-serialized][output-sql]" .
                                "[diff-friendly][meta-data][table-type:][table-charset:][compatible-sql]" .
                                "[format:]" .
                                "[output-types:][allow-multi-insert]",
                                "[database][filename]",
                                array( 'type' => ( "Which database type to use for source, can be one of:\n" .
                                                          "mysql, postgresql" ),
                                       'host' => "Connect to host source database",
                                       'user' => "User for login to source database",
                                       'password' => "Password to use when connecting to source database",
                                       'output-array' => 'Create file with array structures (Human readable)',
                                       'output-serialized' => 'Create file with serialized data (Saves space)',
                                       'output-sql' => 'Create file with SQL data (DB friendly)',
                                       'compatible-sql' => 'Will turn SQL to be more compatible to existing dumps',
                                       'table-type' => ( "The table storage type to use for SQL output when creating tables.\n" .
                                                         "MySQL: bdb, innodb and myisam\n" .
                                                         "PostgreSQL: \n" .
                                                         "Oracle: " ),
                                       'table-charset' => 'Defines the charset to use on tables, the names of the charset depends on database type',
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

if ( file_exists( $database ) and is_file( $database ) )
{
    include_once( 'lib/ezdbschema/classes/ezdbschema.php' );
    $schema =& eZDBSchema::read( $database );
    if ( $schema === false )
    {
            eZDebug::writeError( "Error reading schema from file $database" );
            $script->shutdown( 1 );
            exit( 1 );
    }
    $type = ereg_replace( '^ez', '', $type );
    $dbSchema = eZDBSchema::instance( array( 'type' => $type, 'schema' => $schema ) );
}
else
{
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

    include_once( 'lib/ezdbschema/classes/ezdbschema.php' );
    $dbSchema = eZDBSchema::instance( $db );
}

if ( $dbSchema === false )
{
    $cli->error( "Error instantiating the appropriate schema handler" );
    $script->shutdown( 1 );
    exit( 1 );
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
