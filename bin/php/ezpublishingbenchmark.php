#!/usr/bin/env php
<?php
/**
 * File containing the contentwaittimeout.php script
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package
 */

/**
 * This script starts parallel publishing processes in order to trigger lock wait timeouts
 * Launch it using $./bin/php/ezexec.phhp contentwaittimeout.php
 *
 * To customize the class, parent node or concurrency level, modify the 3 variables below.
 * @package tests
 */


require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => "eZ Publish Parallel publishing benchmark",
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[b:|batches-count:][c:|content-class:][l:|concurrency-level:][p:|parent-node:][g|generate-content]",
"",
array( 'content-class'     => "Identifier of the content class used for testing [default: article]",
       'concurrency-level' => "Parallel processes to use [default: 20]",
       'generate-content'  => "Wether content should  be generated or not (not fully supported yet) [default: off]",
       'parent-node'       => "Container content should be created in [default: 2]",
       'batches-count'     => "How many times a concurrent batch should be started [default: 1]" ) );
$sys = eZSys::instance();

$script->initialize();

$optParentNode = 2;
$optContentClass = 'article';
$optConcurrencyLevel = 20;
$optGenerateContent = false;
$optQuiet = false;
$optBatchesCount = 1;

if ( $options['content-class'] )
    $optContentClass = $options['content-class'];
if ( $options['batches-count'] )
    $optBatchesCount = $options['batches-count'];
if ( $options['concurrency-level'] )
    $optConcurrencyLevel = (int)$options['concurrency-level'];
if ( $options['parent-node'] )
    $optParentNode = $options['parent-node'];
if ( $options['generate-content'] )
    $optGenerateContent = true;
$originalParentNodeId = $optParentNode;

$db = eZDB::instance();
$mysqlOptions = $db->arrayQuery( "SHOW VARIABLES LIKE 'innodb_lock_wait_timeout'" );
$mysqlInnoDBLockWaitTimeout = $mysqlOptions[0]['Value'];
$mysqlOptions = $db->arrayQuery( "SHOW VARIABLES LIKE 'max_connections'" );
$mysqlMaxConnections = $mysqlOptions[0]['Value'];

$cli->output( "Options:" );
$cli->output( " * Batches count: $optBatchesCount" );
$cli->output( " * Concurrency level: $optConcurrencyLevel" );
$cli->output( " * Content class: $optContentClass" );
$cli->output( " * Generate content: " . ( $optGenerateContent ? 'yes' : 'no' ) );
$cli->output();
$cli->output( "Settings:" );
$cli->output( " * mysql.innodb_lock_wait_timeout: $mysqlInnoDBLockWaitTimeout seconds" );
$cli->output( " * mysql.max_connections: $mysqlMaxConnections" );
$cli->output();

$currentJobs = array();
$signalQueue = array();

for( $iteration = 0; $iteration < $optBatchesCount; $iteration++ )
{
    $db = eZDB::instance( false, false, true );
    eZDB::setInstance( $db );

    if ( $script->verboseOutputLevel() > 0 )
        $cli->output("Iteration {$iteration}/{$optBatchesCount}" );

    // Create the containing folder...
    // if mt_rand is initialized (it is in eZContentObject::create), and the process is forked, each fork will get the SAME
    // "random" value when calling mt_rand again. This will cause duplicate key errors on ezcontentobject.remote_id
    // this patch IS required
    /*$container = new ezpObject( 'folder', $originalParentNodeId );
    $container->name = "Bench on {$optContentClass}, iteration ". ( $iteration + 1 ) ."/{$optBatchesCount} [concurrency: {$optConcurrencyLevel}]";
    $contentObjectID = $container->publish();
    eZContentObject::clearCache();
    $object = eZContentObject::fetch( $contentObjectID );
    $node = $object->attribute( 'main_node_id' );
    print_r( $node );*/
    // $parentNode = eZContentObjectTreeNode::fetchByContentObjectID( $contentObjectID );
    // print_r( $parentNode );
     // eZExecution::cleanExit();

    eZDB::instance()->close();
    unset( $GLOBALS['eZDBGlobalInstance'] );

    // $optParentNode = $container->attribute( 'main_node_id' );
    if ( $script->verboseOutputLevel() > 0 )
        $cli->output( "Container ID: {$optParentNode}" );

    for( $i = 0; $i < $optConcurrencyLevel; $i++ )
    {
        $pid = pcntl_fork();
        // Problem launching the job
        if ( $pid == - 1 )
        {
            error_log( 'Could not launch new job, exiting' );
        }
        // parent process
        else if ( $pid > 1 )
        {
            $currentJobs[] = $pid;
        }
        // Forked child
        else
        {
            $exitStatus = 0; //Error code if you need to or whatever
            $myPid = getmypid();

            // No need if the DB ain't initialized before forking
            $db = eZDB::instance( false, false, true );
            eZDB::setInstance( $db );

            // suppress error output due to fatal DB errors
            // exceptions from the DB layer would allow for better information output
            fclose( STDERR );

            $object = new ezpObject( $optContentClass, $optParentNode );
            $object->title = "Wait Timeout Test, pid {$myPid}\n";
            if ( $optGenerateContent === true )
                $object->body = file_get_contents( 'xmltextsource.txt' );
            $object->publish();

            eZExecution::cleanExit();
        }
    }

    if ( $script->verboseOutputLevel() > 0 )
        $cli->output( "Main process: waiting for children..." );
        $errors = 0;
    while ( !empty( $currentJobs ) )
    {
        foreach( $currentJobs as $index => $pid )
        {
            if( pcntl_waitpid( $pid, $exitStatus, WNOHANG ) > 0 )
            {
                $exitCode = pcntl_wexitstatus( $exitStatus );
                if ( $exitCode != 0 )
                {
                    $errors++;
                    if ( !$optQuiet )
                        if ( $script->verboseOutputLevel() > 0 )
                            $cli->output( "process #$pid exited with code $exitCode" );
                }
                else
                {
                    if ( !$optQuiet )
                        if ( $script->verboseOutputLevel() > 0 )
                            $cli->output( "process #$pid exited successfully" );
                }
                unset( $currentJobs[$index] );
            }
        }
    }
    if ( $script->verboseOutputLevel() > 0 )
        $cli->output( "Done waiting\n" );
    $failurePercentage = round( $errors / $optConcurrencyLevel * 100, 0 );
    $cli->output( "Result: {$errors} errors out of {$optConcurrencyLevel} publishing operations ({$failurePercentage}%)" );
}
eZExecution::cleanExit();
?>