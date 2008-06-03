<?php
//
// Definition of eZRole class
//
// Created on: <14-Aug-2002 14:08:46 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

/*! \file ezrole.php
*/

/*! \defgroup eZRole Role based permission system */

/*!
  \class eZRole ezrole.php
  \ingroup eZRole
  \brief A container for policies in the permission system

  It consists merely of a name() and has a DB id() and a version() number.
  The actual permissions are stored in policies and policy values
  which can be fetched with the method policyList().

  To fetch permission access array you can use accessArrayByUserID() and accessArray().

  There are multiple ways to fetch a role,
  directly from an id() with fetch(), by a role name() with fetchByName(),
  by a given user with fetchByUser() or the whole list with fetchList() and fetchByOffset().

  Creating roles is done with create(), after which new policies can be added
  using appendPolicy().

  Remove roles with remove() and its policies with removePolicies().

*/
//include_once( 'kernel/classes/ezpersistentobject.php' );
//include_once( 'lib/ezutils/classes/ezini.php' );
//include_once( "lib/ezdb/classes/ezdb.php" );

class eZRole extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZRole( $row = array() )
    {
        $this->eZPersistentObject( $row );
        $this->PolicyArray = 0;
        $this->LimitIdentifier = false;
        $this->LimitValue = false;
        if ( isset( $row['limit_identifier'] ) )
            $this->LimitIdentifier = $row['limit_identifier'];
        if ( isset( $row['limit_value'] ) )
            $this->LimitValue = $row['limit_value'];
        if ( isset( $row['user_role_id'] ) )
            $this->UserRoleID = $row['user_role_id'];
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "version" => array( 'name' => "Version",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "is_new" => array( 'name' => "IsNew",
                                                            'datatype' => 'integer',
                                                            'default' => '0',
                                                            'required' => false ) ),
                      "function_attributes" => array( "policies" => "policyList",
                                                      'limit_identifier' => 'limitIdentifier',
                                                      'limit_value' => 'limitValue',
                                                      'user_role_id' => 'userRoleID' ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZRole",
                      "name" => "ezrole" );
    }

    /*!
     Returns the limit identifier if it is set.
     \note This will only be available when fetching roles for a specific user
     \sa limitValue
    */
    function limitIdentifier()
    {
        return $this->LimitIdentifier;
    }

    /*!
     Returns the limit value if it is set.
     \note This will only be available when fetching roles for a specific user
     \sa limitIdentifier
    */
    function limitValue()
    {
        return $this->LimitValue;
    }

    /*!
     Returns the user role ID if it is set.
    \note This will only be available when fetching roles for a specific user
    \sa userRoleID
    */
    function userRoleID()
    {
        return $this->UserRoleID;
    }

    /*!
     Copies this role, stores it and returns it.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function copy()
    {
        $db = eZDB::instance();
        $db->begin();

        $newRole = eZRole::createNew();
        $this->copyPolicies( $newRole->attribute( 'id' ) );
        $newRole->setAttribute( 'name', ezi18n( 'kernel/role/edit', 'Copy of %rolename', null,
                                                array( '%rolename' => $this->attribute( 'name' ) ) ) );
        $newRole->store();
        $db->commit();
        return $newRole;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function createTemporaryVersion()
    {
        $db = eZDB::instance();
        $db->begin();

        $newRole = eZRole::createNew();
        $this->copyPolicies( $newRole->attribute( 'id' ) );
        $newRole->setAttribute( 'name', $this->attribute( 'name' ) );
        $newRole->setAttribute( 'version', $this->attribute( 'id' ) );
        $newRole->store();

        $db->commit();
        return $newRole;
    }

    /*!
     \static
     Creates a new role with the name 'New role', stores it and returns it.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function createNew()
    {
        $role = new eZRole( array( 'name' => ezi18n( 'kernel/role/edit', 'New role' ),
                                   'is_new' => 1 ) );
        $role->store();
        return $role;
    }

    /*!
     \static
     Creates a new role with the name \a $roleName and version \a $version and returns it.
     \note The role is not stored.
    */
    static function create( $roleName, $version = 0 )
    {
        $row = array( 'id' => null,
                      'name' => $roleName,
                      'version' => 0 );
        $role = new eZRole( $row );
        return $role;
    }

    /*!
     Appends a new policy to the current role and returns it.
     \note The policy and it's limitation values will be stored to the database before returning.
     \param $module Which module to give access to or \c true to give access to all modules.
     \param $function Which function to give access to or \c true to give access to all functions.
     \param $limitations An associative array with limitations and their values, use an empty array for no limitations.

     \code
     // Access to content/read
     $policy1 = $role->appendPolicy( 'content', 'read' );
     // Access to content/read in section 1
     $policy2 = $role->appendPolicy( 'content', 'read', array( 'Section' => 1 ) );
     // Access to content/read for class 2 and 5
     $policy3 = $role->appendPolicy( 'content', 'read', array( 'Class' => array( 2, 5 ) ) );
     \endcode

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function appendPolicy( $module, $function, $limitations = array() )
    {
        //include_once( 'kernel/classes/ezpolicy.php' );
        $policy = eZPolicy::create( $this->ID, $module, $function );

        $db = eZDB::instance();
        $db->begin();
        $policy->store();
        if ( count( $limitations ) > 0 )
        {
            foreach ( $limitations as $limitationIdentifier => $limitationValues )
            {
                if ( !is_array( $limitationValues ) )
                    $limitationValues = array( $limitationValues );
                $policy->appendLimitation( $limitationIdentifier, $limitationValues );
            }
        }
        $db->commit();

        if ( isset( $this->Policies ) )
        {
            $this->Policies[] = $policy;
        }
        return $policy;
    }

    /*!
     Copies all policies for this role and assigns them to the role identified by ID \a $roleID.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function copyPolicies( $roleID )
    {
        $db = eZDB::instance();
        $db->begin();
        foreach ( $this->attribute( 'policies' ) as $policy )
        {
            $policy->copy( $roleID );
        }
        $db->commit();
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function revertFromTemporaryVersion()
    {
        $temporaryVersion = eZRole::fetch( 0, $this->attribute( 'id' ) );
        if ( is_null( $temporaryVersion ) )
            return 0;
        $this->removePolicies();
        $this->setAttribute( 'name', $temporaryVersion->attribute( 'name') );
        $this->setAttribute( 'is_new', 0 );

        $db = eZDB::instance();
        $db->begin();
        $this->store();

        $query = "UPDATE  ezpolicy
                  SET role_id = " . $this->attribute( 'id' ) . "
                  WHERE role_id = " . $temporaryVersion->attribute( 'id' );
        $db->query( $query );
        $temporaryVersion->removePolicies( false );
        $temporaryVersion->remove();
        $db->commit();

        // Expire role cache
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-access-cache', time() );
        $handler->setTimestamp( 'user-info-cache', time() );
        $handler->setTimestamp( 'user-class-cache', time() );
        $handler->store();
    }

    /*!
     \static
     Removes all temporary roles and roles without policies from the database.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function removeTemporary()
    {
        $temporaryRoles = eZRole::fetchList( true );

        $db = eZDB::instance();
        $db->begin();
        foreach ( $temporaryRoles as $role )
        {
            $role->removeThis();
        }
        $db->commit();
    }

    /*!
     \static
     \sa removeThis
    */
    static function removeRole( $roleID )
    {
        if ( !isset( $roleID ) )
        {
            return 0;
        }
        return eZRole::fetch( $roleID )->removeThis();
    }

    /*!
     Removes the role, it's policies and any assignments to users/groups.
     \param $roleID If this is \c false then the function is not static and the ID is fetched from \c $this.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function removeThis()
    {
        $db = eZDB::instance();
        $db->begin();
        foreach ( $this->attribute( 'policies' ) as $policy )
        {
            $policy->removeThis();
        }
        $db->query( "DELETE FROM ezrole WHERE id='" . $db->escapeString( $this->attribute( 'id' ) ) . "'" );
        $db->query( "DELETE FROM ezuser_role WHERE role_id = '" . $db->escapeString( $this->attribute( 'id' ) ) . "'" );
        $db->commit();
    }

    /*!
     Removes the policy object list from this role.
     \param $fromDB If \c true then the policies are removed from database.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function removePolicies( $fromDB = true )
    {
        $db = eZDB::instance();
        $db->begin();
        if ( $fromDB )
        {
            foreach ( $this->attribute( 'policies' ) as $policy )
            {
                $policy->removeThis();
            }
        }
        $db->commit();
        unset( $this->Policies );
    }

    /*!
     Removes the policy object(s) by specified \a $moduleName and/or \a $functionName.
     Removes all policies for module \a $moduleName if \a $functionName is \c false.
     \param $moduleName Module name
     \param $functionName function name. Default is \c false.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function removePolicy( $moduleName, $functionName = false )
    {
        $policyList = $this->policyList();
        if ( is_array( $policyList ) && count( $policyList ) > 0 )
        {
            $db = eZDB::instance();
            $db->begin();

            foreach( $policyList as $key => $policy )
            {
                if ( is_object( $policy ) )
                {
                    if ( $policy->attribute( 'module_name' ) == $moduleName )
                    {
                        if ( ( $functionName === false ) || ( $policy->attribute( 'function_name' ) == $functionName ) )
                        {
                            $policy->removeThis();
                            unset( $this->Policies[$key] );
                        }
                    }
                }
            }

            $db->commit();
        }
    }

    /*!
     \static
     Cleans up policies and role assignments related to node when this node is removed
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanupByNode( $node )
    {
        // Clean up role assignments with limitations related to this object
        $db = eZDB::instance();
        $db->begin();
        $pathString = $node->attribute( 'path_string' );
        $nodeID = $node->attribute( 'node_id' );
        $db->query( "DELETE FROM ezuser_role
                     WHERE limit_value LIKE '$pathString%' AND limit_identifier='Subtree'" );
                        // Clean up subtree limitations related to this object


        $limitationsToFix = eZPolicyLimitation::findByType( 'SubTree', $node->attribute( 'path_string' ), true, true );

        foreach( $limitationsToFix as $limitation )
        {
            $values = $limitation->attribute( 'values' );
            $valueCount = count( $values );
            if ( $valueCount > 0 )
            {
                foreach ( $values as $value )
                {
                    if ( strpos( $value->attribute( 'value' ), $node->attribute( 'path_string' ) ) === 0 )
                    {
                        $value->remove();
                        $valueCount--;
                    }
                }
            }
            if( $valueCount == 0 )
            {
                $policy = eZPolicy::fetch( $limitation->attribute( 'policy_id' ) );
                if ( is_object ( $policy ) )
                {
                    $policy->removeThis();
                }
            }
        }

        $limitationsToFixNode = eZPolicyLimitation::findByType( 'Node', $node->attribute( 'node_id' ) );

        foreach( $limitationsToFixNode as $limitation )
        {
            $values = $limitation->attribute( 'values' );
            $valueCount = count( $values );
            if ( $valueCount > 0 )
            {
                foreach ( $values as $value )
                {
                    if ( $value->attribute( 'value' ) == $node->attribute( 'node_id' ) )
                    {
                        $value->remove();
                        $valueCount--;
                    }
                }
            }
            if( $valueCount == 0 )
            {
                $policy = eZPolicy::fetch( $limitation->attribute( 'policy_id' ) );
                if ( is_object ( $policy ) )
                {
                    $policy->removeThis();
                }
            }
        }

        eZRole::expireCache();

        $db->commit();

    }

    /*!
     \static
     Returns the roles which the corresponds to the array of content object id's ( Users and user group id's ).

     \param recursive, default false
    */
    static function fetchByUser( $idArray, $recursive = false )
    {
        $db = eZDB::instance();

        if ( !$recursive )
        {
            $groupString = $db->implodeWithTypeCast( ',', $idArray, 'int' );
            $query = "SELECT DISTINCT ezrole.id,
                                      ezrole.name,
                                      ezuser_role.limit_identifier,
                                      ezuser_role.limit_value,
                                      ezuser_role.id as user_role_id
                      FROM ezrole,
                           ezuser_role
                      WHERE ezuser_role.contentobject_id IN ( $groupString ) AND
                            ezuser_role.role_id = ezrole.id";
        }
        else
        {
            $userNodeIDArray = array();
            foreach( $idArray as $id )
            {
                $nodeDefinition = eZContentObjectTreeNode::fetchByContentObjectID( $id );
                foreach ( $nodeDefinition as $nodeDefinitionElement )
                {
                    $userNodeIDArray = array_merge( $nodeDefinitionElement->attribute( 'path_array' ), $userNodeIDArray );
                }
            }

            $query = 'SELECT DISTINCT ezrole.id,
                                      ezrole.name,
                                      ezuser_role.limit_identifier,
                                      ezuser_role.limit_value,
                                      ezuser_role.id as user_role_id
                      FROM ezrole,
                           ezuser_role,
                           ezcontentobject_tree role_tree
                      WHERE ezuser_role.contentobject_id = role_tree.contentobject_id AND
                            ezuser_role.role_id = ezrole.id AND
                            role_tree.node_id IN ( ' . implode( ',', $userNodeIDArray ) . ' )';
        }

        $roleArray = $db->arrayQuery( $query );

        $roles = array();
        foreach ( $roleArray as $roleRow )
        {
            $role = new eZRole( $roleRow );
            $roles[] = $role;
        }

        return $roles;
    }

    /*!
      Expires all roles, policies and limitations cache.
    */
    static function expireCache()
    {
        $http = eZHTTPTool::instance();

        $http->removeSessionVariable( 'UserPolicies' );
        $http->removeSessionVariable( 'UserLimitations' );
        $http->removeSessionVariable( 'UserLimitationValues' );
        $http->removeSessionVariable( 'CanInstantiateClassesCachedForUser' );
        $http->removeSessionVariable( 'CanInstantiateClassList' );
        $http->removeSessionVariable( 'ClassesCachedForUser' );

        // Expire role cache
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-access-cache', time() );
        $handler->store();
    }

    /*!
      \static
      \param user id
      \return array containing complete access limitation description
       Returns newly generated access array which corresponds to the array of user/group ids list.
    */
    static function accessArrayByUserID( $userIDArray )
    {
        $roles = eZRole::fetchByUser( $userIDArray );
        $userLimitation = false;

        $accessArray = array();
        foreach ( array_keys ( $roles )  as $roleKey )
        {
            $accessArray = array_merge_recursive( $accessArray, $roles[$roleKey]->accessArray() );
            if ( $roles[$roleKey]->attribute( 'limit_identifier' ) )
            {
                $userLimitation = true;
            }
        }

        if ( $userLimitation )
        {
            foreach( $accessArray as $moduleKey => $functionList )
            {
                foreach( $functionList as $functionKey => $policyList )
                {
                    foreach( $policyList as $policyKey => $limitationList )
                    {
                        if ( is_array( $limitationList ) )
                        {
                            foreach( $limitationList as $limitationKey => $limitKeyArray )
                            {
                                if ( is_array( $limitKeyArray ) )
                                {
                                    $accessArray[$moduleKey][$functionKey][$policyKey][$limitationKey] = array_unique( $limitKeyArray );
                                }
                            }
                        }
                    }
                }
            }
        }
        return $accessArray;
    }

    /*!
     Fetch access array of current role
    */
    function accessArray( $ignoreLimitIdentifier = false )
    {
        $accessArray = array();

        $policies = $this->attribute( 'policies' );
        foreach ( array_keys( $policies ) as $policyKey )
        {
            $accessArray = array_merge_recursive( $accessArray, $policies[$policyKey]->accessArray( $ignoreLimitIdentifier ) );
        }

        return $accessArray;
    }

    function policyList()
    {
        if ( !isset( $this->Policies ) )
        {
            //include_once( "kernel/classes/ezpolicy.php" );
            $policies = eZPersistentObject::fetchObjectList( eZPolicy::definition(),
                                                              null, array( 'role_id' => $this->attribute( 'id') ), null, null,
                                                              true );

            if ( $this->LimitIdentifier )
            {
                foreach ( array_keys( $policies ) as $policyKey )
                {
                    $policies[$policyKey]->setAttribute( 'limit_identifier', 'User_' . $this->attribute( 'limit_identifier' ) );
                    $policies[$policyKey]->setAttribute( 'limit_value', $this->attribute( 'limit_value' ) );
                    $policies[$policyKey]->setAttribute( 'user_role_id', $this->attribute( 'user_role_id' ) );
                }
            }
            $this->Policies = $policies;
        }

        return $this->Policies;
    }

    /*!
     Returns a list of role ids which the corresponds to the array of content object id's ( Users and user group id's ).
    */
    static function fetchIDListByUser( $idArray )
    {
        $db = eZDB::instance();

        $groupString = $db->implodeWithTypeCast( ',', $idArray, 'int' );
        $query = "SELECT DISTINCT ezrole.id AS id
                  FROM ezrole,
                       ezuser_role
                  WHERE ezuser_role.contentobject_id IN ( $groupString ) AND
                        ezuser_role.role_id = ezrole.id ORDER BY ezrole.id";

        $retArray = array();
        foreach( $db->arrayQuery( $query ) as $resultSet )
        {
            $retArray[] = $resultSet['id'];
        }
        return $retArray;
    }

    /*!
     Assigns the current role to the given user or user group identified by the id.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     \note WARNING: Roles and content caches need to be cleared after calling this function.
    */
    function assignToUser( $userID, $limitIdent = '', $limitValue = '' )
    {
        $db = eZDB::instance();
        $limitIdent = $db->escapeString( $limitIdent );
        $limitValue = $db->escapeString( $limitValue );
        $userID =(int) $userID;

        // Who assign which role to whom should be logged.
        $object = eZContentObject::fetch( $userID );
        $objectName = $object ? $object->attribute( 'name' ) : 'null';

        //include_once( "kernel/classes/ezaudit.php" );
        eZAudit::writeAudit( 'role-assign', array( 'Role ID' => $this->ID, 'Role name' => $this->attribute( 'name' ),
                                                   'Assign to content object ID' => $userID,
                                                   'Content object name' => $objectName,
                                                   'Comment' => 'Assigned the current role to user or user group identified by the id: eZRole::assignToUser()' ) );

        switch( $limitIdent )
        {
            case 'subtree':
            {
                //include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

                $node = eZContentObjectTreeNode::fetch( $limitValue, false, false );
                if ( $node )
                {
                    $limitIdent = 'Subtree';
                    $limitValue = $node['path_string'];
                }
                else
                {
                    $limitValue = '';
                    $limitIdent = '';
                }
            } break;
            case 'section':
            {
                $limitIdent = 'Section';
            } break;
        }

        $query = "SELECT * FROM ezuser_role WHERE role_id='$this->ID' AND contentobject_id='$userID' AND limit_identifier='$limitIdent' AND limit_value='$limitValue'";

        $rows = $db->arrayQuery( $query );
        if ( count( $rows ) > 0 )
            return false;

        $db->begin();

        $query = "INSERT INTO ezuser_role ( role_id, contentobject_id, limit_identifier, limit_value ) VALUES ( '$this->ID', '$userID', '$limitIdent', '$limitValue' )";
        $db->query( $query );

        $db->commit();
        return true;
    }

    /*!
     Fetch user id array which have been assigned to this role.
    */
    function fetchUserID()
    {
        $db = eZDB::instance();

        $query = "SELECT contentobject_id FROM  ezuser_role WHERE role_id='$this->ID'";

        return $db->arrayQuery( $query );
    }

    /*!
     Removes the role assignment
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     \note WARNING: Roles and content caches need to be cleared after calling this function.
    */
    function removeUserAssignment( $userID )
    {
        $db = eZDB::instance();
        $userID =(int) $userID;
        $query = "DELETE FROM ezuser_role WHERE role_id='$this->ID' AND contentobject_id='$userID'";

        $db->query( $query );
    }

    /*!
     Remove ezuser_role by id

     \param ezuser_role id
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     \note WARNING: Roles and content caches need to be cleared after calling this function.
    */
    function removeUserAssignmentByID( $id )
    {
        // Remove the assignment.
        $db = eZDB::instance();
        $id =(int) $id;
        $query = "DELETE FROM ezuser_role WHERE id='$id'";
        $db->query( $query );
    }

    /*!
      \return the users and user groups assigned to the current role.
    */
    function fetchUserByRole( )
    {
        $db = eZDB::instance();

        $query = "SELECT
                     ezuser_role.contentobject_id as user_id,
                     ezuser_role.limit_value,
                     ezuser_role.limit_identifier,
                     ezuser_role.id
                  FROM
                     ezuser_role
                  WHERE
                    ezuser_role.role_id = '$this->ID'";

        $userRoleArray = $db->arrayQuery( $query );
        $userRoles = array();
        foreach ( $userRoleArray as $userRole )
        {
            $role = array();
            $role['user_object'] = eZContentObject::fetch( $userRole['user_id'] );
            $role['user_role_id'] = $userRole['id'];
            $role['limit_ident'] = $userRole['limit_identifier'];
            $role['limit_value'] = $userRole['limit_value'];

            $userRoles[] = $role;
        }
        return $userRoles;
    }

    static function fetchRolesByLimitation( $limit_identifier, $limit_value )
    {
        $db = eZDB::instance();
        $limit_identifier = $db->escapeString( $limit_identifier );
        $limit_value = $db->escapeString( $limit_value );
        $query = "SELECT DISTINCT
                     ezuser_role.role_id as role_id,
                     ezuser_role.contentobject_id as user_id
                  FROM
                     ezuser_role
                  WHERE
                     ezuser_role.limit_value = '$limit_value' AND
                     ezuser_role.limit_identifier = '$limit_identifier'";

        $userRoleArray = $db->arrayQuery( $query );
        $userRoles = array();
        foreach ( $userRoleArray as $userRole )
        {
            $role = array();
            $role['user'] = eZContentObject::fetch( $userRole['user_id'] );
            $role['role'] = eZRole::fetch( $userRole['role_id'] );
            $userRoles[] = $role;
        }
        return $userRoles;
    }

    /*!
     Fetches the role identified by the role ID \a $roleID and returns it.
     \param $version Which version to fetch, 0 is the published one. Temporary versions get
      the id of the role.
    */
    static function fetch( $roleID, $version = 0 )
    {
        if ( $version != 0 )
        {
            return eZPersistentObject::fetchObject( eZRole::definition(),
                                                    null, array( 'version' => $version ), true );
        }
        return eZPersistentObject::fetchObject( eZRole::definition(),
                                                null, array('id' => $roleID ), true );
    }

    /*!
     Fetches the role identified by the role name \a $roleName and returns it.
     \param $version Which version to fetch, 0 is the published one and 1 is the temporary.
    */
    static function fetchByName( $roleName, $version = 0 )
    {
        return eZPersistentObject::fetchObject( eZRole::definition(),
                                                null, array( 'name' => $roleName,
                                                             'version' => $version ), true );
    }

    static function fetchList( $tempVersions = false )
    {
        if ( !$tempVersions )
        {
            return eZPersistentObject::fetchObjectList( eZRole::definition(),
                                                        null, array( 'version' => '0'), null,null,
                                                        true );
        }
        else
        {
            return eZPersistentObject::fetchObjectList( eZRole::definition(),
                                                        null, array( 'version' => array( '>', '0') ), null,null,
                                                        true);
        }
    }

    static function fetchByOffset( $offset, $limit, $asObject = true, $ignoreTemp = false, $ignoreNew = true )
    {

        if ( $ignoreTemp && $ignoreNew )
            $igTemp = array( 'version' => '0',
                             'is_new' => '0' );
        elseif ( $ignoreTemp )
            $igTemp = array( 'version' => '0' );
        elseif ( $ignoreNew )
            $igTemp = array( 'is_new' => '0' );
        else
            $igTemp = null;

        return eZPersistentObject::fetchObjectList( eZRole::definition(),
                                                    null,
                                                    $igTemp,
                                                    array( 'name' => 'ASC' ),
                                                    array( 'offset' => $offset, 'length' => $limit ),
                                                    $asObject );
    }

    /*!
     \static
     \return the number of roles in the database.
    */
    static function roleCount()
    {
        $db = eZDB::instance();

        $countArray = $db->arrayQuery(  "SELECT count( * ) AS count FROM ezrole WHERE version=0" );
        return $countArray[0]['count'];
    }

    /*!
     Sets caching of policies to off for this role.
    */
    function turnOffCaching()
    {
        $this->CachePolicies = false;
    }

    /*!
     Sets caching of policies to on for this role.
    */
    function turnOnCaching()
    {
        $this->CachePolicies = true;
    }


    /// \privatesection
    public $ID;
    public $Name;
    public $Modules;
    public $Functions;
    public $LimitValue;
    public $LimitIdentifier;
    public $UserRoleID;
    public $PolicyArray;
    public $Sets;
    public $Policies;
    public $AccessArray;
    public $CachePolicies = true;
}

?>
