<?php
//
// Definition of eZWebDAVServer class
//
// Created on: <01-Aug-2003 13:13:13 bh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezwebdavserver.php
 WebDAV server base class.
*/

/*! \defgroup eZWebDAV WebDAV system */

/*!
  \class eZWebDAVServer ezwebdavserver.php
  \ingroup eZWebDAV
  \brief Virtual base class for implementing WebDAV servers.

  \todo Add support for propall and propname
*/

//include_once( "lib/ezutils/classes/ezmimetype.php" );
//include_once( 'lib/ezfile/classes/ezdir.php' );

/*!
  \return \c true if logging is enabled.
  \deprecated Use eZWebDAVServer::isLoggingEnabled() instead.
*/
function eZWebDavCheckLogSetting()
{
    return eZWebDAVServer::isLoggingEnabled();
}

/*!
  Logs the string \a $logString to the logfile /tmp/webdavlog.txt
  if logging is enabled.
  \deprecated Appending to log file is now done through appendLogEntry in the webdav server class.
*/
function append_to_log( $logString )
{
    return eZWebDAVServer::appendLogEntry( $logString );
}

/*!
  Logs the string \a $logString to the logfile /tmp/webdavlog.txt
  if logging is enabled.
  \deprecated Appending to log file is now done through appendLogEntry in the webdav server class.
*/
function eZWebDavAppendToLog( $logString )
{
    return eZWebDAVServer::appendLogEntry( $logString );
}

class eZWebDAVServer
{
    // General status OK return codes:
    const OK = 10;
    const OK_SILENT = 11;
    const OK_CREATED = 12;
    const OK_OVERWRITE = 13;

    // General status FAILED return codes:
    const FAILED_FORBIDDEN = 30;
    const FAILED_NOT_FOUND = 31;
    const FAILED_EXISTS = 32;
    const FAILED_CONFLICT = 33;
    const FAILED_PRECONDITION = 34;
    const FAILED_LOCKED = 35;
    const FAILED_BAD_GATEWAY = 36;
    const FAILED_STORAGE_FULL = 37;
    const FAILED_UNSUPPORTED = 38;

    // File timestamp formats (MUST be correct, or else: Won't work in MSIE...).
    // Yes, the two timestamps are actually in different formats. Don't touch!
    const CTIME_FORMAT = "Y-m-d\\TH:i:s\\Z";
    const MTIME_FORMAT = "D, d M Y H:i:s";

    // Temporary (uploaded) file stuff:
    const TEMP_FILE_PREFIX = "eZWebDAVUpload_";

