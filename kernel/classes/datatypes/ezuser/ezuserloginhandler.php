<?php
//
// Definition of eZUserLoginHandler class
//
// Created on: <24-Jul-2003 15:11:57 wy>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \file ezuserloginhandler.php
*/

/*!
  \class eZUserLoginHandler ezuserloginhandler.php
  \ingroup eZDatatype
  \brief The class eZUserLoginHandler does

*/

define( 'EZ_LOGIN_HANDLER_AVAILABLE_ARRAY' , 'eZLoginHandlerAvailbleArray' ); // stores untested login handlers for login
define( 'EZ_LOGIN_HANDLER_STEP', 'eZLoginHandlerStep' );
define( 'EZ_LOGIN_HANDLER_USER_INFO', 'eZLoginHandlerUserInfo' );
define( 'EZ_LOGIN_HANDLER_LAST_CHECK_REDIRECT', 'eZLoginHandlerLastCheckRedirect' );
define( 'EZ_LOGIN_HANDLER_FORCE_LOGIN', 'eZLoginHandlerForceLogin' );

define( 'EZ_LOGIN_HANDLER_STEP_PRE_CHECK_USER_INFO', 0 );
define( 'EZ_LOGIN_HANDLER_STEP_PRE_COLLECT_USER_INFO', 1 );
define( 'EZ_LOGIN_HANDLER_STEP_POST_COLLECT_USER_INFO', 2 );
define( 'EZ_LOGIN_HANDLER_STEP_CHECK_USER', 3 );
define( 'EZ_LOGIN_HANDLER_STEP_LOGIN_USER', 4 );

class eZUserLoginHandler
{
    /*!
     Constructor
    */
    function eZUserLoginHandler()
    {
    }

    /*!
     \static
     Clean up session variables used by the login procedure.
    */
    function sessionCleanup()
    {
        $http =& eZHTTPTool::instance();

        $valueList = array( EZ_LOGIN_HANDLER_AVAILABLE_ARRAY,
                            EZ_LOGIN_HANDLER_STEP,
                            EZ_LOGIN_HANDLER_USER_INFO,
                            EZ_LOGIN_HANDLER_LAST_CHECK_REDIRECT,
                            EZ_LOGIN_HANDLER_FORCE_LOGIN );

        foreach ( $valueList as $value )
        {
            if ( $http->hasSessionVariable( $value ) )
            {
                $http->removeSessionVariable( $value );
            }
        }

        $ini =& eZINI::instance();
        $handlerList = array( 'standard' );
        if ( $ini->hasVariable( 'UserSettings', 'LoginHandler' ) )
        {
            $handlerList = $ini->variable( 'UserSettings', 'LoginHandler' );
        }

        foreach( $handlerList as $handler )
        {
            $loginHandler =& eZUserLoginHandler::instance( $handler );
            $loginHandler->sessionCleanup();
        }
    }

