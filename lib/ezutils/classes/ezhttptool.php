<?php
/**
 * File containing the eZHTTPTool class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*! \defgroup eZHTTP HTTP utilities
    \ingroup eZUtils */

/*!
  \class eZHTTPTool ezhttptool.php
  \ingroup eZHTTP
  \brief Provides access to HTTP post,get and session variables

  See PHP manual on <a href="http://www.php.net/manual/fi/language.variables.predefined.php">Predefined Variables</a> for more information.

*/

class eZHTTPTool
{
    /*!
     Initializes the class. Use eZHTTPTool::instance to get a single instance.
    */
    function eZHTTPTool()
    {
        $this->UseFullUrl = false;
        $magicQuote = get_magic_quotes_gpc();

        if ( $magicQuote == 1 )
        {
            eZHTTPTool::removeMagicQuotes();
        }
    }

    /*!
     Sets the post variable \a $var to \a $value.
     \sa postVariable
    */
    function setPostVariable( $var, $value )
    {
        $_POST[$var] = $value;
    }

    /*!
     \return the HTTP post variable $var, or $fallbackValue if the post variable does not exist , or null if $fallbackValue is omitted.
     \sa variable
    */
   function postVariable( $var, $fallbackValue = null )
    {
        $ret = $fallbackValue;
        if ( isset( $_POST[$var] ) )
        {
            $ret = $_POST[$var];
        }
        else if ( $ret === null )
        {
            eZDebug::writeWarning( "Undefined post variable: $var",
                                   "eZHTTPTool" );
        }
        return $ret;
    }

    /*!
     \return true if the HTTP post variable $var exist.
     \sa hasVariable
    */
    function hasPostVariable( $var )
    {
        return isset( $_POST[$var] );
    }

    /*!
     Sets the get variable \a $var to \a $value.
     \sa getVariable
    */
    function setGetVariable( $var, $value )
    {
        $_GET[$var] = $value;
    }

    /*!
     \return the HTTP get variable $var, or $fallbackValue if the get variable does not exist, or null if $fallbackValue is omitted.
     \sa variable
    */
    function getVariable( $var, $fallbackValue = null )
    {
        $ret = $fallbackValue;
        if ( isset( $_GET[$var] ) )
        {
            $ret = $_GET[$var];
        }
        else if ( $ret === null )
        {
            eZDebug::writeWarning( "Undefined get variable: $var",
                                   "eZHTTPTool" );
        }
        return $ret;
    }

    /*!
     \return true if the HTTP get variable $var exist.
     \sa hasVariable
    */
    function hasGetVariable( $var )
    {
        return isset( $_GET[$var] );
    }

    /*!
     \return true if the HTTP post/get variable $var exists.
     \sa hasPostVariable
    */
    function hasVariable( $var )
    {

        if ( isset( $_POST[$var] ) )
        {
            return isset( $_POST[$var] );
        }
        else
        {
            return isset( $_GET[$var] );
        }
    }

    /*!
     \return the HTTP post/get variable $var, or $fallbackValue if the post/get variable does not exist , or null if $fallbackValue is omitted.
     \sa postVariable
    */
    function variable( $var, $fallbackValue = null )
    {
        if ( isset( $_POST[$var] ) )
        {
            return $_POST[$var];
        }
        else if ( isset( $_GET[$var] ) )
        {
            return $_GET[$var];
        }
        $ret = $fallbackValue;
        if ( $ret === null )
        {
            eZDebug::writeWarning( "Undefined post/get variable: $var",
                                   "eZHTTPTool" );
        }
        return $ret;
    }

    /*!
     \return the attributes for this object.
    */
    function attributes()
    {
        return array( "post", "get", "session" );
    }

    /*!
     \return true if the attribute $attr exist.
    */
    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    /*!
     \return the value for the attribute $attr or null if the attribute does not exist.
    */
    function attribute( $attr )
    {
        if ( $attr == "post" )
            return $_POST;
        if ( $attr == "get" )
            return $_GET;
        if ( $attr == "session" )
        {
            return eZSession::get();
        }
        $retValue = null;
        return $retValue;
    }

