<?php
//
// Created on: <17-Apr-2002 10:34:48 bf>
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

include_once( "kernel/classes/ezcontentclass.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );

include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezcontentobjectversion.php" );
include_once( "kernel/classes/ezcontentobjectattribute.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );

include_once( "kernel/classes/ezsearch.php" );

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

include_once( "kernel/common/template.php" );

$tpl =& templateInit();
$Module =& $Params["Module"];
$ObjectID =& $Params["ObjectID"];

if ( !is_numeric( $EditVersion ) )
{
    $object =& eZContentObject::fetch( $ObjectID );

    // Create a new
    $version =& $object->createNewVersion();

    $Module->redirectTo( $Module->functionURI( "edit" ) . "/" . $ObjectID . "/" . $version->attribute( "version" ) );
    return;
}

$object =& eZContentObject::fetch( $ObjectID );

if ( ! $object->attribute( 'can_edit' ) )
{
        $Module->redirectTo( '/error/error/403' );
        return;
}

$version =& $object->version( $EditVersion );
//$children = $object->children(  );

$classID = $object->attribute( "contentclass_id" );
$class =& eZContentClass::fetch( $classID );

$contentObjectAttributes =& $version->attributes();

$http =& eZHTTPTool::instance();

// Add object relations
if ( $http->hasPostVariable( "BrowseActionName" ) and
     $http->postVariable( "BrowseActionName" ) == "AddRelatedObject"
     )
{
    $selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );

    foreach ( $selectedObjectIDArray as $objectID )
    {
        $object->addContentObjectRelation( $objectID, $EditVersion );
    }
}

// Assign to nodes
if ( $http->hasPostVariable( "BrowseActionName" ) and
     $http->postVariable( "BrowseActionName" ) == "AddNodeAssignment"
     )
{
    $selectedNodeIDArray = $http->postVariable( "SelectedNodeIDArray" );

    foreach ( $selectedNodeIDArray as $nodeID )
    {
        $node = eZContentObjectTreeNode::fetch( $nodeID );
        $node->addChild( $ObjectID );
    }
}

//$selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );

$validation = array( "processed" => false,
                     "attributes" => array() );

$customAction = false;
$customActionAttributeID = null;
// Check for custom actions
if ( $http->hasPostVariable( "CustomActionButton" ) )
{
    $customActionArray = $http->postVariable( "CustomActionButton" );
    $customActionString = key( $customActionArray );

    $customActionAttributeID = preg_match( "#^([0-9]+)_(.*)$#", $customActionString, $matchArray );

    $customActionAttributeID = $matchArray[1];
    $customAction = $matchArray[2];
}

$storeActions = array( "PreviewButton",
                       "PermissionButton",
                       "TranslateButton",
                       "VersionsButton",
                       "PublishButton",
                       "StoreButton" );
$storingAllowed = false;
foreach ( $storeActions as $storeAction )
{
    if ( $http->hasPostVariable( $storeAction ) )
    {
        $storingAllowed = true;
        break;
    }
}

// These variables will be modified according to validation
$inputValidated = true;
$requireFixup = false;
if ( $storingAllowed || ( $customAction != false ) )
{
    $mainNodeID = $http->postVariable( "MainNodeID" );

    if ( $http->hasPostVariable( "NodesID" ) )
    {
        $nodesID = $http->postVariable( "NodesID" );
    }else
    {
        $nodesID = array();
    }

    // Validate input
    include_once( "lib/ezutils/classes/ezinputvalidator.php" );
    $unvalidatedAttributes = array();
    reset( $contentObjectAttributes );
    while( ( $key = key( $contentObjectAttributes ) ) !== null )
    {
        $contentObjectAttribute =& $contentObjectAttributes[$key];
        $status = $contentObjectAttribute->validateInput( $http, "ContentObjectAttribute" );
        if ( $status == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
            $requireFixup = true;
        else if ( $status == EZ_INPUT_VALIDATOR_STATE_INVALID )
        {
//            eZDebug::writeNotice( "Validating " . $contentObjectAttribute->attribute( "id" ) . " failed" );
            $inputValidated = false;
            $dataType =& $contentObjectAttribute->dataType();
            $attributeName = $dataType->attribute( "information" );
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
            $attributeName = $attributeName["name"];
            $unvalidatedAttributes[] = array( "id" => $contentObjectAttribute->attribute( "id" ),
                                              "identifier" => $contentClassAttribute->attribute( "identifier" ),
                                              "name" => $contentObjectAttribute->attribute( "validation_error" ) );
        }
        else
        {
//            eZDebug::writeNotice( "Validating " . $contentObjectAttribute->attribute( "id" ) . " success" );
        }
        next( $contentObjectAttributes );
    }

//    eZDebug::writeNotice( "Fixup is " . ( $requireFixup ? "" : "not " ) . "required" );

    // Fixup input
    if ( $requireFixup )
    {
        reset( $contentObjectAttributes );
        while( ( $key = key( $contentObjectAttributes ) ) !== null )
        {
            $contentObjectAttribute =& $contentObjectAttributes[$key];
            $contentObjectAttribute->fixupInput( $http, "ContentObjectAttribute" );
            next( $contentObjectAttributes );
        }
    }

//    eZDebug::writeNotice( "Input is " . ( $inputValidated ? "" : "not " ) . "validated" );

    reset( $contentObjectAttributes );
    while( ( $key = key( $contentObjectAttributes ) ) !== null )
    {
        $contentObjectAttribute =& $contentObjectAttributes[$key];
        $contentObjectAttribute->fetchInput( $http, "ContentObjectAttribute" );

        //        $contentObjectAttribute->setAttribute( "id", null );
        //        $contentObjectAttribute->setAttribute( "version", $editVersion );

        if ( $customActionAttributeID == $contentObjectAttribute->attribute( "id" ) )
        {
            $contentObjectAttribute->customHTTPAction( $http, $customAction );
        }

        next( $contentObjectAttributes );
    }

    if ( $inputValidated == true )
    {
        // increment the current version for the object
        // !! this function should be moved to a publish function to
        // !! work with workflows
        $currentVersion =& $object->attribute( "current_version" );

        $nodeID = eZContentObjectTreeNode::findNode( $mainNodeID, $object->attribute('id') );
        eZDebug::writeNotice( $nodeID, "nodeID" );
        $object->setAttribute( "main_node_id", $nodeID );
        $object->store();

        $version->setAttribute( "modified", mktime() );
        $version->store();

        // fetch the current version object
        reset( $contentObjectAttributes );
        while( ( $key = key( $contentObjectAttributes ) ) !== null )
        {
            $contentObjectAttribute =& $contentObjectAttributes[$key];
            $contentObjectAttribute->store();
            next( $contentObjectAttributes );
        }

        $objectName = $class->contentObjectName( $object );
        $object->setAttribute( "name", $objectName );

        $object->store();
    }
    else
    {

    }
    reset( $contentObjectAttributes );

//     $tpl->setVariable( "unvalidated_attributes", $unvalidatedAttributes );
    $validation["processed"] = true;
    $validation["attributes"] = $unvalidatedAttributes;
}

// After the object has been validated we can check for other actions
if ( $inputValidated == true )
{
    if ( $http->hasPostVariable( "PreviewButton" )  )
    {
        $Module->redirectTo( $Module->functionURI( "versionview" ) . "/" . $ObjectID . "/" .  $EditVersion . "/" );
        return;
    }

    if ( $http->hasPostVariable( "PermissionButton" )  )
    {
        $Module->redirectTo( $Module->functionURI( "permission" ) . "/" . $ObjectID );
        return;
    }

    if ( $http->hasPostVariable( "TranslateButton" ) )
    {
        $Module->redirectTo( $Module->functionURI( "translate" ) . "/" . $ObjectID . "/" . $EditVersion . "/" );
        return;
    }

    if ( $http->hasPostVariable( "VersionsButton" )  )
    {
        $Module->redirectTo( $Module->functionURI( "versions" ) . "/" . $ObjectID );
        return;
    }

    if ( $http->hasPostVariable( "BrowseObjectButton" )  )
    {
        $http->setSessionVariable( "BrowseFromPage", "/content/edit/$ObjectID/$EditVersion/" );
        $http->removeSessionVariable( "CustomBrowseActionAttributeID" );


        $http->setSessionVariable( "BrowseActionName", "AddRelatedObject" );
        $http->setSessionVariable( "BrowseReturnType", "ObjectID" );

        if ( $http->hasPostVariable( "CustomActionButton" ) )
        {
            $http->setSessionVariable( "CustomActionButton",  key( $http->postVariable( "CustomActionButton" ) ) );
            $http->setSessionVariable( "BrowseActionName", "CustomAction" );
        }

        $NodeID = 1;
        $Module->redirectTo( $Module->functionURI( "browse" ) . "/" . $NodeID . "/" . $ObjectID . "/" . $EditVersion );
        return;
    }

    if ( $http->hasPostVariable( "BrowseNodeButton" )  )
    {
        $http->setSessionVariable( "BrowseFromPage", "/content/edit/$ObjectID/$EditVersion/" );
        $http->setSessionVariable( "BrowseActionName", "AddNodeAssignment" );
        $http->setSessionVariable( "BrowseReturnType", "NodeID" );

        $node = eZContentObjectTreeNode::fetch( $object->attribute( "main_node_id" ) );
        $nodePath =  $node->attribute( 'path' );
        $rootNodeForObject = $nodePath[0];
        $NodeID = $rootNodeForObject->attribute( 'node_id' );
        $Module->redirectTo( $Module->functionURI( "browse" ) . "/" . $NodeID . "/" . $ObjectID . "/" . $EditVersion );
        return;
    }


    if ( $http->hasPostVariable( "PublishButton" ) )
    {
        $object->setAttribute( "current_version", $EditVersion );
        $object->store();

        // Register the object in the search engine.
        eZSearch::removeObject( $object );
        eZSearch::addObject( $object );
        eZDebug::writeNotice( $object, "object" );
        $Module->redirectTo( $Module->functionURI( "view" ) . "/full/" . $object->attribute( "main_node_id" ) . "/" );
        return;
    }

    if ( $http->hasPostVariable( "DeleteNodeButton" )  )
    {

        if ( $http->hasPostVariable( "DeleteParentIDArray" ) )
        {
            $nodesID = $http->postVariable( "DeleteParentIDArray" );
        }else
        {
            $nodesID = array();
        }
        $mainNodeID = $http->postVariable( "MainNodeID" );
        foreach ( $nodesID as $node )
        {
            if ( $node != $mainNodeID )
            {
                eZContentObjectTreeNode::deleteNodeWhereParent( $node, $ObjectID );
            }
        }

    }

}

$tpl->setVariable( "validation", $validation );

$Module->setTitle( "Edit " . $class->attribute( "name" ) );

//$nodes =& eZContentObjectTreeNode::fetchList( true, $object->attribute( 'id' ) );

$relatedObjects =& $object->relatedContentObjectArray( $EditVersion );


$assignedNodeArray =& $object->parentNodes( );

$mainParentNodeID = $object->attribute( 'main_parent_node_id' );

$res =& eZTemplateDesignResource::instance();

$res->setKeys( array( array( "object", $object->attribute( "id" ) ), // Object ID
                      array( "class", $class->attribute( "id" ) ), // Class ID
                      array( "section", 0 ) ) ); // Section ID, 0 so far

$tpl->setVariable( "edit_version", $EditVersion );
$tpl->setVariable( "http", $http );
$tpl->setVariable( "content_attributes", $contentObjectAttributes );
//$tpl->setVariable( "children", $children );
$tpl->setVariable( "class", $class );
$tpl->setVariable( "object", $object );
$tpl->setVariable( "assigned_node_array", $assignedNodeArray );
$tpl->setVariable( "related_contentobjects", $relatedObjects );

$tpl->setVariable( "main_node_id", $mainParentNodeID );

$Result =& $tpl->fetch( "design:content/edit.tpl" );

?>
