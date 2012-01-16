<?php
class ezpPostgresqlClusterGateway extends ezpClusterGateway
{
    public function getDefaultPort()
    {
        return 5433;
    }

    /**
    *
     * @return \PDO|false
     */
    public function connect( $host, $port, $user, $password, $database, $charset )
    {
        $connectString = sprintf( 'pgsql:host=%s;dbname=%s;port=%s', $host, $database, $port );

        try {
            $this->db = new PDO( $connectString, $user, $pass );
            if ( $this->db->exec( "SET NAMES '$charset'" ) === false )
            {
                throw new RuntimeException( "Failed to set database charset to '$charset'");
            }
        } catch( PDOException $e ) {
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

    public function passthrough( $filepath, $offset = false, $length = false)
    {
        $dfsFilePath = CLUSTER_MOUNT_POINT_PATH . '/' . $filepath;

        if ( !file_exists( $dfsFilePath ) )
            throw new RuntimeException( "Unable to open DFS file '$dfsFilePath'" );

        $fp = fopen( $dfsFilePath, 'r' );
        fpassthru( $fp );
        fclose( $fp );
    }

    public function close()
    {
        unset( $this->db );
    }
}

// return the class name for easier instanciation
return 'ezpPostgresqlClusterGateway';