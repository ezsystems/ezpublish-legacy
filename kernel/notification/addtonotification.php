<?php
//
// Adding to notifications
//
// Created on: <24-Jan-2005 17:48 rl>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \file addtonotification.php
*/
include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/notification/handler/ezsubtree/ezsubtreenotificationrule.php' );

$module =& $Params['Module'];
$http =& eZHTTPTool::instance();

//$Offset = $Params['Offset'];
//$viewParameters = array( 'offset' => $Offset );

//$nodeID = $http->postVariable( 'ContentNodeID' );
$nodeID =& $Params['ContentNodeID'];
$user =& eZUser::currentUser();

$redirectURI = '';
if ( $http->hasSessionVariable( "LastAccessesURI" ) )
{
    $redirectURI = $http->sessionVariable( "LastAccessesURI" );
}

if ( $http->hasPostVariable( 'ViewMode' ) )
   $viewMode = $http->postVariable( 'ViewMode' );
else
    $viewMode = 'full';

if ( !$user->isLoggedIn() )
{
    eZDebug::writeError( 'User not logged in trying to subscribe for notification, node ID: ' . $nodeID,
                         'kernel/content/action.php' );
    $module->redirectTo( $redirectURI );
    return;
}

$contentNode = eZContentObjectTreeNode::fetch( $nodeID );
if ( !$contentNode )
{
    eZDebug::writeError( 'The nodeID parameter was empty, user ID: ' . $user->attribute( 'contentobject_id' ),
                         'kernel/content/action.php' );
    $module->redirectTo( $redirectURI );
    return;
}
if ( !$contentNode->attribute( 'can_read' ) )
{
    eZDebug::writeError( 'User does not have access to subscribe for notification, node ID: ' . $nodeID . ', user ID: ' . $user->attribute( 'contentobject_id' ),
                         'kernel/content/action.php' );
    $module->redirectTo( $redirectURI );
    return;
}

$tpl =& templateInit();
if ( $http->hasSessionVariable( "LastAccessesURI" ) )
    $tpl->setVariable( 'redirect_url', $http->sessionVariable( "LastAccessesURI" ) );
//else
//    $tpl->setVariable( 'redirect_url', $module->functionURI( 'view' ) . '/full/2' );

$alreadyExists = true;

$nodeIDList = eZSubtreeNotificationRule::fetchNodesForUserID( $user->attribute( 'contentobject_id' ), false );
if ( !in_array( $nodeID, $nodeIDList ) )
{
    $rule = eZSubtreeNotificationRule::create( $nodeID, $user->attribute( 'contentobject_id' ) );
    $rule->store();
    $alreadyExists = false;
}
$tpl->setVariable( 'already_exists', $alreadyExists );
$tpl->setVariable( 'node_id', $nodeID );


$Result = array();
$Result['content'] =& $tpl->fetch( 'design:notification/addingresult.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/notification', ($alreadyExists ? 'Notification already exists.' : 'Notification was added successfully!') ),
                                'url' => false ) );

?>
