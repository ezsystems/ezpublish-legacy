<?php
//
// Definition of eZDBFile class
//
// Created on: Created on: <07-Sep-2005 15:40:54 bf>
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

/*!
  \class eZDBFile ezdbfile.php
  \ingroup eZDatatype
  \brief The class eZDBFile handles registered binaryfiles

  NOTE:
  This file contains only a proof-of-concept implementation of
  storing files in database.
  This implementation is going to replaced soon.
*/

class eZDBFile
{
    function eZDBFile()
    {
       $this->StorageIP = "localhost";
//        $this->StorageIP = "10.0.2.164";
//        $this->StorageIP = "10.0.2.164";
        $this->StoragePort = 3306;
        $this->StorageUser = "root";
        $this->StoragePasswd = "";
        $this->StorageDB = "trunk";

        $connectto = $this->StorageIP . ":" . $this->StoragePort;

        if (!$this->LinkID = @mysql_connect( $connectto, $this->StorageUser, $this->StoragePasswd ) )
        {
            die("Unable to connect to storage server!");
        }
        if ( !mysql_select_db( $this->StorageDB, $this->LinkID ) )
        {
            die("Unable to connect to storage database!");
        }
    }

    /*!
       Uploads a file directly from HTTP Post variable to database.
     */
    function uploadHTTPFile( $postVariable )
    {
        // Init values - these are used incase you want to upload multiple files, you just
        // add them to the source form as file1, file2, file3, etc.
        // Try!
        $SrcPathFile = $postVariable["tmp_name"];
        $SrcFileType = $postVariable["type"];
        $DstFileName = $postVariable["name"];

        $this->storeFile( $DstFileName, $SrcFileType, $SrcPathFile);
    }

    /*!
      store file in database. $localfilename is name of file on local filesysten to store, $realfilename is actual name of file, could be different if $localfilename is jus a tmp filename
    */
    function storeFile( $realfilename, $filetype, $localfilename, $scope )
    {
        $realfilename = mysql_real_escape_string( $realfilename );
        clearstatcache();
        $time = filemtime( $localfilename );
        // File Processing
        if ( file_exists( $localfilename ) )
        {
            if ( $this->fileExists( $realfilename ) )
                $this->delete( $realfilename );
            // Insert into file table
            $sql  = "insert into ezdbfile (datatype, name, name_hash, size, mtime, scope) values ('";
            $sql .= $filetype . "', '" . $realfilename . "', '" . md5( $realfilename ) .  "', " . filesize( $localfilename );
            $sql .= ", '" . $time . "', '" . $scope . "'  )";

            if (!$res = mysql_query( $sql, $this->LinkID ) )
            {
                die("Failure on insert to file table!");
            }

            $fileid = mysql_insert_id( $this->LinkID );

            // Insert into the filedata table
            $fp = fopen( $localfilename, "rb" );
            while (!feof( $fp ) )
            {
                // Make the data mysql insert safe
                $binarydata = mysql_real_escape_string( fread( $fp, 65535 ) );

                $sql = "insert into ezdbfile_data (masterid, filedata) values (";
                $sql .= $fileid . ", '" . $binarydata . "')";

                if ( !mysql_query( $sql, $this->LinkID ) )
                {
                    die("Failure to insert binary inode data row!");
                }
            }

            fclose( $fp );
        }
        else
        {
            echo ("Unable to store file : $localfilename\n");
        }
    }

    /*!
      Save data in variable $fileContent do database
    */
    function storeContentToFile( $filename, $fileContent, $scope, $datatype = false )
    {
        if ( $this->fileExists( $filename ) )
            $this->delete( $filename );
        $filename = mysql_real_escape_string( $filename );
        $currenttime = time();
        clearstatcache();

        if ( $datatype === false )
            $datatype = 'UNSET_FILE_TYPE';

        // Insert into file table
        $content_size = strlen($fileContent);
        $sql  = "insert into ezdbfile (datatype, name, name_hash, scope, size, mtime) values ('";
        $sql .= $datatype . "', '" . $filename . "', '" . md5($filename) .  "', '" . $scope .  "', " . $content_size;
        $sql .= ", '" . $currenttime . "')";

        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("Failure on insert to file table!");
        }

