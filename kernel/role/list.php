<?php
//
// Created on: <15-Aug-2002 14:37:29 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

//include_once( 'kernel/classes/ezcontentobject.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
//include_once( 'kernel/classes/ezcontentclass.php' );
//include_once( 'kernel/classes/ezcontentbrowse.php' );
require_once( 'kernel/common/template.php' );
//include_once( 'kernel/classes/ezrole.php' );
//include_once( 'kernel/classes/ezpreferences.php' );

$http = eZHTTPTool::instance();


$Module = $Params['Module'];

$offset = $Params['Offset'];

if( eZPreferences::value( 'admin_role_list_limit' ) )
{
    switch( eZPreferences::value( 'admin_role_list_limit' ) )
    {
        case '2': { $limit = 25; } break;
        case '3': { $limit = 50; } break;
        default:  { $limit = 10; } break;
    }
}
else
{
    $limit = 10;
}

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
   if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $http->postVariable( 'DeleteIDArray' );
        $db = eZDB::instance();
        $db->begin();
        foreach ( $deleteIDArray as $deleteID )
        {
            eZRole::removeRole( $deleteID );
        }
        // Clear role caches.
        eZRole::expireCache();

        // Clear all content cache.
        //include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearAllContentCache();

        $db->commit();
    }
}
// Redirect to content node browse in the user tree
// Assign the role for a user or group
if ( $Module->isCurrentAction( 'AssignRole' ) )
{
    $selectedObjectIDArray = eZContentBrowse::result( 'AssignRole' );

    foreach ( $selectedObjectIDArray as $objectID )
    {
        $role->assignToUser( $objectID );
    }
    // Clear role caches.
    eZRole::expireCache();

    // Clear all content cache.
    //include_once( 'kernel/classes/ezcontentcachemanager.php' );
    eZContentCacheManager::clearAllContentCache();
}

if ( $http->hasPostVariable( 'NewButton' )  )
{
    $role = eZRole::createNew( );
    return $Module->redirectToView( 'edit', array( $role->attribute( 'id' ) ) );
}

$viewParameters = array( 'offset' => $offset );
$tpl = templateInit();

$roles = eZRole::fetchByOffset( $offset, $limit, $asObject = true, $ignoreTemp = true );
$roleCount = eZRole::roleCount();
$tempRoles = eZRole::fetchList( $temporaryVersions = true );
$tpl->setVariable( 'roles', $roles );
$tpl->setVariable( 'role_count', $roleCount );
$tpl->setVariable( 'temp_roles', $tempRoles );
$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'limit', $limit );


$Result = array();
$Result['content'] = $tpl->fetch( 'design:role/list.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/role', 'Role list' ) ) );
?>
