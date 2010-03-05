<?php
/**
 * Cluster binary files purge cronjob
 *
 * @since 4.3.0
 */

if ( !eZScriptClusterPurge::isRequired() )
{
    $cli->error( "Your current cluster handler does not require binary purge" );
    $script->shutdown( 1 );
}

$purgeHandler = new eZScriptClusterPurge();
$purgeHandler->run();
?>