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

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

function checkRelationAssignments( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion )
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

function storeRelationAssignments( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion )
{
}

function checkRelationActions( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion )
{
    $http =& eZHTTPTool::instance();
    if ( $module->isCurrentAction( 'BrowseForObjects' ) )
    {
        $objectID = $object->attribute( 'id' );
//         $http->setSessionVariable( 'BrowseFromPage', "/content/edit/$objectID/$editVersion/" );
        $http->setSessionVariable( 'BrowseFromPage', $module->redirectionURI( 'content', 'edit', array( $objectID, $editVersion ) ) );
        $http->removeSessionVariable( 'CustomBrowseActionAttributeID' );

        $http->setSessionVariable( 'BrowseActionName', 'AddRelatedObject' );
        $http->setSessionVariable( 'BrowseReturnType', 'ObjectID' );

        if ( $http->hasPostVariable( 'CustomActionButton' ) )
        {
            $http->setSessionVariable( 'CustomActionButton',  key( $http->postVariable( 'CustomActionButton' ) ) );
            $http->setSessionVariable( 'BrowseActionName', 'CustomAction' );
        }

        $nodeID = 2;
        $module->redirectToView( 'browse', array( $nodeID, $objectID, $editVersion ) );
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
}

function handleRelationTemplate( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, &$tpl )
{
    $relatedObjects =& $object->relatedContentObjectArray( $editVersion );
    if ( ( $relatedObjects == null ) and ( !$module->isCurrentAction( 'DeleteRelation' ) ) )
    {
        $relatedObjects =& $object->relatedContentObjectArray();
        foreach ( $relatedObjects as $relatedObject )
        {
            $objectID = $relatedObject->attribute( 'id' );
            $object->addContentObjectRelation( $objectID, $editVersion );
        }
    }
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
