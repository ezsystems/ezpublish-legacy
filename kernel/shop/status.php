<?php
//
// Created on: <07-Nov-2005 18:07:10 jhe>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

include_once( "kernel/common/template.php" );
include_once( "kernel/classes/ezorderstatus.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module =& $Params["Module"];
$http =& eZHttpTool::instance();
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
            $orderStatus->setAttribute( 'is_active', $http->hasPostVariable( "orderstatus_active_" . $id ) );
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
        $status->remove();
        $hasRemoved = true;
    }
    if ( $hasRemoved )
        $messages[] = array( 'description' => ezi18n( 'kernel/shop', 'Selected order statuses were successfully removed.' ) );
    if ( $triedRemoveInternal )
        $messages[] = array( 'description' => ezi18n( 'kernel/shop', 'Internal orders cannot be removed.' ) );
}

$orderStatusArray = eZOrderStatus::fetchList( true, true );

$tpl =& templateInit();
$tpl->setVariable( "orderstatus_array", $orderStatusArray );
$tpl->setVariable( "module", $module );
$tpl->setVariable( "messages", $messages );

$path = array();
$path[] = array( 'text' => ezi18n( 'kernel/shop', 'Order list' ),
                 'url' => 'shop/orderlist' );
$path[] = array( 'text' => ezi18n( 'kernel/shop', 'Status' ),
                 'url' => false );

$Result = array();
$Result['path'] =& $path;
$Result['content'] =& $tpl->fetch( "design:shop/status.tpl" );

?>
