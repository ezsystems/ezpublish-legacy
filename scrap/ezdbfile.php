<?php

/*

CREATE TABLE file (
 id mediumint(8) unsigned NOT NULL auto_increment,
 datatype varchar(60) NOT NULL default 'application/octet-stream',
 name varchar(255) NOT NULL default '',
 name_hash varchar(34) NOT NULL default '',
 scope varchar(20) NOT NULL default '',
 size bigint(20) unsigned NOT NULL default '1024',
 mtime int(11) NOT NULL default '0',
 PRIMARY KEY (id) ) TYPE=MyISAM;
create index name on file(name);
create index name_hash on file(name_hash);

 CREATE TABLE filedata (
 id mediumint(8) unsigned NOT NULL auto_increment,
 masterid mediumint(8) unsigned NOT NULL default '0',
 filedata blob NOT NULL,
 PRIMARY KEY (id),
 KEY master_idx (masterid) ) TYPE=MyISAM;






CREATE TABLE file (
 id mediumint(8) unsigned NOT NULL auto_increment,
 datatype varchar(60) NOT NULL default 'application/octet-stream',
 name varchar(255) NOT NULL default '',
 name_hash varchar(34) NOT NULL default '',
 size bigint(20) unsigned NOT NULL default '1024',
 filedate datetime NOT NULL default '0000-00-00 00:00:00',
 PRIMARY KEY (id) ) TYPE=InnoDB;
create index name on file(name);
create index name_hash on file(name_hash);

 CREATE TABLE filedata (
 id mediumint(8) unsigned NOT NULL auto_increment,
 masterid mediumint(8) unsigned NOT NULL default '0',
 filedata blob NOT NULL,
 PRIMARY KEY (id),
 KEY master_idx (masterid) ) TYPE=InnoDB;







*/

//save to disk
//purge from disk
//store filer
//filesize

class eZDBFile
{

    function eZDBFile()
    {
        $this->Storage_IP = "localhost";
        $this->Storage_Port = 3306;
        $this->Storage_User = "cluster";
        $this->Storage_Passwd = "cluster";
        $this->Storage_DB = "cluster";


        $connectto = $this->Storage_IP . ":" . $this->Storage_Port;

        if (!$this->linkid = @mysql_connect($connectto, $this->Storage_User, $this->Storage_Passwd))
        {
            die("Unable to connect to storage server!");
        }
        if (!mysql_select_db($this->Storage_DB, $this->linkid))
        {
            die("Unable to connect to storage database!");
        }

    }


    function uploadHttpFile($postVariable)
    {
// Init values - these are used incase you want to upload multiple files, you just
// add them to the source form as file1, file2, file3, etc.
        // Try!
        $SrcPathFile = $postVariable["tmp_name"];
        $SrcFileType = $postVariable["type"];
        $DstFileName = $postVariable["name"];

        $this->storeFile( $DstFileName, $SrcFileType, $SrcPathFile);

    }

    /* store file in database. $localfilename is name of file on local filesysten to store, $realfilename is actual name of file, could be different if $localfilename is jus a tmp filename */
    function storeFile( $realfilename, $filetype, $localfilename, $scope)
    {
        $realfilename = mysql_real_escape_string($realfilename);
        clearstatcache();
        $time = filemtime($localfilename);
//        $storedate = date("Y-m-d H:i:s", $time);
        // File Processing
        if (file_exists($localfilename))
        {
            // Insert into file table
            $SQL  = "insert into file (datatype, name, name_hash, size, mtime, scope) values ('";
            $SQL .= $filetype . "', '" . $realfilename . "', '" . md5($realfilename) .  "', " .filesize($localfilename);
            $SQL .= ", '" . $time . "', '" . $scope . "'  )";

            if (!$RES = mysql_query($SQL, $this->linkid))
            {
                die("Failure on insert to file table!");
            }

            $fileid = mysql_insert_id($this->linkid);

            // Insert into the filedata table
            $fp = fopen($localfilename, "rb");
            while (!feof($fp))
            {
                // Make the data mysql insert safe
                $binarydata = mysql_real_escape_string(fread($fp, 65535));

                $SQL = "insert into filedata (masterid, filedata) values (";
                $SQL .= $fileid . ", '" . $binarydata . "')";

                if (!mysql_query($SQL, $this->linkid)) {
                    die("Failure to insert binary inode data row!");
                }
            }

            fclose($fp);
//            echo "Upload Complete";
//            echo ("Stored file : $localfilename\n");
        } else
        {
            echo ("Unable to store file : $localfilename\n");
        }
    }