    /*! Constructor of eZWebDAVServer;
        disables PHP error messages.
     */
    function eZWebDAVServer()
    {
        $this->setupXMLOutputCharset();
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
        $this->appendLogEntry( "WebDAV server started...", 'processClientRequest' );
        $this->XMLBodyRead = false;

        // Dump some custom header/info.
        $this->headers();

        // Clear file status cache (just in case).
        clearstatcache();

        // Convert the requested URI string to non-bogus format.
        $target = urldecode( $_SERVER["REQUEST_URI"] );
        $target = $this->processURL( $target );

        $this->appendLogEntry( "----------------------------------------" );
        $this->appendLogEntry( "Client says: " . $_SERVER["REQUEST_METHOD"], 'processClientRequest' );
        $this->appendLogEntry( "Target: " . $_SERVER["REQUEST_URI"], 'processClientRequest' );
        $this->appendLogEntry( "----------------------------------------" );

        $status = eZWebDAVServer::FAILED_NOT_FOUND;

        switch ( $_SERVER["REQUEST_METHOD"] )
        {
            // OPTIONS  (Server ID reply.)
            case "OPTIONS":
            {
                $this->appendLogEntry( "OPTIONS was issued from client.", 'processClientRequest' );
                $options = $this->options( $target );
                $status  = $this->outputOptions( $options );
            } break;

            // PROPFIND (Show dir/collection content.)
            case "PROPFIND":
            {
                $this->appendLogEntry( "PROPFIND was issued from client.", 'processClientRequest' );

                $depth = $_SERVER['HTTP_DEPTH'];
                if ( $depth != 0 and $depth != 1 and $depth != "infinity" )
                    $depth = "infinity";
                $this->appendLogEntry( "Depth: $depth.", 'processClientRequest' );

                $xmlBody = $this->xmlBody();
                // Find which properties were requested
                // $this->appendLogEntry( $xmlBody, 'xmlbody' );
                $dom = new DOMDocument( '1.0', 'utf-8' );
                $dom->preserveWhiteSpace = false;
                $ok = $dom->loadXML( $xmlBody );

                $requestedProperties = array();
                if ( $ok )
                {
                    $propfindNode = $dom->documentElement;
                    $propNode = $propfindNode->getElementsByTagName( 'prop' )->item( 0 );
                    if ( $propNode )
                    {
                        $propList = $propNode->childNodes;
                        foreach ( $propList as $node )
                        {
                            $name = $node->localName;
                            $requestedProperties[] = $name;
                        }
                    }
                    else
                    {
                        $allpropNode = $propfindNode->getElementsByTagName( 'allprop' )->item( 0 );
                        if ( $allpropNode )
                        {
                            // The server must return all possible properties
                            $requestedProperties = true;
                        }
                        else
                        {
                            $propnameNode = $propfindNode->getElementsByTagName( 'propname' )->item( 0 );
                            if ( $propnameNode )
                            {
                                // The server must return only the names of all properties
                                $requestedProperties = false;
                            }
                        }
                    }
                }

                $collection = $this->getCollectionContent( $target, $depth, $requestedProperties );
                if ( is_array( $collection ) )
                {
                    $status = $this->outputCollectionContent( $collection, $requestedProperties );
                }
                else
                {
                    $status = $collection;
                }
            } break;

            // HEAD (Check if file/resource exists.)
            case "HEAD":
            {
                $this->appendLogEntry( "HEAD was issued from client.", 'processClientRequest' );
                $data   = $this->get( $target );
                $status = $this->outputSendDataToClient( $data, true );
            } break;

            // GET (Download a file/resource from server to client.)
            case "GET":
            {
                $this->appendLogEntry( "GET was issued from client.", 'processClientRequest' );
                $data   = $this->get( $target );
                $status = $this->outputSendDataToClient( $data );
            } break;

            // PUT (Upload a file/resource from client to server.)
            case "PUT":
            {
                $this->appendLogEntry( "PUT was issued from client.", 'processClientRequest' );
                $status = eZWebDAVServer::OK_CREATED;

                // Attempt to get file/resource sent from client/browser.
                $tempFile = $this->storeUploadedFile( $target );

                // If there was an actual file:
                if ( $tempFile )
                {
                    // Attempt to do something with it (copy/whatever).
                    $status = $this->put( $target, $tempFile );

                    unlink( $tempFile );
                    //include_once( 'lib/ezfile/classes/ezdir.php' );
                    eZDir::cleanupEmptyDirectories( dirname( $tempFile ) );
                }
                // Else: something went wrong...
                else
                {
                    $status = eZWebDAVServer::FAILED_FORBIDDEN;
                }

            } break;

            // MKCOL (Create a directory/collection.)
            case "MKCOL":
            {
                $this->appendLogEntry( "MKCOL was issued from client.", 'processClientRequest' );
                if ( strlen( $this->xmlBody() ) > 0 )
                {
                    $this->appendLogEntry( "MKCOL body error.", 'processClientRequest' );
                    $status = eZWebDAVServer::FAILED_FORBIDDEN;
                }
                else
                {
                    $status = $this->mkcol( $target );
                }
            } break;

            // COPY (Copy a resource/collection from one location to another.)
            case "COPY":
            {
                $this->appendLogEntry( "COPY was issued from client.", 'processClientRequest' );

                $source      = $target;
                $url         = parse_url( $_SERVER["HTTP_DESTINATION"] );
                $destination = urldecode( $url["path"] );
                $destination = $this->processURL( $destination );
                $status      = $this->copy( $source, $destination );
            } break;

            // MOVE (Move a resource/collection from one location to another.)
            case "MOVE":
            {
                $this->appendLogEntry( "MOVE was issued from client.", 'processClientRequest' );
                $source      = $target;
                $url         = parse_url( $_SERVER["HTTP_DESTINATION"] );
                $destination = urldecode( $url["path"] );
                $destination = $this->processURL( $destination );
                $status      = $this->move( $source, $destination );
            } break;

            // DELETE (Remove a resource/collection.)
            case "DELETE":
            {
                $this->appendLogEntry( "DELETE was issued from client.", 'processClientRequest' );
                $status = $this->delete( $target );
            } break;

            // Default case: unknown command from client.
            default:
            {
                // __FIX_ME__
            } break;
        }
        // Read the XML body if it is not used yet,
        // PHP should discard it but it's a bug in some PHP versions
        if ( !$this->XMLBodyRead )
            $this->flushXMLBody();

        // Handle the returned status code (post necessary/matching headers, etc.).
        $this->handle( $status );
    }

