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
     Remove specified nodeassignment if \param parentNodeID and \param contentObjectID are given.
     \param parent node
     \param content object id
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

    function &fetchByID( $id ,$asObject = true )
    {
        $cond = array( 'id' => $id );
        return eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                null,
                                                $cond,
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
