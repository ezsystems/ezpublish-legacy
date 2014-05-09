<?php
/**
 * File containing the cleanupdfscache.php script
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

require_once 'autoload.php';

$cli = eZCLI::instance();

$script = eZScript::instance(
    array(
        'description' => "Deletes cache records from the DFS storage table.\n" .
            "Can be used on a live site to cleanup cache leftovers in ezdfsfile.",
        'use-session' => true,
        'use-modules' => false,
        'use-extensions' => true
    )
);
$script->startup();

$options = $script->getOptions(
    "[iteration-sleep:][iteration-limit:]",
    "",
    array(
        'iteration-sleep' => 'Sleep duration between batches, in milliseconds (default: 100)',
        'iteration-limit' => 'Batch size (default: 1000)'
    )
);
$optIterationSleep = (int)$options['iteration-sleep'] ?: 100;
$optIterationLimit = (int)$options['iteration-limit'] ?: 1000;

$script->initialize();

$mysqliDFSBackend = new eZDFSFileHandlerMySQLiBackend();
try
{
    $mysqliDFSBackend->_connect();
}
catch ( eZClusterHandlerDBNoConnectionException $e )
{
    $script->shutdown( 2, "Unable to connect to the cluster database. Is this instance configured to use the feature ?" );
}


$clusterHandler = eZClusterFileHandler::instance();

try
{
    while ( $deletedCount = $mysqliDFSBackend->deleteCacheFiles( $optIterationLimit ) )
    {
        $cli->output( "Deleted $deletedCount cache record(s)" );
        usleep( $optIterationSleep * 1000 );
    }
}
catch ( InvalidArgumentException $e )
{
    $script->shutdown( 0, "The cache and storage tables are identical, nothing to do" );
}
catch ( RuntimeException $e )
{
    $script->shutdown( 1, "A database error occured:\n" . $e->getMessage() );
}
$cli->output( "Done" );

$script->shutdown();
