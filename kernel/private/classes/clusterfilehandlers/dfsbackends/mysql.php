<?php
//
// Definition of eZDFSFileHandlerMySQLBackend class
//
// Created on: <19-Apr-2006 16:15:17 bd>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.2.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*
This is the structure / SQL CREATE for the DFS database table.
It can be created anywhere, in the same database on the same server, or on a
distinct database / server.

CREATE TABLE ezdfsfile (
  `name` text NOT NULL,
  name_trunk text NOT NULL,
  name_hash varchar(34) NOT NULL DEFAULT '',
  datatype varchar(60) NOT NULL DEFAULT 'application/octet-stream',
  scope varchar(20) NOT NULL DEFAULT '',
  size bigint(20) unsigned NOT NULL DEFAULT '0',
  mtime int(11) NOT NULL DEFAULT '0',
  expired tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (name_hash),
  KEY ezdfsfile_name (`name`(250)),
  KEY ezdfsfile_name_trunk (name_trunk(250)),
  KEY ezdfsfile_mtime (mtime),
  KEY ezdfsfile_expired_name (expired,`name`(250))
) ENGINE=InnoDB;
 */

class eZDFSFileHandlerMySQLBackend
{
    /**
     * Connects to the database.
     *
     * @return void
     * @throw eZClusterHandlerDBNoConnectionException
     * @throw eZClusterHandlerDBNoDatabaseException
     **/
    public function _connect()
    {
        // DB Connection setup
        // This part is not actually required since _connect will only be called
        // once, but it is useful to run the unit tests. So be it.
        // @todo refactor this using eZINI::setVariable in unit tests
        if ( self::$dbparams === null )
        {
            $siteINI = eZINI::instance( 'site.ini' );
            $fileINI = eZINI::instance( 'file.ini' );

            self::$dbparams = array();
            self::$dbparams['host']       = $fileINI->variable( 'eZDFSClusteringSettings', 'DBHost' );
            self::$dbparams['port']       = $fileINI->variable( 'eZDFSClusteringSettings', 'DBPort' );
            self::$dbparams['socket']     = $fileINI->variable( 'eZDFSClusteringSettings', 'DBSocket' );
            self::$dbparams['dbname']     = $fileINI->variable( 'eZDFSClusteringSettings', 'DBName' );
            self::$dbparams['user']       = $fileINI->variable( 'eZDFSClusteringSettings', 'DBUser' );
            self::$dbparams['pass']       = $fileINI->variable( 'eZDFSClusteringSettings', 'DBPassword' );

            self::$dbparams['max_connect_tries'] = $fileINI->variable( 'eZDFSClusteringSettings', 'DBConnectRetries' );
            self::$dbparams['max_execute_tries'] = $fileINI->variable( 'eZDFSClusteringSettings', 'DBExecuteRetries' );

            self::$dbparams['sql_output'] = $siteINI->variable( "DatabaseSettings", "SQLOutput" ) == "enabled";

            self::$dbparams['cache_generation_timeout'] = $siteINI->variable( "ContentSettings", "CacheGenerationTimeout" );
        }

        $serverString = self::$dbparams['host'];
        if ( self::$dbparams['socket'] )
            $serverString .= ':' . self::$dbparams['socket'];
        elseif ( self::$dbparams['port'] )
            $serverString .= ':' . self::$dbparams['port'];

        $maxTries = self::$dbparams['max_connect_tries'];
        $tries = 0;
        while ( $tries < $maxTries )
        {
            if ( $this->db = mysql_connect( $serverString, self::$dbparams['user'], self::$dbparams['pass'] ) )
                break;
            ++$tries;
        }
        if ( !$this->db )
            throw new eZClusterHandlerDBNoConnectionException( $serverString, self::$dbparams['user'], self::$dbparams['pass'] );

        if ( !mysql_select_db( self::$dbparams['dbname'], $this->db ) )
            throw new eZClusterHandlerDBNoDatabaseException( self::$dbparams['dbname'] );

        // DFS setup
        if ( $this->dfsbackend === null )
        {
            $this->dfsbackend = new eZDFSFileHandlerDFSBackend();
        }
    }

    /**
     * Creates a copy of a file in DB+DFS
     * @param string $srcFilePath Source file
     * @param string $dstFilePath Destination file
     * @param string $fname
     * @return bool
     *
     * @see _copyInner
     **/
    public function _copy( $srcFilePath, $dstFilePath, $fname = false )
    {
        if ( $fname )
            $fname .= "::_copy($srcFilePath, $dstFilePath)";
        else
            $fname = "_copy($srcFilePath, $dstFilePath)";

        // fetch source file metadata
        $metaData = $this->_fetchMetadata( $srcFilePath, $fname );
        // if source file does not exist then do nothing.
        // @todo Throw an exception here.
        //       Info: $srcFilePath
        if ( !$metaData )
        {
            return false;
        }
        return $this->_protect( array( $this, "_copyInner" ), $fname,
                                $srcFilePath, $dstFilePath, $fname, $metaData );
    }

    /**
     * Inner function used by _copy to perform the operation in a transaction
     *
     * @param string $srcFilePath
     * @param string $dstFilePath
     * @param bool   $fname
     * @param array  $metaData Source file's metadata
     * @return bool
     *
     * @see _copy
     */
    private function _copyInner( $srcFilePath, $dstFilePath, $fname, $metaData )
    {
        $this->_delete( $dstFilePath, true, $fname );

        $datatype        = $metaData['datatype'];
        $filePathHash    = md5( $dstFilePath );
        $scope           = $metaData['scope'];
        $contentLength   = $metaData['size'];
        $fileMTime       = $metaData['mtime'];
        $nameTrunk       = self::nameTrunk( $dstFilePath, $scope );

        // Copy file metadata.
        if ( $this->_insertUpdate( self::TABLE_METADATA,
                                   array( 'datatype'=> $datatype,
                                          'name' => $dstFilePath,
                                          'name_trunk' => $nameTrunk,
                                          'name_hash' => $filePathHash,
                                          'scope' => $scope,
                                          'size' => $contentLength,
                                          'mtime' => $fileMTime,
                                          'expired' => ($fileMTime < 0) ? 1 : 0 ),
                                   "datatype=VALUES(datatype), scope=VALUES(scope), size=VALUES(size), mtime=VALUES(mtime), expired=VALUES(expired)",
                                   $fname ) === false )
        {
            return $this->_fail( $srcFilePath, "Failed to insert file metadata on copying." );
        }

        // Copy file data.
        if ( !$this->dfsbackend->copyFromDFSToDFS( $srcFilePath, $dstFilePath ) )
        {
            return $this->_fail( $srcFilePath, "Failed to copy DFS://$srcFilePath to DFS://$dstFilePath" );
        }
        return true;
    }

