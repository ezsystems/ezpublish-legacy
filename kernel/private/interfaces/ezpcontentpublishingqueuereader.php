<?php
/**
 * File containing the ezpContentPublishingQueueReaderInterface interface
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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

    /**
     * Initialization method. Called once before the reader class is used.
     */
    public static function init();
}
?>
