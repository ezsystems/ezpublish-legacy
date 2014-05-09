<?php
/**
 * File containing the ezpAsynchronousPublisherCliOutput class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Handles asynchronous publishing output to CLI
 * @package kernel
 */
class ezpAsynchronousPublisherCliOutput implements ezpAsynchronousPublisherOutput
{
    public function __construct()
    {
        $this->cli = eZCLI::instance();
    }

    public function write( $message )
    {
        $this->cli->output( $message );
    }

    private $cli;
}
?>
