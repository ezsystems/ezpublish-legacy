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
$your_name = '';
$your_email = '';
$user = eZUser::currentUser();
$ini =& eZINI::instance();
// Get name and email from current user, unless it is the anonymous user
if ( is_object( $user ) && $user->id() != $ini->variable( 'UserSettings', 'AnonymousUserID' ) )
{
    $user_content = $user->attribute( 'contentobject' );
    $map = $user_content->attribute( 'data_map' );
    $your_name = $map['first_name']->content() . ' ' . $map['last_name']->content();
    $your_email = $user->attribute( 'email' );
}
$receivers_name = '';
$receivers_email = '';

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
        $your_name = $http->variable( 'YourName' );

    if ( $http->hasPostVariable( 'YourEmail' ) && $your_email == '' )
            $your_email = $http->variable( 'YourEmail' );

    if ( $http->hasPostVariable( 'ReceiversName' ) )
        $receivers_name = $http->variable( 'ReceiversName' );

    if ( $http->hasPostVariable( 'ReceiversEmail' ) )
        $receivers_email = $http->variable( 'ReceiversEmail' );

    if ( $http->hasPostVariable( 'Subject' ) )
        $subject = $http->variable( 'Subject' );

    if ( $http->hasPostVariable( 'Comment' ) )
        $comment = $http->variable( 'Comment' );

    // email validation
    if ( !eZMail::validate( $your_email ) )
        $error_strings[] = ezi18n( 'kernel/content', 'The email address of the sender is not valid' );
    if ( !eZMail::validate( $receivers_email ) )
        $error_strings[] = ezi18n( 'kernel/content', 'The email address of the receiver is not valid' );

    // no validation errors
    if ( count( $error_strings ) == 0 )
    {
        $mail = new eZMail();
        $mail->setSender( $your_email, $your_name );
        $mail->setReceiver( $receivers_email, $receivers_name );
        $mail->setSubject( $subject );

        // fetch text from mail template
        $mailtpl =& templateInit();
        $mailtpl->setVariable( 'hostname', $hostname );
        $mailtpl->setVariable( 'nodename', $nodename );
        $mailtpl->setVariable( 'node_id', $NodeID );
        $mailtpl->setVariable( 'your_name', $your_name );
        $mailtpl->setVariable( 'your_email', $your_email );
        $mailtpl->setVariable( 'receivers_name', $receivers_name );
        $mailtpl->setVariable( 'receivers_email', $receivers_email );
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
$tpl->setVariable( 'your_name', $your_name );
$tpl->setVariable( 'your_email', $your_email );
$tpl->setVariable( 'receivers_name', $receivers_name );
$tpl->setVariable( 'receivers_email', $receivers_email );
$tpl->setVariable( 'subject', $subject );
$tpl->setVariable( 'comment', $comment );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/tipafriend.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Tip a friend' ),
                                'url' => false ) );

?>
