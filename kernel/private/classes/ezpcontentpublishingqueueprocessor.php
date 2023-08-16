<?php
/**
 * File containing the ezpContentPublishingQueueProcessor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 * @subpackage content
 */

// required for proper signal handling
declare( ticks=1 );

/**
 * This class manages the publishing queue through ezpContentPublishingProcess persistent objects
 * @package kernel
 * @subpackage content
 */
class ezpContentPublishingQueueProcessor
{
    public $sleepInterval;
    public $signalQueue;
    public function __construct()
    {
        $this->contentINI = eZINI::instance( 'content.ini' );
        $this->allowedPublishingSlots = $this->contentINI->variable( 'PublishingSettings', 'PublishingProcessSlots' );
        $this->sleepInterval = $this->contentINI->variable( 'PublishingSettings', 'AsynchronousPollingInterval' );

        // output to log by default
        $this->setOutput( new ezpAsynchronousPublisherLogOutput );

        // initiates timer for the DB cleanup process
        $this->cleanupLastTime = time();
        $this->cleanupInterval = $this->contentINI->variable( 'PublishingSettings', 'AsynchronousCleanupInterval' );
        $this->cleanupAgeLimit = $this->contentINI->variable( 'PublishingSettings', 'AsynchronousCleanupAgeLimit' );

        // initiate current time for cache refresh
        $this->lastRefreshTimestamp = time();

        // Queue reader handler
        $this->queueReader = $this->contentINI->variable( 'PublishingSettings', 'AsynchronousPublishingQueueReader' );
        $reflection = new ReflectionClass( $this->queueReader );
        if ( !$reflection->implementsInterface( 'ezpContentPublishingQueueReaderInterface' ) )
            throw new Exception( "Configured asynchronous publishing queue reader doesn't implement ezpContentPublishingQueueReaderInterface", __CLASS__ );

        call_user_func( array( $this->queueReader, 'init' ) );
    }

    /**
     * Singleton class loader
     * @return ezpContentPublishingQueueProcessor
     */
    public static function instance()
    {
        if ( !self::$instance instanceof ezpContentPublishingQueueProcessor )
        {
            self::$instance = new ezpContentPublishingQueueProcessor();
            // signal handler for children termination signal
            self::$instance->registerSignalHandler();
        }

        return self::$instance;
    }

    /**
     * Checks if a publishing slot is available
     * @return ezpContentPublishingQueueProcessor
     */
    private function isSlotAvailable()
    {
        return ( ezpContentPublishingProcess::currentWorkingProcessCount() < $this->allowedPublishingSlots );
    }

    /**
     * Main method: infinite method that monitors queued objects, and starts
     * the publishinbg processes if allowed
     *
     * @return void
     */
    public function run()
    {
        // use DB exceptions so that errors can be fully handled
        eZDB::setErrorHandling( eZDB::ERROR_HANDLING_EXCEPTIONS );

        $this->cleanupDeadProcesses();

        while ( $this->canProcess )
        {
            try {
                $this->cleanupFinishedProcesses();
                /** @var ezpContentPublishingProcess $publishingItem */
                $publishingItem = $this->getNextItem();
                if ( $publishingItem === false )
                {
                    sleep( $this->sleepInterval );
                    continue;
                }

                if ( !$this->isSlotAvailable() )
                {
                    $this->out->write( "No slot is available", 'async.log' );
                    sleep( $this->sleepInterval );
                    continue;
                }

                $this->out->write(
                    sprintf(
                        "Processing item #%d (content %d.%d)",
                        $publishingItem->attribute( 'ezcontentobject_version_id' ),
                        $publishingItem->version()->attribute( 'contentobject_id' ),
                        $publishingItem->version()->attribute( 'version' )
                    ),
                    'async.log'
                );

                $this->refreshCacheStateInfo();

                $pid = $publishingItem->publish();
                pcntl_signal( SIGINT, $this->signalHandler );
                $this->currentJobs[$pid] = $publishingItem;

                // In the event that a signal for this pid was caught before we get here, it will be in our
                // signalQueue array
                // Process it now as if we'd just received the signal
                if ( isset( $this->signalQueue[$pid] ) )
                {
                    $this->out->write( "found $pid in the signal queue, processing it now" );
                    $this->childSignalHandler( SIGCHLD, $pid, $this->signalQueue[$pid] );
                    unset( $this->signalQueue[$pid] );
                }
            } catch ( eZDBException $e ) {
                $this->out->write( "Database error #" . $e->getCode() . ": " . $e->getMessage() );
                // force the DB connection closed so that it is recreated
                try {
                    $db = eZDB::instance();
                    $db->close();
                    $db = null;
                    eZDB::setInstance( null );
                } catch( eZDBException $e ) {
                    // Do nothing, this will be retried until the DB is back up
                }
                sleep( 1 );
            }
        }
    }

    /**
     * Refreshes/reloads cache and other state information periodically.
     */
    private function refreshCacheStateInfo()
    {
        if ( time() < ( $this->lastRefreshTimestamp + $this->sleepInterval ) )
        {
            return;
        }

        // Reload expiry timestamps
        eZExpiryHandler::instance()->restore();

        $this->lastRefreshTimestamp = time();
    }

    /**
     * Checks WORKING processes, and removes from the queue those who are dead
     *
     * @return void
     */
    private function cleanupDeadProcesses()
    {
        $processes = ezpContentPublishingProcess::fetchProcesses( ezpContentPublishingProcess::STATUS_WORKING );
        foreach( $processes as $process )
        {
            if ( !$process->isAlive() )
            {
                $process->reset();
            }
        }
    }

