<?php
/**
 * File containing the ezpKernel class.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

/**
 * Base eZ Publish kernel class.
 * Wraps a "kernel handler" and forwards calls to it.
 * This allows to have different kernel handlers depending on the context (i.e. "web" or "cli")
 */
class ezpKernel implements ezpKernelHandler
{
    /**
     * @var ezpKernelHandler
     */
    private $kernelHandler;

    public function __construct( ezpKernelHandler $kernelHandler )
    {
        /**
         * PHP 5.3.3 is our hard requirement
         */
        if ( version_compare( PHP_VERSION, '5.3.3' ) < 0 )
        {
            echo "<h1>Unsupported PHP version " . PHP_VERSION . "</h1>",
            "<p>eZ Publish 5.x does not run with PHP version lower than 5.3.</p>",
            "<p>For more information about supported software please visit ",
            "<a href=\"http://ez.no/download/ez_publish\" >eZ Publish download page</a></p>";
            exit;
        }

        $this->kernelHandler = $kernelHandler;
    }

    /**
     * Execution point for controller actions.
     * Returns false if not supported
     *
     * @return ezpKernelResult|false
     */
    public function run()
    {
        return $this->kernelHandler->run();
    }

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
    public function runCallback( \Closure $callback, $postReinitialize = true )
    {
        return $this->kernelHandler->runCallback( $callback, $postReinitialize );
    }

    /**
     * Sets whether to use exceptions inside the kernel.
     *
     * @param bool $useExceptions
     */
    public function setUseExceptions( $useExceptions )
    {
        $this->kernelHandler->setUseExceptions( $useExceptions );
    }
}
