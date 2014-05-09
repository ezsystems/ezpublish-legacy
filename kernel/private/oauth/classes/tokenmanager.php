<?php
/**
 * File containing the ezpRestTokenManager class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package oAuth
 */

/**
 * This class handles application tokens
 * @package oAuth
 */

class ezpRestTokenManager
{
    /**
     * Generates a token
     *
     * @todo See if this should maybe return a token object with token + expiry
     *
     * @param string $scope
     *
     * @return string $token
     */
    public static function generateToken( $scope )
    {
        return md5( 'uniqid' );
    }
}
?>
