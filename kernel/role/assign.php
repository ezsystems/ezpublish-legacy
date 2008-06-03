<?php
//
//
// Created on: <16-ïËÔ-2002 10:45:47 sp>
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

//include_once( 'kernel/classes/ezrole.php' );
//include_once( 'kernel/classes/ezcontentbrowse.php' );

$http = eZHTTPTool::instance();

$Module = $Params['Module'];
$roleID = $Params['RoleID'];
$limitIdent = $Params['LimitIdent'];
$limitValue = $Params['LimitValue'];

if ( $http->hasPostVariable( 'AssignSectionCancelButton' ) )
{
    $Module->redirectTo( '/role/view/' . $roleID );
}

if ( $http->hasPostVariable( 'BrowseCancelButton' ) )
{
    if ( $http->hasPostVariable( 'BrowseCancelURI' ) )
    {
        return $Module->redirectTo( $http->postVariable( 'BrowseCancelURI' ) );
    }
}

if ( $http->hasPostVariable( 'AssignSectionID' ) &&
     $http->hasPostVariable( 'SectionID' ) )
{
    $Module->redirectTo( '/role/assign/' . $roleID . '/' . $limitIdent . '/' . $http->postVariable( 'SectionID' ) );
}
else if ( $http->hasPostVariable( 'BrowseActionName' ) and
          $http->postVariable( 'BrowseActionName' ) == 'SelectObjectRelationNode' )
{
    $selectedNodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
    if ( count( $selectedNodeIDArray ) == 1 )
    {
        $limitValue = $selectedNodeIDArray[0];
    }
    $Module->redirectTo( '/role/assign/' . $roleID . '/' . $limitIdent . '/' . $limitValue );
}
else if ( $http->hasPostVariable( 'BrowseActionName' ) and
          $http->postVariable( 'BrowseActionName' ) == 'AssignRole' )
{
    $selectedObjectIDArray = $http->postVariable( 'SelectedObjectIDArray' );
    $role = eZRole::fetch( $roleID );

    $db = eZDB::instance();
    $db->begin();
    foreach ( $selectedObjectIDArray as $objectID )
    {
        $role->assignToUser( $objectID, $limitIdent, $limitValue );
    }
    // Clear role caches.
    eZRole::expireCache();

    $db->commit();
    if ( count( $selectedObjectIDArray ) > 0 )
    {
        //include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearAllContentCache();
    }

    /* Clean up policy cache */
    //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
    eZUser::cleanupCache();

    $Module->redirectTo( '/role/view/' . $roleID );
}
else if ( is_string( $limitIdent ) && !isset( $limitValue ) )
{
    switch( $limitIdent )
    {
        case 'subtree':
        {
            eZContentBrowse::browse( array( 'action_name' => 'SelectObjectRelationNode',
                                            'from_page' => '/role/assign/' . $roleID . '/' . $limitIdent,
                                            'cancel_page' => '/role/view/' . $roleID ),
                                     $Module );
            return;
        } break;

        case 'section':
        {
            require_once( 'kernel/common/template.php' );
            //include_once( 'kernel/classes/ezsection.php' );
            $sectionArray = eZSection::fetchList( );
            $tpl = templateInit();
            $tpl->setVariable( 'section_array', $sectionArray );
            $tpl->setVariable( 'role_id', $roleID );
            $tpl->setVariable( 'limit_ident', $limitIdent );

            $Result = array();
            $Result['content'] = $tpl->fetch( 'design:role/assign_limited_section.tpl' );
            $Result['path'] = array( array( 'url' => false,
                                            'text' => ezi18n( 'kernel/role', 'Limit on section' ) ) );
            return;
        } break;

        default:
        {
            eZDebug::writeWarning( 'Unsupported assign limitation: ' . $limitIdent );
            $Module->redirectTo( '/role/view/' . $roleID );
        } break;
    }
}
else if ( is_numeric( $roleID ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'AssignRole',
                                    'from_page' => '/role/assign/' . $roleID . '/' . $limitIdent . '/' . $limitValue,
                                    'cancel_page' => '/role/view/' . $roleID ),
                             $Module );

    return;
}

?>
