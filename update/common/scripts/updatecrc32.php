#!/usr/bin/env php
<?php
//
// Definition of updatecrc32
//
// Created on: <17-Jan-2006 16:05:43 dl>
//
// Copyright (C) 1999-2006 eZ systems as. All rights reserved.
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

/*! \file updatecrc32.php
*/

set_time_limit ( 0 );

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish crc32 polynomial update script.\n\n" .
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

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$db =& eZDB::instance();

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
    $db =& eZDB::instance( $dbImpl, $params, true );
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
