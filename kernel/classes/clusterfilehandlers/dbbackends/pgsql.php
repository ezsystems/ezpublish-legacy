<?php
//
// Definition of eZDBFileHandlerPgsqlBackend class
//
// Created on: <19-Apr-2006 16:15:17 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezdbfilehandlerpgsqlbackend.php
*/

define( 'TABLE_METADATA',     'ezdbfile' );

/*
CREATE TABLE ezdbfile (
  id        SERIAL PRIMARY KEY,
  name      VARCHAR(255) NOT NULL UNIQUE,
  name_hash VARCHAR(34)  NOT NULL UNIQUE,
  datatype  VARCHAR(60)  NOT NULL DEFAULT 'application/octet-stream',
  scope     VARCHAR(20)  NOT NULL DEFAULT 'UNKNOWN',
  size      BIGINT       NOT NULL DEFAULT 0,
  mtime     INT          NOT NULL DEFAULT 0,
  lob_id    OID          NOT NULL
);
*/

require_once( 'lib/ezutils/classes/ezdebugsetting.php' );
require_once( 'lib/ezutils/classes/ezdebug.php' );

class eZDBFileHandlerPgsqlBackend
{
    function _connect()
    {
        if ( !isset( $GLOBALS['eZDBFileHandlerPgsqlBackend_dbparams'] ) )
        {
            $fileINI = eZINI::instance( 'file.ini' );

            $params['host']       = $fileINI->variable( 'ClusteringSettings', 'DBHost' );
            $params['port']       = $fileINI->variable( 'ClusteringSettings', 'DBPort' );
            $params['dbname']     = $fileINI->variable( 'ClusteringSettings', 'DBName' );
            $params['user']       = $fileINI->variable( 'ClusteringSettings', 'DBUser' );
            $params['pass']       = $fileINI->variable( 'ClusteringSettings', 'DBPassword' );
            $params['chunk_size'] = $fileINI->variable( 'ClusteringSettings', 'DBChunkSize' );

            $GLOBALS['eZDBFileHandlerPgsqlBackend_dbparams'] = $params;
        }
        else
            $params = $GLOBALS['eZDBFileHandlerPgsqlBackend_dbparams'];

        $connStr  = "host=$params[host] ";
        $connStr .= "port=$params[port] ";
        $connStr .= "dbname=$params[dbname] ";
        $connStr .= "user=$params[user] ";
        $connStr .= "password=$params[pass]";
        if ( !$this->db = pg_connect( $connStr ) )
            $this->_die( "Unable to connect to storage server." );

        $this->dbparams = $params;
    }

    function _delete( $filePath, $insideOfTransaction = false )
    {
        if ( !$metaData = $this->_fetchMetadata( $filePath ) )
            return false;

        $result = true;

        $insideOfTransaction || pg_query( $this->db, 'BEGIN' );

        if ( !pg_query( "DELETE FROM " . TABLE_METADATA . " WHERE id=" . $metaData['id'] ) )
        {
            eZDebug::writeError( "Failed to delete file metadata: $filePath: " . pg_last_error( $this->db ) );
            $result = false;
        }

        if ( !pg_lo_unlink( $metaData['lob_id'] ) )
        {
            eZDebug::writeError( "Failed to remove large object while deleting file '$filePath': " . pg_last_error( $this->db ) );
            $result = false;
        }

        $insideOfTransaction || pg_query( $this->db, $result ? 'COMMIT' : 'ROLLBACK' );

        return $result;
    }

    function _deleteByRegex( $regex )
    {
        $regex = pg_escape_string( $regex );
        $sql = "SELECT name FROM " . TABLE_METADATA . " WHERE name ~ '$regex'";
        if ( !$res = pg_query( $this->db, $sql ) )
        {
            eZDebug::writeError( "Failed to delete files by regex: '$regex'" );
            return false;
        }

        if ( !pg_num_rows( $res ) )
        {
            pg_free_result( $res );
            return true;
        }

        while ( $row = pg_fetch_row( $res ) )
        {
            $deleteFilename = $row[0];
            $this->_delete( $deleteFilename );
        }

        pg_free_result( $res );
        return true;
    }

