#!/usr/bin/env php
<?php
//
// Created on: <19-Dec-2003 14:34:55 amos>
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

set_time_limit( 0 );

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$endl = $cli->endlineString();

$script =& eZScript::instance( array( 'description' => ( "eZ publish database flattening.\n\n" .
                                                         "Will remove data that is not considered currently in use to minimize the amount of database data it consumes\n" .
                                                         "\n" .
                                                         "Possible values for NAME is:\n" .
                                                         "contentobject, contentclass, workflow, role or all (for all items)\n" .
                                                         "flatten.php -s admin contentobject"),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[db-user:][db-password:][db-database:][db-driver:][sql]",
                                "[name]",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-driver' => "Database driver",
                                       'sql' => "Display sql queries"
                                       ) );
$script->initialize();

if ( count( $options['arguments'] ) < 1 )
{
    $cli->error( "Missing NAME value ( could be contentobject, contentclass, workflow, role or all )" );
    $script->shutdown( 1 );
}

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


$flattenAllItems = false;
$flattenItems = array();
$flatten = array( 'contentobject' => false,
                  'contentclass' => false,
                  'workflow' => false,
                  'role' => false );

foreach ( $options['arguments'] as $arg )
{

    $item = strtolower( $arg );
    if ( $item == 'all' )
        $flattenAllItems = true;
    else
        $flattenItems[] = $item;
}

if ( $flattenAllItems )
{
    $names = array_keys( $flatten );
    foreach ( $names as $name )
    {
        $flatten[$name] = true;
    }
}
else
{
    if ( count( $flattenItems ) == 0 )
    {
        help();
        exit;
    }
    foreach ( $flattenItems as $name )
    {
        $flatten[$name] = true;
    }
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli =& eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for flattening" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

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

include_once( 'kernel/classes/ezpersistentobject.php' );

if ( $flatten['contentobject'] )
{
    include_once( 'kernel/classes/ezcontentobject.php' );
    $cli->output( "Removing non-published content object versions" );
    eZContentObject::removeVersions();
}

if ( $flatten['contentclass'] )
{
    include_once( 'kernel/classes/ezcontentclass.php' );
    $cli->output( "Removing temporary content classes" );
    eZContentClass::removeTemporary();
}

if ( $flatten['workflow'] )
{
    include_once( 'kernel/classes/ezworkflow.php' );
    $cli->output( "Removing temporary workflows" );
    eZWorkflow::removeTemporary();
}

if ( $flatten['role'] )
{
    include_once( 'kernel/classes/ezrole.php' );
    $cli->output( "Removing temporary roles" );
    eZRole::removeTemporary();
}


$script->shutdown();

?>
