<?php
//
// Created on: <04-Jul-2002 13:06:30 bf>
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
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( "lib/ezutils/classes/ezhttptool.php" );

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

if ( $http->hasPostVariable( 'NewButton' )  )
{
    if ( $http->hasPostVariable( 'ClassID' ) && $http->hasPostVariable( 'NodeID' ) )
    {
        $node =& eZContentObjectTreeNode::fetch( $http->postVariable( 'NodeID' ) );
        $parentContentObject =& $node->attribute( 'object' );

        if ( $parentContentObject->checkAccess( 'create', $http->postVariable( 'ClassID' ),  $parentContentObject->attribute( 'contentclass_id' ) ) == '1' )
        {
            $user =& eZUser::currentUser();
            $userID =& $user->attribute( 'contentobject_id' );
            $sectionID = $parentContentObject->attribute( 'section_id' );
            $contentClassID = $http->postVariable( 'ClassID' );
            $class =& eZContentClass::fetch( $contentClassID );
            $contentObject =& $class->instantiate( $userID, $sectionID );
            $nodeAssignment =& eZNodeAssignment::create( array(
                                                             'contentobject_id' => $contentObject->attribute( 'id' ),
                                                             'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                             'parent_node' => $node->attribute( 'node_id' ),
                                                             'main' => 1
                                                             )
                                                         );
            $nodeAssignment->store();

//            $contentObject =& eZContentObjectTreeNode::createObject( $http->postVariable( 'ClassID' ), $http->postVariable( 'NodeID' ) );

            $module->redirectTo( $module->functionURI( 'edit' ) . '/' . $contentObject->attribute( 'id' ) . '/' . $contentObject->attribute( 'current_version' ) );
            return;

        }
        else
        {
            $Module->redirectTo( '/error/403' );
            return;
        }

    }
}

if ( $http->hasPostVariable( 'EditButton' )  )
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

if ( $http->hasPostVariable( 'RemoveButton' ) )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray =& $http->postVariable( 'DeleteIDArray' );
        foreach ( $deleteIDArray as $deleteID )
        {
            $contentObject = eZContentObject::fetch( $deleteID );
            if ( $contentObject->attribute( 'can_remove' ) )
            {
                $contentObject->remove();
            }
        }
        unset( $contentObject );
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
    $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/' . $topLevelNode . '/' );
    return;
}

if ( $http->hasPostVariable( 'UpdatePriorityButton' ) )
{
    if ( $http->hasPostVariable( 'Priority' ) and $http->hasPostVariable( 'PriorityID' ) )
    {
        $db =& eZDB::instance();
        $priorityArray =& $http->postVariable( 'Priority' );
        $priorityIDArray =& $http->postVariable( 'PriorityID' );
        for ( $i=0; $i<count( $priorityArray );$i++ )
        {
            $priority = $priorityArray[$i];
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
    
    $module->redirectTo( $module->functionURI( 'view' ) . '/' . $viewMode . '/19/' );
    return;
}

if ( $http->hasPostVariable( 'RemoveObject' ) )
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
}

if ( $http->hasPostVariable( "ContentObjectID" )  )
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
        $shopModule =& eZModule::exists( "shop" );

        $result =& $shopModule->run( "wishlist", array() );
        $module->setExitStatus( $shopModule->exitStatus() );
        $module->setRedirectURI( $shopModule->redirectURI() );
    }
    else
    {
        eZDebug::writeError( "Unknown content object action", "kernel/content/action.php" );
    }
}

// return module contents
$Result = array();
$Result['content'] =& $result;

?>