    function _deleteByWildcard( $wildcard )
    {
        // Convert wildcard to regexp.
        $regex = '^' . pg_escape_string( $wildcard ) . '$';

        $regex = str_replace( array( '.'  ),
                              array( '\.' ),
                              $regex );

        $regex = str_replace( array( '?', '*',  '{', '}', ',' ),
                              array( '.', '.*', '(', ')', '|' ),
                              $regex );

        $sql = "SELECT name FROM " . TABLE_METADATA . " WHERE name ~ '$regex'" ;
        if ( !$res = pg_query( $this->db, $sql ) )
        {
            eZDebug::writeError( "Failed to delete files by wildcard: '$wildcard'" );
            return false;
        }

        if ( !pg_num_rows( $res ) )
        {
            pg_free_result( $res );
            return true;
        }

        while ( $row = pg_fetch_row( $res ) )
        {
            $deleteFilename = $row[0];
            $this->_delete( $deleteFilename );
        }

        pg_free_result( $res );
        return true;
    }

    function _deleteByLike( $like )
    {
        $like = pg_escape_string( $like );
        $sql = "SELECT name FROM " . TABLE_METADATA . " WHERE name like '$like'" ;
        if ( !$res = pg_query( $this->db, $sql ) )
        {
            eZDebug::writeError( "Failed to delete files by like: '$like'" );
            return false;
        }

        if ( !pg_num_rows( $res ) )
        {
            pg_free_result( $res );
            return true;
        }

        while ( $row = pg_fetch_row( $res ) )
        {
            $deleteFilename = $row[0];
            $this->_delete( $deleteFilename );
        }

        pg_free_result( $res );
        return true;
    }

    function _deleteByDirList( $dirList, $commonPath, $commonSuffix )
    {

        foreach ( $dirList as $dirItem )
        {
            $sql = "SELECT name FROM " . TABLE_METADATA . " WHERE name like '$commonPath/$dirItem/$commonSuffix%'" ;
            if ( !$res = pg_query( $this->db, $sql ) )
            {
                eZDebug::writeError( "Failed to delete files in dir: '$commonPath/$dirItem/$commonSuffix%'" );
                return false;
            }

            if ( !pg_num_rows( $res ) )
            {
                pg_free_result( $res );
                continue;
            }

            while ( $row = pg_fetch_row( $res ) )
            {
                $deleteFilename = $row[0];
                $this->_delete( $deleteFilename );
            }

            pg_free_result( $res );
        }
        return true;
    }

    function _exists( $filePath )
    {
        $filePathHash = md5( $filePath );
        $res = pg_query( "SELECT COUNT(*) AS count FROM " . TABLE_METADATA . " WHERE name_hash='$filePathHash'" );
        $row = pg_fetch_row( $res );
        $count = $row[0];
        pg_free_result( $res );
        return $count;
    }

    function __mkdir_p( $dir )
    {
        // create parent directories
        $dirElements = explode( '/', $dir );
        if ( count( $dirElements ) == 0 )
            return true;

        $result = true;
        $currentDir = $dirElements[0];

        if ( $currentDir != '' && !file_exists( $currentDir ) && !mkdir( $currentDir, '0777' ))
            return false;

        for ( $i = 1; $i < count( $dirElements ); ++$i )
        {
            $dirElement = $dirElements[$i];
            if ( strlen( $dirElement ) == 0 )
                continue;

            $currentDir .= '/' . $dirElement;

            if ( !file_exists( $currentDir ) && !mkdir( $currentDir, 0777 ) )
                return false;

            $result = true;
        }

        return $result;
    }

    function _fetch( $filePath )
    {
        $metaData = $this->_fetchMetadata( $filePath );
        if ( !$metaData )
        {
            eZDebug::writeNotice( "File '$filePath' does not exists while trying to fetch." );
            return false;
        }

        // create temporary file
        $tmpFilePath = $filePath.getmypid().'tmp';
        $this->__mkdir_p( dirname( $tmpFilePath ) );
        if ( !( $fp = fopen( $tmpFilePath, 'wb' ) ) )
        {
            eZDebug::writeError( "Cannot write to '$tmpFilePath' while fetching file." );
            return false;
        }

        // copy large object contents to the file
        pg_query( $this->db, "BEGIN" );
        $chunkSize = $this->dbparams['chunk_size'];
        $lobHandle = pg_lo_open( $this->db, $metaData['lob_id'], 'r' );
        while ( $chunk = pg_lo_read( $lobHandle, $chunkSize ) )
            fwrite( $fp, $chunk );
        pg_lo_close( $lobHandle );
        pg_query( $this->db, "COMMIT" );

        // rename temporary file to the target one
        fclose( $fp );
        rename( $tmpFilePath, $filePath );

        return true;
    }