    /*!
      \protected
      Generates HTTP headers with information on what the server supports.
      \param $options An array with the various options the server supports
                      - methods - An array with methods it can handle,
                                   if not supplied it will report all possible methods.
                      - versions - An array with versions this server supports,
                                    if not supplied it will return 1,2,<http://apache.org/dav/propset/fs/1>.
      \return The WebDAV status code
    */
    function outputOptions( $options )
    {
        // Default options
        $methods = array( 'OPTIONS', 'GET', 'HEAD', 'POST', 'DELETE', 'TRACE', 'PROPFIND', 'PROPPATCH', 'COPY', 'MOVE', 'LOCK', 'UNLOCK' );
        $versions = array( '1', '2', '<http://apache.org/dav/propset/fs/1>' );

        if ( isset( $options['methods'] ) )
            $methods = $options['methods'];
        if ( isset( $options['versions'] ) )
            $versions = $options['versions'];

        header( 'Content-Length: 0' );
        header( 'MS-Author-Via: DAV' );
        header( 'Allow: ' . implode( ', ', $methods ) );
        header( 'DAV: ' . implode( ',', $versions ) );
        header( 'Content-Type: text/plain; charset=iso-8859-1' );

        return eZWebDAVServer::OK_SILENT;
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
    function outputCollectionContent( $collection, $requestedProperties )
    {
        // Sanity check & action: if client forgot to ask, we'll still
        // play along revealing some basic/default properties. This is
        // necessary to make it work with Windows XP + SP2.
        if( !is_array( $requestedProperties ) || count( $requestedProperties ) == 0 )
        {
            $requestedProperties = array( 'displayname',
                                          'creationdate',
                                          'getlastmodified',
                                          'getcontenttype',
                                          'getcontentlength',
                                          'resourcetype' );
        }

        // Fix for CaDAVer (text based linux client) -> error if not revealed.
        // Apparently it does not mess up for other clients so I'll just leave
        // it without wrapping it inside a client-specific check.
        if( !in_array( 'getcontenttype', $requestedProperties ) )
        {
            $requestedProperties[] = 'getcontenttype';
        }

        $this->appendLogEntry( 'Client requested ' .
                               count( $requestedProperties ) .
                               ' properties.', 'outputCollectionContent' );

        $dataCharset = eZWebDAVServer::dataCharset();
        $xmlCharset = $this->XMLOutputCharset();

        $xmlText = "<?xml version=\"1.0\" encoding=\"$xmlCharset\"?>\n" .
                   "<D:multistatus xmlns:D=\"DAV:\">\n";

        // Maps from WebDAV property names to internal names
        $nameMap = array( 'displayname' => 'name',
                          'creationdate' => 'ctime',
                          'getlastmodified' => 'mtime',
                          'getcontenttype' => 'mimetype',
                          'getcontentlength' => 'size' );

        foreach ( $requestedProperties as $requestedProperty )
        {
            if ( !isset( $nameMap[$requestedProperty] ) )
                $nameMap[$requestedProperty] = $requestedProperty;
        }

        // Misc helpful debug info (use when things go wrong..)
        //$this->appendLogEntry( var_dump( $requestedProperties ), 'outputCollectionContent' );
        //$this->appendLogEntry( var_dump( $nameMap ), 'outputCollectionContent' );
        //$this->appendLogEntry( var_dump( $collection ), 'outputCollectionContent' );

        // For all the entries in this dir/collection-array:
        foreach ( $collection as $entry )
        {
            // Translate the various UNIX timestamps to WebDAV format:
            $creationTime = date( eZWebDAVServer::CTIME_FORMAT, $entry['ctime'] );
            $modificationTime = date( eZWebDAVServer::MTIME_FORMAT, $entry['mtime'] );

            // The following lines take care of URL encoding special characters
            // for each element (stuff between slashes) in the path.
            $href = $entry['href'];
            $pathArray = split( '/', eZWebDAVServer::recode( "$href", $dataCharset, $xmlCharset ) );

            $encodedPath = '/';

            foreach( $pathArray as $pathElement )
            {
                if( $pathElement != '' )
                {
                    $encodedPath .= rawurlencode( $pathElement );
                    $encodedPath .= '/';
                }
            }

            $isCollection = $entry['mimetype'] == 'httpd/unix-directory';

            // If this is not a collection, don't leave a trailing '/'
            // on the href. If you do, Goliath gets confused.
            if ( !$isCollection )
                $encodedPath = rtrim($encodedPath, '/');

            $xmlText .= "<D:response>\n" .
                 " <D:href>" . $encodedPath ."</D:href>\n" .
                 " <D:propstat>\n" .
                 "  <D:prop>\n";

            $unknownProperties = array();

            foreach ( $requestedProperties as $requestedProperty )
            {
                $name = $nameMap[$requestedProperty];
                if ( isset( $entry[$name] ) )
                {
                    if ( $requestedProperty == 'creationdate' )
                    {
                        $creationTime = date( eZWebDAVServer::CTIME_FORMAT, $entry['ctime'] );
                        $xmlText .= "   <D:" . $requestedProperty . ">" . $creationTime . "</D:" . $requestedProperty . ">\n";
                    }
                    else if ( $requestedProperty == 'getlastmodified' )
                    {
                        $modificationTime = date( eZWebDAVServer::MTIME_FORMAT, $entry['mtime'] );
                        $xmlText .= "   <D:" . $requestedProperty . ">" . $modificationTime . "</D:" . $requestedProperty . ">\n";
                    }
                    else if ( $isCollection and $requestedProperty == 'getcontenttype' )
                    {

                        $xmlText .= ( "   <D:resourcetype>\n" .
                                      "    <D:collection />\n" .
                                      "   </D:resourcetype>\n" );

                        $unknownProperties[] = $requestedProperty;
                    }
                    else
                    {
                        $xmlText .= "   <D:" . $requestedProperty . ">" . htmlspecialchars( eZWebDAVServer::recode( "$entry[$name]", $dataCharset, $xmlCharset ) ) . "</D:" . $requestedProperty . ">\n";
                    }
                }
                else if ( $requestedProperty != 'resourcetype' or !$isCollection )
                {
                    $unknownProperties[] = $requestedProperty;
                }
            }

            $xmlText .= ( "  <D:lockdiscovery/>\n" );

            $xmlText .= ( "  </D:prop>\n" .
                          "  <D:status>HTTP/1.1 200 OK</D:status>\n" .
                          " </D:propstat>\n" );


            // List the non supported properties and mark with 404
            // This behavior (although recommended/standard) might
            // confuse some clients. Try commenting out if necessary...

            $xmlText .= " <D:propstat>\n";
            $xmlText .= "  <D:prop>\n";
            foreach ( $unknownProperties as $unknownProperty )
            {
                $xmlText .= "   <D:" . $unknownProperty . " />\n";
            }
            $xmlText .= "  </D:prop>\n";
            $xmlText .= "  <D:status>HTTP/1.1 404 Not Found</D:status>\n";
            $xmlText .= " </D:propstat>\n";

            $xmlText .= "</D:response>\n";
        }

        $xmlText .= "</D:multistatus>\n";
        // Send the necessary headers...
        header( 'HTTP/1.1 207 Multi-Status' );
        header( 'Content-Type: text/xml' );

        // Comment out the next line if you don't
        // want to use chunked transfer encoding.
        //header( 'Content-Length: '.strlen( $xmlText ) );

        $text = @ob_get_contents();
        if ( strlen( $text ) != 0 )
            $this->appendLogEntry( $text, "DAV: PHP Output" );
        while ( @ob_end_clean() );

        // Dump XML response (from server to client to logfile.
        //$this->appendLogEntry( $xmlText, 'xmlText' );

        // Dump the actual XML data containing collection list.
        print( $xmlText );

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $success = $dom->loadXML( $xmlText );
        if ( $success )
            $this->appendLogEntry( "XML was parsed", 'outputCollectionContent' );
        else
            $this->appendLogEntry( "XML was NOT parsed $xmlText", 'outputCollectionContent' );

        // If we got this far: everything is OK.
        return eZWebDAVServer::OK_SILENT;
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
        if ( !$output )
        {
            $this->appendLogEntry( "outputData: no data available", 'outputSendDataToClient' );
            return eZWebDAVServer::FAILED_NOT_FOUND;
        }

        // Check if we are dealing with custom data.
        if ( $output["data"] )
        {
            $this->appendLogEntry( "outputData: DATA is a string...", 'outputSendDataToClient' );
        }
        // Else: we need to output a file.
        elseif ( $output["file"] )
        {
            $this->appendLogEntry( "outputData: DATA is a file...", 'outputSendDataToClient' );
            $realPath = $output["file"];

            // Check if the file/dir actually exists and is readable (permission):
            if ( ( file_exists( $realPath ) ) && ( is_readable( $realPath ) ) )
            {
                $this->appendLogEntry( "outputData: file exists on server...", 'outputSendDataToClient' );

                // Get misc. file info.
                $eTag = md5_file( $realPath );
                $size = filesize( $realPath );

                $dir  = dirname( $realPath );
                $file = basename( $realPath );

                $mimeInfo = eZMimeType::findByURL( $dir . '/' . $file );
                $mimeType = $mimeInfo['name'];

                // Send necessary headers to client.
                header( 'HTTP/1.1 200 OK' );
                header( 'Accept-Ranges: bytes' );
                header( 'Content-Length: '.$size );
                header( 'Content-Type: '.$mimeType );
                header( 'ETag: '.$eTag );

                $text = @ob_get_contents();
                if ( strlen( $text ) != 0 )
                    $this->appendLogEntry( $text, "DAV: PHP Output" );
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
                        return eZWebDAVServer::OK_SILENT;
                    }
                    else
                    {
                        return eZWebDAVServer::FAILED_FORBIDDEN;
                    }
                }
                else
                {
                    return eZWebDAVServer::OK_SILENT;
                }
            }
            // Else: file/dir doesn't exist!
            else
            {
                $this->appendLogEntry( "outputData: file DOES NOT exists on server...", 'outputSendDataToClient' );
                return eZWebDAVServer::FAILED_NOT_FOUND;
            }
        }
        else
        {
            $this->appendLogEntry( "outputData: No file specified", 'outputSendDataToClient' );

            $text = @ob_get_contents();
            if ( strlen( $text ) != 0 )
                $this->appendLogEntry( $text, "DAV: PHP Output" );
            while ( @ob_end_clean() );

            return eZWebDAVServer::FAILED_NOT_FOUND;
        }
    }

    /*!
      \protected
      Will try to store the uploaded to a temporary location using \a $target
      for name.
      \return The name of the temp file or \c false if it failed.
    */
    function storeUploadedFile( $target )
    {
        $dir = eZWebDAVServer::tempDirectory() . '/' . md5( microtime() . '-' . $target );
        $filePath = $dir . '/' . basename( $target );

        if ( !file_exists( $dir ) )
        {
            //include_once( 'lib/ezfile/classes/ezdir.php' );
            eZDir::mkdir( $dir, false, true );
        }

        $result = copy( "php://input", $filePath );
        if ( !$result )
        {
            $result = file_exists( $filePath );
        }

        if ( $result )
        {
            header( "HTTP/1.1 201 Created" );
            return $filePath;
        }
        return false;
    }

    /*!
      \return The XML body text for the current request.
    */
    function xmlBody()
    {
        $xmlBody = file_get_contents( "php://input" );
//        $this->appendLogEntry( $xmlBody, 'xmlBody' );
        $this->XMLBodyRead = true;
        return $xmlBody;
    }

    /*!
      \return The XML body text for the current request.
    */
    function flushXMLBody()
    {
        // Flush the XML body by reading it,
        // PHP should discard it but it's a bug in some PHP versions
        $xmlBody = file_get_contents( "php://input" );
    }

    /*!
      \protected
      This method will be called on all intercepted URLs and can be reimplemented
      to clean up the URL for further processing.
      A typical usage is when the server is running without rewrite rules and will
      have the .php file in the path.
      \return The new URL which can safely be passed to the operation methods.
    */
    function processURL( $url )
    {
        return $url;
    }

    /*!
     \protected
     This is called before each each request is processed and can be used to output
     some common headers.
    */
    function headers()
    {
    }

    /*!
      \virtual
      Reports WebDAV options which information on what the server supports.
      \return An associative array with options, can contain:
              - methods - An array with methods it can handle,
                           if not supplied it will use all possible methods.
              - versions - An array with versions this server supports,
                            if not supplied it will use 1,2,<http://apache.org/dav/propset/fs/1>.
    */
    function options( $target )
    {
    }

    /*!
      \virtual
      \return An array with elements that belongs to the collection \a $collection
      \param $depth The current depth, \c 0 for only current object, \c 1 for it's children
      \param $properties Which properties the client asked for, either an array with DAV
                         property names, \c true for all properties or \c false for only property names.
    */
    function getCollectionContent( $collection, $depth = false, $properties = false )
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
        $this->appendLogEntry( "handle function was called with status: $status", 'handle' );

        // Check and translate status to HTTP:WebDAV reply.
        switch ( $status )
        {
            // OK.
            case eZWebDAVServer::OK:
            {
                header( "HTTP/1.1 200 OK" );
            } break;

            // OK, SILENT.
            case eZWebDAVServer::OK_SILENT:
            {
                // Do nothing...
            } break;

            // OK, CREATED.
            case eZWebDAVServer::OK_CREATED:
            {
                header( "HTTP/1.1 201 Created" );
            } break;

            // OK, OVERWRITE.
            case eZWebDAVServer::OK_OVERWRITE:
            {
                header( "HTTP/1.1 204 No Content");
            } break;

            // FAILED, FORBIDDEN!
            case eZWebDAVServer::FAILED_FORBIDDEN:
            {
                header( "HTTP/1.1 403 Forbidden");
            } break;

            // FAILED, NOT FOUND!
            case eZWebDAVServer::FAILED_NOT_FOUND:
            {
                header( "HTTP/1.1 404 Not Found" );
            } break;

            // FAILED, ALREADY EXISTS!
            case eZWebDAVServer::FAILED_EXISTS:
            {
                header( "HTTP/1.1 405 Method not allowed" );
            } break;

            // FAILED, CONFLICT!
            case eZWebDAVServer::FAILED_CONFLICT:
            {
                header( "HTTP/1.1 409 Conflict" );
            }break;

            // FAILED, PRECONDITION.
            case eZWebDAVServer::FAILED_PRECONDITION:
            {
                header( "HTTP/1.1 412 Precondition Failed" );
            } break;

            // FAILED, RESOURCE IS LOCKED!
            case eZWebDAVServer::FAILED_LOCKED:
            {
                header( "HTTP/1.1 423 Locked" );
            } break;

            // FAILED, BAD GATEWAY!
            case eZWebDAVServer::FAILED_BAD_GATEWAY:
            {
                header( "HTTP/1.1 502 Bad Gateway" );
            } break;

            // FAILED, NO SPACE LEFT ON DEVICE!
            case eZWebDAVServer::FAILED_STORAGE_FULL:
            {
                header( "HTTP/1.1 507 Insufficient Storage" );
            } break;

            // FAILED, UNSUPPORTED REQUEST!
            case eZWebDAVServer::FAILED_UNSUPPORTED:
            {
                header( "HTTP/1.1 415 Unsupported Media Type" );
            } break;

            // Default case: something went wrong...
            default:
            {
                $this->appendLogEntry( "HTTP 500, THIS SHOULD NOT HAPPEN!", 'handle' );
                header( "HTTP/1.1 500 Internal Server Error" );
            } break;
        }

        $text = @ob_get_contents();
        if ( strlen( $text ) != 0 )
            $this->appendLogEntry( $text, "DAV: PHP Output" );
        while ( @ob_end_clean() );
    }

    /*!
      Logs the string \a $logString to the logfile webdav.log
      in the current log directory (usually var/log).
      If logging is disabled, nothing is done.
    */
    static function appendLogEntry( $logString, $label = false )
    {
        if ( !eZWebDAVServer::isLoggingEnabled() )
            return false;

        $varDir = eZSys::varDirectory();

        $logDir = 'log';
        $logName = 'webdav.log';
        $fileName = $varDir . '/' . $logDir . '/' . $logName;
        if ( !file_exists( $varDir . '/' . $logDir ) )
        {
            //include_once( 'lib/ezfile/classes/ezdir.php' );
            eZDir::mkdir( $varDir . '/' . $logDir, 0775, true );
        }

        $logFile = fopen( $fileName, 'a' );
        $nowTime = date( "Y-m-d H:i:s : " );
        $text = $nowTime . $logString;
        if ( $label )
            $text .= ' [' . $label . ']';
        fwrite( $logFile, $text . "\n" );
        fclose( $logFile );
    }

    /*!
      \static
      \return \c true if WebDAV logging is enabled.
    */
    static function isLoggingEnabled()
    {
        $useLogging =& $GLOBALS['eZWebDavLogging'];
        if ( !isset( $useLogging ) )
        {
            $ini = eZINI::instance( 'webdav.ini' );
            $useLogging = $ini->variable( 'GeneralSettings', 'Logging' ) == 'enabled';
        }
        return $useLogging;
    }

    /*!
     Sets charset for outputted xml by 'userAgent'
    */
    function setupXMLOutputCharset()
    {
        $charset = eZWebDAVServer::dataCharset();

        $userAgent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : false;
        $pattern = eZWebDAVServer::userAgentPattern();
        $userAgentSettings = eZWebDAVServer::userAgentSettings();

        if ( preg_match( $pattern, $userAgent, $matches ) && isset( $userAgentSettings[$matches[0]] ) )
        {
            $agentSettings = $userAgentSettings[$matches[0]];
            if ( isset( $agentSettings['xmlCharset'] ) && $agentSettings['xmlCharset'] != '' )
                $charset = $agentSettings['xmlCharset'];
        }

        $this->setXMLOutputCharset( $charset );
    }

    /*!
     Sets charset for outputted xml.
    */
    function setXMLOutputCharset( $charset )
    {
        if ( $charset == '' )
        {
            $this->appendLogEntry( "Error: unable to set empty charset for outputted xml.", 'setXMLOutputCharset' );
            return false;
        }

        $this->XMLOutputCharset = $charset;
        return true;
    }

    /*!
     \return charset for outputted xml
    */
    function XMLOutputCharset()
    {
        return $this->XMLOutputCharset;
    }

    /*!
     \static
     \return charset of data. It's used as charset for outputted xml if
     other charset is not specified in 'userAgentSettings'.
    */
    function dataCharset()
    {
        $ini = eZINI::instance( 'i18n.ini' );
        $charset = $ini->variable('CharacterSettings', 'Charset' );
        return $charset;
    }

    /*!
     \static
     \return pattern for 'preg_match'. The pattern is built from 'userAgentSettings'.
    */
    function userAgentPattern()
    {
        $pattern = '//';

        $userAgentSettings = eZWebDAVServer::userAgentSettings();
        if( count( $userAgentSettings ) > 0 )
        {
            $pattern = '/';

            foreach( $userAgentSettings as $agent => $settings )
                $pattern .= $agent . '|';

            $pattern[strlen($pattern)-1] = '/';
        }

        return $pattern;
    }

    /*!
     \static
     \return a list of different settings for known user-agents.
    */
    function userAgentSettings()
    {
        return array( 'WebDrive' => array( 'xmlCharset' => 'utf-8' ),
                      'Microsoft Data Access Internet Publishing Provider' => array( 'xmlCharset' => 'utf-8' )
                    );
    }

    /*!
     \static
     \return recoded \a $string form \a $fromCharset to \a $toCharset
    */
    function recode( $string, $fromCharset, $toCharset, $stop = false )
    {
        //include_once( 'lib/ezi18n/classes/eztextcodec.php' );
        $codec = eZTextCodec::instance( $fromCharset, $toCharset, false );
        if ( $codec )
            $string = $codec->convertString( $string );

        return $string;
    }

    /*!
     \static
     \return the path to the WebDAV temporary directory

     If the directory does not exist yet, it will be created first.
    */
    static function tempDirectory()
    {
        $tempDir = eZSys::varDirectory() . '/webdav/tmp';
        if ( !file_exists( $tempDir ) )
        {
            eZDir::mkdir( $tempDir, eZDir::directoryPermission(), true );
        }
        return $tempDir;
    }

    /*
     \static
     \return the path to the WebDAV root directory

     If the directory does not exist yet, it will be created first.
    */
    static function rootDirectory()
    {
        $rootDir = eZSys::varDirectory() . '/webdav/root';
        if ( !file_exists( $rootDir ) )
        {
            eZDir::mkdir( $rootDir, eZDir::directoryPermission(), true );
        }
        return $rootDir;
    }

    /// \privatesection
    public $ServerRootDir = "";
    public $XMLBodyRead = false;
    public $XMLOutputCharset = 'utf-8';
}
?>
