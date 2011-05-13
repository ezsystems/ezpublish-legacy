<?php
/**
 * File containing the ezpAsynchronousPublisherOutput interface
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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