<?php
//
// Definition of eZCollaborationItem class
//
// Created on: <22-Jan-2003 15:44:48 amos>
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

/*! \file ezcollaborationitem.php
*/

/*!
  \class eZCollaborationItem ezcollaborationitem.php
  \brief The class eZCollaborationItem does

*/

define( "EZ_COLLABORATION_STATUS_ACTIVE", 1 );
define( "EZ_COLLABORATION_STATUS_INACTIVE", 2 );
define( "EZ_COLLABORATION_STATUS_ARCHIVE", 3 );

include_once( 'kernel/classes/ezpersistentobject.php' );

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
        return array( 'fields' => array( 'id' => 'ID',
                                         'type_identifier' => 'TypeIdentifier',
                                         'creator_id' => 'CreatorID',
                                         'status' => 'Status',
                                         'data_text1' => 'DataText1',
                                         'data_text2' => 'DataText2',
                                         'data_text3' => 'DataText3',
                                         'data_int1' => 'DataInt1',
                                         'data_int2' => 'DataInt2',
                                         'data_int3' => 'DataInt3',
                                         'data_float1' => 'DataFloat1',
                                         'data_float2' => 'DataFloat2',
                                         'data_float3' => 'DataFloat3',
                                         'created' => 'Created',
                                         'modified' => 'Modified' ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZCollaborationItem',
                      'sort' => array( 'modified' => 'asc' ),
                      'name' => 'ezcollab_item' );
    }

    function &create( $typeIdentifier, $creatorID, $status = EZ_COLLABORATION_STATUS_ACTIVE )
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $date_time = eZDateTime::currentTimeStamp();
        $row = array(
            'id' => null,
            'type_identifier' => $typeIdentifier,
            'creator_id' => $creatorID,
            'status' => $status,
            'created' => $date_time,
            'modified' => $date_time );
        return new eZCollaborationItem( $row );
    }

    function &fetch( $id, $creatorID = false, $asObject = true )
    {
        $conditions = array( "id" => $id );
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
                $user =& eZUser::currentUser();
                $userID = $user->attribute( 'contentobject_id' );
                return $userID == $this->CreatorID;
            } break;
            case 'participant_list':
            {
                include_once( 'kernel/classes/ezcollaborationitemparticipantlist.php' );
                return eZCollaborationItemParticipantList::fetchParticipantList( $this->ID );
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

    function fetchListCount( $parameters = array() )
    {
        $parameters = array_merge( array(),
                                   $parameters );

        $user =& eZUser::currentUser();
        $userID =& $user->attribute( 'contentobject_id' );

        $statusText = implode( ', ', array( EZ_COLLABORATION_STATUS_ACTIVE,
                                            EZ_COLLABORATION_STATUS_INACTIVE ) );

        $sql = "SELECT count( ezcollab_item.id ) as count
                FROM
                       ezcollab_item,
                       ezcollab_item_group_link
                WHERE  ezcollab_item.status IN ( $statusText ) AND
                       ezcollab_item.id = ezcollab_item_group_link.collaboration_id AND
                       ezcollab_item_group_link.user_id=$userID";

        $db =& eZDB::instance();
        $itemCount =& $db->arrayQuery( $sql );
        return $itemCount[0]['count'];
    }

    function fetchList( $parameters = array() )
    {
        $parameters = array_merge( array( 'as_object' => true,
                                          'offset' => false,
                                          'parent_group_id' => false,
                                          'limit' => false,
                                          'status' => false,
                                          'sort_by' => false ),
                                   $parameters );
        $asObject = $parameters['as_object'];
        $offset = $parameters['offset'];
        $limit = $parameters['limit'];
        $statusTypes = $parameters['status'];
        $parentGroupID = $parameters['parent_group_id'];

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
                    $sortingFields .= $sortOrder ? " ASC" : " DESC";
                    ++$sortCount;
                }
            }
        }
        if ( $sortCount == 0 )
        {
            $sortingFields = " ezcollab_item_group_link.modified DESC";
        }

        $parentGroupText = '';
        if ( $parentGroupID !== false )
        {
            $parentGroupText = "ezcollab_item_group_link.group_id = '$parentGroupID' AND";
        }

        $user =& eZUser::currentUser();
        $userID =& $user->attribute( 'contentobject_id' );

        $statusText = '';
        if ( $statusTypes === false )
            $statusTypes = array( EZ_COLLABORATION_STATUS_ACTIVE,
                                  EZ_COLLABORATION_STATUS_INACTIVE );
        $statusText = implode( ', ', $statusTypes );

        $sql = "SELECT ezcollab_item.*
                FROM
                       ezcollab_item,
                       ezcollab_item_group_link
                WHERE  ezcollab_item.status IN ( $statusText ) AND
                       ezcollab_item.id = ezcollab_item_group_link.collaboration_id AND
                       $parentGroupText
                       ezcollab_item_group_link.user_id = '$userID'
                ORDER BY $sortingFields";

        $db =& eZDB::instance();
        $sqlParameters = array();
        if ( $offset !== false and $limit !== false )
        {
            $sqlParameters['offset'] = $offset;
            $sqlParameters['limit'] = $limit;
        }
        $itemListArray =& $db->arrayQuery( $sql, $sqlParameters );
        $returnItemList =& eZPersistentObject::handleRows( $itemListArray, 'eZCollaborationItem', $asObject );
        eZDebugSetting::writeDebug( 'collaboration-item-list', $returnItemList );
        return $returnItemList;
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
