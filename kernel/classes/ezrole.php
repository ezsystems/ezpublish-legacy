<?php
//
// Definition of eZRole class
//
// Created on: <14-Aug-2002 14:08:46 sp>
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
include_once( 'lib/ezutils/classes/ezini.php' );
include_once( "lib/ezdb/classes/ezdb.php" );

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
    }

    function &definition()
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
                                                      'limit_value' => 'limitValue' ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZRole",
                      "name" => "ezrole" );
    }

    /*!
     \reimp
    */
    function &attribute( $attr )
    {
        switch( $attr )
        {
            default:
            {
                return eZPersistentObject::attribute( $attr );
            } break;
        }
    }

    /*!
     Returns the limit identifier if it is set.
     \note This will only be available when fetching roles for a specific user
     \sa limitValue
    */
    function &limitIdentifier()
    {
        return $this->LimitIdentifier;
    }

    /*!
     Returns the limit value if it is set.
     \note This will only be available when fetching roles for a specific user
     \sa limitIdentifier
    */
    function &limitValue()
    {
        return $this->LimitValue;
    }

    /*!
     \static
    */
    function createTemporaryVersion()
    {
        $newRole =& eZRole::createNew();
        $this->copyPolicies( $newRole->attribute( 'id' ) );
        $newRole->setAttribute( 'name', $this->attribute( 'name' ) );
        $newRole->setAttribute( 'version', $this->attribute( 'id' ) );
        $newRole->store();
        return $newRole;
    }

    /*!
     \static
     Creates a new role with the name 'New role', stores it and returns it.
    */
    function createNew()
    {
        $role = new eZRole( array() );
        $role->setAttribute( 'name', ezi18n( 'kernel/role/edit', 'New role' ) );
        $role->setAttribute( 'is_new', 1 );
        $role->store();
        return $role;
    }

    /*!
     \static
     Crates a new role with the name \a $roleName and version \a $version and returns it.
     \note The role is not stored.
    */
    function &create( $roleName, $version = 0 )
    {
        $row = array( 'id' => false,
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
     $policy1 =& $role->appendPolicy( 'content', 'read' );
     // Access to content/read in section 1
     $policy2 =& $role->appendPolicy( 'content', 'read', array( 'Section' => 1 ) );
     // Access to content/read for class 2 and 5
     $policy3 =& $role->appendPolicy( 'content', 'read', array( 'Class' => array( 2, 5 ) ) );
     \encode
    */
    function appendPolicy( $module, $function, $limitations = array() )
    {
        include_once( 'kernel/classes/ezpolicy.php' );
        $policy =& eZPolicy::create( $this->ID, $module, $function );
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
        if ( isset( $this->Policies ) )
        {
            $this->Policies[] =& $policy;
        }
        return $policy;
    }

    /*!
     Copies all policies for this role and assigns them to the role identified by ID \a $roleID.
    */
    function copyPolicies( $roleID )
    {
        foreach ( $this->attribute( 'policies' ) as $policy )
        {
            $policy->copy( $roleID );
        }
    }

    function revertFromTemporaryVersion()
    {
        $temporaryVersion =& eZRole::fetch( 0, $this->attribute( 'id' ) );
        if ( is_null( $temporaryVersion ) )
            return 0;
        $this->removePolicies();
        $this->setAttribute( 'name', $temporaryVersion->attribute( 'name') );
        $this->setAttribute( 'is_new', 0 );
        $this->store();

        $db =& eZDB::instance();
        $query = "UPDATE  ezpolicy
                  SET role_id = " . $this->attribute( 'id' ) . "
                  WHERE role_id = " . $temporaryVersion->attribute( 'id' );
        $db->query( $query );
        $temporaryVersion->removePolicies( false );
        $temporaryVersion->remove();

        // Expire role cache
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-access-cache', mktime() );
        $handler->setTimestamp( 'user-class-cache', mktime() );
        $handler->store();
    }

    /*!
     \static
     Removes all temporary roles and roles without policies from the database.
    */
    function removeTemporary()
    {
        $temporaryRoles =& eZRole::fetchList( true );
        foreach ( $temporaryRoles as $role )
        {
            $role->remove();
        }
    }

    /*!
     \static
     Removes the role, it's policies and any assignments to users/groups.
     \param $roleID If this is \c false then the function is not static and the ID is fetched from \c $this.
    */
    function remove( $roleID = false )
    {
        if ( $roleID )
        {
            $role =& eZRole::fetch( $roleID );
        }
        else
        {
            $role = $this;
            $roleID = $this->attribute('id');
        }
        if ( !isset( $role->ID ) )
        {
            return 0;
        }
        foreach ( $role->attribute( 'policies' ) as $policy )
        {
            $policy->remove();
        }
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezrole WHERE id='$roleID'" );
        $db->query( "DELETE FROM ezuser_role WHERE role_id = '$roleID'" );
    }

    /*!
     Removes the policy object list from this role.
     \param $fromDB If \c true then the policies are removed from database.
    */
    function removePolicies( $fromDB = true )
    {
        if ( $fromDB )
        {
            foreach ( $this->attribute( 'policies' ) as $policy )
            {
                $policy->remove();
            }
        }
        unset( $this->Policies );
    }

    /*!
     \static
     Cleans up policies and role assignments related to node when this node is removed
    */
    function cleanupByNode( $node )
    {
        // Clean up role assignments with limitations related to this object
        $db =& eZDB::instance();
        $pathString = $node->attribute( 'path_string' );
        $nodeID = $node->attribute( 'node_id' );
        $db->query( "DELETE FROM ezuser_role
                     WHERE limit_value LIKE '$pathString%' AND limit_identifier='Subtree'" );
                        // Clean up subtree limitations related to this object


        $limitationsToFix =& eZPolicyLimitation::findByType( 'SubTree', $node->attribute( 'path_string' ), true, true );

        foreach( $limitationsToFix as $limitation )
        {
            $values =& $limitation->attribute( 'values' );
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
                $policy =& eZPolicy::fetch( $limitation->attribute( 'policy_id' ) );
                $policy->remove();
                eZContentObject::expireAllCache();
                eZRole::expireCache();
            }
        }

        $limitationsToFixNode =& eZPolicyLimitation::findByType( 'Node', $node->attribute( 'node_id' ) );

        foreach( $limitationsToFixNode as $limitation )
        {
            $values =& $limitation->attribute( 'values' );
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
                $policy =& eZPolicy::fetch( $limitation->attribute( 'policy_id' ) );
                $policy->remove();
                eZContentObject::expireAllCache();
                eZRole::expireCache();
            }
        }


    }

    /*!
     \static
     Returns the roles which the corresponds to the array of content object id's ( Users and user group id's ).

     \param recursive, default false
    */
    function &fetchByUser( $idArray, $recursive = false )
    {
        $db =& eZDB::instance();

        if ( !$recursive )
        {
            $groupString = implode( ',', $idArray );
            $query = "SELECT DISTINCT ezrole.id,
                                      ezrole.name,
                                      ezuser_role.limit_identifier,
                                      ezuser_role.limit_value
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
                                      ezuser_role.limit_value
                      FROM ezrole,
                           ezuser_role,
                           ezcontentobject_tree role_tree
                      WHERE ezuser_role.contentobject_id = role_tree.contentobject_id AND
                            ezuser_role.role_id = ezrole.id AND
                            role_tree.node_id IN ( ' . implode( ',', $userNodeIDArray ) . ' )';
        }

        $roleArray =& $db->arrayQuery( $query );

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
    function expireCache()
    {
        $http =& eZHTTPTool::instance();

        $http->removeSessionVariable( 'UserPolicies' );
        $http->removeSessionVariable( 'UserLimitations' );
        $http->removeSessionVariable( 'UserLimitationValues' );
        $http->removeSessionVariable( 'CanInstantiateClassesCachedForUser' );
        $http->removeSessionVariable( 'CanInstantiateClassList' );
        $http->removeSessionVariable( 'ClassesCachedForUser' );

        // Expire role cache
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-role-cache', mktime() );
        $handler->store();
    }

    /*!
      \static
      Fetch access array by user id

      \param user id

      \return array containing complete access limitation description

      Returns a list of role ids which the corresponds to the array of content object id's ( Users and user group id's ).
    */
    function &accessArrayByUserID( $userIDArray )
    {
        if ( isset( $this->AccessArray ) )
            return $this->AccessArray;

        $ini =& eZINI::instance();
        $enableCaching = $ini->variable( 'RoleSettings', 'EnableCaching' );

        $accessArray = array();

        if ( $enableCaching == 'true' )
        {
            $http =& eZHTTPTool::instance();

            if ( $http->hasSessionVariable( 'AccessArray' ) )
            {
                $expiredTimeStamp = 0;
                $handler =& eZExpiryHandler::instance();
                if ( $handler->hasTimestamp( 'user-access-cache' ) )
                {
                    $expiredTimeStamp = $handler->timestamp( 'user-access-cache' );
                }
                else
                {
                    $handler->setTimestamp( 'user-access-cache', mktime() );
                }

                $userAccessTimestamp = $http->sessionVariable( 'AccessArrayTimestamp' );

                if ( $userAccessTimestamp > $expiredTimeStamp )
                {
                    $this->AccessArray =& $http->sessionVariable( 'AccessArray' );
                    return $this->AccessArray;
                }
            }
        }

        $roles =& eZRole::fetchByUser( $userIDArray );

        $userLimitation = false;
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
            foreach( array_keys( $accessArray ) as $moduleKey )
            {
                foreach( array_keys( $accessArray[$moduleKey] ) as $functionKey )
                {
                    foreach( array_keys( $accessArray[$moduleKey][$functionKey] ) as $policyKey )
                    {
                        foreach( array_keys( $accessArray[$moduleKey][$functionKey][$policyKey] ) as $limitationKey )
                        {
                            $limitKeyArray =& $accessArray[$moduleKey][$functionKey][$policyKey][$limitationKey];
                            $limitKeyArray = array_unique( $limitKeyArray );
                        }
                    }
                }
            }
        }


        if ( $enableCaching == 'true' )
        {
            $http =& eZHTTPTool::instance();
            $http->setSessionVariable( 'AccessArray', $accessArray );
            $http->setSessionVariable( 'AccessArrayTimestamp', mktime() );
        }

        $this->AccessArray =& $accessArray;

        return $this->AccessArray;
    }

    /*!
     Fetch access array of current role
    */
    function &accessArray()
    {
        $accessArray = array();

        $policies =& $this->attribute( 'policies' );

        foreach ( array_keys( $policies ) as $policyKey )
        {
            $accessArray = array_merge_recursive( $accessArray, $policies[$policyKey]->accessArray() );
        }

        return $accessArray;
    }

    function &policyList()
    {
        if ( !isset( $this->Policies ) )
        {
            include_once( "kernel/classes/ezpolicy.php" );
            $policies =& eZPersistentObject::fetchObjectList( eZPolicy::definition(),
                                                              null, array( 'role_id' => $this->attribute( 'id') ), null, null,
                                                              true );

            if ( $this->LimitIdentifier )
            {
                foreach ( array_keys( $policies ) as $policyKey )
                {
                    $policies[$policyKey]->setAttribute( 'limit_identifier', $this->attribute( 'limit_identifier' ) );
                    $policies[$policyKey]->setAttribute( 'limit_value', $this->attribute( 'limit_value' ) );
                }
            }
            $this->Policies =& $policies;
        }

        return $this->Policies;
    }

    /*!
     Returns a list of role ids which the corresponds to the array of content object id's ( Users and user group id's ).
    */
    function &fetchIDListByUser( $idArray )
    {
        $db =& eZDB::instance();

        $groupString = implode( ',', $idArray );
        $query = "SELECT DISTINCT ezrole.id
                  FROM ezrole,
                       ezuser_role
                  WHERE ezuser_role.contentobject_id IN ( $groupString ) AND
                        ezuser_role.role_id = ezrole.id ORDER BY ezrole.id";

        $roleArray =& $db->arrayQuery( $query );
        $roles = array();

        $keys = array_keys( $roleArray );
        foreach ( $keys as $key )
        {
            $roles[] = $roleArray[$key]['id'];
        }

        return $roles;
    }

    /*!
     Assigns the current role to the given user or user group identified by the id.
    */
    function assignToUser( $userID, $limitIdent = '', $limitValue = '' )
    {
        $db =& eZDB::instance();

        switch( $limitIdent )
        {
            case 'subtree':
            {
                include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

                $node =& eZContentObjectTreeNode::fetch( $limitValue );
                if ( $node )
                {
                    $limitIdent = 'Subtree';
                    $limitValue = $node->attribute( 'path_string' );
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

        $query = "INSERT INTO ezuser_role ( role_id, contentobject_id, limit_identifier, limit_value ) VALUES ( '$this->ID', '$userID', '$limitIdent', '$limitValue' )";

        $db->query( $query );

        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-access-cache', mktime() );
        $handler->setTimestamp( 'user-class-cache', mktime() );
        $handler->store();
        return true;
    }

    /*!
     Fetch user id array which have been assigned to this role.
    */
    function &fetchUserID()
    {
        $db =& eZDB::instance();

        $query = "SELECT contentobject_id FROM  ezuser_role WHERE role_id='$this->ID'";

        $results = $db->arrayQuery( $query );
        $idArray = array();
        foreach ( $results as $result )
        {
            $idArray[] = $result['contentobject_id'];
        }
        return $idArray;
    }

    /*!
     Removes the role assignment
    */
    function removeUserAssignment( $userID )
    {
        $db =& eZDB::instance();

        $query = "DELETE FROM ezuser_role WHERE role_id='$this->ID' AND contentobject_id='$userID'";

        $db->query( $query );
    }

    /*!
     Remove ezuser_role by id

     \param ezuser_role id
    */
    function removeUserAssignmentByID( $id )
    {
        $db =& eZDB::instance();

        $query = "DELETE FROM ezuser_role WHERE id='$id'";

        $db->query( $query );
    }

    /*!
      \return the users and user groups assigned to the current role.
    */
    function &fetchUserByRole( )
    {
        $db =& eZDB::instance();

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

    function &fetchRolesByLimitation( $limit_identifier, $limit_value )
    {
        $db =& eZDB::instance();

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
            $userRoles[] =& $role;
        }
        return $userRoles;
    }

    /*!
     Fetches the role identified by the role ID \a $roleID and returns it.
     \param $version Which version to fetch, 0 is the published one and 1 is the temporary.
    */
    function fetch( $roleID, $version = 0 )
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
    function fetchByName( $roleName, $version = 0 )
    {
        return eZPersistentObject::fetchObject( eZRole::definition(),
                                                null, array( 'name' => $roleName,
                                                             'version' => $version ), true );
    }

    function fetchList( $tempVersions = false )
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

    function &fetchByOffset( $offset, $limit, $asObject = true, $ignoreTemp = false, $ignoreNew = true )
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
    function &roleCount()
    {
        $db =& eZDB::instance();

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
    var $ID;
    var $Name;
    var $Modules;
    var $Functions;
    var $LimitValue;
    var $LimitIdentifier;
    var $PolicyArray;
    var $Sets;
    var $Policies;
    var $AccessArray;
    var $CachePolicies = true;
}

?>
