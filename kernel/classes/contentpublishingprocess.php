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
                                                  'required' => true ) ),
                      'keys' => array( 'ezcontentobject_version_id' ),
                      'function_attributes' => array(),
                      'class_name' => "ezpContentPublishingProcess",
                      "increment_key" => null,
                      'sort' => array( 'started' => 'asc' ),
                      'name' => 'ezpublishingqueueprocesses' );
        return $definition;
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
     * Starts the publishing process for a content object version
     * @param eZContentObjectVersion $version
     * @return bool
     */
    public static function publish( eZContentObjectVersion $version )
    {
        $contentObjectId = $version->attribute( 'contentobject_id' );
        $contentObjectVersion = $version->attribute( 'version' );

        // launch the process
        // @todo Will only work on linux, but it'll do for now
        // $phpExec = $_SERVER['_'];
        $op = null;

        $pid = pcntl_fork();
        $db = eZDB::instance();
        $db->IsConnected = false;
        $db = null;
        eZDB::setInstance( $db );
        eZDB::setInstance( eZDB::instance( false, false, true ) );
        if ( $pid == -1 )
        {
            return false;
        }
        else if ( $pid )
        {
            // set the version as pending so that no other publishing process catches it
            $version->setAttribute( 'status', eZContentObjectVersion::STATUS_PENDING );
            $version->store();

            // create the process object
            $processObjectRow = array(
                'ezcontentobject_version_id' => $version->attribute( 'id' ),
                'pid' => $pid,
                'status' => self::STATUS_WORKING,
                'started' => time(),
            );
            $processObject = new self( $processObjectRow );
            $processObject->store();

            return $pid;
        }
        else
        {
            // child process: spawn
            $myPid = getmypid();

            exec( "/usr/bin/php ./bin/php/publish_content.php $contentObjectId $contentObjectVersion", $op );

            // mark the process as completed
            $processObject = self::fetchByContentVersionId( $version->attribute( 'id' ) );
            $processObject->setAttribute( 'status', self::STATUS_FINISHED );
            $processObject->store();

            eZScript::instance()->shutdown();
            exit;
        }

        return true;
    }
}
?>