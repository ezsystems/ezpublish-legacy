<?php
//
// Definition of eZHTTPTool class
//
// Created on: <18-Apr-2002 14:05:21 amos>
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

/*! \defgroup eZHTTP HTTP utilities
    \ingroup eZUtils */

/*!
  \class eZHTTPTool ezhttptool.php
  \ingroup eZHTTP
  \brief Provides access to HTTP post,get and session variables

  See PHP manual on <a href="http://www.php.net/manual/fi/language.variables.predefined.php">Predefined Variables</a> for more information.

*/

require_once( "lib/ezutils/classes/ezdebug.php" );
require_once( "lib/ezutils/classes/ezsession.php" );
//include_once( "lib/ezutils/classes/ezsys.php" );

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
     \return a reference to the HTTP post variable $var, or null if it does not exist.
     \sa variable
    */
    function postVariable( $var )
    {
        $ret = null;
        if ( isset( $_POST[$var] ) )
            $ret = $_POST[$var];
        else
            eZDebug::writeWarning( "Undefined post variable: $var",
                                   "eZHTTPTool" );
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
     \return a reference to the HTTP get variable $var, or null if it does not exist.
     \sa variable
    */
    function getVariable( $var )
    {
        $ret = null;
        if ( isset( $_GET[$var] ) )
            $ret = $_GET[$var];
        else
            eZDebug::writeWarning( "Undefined get variable: $var",
                                   "eZHTTPTool" );
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
     \return a reference to the HTTP post/get variable $var, or null if it does not exist.
     \sa postVariable
    */
    function variable( $var )
    {
        if ( isset( $_POST[$var] ) )
        {
            return $_POST[$var];
        }
        else if ( isset( $_GET[$var] ) )
        {
            return $_GET[$var];
        }
        $ret = false;
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
            return $_SESSION;
        }
        $retValue = null;
        return $retValue;
    }

    /*!
     \return the unique instance of the HTTP tool
    */
    static function instance()
    {
        if ( !isset( $GLOBALS["eZHTTPToolInstance"] ) ||
             !( $GLOBALS["eZHTTPToolInstance"] instanceof eZHTTPTool ) )
        {
            $GLOBALS["eZHTTPToolInstance"] = new eZHTTPTool();
            $GLOBALS["eZHTTPToolInstance"]->createPostVarsFromImageButtons();
            eZSessionStart();
        }

        return $GLOBALS["eZHTTPToolInstance"];
    }

    /*!
     \static

     Sends a http request to the specified host. Using https:// requires PHP 4.3.0, and compiled in OpenSSL support.

     \param http/https address, only path to send request to eZ Publish.
            examples: http://ez.no, https://secure.ez.no, ssl://secure.ez.no, content/view/full/2
     \param port, default 80
     \param post parameters array (optional), if no post parameters are present, a get request will be send.
     \param user agent, default will be eZ Publish
     \param passtrough, will send result directly to client, default false

     \return result if http request, or return false if an error occurs.
             If pipetrough, program will end here.

    */
    static function sendHTTPRequest( $uri, $port = 80, $postParameters = false, $userAgent = 'eZ Publish', $passtrough = true )
    {
        preg_match( "/^((http[s]?:\/\/)([a-zA-Z0-9_.]+))?([\/]?[~]?(\.?[^.]+[~]?)*)/i", $uri, $matches );
        $protocol = $matches[2];
        $host = $matches[3];
        $path = $matches[4];
        if ( !$path )
        {
            $path = '/';
        }

        $data = '';
        if ( $postParameters )
        {
            $method = 'POST';
            $dataCount = 0;
            foreach( array_keys( $postParameters ) as $paramName )
            {
                if ( $dataCount > 0 )
                {
                    $data .= '&';
                }
                ++$dataCount;
                if ( !is_array( $postParameters[$paramName] ) )
                {
                    $data .= urlencode( $paramName ) . '=' . urlencode( $postParameters[$paramName] );
                }
                else
                {
                    foreach( $postParameters[$paramName] as $value )
                    {
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
        else{
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
        if ( $checkIP == -1 or $checkIP === false )
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

        $request = $method . ' ' . $path . ' ' . 'HTTP/1.1' . "\r\n" .
             "Host: $host\r\n" .
             "Accept: */*\r\n" .
             "Content-type: application/x-www-form-urlencoded\r\n" .
             "Content-length: " . strlen( $data ) . "\r\n" .
             "User-Agent: $userAgent\r\n" .
             "Pragma: no-cache\r\n" .
             "Connection: close\r\n\r\n";

        fputs( $fp, $request );
        if ( $method == 'POST' )
        {
            fputs( $fp, $data );
        }

        $buf = '';
        if ( $passtrough )
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

            header( 'Content-Location: ' . $uri );

            fpassthru( $fp );
            require_once( 'lib/ezutils/classes/ezexecution.php' );
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

    static function redirect( $path, $parameters = array(), $status = false )
    {
        $url = eZHTTPTool::createRedirectUrl( $path, $parameters );
        if ( strlen( $status ) > 0 )
        {
            header( $_SERVER['SERVER_PROTOCOL'] .  " " . $status );
            eZHTTPTool::headerVariable( "Status", $status );
        }

        $url = eZURI::encodeURL( $url );

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

    /*!
     Sets the session variable $name to value $value.
    */
    function getSessionKey()
    {
        return session_id();
    }

    function setSessionKey( $sessionKey )
    {
        return session_id( $sessionKey );
    }

    function setSessionVariable( $name, $value )
    {
        $_SESSION[$name] =& $value;
    }

    /*!
     Removes the session variable $name.
    */
    function removeSessionVariable( $name )
    {
        unset( $_SESSION[$name] );
    }

    /*!
     \return true if the session variable $name exist.
    */
    function hasSessionVariable( $name )
    {
        return isset( $_SESSION[$name] );
    }

    /*!
     \return the session variable $name.
    */
    function &sessionVariable( $name )
    {
        return $_SESSION[$name];
    }

    /*!
     \return the session id
    */
    function sessionID()
    {
        return session_id();
    }

    /*!
     \static
     \param $url
     \param $justCheckURL if true, we should check url only not downloading data.
     \return data from \p $url, false if invalid URL
    */
    static function getDataByURL( $url, $justCheckURL = false )
    {
        // First try CURL
        if ( extension_loaded( 'curl' ) )
        {
            $ch = curl_init( $url );
            if ( $justCheckURL )
            {
                curl_setopt( $ch, CURLOPT_TIMEOUT, 15 );
                curl_setopt( $ch, CURLOPT_FAILONERROR, 1 );
                curl_setopt( $ch, CURLOPT_NOBODY, 1 );
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
                    return false;

                curl_close( $ch );
                return true;
            }
            // Getting data
            ob_start();
            if ( !curl_exec( $ch ) )
                return false;

            curl_close ( $ch );
            $data = ob_get_contents();
            ob_end_clean();

            return $data;
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