    /**
     * Return a unique instance of the eZHTTPTool class
     *
     * @return eZHTTPTool
     */
    static function instance()
    {
        if ( !isset( $GLOBALS["eZHTTPToolInstance"] ) ||
             !( $GLOBALS["eZHTTPToolInstance"] instanceof eZHTTPTool ) )
        {
            $GLOBALS["eZHTTPToolInstance"] = new eZHTTPTool();
            $GLOBALS["eZHTTPToolInstance"]->createPostVarsFromImageButtons();
        }

        return $GLOBALS["eZHTTPToolInstance"];
    }

    /**
     * Sends a http request to the specified host. Using https:// requires compiled in OpenSSL support.
     *
     * @param string $uri http/https address or only path to send request to current eZ Publish instance
     *        examples: http://ez.no, https://secure.ez.no or content/view/full/2
     * @param int|false $port Which port to connect to, default 80, uses port in $uri if present when $port = false
     * @param array|false $postParameters Optional post parameters array, if no post parameters are present, a GET request will be sent
     * @param string $userAgent User agent string, default will be 'eZ Publish'
     * @param bool $passthrough Will send result directly to client, default: true
     * @param array $cookies Optional hash of cookie name => values to add to http header
     * @return string|false String if http request, or false if an error occurs.
     *         If $passthrough = true, program will end here and send result directly to client.
    */
    static function sendHTTPRequest( $uri, $port = false, $postParameters = false, $userAgent = 'eZ Publish', $passthrough = true, array $cookies = array() )
    {
        preg_match( "/^((http[s]?:\/\/)([a-zA-Z0-9_.-]+)(\:(d+))?)?([\/]?[~]?(\.?[^.]+[~]?)*)/i", $uri, $matches );
        $protocol = $matches[2];
        $host     = $matches[3];
        $uriPort  = $matches[5];
        $path     = $matches[6];

        // Use port from uri if  set and port parameter evaluates to false
        if ( $uriPort && !$port )
            $port = $uriPort;
        else if ( !$port )
            $port = 80;

        if ( !$path )
        {
            $path = '/';
        }

        $data = '';
        if ( $postParameters )
        {
            $method = 'POST';
            foreach( $postParameters as $paramName => $paramData )
            {
                if ( !is_array( $paramData) )
                {
                    if ( $data !== '' )
                        $data .= '&';
                    $data .= urlencode( $paramName ) . '=' . urlencode( $paramData );
                }
                else
                {
                    foreach( $paramData as $value )
                    {
                        if ( $data !== '' )
                            $data .= '&';
                        $data .= urlencode( $paramName ) . '[]=' . urlencode( $value );
                    }
                }
            }
        }
        else
        {
            $method = 'GET';
        }

        if ( !$host )
        {
            $host = $_SERVER['HTTP_HOST'];
            $filename = $host;
            if ( $path[0] != '/' )
            {
                $path = $_SERVER['SCRIPT_NAME'] . '/' . $path;
            }
            else
            {
                $path = $_SERVER['SCRIPT_NAME'] . $path;
            }
        }
        else
        {
            if ( !$protocol || $protocol == 'https://' )
            {
                $filename = 'ssl://' . $host;
            }
            else
            {
                $filename = 'tcp://' . $host;
            }
        }

        // make sure we have a valid hostname or call to fsockopen() will fail
        $parsedUrl = parse_url( $filename );
        $ip = isset( $parsedUrl[ 'host' ] ) ? gethostbyname( $parsedUrl[ 'host' ] ) : '';
        $checkIP = ip2long( $ip );
        if ( $checkIP == -1 || $checkIP === false )
        {
            return false;
        }

        $fp = fsockopen( $filename, $port );

        // make sure we have a valid stream resource or calls to other file
        // functions will fail
        if ( !$fp )
        {
            return false;
        }

        $cookieStr = '';
        foreach ( $cookies as $name => $value )
        {
            if ( $cookieStr === '' )
                $cookieStr = "Cookie: $name=$value";
            else
                $cookieStr .= "; $name=$value";
        }

        $usePort = ( $port != 80 && $protocol === 'http://' ) && ( $port != 443 && $protocol === 'https://' );
        $request = $method . ' ' . $path . ' ' . 'HTTP/1.1' . "\r\n" .
             "Host: $host" . ( $usePort ? ":$port": '' ) . "\r\n" .
             "Accept: */*\r\n" .
             "Content-type: application/x-www-form-urlencoded\r\n" .
             "Content-length: " . strlen( $data ) . "\r\n" .
             $cookieStr .
             "User-Agent: $userAgent\r\n" .
             "Pragma: no-cache\r\n" .
             "Connection: close\r\n\r\n";

        fputs( $fp, $request );
        if ( $method == 'POST' )
        {
            fputs( $fp, $data );
        }

        $buf = '';
        if ( $passthrough )
        {
            ob_end_clean();
            $header = true;

            $character = '';
            while( $header )
            {
                $buffer = $character;
                while ( !feof( $fp ) )
                {
                    $character = fgetc( $fp );
                    if ( $character == "\r" )
                    {
                        fgetc( $fp );
                        $character = fgetc( $fp );
                        if ( $character == "\r" )
                        {
                            fgetc( $fp );
                            $header = false;
                        }
                        break;
                    }
                    else
                    {
                        $buffer .= $character;
                    }
                }

                header( $buffer );
            }

            header( 'Content-Location: ' . $protocol . $host . ( $usePort ? ":$port": '' ) . $path );

            fpassthru( $fp );
            eZExecution::cleanExit();
        }
        else
        {
            $buf = '';
            while ( !feof( $fp ) )
            {
                $buf .= fgets( $fp, 128 );
            }
        }

        fclose($fp);
        return $buf;
    }

