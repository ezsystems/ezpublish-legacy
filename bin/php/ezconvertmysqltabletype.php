#!/usr/bin/env php
<?php
//
// Created on: <21-Apr-2004 09:51:56 kk>
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

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish Database Converter\n\n" .
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
$db =& connectToDatabase( $cli, $script, $host, $user, $password, $database );

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

/**
 * Connect to the database
**/
function &connectToDatabase( $cli, $script, $host, $user, $password, $database )
{
    include_once( 'lib/ezdb/classes/ezdb.php' );

    if ( $user )
    {
        $db =& eZDB::instance( "mysql",
                           array( 'server' => $host,
                                  'user' => $user,
                                  'password' => $password,
                                  'database' => $database ) );
    } else
    {
         $db =& eZDB::instance();
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
        $eZpublishTable = strncmp( $tableName, "ez", 2 ) == 0 ? "" : "(non eZ publish)";
        $cli->notice( "$tableName $spaces $tableType $eZpublishTable" );
    }
}

function alterType( $db, $tableName, $newType )
{
     $db->query( "ALTER TABLE $tableName TYPE=$newType" );
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

    $pattern = array( "/TYPE=(\w*)/", "/TABLE `$tableFrom`/" );
    $replacement = array( "TYPE=$newType", "TABLE `$tableTo`" );
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
            $cli->notice( "Skipping table $tableName because it is not an eZ publish table" );
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
