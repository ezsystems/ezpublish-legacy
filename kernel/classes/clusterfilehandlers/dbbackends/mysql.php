<?php
//
// Definition of eZDBFileHandlerMysqlBackend class
//
// Created on: <19-Apr-2006 16:15:17 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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

/*! \file ezdbfilehandlermysqlbackend.php
*/

define( 'TABLE_METADATA',     'ezdbfile' );
define( 'TABLE_DATA',         'ezdbfile_data' );

/*
CREATE TABLE ezdbfile (
  id        MEDIUMINT(8) UNSIGNED NOT NULL auto_increment,
  datatype  VARCHAR(60)  NOT NULL DEFAULT 'application/octet-stream',
  name      VARCHAR(255) NOT NULL DEFAULT '',
  name_hash VARCHAR(34)  NOT NULL DEFAULT '',
  scope     VARCHAR(20)  NOT NULL DEFAULT '',
  size      BIGINT(20)   UNSIGNED NOT NULL,
  mtime     INT(11)      NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE INDEX ezdbfile_name (name),
  UNIQUE INDEX ezdbfile_name_hash (name_hash)
) ENGINE=InnoDB;

CREATE TABLE ezdbfile_data (
  id       MEDIUMINT(8) unsigned NOT NULL auto_increment,
  masterid MEDIUMINT(8) unsigned NOT NULL default '0',
  filedata BLOB NOT NULL,
  PRIMARY KEY (id),
  KEY master_idx (masterid)
) ENGINE=InnoDB;
 */

require_once( 'lib/ezutils/classes/ezdebugsetting.php' );
require_once( 'lib/ezutils/classes/ezdebug.php' );

class eZDBFileHandlerMysqlBackend
{
    function _connect()
    {
        if ( !isset( $GLOBALS['eZDBFileHandlerMysqlBackend_dbparams'] ) )
        {
            $fileINI = eZINI::instance( 'file.ini' );

            $params['host']       = $fileINI->variable( 'ClusteringSettings', 'DBHost' );
            $params['port']       = $fileINI->variable( 'ClusteringSettings', 'DBPort' );
            $params['socket']     = $fileINI->variable( 'ClusteringSettings', 'DBSocket' );
            $params['dbname']     = $fileINI->variable( 'ClusteringSettings', 'DBName' );
            $params['user']       = $fileINI->variable( 'ClusteringSettings', 'DBUser' );
            $params['pass']       = $fileINI->variable( 'ClusteringSettings', 'DBPassword' );
            $params['chunk_size'] = $fileINI->variable( 'ClusteringSettings', 'DBChunkSize' );

            $GLOBALS['eZDBFileHandlerMysqlBackend_dbparams'] = $params;
        }
        else
            $params = $GLOBALS['eZDBFileHandlerMysqlBackend_dbparams'];

        $serverString = $params['host'];
        if ( $params['socket'] )
            $serverString .= ':' . $params['socket'];
        elseif ( $params['port'] )
            $serverString .= ':' . $params['port'];

        if ( !$this->db = mysql_connect( $serverString, $params['user'], $params['pass'] ) )
            $this->_die( "Unable to connect to storage server" );

        if ( !mysql_select_db( $params['dbname'], $this->db ) )
            $this->_die( "Unable to connect to storage database" );

        $this->dbparams = $params;
    }