    /*!
     \static
    */
    static function parseHTTPResponse( &$response, &$header, &$body )
    {
        if ( $response )
        {
            $crlf = "\r\n";

            // split header and body
            $pos = strpos( $response, $crlf . $crlf );
            if ( $pos !== false )
            {
                $headerBuf = substr( $response, 0, $pos );
                $body = substr( $response, $pos + 2 * strlen( $crlf ) );

                // parse headers
                $header = array();
                $lines = explode( $crlf, $headerBuf );
                foreach ( $lines as $line )
                {
                    if ( ( $pos = strpos( $line, ':') ) !== false )
                    {
                        $header[strtolower( trim( substr( $line, 0, $pos ) ) )] = trim( substr( $line, $pos+1 ) );
                    }
                }

                return true;
            }
        }

        return false;
    }

    /*!
     \static

     Returns username from HTTP authentication or false if not logged in.
     See http://en.php.net/features.http-auth why you can`t safely use $_SERVER['PHP_AUTH_USER'].
    */
    static function username()
    {
        $ini = eZINI::instance();
        $AUTHKey = $ini->variable( 'SiteSettings', 'HTTPAUTHServerVariable' );
        $matches = array();
        if ( array_key_exists( 'PHP_AUTH_USER', $_SERVER ) )
        {
            return $_SERVER['PHP_AUTH_USER'];
        }
        elseif ( substr( php_sapi_name(), 0, 3 ) == 'cgi' and
                 array_key_exists( $AUTHKey, $_SERVER ) and
                 preg_match('/Basic\s+(.*)$/i', $_SERVER[$AUTHKey], $matches ) )
        {
            list( $name, $password ) = explode( ':', base64_decode( $matches[1] ) );
            return $name;
        }
        return false;
    }

    /*!
     \static

     Returns password from HTTP authentication or false if not logged in.
     See http://en.php.net/features.http-auth why you can`t safely use $_SERVER['PHP_AUTH_PW'].
    */
    static function password()
    {
        $ini = eZINI::instance();
        $AUTHKey = $ini->variable( 'SiteSettings', 'HTTPAUTHServerVariable' );
        $matches = array();
        if ( array_key_exists( 'PHP_AUTH_PW', $_SERVER ) )
        {
            return $_SERVER['PHP_AUTH_PW'];
        }
        elseif ( substr( php_sapi_name(), 0, 3 ) == 'cgi' and
                 array_key_exists( $AUTHKey, $_SERVER ) and
                 preg_match('/Basic\s+(.*)$/i', $_SERVER[$AUTHKey], $matches ) )
        {
            list( $name, $password ) = explode( ':', base64_decode( $matches[1] ) );
            return $password;
        }
        return false;
    }

