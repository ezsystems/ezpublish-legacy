<?php
//
// Definition of eZWebDAVServer class
//
// Created on: <01-Aug-2003 13:13:13 bh>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezwebdavserver.php
 WebDAV server base class.
*/

/*! \defgroup eZWebDAV WebDAV system */

/*!
  \class eZWebDAVServer ezwebdavserver.php
  \ingroup eZWebDAV
  \brief Virtual base class for implementing WebDAV servers.

  __FIX_ME__
*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( "lib/ezutils/classes/ezmimetype.php" );
include_once( 'lib/ezfile/classes/ezdir.php' );

// General status OK return codes:
define( "EZ_WEBDAV_OK", 10 );
define( "EZ_WEBDAV_OK_SILENT", 11 );
define( "EZ_WEBDAV_OK_CREATED", 12 );
define( "EZ_WEBDAV_OK_OVERWRITE", 13 );

// General status FAILED return codes:
define( "EZ_WEBDAV_FAILED_FORBIDDEN", 30 );
define( "EZ_WEBDAV_FAILED_NOT_FOUND", 31 );
define( "EZ_WEBDAV_FAILED_EXISTS", 32 );
define( "EZ_WEBDAV_FAILED_CONFLICT", 33 );
define( "EZ_WEBDAV_FAILED_PRECONDITION", 34 );
define( "EZ_WEBDAV_FAILED_LOCKED", 35 );
define( "EZ_WEBDAV_FAILED_BAD_GATEWAY", 36 );
define( "EZ_WEBDAV_FAILED_STORAGE_FULL", 37 );
define( "EZ_WEBDAV_FAILED_UNSUPPORTED", 38 );


// File timestamp formats (MUST be correct, or else: Won't work in MSIE...).
// Yes, the two timestamps are actually in different formats. Don't touch!
define( "EZ_WEBDAV_CTIME_FORMAT", "Y-m-d\\TH:i:s\\Z" );
define( "EZ_WEBDAV_MTIME_FORMAT", "D, d M Y H:i:s" );


// Get the location of the correct var directory.
$varDir = eZSys::varDirectory();


// Temporary (uploaded) file stuff:
define( "EZ_WEBDAV_TEMP_DIRECTORY",   $varDir."/webdav/tmp" );
define( "EZ_WEBDAV_ROOT_DIRECTORY",   $varDir."/webdav/root" );
define( "EZ_WEBDAV_TEMP_FILE_PREFIX", "eZWebDAVUpload_" );


// Check if necessary temp. dir. actually exists, if not: create it!
if ( !file_exists( EZ_WEBDAV_TEMP_DIRECTORY ) )
{
    eZDir::mkdir( EZ_WEBDAV_TEMP_DIRECTORY, eZDir::directoryPermission(), true);
}

// Check if necessary root dir. actually exists, if not: create it!
if ( !file_exists( EZ_WEBDAV_ROOT_DIRECTORY ) )
{
    eZDir::mkdir( EZ_WEBDAV_ROOT_DIRECTORY, eZDir::directoryPermission(), true);
}


/*!
 \return \c true if logging is enabled.
*/
function eZWebDavCheckLogSetting()
{
    $useLogging =& $GLOBALS['eZWebDavLogging'];
    if ( !isset( $useLogging ) )
    {
        $ini =& eZINI::instance( 'webdav.ini' );
        $useLogging = $ini->variable( 'GeneralSettings', 'Logging' ) == 'enabled';
    }
    return $useLogging;
}

/*!
 Convencience function for calling eZWebDavAppendToLog()
*/
function append_to_log( $logString )
{
    eZWebDavAppendToLog( $logString );
}

/*!
  Logs the string \a $logString to the logfile /tmp/webdavlog.txt
  if logging is enabled.
*/
function eZWebDavAppendToLog( $logString )
{
    if ( !eZWebDavCheckLogSetting() )
        return false;

    $varDir = eZSys::varDirectory();

    $logDir = 'log';
    $logName = 'webdav.log';
    $fileName = $varDir . '/' . $logDir . '/' . $logName;
    if ( !file_exists( $varDir . '/' . $logDir ) )
    {
        include_once( 'lib/ezfile/classes/ezdir.php' );
        eZDir::mkdir( $varDir . '/' . $logDir, 0775, true );
    }

    $logFile = fopen( $fileName, 'a' );
    $nowTime = date( "Y-m-d H:i:s : " );
    fwrite( $logFile, $nowTime . $logString . "\n" );
    fclose( $logFile );
}

