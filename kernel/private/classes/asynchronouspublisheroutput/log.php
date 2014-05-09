<?php
/**
 * File containing the ezpAsynchronousPublisherLogOutput class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Handles asynchronous publishing output to var/log/async.log
 * @package kernel
 */
class ezpAsynchronousPublisherLogOutput implements ezpAsynchronousPublisherOutput
{
    private $logFile = 'async.log';
    private $logDir = 'var/log';

    public function write( $message )
    {
        eZLog::write( $message, $this->logFile, $this->logDir );
    }
}
?>