    function _copy( $srcFilePath, $dstFilePath )
    {
        // fetch source file metadata
        $metaData = $this->_fetchMetadata( $srcFilePath );
        if ( !$metaData ) // if source file does not exist then do nothing.
            return false;

        mysql_query( 'BEGIN', $this->db );

        if ( $this->_exists( $dstFilePath ) )
            $this->_delete( $dstFilePath, true );

        $srcFilePath = mysql_real_escape_string( $srcFilePath );
        $dstFilePath = mysql_real_escape_string( $dstFilePath );

        $datatype        = $metaData['datatype'];
        $filePathEscaped = $dstFilePath;
        $filePathHash    = md5( $filePathEscaped );
        $scope           = $metaData['scope'];
        $contentLength   = $metaData['size'];
        $fileMTime       = $metaData['mtime'];

        // Copy file metadata.
        $sql  = "INSERT INTO " . TABLE_METADATA . " (datatype, name, name_hash, scope, size, mtime) VALUES";
        $sql .= "('$datatype', '$filePathEscaped', '$filePathHash', '$scope', $contentLength, '$fileMTime')";

        if ( !$res = mysql_query( $sql, $this->db ) )
        {
            eZDebug::writeError( $srcFilPath, "Failed to insert file metadata on copying." );
            mysql_query( 'ROLLBACK', $this->db );
            return false;
        }

        // Copy file data.

        $srcFileID = $metaData['id'];
        $sql = "SELECT filedata FROM " . TABLE_DATA . " WHERE masterid=$srcFileID";
        if ( !$res = mysql_query( $sql, $this->db ) )
        {
            eZDebug::writeError( $srcFilePath, "Failed to fetch source file data on copying." );
            mysql_query( 'ROLLBACK', $this->db );
            return false;
        }

        $dstFileID = mysql_insert_id( $this->db );
        while ( $row = mysql_fetch_row( $res ) )
        {
            // make the data mysql insert safe.
            $binarydata = mysql_real_escape_string( $row[0] );

            $sql = "INSERT INTO " . TABLE_DATA . " (masterid, filedata) VALUES ($dstFileID, '$binarydata')";

            if ( !mysql_query( $sql, $this->db ) )
            {
                eZDebug::writeError( "Failed to insert data row while copying file." );
                mysql_query( 'ROLLBACK', $this->db );
                mysql_free_result( $res );
                return false;
            }
        }

        mysql_free_result( $res );
        mysql_query( 'COMMIT', $this->db );

        return true;
    }

    function _delete( $filePath, $insideOfTransaction = false )
    {
        $filePath = mysql_real_escape_string( $filePath );
        $sql = "select * from " . TABLE_METADATA . " where name_hash='" . md5( $filePath ) . "'" ;
        if ( !$res = mysql_query( $sql, $this->db ) )
        {
            eZDebug::writeError( "Failed to retrive file metadata before deletion: $filePath.");
            return false;
        }

        if ( mysql_num_rows( $res ) == 0 )
        {
            mysql_free_result( $res );
            return false;
        }

        if ( !$insideOfTransaction )
            mysql_query( 'BEGIN', $this->db );

        $result = true;

        while ( $row = mysql_fetch_row( $res ) )
        {
            $fileID = (int) $row[0];
            if ( !mysql_query( "DELETE FROM " . TABLE_DATA . " WHERE masterid=$fileID", $this->db ) )
            {
                eZDebug::writeError( "Failed to delete file data: $filePath: " . mysql_error( $this->db ) );
                $result = false;
                break;
            }
        }

        if ( !mysql_query( "DELETE FROM " . TABLE_METADATA . " WHERE id=$fileID", $this->db ) )
        {
            eZDebug::writeError( "Failed to delete file metadata: $filePath: " . mysql_error( $this->db ) );
            $result = false;
        }

        mysql_free_result( $res );

        if ( !$insideOfTransaction )
        {
            if ( $result )
                mysql_query( 'COMMIT', $this->db );
            else
                mysql_query( 'ROLLBACK', $this->db );
        }

        return $result;
    }

    function _deleteByLike( $like )
    {
        $like = mysql_real_escape_string( $like );
        $sql = "SELECT name FROM " . TABLE_METADATA . " WHERE name like '$like'" ;
        if ( !$res = mysql_query( $sql, $this->db ) )
        {
            eZDebug::writeError( "Failed to delete files by like: '$like'" );
            return false;
        }

        if ( !mysql_num_rows( $res ) )
        {
            mysql_free_result( $res );
            return true;
        }

        while ( $row = mysql_fetch_row( $res ) )
        {
            $deleteFilename = $row[0];
            $this->_delete( $deleteFilename );
        }

        mysql_free_result( $res );
        return true;
    }

    function _deleteByRegex( $regex )
    {
        $regex = mysql_real_escape_string( $regex );
        $sql = "SELECT name FROM " . TABLE_METADATA . " WHERE name REGEXP '$regex'" ;
        if ( !$res = mysql_query( $sql, $this->db ) )
        {
            eZDebug::writeError( "Failed to delete files by regex: '$regex'" );
            return false;
        }

        if ( !mysql_num_rows( $res ) )
        {
            mysql_free_result( $res );
            return true;
        }

        while ( $row = mysql_fetch_row( $res ) )
        {
            $deleteFilename = $row[0];
            $this->_delete( $deleteFilename );
        }

        mysql_free_result( $res );
        return true;
    }

