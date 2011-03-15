<?php
/**
 * File containing the eZRemoteIdUtility class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package lib
 */

/**
 * This class provides tools around remote ids
 * @package lib
 */
class eZRemoteIdUtility
{
    /**
     * Generates a remote ID
     * @param string $type A type string, that will ensure more unicity: object, node, class...
     */
    public static function generate( $type = '' )
    {
        return md5( uniqid( $type, true ) );
    }
}
?>