<?php
//
// Created on: <31-Jul-2002 16:47:15 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.5.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

$Module = array( "name" => "eZShop",
                 "variable_params" => true );

$ViewList = array();
$ViewList["orderview"] = array(
    "functions" => array( 'buy' ),
    "script" => "orderview.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array( "OrderID" ) );

$ViewList["basket"] = array(
    "functions" => array( 'buy' ),
    "script" => "basket.php",
    "default_navigation_part" => 'ezmynavigationpart',
    'unordered_params' => array( 'error' => 'Error' ),
    "params" => array(  ) );

$ViewList["register"] = array(
    "functions" => array( 'buy' ),
    "script" => "register.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'CancelButton' => 'Cancel'
                                    ),
    "params" => array(  ) );

$ViewList["userregister"] = array(
    "functions" => array( 'buy' ),
    "script" => "userregister.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'CancelButton' => 'Cancel'
                                    )
    );

$ViewList["wishlist"] = array(
    "functions" => array( 'buy' ),
    "script" => "wishlist.php",
    "default_navigation_part" => 'ezmynavigationpart',
    'unordered_params' => array( 'offset' => 'Offset' ),
    "params" => array(  ) );

$ViewList["orderlist"] = array(
    "functions" => array( 'administrate' ),
    "script" => "orderlist.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "unordered_params" => array( "offset" => "Offset" ),
    "params" => array(  ) );

$ViewList["removeorder"] = array(
    "functions" => array( 'administrate' ),
    "script" => "removeorder.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array() );

$ViewList["customerlist"] = array(
    "functions" => array( 'administrate' ),
    "script" => "customerlist.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "unordered_params" => array( 'offset' => 'Offset' ),
    "params" => array(  ) );

$ViewList["customerorderview"] = array(
    "functions" => array( 'administrate' ),
    "script" => "customerorderview.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array( "CustomerID", "Email" ) );

$ViewList["statistics"] = array(
    "functions" => array( 'administrate' ),
    "script" => "orderstatistics.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array( 'Year', 'Month' ) );

$ViewList["confirmorder"] = array(
    "functions" => array( 'buy' ),
    "script" => "confirmorder.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array(  ) );

$ViewList["checkout"] = array(
    "functions" => array( 'buy' ),
    "script" => "checkout.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array(  ) );

$ViewList["vattype"] = array(
    "functions" => array( 'setup' ),
    "script" => "vattype.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array(  ) );

$ViewList["discountgroup"] = array(
    "functions" => array( 'setup' ),
    "script" => "discountgroup.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array(  ) );

$ViewList["discountgroupedit"] = array(
    "functions" => array( 'setup' ),
    "script" => "discountgroupedit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array( 'DiscountGroupID' ) );

$ViewList["discountruleedit"] = array(
    "functions" => array( 'setup' ),
    "script" => "discountruleedit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    "params" => array( 'DiscountGroupID', 'DiscountRuleID'  ) );

$ViewList["discountgroupview"] = array(
    "script" => "discountgroupmembershipview.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    "params" => array( 'DiscountGroupID' ) );

$FunctionList['setup'] = array( );
$FunctionList['administrate'] = array( );
$FunctionList['buy'] = array( );

?>