class eZWebDAVServer
{
    /*! Constructor of eZWebDAVServer;
        disables PHP error messages.
     */
    function eZWebDAVServer()
    {
    }

    function setServerRoot( $rootDir )
    {
        if ( file_exists ( $rootDir ) )
        {
            $this->ServerRootDir = $rootDir;

            return true;
        }
        else
        {
            return false;
        }
    }

    /*! Server process function.
        Dumps a custom header, sets the path and finally
        checks what the clients wants. Calls the appropriate
        virtual function (based on the client request).
     */
    function processClientRequest()
    {
        append_to_log( "WebDAV server started..." );

        // Dump some custom header/info.
//		header( "WebDAV-Powered-By: eZ publish" );

        // Clear file status cache (just in case).
        clearstatcache();

        // Convert the requested URI string to non-bogus format.
        $target = urldecode( $_SERVER["REQUEST_URI"] );

        append_to_log( "----------------------------------------" );
        append_to_log( "Client says: ".$_SERVER["REQUEST_METHOD"] );
        append_to_log( "Target: ".$_SERVER["REQUEST_URI"] );
        append_to_log( "----------------------------------------" );

        // Read the XML body, PHP should discard it but it's a bug in some PHP versions
        $xmlBody = file_get_contents( "php://input" );

        switch ( $_SERVER["REQUEST_METHOD"] )
        {
            // OPTIONS  (Server ID reply.)
            case "OPTIONS":
            {
                append_to_log( "OPTIONS was issued from client." );
                $options = $this->options( $target );
                $status  = $this->outputOptions( $options );
            } break;

            // PROPFIND (Show dir/collection content.)
            case "PROPFIND":
            {
                append_to_log( "PROPFIND was issued from client." );

                $depth = $_SERVER['HTTP_DEPTH'];
                if ( $depth != 0 and $depth != 1 and $depth != "infinity" )
                    $depth = "infinity";
                append_to_log( "Depth: $depth." );

                $collection = $this->getCollectionContent( $target, $depth );
                $status = $this->outputCollectionContent( $collection, $xmlBody );
            } break;

            // HEAD (Check if file/resource exists.)
            case "HEAD":
            {
                append_to_log( "HEAD was issued from client." );
                $data   = $this->get( $target );
                $status = $this->outputSendDataToClient( $data, true );
            } break;

            // GET (Download a file/resource from server to client.)
            case "GET":
            {
                append_to_log( "GET was issued from client." );
                $data   = $this->get( $target );
                $status = $this->outputSendDataToClient( $data );
            } break;

            // PUT (Upload a file/resource from client to server.)
            case "PUT":
            {
                append_to_log( "PUT was issued from client." );
                $status = EZ_WEBDAV_OK_CREATED;

                // Attempt to get file/resource sent from client/browser.
                $tempFile = $this->createTempFile( $xmlBody );

                // If there was an actual file:
                if ( $tempFile )
                {
                    // Attempt to do something with it (copy/whatever).
                    $status = $this->put( $target, $tempFile );

                    unlink( $tempFile );
                }
                // Else: something went wrong...
                else
                {
                    $status = EZ_WEBDAV_FAILED_FORBIDDEN;
                }

            } break;

            // MKCOL (Create a directory/collection.)
            case "MKCOL":
            {
                append_to_log( "MKCOL was issued from client." );
                if ( strlen( $xmlBody ) > 0 )
                {
                    append_to_log( "MKCOL body error." );
                    $status = EZ_WEBDAV_FAILED_FORBIDDEN;
                }
                else
                {
                    $status = $this->mkcol( $target );
                }
            } break;

            // COPY (Copy a resource/collection from one location to another.)
            case "COPY":
            {
                append_to_log( "COPY was issued from client." );

                $source      = $target;
                $url         = parse_url( $_SERVER["HTTP_DESTINATION"] );
                $destination = urldecode( $url["path"] );
                $status      = $this->copy( $source, $destination );
            } break;

            // MOVE (Move a resource/collection from one location to another.)
            case "MOVE":
            {
                append_to_log( "MOVE was issued from client." );
                $source      = $target;
                $url         = parse_url( $_SERVER["HTTP_DESTINATION"] );
                $destination = urldecode( $url["path"] );
                $status      = $this->move( $source, $destination );
            } break;

            // DELETE (Remove a resource/collection.)
            case "DELETE":
            {
                append_to_log( "DELETE was issued from client." );
                $status = $this->delete( $target );
            } break;

            // Default case: unknown command from client.
            default:
            {
                // __FIX_ME__
            } break;
        }

        // Handle the returned status code (post necessary/matching headers, etc.).
        $this->handle( $status );
    }