        $fileid = mysql_insert_id($this->LinkID);

        $pos = 0;
        while ( $pos < $content_size )
        {
            $chunk = substr( $fileContent, $pos, 65535 );
            $pos += 65535;

            $sql = "insert into ezdbfile_data (masterid, filedata) values (";
            $sql .= $fileid . ", '" . mysql_real_escape_string( $chunk ) . "')";
            if (!mysql_query($sql, $this->LinkID))
            {
                die("Failure to insert binary inode data row!");
            }
        }
    }

    /*!
      Check if file exist in database
    */
    function fileExists( $filename )
    {
        $filename = mysql_real_escape_string( $filename );
        $sql = "select * from ezdbfile where name_hash='" . md5( $filename ) . "'" ;
        if (!$res = mysql_query( $sql, $this->LinkID ) )
        {
            die("Failure to retrive file metadata : $realfilename");
        }

        if ( mysql_num_rows( $res ) == 0 )
            return false;
        else
            return true;
    }

    /*!
     Gets the modification timestamp of the file stored in database
    */
    function fileMTime( $filename )
    {
        $filename = mysql_real_escape_string( $filename );
        $sql = "select * from ezdbfile where name_hash='" . md5( $filename ) . "'" ;
        if (!$res = mysql_query( $sql, $this->LinkID ))
        {
            die( "Failure to retrive file metadata : $realfilename" );
        }

        if ( mysql_num_rows( $res ) == 1 )
        {
            $row = mysql_fetch_object( $res );
            return $row->mtime;
        }
        else
            return 0;
    }

    /*!
      get mtime of file
    */
    function fileSize( $filename)
    {
        $filename = mysql_real_escape_string($filename);
        $sql = "select * from ezdbfile where name_hash='" . md5($filename) . "'" ;
        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows($res) == 1)
        {
            $row = mysql_fetch_object($res);
            return $row->size;
        }
        else
// FIXME : shouldn't happend.....
            return 0;
    }

    /*!
       Returns the filetype
    */
    function fileType( $filename )
    {
        $filename = mysql_real_escape_string($filename);
        $sql = "select * from ezdbfile where name_hash='" . md5($filename) . "'" ;
        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows($res) == 1)
        {
            $row = mysql_fetch_object($res);
            return $row->datatype;
        }
        else
// FIXME : shouldn't happend.....
            return 0;

    }

    /*!
       Returns the file metadata as array
    */
    function fileMetadata( $filename )
    {
        $filename = mysql_real_escape_string($filename);
        $sql = "select * from ezdbfile where name_hash='" . md5($filename) . "'" ;
        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows($res) == 1)
        {
            $row = mysql_fetch_array($res);
            return $row;
        }
        else
