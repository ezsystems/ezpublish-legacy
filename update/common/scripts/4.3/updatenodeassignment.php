#!/usr/bin/env php
<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.3.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/**
 * Update script for bug 15478:Node assignment is not removed when removing node from child list
 * Cleaning up the unused data in eznode_assignment table whose relevant record in ezcontentobject_tree
 *  has been deleted when removing a node in the bug.
 *
 */

class updateNodeAssignment
{
    public static function execute( $exclusiveParentID = 1 )
    {
        $db = eZDB::instance();
        $cli = eZCLI::instance();
        //1. delete the assignments from eznode_assignment table

        // delete the assignments which don't have relevant entry in ezconentobject_tree
        // select the data that doesn't exist in either eznode_assignment or ezcontentobject_tree
        $deletedAssignmentList = $db->arrayQuery( "SELECT * FROM eznode_assignment WHERE id NOT IN " .
                                                 "(SELECT assign.id FROM eznode_assignment assign, ezcontentobject_tree tree WHERE " .
                                                "assign.contentobject_id = tree.contentobject_id AND assign.parent_node = tree.parent_node_id)" );

        $deletedCount = 0;
        foreach ( $deletedAssignmentList as $deletedAssignment )
        {
            // select the content object which is published.
            //If the object of the assignment is in trash or draft, it's not the one to be deleted
            $tempAssignID = $deletedAssignment["id"];
            $content = eZContentObject::fetch( $deletedAssignment["contentobject_id"], false );
            if( $content && $deletedAssignment['parent_node'] != $exclusiveParentID )
            {
                if ( $content["status"] == eZContentObject::STATUS_PUBLISHED )
                {
                    // iterate the data to be deleted, delete them
                    $cli->notice(
                        'Node assignment [ id: ' . $deletedAssignment['id'] . ' ] for contentobject [ id: ' .
                        $deletedAssignment['contentobject_id'] . ' ] is not consistent with entries in contentobject_tree' .
                        ' table thus will be removed.'
                    );
                    $sql = "DELETE FROM eznode_assignment WHERE id = " . $tempAssignID;
                    $result = $db->query( $sql );
                    if( $result === false )
                    {
                        $cli->notice( 'Node assignment [ id: ' . $deletedAssignment['id'] . ' ] ' .
                                      'could not be removed. Please restore your database from backup and try again.' );
                        return;
                    }
                    $deletedCount ++;
                }
            }
        }

        //2. Delete the duplicated entries which have same contentobject_id, contentobject_version and is_main
        // The process of deleting duplicated entries deletes the old entries, keeps the latest entry in the
        // duplicated entry list.
        $tempDeleteList = array();

        $duplicatedContentList = $db->arrayQuery( "SELECT contentobject_id, contentobject_version, is_main, parent_node
                                              FROM eznode_assignment
                                              GROUP BY contentobject_id, contentobject_version, is_main, parent_node
                                              HAVING COUNT(*) > 1" );
        foreach ( $duplicatedContentList as $duplicatedContent )
        {
            $assignmentList = $db->arrayQuery( "SELECT * FROM eznode_assignment".
                                               " WHERE contentobject_id = " . $duplicatedContent['contentobject_id'] .
                                               " AND contentobject_version = " . $duplicatedContent["contentobject_version"] .
                                               " AND parent_node =" . $duplicatedContent["parent_node"] .
                                               " ORDER BY id DESC" );
            $assignmentListCount = count( $assignmentList );
            //Find the duplicated entries( array index start from 1 ) and delete them. Leave the one entry( array index is 0 )
            for ( $i=1; $i < $assignmentListCount; $i++ )
            {
                if( $assignmentList[$i]["parent_node"] != $exclusiveParentID )
                {
                    $tempAssignID = $assignmentList[$i]["id"];
                    $cli->notice(
                        'Node assignment [ id: ' . $tempAssignID . ' ] for contentobject [ id: ' .
                        $assignmentList[$i]["contentobject_id"] . '] is duplicated thus will be removed.'
                    );
                    $sql = "DELETE FROM eznode_assignment WHERE id = " . $tempAssignID;
                    $result = $db->query( $sql );
                    if( $result === false )
                    {
                        $cli->notice( 'Node assignment [ id: ' . $tempAssignID . ' ] ' .
                                      'could not be removed. Please restore your database from backup and try again.' );
                        return;
                    }
                    $deletedCount ++;
                }
            }
        }

        if ( $deletedCount != 0 )
        {
            $cli->output( $deletedCount . ' node assignments have been deleted.' );
        }
        else
        {
            $cli->output( 'None of available node assignments has been deleted.' );
        }
    }
}

require 'autoload.php';
$script = eZScript::instance( array( 'description' => "eZ Publish node assignment update script. " .
                                           "This script will clean up unused node assignment entries because of bug 15478: " .
                                           "node assignment is not removed when removing node from child list",
                                    'use-session' => false,
                                    'use-modules' => false,
                                    'use-extensions' => true ) );
$script->startup();
$options = $script->getOptions( "", "", array( "-q" => "Quiet mode" ) );
$script->initialize();

// execlude node whose parent_node = 1
$cli = eZCLI::instance();
$cli->output( "Start." );
updateNodeAssignment::execute( 1 );
$cli->output( "Done." );
$script->shutdown();

?>