    /**
 	 * Purges meta-data and file-data for a file entry
 	 *
 	 * Will only expire a single file. Use _purgeByLike to purge multiple files
 	 *
 	 * @param string $filePath Path of the file to purge
 	 * @param bool $onlyExpired Only purges expired files
 	 * @param bool|int $expiry
 	 * @param bool $fname
 	 *
 	 * @see _purgeByLike
     **/
    public function _purge( $filePath, $onlyExpired = false, $expiry = false, $fname = false )
    {
        if ( $fname )
            $fname .= "::_purge($filePath)";
        else
            $fname = "_purge($filePath)";
        $sql = "DELETE FROM " . self::TABLE_METADATA . " WHERE name_hash=" . $this->_md5( $filePath );
        if ( $expiry !== false )
        {
            $sql .= " AND mtime<" . (int)$expiry;
        }
        elseif ( $onlyExpired )
        {
            $sql .= " AND expired=1";
        }
        if ( !$this->_query( $sql, $fname ) )
        {
            return $this->_fail( "Purging file metadata for $filePath failed" );
        }
        if ( mysql_affected_rows() == 1 )
        {
            $this->dfsbackend->delete( $filePath );
        }
        return true;
    }

    /**
     * Purges meta-data and file-data for files matching a pattern using a SQL
     * LIKE syntax.
     *
     * @param string $like
     *        SQL LIKE string applied to ezdfsfile.name to look for files to
     *        purge
     * @param bool $onlyExpired
     *        Only purge expired files (ezdfsfile.expired = 1)
     * @param integer $limit Maximum number of items to purge in one call
     * @param integer $expiry
     *        Timestamp used to limit deleted files: only files older than this
     *        date will be deleted
     * @param mixed $fname Optional caller name for debugging
     * @see _purge
     * @return bool|int false if it fails, number of affected rows otherwise
     * @todo This method should also remove the files from disk
     */
    public function _purgeByLike( $like, $onlyExpired = false, $limit = 50, $expiry = false, $fname = false )
    {
        if ( $fname )
            $fname .= "::_purgeByLike($like, $onlyExpired)";
        else
            $fname = "_purgeByLike($like, $onlyExpired)";

        // common query part used for both DELETE and SELECT
        $where = " WHERE name LIKE " . $this->_quote( $like );

        if ( $expiry !== false )
            $where .= " AND mtime < " . (int)$expiry;
        elseif ( $onlyExpired )
            $where .= " AND expired = 1";

        if ( $limit )
            $sqlLimit = " LIMIT $limit";
        else
            $sqlLimit = "";

        $this->_begin( $fname );

        // select query, in FOR UPDATE mode
        $selectSQL = "SELECT name FROM " . self::TABLE_METADATA .
                     "{$where} {$sqlLimit} FOR UPDATE";
        if ( !$res = $this->_query( $selectSQL, $fname ) )
        {
            $this->_rollback( $fname );
            return $this->_fail( "Selecting file metadata by like statement $like failed" );
        }
        $resultCount = mysql_num_rows( $res );

        // if there are no results, we can just return 0 and stop right here
        if ( $resultCount == 0 )
        {
            $this->_rollback( $fname );
            return 0;
        }
        // the candidate for purge are indexed in an array
        else
        {
            for ( $i = 0; $i < $resultCount; $i++ )
            {
                $row = mysql_fetch_assoc( $res );
                $files[] = $row['name'];
            }
        }

        // delete query
        $deleteSQL = "DELETE FROM " . self::TABLE_METADATA . " {$where} {$sqlLimit}";
        if ( !$res = $this->_query( $deleteSQL, $fname ) )
        {
            $this->_rollback( $fname );
            return $this->_fail( "Purging file metadata by like statement $like failed" );
        }
        $deletedDBFiles = mysql_affected_rows( $this->db );
        $this->dfsbackend->delete( $files );

        $this->_commit( $fname );

        return $deletedDBFiles;
    }

    /**
     * Deletes a file from DB
     *
     * The file won't be removed from disk, _purge has to be used for this.
     * Only single files will be deleted, to delete multiple files,
     * _deleteByLike has to be used.
     *
     * @param string $filePath Path of the file to delete
     * @param bool $insideOfTransaction
     *        Wether or not a transaction is already started
     * @param bool|string $fname Optional caller name for debugging
     * @see _deleteInner
     * @see _deleteByLike
     * @return bool
     */
    public function _delete( $filePath, $insideOfTransaction = false, $fname = false )
    {
        if ( $fname )
            $fname .= "::_delete($filePath)";
        else
            $fname = "_delete($filePath)";
        // @todo Check if this is requried: _protec will already take care of
        //       checking if a transaction is running. But leave it like this
        //       for now.
        if ( $insideOfTransaction )
        {
            $res = $this->_deleteInner( $filePath, $fname );
            if ( !$res || $res instanceof eZMySQLBackendError )
            {
                $this->_handleErrorType( $res );
            }
        }
        else
        {
            return $this->_protect( array( $this, '_deleteInner' ), $fname,
                                    $filePath, $insideOfTransaction, $fname );
        }
    }

    /**
     * Callback method used by by _delete to delete a single file
     *
     * @param string $filePath Path of the file to delete
     * @param string $fname Optional caller name for debugging
     * @return bool
     **/
    protected function _deleteInner( $filePath, $fname )
    {
        if ( !$this->_query( "UPDATE " . self::TABLE_METADATA . " SET mtime=-ABS(mtime), expired=1 WHERE name_hash=" . $this->_md5( $filePath ), $fname ) )
            return $this->_fail( "Deleting file $filePath failed" );
        return true;
    }

    /**
     * Deletes multiple files using a SQL LIKE statement
     *
     * Use _delete if you need to delete single files
     *
     * @param string $like
     *        SQL LIKE condition applied to ezdfsfile.name to look for files
     *        to delete. Will use name_trunk if the LIKE string matches a
     *        filetype that supports name_trunk.
     * @param string $fname Optional caller name for debugging
     * @return bool
     * @see _deleteByLikeInner
     * @see _delete
     */
    public function _deleteByLike( $like, $fname = false )
    {
        if ( $fname )
            $fname .= "::_deleteByLike($like)";
        else
            $fname = "_deleteByLike($like)";
        return $this->_protect( array( $this, '_deleteByLikeInner' ), $fname,
                                $like, $fname );
    }

    /**
     * Callback used by _deleteByLike to perform the deletion
     *
     * @param string $like
     * @param mixed $fname
     * @return
     */
    private function _deleteByLikeInner( $like, $fname )
    {
        $sql = "UPDATE " . self::TABLE_METADATA . " SET mtime=-ABS(mtime), expired=1\nWHERE name like ". $this->_quote( $like );
        if ( !$res = $this->_query( $sql, $fname ) )
        {
            return $this->_fail( "Failed to delete files by like: '$like'" );
        }
        return true;
    }

    /**
     * Deletes DB files by using a SQL regular expression applied to file names
     *
     * @param string $regex
     * @param mixed $fname
     * @return bool
     * @deprecated Has severe performance issues
     */
    public function _deleteByRegex( $regex, $fname = false )
    {
        if ( $fname )
            $fname .= "::_deleteByRegex($regex)";
        else
            $fname = "_deleteByRegex($regex)";
        return $this->_protect( array( $this, '_deleteByRegexInner' ), $fname,
                                $regex, $fname );
    }