    function _fetchContents( $filePath )
    {
        $metaData = $this->_fetchMetadata( $filePath );
        if ( !$metaData )
        {
            eZDebug::writeNotice( "File '$filePath' does not exists while trying to fetch its contents." );
            return false;
        }

        // fetch large object contents
        $contents = '';
        $chunkSize = $this->dbparams['chunk_size'];
        pg_query( $this->db, "BEGIN" );
        $lobHandle = pg_lo_open( $this->db, $metaData['lob_id'], 'r' );
        while ( $chunk = pg_lo_read( $lobHandle, $chunkSize ) )
            $contents .= $chunk;
        pg_lo_close( $lobHandle );
        pg_query( $this->db, "COMMIT" );

        return $contents;
    }

    function _fetchMetadata( $filePath )
    {
        $filePathHash = md5( $filePath );
        if( !( $res = pg_query( $this->db, "SELECT * FROM " . TABLE_METADATA . " WHERE name_hash='$filePathHash'" ) ) )
        {
            eZDebug::writeError( "Error fetching file metadata: " . pg_last_error( $this->db ) );
            return false;
        }
        if ( ( $nrows = pg_num_rows( $res ) ) > 1 )
            eZDebug::writeError( "Duplicate file '$filePath' found." );
        elseif ( $nrows == 0 )
            return false;
        $row = pg_fetch_array( $res, null, PGSQL_ASSOC );
        pg_free_result( $res );

        return $row;
    }

    function _store( $filePath, $datatype, $scope )
    {
        if ( !is_readable( $filePath ) )
        {
            eZDebug::writeError( "Unable to store file '$filePath' since it is not readable.", 'ezdbfilehandlerpgsqlbackend' );
            return;
        }

        if ( !$fp = @fopen( $filePath, 'rb' ) )
        {
            eZDebug::writeError( "Cannot read '$filePath'.", 'ezdbfilehandlerpgsqlbackend' );
            return;
        }

        // Prepare file metadata for storing.
        $filePathHash = md5( $filePath );
        $filePathEscaped = pg_escape_string( $filePath );
        $datatype = pg_escape_string( $datatype );
        $scope = pg_escape_string( $scope );
        $fileMTime = (int) filemtime( $filePath );
        $contentLength = (int) filesize( $filePath );

        // Start DB transaction.
        pg_query( $this->db, 'BEGIN' );

        // Create new large object
        $lobOid = pg_lo_create( $this->db );
        $lobHandle = pg_lo_open( $this->db, $lobOid, 'w');
        $chunkSize = $this->dbparams['chunk_size'];
        while ( !feof( $fp ) )
        {
            $chunk = fread( $fp, $chunkSize );

            if ( pg_lo_write( $lobHandle, $chunk ) === false )
            {
                eZDebug::writeNotice( "Failed to insert file data chunk while storing. Possible race condition: " . $sql );
                pg_query( $this->db, 'ROLLBACK' );
                return;
            }
        }
        pg_lo_close( $lobHandle );
        fclose( $fp );

        // Check if a file with the same name already exists in db.
        if ( $row = $this->_fetchMetadata( $filePath ) ) // if it does
        {
            // re-write the file

            if ( pg_lo_unlink( $row['lob_id'] ) === false )
            {
                eZDebug::writeError( "Error removing large object while storing file: " . pg_last_error( $this->db ) );
                pg_query( $this->db, 'ROLLBACK' );
                return;
            }

            $sql  = "UPDATE " . TABLE_METADATA . " SET ";
            $sql .= "name='$filePathEscaped', name_hash='$filePathHash', ";
            $sql .= "datatype='$datatype', scope='$scope', ";
            $sql .= "size=$contentLength, mtime=$fileMTime, lob_id=$lobOid ";
            $sql .= "WHERE id=" . $row['id'];
        }
        else // else if it doesn't
        {
            // create file in db
            $sql  = "INSERT INTO " . TABLE_METADATA . " (name, name_hash, datatype, scope, size, mtime, lob_id) ";
            $sql .= "VALUES ('$filePathEscaped', '$filePathHash', '$datatype', ";
            $sql .= "'$scope', '$contentLength', '$fileMTime', $lobOid)";
        }

        if ( !pg_query( $this->db,  $sql ) )
        {
            eZDebug::writeError( "Error storing file '$filePath': " . pg_last_error( $this->db ), 'ezdbfilehandlerpgsqlbackend' );
            pg_query( $this->db, 'ROLLBACK');
            return;
        }

        // Commit DB transaction.
        pg_query( $this->db, 'COMMIT');
    }

