<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
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

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "lib/ezutils/classes/ezmail.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );
include_once( "kernel/classes/ezcontentclass.php" );

$http =& eZHTTPTool::instance();
$Module =& $Params["Module"];

$Params['TemplateName'] = "design:user/register.tpl";
$EditVersion = 1;

include_once( "kernel/common/template.php" );
$tpl =& templateInit();
$Params['TemplateObject'] =& $tpl;

// $http->removeSessionVariable( "RegisterUserID" );

// Create new user object if user is not logged in
$user =& eZUser::currentUser();
if ( !$user->isLoggedIn() and !$http->hasSessionVariable( "RegisterUserID" ) )
{
    $ini =& eZINI::instance();

    $defaultUserPlacement = $ini->variable( "UserSettings", "DefaultUserPlacement" );

    $userClassID = $ini->variable( "UserSettings", "UserClassID" );
    $class =& eZContentClass::fetch( $userClassID );

    $userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
    $defaultSectionID = $ini->variable( "UserSettings", "DefaultSectionID" );
    // Create object by user 14 in section 1
    $contentObject =& $class->instantiate( $userCreatorID, $defaultSectionID );
    $objectID = $contentObject->attribute( 'id' );

    // Store the ID in session variable
    $http->setSessionVariable( "RegisterUserID", $objectID );

    $userID = $objectID;

    $nodeAssignment =& eZNodeAssignment::create( array(
                                                     'contentobject_id' => $contentObject->attribute( 'id' ),
                                                     'contentobject_version' => 1,
                                                     'parent_node' => $defaultUserPlacement,
                                                     'is_main' => 1
                                                     )
                                                 );
    $nodeAssignment->store();
}
else if ( $http->hasSessionVariable( "RegisterUserID" ) )
{
    $userID = $http->sessionVariable( "RegisterUserID" );
}
else
{
    $userID = $user->attribute( 'contentobject_id' );
}


$Params['ObjectID'] = $userID;

$Module->addHook( 'post_publish', 'registerSearchObject', 1, false );

if ( !function_exists( 'checkContentActions' ) )
{
    function checkContentActions( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $EditVersion, $EditLanguage )
    {
        if ( $module->isCurrentAction( 'Cancel' ) )
        {
            $module->redirectTo( '/content/view/full/2/' );

            $objectID = $object->attribute( 'id' );
            $versionCount= $object->getVersionCount();
            $db =& eZDB::instance();
            $db->query( "DELETE FROM ezcontentobject_link
		                 WHERE from_contentobject_id=$objectID AND from_contentobject_version=$EditVersion" );
            $db->query( "DELETE FROM eznode_assignment
		                 WHERE contentobject_id=$objectID AND contentobject_version=$EditVersion" );
            $version->remove();
            foreach ( $contentObjectAttributes as $contentObjectAttribute )
            {
                $objectAttributeID = $contentObjectAttribute->attribute( 'id' );
                $version = $contentObjectAttribute->attribute( 'version' );
                if ( $version == $EditVersion )
                {
                    $contentObjectAttribute->remove( $objectAttributeID, $version );
                }
            }
            if ( $versionCount == 1 )
            {
                $object->remove();
            }
            $http =& eZHTTPTool::instance();
            $http->removeSessionVariable( "RegisterUserID" );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }

        if ( $module->isCurrentAction( 'Publish' ) )
        {
            $http =& eZHTTPTool::instance();

            $user =& eZUser::currentUser();
            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ),
                                                                                         'version' => $version->attribute( 'version') ) );

            $object = eZContentObject::fetch( $object->attribute( 'id' ) );

            // Check if user should be enabled and logged in
            $user =& eZUser::fetch( $object->attribute( 'id' ) );
            $user->loginCurrent();

            $receiver = $user->attribute( 'email' );
            $mail = new eZMail();
            if ( !$mail->validate( $receiver ) )
            {
            }
            include_once( "kernel/common/template.php" );
            include_once( 'lib/ezutils/classes/ezmail.php' );
            include_once( 'lib/ezutils/classes/ezmailtransport.php' );
            $tpl =& templateInit();
            $tpl->setVariable( 'user', $user );
            $password = $http->sessionVariable( "GeneratedPassword" );

            $tpl->setVariable( 'password', $password );

            $templateResult =& $tpl->fetch( 'design:user/registrationinfo.tpl' );

            $mail->setReceiver( $receiver );
            $mail->setSubject( 'registration info' );
            $mail->setBody( $templateResult );
            $mailResult = eZMailTransport::send( $mail );

            $http->removeSessionVariable( "GeneratedPassword" );
            $http->removeSessionVariable( "RegisterUserID" );

            // check for redirectionvariable
            if ( eZHTTPTool::hasSessionVariable( 'RedirectAfterUserRegister' ) )
            {
                $module->redirectTo( eZHTTPTool::sessionVariable( 'RedirectAfterUserRegister' ) );
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
if ( $includeResult != 1 )
{
    return $includeResult;
}
$ini =& eZINI::instance();
eZDebug::writeDebug( $includeResult );
if ( $ini->variable( 'SiteSettings', 'LoginPage' ) == 'custom' )
    $Result['pagelayout'] = 'loginpagelayout.tpl';
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/user', 'User' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/user', 'Register' ) ) );


