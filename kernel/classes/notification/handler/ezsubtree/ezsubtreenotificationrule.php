<?php
//
// Definition of eZSubtreeNotificationRule class
//
// Created on: <14-May-2003 16:20:24 sp>
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

/*! \file ezsubtreenotificationrule.php
*/

/*!
  \class eZSubtreeNotificationRule ezsubtreenotificationrule.php
  \brief The class eZSubtreeNotificationRule does

*/
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

class eZSubtreeNotificationRule extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZSubtreeNotificationRule( $row )
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
                                                             'default' => '',
                                                             'required' => true ),
                                         "use_digest" => array( 'name' => "UseDigest",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         "node_id" => array( 'name' => "NodeID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'node' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZSubtreeNotificationRule",
                      "name" => "ezsubtree_notification_rule" );
    }

    function hasAttribute( $attr )
    {
        if ( $attr == 'node' )
        {
            return true;
        }
        return eZPersistentObject::hasAttribute( $attr );
    }
    function &attribute( $attr )
    {
        if ( $attr == 'node' )
        {
            return $this->node();
        }
        return eZPersistentObject::attribute( $attr );
    }

    function &create( $nodeID, $userID, $useDigest = 0 )
    {
        $rule =& new eZSubtreeNotificationRule( array( 'user_id' => $userID,
                                                        'use_digest' => $useDigest,
                                                        'node_id' => $nodeID ) );
        return $rule;
    }

    function &fetchNodesForUserID( $userID, $asObject = true )
    {
        $nodeIDList =& eZPersistentObject::fetchObjectList( eZSubtreeNotificationRule::definition(),
                                                            array( 'node_id' ), array( 'user_id' => $userID ),
                                                            null,null,false );
        $nodes = array();
        if ( $asObject )
        {
            foreach ( $nodeIDList as $nodeRow )
            {
                $nodes[] =& eZContentObjectTreeNode::fetch( $nodeRow['node_id'] );
            }
        }
        else
        {
            foreach ( $nodeIDList as $nodeRow )
            {
                $nodes[] = $nodeRow['node_id'];
            }
        }
        return $nodes;
    }

    function &fetchList( $userID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZSubtreeNotificationRule::definition(),
                                                    null, array( 'user_id' => $userID ),
                                                    null,null,true );
    }

    /*!
      \return an array of arrays with user_id, address and use_digest
    */
    function &fetchUserList( $nodeIDList )
    {
        $db =& eZDB::instance();

        $rules = array();
        if ( count( $nodeIDList ) > 0 )
        {
            $nodeIDWhereString = implode( ',', $nodeIDList );
            $rules =& $db->arrayQuery( "SELECT rule.user_id, rule.use_digest, ezuser.email as address
                                        FROM ezsubtree_notification_rule as rule, ezuser
                                        WHERE rule.user_id=ezuser.contentobject_id AND rule.node_id IN ( $nodeIDWhereString )" );
        }

        /*
        $rules =& eZPersistentObject::fetchObjectList( eZSubtreeNotificationRule::definition(),
                                                      array(), array( 'node_id' => array( $nodeIDList ) ),
                                                      array( 'user_id' => 'asc' , 'use_digest' => 'desc'  ),null,
                                                      false, false, array( array( 'operation' => 'distinct address,use_digest' ) )  );
        */
        return $rules;
    }

    function node()
    {
        if ( $this->Node == null )
        {
            $this->Node =& eZContentObjectTreeNode::fetch( $this->attribute( 'node_id' ) );
        }
        return $this->Node;
    }

    function removeByNodeAndUserID( $userID, $nodeID )
    {
        eZPersistentObject::removeObject( eZSubtreeNotificationRule::definition(), array( 'user_id' => $userID,
                                                                                          'node_id' => $nodeID ) );
    }

    /*!
     \static

     Remove notifications by user id

     \param userID
    */
    function removeByUserID( $userID )
    {
        eZPersistentObject::removeObject( eZSubtreeNotificationRule::definition(), array( 'user_id' => $userID ) );
    }

    /*!
     \static
     Cleans up all notification rules for all users.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezsubtree_notification_rule" );
    }

    var $Node = null;
}

?>