    // store data in variable $fileContent do database
    function storeContentToFile( $filename, $fileContent, $scope)
    {
        if ($this->file_exists( $filename ) )
            $this->delete( $filename );
        $filename = mysql_real_escape_string($filename);
        clearstatcache();
        $currenttime = time();

        // Insert into file table
        $content_size = strlen($fileContent);
        $SQL  = "insert into file (datatype, name, name_hash, scope, size, mtime) values ('";
        $SQL .= "UNSET_FILE_TYPE" . "', '" . $filename . "', '" . md5($filename) .  "', '" . $scope .  "', " . $content_size;
        $SQL .= ", '" . $currenttime . "')";

        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure on insert to file table!");
        }

        $fileid = mysql_insert_id($this->linkid);

        $pos = 0;
        while ( $pos < $content_size )
        {
            $chunk = substr( $fileContent, $pos, 65535 );
            $pos += 65535;

            $SQL = "insert into filedata (masterid, filedata) values (";
            $SQL .= $fileid . ", '" . mysql_real_escape_string( $chunk ) . "')";
            if (!mysql_query($SQL, $this->linkid))
            {
                die("Failure to insert binary inode data row!");
            }
        }
    }

    // check if file exist in database
    function file_exists( $filename)
    {
        $filename = mysql_real_escape_string($filename);
        $SQL = "select * from file where name_hash='" . md5($filename) . "'" ;
        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows($RES) == 0)
            return false;
        else
            return true;
    }

    // get mtime of file
    function getMtime( $filename)
    {
        $filename = mysql_real_escape_string($filename);
        $SQL = "select * from file where name_hash='" . md5($filename) . "'" ;
        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows($RES) == 1)
        {
            $row = mysql_fetch_object($RES);
            return $row->mtime;
        }
        else
// FIXME : shouldn't happend.....
            return 0;
    }

    // get mtime of file
    function getFileSize( $filename)
    {
        $filename = mysql_real_escape_string($filename);
        $SQL = "select * from file where name_hash='" . md5($filename) . "'" ;
        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows($RES) == 1)
        {
            $row = mysql_fetch_object($RES);
            return $row->size;
        }
        else
// FIXME : shouldn't happend.....
            return 0;
    }

    function getFileType( $filename )
    {
        $filename = mysql_real_escape_string($filename);
        $SQL = "select * from file where name_hash='" . md5($filename) . "'" ;
        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows($RES) == 1)
        {
            $row = mysql_fetch_object($RES);
            return $row->datatype;
        }
        else
// FIXME : shouldn't happend.....
            return 0;

    }



    // delete file from database
    function delete( $filename )
    {
        $filename = mysql_real_escape_string($filename);
        $SQL = "select * from file where name_hash='" . md5($filename) . "'" ;
        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("a1Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows($RES) == 0)
            return false;
        else
        {
            $FileObj = mysql_fetch_object($RES);
//        mysql_free_result($RES);
            // Pull the list of file inodes
            $SQL = "SELECT id FROM filedata WHERE masterid = " . $FileObj->id . " order by id";

            if (!$RES = mysql_query($SQL, $this->linkid))
            {
                die("a2Failure to retrive list of file inodes");
            }

            while ($CUR = mysql_fetch_object($RES))
            {
                $nodelist[] = $CUR->id;
                $SQL = "delete filedata from filedata where id = " . $CUR->id;
                if (!$RESX = mysql_query($SQL, $this->linkid))
                {
                    die("a3Failure to delete file node data, node : " . $CUR->id);
                }
            }
        $SQL = "delete from file where id=" . $FileObj->id ;
        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("a4Failure to retrive file metadata : $realfilename");
        }

//        mysql_free_result($RES);
        }
        return true;
    }


    // Rename file in database
    function rename( $sourceFilename, $destinationFilename )
    {
        if ( $this->file_exists( $destinationFilename ) )
        {
            $this->delete( $destinationFilename );
        }

        $sourceFilename = mysql_real_escape_string($sourceFilename);
        $destinationFilename = mysql_real_escape_string($destinationFilename);

        $SQL = "select * from file where name_hash='" . md5($sourceFilename) . "'" ;
        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("a1b Failure to retrive file metadata : $sourceFilename");
        }

        if (mysql_num_rows($RES) == 1)
        {
            $FileObj = mysql_fetch_object($RES);
//        mysql_free_result($RES);
            // Pull the list of file inodes
            $SQL = "Update file set name='" . $destinationFilename . "', name_hash='" . md5($destinationFilename) . "' WHERE id = " . $FileObj->id . " order by id";
            echo ( "$SQL");
            if (!$RES = mysql_query($SQL, $this->linkid))
            {
                die("Failed to rename file $sourceFilename to $destinationFilename in database");
            }
        } else
        {
            echo ("balle");
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
        $SQL = "select id from file where name_hash='" . md5($realfilename). "'";
        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows($RES) != 1)
        {
            die("1 Not a valid file id! : $realfilename");
        }

        $FileObj = mysql_fetch_object($RES);
