<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$module = $Params['Module'];
$http = eZHTTPTool::instance();

//$Offset = $Params['Offset'];
//$viewParameters = array( 'offset' => $Offset );

//$nodeID = $http->postVariable( 'ContentNodeID' );
$nodeID = $Params['ContentNodeID'];
$user = eZUser::currentUser();

$redirectURI = $http->postVariable( 'RedirectURI', $http->sessionVariable( 'LastAccessesURI', '/' ) );

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

$tpl = eZTemplate::factory();
if ( $http->hasSessionVariable( "LastAccessesURI", false ) )
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
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/notification', ($alreadyExists ? 'Notification already exists.' : 'Notification was added successfully!') ),
                                'url' => false ) );

?>
