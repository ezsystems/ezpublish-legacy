<?php
/**
 * File containing the ezpContentPublishingQueue class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package
 */

/**
 * This class manages the content publishing queue. It accepts new items for input, and provides information about the
 * current queue
 *
 * @package kernel
 * @since 4.5
 */
class ezpContentPublishingQueue
{
    /**
     * Adds a draft to the publishing queue
     *
     * @param int $objectId
     * @param int $version
     */
    public static function add( $objectId, $version )
    {
        return ezpContentPublishingProcess::queue( eZContentObjectVersion::fetchVersion( $version, $objectId ) );
    }

    /**
     * Checks if an object exists in the queue, whatever the status is
     * @param int $objectId
     * @param int $version
     * @return bool
     */
    public static function isQueued( $objectId, $version )
    {
        $process = ezpContentPublishingProcess::fetchByContentObjectVersion( $objectId, $version );
        return ( $process instanceof ezpContentPublishingProcess );
    }

    /**
     * Returns the next processable item
     * @return eZContentObjectVersion The next object to process, or false if none is available
     */
    public static function next()
    {
        $queuedProcess = eZPersistentObject::fetchObjectList( ezpContentPublishingProcess::definition(),
            null,
            array( 'status' => ezpContentPublishingProcess::STATUS_PENDING ),
            array( 'created' => 'desc' ),
            array( 'offset' => 0, 'length' => 1 )
        );

        if ( count( $queuedProcess ) == 0 )
            return false;
        else
            return $queuedProcess[0];
    }
}
?>