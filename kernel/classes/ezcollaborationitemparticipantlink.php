<?php
//
// Definition of eZCollaborationItemParticipantLink class
//
// Created on: <22-Jan-2003 16:08:22 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezcollaborationparticipantlink.php
*/

/*!
  \class eZCollaborationItemParticipantLink ezcollaborationitemparticipantlink.php
  \brief The class eZCollaborationItemParticipantLink does

*/

define( "EZ_COLLABORATION_PARTICIPANT_TYPE_USER", 1 );
define( "EZ_COLLABORATION_PARTICIPANT_TYPE_USERGROUP", 2 );

// Everything from 1024 and above is considered custom and is specific per collaboration handler.
define( "EZ_COLLABORATION_PARTICIPANT_TYPE_CUSTOM", 1024 );

define( "EZ_COLLABORATION_PARTICIPANT_ROLE_STANDARD", 1 );
define( "EZ_COLLABORATION_PARTICIPANT_ROLE_OBSERVER", 2 );
define( "EZ_COLLABORATION_PARTICIPANT_ROLE_OWNER", 3 );
define( "EZ_COLLABORATION_PARTICIPANT_ROLE_APPROVER", 4 );
define( "EZ_COLLABORATION_PARTICIPANT_ROLE_AUTHOR", 5 );

