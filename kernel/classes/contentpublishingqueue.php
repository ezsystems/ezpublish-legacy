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
 * This class manages the content publishing queue. It accepts new items for input, and decides what item should be
 * processed next
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
}
?>