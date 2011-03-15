<?php
/**
 * File containing the ezpRestException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * This is the base exception for the eZ Publish REST layer
 *
 * @package rest
 */
abstract class ezpRestException extends ezcBaseException
{
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}
?>