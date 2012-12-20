<?php
/**
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * DB/MySQLi cluster gateway
 */
class ezpDbMySQLiClusterGateway extends ezpClusterGateway
{
    protected $port = 3306;

    public function connect()
    {
        if ( !$this->db = mysqli_connect( $this->host, $this->user, $this->password, $this->name, $this->port ) )
            throw new RuntimeException( "Failed connecting to the MySQL database " .
                "(error #". mysqli_errno( $this->db ).": " . mysqli_error( $this->db ) );

        if ( !mysqli_set_charset( $this->db, $this->charset ) )
            throw new RuntimeException( "Failed to set database charset to '$this->charset' " .
                "(error #". mysqli_errno( $this->db ).": " . mysqli_error( $this->db ) );
    }

    public function fetchFileMetadata( $filepath )
    {
        $sql = "SELECT `datatype`, `size`, `mtime` FROM ezdbfile WHERE name_hash = MD5('$filepath')";
        if ( !$res = mysqli_query( $this->db, $sql ) )
            throw new RuntimeException( "Failed to fetch file metadata for '$filepath' " .
                "(error #". mysqli_errno( $this->db ).": " . mysqli_error( $this->db ) );

        if ( mysqli_num_rows( $res ) == 0 )
            return false;

        $metadata = mysqli_fetch_assoc( $res );
        mysqli_free_result( $res );
        return $metadata;
    }

    public function passthrough( $filepath, $filesize, $offset = false, $length = false)
    {
        if ( $offset !== false )
            throw new UnexpectedValueException( "HTTP Range is not supported by " . __CLASS__ );

        if ( !$res = mysqli_query( $this->db, "SELECT filedata FROM ezdbfile_data WHERE name_hash=MD5('$filepath') ORDER BY offset ASC" ) )
            throw new RuntimeException( "Unable to open file data for '$filepath' " .
                "(error #". mysqli_errno( $this->db ).": " . mysqli_error( $this->db ) );
        while ( $row = mysqli_fetch_row( $res ) )
            echo $row[0];
        mysqli_free_result( $res );
    }

    public function close()
    {
        mysqli_close( $this->db );
        unset( $this->db );
    }
}

ezpClusterGateway::setGatewayClass( 'ezpDbMySQLiClusterGateway' );