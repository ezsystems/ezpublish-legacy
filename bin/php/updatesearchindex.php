#!/usr/bin/env php
<?php
/**
 * File containing the updatesearchindex.php script.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

set_time_limit( 0 );

require 'autoload.php';

$cli = eZCLI::instance();
$endl = $cli->endlineString();

$script = eZScript::instance( array( 'description' => ( "eZ Publish search index updater.\n\n" .
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

$script->setIterationData( '.', '~' );

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
    changeSiteAccessSetting( $siteAccess );
}

function changeSiteAccessSetting( $siteAccess )
{
    $cli = eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $siteAccess) )
    {
        $cli->output( "Using siteaccess $siteAccess for nice url update" );
    }
    else
    {
        $cli->notice( "Siteaccess $siteAccess does not exist, using default siteaccess" );
    }
}

print( "Starting object re-indexing\n" );

eZExecution::registerShutdownHandler();
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

if ( $cleanupSearch )
{
    print( "{eZSearchEngine: Cleaning up search data" );
    eZSearch::cleanup();
    print( "}$endl" );
}

$def = eZContentObject::definition();
$conds = array(
    'status' => eZContentObject::STATUS_PUBLISHED
);

$count = eZPersistentObject::count( $def, $conds, 'id' );

print( "Number of objects to index: $count $endl" );

$length = 50;
$limit = array( 'offset' => 0 , 'length' => $length );

$fieldFilters = null;

$script->resetIteration( $count );

do
{
    // clear in-memory object cache
    eZContentObject::clearCache();

    $objects = eZPersistentObject::fetchObjectList( $def, $fieldFilters, $conds, null, $limit );

    foreach ( $objects as $object )
    {
        if ( !$cleanupSearch )
        {
            eZSearch::removeObject( $object );
        }
        eZSearch::addObject( $object );

        $script->iterate( $cli, true );
    }

    $limit['offset'] += $length;

} while ( count( $objects ) == $length );


print( $endl . "done" . $endl );

$script->shutdown();

?>
