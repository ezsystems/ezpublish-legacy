<?php
/**
 * File containing the ezpOauthRequiredException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
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