/*
$message = 0;
$userIDNotValid = 0;
$passwordNotValid = 0;
$emailNotValid = 0;
$userClassAttributes =& eZContentClassAttribute::fetchFilteredList( array( 'contentclass_id' => 4, 'version' => 0 ) );
$userAttributes = array();
foreach ( $userClassAttributes as $userClassAttribute )
{
    $classAttributeID = $userClassAttribute->attribute( 'id' );
    $classAttributeName = $userClassAttribute->attribute( 'name' );
    if( $classAttributeName != "User account" )
    {
        $item = array( "name" => $classAttributeName,
                       "classAttribute_id" => $classAttributeID );
        $userAttributes[] = $item;
    }
}

$http =& eZHTTPTool::instance();

$filledUserAttributes = array();
foreach ( $userAttributes as $userAttribute )
{
    $classAttributeID = $userAttribute["classAttribute_id"];
    $classAttributeName = $userAttribute["name"];
    if ( $http->hasPostVariable( 'ContentObjectAttribute_' . $classAttributeID  ) )
    {
         $value = $http->postVariable( 'ContentObjectAttribute_' . $classAttributeID  );
         $item = array( "name" => $classAttributeName,
                        "classAttribute_id" => $classAttributeID,
                        "value" => $value );
         $filledUserAttributes[] = $item;
    }else
    {
        $item = array( "name" => $classAttributeName,
                       "classAttribute_id" => $classAttributeID );
        $filledUserAttributes[] = $item;
    }
}

if ( $http->hasPostVariable( "StoreButton" ) )
{
    $validate = true;
    $userID = null;
    if ( ( $http->hasPostVariable( "Login_id" ) ) and
         ( $http->hasPostVariable( "Email" ) ) and
         ( $http->hasPostVariable( "Password" ) ) and
         ( $http->hasPostVariable( "Password_confirm" ) ) )
    {
        $login = $http->postVariable( "Login_id" );
        $email = $http->postVariable( "Email" );
        $password = $http->postVariable( "Password" );
        $passwordConfirm = $http->postVariable( "Password_confirm" );
        $existUser =& eZUser::fetchByName( $login);
        $isEmailValidate =  eZMail::validate( $email );
        $message ="";
        $validate = true;
        if ( $existUser != null )
        {
            $message .= "User exist, please choose another user name.<br>";
            $validate = false;
            $userIDNotValid = true;
        }
        if ( ! $isEmailValidate )
        {
            $message .= "Email address is not valid.<br>";
            $validate = false;
            $emailNotValid = true;
        }
        if ( ( $password != $passwordConfirm ) || ( $password == "" ) )
        {
            $message .= "Password not match.<br>";
            $validate = false;
            $passwordNotValid = true;
        }

        if ( $validate )
        {
            $class =& eZContentClass::fetch( 4 );
            //Todo which owner id should use?
            $object =& $class->instantiate( 14, 0 );
            $object->store();
            $objectName = null;
            $objectAttributes =& $object->contentObjectAttributes();
            foreach ( $objectAttributes as $objectAttribute )
            {
                $classAttributeID = $objectAttribute->attribute('contentclassattribute_id');
                foreach ( $userClassAttributes as $userClassAttribute )
                {
                    $cID = $userClassAttribute->attribute( 'id' );
                    $classAttributeName = $userClassAttribute->attribute( 'name' );
                    if ( $cID == $classAttributeID )
                    {
                         if( $classAttributeName != "User account" )
                         {
                              if ( $http->hasPostVariable( 'ContentObjectAttribute_' . $cID  ) )
                              {
                                  $value = $http->postVariable( 'ContentObjectAttribute_' . $cID  );
                                  $objectAttribute->setAttribute( 'data_text', $value );
                                  $objectAttribute->store();
                                  //Todo change objectName according to defination such as<first name> <last name>
                                  $objectName .= $value . ' ';
                              }
                         }
                         else
                         {
                             $user =& eZUser::fetch( $objectAttribute->attribute('contentobject_id' ) );
                             $userID = $user->attribute( 'contentobject_id' );
                             $user->setInformation( $userID, $login, $email, $password );
                             $user->store();
                             $isEnabled = 0;
                             $userSetting =& eZUserSetting::create( $userID, $isEnabled );
                             $userSetting->store();
                         }
                    }
                }
            }
            $object->setName( $objectName );
            $object->store();

            $userIDHash = md5( "$login\n$userID" );
            include_once( "lib/ezutils/classes/ezmail.php" );
            $mail = new eZMail();
            $title = "User registration confirmation";
            $body .= "Your account profile:\n";
            $body .= "Login ID: " . $login;
            $body .= "\nPassword: " . $password;
            $domain = eZSys::hostname();
            $admin = eZSys::serverVariable( 'SERVER_ADMIN' );
            $body .= "\n\nPlease go to the link below to activate your account:\n";;

            $activationURL = "http://" .  $domain;
            $activationURL .= eZSys::indexDir();
            $activationURL .= '/user/activate/' . $login . '/' . $userIDHash;

            $body .= "\n$activationURL";
            $body .= "\n\n\n";
            $mail->setReceiver( $email );
            $mail->setSender( $admin );
            $mail->setFromName( "Administrator" );
            $mail->setSubject( $title );
            $mail->setBody( $body );
            $mail->send();
            return $Module->redirectTo( $Module->functionURI( "success" ) );
        }
    }
}

if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( "/user/login" );
    return;
}

$Module->setTitle( "Sign up" );
// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );
$tpl->setVariable( "userAttributes", $filledUserAttributes );
$tpl->setVariable( "message", $message );
$tpl->setVariable( "login", $login );
$tpl->setVariable( "email", $email );
$tpl->setVariable( "password", $password );
$tpl->setVariable( "passwordConfirm", $passwordConfirm );
$tpl->setVariable( "emailNotValid", $emailNotValid );
$tpl->setVariable( "passwordNotValid", $passwordNotValid );
$tpl->setVariable( "userIDNotValid", $userIDNotValid );

*/
?>
