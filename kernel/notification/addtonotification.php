<?php
//
// Adding to notifications
//
// Created on: <24-Jan-2005 17:48 rl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*! \file
*/
require_once( 'kernel/common/template.php' );
$module = $Params['Module'];
$http = eZHTTPTool::instance();

//$Offset = $Params['Offset'];
//$viewParameters = array( 'offset' => $Offset );

//$nodeID = $http->postVariable( 'ContentNodeID' );
$nodeID = $Params['ContentNodeID'];
$user = eZUser::currentUser();

$redirectURI = $http->hasSessionVariable( "LastAccessesURI" ) ? $http->sessionVariable( "LastAccessesURI" ): '';

$viewMode = $http->hasPostVariable( 'ViewMode' ) ? $http->postVariable( 'ViewMode' ) : 'full';

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

$tpl = templateInit();
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
$Result['content'] = $tpl->fetch( 'design:notification/addingresult.tpl' );
$Result['path'] = array( array( 'text' => eZi18n::translate( 'kernel/notification', ($alreadyExists ? 'Notification already exists.' : 'Notification was added successfully!') ),
                                'url' => false ) );

?>
