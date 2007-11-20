#!/usr/bin/env php
<?php
//
// Definition of Updaterssimport class
//
// Created on: <01-Dec-2005 10:59:24 hovik>
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

/*! \file updaterssimport.php
*/

set_time_limit( 0 );

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );
//include_once( 'kernel/classes/ezrssimport.php' );

$cli = eZCLI::instance();
$endl = $cli->endlineString();

$script = eZScript::instance( array( 'description' => ( "Update RSS import settings.\n\n" .
                                                         "Goes through all RSS imports and upgrades them." .
                                                         "\n" .
                                                         "updaterssimport.php"),
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[db-host:][db-user:][db-password:][db-database:][db-type:|db-driver:][sql]",
                                "",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-driver' => "Database driver",
                                       'db-type' => "Database driver, alias for --db-driver",
                                       'sql' => "Display sql queries" ) );
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
            $cli->notice( "Using siteaccess $siteaccess for rss import update" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

print( "Starting object re-indexing\n" );

require_once( 'lib/ezutils/classes/ezexecution.php' );
require_once( "lib/ezutils/classes/ezdebug.php" );

//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

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

foreach( eZRSSImport::fetchList( true, false ) as $rssImport )
{
    if ( $rssImport->attribute( 'import_description' ) != '' )
    {
        continue;
    }

    $classAttributeDescription = array();

    $classAttributeList = eZContentClassAttribute::fetchListByClassID( $rssImport->attribute( 'class_id' ) );
    foreach( $classAttributeList as $classAttribute )
    {
        if ( $classAttribute->attribute( 'identifier' ) == $rssImport->attribute( 'class_title' ) )
        {
            $classAttributeDescription[$classAttribute->attribute( 'id' )] = 'item - elements - title';
        }

        if ( $classAttribute->attribute( 'identifier' ) == $rssImport->attribute( 'class_url' ) )
        {
            $classAttributeDescription[$classAttribute->attribute( 'id' )] = 'item - elements - link';
        }

        if ( $classAttribute->attribute( 'identifier' ) == $rssImport->attribute( 'class_description' ) )
        {
            $classAttributeDescription[$classAttribute->attribute( 'id' )] = 'item - elements - description';
        }
    }

    $importDescription = array( 'rss_version' => $rssImport->getRSSVersion( $rssImport->attribute( 'url' ) ),
                                'object_attributes' => array(),
                                'class_attributes' => $classAttributeDescription );

    $rssImport->setImportDescription( $importDescription );

    $rssImport->store();
}

print( $endl . "done" . $endl );

$script->shutdown();

?>
