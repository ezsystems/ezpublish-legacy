<?php
//
// Definition of eZSubtreeNotificationRule class
//
// Created on: <14-May-2003 16:20:24 sp>
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
  \class eZSubtreeNotificationRule ezsubtreenotificationrule.php
  \brief The class eZSubtreeNotificationRule does

*/
class eZSubtreeNotificationRule extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZSubtreeNotificationRule( $row )
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
                                                             'default' => '',
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '1..*' ),
                                         "use_digest" => array( 'name' => "UseDigest",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         "node_id" => array( 'name' => "NodeID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZContentObjectTreeNode',
                                                             'foreign_attribute' => 'node_id',
                                                             'multiplicity' => '1..*' ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'node' => 'node' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZSubtreeNotificationRule",
                      "name" => "ezsubtree_notification_rule" );
    }


    static function create( $nodeID, $userID, $useDigest = 0 )
    {
        $rule = new eZSubtreeNotificationRule( array( 'user_id' => $userID,
                                                      'use_digest' => $useDigest,
                                                      'node_id' => $nodeID ) );
        return $rule;
    }

    static function fetchNodesForUserID( $userID, $asObject = true )
    {
        $nodeIDList = eZPersistentObject::fetchObjectList( eZSubtreeNotificationRule::definition(),
                                                            array( 'node_id' ), array( 'user_id' => $userID ),
                                                            null,null,false );
        $nodes = array();
        if ( $asObject )
        {
            foreach ( $nodeIDList as $nodeRow )
            {
                $nodes[] = eZContentObjectTreeNode::fetch( $nodeRow['node_id'] );
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

    static function fetchList( $userID, $asObject = true, $offset = false, $limit = false )
    {
        return eZPersistentObject::fetchObjectList( eZSubtreeNotificationRule::definition(),
                                                            null, array( 'user_id' => $userID ),
                                                            null, array( 'offset' => $offset,
                                                                         'length' => $limit ), $asObject );
    }

    static function fetchListCount( $userID )
    {
        $countRes = eZPersistentObject::fetchObjectList( eZSubtreeNotificationRule::definition(),
                                                         array(),
                                                         array( 'user_id' => $userID ),
                                                         false,
                                                         null,
                                                         false,
                                                         false,
                                                         array( array( 'operation' => 'count( id )',
                                                                       'name' => 'count' ) ) );
        return $countRes[0]['count'];
    }

    /**
     * Fetch allowed subtreenotification rules based on node_id list and a
     * content object
     *
     * @param array $nodeIDList node id list for notification event
     * @param eZContentObject content object to add
     *
     * @return array matching subtree notification rule data
     **/
    static function fetchUserList( $nodeIDList, $contentObject )
    {
        if ( count( $nodeIDList ) == 0 )
        {
            $retValue = array();
            return $retValue;
        }

        $db = eZDB::instance();
        $concatString = $db->concatString(  array( 'user_tree.path_string', "'%'" ) );

        // Select affected users
        $sqlINString = $db->generateSQLINStatement( $nodeIDList, 'subtree_rule.node_id', false, false, 'int' );
        $sql = "SELECT DISTINCT subtree_rule.user_id,
                                user_node.node_id
                FROM ezsubtree_notification_rule subtree_rule,
                     ezcontentobject_tree user_node,
                     ezuser_setting
                WHERE $sqlINString AND
                      user_node.contentobject_id = subtree_rule.user_id AND
                      ezuser_setting.user_id = subtree_rule.user_id AND
                      user_node.is_invisible = 0 AND
                      ezuser_setting.is_enabled = 1";
        $userPart = $db->arrayQuery( $sql );

        // Remove duplicates
        $userNodeIDList = array();
        foreach ( $userPart as $row )
            $userNodeIDList[] = $row['node_id'];
        $userNodeIDList = array_unique( $userNodeIDList );

        if ( count( $userNodeIDList ) == 0 )
        {
            $retValue = array();
            return $retValue;
        }

        // Select affected nodes
        $sqlINString = $db->generateSQLINstatement( $userNodeIDList, 'user_node.node_id', false, false, 'int' );
        $sql = "SELECT DISTINCT user_node.node_id,
                                user_node.path_string,
                                user_tree.contentobject_id
                FROM ezcontentobject_tree user_node,
                     ezcontentobject_tree user_tree
                WHERE $sqlINString AND
                      user_node.path_string LIKE $concatString";
        $nodePart = $db->arrayQuery( $sql );

        // Remove duplicates
        $objectIDList = array();
        foreach ( $nodePart as $row )
            if ( $row['contentobject_id'] != '0' )
                $objectIDList[] = $row['contentobject_id'];
        $objectIDList = array_unique( $objectIDList );

        if ( count( $objectIDList ) == 0 )
        {
            $retValue = array();
            return $retValue;
        }

        // Select affected roles and policies
        $sqlINString = $db->generateSQLINStatement( $objectIDList, 'user_role.contentobject_id', false, false, 'int' );
        $sql = "SELECT DISTINCT user_role.contentobject_id,
                                policy.id AS policy_id,
                                user_role.limit_identifier AS limitation,
                                user_role.limit_value AS value
                FROM ezuser_role user_role,
                     ezpolicy policy
                WHERE $sqlINString AND
                      ( user_role.role_id=policy.role_id AND
                        ( policy.module_name='*' OR
                          ( policy.module_name='content' AND
                            ( policy.function_name='*' OR
                              policy.function_name='read'
                            )
                          )
                        )
                      )";
        $rolePart = $db->arrayQuery( $sql );

        // Build resultArray. Make sure there are no duplicates.
        $resultArray = array();
        foreach ( $userPart as $up )
        {
            foreach ( $nodePart as $np )
            {
                if ( $up['node_id'] == $np['node_id'] )
                {
                    foreach ( $rolePart as $rp )
                    {
                        if ( $np['contentobject_id'] == $rp['contentobject_id'] )
                        {
                            $key = $rp['policy_id'] . $up['user_id'] . $rp['limitation'] . $rp['value'];
                            $resultArray[$key] = array( 'policy_id' => $rp['policy_id'],
                                                        'user_id' => $up['user_id'],
                                                        'limitation' => $rp['limitation'],
                                                        'value' => $rp['value'] );
                        }
                    }
                }
            }
        }

        $policyIDArray = array();
        $limitedPolicyIDArray = array();
        $userIDArray = array();
        foreach( $resultArray as $result )
        {
            $userIDArray[(string)$result['user_id']] = (int)$result['user_id'];
        }

        foreach( $resultArray as $result )
        {
            if ( $result['limitation'] == '' )
            {
                $policyIDArray[(string)$result['policy_id']][] =& $userIDArray[(string)$result['user_id']];
            }
            else
            {
                $limitedPolicyIDArray[] = array( 'user_id' => $userIDArray[(string)$result['user_id']],
                                                 'limitation' => $result['limitation'],
                                                 'value' => $result['value'],
                                                 'policyID' => $result['policy_id'] );
            }
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
            $acceptedUserArray = array_merge( $acceptedUserArray, $userArray );

            foreach ( $userArray as $userID )
            {
                $userIDArray[(string)$userID] = false;
            }
        }

        foreach( $limitedPolicyIDArray as $policyEntry )
        {
            if ( $policyEntry['user_id'] === false )
            {
                continue;
            }

            $userArray = eZSubtreeNotificationRule::checkObjectAccess( $contentObject,
                                                                       $policyEntry['policyID'],
                                                                       array( $policyEntry['user_id'] ),
                                                                       array( $policyEntry['limitation'] => $policyEntry['value'] ) );

            $acceptedUserArray = array_merge( $acceptedUserArray, $userArray );
            foreach ( $userArray as $userID )
            {
                $userIDArray[(string)$userID] = false;
            }
        }
        $acceptedUserArray = array_unique( $acceptedUserArray );

        foreach( array_keys( $acceptedUserArray ) as $key )
        {
            if ( !is_int( $acceptedUserArray[$key] ) or $acceptedUserArray[$key] == 0 )
            {
                unset( $acceptedUserArray[$key] );
            }
        }

        if ( count( $acceptedUserArray ) == 0 )
        {
            $retValue = array();
            return $retValue;
        }

        $nodeIDWhereString = $db->generateSQLINStatement( $nodeIDList, 'rule.node_id', false, false, 'int' );
        $userIDWhereString = $db->generateSQLINStatement( $acceptedUserArray, 'rule.user_id', false, false, 'int' );
        $rules = $db->arrayQuery( "SELECT rule.user_id, rule.use_digest, ezuser.email as address
                                      FROM ezsubtree_notification_rule rule, ezuser
                                      WHERE rule.user_id=ezuser.contentobject_id AND
                                            $nodeIDWhereString AND
                                            $userIDWhereString" );
        return $rules;
    }

    /*!
     \private

     Check access for specified policy on object, and user list.

     \param Content object
     \param policyID
     \param userID array
     \param user limits

     \return array of user ID's which has access to object
    */
    static function checkObjectAccess( $contentObject, $policyID, $userIDArray, $userLimits = false )
    {
        $policy = eZPolicy::fetch( $policyID );
        if ( $userLimits )
        {
            reset( $userLimits );
            $policy->setAttribute( 'limit_identifier', 'User_' . key( $userLimits ) );
            $policy->setAttribute( 'limit_value', current( $userLimits ) );
        }

        $limitationArray = $policy->accessArray();
        $limitationArray = current( current( $limitationArray ) );
        $accessUserIDArray = $userIDArray;

        if ( isset( $limitationArray['*'] ) &&
             $limitationArray['*'] == '*' )
        {
            $returnArray = array();
            foreach ( $accessUserIDArray as $userID )
            {
                $returnArray[] = $userID;
            }
            return $returnArray;
        }

        $limitationArray = current( $limitationArray );

        $user = eZUser::currentUser();
        $classID = $contentObject->attribute( 'contentclass_id' );
        $nodeArray = $contentObject->attribute( 'assigned_nodes' );

        if ( isset( $limitationArray['Subtree' ] ) )
        {
            $checkedSubtree = false;
        }
        else
        {
            $checkedSubtree = true;
            $nodeSubtree = true;
        }
        if ( isset( $limitationArray['Node'] ) )
        {
            $checkedNode = false;
        }
        else
        {
            $checkedNode = true;
            $nodeLimit = true;
        }

        foreach ( array_keys( $limitationArray ) as $key )
        {
            if ( count( $accessUserIDArray ) == 0 )
            {
                return array();
            }
            switch( $key )
            {
                case 'Class':
                {
                    if ( !in_array( $contentObject->attribute( 'contentclass_id' ), $limitationArray[$key] )  )
                    {
                        return array();
                    }
                } break;

                case 'ParentClass':
                {

                    if ( !in_array( $contentObject->attribute( 'contentclass_id' ), $limitationArray[$key]  ) )
                    {
                        return array();
                    }
                } break;

                case 'Section':
                case 'User_Section':
                {
                    if ( !in_array( $contentObject->attribute( 'section_id' ), $limitationArray[$key]  ) )
                    {
                        return array();
                    }
                } break;

                case 'Owner':
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
                        return array();
                    }
                } break;

                case 'Node':
                {
                    $nodeLimit = true;
                    foreach ( $nodeArray as $node )
                    {
                        if( in_array( $node->attribute( 'node_id' ), $limitationArray[$key] ) )
                        {
                            $nodeLimit = false;
                            break;
                        }
                    }
                    if ( $nodeLimit && $checkedSubtree && $nodeSubtree )
                    {
                        return array();
                    }
                    $checkedNode = true;
                } break;

                case 'Subtree':
                {
                    $nodeSubtree = true;
                    foreach ( $nodeArray as $node )
                    {
                        $path = $node->attribute( 'path_string' );
                        $subtreeArray = $limitationArray[$key];
                        $validSubstring = false;
                        foreach ( $subtreeArray as $subtreeString )
                        {
                            if ( strstr( $path, $subtreeString ) )
                            {
                                $nodeSubtree = false;
                                break;
                            }
                        }
                        if ( !$nodeSubtree )
                        {
                            break;
                        }
                    }
                    if ( $nodeSubtree && $checkedNode && $nodeLimit )
                    {
                        return array();
                    }
                    $checkedSubtree = true;
                } break;

                case 'User_Subtree':
                {
                    $userSubtreeLimit = true;
                    foreach ( $nodeArray as $node )
                    {
                        $path = $node->attribute( 'path_string' );
                        $subtreeArray = $limitationArray[$key];
                        $validSubstring = false;
                        foreach ( $subtreeArray as $subtreeString )
                        {
                            if ( strstr( $path, $subtreeString ) )
                            {
                                $userSubtreeLimit = false;
                                break;
                            }
                        }
                        if ( !$userSubtreeLimit )
                        {
                            break;
                        }
                    }
                    if ( $userSubtreeLimit )
                    {
                        return array();
                    }
                } break;
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
            $this->Node = eZContentObjectTreeNode::fetch( $this->attribute( 'node_id' ) );
        }
        return $this->Node;
    }

    static function removeByNodeAndUserID( $userID, $nodeID )
    {
        eZPersistentObject::removeObject( eZSubtreeNotificationRule::definition(), array( 'user_id' => $userID,
                                                                                          'node_id' => $nodeID ) );
    }

    /*!
     \static

     Remove notifications by user id

     \param userID
    */
    static function removeByUserID( $userID )
    {
        eZPersistentObject::removeObject( eZSubtreeNotificationRule::definition(), array( 'user_id' => $userID ) );
    }

    /*!
     \static
     Cleans up all notification rules for all users.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM ezsubtree_notification_rule" );
    }

    public $Node = null;
}

?>