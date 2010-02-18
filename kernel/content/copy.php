<?php
//
// Created on: <17-Jan-2003 12:47:11 amos>
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

$Module = $Params['Module'];
$ObjectID = $Params['ObjectID'];

$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( 'BrowseCancelButton' ) )
{
    if ( $http->hasPostVariable( 'BrowseCancelURI' ) )
    {
        return $Module->redirectTo( $http->postVariable( 'BrowseCancelURI' ) );
    }
}

if ( $ObjectID === null )
{
    // ObjectID is returned after browsing
    $ObjectID = $http->postVariable( 'ObjectID' );
}

$object = eZContentObject::fetch( $ObjectID );

if ( $object === null )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$object->attribute( 'can_read' ) )
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $mainParentNodeID = $object->attribute( 'main_parent_node_id' );
    return $Module->redirectToView( 'view', array( 'full', $mainParentNodeID ) );
}

$contentINI = eZINI::instance( 'content.ini' );

/*!
 Copy the specified object to a given node
*/
function copyObject( $Module, $object, $allVersions, $newParentNodeID )
{
    if ( !$newParentNodeID )
        return $Module->redirectToView( 'view', array( 'full', 2 ) );

    // check if we can create node under the specified parent node
    if( ( $newParentNode = eZContentObjectTreeNode::fetch( $newParentNodeID ) ) === null )
        return $Module->redirectToView( 'view', array( 'full', 2 ) );

    $classID = $object->attribute('contentclass_id');

    if ( !$newParentNode->checkAccess( 'create', $classID ) )
    {
        $objectID = $object->attribute( 'id' );
        eZDebug::writeError( "Cannot copy object $objectID to node $newParentNodeID, " .
                               "the current user does not have create permission for class ID $classID",
                             'content/copy' );
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }

    $db = eZDB::instance();
    $db->begin();
    $newObject = $object->copy( $allVersions );
    // We should reset section that will be updated in updateSectionID().
    // If sectionID is 0 then the object has been newly created
    $newObject->setAttribute( 'section_id', 0 );
    $newObject->store();

    $curVersion        = $newObject->attribute( 'current_version' );
    $curVersionObject  = $newObject->attribute( 'current' );
    $newObjAssignments = $curVersionObject->attribute( 'node_assignments' );
    unset( $curVersionObject );

    // remove old node assignments
    foreach( $newObjAssignments as $assignment )
    {
        $assignment->purge();
    }

    // and create a new one
    $nodeAssignment = eZNodeAssignment::create( array(
                                                     'contentobject_id' => $newObject->attribute( 'id' ),
                                                     'contentobject_version' => $curVersion,
                                                     'parent_node' => $newParentNodeID,
                                                     'is_main' => 1
                                                     ) );
    $nodeAssignment->store();

    // publish the newly created object
    eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newObject->attribute( 'id' ),
                                                              'version'   => $curVersion ) );
    // Update "is_invisible" attribute for the newly created node.
    $newNode = $newObject->attribute( 'main_node' );
    eZContentObjectTreeNode::updateNodeVisibility( $newNode, $newParentNode );

    $db->commit();
    return $Module->redirectToView( 'view', array( 'full', $newParentNodeID ) );
}

