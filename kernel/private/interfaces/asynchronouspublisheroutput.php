<?php
/**
 * File containing the ezpAsynchronousPublisherOutput interface
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package
 */

/**
 * This interface is used as the basis for the ezpasynchronouspublisher.php daemon
 * @package
 */
interface ezpAsynchronousPublisherOutput
{
    /**
     * Write a message to the output
     * @param string $message
     */
    public function write( $message );
}
?>
