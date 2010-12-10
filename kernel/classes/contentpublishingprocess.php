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
                                'start' => array( 'name' => 'Started',
                                                  'datatype' => 'integer',
                                                  'default' => 0,
                                                  'required' => true ) ),
                      'keys' => array( 'ezcontentobject_version_id' ),
                      'function_attributes' => array(),
                      'class_name' => "ezpPublishingQueueProcessor",
                      "increment_key" => null,
                      'sort' => array( 'start' => 'asc' ),
                      'name' => 'ezpublishingqueueprocesses' );
        return $definition;
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
     * Starts the publishing process for a content object version
     * @param eZContentObjectVersion $version
     * @return bool
     */
    public static function publish( eZContentObjectVersion $version )
    {
        $row = array( 'ezcontentobject_version_id' => $version->attribute( 'id' ),
                      'status' => self::STATUS_WORKING,
                      'start'  => time() );
        $process = new self( $row );

        $contentObjectId = $version->attribute( 'contentobject_id' );
        $contentObjectVersion = $version->attribute( 'version' );

        // set the version as pending so that no other publishing process catches it
        $version->setAttribute( 'status', eZContentObjectVersion::STATUS_PENDING );
        $version->store();

        // launch the process
        // @todo Will only work on linux, but it'll do for now
        $phpExec = $_SERVER['_'];
        $op = null;
        echo "Publishing {$contentObjectId}/{$contentObjectVersion}\n";
        exec( "$phpExec bin/php/publish_content.php $contentObjectId $contentObjectVersion > /dev/null 2>&1 & echo $!", $op );
        $pid = $op[0];

        return true;
    }
}
?>