<?php
/**
 * File containing the eZClusterHandlerDBNoConnectionException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Class representing a cluster no connection exception
 *
 * @version //autogentag//
 * @package kernel
 */

class eZClusterHandlerDBNoConnectionException extends eZDBException
{
    /**
     * Constructs a new eZClusterHandlerDBNoConnectionException
     *
     * @param string $host The hostname
     * @param string $user The username
     * @param string $pass The password (will be displayed as *)
     * @return void
     */
    function __construct( $host, $user, $password, $message = null )
    {
        $password = str_repeat( "*", strlen( $password ) );
        parent::__construct(
            "Unable to connect to the database server '{$host}' using username '{$user}' and password '{$password}'" .
            $message ? "\n$message" : ''
        );
    }
}
?>
