<?php
//
// Created on: <04-Jul-2002 13:06:30 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezcontentbrowse.php' );
include_once( 'kernel/classes/ezcontentbrowsebookmark.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( "lib/ezutils/classes/ezhttptool.php" );

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

if ( $http->hasPostVariable( 'NewButton' ) || $module->isCurrentAction( 'NewObjectAddNodeAssignment' )  )
{
    $hasClassInformation = false;
    $contentClassID = false;
    $contentClassIdentifier = false;
    $class = false;
    if ( $http->hasPostVariable( 'ClassID' ) )
    {
        $contentClassID = $http->postVariable( 'ClassID' );
        if ( $contentClassID )
            $hasClassInformation = true;
    }
    else if ( $http->hasPostVariable( 'ClassIdentifier' ) )
    {
        $contentClassIdentifier = $http->postVariable( 'ClassIdentifier' );
        $class =& eZContentClass::fetchByIdentifier( $contentClassIdentifier );
        if ( is_object( $class ) )
        {
            $contentClassID = $class->attribute( 'id' );
            if ( $contentClassID )
                $hasClassInformation = true;
        }
    }
    if ( ( $hasClassInformation && $http->hasPostVariable( 'NodeID' ) ) || $module->isCurrentAction( 'NewObjectAddNodeAssignment' ) )
    {
        if (  $module->isCurrentAction( 'NewObjectAddNodeAssignment' ) )
        {
            $selectedNodeIDArray = eZContentBrowse::result( 'NewObjectAddNodeAssignment' );
            if ( count( $selectedNodeIDArray ) == 0 )
                return $module->redirectToView( 'view', array( 'full', 2 ) );
            $node =& eZContentObjectTreeNode::fetch( $selectedNodeIDArray[0] );
        }
        else
        {
            $node =& eZContentObjectTreeNode::fetch( $http->postVariable( 'NodeID' ) );
        }
        $parentContentObject =& $node->attribute( 'object' );

        if ( $parentContentObject->checkAccess( 'create', $contentClassID,  $parentContentObject->attribute( 'contentclass_id' ), $accessList ) == '1' )
        {
            $user =& eZUser::currentUser();
            $userID =& $user->attribute( 'contentobject_id' );
            $sectionID = $parentContentObject->attribute( 'section_id' );

            if ( !is_object( $class ) )
                $class =& eZContentClass::fetch( $contentClassID );
            $contentObject =& $class->instantiate( $userID, $sectionID );
            $nodeAssignment =& eZNodeAssignment::create( array(
                                                             'contentobject_id' => $contentObject->attribute( 'id' ),
                                                             'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                             'parent_node' => $node->attribute( 'node_id' ),
                                                             'is_main' => 1
                                                             )
                                                         );
            if ( $http->hasPostVariable( 'AssignmentRemoteID' ) )
            {
                $nodeAssignment->setAttribute( 'remote_id', $http->postVariable( 'AssignmentRemoteID' ) );
            }
            $nodeAssignment->store();

            if ( $http->hasPostVariable( 'RedirectURIAfterPublish' ) )
            {
                $http->setSessionVariable( 'RedirectURIAfterPublish', $http->postVariable( 'RedirectURIAfterPublish' ) );
            }
            $module->redirectTo( $module->functionURI( 'edit' ) . '/' . $contentObject->attribute( 'id' ) . '/' . $contentObject->attribute( 'current_version' ) );
            return;

        }
        else
        {
            return $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
        }

    }
    else if ( $hasClassInformation )
    {
        if ( !is_object( $class ) )
            $class =& eZContentClass::fetch( $contentClassID );
        eZContentBrowse::browse( array( 'action_name' => 'NewObjectAddNodeAssignment',
                                        'description_template' => 'design:content/browse_first_placement.tpl',
                                        'keys' => array( 'class' => $class->attribute( 'id' ),
                                                         'classgroup' => $class->attribute( 'ingroup_id_list' ) ),
                                        'persistent_data' => array( 'ClassID' => $class->attribute( 'id' ) ),
                                        'content' => array( 'class_id' => $class->attribute( 'id' ) ),
                                        'from_page' => "/content/action" ),
                                 $module );
    }
}
else if ( $http->hasPostVariable( 'EditButton' )  )
{
    if ( $http->hasPostVariable( 'ContentObjectID' ) )
    {
        $parameters = array( $http->postVariable( 'ContentObjectID' ) );
        if ( $http->hasPostVariable( 'ContentObjectVersion' ) )
        {
            $parameters[] = $http->postVariable( 'ContentObjectVersion' );
            if ( $http->hasPostVariable( 'ContentObjectLanguageCode' ) )
            {
                $parameters[] = $http->postVariable( 'ContentObjectLanguageCode' );
            }
        }
        $module->redirectToView( 'edit', $parameters );
        return;
    }
}
else if ( $http->hasPostVariable( 'PreviewPublishButton' )  )
{
    if ( $http->hasPostVariable( 'ContentObjectID' ) )
    {
        $parameters = array( $http->postVariable( 'ContentObjectID' ) );
        if ( $http->hasPostVariable( 'ContentObjectVersion' ) )
        {
            $parameters[] = $http->postVariable( 'ContentObjectVersion' );
            if ( $http->hasPostVariable( 'ContentObjectLanguageCode' ) )
            {
                $parameters[] = $http->postVariable( 'ContentObjectLanguageCode' );
            }
        }
        $module->setCurrentAction( 'Publish', 'edit' );
        return $module->run( 'edit', $parameters );
    }
}
else if ( $http->hasPostVariable( 'RemoveButton' ) )
{
    if ( $http->hasPostVariable( 'ViewMode' ) )
    {
        $viewMode = $http->postVariable( 'ViewMode' );
    }
    else
    {
        $viewMode = 'full';
    }
//     if ( $http->hasPostVariable( 'TopLevelNode' ) )
//     {
//         $topLevelNode = $http->postVariable( 'TopLevelNode' );
//     }
//     else
//     {
//         $topLevelNode = '2';
//     }
    $contentNodeID = 2;
    if ( $http->hasPostVariable( 'ContentNodeID' ) )
        $contentNodeID = $http->postVariable( 'ContentNodeID' );
    $contentObjectID = 1;
    if ( $http->hasPostVariable( 'ContentObjectID' ) )
        $contentObjectID = $http->postVariable( 'ContentObjectID' );

    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray =& $http->postVariable( 'DeleteIDArray' );
        if ( is_array( $deleteIDArray ) && count( $deleteIDArray ) > 0 )
        {
            $http->setSessionVariable( 'CurrentViewMode', $viewMode );
            $http->setSessionVariable( 'ContentNodeID', $contentNodeID );
            $http->setSessionVariable( 'ContentObjectID', $contentObjectID );
            $http->setSessionVariable( 'DeleteIDArray', $deleteIDArray );
            $module->redirectTo( $module->functionURI( 'removeobject' ) . '/' );
        }
        else
        {
            $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $contentNodeID . '/' );
        }
    }
    else
    {
        $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $contentNodeID . '/' );
    }
}
else if ( $http->hasPostVariable( 'UpdatePriorityButton' ) )
{
    include_once( 'kernel/classes/ezcontentcache.php' );
    if ( $http->hasPostVariable( 'Priority' ) and $http->hasPostVariable( 'PriorityID' ) )
    {
        $db =& eZDB::instance();
        $priorityArray =& $http->postVariable( 'Priority' );
        $priorityIDArray =& $http->postVariable( 'PriorityID' );
        for ( $i=0; $i<count( $priorityArray );$i++ )
        {
            $priority = (int) $priorityArray[$i];
            $nodeID = $priorityIDArray[$i];
            $db->query( "UPDATE ezcontentobject_tree SET priority=$priority WHERE node_id=$nodeID" );
        }
    }

    if ( $http->hasPostVariable( 'ViewMode' ) )
    {
        $viewMode = $http->postVariable( 'ViewMode' );
    }
    else
    {
        $viewMode = 'full';
    }
    if ( $http->hasPostVariable( 'TopLevelNode' ) )
    {
        $topLevelNode = $http->postVariable( 'TopLevelNode' );
    }
    else
    {
        $topLevelNode = '2';
    }

    $clearNodeArray = array();
    if ( $http->hasPostVariable( 'ContentObjectID' ) )
    {
        $object =& eZContentObject::fetch( $http->postVariable( 'ContentObjectID' ) );
        $nodes =& $object->assignedNodes( false );
        foreach ( $nodes as $node )
        {
            $clearNodeArray[] = $node['main_node_id'];
        }
    }

    if ( eZContentCache::cleanup( $clearNodeArray ) )
    {
//                     eZDebug::writeDebug( 'cache cleaned up', 'content' );
    }

    $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $topLevelNode . '/' );
    return;
}
else if ( $http->hasPostVariable( "ActionAddToBookmarks" ) )
{
    $user =& eZUser::currentUser();
    $nodeID = false;
    if ( $http->hasPostVariable( 'ContentNodeID' ) )
    {
        $nodeID = $http->postVariable( 'ContentNodeID' );
        $node =& eZContentObjectTreeNode::fetch( $nodeID );
        $bookmark = eZContentBrowseBookmark::createNew( $user->id(), $nodeID, $node->attribute( 'name' ) );
    }
    if ( !$nodeID )
    {
        $contentINI =& eZINI::instance( 'content.ini' );
        $nodeID = $contentINI->variable( 'NodeSettings', 'RootNode' );
    }
    if ( $http->hasPostVariable( 'ViewMode' ) )
    {
        $viewMode = $http->postVariable( 'ViewMode' );
    }
    else
    {
        $viewMode = 'full';
    }
    $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $nodeID . '/' );
    return;
}
else if ( $http->hasPostVariable( "ContentObjectID" )  )
{
    $objectID = $http->postVariable( "ContentObjectID" );
    $action = $http->postVariable( "ContentObjectID" );


    // Check which action to perform
    if ( $http->hasPostVariable( "ActionAddToBasket" ) )
    {
        $shopModule =& eZModule::exists( "shop" );
        $result =& $shopModule->run( "basket", array() );
        $module->setExitStatus( $shopModule->exitStatus() );
        $module->setRedirectURI( $shopModule->redirectURI() );

    }
    else if ( $http->hasPostVariable( "ActionAddToWishList" ) )
    {
        $user =& eZUser::currentUser();
        if ( !$user->isLoggedIn() )
            return $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

        $shopModule =& eZModule::exists( "shop" );
        $result =& $shopModule->run( "wishlist", array() );
        $module->setExitStatus( $shopModule->exitStatus() );
        $module->setRedirectURI( $shopModule->redirectURI() );
    }
    else if ( $http->hasPostVariable( "ActionAddToNotification" ) )
    {
        include_once( 'kernel/classes/notification/handler/ezsubtree/ezsubtreenotificationrule.php' );
        $user =& eZUser::currentUser();

        $nodeID = $http->postVariable( 'ContentNodeID' );

        if ( $http->hasPostVariable( 'ViewMode' ) )
            $viewMode = $http->postVariable( 'ViewMode' );
        else
            $viewMode = 'full';

        if ( !$user->isLoggedIn() )
        {
            eZDebug::writeError( 'User not logged in trying to subscribe for notification, node ID: ' . $nodeID,
                                 'kernel/content/action.php' );
            $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $nodeID . '/' );
            return;
        }
        $contentNode = eZContentObjectTreeNode::fetch( $nodeID );
        if ( !$contentNode->attribute( 'can_read' ) )
        {
            eZDebug::writeError( 'User does not have access to subscribue for notification, node ID: ' . $nodeID . ', user ID: ' . $user->attribute( 'contentobject_id' ),
                                 'kernel/content/action.php' );
            $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $nodeID . '/' );
            return;
        }

        $nodeIDList =& eZSubtreeNotificationRule::fetchNodesForUserID( $user->attribute( 'contentobject_id' ), false );
        if ( !in_array( $nodeID, $nodeIDList ) )
        {
            $rule =& eZSubtreeNotificationRule::create( $nodeID, $user->attribute( 'contentobject_id' ) );
            $rule->store();
        }
        $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $nodeID . '/' );
        return;
    }
    else if ( $http->hasPostVariable( "ActionPreview" ) )
    {
        $user =& eZUser::currentUser();
        $objectID = $http->postVariable( 'ContentObjectID' );
        $object =& eZContentObject::fetch(  $objectID );
        $module->redirectTo( $module->functionURI( 'versionview' ) . '/' . $objectID . '/' . $object->attribute( 'current_version' ) . '/' );
        return;

    }
    else if ( $http->hasPostVariable( "ActionRemove" ) )
    {
        if ( $http->hasPostVariable( 'ViewMode' ) )
        {
            $viewMode = $http->postVariable( 'ViewMode' );
        }
        else
        {
            $viewMode = 'full';
        }
        $parentNodeID = 2;
        $contentNodeID = null;
        if ( $http->hasPostVariable( 'ContentNodeID' ) )
        {
            $contentNodeID = $http->postVariable( 'ContentNodeID' );
            $node =& eZContentObjectTreeNode::fetch( $contentNodeID );
            $parentNodeID =& $node->attribute( 'parent_node_id' );
        }
        $contentObjectID = 1;
        if ( $http->hasPostVariable( 'ContentObjectID' ) )
            $contentObjectID = $http->postVariable( 'ContentObjectID' );

        if ( $contentNodeID != null )
        {
            $http->setSessionVariable( 'CurrentViewMode', $viewMode );
            $http->setSessionVariable( 'ContentNodeID', $parentNodeID );
            $http->setSessionVariable( 'ContentObjectID', $contentObjectID );
            $http->setSessionVariable( 'DeleteIDArray', array( $contentNodeID ) );
            $module->redirectTo( $module->functionURI( 'removeobject' ) . '/' );
        }
    }
    else if ( $http->hasPostVariable( "ActionCollectInformation" ) )
    {
        $Result =& $module->run( "collectinformation", array() );
        return $Result;
    }
    else
    {
        include_once( 'lib/ezutils/classes/ezextension.php' );
        $baseDirectory = eZExtension::baseDirectory();
        $contentINI =& eZINI::instance( 'content.ini' );
        $extensionDirectories = $contentINI->variable( 'ActionSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/actions/content_actionhandler.php';
            if ( file_exists( $extensionPath ) )
            {
                include_once( $extensionPath );
                $actionFunction = $extensionDirectory . '_ContentActionHandler';
                if ( function_exists( $actionFunction ) )
                {
                    $actionResult = $actionFunction( $Module, $http, $objectID );
                    if ( $actionResult )
                        return $actionResult;
                }
            }
        }
        eZDebug::writeError( "Unknown content object action", "kernel/content/action.php" );
    }
}
else if ( $http->hasPostVariable( 'RedirectButton' ) )
{
    if ( $http->hasPostVariable( 'RedirectURI' ) )
    {
        $module->redirectTo( $http->postVariable( 'RedirectURI' ) );
        return;
    }
}
/*else if ( $http->hasPostVariable( 'RemoveObject' ) )
{
    $removeObjectID = $http->postVariable( 'RemoveObject' );
    if ( is_numeric( $removeObjectID ) )
    {
        $contentObject = eZContentObject::fetch( $removeObjectID );
        if ( $contentObject->attribute( 'can_remove' ) )
        {
            $contentObject->remove();
        }
    }
    $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $topLevelNode . '/' );
    return;
}*/


// return module contents
$Result = array();
$Result['content'] =& $result;

?>
