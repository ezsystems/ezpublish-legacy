<?php
//
// Created on: <01-Aug-2002 10:40:10 bf>
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

require_once( 'kernel/common/template.php' );
//include_once( 'kernel/classes/ezorder.php' );
//include_once( 'kernel/classes/ezorderstatus.php' );
//include_once( 'kernel/classes/ezpreferences.php' );

$module = $Params['Module'];

$tpl = templateInit();

$offset = $Params['Offset'];
$limit = 50;


if( eZPreferences::value( 'admin_archivelist_sortfield' ) )
{
    $sortField = eZPreferences::value( 'admin_archivelist_sortfield' );
}

if ( !isset( $sortField ) || ( ( $sortField != 'created' ) && ( $sortField!= 'user_name' ) ) )
{
    $sortField = 'created';
}

if( eZPreferences::value( 'admin_archivelist_sortorder' ) )
{
    $sortOrder = eZPreferences::value( 'admin_archivelist_sortorder' );
}

if ( !isset( $sortOrder ) || ( ( $sortOrder != 'asc' ) && ( $sortOrder!= 'desc' ) ) )
{
    $sortOrder = 'asc';
}

$http = eZHTTPTool::instance();

// Unarchive options.
if ( $http->hasPostVariable( 'UnarchiveButton' ) )
{
    if ( $http->hasPostVariable( 'OrderIDArray' ) )
    {
        $orderIDArray = $http->postVariable( 'OrderIDArray' );
        if ( $orderIDArray !== null )
        {
            $http->setSessionVariable( 'OrderIDArray', $orderIDArray );
            $Module->redirectTo( $Module->functionURI( 'unarchiveorder' ) . '/' );
        }
    }
}

$archiveArray = eZOrder::active( true, $offset, $limit, $sortField, $sortOrder, eZOrder::SHOW_ARCHIVED );
$archiveCount = eZOrder::activeCount( eZOrder::SHOW_ARCHIVED );

$tpl->setVariable( 'archive_list', $archiveArray );
$tpl->setVariable( 'archive_list_count', $archiveCount );
$tpl->setVariable( 'limit', $limit );

$viewParameters = array( 'offset' => $offset );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'sort_field', $sortField );
$tpl->setVariable( 'sort_order', $sortOrder );

$Result = array();
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/shop', 'Order list' ),
                                'url' => false ) );

$Result['content'] = $tpl->fetch( 'design:shop/archivelist.tpl' );
?>
