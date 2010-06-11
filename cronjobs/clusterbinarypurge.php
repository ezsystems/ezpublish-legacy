<?php
/**
 * Cluster binary files purge cronjob
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */

if ( !eZScriptClusterPurge::isRequired() )
{
    $cli->error( "Your current cluster handler does not require binary purge" );
    $script->shutdown( 1 );
}

$purgeHandler = new eZScriptClusterPurge();
$purgeHandler->run();
?>
