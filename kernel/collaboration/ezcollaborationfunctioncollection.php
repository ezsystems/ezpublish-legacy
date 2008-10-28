<?php
//
// Definition of eZCollaborationFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezcontentfunctioncollection.php
*/

/*!
  \class eZCollaborationFunctionCollection ezcontentfunctioncollection.php
  \brief The class eZCollaborationFunctionCollection does

*/

class eZCollaborationFunctionCollection
{
    /*!
     Constructor
    */
    function eZCollaborationFunctionCollection()
    {
    }

    function fetchParticipant( $itemID, $participantID )
    {
        if ( $participantID === false )
        {
            $user = eZUser::currentUser();
            $participantID = $user->attribute( 'contentobject_id' );
        }
        $participant = eZCollaborationItemParticipantLink::fetch( $itemID, $participantID );
        if ( $participant === null )
        {
            $resultArray = array( 'error' => array( 'error_type' => 'kernel',
                                                    'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $resultArray = array( 'result' => $participant );
        }
        return $resultArray;
    }

    function fetchParticipantList( $itemID, $sortBy, $offset, $limit )
    {
        $itemParameters = array( 'item_id' => $itemID,
                                 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy );
        $children = eZCollaborationItemParticipantLink::fetchParticipantList( $itemParameters );
        if ( $children === null )
        {
            $resultArray = array( 'error' => array( 'error_type' => 'kernel',
                                                    'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $resultArray = array( 'result' => $children );
        }
        return $resultArray;
    }

    function fetchParticipantMap( $itemID, $sortBy, $offset, $limit, $field )
    {
        $itemParameters = array( 'item_id' => $itemID,
                                 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy );
        if ( $field !== false )
            $itemParameters['sort_field'] = $field;
        $children = eZCollaborationItemParticipantLink::fetchParticipantMap( $itemParameters );
        if ( $children === null )
        {
            $resultArray = array( 'error' => array( 'error_type' => 'kernel',
                                                    'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $resultArray = array( 'result' => $children );
        }
        return $resultArray;
    }

    function fetchMessageList( $itemID, $sortBy, $offset, $limit )
    {
        $itemParameters = array( 'item_id' => $itemID,
                                 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy );
        $children = eZCollaborationItemMessageLink::fetchItemList( $itemParameters );
        if ( $children === null )
        {
            $resultArray = array( 'error' => array( 'error_type' => 'kernel',
                                                    'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $resultArray = array( 'result' => $children );
        }
        return $resultArray;
    }

    function fetchItemList( $sortBy, $offset, $limit, $status, $isRead, $isActive, $parentGroupID )
    {
        $itemParameters = array( 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy,
                                 'is_read' => $isRead,
                                 'is_active' => $isActive,
                                 'parent_group_id' => $parentGroupID );
        if ( $status !== false )
            $itemParameters['status'] = $status;
        $children = eZCollaborationItem::fetchList( $itemParameters );
        if ( $children === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $children );
    }

    function fetchItemCount( $isRead, $isActive, $parentGroupID, $status )
    {
        $itemParameters = array( 'is_read' => $isRead,
                                 'is_active' => $isActive,
                                 'parent_group_id' => $parentGroupID
                                 );
        if ( $status !== false )
            $itemParameters['status'] = $status;
        $count = eZCollaborationItem::fetchListCount( $itemParameters );
        return array( 'result' => $count );
    }

    function fetchGroupTree( $parentGroupID, $sortBy, $offset, $limit, $depth )
    {
        $treeParameters = array( 'parent_group_id' => $parentGroupID,
                                 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy,
                                 'depth' => $depth );
        $children = eZCollaborationGroup::subTree( $treeParameters );
        if ( $children === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $children );
    }

    function fetchObjectTreeCount( $parentNodeID, $class_filter_type, $class_filter_array, $depth )
    {
        $node = eZContentObjectTreeNode::fetch( $parentNodeID );
        $childrenCount = $node->subTreeCount( array( 'Limitation' => null,
                                                     'ClassFilterType' => $class_filter_type,
                                                     'ClassFilterArray' => $class_filter_array,
                                                     'Depth' => $depth ) );
        if ( $childrenCount === null )
        {
            $resultArray = array( 'error' => array( 'error_type' => 'kernel',
                                                    'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $resultArray = array( 'result' => $childrenCount );
        }
        return $resultArray;
    }

}

?>
