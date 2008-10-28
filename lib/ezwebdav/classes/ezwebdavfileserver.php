<?php
//
// This is the index_webdav.php file. Manages WebDAV sessions.
//
// Created on: <18-Aug-2003 15:15:15 bh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZWebDAVFileServer ezwebdavfileserver.php
  \ingroup eZWebDAV
  \brief A simple file based WebDAV server

  Enables local file administration/management through the WebDAV interface.

  Usage:
  \code
  $myserver = new eZWebDAVFileServer();
  $myserver->processClientRequest();
  \endcode
*/

// Get and return the files/dir-names that reside at a given path.
function getDirEntries( $targetPath )
{
    $files = array();

    // Attempt to open the target dir for listing.
    if ( $handle = opendir( $targetPath ) )
    {
        // For all entries in target dir: get filename.
        while ( false !== ( $file = readdir( $handle ) ) )
        {
            if ( $file != "." && $file != ".." )
            {
                $files[] = $file;
            }
        }
        closedir( $handle );
    }
    // Else: unable to open the dir for listing, bail out...
    else
    {
        return false;
    }

    // Return array of filenames.
    return $files;
}

// Recursively copies the contents of a directory.
function copyDir( $source, $destination )
{
    // Attempt to create destination dir.
    $status = eZDir::mkdir( $destination );

    // If no success: bail out.
    if ( !$status )
    {
        return false;
    }

    // Get the contents of the directory.
    $entries = getDirEntries( $source );

    // Bail if contents is unavailable.
    if ( $entries == false )
    {
        return false;
    }
    // Else: contents is OK:
    else
    {
        // Copy each and every entry:
        foreach ( $entries as $entry )
        {
            if ( $entry )
            {
                $from = "$source/$entry";
                $to   = "$destination/$entry";

                // Is this a directory? -> special case.
                if ( is_dir( $from ) )
                {
                    $status = copyDir( $from, $to );
                    if (!$status)
                    {
                        return false;
                    }
                }
                // Else: simple file case.
                else
                {
                    $status = copy( $from, $to );
                    if (!$status)
                    {
                        return false;
                    }
                }
            }
        }

    }

    // Phew: if we got this far then everything is OK.
    return true;
}

// Recursively deletes the contents of a directory.
function delDir( $dir )
{
    // Attempt to open the target dir.
    $currentDir = opendir( $dir );

    // Bail if unable to open dir.
    if ( $currentDir == false )
    {
        return false;
    }
    // Else, dir is available, do the thing:
    else
    {
        // For all entires in the dir:
        while ( false !== ( $entry = readdir( $currentDir ) ) )
        {
            // If entry is a directory and not . && .. :
            if ( is_dir( "$dir/$entry" ) and
                 ( $entry != "." and $entry!="..") )
            {
                // Delete the dir.
                $status = deldir( "${dir}/${entry}" );

                // Bail if unable to delete the dir.
                if ( !$status )
                {
                    return false;
                }
            }
            // Else: not dir but plain file.
            elseif ( $entry != "." and $entry != ".." )
            {
                // Simply unlink the file.
                $status = unlink( "${dir}/${entry}" );

                // Bail if unable to delete the file.
                if ( !$status )
                {
                    return false;
                }
            }
        }
    }

    // We're finished going through the contents of the target dir.
    closedir( $currentDir );

    // Attempt to remove the target dir itself & return status (should be
    // OK as soon as we get this far...
    $status = rmdir( ${dir} );

    return $status;
}

/* getFileInfo
   Gathers information about a specific file,
   stores it in an associative array and returns it.
*/
function getFileInfo( $dir, $file )
{
    append_to_log( "inside getFileInfo, dir: $dir, file: $file");
    $realPath = $dir.'/'.$file;
    $fileInfo = array();

    $fileInfo["name"] = $file;

    // If the file is a directory:
    if ( is_dir( $realPath ) )
    {
        $fileInfo["size"]     = 0;
        $fileInfo["mimetype"] = "httpd/unix-directory";

        // Get the dir's creation & modification times.
        $fileInfo["ctime"] = filectime( $realPath.'/.' );
        $fileInfo["mtime"] = filemtime( $realPath.'/.' );

    }
    // Else: The file is an actual file (not a dir):
    else
    {
        // Get the file's creation & modification times.
        $fileInfo["ctime"] = filectime( $realPath );
        $fileInfo["mtime"] = filemtime( $realPath );

        // Get the file size (bytes).
        $fileInfo["size"] = filesize( $realPath );

        // Check if the filename exists and is readable:
        if ( is_readable( $realPath ) )
        {
            // Attempt to get & set the MIME type.
            $mimeInfo = eZMimeType::findByURL( $dir . '/' . $file );
            $fileInfo['mimetype'] = $mimeInfo['name'];
        }
        // Non-readable? -> MIME type fallback to 'application/x-non-readable'
        else
        {
            $fileInfo["mimetype"] = "application/x-non-readable";
        }
     }

    // Return the array (hopefully containing correct info).
    return $fileInfo;
}


class eZWebDAVFileServer extends eZWebDAVServer
{
    function eZWebDAVFileServer()
    {
        $this->eZWebDAVServer();
    }

    /*!
     \reimp
     Returns if the file \a $target exists or not
    */
    function head( $target )
    {
        // Make real path.
        $realPath = $_SERVER["DOCUMENT_ROOT"].$target;

        append_to_log( "HEAD: realPath is $realPath");

        // Check if the target file/dir really exists:
        if ( file_exists( $realPath ) )
        {
            return eZWebDAVServer::OK_CREATED;
        }
        else
        {
            return eZWebDAVServer::FAILED_NOT_FOUND;
        }
    }