    function _storeContents( $filePath, $contents, $scope, $datatype )
    {
        // Mostly cut&pasted from _store().

        // Prepare file metadata for storing.
        $filePathHash = md5( $filePath );
        $filePathEscaped = pg_escape_string( $filePath );
        $datatype = pg_escape_string( $datatype );
        $scope = pg_escape_string( $scope );
        $fileMTime = time();
        $contentLength = strlen( $contents );

        // Start DB transaction.
        pg_query( $this->db, 'BEGIN' );

        // Create new large object
        $lobOid = pg_lo_create( $this->db );
        $lobHandle = pg_lo_open( $this->db, $lobOid, 'w');
        $chunkSize = $this->dbparams['chunk_size'];
        for ( $pos = 0; $pos < $contentLength; $pos += $chunkSize )
        {
            $chunk = substr( $contents, $pos, $chunkSize );

            if ( pg_lo_write( $lobHandle, $chunk ) === false )
            {
                eZDebug::writeNotice( "Failed to insert file data chunk while storing contents. Possible race condition: " . $sql );
                pg_query( $this->db, 'ROLLBACK' );
                return;
            }
        }
        pg_lo_close( $lobHandle );

        // If a file with the same name already exists in db.
        if ( $row = $this->_fetchMetadata( $filePath ) ) // if it does
        {
            // re-write the file

            if ( pg_lo_unlink( $row['lob_id'] ) === false )
            {
                eZDebug::writeError( "Error removing large object while storing file contents: " . pg_last_error( $this->db ) );
                pg_query( $this->db, 'ROLLBACK' );
                return;
            }

            $sql  = "UPDATE " . TABLE_METADATA . " SET ";
            $sql .= "name='$filePathEscaped', name_hash='$filePathHash', ";
            $sql .= "datatype='$datatype', scope='$scope', ";
            $sql .= "size=$contentLength, mtime=$fileMTime, lob_id=$lobOid ";
            $sql .= "WHERE id=" . $row['id'];
        }
        else // else if it doesn't
        {
            // create file in db
            $sql  = "INSERT INTO " . TABLE_METADATA . " (name, name_hash, datatype, scope, size, mtime, lob_id) ";
            $sql .= "VALUES ('$filePathEscaped', '$filePathHash', '$datatype', ";
            $sql .= "'$scope', '$contentLength', '$fileMTime', $lobOid)";
        }

        if ( !pg_query( $this->db,  $sql ) )
        {
            eZDebug::writeError( $sql, "Error storing file '$filePath' contents: " .
                                 pg_last_error( $this->db ), 'ezdbfilehandlerpgsqlbackend' );
            pg_query( $this->db, 'ROLLBACK');
            return;
        }

        // Commit DB transaction.
        pg_query( $this->db, 'COMMIT');
    }

    function _copy( $srcFilePath, $dstFilePath )
    {
        // fetch source file metadata
        $srcMetadata = $this->_fetchMetadata( $srcFilePath );
        if ( !$srcMetadata ) // if source file does not exist then do nothing.
            return false;

        pg_query( $this->db, 'BEGIN' );

        // Delete destination file if exists.
        // NOTE: check for race conditions and deadlocks here.
        if ( $this->_exists( $dstFilePath ) )
            $this->_delete( $dstFilePath, true );

        // Copy file data.
        $dstLobOid = pg_lo_create( $this->db );
        $dstLobHandle = pg_lo_open( $this->db, $dstLobOid, 'w');
        $srcLobHandle = pg_lo_open( $this->db, $srcMetadata['lob_id'], 'r' );
        $chunkSize = $this->dbparams['chunk_size'];
        while ( $chunk = pg_lo_read( $srcLobHandle, $chunkSize ) )
        {
            if ( pg_lo_write( $dstLobHandle, $chunk ) === false )
            {
                eZDebug::writeError( "Failed to write data chunk while copying file '$srcFilePath': " .
                                     pg_last_error( $this->db ), 'ezdbfilehandlerpgsqlbackend' );
                pg_lo_close( $srcLobHandle );
                pg_lo_close( $dstLobHandle );
                pg_query( $this->db, 'ROLLBACK' );
                return false;
            }
        }
        pg_lo_close( $srcLobHandle );
        pg_lo_close( $dstLobHandle );

        // Copy file metadata.
        $sql = "INSERT INTO " . TABLE_METADATA . " (name, name_hash, datatype, scope, size, mtime, lob_id) VALUES";
        $sql .= sprintf( "('%s', '%s', '%s', '%s', %d, %d, %d)",

                         pg_escape_string( $dstFilePath ), md5( $dstFilePath ),
                         $srcMetadata['datatype'], $srcMetadata['scope'], $srcMetadata['size'], $srcMetadata['mtime'],
                         $dstLobOid );
        if ( !$res = pg_query( $this->db, $sql ) )
        {
            eZDebug::writeError( $srcFilePath, "Failed to insert file metadata on copying." );
            pg_query( $this->db, 'ROLLBACK' );
            return false;
        }

        pg_query( $this->db, 'COMMIT' );

        return true;
    }

