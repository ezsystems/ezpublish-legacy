<?php
//
// Created on: <17-Feb-2006 15:02:37 vs>
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
//include_once( "lib/ezutils/classes/ezhttppersistence.php" );
//include_once( "kernel/classes/ezproductcategory.php" );

/*!
  Apply changes made to categories' names.

  \return errors, empty array if none exists
 */
function applyChanges( $module, $http, $productCategories = false )
{
    $errors = array();
    if ( $productCategories === false )
        $productCategories = eZProductCategory::fetchList();

    $db = eZDB::instance();
    $db->begin();
    foreach ( $productCategories as $cat )
    {
        $id = $cat->attribute( 'id' );

        if ( !$http->hasPostVariable( "category_name_" . $id ) )
            continue;

        $name = $http->postVariable( "category_name_" . $id );

        if ( !$name )
        {
            $errors[] = ezi18n( 'kernel/shop/productcategories', 'Empty category names are not allowed (corrected).' );
            continue;
        }

        $cat->setAttribute( 'name', $name );
        $cat->store();
    }
    $db->commit();

    return $errors;
}

/**
 * Generate a unique category name.
 *
 * The generated name looks like "Product category X"
 * where X is a unique number.
 */
function generateUniqueCategoryName( $productCategories )
{
    $commonPart = ezi18n( 'kernel/shop/productcategories', 'Product category' );
    $maxNumber = 0;
    foreach ( $productCategories as $cat )
    {
        $catName = $cat->attribute( 'name' );

        if ( !preg_match( "/^$commonPart (\d+)/", $catName, $matches ) )
            continue;

        $curNumber = $matches[1];
        if ( $curNumber > $maxNumber )
            $maxNumber = $curNumber;
    }

    $maxNumber++;
    return "$commonPart $maxNumber";
}

$module = $Params['Module'];
$http   = eZHTTPTool::instance();
$tpl = templateInit();
$errors = false;

// Remove checked categories.
// Will check for categories' dependencies.
// If there are none just removes the categories.
// Otherwise shows confirmation dialog with the dependencies displayed.
if ( $module->isCurrentAction( 'Remove' ) )
{
    $errors = applyChanges( $module, $http );

    if ( !$module->hasActionParameter( 'CategoryIDList' ) )
        $catIDList = array();
    else
        $catIDList = $module->actionParameter( 'CategoryIDList' );

    if ( $catIDList )
    {
        // Find dependencies for the categories being removed.

        $deps = array();
        require_once( 'kernel/classes/ezvatrule.php' );
        foreach ( $catIDList as $catID )
        {
            $category = eZProductCategory::fetch( $catID );
            if ( !is_object( $category ) )
                continue;

            $catName  = $category->attribute( 'name' );
            $dependantRulesCount = eZVatRule::fetchCountByCategory( $catID );
            $dependantProductsCount = eZProductCategory::fetchProductCountByCategory( $catID );
            $deps[$catID] = array( 'name' => $catName,
                                   'affected_rules_count'    => $dependantRulesCount,
                                   'affected_products_count' => $dependantProductsCount );
        }

        // Skip the confirmation dialog if the categories
        // being removed have no conflicts.
        $haveDeps = false;
        foreach ( $deps as $dep )
        {
            if ( $dep['affected_rules_count'] > 0 || $dep['affected_products_count'] > 0 )
                $haveDeps = true;
        }

        // Show the confirmation dialog.
        if ( $haveDeps )
        {
            $tpl->setVariable( 'dependencies', $deps );
            $tpl->setVariable( 'category_ids', join( ',', $catIDList ) );
            $path = array( array( 'text' => ezi18n( 'kernel/shop/productcategories', 'Product categories' ),
                                  'url' => false ) );
            $Result = array();
            $Result['path'] = $path;
            $Result['content'] = $tpl->fetch( "design:shop/removeproductcategories.tpl" );
            return;
        }
        else
        {
            // Pass through.
            $module->setCurrentAction( 'ConfirmRemoval' );
        }

    }
}
// Silently remove specified categories.
if ( $module->isCurrentAction( 'ConfirmRemoval' ) )
{
    if ( !$module->hasActionParameter( 'CategoryIDList' ) )
        $catIDList = array();
    else
    {
        // The list of categories is a string if passed from the confirmation dialog
        // and an array if passed from another action.
        $catIDList = $module->actionParameter( 'CategoryIDList' );
        if ( is_string( $catIDList ) )
            $catIDList = explode( ',', $catIDList );
    }

    $db = eZDB::instance();
    $db->begin();
    foreach ( $catIDList as $catID )
        eZProductCategory::removeByID( (int) $catID );
    $db->commit();
}
// Add new category.
elseif ( $module->isCurrentAction( 'Add' ) )
{
    $productCategories = eZProductCategory::fetchList( true );
    $errors = applyChanges( $module, $http, $productCategories );

    $category = eZProductCategory::create();
    $category->setAttribute( 'name', generateUniqueCategoryName( $productCategories ) );
    $category->store();
    $tpl->setVariable( 'last_added_id', $category->attribute( 'id' ) );
}
// Apply changes made to categories' names.
elseif ( $module->isCurrentAction( 'StoreChanges' ) )
{
    $errors = applyChanges( $module, $http );
}

// (re-)fetch product categoried list to display them in the template
$productCategories = eZProductCategory::fetchList();

if ( is_array( $errors ) )
    $errors = array_unique( $errors );

$tpl->setVariable( 'categories', $productCategories );
$tpl->setVariable( 'errors', $errors );

$path = array();
$path[] = array( 'text' => ezi18n( 'kernel/shop/productcategories', 'Product categories' ),
                 'url' => false );
$Result = array();
$Result['path'] = $path;
$Result['content'] = $tpl->fetch( "design:shop/productcategories.tpl" );

?>
