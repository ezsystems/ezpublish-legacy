<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
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

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "lib/ezutils/classes/ezmail.php" );
$Module =& $Params["Module"];
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
            foreach (  $objectAttributes as $objectAttribute )
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
            $object->setAttribute( 'name', $objectName );
            $object->store();

            $userIDHash = md5( "$login\n$userID" );
            include_once( "lib/ezutils/classes/ezmail.php" );
            $mail = new eZMail();
            $title = "User registration confirmation";
            $body .= "Your account profile:\n";
            $body .= "Login ID: " . $login;
            $body .= "\nPassword: " . $password;
            $domain = getenv( 'HTTP_HOST' );
            $admin = getenv( 'SERVER_ADMIN' );
            $body .= "\n\nPlease go to the link below to activate your account:\n";;
            $body .= "\nhttp://" .  $domain . '/user/activate/' . $login . '/' . $userIDHash;
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

$Result = array();
$Result['content'] =& $tpl->fetch( "design:user/register.tpl" );

?>
