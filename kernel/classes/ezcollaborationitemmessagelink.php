<?php
//
// Definition of eZCollaborationItemMessageLink class
//
// Created on: <24-Jan-2003 15:11:23 amos>
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

/*! \file ezcollaborationitemmessagelink.php
*/

/*!
  \class eZCollaborationItemMessageLink ezcollaborationitemmessagelink.php
  \brief The class eZCollaborationItemMessageLink does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );

class eZCollaborationItemMessageLink extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationItemMessageLink( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'collaboration_id' => array( 'name' => 'CollaborationID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         'message_id' => array( 'name' => 'MessageID',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'message_type' => array( 'name' => 'MessageType',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         'participant_id' => array( 'name' => 'ParticipantID',
                                                                    'datatype' => 'integer',
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
                      'class_name' => 'eZCollaborationItemMessageLink',
                      'name' => 'ezcollab_item_message_link' );
    }

    function &create( $collaborationID, $messageID, $messageType, $participantID )
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

    function &addMessage( &$collaborationItem, &$message, $messageType, $participantID = false )
    {
        $messageID =& $message->attribute( 'id' );
        eZDebug::writeDebug( $message );
        eZDebug::writeDebug( $messageID );
        if ( !$messageID )
        {
            eZDebug::writeError( 'No message ID, cannot create link', 'eZCollaborationItemMessageLink::addMessage' );
            return null;
        }
        if ( $participantID === false )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            $user =& eZUser::currentUser();
            $participantID =& $user->attribute( 'contentobject_id' );
        }
        $collaborationID = $collaborationItem->attribute( 'id' );
        $timestamp = time();
        $collaborationItem->setAttribute( 'modified', $timestamp );
        $collaborationItem->sync();
        $link =& eZCollaborationItemMessageLink::create( $collaborationID, $messageID, $messageType, $participantID );
        $link->store();
        return $link;
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZCollaborationItemMessageLink::definition(),
                                                null,
                                                array( "id" => $id ),
                                                null, null,
                                                $asObject );
    }

    function &fetchItemCount( $parameters )
    {
        $parameters = array_merge( array( 'item_id' => false,
                                          'conditions' => null ),
                                   $parameters );
        $itemID = $parameters['item_id'];
        $conditions = $parameters['conditions'];
        if ( $conditions === null )
            $conditions = array();
        $conditions['collaboration_id'] = $itemID;

        $objectList =& eZPersistentObject::fetchObjectList( eZCollaborationItemMessageLink::definition(),
                                                            array(),
                                                            $conditions,
                                                            null, null,
                                                            false,
                                                            false,
                                                            array( array( 'operation' => 'count( id )',
                                                                          'name' => 'count' ) ) );
        return $objectList[0]['count'];
    }

    function &fetchItemList( $parameters )
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

    function hasAttribute( $attribute )
    {
        return ( $attribute == 'collaboration_item' or
                 $attribute == 'participant' or
                 $attribute == 'simple_message' or
                 eZPersistentObject::hasAttribute( $attribute ) );
    }

    function &attribute( $attribute )
    {
        switch( $attribute )
        {
            case 'collaboration_item':
            {
                include_once( 'kernel/classes/ezcollaborationitem.php' );
                return eZCollaborationItem::fetch( $this->CollaborationID );
            } break;
            case 'simple_message':
            {
                include_once( 'kernel/classes/ezcollaborationsimplemessage.php' );
                $message =& eZCollaborationSimpleMessage::fetch( $this->MessageID );
                eZDebug::writeDebug( $message );
                return $message;
            } break;
            case 'participant':
            {
                $participantLink =& eZCollaborationItemParticipantLink::fetch( $this->CollaborationID, $this->ParticipantID );
                return $participantLink;
            } break;
            default:
                return eZPersistentObject::attribute( $attribute );
        }
    }

    /// \privatesection
    var $CollaborationID;
    var $MessageID;
    var $ParticipantID;
    var $Created;
    var $Modified;
}

?>
