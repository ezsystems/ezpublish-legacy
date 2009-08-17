<?php
//
// Definition of eZUserOperationCollection class
//
// Created on: <27-Apr-2009 13:51:17 rp/ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZUserOperationCollection ezuseroperationcollection.php
  \brief The class eZUserOperationCollection does

*/

class eZUserOperationCollection
{
    /*!
     Constructor
    */
    function eZUserOperationCollection()
    {
    }

   /**
     * Changes user settings
     *
     * @param int $userID
     * @param int $isEnabled
     * @param int $maxLogin
     *
     * @return array An array with operation status, always true if userID is ok
     */
    static public function setSettings( $userID, $isEnabled, $maxLogin )
    {
        $userSetting = eZUserSetting::fetch( $userID );

        if ( $userSetting )
        {
            $userSetting->setAttribute( 'max_login', $maxLogin );
            $isUserEnabled = $isEnabled != 0;

            if ( $userSetting->attribute( 'is_enabled' ) != $isUserEnabled )
            {
                eZContentCacheManager::clearContentCacheIfNeeded( $userID );
                eZContentCacheManager::generateObjectViewCache( $userID );
            }

            $userSetting->setAttribute( "is_enabled", $isUserEnabled );
            $userSetting->store();

            if ( !$isUserEnabled )
            {
                eZUser::removeSessionData( $userID );
            }
            return array( 'status' => true );
        }
        else
        {
            eZDebug::writeError( "Failed to change settings of user $userID ", __METHOD__ );
            return array( 'status' => false );
        }
    }

   /**
     * Activate user with user or deactivate and create new eZUserAccountKey with user hash
     * depending on $enableUser being true or not.
     *
     * @param int $userID
     * @param string $userHash
     * @param bool $enableUser
     *
     * @return array An array with operation status, always true if userID is ok
     */
    static public function activation( $userID, $userHash, $enableUser = false )
    {
        $user = eZUser::fetch( $userID );
        $userSetting = eZUserSetting::fetch( $userID );

        if ( $user && $userSetting )
        {
            $userChange = $userSetting->attribute( 'is_enabled' ) != $enableUser;
            
            if ( $enableUser )
            {
                $userSetting->setAttribute( 'is_enabled', 1 );
                $userSetting->store();

                eZUserAccountKey::removeByUserID( $userID );
            }
            else
            {
                $userSetting->setAttribute( 'is_enabled', 0 );
                $userSetting->store();

                $accountKey = eZUserAccountKey::createNew( $userID, $userHash, time() );
                $accountKey->store();
            }

            if ( $userChange )
            {
                if ( !$enableUser )
                {
                    eZUser::removeSessionData( $userID );
                }
                eZContentCacheManager::clearContentCacheIfNeeded( $userID );
            }
            return array( 'status' => true );
        }
        else
        {
            eZDebug::writeError( "Failed to activate user $userID (could not fetch)", __METHOD__ );
            return array( 'status' => false );
        }
    }

   /**
     * Change user password
     *
     * @param int $userID
     * @param string $newPassword
     *
     * @return array An array with operation status, always true if userID is ok
     */
    static public function password( $userID, $newPassword )
    {
        $user = eZUser::fetch( $userID );

        if ( $user instanceof eZUser )
        {
            $login   = $user->attribute( 'login' );
            $type    = $user->attribute( 'password_hash_type' );
            $site    = $user->site();
            $newHash = $user->createHash( $login, $newPassword, $site, $type );
            $user->setAttribute( 'password_hash', $newHash );
            $user->store();
            return array( 'status' => true );
        }
        else
        {
            eZDebug::writeError( "Failed to change password of user $userID (could not fetch user)", __METHOD__ );
            return array( 'status' => false );
        }
    }

   /**
     * Generate forgotpassword object
     *
     * @param int $userID
     * @param string $passwordHash
     * @param int $time
     *
     * @return array An array with operation status, always true if userID is ok
     */
    static public function forgotpassword( $userID, $passwordHash, $time )
    {
        $user = eZUser::fetch( $userID );

        if ( $user instanceof eZUser )
        {
            $forgotPasswdObj = eZForgotPassword::createNew( $userID, $passwordHash, $time );
            $forgotPasswdObj->store();
            return array( 'status' => true );
        }
        else
        {
            eZDebug::writeError( "Failed to generate password hash for user $userID (could not fetch user)", __METHOD__ );
            return array( 'status' => false );
        }
    }

   /**
     * Set user preferences
     * Only needed for operations, call eZPreferences::setValue() directly if you want to set user preferences
     *
     * @param string $key
     * @param string $value
     *
     * @return array An array with operation status, always true
     */
    static public function preferences( $key, $value )
    {
        eZPreferences::setValue( $key, $value );
        return array( 'status' => true );
    }
}
?>
