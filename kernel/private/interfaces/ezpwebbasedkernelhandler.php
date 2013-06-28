<?php
/**
 * File containing the ezpWebBasedKernelHandler interface.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