    /*!
     \static
     Sends a redirect path to the browser telling it to
     load the new path.
     By default only \a $path is required, other parameters
     will be fetched automatically to create a HTTP/1.1
     compatible header.
     The input \a $parameters may contain the following keys.
     - host - the name of the host, default will fetch the currenty hostname
     - protocol - which protocol to use, default will use HTTP
     - port - the port on the host
     - username - a username which is required to login on the site
     - password - if username is supplied this password will be used for authentication

     The path may be specified relativily \c rel/ative, from root \c /a/root, with hostname
     change \c //myhost.com/a/root/rel/ative, with protocol \c http://myhost.com/a/root/rel/ative.
     Also port may be placed in the path string.
     It is recommended that the path only contain a plain root path and instead send the rest
     as optional parameters, the support for different kinds of paths is only incase you get
     URLs externally which contains any of the above cases.

     \note The redirection does not happen immedietaly and the script execution will continue.
    */
    static function createRedirectUrl( $path, $parameters = array() )
    {
        $parameters = array_merge( array( 'host' => false,
                                          'protocol' => false,
                                          'port' => false,
                                          'username' => false,
                                          'password' => false,
                                          'override_host' => false,
                                          'override_protocol' => false,
                                          'override_port' => false,
                                          'override_username' => false,
                                          'override_password' => false,
                                          'pre_url' => true ),
                                   $parameters );
        $host = $parameters['host'];
        $protocol = $parameters['protocol'];
        $port = $parameters['port'];
        $username = $parameters['username'];
        $password = $parameters['password'];
        if ( preg_match( '#^([a-zA-Z0-9]+):(.+)$#', $path, $matches ) )
        {
            if ( $matches[1] )
                $protocol = $matches[1];
            $path = $matches[2];

        }
        if ( preg_match( '#^//((([a-zA-Z0-9_.]+)(:([a-zA-Z0-9_.]+))?)@)?([^./:]+(\.[^./:]+)*)(:([0-9]+))?(.*)$#', $path, $matches ) )
        {
            if ( $matches[6] )
            {
                $host = $matches[6];
            }

            if ( $matches[3] )
                $username = $matches[3];
            if ( $matches[5] )
                $password = $matches[5];
            if ( $matches[9] )
                $port = $matches[9];
            $path = $matches[10];
        }
        if ( $parameters['pre_url'] )
        {
            if ( strlen( $path ) > 0 and
                 $path[0] != '/' )
            {
                $preURL = eZSys::serverVariable( 'SCRIPT_URL' );
                if ( strlen( $preURL ) > 0 and
                     $preURL[strlen($preURL) - 1] != '/' )
                    $preURL .= '/';
                $path = $preURL . $path;
            }
        }

        if ( $parameters['override_host'] )
            $host = $parameters['override_host'];
        if ( $parameters['override_port'] )
            $port = $parameters['override_port'];
        if ( !is_string( $host ) )
            $host = eZSys::hostname();
        if ( !is_string( $protocol ) )
        {
            $protocol = 'http';
            // Default to https if SSL is enabled

            // Check if SSL port is defined in site.ini
            $ini = eZINI::instance();
            $sslPort = 443;
            if ( $ini->hasVariable( 'SiteSettings', 'SSLPort' ) )
            {
                $sslPort = $ini->variable( 'SiteSettings', 'SSLPort' );
            }

            if ( eZSys::serverPort() == $sslPort )
            {
                $protocol = 'https';
                $port = false;
            }
        }
        if ( $parameters['override_protocol'] )
            $host = $parameters['override_protocol'];

        $uri = $protocol . '://';
        if ( $parameters['override_username'] )
            $username = $parameters['override_username'];
        if ( $parameters['override_password'] )
            $password = $parameters['override_password'];
        if ( $username )
        {
            $uri .= $username;
            if ( $password )
                $uri .= ':' . $password;
            $uri .= '@';
        }
        $uri .= $host;
        if ( $port )
            $uri .= ':' . $port;
        $uri .= $path;
        return $uri;
    }

