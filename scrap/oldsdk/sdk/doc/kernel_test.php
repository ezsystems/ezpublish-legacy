<?php
//
// Created on: <20-Jun-2002 15:24:42 bf>
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


include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezcontentobjectpermission.php" );

// check current user
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

$user =& eZUser::currentUser();

print( "Current user id: " . $user->attribute( "id" ) . "<br>" );
eZDebug::writeNotice( $user, "User account"  );

//$contentObject =& eZContentObject::createNew( 2 );

// fetch permission object for permissionID 1 and user group 2
$permission =& eZContentObjectPermission::fetch( 1, 2 );

eZDebug::writeNotice( $permission );

/*
// create a new permission for permissionID 2 and user group 2
$permission =& eZContentObjectPermission::create( 4, 2 );
$permission->setAttribute( "read_permission", 1 );
$permission->setAttribute( "create_permission", 1 );
$permission->setAttribute( "edit_permission", 0 );
$permission->setAttribute( "remove_permission", 0 );

// store the permissions
$permission->store();

*/
eZDebug::writeNotice( $permission );


?>
