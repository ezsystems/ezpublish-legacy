<?php
//
// Definition of eZContentObjectPermission class
//
// Created on: <03-May-2002 12:44:04 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

/*!
  \class eZContentObjectPermission ezcontentobjectpermission.php
  \ingroup eZKernel
  \brief The class eZContentObjectPermission handles permissions and workflows on content objects

*/

include_once( "kernel/classes/ezpersistentobject.php" );

class eZContentObjectPermission extends eZPersistentObject
{
    function eZContentObjectPermission( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "permission_id" => "PermissionID",
                                         "user_group_id" => "UserGroupID",
                                         "read_permission" => "ReadPermission",
                                         "create_permission" => "CreatePermission",
                                         "edit_permission" => "EditPermission",
                                         "remove_permission" => "RemovePermission"
                                         ),
                      "class_name" => "eZContentObjectPermission",
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "name" => "ezcontentobject_permission" );
    }

    function &create( $permissionID, $userGroupID )
    {
        $row = array(
            "permission_id" => $permissionID,
            "user_group_id" => $userGroupID
            );
        return new eZContentObjectPermission( $row );
    }

    /*!
     Returns the permissions for the given user group.
    */
    function &groupPermissions( $permissionID, $userGroup )
    {
        $db =& eZDB::instance();

        $query = "SELECT MAX( read_permission ) AS read_permission,
                         MAX( create_permission ) AS create_permission,
                         MAX( edit_permission ) AS edit_permission,
                         MAX( remove_permission ) AS remove_permission
                  FROM ezcontentobject_permission
                  WHERE permission_id='$permissionID' AND ( user_group_id='$userGroup'  )";

        $permission =& $db->arrayQuery( $query );

        return $permission;
    }

    /*!
     Returns the user group id's with read access to the given object.
    */
    function &readGroups( $permissionID )
    {
        $db =& eZDB::instance();

        $query = "select user_group_id as id from ezcontentobject_permission where permission_id='$permissionID' and read_permission='1'";

        $readGroups =& $db->arrayQuery( $query );

        return $readGroups;
    }

    /*!
     Returns the user group id's with create access to the given object.
    */
    function &createGroups( $permissionID )
    {
        $db =& eZDB::instance();

        $query = "select user_group_id as id from ezcontentobject_permission where permission_id='$permissionID' and create_permission='1'";

        $createGroups =& $db->arrayQuery( $query );

        return $createGroups;
    }

    /*!
     Returns the user group id's with edit access to the given object.
    */
    function &editGroups( $permissionID )
    {
        $db =& eZDB::instance();

        $query = "select user_group_id as id from ezcontentobject_permission where permission_id='$permissionID' and edit_permission='1'";

        $editGroups =& $db->arrayQuery( $query );

        return $editGroups;
    }

    /*!
     Returns the user group id's with remove access to the given object.
    */
    function &removeGroups( $permissionID )
    {
        $db =& eZDB::instance();

        $query = "select user_group_id as id from ezcontentobject_permission where permission_id='$permissionID' and remove_permission='1'";

        $removeGroups =& $db->arrayQuery( $query );

        return $removeGroups;
    }

    /*!
     Removes the permissions for the given content object id
     */
    function removePermissions( $permissionID )
    {
        $db =& eZDB::instance();

        $db->query( "DELETE FROM ezcontentobject_permission
                     WHERE permission_id='$permissionID'" );
    }

    function remove( $permissionID, $groupID )
    {
        $db =& eZDB::instance();

        $db->query( "DELETE FROM ezcontentobject_permission
                     WHERE permission_id='$permissionID' and user_group_id='$groupID'" );
        
    }
    
    function fetchPermissionSets()
    {
         $db =& eZDB::instance();
         $query = 'select distinct permission_id as id,permission_name as name from ezcontentobject_permission';
         $permissionSets =& $db->arrayQuery( $query );
         return $permissionSets;
         
       
    }
    function &fetch( $permissionID, $userGroupID, $as_object = true )
    {
        return eZPersistentObject::fetchObject( eZContentObjectPermission::definition(),
                                                null,
                                                array( "permission_id" => $permissionID,
                                                       "user_group_id" => $userGroupID
                                                      ),
                                                $as_object );
    }
}

?>
