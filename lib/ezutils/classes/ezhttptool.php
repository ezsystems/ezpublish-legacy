<?php
//
// Definition of eZHTTPTool class
//
// Created on: <18-Apr-2002 14:05:21 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \defgroup eZHTTP HTTP utilities
    \ingroup eZUtils */

/*!
  \class eZHTTPTool ezhttptool.php
  \ingroup eZHTTP
  \brief Provides access to HTTP post,get and session variables

  See PHP manual on <a href="http://www.php.net/manual/fi/language.variables.predefined.php">Predefined Variables</a> for more information.

*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezsession.php" );
include_once( "lib/ezutils/classes/ezsys.php" );

class eZHTTPTool
{
    /*!
     Initializes the class. Use eZHTTPTool::instance to get a single instance.
    */
    function eZHTTPTool()
    {
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
        $post_vars =& $GLOBALS["HTTP_POST_VARS"];
        $post_vars[$var] =& $value;
    }

    /*!
     \return a reference to the HTTP post variable $var, or null if it does not exist.
     \sa variable
    */
    function &postVariable( $var )
    {
        $post_vars =& $GLOBALS["HTTP_POST_VARS"];
        $ret = null;
        if ( isset( $post_vars[$var] ) )
            $ret =& $post_vars[$var];
        else
            eZDebug::writeWarning( "Undefined post variable: $var",
                                   "eZHTTPTool" );
        return $ret;
    }

    /*!
     \return true if the HTTP post variable $var exist.
     \sa hasVariable
    */
    function &hasPostVariable( $var )
    {
        $post_vars =& $GLOBALS["HTTP_POST_VARS"];
        return isset( $post_vars[$var] );
    }

    /*!
     Sets the get variable \a $var to \a $value.
     \sa getVariable
    */
    function setGetVariable( $var, $value )
    {
        $get_vars =& $GLOBALS["_GET"];
        $get_vars[$var] =& $value;
    }

    /*!
     \return a reference to the HTTP get variable $var, or null if it does not exist.
     \sa variable
    */
    function &getVariable( $var )
    {
        $get_vars =& $GLOBALS["_GET"];
        $ret = null;
        if ( isset( $get_vars[$var] ) )
            $ret =& $get_vars[$var];
        else
            eZDebug::writeWarning( "Undefined get variable: $var",
                                   "eZHTTPTool" );
        return $ret;
    }

    /*!
     \return true if the HTTP get variable $var exist.
     \sa hasVariable
    */
    function &hasGetVariable( $var )
    {
        $get_vars =& $GLOBALS["_GET"];
        return isset( $get_vars[$var] );
    }

    /*!
     \return true if the HTTP post/get variable $var exist.
     \sa hasPostVariable
    */
    function hasVariable( $var )
    {

        if ( isset( $GLOBALS["HTTP_POST_VARS"][$var] ) )
            return isset( $GLOBALS["HTTP_POST_VARS"][$var] );
        else
        {
            return isset( $GLOBALS["_GET"][$var] );
        }
    }

    /*!
     \return a reference to the HTTP post/get variable $var, or null if it does not exist.
     \sa postVariable
    */
    function variable( $var )
    {
        if ( isset( $GLOBALS["HTTP_POST_VARS"][$var] ) )
            return $GLOBALS["HTTP_POST_VARS"][$var];
        else
        {
            if ( isset( $GLOBALS["_GET"][$var] ) )
                return $GLOBALS["_GET"][$var];
            else
                return false;
        }
    }

    /*!
     \return the attributes for this object.
    */
    function &attributes()
    {
        return array( "post", "get", "session" );
    }

    /*!
     \return true if the attribute $attr exist.
    */
    function hasAttribute( $attr )
    {
        return $attr == "post" or $attr == "get" or $attr == "session";
    }

    /*!
     \return the value for the attribute $attr or null if the attribute does not exist.
    */
    function &attribute( $attr )
    {
        if ( $attr == "post" )
            return $GLOBALS["HTTP_POST_VARS"];
        if ( $attr == "get" )
            return $GLOBALS["_GET"];
        if ( $attr == "session" )
        {
            eZSessionStart();
            return $_SESSION;
//             return $GLOBALS["HTTP_SESSION_VARS"];
        }
        return null;
    }

    /*!
     \return the unique instance of the HTTP tool
    */
    function &instance()
    {
        $instance =& $GLOBALS["eZHTTPToolInstance"];
        if ( get_class( $instance ) != "ezhttptool" )
        {
            $instance = new eZHTTPTool();
            $instance->createPostVarsFromImageButtons();
        }
        return $instance;
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
    function createRedirectUrl( $path, $parameters = array() )
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
            $ini =& eZINI::instance();
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

    function redirect( $path, $parameters = array() )
    {
        $uri = eZHTTPTool::createRedirectUrl( $path, $parameters );
        eZHTTPTool::headerVariable( 'Location', $uri );

        /* Fix for redirecting using workflows and apache 2 */
        echo '<HTML><HEAD>';
        echo '<META HTTP-EQUIV="Refresh" Content="0;URL='. htmlspecialchars( $uri ) .'">';
        echo '<META HTTP-EQUIV="Location" Content="'. htmlspecialchars( $uri ) .'">';
        echo '</HEAD><BODY></BODY></HTML>';
    }

    /*!
     \static
     Sets the header variable \a $headerName to have the data \a $headerData.
     \note Calls PHPs header() with a constructed string.
    */
    function headerVariable( $headerName, $headerData )
    {
        header( $headerName .': '. $headerData );
    }

	function removeMagicQuotes()
	{
        foreach ( array_keys( $GLOBALS["HTTP_POST_VARS"] ) as $key )
        {
			if ( !is_array( $GLOBALS["HTTP_POST_VARS"][$key] ) )
			{
				$GLOBALS["HTTP_POST_VARS"][$key] = str_replace( "\'", "'", $GLOBALS["HTTP_POST_VARS"][$key] );
				$GLOBALS["HTTP_POST_VARS"][$key] = str_replace( '\"', '"', $GLOBALS["HTTP_POST_VARS"][$key] );
				$GLOBALS["HTTP_POST_VARS"][$key] = str_replace( '\\\\', '\\', $GLOBALS["HTTP_POST_VARS"][$key] );
			}
            else
            {
                foreach ( array_keys( $GLOBALS["HTTP_POST_VARS"][$key] ) as $arrayKey )
                {
                    $GLOBALS["HTTP_POST_VARS"][$key][$arrayKey] = str_replace( "\'", "'", $GLOBALS["HTTP_POST_VARS"][$key][$arrayKey] );
                    $GLOBALS["HTTP_POST_VARS"][$key][$arrayKey] = str_replace( '\"', '"', $GLOBALS["HTTP_POST_VARS"][$key][$arrayKey] );
                    $GLOBALS["HTTP_POST_VARS"][$key][$arrayKey] = str_replace( '\\\\', '\\', $GLOBALS["HTTP_POST_VARS"][$key][$arrayKey] );
                }
            }
        }
        foreach ( array_keys( $GLOBALS["_GET"] ) as $key )
        {
			if ( !is_array( $GLOBALS["_GET"][$key] ) )
			{
				$GLOBALS["_GET"][$key] = str_replace( "\'", "'", $GLOBALS["_GET"][$key] );
				$GLOBALS["_GET"][$key] = str_replace( '\"', '"', $GLOBALS["_GET"][$key] );
				$GLOBALS["_GET"][$key] = str_replace( '\\\\', '\\', $GLOBALS["_GET"][$key] );
			}
            else
            {
                foreach ( array_keys( $GLOBALS["_GET"][$key] ) as $arrayKey )
                {
                    $GLOBALS["_GET"][$key][$arrayKey] = str_replace( "\'", "'", $GLOBALS["_GET"][$key][$arrayKey] );
                    $GLOBALS["_GET"][$key][$arrayKey] = str_replace( '\"', '"', $GLOBALS["_GET"][$key][$arrayKey] );
                    $GLOBALS["_GET"][$key][$arrayKey] = str_replace( '\\\\', '\\', $GLOBALS["_GET"][$key][$arrayKey] );
                }
            }
        }
	}

    function createPostVarsFromImageButtons()
    {
        foreach ( array_keys( $GLOBALS["HTTP_POST_VARS"] ) as $key )
        {
            if ( substr( $key, -2 ) == '_x' )
            {
                $yKey = substr( $key, 0, -2 ) . '_y';
                if ( array_key_exists( $yKey, $GLOBALS["HTTP_POST_VARS"] ) )
                {
                    $keyClean = substr( $key, 0, -2 );
                    $matches = array();
                    if ( preg_match( "/_(\d+)$/", $keyClean, $matches ) )
                    {
                        $value = $matches[1];
                        $keyClean = preg_replace( "/(_\d+)$/","", $keyClean );
                        $GLOBALS["HTTP_POST_VARS"][$keyClean] = $value;
//                         eZDebug::writeDebug( $GLOBALS["HTTP_POST_VARS"][$keyClean], "We have create new  Post Var with name $keyClean and value $value:" );
                    }
                    else
                    {
                        $GLOBALS["HTTP_POST_VARS"][$keyClean] = true;
//                         eZDebug::writeDebug( $GLOBALS["HTTP_POST_VARS"][$keyClean], "We have create new  Post Var with name $keyClean and value true:" );
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
        eZSessionStart();
        return session_id();
    }

    function setSessionKey( $sessionKey )
    {
        eZSessionStart();
        return session_id( $sessionKey );
    }

    function setSessionVariable( $name, $value )
    {
        eZSessionStart();
//         session_register( $name );
        $_SESSION[$name] = $value;
    }

    /*!
     Removes the session variable $name.
    */
    function removeSessionVariable( $name )
    {
        eZSessionStart();
//         session_unregister( $name );
        unset( $_SESSION[$name] );
    }

    /*!
     \return true if the session variable $name exist.
    */
    function hasSessionVariable( $name )
    {
        eZSessionStart();
//         global $HTTP_SESSION_VARS;
        return isset( $_SESSION[$name] );
    }

    /*!
     \return the session variable $name.
    */
    function &sessionVariable( $name )
    {
        eZSessionStart();
//         global $HTTP_SESSION_VARS;
        return $_SESSION[$name];
    }

    /*!
     \return the session id
    */
    function sessionID()
    {
        eZSessionStart();
        return session_id();
    }
}

?>
