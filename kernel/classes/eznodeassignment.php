<?php
//
// Definition of eZNodeAssignment class
//
// Created on: <02-ïËÔ-2002 15:58:10 sp>
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

/*! \file eznodeassignment.php
*/

/*!
  \class eZNodeAssignment eznodeassignment.php
  \brief The class eZNodeAssignment does

*/
include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "kernel/classes/ezpersistentobject.php" );

class eZNodeAssignment extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZNodeAssignment( $row )
    {
        $this->TempNode = null;
        $this->Name = false;
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'remote_id' => array( 'name' => 'RemoteID',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'contentobject_id' => array( 'name' => 'ContentobjectID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         'contentobject_version' => array( 'name' => 'ContentObjectVersion',
                                                                           'datatype' => 'integer',
                                                                           'default' => 0,
                                                                           'required' => true ),
                                         'parent_node' => array( 'name' => 'ParentNode',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'sort_field' => array( 'name' => 'SortField',
                                                                'datatype' => 'integer',
                                                                'default' => 1,
                                                                'required' => true ),
                                         'sort_order' => array( 'name' => 'SortOrder',
                                                                'datatype' => 'integer',
                                                                'default' => 1,
                                                                'required' => true ),
                                         'is_main' => array( 'name' => 'Main',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'from_node_id' => array( 'name' => 'FromNodeID',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         'parent_remote_id' => array( 'name' => 'ParentRemoteID',
                                                                    'datatype' => 'string',
                                                                    'default' => '',
                                                                    'required' => false ) ),
                      'keys' => array( 'id' ),
                      "function_attributes" => array( "parent_node_obj" => "getParentNode",
                                                      "parent_contentobject" => "getParentObject",
                                                      "node" => "fetchNode",
                                                      'temp_node' => 'tempNode' ),

                      "increment_key" => "id",
                      'class_name' => 'eZNodeAssignment',
                      'name' => 'eznode_assignment' );
    }


    function &attribute( $attr )
    {
        if ( $attr == 'parent_node_obj' )
            return $this->getParentNode();
        else if ( $attr == 'temp_node' )
            return $this->tempNode();
        else
            return eZPersistentObject::attribute( $attr );
    }

    function &tempNode()
    {
        if ( $this->TempNode !== null )
            return $this->TempNode;
        $this->TempNode = eZContentObjectTreeNode::create( $this->attribute( 'parent_node' ),
                                                           $this->attribute( 'contentobject_id' ),
                                                           $this->attribute( 'contentobject_version' ),
                                                           $this->attribute( 'sort_field' ),
                                                           $this->attribute( 'sort_order' ) );
        $this->TempNode->setName( $this->Name );
        return $this->TempNode;
    }

    function setName( $name )
    {
        return $this->Name = $name;
    }

    function name()
    {
        return $this->Name;
    }

    function &create( $parameters = array() )
    {
        if ( !isset( $parameters['contentobject_id'] ) )
        {
            eZDebug::writeError( $parameters, "Cannot create node assignment without contentobject_id" );
            return null;
        }
        if ( !isset( $parameters['contentobject_version'] ) )
        {
            $parameters['contentobject_version'] = 1;
        }
        if ( !isset( $parameters['remote_id'] ) )
        {
            $parameters['remote_id'] = 0;
        }
        if ( !isset( $parameters['parent_node'] ) )
        {
            $parameters['parent_node'] = 2;
        }
        if ( !isset( $parameters['is_main'] ) )
        {
            $parameters['is_main'] = 0;
        }
        if ( !isset( $parameters['sort_field'] ) )
        {
            $parameters['sort_field'] = 2; // Published
        }
        if ( !isset( $parameters['sort_order'] ) )
        {
            $parameters['sort_order'] = 0; // Desc
        }
        if ( !isset( $parameters['from_node_id'] ) )
        {
            $parameters['from_node_id'] = 0;
        }
        if ( !isset( $parameters['parent_remote_id'] ) )
        {
            $parameters['parent_remote_id'] = '';
        }
        return new eZNodeAssignment( $parameters );
    }

    /*!
     Remove specified nodeassignment if \a parentNodeID and \a contentObjectID are given,
     if they are \c false it will remove the current node assignment.

     \param $parentNodeID The ID of the parent node
     \param $contentObjectID The ID of the object
     */
    function remove( $parentNodeID = false, $contentObjectID = false )
    {
        $db =& eZDB::instance();
        if ( $parentNodeID == false and $contentObjectID == false )
        {
            $nodeAssignment =& $this;
            $nodeAssignmentID = $nodeAssignment->attribute( 'id' );
            $sqlQuery = "DELETE FROM eznode_assignment WHERE id='$nodeAssignmentID'";
            $db->query( $sqlQuery );
        }
        else
        {
            $sqlQuery = "DELETE FROM eznode_assignment WHERE parent_node='$parentNodeID' AND contentobject_id='$contentObjectID'";
            $db->query( $sqlQuery );
        }
    }

    /*!
     \static
     Removes the node assignment with the ID \a $assignmentID.

     \param $assignmentID Either an ID or an array with IDs.
     \return \c true if it were able to remove the assignments, \c false if something failed.
     \note If \a $assignmentID is an empty array it immediately returns \c false.
    */
    function removeByID( $assignmentID )
    {
        $db =& eZDB::instance();
        if ( is_array( $assignmentID ) )
        {
            if ( count( $assignmentID ) == 0 )
                return false;
            $sql = "DELETE FROM eznode_assignment WHERE id IN ( ";
            $i = 0;
            foreach ( $assignmentID as $id )
            {
                if ( $i > 0 )
                    $sql .= ", ";
                $sql .= (int)$id;
                ++$i;
            }
            $sql .= ' )';
        }
        else
        {
            $sql = "DELETE FROM eznode_assignment WHERE id=" . (int)$assignmentID;
        }
        $db->query( $sql );
        return true;
    }

    function &fetchForObject( $contentObjectID, $version = 1, $main = 0, $asObject = true )
    {
        $cond = array( 'contentobject_id' => $contentObjectID,
                       'contentobject_version' => $version );
        if( $main > 0 )
        {
            $cond['is_main'] = 1;
        }
        return eZPersistentObject::fetchObjectList( eZNodeAssignment::definition(),
                                                    null,
                                                    $cond,
                                                    $asObject );
    }

    function &fetch( $contentObjectID, $version = 1, $parentNode = 0 ,$asObject = true )
    {
        $cond = array( 'contentobject_id' => $contentObjectID,
                       'contentobject_version' => $version,
                       'parent_node' => $parentNode );
        return eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                null,
                                                $cond,
                                                $asObject );

    }

    /*!
     Finds the node for the current assignemnt if it exists and returns it.
     \return An eZContentObjectTreeNode object or \c null if no node was found.
      \sa eZContentObjectTreeNode::fetchNode
    */
    function &fetchNode()
    {
        return eZContentObjectTreeNode::fetchNode( $this->ContentobjectID, $this->ParentNode );
    }

    /*!
     Fetches the node assignment which has id \a $id and returns it.
     \sa fetchListByID
    */
    function &fetchByID( $id ,$asObject = true )
    {
        $cond = array( 'id' => $id );
        return eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                null, $cond,
                                                $asObject );
    }

    /*!
     Fetches all node assignments which is mentioned in array \a $ID and returns it.
     \sa fetchByID
    */
    function &fetchListByID( $idList ,$asObject = true )
    {
        $cond = array( 'id' => array( $idList ) );
        return eZPersistentObject::fetchObjectList( eZNodeAssignment::definition(),
                                                    null, $cond, null, null,
                                                    $asObject );
    }

    function &clone( $nextVersionNumber = 1, $contentObjectID = false )
    {
        $assignmentRow = array( 'contentobject_id' => $this->attribute( 'contentobject_id' ),
                                'contentobject_version' => $nextVersionNumber,
                                'remote_id' => $this->attribute( 'remote_id' ),
                                'parent_node' => $this->attribute( 'parent_node' ),
                                'sort_field' => $this->attribute( 'sort_field' ),
                                'sort_order' => $this->attribute( 'sort_order' ),
                                'is_main' => $this->attribute( 'is_main' ),
                                'parent_remote_id' => $this->attribute( 'parent_remote_id' ) );
        if ( $contentObjectID !== false )
            $assignmentRow['contentobject_id'] = $contentObjectID;
        return eZNodeAssignment::create( $assignmentRow );
    }

    function &getParentNode()
    {
        return eZContentObjectTreeNode::fetch( $this->attribute( 'parent_node' ) );
    }

    /*!
     \return The contentobject which the parent node points to.
    */
    function &getParentObject( )
    {
        return eZContentObject::fetchByNodeID( $this->attribute( 'parent_node' ) );
    }

    /*!
    \static
    Chooses and sets new main assignment for the specified object, in case if there's main assignment already.
    \return false if there is already main assignment, true on success.
    */
    function setNewMainAssignment( $objectID, $version )
    {

        $assignments =& eZNodeAssignment::fetchForObject( $objectID, $version );

        if ( count( $assignments ) == 0 )
            return true;

        // check: if there is already main assignment for the object then we should do nothing
        foreach ( $assignments as $key => $assignment )
        {
            if ( $assignment->attribute( 'is_main' ) )
                return false;
        }

        // choose first assignment as new first assignment
        $newMainAssignment =& $assignments[0];
        $parentMainNodeID = $newMainAssignment->attribute( 'parent_node' );

        $db =& eZDB::instance();
        $db->query( "UPDATE eznode_assignment SET is_main=1 WHERE contentobject_id=$objectID AND contentobject_version=$version AND parent_node=$parentMainNodeID" );
        $db->query( "UPDATE eznode_assignment SET is_main=0 WHERE contentobject_id=$objectID AND contentobject_version=$version AND parent_node<>$parentMainNodeID" );

        return true;
    }

    /// \privatesection
    var $ID;
    /// Used for giving unique values to an assignment which can later be checked.
    /// This is often used in templates to provide limited choices for assignments.
    var $RemoteID;
    var $ParentRemoteID;
    var $ContentobjectID;
    var $ContentObjectVersion;
    var $ParentNode;
    var $SortField;
    var $SortOrder;
    var $Main;
    var $FromNodeID;
}

?>
