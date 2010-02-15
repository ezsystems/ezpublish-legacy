<?php
//
// Created on: <18-Feb-2006 13:11:18 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

$module = $Params['Module'];

$errors = false;
$errorHeader = false;
$productCategories = eZProductCategory::fetchList();

/**
 * Check entered data.
 *
 * \return list of errors found, or false if the data is ok.
 */
function checkEnteredData( $country, $categories, $vatType, $productCategories, $ruleID )
{
    $errors = false;
    $errorHeader = '';

    /*
     * Check if the data was entered correctly.
     */

    if ( !$country || !is_numeric( $vatType ) )
    {
        $errors = array();
        $errorHeader = eZi18n::translate( 'kernel/shop/editvatrule', 'Invalid data entered' );

        if ( !$country )
            $errors[] = eZi18n::translate( 'kernel/shop/editvatrule', 'Choose a country.' );
        if ( !is_numeric( $vatType ) )
            $errors[] = eZi18n::translate( 'kernel/shop/editvatrule', 'Choose a VAT type.' );

        return array( $errorHeader, $errors );
    }

    /*
     * Check if the rule we're about to create
     * conflicts with existing ones.
     */

    $errorHeader = eZi18n::translate( 'kernel/shop/editvatrule', 'Conflicting rule' );
    $vatRules = eZVatRule::fetchList();

    // If the rule is default one
    if ( !$categories )
    {
        // check if default rule already exists.
        foreach ( $vatRules as $i )
        {
            if ( $i->attribute( 'id' ) == $ruleID ||
                 $i->attribute( 'country_code' ) != $country ||
                 $i->attribute( 'product_categories' ) )
                continue;

            if ( $country == '*' )
                $errors[] = eZi18n::translate( 'kernel/shop/editvatrule', 'Default rule for any country already exists.' );
            else
            {
                $errorMessage = "Default rule for country '%1' already exists.";
                $errors[] = eZi18n::translate( 'kernel/shop/editvatrule', $errorMessage, null, array( $country ) );
            }

            break;
        }
    }

    // If the rule contains some categories
    else
    {
        // check if there are already rules defined for the same country
        // containing some of the categories.

        foreach ( $vatRules as $i )
        {
            if ( $i->attribute( 'id' ) == $ruleID ||
                 $i->attribute( 'country_code' ) != $country ||
                 !$i->attribute( 'product_categories' ) )
                continue;

            $intersection = array_intersect( $categories, $i->attribute( 'product_categories_ids' ) );
            if ( !$intersection )
                continue;

            // Construct string containing name of all the conflicting categories.
            $intersectingCategories = array();
            foreach ( $productCategories as $cat )
            {
                if ( array_search( $cat->attribute( 'id' ), $intersection ) !== false )
                     $intersectingCategories[] = $cat->attribute( 'name' );
            }
            $intersectingCategories = join( ', ', $intersectingCategories );

            if ( $country == '*' )
                $errorMessage = "There is already a rule defined for any country containing the following categories: %2.";
            else
                $errorMessage = "There is already a rule defined for country '%1' containing the following categories: %2.";

            $errors[] = eZi18n::translate( 'kernel/shop/editvatrule', $errorMessage, null, array( $country, $intersectingCategories ) );
        }
    }

    return array( $errorHeader, $errors );
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

    list( $errorHeader, $errors ) = checkEnteredData( $chosenCountry, $chosenCategories, $chosenVatType,
                                                      $productCategories, $ruleID );

    // save rule (creating it if not exists)
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
                $errors[] = eZi18n::translate( 'kernel/shop/editvatrule', 'Rule not found' );
                break;
            }
        }
        else
        {
            // Create a new VAT rule...
            $vatRule = eZVatRule::create();
        }

        // Modify chosen categories array
        // so that it can be saved into the VAT rule.
        $addID = create_function('$i', "return array( 'id' => \$i ) ;" );
        $chosenCategories = array_map( $addID, $chosenCategories );

        $vatRule->setAttribute( 'country_code', $chosenCountry );
        $vatRule->setAttribute( 'product_categories', $chosenCategories );
        $vatRule->setAttribute( 'vat_type', $chosenVatType );
        $vatRule->store();

        return $module->redirectTo( $module->functionURI( 'vatrules' ) );

    } while ( false );
}

if ( is_numeric( $ruleID ) )
{
    $tplVatRule      = eZVatRule::fetch( $ruleID );
    $tplCountry      = $tplVatRule->attribute( 'country_code' );
    $tplCategoryIDs  = $tplVatRule->attribute( 'product_categories_ids' );
    $tplVatTypeID    = $tplVatRule->attribute( 'vat_type' );

    $pathText = eZi18n::translate( 'kernel/shop/editvatrule', 'Edit VAT charging rule' );
}
else
{
    $tplVatRule = null;
    $tplCountry = false;
    $tplVatTypeID = false;
    $tplCategoryIDs = array();

    $pathText = eZi18n::translate( 'kernel/shop/editvatrule', 'Create new VAT charging rule' );
}

if ( $errors !== false )
{
    $tplCountry     = $chosenCountry;
    $tplCategoryIDs = $chosenCategories;
    $tplVatTypeID   = $chosenVatType;
}

$vatTypes = eZVatType::fetchList( true, true );

require_once( 'kernel/common/template.php' );
$tpl = templateInit();

$tpl->setVariable( 'error_header', $errorHeader );
$tpl->setVariable( 'errors', $errors );
$tpl->setVariable( 'all_vat_types', $vatTypes );
$tpl->setVariable( 'all_product_categories', $productCategories );

$tpl->setVariable( 'rule',         $tplVatRule );
$tpl->setVariable( 'country_code', $tplCountry );
$tpl->setVariable( 'category_ids', $tplCategoryIDs );
$tpl->setVariable( 'vat_type_id',  $tplVatTypeID );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/editvatrule.tpl" );
$Result['path'] = array( array( 'text' => $pathText,
                                'url' => false ) );

?>
