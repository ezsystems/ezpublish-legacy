<?php
//
// Definition of eZCollaborationItemGroupLink class
//
// Created on: <22-Jan-2003 15:51:09 amos>
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

/*! \file ezcollaborationitemgrouplink.php
*/

/*!
  \class eZCollaborationItemGroupLink ezcollaborationitemgrouplink.php
  \brief The class eZCollaborationItemGroupLink does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );

class eZCollaborationItemGroupLink extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationItemGroupLink( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'collaboration_id' => 'CollaborationID',
                                         'group_id' => 'GroupID',
                                         'user_id' => 'UserID',
                                         'created' => 'Created',
                                         'modified' => 'Modified' ),
                      'keys' => array( 'collaboration_id' ),
                      'class_name' => 'eZCollaborationItemGroupLink',
                      'sort' => array( 'modified' => 'asc' ),
                      'name' => 'ezcollab_item_group_link' );
    }

    function &create( $collaborationID, $groupID, $userID )
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $date_time = eZDateTime::currentTimeStamp();
        $row = array(
            'collaboration_id' => $collaborationID,
            'group_id' => $groupID,
            'user_id' => $userID,
            'created' => $date_time,
            'modified' => $date_time );
        return new eZCollaborationItemGroupLink( $row );
    }

    function &fetch( $collaborationID, $groupID, $userID, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZCollaborationItemGroupLink::definition(),
                                                null,
                                                array( "collaboration_id" => $collaborationID,
                                                       'group_id' => $groupID,
                                                       'user_id' => $userID ),
                                                $asObject );
    }

    function hasAttribute( $attr )
    {
        return ( $attr == 'collaboration_item' or
                 $attr == 'collaboration_group' or
                 $attr == 'user' or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'user':
            {
                include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
                return eZUser::fetch( $this->UserID );
            } break;
            case 'collaboration_item':
            {
                include_once( 'kernel/classes/ezcollaborationitem.php' );
                return eZCollaborationItem::fetch( $this->CollaborationID, $this->UserID );
            } break;
            case 'collaboration_group':
            {
                include_once( 'kernel/classes/ezcollaborationitem.php' );
                return eZCollaborationGroup::fetch( $this->GroupID, $this->UserID );
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
    }

    /// \privatesection
    var $CollaborationID;
    var $GroupID;
    var $UserID;
    var $Created;
    var $Modified;
}

?>
