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
     *
     * @var mixed
     */
    protected $db;

    /**
     * Database hostname
     *
     * @var string
     */
    protected $host;

    /**
     * Database port
     *
     * @var int
     */
    protected $port;

    /**
     * Database user
     *
     * @var string
     */
    protected $user;

    /**
     * Database password
     *
     * @var string
     */
    protected $password;

    /**
     * Database name
     *
     * @var string
     */
    protected $name;

    /**
     * Database charset
     *
     * @var string
     */
    protected $charset;

    /**
     * Instantiate a gateway with the database parameters.
     *
     * @param array $params Database parameters.
     */
    public function __construct( array $params = array() )
    {
        if ( isset( $params["host"] ) )
            $this->host = $params["host"];

        if ( isset( $params["port"] ) )
            $this->port = $params["port"];

        if ( isset( $params["user"] ) )
            $this->user = $params["user"];

        if ( isset( $params["password"] ) )
            $this->password = $params["password"];

        if ( isset( $params["name"] ) )
            $this->name = $params["name"];

        if ( isset( $params["charset"] ) )
            $this->charset = $params["charset"];
    }

    /**
     * Creates the necessary database connection
     *
     * The database connexion must be usable as is after return, meaning
     * that database connection, charset choice must be set
     *
     * @return mixed The connection object, whatever the type
     *
     * @throws RuntimeException if connection failed
     */
    abstract function connect();

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
     * @param int $filesize
     * @param int $offset
     * @param int $length
     * @return void
     */
    abstract function passthrough( $filepath, $filesize, $offset = false, $length = false );

    /**
     * Closes any connection that should be closed
     */
    abstract function close();
}
