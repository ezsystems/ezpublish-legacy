#!/usr/bin/env php
<?php
/**
 * File containing the updatesearchindex.php script.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

set_time_limit( 0 );

require_once 'autoload.php';

$cli = eZCLI::instance();

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
    if ( in_array( $siteAccess, eZINI::instance()->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' ) ) )
    {
        $cli->output( "Using siteaccess $siteAccess for update search index" );
    }
    else
    {
        $cli->notice( "Siteaccess $siteAccess does not exist, using default siteaccess" );
    }
}

$cli->output( "Starting object re-indexing" );

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

$searchEngine = eZSearch::getEngine();

if ( !$searchEngine instanceof ezpSearchEngine )
{
    $cli->error( "The configured search engine does not implement the ezpSearchEngine interface or can't be found." );
    $script->shutdown( 1 );
}


if ( $cleanupSearch )
{
    $cli->output( "{eZSearchEngine: Cleaning up search data", false );
    $searchEngine->cleanup();
    $cli->output( "}" );
}

$def = eZContentObject::definition();
$conds = array(
    'status' => eZContentObject::STATUS_PUBLISHED
);

$count = eZPersistentObject::count( $def, $conds, 'id' );

$cli->output( "Number of objects to index: $count");

$length = 50;
$limit = array( 'offset' => 0 , 'length' => $length );

$script->resetIteration( $count );

$needRemoveWithUpdate = $searchEngine->needRemoveWithUpdate();

do
{
    // clear in-memory object cache
    eZContentObject::clearCache();

    $objects = eZPersistentObject::fetchObjectList( $def, null, $conds, null, $limit );

    foreach ( $objects as $object )
    {
        if ( $needRemoveWithUpdate || !$cleanupSearch )
        {
            $searchEngine->removeObjectById( $object->attribute( "id" ), false );
        }
        if ( !$searchEngine->addObject( $object, false ) )
        {
            $cli->warning( "\tFailed indexing object ID #" . $object->attribute( "id" ) . "." );
        }

        $script->iterate( $cli, true );
    }

    $limit['offset'] += $length;

} while ( count( $objects ) == $length );

$searchEngine->commit();

$cli->output();
$cli->output( "done" );

$script->shutdown();

?>