    /**
     * Callback used by _deleteByRegex to perform the deletion
     *
     * @param mixed $regex
     * @param mixed $fname
     * @return
     * @deprecated Has severe performances issues
     */
    public function _deleteByRegexInner( $regex, $fname )
    {
        $sql = "UPDATE " . self::TABLE_METADATA . " SET mtime=-ABS(mtime), expired=1\nWHERE name REGEXP " . $this->_quote( $regex );
        if ( !$res = $this->_query( $sql, $fname ) )
        {
            return $this->_fail( "Failed to delete files by regex: '$regex'" );
        }
        return true;
    }

    /**
     * Deletes multiple DB files by wildcard
     *
     * @param string $wildcard
     * @param mixed $fname
     * @return bool
     * @deprecated Has severe performance issues
     */
    public function _deleteByWildcard( $wildcard, $fname = false )
    {
        if ( $fname )
            $fname .= "::_deleteByWildcard($wildcard)";
        else
            $fname = "_deleteByWildcard($wildcard)";
        return $this->_protect( array( $this, '_deleteByWildcardInner' ), $fname,
                                $wildcard, $fname );
    }

    /**
     * Callback used by _deleteByWildcard to perform the deletion
     *
     * @param mixed $wildcard
     * @param mixed $fname
     * @return bool
     * @deprecated Has severe performance issues
     */
    protected function _deleteByWildcardInner( $wildcard, $fname )
    {
        // Convert wildcard to regexp.
        $regex = '^' . mysql_real_escape_string( $wildcard ) . '$';

        $regex = str_replace( array( '.'  ),
                              array( '\.' ),
                              $regex );

        $regex = str_replace( array( '?', '*',  '{', '}', ',' ),
                              array( '.', '.*', '(', ')', '|' ),
                              $regex );

        $sql = "UPDATE " . self::TABLE_METADATA . " SET mtime=-ABS(mtime), expired=1\nWHERE name REGEXP '$regex'";
        if ( !$res = $this->_query( $sql, $fname ) )
        {
            return $this->_fail( "Failed to delete files by wildcard: '$wildcard'" );
        }
        return true;
    }

    public function _deleteByDirList( $dirList, $commonPath, $commonSuffix, $fname = false )
    {
        if ( $fname )
            $fname .= "::_deleteByDirList($dirList, $commonPath, $commonSuffix)";
        else
            $fname = "_deleteByDirList($dirList, $commonPath, $commonSuffix)";
        return $this->_protect( array( $this, '_deleteByDirListInner' ), $fname,
                                $dirList, $commonPath, $commonSuffix, $fname );
    }

    protected function _deleteByDirListInner( $dirList, $commonPath, $commonSuffix, $fname )
    {
        foreach ( $dirList as $dirItem )
        {
            if ( strstr( $commonPath, '/cache/content' ) !== false or strstr( $commonPath, '/cache/template-block' ) !== false )
            {
                $where = "WHERE name_trunk = '$commonPath/$dirItem/$commonSuffix'";
            }
            else
            {
                $where = "WHERE name LIKE '$commonPath/$dirItem/$commonSuffix%'";
            }
            $sql = "UPDATE " . self::TABLE_METADATA . " SET mtime=-ABS(mtime), expired=1\n$where";
            if ( !$res = $this->_query( $sql, $fname ) )
            {
                eZDebug::writeError( "Failed to delete files in dir: '$commonPath/$dirItem/$commonSuffix%'", __METHOD__ );
            }
        }
        return true;
    }

    public function _exists( $filePath, $fname = false, $ignoreExpiredFiles = true )
    {
        if ( $fname )
            $fname .= "::_exists($filePath)";
        else
            $fname = "_exists($filePath)";
        $row = $this->_selectOneRow( "SELECT name, mtime FROM " . self::TABLE_METADATA . " WHERE name_hash=" . $this->_md5( $filePath ),
                                     $fname, "Failed to check file '$filePath' existance: ", true );
        if ( $row === false )
            return false;

        if ( $ignoreExpiredFiles )
            $rc = $row[1] >= 0;
        else
            $rc = true;

        return $rc;
    }

    protected function __mkdir_p( $dir )
    {
        // create parent directories
        $dirElements = explode( '/', $dir );
        if ( count( $dirElements ) == 0 )
            return true;

        $result = true;
        $currentDir = $dirElements[0];

        if ( $currentDir != '' && !file_exists( $currentDir ) && !eZDir::mkdir( $currentDir, false ) )
            return false;

        for ( $i = 1; $i < count( $dirElements ); ++$i )
        {
            $dirElement = $dirElements[$i];
            if ( strlen( $dirElement ) == 0 )
                continue;

            $currentDir .= '/' . $dirElement;

            if ( !file_exists( $currentDir ) && !eZDir::mkdir( $currentDir, false ) )
                return false;

            $result = true;
        }

        return $result;
    }

    /**
    * Fetches the file $filePath from the database to its own name
    *
    * Saving $filePath locally with its original name, or $uniqueName if given
    *
    * @param string $filePath
    * @param string $uniqueName Alternative name to save the file to
    * @return string|bool the file physical path, or false if fetch failed
    **/
    public function _fetch( $filePath, $uniqueName = false )
    {
        $metaData = $this->_fetchMetadata( $filePath );
        if ( !$metaData )
        {
            // @todo Throw an exception
            eZDebug::writeError( "File '$filePath' does not exist while trying to fetch.", __METHOD__ );
            return false;
        }
        $contentLength = $metaData['size'];

        // create temporary file
        if ( strrpos( $filePath, '.' ) > 0 )
            $tmpFilePath = substr_replace( $filePath, getmypid().'tmp', strrpos( $filePath, '.' ), 0  );
        else
            $tmpFilePath = $filePath . '.' . getmypid().'tmp';
        $this->__mkdir_p( dirname( $tmpFilePath ) );

        // copy DFS file to temporary FS path
        // @todo Throw an exception
        if ( !$this->dfsbackend->copyFromDFS( $filePath, $tmpFilePath ) )
        {
            eZDebug::writeError("Failed copying DFS://$filePath to FS://$tmpFilePath ");
            return false;
        }

        // Make sure all data is written correctly
        clearstatcache();
        $tmpSize = filesize( $tmpFilePath );
        // @todo Throw an exception
        if ( $tmpSize != $metaData['size'] )
        {
            eZDebug::writeError( "Size ($tmpSize) of written data for file '$tmpFilePath' does not match expected size " . $metaData['size'], __METHOD__ );
            return false;
        }

        if ( $uniqueName !== true )
        {
            eZFile::rename( $tmpFilePath, $filePath );
        }
        else
        {
            $filePath = $tmpFilePath;
        }

        return $filePath;
    }