/*!
Browse for node to place the object copy into
*/
function browse( $Module, $object )
{
    if ( $Module->hasActionParameter( 'LanguageCode' ) )
        $languageCode = $Module->actionParameter( 'LanguageCode' );
    else
    {
        $languageCode = false;
    }

    $objectID = $object->attribute( 'id' );
    $node     = $object->attribute( 'main_node' );
    $class    = $object->contentClass();

    $ignoreNodesSelect = array();
    $ignoreNodesClick = array();
    foreach ( $object->assignedNodes( false ) as $element )
    {
        $ignoreNodesSelect[] = $element['node_id'];
        $ignoreNodesClick[]  = $element['node_id'];
    }
    $ignoreNodesSelect = array_unique( $ignoreNodesSelect );
    $ignoreNodesClick = array_unique( $ignoreNodesClick );

    $viewMode = 'full';
    if ( $Module->hasActionParameter( 'ViewMode' ) )
        $viewMode = $module->actionParameter( 'ViewMode' );


    $sourceParentNodeID = $node->attribute( 'parent_node_id' );
    eZContentBrowse::browse( array( 'action_name' => 'CopyNode',
                                    'description_template' => 'design:content/browse_copy_node.tpl',
                                    'keys' => array( 'class' => $class->attribute( 'id' ),
                                                     'class_id' => $class->attribute( 'identifier' ),
                                                     'classgroup' => $class->attribute( 'ingroup_id_list' ),
                                                     'section' => $object->attribute( 'section_id' ) ),
                                    'ignore_nodes_select' => $ignoreNodesSelect,
                                    'ignore_nodes_click'  => $ignoreNodesClick,
                                    'persistent_data' => array( 'ObjectID' => $objectID ),
                                    'permission' => array( 'access' => 'create', 'contentclass_id' => $class->attribute( 'id' ) ),
                                    'content' => array( 'object_id' => $objectID,
                                                        'object_version' => $object->attribute( 'current_version' ),
                                                        'object_language' => $languageCode ),
                                    'start_node' => $sourceParentNodeID,
                                    'cancel_page' => $Module->redirectionURIForModule( $Module, 'view',
                                                                                       array( $viewMode, $sourceParentNodeID, $languageCode ) ),
                                    'from_page' => "/content/copy" ),
                             $Module );
}

/*!
Redirect to the page that lets a user to choose which versions to copy:
either all version or the current one.
*/
function chooseObjectVersionsToCopy( $Module, &$Result, $object )
{
        $selectedNodeIDArray = eZContentBrowse::result( $Module->currentAction() );
        require_once( 'kernel/common/template.php' );
        $tpl = templateInit();
        $tpl->setVariable( 'object', $object );
        $tpl->setVariable( 'selected_node_id', $selectedNodeIDArray[0] );
        $Result['content'] = $tpl->fetch( 'design:content/copy.tpl' );
        $Result['path'] = array( array( 'url' => false,
                                        'text' => ezpI18n::translate( 'kernel/content', 'Content' ) ),
                                 array( 'url' => false,
                                        'text' => ezpI18n::translate( 'kernel/content', 'Copy' ) ) );
}

/*
 Object copying logic in pseudo-code:

 $targetNodeID = browse();
 $versionsToCopy = fetchObjectVersionsToCopyFromContentINI();
 if ( $versionsToCopy != 'user-defined' )
    $versionsToCopy = askUserAboutVersionsToCopy();
 copyObject( $object, $versionsToCopy, $targeNodeID );

 Action parameters:

 1. initially:                                   null
 2. when user has selected the target node:     'CopyNode'
 3. when/if user has selected versions to copy: 'Copy' or 'Cancel'
*/

$versionHandling = $contentINI->variable( 'CopySettings', 'VersionHandling' );
$chooseVersions = ( $versionHandling == 'user-defined' );
if( $chooseVersions )
    $allVersions = ( $Module->actionParameter( 'VersionChoice' ) == 1 ) ? true : false;
else
    $allVersions = ( $versionHandling == 'last-published' ) ? false : true;

if ( $Module->isCurrentAction( 'Copy' ) )
{
    // actually do copying after a user has selected object versions to copy
    $newParentNodeID = $http->postVariable( 'SelectedNodeID' );
    return copyObject( $Module, $object, $allVersions, $newParentNodeID );
}
else if ( $Module->isCurrentAction( 'CopyNode' ) )
{
    // we get here after a user selects target node to place the source object under
    if( $chooseVersions )
    {
        // redirect to the page with choice of versions to copy
        $Result = array();
        chooseObjectVersionsToCopy( $Module, $Result, $object );
    }
    else
    {
        // actually do copying of the pre-configured object version(s)
        $selectedNodeIDArray = eZContentBrowse::result( $Module->currentAction() );
        $newParentNodeID = $selectedNodeIDArray[0];
        return copyObject( $Module, $object, $allVersions, $newParentNodeID );
    }
}
else // default, initial action
{
    /*
    Browse for target node.
    We get here when a user clicks "copy" button when viewing some node.
    */
    browse( $Module, $object );
}

?>
