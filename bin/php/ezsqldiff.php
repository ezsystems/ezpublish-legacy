#!/usr/bin/env php
<?php
//
// Created on: <19-Mar-2004 09:51:56 amos>
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
$script =& eZScript::instance( array( 'description' => ( "eZ publish SQL diff\n\n" .
                                                         "Displays differences between two database schemas,\n" .
                                                         "and sets exit code based whether there is a difference or not\n" .
                                                         "\n" .
                                                         "ezsqldiff.php --type mysql --user=root stable32 stable33" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[source-type:][source-host:][source-user:][source-password:]" .
                                "[match-type:][match-host:][match-user:][match-password:]" .
                                "[t:|type:][host:][u:|user:][p:|password:]" .
                                "[reverse][check-only]",
                                "[source-db][match-db]",
                                array( 'source-type' => ( "Which database type to use for source, can be one of:\n" .
                                                          "mysql, postgresql" ),
                                       'source-host' => "Connect to host source database",
                                       'source-user' => "User for login to source database",
                                       'source-password' => "Password to use when connecting to source database",
                                       'match-type' => ( "Which database type to use for match, can be one of:\n" .
                                                         "mysql, postgresql" ),
                                       'match-host' => "Connect to host match database",
                                       'match-user' => "User for login to match database",
                                       'match-password' => "Password to use when connecting to match database",
                                       'type' => ( "Which database type to use for match and source, can be one of:\n" .
                                                   "mysql, postgresql" ),
                                       'host' => "Connect to host match and source database",
                                       'user' => "User for login to match and source database",
                                       'password' => "Password to use when connecting to match and source database",
                                       'reverse' => "Reverse the differences",
                                       'check-only' => "Don't show SQLs for the differences, just set exit code and return"
                                       ) );
$script->initialize();

if ( count( $options['arguments'] ) < 1 )
{
    $cli->error( "Missing source database" );
    $script->shutdown( 1 );
}

if ( count( $options['arguments'] ) < 2 )
{
    $cli->error( "Missing match database" );
    $script->shutdown( 1 );
}

$sourceType = $options['source-type'] ? $options['source-type'] : $options['type'];
$sourceDBHost = $options['source-host'] ? $options['source-host'] : $options['host'];
$sourceDBUser = $options['source-user'] ? $options['source-user'] : $options['user'];
$sourceDBPassword = $options['source-password'] ? $options['source-password'] : $options['password'];
$sourceDB = $options['arguments'][0];

$matchType = $options['match-type'] ? $options['match-type'] : $options['type'];
$matchDBHost = $options['match-host'] ? $options['match-host'] : $options['host'];
$matchDBUser = $options['match-user'] ? $options['match-user'] : $options['user'];
$matchDBPassword = $options['match-password'] ? $options['match-password'] : $options['password'];
$matchDB = $options['arguments'][1];


if ( strlen( trim( $sourceType ) ) == 0)
{
    $cli->error( "No source type chosen" );
    $script->shutdown( 1 );
}
if ( !in_array( $sourceType, array( 'mysql', 'postgresql' ) ) )
{
    $cli->error( "Unknown source type " . $cli->stylize( 'emphasize', $sourceType ) );
    $script->shutdown( 1 );
}
if ( strlen( trim( $matchType ) ) == 0)
{
    $cli->error( "No match type chosen" );
    $script->shutdown( 1 );
}
if ( !in_array( $matchType, array( 'mysql', 'postgresql' ) ) )
{
    $cli->error( "Unknown match type " . $cli->stylize( 'emphasize', $matchType ) );
    $script->shutdown( 1 );
}

$ini =& eZINI::instance();

function &loadDatabaseSchema( $type, $host, $user, $password, $db )
{
    if ( $type == 'mysql' )
    {
        include_once( 'lib/ezdbschema/classes/ezmysqlschema.php' );
        $connection = mysql_connect( $host, $user, $password );
        if ( !$connection )
        {
            $cli =& eZCLI::instance();
            $cli->error( "MySQL connection error: " . mysql_error() );
            return false;
        }
        mysql_select_db( $db );
        $dbschema = new eZMysqlSchema();
        $schema = $dbschema->read( $connection );
        return $schema;
    }
    else if ( $type == 'postgresql' )
    {
        include_once( 'lib/ezdbschema/classes/ezpgsqlschema.php' );
        $connection = pg_connect( "host=$host dbname=$db user=$user password=$password" );
        if ( !$connection )
        {
            $cli =& eZCLI::instance();
            $cli->error( "PostgreSQL connection error: " . pg_errormessage( $connection ) );
            return false;
        }
        $dbschema = new eZPgsqlSchema();
        $schema = $dbschema->read( $connection );
        return $schema;
    }
    return false;
}

$sourceSchema = loadDatabaseSchema( $sourceType, $sourceDBHost, $sourceDBUser, $sourceDBPassword, $sourceDB );
if ( !$sourceSchema )
{
    $cli->error( "Failed to load schema from source database" );
    $cli->output( "host=$sourceDBHost, user=$sourceDBUser, database=$sourceDB" );
    $script->shutdown( 1 );
}

$matchSchema = loadDatabaseSchema( $matchType, $matchDBHost, $matchDBUser, $matchDBPassword, $matchDB );
if ( !$matchSchema )
{
    $cli->error( "Failed to load schema from match database" );
    $cli->output( "host=$matchDBHost, user=$matchDBUser, database=$matchDB" );
    $script->shutdown( 1 );
}

function generateSQLChanges( $type, $differences )
{
    if ( $type == 'mysql' )
    {
        return eZMySQLSchema::generateUpgradeFile( $differences );
    }
    else if ( $type == 'postgresql' )
    {
        return eZPGSQLSchema::generateUpgradeFile( $differences );
    }
}

function SQLName( $type )
{
    if ( $type == 'mysql' )
    {
        return 'MySQL';
    }
    else if ( $type == 'postgresql' )
    {
        return 'PostgreSQL';
    }
}

include_once( 'lib/ezdbschema/classes/ezdbschemachecker.php' );

if ( $options['reverse'] )
{
    $differences = eZDbSchemaChecker::diff( $matchSchema, $sourceSchema, $matchType, $sourceType );
    if ( !$options['check-only'] )
    {
        $cli->output( "-- Difference in SQL commands for " . SQLName( $matchType ) );
        $sql = generateSQLChanges( $matchType, $differences );
        $cli->output( $sql );
    }
}
else
{
    $differences = eZDbSchemaChecker::diff( $sourceSchema, $matchSchema, $sourceType, $matchType );
    if ( !$options['check-only'] )
    {
        $cli->output( "-- Difference in SQL commands for " . SQLName( $sourceType ) );
        $sql = generateSQLChanges( $sourceType, $differences );
        $cli->output( $sql );
    }
}

if ( count( $differences ) > 0 )
    $script->setExitCode( 1 );

$script->shutdown();

?>
