<?php
/**
 * File containing the eZDBException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Class representing a the top class for any
 * database related exception
 *
 * @version //autogentag//
 * @package kernel
 */
class eZDBException extends ezcBaseException
{
    /**
     * Original message, before escaping
     */
    public $originalMessage;

    /**
     * Constructs a new eZDBException with $message and $code
     *
     * @param string $message
     * @param int $code
     */
    public function __construct( $message, $code = 0 )
    {
        $this->originalMessage = $message;
        $this->code = $code;

        if ( php_sapi_name() == 'cli' )
        {
            $this->message = $message;
        }
        else
        {
            $this->message = htmlspecialchars( $message, ENT_QUOTES, 'UTF-8' );
        }
    }
}
?>
