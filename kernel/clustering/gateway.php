<?php
/**
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Basis for a cluster gateway.
 *
 * Inherited by cluster index gateways
 */
abstract class ezpClusterGateway
{
    /**
     * Database instance, optional
     * @var mixed
     */
    private $db;

    /**
     * Sets, if required, the database instance to $db
     * @param mixed $db
     */
    function setDatabase( $db )
    {
        $this->db = $db;
    }

    /**
     * Returns the database default connexion port
     * @return int
     */
    abstract function getDefaultPort();

    /**
     * Creates the necessary database connection
     *
     * The database connexion must be usable as is after return, meaning
     * that database connection, charset choice must be set
     *
     * @param $host
     * @param $port
     * @param $user
     * @param $password
     * @param $database
     * @param $charset
     *
     * @return mixed The connection object, whatever the type
     *
     * @throws RuntimeException if connection failed
     */
    abstract function connect( $host, $port, $user, $password, $database, $charset );

    /**
     * Fetches file metadata for $filepath
     *
     * @param string $filepath
     *
     * @return array|false
     */
    abstract function fetchFileMetadata( $filepath );

    /**
     * Passes the $filepath data through
     * @param mixed $db
     * @param string $filepath
     * @param int $offset
     * @param int $length
     * @return void
     */
    abstract function passthrough( $filepath, $offset = false, $length = false );

    /**
     * Closes any connection that should be closed
     */
    abstract function close();
}
?>