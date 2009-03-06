<?php
class ezcWebdavTransportWrapper extends ezcWebdavTransport
{
    protected function retrieveBody()
    {
        return isset( $GLOBALS['ezc_post_body'] ) ? $GLOBALS['ezc_post_body'] : null;
    }

    protected function sendResponse( ezcWebdavOutputResult $output )
    {
        $response = '';

        // Sends HTTP headers
        foreach ( $output->headers as $name => $content )
        {
            $content   = is_array( $content ) ? $content : array( $content );
            $response .= "{$name}: ";
            foreach ( $content as $contentLine )
            {
                $response .= "{$contentLine}\n";
            }
        }

        // Send HTTP status code
        $response .= "$output->status\n\n";

        $response .= "$output->body\n";

        // Content-Length header automatically send
        $GLOBALS['ezc_response_body'] = $response;
    }
}

class ezcWebdavServerConfigurationManagerWrapper extends ezcWebdavServerConfigurationManager
{
    public function __construct()
    {
        $config = new ezcWebdavServerConfiguration();
        $config->transportClass = 'ezcWebdavTransportWrapper';
        $config->pathFactory = new ezcWebdavBasicPathFactory( 'http://webdav.ezp' );
        $this[] = $config;
    }
}

class eZWebDAVContentServerWrapper extends eZWebDAVContentServer
{
    function outputSendDataToClient( $output, $headers_only = false )
    {
        if ( $output["file"] )
        {
            $realPath = $output["file"];
            require_once( 'kernel/classes/ezclusterfilehandler.php' );
            $file = eZClusterFileHandler::instance( $realPath );
            $file->fetch();
        }
        $result = $this->outputSendDataToClientFull($output,$headers_only);
        if ( $output["file"] && is_object( $file ) )
            $file->deleteLocal();
        return $result;
    }

    function outputSendDataToClientFull( $output, $headers_only = false )
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
// @as output to a $GLOBAL variable
                    // Attempt to open the file.
//                    $fp = fopen( $realPath, "rb" );

                    // Output the actual contents of the file.
                    $status = file_get_contents( $realPath );

                    // Check if the last command succeded..
                    if ( strlen( $status ) == $size )
                    {
                        $GLOBALS['ezc_response_body'] = $status;
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

            $xmlText .= "  <D:response>\n" .
                 "    <D:href>" . $encodedPath ."</D:href>\n" .
                 "    <D:propstat>\n" .
                 "      <D:prop>\n";

            $unknownProperties = array();

            foreach ( $requestedProperties as $requestedProperty )
            {
                $name = $nameMap[$requestedProperty];
                if ( isset( $entry[$name] ) )
                {
                    if ( $requestedProperty == 'creationdate' )
                    {
                        $creationTime = date( eZWebDAVServer::CTIME_FORMAT, $entry['ctime'] );
                        $xmlText .= "        <D:" . $requestedProperty . ">" . $creationTime . "</D:" . $requestedProperty . ">\n";
                    }
                    else if ( $requestedProperty == 'getlastmodified' )
                    {
                        $modificationTime = date( eZWebDAVServer::MTIME_FORMAT, $entry['mtime'] );
                        $xmlText .= "        <D:" . $requestedProperty . ">" . $modificationTime . "</D:" . $requestedProperty . ">\n";
                    }
                    else if ( $isCollection and $requestedProperty == 'getcontenttype' )
                    {

                        $xmlText .= ( "        <D:resourcetype>\n" .
                                      "          <D:collection/>\n" .
                                      "        </D:resourcetype>\n" );

                        $unknownProperties[] = $requestedProperty;
                    }
                    else
                    {
                        $xmlText .= "        <D:" . $requestedProperty . ">" . htmlspecialchars( eZWebDAVServer::recode( "$entry[$name]", $dataCharset, $xmlCharset ) ) . "</D:" . $requestedProperty . ">\n";
                    }
                }
                else if ( $requestedProperty != 'resourcetype' or !$isCollection )
                {
                    $unknownProperties[] = $requestedProperty;
                }
            }

            $xmlText .= ( "        <D:lockdiscovery/>\n" );

            $xmlText .= ( "      </D:prop>\n" .
                          "      <D:status>HTTP/1.1 200 OK</D:status>\n" .
                          "    </D:propstat>\n" );


            // List the non supported properties and mark with 404
            // This behavior (although recommended/standard) might
            // confuse some clients. Try commenting out if necessary...

            $xmlText .= "    <D:propstat>\n";
            $xmlText .= "      <D:prop>\n";
            foreach ( $unknownProperties as $unknownProperty )
            {
                $xmlText .= "        <D:" . $unknownProperty . "/>\n";
            }
            $xmlText .= "      </D:prop>\n";
            $xmlText .= "      <D:status>HTTP/1.1 404 Not Found</D:status>\n";
            $xmlText .= "    </D:propstat>\n";

            $xmlText .= "  </D:response>\n";
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
// @as use $GLOBALS
        $GLOBALS['ezc_response_body'] = $xmlText;

// @as        $dom = new DOMDocument( '1.0', 'utf-8' );
//        $success = $dom->loadXML( $xmlText );
//        if ( $success )
//            $this->appendLogEntry( "XML was parsed", 'outputCollectionContent' );
//        else
//            $this->appendLogEntry( "XML was NOT parsed $xmlText", 'outputCollectionContent' );

        // If we got this far: everything is OK.
        return eZWebDAVServer::OK_SILENT;
    }

    function xmlBody()
    {
        $xmlBody = $GLOBALS['ezc_post_body'];
        $this->XMLBodyRead = true;
        return $xmlBody;
    }
}

class eZWebDAVServerWrapper extends eZWebDAVServer
{

}
/*
    public static function appendLogEntry( $logString, $label = false )
    {
        if ( self::$useLogging )
        {
            if ( PHP_SAPI === 'cli' )
            {
                var_dump( $logString );
            }
            else
            {
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
                {
                    $text .= ' [' . $label . ']';
                }
                fwrite( $logFile, $text . "\n" );
                fclose( $logFile );
            }
        }
    }
*/
?>
