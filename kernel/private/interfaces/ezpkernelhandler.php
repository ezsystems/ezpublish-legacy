<?php
/**
 * File containing the ezpKernelHandler interface.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

/**
 * Interface for kernel handlers
 */
interface ezpKernelHandler
{
    /**
     * Execution point for controller actions.
     * Returns false if not supported
     *
     * @return ezpKernelResult|false
     */
    public function run();

    /**
     * Runs a callback function in the kernel environment.
     * This is useful to run eZ Publish 4.x code from a non-related context (like eZ Publish 5)
     *
     * @param \Closure $callback
     * @return mixed The result of the callback
     */
    public function runCallback( \Closure $callback );

    /**
     * Sets whether to use exceptions inside the kernel.
     *
     * @param bool $useExceptions
     */
    public function setUseExceptions( $useExceptions );
}
