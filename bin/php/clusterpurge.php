<?php
/**
 * Cluster files purge script
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish cluster files purge\n" .
                                                        "Physically purges files\n" .
                                                        "\n" .
                                                        "./bin/php/clusterpurge.php --scopes=scope1,scope2" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[dry-run][iteration-sleep:][iteration-limit:][memory-monitoring][scopes:][expiry:]",
"",
array( 'dry-run' => 'Test mode, output the list of affected files without removing them',
       'iteration-sleep' => 'Amount of seconds to sleep between each iteration when performing a purge operation, can be a float. Default is one second.',
       'iteration-limit' => 'Amount of items to remove in each iteration when performing a purge operation. Default is 100.',
       'memory-monitoring' => 'If set, memory usage will be logged in var/log/clusterpurge.log.',
       'scopes' => 'Comma separated list of file types to purge. Possible values are: classattridentifiers, classidentifiers, content, expirycache, statelimitations, template-block, user-info-cache, viewcache, wildcard-cache-index, image, binaryfile',
       'expiry' => 'Number of days since the file was expired. Only files older than this will be purged. Default is 30, minimum is 1.' ) );
$sys = eZSys::instance();

$script->initialize();

if ( !eZScriptClusterPurge::isRequired() )
{
    $cli->error( "Your current cluster handler does not require files purge" );
    $script->shutdown( 1 );
}

$purgeHandler = new eZScriptClusterPurge();
if ( $options['dry-run'] )
{
    $purgeHandler->optDryRun = true;
}

if ( $options['iteration-sleep'] )
{
    $purgeHandler->optIterationSleep = (int)( $options['iteration-sleep'] * 1000000 );
}

if ( $options['iteration-limit'] )
{
    $purgeHandler->optIterationLimit = (int)$options['iteration-limit'];
}

if ( $options['memory-monitoring'] )
{
    $purgeHandler->optMemoryMonitoring = true;
}

if ( $options['scopes'] )
{
    $purgeHandler->optScopes = explode( ',', $options['scopes'] );
}

if ( $options['expiry'] )
{
    $purgeHandler->optExpiry = (int)$options['expiry'] * 86400; // 60*60*24
}

$purgeHandler->run();

$script->shutdown();

?>
