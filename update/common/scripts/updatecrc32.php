#!/usr/bin/env php
<?php
//
// Definition of updatecrc32
//
// Created on: <17-Jan-2006 16:05:43 dl>
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

/*! \file updatecrc32.php
*/

set_time_limit ( 0 );

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish crc32 polynomial update script.\n\n" .
                                                        "Will go trough and update crc32 polynomial form signed int\n".
                                                        "to unsigned int.\n" .
                                                        "\n" .
                                                        "updatecrc32.php" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[db-user:][db-password:][db-database:][db-type:|db-driver:][sql]",
                                "",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-driver' => "Database driver",
                                       'db-type' => "Database driver, alias for --db-driver",
                                       'sql' => "Display sql queries"
                                       ) );
$script->initialize();

$dbUser = $options['db-user'] ? $options['db-user'] : false;
$dbPassword = $options['db-password'] ? $options['db-password'] : false;
$dbHost = isset( $options['db-host'] ) && $options['db-host'] ? $options['db-host'] : false;
$dbName = $options['db-database'] ? $options['db-database'] : false;
$dbImpl = $options['db-driver'] ? $options['db-driver'] : false;
$showSQL = $options['sql'] ? true : false;

//include_once( 'lib/ezdb/classes/ezdb.php' );
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


// Update ezpolicy_limitation_value.value for 'SiteAccess' limitation.
$query = "SELECT ezpolicy_limitation_value.id, ezpolicy_limitation_value.value
          FROM ezpolicy_limitation_value, ezpolicy_limitation
          WHERE ezpolicy_limitation.id = ezpolicy_limitation_value.limitation_id AND
                ezpolicy_limitation.identifier = 'SiteAccess' AND
                ezpolicy_limitation_value.value < 0";

$limitationValues = $db->arrayQuery( $query );

foreach( $limitationValues as $limitationValue )
{
    $value = $limitationValue['value'];
    $value = sprintf( '%u', $value );
    $db->query( "UPDATE ezpolicy_limitation_value SET value='$value' WHERE id={$limitationValue['id']}" );
}

$script->shutdown();

?>