    /**
     * \static
     * Performs an HTTP redirect.
     *
     * \param  $path  The path to redirect
     * \param  $parameters  \see createRedirectUrl()
     * \param  $status  The HTTP status code as a string
     * \param  $encodeURL  Encode the URL. This should normally be true, but
     * may be set to false to avoid double encoding when redirect() is called
     * twice.
     */
    static function redirect( $path, $parameters = array(), $status = false, $encodeURL = true )
    {
        $url = eZHTTPTool::createRedirectUrl( $path, $parameters );
        if ( strlen( $status ) > 0 )
        {
            header( $_SERVER['SERVER_PROTOCOL'] .  " " . $status );
            eZHTTPTool::headerVariable( "Status", $status );
        }

        if ( $encodeURL )
        {
            $url = eZURI::encodeURL( $url );
        }

        eZHTTPTool::headerVariable( 'Location', $url );

        /* Fix for redirecting using workflows and apache 2 */
        echo '<HTML><HEAD>';
        echo '<META HTTP-EQUIV="Refresh" Content="0;URL='. htmlspecialchars( $url ) .'">';
        echo '<META HTTP-EQUIV="Location" Content="'. htmlspecialchars( $url ) .'">';
        echo '</HEAD><BODY></BODY></HTML>';
    }

    /*!
     \static
     Sets the header variable \a $headerName to have the data \a $headerData.
     \note Calls PHPs header() with a constructed string.
    */
    static function headerVariable( $headerName, $headerData )
    {
        header( $headerName .': '. $headerData );
    }

    static function removeMagicQuotes()
    {
        foreach ( array_keys( $_POST ) as $key )
        {
            if ( !is_array( $_POST[$key] ) )
            {
                $_POST[$key] = str_replace( "\'", "'", $_POST[$key] );
                $_POST[$key] = str_replace( '\"', '"', $_POST[$key] );
                $_POST[$key] = str_replace( '\\\\', '\\', $_POST[$key] );
            }
            else
            {
                foreach ( array_keys( $_POST[$key] ) as $arrayKey )
                {
                    $_POST[$key][$arrayKey] = str_replace( "\'", "'", $_POST[$key][$arrayKey] );
                    $_POST[$key][$arrayKey] = str_replace( '\"', '"', $_POST[$key][$arrayKey] );
                    $_POST[$key][$arrayKey] = str_replace( '\\\\', '\\', $_POST[$key][$arrayKey] );
                }
            }
        }
        foreach ( array_keys( $_GET ) as $key )
        {
            if ( !is_array( $_GET[$key] ) )
            {
                $_GET[$key] = str_replace( "\'", "'", $_GET[$key] );
                $_GET[$key] = str_replace( '\"', '"', $_GET[$key] );
                $_GET[$key] = str_replace( '\\\\', '\\', $_GET[$key] );
            }
            else
            {
                foreach ( array_keys( $_GET[$key] ) as $arrayKey )
                {
                    $_GET[$key][$arrayKey] = str_replace( "\'", "'", $_GET[$key][$arrayKey] );
                    $_GET[$key][$arrayKey] = str_replace( '\"', '"', $_GET[$key][$arrayKey] );
                    $_GET[$key][$arrayKey] = str_replace( '\\\\', '\\', $_GET[$key][$arrayKey] );
                }
            }
        }
    }

    function createPostVarsFromImageButtons()
    {
        foreach ( array_keys( $_POST ) as $key )
        {
            if ( substr( $key, -2 ) == '_x' )
            {
                $yKey = substr( $key, 0, -2 ) . '_y';
                if ( array_key_exists( $yKey, $_POST ) )
                {
                    $keyClean = substr( $key, 0, -2 );
                    $matches = array();
                    if ( preg_match( "/_(\d+)$/", $keyClean, $matches ) )
                    {
                        $value = $matches[1];
                        $keyClean = preg_replace( "/(_\d+)$/","", $keyClean );
                        $_POST[$keyClean] = $value;
//                         eZDebug::writeDebug( $_POST[$keyClean], "We have create new  Post Var with name $keyClean and value $value:" );
                    }
                    else
                    {
                        $_POST[$keyClean] = true;
//                         eZDebug::writeDebug( $_POST[$keyClean], "We have create new  Post Var with name $keyClean and value true:" );
                    }
                }
            }
        }
    }

    /**
     * Return the session id
     *
     * @deprecated Since 4.4, use ->sessionID instead!
     * @return string
     */
    function getSessionKey()
    {
        return session_id();
    }

    /**
     * Sets a new session id
     *
     * @deprecated Since 4.4, use ->setSessionID instead!
     * @param string $sessionKey Allowed characters in the range a-z A-Z 0-9 , (comma) and - (minus)
     * @return string Current(old) session id
    */
    function setSessionKey( $sessionKey )
    {
        return session_id( $sessionKey );
    }

