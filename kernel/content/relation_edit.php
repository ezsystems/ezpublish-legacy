<?php
//
// Created on: <17-Apr-2002 10:34:48 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezcontentbrowse.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

function checkRelationAssignments( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage )
{
    $http =& eZHTTPTool::instance();
    // Add object relations
//     if ( $module->isCurrentAction( 'AddRelatedObject' ) )
    if ( $module->isCurrentAction( 'AddRelatedObject' ) )
    {
        $selectedObjectIDArray = eZContentBrowse::result( 'AddRelatedObject' );
        $relatedObjects =& $object->relatedContentObjectArray( $editVersion );
        $relatedObjectIDArray = array();
        foreach (  $relatedObjects as  $relatedObject )
        {
            $relatedObjectID =  $relatedObject->attribute( 'id' );
            $relatedObjectIDArray[] =  $relatedObjectID;
        }
        foreach ( $selectedObjectIDArray as $objectID )
        {
            if ( !in_array( $objectID, $relatedObjectIDArray ) )
            {
                $object->addContentObjectRelation( $objectID, $editVersion );
            }
        }
    }
    if ( $module->isCurrentAction( 'UploadedFileRelation' ) )
    {
        include_once( 'kernel/classes/ezcontentupload.php' );
        $relatedObjectID = eZContentUpload::result( 'RelatedObjectUpload' );
        if ( $relatedObjectID )
        {
            $object->addContentObjectRelation( $relatedObjectID, $editVersion );
        }
        $module->redirectToView( 'edit', array( $object->attribute( 'id' ), $editVersion, $editLanguage ) );
        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
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
        eZContentBrowse::browse( array( 'action_name' => 'AddRelatedObject',
                                        'description_template' => 'design:content/browse_related.tpl',
                                        'content' => array( 'object_id' => $objectID,
                                                            'object_version' => $editVersion,
                                                            'object_language' => $editLanguage ),
                                        'keys' => array( 'class' => $class->attribute( 'id' ),
                                                         'class_id' => $class->attribute( 'identifier' ),
                                                         'classgroup' => $class->attribute( 'ingroup_id_list' ),
                                                         'section' => $object->attribute( 'section_id' ) ),
                                        'from_page' => $module->redirectionURI( 'content', 'edit', array( $objectID, $editVersion, $editLanguage ) ) ),
                                 $module );

        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }
    if ( $module->isCurrentAction( 'UploadFileRelation' ) )
    {
        $objectID = $object->attribute( 'id' );

        include_once( 'kernel/classes/ezsection.php' );
        $section =& eZSection::fetch( $object->attribute( 'section_id' ) );
        $navigationPart = false;
        if ( $section )
            $navigationPart = $section->attribute( 'navigation_part_identifier' );

        include_once( 'kernel/classes/ezcontentupload.php' );
        eZContentUpload::upload( array( 'action_name' => 'RelatedObjectUpload',
                                        'description_template' => 'design:content/upload_related.tpl',
                                        'navigation_part_identifier' => $navigationPart,
                                        'content' => array( 'object_id' => $objectID,
                                                            'object_version' => $editVersion,
                                                            'object_language' => $editLanguage ),
                                        'keys' => array( 'class' => $class->attribute( 'id' ),
                                                         'class_id' => $class->attribute( 'identifier' ),
                                                         'classgroup' => $class->attribute( 'ingroup_id_list' ),
                                                         'section' => $object->attribute( 'section_id' ) ),
                                        'result_action_name' => 'UploadedFileRelation',
                                        'result_module' => array( 'content', 'edit', array( $objectID, $editVersion, $editLanguage ) ) ),
                                 $module );
//                                        'result_uri' => $module->redirectionURI( 'content', 'edit', array( $objectID, $editVersion, $editLanguage ) ) ),

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
            include_once( 'kernel/classes/ezcontentobjectassignmenthandler.php' );
            $user =& eZUser::currentUser();
            $userID =& $user->attribute( 'contentobject_id' );
            $sectionID = 0; /* Will be changed later */
            $contentClassID = $http->postVariable( 'ClassID' );
            $class =& eZContentClass::fetch( $contentClassID );
            $relatedContentObject =& $class->instantiate( $userID, $sectionID );
            $newObjectID = $relatedContentObject->attribute( 'id' );
            $relatedContentVersion =& $relatedContentObject->attribute( 'current' );

            if ( $relatedContentObject->attribute( 'can_edit' ) )
            {
                $assignmentHandler = new eZContentObjectAssignmentHandler( $relatedContentObject, $relatedContentVersion );
                $sectionID = $assignmentHandler->setupAssignments( array( 'group-name' => 'RelationAssignmentSettings',
                                                                   'default-variable-name' => 'DefaultAssignment',
                                                                   'specific-variable-name' => 'ClassSpecificAssignment',
                                                                   'section-id-wanted' => true,
                                                                   'fallback-node-id' => $object->attribute( 'main_node_id' ) ) );

                $http->setSessionVariable( 'ParentObject', array( $object->attribute( 'id' ), $editVersion, $editLanguage ) );
                $http->setSessionVariable( 'NewObjectID', $newObjectID );

                /* Change session ID to the same one as the main node placement */
                $db =& eZDB::instance();
                $db->query("UPDATE ezcontentobject SET section_id = {$sectionID} WHERE id = {$newObjectID}");

                $module->redirectToView( 'edit', array( $relatedContentObject->attribute( 'id' ),
                                                        $relatedContentObject->attribute( 'current_version' ),
                                                        false ) );
            }
            else
            {
                $relatedContentObject->purge();
            }
            return;
        }
    }
}

