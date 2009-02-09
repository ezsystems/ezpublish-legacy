<?php
/**
 * File containing the eZDBNoConnectionException class.
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package kernel
 */

/**
 * This exceptions is used when a database connection is not possible
 * or failed
 *
 * @package kernel
 */
class eZDBNoConnectionException extends ezcBaseException
{
    /**
     * Constructs a new exception.
     */
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}
?>
