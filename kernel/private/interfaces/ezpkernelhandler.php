<?php
/**
 * File containing the ezpKernelHandler interface.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
     * @return ezpKernelResult
     */
    public function run();

    /**
     * Runs a callback function in the kernel environment.
     * This is useful to run eZ Publish 4.x code from a non-related context (like eZ Publish 5)
     *
     * @param \Closure $callback
     * @param bool $postReinitialize Default is true.
     *                               If set to false, the kernel environment will not be reinitialized.
     *                               This can be useful to optimize several calls to the kernel within the same context.
     * @return mixed The result of the callback
     */
    public function runCallback( \Closure $callback, $postReinitialize = true );

    /**
     * Sets whether to use exceptions inside the kernel.
     *
     * @param bool $useExceptions
     */
    public function setUseExceptions( $useExceptions );

    /**
     * Reinitializes the kernel environment.
     *
     * @abstract
     * @return void
     */
    public function reInitialize();

    /**
     * Checks whether the kernel handler has the Symfony service container
     * container or not.
     *
     * @return bool
     */
    public function hasServiceContainer();

    /**
     * Returns the Symfony service container if it has been injected,
     * otherwise returns null.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface|null
     */
    public function getServiceContainer();
}