// FIXME : shouldn't happen..
            return null;

    }

    // JB temporary function until the clustering code is imported
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
            return true;

        if ( !mysql_num_rows( $res ) )
            return true;

        while ( $row = mysql_fetch_row( $res ) )
        {
            $deleteFilename = $row[0];
            $this->_delete( $deleteFilename );
        }

        return true;
    }

    /*!
      Deletes the files matching the given wildcard. * Is used as wildcard character.
    */
    function deleteWildcard( $filename )
    {
        $filename = str_replace( "*", "%", $filename );
        $filename = mysql_real_escape_string($filename);
        $sql = "SELECT name FROM ezdbfile WHERE name LIKE '$filename'" ;
        if ( !$res = mysql_query( $sql, $this->LinkID ) )
        {
            die("a1Failure to retrive file metadata : $realfilename");
        }

        if ( mysql_num_rows( $res ) > 0 )
        {
            while ( $row = mysql_fetch_row( $res ) )
            {
                $deleteFilename = $row[0];
                $this->delete( $deleteFilename );
            }
        }
    }

    /*!
      Deletes the files matching the given regular expression.
    */
    function deleteRegex( $filename )
    {
        $filename = mysql_real_escape_string( $filename );
        $sql = "SELECT name FROM ezdbfile WHERE name REGEXP '$filename'" ;
        //eZDebug::writeDebug( $sql, 'deleteRegex: sql' );
        if ( !$res = mysql_query( $sql, $this->LinkID ) )
        {
            die("a1Failure to retrive file metadata : $realfilename");
        }

        if ( mysql_num_rows( $res ) > 0 )
        {
            while ( $row = mysql_fetch_row( $res ) )
            {
                $deleteFilename = $row[0];
                $this->delete( $deleteFilename );
            }
        }
    }

    /*!
      delete file from database
    */
    function delete( $filename )
    {
        $filename = mysql_real_escape_string($filename);
        $sql = "select * from ezdbfile where name_hash='" . md5($filename) . "'" ;
        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("a1Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows( $res ) == 0)
            return false;
        else
        {
            $FileObj = mysql_fetch_object( $res );
//        mysql_free_result($res);
            // Pull the list of file inodes
            $sql = "SELECT id FROM ezdbfile_data WHERE masterid = " . $FileObj->id . " order by id";

            if (!$res = mysql_query( $sql, $this->LinkID ) )
            {
                die("a2Failure to retrive list of ezdbfile inodes");
            }

            while ( $CUR = mysql_fetch_object( $res ) )
            {
                $nodelist[] = $CUR->id;
                $sql = "delete from ezdbfile_data where id = " . $CUR->id;
                if (!$resX = mysql_query($sql, $this->LinkID))
                {
                    die("a3Failure to delete file node data, node : " . $CUR->id);
                }
            }
        $sql = "delete from ezdbfile where id=" . $FileObj->id ;
        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("a4Failure to retrive file metadata : $realfilename");
        }

//        mysql_free_result($res);
        }
        return true;
    }


    // Rename file in database
    function rename( $sourceFilename, $destinationFilename )
    {
        if ( $this->fileExists( $destinationFilename ) )
        {
            $this->delete( $destinationFilename );
        }

        $sourceFilename = mysql_real_escape_string($sourceFilename);
        $destinationFilename = mysql_real_escape_string($destinationFilename);

        $sql = "select * from ezdbfile where name_hash='" . md5($sourceFilename) . "'" ;
        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("a1b Failure to retrive file metadata : $sourceFilename");
        }

        if (mysql_num_rows($res) == 1)
        {
            $FileObj = mysql_fetch_object($res);
//        mysql_free_result($res);
            // Pull the list of file inodes
            $sql = "UPDATE ezdbfile SET name='" . $destinationFilename . "', name_hash='" . md5($destinationFilename) . "' WHERE id = " . $FileObj->id . " order by id";
            if (!$res = mysql_query($sql, $this->LinkID))
            {
                die("Failed to rename file $sourceFilename to $destinationFilename in database");
            }
        } else
        {
            return false; // FIXME : Should not happend
        }
        return true;
    }

    /*!
     copy file in database
    */
    function copy( $sourceFilename, $destinationFilename )
    {
        print( "copying from $sourceFilename  $destinationFilename <br>" );
        if ( ! $this->fileExists( $sourceFilename ) )
        {
            return false;
        }
        if ( $this->fileExists( $destinationFilename ) )
        {
            $this->delete( $destinationFilename );
        }

        $sourceFilename = mysql_real_escape_string($sourceFilename);
        $destinationFilename = mysql_real_escape_string($destinationFilename);

        $sql = "select * from ezdbfile where name_hash='" . md5( $sourceFilename ) . "'" ;
        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("a1b Failure to retrive file metadata : $sourceFilename");
        }

        // VL message to BF: code below is wroong (cut/paste from rename()
        if (mysql_num_rows($res) == 1)
        {
            $FileObj = mysql_fetch_object($res);

            $this->storeContentToFile( $destinationFilename, $this->fetchFile ( $sourceFilename ), $FileObj['scope'] );
        }
        else
        {
            return false; // FIXME : Should not happend
        }
        return true;
    }

    /* fetch file form db, returns filecontent */
    function fetchFile ( $realfilename)
    {
        $realfilename = mysql_real_escape_string($realfilename);
        $nodelist = array();

        // Pull file meta-data
        $sql = "select id from ezdbfile where name_hash='" . md5($realfilename). "'";
        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows($res) != 1)
        {
            die("1 Not a valid file id!");
        }

        $FileObj = mysql_fetch_object($res);
