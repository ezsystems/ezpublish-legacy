<?php
//
// Definition of eZCollaborationFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
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

/*! \file ezcontentfunctioncollection.php
*/

/*!
  \class eZCollaborationFunctionCollection ezcontentfunctioncollection.php
  \brief The class eZCollaborationFunctionCollection does

*/

include_once( 'kernel/error/errors.php' );

class eZCollaborationFunctionCollection
{
    /*!
     Constructor
    */
    function eZCollaborationFunctionCollection()
    {
    }

    function &fetchParticipant( $itemID, $participantID )
    {
        include_once( 'kernel/classes/ezcollaborationitemparticipantlink.php' );
        if ( $participantID === false )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            $user =& eZUser::currentUser();
            $participantID = $user->attribute( 'contentobject_id' );
        }
        $participant =& eZCollaborationItemParticipantLink::fetch( $itemID, $participantID );
        if ( $participant === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$participant );
    }

    function &fetchParticipantList( $itemID, $sortBy, $offset, $limit )
    {
        include_once( 'kernel/classes/ezcollaborationitemparticipantlink.php' );
        $itemParameters = array( 'item_id' => $itemID,
                                 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy );
        $children =& eZCollaborationItemParticipantLink::fetchParticipantList( $itemParameters );
        if ( $children === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$children );
    }

    function &fetchParticipantMap( $itemID, $sortBy, $offset, $limit, $field )
    {
        include_once( 'kernel/classes/ezcollaborationitemparticipantlink.php' );
        $itemParameters = array( 'item_id' => $itemID,
                                 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy );
        if ( $field !== false )
            $itemParameters['sort_field'] = $field;
        $children =& eZCollaborationItemParticipantLink::fetchParticipantMap( $itemParameters );
        if ( $children === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$children );
    }

    function &fetchMessageList( $itemID, $sortBy, $offset, $limit )
    {
        include_once( 'kernel/classes/ezcollaborationitemmessagelink.php' );
        $itemParameters = array( 'item_id' => $itemID,
                                 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy );
        $children =& eZCollaborationItemMessageLink::fetchItemList( $itemParameters );
        if ( $children === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$children );
    }

    function &fetchItemList( $sortBy, $offset, $limit, $status, $isRead, $isActive, $parentGroupID )
    {
        include_once( 'kernel/classes/ezcollaborationitem.php' );
        $itemParameters = array( 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy,
                                 'is_read' => $isRead,
                                 'is_active' => $isActive,
                                 'parent_group_id' => $parentGroupID );
        if ( $status !== false )
            $itemParameters['status'] = $status;
        $children =& eZCollaborationItem::fetchList( $itemParameters );
        if ( $children === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$children );
    }

    function &fetchItemCount( $parentGroupID )
    {
        include_once( 'kernel/classes/ezcollaborationitem.php' );
        $itemParameters = array( 'parent_group_id' => $parentGroupID );
        $count =& eZCollaborationItem::fetchListCount( $itemParameters );
        return array( 'result' => $count );
    }

    function &fetchGroupTree( $parentGroupID, $sortBy, $offset, $limit, $depth )
    {
        include_once( 'kernel/classes/ezcollaborationgroup.php' );
        $treeParameters = array( 'parent_group_id' => $parentGroupID,
                                 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy,
                                 'depth' => $depth );
        $children =& eZCollaborationGroup::subTree( $treeParameters );
        if ( $children === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$children );
    }

    function &fetchObjectTreeCount( $parentNodeID, $class_filter_type, $class_filter_array, $depth )
    {
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $node =& eZCollaborationObjectTreeNode::fetch( $parentNodeID );
        $childrenCount =& $node->subTreeCount( array( 'Limitation' => null,
                                                      'ClassFilterType' => $class_filter_type,
                                                      'ClassFilterArray' => $class_filter_array,
                                                      'Depth' => $depth ) );
        if ( $childrenCount === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$childrenCount );
    }

}

?>
