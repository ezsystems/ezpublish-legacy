<?php
//
// Created on: <24-Apr-2002 16:06:53 bf>
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

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "lib/ezutils/classes/ezmail.php" );
include_once( "lib/ezutils/classes/ezmailtransport.php" );
include_once( "lib/ezutils/classes/ezsys.php" );
include_once( 'lib/ezutils/classes/ezfunctionhandler.php' );
include_once( "lib/ezutils/classes/ezini.php" );

include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "kernel/common/template.php" );


$http =& eZHTTPTool::instance();

$NodeID = $Params['NodeID'];
$Module =& $Params['Module'];

$tpl =& templateInit();
$tpl->setVariable( 'action', '' );

$error_strings = array();
$yourName = '';
$yourEmail = '';
$user = eZUser::currentUser();
$ini =& eZINI::instance();
// Get name and email from current user, unless it is the anonymous user
if ( is_object( $user ) && $user->id() != $ini->variable( 'UserSettings', 'AnonymousUserID' ) )
{
    $userObject = $user->attribute( 'contentobject' );
    $yourName = $userObject->attribute( 'name' );
    $yourEmail = $user->attribute( 'email' );
}
$receiversName = '';
$receiversEmail = '';

if ( $http->hasPostVariable( 'NodeID' ) )
    $NodeID = $http->variable( 'NodeID' );

$node =& eZFunctionHandler::execute( 'content', 'node', array( 'node_id' => $NodeID ) );
if ( $node )
    $nodename = $node->Name;
$hostname = eZSys::hostname();
$subject = ezi18n( 'kernel/content', 'Tip from %1: %2', null, array( $hostname, $nodename ) );
$comment = '';

if ( $http->hasPostVariable( 'SendButton' ) )
{
    if ( $http->hasPostVariable( 'YourName' ) )
        $yourName = $http->variable( 'YourName' );

    if ( $http->hasPostVariable( 'YourEmail' ) )
        $yourEmail = $http->variable( 'YourEmail' );

    if ( $http->hasPostVariable( 'ReceiversName' ) )
        $receiversName = $http->variable( 'ReceiversName' );

    if ( $http->hasPostVariable( 'ReceiversEmail' ) )
        $receiversEmail = $http->variable( 'ReceiversEmail' );

    if ( $http->hasPostVariable( 'Subject' ) )
        $subject = $http->variable( 'Subject' );

    if ( $http->hasPostVariable( 'Comment' ) )
        $comment = $http->variable( 'Comment' );

    // email validation
    if ( !eZMail::validate( $yourEmail ) )
        $error_strings[] = ezi18n( 'kernel/content', 'The email address of the sender is not valid' );
    if ( !eZMail::validate( $receiversEmail ) )
        $error_strings[] = ezi18n( 'kernel/content', 'The email address of the receiver is not valid' );

    // no validation errors
    if ( count( $error_strings ) == 0 )
    {
        $mail = new eZMail();
        $mail->setSender( $yourEmail, $yourName );
        $mail->setReceiver( $receiversEmail, $receiversName );
        $mail->setSubject( $subject );

        // fetch text from mail template
        $mailtpl =& templateInit();
        $mailtpl->setVariable( 'hostname', $hostname );
        $mailtpl->setVariable( 'nodename', $nodename );
        $mailtpl->setVariable( 'node_id', $NodeID );
        $mailtpl->setVariable( 'your_name', $yourName );
        $mailtpl->setVariable( 'your_email', $yourEmail );
        $mailtpl->setVariable( 'receivers_name', $receiversName );
        $mailtpl->setVariable( 'receivers_email', $receiversEmail );
        $mailtpl->setVariable( 'comment', $comment );
        $mailtext =& $mailtpl->fetch( 'design:content/tipafriendmail.tpl' );

        $mail->setBody( $mailtext );

        // mail was sent ok
        if ( eZMailTransport::send( $mail ) )
        {
            $tpl->setVariable( 'action', 'confirm' );

            // Increase tipafriend count for this node
            include_once( "kernel/classes/eztipafriendcounter.php" );
            $counter =& eZTipafriendCounter::fetch( $NodeID );
            if ( $counter == null )
            {
                $counter =& eZTipafriendCounter::create( $NodeID );
            }
            $counter->increase();
            $counter->store();
        }
        else // some error occured
        {
            $tpl->setVariable( 'action', 'error' );
        }
        if ( $http->hasPostVariable( 'RedirectBack' ) && $http->postVariable( 'RedirectBack' ) == 1 )
        {
            $Module->redirectTo( '/content/view/full/' . $NodeID );
            return;
        }
    }
}
else if ( $http->hasPostVariable( 'CancelButton' ) )
{
    $Module->redirectTo( '/content/view/full/' . $NodeID );
}


$Module->setTitle( 'Tip a friend' );

$tpl->setVariable( 'node_id', $NodeID );
$tpl->setVariable( 'error_strings', $error_strings );
$tpl->setVariable( 'your_name', $yourName );
$tpl->setVariable( 'your_email', $yourEmail );
$tpl->setVariable( 'receivers_name', $receiversName );
$tpl->setVariable( 'receivers_email', $receiversEmail );
$tpl->setVariable( 'subject', $subject );
$tpl->setVariable( 'comment', $comment );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/tipafriend.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Tip a friend' ),
                                'url' => false ) );

?>
