<?php
//
// Definition of eZUser class
//
// Created on: <10-Jun-2002 17:03:15 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

/*!
  \class eZUser ezuser.php
  \brief eZUser handles eZ publish user accounts
  \ingroup eZKernel

*/

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezrole.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );

$ini =& eZINI::instance();
define( 'EZ_USER_ANONYMOUS_ID', $ini->variable( 'UserSettings', 'AnonymousUserID' ) );

/// MD5 of password
define( 'EZ_USER_PASSWORD_HASH_MD5_PASSWORD', 1 );
/// MD5 of user and password
define( 'EZ_USER_PASSWORD_HASH_MD5_USER', 2 );
/// MD5 of site, user and password
define( 'EZ_USER_PASSWORD_HASH_MD5_SITE', 3 );
/// Legacy support for mysql hashed passwords
define( 'EZ_USER_PASSWORD_HASH_MYSQL', 4 );
/// Passwords in plaintext, should not be used for real sites
define( 'EZ_USER_PASSWORD_HASH_PLAINTEXT', 5 );

$GLOBALS['eZUserBuiltins'] = array( EZ_USER_ANONYMOUS_ID );

class eZUser extends eZPersistentObject
{
    function eZUser( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'contentobject_id' => 'ContentObjectID',
                                         'login' => 'Login',
                                         'email' => 'Version',
                                         'password_hash' => 'PasswordHash',
                                         'password_hash_type' => 'PasswordHashType'
                                         ),
                      'keys' => array( 'contentobject_id' ),
                      'function_attributes' => array( 'groups' => 'groups',
                                                      'roles' => 'roles',
                                                      'is_logged_in' => 'isLoggedIn'
                                                      ),
                      'relations' => array( 'contentobject_id' => array( 'class' => 'ezcontentobject',
                                                                                 'field' => 'id' ) ),
                      'class_name' => 'eZUser',
                      'name' => 'ezuser' );
    }

    function attribute( $attr )
    {
        if ( $attr == 'groups')
        {
            return $this->groups();
        }
        if ( $attr == 'is_logged_in')
        {
            return $this->isLoggedIn();
        }
        if ( $attr == 'roles')
        {
            return $this->roles();
        }
        else
            return eZPersistentObject::attribute( $attr );
    }

    function &create( $contentObjectID )
    {
        $row = array(
            'contentobject_id' => $contentObjectID,
            'login' => null,
            'email' => null,
            'password_hash' => null,
            'password_hash_type' => null
            );
        return new eZUser( $row );
    }

    /*!
     Fills in the \a $id, \a $login, \a $email and \a $password for the user
     and creates the proper password hash.
    */
    function setInformation( $id, $login, $email, $password )
    {
        $this->setAttribute( "content_object_id", $id );
        $this->setAttribute( "email", $email );
        if ( $password !== null ) // Cannot change login or password_hash without login and password
        {
            $this->setAttribute( "login", $login );
            $this->setAttribute( "password_hash", eZUser::createHash( $login, $password, eZUser::site(),
                                                                      eZUser::hashType() ) );
            $this->setAttribute( "password_hash_type", eZUser::hashType() );
        }
    }

    function &fetch( $id, $as_object = true )
    {
        return eZPersistentObject::fetchObject( eZUser::definition(),
                                                null,
                                                array( 'contentobject_id' => $id
                                                      ),
                                                $as_object );
    }

    /*!
     \static
     \return the default hash type which is specified in UserSettings/HashType in site.ini
    */
    function hashType()
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $type = strtolower( $ini->variable( 'UserSettings', 'HashType' ) );
        if ( $type == 'md5_site' )
            return EZ_USER_PASSWORD_HASH_MD5_SITE;
        else if ( $type == 'md5_user' )
            return EZ_USER_PASSWORD_HASH_MD5_PASSWORD;
        else if ( $type == 'plaintext' )
            return EZ_USER_PASSWORD_HASH_PLAINTEXT;
        else
            return EZ_USER_PASSWORD_HASH_MD5_USER;
    }

    /*!
     \static
     \return the site name used in password hashing.
    */
    function site()
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        return $ini->variable( 'UserSettings', 'SiteName' );
    }

    /*!
     Fetches a builtin user and returns it, this helps avoid special cases where
     user is not logged in.
    */
    function &fetchBuiltin( $id )
    {
        if ( !in_array( $id, $GLOBALS['eZUserBuiltins'] ) )
            $id = EZ_USER_ANONYMOUS_ID;
        $builtinInstance =& $GLOBALS["eZUserBuilitinInstance-$id"];
        if ( get_class( $builtinInstance ) != 'ezuser' )
        {
            include_once( 'lib/ezutils/classes/ezini.php' );
            $builtinInstance =  eZUser::fetch( EZ_USER_ANONYMOUS_ID );
        }
        return $builtinInstance;
    }


    /*!
     \return the user id.
    */
    function id()
    {
        return $this->ContentObjectID;
    }
    /*!
    \static
     Logs in the user if applied username and password is
     valid. The userID is returned if succesful, false if not.
    */
    function &loginUser( $login, $password )
    {
        $http =& eZHTTPTool::instance();
        $db =& eZDB::instance();

        $query = "SELECT contentobject_id, password_hash, password_hash_type, email,login FROM ezuser WHERE login='$login'";

        $users =& $db->arrayQuery( $query );
        $exists = false;
        if ( count( $users ) >= 1 )
        {
//             eZDebug::writeNotice( $users, "ezuser" );
            include_once( 'lib/ezutils/classes/ezini.php' );
            $ini =& eZINI::instance();
            $hashType = $users[0]['password_hash_type'];
            $hash = $users[0]['password_hash'];
            $exists = eZUser::authenticateHash( $login, $password, eZUser::site(),
                                                $hashType,
                                                $hash );
            if ( $hashType != eZUser::hashType() and
                 strtolower( $ini->variable( 'UserSettings', 'UpdateHash' ) ) == 'true' )
            {
                $hashType = eZUser::hashType();
                $hash = eZUser::createHash( $login, $password, eZUser::site(),
                                            $hashType );
                $db->query( "UPDATE ezuser SET password_hash='$hash', password_hash_type='$hashType' WHERE login='$login'" );
            }
        }
        if ( $exists )
        {
            $user =& new eZUser( $users[0] );
            $GLOBALS["eZUserGlobalInstance"] =& $user;
            $http->setSessionVariable( 'eZUserLoggedInID', $users[0]['contentobject_id'] );
            return $user;
        }
        else
            return false;
    }

    /*!
     Finds the user with the id \a $id and returns the unique instance of it.
     If the user instance is not created yet it tries to either fetch it from the
     database with eZUser::fetch(). If $id is false or the user was not found, the
     default user is returned. This is a site.ini setting under UserSettings:AnonymousUserID.
     The instance is then returned.
     If \a $id is false then the current user is fetched.
    */
    function &instance( $id = false )
    {
        $currentUser =& $GLOBALS["eZUserGlobalInstance"];
        if( get_class( $currentUser ) == 'ezuser' )
        {
            return $currentUser;
        }

        $http =& eZHTTPTool::instance();
        // If not specified get the current user
        if ( $id === false )
        {
            $id = $http->sessionVariable( 'eZUserLoggedInID' );
            eZDebug::writeNotice( $id, "userID" );

            if ( !is_numeric( $id ) )
                $id = EZ_USER_ANONYMOUS_ID;
        }

        $currentUser = eZUser::fetch( $id );
        if ( !$currentUser )
        {
            $currentUser = new eZUser( array( 'id' => -1, 'login' => 'NoUser' ) );
            eZDebug::writeWarning( 'User not found, returning anonymous' );
        }

        return $currentUser;
    }

    /*!
     \static
     Returns the currently logged in user.
    */
    function &currentUser()
    {
        return eZUser::instance();
    }

    /*!
     \static
     Creates a hash out of \a $user, \a $password and \a $site according to the type \a $type.
     \return true if the generated hash is equal to the supplied hash \a $hash.
    */
    function authenticateHash( $user, $password, $site, $type, $hash )
    {
        return eZUser::createHash( $user, $password, $site, $type ) == $hash;
    }

    /*!
     \static
     Will create a hash of the given string. This is used to store the passwords in the database.
    */
    function createHash( $user, $password, $site, $type )
    {
        $str = '';
//         eZDebug::writeNotice( "'$user' '$password' '$site'", "ezuser($type)" );
        if( $type == EZ_USER_PASSWORD_HASH_MD5_USER )
        {
            $str = md5( "$user\n$password" );
        }
        else if ( $type == EZ_USER_PASSWORD_HASH_MD5_SITE )
        {
            $str = md5( "$user\n$password\n$site" );
        }
        else if( $type == EZ_USER_PASSWORD_HASH_MYSQL )
        {
            // Do some MySQL stuff here
        }
        else if( $type == EZ_USER_PASSWORD_HASH_PLAINTEXT )
        {
            $str = $password;
        }
        else // EZ_USER_PASSWORD_HASH_MD5_PASSWORD
        {
            $str = md5( $password );
        }
//         eZDebug::writeNotice( $str, "ezuser($type)" );
        return $str;
    }

    function &hasAccessTo( $module, $function )
    {
        $roles =& $this->attribute( 'roles' );
        $access = 'no';
        $limitationPolicyList = array();
        reset( $roles );
        foreach ( array_keys( $roles ) as $key )
        {
            $role =& $roles[$key];
            $policies =& $role->attribute( 'policies');
            foreach ( array_keys( $policies ) as $policy_key )
            {
                $policy =& $policies[$policy_key];
                if ( $policy->attribute( 'module_name' ) == '*' )
                {
                    return array( 'accessWord' => 'yes' );
                }
                elseif ( $policy->attribute( 'module_name' ) == $module )
                {
                    if ( $policy->attribute( 'function_name' ) == '*' )
                    {
                        return array( 'accessWord' => 'yes' );
                    }
                    elseif ( $policy->attribute( 'function_name' ) == $function )
                    {
                        if ( $policy->attribute( 'limitation' ) == '*' )
                        {
                            return array( 'accessWord' => 'yes' );
                        }
                        else
                        {
                            $access = 'limited';
                            $limitationPolicyList[] =& $policy;
                        }
                    }
                }
            }
        }
        return array( 'accessWord' => $access, 'policies' => $limitationPolicyList );
    }

    /*!
     \return an array of roles which the user is assigned to
    */
    function &roles()
    {
        if ( !isset( $this->Roles ) )
        {
            $groups = $this->attribute( 'groups' );
            $groups[] = $this->attribute( 'contentobject_id' );
            $roles =& eZRole::fetchByUser( $groups );
            $this->Roles =& $roles;
        }
        return $this->Roles;
    }

    /*!
     Returns true if it's a real user which is logged in. False if the user
     is the default user or the fallback buildtin user.
    */
    function &isLoggedIn()
    {
        $return = true;
        if ( $this->ContentObjectID == EZ_USER_ANONYMOUS_ID or
             $this->ContentObjectID == -1
             )
            $return = false;
        return $return;
    }

    /*!
     \return an array of id's with all the groups the user belongs to.
    */
    function &groups( $as_object = false, $userID = false )
    {
        $db =& eZDB::instance();

        if ( $as_object == true )
            eZDebug::writeError( 'Returning user groups as objects not implemented', 'ezuser' );
        else
        {
            if( !isset( $this->Groups ) )
            {
                if ( $userID )
                {
                    $contentobjectID = $userID;
                }
                else
                {
                    $contentobjectID = $this->attribute( 'contentobject_id' );
                }
                $userGroups = $db->arrayQuery( "SELECT  c.contentobject_id as id
                                                FROM ezcontentobject_tree  b,
                                                     ezcontentobject_tree  c
                                                WHERE b.contentobject_id='$contentobjectID' AND
                                                      b.parent_node_id = c.node_id
                                                ORDER BY c.contentobject_id  ");
                $userGroupArray = array();

                foreach ( $userGroups as $group )
                {
                    $userGroupArray[] = $group['id'];
                }
                $this->Groups = $userGroupArray;
            }
        }
        return $this->Groups;
    }

    /// \privatesection
    var $Login;
    var $Email;
    var $PasswordHash;
    var $PasswordHashType;
    var $Groups;
    var $Roles;
}

?>
