<?php
//
// Created on: <13-Мар-2003 13:06:18 sp>
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

/*! \file forgetpassword.php
*/

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );


include_once( "kernel/common/template.php" );
$tpl =& templateInit();
$tpl->setVariable( 'generated', false );
$tpl->setVariable( 'wrong_email', false );

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];


if ( $module->isCurrentAction( "Generate" ) )
{
    $ini =& eZINI::instance();
    $passwordLength = $ini->variable( "UserSettings", "GeneratePasswordLength" );
    $password = eZUser::createPassword( $passwordLength );
    $passwordConfirm = $password;
    eZDebug::writeDebug( $password, "New Password" );

//    $http->setSessionVariable( "GeneratedPassword", $password );

/*    if ( $moduile->hasActionParameter( "Login" ) )
    {
        $login = $module->actionParameter( "Login" );
        $user =& eZUser::fetchByName( $login );
        $email = $user->attribute( 'email' );
    }
*/
    if ( $module->hasActionParameter( "Email" ) )
    {
        $email = $module->actionParameter( "Email" );
        $users =& eZPersistentObject::fetchObjectList( eZUser::definition(),
                                                       null,
                                                       array( 'email' => $email ),
                                                       null,
                                                       null,
                                                       true );
        if ( count($users) > 0 )
        {
            $userToSendEmail =& $users[0];
            foreach ( array_keys( $users ) as $key )
            {
                $user =& $users[$key];
                $user->setInformation( $user->id(), $user->attribute( 'login' ), $email, $password, $passwordConfirm );
                $user->store();
            }
            include_once( "kernel/common/template.php" );
            include_once( 'lib/ezutils/classes/ezmail.php' );
            include_once( 'lib/ezutils/classes/ezmailtransport.php' );

            $receiver = $email;
            $mail = new eZMail();
            if ( !$mail->validate( $receiver ) )
            {
            }
            $tpl =& templateInit();
            $tpl->setVariable( 'user', $userToSendEmail );
            $tpl->setVariable( 'object', $userToSendEmail->attribute( 'contentobject' ) );
            $tpl->setVariable( 'password', $password );
            eZDebug::writeDebug( $password, "New Password" );
            $templateResult =& $tpl->fetch( 'design:user/forgetpasswordmail.tpl' );
            $mail->setReceiver( $receiver );
            $subject = ezi18n( 'kernel/user/register', 'Registration info' );
            if ( $tpl->hasVariable( 'subject' ) )
                $subject = $tpl->variable( 'subject' );
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            $mailResult = eZMailTransport::send( $mail );
            $tpl->setVariable( 'generated', true );
            $tpl->setVariable( 'email', $email );
        }
        else
        {
            $tpl->setVariable( 'wrong_email', $email );
        }
    }
}
eZDebug::writeDebug( "forgetPassword" );
$Result['content'] =& $tpl->fetch( 'design:user/forgetpassword.tpl' );

?>
