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
        eZContentOperationCollection::setVersionStatus( $objectId, $version, eZContentObjectVersion::STATUS_QUEUED );
    }

    /**
     * Returns the next processable item
     * @return eZContentObjectVersion The next object to process, or false if none is available
     */
    public static function next()
    {
        $objectVersionArray = eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
            null,
            array( 'status' => eZContentObjectVersion::STATUS_QUEUED ),
            array( 'modified' => 'desc' )
        );

        if ( $objectVersionArray === null || count( $objectVersionArray ) == 0 )
            return false;

        foreach( $objectVersionArray as $objectVersion )
        {
            if ( !ezpContentPublishingProcess::isProcessing( $objectVersion ) )
                return $objectVersion;
        }
        return false;
    }
}
?>