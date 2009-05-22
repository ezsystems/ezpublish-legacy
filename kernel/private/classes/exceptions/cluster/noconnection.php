<?php
/**
 * File containing the eZClusterHandlerDBNoConnectionException class.
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
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
    function __construct( $host, $user, $password )
    {
        $password = str_repeat( "*", strlen( $password ) );
	parent::__construct( "Unable to connect to the database server '{$host}' using username '{$user}' and password '{$password}'" );
    }
}
?>
