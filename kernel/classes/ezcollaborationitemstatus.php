<?php
//
// Definition of eZCollaborationItemStatus class
//
// Created on: <30-Jan-2003 13:51:22 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezcollaborationitemstatus.php
*/

/*!
  \class eZCollaborationItemStatus ezcollaborationitemstatus.php
  \brief The class eZCollaborationItemStatus does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( 'lib/ezlocale/classes/ezdatetime.php' );

class eZCollaborationItemStatus extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationItemStatus( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'collaboration_id' => array( 'name' => 'CollaborationID',
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
                                                               'required' => true ) ),
                      'keys' => array( 'collaboration_id', 'user_id' ),
                      'class_name' => 'eZCollaborationItemStatus',
                      'name' => 'ezcollab_item_status' );
    }

    function &create( $collaborationID, $userID = false )
    {
        if ( $userID === false )
            $userID =& eZUser::currentUserID();
        $row = array(
            'collaboration_id' => $collaborationID,
            'user_id' => $userID,
            'is_read' => false,
            'is_active' => true,
            'last_read' => 0 );
        $statusObject =& $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID];
        $statusObject = new eZCollaborationItemStatus( $row );
        return $statusObject;
    }

    function store()
    {
        $stored = eZPersistentObject::store();
        $this->updateCache();
        return $stored;
    }

    function updateCache()
    {
        $userID = $this->UserID;
        $collaborationID = $this->CollaborationID;
        $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID] =& $this;
    }

    function &fetch( $collaborationID, $userID = false, $asObject = true )
    {
        if ( $userID === false )
            $userID =& eZUser::currentUserID();
        $statusObject =& $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID];
        if ( isset( $statusObject ) )
            return $statusObject;
        $conditions = array( 'collaboration_id' => $id,
                             'user_id' => $userID );
        $statusObject = eZPersistentObject::fetchObject( eZCollaborationItemStatus::definition(),
                                                         null,
                                                         $conditions,
                                                         $asObject );
    }

    function setLastRead( $collaborationID, $userID = false, $timestamp = false )
    {
        if ( $timestamp === false )
            $timestamp = eZDateTime::currentTimeStamp();

        eZCollaborationItemStatus::updateFields( $collaborationID, $userID, array( 'last_read' => $timestamp,
                                                                                   'is_read' => 1 ) );
    }

    function updateFields( $collaborationID, $userID = false, $fields )
    {
        if ( $userID === false )
            $userID = eZUser::currentUserID();

        eZPersistentObject::updateObjectList( array( 'definition' => eZCollaborationItemStatus::definition(),
                                                     'update_fields' => $fields,
                                                     'conditions' => array( 'collaboration_id' => $collaborationID,
                                                                            'user_id' => $userID ) ) );
        $statusObject =& $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID];
        if ( isset( $statusObject ) )
        {
            foreach ( $fields as $field => $value )
            {
                $statusObject->setAttribute( $field, $value );
            }
        }
    }

}

?>