// Everything from 1024 and above is considered custom and is specific per collaboration handler.
define( "EZ_COLLABORATION_PARTICIPANT_ROLE_CUSTOM", 1024 );

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

    function definition()
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

    function &create( $collaborationID, $participantID,
                      $participantRole = EZ_COLLABORATION_PARTICIPANT_ROLE_STANDARD, $participantType = EZ_COLLABORATION_PARTICIPANT_TYPE_USER )
    {
        $dateTime = time();
        $row = array(   'collaboration_id' => $collaborationID,
                        'participant_id' => $participantID,
                        'participant_role' => $participantRole,
                        'participant_type' => $participantType,
                        'created' => $dateTime,
                        'modified' => $dateTime );
        $newCollaborationItemParticipantLink = new eZCollaborationItemParticipantLink( $row );
        return $newCollaborationItemParticipantLink;
    }

    /*!
     \note transaction unsafe
     */
    function setLastRead( $collaborationID, $userID = false, $timestamp = false )
    {
        if ( $userID === false )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            $userID = eZUser::currentUserID();
        }
        if ( $timestamp === false )
        {
            $timestamp = time();
        }
        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();
        $userID = (int) $userID;
        $timestamp = (int) $timestamp;
        $sql = "UPDATE ezcollab_item_participant_link set last_read='$timestamp'
                WHERE  collaboration_id='$collaborationID' AND participant_id='$userID'";
        $db->query( $sql );
        $collabLink =& $GLOBALS["eZCollaborationItemParticipantLinkCache"][$collaborationID][$userID];
        if ( isset( $collabLink ) )
            $collabLink->setAttribute( 'last_read', $timestamp );
    }

    function &fetch( $collaborationID, $participantID, $asObject = true )
    {
        $collabLink =& $GLOBALS["eZCollaborationItemParticipantLinkCache"][$collaborationID][$participantID];
        if ( isset( $collabLink ) )
            return $collabLink;
        $collabLink = eZPersistentObject::fetchObject( eZCollaborationItemParticipantLink::definition(),
                                                       null,
                                                       array( "collaboration_id" => $collaborationID,
                                                              'participant_id' => $participantID ),
                                                       $asObject );
        return $collabLink;
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
        $linkList = null;
        if ( !$offset and !$limit )
        {
            $linkList =& $GLOBALS['eZCollaborationItemParticipantLinkListCache'];
            if ( isset( $linkList ) )
                return $linkList;
        }
        $limitArray = null;
        if ( $offset and $limit )
            $limitArray = array( 'offset' => $offset, 'length' => $limit );
        $linkList = eZPersistentObject::fetchObjectList( eZCollaborationItemParticipantLink::definition(),
                                                          null,
                                                          array( "collaboration_id" => $itemID ),
                                                          null, $limitArray,
                                                          $asObject );
        for ( $i = 0; $i < count( $linkList ); ++$i )
        {
            $linkItem =& $linkList[$i];
            if ( $asObject )
                $participantID =& $linkItem->attribute( 'participant_id' );
            else
                $participantID =& $linkItem['participant_id'];
            if ( !isset( $GLOBALS["eZCollaborationItemParticipantLinkCache"][$itemID][$participantID] ) )
                $GLOBALS["eZCollaborationItemParticipantLinkCache"][$itemID][$participantID] =& $linkList[$i];
        }
        return $linkList;
    }

    function &fetchParticipantMap( $originalParameters = array() )
    {
        $parameters = array_merge( array( 'sort_field' => 'role' ),
                                   $originalParameters );
        $itemID = $parameters['item_id'];
        $sortField = $parameters['sort_field'];
        $list =& eZCollaborationItemParticipantLink::fetchParticipantList( $originalParameters );
        if ( $list === null )
        {
            $listMap = null;
            return $listMap;
        }

        $listKeys = array_keys( $list );
        $listMap = array();
        foreach ( $listKeys as $listKey )
        {
            $listItem =& $list[$listKey];
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
                $listMap[$sortKey]['items'][] =& $listItem;
            }
        }
        return $listMap;
    }

    function &typeString( $participantType )
    {
        $typeMap =& $GLOBALS['eZCollaborationParticipantTypeMap'];
        if ( !isset( $typeMap ) )
        {
            $typeMap = array( EZ_COLLABORATION_PARTICIPANT_TYPE_USER => 'user',
                              EZ_COLLABORATION_PARTICIPANT_TYPE_USERGROUP => 'usergroup' );
        }
        if ( isset( $typeMap[$participantType] ) )
            $retString = $typeMap[$participantType];
        else
            $retString = null;
        return $retString;
    }

    function &roleString( $participantRole )
    {
        $roleMap =& $GLOBALS['eZCollaborationParticipantRoleMap'];
        if ( !isset( $roleMap ) )
        {
            $roleMap = array( EZ_COLLABORATION_PARTICIPANT_ROLE_STANDARD => 'standard',
                              EZ_COLLABORATION_PARTICIPANT_ROLE_OBSERVER => 'observer',
                              EZ_COLLABORATION_PARTICIPANT_ROLE_OWNER => 'owner',
                              EZ_COLLABORATION_PARTICIPANT_ROLE_APPROVER => 'approver',
                              EZ_COLLABORATION_PARTICIPANT_ROLE_AUTHOR => 'author' );
        }
        if ( isset( $roleMap[$participantRole] ) )
            $retString = $roleMap[$participantRole];
        else
            $retString = null;
        return $retString;
    }

    function &roleName( $collaborationID, $roleID )
    {
        if ( $roleID < EZ_COLLABORATION_PARTICIPANT_TYPE_CUSTOM )
        {
            $roleNameMap =& $GLOBALS['eZCollaborationParticipantRoleNameMap'];
            if ( !isset( $roleNameMap ) )
            {
                include_once( 'kernel/common/i18n.php' );
                $roleNameMap = array( EZ_COLLABORATION_PARTICIPANT_ROLE_STANDARD => ezi18n( 'kernel/classes', 'Standard' ),
                                      EZ_COLLABORATION_PARTICIPANT_ROLE_OBSERVER => ezi18n( 'kernel/classes', 'Observer' ),
                                      EZ_COLLABORATION_PARTICIPANT_ROLE_OWNER => ezi18n( 'kernel/classes', 'Owner' ),
                                      EZ_COLLABORATION_PARTICIPANT_ROLE_APPROVER => ezi18n( 'kernel/classes', 'Approver' ),
                                      EZ_COLLABORATION_PARTICIPANT_ROLE_AUTHOR => ezi18n( 'kernel/classes', 'Author' ) );
            }
            if ( isset( $roleNameMap[$roleID] ) )
                $retRoleName = $roleNameMap[$roleID];
            else
                $retRoleName = null;
        }
        else
        {
            $item = eZCollaborationItem::fetch( $collaborationID );
            $itemHandler =& $item->handler();
            $retRoleName = $itemHandler->roleName( $collaborationID, $roleID );
        }
        return $retRoleName;
    }

    function &collaborationItem()
    {
        include_once( 'kernel/classes/ezcollaborationitem.php' );
        $item = eZCollaborationItem::fetch( $this->CollaborationID );
        return $item;
    }

    function &participant()
    {
        if ( $this->ParticipantType == EZ_COLLABORATION_PARTICIPANT_TYPE_USER )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            $participant = eZUser::fetch( $this->ParticipantID );
        }
        else if ( $this->ParticipantType == EZ_COLLABORATION_PARTICIPANT_TYPE_USERGROUP )
        {
            include_once( 'kernel/classes/ezcontentobject.php' );
            $participant =& eZContentObject::fetch( $this->ParticipantID );
        }
        else
        {
            $participant = null;
        }
        return $participant;
    }

    function &participantTypeString()
    {
        if ( $this->ParticipantType < EZ_COLLABORATION_PARTICIPANT_TYPE_CUSTOM )
        {
            $typeString =& eZCollaborationItemParticipantLink::typeString( $this->ParticipantType );
        }
        else
        {
            $item = eZCollaborationItem::fetch( $this->CollaborationID );
            $itemHandler =& $item->handler();
            $typeString = $item->attribute( 'type_identifier' ) . '_' . $itemHandler->participantTypeString( $this->ParticipantType );
        }
        return $typeString;
    }

    function &participantRoleString()
    {
        if ( $this->ParticipantRole < EZ_COLLABORATION_PARTICIPANT_ROLE_CUSTOM )
        {
            $roleString =& eZCollaborationItemParticipantLink::roleString( $this->ParticipantRole );
        }
        else
        {
            $item = eZCollaborationItem::fetch( $this->CollaborationID );
            $itemHandler =& $item->handler();
            $roleString = $item->attribute( 'type_identifier' ) . '_' . $itemHandler->participantRoleString( $this->ParticipantRole );
        }
        return $roleString;
    }

    function &isBuiltinType()
    {
        $isBuiltinType = $this->ParticipantType < EZ_COLLABORATION_PARTICIPANT_TYPE_CUSTOM;
        return $isBuiltinType;
    }

    function &isBuiltinRole()
    {
        $isBuiltinRole = $this->ParticipantRole < EZ_COLLABORATION_PARTICIPANT_ROLE_CUSTOM;
        return $isBuiltinRole;
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
