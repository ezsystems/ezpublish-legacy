<?php
//
// Definition of eZCollaborationItemParticipantLink class
//
// Created on: <22-Jan-2003 16:08:22 amos>
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

/*! \file ezcollaborationparticipantlink.php
*/

/*!
  \class eZCollaborationItemParticipantLink ezcollaborationitemparticipantlink.php
  \brief The class eZCollaborationItemParticipantLink does

*/

define( "EZ_COLLABORATION_PARTICIPANT_TYPE_USER", 1 );
define( "EZ_COLLABORATION_PARTICIPANT_TYPE_USERGROUP", 2 );

include_once( 'kernel/classes/ezpersistentobject.php' );

class eZCollaborationItemParticipantLink extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationItemParticipantLink( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'collaboration_id' => 'CollaborationID',
                                         'participant_id' => 'ParticipantID',
                                         'participant_type' => 'ParticipantType',
                                         'is_read' => 'IsRead',
                                         'is_active' => 'IsActive',
                                         'created' => 'Created',
                                         'modified' => 'Modified' ),
                      'keys' => array( 'collaboration_id', 'participant_id' ),
                      'class_name' => 'eZCollaborationItemParticipantLink',
                      'name' => 'ezcollab_item_participant_link' );
    }

    function &create( $collaborationID, $participantID, $participantType = EZ_COLLABORATION_PARTICIPANT_TYPE_USER )
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $date_time = eZDateTime::currentTimeStamp();
        $row = array(
            'collaboration_id' => $collaborationID,
            'participant_id' => $participantID,
            'participant_type' => $participantType,
            'is_read' => false,
            'is_active' => true,
            'created' => $date_time,
            'modified' => $date_time );
        return new eZCollaborationItemParticipantLink( $row );
    }

    function &fetch( $collaborationID, $participantID, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZCollaborationItemParticipantLink::definition(),
                                                null,
                                                array( "collaboration_id" => $collaborationID,
                                                       'participant_id' => $participantID ),
                                                $asObject );
    }

    function &fetchParticipantList( $parameters = array() )
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
            $limitArray = array( $offset, $limit );
        return eZPersistentObject::fetchObjectList( eZCollaborationItemParticipantLink::definition(),
                                                    null,
                                                    array( "collaboration_id" => $itemID ),
                                                    null, $limitArray,
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
                if ( $this->ParticipantType == EZ_COLLABORATION_PARTICIPANT_TYPE_USER )
                {
                    include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
                    $user =& eZUser::fetch( $this->ParticipantID );
                    return $user;
                }
                else if ( $this->ParticipantType == EZ_COLLABORATION_PARTICIPANT_TYPE_USERGROUP )
                {
                    include_once( 'kernel/classes/ezcontentobject.php' );
                    return eZContentObject::fetch( $this->ParticipantID );
                }
                return null;
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
    }

    /// \privatesection
    var $CollaborationID;
    var $ParticipantID;
    var $ParticipantType;
    var $IsRead;
    var $IsActive;
    var $Created;
    var $Modified;
}

?>