    function _linkCopy( $srcPath, $dstPath )
    {
        return $this->_copy( $srcPath, $dstPath );
    }

    function _rename( $srcFilePath, $dstFilePath )
    {
        // fetch destination file metadata

        pg_query( $this->db, 'BEGIN' );

        // check if source file exists
        $srcMetadata = $this->_fetchMetadata( $srcFilePath );
        if ( !$srcMetadata )
        {
            // if doesn't then do nothing
            pg_query( $this->db, 'ROLLBACK' ); // nothing to rollback, actually
            eZDebug::writeWarning( "File '$srcFilePath' to rename does not exist",
                                   'ezdbfilehandlerpgsqlbackend' );
            return false;
        }

        // delete destination file if exists
        $dstMetadata = $this->_fetchMetadata( $dstFilePath );
        if ( $dstMetadata ) // if destination file exists
            $this->_delete( $dstFilePath, true );

        // update source file metadata
        $sql = sprintf( "UPDATE %s SET name='%s', name_hash='%s' WHERE id=%d",
                        TABLE_METADATA,
                        pg_escape_string( $dstFilePath ), md5( $dstFilePath ),
                        $srcMetadata['id'] );

        if ( !pg_query( $this->db,  $sql ) )
        {
            eZDebug::writeError( "Error renaming file '$srcFilePath': " .
                                 pg_last_error( $this->db ), 'ezdbfilehandlerpgsqlbackend' );
            pg_query( $this->db, 'ROLLBACK');
            return false;
        }

        pg_query( $this->db, 'COMMIT' );
        return true;
    }

    function _passThrough( $filePath )
    {
        $metaData = $this->_fetchMetadata( $filePath );
        if ( !$metaData )
            return false;

        pg_query( $this->db, "BEGIN" );
        $lobHandle = pg_lo_open( $this->db, $metaData['lob_id'], 'r' );
        $chunkSize = $this->dbparams['chunk_size'];
        while ( $chunk = pg_lo_read( $lobHandle, $chunkSize ) )
            echo $chunk;
        pg_lo_close( $lobHandle );
        pg_query( $this->db, "COMMIT" );

        return true;
    }

    function _getFileList( $skipBinaryFiles, $skipImages )
    {
        $query = 'SELECT name FROM ' . TABLE_METADATA;

        // omit some file types if needed
        $filters = array();
        if ( $skipBinaryFiles )
            $filters[] = "'binaryfile'";
        if ( $skipImages )
            $filters[] = "'image'";
        if ( $filters )
            $query .= ' WHERE scope NOT IN (' . join( ', ', $filters ) . ')';

        $rslt = pg_query( $this->db, $query );
        if ( !$rslt )
        {
            eZDebug::writeError( pg_last_error( $this->db ) );
            return false;
        }

        $filePathList = array();
        while ( $row = pg_fetch_row( $rslt ) )
            $filePathList[] = $row[0];

        pg_free_result( $rslt );

        return $filePathList;
    }

    function _die( $msg, $sql = null )
    {
        eZDebug::writeError( $sql, "$msg: " . pg_last_error( $this->db ) );

        if( @include_once( '../bt.php' ) )
        {
            bt();
            die( $msg );
        }
    }

    var $db   = null;
}

?>
