<?php
//
// Definition of eZContentOperationCollection class
//
// Created on: <01-Nov-2002 13:51:17 amos>
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezcontentoperationcollection.php
*/

/*!
  \class eZContentOperationCollection ezcontentoperationcollection.php
  \brief The class eZContentOperationCollection does

*/

class eZContentOperationCollection
{
    /*!
     Constructor
    */
    function eZContentOperationCollection()
    {
    }

    function readNode( $nodeID )
    {

    }

    function readObject( $nodeID, $languageCode )
    {
        $node =& eZContentObjectTreeNode::fetch( $nodeID );

        if ( $node === null )
//            return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
            return false;


        $object = $node->attribute( 'object' );

        if ( $object === null )
//            return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
        {
            return false;
        }
/*
        if ( !$object->attribute( 'can_read' ) )
        {
//            return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
            return false;
        }
*/
        if ( $languageCode != '' )
        {
            $object->setCurrentLanguage( $languageCode );
        }
        return array( 'status' => true, 'object' => $object, 'node' => $node );
    }

    function loopNodes( $nodeID )
    {
        return array( 'parameters' => array( array( 'parent_node_id' => 3 ),
                                             array( 'parent_node_id' => 5 ),
                                             array( 'parent_node_id' => 12 ) ) );
    }

    function loopNodeAssignment( $objectID, $versionNum )
    {
        $object =& eZContentObject::fetch( $objectID );
        $version =& $object->version( $versionNum );
        $nodeAssignmentList =& $version->attribute( 'node_assignments' );
//        var_dump( $nodeAssignmentList );

        $parameters = array();
        foreach ( array_keys( $nodeAssignmentList ) as $key )
        {
            $nodeAssignment =& $nodeAssignmentList[$key];
            if ( $nodeAssignment->attribute( 'parent_node' ) > 0 )
                $parameters[] = array( 'parent_node_id' => $nodeAssignment->attribute( 'parent_node' ) );
        }

        return array( 'parameters' => $parameters );
    }

    function setVersionStatus( $objectID, $versionNum, $status )
    {
        $object =& eZContentObject::fetch( $objectID );
        if ( !$versionNum )
        {
            $versionNum = $object->attribute( 'current_version' );
        }
        $version =& $object->version( $versionNum );
        if ( $version === null )
            return;
        switch ( $status )
        {
            case 1:
            {
                $statusName = 'pending';
                $version->setAttribute( 'status', EZ_VERSION_STATUS_PENDING );
            } break;
            case 2:
            {
                $statusName = 'archived';
                $version->setAttribute( 'status', EZ_VERSION_STATUS_ARCHIVED );
            } break;
            case 3:
            {
                $statusName = 'published';
                $version->setAttribute( 'status', EZ_VERSION_STATUS_PUBLISHED );
            } break;
            default:
                $statusName = 'none';
        }
        $version->store();
    }

    function setObjectStatusPublished( $objectID )
    {
        $object =& eZContentObject::fetch( $objectID );
        $object->setAttribute( 'status', EZ_CONTENT_OBJECT_STATUS_PUBLISHED );
        $object->store();
    }

