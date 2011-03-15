<?php
/**
 * File containing the ezpOauthRequiredException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * This is the base exception for responsenses need authentication.
 *
 * @package oauth
 */
abstract class ezpOauthRequiredException extends ezpOauthException
{
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}
?>