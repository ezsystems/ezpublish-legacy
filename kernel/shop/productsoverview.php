<?php
//
// Created on: <04-Nov-2005 12:26:52 dl>
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

/*! \file ezproductsoverview.php
*/

require_once( 'kernel/common/template.php' );
$module = $Params['Module'];
$offset = $Params['Offset'];
$productClassIdentifier = $Params['ProductClass'];
$productClass = false;
$priceAttributeIdentifier = false;

if ( $module->isCurrentAction( 'Sort' ) )
{
    $productClassIdentifier = $module->hasActionParameter( 'ProductClass' ) ? $module->actionParameter( 'ProductClass' ) : false;
    $sortingField = $module->hasActionParameter( 'SortingField' ) ? $module->actionParameter( 'SortingField' ) : 'none';
    $sortingOrder = $module->hasActionParameter( 'SortingOrder' ) ? $module->actionParameter( 'SortingOrder' ) : 'asc';

    eZPreferences::setValue( 'productsoverview_sorting_field', $sortingField );
    eZPreferences::setValue( 'productsoverview_sorting_order', $sortingOrder );
}

if ( $module->isCurrentAction( 'ShowProducts' ) )
    $productClassIdentifier = $module->hasActionParameter( 'ProductClass' ) ? $module->actionParameter( 'ProductClass' ) : false;

$productClassList = eZShopFunctions::productClassList();

// find selected product class
if ( count( $productClassList ) > 0 )
{
    if ( $productClassIdentifier )
    {
        foreach( $productClassList as $productClassItem )
        {
            if ( $productClassItem->attribute( 'identifier' ) === $productClassIdentifier )
            {
                $productClass = $productClassItem;
                break;
            }
        }
    }
    else
    {
        // use first element of $productClassList
        $productClass = $productClassList[0];
    }
}

if ( is_object( $productClass ) )
    $priceAttributeIdentifier = eZShopFunctions::priceAttributeIdentifier( $productClass );

switch ( eZPreferences::value( 'productsoverview_list_limit' ) )
{
    case '2': { $limit = 25; } break;
    case '3': { $limit = 50; } break;
    default:  { $limit = 10; } break;
}

$sortingField = eZPreferences::value( 'productsoverview_sorting_field' );
$sortingOrder = eZPreferences::value( 'productsoverview_sorting_order' );

$viewParameters = array( 'offset' => $offset );

$tpl = templateInit();
$tpl->setVariable( 'product_class_list', $productClassList );
$tpl->setVariable( 'product_class', $productClass );
$tpl->setVariable( 'price_attribute_identifier', $priceAttributeIdentifier );
$tpl->setVariable( 'sorting_field', $sortingField );
$tpl->setVariable( 'sorting_order', $sortingOrder );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/productsoverview.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/shop', 'Products overview' ),
                                'url' => false ) );

?>
