<?php
//
// Created on: <25-Nov-2002 15:40:10 wy>
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

// TODO: it was not in the original code, but we may consider to add support for "folder with products",
//       not only products (i.e. objects with attribute of the ezprice datatype).

require_once( 'kernel/common/template.php' );
$module = $Params['Module'];

if ( !isset( $Params['DiscountGroupID'] ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}
else
{
    $discountGroupID = $Params['DiscountGroupID'];
}

$discountRuleID = false;

if ( isset( $Params['DiscountRuleID'] ) )
{
    $discountRuleID = $Params['DiscountRuleID'];
}

$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( 'DiscardButton' ) )
{
    return $module->redirectTo( $module->functionURI( 'discountgroupview' ) . '/' . $discountGroupID );
}


if ( $http->hasPostVariable( 'BrowseProductButton' ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'FindProduct',
                                    'description_template' => 'design:shop/browse_discountproduct.tpl',
                                    'keys' => array( 'discountgroup_id' => $discountGroupID,
                                                     'discountrule_id' => $discountRuleID ),
                                    'content' => array( 'discountgroup_id' => $discountGroupID,
                                                        'discountrule_id' => $discountRuleID ),
                                    'persistent_data' => array( 'discountrule_name' => $http->postVariable( 'discountrule_name' ),
                                                                'discountrule_percent' => $http->postVariable( 'discountrule_percent' ),
                                                                'Contentclasses' => ( $http->hasPostVariable( 'Contentclasses' ) )? serialize( $http->postVariable( 'Contentclasses' ) ): '',
                                                                'Sections' => ( $http->hasPostVariable( 'Sections' ) )? serialize( $http->postVariable( 'Sections' ) ): '',
                                                                'Products' => ( $http->hasPostVariable( 'Products' ) )? serialize( $http->postVariable( 'Products' ) ): '' ),
                                    'from_page' => "/shop/discountruleedit/$discountGroupID/$discountRuleID" ),
                             $module );
    return;
}

if ( $http->hasPostVariable( 'discountrule_name' ) )
{
    // if it has post variables, the values will be taken from POST variables instead of object itself
    $locale = eZLocale::instance();

    $discountRuleName = $http->postVariable( 'discountrule_name' );
    $discountRulePercent = $locale->internalNumber( $http->postVariable( 'discountrule_percent' ) );

    $discountRuleSelectedClasses = array();
    if ( $http->hasPostVariable( 'Contentclasses' ) && $http->postVariable( 'Contentclasses' ) )
    {
        $discountRuleSelectedClasses = $http->postVariable( 'Contentclasses' );
        if ( !is_array( $discountRuleSelectedClasses ) )
        {
            $discountRuleSelectedClasses = unserialize( $discountRuleSelectedClasses );
        }
    }

    $discountRuleSelectedSections = array();
    if ( $http->hasPostVariable( 'Sections' ) && $http->postVariable( 'Sections' ) )
    {
        $discountRuleSelectedSections = $http->postVariable( 'Sections' );
        if ( !is_array( $discountRuleSelectedSections ) )
        {
            $discountRuleSelectedSections = unserialize( $discountRuleSelectedSections );
        }
    }

    $discountRuleSelectedProducts = array();
    if ( $http->hasPostVariable( 'Products' ) && $http->postVariable( 'Products' ) )
    {
        $discountRuleSelectedProducts = $http->postVariable( 'Products' );
        if ( !is_array( $discountRuleSelectedProducts ) )
        {
            $discountRuleSelectedProducts = unserialize( $discountRuleSelectedProducts );
        }
    }

    $discountRule = array( 'id' => $discountRuleID ,
                           'name' => $discountRuleName,
                           'discount_percent' => $discountRulePercent );
}
else
{
    // read variables from object, if it exists, if not, create new one...
    if ( $discountRuleID )
    {
        // exists => read needed info from db
        $discountRule = eZDiscountSubRule::fetch( $discountRuleID );
        if ( !$discountRule )
        {
            return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
        }

        $discountRuleSelectedClasses = array();
        $discountRuleSelectedClassesValues = eZDiscountSubRuleValue::fetchBySubRuleID( $discountRuleID, 0, false );
        foreach( $discountRuleSelectedClassesValues as $value )
        {
            $discountRuleSelectedClasses[] = $value['value'];
        }
        if ( count( $discountRuleSelectedClasses ) == 0 )
        {
            $discountRuleSelectedClasses[] = -1;
        }

        $discountRuleSelectedSections = array();
        $discountRuleSelectedSectionsValues = eZDiscountSubRuleValue::fetchBySubRuleID( $discountRuleID, 1, false );
        foreach( $discountRuleSelectedSectionsValues as $value )
        {
            $discountRuleSelectedSections[] = $value['value'];
        }
        if ( count( $discountRuleSelectedSections ) == 0 )
        {
            $discountRuleSelectedSections[] = -1;
        }

        $discountRuleSelectedProductsValues = eZDiscountSubRuleValue::fetchBySubRuleID( $discountRuleID, 2, false );
        foreach( $discountRuleSelectedProductsValues as $value )
        {
            $discountRuleSelectedProducts[] = $value['value'];
        }
    }
    else
    {
        // does not exist => create new one, but do not store...
        $discountRuleName = ezi18n( 'design/admin/shop/discountruleedit', 'New discount rule' );
        $discountRulePercent = 0.0;
        $discountRuleSelectedClasses = array( -1 );
        $discountRuleSelectedSections = array( -1 );
        $discountRuleSelectedProducts = array();

        $discountRule = array( 'id' => 0,
                               'name' => $discountRuleName,
                               'discount_percent' => $discountRulePercent );
    }
}

