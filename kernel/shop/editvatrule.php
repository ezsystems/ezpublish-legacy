<?php
//
// Created on: <18-Feb-2006 13:11:18 vs>
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

// Parameters: ruleID (optional)

$module =& $Params['Module'];

require_once( 'kernel/classes/ezvatrule.php' );
require_once( 'kernel/classes/ezproductcategory.php' );
require_once( 'kernel/classes/ezvattype.php' );

$errors = false;
$productCategories = eZProductCategory::fetchList();

/**
 * Check entered data.
 *
 * \return list of errors found, or false if the data is ok.
 */
function checkEnteredData( $country, $categories, $vatType )
{
    if ( $country && is_numeric( $vatType ) )
        return false;

    $errors = array();
    if ( !$country )
        $errors[] = ezi18n( 'kernel/shop/editvatrule', 'Choose a country.' );
    if ( !is_numeric( $vatType ) )
        $errors[] = ezi18n( 'kernel/shop/editvatrule', 'Choose a VAT type.' );

    return $errors;
}

if ( $module->isCurrentAction( 'Cancel' ) )
{
    return $module->redirectTo( $module->functionURI( 'vatrules' ) );
}
else if ( in_array( $module->currentAction(), array(  'Create', 'StoreChanges' ) ) )
{
    if ( $module->isCurrentAction( 'StoreChanges' ) )
        $ruleID = $module->actionParameter( 'RuleID' );

    $chosenCountry = $module->actionParameter( 'Country' );
    $chosenCategories = $module->hasActionParameter( 'Categories' ) ? $module->actionParameter( 'Categories' ) : array();
    $chosenVatType = $module->actionParameter( 'VatType' );

    // normalize data
    if ( in_array( '*', $chosenCategories ) )
        $chosenCategories = array();

    $errors = checkEnteredData( $chosenCountry, $chosenCategories, $chosenVatType );

    do // one-iteration loop to prevent high nesting levels
    {
        if ( $errors !== false )
            break;

        // The entered data is ok.
        if ( $module->isCurrentAction( 'StoreChanges' ) )
        {
            // Store changes made to the VAT rule.
            $vatRule = eZVatRule::fetch( $ruleID );

            if ( !is_object( $vatRule ) )
            {
                //$ruleID = null;
                $errors[] = ezi18n( 'kernel/shop/editvatrule', 'Rule not found' );
                break;
            }
        }
        else
        {
            // Create a new VAT rule...
            $vatRule = eZVatRule::create();
        }

        $vatRule->setAttribute( 'country', $chosenCountry );
        $vatRule->setAttribute( 'product_categories', $chosenCategories );
        $vatRule->setAttribute( 'vat_type', $chosenVatType );
        $vatRule->store();

        return $module->redirectTo( $module->functionURI( 'vatrules' ) );

    } while ( false );
}

if ( is_numeric( $ruleID ) )
{
    $tplVatRule      = eZVatRule::fetch( $ruleID );
    $tplCountry      = $tplVatRule->attribute( 'country' );
    $tplCategoryIDs  = $tplVatRule->attribute( 'product_categories_ids' );
    $tplVatTypeID    = $tplVatRule->attribute( 'vat_type' );

    $pathText = ezi18n( 'kernel/shop/editvatrule', 'Edit VAT charging rule' );
}
else
{
    $tplVatRule = null;
    $tplCountry = false;
    $tplVatTypeID = false;
    $tplCategoryIDs = array();

    $pathText = ezi18n( 'kernel/shop/editvatrule', 'Create new VAT charging rule' );
}

if ( $errors !== false )
{
    $tplCountry     = $chosenCountry;
    $tplCategoryIDs = $chosenCategories;
    $tplVatTypeID   = $chosenVatType;
}

$vatTypes = eZVatType::fetchList( true, true );

include_once( 'kernel/common/template.php' );
$tpl =& templateInit();

$tpl->setVariable( 'errors', $errors );
$tpl->setVariable( 'all_vat_types', $vatTypes );
$tpl->setVariable( 'all_product_categories', $productCategories );

$tpl->setVariable( 'rule',         $tplVatRule );
$tpl->setVariable( 'country',      $tplCountry );
$tpl->setVariable( 'category_ids', $tplCategoryIDs );
$tpl->setVariable( 'vat_type_id',  $tplVatTypeID );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/editvatrule.tpl" );
$Result['path'] = array( array( 'text' => $pathText,
                                'url' => false ) );

?>