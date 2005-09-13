<?php
//
// Definition of eZCollaborationItemGroupLink class
//
// Created on: <22-Jan-2003 15:51:09 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \file ezcollaborationitemgrouplink.php
*/

/*!
  \class eZCollaborationItemGroupLink ezcollaborationitemgrouplink.php
  \brief The class eZCollaborationItemGroupLink does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( 'lib/ezlocale/classes/ezdatetime.php' );

class eZCollaborationItemGroupLink extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationItemGroupLink( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function definition()
    {
        return array( 'fields' => array( 'collaboration_id' => array( 'name' => 'CollaborationID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         'group_id' => array( 'name' => 'GroupID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'user_id' => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'is_read' => array( 'name' => 'IsRead',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'is_active' => array( 'name' => 'IsActive',
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
                      'keys' => array( 'collaboration_id', 'group_id', 'user_id' ),
                      'function_attributes' => array( 'user' => 'user',
                                                      'collaboration_item' => 'collaborationItem',
                                                      'collaboration_group' => 'collaborationGroup' ),
                      'class_name' => 'eZCollaborationItemGroupLink',
                      'sort' => array( 'modified' => 'asc' ),
                      'name' => 'ezcollab_item_group_link' );
    }

    function create( $collaborationID, $groupID, $userID )
    {
        $date_time = time();
        $row = array(
            'collaboration_id' => $collaborationID,
            'group_id' => $groupID,
            'is_read' => false,
            'is_active' => true,
            'last_read' => 0,
            'user_id' => $userID,
            'created' => $date_time,
            'modified' => $date_time );
        return new eZCollaborationItemGroupLink( $row );
    }

    function addItem( $groupID, $collaborationID, $userID )
    {
        $groupLink = eZCollaborationItemGroupLink::create( $collaborationID, $groupID, $userID );

        $db =& eZDB::instance();
        $db->begin();
        $groupLink->store();
        $itemStatus = eZCollaborationItemStatus::create( $collaborationID, $userID );
        $itemStatus->store();
        $db->commit();

        return $groupLink;
    }

    function fetch( $collaborationID, $groupID, $userID = false, $asObject = true )
    {
        if ( $userID == false )
            $userID == eZUser::currentUserID();
        return eZPersistentObject::fetchObject( eZCollaborationItemGroupLink::definition(),
                                                null,
                                                array( 'collaboration_id' => $collaborationID,
                                                       'group_id' => $groupID,
                                                       'user_id' => $userID ),
                                                $asObject );
    }

    function fetchList( $collaborationID, $userID = false, $asObject = true )
    {
        if ( $userID == false )
            $userID == eZUser::currentUserID();
        return eZPersistentObject::fetchObjectList( eZCollaborationItemGroupLink::definition(),
                                                    null,
                                                    array( 'collaboration_id' => $collaborationID,
                                                           'user_id' => $userID ),
                                                    null, null,
                                                    $asObject );
    }

    function &user()
    {
        if ( isset( $this->UserID ) and $this->UserID )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            $user = eZUser::fetch( $this->UserID );
        }
        else
            $user = null;
        return $user;
    }

    function &collaborationItem()
    {
        if ( isset( $this->CollaborationID ) and $this->CollaborationID )
        {
            include_once( 'kernel/classes/ezcollaborationitem.php' );
            $item = eZCollaborationItem::fetch( $this->CollaborationID, $this->UserID );
        }
        else
            $item = null;
        return $item;
    }

    function &collaborationGroup()
    {
        if ( isset( $this->GroupID ) and $this->GroupID )
        {
            include_once( 'kernel/classes/ezcollaborationitem.php' );
            $group = eZCollaborationGroup::fetch( $this->GroupID, $this->UserID );
        }
        else
            $group = null;
        return $group;
    }


    /// \privatesection
    var $CollaborationID;
    var $GroupID;
    var $UserID;
    var $Created;
    var $Modified;
}

?>
