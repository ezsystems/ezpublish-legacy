<?php
//
// Created on: <22-Aug-2002 16:38:41 sp>
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

/*! \file view.php
*/

include_once( 'kernel/classes/ezmodulemanager.php' );
include_once( 'kernel/classes/ezrole.php' );
include_once( 'kernel/classes/ezsearch.php' );
include_once( 'kernel/classes/ezcontentbrowse.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );
include_once( 'lib/ezutils/classes/ezmodule.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

include_once( 'kernel/common/template.php' );

$http =& eZHTTPTool::instance();
$Module =& $Params['Module'];
$roleID =& $Params['RoleID'];

$role =& eZRole::fetch( $roleID );

if ( !$role )
{
    $Module->redirectTo( '/role/list/' );
    return;
}

// Redirect to role edit
if ( $http->hasPostVariable( 'EditRoleButton' ) )
{
    $Module->redirectTo( '/role/edit/' . $roleID );
    return;
}

// Redirect to content node browse in the user tree
if ( $http->hasPostVariable( 'AssignRoleButton' ) )
{
    include_once( 'kernel/classes/ezcontentbrowse.php' );
    eZContentBrowse::browse( array( 'action_name' => 'AssignRole',
                                    'from_page' => '/role/assign/' . $roleID,
                                    'cancel_page' => '/role/view/'. $roleID ),
                             $Module );

    return;
}
else if ( $http->hasPostVariable( 'AssignRoleLimitedButton' ) )
{
    $Module->redirectTo( '/role/assign/' . $roleID . '/' . $http->postVariable( 'AssignRoleType' ) );
    return;
}

// Assign the role for a user or group
if ( $Module->isCurrentAction( 'AssignRole' ) )
{
    $selectedObjectIDArray = eZContentBrowse::result( 'AssignRole' );

    $assignedUserIDArray =& $role->fetchUserID();
    foreach ( $selectedObjectIDArray as $objectID )
    {
        if ( !in_array(  $objectID, $assignedUserIDArray ) )
        {
            $role->assignToUser( $objectID );
        }
    }
}

// Remove the role assignment
if ( $http->hasPostVariable( 'RemoveRoleAssignmentButton' ) )
{
    $idArray = $http->postVariable( "IDArray" );

    foreach ( $idArray as $id )
    {
        $role->removeUserAssignmentByID( $id );
    }
}

$tpl =& templateInit();

$userArray =& $role->fetchUserByRole();

$policies = $role->attribute( 'policies' );
$tpl->setVariable( 'policies', $policies );
$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'role', $role );

$tpl->setVariable( 'user_array', $userArray );

$Module->setTitle( 'View role - ' . $role->attribute( 'name' ) );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:role/view.tpl' );
$Result['path'] = array( array( 'text' => 'Role',
                                'url' => 'role/list' ),
                         array( 'text' => $role->attribute( 'name' ),
                                'url' => false ) );

?>
