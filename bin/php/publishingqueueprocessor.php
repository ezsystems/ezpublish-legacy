#!/usr/bin/env php
<?php
/**
* File containing the contentwaittimeout.php script
*
* @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
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


declare( ticks=1 );

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => "Processes the eZ Publish publishing queue",
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions(
    // options definition
    "[n|daemon][p|pid-file]",
    // arguments definition
    "",
    // options documentation
    array( 'daemon' => 'Run in the background',
           'pid-file' => 'PID file' ) );
$sys = eZSys::instance();

$script->initialize();

if ( $options['daemon'] )
{
    // Trap signals that we expect to recieve
    pcntl_signal( SIGCHLD, 'childHandler' );
    pcntl_signal( SIGUSR1, 'childHandler' );
    pcntl_signal( SIGALRM, 'childHandler' );

    $pid = pcntl_fork();
    if ( $pid < 0 )
    {
        error_log( "unable to fork daemon" );
        $script->shutdown( 1, "unable to fork daemon" );
    }
    // If we got a good PID, then we can wait until the daemon tells us to terminate
    if ( $pid > 0 )
    {
        // Wait for confirmation from the child via SIGTERM or SIGCHLD, or
        // for two seconds to elapse (SIGALRM).  pause() should not return. */
        sleep( 10 );

        $script->shutdown( 1, "Failed spawning the daemon process" );
    }

    // At this point we are executing as the child process
    $parentProcessID = posix_getppid();

    /* Cancel certain signals */
    pcntl_signal( SIGCHLD, SIG_DFL ); // A child process dies
    pcntl_signal( SIGTSTP, SIG_IGN ); // Various TTY signals
    pcntl_signal( SIGTTOU, SIG_IGN );
    pcntl_signal( SIGTTIN, SIG_IGN );
    pcntl_signal( SIGHUP,  SIG_IGN ); // Ignore hangup signal

    $sid = posix_setsid();
    if ( $sid < 0 )
    {
        error_log( "unable to create a new session" );
        $script->shutdown( 1, 'unable to create a new session' );
    }

    pcntl_signal( SIGTERM, 'daemonSignalHandler' );

    $fp = fopen( 'var/run/asynchronous-publishing.pid', 'w' );
    $pid = getmypid();
    $cli->output( "Publishing daemon started. Process ID: $pid" );
    fputs( $fp, $pid );
    fclose( $fp );

    // stop output completely
    fclose( STDIN );
    fclose( STDOUT );
    fclose( STDERR );

    // terminate the parent
    posix_kill( $parentProcessID, SIGUSR1 );
}
else
{
    $cli->output( "Running in interactive mode. Hit ctrl-c to interrupt." );
}

// actual daemon code
$processor = ezpContentPublishingQueueProcessor::instance();
$processor->run();


eZScript::instance()->shutdown( 0 );

/**
* Signal handler
* @param int $signo Signal number
*/
function childHandler( $signo )
{
    switch( $signo )
    {
        case SIGALRM: eZScript::instance()->shutdown( 1 ); break;
        case SIGUSR1: eZScript::instance()->shutdown( 0 ); break;
        case SIGCHLD: eZScript::instance()->shutdown( 1 ); break;
    }
}

/**
 * Signal handler for the daemon process
 * @param int $signo Signal number
 */
function daemonSignalHandler( $signo )
{
    switch( $signo )
    {
        case SIGTERM:
            ezpContentPublishingQueueProcessor::terminate();
            @unlink( 'var/run/asynchronous-publishing.pid' );
            eZScript::instance()->shutdown( 0 );
            break;
    }
}
?>