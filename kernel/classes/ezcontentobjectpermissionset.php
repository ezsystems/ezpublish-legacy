<?php
//
// Definition of eZContentObjectPermissionSet class
//
// Created on: <24-Jul-2002 15:53:59 sp>
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

/*! \file ezcontentobjectpermissionset.php
*/

/*!
  \class eZContentObjectPermissionSet ezcontentobjectpermissionset.php
  \brief The class eZContentObjectPermissionSet does

*/
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentobjectpermission.php" );


class eZContentObjectPermissionSet extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZContentObjectPermissionSet( $row )
    {
        $this->eZPersistentObject( $row );

    }

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "name" => "Name"
                                         ),
                      "function_attributes" => array( "read_groups" => "getReadGroups",
                                                      "edit_groups" => "getEditGroups",
                                                      "create_groups" => "getCreateGroups",
                                                      "remove_groups" => "getRemoveGroups"
                                                      ),
                      "class_name" => "eZContentObjectPermissionSet",
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "name" => "ezcontentobject_perm_set" );
    }
    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function attribute( $attr )
    {
        if ( $attr == 'read_groups')
        {
            return  $this->getReadGroups();
        }elseif ( $attr == 'edit_groups' )
        {
            return  $this->getEditGroups();
        }elseif ( $attr == 'create_groups' )
        {
            return  $this->getEditGroups();
        }elseif ( $attr == 'remove_groups' )
        {
            return  $this->getEditGroups();
        }else
            return eZPersistentObject::attribute( $attr );
    }

    function & createNew( $name )
    {

        $permissionSet =& new eZContentObjectPermissionSet( array() );
        eZDebug::writeNotice($permissionSet, "permissionSet1");
        $permissionSet->setAttribute( 'name', $name );
        eZDebug::writeNotice($permissionSet, "permissionSet2");
        $permissionSet->store();
        eZDebug::writeNotice($permissionSet, "permissionSet3");
        return $permissionSet;
    }

    function getGroupsInSet()
    {
        $permissionID = $this->attribute( 'id' );
        $db =& eZDB::instance();
        
        $query = "select user_group_id as id from ezcontentobject_permission where permission_id='$permissionID'";

        $groups =& $db->arrayQuery( $query );
        $retArray = eZContentObjectPermissionSet::toArray( $groups, 'id' );
        return $retArray;

    }
     
    function toArray( $hashArray, $key )
    {
        $retArray = array();
        foreach ( $hashArray as $element )
        {
            $retArray[] = $element[ $key ];
        }
        return $retArray;
        
    }
        
    function getReadGroups( $asarray = false )
    {
        $groupArray = eZContentObjectPermission::readGroups( $this->attribute( 'id' ) );
        if ( $asarray )
        {
            $groups = eZContentObjectPermissionSet::toArray( $groupArray, 'id' );
        }else
        {
            $groups =& $groupArray;
        }
        return $groups;
    }

    function getEditGroups( $asarray = false )
    {
        $groupArray = eZContentObjectPermission::editGroups( $this->attribute( 'id' ) );
        if ( $asarray )
        {
            $groups = eZContentObjectPermissionSet::toArray( $groupArray, 'id' );
        }else
        {
            $groups =& $groupArray;
        }
        return $groups;
    }

    function getCreateGroups( $asarray = false )
    {
        $groupArray = eZContentObjectPermission::editGroups( $this->attribute( 'id' ) );
        if ( $asarray )
        {
            $groups = eZContentObjectPermissionSet::toArray( $groupArray, 'id' );
        }else
        {
            $groups =& $groupArray;
        }
        return $groups;
    }

    function getRemoveGroups( $asarray = false )
    {
        $groupArray = eZContentObjectPermission::editGroups( $this->attribute( 'id' ) );
        if ( $asarray )
        {
            $groups = eZContentObjectPermissionSet::toArray( $groupArray, 'id' );
        }else
        {
            $groups =& $groupArray;
        }
        return $groups;
    }
    function fetchPermissionSets()
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectPermissionSet::definition(),
                                                    null, null, null, null,
                                                    true );
    }
    
    function & fetch( $setID )
    {
        return eZPersistentObject::fetchObject( eZContentObjectPermissionSet::definition(),
                                                null,
                                                array( "id" => $setID ),
                                                true );
    }

    function calculateNewSet()
    {
        $groupsMerge = array_merge( $this->ReadGroups, $this->CreateGroups, $this->EditGroups, $this->RemoveGroups );
        $valuesArray = array_count_values( $groupsMerge ); 
        $groups = array_keys( $valuesArray ); 
        return $groups;
    }
    
    function modifyPermissions( $groupArray )
    {
        $oldReadGroups = $this->getReadGroups( true );
        $oldCreateGroups =  $this->getCreateGroups( true );
        $oldEditGroups =  $this->getEditGroups( true );
        $oldRemoveGroups =  $this->getRemoveGroups( true );
        $permissionID = $this->attribute( 'id' );
        foreach ( $groupArray as $group )
        {
            $needModify = false;
            $updateArray = array();
            if ( in_array( $group, $this->ReadGroups) && !in_array( $group, $oldReadGroups ) )
            {
                $needModify = true;
                $updateArray[] = " read_permission = '1' " ;
            }
            elseif (  !in_array( $group, $this->ReadGroups) && in_array( $group, $oldReadGroups ) )
            {
                $needModify = true;
                $updateArray[] = " read_permission = '0' ";
            }

            if ( in_array( $group, $this->CreateGroups) && !in_array( $group, $oldCreateGroups ) )
            {
                $needModify = true;
                $updateArray[] = " create_permission = '1' " ;
            }
            elseif (  !in_array( $group, $this->CreateGroups ) && in_array( $group, $oldCreateGroups ) )
            {
                $needModify = true;
                $updateArray[] = " create_permission = '0' ";
            }

            if ( in_array( $group, $this->EditGroups) && !in_array( $group, $oldEditGroups ) )
            {
                $needModify = true;
                $updateArray[] = " edit_permission = '1' " ;
            }
            elseif (  !in_array( $group, $this->EditGroups ) && in_array( $group, $oldEditGroups ) )
            {
                $needModify = true;
                $updateArray[] = " edit_permission = '0' ";
            }

            if ( in_array( $group, $this->RemoveGroups) && !in_array( $group, $oldRemoveGroups ) )
            {
                $needModify = true;
                $updateArray[] = " remove_permission = '1' " ;
            }
            elseif (  !in_array( $group, $this->RemoveGroups ) && in_array( $group, $oldRemoveGroups ) )
            {
                $needModify = true;
                $updateArray[] = " remove_permission = '0' ";
            }
            
            if( $needModify )
            {
                $updateStr = implode( ',' , $updateArray );
                $db =& eZDB::instance();
        
                $query = "UPDATE ezcontentobject_permission
                          SET $updateStr
                          WHERE permission_id ='$permissionID' and
                                user_group_id ='$group'";
                $groups =& $db->arrayQuery( $query );
            }

        }
    }
    function storeSet()
    {

        $groupsInSet = $this->getGroupsInSet();
        $newSet = $this->calculateNewSet(); 
        $deleteGroups = array_diff( $groupsInSet, $newSet );
        $permissionID = $this->attribute( 'id' );
        foreach ( $deleteGroups as $group )
        {
            eZContentObjectPermission::remove( $permissionID, $group );
        }
        $modifyArray = array_intersect( $newSet, $groupsInSet );
        $this->modifyPermissions( $modifyArray );
        $addGroups = array_diff( $newSet, $groupsInSet );
         
        foreach ( $addGroups as $group )
        {
            $permission = eZContentObjectPermission::create( $permissionID, $group );
            $readPermission = 0;
            $createPermission = 0;
            $editPermission = 0;
            $removePermission = 0;

            if ( in_array( $group, $this->ReadGroups ) )
            {
                $readPermission = 1;
            }
            if ( in_array( $group, $this->CreateGroups ) )
            {
                $createPermission = 1;
            }
            if ( in_array( $group, $this->EditGroups ) )
            {
                $editPermission = 1;
            }
            if ( in_array( $group, $this->RemoveGroups ) )
            {
                $removePermission = 1;
            }
            $permission->setAttribute( "read_permission",  $readPermission );
            $permission->setAttribute( "create_permission", $createPermission );
            $permission->setAttribute( "edit_permission",  $editPermission );
            $permission->setAttribute( "remove_permission",  $removePermission );
            $permission->store();
            
        }
        
        
        

        
    }

    




    function setReadGroups( $readGroupArray )
    {
         $this->ReadGroups = $readGroupArray;
    }

    function setCreateGroups( $createGroupArray )
    {
        $this->CreateGroups = $createGroupArray;
    
    }

    function setEditGroups( $editGroupArray )
    {
        $this->EditGroups = $editGroupArray;
    }
    
    function setRemoveGroups( $removeGroupArray )
    {
        $this->RemoveGroups = $removeGroupArray;
    }

    var $ReadGroups;
    var $CreateGroups;
    var $EditGroups;
    var $RemoveGroups;
    

}

?>