    function _deleteByWildcard( $wildcard )
    {
        // Convert wildcard to regexp.
        $regex = '^' . mysql_real_escape_string( $wildcard ) . '$';

        $regex = str_replace( array( '.'  ),
                              array( '\.' ),
                              $regex );

        $regex = str_replace( array( '?', '*',  '{', '}', ',' ),
                              array( '.', '.*', '(', ')', '|' ),
                              $regex );

        $sql = "SELECT name FROM " . TABLE_METADATA . " WHERE name REGEXP '$regex'" ;
        if ( !$res = mysql_query( $sql, $this->db ) )
        {
            eZDebug::writeError( "Failed to delete files by wildcard: '$wildcard'" );
            return false;
        }

        if ( !mysql_num_rows( $res ) )
        {
            mysql_free_result( $res );
            return true;
        }

        while ( $row = mysql_fetch_row( $res ) )
        {
            $deleteFilename = $row[0];
            $this->_delete( $deleteFilename );
        }

        mysql_free_result( $res );
        return true;
    }

    function _deleteByDirList( $dirList, $commonPath, $commonSuffix )
    {

        foreach ( $dirList as $dirItem )
        {
            $sql = "SELECT name FROM " . TABLE_METADATA . " WHERE name like '$commonPath/$dirItem/$commonSuffix%'" ;
            if ( !$res = mysql_query( $sql, $this->db ) )
            {
                eZDebug::writeError( "Failed to delete files in dir: '$commonPath/$dirItem/$commonSuffix%'" );
                return false;
            }

            if ( !mysql_num_rows( $res ) )
            {
                mysql_free_result( $res );
                continue;
            }

            while ( $row = mysql_fetch_row( $res ) )
            {
                $deleteFilename = $row[0];
                $this->_delete( $deleteFilename );
            }

            mysql_free_result( $res );
        }
        return true;
    }


