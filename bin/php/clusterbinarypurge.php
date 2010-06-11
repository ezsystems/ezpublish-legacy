<?php
/**
 * Cluster binary files purge script
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish binary files purge\n" .
                                                        "Physically purges expired binary files\n" .
                                                        "\n" .
                                                        "./bin/php/clusterbinarypurge.php" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[dry-run][iteration-sleep:][iteration-limit:][memory-monitoring]",
"",
array( 'dry-run' => 'Test mode, output the list of affected files without removing them',
       'iteration-sleep' => 'Amount of seconds to sleep between each iteration when performing a purge operation, can be a float. Default is one second.',
       'iteration-limit' => 'Amount of items to remove in each iteration when performing a purge operation. Default is 100.',
       'memory-monitoring' => 'If set, memory usage will be logged in var/log/clusterbinarypurge.log.' ) );
$sys = eZSys::instance();

$script->initialize();

if ( !eZScriptClusterPurge::isRequired() )
{
    $cli->error( "Your current cluster handler does not require binary purge" );
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

$purgeHandler->run();

$script->shutdown();
?>
