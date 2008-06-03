<?php
//
// Definition of eZUserLoginHandler class
//
// Created on: <24-Jul-2003 15:11:57 wy>
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

/*! \file ezuserloginhandler.php
*/

/*!
  \class eZUserLoginHandler ezuserloginhandler.php
  \ingroup eZDatatype
  \brief The class eZUserLoginHandler does

*/

class eZUserLoginHandler
{
    const AVAILABLE_ARRAY = 'eZLoginHandlerAvailbleArray'; // stores untested login handlers for login
    const STEP = 'eZLoginHandlerStep';
    const USER_INFO = 'eZLoginHandlerUserInfo';
    const LAST_CHECK_REDIRECT = 'eZLoginHandlerLastCheckRedirect';
    const FORCE_LOGIN = 'eZLoginHandlerForceLogin';
    const LAST_HANDLER_NAME = 'eZLoginHandlerLastHandlerName';

    const STEP_PRE_CHECK_USER_INFO = 0;
    const STEP_PRE_COLLECT_USER_INFO = 1;
    const STEP_POST_COLLECT_USER_INFO = 2;
    const STEP_CHECK_USER = 3;
    const STEP_LOGIN_USER = 4;

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
    static function sessionCleanup()
    {
        $http = eZHTTPTool::instance();

        $valueList = array( self::AVAILABLE_ARRAY,
                            self::STEP,
                            self::USER_INFO,
                            self::LAST_CHECK_REDIRECT,
                            self::FORCE_LOGIN );

        foreach ( $valueList as $value )
        {
            if ( $http->hasSessionVariable( $value ) )
            {
                $http->removeSessionVariable( $value );
            }
        }

        $ini = eZINI::instance();
        $handlerList = array( 'standard' );
        if ( $ini->hasVariable( 'UserSettings', 'LoginHandler' ) )
        {
            $handlerList = $ini->variable( 'UserSettings', 'LoginHandler' );
        }

        foreach( $handlerList as $handler )
        {
            $loginHandler = eZUserLoginHandler::instance( $handler );
            if ( $loginHandler )
            {
                $loginHandler->sessionCleanup();
            }
        }
    }

