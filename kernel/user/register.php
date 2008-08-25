<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

//include_once( "lib/ezutils/classes/ezhttptool.php" );
//include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
//include_once( "lib/ezutils/classes/ezmail.php" );
//include_once( "kernel/classes/ezcontentclassattribute.php" );
//include_once( "kernel/classes/ezcontentclass.php" );

$http = eZHTTPTool::instance();
$Module = $Params['Module'];

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}
$viewParameters = array();
$viewParameters = array_merge( $viewParameters, $UserParameters );

$Params['TemplateName'] = "design:user/register.tpl";
$EditVersion = 1;

require_once( "kernel/common/template.php" );
$tpl = templateInit();
$tpl->setVariable( 'view_parameters', $viewParameters );

$Params['TemplateObject'] = $tpl;

// $http->removeSessionVariable( "RegisterUserID" );

$db = eZDB::instance();
$db->begin();

// Create new user object if user is not logged in
if ( !$http->hasSessionVariable( "RegisterUserID" ) )
{
    $ini = eZINI::instance();
    $errMsg = '';
    $checkErrNodeId = false;

    $defaultUserPlacement = (int)$ini->variable( "UserSettings", "DefaultUserPlacement" );

    $sql = "SELECT count(*) as count FROM ezcontentobject_tree WHERE node_id = $defaultUserPlacement";
    $rows = $db->arrayQuery( $sql );
    $count = $rows[0]['count'];
    if ( $count < 1 )
    {
        $errMsg = ezi18n( 'design/standard/user', 'The node (%1) specified in [UserSettings].DefaultUserPlacement setting in site.ini does not exist!', null, array( $defaultUserPlacement ) );
        $checkErrNodeId = true;
        eZDebug::writeError( "$errMsg" );
        $tpl->setVariable( 'errMsg', $errMsg );
        $tpl->setVariable( 'checkErrNodeId', $checkErrNodeId );
    }
    $userClassID = $ini->variable( "UserSettings", "UserClassID" );
    $class = eZContentClass::fetch( $userClassID );

    $userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
    $defaultSectionID = $ini->variable( "UserSettings", "DefaultSectionID" );
    // Create object by user 14 in section 1
    $contentObject = $class->instantiate( $userCreatorID, $defaultSectionID );
    $objectID = $contentObject->attribute( 'id' );

    // Store the ID in session variable
    $http->setSessionVariable( "RegisterUserID", $objectID );

    $userID = $objectID;

    $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $contentObject->attribute( 'id' ),
                                                       'contentobject_version' => 1,
                                                       'parent_node' => $defaultUserPlacement,
                                                       'is_main' => 1 ) );
    $nodeAssignment->store();
}
else if ( $http->hasSessionVariable( "RegisterUserID" ) )
{
    $userID = $http->sessionVariable( "RegisterUserID" );
}

$Params['ObjectID'] = $userID;

$Module->addHook( 'post_publish', 'registerSearchObject', 1, false );

