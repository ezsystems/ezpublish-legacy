<?php
//
// Definition of eZCollaborationItemParticipantLink class
//
// Created on: <22-Jan-2003 16:08:22 amos>
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

    function &definition()
    {
        return array( 'fields' => array( 'collaboration_id' => array( 'name' => 'CollaborationID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         'participant_id' => array( 'name' => 'ParticipantID',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
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
                      'class_name' => 'eZCollaborationItemParticipantLink',
                      'name' => 'ezcollab_item_participant_link' );
    }

    function &create( $collaborationID, $participantID,
                      $participantRole = EZ_COLLABORATION_PARTICIPANT_ROLE_STANDARD, $participantType = EZ_COLLABORATION_PARTICIPANT_TYPE_USER )
    {
        $dateTime = time();
        $row = array(
            'collaboration_id' => $collaborationID,
            'participant_id' => $participantID,
            'participant_role' => $participantRole,
            'participant_type' => $participantType,
            'created' => $dateTime,
            'modified' => $dateTime );
        return new eZCollaborationItemParticipantLink( $row );
    }

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
            return null;
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
            return $typeMap[$participantType];
        return null;
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
            return $roleMap[$participantRole];
        return null;
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
                return $roleNameMap[$roleID];
            return null;
        }
        else
        {
            $item =& eZCollaborationItem::fetch( $collaborationID );
            $itemHandler =& $item->handler();
            return $itemHandler->roleName( $collaborationID, $roleID );
        }
    }

    function hasAttribute( $attr )
    {
        return ( $attr == 'collaboration_item' or
                 $attr == 'participant' or
                 $attr == 'participant_type_string' or
                 $attr == 'participant_role_string' or
                 $attr == 'is_builtin_type' or
                 $attr == 'is_builtin_role' or
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
            case 'is_builtin_type':
            {
                return $this->ParticipantType < EZ_COLLABORATION_PARTICIPANT_TYPE_CUSTOM;
            } break;
            case 'is_builtin_role':
            {
                return $this->ParticipantRole < EZ_COLLABORATION_PARTICIPANT_ROLE_CUSTOM;
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
            case 'participant_type_string':
            {
                if ( $this->ParticipantType < EZ_COLLABORATION_PARTICIPANT_TYPE_CUSTOM )
                {
                    return eZCollaborationItemParticipantLink::typeString( $this->ParticipantType );
                }
                else
                {
                    $item =& eZCollaborationItem::fetch( $this->CollaborationID );
                    $itemHandler =& $item->handler();
                    $typeString = $item->attribute( 'type_identifier' ) . '_' . $itemHandler->participantTypeString( $this->ParticipantType );
                    return $typeString;
                }
                return null;
            } break;
            case 'participant_role_string':
            {
                if ( $this->ParticipantRole < EZ_COLLABORATION_PARTICIPANT_ROLE_CUSTOM )
                {
                    return eZCollaborationItemParticipantLink::roleString( $this->ParticipantRole );
                }
                else
                {
                    $item =& eZCollaborationItem::fetch( $this->CollaborationID );
                    $itemHandler =& $item->handler();
                    $roleString = $item->attribute( 'type_identifier' ) . '_' . $itemHandler->participantRoleString( $this->ParticipantRole );
                    return $roleString;
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