    public function _fetchContents( $filePath, $fname = false )
    {
        if ( $fname )
            $fname .= "::_fetchContents($filePath)";
        else
            $fname = "_fetchContents($filePath)";
        $metaData = $this->_fetchMetadata( $filePath, $fname );
        // @todo Throw an exception
        if ( !$metaData )
        {
            eZDebug::writeError( "File '$filePath' does not exist while trying to fetch its contents.", __METHOD__ );
            return false;
        }
        $contentLength = $metaData['size'];

        // @todo Catch an exception
        if ( !$contents = $this->dfsbackend->getContents( $filePath ) )
        {
            eZDebug::writeError("An error occured while reading contents of DFS://$filePath", __METHOD__ );
            return false;
        }
        return $contents;
    }

    /**
    * Fetches and returns metadata for $filePath
    * @return array|false file metadata, or false if the file does not exist in
    *                     database.
     */
    function _fetchMetadata( $filePath, $fname = false )
    {
        if ( $fname )
            $fname .= "::_fetchMetadata($filePath)";
        else
            $fname = "_fetchMetadata($filePath)";
        $sql = "SELECT * FROM " . self::TABLE_METADATA . " WHERE name_hash=" . $this->_md5( $filePath );
        return $this->_selectOneAssoc( $sql, $fname,
                                       "Failed to retrieve file metadata: $filePath",
                                       true );
    }

    public function _linkCopy( $srcPath, $dstPath, $fname = false )
    {
        if ( $fname )
            $fname .= "::_linkCopy($srcPath,$dstPath)";
        else
            $fname = "_linkCopy($srcPath,$dstPath)";
        return $this->_copy( $srcPath, $dstPath, $fname );
    }

    /**
    * Passes $filePath content through
    * @param string $filePath
    * @deprecated should not be used since it cannot handle reading errors
    **/
    public function _passThrough( $filePath, $fname = false )
    {
        if ( $fname )
            $fname .= "::_passThrough($filePath)";
        else
            $fname = "_passThrough($filePath)";

        $metaData = $this->_fetchMetadata( $filePath, $fname );
        // @todo Throw an exception
        if ( !$metaData )
            return false;

        // @todo Catch an exception
        $this->dfsbackend->passthrough( $filePath );

        return true;
    }

    /**
     * Renames $srcFilePath to $dstFilePath
     *
     * @param string $srcFilePath
     * @param string $dstFilePath
     * @return bool
     */
    public function _rename( $srcFilePath, $dstFilePath )
    {
        if ( strcmp( $srcFilePath, $dstFilePath ) == 0 )
            return;

        // fetch source file metadata
        $metaData = $this->_fetchMetadata( $srcFilePath );
        // if source file does not exist then do nothing.
        // @todo Throw an exception
        if ( !$metaData )
            return false;

        $this->_begin( __METHOD__ );

        $srcFilePathStr  = mysql_real_escape_string( $srcFilePath );
        $dstFilePathStr  = mysql_real_escape_string( $dstFilePath );
        $dstNameTrunkStr = mysql_real_escape_string( self::nameTrunk( $dstFilePath, $metaData['scope'] ) );

        // Mark entry for update to lock it
        $sql = "SELECT * FROM " . self::TABLE_METADATA . " WHERE name_hash=MD5('$srcFilePathStr') FOR UPDATE";
        if ( !$this->_query( $sql, "_rename($srcFilePath, $dstFilePath)" ) )
        {
            // @todo Throw an exception
            eZDebug::writeError( "Failed locking file '$srcFilePath'", __METHOD__ );
            $this->_rollback( __METHOD__ );
            return false;
        }

        if ( $this->_exists( $dstFilePath, false, false ) )
            $this->_purge( $dstFilePath, false );

        // Create a new meta-data entry for the new file to make foreign keys happy.
        $sql = "INSERT INTO " . self::TABLE_METADATA . " (name, name_trunk, name_hash, datatype, scope, size, mtime, expired) SELECT '$dstFilePathStr' AS name, '$dstNameTrunkStr' as name_trunk, MD5('$dstFilePathStr') AS name_hash, datatype, scope, size, mtime, expired FROM " . self::TABLE_METADATA . " WHERE name_hash=MD5('$srcFilePathStr')";
        if ( !$this->_query( $sql, "_rename($srcFilePath, $dstFilePath)" ) )
        {
            eZDebug::writeError( "Failed making new file entry '$dstFilePath'", __METHOD__ );
            $this->_rollback( __METHOD__ );
            // @todo Throw an exception
            return false;
        }

        if ( !$this->dfsbackend->copyFromDFSToDFS( $srcFilePath, $dstFilePath ) )
        {
            return $this->_fail( "Failed to copy DFS://$srcFilePath to DFS://$dstFilePath" );
        }

        // Remove old entry
        $sql = "DELETE FROM " . self::TABLE_METADATA . " WHERE name_hash=MD5('$srcFilePathStr')";
        if ( !$this->_query( $sql, "_rename($srcFilePath, $dstFilePath)" ) )
        {
            eZDebug::writeError( "Failed removing old file '$srcFilePath'", __METHOD__ );
            $this->_rollback( __METHOD__ );
            // @todo Throw an exception
            return false;
        }

        // delete original DFS file
        // @todo Catch an exception
        $this->dfsbackend->delete( $srcFilePath );

        $this->_commit( __METHOD__ );

        return true;
    }

    /**
     * Stores $filePath to cluster
     *
     * @param string $filePath
     * @param string $datatype
     * @param string $scope
     * @param string $fname
     * @return void
     */
    function _store( $filePath, $datatype, $scope, $fname = false )
    {
        if ( !is_readable( $filePath ) )
        {
            eZDebug::writeError( "Unable to store file '$filePath' since it is not readable.", __METHOD__ );
            return;
        }
        if ( $fname )
            $fname .= "::_store($filePath, $datatype, $scope)";
        else
            $fname = "_store($filePath, $datatype, $scope)";

        $this->_protect( array( $this, '_storeInner' ), $fname,
                         $filePath, $datatype, $scope, $fname );
    }

    /**
     * Callback function used to perform the actual file store operation
     * @param string $filePath
     * @param string $datatype
     * @param string $scope
     * @param string $fname
     * @see eZDFSFileHandlerMySQLBackend::_store()
     * @return bool
     **/
    function _storeInner( $filePath, $datatype, $scope, $fname )
    {
        // Insert file metadata.
        clearstatcache();
        $fileMTime = filemtime( $filePath );
        $contentLength = filesize( $filePath );
        $filePathHash = md5( $filePath );
        $nameTrunk = self::nameTrunk( $filePath, $scope );

        if ( $this->_insertUpdate( self::TABLE_METADATA,
            array( 'datatype' => $datatype,
                   'name' => $filePath,
                   'name_trunk' => $nameTrunk,
                   'name_hash' => $filePathHash,
                   'scope' => $scope,
                   'size' => $contentLength,
                   'mtime' => $fileMTime,
                   'expired' => ( $fileMTime < 0 ) ? 1 : 0 ),
            "datatype=VALUES(datatype), scope=VALUES(scope), size=VALUES(size), mtime=VALUES(mtime), expired=VALUES(expired)",
            $fname ) === false )
        {
            return $this->_fail( "Failed to insert file metadata while storing. Possible race condition" );
        }

        // copy given $filePath to DFS
        if ( !$this->dfsbackend->copyToDFS( $filePath ) )
        {
            return $this->_fail( "Failed to copy FS://$filePath to DFS://$filePath" );
        }

        return true;
    }

