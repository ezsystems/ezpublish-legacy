<?php
//
// Definition of eZCollaborationNotificationRule class
//
// Created on: <09-Jul-2003 16:36:55 amos>
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

/*! \file ezcollaborationnotificationrule.php
*/

/*!
  \class eZCollaborationNotificationRule ezcollaborationnotificationrule.php
  \brief The class eZCollaborationNotificationRule does

*/
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

class eZCollaborationNotificationRule extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationNotificationRule( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "user_id" => array( 'name' => "UserID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "collab_identifier" => array( 'name' => "CollaborationIdentifier",
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'user' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZCollaborationNotificationRule",
                      "name" => "ezcollab_notification_rule" );
    }

    function hasAttribute( $attr )
    {
        if ( $attr == 'user' )
        {
            return true;
        }
        return eZPersistentObject::hasAttribute( $attr );
    }
    function &attribute( $attr )
    {
        if ( $attr == 'user' )
        {
            return true;
        }
        return eZPersistentObject::attribute( $attr );
    }

    function &create( $collaborationIdentifier, $userID = false )
    {
        if ( !$userID )
            $userID =& eZUser::currentUserID();
        $rule =& new eZCollaborationNotificationRule( array( 'user_id' => $userID,
                                                             'collab_identifier' => $collaborationIdentifier ) );
        return $rule;
    }

//     function &fetchNodesForAddress( $email, $asObject = true )
//     {
//         $nodeIDList =& eZPersistentObject::fetchObjectList( eZCollaborationNotificationRule::definition(),
//                                                             array( 'node_id' ), array( 'address' => $email ),
//                                                             null,null,false );
//         $nodes = array();
//         if ( $asObject )
//         {
//             foreach ( $nodeIDList as $nodeRow )
//             {
//                 $nodes[] =& eZContentObjectTreeNode::fetch( $nodeRow['node_id'] );
//             }
//         }
//         else
//         {
//             foreach ( $nodeIDList as $nodeRow )
//             {
//                 $nodes[] = $nodeRow['node_id'];
//             }
//         }
//         return $nodes;
//     }

    function &fetchList( $userID = false, $asObject = true )
    {
        if ( !$userID )
            $userID =& eZUser::currentUserID();
        return eZPersistentObject::fetchObjectList( eZCollaborationNotificationRule::definition(),
                                                    null, array( 'user_id' => $userID ),
                                                    null, null, $asObject );
    }

    function &fetchItemTypeList( $collaborationIdentifier, $userIDList, $asObject = true )
    {
        if ( is_array( $collaborationIdentifier ) )
            $collaborationIdentifier = array( $collaborationIdentifier );
        return eZPersistentObject::fetchObjectList( eZCollaborationNotificationRule::definition(),
                                                    null, array( 'user_id' => array( $userIDList ),
                                                                 'collab_identifier' => $collaborationIdentifier ),
                                                    null, null, $asObject );
    }

//     function &fetchUserList( $nodeIDList )
//     {
//         $rules =& eZPersistentObject::fetchObjectList( eZCollaborationNotificationRule::definition(),
//                                                       array(), array( 'node_id' => array( $nodeIDList ) ),
//                                                       array( 'address' => 'asc' , 'use_digest' => 'desc'  ),null,
//                                                       false, false, array( array( 'operation' => 'distinct address,use_digest' ) )  );
//         return $rules;
//     }

//     function node()
//     {
//         if ( $this->Node == null )
//         {
//             $this->Node =& eZContentObjectTreeNode::fetch( $this->attribute( 'node_id' ) );
//         }
//         return $this->Node;
//     }

    function removeByIdentifier( $collaborationIdentifier, $userID = false )
    {
        if ( !$userID )
            $userID =& eZUser::currentUserID();
        eZPersistentObject::removeObject( eZCollaborationNotificationRule::definition(),
                                          array( 'collab_identifier' => $collaborationIdentifier,
                                                 'user_id' => $userID ) );
    }

//     function removeByNodeAndAddress( $address, $nodeID )
//     {
//         eZPersistentObject::removeObject( eZCollaborationNotificationRule::definition(), array( 'address' => $address,
//                                                                                                 'node_id' => $nodeID ) );
//     }
//     var $Node = null;

    /*!
     \static
     Removes all notification rules for all collaboration items for all users.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezcollab_notification_rule" );
    }
}

?>
