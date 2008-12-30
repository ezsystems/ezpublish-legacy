<?php
//
// Definition of eZCollaborationNotificationRule class
//
// Created on: <09-Jul-2003 16:36:55 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZCollaborationNotificationRule ezcollaborationnotificationrule.php
  \brief The class eZCollaborationNotificationRule does

*/
class eZCollaborationNotificationRule extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationNotificationRule( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "user_id" => array( 'name' => "UserID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '1..*' ),
                                         "collab_identifier" => array( 'name' => "CollaborationIdentifier",
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'user' => 'user' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZCollaborationNotificationRule",
                      "name" => "ezcollab_notification_rule" );
    }

    function user()
    {
        return eZUser::fetch( $this->attribute( 'user_id' ) );
    }

    static function create( $collaborationIdentifier, $userID = false )
    {
        if ( !$userID )
            $userID = eZUser::currentUserID();
        return new eZCollaborationNotificationRule( array( 'user_id' => $userID,
                                                           'collab_identifier' => $collaborationIdentifier ) );
    }

    static function fetchList( $userID = false, $asObject = true )
    {
        if ( !$userID )
            $userID = eZUser::currentUserID();
        return eZPersistentObject::fetchObjectList( eZCollaborationNotificationRule::definition(),
                                                    null, array( 'user_id' => $userID ),
                                                    null, null, $asObject );
    }

    static function fetchItemTypeList( $collaborationIdentifier, $userIDList, $asObject = true )
    {
        if ( is_array( $collaborationIdentifier ) )
            $collaborationIdentifier = array( $collaborationIdentifier );
        return eZPersistentObject::fetchObjectList( eZCollaborationNotificationRule::definition(),
                                                    null, array( 'user_id' => array( $userIDList ),
                                                                 'collab_identifier' => $collaborationIdentifier ),
                                                    null, null, $asObject );
    }

    static function removeByIdentifier( $collaborationIdentifier, $userID = false )
    {
        if ( !$userID )
            $userID = eZUser::currentUserID();
        eZPersistentObject::removeObject( eZCollaborationNotificationRule::definition(),
                                          array( 'collab_identifier' => $collaborationIdentifier,
                                                 'user_id' => $userID ) );
    }

    /*!
     \static

     Remove notifications by user id

     \param userID
    */
    static function removeByUserID( $userID )
    {
        eZPersistentObject::removeObject( eZCollaborationNotificationRule::definition(), array( 'user_id' => $userID ) );
    }

    /*!
     \static
     Removes all notification rules for all collaboration items for all users.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM ezcollab_notification_rule" );
    }
}

?>