//        mysql_free_result($res);
        // Pull the list of file inodes
        $sql = "SELECT id FROM ezdbfile_data WHERE masterid = " . $FileObj->id . " order by id";

        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("Failure to retrive list of file inodes");
        }

        while ($CUR = mysql_fetch_object($res))
        {
            $nodelist[] = $CUR->id;
        }
//        mysql_free_result($res);


        $result="";
        for ($Z = 0 ; $Z < count($nodelist) ; $Z++)
        {
            $sql = "select filedata from ezdbfile_data where id = " . $nodelist[$Z];

            if (!$resX = mysql_query($sql, $this->LinkID))
            {
                die("Failure to retrive file node data");
            }

            $DataObj = mysql_fetch_object($resX);
            $result.=$DataObj->filedata;
//            unset ($DataObj);
//            mysql_free_result($resX);
        }

        return $result;
    }

    // Same as fetchFile() but outputs content instead of giving it as return value */
    function passThru( $realfilename )
    {
        $realfilename = mysql_real_escape_string($realfilename);
        $nodelist = array();


        // Pull file meta-data
        $sql = "select id from ezdbfile where name_hash='" . md5($realfilename). "'";
        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows($res) != 1)
        {
            die("2 Not a valid file id! : $realfilename");
        }

        $FileObj = mysql_fetch_object($res);
//        mysql_free_result($res);
        // Pull the list of file inodes
        $sql = "SELECT id FROM ezdbfile_data WHERE masterid = " . $FileObj->id . " order by id";

        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("Failure to retrive list of file inodes");
        }

        while ($CUR = mysql_fetch_object($res))
        {
            $nodelist[] = $CUR->id;
        }
//        mysql_free_result($res);


        $result="";
        for ($Z = 0 ; $Z < count($nodelist) ; $Z++)
        {
            $sql = "select filedata from ezdbfile_data where id = " . $nodelist[$Z];

            if (!$resX = mysql_query($sql, $this->LinkID))
            {
                die("Failure to retrive file node data");
            }

            $DataObj = mysql_fetch_object($resX);
            echo( $DataObj->filedata );
        }
    }

    // save file to tmpfile on disk. caller must delete tmp file
    // returns name of tempfile
    function saveToTmpFile( $filename )
    {
          $tmpBaseDir = isset( $_ENV['TMPDIR'] ) ? $_ENV['TMPDIR'] : '';
            // When TMPDIR is not set we have to guess the directory
            // On Unix systems we expect /tmp to be used
            if ( strlen( $uploadDir ) == 0 )
            {
                $tmpBaseDir = '/tmp';
            }

        $tmpfilename = tempnam( $tmpBaseDir, "tmpdbfile");
        $filename = mysql_real_escape_string($filename);
        $nodelist = array();

        // Pull file meta-data
        $sql = "select id from ezdbfile where name_hash='" . md5($filename). "'";

        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("Failure to retrive file metadata : $filename");
        }

        if (mysql_num_rows($res) != 1)
        {
            die("3 Not a valid file id! : $filename");
        }

        $FileObj = mysql_fetch_object($res);
