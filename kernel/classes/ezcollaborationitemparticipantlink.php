<?php
//
// Definition of eZCollaborationItemParticipantLink class
//
// Created on: <22-Jan-2003 16:08:22 amos>
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
  \class eZCollaborationItemParticipantLink ezcollaborationitemparticipantlink.php
  \brief The class eZCollaborationItemParticipantLink does

*/

class eZCollaborationItemParticipantLink extends eZPersistentObject
{
    const TYPE_USER = 1;
    const TYPE_USERGROUP = 2;

    // Everything from 1024 and above is considered custom and is specific per collaboration handler.
    const TYPE_CUSTOM = 1024;

    const ROLE_STANDARD = 1;
    const ROLE_OBSERVER = 2;
    const ROLE_OWNER = 3;
    const ROLE_APPROVER = 4;
    const ROLE_AUTHOR = 5;

    // Everything from 1024 and above is considered custom and is specific per collaboration handler.
    const ROLE_CUSTOM = 1024;

    /*!
     Constructor
    */
    function eZCollaborationItemParticipantLink( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'collaboration_id' => array( 'name' => 'CollaborationID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true,
                                                                      'foreign_class' => 'eZCollaborationItem',
                                                                      'foreign_attribute' => 'id',
                                                                      'multiplicity' => '1..*' ),
                                         'participant_id' => array( 'name' => 'ParticipantID',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true,
                                                                    'foreign_class' => 'eZContentObject',
                                                                    'foreign_attribute' => 'id',
                                                                    'multiplicity' => '1..*' ),
                                         'participant_type' => array( 'name' => 'ParticipantType',
                                                                      'datatype' => 'integer',
                                                                      'default' => 1,
                                                                      'required' => true ),
                                         'participant_role' => array( 'name' => 'ParticipantRole',
                                                                      'datatype' => 'integer',
                                                                      'default' => 1,
                                                                      'required' => true ),
                                         'last_read' => array( 'name' => 'LastRead',
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
                      'keys' => array( 'collaboration_id', 'participant_id' ),
                      'function_attributes' => array( 'collaboration_item' => 'collaborationItem',
                                                      'participant' => 'participant',
                                                      'participant_type_string' => 'participantTypeString',
                                                      'participant_role_string' => 'participantRoleString',
                                                      'is_builtin_type' => 'isBuiltinType',
                                                      'is_builtin_role' => 'isBuiltinRole' ),
                      'class_name' => 'eZCollaborationItemParticipantLink',
                      'name' => 'ezcollab_item_participant_link' );
    }

    static function create( $collaborationID, $participantID,
                      $participantRole = self::ROLE_STANDARD, $participantType = self::TYPE_USER )
    {
        $dateTime = time();
        $row = array(   'collaboration_id' => $collaborationID,
                        'participant_id' => $participantID,
                        'participant_role' => $participantRole,
                        'participant_type' => $participantType,
                        'created' => $dateTime,
                        'modified' => $dateTime );
        return new eZCollaborationItemParticipantLink( $row );
    }

    /*!
     \note transaction unsafe
     */
    static function setLastRead( $collaborationID, $userID = false, $timestamp = false )
    {
        if ( $userID === false )
        {
            $userID = eZUser::currentUserID();
        }
        if ( $timestamp === false )
        {
            $timestamp = time();
        }
        $db = eZDB::instance();
        $userID = (int) $userID;
        $timestamp = (int) $timestamp;
        $sql = "UPDATE ezcollab_item_participant_link set last_read='$timestamp'
                WHERE  collaboration_id='$collaborationID' AND participant_id='$userID'";
        $db->query( $sql );
        if ( !empty( $GLOBALS["eZCollaborationItemParticipantLinkCache"][$collaborationID][$userID] ) )
            $GLOBALS["eZCollaborationItemParticipantLinkCache"][$collaborationID][$userID]->setAttribute( 'last_read', $timestamp );
    }

    static function fetch( $collaborationID, $participantID, $asObject = true )
    {
        if ( empty( $GLOBALS["eZCollaborationItemParticipantLinkCache"][$collaborationID][$participantID] ) )
        {
            $GLOBALS["eZCollaborationItemParticipantLinkCache"][$collaborationID][$participantID] =
                eZPersistentObject::fetchObject( eZCollaborationItemParticipantLink::definition(),
                                                 null,
                                                 array( "collaboration_id" => $collaborationID,
                                                        'participant_id' => $participantID ),
                                                 $asObject );
        }
        return $GLOBALS["eZCollaborationItemParticipantLinkCache"][$collaborationID][$participantID];
    }

