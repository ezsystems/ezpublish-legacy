<?php
/**
 * File containing the ezpContentPublishingQueueProcess class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 * @subpackage content
 */

/**
 * This class provides a PersistentObject interface to a publishing queue process.
 * @package kernel
 * @subpackage content
 */
class ezpContentPublishingProcess extends eZPersistentObject
{
    const STATUS_WORKING = 1;
    const STATUS_FINISHED = 2;
    const STATUS_PENDING = 3;

    /**
     * Set when an operation is deferred to crontab
     */
    const STATUS_DEFERRED = 4;

    /**
     * Joker status, used for non handling operation results
     */
    const STATUS_UNKNOWN = 5;

    /**
     * eZPersistentObject definition
     * @return array
     */
    public static function definition()
    {
        static $definition = array(
             'fields' => array( 'ezcontentobject_version_id' => array( 'name' => 'ContentObjectVersionID',
                                                                       'datatype' => 'integer',
                                                                       'default' => 0,
                                                                       'required' => true,
                                                                       'foreign_class' => 'eZContentObjectVersion',
                                                                       'foreign_attribute' => 'id',
                                                                       'multiplicity' => '1..*' ),
                                'pid' => array( 'name' => 'PID',
                                                'datatype' => 'integer',
                                                'default' => 0,
                                                'required' => false ),
                                'status' => array( 'name' => 'Status',
                                                   'datatype' => 'integer',
                                                   'default' => 0,
                                                   'required' => true ),
                                'started' => array( 'name' => 'Started',
                                                    'datatype' => 'integer',
                                                    'default' => 0,
                                                    'required' => true ),
                                'created' => array( 'name' => 'Created',
                                                    'datatype' => 'integer',
                                                    'default' => 0,
                                                    'required' => true ),
                                'finished' => array( 'name' => 'Finished',
                                                     'datatype' => 'integer',
                                                     'default' => 0,
                                                     'required' => true ) ),
                      'keys' => array( 'ezcontentobject_version_id' ),
                      'function_attributes' => array( 'version' => 'version' ),
                      'class_name' => 'ezpContentPublishingProcess',
                      'increment_key' => null,
                      'sort' => array( 'created' => 'asc' ),
                      'name' => 'ezpublishingqueueprocesses' );
        return $definition;
    }

    /**
     * Returns the version object the process is linked to
     * @return eZContentObjectVersion
     */
    public function version()
    {
        if ( $this->versionObject === null )
            $this->versionObject = eZContentObjectVersion::fetch( $this->attribute( 'ezcontentobject_version_id' ) );

        return $this->versionObject;
    }

    /**
     * Fetches a process by its content object version
     * @param int $contentObjectVersionId
     * @return ezpContentPublishingProcess
     */
    public static function fetchByContentVersionId( $contentObjectVersionId )
    {
        return parent::fetchObject(
            self::definition(),
            false,
            array( 'ezcontentobject_version_id' => $contentObjectVersionId )
        );
    }