//        mysql_free_result($res);
        // Pull the list of file inodes
        $sql = "SELECT id FROM ezdbfile_data WHERE masterid = " . $FileObj->id . " order by id";

        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("Failure to retrive list of file inodes");
        }

        while ($CUR = mysql_fetch_object($res))
        {
            $nodelist[] = $CUR->id;
        }
//        mysql_free_result($res);


        $result="";
        $fp = fopen($tmpfilename, "w");

        for ($Z = 0 ; $Z < count($nodelist) ; $Z++)
        {
            $sql = "select filedata from ezdbfile_data where id = " . $nodelist[$Z];

            if (!$resX = mysql_query($sql, $this->LinkID))
            {
                die("Failure to retrive file node data");
            }

            $DataObj = mysql_fetch_object($resX);
            fwrite( $fp, $DataObj->filedata) ;

//            unset ($DataObj);
//            mysql_free_result($resX);
        }

        fclose( $fp );

        return $tmpfilename;

    }

    // save file to file on disk. caller must delete file when done with it
    // returns false if unsuccesfull
    function saveToFileSystem( $filename, $filenameOnFileSystem = "default" )
    {
        if ( $filenameOnFileSystem == "default" )
            $filenameOnFileSystem = $filename;

        // create parent directories
            $dirElements = explode( '/', dirname ($filenameOnFileSystem) );
            if ( count( $dirElements ) == 0 )
                return true;
            $currentDir = $dirElements[0];
            $result = true;
            if ( !file_exists( $currentDir ) and $currentDir != "" )
                $result = mkdir( $currentDir, "0777" );
            if ( !$result )
                return false;

            for ( $i = 1; $i < count( $dirElements ); ++$i )
            {
                $dirElement = $dirElements[$i];
                if ( strlen( $dirElement ) == 0 )
                    continue;
                $currentDir .= '/' . $dirElement;
                $result = true;
                if ( !file_exists( $currentDir ) )
                    $result = mkdir( $currentDir, 0777 );
                if ( !$result )
                    return false;
            }


/*        $dirs = implode( "/", dirname ( $filenameOnFileSystem ) );
        for
        if ( ! file_exists( dirname ( $filenameOnFileSystem ) ) )
        {
            echo ("dirname : " . dirname ( $filenameOnFileSystem));
            $success = mkdir ( dirname ( $filenameOnFileSystem ), 0777 );
            if ( ! $success )
                return false;
        } */
        $tmpfilename = $filenameOnFileSystem;
        $filename = mysql_real_escape_string($filename);
        $nodelist = array();

        // Pull file meta-data
        $sql = "select id from ezdbfile where name_hash='" . md5($filename). "'";

        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("Failure to retrive file metadata : $filename");
        }

        if (mysql_num_rows($res) != 1)
        {
            return false;
//            die("4 Not a valid file id! : $filename");
        }

        $FileObj = mysql_fetch_object($res);
//        mysql_free_result($res);
        // Pull the list of file inodes
        $sql = "SELECT id FROM ezdbfile_data WHERE masterid = " . $FileObj->id . " order by id";

        if (!$res = mysql_query($sql, $this->LinkID))
        {
            die("Failure to retrive list of file inodes");
        }

        while ($CUR = mysql_fetch_object($res))
        {
            $nodelist[] = $CUR->id;
        }
//        mysql_free_result($res);


        $result="";
        $fp = fopen($tmpfilename, "w");

        for ($Z = 0 ; $Z < count($nodelist) ; $Z++)
        {
            $sql = "select filedata from ezdbfile_data where id = " . $nodelist[$Z];

            if (!$resX = mysql_query($sql, $this->LinkID))
            {
                die("Failure to retrive file node data");
            }

            $DataObj = mysql_fetch_object($resX);
            fwrite( $fp, $DataObj->filedata) ;
        }

        fclose( $fp );
        return true;

    }

    var $StorageIP;
    var $StoragePort;
    var $StorageUser;
    var $StoragePasswd;
    var $StorageDB;
    var $LinkID;

}

?>
