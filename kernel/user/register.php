<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2011 eZ Systems AS
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

$http = eZHTTPTool::instance();
$Module = $Params['Module'];
$redirectNumber = $Params['redirect_number'];

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


$tpl = eZTemplate::factory();
$tpl->setVariable( 'view_parameters', $viewParameters );

$Params['TemplateObject'] = $tpl;

// $http->removeSessionVariable( "RegisterUserID" );

if ( $redirectNumber == '3' )
{
    $tpl->setVariable( 'content_attributes', false );

    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:user/register.tpl' );
    $Result['path'] = array( array( 'url' => false,
                            'text' => ezpI18n::tr( 'kernel/user', 'User' ) ),
                        array( 'url' => false,
                            'text' => ezpI18n::tr( 'kernel/user', 'Register' ) ) );
    return $Result;
}

$db = eZDB::instance();
$db->begin();

// Create new user object if user is not logged in
if ( !$http->hasSessionVariable( "RegisterUserID" ) )
{
    // flag if user client supports cookies and if we should do redirect
    $userClientValidates  = true;
    $doValidationRedirect = false;
    if ( !eZSession::userHasSessionCookie() )
    {
        if ( $redirectNumber == '2' )
            $userClientValidates = false;
        else
            $doValidationRedirect = true;
    }

    if ( $doValidationRedirect )
    {
        $db->rollback();
        return $Module->redirectTo( '/user/register/2' );
    }
    else if ( !$userClientValidates )
    {
        $db->rollback();

        $tpl->setVariable( 'user_has_cookie', eZSession::userHasSessionCookie(), 'User' );
        $tpl->setVariable( 'user_session_validates', true, 'User' );

        $Result = array();
        $Result['content'] = $tpl->fetch( 'design:user/register_user_not_valid.tpl' );
        $Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/user', 'User' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/user', 'Register' ) ) );
        return $Result;
    }
    // else create user object

    if ( $http->hasSessionVariable( 'StartedRegistration' ) )
    {
        eZDebug::writeWarning( 'Cancel module run to protect against multiple form submits', 'user/register' );
        $http->removeSessionVariable( "RegisterUserID" );
        $http->removeSessionVariable( 'StartedRegistration' );
        $db->commit();
        return eZModule::HOOK_STATUS_CANCEL_RUN;
    }
    else if ( $http->hasPostVariable( 'PublishButton' ) or $http->hasPostVariable( 'CancelButton' ) )
    {
        $http->setSessionVariable( 'StartedRegistration', 1 );
    }

    $ini = eZINI::instance();
    $errMsg = '';
    $checkErrNodeId = false;

    $defaultUserPlacement = (int)$ini->variable( "UserSettings", "DefaultUserPlacement" );

    $sql = "SELECT count(*) as count FROM ezcontentobject_tree WHERE node_id = $defaultUserPlacement";
    $rows = $db->arrayQuery( $sql );
    $count = $rows[0]['count'];
    if ( $count < 1 )
    {
        $errMsg = ezpI18n::tr( 'design/standard/user', 'The node (%1) specified in [UserSettings].DefaultUserPlacement setting in site.ini does not exist!', null, array( $defaultUserPlacement ) );
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
else
{
    if ( $http->hasSessionVariable( 'StartedRegistration' ) )
    {
        eZDebug::writeWarning( 'Cancel module run to protect against multiple form submits', 'user/register' );
        $http->removeSessionVariable( "RegisterUserID" );
        $http->removeSessionVariable( 'StartedRegistration' );
        $db->commit();
        return eZModule::HOOK_STATUS_CANCEL_RUN;
    }

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
            $http = eZHTTPTool::instance();
            if ( $http->hasPostVariable( 'RedirectIfDiscarded' ) )
            {
                eZRedirectManager::redirectTo( $module, $http->postVariable( 'RedirectIfDiscarded' ) );
            }
            else
            {
               eZRedirectManager::redirectTo( $module, '/' );
            }

            $version->removeThis();

            $http = eZHTTPTool::instance();
            $http->removeSessionVariable( "RegisterUserID" );
            $http->removeSessionVariable( 'StartedRegistration' );
            return eZModule::HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Publish' ) )
        {
            $userID = $object->attribute( 'id' );
            $operationResult = eZOperationHandler::execute( 'user', 'register', array( 'user_id' => $userID ) );

            // send feedback
            $ini = eZINI::instance();
            $tpl = eZTemplate::factory();
            $hostname = eZSys::hostname();
            $user = eZUser::fetch( $userID );
            $feedbackTypes = $ini->variableArray( 'UserSettings', 'RegistrationFeedback' );
            foreach ( $feedbackTypes as $feedbackType )
            {
                switch ( $feedbackType )
                {
                    case 'email':
                    {
                        // send feedback with the default email type
                        $mail = new eZMail();
                        $tpl->resetVariables();
                        $tpl->setVariable( 'user', $user );
                        $tpl->setVariable( 'object', $object );
                        $tpl->setVariable( 'hostname', $hostname );
                        $templateResult = $tpl->fetch( 'design:user/registrationfeedback.tpl' );

                        if ( $tpl->hasVariable( 'content_type' ) )
                            $mail->setContentType( $tpl->variable( 'content_type' ) );

                        $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
                        if ( $tpl->hasVariable( 'email_sender' ) )
                            $emailSender = $tpl->variable( 'email_sender' );
                        else if ( !$emailSender )
                            $emailSender = $ini->variable( 'MailSettings', 'AdminEmail' );

                        $feedbackReceiver = $ini->variable( 'UserSettings', 'RegistrationEmail' );
                        if ( $tpl->hasVariable( 'email_receiver' ) )
                            $feedbackReceiver = $tpl->variable( 'email_receiver' );
                        else if ( !$feedbackReceiver )
                            $feedbackReceiver = $ini->variable( 'MailSettings', 'AdminEmail' );

                        if ( $tpl->hasVariable( 'subject' ) )
                            $subject = $tpl->variable( 'subject' );
                        else
                            $subject = ezpI18n::tr( 'kernel/user/register', 'New user registered' );

                        $mail->setSender( $emailSender );
                        $mail->setReceiver( $feedbackReceiver );
                        $mail->setSubject( $subject );
                        $mail->setBody( $templateResult );
                        $mailResult = eZMailTransport::send( $mail );
                    } break;
                    default:
                    {
                        $registrationFeedbackClass = false;
                        // load custom registration feedback settings
                        if ( $ini->hasGroup( 'RegistrationFeedback_' . $feedbackType ) )
                        {
                            if ( $ini->hasVariable( 'RegistrationFeedback_' . $feedbackType, 'File' ) )
                                include_once( $ini->variable( 'RegistrationFeedback_' . $feedbackType, 'File' ) );
                            $registrationFeedbackClass = $ini->variable( 'RegistrationFeedback_' . $feedbackType, 'Class' );
                        }
                        // try to call the registration feedback class with function registrationFeedback
                        if ( $registrationFeedbackClass && method_exists( $registrationFeedbackClass, 'registrationFeedback' ) )
                            call_user_func( array( $registrationFeedbackClass, 'registrationFeedback' ), $user, $tpl, $object, $hostname );
                        else
                            eZDebug::writeWarning( "Unknown feedback type '$feedbackType'", 'user/register' );
                    }
                }
            }

            $http = eZHTTPTool::instance();
            $http->removeSessionVariable( "GeneratedPassword" );
            $http->removeSessionVariable( "RegisterUserID" );
            $http->removeSessionVariable( 'StartedRegistration' );

            // if everything is passed, login the user
            if( $operationResult['status'] === eZModuleOperationInfo::STATUS_CONTINUE )
            {
                $user->loginCurrent();
            }

            // check for redirectionvariable
            if( $operationResult['status'] === eZModuleOperationInfo::STATUS_CONTINUE ||
                    $operationResult['status'] === eZModuleOperationInfo::STATUS_HALTED )
            {
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
            else
            {
                eZDebug::writeError( 'Unexpected operation status: ' . $operationResult['status'], 'user/register' );
                // @todo: finish the failure code
                $module->redirectTo( '/user/register/5' );
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
                                'text' => ezpI18n::tr( 'kernel/user', 'User' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/user', 'Register' ) ) );

?>
