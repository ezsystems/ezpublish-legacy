<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$CustomerID = $Params['CustomerID'];
$Email = $Params['Email'];
$module = $Params['Module'];


$http = eZHTTPTool::instance();

$tpl = eZTemplate::factory();

$Email = urldecode( $Email );
$productList = eZOrder::productList( $CustomerID, $Email );
$orderList = eZOrder::orderList( $CustomerID, $Email );

$tpl->setVariable( "product_list", $productList );

$tpl->setVariable( "order_list", $orderList );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/customerorderview.tpl" );
$path = array();
$path[] = array( 'url' => '/shop/orderlist',
                 'text' => ezpI18n::tr( 'kernel/shop', 'Order list' ) );
$path[] = array( 'url' => false,
                 'text' => ezpI18n::tr( 'kernel/shop', 'Customer order view' ) );
$Result['path'] = $path;

?>