    /*!
      \protected
      Generates HTTP headers with information on what the server supports.
      \return The WebDAV status code
    */
    function outputOptions( $options )
    {
        header( "Content-Length: 0" );
        header( "MS-Author-Via: DAV" );
        header( "Allow: OPTIONS, GET, HEAD, POST, DELETE, TRACE, PROPFIND, PROPPATCH, COPY, MOVE, LOCK, UNLOCK" );
        header( "DAV: 1,2,<http://apache.org/dav/propset/fs/1>" );
        header( "Content-Type: text/plain; charset=iso-8859-1" );

        return EZ_WEBDAV_OK_SILENT;
    }

    /*!
      \protected
      Generates the WebDAV XML from \a $collection and outputs using print().
      \param $collection An array with elements (e.g dirs/files).
                         Each element consists of:
                         - ctime - The timestamp when the element was created
                         - mtime - The timestamp when the element was last modified
                         - mimetype - The type of element, use httpd/unix-directory for folder like entries
                         - href - URL which points to the element
                         - name - The name of the element
                         - size - The size of the element in bytes, not needed for folders
      \return The WebDAV status code
    */
    function outputCollectionContent( $collection, $xmlBody )
    {
        $xmlText = "<?xml version='1.0' encoding='utf-8'?>\n" .
                   "<D:multistatus xmlns:D='DAV:'>\n";

        // For all the entries in this dir/collection-array:
        foreach ( $collection as $entry )
        {
            // Translate the various UNIX timestamps to WebDAV format:
            $creationTime = date( EZ_WEBDAV_CTIME_FORMAT, $entry['ctime'] );
            $modificationTime = date( EZ_WEBDAV_MTIME_FORMAT, $entry['mtime'] );

            // Check if collection and append/use appropriate tags.
            if ( $entry['mimetype'] == 'httpd/unix-directory' )
            {
                $xmlText .= "<D:response>\n" .
                     " <D:href>" . $entry['href'] ."</D:href>\n" .
                     " <D:propstat>\n" .
                     "  <D:prop>\n" .
                     "   <D:displayname>" . $entry['name'] . "</D:displayname>\n" .
                     "   <D:resourcetype>\n" .
                     "    <D:collection />\n" .
                     "   </D:resourcetype>\n" .
                     "   <D:creationdate>$creationTime</D:creationdate>\n" .
                     "   <D:getlastmodified>$modificationTime</D:getlastmodified>\n" .
                     "  </D:prop>\n" .
                     "  <D:status>HTTP/1.1 200 OK</D:status>\n" .
                     " </D:propstat>\n";
            }
            else
            {
                $xmlText .= "<D:response>\n" .
                     " <D:href>" . $entry['href'] ."</D:href>\n" .
                     " <D:propstat>\n" .
                     "  <D:prop>\n" .
                     "   <D:displayname>" . $entry['name'] . "</D:displayname>\n" .
                     "   <D:getcontenttype>" . $entry['mimetype'] . "</D:getcontenttype>\n" .
                     "   <D:getcontentlength>" . $entry['size']  . "</D:getcontentlength>\n" .
                     "   <D:creationdate>$creationTime</D:creationdate>\n" .
                     "   <D:getlastmodified>$modificationTime</D:getlastmodified>\n" .
                     "  </D:prop>\n" .
                     "  <D:status>HTTP/1.1 200 OK</D:status>\n" .
                     " </D:propstat>\n";
            }

            // List the non supported properties
            $xmlText .= "<D:propstat>\n";
            $xmlText .= " <D:prop>\n";
            $xmlText .= "  <D:supportedlock />\n";
            $xmlText .= "  <D:source />\n";
            $xmlText .= "  <D:executable />\n";
            $xmlText .= "  <D:getetag />\n";
            $xmlText .= "  <D:lockdiscovery />\n";
            $xmlText .= " </D:prop>\n";
            $xmlText .= "<D:status>HTTP/1.1 404 Not Found</D:status>";
            $xmlText .= "</D:propstat>\n";

            $xmlText .= "</D:response>\n";
        }

        $xmlText .= "</D:multistatus>\n";
        // Send the necessary headers...
        header( 'HTTP/1.1 207 Multi-Status' );
        header( 'Content-Type: text/xml; charset="utf-8"' );

        // Comment out the next line if you don't
        // want to use chunked transfer encoding.
        // header( 'Content-Length: '.strlen( $xml ) );

        while ( @ob_end_clean() );

        // Dump the actual XML data containing collection list.
        print( $xmlText );

        $xml = new eZXML();
        $domTree = $xml->domTree( $xmlText );
        if ( $domTree )
            append_to_log( "XML was parsed" );
        else
            append_to_log( "XML was NOT parsed $xmlText" );

        // If we got this far: everything is OK.
        return EZ_WEBDAV_OK_SILENT;
    }

