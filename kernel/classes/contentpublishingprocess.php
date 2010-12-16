<?php
/**
 * File containing the ezpContentPublishingQueueProcess class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
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
     * Starts the publishing process for the linked version
     * @return bool
     */
    public function publish()
    {
        $contentObjectId = $this->version()->attribute( 'contentobject_id' );
        $contentObjectVersion = $this->version()->attribute( 'version' );

        $processObject = ezpContentPublishingProcess::fetchByContentObjectVersion( $contentObjectId, $contentObjectVersion );
        $processObject->setAttribute( 'status', self::STATUS_WORKING );
        $processObject->store();

        $pid = pcntl_fork();

        $db = eZDB::instance();
        $db->IsConnected = false;
        $db = null;
        eZDB::setInstance( $db );

        // error, cancel
        if ( $pid == -1 )
        {
            $processObject->setAttribute( 'status', self::STATUS_PENDING );
            $processObject->store();
            return false;
        }
        else if ( $pid )
        {
            return $pid;
        }
        // child process
        else
        {
            // child process: spawn
            $myPid = getmypid();

            // @todo Make the executable configurable
            exec( "/usr/bin/php ./bin/php/publish_content.php $contentObjectId $contentObjectVersion", $op );

            // mark the process as completed
            $processObject = self::fetchByContentVersionId( $this->version()->attribute( 'id' ) );
            $processObject->setAttribute( 'status', self::STATUS_FINISHED );
            $processObject->store();

            // Make sure this is correct
            eZScript::instance()->shutdown();
            exit;
        }

        return true;
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

    private $versionObject = null;
}
?>