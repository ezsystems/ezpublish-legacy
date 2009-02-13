#!/usr/bin/env php
<?php
//
// Created on: <21-Apr-2004 09:51:56 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Database Converter\n\n" .
                                                        "Convert the database to the given type\n".
                                                        "ezconvertmysqltabletype.php [--host=VALUE --user=VALUE --database=VALUE [--password=VALUE]] [--list] [--newtype=TYPE] [--usecopy]" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => false ) );

$script->startup();

$options = $script->getOptions( "[host:][user:][password:][database:][list][newtype:][usecopy]",
                                "",
                                array(
                                       'list' => "List the table types",
                                       'host' => "Connect to host database",
                                       'user' => "User for login to the database",
                                       'password' => "Password to use when connecting to the database",
                                       'newtype' => "Convert the database to the given type.\nType can either be: myisam or innodb\n".
                                                    "Make sure that you have made a BACKUP UP of YOUR DATABASE!",
                                       'usecopy' => "To convert the table we rename the original table and copy the data to the new table structure.\n".
                                                    "This conversion method is much slower and has a higher risk to corrupt the data in the database.\n".
                                                    "However this option may circumvent the MySQL crash on the ALTER query." )
                              );
$script->initialize();

$host = $options['host'];
$user = $options['user'];

$password = is_string( $options['password'] ) ? $options['password'] : "";
$database = $options['database'];
$listMode = $options['list'];
$newType = $options["newtype"];
$usecopy = $options["usecopy"];

checkParameters( $cli, $script, $options, $host, $user, $password, $database, $listMode, $newType );
$db = connectToDatabase( $cli, $script, $host, $user, $password, $database );

// If the listMode parameter is set or no newType is assigned then show the list.
if ( $listMode || !isset( $newType ) )
{
    listTypes( $cli, $db );
}
else
{
    setNewType( $cli, $db, $newType, $usecopy );
}

/**
 *  Check whether the parameters are correctly set.
**/
function checkParameters( $cli, $script, $options, $host, $user, $password, $database, $listMode, $newType )
{
    // Extra parameters are not tolerated.
    if ( count ( $options['arguments'] ) != 0 )
    {
            $cli->error( "Unknown parameters" );
            $script->shutdown( 1 );
    }

    // Host, User, and database are like the three musketeers.
    // Either the three parameters must be set or none.
    if ( isset( $host ) || isset( $user ) || isset( $database ) )
    {
        if ( !isset( $host ) || !isset( $user ) || !isset( $database ) )
        {
            $cli->error( "Use the host, user, database, and optionally a password together." );
            $script->shutdown( 1 );
        }
    }

    // If the newType is set, check whether the given type exist.
    if ( $newType )
    {
        switch ( strtolower( $newType ) )
        {
            case "innodb": break;
            case "myisam": break;

            default: $cli->error( "New table type not supported." );
                     $script->shutDown( 1 );
        }
    }
}

// Creates a displayable string for the end-user explaining
// which database, host, user and password which were tried
function eZTriedDatabaseString( $database, $host, $user, $password )
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
    return $msg;
}

/**
 * Connect to the database
**/
function connectToDatabase( $cli, $script, $host, $user, $password, $database )
{
    //include_once( 'lib/ezdb/classes/ezdb.php' );

    if ( $user )
    {
        $db = eZDB::instance( "mysql",
                           array( 'server' => $host,
                                  'user' => $user,
                                  'password' => $password,
                                  'database' => $database ) );
    } else
    {
         $db = eZDB::instance();
         if ( $db->databaseName() != "mysql" )
         {
            $cli->error( 'This script can only show and convert mysql databases.' );
            $script->shutdown( 1 );
         }
    }

    if ( !is_object( $db ) )
    {
        $cli->error( 'Could not initialize database:' );
        $cli->error( '* No database handler was found for mysql' );
        $script->shutdown( 1 );
    }
    if ( !$db or !$db->isConnected() )
    {
        $cli->error( "Could not initialize database:" );
        $cli->error( "* Tried database " . eZTriedDatabaseString( $database, $host, $user, $password ) );

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

    return $db;
}

function getTableType( $db, $tableName )
{
    $res = $db->arrayQuery( "SHOW CREATE TABLE `$tableName`" );
    preg_match( '/(?:TYPE|ENGINE)=(\w*)/', $res[0]["Create Table"], $grep );
    return $grep[1];
}

function listTypes( $cli, $db )
{
    $tables = $db->arrayQuery( "show tables" );

    $spaces = str_pad ( ' ', 35 );
    $cli->notice( "Table $spaces Type" );
    $cli->notice( "----- $spaces ----" );
    foreach ( $tables as $table )
    {
        $tableName = current( $table );
        $tableType = getTableType( $db, $tableName );

        $spaces = str_pad(' ', 40 - strlen( $tableName ) );
        $eZpublishTable = strncmp( $tableName, "ez", 2 ) == 0 ? "" : "(non eZ Publish)";
        $cli->notice( "$tableName $spaces $tableType $eZpublishTable" );
    }
}

function alterType( $db, $tableName, $newType )
{
     $db->query( "ALTER TABLE $tableName ENGINE=$newType" );
}

function renameTable( $db, $tableFrom, $tableTo )
{
    $db->query( "ALTER TABLE $tableFrom RENAME $tableTo" );
}

function copyTable( $db, $tableFrom, $tableTo )
{
    $db->query( "INSERT INTO $tableTo SELECT * FROM $tableFrom" );
}

function createTableStructure( $db, $tableFrom, $tableTo, $newType )
{
    $res = $db->arrayQuery( "SHOW CREATE TABLE `$tableFrom`" );

    $pattern = array( "/(TYPE|ENGINE)=(\w*)/", "/TABLE `$tableFrom`/" );
    $replacement = array( "ENGINE=$newType", "TABLE `$tableTo`" );
    $structure = preg_replace( $pattern, $replacement, $res[0]["Create Table"] );

    $db->query( $structure );
}

function dropTable( $db, $tableName )
{
    $db->query( "DROP TABLE $tableName" );
}

function setNewType( $cli, $db, $newType, $usecopy )
{
    $tables = $db->arrayQuery( "show tables" );

    foreach ( $tables as $table )
    {
        $tableName = current( $table );

        // Checking if it is necessary to convert the table.
        if ( strncmp( $tableName, "ez", 2 ) != 0 )
        {
            $cli->notice( "Skipping table $tableName because it is not an eZ Publish table" );
        }
        else if ( strcasecmp( getTableType( $db, $tableName ), $newType ) == 0 )
        {
            $cli->notice( "Skipping table $tableName because it has already the $newType type" );
        }
        else
        {
            // Yes, convert.
            $cli->notice( "Converting table $tableName ... " );

            if ( !$usecopy )
            {
                // The simple one
                alterType( $db, $tableName, $newType );
            }
            else
            {
                renameTable( $db, $tableName, "eztemp__$tableName" );
                createTableStructure( $db, "eztemp__$tableName", $tableName, $newType );
                copyTable( $db, "eztemp__$tableName", $tableName );
                dropTable( $db, "eztemp__$tableName" );
            }
        }
    }
}

$script->shutdown();
?>
