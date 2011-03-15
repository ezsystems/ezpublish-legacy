<?php
/**
 * File containing the ezpContentPublishingQueueReaderInterface interface
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * This interface describes an asynchronous publishing queue reader
 * Queue readers are meant to provide the asynchronous publishing system with the next item to be published
 * @since 4.5
 * @package kernel
 */
    interface ezpContentPublishingQueueReaderInterface
{
    /**
     * Searches for and returns the next queue item to be processed
     *
     * @return ezpContentPublishingProcess|false the next process, or false if nothing is to be processed
     */
    public static function next();
}
?>