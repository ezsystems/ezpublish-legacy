<?php
//
// Definition of eZHTTPTool class
//
// Created on: <18-Apr-2002 14:05:21 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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

  \todo All set/get functions should be unified
*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezsessioncache.php" );

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
            return $GLOBALS["HTTP_SESSION_VARS"];
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

	function removeMagicQuotes()
	{
        foreach ( array_keys( $GLOBALS["HTTP_POST_VARS"] ) as $key )
        {
			if ( !is_array( $GLOBALS["HTTP_POST_VARS"][$key] ) )
			{
				$GLOBALS["HTTP_POST_VARS"][$key] = str_replace( "\'", "'", $GLOBALS["HTTP_POST_VARS"][$key] );
				$GLOBALS["HTTP_POST_VARS"][$key] = str_replace( '\"', '"', $GLOBALS["HTTP_POST_VARS"][$key] );
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
                        $value =  $matches[1];
                        $keyClean = preg_replace( "/(_\d+)$/","", $keyClean );
                        $GLOBALS["HTTP_POST_VARS"][$keyClean] = $value;
                        eZDebug::writeDebug( $GLOBALS["HTTP_POST_VARS"][$keyClean], "We have create new  Post Var with name $keyClean and value $value:" );
                    }
                    else
                    {
                        $GLOBALS["HTTP_POST_VARS"][$keyClean] = true;
                        eZDebug::writeDebug( $GLOBALS["HTTP_POST_VARS"][$keyClean], "We have create new  Post Var with name $keyClean and value true:" );
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
        global $HTTP_SESSION_VARS;
        session_register( $name );
        $HTTP_SESSION_VARS[$name] =& $value;
    }

    /*!
     Removes the session variable $name.
    */
    function removeSessionVariable( $name )
    {
        eZSessionStart();
        session_unregister( $name );
    }

    /*!
     \return true if the session variable $name exist.
    */
    function hasSessionVariable( $name )
    {
        eZSessionStart();
        global $HTTP_SESSION_VARS;
        return isset( $HTTP_SESSION_VARS[$name] );
    }

    /*!
     \return the session variable $name.
    */
    function &sessionVariable( $name )
    {
        eZSessionStart();
        global $HTTP_SESSION_VARS;
        return $HTTP_SESSION_VARS[$name];
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
