<?php
//
// Created on: <29-Apr-2002 09:28:51 bf>
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

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezworkflow.php" );
include_once( "kernel/classes/ezcontentobjectpermission.php" );
include_once( "kernel/classes/ezcontentobjectpermissionset.php" );
include_once( "kernel/common/template.php" );

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( "CancelButton" ) )
{

    $Module->redirectTo( $Module->functionURI( "edit" ) . "/" .  $ObjectID );
    return;
}


if ( $http->hasPostVariable( "SelectPermissionSet" ) )
{

    $newPermissionSetID = $http->postVariable( 'PermissionSet' );
    $object =& eZContentObject::fetch( $ObjectID );
    $object->setAttribute( 'permission_id' , $newPermissionSetID );
    $object->store();
}

if ( $http->hasPostVariable( 'CreateNewPermissionSet' ) )
{

    $newPermissionSetName = $http->postVariable( 'NewPermissionName' );
    $permissionSet =& eZContentObjectPermissionSet::createNew( $newPermissionSetName );
    
    $object =& eZContentObject::fetch( $ObjectID );
    $object->setAttribute( 'permission_id' , $permissionSet->attribute( 'id' ) );
    $object->store();
    eZDebug::writeNotice( $permissionSet, 'permissionSet' );
    
}

if ( $http->hasPostVariable( "StoreButton" ) || $http->hasPostVariable( 'CreateNewPermissionSet' ) )
{
//    eZContentObjectPermission::removePermissions( $ObjectID );


    $ReadGroupArray = $http->postVariable( "ReadGroupArray" );
    $CreateGroupArray = $http->postVariable( "CreateGroupArray" );
    $EditGroupArray = $http->postVariable( "EditGroupArray" );
    $RemoveGroupArray = $http->postVariable( "RemoveGroupArray" );


    $object =& eZContentObject::fetch( $ObjectID );
    $permissionID =& $object->attribute( "permission_id" );

    $permissionSet = eZContentObjectPermissionSet::fetch( $permissionID );
    eZDebug::writeNotice( $object, 'object' );
    eZDebug::writeNotice( $permissionSet, 'permissionSet' );

    $permissionSet->setReadGroups( $ReadGroupArray ); 
    $permissionSet->setCreateGroups( $CreateGroupArray ); 
    $permissionSet->setEditGroups( $EditGroupArray );
    $permissionSet->setRemoveGroups($RemoveGroupArray); 
    $permissionSet->storeSet();


/*    $oldReadGroupArray = $permissionSet->attribute( 'read_groups' );
    $oldCreateGroupArray =  $permissionSet->attribute( 'create_groups' );
    $oldEditGroupArray =  $permissionSet->attribute( 'edit_groups' );
    $oldRemoveGroupArray =  $permissionSet->attribute( 'remove_groups' );
    

    $permissionArray = array();

    foreach ( $ReadGroupArray as $readGroup )
    {
        $permissionArray[$readGroup] .= "r";
    }

    foreach ( $CreateGroupArray as $createGroup )
    {
        $permissionArray[$createGroup] .= "c";
    }

    foreach ( $EditGroupArray as $editGroup )
    {
        $permissionArray[$editGroup] .= "e";
    }

    foreach ( $RemoveGroupArray as $removeGroup )
    {
        $permissionArray[$removeGroup] .= "d";
    }

    reset( $permissionArray );
    while ( list( $userGroup, $value ) = each( $permissionArray ) )
    {
        $object =& eZContentObject::fetch( $ObjectID );
        $permissionID =& $object->attribute( "permission_id" );

        $permission = eZContentObjectPermission::create( $permissionID, $userGroup );

        $permission->setAttribute( "read_permission",  substr_count( $value, "r") );
        $permission->setAttribute( "create_permission",  substr_count( $value, "c") );
        $permission->setAttribute( "edit_permission",  substr_count( $value, "e") );
        $permission->setAttribute( "remove_permission",  substr_count( $value, "d") );
//        $permission->setAttribute( "permission_name",  $permissionName );

        $permission->store();
    }

//    $Module->redirectTo( $Module->functionURI( "edit" ) . "/" .  $ObjectID );
//    return;
*/

}



$tpl =& templateInit();

// Get the user groups
$userGroups =& eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                    null, array( "contentclass_id" => 3 ),
                                                    null, null,
                                                    true );

$permissionSets = eZContentObjectPermissionSet::fetchPermissionSets();
$workflows =& eZWorkflow::fetchList();

$object =& eZContentObject::fetch( $ObjectID );
$permissionID =& $object->attribute( "permission_id" );

$readGroups = eZContentObjectPermission::readGroups( $permissionID );
$createGroups = eZContentObjectPermission::createGroups( $permissionID );
$editGroups = eZContentObjectPermission::editGroups( $permissionID );
$removeGroups = eZContentObjectPermission::removeGroups( $permissionID );



$tpl->setVariable( "read_groups", $readGroups );
$tpl->setVariable( "create_groups", $createGroups );
$tpl->setVariable( "edit_groups", $editGroups );
$tpl->setVariable( "remove_groups", $removeGroups );

$tpl->setVariable( "object_id", $ObjectID );
$tpl->setVariable( "user_groups", $userGroups );
$tpl->setVariable( "workflows", $workflows );
$tpl->setVariable( "permission_sets", $permissionSets );
$tpl->setVariable( "permission_id", $permissionID );

$Result =& $tpl->fetch( "design:content/permission.tpl" );


?>
