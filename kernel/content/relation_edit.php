<?php
//
// Created on: <17-Apr-2002 10:34:48 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

function checkRelationAssignments( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage )
{
    $http =& eZHTTPTool::instance();
    // Add object relations
    if ( $module->isCurrentAction( 'AddRelatedObject' ) )
    {
        $selectedObjectIDArray = $http->postVariable( 'SelectedObjectIDArray' );

        $relatedObjects =& $object->relatedContentObjectArray( $editVersion );
        $relatedObjectIDArray = array();
        foreach (  $relatedObjects as  $relatedObject )
        {
            $relatedObjectID =  $relatedObject->attribute( 'id' );
            $relatedObjectIDArray[] =  $relatedObjectID;
        }
        foreach ( $selectedObjectIDArray as $objectID )
        {
            if ( !in_array(  $objectID, $relatedObjectIDArray ) )
            {
                $object->addContentObjectRelation( $objectID, $editVersion );
            }
        }
    }
}

function storeRelationAssignments( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage )
{
}

function checkRelationActions( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage )
{
    $http =& eZHTTPTool::instance();
    if ( $module->isCurrentAction( 'BrowseForObjects' ) )
    {
        $objectID = $object->attribute( 'id' );
//         $http->setSessionVariable( 'BrowseFromPage', "/content/edit/$objectID/$editVersion/" );
        $http->setSessionVariable( 'BrowseFromPage', $module->redirectionURI( 'content', 'edit', array( $objectID, $editVersion, $editLanguage ) ) );
        $http->removeSessionVariable( 'CustomBrowseActionAttributeID' );

        $http->setSessionVariable( 'BrowseActionName', 'AddRelatedObject' );
        $http->setSessionVariable( 'BrowseReturnType', 'ObjectID' );

        if ( $http->hasPostVariable( 'CustomActionButton' ) )
        {
            $http->setSessionVariable( 'CustomActionButton',  key( $http->postVariable( 'CustomActionButton' ) ) );
            $http->setSessionVariable( 'BrowseActionName', 'CustomAction' );
        }

        $nodeID = 2;
        $module->redirectToView( 'browse', array( $nodeID ) );
        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }
    if ( $module->isCurrentAction( 'DeleteRelation' ) )
    {
        $objectID = $object->attribute( 'id' );
        if ( $http->hasPostVariable( 'DeleteRelationIDArray' ) )
        {
            $relationObjectIDs = $http->postVariable( 'DeleteRelationIDArray' );
        }
        foreach ( $relationObjectIDs as $relationObjectID )
        {
            $object->removeContentObjectRelation( $relationObjectID, $editVersion );
        }

    }
    if ( $module->isCurrentAction( 'NewObject' ) )
    {
        if ( $http->hasPostVariable( 'ClassID' ) )
        {
            $ini =& eZINI::instance();
            $nodeID = $ini->variable( "ContentSettings", "SurplusNode" );
            if ( ( $nodeID <= 0 ) )
            {
                eZDebug::writeNotice( "SurplusNode variable is not found in ContentSetting. Falling back on root folder" );
                $nodeID = 2;
            }
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
            if ( $node == null )
            {
                eZDebug::writeNotice( "SurplusNode variable is not refering to a valid node. Falling back on root folder" );
                $nodeID = 2;
                $node =& eZContentObjectTreeNode::fetch( $nodeID );
            }
            if ( $node != null )
            {
                $parentContentObject =& $node->attribute( 'object' );

                if ( $parentContentObject->checkAccess( 'create', $http->postVariable( 'ClassID' ), $parentContentObject->attribute( 'contentclass_id' ) ) == '1' )
                {
                    $user =& eZUser::currentUser();
                    $userID =& $user->attribute( 'contentobject_id' );
                    $sectionID = $parentContentObject->attribute( 'section_id' );
                    $contentClassID = $http->postVariable( 'ClassID' );
                    $class =& eZContentClass::fetch( $contentClassID );
                    $editVersion = $object->attribute( 'current_version' );
                    $language = $object->attribute( 'current_language' );
                    $parentObjectID = $object->attribute( 'id' );

                    $contentObject =& $class->instantiate( $userID, $sectionID );
                    $nodeAssignment =& eZNodeAssignment::create( array(
                                                                     'contentobject_id' => $contentObject->attribute( 'id' ),
                                                                     'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                                     'parent_node' => $node->attribute( 'node_id' ),
                                                                     'is_main' => 1
                                                                     )
                                                                 );
                    $nodeAssignment->store();

                    $http->setSessionVariable( 'ParentObject', array( $parentObjectID, $editVersion, $language ) );
                    $http->setSessionVariable( 'NewObjectID', $contentObject->attribute( 'id' ) );
                    $module->redirectTo( $module->functionURI( 'edit' ) . '/' . $contentObject->attribute( 'id' ) );
                    return;
                }
            }
            else
            {
                $module->redirectTo( '/error/403' );
                return;
            }
        }
    }
}

function handleRelationTemplate( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage, &$tpl )
{
    $relatedObjects =& $object->relatedContentObjectArray( $editVersion );
    $tpl->setVariable( 'related_contentobjects', $relatedObjects );
}

function initializeRelationEdit( &$module )
{
    $module->addHook( 'post_fetch', 'checkRelationAssignments' );
    $module->addHook( 'pre_commit', 'storeRelationAssignments' );
    $module->addHook( 'action_check', 'checkRelationActions' );
    $module->addHook( 'pre_template', 'handleRelationTemplate' );
}

?>