if ( !function_exists( 'checkContentActions' ) )
{
    function checkContentActions( $module, $class, $object, $version, $contentObjectAttributes, $EditVersion, $EditLanguage )
    {
        if ( $module->isCurrentAction( 'Cancel' ) )
        {
            //include_once( 'kernel/classes/ezredirectmanager.php' );
            eZRedirectManager::redirectTo( $module, '/' );

            $version->removeThis();

            $http = eZHTTPTool::instance();
            $http->removeSessionVariable( "RegisterUserID" );
            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Publish' ) )
        {
            $http = eZHTTPTool::instance();

            $user = eZUser::currentUser();
            //include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ),
                                                                                         'version' => $version->attribute( 'version') ) );

            $object = eZContentObject::fetch( $object->attribute( 'id' ) );

            // Check if user should be enabled and logged in
            unset($user);
            $user = eZUser::fetch( $object->attribute( 'id' ) );
            $user->loginCurrent();

            $receiver = $user->attribute( 'email' );
            $mail = new eZMail();
            if ( !$mail->validate( $receiver ) )
            {
            }
            require_once( "kernel/common/template.php" );
            //include_once( 'lib/ezutils/classes/ezmail.php' );
            //include_once( 'lib/ezutils/classes/ezmailtransport.php' );
            $ini = eZINI::instance();
            $tpl = templateInit();
            $tpl->setVariable( 'user', $user );
            $tpl->setVariable( 'object', $object );
            $hostname = eZSys::hostname();
            $tpl->setVariable( 'hostname', $hostname );
            $password = $http->sessionVariable( "GeneratedPassword" );

            $tpl->setVariable( 'password', $password );

            // Check whether account activation is required.
            $verifyUserEmail = $ini->variable( 'UserSettings', 'VerifyUserEmail' );

            if ( $verifyUserEmail == "enabled" ) // and if it is
            {
                // Disable user account and send verification mail to the user
                $userSetting = eZUserSetting::fetch( $user->attribute( 'contentobject_id' ) );
                $userSetting->setAttribute( 'is_enabled', 0 );
                $userSetting->store();

                // Log out current user
                eZUser::logoutCurrent();

                // Create enable account hash and send it to the newly registered user
                $hash = md5( time() . $user->attribute( 'contentobject_id' ) );
                //include_once( "kernel/classes/datatypes/ezuser/ezuseraccountkey.php" );
                $accountKey = eZUserAccountKey::createNew( $user->attribute( 'contentobject_id' ), $hash, time() );
                $accountKey->store();

                $tpl->setVariable( 'hash', $hash );
            }

            $templateResult = $tpl->fetch( 'design:user/registrationinfo.tpl' );
            $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
            if ( !$emailSender )
                $emailSender = $ini->variable( 'MailSettings', 'AdminEmail' );
            $mail->setSender( $emailSender );
            $mail->setReceiver( $receiver );
            $subject = ezi18n( 'kernel/user/register', 'Registration info' );
            if ( $tpl->hasVariable( 'subject' ) )
                $subject = $tpl->variable( 'subject' );
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            $mailResult = eZMailTransport::send( $mail );

            $feedbackTypes = $ini->variableArray( 'UserSettings', 'RegistrationFeedback' );
            foreach ( $feedbackTypes as $feedbackType )
            {
                switch ( $feedbackType )
                {
                    case 'email':
                    {
                        $mail = new eZMail();
                        $tpl->resetVariables();
                        $tpl->setVariable( 'user', $user );
                        $tpl->setVariable( 'object', $object );
                        $tpl->setVariable( 'hostname', $hostname );
                        $templateResult = $tpl->fetch( 'design:user/registrationfeedback.tpl' );

                        $feedbackReceiver = $ini->variable( 'UserSettings', 'RegistrationEmail' );
                        if ( !$feedbackReceiver )
                            $feedbackReceiver = $ini->variable( "MailSettings", "AdminEmail" );

                        $subject = ezi18n( 'kernel/user/register', 'New user registered' );
                        if ( $tpl->hasVariable( 'subject' ) )
                            $subject = $tpl->variable( 'subject' );
                        if ( $tpl->hasVariable( 'email_receiver' ) )
                            $feedbackReceiver = $tpl->variable( 'email_receiver' );

                        $mail->setReceiver( $feedbackReceiver );
                        $mail->setSubject( $subject );
                        $mail->setBody( $templateResult );
                        $mailResult = eZMailTransport::send( $mail );
                    } break;
                    default:
                    {
                        eZDebug::writeWarning( "Unknown feedback type '$feedbackType'", 'user/register' );
                    }
                }
            }



            $http->removeSessionVariable( "GeneratedPassword" );
            $http->removeSessionVariable( "RegisterUserID" );

            // check for redirectionvariable
            if ( $http->hasSessionVariable( 'RedirectAfterUserRegister' ) )
            {
                $module->redirectTo( $http->sessionVariable( 'RedirectAfterUserRegister' ) );
                $http->removeSessionVariable( 'RedirectAfterUserRegister' );
            }
            else if ( $http->hasPostVariable( 'RedirectAfterUserRegister' ) )
            {
                $module->redirectTo( $http->postVariable( 'RedirectAfterUserRegister' ) );
            }
            else
            {
                $module->redirectTo( '/user/success/' );
            }
        }
    }
}
$Module->addHook( 'action_check', 'checkContentActions' );

$OmitSectionSetting = true;

$includeResult = include( 'kernel/content/attribute_edit.php' );

$db->commit();

if ( $includeResult != 1 )
{
    return $includeResult;
}
$ini = eZINI::instance();

if ( $ini->variable( 'SiteSettings', 'LoginPage' ) == 'custom' )
{
    $Result['pagelayout'] = 'loginpagelayout.tpl';
}

$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/user', 'User' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/user', 'Register' ) ) );

?>