function handleRelationTemplate( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage, &$tpl )
{
    $relatedObjects =& $object->relatedContentObjectArray( $editVersion );
    $tpl->setVariable( 'related_contentobjects', $relatedObjects );

    $ini =& eZINI::instance( 'content.ini' );

    $groups = $ini->variable( 'RelationGroupSettings', 'Groups' );
    $defaultGroup = $ini->variable( 'RelationGroupSettings', 'DefaultGroup' );

    $groupedRelatedObjects = array();
    $groupClassLists = array();
    $classGroupMap = array();
    foreach ( $groups as $groupName )
    {
        $groupedRelatedObjects[$groupName] = array();
        $setting = strtoupper( $groupName[0] ) . substr( $groupName, 1 ) . 'ClassList';
        $groupClassLists[$groupName] = $ini->variable( 'RelationGroupSettings', $setting );
        foreach ( $groupClassLists[$groupName] as $classIdentifier )
        {
            $classGroupMap[$classIdentifier] = $groupName;
        }
    }
    $groupedRelatedObjects[$defaultGroup] = array();

    foreach ( $relatedObjects as $relatedObjectKey => $relatedObject )
    {
        $classIdentifier = $relatedObject->attribute( 'class_identifier' );
        if ( isset( $classGroupMap[$classIdentifier] ) )
        {
            $groupName = $classGroupMap[$classIdentifier];
            $groupedRelatedObjects[$groupName][] =& $relatedObjects[$relatedObjectKey];
        }
        else
        {
            $groupedRelatedObjects[$defaultGroup][] =& $relatedObjects[$relatedObjectKey];
        }
    }
    $tpl->setVariable( 'related_contentobjects', $relatedObjects );
    $tpl->setVariable( 'grouped_related_contentobjects', $groupedRelatedObjects );
}

function initializeRelationEdit( &$module )
{
    $module->addHook( 'post_fetch', 'checkRelationAssignments' );
    $module->addHook( 'pre_commit', 'storeRelationAssignments' );
    $module->addHook( 'action_check', 'checkRelationActions' );
    $module->addHook( 'pre_template', 'handleRelationTemplate' );
}

?>
