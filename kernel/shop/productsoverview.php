<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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

$tpl = eZTemplate::factory();
$tpl->setVariable( 'product_class_list', $productClassList );
$tpl->setVariable( 'product_class', $productClass );
$tpl->setVariable( 'price_attribute_identifier', $priceAttributeIdentifier );
$tpl->setVariable( 'sorting_field', $sortingField );
$tpl->setVariable( 'sorting_order', $sortingOrder );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/productsoverview.tpl" );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/shop', 'Products overview' ),
                                'url' => false ) );

?>