    /*!
     \reimp
     Renames the temp file \a $tempFile to \a $target.
    */
    function put( $target, $tempFile )
    {
        // Make real path.
        $realPath = $_SERVER["DOCUMENT_ROOT"].$target;

        append_to_log( "PUT: realPath is $realPath" );
        append_to_log( "PUT: tempfile is $tempFile" );

        // Attempt to move the file from temp to desired location.
        eZFile::rename( $tempFile, $realPath );

        // Check status & return corresponding code:
        if ( $status )
        {
            append_to_log( "move of tempfile was OK" );
            return eZWebDAVServer::OK_CREATED;
        }
        else
        {
            append_to_log( "move of tempfile FAILED" );
            return eZWebDAVServer::FAILED_FORBIDDEN;
        }
    }

    /*!
     \reimp
     \return An information structure with the filename.
    */
    function get( $target )
    {
        $result         = array();
        $result["data"] = false;
        $result["file"] = false;

        // Set the file.
        $result["file"] = $_SERVER["DOCUMENT_ROOT"] . $target;

        append_to_log( "GET: file is ".$result["file"]);

        return $result;
    }

    /*!
     \reimp
     Creates the directory \a $target
    */
    function mkcol( $target )
    {
        // Make real path.
        $realPath = $_SERVER["DOCUMENT_ROOT"].$target;

        append_to_log( "attempting to create dir: $realPath" );

        // Proceed only if the dir/file-name doesn't exist:
        if ( !file_exists( $realPath ) )
        {
            // Attempt to create the directory.
            $status = mkdir( $realPath );

            // Check status:
            if ( $status )
            {
                // OK:
                return eZWebDAVServer::OK_CREATED;
            }
            else
            {
                // No deal.
                return eZWebDAVServer::FAILED_FORBIDDEN;
            }
        }
        // Else: a dir/file with that name already exists:
        else
        {
            return eZWebDAVServer::FAILED_EXISTS;
        }
    }

    /*!
     \reimp
     Removes the directory or file \a $target
    */
    function delete( $target )
    {
        // Make real path.
        $realPath = $_SERVER["DOCUMENT_ROOT"] . $target;

        append_to_log( "attempting to DELETE: $realPath" );

        // Check if the file actually exists (NULL compliance).
        if ( file_exists( $realPath ) )
        {
            append_to_log( "File/dir exists..." );

            if ( is_dir( $realPath ) )
            {
                // Attempt to remove the target directory.
                $status = delDir( $realPath );
            }
            else
            {
                append_to_log( "File is a file..." );

                // Attempt to remove the file.
                $status = unlink( $realPath );
            }

            // Check the return code:
            if ( $status )
            {
                append_to_log( "delete was OK" );
                return eZWebDAVServer::OK;
            }
            else
            {
                append_to_log( "delete FAILED" );
                return eZWebDAVServer::FAILED_FORBIDDEN;
            }
        }
        else
        {
            return eZWebDAVServer::FAILED_NOT_FOUND;
        }
    }

    /*!
     \reimp
     Moves the file or directory \a $source to \a $destination by trying to rename it.
    */
    function move( $source, $destination )
    {
        append_to_log( "Source: $source   Destination: $destination" );

        // Make real path to source and destination.
        $realSource      = $_SERVER["DOCUMENT_ROOT"] . $source;
        $realDestination = $_SERVER["DOCUMENT_ROOT"] . $destination;

        append_to_log( "RealSource: $realSource   RealDestination: $realDestination" );
        $status = eZFile::rename( $realSource, $realDestination );

        if ( $status )
        {
            append_to_log( "move was OK" );
            return eZWebDAVServer::OK_CREATED;
        }
        else
        {
            append_to_log( "move FAILED" );
            return eZWebDAVServer::FAILED_CONFLICT;
        }
    }

    /*!
     \reimp
     Copies the file or directory \a $source to \a $destination.
    */
    function copy( $source, $destination )
    {
        append_to_log( "Source: $source   Destination: $destination" );
        ob_start(); var_dump( $_SERVER ); $m = ob_get_contents(); ob_end_clean(); append_to_log( $m );

        // Make real path to source and destination.
        $realSource      = $_SERVER["DOCUMENT_ROOT"] . $source;
        $realDestination = $_SERVER["DOCUMENT_ROOT"] . $destination;

        append_to_log( "RealSource: $realSource   RealDestination: $realDestination" );
        $status = copyDir( $realSource, $realDestination );

        if ( $status )
        {
            append_to_log( "copy was OK" );
            return eZWebDAVServer::OK_CREATED;
        }
        else
        {
            append_to_log( "copy FAILED" );
            return eZWebDAVServer::FAILED_CONFLICT;
        }
    }

    /*!
     \reimp
     Finds all files and directories in the directory \a $dir and return an element list of it.
    */
    function getCollectionContent( $dir, $depth = false, $properties = false )
    {
        $directory = dirname( $_SERVER['SCRIPT_FILENAME'] ) . $dir;

        $files  = array();

        append_to_log( "inside getDirectoryContent, dir: $directory" );
        $handle = opendir( $directory );

        // For all the entries in the directory:
        while ( false !== ( $filename = readdir( $handle ) ) )
        {
            // Skip current and parent dirs ('.' and '..').
            if ( $filename == '.' or $filename == '..' )
                continue;
            $files[] = getFileInfo( $directory, $filename );
            append_to_log( "inside getDirectoryContent, dir: $directory, fil: $filename" );
        }
        return $files;
    }
}
?>