    /*!
      \protected
      Outputs the data \a $output using print().
      \param $output Is an array which can contain:
                     - data - String or byte data
                     - file - The path to the file, the contents of the file will be output
      \return The WebDAV status code
    */
    function outputSendDataToClient( $output, $headers_only = false )
    {
        // Check if we are dealing with custom data.
        if ( $output["data"] )
        {
            append_to_log( "outputData: DATA is a string..." );
        }
        // Else: we need to output a file.
        elseif ( $output["file"] )
        {
            append_to_log( "outputData: DATA is a file..." );
            $realPath = $output["file"];

            // Check if the file/dir actually exists and is readable (permission):
            if ( ( file_exists( $realPath ) ) && ( is_readable( $realPath ) ) )
            {
                append_to_log( "outputData: file exists on server..." );

                // Get misc. file info.
                $eTag = md5_file( $realPath );
                $size = filesize( $realPath );

                $dir  = dirname( $realPath );
                $file = basename( $realPath );

                $mime     = new eZMimeType ();
                $mimeType = $mime->mimeTypeFor( $dir, $file );

                // Send necessary headers to client.
                header( 'HTTP/1.1 200 OK' );
                header( 'Accept-Ranges: bytes' );
                header( 'Content-Length: '.$size );
                header( 'Content-Type: '.$mimeType );
                header( 'ETag: '.$eTag );

                while ( @ob_end_clean() );

                if ( !$headers_only )
                {
                    // Attempt to open the file.
                    $fp = fopen( $realPath, "rb" );

                    // Output the actual contents of the file.
                    $status = fpassthru( $fp );

                    // Check if the last command succeded..
                    if ( $status == $size)
                    {
                        return EZ_WEBDAV_OK_SILENT;
                    }
                    else
                    {
                        return EZ_WEBDAV_FAILED_FORBIDDEN;
                    }
                }
                else
                {
                    return EZ_WEBDAV_OK_SILENT;
                }
            }
            // Else: file/dir doesn't exist!
            else
            {
                append_to_log( "outputData: file DOES NOT exists on server...");
                return EZ_WEBDAV_FAILED_NOT_FOUND;
            }
        }
        else
        {
            append_to_log( "outputData: No file specified");

            while ( @ob_end_clean() );

            return EZ_WEBDAV_FAILED_NOT_FOUND;
        }
    }

    /*!
      \protected
      Will create a temporary file containing the body of the PUT request.
      If permission is denied false will be returned.
    */
    function createTempFile( &$body )
    {
        $tempFileName = tempnam( EZ_WEBDAV_TEMP_DIRECTORY, EZ_WEBDAV_TEMP_FILE_PREFIX );

        $fpWrite = fopen( $tempFileName, "wb" );

        if ( $fpWrite )
        {
            header( "HTTP/1.1 201 Created" );

            fwrite( $fpWrite, $body );
            fclose( $fpWrite );

            return $tempFileName;
        }
        else
        {
            return false;
        }
    }

