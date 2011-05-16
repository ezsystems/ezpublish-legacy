<?php
/**
 * File containing the ezpAsynchronousPublishingFilterInterface interface.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 * @since 4.5
 */

/**
 * Base interface for eZ Asynchronous Publishing filter classes
 * @package kernel
 */
interface ezpAsynchronousPublishingFilterInterface
{
    /**
     * Hook called by the kernel to check for async acceptance of an object
     *
     * @return bool true if the object can be published asynchronously, false if it shouldn't
     */
    public function accept();
}
?>
