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

/*! \file ezrole.php
*/

/*!
  \class eZRole ezrole.php
  \brief The class eZRole does

*/
include_once( 'lib/ezutils/classes/ezini.php' );
include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpolicy.php" );

class eZRole extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZRole( $row = array() )
    {
        $this->eZPersistentObject( $row );
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
                                                          'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( "policies" => "policyList"
//                                                     "class_name" => "className",
                                                      ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZRole",
                      "name" => "ezrole" );
    }

    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function &attribute( $attr )
    {
        if ( $attr == "policies" )
            return $this->policyList();

        return eZPersistentObject::attribute( $attr );
    }

    function &policyList()
    {
        if ( !isset( $this->Policies ) )
        {
            $ini =& eZINI::instance();
            $enableCaching = $ini->variable( 'RoleSettings', 'EnableCaching' );

            $loadFromDB = true;
            $roleID = $this->attribute( 'id' );
            if ( $enableCaching == 'true' && $this->CachePolicies )
            {
                $http =& eZHTTPTool::instance();

                $hasPoliciesInCache = $http->hasSessionVariable( 'UserPolicies' );
                if ( $hasPoliciesInCache )
                {
                    $policiesForAllUserRoles =& $http->sessionVariable( 'UserPolicies' );
                    $policiesForCurrentRole =& $policiesForAllUserRoles["$roleID"];
                    if ( count( $policiesForCurrentRole ) > 0 )
                    {
                        $policies = array();
                        foreach ( array_keys( $policiesForCurrentRole ) as $key )
                        {
                            $policyRow = $policiesForCurrentRole[$key];
                            $policies[] = new eZPolicy( $policyRow );
                        }
                        $this->Policies =& $policies;
                        $loadFromDB = false;
                    }
                }
            }

            if ( $loadFromDB )
            {
                $policies =& eZPersistentObject::fetchObjectList( eZPolicy::definition(),
                                                                  null, array( 'role_id' => $this->attribute( 'id') ), null, null,
                                                                  true );
                if ( $enableCaching )
                {
                    $policiesForCurrentRole = array();
                    foreach ( array_keys( $policies ) as $key )
                    {
                        $policy =& $policies[$key];
                        $policyAttributes = array();
                        $policyAttributes['id'] = $policy->attribute( 'id' );
                        $policyAttributes['role_id'] = $policy->attribute( 'role_id' );
                        $policyAttributes['module_name'] = $policy->attribute( 'module_name' );
                        $policyAttributes['function_name'] = $policy->attribute( 'function_name' );
                        $policyAttributes['limitation'] = $policy->attribute( 'limitation' );
                        $policiesForCurrentRole[] = $policyAttributes;
                    }
                    $http =& eZHTTPTool::instance();
                    if ( !$http->hasSessionVariable( 'UserPolicies' ) )
                    {
                        $policyArray =& $http->sessionVariable( 'UserPolicies' );
                    }
                    else
                    {
                        $policyArray = array();
                    }
                    $policyArray["$roleID"] = $policiesForCurrentRole;
                }
                $this->Policies =& $policies;
            }
        }

        return $this->Policies;
    }

    function createNew()
    {
        $role = new eZRole( array() );
        $role->setAttribute( 'name', 'New Role' );
        $role->store();
        return $role;
    }

    function createTmporaryVersion()
    {
        $newRole =& eZRole::createNew();
        $this->copyPolicies( $newRole->attribute( 'id' ) );
        $newRole->setAttribute( 'name', $this->attribute( 'name' ) );
        $newRole->setAttribute( 'version', $this->attribute( 'id' ) );
        $newRole->store();
        return $newRole;
    }

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
        $handler->setTimestamp( 'user-role-cache', mktime() );
        $handler->setTimestamp( 'user-class-cache', mktime() );
        $handler->store();
    }

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
     Returns the roles which the corresponds to the array of content object id's ( Users and user group id's ).
    */
    function &fetchByUser( $idArray )
    {
        $ini =& eZINI::instance();
        $enableCaching = $ini->variable( 'RoleSettings', 'EnableCaching' );

        $roleArray = false;
        if ( $enableCaching == 'true' )
        {
            $roleArray =& eZRole::cachedRoles();
        }

        if ( $roleArray == false )
        {
            $db =& eZDB::instance();
            $groupString = implode( ',', $idArray );
            $query = "SELECT DISTINCT ezrole.id,
                                      ezrole.name
                      FROM ezrole,
                           ezuser_role
                      WHERE ezuser_role.contentobject_id IN ( $groupString ) AND
                            ezuser_role.role_id = ezrole.id";

            $roleArray =& $db->arrayQuery( $query );
            if ( $enableCaching == 'true' )
            {
                $user =& eZUser::currentUser();
                $http =& eZHTTPTool::instance();
                $userID = $user->id();

                $http->setSessionVariable( 'UserRoles', $roleArray );
                $http->setSessionVariable( 'PermissionCachedForUserID', $userID );
                $http->setSessionVariable( 'PermissionCachedForUserIDTimestamp', mktime() );

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
        }
        else
        {
        }
        $roles = array();
        foreach ( $roleArray as $roleRow )
        {
            $roles[] = new eZRole( $roleRow );
        }
        return $roles;
    }

    /*!
     Returns a list of role ids which the corresponds to the array of content object id's ( Users and user group id's ).
    */
    function &fetchIDListByUser( $idArray )
    {
        $ini =& eZINI::instance();
        $db =& eZDB::instance();

        $enableCaching = $ini->variable( 'RoleSettings', 'EnableCaching' );

        $roleArray = false;
        if ( $enableCaching == 'true' )
        {
            $roleArray =& eZRole::cachedRoles();
        }

        if ( $roleArray == false )
        {
            $groupString = implode( ',', $idArray );
            $query = "SELECT DISTINCT ezrole.id
                  FROM ezrole,
                       ezuser_role
                  WHERE ezuser_role.contentobject_id IN ( $groupString ) AND
                        ezuser_role.role_id = ezrole.id";

            $roleArray =& $db->arrayQuery( $query );
            $roles = array();
        }

        $keys = array_keys( $roleArray );
        foreach ( $keys as $key )
        {
            $roles[] = $roleArray[$key]['id'];
        }
        return $roles;
    }

    /*!
     \return the cached roles for the current user. False is returned if roles are not cached.
    */
    function &cachedRoles()
    {
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $expiredTimeStamp = 0;
        if ( $handler->hasTimestamp( 'user-role-cache' ) )
            $expiredTimeStamp = $handler->timestamp( 'user-role-cache' );

        $returnRoles = false;
        $http =& eZHTTPTool::instance();

        $permissionCachedForUser = $http->sessionVariable( 'PermissionCachedForUserID' );
        $permissionCachedForUserTimestamp = $http->sessionVariable( 'PermissionCachedForUserIDTimestamp' );

        if ( $permissionCachedForUserTimestamp >= $expiredTimeStamp )
        {
            $user =& eZUser::currentUser();
            $userID = $user->id();

            if ( $permissionCachedForUser == $userID )
            {
                $roleArray =& $http->sessionVariable( 'UserRoles' );
                if ( count( $roleArray ) > 0 )
                {
                    $returnRoles =& $roleArray;
                }
            }
        }
        return $returnRoles;
    }

    /*!
     Assigns the current role to the given user or user group identified by the id.
    */
    function assignToUser( $userID )
    {
        $db =& eZDB::instance();

        $query = "SELECT * FROM ezuser_role WHERE role_id='$this->ID' AND contentobject_id='$userID'";

        $rows = $db->arrayQuery( $query );
        if ( count( $rows ) > 0 )
            return false;

        $query = "INSERT INTO ezuser_role ( role_id, contentobject_id ) VALUES ( '$this->ID', '$userID' )";

        $db->query( $query );

        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-role-cache', mktime() );
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
      \return the users and user groups assigned to the current role.
    */
    function &fetchUserByRole( )
    {
        $db =& eZDB::instance();

        $query = "SELECT
                     ezuser_role.contentobject_id as id
                  FROM
                     ezuser_role
                  WHERE
                    ezuser_role.role_id = '$this->ID'";

        $userIDArray = $db->arrayQuery( $query );
        $users = array();
        foreach ( $userIDArray as $user )
        {
            $users[] = eZContentObject::fetch( $user['id'] );
        }
        return $users;
    }


    function fetch( $roleID, $version = 0 )
    {
        if ( $version != 0 )
        {
            return eZPersistentObject::fetchObject( eZRole::definition(),
                                                    null, array( 'version' => $version ), true );
        }
        return eZPersistentObject::fetchObject( eZRole::definition(),
                                                null, array( 'id' => $roleID ), true );
    }

    function fetchList( $tempVersions = false )
    {
        if ( !$tempVersions )
        {
            return eZPersistentObject::fetchObjectList( eZRole::definition(),
                                                        null, array( 'version' => '0' ), null,null,
                                                        true );
        }
        else
        {
            return eZPersistentObject::fetchObjectList( eZRole::definition(),
                                                        null, array( 'version' => array( '>', '0' ) ), null,null,
                                                        true );
        }
    }

    function &fetchByOffset( $offset, $limit, $asObject = true, $ignoreTemp = false )
    {
        if ( $ignoreTemp )
            $igTemp = array( 'version' => '0' );
        else
            $igTemp = null;
        return eZPersistentObject::fetchObjectList( eZRole::definition(),
                                                    null,
                                                    $igTemp,
                                                    array( 'name' => 'ASC' ),
                                                    array( 'offset' => $offset, 'length' => $limit ),
                                                    $asObject );
    }

    function &roleCount()
    {
        $db =& eZDB::instance();

        $countArray = $db->arrayQuery( "SELECT count(*) AS count FROM ezrole" );
        return $countArray[0]['count'];
    }

    function checkItem( $accessItem = array() )
    {

    }

    function getSql()
    {

    }

    function turnOffCaching()
    {
        $this->CachePolicies = false;
    }

    function turnOnCaching()
    {
        $this->CachePolicies = true;
    }


    var $ID;
    var $Name;
    var $Modules;
    var $Functions;
    var $Sets;
    var $CachePolicies = true;
}

?>
