<?php
//
// Definition of eZCollaborationItem class
//
// Created on: <22-Jan-2003 15:44:48 amos>
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

/*! \file ezcollaborationitem.php
*/

/*!
  \class eZCollaborationItem ezcollaborationitem.php
  \brief The class eZCollaborationItem does

*/

define( 'EZ_COLLABORATION_STATUS_ACTIVE', 1 );
define( 'EZ_COLLABORATION_STATUS_INACTIVE', 2 );
define( 'EZ_COLLABORATION_STATUS_ARCHIVE', 3 );

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezcollaborationitemstatus.php' );

class eZCollaborationItem extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationItem( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'type_identifier' => array( 'name' => 'TypeIdentifier',
                                                                     'datatype' => 'string',
                                                                     'default' => '',
                                                                     'required' => true ),
                                         'creator_id' =>  array( 'name' => 'CreatorID',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'status' => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => 1,
                                                            'required' => true ),
                                         'data_text1' => array( 'name' => 'DataText1',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'data_text2' => array( 'name' => 'DataText2',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'data_text3' => array( 'name' => 'DataText3',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'data_int1' => array( 'name' => 'DataInt1',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'data_int2' => array( 'name' => 'DataInt2',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'data_int3' => array( 'name' => 'DataInt3',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'data_float1' => array( 'name' => 'DataFloat1',
                                                                 'datatype' => 'float',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'data_float2' => array( 'name' => 'DataFloat2',
                                                                 'datatype' => 'float',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'data_float3' => array( 'name' => 'DataFloat3',
                                                                 'datatype' => 'float',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZCollaborationItem',
                      'sort' => array( 'modified' => 'asc' ),
                      'name' => 'ezcollab_item' );
    }

    function &create( $typeIdentifier, $creatorID, $status = EZ_COLLABORATION_STATUS_ACTIVE )
    {
        $date_time = time();
        $row = array(
            'id' => null,
            'type_identifier' => $typeIdentifier,
            'creator_id' => $creatorID,
            'status' => $status,
            'created' => $date_time,
            'modified' => $date_time );
        return new eZCollaborationItem( $row );
    }

    /*!
     Creates a collaboration notification event and stores it.
     \a subType can be used to specify a sub type of this collaboration item.
    */
    function &createNotificationEvent( $subType = false )
    {
        $handler =& $this->attribute( 'handler' );
        $info = $handler->attribute( 'info' );
        $type = $info['type-identifier'];
        if ( $subType )
            $type .= '_' . $subType;
        include_once( 'kernel/classes/notification/eznotificationevent.php' );
        $event =& eZNotificationEvent::create( 'ezcollaboration', array( 'collaboration_id' => $this->attribute( 'id' ),
                                                                         'collaboration_identifier' => $type ) );
        $event->store();
        return $event;
    }

    function &fetch( $id, $creatorID = false, $asObject = true )
    {
        $conditions = array( 'id' => $id );
        if ( $creatorID !== false )
            $conditions['creator_id'] = $creatorID;
        return eZPersistentObject::fetchObject( eZCollaborationItem::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    function hasAttribute( $attribute )
    {
        return ( $attribute == 'creator' or
                 $attribute == 'participant_list' or
                 $attribute == 'handler' or
                 $attribute == 'content' or
                 $attribute == 'title' or
                 $attribute == 'user_status' or
                 $attribute == 'use_messages' or
                 $attribute == 'message_count' or
                 $attribute == 'unread_message_count' or
                 $attribute == 'is_creator' or
                 eZPersistentObject::hasAttribute( $attribute ) );
    }

    function &attribute( $attribute )
    {
        switch( $attribute )
        {
            case 'creator':
            {
                $userID = $this->CreatorID;
                include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
                $user =& eZUser::fetch( $userID );
                return $user;
            } break;
            case 'is_creator':
            {
                include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
                $userID =& eZUser::currentUserID();
                return $userID == $this->CreatorID;
            } break;
            case 'participant_list':
            {
                include_once( 'kernel/classes/ezcollaborationitemparticipantlink.php' );
                return eZCollaborationItemParticipantLink::fetchParticipantList( $this->ID );
            } break;
            case 'user_status':
            {
                include_once( 'kernel/classes/ezcollaborationitemstatus.php' );
                include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
                $userID =& eZUser::currentUserID();
                return eZCollaborationItemStatus::fetch( $this->ID, $userID );
            } break;
            case 'use_messages':
            {
                return $this->useMessages();
            } break;
            case 'message_count':
            {
                return $this->messageCount();
            } break;
            case 'unread_message_count':
            {
                return $this->unreadMessageCount();
            } break;
            case 'handler':
            {
                return $this->handler();
            } break;
            case 'content':
            {
                return $this->content();
            } break;
            case 'title':
            {
                return $this->content();
            } break;
            default:
                return eZPersistentObject::attribute( $attribute );
        }
    }

    /*!
     \return true if the item uses messages.
     \note It's up to each handler to control this.
    */
    function useMessages()
    {
        $handler =& $this->handler();
        if ( !$handler )
            return false;
        return $handler->useMessages( $this );
    }

    /*!
     \return the number of messages in this item.
     \note The message count is purely abstract and it's up to each handler to return a valid count.
    */
    function messageCount()
    {
        $handler =& $this->handler();
        return $handler->messageCount( $this );
    }

    /*!
     \return the number of unread messages in this item.
     \note The message count is purely abstract and it's up to each handler to return a valid count.
           It's also up the handler to keep track of which messages are read or not.
    */
    function unreadMessageCount()
    {
        $handler =& $this->handler();
        return $handler->unreadMessageCount( $this );
    }

    function hasContentAttribute( $attribute )
    {
        $handler =& $this->handler();
        return $handler->hasContentAttribute( $this, $attribute );
    }

    function &contentAttribute( $attribute )
    {
        $handler =& $this->handler();
        return $handler->contentAttribute( $this, $attribute );
    }

    function content()
    {
        $handler =& $this->handler();
        return $handler->content( $this );
    }

    function title()
    {
        $handler =& $this->handler();
        return $handler->title( $this );
    }

    function &handler()
    {
        include_once( 'kernel/classes/ezcollaborationitemhandler.php' );
        $handler =& eZCollaborationItemHandler::instantiate( $this->attribute( 'type_identifier' ) );
        return $handler;
    }

    /*!
     \static
    */
    function setIsActive( $active, $userID = false )
    {
        eZCollaborationItemStatus::updateFields( $this->attribute( 'id' ), $userID, array( 'is_active' => $active ) );
    }

    function fetchListCount( $parameters = array() )
    {
        return eZCollaborationItem::fetchListTool( $parameters, true );
//         $parameters = array_merge( array( 'status' => false
//                                           'is_active' => null,
//                                           'is_read' => null ),
//                                    $parameters );
//         $statusTypes = $parameters['status'];
//         $isRead = $parameters['is_read'];
//         $isActive = $parameters['is_active'];

//         $user =& eZUser::currentUser();
//         $userID =& $user->attribute( 'contentobject_id' );

//         $isReadText = '';
//         if ( $isRead !== null )
//         {
//             $isReadValue = $isRead ? 1 : 0;
//             $isReadText = "ezcollab_item_group_link.is_read = '$isReadValue' AND";
//         }

//         $isActiveText = '';
//         if ( $isActive !== null )
//         {
//             $isActiveValue = $isActive ? 1 : 0;
//             $isActiveText = "ezcollab_item_group_link.is_active = '$isActiveValue' AND";
//         }

//         $statusText = '';
//         if ( $statusTypes === false )
//             $statusTypes = array( EZ_COLLABORATION_STATUS_ACTIVE,
//                                   EZ_COLLABORATION_STATUS_INACTIVE );
//         $statusText = implode( ', ', $statusTypes );

//         $sql = "SELECT count( ezcollab_item.id ) as count
//                 FROM
//                        ezcollab_item,
//                        ezcollab_item_group_link
//                 WHERE  ezcollab_item.status IN ( $statusText ) AND
//                        $isReadText
//                        $isActiveText
//                        ezcollab_item.id = ezcollab_item_group_link.collaboration_id AND
//                        ezcollab_item_group_link.user_id=$userID";

//         $db =& eZDB::instance();
//         $itemCount =& $db->arrayQuery( $sql );
//         return $itemCount[0]['count'];
    }

    function setLastRead( $userID = false, $timestamp = false )
    {
        if ( $userID === false )
            $userID = eZUser::currentUserID();
        if ( $timestamp === false )
            $timestamp = time();
        $collaborationID = $this->attribute( 'id' );

        eZCollaborationItemStatus::setLastRead( $collaborationID, $userID, $timestamp );
        eZCollaborationItemParticipantLink::setLastRead( $collaborationID, $userID, $timestamp );
    }

    function fetchList( $parameters = array() )
    {
        return eZCollaborationItem::fetchListTool( $parameters, false );
    }

    function &fetchListTool( $parameters = array(), $asCount )
    {
        $parameters = array_merge( array( 'as_object' => true,
                                          'offset' => false,
                                          'parent_group_id' => false,
                                          'limit' => false,
                                          'is_active' => null,
                                          'is_read' => null,
                                          'status' => false,
                                          'sort_by' => false ),
                                   $parameters );
        $asObject = $parameters['as_object'];
        $offset = $parameters['offset'];
        $limit = $parameters['limit'];
        $statusTypes = $parameters['status'];
        $isRead = $parameters['is_read'];
        $isActive = $parameters['is_active'];
        $parentGroupID = $parameters['parent_group_id'];

        $sortText = '';
        if ( !$asCount )
        {
            $sortCount = 0;
            $sortList = $parameters['sort_by'];
            if ( is_array( $sortList ) and
                 count( $sortList ) > 0 )
            {
                if ( count( $sortList ) > 1 and
                     !is_array( $sortList[0] ) )
                {
                    $sortList = array( $sortList );
                }
            }
            if ( $sortList !== false )
            {
                $sortingFields = '';
                foreach ( $sortList as $sortBy )
                {
                    if ( is_array( $sortBy ) and count( $sortBy ) > 0 )
                    {
                        if ( $sortCount > 0 )
                            $sortingFields .= ', ';
                        $sortField = $sortBy[0];
                        switch ( $sortField )
                        {
                            case 'created':
                            {
                                $sortingFields .= 'ezcollab_item_group_link.created';
                            } break;
                            case 'modified':
                            {
                                $sortingFields .= 'ezcollab_item_group_link.modified';
                            } break;
                            default:
                            {
                                eZDebug::writeWarning( 'Unknown sort field: ' . $sortField, 'eZCollaborationItem::fetchList' );
                                continue;
                            };
                        }
                        $sortOrder = true; // true is ascending
                        if ( isset( $sortBy[1] ) )
                            $sortOrder = $sortBy[1];
                        $sortingFields .= $sortOrder ? ' ASC' : ' DESC';
                        ++$sortCount;
                    }
                }
            }
            if ( $sortCount == 0 )
            {
                $sortingFields = ' ezcollab_item_group_link.modified DESC';
            }
            $sortText = "ORDER BY $sortingFields";
        }

        $parentGroupText = '';
        if ( $parentGroupID > 0 )
        {
            $parentGroupText = "ezcollab_item_group_link.group_id = '$parentGroupID' AND";
        }

        $isReadText = '';
        if ( $isRead !== null )
        {
            $isReadValue = $isRead ? 1 : 0;
            $isReadText = "ezcollab_item_status.is_read = '$isReadValue' AND";
        }

        $isActiveText = '';
        if ( $isActive !== null )
        {
            $isActiveValue = $isActive ? 1 : 0;
            $isActiveText = "ezcollab_item_status.is_active = '$isActiveValue' AND";
        }

        $userID =& eZUser::currentUserID();

        $statusText = '';
        if ( $statusTypes === false )
            $statusTypes = array( EZ_COLLABORATION_STATUS_ACTIVE,
                                  EZ_COLLABORATION_STATUS_INACTIVE );
        $statusText = implode( ', ', $statusTypes );

        if ( $asCount )
            $selectText = 'count( ezcollab_item.id ) as count';
        else
            $selectText = 'ezcollab_item.*, ezcollab_item_status.is_read, ezcollab_item_status.is_active, ezcollab_item_status.last_read';

        $sql = "SELECT $selectText
                FROM
                       ezcollab_item,
                       ezcollab_item_status,
                       ezcollab_item_group_link
                WHERE  ezcollab_item.status IN ( $statusText ) AND
                       $isReadText
                       $isActiveText
                       ezcollab_item.id = ezcollab_item_status.collaboration_id AND
                       ezcollab_item.id = ezcollab_item_group_link.collaboration_id AND
                       $parentGroupText
                       ezcollab_item_status.user_id = '$userID' AND
                       ezcollab_item_group_link.user_id = '$userID'
                $sortText";

        $db =& eZDB::instance();
        if ( !$asCount )
        {
            $sqlParameters = array();
            if ( $offset !== false and $limit !== false )
            {
                $sqlParameters['offset'] = $offset;
                $sqlParameters['limit'] = $limit;
            }
            $itemListArray =& $db->arrayQuery( $sql, $sqlParameters );
            for ( $i = 0; $i < count( $itemListArray ); ++$i )
            {
                $itemData =& $itemListArray[$i];
                $statusObject =& eZCollaborationItemStatus::create( $itemData['id'], $userID );
                $statusObject->setAttribute( 'is_read', $itemData['is_read'] );
                $statusObject->setAttribute( 'is_active', $itemData['is_active'] );
                $statusObject->setAttribute( 'last_read', $itemData['last_read'] );
                $statusObject->updateCache();
            }
            $returnItemList =& eZPersistentObject::handleRows( $itemListArray, 'eZCollaborationItem', $asObject );
            eZDebugSetting::writeDebug( 'collaboration-item-list', $returnItemList );
            return $returnItemList;
        }
        else
        {
            $itemCount =& $db->arrayQuery( $sql );
            return $itemCount[0]['count'];
        }
    }

    function handleView( $viewMode )
    {
        $handler =& $this->handler();
        $handler->readItem( $this, $viewMode );
        return true;
    }

    /*!
     \static
     Removes all collaboration items by fetching them and calling remove on them.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezcollab_item" );
        $db->query( "DELETE FROM ezcollab_item_group_link" );
        $db->query( "DELETE FROM ezcollab_item_message_link" );
        $db->query( "DELETE FROM ezcollab_item_participant_link" );
        $db->query( "DELETE FROM ezcollab_item_status" );
        $db->query( "DELETE FROM ezcollab_notification_rule" );
        $db->query( "DELETE FROM ezcollab_profile" );
        $db->query( "DELETE FROM ezcollab_simple_message" );
    }

    /// \privatesection
    var $ID;
    var $TypeIdentifier;
    var $CreatorID;
    var $Status;
    var $Created;
    var $Modified;
    var $DataText1;
    var $DataText2;
    var $DataText3;
    var $DataInt1;
    var $DataInt2;
    var $DataInt3;
    var $DataFloat1;
    var $DataFloat2;
    var $DataFloat3;
}

?>
