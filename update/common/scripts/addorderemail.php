#!/usr/bin/env php
<?php
//
// Created on: <27-Feb-2004 13:12:40 wy>
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

set_time_limit( 0 );

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

$cli = eZCLI::instance();
$endl = $cli->endlineString();

$script = eZScript::instance( array( 'description' => ( "eZ Publish add order email.\n\n" .
                                                        "Fetch email value from eZShopAccountHandler and insert into table ezorder\n" .
                                                        "This script only need to be run when updating from eZ Publish 3.3 to eZ Publish 3.4\n" .
                                                        "\n" .
                                                        "addorderemail.php"),
                                     'use-session' => true,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[db-user:][db-password:][db-database:][db-driver:][sql]",
                                "",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-driver' => "Database driver",
                                       'sql' => "Display sql queries"
                                       ) );
$script->initialize();

$dbUser = $options['db-user'] ? $options['db-user'] : false;
$dbPassword = $options['db-password'] ? $options['db-password'] : false;
$dbHost = $options['db-host'] ? $options['db-host'] : false;
$dbName = $options['db-database'] ? $options['db-database'] : false;
$dbImpl = $options['db-driver'] ? $options['db-driver'] : false;
$showSQL = $options['sql'] ? true : false;
$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;

if ( $siteAccess )
{
    changeSiteAccessSetting( $siteaccess, $siteAccess );
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli = eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for adding order email" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

print( "Starting add email into table ezorder\n" );

//eZDebug::setHandleType( eZDebug::HANDLE_FROM_PHP );

//include_once( "lib/ezutils/classes/ezmodule.php" );
// eZModule::setGlobalPathList( array( "kernel" ) );
require_once( 'lib/ezutils/classes/ezexecution.php' );
require_once( "lib/ezutils/classes/ezdebug.php" );
//include_once( 'kernel/classes/ezorder.php' );

$db = eZDB::instance();

if ( $dbHost or $dbName or $dbUser or $dbImpl )
{
    $params = array();
    if ( $dbHost !== false )
        $params['server'] = $dbHost;
    if ( $dbUser !== false )
    {
        $params['user'] = $dbUser;
        $params['password'] = '';
    }
    if ( $dbPassword !== false )
        $params['password'] = $dbPassword;
    if ( $dbName !== false )
        $params['database'] = $dbName;
    $db = eZDB::instance( $dbImpl, $params, true );
    eZDB::setInstance( $db );
}

$db->setIsSQLOutputEnabled( $showSQL );

$orderArray = eZOrder::fetchList();
$orderCount = count( $orderArray );

print( $endl . $orderCount . " order email will be updated" . $endl );
// Fetch the shop account handler
//include_once( 'kernel/classes/ezshopaccounthandler.php' );
$accountHandler = eZShopAccountHandler::instance();

$i = 0;
$dotMax = 70;
$dotCount = 0;
$limit = 50;

foreach ( array_keys ( $orderArray ) as $key  )
{
    $order =& $orderArray[$key];

    $email = $accountHandler->email( $order );
    $order->setAttribute( 'email', $email );
    $order->store();
    ++$i;
    ++$dotCount;
    if ( $dotCount >= $dotMax or $i >= $orderCount )
    {
        $dotCount = 0;
        $percent = (float)( ($i*100.0) / $orderCount );
        print( " " . $percent . "%" . $endl );
    }
}

print( $endl . "done" . $endl );

$script->shutdown();

?>