    /**
     * Stores $contents as the contents of $filePath to the cluster
     *
     * @param string $filePath
     * @param string $contents
     * @param string $scope
     * @param string $datatype
     * @param int $mtime
     * @param string $fname
     * @return void
     */
    function _storeContents( $filePath, $contents, $scope, $datatype, $mtime = false, $fname = false )
    {
        if ( $fname )
            $fname .= "::_storeContents($filePath, ..., $scope, $datatype)";
        else
            $fname = "_storeContents($filePath, ..., $scope, $datatype)";

        $this->_protect( array( $this, '_storeContentsInner' ), $fname,
                         $filePath, $contents, $scope, $datatype, $mtime, $fname );
    }

    function _storeContentsInner( $filePath, $contents, $scope, $datatype, $curTime, $fname )
    {
        // Insert file metadata.
        $contentLength = strlen( $contents );
        $filePathHash = md5( $filePath );
        $nameTrunk = self::nameTrunk( $filePath, $scope );
        if ( $curTime === false )
            $curTime = time();

        if ( $this->_insertUpdate( self::TABLE_METADATA,
            array( 'datatype' => $datatype,
                   'name' => $filePath,
                   'name_trunk' => $nameTrunk,
                   'name_hash' => $filePathHash,
                   'scope' => $scope,
                   'size' => $contentLength,
                   'mtime' => $curTime,
                   'expired' => ( $curTime < 0 ) ? 1 : 0 ),
            "datatype=VALUES(datatype), name_trunk='$nameTrunk', scope=VALUES(scope), size=VALUES(size), mtime=VALUES(mtime), expired=VALUES(expired)",
            $fname ) === false )
        {
            return $this->_fail( "Failed to insert file metadata while storing contents. Possible race condition" );
        }

        if ( !$this->dfsbackend->createFileOnDFS( $filePath, $contents ) )
        {
            return $this->_fail( "Failed to open DFS://$filePath for writing" );
        }

        return true;
    }

    public function _getFileList( $scopes = false, $excludeScopes = false )
    {
        $query = 'SELECT name FROM ' . self::TABLE_METADATA;

        if ( is_array( $scopes ) && count( $scopes ) > 0 )
        {
            $query .= ' WHERE scope ';
            if ( $excludeScopes )
                $query .= 'NOT ';
            $query .= "IN ('" . implode( "', '", $scopes ) . "')";
        }

        $rslt = $this->_query( $query, "_getFileList( array( " . implode( ', ', $scopes ) . " ), $excludeScopes )" );
        if ( !$rslt )
        {
            eZDebug::writeDebug( 'Unable to get file list', __METHOD__ );
            // @todo Throw an exception
            return false;
        }

        $filePathList = array();
        while ( $row = mysql_fetch_row( $rslt ) )
            $filePathList[] = $row[0];

        return $filePathList;
    }

    /**
    * Handles a DB error, displaying it as an eZDebug error
    * @see eZDebug::writeError
    * @param string $msg Message to display
    * @param string $sql SQL query to display error for
    * @return void
    **/
	protected function _die( $msg, $sql = null )
    {
        if ( $this->db )
        {
            eZDebug::writeError( $sql, "$msg" . mysql_error( $this->db ) );
        }
        else
        {
            eZDebug::writeError( $sql, "$msg: " . mysql_error() );
        }
    }

    /**
    * Performs an insert of the given items in $array.
    * @param string $table Name of table to execute insert on.
    * @param array $array Associative array with data to insert, the keys are
    *                     the field names and the values will be quoted
    *                     according to type.
    * @param string $fname Name of caller function (for logging purpuse)
    **/
    function _insert( $table, $array, $fname )
    {
        $keys = array_keys( $array );
        $query = "INSERT INTO $table (" . join( ", ", $keys ) . ") VALUES (" . $this->_sqlList( $array ) . ")";
        $res = $this->_query( $query, $fname );
        if ( !$res )
        {
            // @todo Throw an exception
            return false;
        }
        return mysql_insert_id( $this->db );
    }

    /**
    * Performs an insert of the given items in $array.
    *
    * If entry specified already exists the $update SQL is executed to update
    * the entry instead.
    *
    * @param string $table Name of table to execute insert on.
    * @param array  array $array Associative array with data to insert, the keys
    *                     are the field names and the values will be quoted
    *                     according to type.
    * @param string $update Partial update SQL which is executed when entry
    *                       exists.
	* @param string $fname Name of caller function (for logging purpuse)
	**/
    protected function _insertUpdate( $table, $array, $update, $fname, $reportError = true )
    {
        $keys = array_keys( $array );
        $query = "INSERT INTO $table (" . join( ", ", $keys ) . ") VALUES (" . $this->_sqlList( $array ) . ")\nON DUPLICATE KEY UPDATE $update";
        $res = $this->_query( $query, $fname, $reportError );
        if ( !$res )
        {
            // @todo Throw an exception
            return false;
        }
        return mysql_insert_id( $this->db );
    }

    /**
    * Formats a list of entries as an SQL list which is separated by commas.
    * Each entry in the list is quoted using _quote().
    *
    * @param array $array
    * @return array
    **/
    protected function _sqlList( $array )
    {
        $text = "";
        $sep = "";
        foreach ( $array as $e )
        {
            $text .= $sep;
            $text .= $this->_quote( $e );
            $sep = ", ";
        }
        return $text;
    }

    /**
    * Runs a select query and returns one numeric indexed row from the result
    * If there are more than one row it will fail and exit, if 0 it returns
    * false.
    *
    * @param string $query
    * @param string $fname The function name that started the query, should
    *                      contain relevant arguments in the text.
    * @param string $error Sent to _error() in case of errors
    * @param bool   $debug If true it will display the fetched row in addition
    *                      to the SQL.
    * @return array|false
    **/
    protected function _selectOneRow( $query, $fname, $error = false, $debug = false )
    {
        return $this->_selectOne( $query, $fname, $error, $debug, "mysql_fetch_row" );
    }

	/**
	 * Runs a select query and returns one associative row from the result.
	 *
	 * If there are more than one row it will fail and exit, if 0 it returns
	 * false.
	 *
	 * @param string $query
	 * @param string $fname The function name that started the query, should
	 *                      contain relevant arguments in the text.
	 * @param string $error Sent to _error() in case of errors
	 * @param bool   $debug If true it will display the fetched row in addition
	 *                      to the SQL.
	 * @return array|false
	 **/
	protected function _selectOneAssoc( $query, $fname, $error = false, $debug = false )
    {
        return $this->_selectOne( $query, $fname, $error, $debug, "mysql_fetch_assoc" );
    }