//        mysql_free_result($RES);
        // Pull the list of file inodes
        $SQL = "SELECT id FROM filedata WHERE masterid = " . $FileObj->id . " order by id";

        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure to retrive list of file inodes");
        }

        while ($CUR = mysql_fetch_object($RES))
        {
            $nodelist[] = $CUR->id;
        }
//        mysql_free_result($RES);


        $result="";
        for ($Z = 0 ; $Z < count($nodelist) ; $Z++)
        {
            $SQL = "select filedata from filedata where id = " . $nodelist[$Z];

            if (!$RESX = mysql_query($SQL, $this->linkid))
            {
                die("Failure to retrive file node data");
            }

            $DataObj = mysql_fetch_object($RESX);
            $result.=$DataObj->filedata;
//            unset ($DataObj);
//            mysql_free_result($RESX);
        }

        return $result;
    }

    // Same as fetchFile() but outputs content instead of giving it as return value */
    function passThru( $realfilename )
    {
        $realfilename = mysql_real_escape_string($realfilename);
        $nodelist = array();


        // Pull file meta-data
        $SQL = "select id from file where name_hash='" . md5($realfilename). "'";
        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure to retrive file metadata : $realfilename");
        }

        if (mysql_num_rows($RES) != 1)
        {
            die("2 Not a valid file id! : $realfilename");
        }

        $FileObj = mysql_fetch_object($RES);
//        mysql_free_result($RES);
        // Pull the list of file inodes
        $SQL = "SELECT id FROM filedata WHERE masterid = " . $FileObj->id . " order by id";

        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure to retrive list of file inodes");
        }

        while ($CUR = mysql_fetch_object($RES))
        {
            $nodelist[] = $CUR->id;
        }
//        mysql_free_result($RES);


        $result="";
        for ($Z = 0 ; $Z < count($nodelist) ; $Z++)
        {
            $SQL = "select filedata from filedata where id = " . $nodelist[$Z];

            if (!$RESX = mysql_query($SQL, $this->linkid))
            {
                die("Failure to retrive file node data");
            }

            $DataObj = mysql_fetch_object($RESX);
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
        $SQL = "select id from file where name_hash='" . md5($filename). "'";

        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure to retrive file metadata : $filename");
        }

        if (mysql_num_rows($RES) != 1)
        {
            die("3 Not a valid file id! : $filename");
        }

        $FileObj = mysql_fetch_object($RES);
//        mysql_free_result($RES);
        // Pull the list of file inodes
        $SQL = "SELECT id FROM filedata WHERE masterid = " . $FileObj->id . " order by id";

        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure to retrive list of file inodes");
        }

        while ($CUR = mysql_fetch_object($RES))
        {
            $nodelist[] = $CUR->id;
        }
//        mysql_free_result($RES);


        $result="";
        $fp = fopen($tmpfilename, "w");

        for ($Z = 0 ; $Z < count($nodelist) ; $Z++)
        {
            $SQL = "select filedata from filedata where id = " . $nodelist[$Z];

            if (!$RESX = mysql_query($SQL, $this->linkid))
            {
                die("Failure to retrive file node data");
            }

            $DataObj = mysql_fetch_object($RESX);
            fwrite( $fp, $DataObj->filedata) ;

//            unset ($DataObj);
//            mysql_free_result($RESX);
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
        $SQL = "select id from file where name_hash='" . md5($filename). "'";

        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure to retrive file metadata : $filename");
        }

        if (mysql_num_rows($RES) != 1)
        {
            echo $SQL;
            return false;
//            die("4 Not a valid file id! : $filename");
        }

        $FileObj = mysql_fetch_object($RES);
//        mysql_free_result($RES);
        // Pull the list of file inodes
        $SQL = "SELECT id FROM filedata WHERE masterid = " . $FileObj->id . " order by id";

        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure to retrive list of file inodes");
        }

        while ($CUR = mysql_fetch_object($RES))
        {
            $nodelist[] = $CUR->id;
        }
//        mysql_free_result($RES);


        $result="";
        $fp = fopen($tmpfilename, "w");

        for ($Z = 0 ; $Z < count($nodelist) ; $Z++)
        {
            $SQL = "select filedata from filedata where id = " . $nodelist[$Z];

            if (!$RESX = mysql_query($SQL, $this->linkid))
            {
                die("Failure to retrive file node data");
            }

            $DataObj = mysql_fetch_object($RESX);
            fwrite( $fp, $DataObj->filedata) ;

//            unset ($DataObj);
//            mysql_free_result($RESX);
        }

        fclose( $fp );

        return true;

    }


//purge from disk


    var $Storage_IP;
    var $Storage_Port;
    var $Storagxe_User;
    var $Storage_Passwd;
    var $Storage_DB;
    var $linkid;

}

?>