    /*!
     Fetch object instance of specified login handler.

     \param login handler name

     \return Login handler object
     */
    static function instance( $protocol = "standard" )
    {
        $triedFiles = array();
        if ( $protocol == "standard" )
        {
            //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            $impl = new eZUser( 0 );
            return $impl;
        }
        else
        {
            $ezuserFile = 'kernel/classes/datatypes/ezuser/ez' . strtolower( $protocol ) . 'user.php';
            $triedFiles[] = $ezuserFile;
            if ( file_exists( $ezuserFile ) )
            {
                include_once( $ezuserFile );
                $className = 'eZ' . $protocol . 'User';
                $impl = new $className();
                return $impl;
            }
            else // check in extensions
            {
                //include_once( 'lib/ezutils/classes/ezextension.php' );
                $ini = eZINI::instance();
                $extensionDirectories = $ini->variable( 'UserSettings', 'ExtensionDirectory' );
                $directoryList = eZExtension::expandedPathList( $extensionDirectories, 'login_handler' );

                foreach( $directoryList as $directory )
                {
                    $userFile = $directory . '/ez' . strtolower( $protocol ) . 'user.php';
                    $triedFiles[] = $userFile;

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
        eZDebug::writeWarning( "Unable to find user login handler '$protocol', searched for these files: " . implode( ', ', $triedFiles ), 'eZUserLoginHandler::instance()' );
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
    static function checkUser( &$siteBasics, &$url )
    {
        $http = eZHTTPTool::instance();

        if ( !$http->hasSessionVariable( self::STEP ) )
        {
            $http->setSessionVariable( self::STEP, self::STEP_PRE_CHECK_USER_INFO );
        }

        $loginStep =& $http->sessionVariable( self::STEP );

        if ( $http->hasSessionVariable( self::FORCE_LOGIN ) &&
             $loginStep < self::STEP_PRE_COLLECT_USER_INFO )
        {
            $loginStep = self::STEP_PRE_COLLECT_USER_INFO;
        }

        switch( $loginStep )
        {
            case self::STEP_PRE_CHECK_USER_INFO:
            {
                $ini = eZINI::instance();
                $handlerList = array( 'standard' );
                if ( $ini->hasVariable( 'UserSettings', 'LoginHandler' ) )
                {
                    $handlerList = $ini->variable( 'UserSettings', 'LoginHandler' );
                }

                if ( $http->hasSessionVariable( self::LAST_HANDLER_NAME ) )
                {
                    $http->removeSessionVariable( self::LAST_HANDLER_NAME );
                }

                foreach( $handlerList as $handler )
                {
                    $userObject = eZUserLoginHandler::instance( $handler );
                    if ( $userObject )
                    {
                        $check = $userObject->checkUser( $siteBasics, $url );
                        if ( $check === null ) // No login needed.
                        {
                            eZUserLoginHandler::sessionCleanup();
                            return null;
                        }
                        $http->setSessionVariable( self::LAST_CHECK_REDIRECT, $check );
                        $http->setSessionVariable( self::LAST_HANDLER_NAME, $handler );
                    }
                }

                $http->setSessionVariable( self::STEP, self::STEP_PRE_COLLECT_USER_INFO );
                return eZUserLoginHandler::checkUser( $siteBasics, $url );
            } break;

            case self::STEP_PRE_COLLECT_USER_INFO:
            {
                $http->setSessionVariable( self::STEP, self::STEP_POST_COLLECT_USER_INFO );

                $handler = null;
                if ( $http->hasSessionVariable( self::LAST_HANDLER_NAME ) )
                {
                    $handlerName = $http->sessionVariable( self::LAST_HANDLER_NAME );
                    $handler = eZUserLoginHandler::instance( $handlerName );
                }
                if ( $handler )
                {
                    return $handler->preCollectUserInfo();
                }
                else
                {
                    $redirect =& $http->sessionVariable( self::LAST_CHECK_REDIRECT );
                    if ( !$redirect )
                    {
                        $redirect = array( 'module' => 'user', 'function' => 'login' );
                    }
                    return $redirect;
                }
            } break;

            case self::STEP_POST_COLLECT_USER_INFO:
            {
                $http->setSessionVariable( self::STEP, self::STEP_LOGIN_USER );

                $handler = null;
                if ( $http->hasSessionVariable( self::LAST_HANDLER_NAME ) )
                {
                    $handlerName = $http->sessionVariable( self::LAST_HANDLER_NAME );
                    $handler = eZUserLoginHandler::instance( $handlerName );
                }

                if ( $handler ) //and $handlerName != 'standard' )
                {
                    // Use specified login handler to handle Login info input
                    if ( !$handler->postCollectUserInfo() ) // Catch cancel of information collection
                    {
                        eZUserLoginHandler::sessionCleanup();
                        eZHTTPTool::redirect( '/' );
                        eZExecution::cleanExit();
                    }
                }
                return eZUserLoginHandler::checkUser( $siteBasics, $url );
            } break;

            case self::STEP_LOGIN_USER:
            {
                $ini = eZINI::instance();
                $handlerList = array( 'standard' );
                if ( $ini->hasVariable( 'UserSettings', 'LoginHandler' ) )
                {
                    $handlerList = $ini->variable( 'UserSettings', 'LoginHandler' );
                }

                $userInfoArray =& $http->sessionVariable( self::USER_INFO );
                $http->removeSessionVariable( self::USER_INFO );

                if ( $http->hasSessionVariable( self::FORCE_LOGIN ) )
                {
                    $http->removeSessionVariable( self::FORCE_LOGIN );
                }

                $user = null;
                if ( is_array( $userInfoArray ) and $userInfoArray['login'] and $userInfoArray['password'] )
                {
                    foreach( $handlerList as $handler )
                    {
                        $userObject = eZUserLoginHandler::instance( $handler );
                        if ( $userObject )
                        {
                            $user = $userObject->loginUser( $userInfoArray['login'], $userInfoArray['password'] );
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
                    }
                }

                $http->setSessionVariable( self::STEP, self::STEP_PRE_CHECK_USER_INFO );
                return eZUserLoginHandler::checkUser( $siteBasics, $url );
            } break;
        }
    }

    /*!
     Set session variable to force login
    */
    static function forceLogin()
    {
        $http = eZHTTPTool::instance();
        $http->setSessionVariable( self::FORCE_LOGIN, 1 );
    }
}

?>
