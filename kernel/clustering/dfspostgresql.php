<?php
/**
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * DFS/PostgreSQL cluster gateway
 */
class ezpDfsPostgresqlClusterGateway extends ezpClusterGateway
{
    protected $port = 5433;

    public function connect()
    {
        try
        {
            $this->db = new PDO( "pgsql:host=$this->host;dbname=$this->name;port=$this->port", $this->user, $this->password );
            if ( $this->db->exec( "SET NAMES '$this->charset'" ) === false )
            {
                throw new RuntimeException( "Failed to set database charset to '$this->charset' " );
            }
        }
        catch ( PDOException $e )
        {
            throw new RuntimeException( $e->getMessage );
        }
    }

    public function fetchFileMetadata( $filepath )
    {
        $filePathHash = md5( $filepath );
        $sql = "SELECT * FROM ezdfsfile WHERE name_hash='$filePathHash'" ;
        if ( !$stmt = $this->db->query( $sql ) )
            throw new RuntimeException( "Failed to fetch file metadata for '$filepath'" );

        if ( $stmt->rowCount() == 0 )
        {
            return false;
        }

        return $stmt->fetch( PDO::FETCH_ASSOC );
    }

    public function passthrough( $filepath, $filesize, $offset = false, $length = false )
    {
        $dfsFilePath = CLUSTER_MOUNT_POINT_PATH . '/' . $filepath;

        if ( !file_exists( $dfsFilePath ) )
            throw new RuntimeException( "Unable to open DFS file '$dfsFilePath'" );

        $fp = fopen( $dfsFilePath, 'rb' );
        if ( $offset !== false && @fseek( $fp, $offset ) === -1 )
            throw new RuntimeException( "Failed to seek offset $offset on file '$filepath'" );
        if ( $offset === false && $length === false )
            fpassthru( $fp );
        else
            echo fread( $fp, $length );

        fclose( $fp );
    }

    public function close()
    {
        unset( $this->db );
    }
}

ezpClusterGateway::setGatewayClass( 'ezpDfsPostgresqlClusterGateway' );