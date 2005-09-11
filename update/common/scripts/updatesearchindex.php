#!/usr/bin/env php
<?php
//
// Created on: <28-Nov-2002 12:45:40 bf>
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

set_time_limit( 0 );

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$endl = $cli->endlineString();

$script =& eZScript::instance( array( 'description' => ( "eZ publish search index updater.\n\n" .
                                                         "Goes trough all objects and reindexes the meta data to the search engine" .
                                                         "\n" .
                                                         "updatesearchindex.php"),
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[db-host:][db-user:][db-password:][db-database:][db-type:|db-driver:][sql][clean]",
                                "",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-driver' => "Database driver",
                                       'db-type' => "Database driver, alias for --db-driver",
                                       'sql' => "Display sql queries",
                                       'clean' =>  "Remove all search data before beginning indexing"
                                       ) );
$script->initialize();

$dbUser = $options['db-user'] ? $options['db-user'] : false;
$dbPassword = $options['db-password'] ? $options['db-password'] : false;
$dbHost = $options['db-host'] ? $options['db-host'] : false;
$dbName = $options['db-database'] ? $options['db-database'] : false;
$dbImpl = $options['db-driver'] ? $options['db-driver'] : false;
$showSQL = $options['sql'] ? true : false;
$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;
$cleanupSearch = $options['clean'] ? true : false;

if ( $siteAccess )
{
    changeSiteAccessSetting( $siteaccess, $siteAccess );
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli =& eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for search index update" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

print( "Starting object re-indexing\n" );

include_once( 'lib/ezutils/classes/ezexecution.php' );
include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "kernel/classes/ezsearch.php" );

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

if ( $cleanupSearch )
{
    print( "{eZSearchEngine: Cleaning up search data" );
    eZSearch::cleanup();
    print( "}$endl" );
}

// Get top node
$topNodeArray = eZPersistentObject::fetchObjectList( eZContentObjectTreeNode::definition(),
                                                      null,
                                                      array( 'parent_node_id' => 1,
                                                             'depth' => 1 ) );
$subTreeCount = 0;
foreach ( array_keys ( $topNodeArray ) as $key  )
{
    $subTreeCount += $topNodeArray[$key]->subTreeCount( array( 'Limitation' => false ) );
}

print( "Number of objects to index: $subTreeCount $endl" );

$i = 0;
$dotMax = 70;
$dotCount = 0;
$limit = 50;

foreach ( array_keys ( $topNodeArray ) as $key  )
{
    $node =& $topNodeArray[$key];
    $offset = 0;
    $subTree =& $node->subTree( array( 'Offset' => $offset, 'Limit' => $limit,
                                       'Limitation' => array() ) );
    while ( $subTree != null )
    {
        foreach ( $subTree as $innerNode )
        {
            $object =& $innerNode->attribute( 'object' );
            eZSearch::removeObject( $object );
            eZSearch::addObject( $object );
            ++$i;
            ++$dotCount;
            print( "." );
            if ( $dotCount >= $dotMax or $i >= $subTreeCount )
            {
                $dotCount = 0;
                $percent = (float)( ($i*100.0) / $subTreeCount );
                print( " " . $percent . "%" . $endl );
            }
        }
        $offset += $limit;
        $subTree =& $node->subTree( array( 'Offset' => $offset, 'Limit' => $limit,
                                           'Limitation' => array() ) );
    }
}

print( $endl . "done" . $endl );

$script->shutdown();

?>