    static function fetchParticipantList( $parameters = array() )
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
        $linkList = null;
        if ( !$offset and !$limit )
        {
            if ( !empty( $GLOBALS['eZCollaborationItemParticipantLinkListCache'] ) )
            {
                return $GLOBALS['eZCollaborationItemParticipantLinkListCache'];
            }
        }
        $limitArray = null;
        if ( $offset and $limit )
            $limitArray = array( 'offset' => $offset, 'length' => $limit );
        $linkList = eZPersistentObject::fetchObjectList( eZCollaborationItemParticipantLink::definition(),
                                                          null,
                                                          array( "collaboration_id" => $itemID ),
                                                          null, $limitArray,
                                                          $asObject );
        foreach( $linkList as $linkItem )
        {
            if ( $asObject )
            {
                $participantID = $linkItem->attribute( 'participant_id' );
            }
            else
            {
                $participantID = $linkItem['participant_id'];
            }
            if ( empty( $GLOBALS["eZCollaborationItemParticipantLinkCache"][$itemID][$participantID] ) )
            {
                $GLOBALS["eZCollaborationItemParticipantLinkCache"][$itemID][$participantID] = $linkItem;
            }
        }
        return $GLOBALS['eZCollaborationItemParticipantLinkListCache'] = $linkList;
    }

    static function fetchParticipantMap( $originalParameters = array() )
    {
        $parameters = array_merge( array( 'sort_field' => 'role' ),
                                   $originalParameters );
        $itemID = $parameters['item_id'];
        $sortField = $parameters['sort_field'];
        $list = eZCollaborationItemParticipantLink::fetchParticipantList( $originalParameters );
        if ( $list === null )
        {
            $listMap = null;
            return $listMap;
        }

        $listMap = array();
        foreach ( $list as $listItem )
        {
            $sortKey = null;
            if ( $sortField == 'role' )
            {
                $sortKey = $listItem->attribute( 'participant_role' );
            }
            if ( $sortKey !== null )
            {
                if ( !isset( $listMap[$sortKey] ) )
                {
                    if ( $sortField == 'role' )
                    {
                        $sortName = eZCollaborationItemParticipantLink::roleName( $itemID, $sortKey );
                    }
                    $listMap[$sortKey] = array( 'name' => $sortName,
                                                'items' => array() );
                }
                $listMap[$sortKey]['items'][] = $listItem;
            }
        }
        return $listMap;
    }

    static function typeString( $participantType )
    {
        if ( !isset( $GLOBALS['eZCollaborationParticipantTypeMap'] ) )
        {
            $GLOBALS['eZCollaborationParticipantTypeMap'] = array( self::TYPE_USER => 'user',
                                                                   self::TYPE_USERGROUP => 'usergroup' );
        }
        if ( isset( $GLOBALS['eZCollaborationParticipantTypeMap'][$participantType] ) )
        {
            return $GLOBALS['eZCollaborationParticipantTypeMap'][$participantType];
        }
        return null;
    }

    static function roleString( $participantRole )
    {
        if ( empty( $GLOBALS['eZCollaborationParticipantRoleMap'] ) )
        {
            $GLOBALS['eZCollaborationParticipantRoleMap'] =
                array( self::ROLE_STANDARD => 'standard',
                       self::ROLE_OBSERVER => 'observer',
                       self::ROLE_OWNER => 'owner',
                       self::ROLE_APPROVER => 'approver',
                       self::ROLE_AUTHOR => 'author' );
        }
        $roleMap = $GLOBALS['eZCollaborationParticipantRoleMap'];
        if ( isset( $roleMap[$participantRole] ) )
        {
            return $roleMap[$participantRole];
        }

        return null;
    }

    static function roleName( $collaborationID, $roleID )
    {
        if ( $roleID < self::TYPE_CUSTOM )
        {
            if ( empty( $GLOBALS['eZCollaborationParticipantRoleNameMap'] ) )
            {
                
                $GLOBALS['eZCollaborationParticipantRoleNameMap'] =
                    array( self::ROLE_STANDARD => ezpI18n::tr( 'kernel/classes', 'Standard' ),
                           self::ROLE_OBSERVER => ezpI18n::tr( 'kernel/classes', 'Observer' ),
                           self::ROLE_OWNER => ezpI18n::tr( 'kernel/classes', 'Owner' ),
                           self::ROLE_APPROVER => ezpI18n::tr( 'kernel/classes', 'Approver' ),
                           self::ROLE_AUTHOR => ezpI18n::tr( 'kernel/classes', 'Author' ) );
            }
            $roleNameMap = $GLOBALS['eZCollaborationParticipantRoleNameMap'];
            if ( isset( $roleNameMap[$roleID] ) )
            {
                return $roleNameMap[$roleID];
            }
            return null;
        }

        $item = eZCollaborationItem::fetch( $collaborationID );
        return $item->handler()->roleName( $collaborationID, $roleID );
    }

    function collaborationItem()
    {
        return eZCollaborationItem::fetch( $this->CollaborationID );
    }

    function participant()
    {
        if ( $this->ParticipantType == self::TYPE_USER )
        {
            return eZUser::fetch( $this->ParticipantID );
        }
        else if ( $this->ParticipantType == self::TYPE_USERGROUP )
        {
            return eZContentObject::fetch( $this->ParticipantID );
        }
        return null;
    }

    function participantTypeString()
    {
        if ( $this->ParticipantType < self::TYPE_CUSTOM )
        {
            return  eZCollaborationItemParticipantLink::typeString( $this->ParticipantType );
        }

        $item = eZCollaborationItem::fetch( $this->CollaborationID );
        return $item->attribute( 'type_identifier' ) . '_' . $item->handler()->participantTypeString( $this->ParticipantType );
    }

    function participantRoleString()
    {
        if ( $this->ParticipantRole < self::ROLE_CUSTOM )
        {
            return  eZCollaborationItemParticipantLink::roleString( $this->ParticipantRole );
        }

        $item = eZCollaborationItem::fetch( $this->CollaborationID );
        return $item->attribute( 'type_identifier' ) . '_' . $item->handler()->participantRoleString( $this->ParticipantRole );
    }

    function isBuiltinType()
    {
        return $this->ParticipantType < self::TYPE_CUSTOM;
    }

    function isBuiltinRole()
    {
        return $this->ParticipantRole < self::ROLE_CUSTOM;
    }

    /// \privatesection
    public $CollaborationID;
    public $ParticipantID;
    public $ParticipantType;
    public $IsRead;
    public $IsActive;
    public $Created;
    public $Modified;
}

?>
