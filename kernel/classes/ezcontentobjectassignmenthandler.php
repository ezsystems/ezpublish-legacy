<?php
//
// Definition of eZContentObjectAssignmentHandler class
//
// Created on: <06-Mar-2003 13:32:27 amos>
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

/*! \file ezcontentobjectassignmenthandler.php
*/

/*!
  \class eZContentObjectAssignmentHandler ezcontentobjectassignmenthandler.php
  \brief Handles default assignments for content objects

*/

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/eznodeassignment.php' );

class eZContentObjectAssignmentHandler
{
    /*!
     Constructor
    */
    function eZContentObjectAssignmentHandler( &$contentObject, &$contentVersion )
    {
        $this->CurrentObject =& $contentObject;
        $this->CurrentVersion =& $contentVersion;
    }

    function nodeIDList( $selectionText )
    {
        $nodeList = array();
        $items = explode( ',', trim( $selectionText ) );
        foreach ( $items as $item )
        {
            $item = trim( $item );
            if ( $item != '' )
            {
                $nodeID = $this->nodeID( $item );
                if ( $nodeID !== false )
                {
                    $nodeList[] = $nodeID;
                }
            }
        }
        return $nodeList;
    }

    function nodeID( $name )
    {
        if ( is_numeric( $name ) )
        {
            $nodeID = false;
            $node =& eZContentObjectTreeNode::fetch( $name );
            if ( $node )
                $nodeID = $node->attribute( 'node_id' );
            return $nodeID;
        }
        $contentINI =& eZINI::instance( 'content.ini' );
        switch ( $name )
        {
            case 'root':
            {
                return $contentINI->variable( 'NodeSettings', 'RootNode' );
            }
            case 'users':
            {
                return $contentINI->variable( 'NodeSettings', 'UserRootNode' );
            }
            case 'none':
            {
                return false;
            }
            default:
            {
                eZDebug::writeError( "Unknown node type '$name'", 'eZContentObjectAssignmentHandler::nodeID' );
            } break;
        }
        return false;
    }

    function setupAssignments( $parameters )
    {
        $parameters = array_merge( array( 'group-name' => false,
                                          'default-variable-name' => false,
                                          'specific-variable-name' => false,
                                          'fallback-node-id' => false ),
                                   $parameters );
        if ( !$parameters['group-name'] and
             !$parameters['default-variable-name'] and
             !$parameters['specific-variable-name'] )
             return false;
        $contentINI =& eZINI::instance( 'content.ini' );
        $defaultAssignment = $contentINI->variable( $parameters['group-name'], $parameters['default-variable-name'] );
        $specificAssignments = $contentINI->variable( $parameters['group-name'], $parameters['specific-variable-name'] );
        $hasAssignment = false;
        $assignments = false;
        $contentClass =& $this->CurrentObject->attribute( 'content_class' );
        $contentClassIdentifier = $contentClass->attribute( 'identifier' );
        $contentClassID = $contentClass->attribute( 'id' );
        foreach ( $specificAssignments as $specificAssignment )
        {
            $assignmentRules = explode( ';', $specificAssignment );
            $classMatches = $assignmentRules[0];
            $assignments = $assignmentRules[1];
            $mainID = false;
            if ( isset( $assignmentRules[2] ) )
                $mainID = $assignmentRules[2];
            $classMatchArray = explode( ',', $classMatches );
            foreach ( $classMatchArray as $classMatch )
            {
                $classMatch = trim( $classMatch );
                if ( preg_match( "#^group_([0-9]+)$#", $classMatch, $matches ) )
                {
                    $classGroupID = $matches[1];
                    if ( $contentClass->inGroup( $classGroupID ) )
                    {
                        $hasAssignment = true;
                        break;
                    }
                }
                else if ( $classMatch == $contentClassIdentifier )
                {
                    $hasAssignment = true;
                    break;
                }
                else if ( $classMatch == $contentClassID )
                {
                    $hasAssignment = true;
                    break;
                }
            }
            if ( $hasAssignment )
                break;
        }
        if ( !$hasAssignment )
        {
            $assignmentRules = explode( ';', $defaultAssignment );
            $assignments = $assignmentRules[0];
            $mainID = false;
            if ( isset( $assignmentRules[1] ) )
                $mainID = $assignmentRules[1];
        }
        eZDebug::writeDebug( $assignments, 'assignments' );
        if ( $assignments )
        {
            if ( $mainID )
                $mainID = $this->nodeID( $mainID );
            $nodeList = $this->nodeIDList( $assignments );
            eZDebug::writeDebug( $nodeList, 'nodeList' );
            $assignmentCount = 0;
            eZDebug::writeDebug( $this->CurrentObject->attribute( 'id' ), 'current object' );
            eZDebug::writeDebug( $this->CurrentVersion->attribute( 'version' ), 'current version' );
            foreach ( $nodeList as $nodeID )
            {
                $node =& eZContentObjectTreeNode::fetch( $nodeID );
                if ( !$node )
                    continue;
                $parentContentObject =& $node->attribute( 'object' );

                eZDebug::writeDebug( "Checking for '$nodeID'" );
                if ( $parentContentObject->checkAccess( 'create',
                                                        $contentClassID,
                                                        $parentContentObject->attribute( 'contentclass_id' ) ) == '1' )
                {
                    eZDebug::writeDebug( "Adding to '$nodeID' and main = '$mainID'" );
                    if ( $mainID === false )
                    {
                        $isMain = ( $assignmentCount == 0 );
                    }
                    else
                        $isMain = ( $mainID == $nodeID );
                    $nodeAssignment =& eZNodeAssignment::create( array( 'contentobject_id' => $this->CurrentObject->attribute( 'id' ),
                                                                        'contentobject_version' => $this->CurrentVersion->attribute( 'version' ),
                                                                        'parent_node' => $node->attribute( 'node_id' ),
                                                                        'is_main' => $isMain ) );
                    $nodeAssignment->store();
                    ++$assignmentCount;
                }
            }

            if ( $assignmentCount == 0 &&
                 $parameters['fallback-node-id'] )
            {
                $nodeAssignment =& eZNodeAssignment::create( array( 'contentobject_id' => $this->CurrentObject->attribute( 'id' ),
                                                                    'contentobject_version' => $this->CurrentVersion->attribute( 'version' ),
                                                                    'parent_node' => $parameters['fallback-node-id'],
                                                                    'is_main' => true ) );
                $nodeAssignment->store();
                ++$assignmentCount;
            }
        }
        return true;
    }

    /// \privatesection
    var $CurrentObject;
    var $ContentVersion;
}

?>
