<?php
//
// Created on: <31-Jul-2002 16:47:15 bf>
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

$Module = array( "name" => "eZShop",
                 "variable_params" => true );

$ViewList = array();
$ViewList["orderview"] = array(
    "script" => "orderview.php",
    "params" => array( "OrderID" ) );

$ViewList["basket"] = array(
    "script" => "basket.php",
    "params" => array(  ) );

$ViewList["wishlist"] = array(
    "script" => "wishlist.php",
    "params" => array(  ) );

$ViewList["orderlist"] = array(
    "script" => "orderlist.php",
    "unordered_params" => array( "offset" => "Offset" ),
    "params" => array(  ) );

$ViewList["confirmorder"] = array(
    "script" => "confirmorder.php",
    "params" => array(  ) );

$ViewList["vattype"] = array(
    "script" => "vattype.php",
    "params" => array(  ) );

$ViewList["discountgroup"] = array(
    "script" => "discountgroup.php",
    "params" => array(  ) );
$ViewList["discountgroupedit"] = array(
    "script" => "discountgroupedit.php",
    "params" => array( 'DiscountGroupID' ) );
$ViewList["discountruleedit"] = array(
    "script" => "discountruleedit.php",
    "params" => array( 'DiscountGroupID', 'DiscountRuleID'  ) );
$ViewList["discountgroupmembershipview"] = array(
    "script" => "discountgroupmembershipview.php",
    "params" => array( 'DiscountGroupID' ) );
?>
