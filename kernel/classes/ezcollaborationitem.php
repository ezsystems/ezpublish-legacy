<?php
//
// Definition of eZCollaborationItem class
//
// Created on: <22-Jan-2003 15:44:48 amos>
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

/*! \file
*/

/*!
  \class eZCollaborationItem ezcollaborationitem.php
  \brief The class eZCollaborationItem does

*/

class eZCollaborationItem extends eZPersistentObject
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_ARCHIVE = 3;

    /*!
     Constructor
    */
    function eZCollaborationItem( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
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
                                                                 'required' => true,
                                                                 'foreign_class' => 'eZUser',
                                                                 'foreign_attribute' => 'contentobject_id',
                                                                 'multiplicity' => '1..*' ),
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
                      'function_attributes' => array( 'creator' => 'creator',
                                                      'is_creator' => 'isCreator',
                                                      'participant_list' => 'participantList',
                                                      'user_status' => 'userStatus',
                                                      'handler' => 'handler',
                                                      'use_messages' => 'useMessages',
                                                      'message_count' => 'messageCount',
                                                      'unread_message_count' => 'unreadMessageCount',
                                                      'content' => 'content',
                                                      'title' => 'title' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZCollaborationItem',
                      'sort' => array( 'modified' => 'asc' ),
                      'name' => 'ezcollab_item' );
    }

    static function create( $typeIdentifier, $creatorID, $status = self::STATUS_ACTIVE )
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
    function createNotificationEvent( $subType = false )
    {
        $handler = $this->attribute( 'handler' );
        $info = $handler->attribute( 'info' );
        $type = $info['type-identifier'];
        if ( $subType )
            $type .= '_' . $subType;
        $event = eZNotificationEvent::create( 'ezcollaboration', array( 'collaboration_id' => $this->attribute( 'id' ),
                                                                         'collaboration_identifier' => $type ) );
        $event->store();
        return $event;
    }

    static function fetch( $id, $creatorID = false, $asObject = true )
    {
        $conditions = array( 'id' => $id );
        if ( $creatorID !== false )
            $conditions['creator_id'] = $creatorID;
        return eZPersistentObject::fetchObject( eZCollaborationItem::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    function creator()
    {
        if ( isset( $this->CreatorID ) and $this->CreatorID )
        {
            return eZUser::fetch( $this->CreatorID );
        }
        return null;
    }

    function isCreator()
    {
        if ( isset( $this->CreatorID ) and $this->CreatorID )
        {
            return ( eZUser::currentUserID() == $this->CreatorID );
        }
        return false;
    }

    function participantList()
    {
        return eZCollaborationItemParticipantLink::fetchParticipantList( array('item_id' => $this->ID ) );
    }

    function userStatus()
    {
        $userID = eZUser::currentUserID();
        return eZCollaborationItemStatus::fetch( $this->ID, $userID );
    }

    function handler()
    {
        return eZCollaborationItemHandler::instantiate( $this->attribute( 'type_identifier' ) );
    }

    /*!
     \return true if the item uses messages.
     \note It's up to each handler to control this.
    */
    function useMessages()
    {
        $handler = $this->handler();
        if ( $handler )
        {
            return $handler->useMessages( $this );
        }
        return null;
    }

    /*!
     \return the number of messages in this item.
     \note The message count is purely abstract and it's up to each handler to return a valid count.
    */
    function messageCount()
    {
        $handler = $this->handler();
        if ( $handler )
        {
            return $handler->messageCount( $this );
        }
        return 0;
    }

    /*!
     \return the number of unread messages in this item.
     \note The message count is purely abstract and it's up to each handler to return a valid count.
           It's also up the handler to keep track of which messages are read or not.
    */
    function unreadMessageCount()
    {
        $handler = $this->handler();
        if ( $handler )
        {
            return $handler->unreadMessageCount( $this );
        }
        return 0;
    }

    function content()
    {
        $handler = $this->handler();
        if ( $handler )
        {
            return $handler->content( $this );
        }
        return null;
    }

    function title()
    {
        $handler = $this->handler();
        if ( $handler )
        {
            return $handler->title( $this );
        }
        return null;
    }


    function hasContentAttribute( $attribute )
    {
        $handler =& $this->handler();
        if ( !$handler )
            $hasContentAttribute = null;
        else
            $hasContentAttribute = $handler->hasContentAttribute( $this, $attribute );
        return $hasContentAttribute;
    }

    function contentAttribute( $attribute )
    {
        $handler = $this->handler();
        if ( $handler )
        {
            return $handler->contentAttribute( $this, $attribute );
        }
        return null;
    }

    function setIsActive( $active, $userID = false )
    {
        $active = intval($active);
        eZCollaborationItemStatus::updateFields( $this->attribute( 'id' ), $userID, array( 'is_active' => $active ) );
    }

    static function fetchListCount( $parameters = array() )
    {
        return eZCollaborationItem::fetchListTool( $parameters, true );
//         $parameters = array_merge( array( 'status' => false
//                                           'is_active' => null,
//                                           'is_read' => null ),
//                                    $parameters );
//         $statusTypes = $parameters['status'];
//         $isRead = $parameters['is_read'];
//         $isActive = $parameters['is_active'];

//         $user = eZUser::currentUser();
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
//             $statusTypes = array( self::STATUS_ACTIVE,
//                                   self::STATUS_INACTIVE );
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

//         $db = eZDB::instance();
//         $itemCount = $db->arrayQuery( $sql );
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

    static function fetchList( $parameters = array() )
    {
        return eZCollaborationItem::fetchListTool( $parameters, false );
    }

    static function fetchListTool( $parameters = array(), $asCount )
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

        $userID = eZUser::currentUserID();

        $statusText = '';
        if ( $statusTypes === false )
            $statusTypes = array( self::STATUS_ACTIVE,
                                  self::STATUS_INACTIVE );
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

        $db = eZDB::instance();
        if ( !$asCount )
        {
            $sqlParameters = array();
            if ( $offset !== false and $limit !== false )
            {
                $sqlParameters['offset'] = $offset;
                $sqlParameters['limit'] = $limit;
            }
            $itemListArray = $db->arrayQuery( $sql, $sqlParameters );

            foreach( $itemListArray as $key => $value )
            {
                $itemData =& $itemListArray[$key];
                $statusObject = eZCollaborationItemStatus::create( $itemData['id'], $userID );
                $statusObject->setAttribute( 'is_read', $itemData['is_read'] );
                $statusObject->setAttribute( 'is_active', $itemData['is_active'] );
                $statusObject->setAttribute( 'last_read', $itemData['last_read'] );
                $statusObject->updateCache();
            }
            $returnItemList = eZPersistentObject::handleRows( $itemListArray, 'eZCollaborationItem', $asObject );
            eZDebugSetting::writeDebug( 'collaboration-item-list', $returnItemList );
            return $returnItemList;
        }
        else
        {
            $itemCount = $db->arrayQuery( $sql );
            return $itemCount[0]['count'];
        }
    }

    function handleView( $viewMode )
    {
        $handler = $this->handler();
        $handler->readItem( $this, $viewMode );
        return true;
    }

    /*!
     \static
     Removes all collaboration items by fetching them and calling remove on them.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->begin();
        $db->query( "DELETE FROM ezcollab_item" );
        $db->query( "DELETE FROM ezcollab_item_group_link" );
        $db->query( "DELETE FROM ezcollab_item_message_link" );
        $db->query( "DELETE FROM ezcollab_item_participant_link" );
        $db->query( "DELETE FROM ezcollab_item_status" );
        $db->query( "DELETE FROM ezcollab_notification_rule" );
        $db->query( "DELETE FROM ezcollab_profile" );
        $db->query( "DELETE FROM ezcollab_simple_message" );
        $db->commit();
    }

    /// \privatesection
    public $ID;
    public $TypeIdentifier;
    public $CreatorID;
    public $Status;
    public $Created;
    public $Modified;
    public $DataText1;
    public $DataText2;
    public $DataText3;
    public $DataInt1;
    public $DataInt2;
    public $DataInt3;
    public $DataFloat1;
    public $DataFloat2;
    public $DataFloat3;
}

?>
