<?php
//
//
// Created on: <16-ïËÔ-2002 10:45:47 sp>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*! \file ~~~
*/

include_once( "kernel/classes/ezrole.php" );

$http =& eZHTTPTool::instance();

$Module =& $Params['Module'];
$roleID =& $Params['RoleID'];

if ( $http->hasPostVariable( "BrowseActionName" ) and
     $http->postVariable( "BrowseActionName" ) == "AssignRole" )
{
    $selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );
    $role =& eZRole::fetch( $roleID );

    foreach ( $selectedObjectIDArray as $objectID )
    {
        $role->assignToUser( $objectID );
    }
    if ( count( $selectedObjectIDArray ) > 0 )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        eZContentObject::expireAllCache();
    }
    $Module->redirectTo( "/role/view/$roleID/" );
}
else if ( is_numeric( $roleID ) )
{
    $http->setSessionVariable( "BrowseFromPage", "/role/assign/" . $roleID . "/" );

    $http->setSessionVariable( "BrowseActionName", "AssignRole" );
    $http->setSessionVariable( "BrowseActionName", "AssignRole" );
    $http->setSessionVariable( "BrowseReturnType", "ObjectID" );

    $Module->redirectTo( "/content/browse/5/" );
    return;
}



?>