if ( $module->isCurrentAction( 'FindProduct' ) )
{
    // returning from browse; add products to product list
    $result = eZContentBrowse::result( 'FindProduct' );
    if ( $result )
    {
        $discountRuleSelectedProducts = array_merge( $discountRuleSelectedProducts, $result );
        $discountRuleSelectedProducts = array_unique( $discountRuleSelectedProducts );
    }
}

if ( $http->hasPostVariable( 'DeleteProductButton' ) )
{
    // remove products from list:
    if ( $http->hasPostVariable( 'DeleteProductIDArray' ) )
    {
        $deletedIDList = $http->postVariable( 'DeleteProductIDArray' );
        $arrayKeys = array_keys( $discountRuleSelectedProducts );

        foreach( $arrayKeys as $key )
        {
            if ( in_array( $discountRuleSelectedProducts[$key], $deletedIDList ) )
            {
                unset( $discountRuleSelectedProducts[$key] );
            }
        }
    }
}

$productList = array();
foreach ( $discountRuleSelectedProducts as $productID )
{
    $object = eZContentObject::fetch( $productID );
    if ( eZShopFunctions::isProductObject( $object ) )
        $productList[] = $object;
}

if ( $http->hasPostVariable( 'StoreButton' ) )
{
    // remove products stored in the database and store them again
    $db = eZDB::instance();
    $db->begin();
    if ( $discountRuleID )
    {
        $discountRule = eZDiscountSubRule::fetch( $discountRuleID );
        eZDiscountSubRuleValue::removeBySubRuleID ( $discountRuleID );
    }
    else
    {
        $discountRule = eZDiscountSubRule::create( $discountGroupID );
        $discountRule->store();
        $discountRuleID = $discountRule->attribute( 'id' );
    }

    $discountRule->setAttribute( 'name', trim( $http->postVariable( 'discountrule_name' ) ) );
    $discountRule->setAttribute( 'discount_percent', $http->postVariable( 'discountrule_percent' ) );
    $discountRule->setAttribute( 'limitation', '*' );

    if ( $http->hasPostVariable( 'Products' ) && $http->postVariable( 'Products' ) )
    {
        foreach( $productList as $product )
        {
            $ruleValue = eZDiscountSubRuleValue::create( $discountRuleID, $product->attribute( 'id' ), 2 );
            $ruleValue->store();
        }
        $discountRule->setAttribute( 'limitation', false );
    }
    else
    {
        if ( $discountRuleSelectedClasses && !in_array( -1, $discountRuleSelectedClasses ) )
        {
            foreach( $discountRuleSelectedClasses as $classID )
            {
                $ruleValue = eZDiscountSubRuleValue::create( $discountRuleID, $classID, 0 );
                $ruleValue->store();
            }
            $discountRule->setAttribute( 'limitation', false );
        }
        if ( $discountRuleSelectedSections && !in_array( -1, $discountRuleSelectedSections ) )
        {
            foreach( $discountRuleSelectedSections as $sectionID )
            {
                $ruleValue = eZDiscountSubRuleValue::create( $discountRuleID, $sectionID, 1 );
                $ruleValue->store();
            }
            $discountRule->setAttribute( 'limitation', false );
        }
    }

    $discountRule->store();
    $db->commit();

    // we changed prices => remove content cache
    eZContentCacheManager::clearAllContentCache();

    return $module->redirectTo( $module->functionURI( 'discountgroupview' ) . '/' . $discountGroupID );
}

$classList = eZContentClass::fetchList();
$productClassList = array();
foreach ( $classList as $class )
{
    if ( eZShopFunctions::isProductClass( $class ) )
        $productClassList[] = $class;
}

$sectionList = eZSection::fetchList();

$tpl = templateInit();

$tpl->setVariable( 'module', $module );
$tpl->setVariable( 'discountgroup_id', $discountGroupID );
$tpl->setVariable( 'discountrule', $discountRule );

$tpl->setVariable( 'product_class_list', $productClassList );
$tpl->setVariable( 'section_list', $sectionList );

$tpl->setVariable( 'class_limitation_list', $discountRuleSelectedClasses );
$tpl->setVariable( 'section_limitation_list', $discountRuleSelectedSections );
$tpl->setVariable( 'product_list', $productList );

$tpl->setVariable( 'class_any_selected', in_array( -1, $discountRuleSelectedClasses ) );
$tpl->setVariable( 'section_any_selected', in_array( -1, $discountRuleSelectedSections ) );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:shop/discountruleedit.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', 'Editing rule' ) ) );

?>