    /**
    * Runs a select query, applying the $fetchCall callback to one result
    * If there are more than one row it will fail and exit, if 0 it returns false.
    *
    * @param string $fname The function name that started the query, should
    *                      contain relevant arguments in the text.
    * @param string $error Sent to _error() in case of errors
    * @param bool $debug If true it will display the fetched row in addition to the SQL.
    * @param callback $fetchCall The callback to fetch the row.
    * @return mixed
    **/
    protected function _selectOne( $query, $fname, $error = false, $debug = false, $fetchCall )
    {
        eZDebug::accumulatorStart( 'mysql_cluster_query', 'MySQL Cluster', 'DB queries' );
        $time = microtime( true );

        $res = mysql_query( $query, $this->db );
        if ( !$res )
        {
            if ( mysql_errno( $this->db ) == 1146 )
            {
                throw new eZDFSFileHandlerTableNotFoundException(
                    $query, mysql_error( $this->db ) );
            }
            else
            {
                $this->_error( $query, $fname, $error );
                eZDebug::accumulatorStop( 'mysql_cluster_query' );
                // @todo Throw an exception
                return false;
            }
        }

        $nRows = mysql_num_rows( $res );
        if ( $nRows > 1 )
        {
            eZDebug::writeError( 'Duplicate entries found', $fname );
            eZDebug::accumulatorStop( 'mysql_cluster_query' );
            // @todo throw an exception instead. Should NOT happen.
        }

        $row = $fetchCall( $res );
        mysql_free_result( $res );
        if ( $debug )
            $query = "SQL for _selectOneAssoc:\n" . $query . "\n\nRESULT:\n" . var_export( $row, true );

        $time = microtime( true ) - $time;
        eZDebug::accumulatorStop( 'mysql_cluster_query' );

        $this->_report( $query, $fname, $time );
        return $row;
    }

    /**
    * Starts a new transaction by executing a BEGIN call.
    * If a transaction is already started nothing is executed.
    **/
    protected function _begin( $fname = false )
    {
        if ( $fname )
            $fname .= "::_begin";
        else
            $fname = "_begin";
        $this->transactionCount++;
        if ( $this->transactionCount == 1 )
            $this->_query( "BEGIN", $fname );
    }

    /**
    * Stops a current transaction and commits the changes by executing a COMMIT call.
    * If the current transaction is a sub-transaction nothing is executed.
    **/
    protected function _commit( $fname = false )
    {
        if ( $fname )
            $fname .= "::_commit";
        else
            $fname = "_commit";
        $this->transactionCount--;
        if ( $this->transactionCount == 0 )
            $this->_query( "COMMIT", $fname );
    }

    /**
    * Stops a current transaction and discards all changes by executing a
    * ROLLBACK call.
    * If the current transaction is a sub-transaction nothing is executed.
    **/
    protected function _rollback( $fname = false )
    {
        if ( $fname )
            $fname .= "::_rollback";
        else
            $fname = "_rollback";
        $this->transactionCount--;
        if ( $this->transactionCount == 0 )
            $this->_query( "ROLLBACK", $fname );
    }

    /**
    * Protects a custom function with SQL queries in a database transaction.
    * If the function reports an error the transaction is ROLLBACKed.
    *
    * The first argument to the _protect() is the callback and the second is the
    * name of the function (for query reporting). The remainder of arguments are
    * sent to the callback.
    *
    * A return value of false from the callback is considered a failure, any
    * other value is returned from _protect(). For extended error handling call
    * _fail() and return the value.
    **/
    protected function _protect()
    {
        $args = func_get_args();
        $callback = array_shift( $args );
        $fname    = array_shift( $args );

        $maxTries = self::$dbparams['max_execute_tries'];
        $tries = 0;
        while ( $tries < $maxTries )
        {
            $this->_begin( $fname );

            $result = call_user_func_array( $callback, $args );

            $errno = mysql_errno( $this->db );
            if ( $errno == 1205 || // Error: 1205 SQLSTATE: HY000 (ER_LOCK_WAIT_TIMEOUT)
                 $errno == 1213 )  // Error: 1213 SQLSTATE: 40001 (ER_LOCK_DEADLOCK)
            {
                $tries++;
                $this->_rollback( $fname );
                continue;
            }

            // @todo replace with an exception
            if ( $result === false )
            {
                $this->_rollback( $fname );
                return false;
            }
            elseif ( $result instanceof eZMySQLBackendError )
            {
                eZDebug::writeError( $result->errorValue, $result->errorText );
                $this->_rollback( $fname );
                return false;
            }

            break; // All is good, so break out of loop
        }

        $this->_commit( $fname );
        return $result;
    }

    protected function _handleErrorType( $res )
    {
        if ( $res === false )
        {
            eZDebug::writeError( "SQL failed" );
        }
        elseif ( $res instanceof eZMySQLBackendError )
        {
            eZDebug::writeError( $res->errorValue, $res->errorText );
        }
    }

    /**
    * Checks if $result is a failure type and returns true if so, false
    * otherwise.
    *
    * A failure is either the value false or an error object of type
    * eZMySQLBackendError.
    **/
    protected function _isFailure( $result )
    {
        if ( $result === false || ($result instanceof eZMySQLBackendError ) )
        {
            return true;
        }
        return false;
    }

    /**
    * Creates an error object which can be read by some backend functions.
    * @param mixed $value The value which is sent to the debug system.
    * @param string $text The text/header for the value.
    **/
    protected function _fail( $value, $text = false )
    {
        $value .= "\n" . mysql_errno( $this->db ) . ": " . mysql_error( $this->db );
        return new eZMySQLBackendError( $value, $text );
    }

    /**
    * Performs mysql query and returns mysql result.
    * Times the sql execution, adds accumulator timings and reports SQL to
    * debug.
    * @param string $fname The function name that started the query, should
    *                      contain relevant arguments in the text.
    **/
    protected function _query( $query, $fname = false, $reportError = true )
    {
        eZDebug::accumulatorStart( 'mysql_cluster_query', 'MySQL Cluster', 'DB queries' );
        $time = microtime( true );

        $res = mysql_query( $query, $this->db );
        if ( !$res && $reportError )
        {
            $this->_error( $query, $fname );
        }

        $numRows = mysql_affected_rows( $this->db );

        $time = microtime( true ) - $time;
        eZDebug::accumulatorStop( 'mysql_cluster_query' );

        $this->_report( $query, $fname, $time, $numRows );
        return $res;
    }

    /**
    * Make sure that $value is escaped and qouted according to type and returned
    * as a string.
    *
    * @param string $value a SQL parameter to escape
    * @return string a string that can safely be used in SQL queries
    **/
    protected function _quote( $value )
    {
        if ( $value === null )
            return 'NULL';
    	elseif ( is_integer( $value ) )
            return (string)$value;
        else
            return "'" . mysql_real_escape_string( $value ) . "'";
    }

    /**
    * Provides the SQL calls to convert $value to MD5
    * The returned value can directly be put into SQLs.
    **/
    protected function _md5( $value )
    {
        return "MD5('" . mysql_real_escape_string( $value ) . "')";
    }

