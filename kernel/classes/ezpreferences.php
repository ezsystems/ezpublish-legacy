<?php
//
// Definition of eZPreferences class
//
// Created on: <11-Aug-2003 13:23:55 bf>
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

/*! \file ezpreferences.php
*/

/*!
  \class eZPreferences ezpreferences.php
  \brief Handles user/session preferences

  Preferences can be either pr user or pr session. eZPreferences will automatically
  set a session preference if the user is not logged in, if not a user preference will be set.

*/

include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( "lib/ezdb/classes/ezdb.php" );

define( 'EZ_PREFERENCES_SESSION_NAME', 'eZPreferences' );

class eZPreferences
{
    function eZPreferences()
    {
    }

    /*!
     \static
     Sets a preference value for a given user. If
     the user is anonymous the value is only stored in session.

     \param $name The name of the preference to store
     \param $value The value of the preference to store
     \param $storeUserID The user which should get the preference,
                         if \c false it will use the current user
     \return \c true if the preference was stored correctly or \c false if something went wrong
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function setValue( $name, $value, $storeUserID = false )
    {
        $db =& eZDB::instance();
        $name = $db->escapeString( $name );
        $value = $db->escapeString( $value );

        $isCurrentUser = true;
        if ( $storeUserID === false )
        {
            $user =& eZUser::currentUser();
        }
        else
        {
            $currentID = eZUser::currentUserID();
            if ( $currentID != $storeUserID )
                $isCurrentUser = false;

            $user = eZUser::fetch( $storeUserID );
            if ( !is_object( $user ) )
            {
                eZDebug::writeError( "Cannot set preference for user $storeUserID, the user does not exist" );
                return false;
            }
        }

        // We must store the database changes if:
        // a - The current user is logged in (ie. not anonymous)
        // b - We have specified a specific user (not the current).
        //    in which case isLoggedIn() will fail.
        if ( $storeUserID !== false or $user->isLoggedIn() )
        {
            // Only store in DB if user is logged in or we have
            // a specific user ID defined
            $userID = $user->attribute( 'contentobject_id' );
            $existingRes = $db->arrayQuery( "SELECT * FROM ezpreferences WHERE user_id = $userID AND name='$name'" );

            if ( count( $existingRes ) > 0 )
            {
                $prefID = $existingRes[0]['id'];
                $query = "UPDATE ezpreferences SET value='$value' WHERE id = $prefID AND name='$name'";
                $db->query( $query );
            }
            else
            {
                $query = "INSERT INTO ezpreferences ( user_id, name, value ) VALUES ( $userID, '$name', '$value' )";
                $db->query( $query );
            }
        }

        // We also store in session if this is the current user (anonymous or normal user)
        if ( $isCurrentUser )
        {
            eZPreferences::storeInSession( $name, $value );
        }

        return true;
    }

    /*!
     \static
     \param $user The user object to read preferences for, if \c false it will read using the current user.
     \return The preference value for the specified user.
             If no variable is found \c false is returned.
     \note The preferences variable will be stored in session after fetching
           if the specified user is the current user.
    */
    function value( $name, $user = false )
    {
        if ( get_class( $user ) != 'ezuser' )
            $user =& eZUser::currentUser();

        $value = false;
        // If the user object is not the currently logged in user we cannot use the session values
        $http =& eZHTTPTool::instance();
        $useCache = ( $user->ContentObjectID == $http->sessionVariable( 'eZUserLoggedInID' ) );
        if ( $useCache and eZPreferences::isStoredInSession( $name ) )
            return eZPreferences::storedSessionValue( $name );

        // If this the anonymous user we should return false, no need to check database.
        if ( $user->isAnonymous() )
            return false;

        $db =& eZDB::instance();
        $name = $db->escapeString( $name );
        $userID = $user->attribute( 'contentobject_id' );
        $existingRes = $db->arrayQuery( "SELECT value FROM ezpreferences WHERE user_id = $userID AND name = '$name'" );

        if ( count( $existingRes ) == 1 )
        {
            $value = $existingRes[0]['value'];
            if ( $useCache )
                eZPreferences::storeInSession( $name, $value );
        }
        else
        {
            if ( $useCache )
                eZPreferences::storeInSession( $name, false );
        }
        return $value;
    }

    /*!
     \static
     \param $user The user object to read preferences for, if \c false it will read using the current user.
     \return An array with all the preferences for the specified user.
             If the user is not logged in the empty array will be returned.
    */
    function values( $user = false )
    {
        if ( get_class( $user ) != 'ezuser' )
            $user =& eZUser::currentUser();

        if ( !$user->isAnonymous() )
        {
            // If the user object is not the currently logged in user we cannot use the session values
            $http =& eZHTTPTool::instance();
            $useCache = ( $user->ContentObjectID == $http->sessionVariable( 'eZUserLoggedInID' ) );

            $returnArray = array();
            $userID = $user->attribute( 'contentobject_id' );
            $db =& eZDB::instance();
            $values = $db->arrayQuery( "SELECT name,value FROM ezpreferences WHERE user_id=$userID ORDER BY id" );
            foreach ( $values as $item )
            {
                if ( $useCache )
                    eZPreferences::storeInSession( $item['name'], $item['value'] );
                $returnArray[$item['name']] = $item['value'];
            }
            return $returnArray;
        }
        else
        {
            // For the anonymous user we just return all values
            $http =& eZHTTPTool::instance();
            if ( $http->hasSessionVariable( EZ_PREFERENCES_SESSION_NAME ) )
                return $http->sessionVariable( EZ_PREFERENCES_SESSION_NAME );
            return array();
        }
    }

    /*!
     \static
     Makes sure the stored session values are cleaned up.
    */
    function sessionCleanup()
    {
        $http =& eZHTTPTool::instance();
        $http->removeSessionVariable( EZ_PREFERENCES_SESSION_NAME );
    }

    /*!
     \static
     Makes sure the preferences named \a $name is stored in the session with the value \a $value.
    */
    function storeInSession( $name, $value )
    {
        $http =& eZHTTPTool::instance();
        $preferencesInSession = array();
        if ( $http->hasSessionVariable( EZ_PREFERENCES_SESSION_NAME ) )
             $preferencesInSession =& $http->sessionVariable( EZ_PREFERENCES_SESSION_NAME );
        $preferencesInSession[$name] = $value;
        $http->setSessionVariable( EZ_PREFERENCES_SESSION_NAME, $preferencesInSession );
    }

    /*!
     \static
     \return \c true if the preference named \a $name is stored in session.
    */
    function isStoredInSession( $name )
    {
        $http =& eZHTTPTool::instance();
        if ( !$http->hasSessionVariable( EZ_PREFERENCES_SESSION_NAME ) )
            return false;
        $preferencesInSession =& $http->sessionVariable( EZ_PREFERENCES_SESSION_NAME );
        return array_key_exists( $name, $preferencesInSession );
    }

    /*!
     \static
     \return the stored preferenced value found in the session or \c null if none were found.
    */
    function storedSessionValue( $name )
    {
        $http =& eZHTTPTool::instance();
        if ( !$http->hasSessionVariable( EZ_PREFERENCES_SESSION_NAME ) )
            return null;
        $preferencesInSession =& $http->sessionVariable( EZ_PREFERENCES_SESSION_NAME );
        if ( !array_key_exists( $name, $preferencesInSession ) )
            return null;
        return $preferencesInSession[$name];
    }

    /*!
     \static
     Removes all preferences for all users.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezpreferences" );
    }
}


?>
