<?php
//
// Created on: <01-Aug-2002 10:40:10 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

include_once( "kernel/classes/ezorder.php" );

$module =& $Params["Module"];

$tpl =& templateInit();

$offset = $Params['Offset'];
$limit = 15;

$http =& eZHttpTool::instance();


// Todo, use viewparameters instead of session variable
if ( $http->hasSessionVariable( "OrderSortField" ) )
{
    $sortField = $http->sessionVariable( "OrderSortField" );
}
else
{
    $sortField = "created";
}

if ( $http->hasSessionVariable( "OrderSortOrder" ) )
{
    $sortOrder = $http->sessionVariable( "OrderSortOrder" );
}
else
{
    $sortOrder = "asc";
}

if ( $http->hasPostVariable( "SortButton" ) )
{
    $sortField = $http->postVariable( "SortField" );
    $sortOrder = $http->postVariable( "SortOrder" );
    $http->setSessionVariable( "OrderSortField", $sortField );
    $http->setSessionVariable( "OrderSortOrder", $sortOrder );
}

if ( $http->hasPostVariable( "RemoveButton" ) )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray =& $http->postVariable( 'DeleteIDArray' );
        if ( $deleteIDArray !== null )
        {
            $http->setSessionVariable( 'DeleteOrderIDArray', $deleteIDArray );
            print_r($deleteIDArray );
            $Module->redirectTo( $Module->functionURI( 'removeorder' ) . '/' );
        }
    }
}

$orderArray =& eZOrder::active( true, $offset, $limit, $sortField, $sortOrder );
$orderCount = eZOrder::activeCount( true, $offset );

$tpl->setVariable( "order_list", $orderArray );
$tpl->setVariable( "order_list_count", $orderCount );
$tpl->setVariable( "limit", $limit );

$viewParameters = array( 'offset' => $offset );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( "sort_field", $sortField );
$tpl->setVariable( "sort_order", $sortOrder );

$path = array();
$path[] = array( 'text' => ezi18n( 'kernel/shop', 'Order list' ),
                 'url' => false );

$Result = array();
$Result['path'] =& $path;

$Result['content'] =& $tpl->fetch( "design:shop/orderlist.tpl" );
?>
