<?php
/**
 * File containing the eZUserOperationCollection class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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
     * Send activativation to the user
     *
     * If the user is enabled, igore
     */
    static public function sendActivationEmail( $userID )
    {
        eZDebugSetting::writeNotice( 'kernel-user',  'Sending activation email.', 'user register' );
        $ini = eZINI::instance();

        $tpl = eZTemplate::factory();
        $user = eZUser::fetch( $userID );
        $tpl->setVariable( 'user', $user );
        $object= eZContentObject::fetch( $userID );
        $tpl->setVariable( 'object', $object );
        $hostname = eZSys::hostname();
        $tpl->setVariable( 'hostname', $hostname );

        // Check whether account activation is required.
        $verifyUserType = $ini->variable( 'UserSettings', 'VerifyUserType' );
        $sendUserMail = !!$verifyUserType;
        // For compatibility with old setting
        if ( $verifyUserType === 'email'
          && $ini->hasVariable( 'UserSettings', 'VerifyUserEmail' )
          && $ini->variable( 'UserSettings', 'VerifyUserEmail' ) !== 'enabled' )
        {
            $verifyUserType = false;
        }

        if ( $verifyUserType === 'email' ) // and if it is email type
        {
            // Disable user account and send verification mail to the user

            // Create enable account hash and send it to the newly registered user
            $hash = md5( mt_rand() . time() . $userID );

            if ( eZOperationHandler::operationIsAvailable( 'user_activation' ) )
            {
                $operationResult = eZOperationHandler::execute( 'user',
                                                                'activation', array( 'user_id'    => $userID,
                                                                                     'user_hash'  => $hash,
                                                                                     'is_enabled' => false ) );
            }
            else
            {
                eZUserOperationCollection::activation( $userID, $hash, false );
            }

            $tpl->setVariable( 'hash', $hash );

            $sendUserMail = true;
        }
        else if ( $verifyUserType )// custom account activation
        {
            $verifyUserTypeClass = false;
            // load custom verify user settings
            if ( $ini->hasGroup( 'VerifyUserType_' . $verifyUserType ) )
            {
                if ( $ini->hasVariable( 'VerifyUserType_' . $verifyUserType, 'File' ) )
                    include_once( $ini->variable( 'VerifyUserType_' . $verifyUserType, 'File' ) );
                $verifyUserTypeClass = $ini->variable( 'VerifyUserType_' . $verifyUserType, 'Class' );
            }
            // try to call the verify user class with function verifyUser
            $user = eZContentObject::fetch( $userID );
            if ( $verifyUserTypeClass && method_exists( $verifyUserTypeClass, 'verifyUser' ) )
                $sendUserMail  = call_user_func( array( $verifyUserTypeClass, 'verifyUser' ), $user, $tpl );
            else
                eZDebug::writeWarning( "Unknown VerifyUserType '$verifyUserType'", 'user/register' );
        }

        // send verification mail to user if email type or custum verify user type returned true
        if ( $sendUserMail )
        {
            $templateResult = $tpl->fetch( 'design:user/registrationinfo.tpl' );
            if ( $tpl->hasVariable( 'content_type' ) )
                $contentType = $tpl->variable( 'content_type' );
            else
                $contentType = $ini->variable( 'MailSettings', 'ContentType' );

            $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
            if ( $tpl->hasVariable( 'email_sender' ) )
                $emailSender = $tpl->variable( 'email_sender' );
            else if ( !$emailSender )
                $emailSender = $ini->variable( 'MailSettings', 'AdminEmail' );

            if ( $tpl->hasVariable( 'subject' ) )
                $subject = $tpl->variable( 'subject' );
            else
                $subject = ezpI18n::tr( 'kernel/user/register', 'Registration info' );

            $mail = new eZMail();
            $mail->setSender( $emailSender );
            $mail->setContentType( $contentType );
            $user = eZUser::fetch( $userID );
            $receiver = $user->attribute( 'email' );
            $mail->setReceiver( $receiver );
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            $mailResult = eZMailTransport::send( $mail );
        }
        return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
    }


    static public function checkActivation( $userID )
    {
        // check if the account is enabled
        $user = eZUser::fetch( $userID );
        if( $user->attribute( 'is_enabled' ) )
        {
            eZDebugSetting::writeNotice( 'kernel-user',  'The user is enabled.', 'user register/check activation' );
            return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
        }
        else
        {
            eZDebugSetting::writeNotice( 'kernel-user',  'The user is not enabled.', 'user register/check activation' );
            return array( 'status' => eZModuleOperationInfo::STATUS_HALTED );
        }
    }

    /**
     *  publish the object
     */
    static public function publishUserContentObject( $userID )
    {
        $object = eZContentObject::fetch( $userID );
        if( $object->attribute( 'current_version' ) !== '1' )
        {
            eZDebug::writeError( 'Current version is wrong for the user object. User ID: ' . $userID , 'user/register' );
            return array( 'status' => eZModuleOperationInfo::STATUS_CANCELLED );
        }
        eZDebugSetting::writeNotice( 'kernel-user' , 'publishing user object', 'user register' );
        // if the object is already published, continue the operation
        if( $object->attribute( 'status' ) )
        {
            eZDebugSetting::writeNotice( 'kernel-user', 'User object publish is published.', 'user register' );
            return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
        }
        $result = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $userID, 'version' => 1 ) );
        if( $result['status'] === eZModuleOperationInfo::STATUS_HALTED )
        {
            eZDebugSetting::writeNotice( 'kernel-user', 'User object publish is in pending.', 'user register' );
            return array( 'status' => eZModuleOperationInfo::STATUS_HALTED );
        }
        return $result;
    }

    /**
     *  Send the notification after registeration
     */
    static public function sendUserNotification( $userID )
    {
        eZDebugSetting::writeNotice( 'Sending approval notification to the user.' , 'kernel-user', 'user register' );
        $user = eZUser::fetch( $userID );
        $ini = eZINI::instance();
        // Send mail
        $tpl = eZTemplate::factory();
        $tpl->setVariable( 'user',  $user );
        $templateResult = $tpl->fetch( 'design:user/registrationapproved.tpl' );

        $mail = new eZMail();
        if ( $tpl->hasVariable( 'content_type' ) )
            $mail->setContentType( $tpl->variable( 'content_type' ) );

        $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
        if ( $tpl->hasVariable( 'email_sender' ) )
            $emailSender = $tpl->variable( 'email_sender' );
        else if ( !$emailSender )
            $emailSender = $ini->variable( 'MailSettings', 'AdminEmail' );

        if ( $tpl->hasVariable( 'subject' ) )
            $subject = $tpl->variable( 'subject' );
        else
            $subject = ezpI18n::tr( 'kernel/user/register', 'User registration approved' );

        $mail->setSender( $emailSender );
        $receiver = $user->attribute( 'email' );
        $mail->setReceiver( $receiver );
        $mail->setSubject( $subject );
        $mail->setBody( $templateResult );
        $mailResult = eZMailTransport::send( $mail );

        return array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
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