    /**
     * Fetches a process by its content object ID + version
     * @param int $contentObjectId
     * @param int $version
     * @return ezpContentPublishingProcess
     */
    public static function fetchByContentObjectVersion( $contentObjectId, $version )
    {
        $contentObjectVersion = eZContentObjectVersion::fetchVersion( $version, $contentObjectId );
        if ( $contentObjectVersion instanceof eZContentObjectVersion )
        {
            $return = self::fetchByContentVersionId( $contentObjectVersion->attribute( 'id' ) );
            return $return;
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns the number of currently working publishing processes
     * @return int
     */
    public static function currentWorkingProcessCount()
    {
        return self::count( self::definition(), array( 'status' => self::STATUS_WORKING ) );
    }

    /**
     * Checks if an object is already being processed
     * @param eZContentObjectVersion $versionObject
     * @return bool
     */
    public static function isProcessing( eZContentObjectVersion $versionObject )
    {
        $count = parent::count(
            self::definition(),
            array(
                'ezcontentobject_version_id' => $versionObject->attribute( 'id' ),
                // 'status' => self::STATUS_WORKING // not used yet
            )
        );
        return ( $count != 0 );
    }

    /**
     * Starts the publishing process for the linked version. After publishing,
     * the child process is terminated.
     *
     * @return false|int false if the fork fails, the pid of the child process
     *                   after the fork
     */
    public function publish()
    {
        $contentObjectId = $this->version()->attribute( 'contentobject_id' );
        $contentObjectVersion = $this->version()->attribute( 'version' );

        // $processObject = ezpContentPublishingProcess::fetchByContentObjectVersion( $contentObjectId, $contentObjectVersion );
        $this->setStatus( self::STATUS_WORKING, true, "beginning publishing" );

        // prepare the cluster file handler for the fork
        eZClusterFileHandler::preFork();

        $pid = pcntl_fork();

        // force the DB connection closed
        $db = eZDB::instance();
        $db->close();
        $db = null;
        eZDB::setInstance( null );

        // Force the new stack DB connection closed as well
        try
        {
            $kernel = ezpKernel::instance();
            if ( $kernel->hasServiceContainer() )
            {
                $serviceContainer = $kernel->getServiceContainer();
                $dbHandler = $serviceContainer->get( 'ezpublish.connection' );
                $factory = $serviceContainer->get( 'ezpublish.api.storage_engine.legacy.dbhandler.factory' );
                $dbHandler->setDbHandler(
                    $factory->buildLegacyDbHandler()->getDbHandler()
                );
            }
        }
        catch ( LogicException $e )
        {
            // we just ignore this, since it means that we are running in a pure legacy context
        }

        // Force the cluster DB connection closed if the cluster handler is DB based
        $cluster = eZClusterFileHandler::instance();

        // error, cancel
        if ( $pid == -1 )
        {
            $this->setStatus( self::STATUS_PENDING, true, "pcntl_fork() failed" );
            return false;
        }
        else if ( $pid )
        {
            return $pid;
        }

        // child process
        try
        {
            $myPid = getmypid();
            pcntl_signal( SIGCHLD, SIG_IGN );

            $this->setAttribute( 'pid', $myPid );
            $this->setAttribute( 'started', time() );
            $this->store( array( 'pid', 'started' ) );

            // login the version's creator to make sure publishing happens as if ran synchronously
            $creatorId = $this->version()->attribute( 'creator_id' );
            $creator = eZUser::fetch( $creatorId );
            eZUser::setCurrentlyLoggedInUser( $creator, $creatorId );
            unset( $creator, $creatorId );

            $operationResult = eZOperationHandler::execute( 'content', 'publish',
                array( 'object_id' => $contentObjectId, 'version' => $contentObjectVersion  )
            );

            // Statuses other than CONTINUE require special handling
            if ( $operationResult['status'] != eZModuleOperationInfo::STATUS_CONTINUE )
            {
                if ( $operationResult['status'] == eZModuleOperationInfo::STATUS_HALTED )
                {
                    // deferred to crontab
                    if ( strpos( $operationResult['result']['content'], 'Deffered to cron' ) !== false )
                        $processStatus = self::STATUS_DEFERRED;
                    else
                        $processStatus = self::STATUS_UNKNOWN;
                }
                else
                {
                    $processStatus = self::STATUS_UNKNOWN;
                }
            }
            else
            {
                $processStatus = self::STATUS_FINISHED;
            }

            // mark the process as completed
            $this->setAttribute( 'pid', 0 );
            $this->setStatus( $processStatus, false, "publishing operation finished" );
            $this->setAttribute( 'finished', time() );
            $this->store( array( 'status', 'finished', 'pid' ) );

            // Call the postProcessing hook
            ezpContentPublishingQueue::signals()->emit( 'postHandling', $contentObjectId, $contentObjectVersion, $processStatus );
        }
        catch( eZDBException $e )
        {
            $this->reset( "database error: " . $e->getMessage() );
        }

        // generate static cache
        $ini = eZINI::instance();
        if ( $ini->variable( 'ContentSettings', 'StaticCache' ) == 'enabled' )
        {
            $staticCacheHandlerClassName = $ini->variable( 'ContentSettings', 'StaticCacheHandler' );
            $staticCacheHandlerClassName::executeActions();
        }

        eZScript::instance()->shutdown();
        exit;
    }

    /**
     * Adds a version to the publishing queue
     * @param eZContentObjectVersion $version
     * @return ezpContentPublishingProcess
     */
    public static function queue( eZContentObjectVersion $version )
    {
        $row = array(
            'ezcontentobject_version_id' => $version->attribute( 'id' ),
            'created' => time(),
            'status' => self::STATUS_PENDING,
         );
        $processObject = new self( $row );
        $processObject->store();

        return $processObject;
    }

    /**
     * Fetches processes, filtered by status
     * @param int $status One of ezpContentPublishingProcess::STATUS_*
     * @return array( ezpContentPublishingProcess )
     */
    public static function fetchProcesses( $status )
    {
        if ( !in_array( $status, array( self::STATUS_FINISHED, self::STATUS_PENDING, self::STATUS_WORKING ) ) )
            throw new ezcBaseValueException( '$status', $status, array( self::STATUS_FINISHED, self::STATUS_PENDING, self::STATUS_WORKING ), 'parameter' );

        return parent::fetchObjectList( self::definition(), false,
            array( 'status' => $status ),
            array( 'created' => 'asc' ) );
    }

    /**
     * Checks if the system process is running
     *
     * @return bool
     * @throws Exception if the process isn't in WORKING status
     */
    public function isAlive()
    {
        if ( $this->attribute( 'status' ) != self::STATUS_WORKING )
            throw new Exception( 'The process\'s status isn\'t \'working\'' );

        // sending 0 only checks if the process is alive (and if the current process is allowed to send signals to it)
        $return = ( posix_kill( $this->attribute( 'pid' ), 0 ) === true );
        return $return;
    }

    /**
     * Resets the current process to the PENDING state
     *
     * @todo Monitor the reset operation, using some kind of counter. The process must NOT get high priority, as it
     *       might block a slot if it fails constantly
     *       Maybe use a STATUS_RESET status, that gives lower priority to the item
     */
    public function reset( $message )
    {
        $this->setStatus( self::STATUS_PENDING, false, "::reset() with message '$message'" );
        $this->setAttribute( 'pid', 0 );
        $this->store( array( 'status', 'pid' ) );
    }

    /**
     * Sets the status to $status and stores (by default) the persistent object
     * @param int $status
     * @param bool $store
     */
    private function setStatus( $status, $store = true, $reason = null )
    {
        $this->logStatusChange( $status, $reason );
        $this->setAttribute( 'status', $status );
        if ( $store )
        {
            $this->store( array( 'status' ) );
        }
    }

    /**
     * Logs a debug message when the process' status is updated
     *
     * @param string $status New status
     * @param null $reason Optional reason
     */
    private function logStatusChange( $status, $reason = null )
    {
        $contentObjectId = $this->version()->attribute( 'contentobject_id' );
        $versionNumber = $this->version()->attribute( 'version' );
        eZDebugSetting::writeDebug(
            'kernel-content-publish',
            sprintf(
                "process #%d, content %d.%d, status changed to %s (reason: %s)",
                $this->attribute( 'ezcontentobject_version_id' ),
                $contentObjectId,
                $versionNumber,
                $this->getStatusString( $status ),
                $reason ?: "none given"
            ),
            'Asynchronous publishing process status changed'
        );
    }

    private function getStatusString( $status )
    {
        $statusMap = array(
            self::STATUS_PENDING => 'pending',
            self::STATUS_DEFERRED => 'deferred',
            self::STATUS_FINISHED => 'finished',
            self::STATUS_UNKNOWN => 'unknown',
            self::STATUS_WORKING => 'working'
        );

        return isset( $statusMap[$status] ) ? $statusMap[$status] : "<<invalid status>>";
    }

    private $versionObject = null;
}
