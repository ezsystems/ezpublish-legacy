<?php
//
// Definition of eZNodeAssignment class
//
// Created on: <02-ïËÔ-2002 15:58:10 sp>
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
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => 'ID',
                                         'contentobject_id' => 'ContentobjectID',
                                         'contentobject_version' => 'ContentObjectVersion',
                                         'parent_node' => 'ParentNode',
                                         'sort_field' => 'SortField',
                                         'sort_order' => 'SortOrder',
                                         'main' => 'Main',
                                         'from_node_id' => 'FromNodeID'
                                         ),
                      'keys' => array( 'id' ),
                      "function_attributes" => array( "parent_node_obj" => "getParentNode" ),

                      "increment_key" => "id",
                      'class_name' => 'eZNodeAssignment',
                      'name' => 'eznode_assignment' );
    }


    function &attribute( $attr )
    {
        if ( $attr == 'parent_node_obj' )
            return $this->getParentNode();
        else
            return eZPersistentObject::attribute( $attr );
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
        if ( !isset( $parameters['parent_node'] ) )
        {
            $parameters['parent_node'] = 2;
        }
        if ( !isset( $parameters['main'] ) )
        {
            $parameters['main'] = 0;
        }
        if ( !isset( $parameters['sort_field'] ) )
        {
            $parameters['sort_field'] = 1;
        }
        if ( !isset( $parameters['sort_order'] ) )
        {
            $parameters['sort_order'] = 1;
        }
        if ( !isset( $parameters['from_node_id'] ) )
        {
            $parameters['from_node_id'] = 0;
        }
        return new eZNodeAssignment( $parameters );
    }

    function &fetchForObject( $contentObjectID, $version = 1, $main = 0 ,$asObject = true )
    {
        $cond = array( 'contentobject_id' => $contentObjectID,
                       'contentobject_version' => $version );
        if( $main > 0 )
        {
            $cond['main'] = 1;
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

    function &clone( $nextVersionNumber = 1 )
    {
        return eZNodeAssignment::create( array( 'contentobject_id' => $this->attribute( 'contentobject_id' ),
                                                'contentobject_version' => $nextVersionNumber,
                                                'parent_node' => $this->attribute( 'parent_node' ),
                                                'sort_field' => $this->attribute( 'sort_field' ),
                                                'sort_order' => $this->attribute( 'sort_order' ),
                                                'main' => $this->attribute( 'main' )
                                                )
                                         );
    }

    function &getParentNode ( )
    {
        return eZContentObjectTreeNode::fetch( $this->attribute( 'parent_node' ) );
    }

    /// \privatesection
    var $ID;
    var $ContentobjectID;
    var $ContentObjectVersion;
    var $ParentNode;
    var $SortField;
    var $SortOrder;
    var $Main;
    var $FromNodeID;
}

?>
