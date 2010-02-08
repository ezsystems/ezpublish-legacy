<?php
//
// Created on: <18-Aug-2006 12:46:05 vd>
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

function sectionEditPostFetch( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage, &$validation )
{
}

function sectionEditPreCommit( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage )
{
}

function sectionEditActionCheck( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage )
{
    if ( $module->isCurrentAction( 'SectionEdit' ) )
    {
        $http = eZHTTPTool::instance();
        if ( $http->hasPostVariable( 'SelectedSectionId' ) )
        {
            $selectedSectionID = (int) $http->postVariable( 'SelectedSectionId' );
            $selectedSection = eZSection::fetch( $selectedSectionID );
            if ( is_object( $selectedSection ) )
            {
                $currentUser = eZUser::currentUser();
                if ( $currentUser->canAssignSectionToObject( $selectedSectionID, $object ) )
                {
                    $db = eZDB::instance();
                    $db->begin();
                    $assignedNodes = $object->attribute( 'assigned_nodes' );
                    if ( count( $assignedNodes ) > 0 )
                    {
                        foreach ( $assignedNodes as $node )
                        {
                            if ( eZOperationHandler::operationIsAvailable( 'content_updatesection' ) )
                            {
                                $operationResult = eZOperationHandler::execute( 'content',
                                                                                'updatesection',
                                                                                array( 'node_id'             => $node->attribute( 'node_id' ),
                                                                                       'selected_section_id' => $selectedSectionID ),
                                                                                null,
                                                                                true );

                            }
                            else
                            {
                                eZContentOperationCollection::updateSection( $node->attribute( 'node_id' ), $selectedSectionID );
                            }
                        }
                    }
                    else
                    {
                        // If there are no assigned nodes we should update db for the current object.
                        $objectID = $object->attribute( 'id' );
                        $db->query( "UPDATE ezcontentobject SET section_id='$selectedSectionID' WHERE id = '$objectID'" );
                        $db->query( "UPDATE ezsearch_object_word_link SET section_id='$selectedSectionID' WHERE  contentobject_id = '$objectID'" );
                    }
                    $object->expireAllViewCache();
                    $db->commit();
                }
                else
                {
                    eZDebug::writeError( "You do not have permissions to assign the section <" . $selectedSection->attribute( 'name' ) .
                                         "> to the object <" . $object->attribute( 'name' ) . ">." );
                }
                $module->redirectToView( 'edit', array( $object->attribute( 'id' ), $editVersion, $editLanguage, $fromLanguage ) );
            }
        }
    }
}

function sectionEditPreTemplate( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $tpl )
{
}

function initializeSectionEdit( $module )
{
    $module->addHook( 'post_fetch', 'sectionEditPostFetch' );
    $module->addHook( 'pre_commit', 'sectionEditPreCommit' );
    $module->addHook( 'action_check', 'sectionEditActionCheck' );
    $module->addHook( 'pre_template', 'sectionEditPreTemplate' );
}

?>