    function _exists( $filePath )
    {
        $filePath = mysql_real_escape_string( $filePath );
        $filePathHash = md5( $filePath );
        $rslt = mysql_query( "SELECT COUNT(*) FROM " . TABLE_METADATA . " WHERE name_hash='$filePathHash' ", $this->db );
        if ( !$rslt )
        {
            eZDebug::writeError( "Failed to check file '$filePath' existance: " . mysql_error( $this->db ) );
            return false;
        }
        $row = mysql_fetch_row( $rslt );
        mysql_free_result( $rslt );
        $rc = (int) $row[0];
        return $rc;
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

    function _fetch( $filePath, $uniqueName = false )
    {
        $metaData = $this->_fetchMetadata( $filePath );
        if ( !$metaData )
        {
            eZDebug::writeNotice( "File '$filePath' does not exists while trying to fetch." );
            return false;
        }

        $fileID = $metaData['id'];
        $sql = "SELECT filedata FROM " . TABLE_DATA . " WHERE masterid=$fileID ORDER BY id";
        if ( !$res = mysql_query( $sql, $this->db ) )
        {
            eZDebug::writeError( $srcFilePath, "Failed to fetch file data." );
            return false;
        }

        if( !mysql_num_rows( $res ) )
        {
            eZDebug::writeNotice( "No rows in file '$filePath' (#$fileID) being fetched." );
            mysql_free_result( $res );
            return false;
        }

        // create temporary file
        if ( strrpos( $filePath, '.' ) > 0 )
            $tmpFilePath = substr_replace( $filePath, getmypid().'tmp', strrpos( $filePath, '.' ), 0  );
        else
            $tmpFilePath = $filePath . '.' . getmypid().'tmp';
//        $tmpFilePath = $filePath.getmypid().'tmp';
        $this->__mkdir_p( dirname( $tmpFilePath ) );
        if ( !( $fp = fopen( $tmpFilePath, 'wb' ) ) )
        {
            eZDebug::writeError( "Cannot write to '$tmpFilePath' while fetching file." );
            return false;
        }

        while ( $row = mysql_fetch_row( $res ) )
            fwrite( $fp, $row[0] );

        fclose( $fp );

        if ( ! $uniqueName === true )
        {
            include_once( 'lib/ezfile/classes/ezfile.php' );
            eZFile::rename( $tmpFilePath, $filePath );
        }
        else
        {
            $filePath = $tmpFilePath;
        }
        mysql_free_result( $res );

        return $filePath;
    }

    function _fetchContents( $filePath )
    {
        $metaData = $this->_fetchMetadata( $filePath );
        if ( !$metaData )
        {
            eZDebug::writeNotice( "File '$filePath' does not exists while trying to fetch its contents." );
            return false;
        }

        $fileID = $metaData['id'];
        $sql = "SELECT filedata FROM " . TABLE_DATA . " WHERE masterid=$fileID ORDER BY id";
        if ( !$res = mysql_query( $sql, $this->db ) )
        {
            eZDebug::writeError( $srcFilePath, "Failed to fetch file data." );
            return false;
        }

        $contents = '';
        while ( $row = mysql_fetch_row( $res ) )
        {
            $contents .= $row[0];
        }

        mysql_free_result( $res );
        return $contents;
    }

    /**
     * \return file metadata, or false if the file does not exist in database.
     */
    function _fetchMetadata( $filePath )
    {
        $filePath = mysql_real_escape_string( $filePath );
        $sql = "SELECT * FROM " . TABLE_METADATA . " WHERE name_hash='" . md5( $filePath ) . "'" ;
        if ( !$res = mysql_query( $sql, $this->db ) )
            $this->_die( "Failed to retrive file metadata: $filePath", $sql );

        $nRows = mysql_num_rows( $res );
        $metaData = false;

        switch ( $nRows )
        {
            case 0;
                break;

            default:
                eZDebug::writeError( "Duplicate file '$filePath' found." );
                // there must be no 'break' here.

            case 1:
                $row = mysql_fetch_array( $res, MYSQL_ASSOC );
                $metaData = $row;
        }

        mysql_free_result( $res );
        return $metaData;
    }

    function _linkCopy( $srcPath, $dstPath )
    {
        return $this->_copy( $srcPath, $dstPath );
    }

    function _passThrough( $filePath )
    {
        $metaData = $this->_fetchMetadata( $filePath );
        if ( !$metaData )
            return false;

        $fileID = $metaData['id'];
        $sql = "SELECT filedata FROM " . TABLE_DATA . " WHERE masterid=$fileID";
        if ( !$res = mysql_query( $sql, $this->db ) )
        {
            eZDebug::writeError( $srcFilePath, "Failed to fetch file data." );
            return false;
        }

        while ( $row = mysql_fetch_row( $res ) )
            echo $row[0];

        mysql_free_result( $res );
        return true;
    }

    function _rename( $srcFilePath, $dstFilePath )
    {
        // fetch source file metadata
        $metaData = $this->_fetchMetadata( $srcFilePath );
        if ( !$metaData ) // if source file does not exist then do nothing.
            return false;

        mysql_query( 'BEGIN', $this->db );

        if ( $this->_exists( $dstFilePath ) )
            $this->_delete( $dstFilePath, true );

        $srcFilePath = mysql_real_escape_string( $srcFilePath );
        $dstFilePath = mysql_real_escape_string( $dstFilePath );

        $srcFilePathHash = $metaData['name_hash'];
        $dstFilePathHash = md5( $dstFilePath );

        $sql =  "UPDATE " . TABLE_METADATA . " SET name='$dstFilePath', name_hash='$dstFilePathHash' ";
        $sql .= "WHERE name_hash='$srcFilePathHash'";
        if ( !mysql_query( $sql, $this->db ) )
        {
            eZDebug::writeError( "Failed renaming file '$srcFilePath' to '$dstFilePath'" );
            mysql_query( 'ROLLBACK', $this->db );
            return false;
        }

        mysql_query( 'COMMIT', $this->db );

        return true;
    }

    function _store( $filePath, $datatype, $scope )
    {
        if ( !is_readable( $filePath ) )
        {
            eZDebug::writeError( "Unable to store file '$filePath' since it is not readable.", 'ezdbfilehandlermysqlbackend' );
            return;
        }

        // Start transction.
        mysql_query( 'BEGIN', $this->db );

        // Delete the file from database if it's already there.
        if ( $this->_exists( $filePath ) )
            $this->_delete( $filePath, true );

        if ( $this->_exists( $filePath ) )
            eZDebug::writeNotice( "File '$filePath' still exists after deletion." );

        // Insert file metadata.
        clearstatcache();
        $fileMTime = filemtime( $filePath );
        $contentLength = filesize( $filePath );
        $filePathEscaped = mysql_real_escape_string( $filePath );
        $filePathHash = md5( $filePathEscaped );
        $sql  = "INSERT INTO " . TABLE_METADATA . " (datatype, name, name_hash, scope, size, mtime) VALUES";
        $sql .= "('$datatype', '$filePathEscaped', '$filePathHash', '$scope', $contentLength, '$fileMTime')";

        if ( !$res = mysql_query( $sql, $this->db ) )
        {
            eZDebug::writeNotice( "Failed to insert file metadata while storing. Possible race condition: " . $sql );
            mysql_query( 'ROLLBACK', $this->db );
            return;
        }

        $fileID = mysql_insert_id( $this->db );

        // Insert file contents.
        if ( !$fp = @fopen( $filePath, 'rb' ) )
        {
            eZDebug::writeError( "Cannot read '$filePath'.", 'ezdbfilehandlermysqlbackend' );
            mysql_query( 'ROLLBACK', $this->db );
            return;
        }

        $chunkSize = $this->dbparams['chunk_size'];
        while ( !feof( $fp ) )
        {
            // make the data mysql insert safe.
            $binarydata = mysql_real_escape_string( fread( $fp, $chunkSize ) );

            $sql = "INSERT INTO " . TABLE_DATA . " (masterid, filedata) VALUES ($fileID, '$binarydata')";

            if ( !mysql_query( $sql, $this->db ) )
            {
                eZDebug::writeNotice( "Failed to insert file data row while storing. Possible race condition: " . $sql );
                mysql_query( 'ROLLBACK', $this->db );
                return;
            }
        }

        // End transaction.
        mysql_query( 'COMMIT', $this->db );

        fclose( $fp );
    }

    function _storeContents( $filePath, $contents, $scope, $datatype )
    {
        // Start transction.
        mysql_query( 'BEGIN', $this->db );

        // Delete the file from database if it's already there.
        if ( $this->_exists( $filePath ) )
            $this->_delete( $filePath, true );

        // Insert file metadata.
        $contentLength = strlen( $contents );
        $filePath = mysql_real_escape_string( $filePath );
        $filePathHash = md5( $filePath );
        $curTime = time();
        $sql  = "INSERT INTO " . TABLE_METADATA . " (datatype, name, name_hash, scope, size, mtime) VALUES";
        $sql .= "('$datatype', '$filePath', '$filePathHash', '$scope', $contentLength, '$curTime')";

        if ( !$res = mysql_query( $sql, $this->db ) )
        {
            eZDebug::writeNotice( "Failed to insert file metadata while storing contents. Possible race condition: " . $sql );
            mysql_query( 'ROLLBACK', $this->db );
            return;
        }
        $fileID = mysql_insert_id( $this->db );

        // Insert file contents.
        $chunkSize = $this->dbparams['chunk_size'];
        for ( $pos = 0; $pos < $contentLength; $pos += $chunkSize )
        {
            $chunk = substr( $contents, $pos, $chunkSize );

            $sql = "INSERT INTO " . TABLE_DATA . " ( masterid, filedata ) VALUES (";
            $sql .= $fileID . ", '" . mysql_real_escape_string( $chunk ) . "')";

            if ( !mysql_query( $sql, $this->db ) )
            {
                eZDebug::writeNotice( "Failed to insert file data row while storing contents. Possible race condition: " . $sql );
                mysql_query( 'ROLLBACK', $this->db );
                return;
            }
        }

        // End transaction.
        mysql_query( 'COMMIT', $this->db );
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

        $rslt = mysql_query( $query, $this->db );
        if ( !$rslt )
        {
            eZDebug::writeError( mysql_error( $this->db ) );
            return false;
        }

        $filePathList = array();
        while ( $row = mysql_fetch_row( $rslt ) )
            $filePathList[] = $row[0];

        mysql_free_result( $rslt );

        return $filePathList;
    }

    function _die( $msg, $sql = null )
    {
        if ( $this->db )
            eZDebug::writeError( $sql, "$msg" . mysql_error( $this->db ) );
        else
            eZDebug::writeError( $sql, "$msg: " . mysql_error() );

        if( @include_once( '../bt.php' ) )
        {
            bt();
            die( $msg );
        }
    }

    var $db   = null;
}

?>