    /**
     * Removes FINISHED processes rows from the db (in one db call)
     * method self-manages the removal, based on the defined cleanupInterval and cleanupAgeLimit
     *
     * @return void
     */
    private function cleanupFinishedProcesses()
    {
        if ( time() < ( $this->cleanupLastTime + $this->cleanupInterval ) )
        {
            return;
        }

        $processes = count( ezpContentPublishingProcess::fetchProcesses( ezpContentPublishingProcess::STATUS_FINISHED ) );
        if ( $processes > 0 )
        {
            //Remove all objects at once
            // this is required as the MySQL connection might be closed anytime by a fork
            try
            {
                eZDebug::writeNotice( "ASYNC:: removing processes entries marked as STATUS_FINISHED in database.");
                $db = eZDB::instance();
                eZDB::setInstance( null );
                $deleteBefore = time() - $this->cleanupAgeLimit;
                $definition = ezpContentPublishingProcess::definition();
                $processTable = $definition['name'];
                $db->query( "DELETE from ". $processTable. " WHERE status =".  ezpContentPublishingProcess::STATUS_FINISHED. " AND finished < ". $deleteBefore );
                $this->cleanupLastTime = time();
            }
            catch( eZDBException $e ) 
            {
                // Do nothing, this will be retried until the DB is back up
            }
        }
    }

    /**
     * Child process signal handler
     */
    public function childSignalHandler( $signo, $pid = null, $status = null )
    {
        // If no pid is provided, that means we're getting the signal from the system.  Let's figure out
        // which child process ended
        if( $pid === null )
        {
            $pid = pcntl_waitpid( -1, $status, WNOHANG );
        }

        //Make sure we get all of the exited children
        while( $pid > 0 )
        {
            if( $pid && isset( $this->currentJobs[$pid] ) )
            {
                $exitCode = pcntl_wexitstatus( $status );
                if ( $exitCode != 0 )
                {
                    $this->out->write( "Process #{$pid} of object version #".$this->currentJobs[$pid]->attribute( 'ezcontentobject_version_id' ) . " exited with status {$exitCode}" );
                    // this is required as the MySQL connection might be closed anytime by a fork
                    // this method is asynchronous, and might be triggered by any signal
                    // the only way is to use a dedicated DB connection, and close it afterwards
                    eZDB::setInstance( eZDB::instance( false, false, true ) );

                    $this->currentJobs[$pid]->reset();

                    eZDB::instance()->close();
                    eZDB::setInstance( null );
                }
                unset( $this->currentJobs[$pid] );
            }
            // A job has finished before the parent process could even note that it had been launched
            // Let's make note of it and handle it when the parent process is ready for it
            // echo "..... Adding $pid to the signal queue ..... \n";
            elseif( $pid )
            {
                $this->signalQueue[$pid] = $status;
            }
            $pid = pcntl_waitpid( -1, $status, WNOHANG );
        }
        return true;
    }

    /**
     * Set the process signal handler
     */
    private function registerSignalHandler()
    {
        pcntl_signal( SIGCHLD, array( $this, 'childSignalHandler' ) );
    }

    /**
     * Stops processing the queue, and cleanup what's currently running
     */
    public static function terminate()
    {
        self::instance()->_terminate();
    }

    private function _terminate()
    {

        $this->canProcess = false;

        $this->out->write( "\n\n" );
        while( !empty( $this->currentJobs ) )
        {
            $this->out->write( count( $this->currentJobs ) . ' jobs remaining, waiting...' );
            sleep( 1 );
        }

        if ( empty( $this->currentJobs ) )
        {
            $this->out->write( 'No waiting children, exiting' );
        }
    }

    /**
     * Sets the used output class
     *
     * @param ezpAsynchronousPublisherOutput $output
     * @return void
     */
    public function setOutput( ezpAsynchronousPublisherOutput $output )
    {
        if ( !$output instanceof ezpAsynchronousPublisherOutput )
            throw new Exception( "Invalid output handler" );
        $this->out = $output;
    }

    /**
     * Sets the signal handler to $signalHandler
     * @param callback $signalHandler
     * @param \
     */
    public function setSignalHandler( $signalHandler)
    {
        if ( !is_callable( $signalHandler) )
        {
            throw new InvalidArgumentException( "\$signalHandler is not callable" );
        }
        $this->signalHandler = $signalHandler;
    }

    /**
     * @return mixed
     */
    private function getNextItem()
    {
        return call_user_func( array( $this->queueReader, 'next' ) );
    }

    /**
     * @var eZINI
     */
    private $contentINI;

    /**
     * Allowed slots
     * @var int
     */
    private $allowedPublishingSlots = null;

    /**
     * Singleton instance of ezpContentPublishingQueueProcessor
     * @var ezpContentPublishingQueueProcessor
     */
    private static $instance = null;

    private $canProcess = true;

    /**
     * Currently running jobs
     * @var array
     */
    private $currentJobs = array();

    /**
     * Output manager
     * @var ezpAsynchronousPublisherOutput
     */
    private $out;

    /**
     * Signal handler callback
     * @var callback
     */
    private $signalHandler;

    /**
     * @var ezpContentPublishingQueueReaderInterface
     */
    private $queueReader;

    /**
     * Time counter for cleanup of finished processes
     * @var int
     */
    private $cleanupLastTime;

     /**
     * Time counter for refresh of cache/state information
     * @var int
     */
    private $lastRefreshTimestamp;

    /**
     * Interval for cleanup of finished processes. Default value is 12 hours in seconds
     * @var int
     */
    private $cleanupInterval = 43200;

    /**
     * All finished processes after this age limit will be cleaned up.
     * Default value is a week (in seconds)
     * @var int
     */
    private $cleanupAgeLimit = 604800;
}
?>
