<?php
//
// Definition of Customorderview class
//
// Created on: <01-Mar-2004 15:53:50 wy>
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

/*! \file customorderview.php
*/

$CustomerID = $Params['CustomerID'];
$Email = $Params['Email'];
$module =& $Params['Module'];
include_once( "kernel/common/template.php" );

include_once( "kernel/classes/ezorder.php" );

$http =& eZHTTPTool::instance();

$tpl =& templateInit();

$Email = urldecode( $Email );
$productList =& eZOrder::productList( $CustomerID, $Email );
$orderList =& eZOrder::orderList( $CustomerID, $Email );

$tpl->setVariable( "product_list", $productList );

$tpl->setVariable( "order_list", $orderList );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/customerorderview.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', ' Customer order view' ) ) );

?>
