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
                                         "address" => array( 'name' => "Address",
                                                             'datatype' => 'string',
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

    function &create( $nodeID, $address, $useDigest = 0 )
    {
        $rule =& new eZSubtreeNotificationRule( array( 'address' => $address,
                                                        'use_digest' => $useDigest,
                                                        'node_id' => $nodeID ) );
        return $rule;
    }

    function &fetchNodesForAddress( $email, $asObject = true )
    {
        $nodeIDList =& eZPersistentObject::fetchObjectList( eZSubtreeNotificationRule::definition(),
                                                            array( 'node_id' ), array( 'address' => $email ),
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

    function &fetchList( $email, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZSubtreeNotificationRule::definition(),
                                                    null, array( 'address' => $email ),
                                                    null,null,true );
    }

    /*!
     Fetch allowed subtreenotification rules based upon node id list and array pf nodes

     \param node id list for notification event.
     \param content object to add

     \return array of eZSubtreeNotificationRule objects
    */
    function &fetchUserList( $nodeIDList, $contentObject )
    {
        if ( count( $nodeIDList ) == 0 )
        {
            return array();
        }

        $db =& eZDB::instance();
        $concatString = $db->concatString(  array( 'user_tree.path_string', "'%'" ) );

        $sql = 'SELECT DISTINCT policy.id as policy_id, ezuser.contentobject_id as user_id, ezuser.email as email
                  FROM ezcontentobject_tree AS user_tree, ezcontentobject_tree AS user_node, ezuser_role AS user_role, ezuser,
                       ezsubtree_notification_rule AS subtree_rule, ezpolicy AS policy, ezpolicy_limitation AS limitation, ezpolicy_limitation_value AS value
                  WHERE subtree_rule.node_id IN ( ' . implode( ', ', $nodeIDList ) . ' ) AND
                        ezuser.email=subtree_rule.address AND
                        user_node.contentobject_id=ezuser.contentobject_id AND
	                    user_node.path_string like ' . $concatString . " AND
                        user_role.contentobject_id=user_tree.contentobject_id AND
                        ( user_role.role_id=policy.role_id AND ( policy.module_name='*' OR ( policy.module_name='content' AND ( policy.function_name='*' OR policy.function_name='read' ) ) ) )";

        $resultArray = $db->arrayQuery( $sql );


        $policyArray = array();
        $userIDArray = array();
        foreach( $resultArray as $result )
        {
            $userIDArray[(string)$result['user_id']] = array( 'email' => $result['email'],
                                                              'id' => (int)$result['user_id'] );
        }
        foreach( $resultArray as $result )
        {
            $policyIDArray[(string)$result['policy_id']][] =& $userIDArray[(string)$result['user_id']]['id'];
        }

        $acceptedUserArray = array();
        foreach( array_keys( $policyIDArray ) as $policyID )
        {
            foreach( array_keys( $policyIDArray[$policyID] ) as $key )
            {
                if ( $policyIDArray[$policyID][$key] === false )
                {
                    unset( $policyIDArray[$policyID][$key] );
                }
            }

            if ( count( $policyIDArray[$policyID] ) == 0 )
            {
                continue;
            }

            $userArray = eZSubtreeNotificationRule::checkObjectAccess( $contentObject, $policyID, $policyIDArray[$policyID] );
            $acceptedUserArray = array_unique( array_merge( $acceptedUserArray, $userArray ) );

            foreach ( $userArray as $userID )
            {
                $userIDArray[(string)$userID]['id'] = false;
            }
        }

        $emailArray = array();
        foreach( $acceptedUserArray as $userID )
        {
            if ( is_int( $userID ) && $userID != 0 )
            {
                $emailArray[] = $userIDArray[(string)$userID]['email'];
            }
        }

        if ( count( $emailArray ) == 0 )
        {
            return array();
        }

        $rules =& eZPersistentObject::fetchObjectList( eZSubtreeNotificationRule::definition(),
                                                       array(),
                                                       array( 'node_id' => array( $nodeIDList ),
                                                              'address' => array( $emailArray ) ),
                                                       array( 'address' => 'asc' , 'use_digest' => 'desc'  ),
                                                       null,
                                                       false,
                                                       false,
                                                       array( array( 'operation' => 'distinct address,use_digest' ) ) );
        return $rules;
    }

    /*!
     \private

     Check access for specified policy on object, and user list.

     \param Content object
     \param policyID
     \param userID array

     \return array of user ID's which has access to object
    */
    function checkObjectAccess( $contentObject, $policyID, $userIDArray )
    {
        $policy = eZPolicy::fetch( $policyID );
        $limitationArray = $policy->limitationList( false );
        $accessUserIDArray = $userIDArray;

        if ( count( $limitationArray ) == 0 )
        {
            $returnArray = array();
            foreach ( $accessUserIDArray as $userID )
            {
                $returnArray[] = $userID;
            }
            return $returnArray;
        }

        $user =& eZUser::currentUser();
        $classID = $contentObject->attribute( 'contentclass_id' );
        $nodeArray = $contentObject->attribute( 'assigned_nodes' );

        foreach ( $limitationArray as $limitation  )
        {
            if ( $limitation->attribute( 'identifier' ) == 'Class' )
            {
                if ( !in_array( $contentObject->attribute( 'contentclass_id' ), $limitation->attribute( 'values_as_array' )  )  )
                {
                    $accessUserIDArray = array();
                    break;
                }
            }
            elseif ( $limitation->attribute( 'identifier' ) == 'ParentClass' )
            {
                if ( !in_array( $contentObject->attribute( 'contentclass_id' ), $limitation->attribute( 'values_as_array' )  ) )
                {
                    $accessUserIDArray = array();
                    break;
                }
            }
            elseif ( $limitation->attribute( 'identifier' ) == 'Section' )
            {
                if ( !in_array( $contentObject->attribute( 'section_id' ), $limitation->attribute( 'values_as_array' )  ) )
                {
                    $accessUserIDArray = array();
                    break;
                }
            }
            elseif ( $limitation->attribute( 'identifier' ) == 'Owner' )
            {
                if ( in_array( $contentObject->attribute( 'owner_id' ), $userIDArray ) )
                {
                    $accessUserIDArray = array_intersect( $contentObject->attribute( 'owner_id' ), $accessUserIDArray );
                }
                else if ( in_array( $contentObject->ID, $userIDArray ) )
                {
                    $accessUserIDArray = array_intersect( $contentObject->ID, $accessUserIDArray );
                }
                else
                {
                    $accessUserIDArray = array();
                    break;
                }
            }
            elseif ( $limitation->attribute( 'identifier' ) == 'Node' )
            {
                $nodeLimit = true;
                foreach ( $nodeArray as $node )
                {
                    if( in_array( $node->attribute( 'node_id' ), $limitation->attribute( 'values_as_array' ) ) )
                    {
                        $nodeLimit = false;
                        break;
                    }
                }
                if ( $nodeLimit )
                {
                    $accessUserIDArray = array();
                    break;
                }
            }
            elseif ( $limitation->attribute( 'identifier' ) == 'Subtree' )
            {
                $nodeLimit = true;
                foreach ( $nodeArray as $node )
                {
                    $path =  $node->attribute( 'path_string' );
                    $subtreeArray = $limitation->attribute( 'values_as_array' );
                    $validSubstring = false;
                    foreach ( $subtreeArray as $subtreeString )
                    {
                        if ( strstr( $path, $subtreeString ) )
                        {
                            $nodeLimit = false;
                            break;
                        }
                    }
                    if ( !$nodeLimit )
                    {
                        break;
                    }
                }
                if ( $nodeLimit )
                {
                    $accessUserIDArray = array();
                    break;
                }
            }
        }

        $returnArray = array();
        foreach ( $accessUserIDArray as $userID )
        {
            $returnArray[] = $userID;
        }
        return $returnArray;
    }

    function node()
    {
        if ( $this->Node == null )
        {
            $this->Node =& eZContentObjectTreeNode::fetch( $this->attribute( 'node_id' ) );
        }
        return $this->Node;
    }

    function removeByNodeAndAddress( $address, $nodeID )
    {
        eZPersistentObject::removeObject( eZSubtreeNotificationRule::definition(), array( 'address' => $address,
                                                                                          'node_id' => $nodeID ) );
    }
    var $Node = null;
}

?>
