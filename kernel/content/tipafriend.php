<?php
//
// Created on: <24-Apr-2002 16:06:53 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
//include_once( "lib/ezutils/classes/ezmail.php" );
//include_once( "lib/ezutils/classes/ezmailtransport.php" );
//include_once( "lib/ezutils/classes/ezsys.php" );
//include_once( "lib/ezutils/classes/ezini.php" );

//include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
require_once( "kernel/common/template.php" );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );


$http = eZHTTPTool::instance();

$NodeID = (int)$Params['NodeID'];
$Module = $Params['Module'];

$tpl = templateInit();
$tpl->setVariable( 'action', '' );

$error_strings = array();
$yourName = '';
$yourEmail = '';
$user = eZUser::currentUser();
$ini = eZINI::instance();
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
    $NodeID = (int)$http->variable( 'NodeID' );

$node = eZContentObjectTreeNode::fetch( $NodeID );
if ( is_object( $node ) )
{
    $nodeName = $node->getName();
}
else
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$object = $node->object();
if ( !$object->canRead() )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array( 'AccessList' => $object->accessList( 'read' ) ) );
}

$hostName = eZSys::hostname();
$subject = ezi18n( 'kernel/content', 'Tip from %1: %2', null, array( $hostName, $nodeName ) );
$comment = '';
$overrideKeysAreSet = false;

if ( $http->hasPostVariable( 'SendButton' ) )
{
    if ( $http->hasPostVariable( 'YourName' ) )
        $yourName = $http->variable( 'YourName' );

    if ( $http->hasPostVariable( 'YourEmail' ) )
        $yourEmail = $http->variable( 'YourEmail' );

    if ( $http->hasPostVariable( 'ReceiversEmail' ) )
        $receiversEmail = $http->variable( 'ReceiversEmail' );

    $receiversName = $receiversEmail;
    if ( $http->hasPostVariable( 'ReceiversName' ) )
        $receiversName = $http->variable( 'ReceiversName' );

    if ( $http->hasPostVariable( 'Subject' ) )
        $subject = $http->variable( 'Subject' );

    if ( $http->hasPostVariable( 'Comment' ) )
        $comment = $http->variable( 'Comment' );

    // email validation
    if ( !eZMail::validate( $yourEmail ) )
        $error_strings[] = ezi18n( 'kernel/content', 'The email address of the sender is not valid' );
    if ( !eZMail::validate( $receiversEmail ) )
        $error_strings[] = ezi18n( 'kernel/content', 'The email address of the receiver is not valid' );

    $fromEmail = null;

    if ( $ini->hasVariable( 'TipAFriend', 'FromEmail' ) )
    {
        $fromEmail = $ini->variable( 'TipAFriend', 'FromEmail' );
        if ( $fromEmail != null )
            if ( !eZMail::validate( $fromEmail ) )
            {
                eZDebug::writeError( "The email < $fromEmail > specified in [TipAFriend].FromEmail setting in site.ini is not valid",'tipafriend' );
                $fromEmail = null;
            }
    }
    if ( $fromEmail == null )
        $fromEmail = $yourEmail;

    //include_once( "kernel/classes/eztipafriendrequest.php" );
    if ( !eZTipafriendRequest::checkReceiver( $receiversEmail ) )
        $error_strings[] = ezi18n( 'kernel/content', 'The receiver has already received the maximum number of tipafriend mails the last hours' );

    // no validation errors
    if ( count( $error_strings ) == 0 )
    {
        $mail = new eZMail();
        $mail->setSender( $fromEmail, $yourName );
        $mail->setReceiver( $receiversEmail, $receiversName );
        $mail->setSubject( $subject );

        // fetch
        $res = eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'object',           $object->attribute( 'id' ) ),
                              array( 'class',            $object->attribute( 'contentclass_id' ) ),
                              array( 'class_identifier', $object->attribute( 'class_identifier' ) ),
                              array( 'class_group',      $object->attribute( 'match_ingroup_id_list' ) ),
                              array( 'section',          $object->attribute( 'section_id' ) ),
                              array( 'node',             $NodeID ),
                              array( 'parent_node',      $node->attribute( 'parent_node_id' ) ),
                              array( 'depth',            $node->attribute( 'depth' ) ),
                              array( 'url_alias',        $node->attribute( 'url_alias' ) )
                              ) );
        $overrideKeysAreSet = true;

        // fetch text from mail template
        $mailtpl = templateInit();
        $mailtpl->setVariable( 'hostname', $hostName );
        $mailtpl->setVariable( 'nodename', $nodeName );
        $mailtpl->setVariable( 'node_id', $NodeID );
        $mailtpl->setVariable( 'your_name', $yourName );
        $mailtpl->setVariable( 'your_email', $yourEmail );
        $mailtpl->setVariable( 'receivers_name', $receiversName );
        $mailtpl->setVariable( 'receivers_email', $receiversEmail );
        $mailtpl->setVariable( 'comment', $comment );
        $mailtext = $mailtpl->fetch( 'design:content/tipafriendmail.tpl' );
        
        if ( $mailtpl->hasVariable( 'content_type' ) )
            $mail->setContentType( $mailtpl->variable( 'content_type' ) );

        $mail->setBody( $mailtext );

        // mail was sent ok
        if ( eZMailTransport::send( $mail ) )
        {
            $tpl->setVariable( 'action', 'confirm' );

            $request = eZTipafriendRequest::create( $receiversEmail );
            $request->store();

            // Increase tipafriend count for this node
            //include_once( "kernel/classes/eztipafriendcounter.php" );
            $counter = eZTipafriendCounter::create( $NodeID );
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

if ( !$overrideKeysAreSet )
{
    $res = eZTemplateDesignResource::instance();
    $res->setKeys( array( array( 'object',           $object->attribute( 'id' ) ),
                          array( 'class',            $object->attribute( 'contentclass_id' ) ),
                          array( 'class_identifier', $object->attribute( 'class_identifier' ) ),
                          array( 'class_group',      $object->attribute( 'match_ingroup_id_list' ) ),
                          array( 'section',          $object->attribute( 'section_id' ) ),
                          array( 'node',             $NodeID ),
                          array( 'parent_node',      $node->attribute( 'parent_node_id' ) ),
                          array( 'depth',            $node->attribute( 'depth' ) ),
                          array( 'url_alias',        $node->attribute( 'url_alias' ) )
                          ) );
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
$Result['content'] = $tpl->fetch( 'design:content/tipafriend.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Tip a friend' ),
                                'url' => false ) );

?>