    function publishNode( $parentNodeID, $objectID, $versionNum )
    {
        $object =& eZContentObject::fetch( $objectID );
        $version =& $object->version( $versionNum );
        $nodeAssignment =& eZNodeAssignment::fetch( $objectID, $versionNum, $parentNodeID );

        $object->setAttribute( 'current_version', $versionNum );
        if ( $versionNum == 1 )
        {
            $object->setAttribute( 'published', mktime() );
        }
        $object->setAttribute( 'modified', mktime() );
        $object->store();

        $class =& eZContentClass::fetch( $object->attribute( 'contentclass_id' ) );
        $objectName = $class->contentObjectName( $object );

        $object->setName( $objectName, $versionNum );
//        $object->setAttribute( 'name', $objectName );
        $object->store();

        $fromNodeID = $nodeAssignment->attribute( 'from_node_id' );
        $originalObjectID = $nodeAssignment->attribute( 'contentobject_id' );

        $nodeID = $nodeAssignment->attribute( 'parent_node' );
        $parentNode =& eZContentObjectTreeNode::fetch( $nodeID );
        $parentNodeID = $parentNode->attribute( 'node_id' );
        $existingNode =& eZContentObjectTreeNode::findNode( $nodeAssignment->attribute( 'parent_node' ) , $object->attribute( 'id' ), true );
        $updateSectionID = false;
        if ( $existingNode  == null )
        {
            if ( $fromNodeID == 0 )
            {
                $parentNode =& eZContentObjectTreeNode::fetch( $nodeID );
                $existingNode =&  $parentNode->addChild( $object->attribute( 'id' ), 0, true );
            }else
            {
                $originalNode =& eZContentObjectTreeNode::fetchNode( $originalObjectID, $fromNodeID );
                if ( $originalNode->attribute( 'main_node_id' ) == $originalNode->attribute( 'node_id' ) )
                {
                    $updateSectionID = true;
                }
                $originalNode->move( $parentNodeID );
                $existingNode =& eZContentObjectTreeNode::fetchNode( $originalObjectID, $parentNodeID );
            }
        }

        $existingNode->setAttribute( 'sort_field', $nodeAssignment->attribute( 'sort_field' ) );
        $existingNode->setAttribute( 'sort_order', $nodeAssignment->attribute( 'sort_order' ) );
        $existingNode->setAttribute( 'contentobject_version', $version->attribute( 'version' ) );
        $existingNode->setAttribute( 'contentobject_is_published', 1 );
        $existingNode->setName( $objectName );

        eZDebug::createAccumulatorGroup( 'nice_urls_total', 'Nice urls' );

        $existingNode->updateSubTreePath();

        if ( $nodeAssignment->attribute( 'is_main' ) )
        {

            if ( $existingNode->attribute( 'main_node_id' ) != $existingNode->attribute( 'node_id' ) )
            {
                $updateSectionID = true;
            }

            $existingNode->setAttribute( 'main_node_id', $existingNode->attribute( 'node_id' ) );
            $existingNodes =& eZContentObjectTreeNode::fetchByContentObjectID( $objectID, true );
            foreach( array_keys( $existingNodes ) as $key )
            {
                $node =& $existingNodes[$key];
                $node->setAttribute( 'main_node_id', $existingNode->attribute( 'node_id' ) );
                $node->store();
            }
        }
/*
        if ( $version->attribute( 'main_parent_node_id' ) == $existingNode->attribute( 'parent_node_id' ) )
        {
            $object->setAttribute( 'main_node_id', $existingNode->attribute( 'node_id' ) );
        }
*/
        $version->setAttribute( 'status', EZ_VERSION_STATUS_PUBLISHED );
        $version->store();

        $object->store();
        $existingNode->store();

        if ( $updateSectionID )
        {
            eZDebug::writeDebug( "will  update section ID " );
            eZContentOperationCollection::updateSectionID( $objectID, $versionNum );
        }

    }

    function updateSectionID( $objectID, $versionNum )
    {
        $object =& eZContentObject::fetch( $objectID );
        $version =& $object->version( $versionNum );

        $assignments =& $version->attribute( 'parent_nodes' );
        foreach ( array_keys( $assignments ) as $key )
        {
            if ( $assignments[$key]->attribute( 'is_main' ) == 1 )
            {
                $parentNode =& $assignments[$key]->attribute( 'parent_node_obj' );
                if ( $parentNode )
                {
                    $parentObject =& $parentNode->attribute( 'object' );
                    if ( $parentObject )
                    {
                        $sectionID = $parentObject->attribute( 'section_id' );
                        if ( $sectionID != $object->attribute( 'section_id' ) )
                        {
                            $object->setAttribute( 'section_id', $sectionID );
                            $object->store();
                        }
                    }
                }
                break;
            }
        }
    }

    function removeOldNodes( $objectID, $versionNum )
    {
        $object =& eZContentObject::fetch( $objectID );
        $version =& $object->version( $versionNum );

        $assignedExistingNodes =& $object->attribute( 'assigned_nodes' );

        $curentVersionNodeAssignments = $version->attribute( 'node_assignments' );
        //    var_dump( $curentVersionNodeAssignments );
        $versionParentIDList = array();
        foreach ( array_keys( $curentVersionNodeAssignments ) as $key )
        {
            $nodeAssignment =& $curentVersionNodeAssignments[$key];
            $versionParentIDList[] = $nodeAssignment->attribute( 'parent_node' );
        }
        foreach ( array_keys( $assignedExistingNodes )  as $key )
        {
            $node =& $assignedExistingNodes[$key];
            if ( $node->attribute( 'contentobject_version' ) < $version->attribute( 'version' ) &&
                 !in_array( $node->attribute( 'parent_node_id' ), $versionParentIDList ) )
            {
                $node->remove();
            }
        }
    }

    function registerSearchObject( $objectID, $versionNum )
    {
        eZDebug::createAccumulatorGroup( 'search_total', 'Search Total' );

        include_once( "kernel/classes/ezsearch.php" );
        $object =& eZContentObject::fetch( $objectID );
        // Register the object in the search engine.
        eZDebug::accumulatorStart( 'remove_object', 'search_total', 'remove object' );
        eZSearch::removeObject( $object );
        eZDebug::accumulatorStop( 'remove_object' );
        eZDebug::accumulatorStart( 'add_object', 'search_total', 'add object' );
        eZSearch::addObject( $object );
        eZDebug::accumulatorStop( 'add_object' );


    }

}

?>
