<?php
//
// Definition of eZCollaborationMessageLink class
//
// Created on: <24-Jan-2003 15:11:23 amos>
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

/*! \file ezcollaborationmessagelink.php
*/

/*!
  \class eZCollaborationMessageLink ezcollaborationmessagelink.php
  \brief The class eZCollaborationMessageLink does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );

class eZCollaborationMessageLink extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationMessageLink( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => 'ID',
                                         'collaboration_id' => 'CollaborationID',
                                         'message_id' => 'MessageID',
                                         'participant_id' => 'ParticipantID',
                                         'created' => 'Created',
                                         'modified' => 'Modified' ),
                      'keys' => array( 'id' ),
                      'class_name' => 'eZCollaborationMessageLink',
                      'name' => 'ezcollab_message_link' );
    }

    function &create( $collaborationID, $messageID, $participantID )
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $date_time = eZDateTime::currentTimeStamp();
        $row = array(
            'collaboration_id' => $collaborationID,
            'message_id' => $messageID,
            'participant_id' => $participantID,
            'created' => $date_time,
            'modified' => $date_time );
        return new eZCollaborationMessageLink( $row );
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZCollaborationMessageLink::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    function hasAttribute( $attr )
    {
        return ( $attr == 'collaboration_item' or
                 $attr == 'participant' or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'collaboration_item':
            {
                include_once( 'kernel/classes/ezcollaborationitem.php' );
                return eZCollaborationItem::fetch( $this->CollaborationID );
            } break;
            case 'participant':
            {
                // TODO: Get participant trough participant link from item
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
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
