<?php
/**
 * File containing the ezpAsynchronousPublishingFilterInterface interface.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
