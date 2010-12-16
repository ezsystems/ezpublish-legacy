<?php
/**
 * File containing the ezpContentPublishingQueueProcessor class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 * @subpackage content
 */

/**
 * This class manages the publishing queue through ezpContentPublishingProcess persistent objects
 * @package kernel
 * @subpackage content
 */
declare( ticks=1 );
class ezpContentPublishingQueueProcessor
{
    public function __construct()
    {
        $this->contentINI = eZINI::instance( 'content.ini' );
        $this->allowedPublishingSlots = $this->contentINI->variable( 'PublishingSettings', 'PublishingProcessSlots' );
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
            self::$instance->setSignalHandler();
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
        $launched = 0;
        $this->cleanupDeadProcesses();

        while ( 1 )
        {
            $publishingItem = ezpContentPublishingQueue::next();
            if ( $publishingItem !== false )
            {
                if ( !$this->isSlotAvailable() )
                {
                    // cleanup dead processes
                    // $this->cleanupDeadProcesses();

                    echo "No slot is available\n";
                    sleep ( 1 );
                    continue;
                }
                else
                {
                    echo "Processing item #" . $publishingItem->attribute( 'ezcontentobject_version_id' ) . "\n";
                    $pid = $publishingItem->publish();
                    $this->currentJobs[$pid] = $publishingItem;

                    // In the event that a signal for this pid was caught before we get here, it will be in our
                    // signalQueue array
                    // Process it now as if we'd just received the signal
                    if( isset( $this->signalQueue[$pid] ) )
                    {
                        echo "found $pid in the signal queue, processing it now \n";
                        $this->childSignalHandler( SIGCHLD, $pid, $this->signalQueue[$pid] );
                        unset( $this->signalQueue[$pid] );
                    }
                }
            }
            else
            {
                echo "Nothing to do, sleeping\n";
                sleep( 1 );
            }
        }
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
                    // echo "$pid (".$this->currentJobs[$pid]->attribute('ezcontentobject_version_id').") exited with status {$exitCode}\n";

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
            elseif( $pid )
            {
                // A job has finished before the parent process could even note that it had been launched
                // Let's make note of it and handle it when the parent process is ready for it
                // echo "..... Adding $pid to the signal queue ..... \n";
                $this->signalQueue[$pid] = $status;
            }
            $pid = pcntl_waitpid( -1, $status, WNOHANG );
        }
        return true;
    }

    /**
     * Set the process signal handler
     */
    private function setSignalHandler()
    {
        pcntl_signal( SIGCHLD, array( $this, 'childSignalHandler' ) );
    }

    /**
     * @var eZDBInterface
     */
    // private $dbInstance;


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
}
?>