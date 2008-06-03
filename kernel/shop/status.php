<?php
//
// Created on: <07-Nov-2005 18:07:10 jhe>
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

require_once( "kernel/common/template.php" );
//include_once( "kernel/classes/ezorderstatus.php" );
//include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$messages = array();

if ( $http->hasPostVariable( "SaveOrderStatusButton" ) or
     $http->hasPostVariable( "AddOrderStatusButton" ) or
     $http->hasPostVariable( "RemoveOrderStatusButton" ) )
{
    $orderStatusArray = eZOrderStatus::fetchList( true, true );
    foreach ( $orderStatusArray as $orderStatus )
    {
        $id = $orderStatus->attribute( 'id' );
        if ( $http->hasPostVariable( "orderstatus_name_" . $id ) )
        {
            $orderStatus->setAttribute( 'name', $http->postVariable( "orderstatus_name_" . $id ) );
        }
        // Only check the checkbox value if the has_input variable is set
        if ( $http->hasPostVariable( "orderstatus_active_has_input_" . $id ) )
        {
            $orderStatus->setAttribute( 'is_active', $http->hasPostVariable( "orderstatus_active_" . $id ) ? 1: 0 );
        }
        $orderStatus->sync();
    }

    eZOrderStatus::flush();
}

if ( $http->hasPostVariable( "AddOrderStatusButton" ) )
{
    $orderStatus = eZOrderStatus::create();
    $orderStatus->storeCustom();
    $messages[] = array( 'description' => ezi18n( 'kernel/shop', 'New order status was successfully added.' ) );
}

if ( $http->hasPostVariable( "SaveOrderStatusButton" ) )
{
    $messages[] = array( 'description' => ezi18n( 'kernel/shop', 'Changes to order status were successfully stored.' ) );
}

if ( $http->hasPostVariable( "RemoveOrderStatusButton" ) )
{
    $orderStatusIDList = array();
    if ( $http->hasPostVariable( 'orderStatusIDList' ) )
        $orderStatusIDList = $http->postVariable( "orderStatusIDList" );

    $hasRemoved = false;
    $triedRemoveInternal = false;
    foreach ( $orderStatusIDList as $orderStatusID )
    {
        $status = eZOrderStatus::fetch( $orderStatusID );
        // Internal status items must not be removed
        if ( $status->isInternal() )
        {
            $triedRemoveInternal = true;
            continue;
        }
        $status->removeThis();
        $hasRemoved = true;
    }
    if ( $hasRemoved )
        $messages[] = array( 'description' => ezi18n( 'kernel/shop', 'Selected order statuses were successfully removed.' ) );
    if ( $triedRemoveInternal )
        $messages[] = array( 'description' => ezi18n( 'kernel/shop', 'Internal orders cannot be removed.' ) );
}

$orderStatusArray = eZOrderStatus::fetchList( true, true );

$tpl = templateInit();
$tpl->setVariable( "orderstatus_array", $orderStatusArray );
$tpl->setVariable( "module", $module );
$tpl->setVariable( "messages", $messages );

$path = array();
$path[] = array( 'text' => ezi18n( 'kernel/shop', 'Order list' ),
                 'url' => 'shop/orderlist' );
$path[] = array( 'text' => ezi18n( 'kernel/shop', 'Status' ),
                 'url' => false );

$Result = array();
$Result['path'] = $path;
$Result['content'] = $tpl->fetch( "design:shop/status.tpl" );

?>
