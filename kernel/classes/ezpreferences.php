<?php
//
// Definition of eZPreferences class
//
// Created on: <11-Aug-2003 13:23:55 bf>
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

/*! \file ezpreferences.php
*/

/*!
  \class eZPreferences ezpreferences.php
  \brief Handles user/session preferences

  Preferences can be either pr user or pr session. eZPreferences will automatically
  set a session preference if the user is not logged in, if not a user preference will be set.

*/

include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

define( 'EZ_PREFERENCES_SESSION_NAME', 'eZPreferences' );

class eZPreferences
{
    function eZPreferences()
    {
    }

    /*!
     \static
     Sets a preference value for the current user. If
     the user is anonymous the value is only stored in session.
    */
    function setValue( $name, $value )
    {
        $db =& eZDB::instance();
        $name = $db->escapeString( $name );
        $value = $db->escapeString( $value );

        $user =& eZUser::currentUser();
        if ( $user->isLoggedIn() )
        {
            // Only store in DB is user is logged in
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
        eZPreferences::storeInSession( $name, $value );
    }

    /*!
     \static
     \return the session variable for the current user/session. If no variable is found
     false is returned. The preferences variable is stored in session after fetching.
    */
    function value( $name )
    {
        $value = false;
        if ( eZPreferences::isStoredInSession( $name ) )
            return eZPreferences::storedSessionValue( $name );

        $db =& eZDB::instance();
        $name = $db->escapeString( $name );
        $user =& eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );
        $existingRes = $db->arrayQuery( "SELECT value FROM ezpreferences WHERE user_id = $userID AND name = '$name'" );

        if ( count( $existingRes ) == 1 )
        {
            $value = $existingRes[0]['value'];
            eZPreferences::storeInSession( $name, $value );
        }
        else
        {
            eZPreferences::storeInSession( $name, false );
        }
        return $value;
    }

    /*!
     \static
     \return the array with all the preferences for the current user. If no user is logged in,
     the empty array will be returned.
    */
    function values()
    {
        $user =& eZUser::currentUser();
        if ( $user->isLoggedIn() )
        {
            $returnArray = array();
            $userID = $user->attribute( 'contentobject_id' );
            $db =& eZDB::instance();
            $values = $db->arrayQuery( "SELECT name,value FROM ezpreferences WHERE user_id=$userID ORDER BY id" );
            foreach ( $values as $item )
            {
                eZPreferences::storeInSession( $item['name'], $item['value'] );
                $returnArray[$item['name']] = $item['value'];
            }
            return $returnArray;
        }
        else
        {
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
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezpreferences" );
    }
}


?>