    /**
    * Prints error message $error to debug system.
    * @param string $query The query that was attempted, will be printed if
    *                      $error is \c false
    * @param string $fname The function name that started the query, should
    *                      contain relevant arguments in the text.
    * @param string $error The error message, if this is an array the first
    *                      element is the value to dump and the second the error
    *                      header (for eZDebug::writeNotice). If this is \c
    *                      false a generic message is shown.
     */
    protected function _error( $query, $fname, $error = "Failed to execute SQL for function:" )
    {
        if ( $error === false )
        {
            $error = "Failed to execute SQL for function:";
        }
        else if ( is_array( $error ) )
        {
            $fname = $error[1];
            $error = $error[0];
        }

        eZDebug::writeError( "$error\n" . mysql_errno( $this->db ) . ': ' . mysql_error( $this->db ), $fname );
    }

    /**
    * Report SQL $query to debug system.
    *
    * @param string $fname The function name that started the query, should contain relevant arguments in the text.
    * @param int    $timeTaken Number of seconds the query + related operations took (as float).
    * @param int $numRows Number of affected rows.
    **/
    function _report( $query, $fname, $timeTaken, $numRows = false )
    {
        if ( !self::$dbparams['sql_output'] )
            return;

        $rowText = '';
        if ( $numRows !== false )
            $rowText = "$numRows rows, ";
        static $numQueries = 0;
        if ( strlen( $fname ) == 0 )
            $fname = "_query";
        $backgroundClass = ($this->transactionCount > 0  ? "debugtransaction transactionlevel-$this->transactionCount" : "");
        eZDebug::writeNotice( "$query", "cluster::mysql::{$fname}[{$rowText}" . number_format( $timeTaken, 3 ) . " ms] query number per page:" . $numQueries++, $backgroundClass );
    }

    /**
    * Attempts to begin cache generation by creating a new file named as the
    * given filepath, suffixed with .generating. If the file already exists,
    * insertion is not performed and false is returned (means that the file
    * is already being generated)
    * @param string $filePath
    * @return array array with 2 indexes: 'result', containing either ok or ko,
    *         and another index that depends on the result:
    *         - if result == 'ok', the 'mtime' index contains the generating
    *           file's mtime
    *         - if result == 'ko', the 'remaining' index contains the remaining
    *           generation time (time until timeout) in seconds
    **/
    public function _startCacheGeneration( $filePath, $generatingFilePath )
    {
        $fname = "_startCacheGeneration( {$filePath} )";

        $nameHash = $this->_md5( $generatingFilePath );
        $mtime = time();

        $insertData = array( 'name' => "'" . mysql_real_escape_string( $generatingFilePath ) . "'",
                             'name_trunk' => "'" . mysql_real_escape_string( $generatingFilePath ) . "'",
                             'name_hash' => $nameHash,
                             'scope' => "''",
                             'datatype' => "''",
                             'mtime' => $mtime,
                             'expired' => 0 );
        $query = 'INSERT INTO ' . self::TABLE_METADATA . ' ( '. implode(', ', array_keys( $insertData ) ) . ' ) ' .
                 "VALUES(" . implode( ', ', $insertData ) . ")";

        if ( !$this->_query( $query, "_startCacheGeneration( $filePath )", false ) )
        {
            $errno = mysql_errno();
            if ( $errno != 1062 )
            {
                eZDebug::writeError( "Unexpected error #$errno when trying to start cache generation on $filePath (".mysql_error().")", __METHOD__ );
                eZDebug::writeDebug( $query, '$query' );

                // @todo Make this an actual error, maybe an exception
                return array( 'res' => 'ko' );
            }
            // error 1062 is expected, since it means duplicate key (file is being generated)
            else
            {
                // generation timout check
                $query = "SELECT mtime FROM " . self::TABLE_METADATA . " WHERE name_hash = {$nameHash}";
                $row = $this->_selectOneRow( $query, $fname, false, false );

                // file has been renamed, i.e it is no longer a .generating file
                if( $row and !isset( $row[0] ) )
                    return array( 'result' => 'ok', 'mtime' => $mtime );

                $remainingGenerationTime = $this->remainingCacheGenerationTime( $row );
                if ( $remainingGenerationTime < 0 )
                {
                    $previousMTime = $row[0];

                    eZDebugSetting::writeDebug( 'kernel-clustering', "$filePath generation has timedout, taking over", __METHOD__ );
                    $updateQuery = "UPDATE " . self::TABLE_METADATA . " SET mtime = {$mtime} WHERE name_hash = {$nameHash} AND mtime = {$previousMTime}";

                    // we run the query manually since the default _query won't
                    // report affected rows
                    $res = mysql_query( $updateQuery, $this->db );
                    if ( ( $res !== false ) and mysql_affected_rows( $this->db ) == 1 )
                    {
                        return array( 'result' => 'ok', 'mtime' => $mtime );
                    }
                    else
                    {
                        // @todo This would require an actual error handling
                        eZDebug::writeError( "An error occured taking over timedout generating cache file $generatingFilePath (".mysql_error().")", __METHOD__ );
                        return array( 'result' => 'error' );
                    }
                }
                else
                {
                    return array( 'result' => 'ko', 'remaining' => $remainingGenerationTime );
                }
            }
        }
        else
        {
            return array( 'result' => 'ok', 'mtime' => $mtime );
        }
    }

    /**
    * Ends the cache generation for the current file: moves the (meta)data for
    * the .generating file to the actual file, and removed the .generating
    * @param string $filePath
    * @return bool
    **/
    public function _endCacheGeneration( $filePath, $generatingFilePath, $rename )
    {
        $fname = "_endCacheGeneration( $filePath )";

        // no rename: the .generating entry is just deleted
        if ( $rename === false )
        {
            $this->_query( "DELETE FROM " . self::TABLE_METADATA . " WHERE name_hash=MD5('$generatingFilePath')", $fname, true );
            $this->dfsbackend->delete( $generatingFilePath );
            return true;
        }
        // rename mode: the generating file and its contents are renamed to the
        // final name
        else
        {
            $this->_begin( $fname );

            // both files are locked for update
            if ( !$res = $this->_query( "SELECT * FROM " . self::TABLE_METADATA . " WHERE name_hash=MD5('$generatingFilePath') FOR UPDATE", $fname, true ) )
            {
                $this->_rollback( $fname );
                // @todo Throw an exception
                return false;
            }
            $generatingMetaData = mysql_fetch_assoc( $res );

            // the original file does not exist: we move the generating file
            $res = $this->_query( "SELECT * FROM " . self::TABLE_METADATA . " WHERE name_hash=MD5('$filePath') FOR UPDATE", $fname, false );
            if ( mysql_num_rows( $res ) == 0 )
            {
                $metaData = $generatingMetaData;
                $metaData['name'] = $filePath;
                $metaData['name_hash'] = md5( $filePath );
                $metaData['name_trunk'] = $this->nameTrunk( $filePath, $metaData['scope'] );
                $insertSQL = "INSERT INTO " . self::TABLE_METADATA . " ( " . implode( ', ', array_keys( $metaData ) ) . " ) " .
                             "VALUES( " . $this->_sqlList( $metaData ) . ")";
                if ( !$this->_query( $insertSQL, $fname, true ) )
                {
                    eZDebug::writeError("An error occured creating the metadata entry for $filePath", $fname );
                    $this->_rollback( $fname );
                    // @todo Throw an exception
                    return false;
                }
                // here we rename the actual FILE. The .generating file has been
                // created on DFS, and should be renamed
                if ( !$this->dfsbackend->renameOnDFS( $generatingFilePath, $filePath ) )
                {
                    eZDebug::writeError("An error occured renaming DFS://$generatingFilePath to DFS://$filePath", $fname );
                    $this->_rollback( $fname );
                    // @todo Throw an exception
                    return false;
                }
                $this->_query( "DELETE FROM " . self::TABLE_METADATA . " WHERE name_hash=MD5('$generatingFilePath')", $fname, true );
            }
            // the original file exists: we move the generating data to this file
            // and update it
            else
            {
                if ( !$this->dfsbackend->renameOnDFS( $generatingFilePath, $filePath ) )
                {
                    eZDebug::writeError("An error occured renaming DFS://$generatingFilePath to DFS://$filePath", $fname );
                    $this->_rollback( $fname );
                    // @todo Throw an exception
                    return false;
                }

                $mtime = $generatingMetaData['mtime'];
                $filesize = $generatingMetaData['size'];
                if ( !$this->_query( "UPDATE " . self::TABLE_METADATA . " SET mtime = '{$mtime}', expired = 0, size = '{$filesize}' WHERE name_hash=MD5('$filePath')", $fname, true ) )
                {
                    $this->_rollback( $fname );
                    // @todo Throw an exception
                    return false;
                }
                $this->_query( "DELETE FROM " . self::TABLE_METADATA . " WHERE name_hash=MD5('$generatingFilePath')", $fname, true );
            }

            $this->_commit( $fname );
        }

        return true;
    }

