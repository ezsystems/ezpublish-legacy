<?php
/**
 * File containing the ezpOauthException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * This is the base exception for the eZ Publish oauth library.
 *
 * @package oauth
 */
abstract class ezpOauthException extends ezcBaseException
{
    public $errorType = null;
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}
?>