    /**
     * Sets the session variable $name to value $value.
     *
     * @param string $name
     * @param mixed $value
    */
    function setSessionVariable( $name, $value )
    {
        eZSession::set( $name, $value );
    }

    /**
     * Unset the session variable $name
     *
     * @param string $name
     * @return bool
     */
    function removeSessionVariable( $name )
    {
        return eZSession::unsetkey( $name );
    }

    /**
     * Check if session variable $name exists
     *
     * @param string $name
     * @param bool $forceStart Force session start if true (default)
     * @return bool|null Null if session has not started and $forceStart is false
     */
    function hasSessionVariable( $name, $forceStart = true )
    {
        return eZSession::issetkey( $name, $forceStart );
    }

    /**
     * Get session variable $name
     *
     * @param string $name
     * @param mixed $fallbackValue Return this if session has not started OR name is undefined
     *              if null(default), then force start session and return null if undefined.
     * @return mixed ByRef
     */
    function &sessionVariable( $name, $fallbackValue = null )
    {
        return eZSession::get( $name, $fallbackValue );
    }

    /**
     * Return the session id
     *
     * @return string
     */
    function sessionID()
    {
        return session_id();
    }

    /**
     * Sets a new session id
     *
     * @param string $sessionKey Allowed characters in the range a-z A-Z 0-9 , (comma) and - (minus)
     * @return string Current(old) session id
    */
    function setSessionID( $sessionKey )
    {
        return session_id( $sessionKey );
    }

    /*!
     \static
     \param $url
     \param $justCheckURL if true, we should check url only not downloading data.
     \return data from \p $url, false if invalid URL
    */
    static function getDataByURL( $url, $justCheckURL = false, $userAgent = false )
    {
        // First try CURL
        if ( extension_loaded( 'curl' ) )
        {
            $ch = curl_init( $url );
            if ( $justCheckURL )
            {
                curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
                curl_setopt( $ch, CURLOPT_TIMEOUT, 15 );
                curl_setopt( $ch, CURLOPT_FAILONERROR, 1 );
                curl_setopt( $ch, CURLOPT_NOBODY, 1 );
            }

            if ( $userAgent )
            {
                curl_setopt( $ch, CURLOPT_USERAGENT, $userAgent );
            }

            $ini = eZINI::instance();
            $proxy = $ini->hasVariable( 'ProxySettings', 'ProxyServer' ) ? $ini->variable( 'ProxySettings', 'ProxyServer' ) : false;
            // If we should use proxy
            if ( $proxy )
            {
                curl_setopt ( $ch, CURLOPT_PROXY , $proxy );
                $userName = $ini->hasVariable( 'ProxySettings', 'User' ) ? $ini->variable( 'ProxySettings', 'User' ) : false;
                $password = $ini->hasVariable( 'ProxySettings', 'Password' ) ? $ini->variable( 'ProxySettings', 'Password' ) : false;
                if ( $userName )
                {
                    curl_setopt ( $ch, CURLOPT_PROXYUSERPWD, "$userName:$password" );
                }
            }
            // If we should check url without downloading data from it.
            if ( $justCheckURL )
            {
                if ( !curl_exec( $ch ) )
                {
                    curl_close( $ch );
                    return false;
                }

                curl_close( $ch );
                return true;
            }
            // Getting data
            ob_start();
            if ( !curl_exec( $ch ) )
            {
                curl_close( $ch );
                ob_end_clean();
                return false;
            }

            curl_close ( $ch );
            $data = ob_get_contents();
            ob_end_clean();

            return $data;
        }

        if ( $userAgent )
        {
            ini_set( 'user_agent', $userAgent );
        }

        // Open and read url
        $fid = fopen( $url, 'r' );
        if ( $fid === false )
        {
            return false;
        }

        if ( $justCheckURL )
        {
            if ( $fid )
                fclose( $fid );

            return $fid;
        }

        $data = "";
        do
        {
            $dataBody = fread( $fid, 8192 );
            if ( strlen( $dataBody ) == 0 )
                break;
            $data .= $dataBody;
        } while( true );

        fclose( $fid );
        return $data;
    }
}

?>
