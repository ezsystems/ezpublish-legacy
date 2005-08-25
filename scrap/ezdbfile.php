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
    function storeFile( $realfilename, $filetype, $localfilename)
    {
        $realfilename = mysql_real_escape_string($realfilename);
        clearstatcache();
        $time = filemtime($localfilename);
//        $storedate = date("Y-m-d H:i:s", $time);
        // File Processing
        if (file_exists($localfilename))
        {
            // Insert into file table
            $SQL  = "insert into file (datatype, name, name_hash, size, mtime) values ('";
            $SQL .= $filetype . "', '" . $realfilename . "', '" . md5($realfilename) .  "', " .filesize($localfilename);
            $SQL .= ", '" . $time . "')";

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
                $binarydata = addslashes(fread($fp, 65535));

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

    function storeContentToFile( $filename, $fileContent, $scope)
    {
        if ($this->file_exists( $filename ) )
            $this->delete( $filename );
        $filename = mysql_real_escape_string($filename);
        clearstatcache();
        $currenttime = time();
//        $storedate = date("Y-m-d H:i:s", $currenttime);

        // Insert into file table
        //FIXME: strlen might return incorrect value
        $content_size = strlen($fileContent);
        $SQL  = "insert into file (datatype, name, name_hash, scope, size, mtime) values ('";
        $SQL .= "UNSET_FILE_TYPE" . "', '" . $filename . "', '" . md5($filename) .  "', '" . $scope .  "', " . $content_size;
        $SQL .= ", '" . $currenttime . "')";

        if (!$RES = mysql_query($SQL, $this->linkid))
        {
            die("Failure on insert to file table!");
        }

        $fileid = mysql_insert_id($this->linkid);

        // Insert into the filedata table
        if ( $content_size > 65535 )
        {
            echo ("ezdbfile::storeContentToFile : datasize to big, not implemented");
            die;
        }
        // Make the data mysql insert safe
        $binarydata = addslashes($fileContent);

        $SQL = "insert into filedata (masterid, filedata) values (";
        $SQL .= $fileid . ", '" . $binarydata . "')";

        if (!mysql_query($SQL, $this->linkid)) {
            die("Failure to insert binary inode data row!");

        }
    }

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
    }


    /* fetch file some db */
    function fetchFile ( $realfilename)
    {
//        if (isset($_GET["id"]))

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
            die("Not a valid file id! : $realfilename");
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

        // Send down the header to the client
        // Loop thru and stream the nodes 1 by 1

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

    var $Storage_IP;
    var $Storage_Port;
    var $Storagxe_User;
    var $Storage_Passwd;
    var $Storage_DB;
    var $linkid;

}

?>
