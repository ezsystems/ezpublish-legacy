<?php
//
// Created on: <17-Feb-2006 15:02:37 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

include_once( "kernel/common/template.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );
include_once( "kernel/classes/ezproductcategory.php" );

function applyChanges( $module, $http )
{
    $productCategories = eZProductCategory::fetchList( true );

    $db =& eZDB::instance();
    $db->begin();
    foreach ( $productCategories as $cat )
    {
        $id = $cat->attribute( 'id' );

        if ( !$http->hasPostVariable( "category_name_" . $id ) )
            continue;

        $name = $http->postVariable( "category_name_" . $id );
        $cat->setAttribute( 'name', $name );
        $cat->store();
    }
    $db->commit();
}

$module =& $Params["Module"];
$http   =& eZHttpTool::instance();

if ( $http->hasPostVariable( "AddCategoryButton" ) )
{
    applyChanges( $module, $http );

    $category = eZProductCategory::create();
    $category->store();
}
elseif ( $http->hasPostVariable( "RemoveCategoryButton" ) )
{
    applyChanges( $module, $http );

    if ( !$http->hasPostVariable( "CategoryIDList" ) )
        $catIDList = array();
    else
        $catIDList = $http->postVariable( "CategoryIDList" );

    $db =& eZDB::instance();
    $db->begin();
    foreach ( $catIDList as $catID )
        eZProductCategory::remove( $catID );
    $db->commit();
}
elseif ( $http->hasPostVariable( "SaveCategoriesButton" ) )
{
    applyChanges( $module, $http );
}

$productCategories = eZProductCategory::fetchList( true );

$tpl =& templateInit();
$tpl->setVariable( 'categories', $productCategories );

$path = array();
$path[] = array( 'text' => ezi18n( 'kernel/shop/productcategories', 'Product categories' ),
                 'url' => false );

$Result = array();
$Result['path'] =& $path;
$Result['content'] =& $tpl->fetch( "design:shop/productcategories.tpl" );

?>
