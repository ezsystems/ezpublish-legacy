<?php
//
// Created on: <18-Aug-2006 12:46:05 vd>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

include_once('lib/ezutils/classes/ezdebug.php');
include_once('lib/ezutils/classes/ezhttptool.php');
include_once('lib/ezdb/classes/ezdb.php');

function sectionEditPostFetch( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage, &$validation )
{
    if ( $module->isCurrentAction( 'SectionEdit' ) )
    {
        $http =& eZHTTPTool::instance();

        $db =& eZDB::instance();
        $db->begin();

        $selectedSectionID = $http->hasPostVariable( 'SelectedSectionId' ) ? (int) $http->postVariable( 'SelectedSectionId' ) : 1;
        if ( !$selectedSectionID )
            $selectedSectionID = 1;

        $objectID = $object->attribute( 'id' );
        $treeNode =& eZContentObjectTreeNode::fetchByContentObjectID( $objectID );
        $assignedNodes =& $object->attribute( 'assigned_nodes' );
        foreach ( $assignedNodes as $node )
        {
            eZContentObjectTreeNode::assignSectionToSubTree( $node->attribute( 'node_id' ), $selectedSectionID );
        }
        // If there are no assigned nodes we should update db for the current object.
        if ( count( $assignedNodes ) == 0 )
        {
            $db->query( "UPDATE ezcontentobject SET section_id='$selectedSectionID' WHERE id = '$objectID'" );
            $db->query( "UPDATE ezsearch_object_word_link SET section_id='$selectedSectionID' WHERE  contentobject_id = '$objectID'" );
            // The current object has been newly created and has only one version,
            // we should not update section_id of current object by parentNodeSectionID in eZContentOperationCollection::updateSectionID()
            $http->setSessionVariable( 'ShouldNotUpdateSectionID', true );
        }
        $object->expireAllViewCache();
        $db->commit();

        $module->redirectToView( 'edit', array( $object->attribute( 'id' ), $editVersion, $editLanguage, $fromLanguage ) );
    }
}

function sectionEditPreCommit( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage )
{
}

function sectionEditActionCheck( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage )
{
}

function sectionEditPreTemplate( &$module, &$class, &$object, &$version, &$contentObjectAttributes, $editVersion, $editLanguage, &$tpl )
{
}

function initializeSectionEdit( &$module )
{
    $module->addHook( 'post_fetch', 'sectionEditPostFetch' );
    $module->addHook( 'pre_commit', 'sectionEditPreCommit' );
    $module->addHook( 'action_check', 'sectionEditActionCheck' );
    $module->addHook( 'pre_template', 'sectionEditPreTemplate' );
}

?>
