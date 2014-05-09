<?php
/**
 * File containing the ezpWebBasedKernelHandler interface.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

/**
 * Interface for web-based kernel handlers
 */
interface ezpWebBasedKernelHandler extends ezpKernelHandler
{
    /**
     * Allows user to avoid executing the pagelayout template when running the kernel
     *
     * @param bool $usePagelayout
     */
    public function setUsePagelayout( $usePagelayout );
}
