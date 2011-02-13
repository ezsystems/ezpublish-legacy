<?php
/**
 * Cluster files purge cronjob
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */

if ( !eZScriptClusterPurge::isRequired() )
{
    $cli->error( "Your current cluster handler does not require file purge" );
    $script->shutdown( 1 );
}

$purgeHandler = new eZScriptClusterPurge();
$purgeHandler->optScopes = array( 'classattridentifiers',
                                  'classidentifiers',
                                  'content',
                                  'expirycache',
                                  'statelimitations',
                                  'template-block',
                                  'user-info-cache',
                                  'viewcache',
                                  'wildcard-cache-index',
                                  'image',
                                  'binaryfile' );
$purgeHandler->optExpiry = 30;
$purgeHandler->run();

?>
