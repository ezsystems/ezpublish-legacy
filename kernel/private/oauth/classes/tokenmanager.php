<?php
/**
 * File containing the ezpRestTokenManager class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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