    /*!
     Fetch object instance of specified login handler.

     \param login handler name

     \return Login handler object
     */
    function &instance( $protocol = "standard" )
    {
        //eZDebug::writeNotice( 'Trying to fetch loginhandler : ' . $protocol,
        //                      'eZUserLoginHandler::instance()' );

        if ( $protocol == "standard" )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            $impl = new eZUser( 0 );
            return $impl;
        }
        else
        {
            $ezuserFile = 'kernel/classes/datatypes/ezuser/ez' . strtolower( $protocol ) . 'user.php';
            if ( file_exists( $ezuserFile ) )
            {
                include_once( $ezuserFile );
                $className = 'eZ' . $protocol . 'User';
                $impl = new $className();
                return $impl;
            }
            else // check in extensions
            {
                include_once( 'lib/ezutils/classes/ezextension.php' );
                $ini =& eZINI::instance();
                $extensionDirectories = $ini->variable( 'UserSettings', 'ExtensionDirectory' );
                $directoryList = eZExtension::expandedPathList( $extensionDirectories, 'login_handler' );

                foreach( $directoryList as $directory )
                {
                    $userFile = $directory . '/ez' . strtolower( $protocol ) . 'user.php';
                    if ( file_exists( $userFile ) )
                    {
                        include_once( $userFile );
                        $className = 'eZ' . $protocol . 'User';
                        $impl = new $className();
                        return $impl;
                    }
                }
            }
        }
        // if no one appropriate instance was found
        $impl = null;
        return $impl;
    }

    /*!
     \static
     Check user redirection for current loginhandler.

     \param siteBasics
     \param possible redirect url
     \param login handler, standard by default. If set to false, handler type will be fetched from ini settings.

     \return  true if user is logged in successfully.
              null or false if failed.
              redirect specification, array ( module, view ).
    */
    function checkUser( &$siteBasics, &$url )
    {
        //eZDebug::writeNotice( 'Checking user for url : ' . var_export( $url, 1),
        //                      'eZUserLoginHandler::checkUser()' );

        $http =& eZHTTPTool::instance();

        if ( !$http->hasSessionVariable( EZ_LOGIN_HANDLER_STEP ) )
        {
            $http->setSessionVariable( EZ_LOGIN_HANDLER_STEP, EZ_LOGIN_HANDLER_STEP_PRE_CHECK_USER_INFO );
        }

        $loginStep =& $http->sessionVariable( EZ_LOGIN_HANDLER_STEP );

        if ( $http->hasSessionVariable( EZ_LOGIN_HANDLER_FORCE_LOGIN ) &&
             $loginStep < EZ_LOGIN_HANDLER_STEP_PRE_COLLECT_USER_INFO )
        {
            $loginStep = EZ_LOGIN_HANDLER_STEP_PRE_COLLECT_USER_INFO;
        }

        //eZDebug::writeNotice( 'Current login step : ' . $loginStep,
        //                      'eZUserLoginHandler::checkUser()' );

        switch( $loginStep )
        {
            case EZ_LOGIN_HANDLER_STEP_PRE_CHECK_USER_INFO:
            {
                $ini =& eZINI::instance();
                $handlerList = array( 'standard' );
                if ( $ini->hasVariable( 'UserSettings', 'LoginHandler' ) )
                {
                    $handlerList = $ini->variable( 'UserSettings', 'LoginHandler' );
                }

                foreach( $handlerList as $handler )
                {
                    $userObject =& eZUserLoginHandler::instance( $handler );
                    $check = $userObject->checkUser( $siteBasics, $url );
                    if ( $check === null ) // No login needed.
                    {
                        eZUserLoginHandler::sessionCleanup();
                        return null;
                    }

                    $http->setSessionVariable( EZ_LOGIN_HANDLER_LAST_CHECK_REDIRECT, $check );
                }

                $http->setSessionVariable( EZ_LOGIN_HANDLER_STEP, EZ_LOGIN_HANDLER_STEP_PRE_COLLECT_USER_INFO );
                return eZUserLoginHandler::checkUser( $siteBasics, $url );
            } break;

            case EZ_LOGIN_HANDLER_STEP_PRE_COLLECT_USER_INFO:
            {
                $ini =& eZINI::instance();

                $http->setSessionVariable( EZ_LOGIN_HANDLER_STEP, EZ_LOGIN_HANDLER_STEP_POST_COLLECT_USER_INFO );

                switch( $ini->variable( 'SiteSettings', 'LoginPage' ) )
                {
                    case 'embedded':
                    case 'custom':
                    {
                        $redirect = $http->sessionVariable( EZ_LOGIN_HANDLER_LAST_CHECK_REDIRECT );
                        if ( !$redirect )
                        {
                            $redirect = array( 'module' => 'user', 'function' => 'login' );
                        }
                        return $redirect;
                    } break;

                    default: // Use specified login handler to handle Login info input
                    {
                        $handlerName = $ini->variable( 'SiteSettings', 'LoginPage' );
                        $handler =& eZUserLoginHandler::instance( $handlerName );

                        //eZDebug::writeNotice( 'Using ' . $handlerName . ' to collect user information.',
                        //                      'eZUserLoginHandler::checkUser()' );
                        return $handler->preCollectUserInfo();
                    } break;
                }
            } break;

            case EZ_LOGIN_HANDLER_STEP_POST_COLLECT_USER_INFO:
            {
                $ini =& eZINI::instance();

                $http->setSessionVariable( EZ_LOGIN_HANDLER_STEP, EZ_LOGIN_HANDLER_STEP_LOGIN_USER );

                switch( $ini->variable( 'SiteSettings', 'LoginPage' ) )
                {
                    case 'embedded':
                    case 'custom':
                    {
                        // Do nothing
                    } break;

                    default: // Use specified login handler to handle Login info input
                    {
                        $handlerName = $ini->variable( 'SiteSettings', 'LoginPage' );
                        $handler =& eZUserLoginHandler::instance( $handlerName );
                        if ( !$handler->postCollectUserInfo() ) // Catch cancel of information collection
                        {
                            eZUserLoginHandler::sessionCleanup();
                            eZHTTPTool::redirect( '/' );
                            eZExecution::cleanExit();
                        }
                    } break;
                }

                return eZUserLoginHandler::checkUser( $siteBasics, $url );
            } break;

            case EZ_LOGIN_HANDLER_STEP_LOGIN_USER:
            {
                $ini =& eZINI::instance();
                $handlerList = array( 'standard' );
                if ( $ini->hasVariable( 'UserSettings', 'LoginHandler' ) )
                {
                    $handlerList = $ini->variable( 'UserSettings', 'LoginHandler' );
                }

                $userInfoArray = $http->sessionVariable( EZ_LOGIN_HANDLER_USER_INFO );
                $http->removeSessionVariable( EZ_LOGIN_HANDLER_USER_INFO );

                if ( $http->hasSessionVariable( EZ_LOGIN_HANDLER_FORCE_LOGIN ) )
                {
                    $http->removeSessionVariable( EZ_LOGIN_HANDLER_FORCE_LOGIN );
                }

                foreach( $handlerList as $handler )
                {
                    $userObject =& eZUserLoginHandler::instance( $handler );
                    $user =& $userObject->loginUser( $userInfoArray['login'], $userInfoArray['password'] );
                    if ( is_subclass_of( $user, 'eZUser' ) )
                    {
                        eZUserLoginHandler::sessionCleanup();
                        return null;
                    }
                    else if ( is_array( $user ) )
                    {
                        eZUserLoginHandler::sessionCleanup();
                        return $user;
                    }
                }

                $http->setSessionVariable( EZ_LOGIN_HANDLER_STEP, EZ_LOGIN_HANDLER_STEP_PRE_CHECK_USER_INFO );
                return eZUserLoginHandler::checkUser( $siteBasics, $url );
            } break;
        }
    }

    /*!
     Set session variable to force login
    */
    function forceLogin()
    {
        $http =& eZHTTPTool::instance();
        $http->setSessionVariable( EZ_LOGIN_HANDLER_FORCE_LOGIN, 1 );
    }
}

?>