    /**
    * Checks if generation has timed out by looking for the .generating file
    * and comparing its timestamp to the one assigned when the file was created
    *
    * @param string $generatingFilePath
    * @param int    $generatingFileMtime
    *
    * @return bool true if the file didn't timeout, false otherwise
    **/
    public function _checkCacheGenerationTimeout( $generatingFilePath, $generatingFileMtime )
    {
        $fname = "_checkCacheGenerationTimeout( $generatingFilePath, $generatingFileMtime )";

        // reporting
        eZDebug::accumulatorStart( 'mysql_cluster_query', 'MySQL Cluster', 'DB queries' );
        $time = microtime( true );

        $nameHash = $this->_md5( $generatingFilePath );
        $newMtime = time();

        // The update query will only succeed if the mtime wasn't changed in between
        $query = "UPDATE " . self::TABLE_METADATA . " SET mtime = $newMtime WHERE name_hash = {$nameHash} AND mtime = $generatingFileMtime";
        $res = mysql_query( $query, $this->db );
        if ( !$res )
        {
            // @todo Throw an exception
            $this->_error( $query, $fname );
            return false;
        }
        $numRows = mysql_affected_rows( $this->db );

        // reporting. Manual here since we don't use _query
        $time = microtime( true ) - $time;
        $this->_report( $query, $fname, $time, $numRows );

        // no rows affected or row updated with the same value
        // f.e a cache-block which takes less than 1 sec to get generated
        // if a line has been updated by the same  values, mysql_affected_rows
        // returns 0, and updates nothing, we need to extra check this,
        if( $numRows == 0 )
        {
            $query = "SELECT mtime FROM " . self::TABLE_METADATA . " WHERE name_hash = {$nameHash}";
            $res = mysql_query( $query, $this->db );
            mysql_fetch_row( $res );
            if ( $res and isset( $row[0] ) and $row[0] == $generatingFileMtime );
            {
                return true;
            }

            // @todo Check if an exception makes sense here
            return false;
        }
        // rows affected: mtime has changed, or row has been removed
        if ( $numRows == 1 )
        {
            return true;
        }
        else
        {
            eZDebugSetting::writeDebug( 'kernel-clustering', "No rows affected by query '$query', record has been modified", __METHOD__ );
            return false;
        }
    }

    /**
    * Aborts the cache generation process by removing the .generating file
    * @param string $filePath Real cache file path
    * @param string $generatingFilePath .generating cache file path
    * @return void
    **/
    public function _abortCacheGeneration( $generatingFilePath )
    {
        $fname = "_abortCacheGeneration( $generatingFilePath )";

        $this->_begin( $fname );

        $sql = "DELETE FROM " . self::TABLE_METADATA . " WHERE name_hash = " . $this->_md5( $generatingFilePath );
        $this->_query( $sql, "_abortCacheGeneration( '$generatingFilePath' )" );
        $this->dfsbackend->delete( $generatingFilePath );

        $this->_commit( $fname );
    }

    /**
    * Returns the name_trunk for a file path
    * @param string $filePath
    * @param string $scope
    * @return string
    **/
    static protected function nameTrunk( $filePath, $scope )
    {
        switch ( $scope )
        {
            case 'viewcache':
            {
                $nameTrunk = substr( $filePath, 0, strrpos( $filePath, '-' ) + 1 );
            } break;

            case 'template-block':
            {
                $templateBlockCacheDir = eZTemplateCacheBlock::templateBlockCacheDir();
                $templateBlockPath = str_replace( $templateBlockCacheDir, '', $filePath );
                if ( strstr( $templateBlockPath, 'subtree/' ) !== false )
                {
                    // 6 = strlen( 'cache/' );
                    $len = strlen( $templateBlockCacheDir ) + strpos( $templateBlockPath, 'cache/' ) + 6;
                    $nameTrunk = substr( $filePath, 0, $len  );
                }
                else
                {
                    $nameTrunk = $filePath;
                }
            } break;

            default:
            {
                $nameTrunk = $filePath;
            }
        }
        return $nameTrunk;
    }

    /**
    * Returns the remaining time, in seconds, before the generating file times
    * out
    *
    * @param resource $fileRow
    *
    * @return int Remaining generation seconds. A negative value indicates a timeout.
    **/
    protected function remainingCacheGenerationTime( $row )
    {
        if( !isset( $row[0] ) )
            return -1;

        return ( $row[0] + self::$dbparams['cache_generation_timeout'] ) - time();
    }

    /**
     * DB connexion handle
     * @var handle
     **/
    public $db = null;

    /**
     * DB connexion parameters
     * @var array
     **/
    protected static $dbparams = null;

    /**
     * Amount of executed queries, for debugging purpose
     * @var int
     **/
    protected $numQueries = 0;

    /**
     * Current transaction level.
     * Will be used to decide wether we can BEGIN (if it's the first BEGIN call)
     * or COMMIT (if we're commiting the last running transaction
     * @var int
     **/
    protected $transactionCount = 0;

    /**
     * DB file table name
     * @var string
     **/
    const TABLE_METADATA = 'ezdfsfile';

    /**
     * Distributed filesystem backend
     * @var eZDFSFileHandlerDFSBackend
     **/
    protected $dfsbackend = null;
}

?>