<?php
//
// Created on: <04-Nov-2005 12:26:52 dl>
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

/*! \file ezproductsoverview.php
*/

include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezpreferences.php' );
include_once( 'kernel/shop/classes/ezshopfunctions.php' );

$module =& $Params['Module'];
$offset =& $Params['Offset'];
$productClassIdentifier =& $Params['ProductClass'];
$productClass = false;
$priceAttributeIdentifier = false;

if ( $module->isCurrentAction( 'ShowProducts' ) )
    $productClassIdentifier = $module->hasActionParameter( 'ProductClass' ) ? $module->actionParameter( 'ProductClass' ) : false;

$productClassList = eZShopFunctions::productClassList();

// find selected product class
if ( count( $productClassList ) > 0 )
{
    if ( $productClassIdentifier )
    {
        $keys = array_keys( $productClassList );
        foreach( $keys as $key )
        {
            $productClass =& $productClassList[$key];
            if ( $productClass->attribute( 'identifier' ) === $productClassIdentifier )
                break;
        }
    }
    else
    {
        // use first element of $productClassList
        $productClass =& $productClassList[0];
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

$viewParameters = array( 'offset' => $offset );

$tpl =& templateInit();
$tpl->setVariable( 'product_class_list', $productClassList );
$tpl->setVariable( 'product_class', $productClass );
$tpl->setVariable( 'price_attribute_identifier', $priceAttributeIdentifier );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/productsoverview.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/shop', 'Products overview' ),
                                'url' => false ) );

?>
