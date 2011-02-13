<?php
//
// Definition of eZCollaborationItemMessageLink class
//
// Created on: <24-Jan-2003 15:11:23 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
  \class eZCollaborationItemMessageLink ezcollaborationitemmessagelink.php
  \brief The class eZCollaborationItemMessageLink does

*/

class eZCollaborationItemMessageLink extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationItemMessageLink( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'collaboration_id' => array( 'name' => 'CollaborationID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true,
                                                                      'foreign_class' => 'eZCollaborationItem',
                                                                      'foreign_attribute' => 'id',
                                                                      'multiplicity' => '1..*' ),
                                         'message_id' => array( 'name' => 'MessageID',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true,
                                                                'foreign_class' => 'eZCollaborationSimpleMessage',
                                                                'foreign_attribute' => 'id',
                                                                'multiplicity' => '1..*' ),
                                         'message_type' => array( 'name' => 'MessageType',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         'participant_id' => array( 'name' => 'ParticipantID',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true,
                                                                    'foreign_class' => 'eZUser',
                                                                    'foreign_attribute' => 'contentobject_id',
                                                                    'multiplicity' => '1..*' ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      'keys' => array( 'id' ),
                      'function_attributes' => array( 'collaboration_item' => 'collaborationItem',
                                                      'participant' => 'participant',
                                                      'simple_message' => 'simpleMessage' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZCollaborationItemMessageLink',
                      'name' => 'ezcollab_item_message_link' );
    }

    static function create( $collaborationID, $messageID, $messageType, $participantID )
    {
        $dateTime = time();
        $row = array(
            'collaboration_id' => $collaborationID,
            'message_id' => $messageID,
            'message_type' => $messageType,
            'participant_id' => $participantID,
            'created' => $dateTime,
            'modified' => $dateTime );
        return new eZCollaborationItemMessageLink( $row );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function addMessage( $collaborationItem, $message, $messageType, $participantID = false )
    {
        $messageID = $message->attribute( 'id' );

        if ( !$messageID )
        {
            eZDebug::writeError( 'No message ID, cannot create link', 'eZCollaborationItemMessageLink::addMessage' );
            $retValue = null;
            return $retValue;
        }
        if ( $participantID === false )
        {
            $user = eZUser::currentUser();
            $participantID = $user->attribute( 'contentobject_id' );
        }
        $collaborationID = $collaborationItem->attribute( 'id' );
        $timestamp = time();
        $collaborationItem->setAttribute( 'modified', $timestamp );

        $db = eZDB::instance();
        $db->begin();
        $collaborationItem->sync();
        $link = eZCollaborationItemMessageLink::create( $collaborationID, $messageID, $messageType, $participantID );
        $link->store();
        $db->commit();

        return $link;
    }

    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZCollaborationItemMessageLink::definition(),
                                                null,
                                                array( "id" => $id ),
                                                null, null,
                                                $asObject );
    }

    static function fetchItemCount( $parameters )
    {
        $parameters = array_merge( array( 'item_id' => false,
                                          'conditions' => null ),
                                   $parameters );
        $itemID = $parameters['item_id'];
        $conditions = $parameters['conditions'];
        if ( $conditions === null )
            $conditions = array();
        $conditions['collaboration_id'] = $itemID;

        $objectList = eZPersistentObject::fetchObjectList( eZCollaborationItemMessageLink::definition(),
                                                           array(),
                                                           $conditions,
                                                           false,
                                                           null,
                                                           false,
                                                           false,
                                                           array( array( 'operation' => 'count( id )',
                                                                         'name' => 'count' ) ) );
        return $objectList[0]['count'];
    }

    static function fetchItemList( $parameters )
    {
        $parameters = array_merge( array( 'as_object' => true,
                                          'item_id' => false,
                                          'offset' => false,
                                          'limit' => false,
                                          'sort_by' => false ),
                                   $parameters );
        $itemID = $parameters['item_id'];
        $asObject = $parameters['as_object'];
        $offset = $parameters['offset'];
        $limit = $parameters['limit'];
        $limitArray = null;
        if ( $offset and $limit )
        {
            $limitArray = array( 'offset' => $offset,
                                 'limit' => $limit );
        }

        return eZPersistentObject::fetchObjectList( eZCollaborationItemMessageLink::definition(),
                                                    null,
                                                    array( "collaboration_id" => $itemID ),
                                                    null, $limitArray,
                                                    $asObject );
    }


    function collaborationItem()
    {
        if ( isset( $this->CollaborationID ) and $this->CollaborationID )
        {
            return eZCollaborationItem::fetch( $this->CollaborationID );
        }

        return null;
    }

    function participant()
    {
        return eZCollaborationItemParticipantLink::fetch( $this->CollaborationID, $this->ParticipantID );
    }

    function simpleMessage()
    {
        if ( isset( $this->MessageID ) and $this->MessageID )
        {
            return eZCollaborationSimpleMessage::fetch( $this->MessageID );
        }

        return null;
    }

    /// \privatesection
    public $CollaborationID;
    public $MessageID;
    public $ParticipantID;
    public $Created;
    public $Modified;
}

?>