    /*!
      \virtual
      Returns WebDAV options which information on what the server supports.
    */
    function options( $target )
    {
    }

    /*!
      \virtual
      \return An array with elements that belongs to the collection \a $collection
     */
    function getCollectionContent( $collection, $depth )
    {
    }

    /*!
      \virtual
      \return Information on a given element
    */
    function head( $target )
    {
    }

    /*!
      \virtual
      Fetches the data for the element \a $target
      \return The contents of a given element, e.g. contents of a file.
    */
    function get( $target )
    {
    }

    /*!
      \virtual
      Tries to create/overwrite an element named \a $target with contents taken from \a $tempFile.
      \return The WebDAV status code
    */
    function put( $target, $tempFile )
    {
    }

    /*!
      \virtual
      Create a new collection (folder) named \a $target.
      \return The WebDAV status code
    */
    function mkcol( $target )
    {
    }

    /*!
      \virtual
      Copies the element \a $source to destination \a $destination
      \return The WebDAV status code
    */
    function copy( $source, $destination )
    {
    }

    /*!
      \virtual
      Moves the element \a $source to destination \a $destination
      \return The WebDAV status code
    */
    function move( $source, $destination )
    {
    }

    /*!
      \virtual
      Removes the element \a $target.
      \return The WebDAV status code
    */
    function delete( $target )
    {
    }

    /*!
      \protected
      Handles return values and sends necessary/corresponding headers.
    */
    function handle( $status )
    {
        append_to_log( "handle function was called with status: $status" );

        // Check and translate status to HTTP:WebDAV reply.
        switch ( $status )
        {
            // OK.
            case EZ_WEBDAV_OK:
            {
                header( "HTTP/1.1 200 OK" );
            } break;

            // OK, SILENT.
            case EZ_WEBDAV_OK_SILENT:
            {
                // Do nothing...
            } break;

            // OK, CREATED.
            case EZ_WEBDAV_OK_CREATED:
            {
                header( "HTTP/1.1 201 Created" );
            } break;

            // OK, OVERWRITE.
            case EZ_WEBDAV_OK_OVERWRITE:
            {
                header( "HTTP/1.1 204 No Content");
            } break;

            // FAILED, FORBIDDEN!
            case EZ_WEBDAV_FAILED_FORBIDDEN:
            {
                header( "HTTP/1.1 403 Forbidden");
            } break;

            // FAILED, NOT FOUND!
            case EZ_WEBDAV_FAILED_NOT_FOUND:
            {
                header( "HTTP/1.1 404 Not Found" );
            } break;

            // FAILED, ALREADY EXISTS!
            case EZ_WEBDAV_FAILED_EXISTS:
            {
                header( "HTTP/1.1 405 Method not allowed" );
            } break;

            // FAILED, CONFLICT!
            case EZ_WEBDAV_FAILED_CONFLICT:
            {
                header( "HTTP/1.1 409 Conflict" );
            }break;

            // FAILED, PRECONDITION.
            case EZ_WEBDAV_FAILED_PRECONDITION:
            {
                header( "HTTP/1.1 412 Precondition Failed" );
            } break;

            // FAILED, RESOURCE IS LOCKED!
            case EZ_WEBDAV_FAILED_LOCKED:
            {
                header( "HTTP/1.1 423 Locked" );
            } break;

            // FAILED, BAD GATEWAY!
            case EZ_WEBDAV_FAILED_BAD_GATEWAY:
            {
                header( "HTTP/1.1 502 Bad Gateway" );
            } break;

            // FAILED, NO SPACE LEFT ON DEVICE!
            case EZ_WEBDAV_FAILED_STORAGE_FULL:
            {
                header( "HTTP/1.1 507 Insufficient Storage" );
            } break;

            // FAILED, UNSUPPORTED REQUEST!
            case EZ_WEBDAV_FAILED_UNSUPPORTED:
            {
                header( "HTTP/1.1 415 Unsupported Media Type" );
            } break;

            // Default case: something went wrong...
            default:
            {
                append_to_log( "HTTP 500, THIS SHOULD NOT HAPPEN!" );
                header( "HTTP/1.1 500 Internal Server Error" );
            } break;
        }
    }

    /// \privatesection
    var $ServerRootDir = "";
}
?>
