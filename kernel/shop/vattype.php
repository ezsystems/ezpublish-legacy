<?php
//
// Definition of  class
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

require_once( "kernel/common/template.php" );
//include_once( "kernel/classes/ezvattype.php" );
//include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = templateInit();
$errors = false;

/*!
  Apply changes made to VAT types' names and/or percentages.

  \return errors array
 */
function applyChanges( $module, $http, $vatTypeArray = false )
{
    $errors = array();
    if ( $vatTypeArray === false )
        $vatTypeArray = eZVatType::fetchList( true, true );

    $db = eZDB::instance();
    $db->begin();
    foreach ( $vatTypeArray as $vatType )
    {
        $id = $vatType->attribute( 'id' );

        if ( $id == -1 ) // avoid storing changes to the "fake" dynamic VAT type
            continue;

        if ( $http->hasPostVariable( "vattype_name_" . $id ) )
        {
            $name = $http->postVariable( "vattype_name_" . $id );
        }
        if ( $http->hasPostVariable( "vattype_percentage_" . $id ) )
        {
            $percentage = $http->postVariable( "vattype_percentage_" . $id );
        }

        if ( !$name || $percentage < 0 || $percentage > 100 )
        {
            if ( !$name )
                $errors[] = ezi18n( 'kernel/shop/vattype', 'Empty VAT type names are not allowed (corrected).' );
            else
                $errors[] = ezi18n( 'kernel/shop/vattype', 'Wrong VAT percentage (corrected).' );

            continue;
        }

        $vatType->setAttribute( 'name', $name );
        $vatType->setAttribute( 'percentage', $percentage );
        $vatType->store();
    }
    $db->commit();

    return $errors;
}

/**
 * Generate a unique VAT type name.
 *
 * The generated name looks like "VAT type X"
 * where X is a unique number.
 */
function generateUniqueVatTypeName( $vatTypes )
{
    $commonPart = ezi18n( 'kernel/shop', 'VAT type' );
    $maxNumber = 0;
    foreach ( $vatTypes as $type )
    {
        $typeName = $type->attribute( 'name' );

        if ( !preg_match( "/^$commonPart (\d+)/", $typeName, $matches ) )
            continue;

        $curNumber = $matches[1];
        if ( $curNumber > $maxNumber )
            $maxNumber = $curNumber;
    }

    $maxNumber++;
    return "$commonPart $maxNumber";
}

/**
 * Determine dependent VAT rules and products for the given VAT types.
 *
 * \private
 */
function findDependencies( $vatTypeIDList, &$deps, &$haveDeps, &$canRemove )
{
    // Find dependencies (products and/or VAT rules).
    require_once( 'kernel/classes/ezvatrule.php' );
    $deps = array();
    $haveDeps = false;
    $canRemove = true;
    foreach ( $vatTypeIDList as $vatID )
    {
        $vatType = eZVatType::fetch( $vatID );
        $vatName = $vatType->attribute( 'name' );

        // Find dependent VAT rules.
        $nRules = eZVatRule::fetchCountByVatType( $vatID );

        // Find dependent products.
        $nProducts = eZVatType::fetchDependentProductsCount( $vatID );

        // Find product classes having this VAT type set as default.
        $nClasses = eZVatType::fetchDependentClassesCount( $vatID );

        if ( $nClasses )
            $canRemove = false;

        $deps[$vatID] = array( 'name' => $vatName,
                               'affected_rules_count' => $nRules,
                               'affected_products_count' => $nProducts,
                               'affected_classes_count' => $nClasses );

        if ( !$haveDeps && ( $nRules > 0 || $nProducts > 0 ) )
            $haveDeps = true;
    }
}

// Add new VAT type.
if ( $module->isCurrentAction( 'Add' ) )
{
    $vatTypeArray = eZVatType::fetchList( true, true );
    $errors = applyChanges( $module, $http, $vatTypeArray );

    $vatType = eZVatType::create();
    $vatType->setAttribute( 'name', generateUniqueVatTypeName( $vatTypeArray ) );
    $vatType->store();
    $tpl->setVariable( 'last_added_id', $vatType->attribute( 'id' ) );
}
// Save changes made to names and percentages.
elseif ( $module->isCurrentAction( 'SaveChanges' ) )
{
    $errors = applyChanges( $module, $http );
}
// Remove checked VAT types [with or without confirmation].
elseif ( $module->isCurrentAction( 'Remove' ) )
{
    $vatIDsToRemove = $module->actionParameter( 'vatTypeIDList' );
    $deps = array();
    $haveDeps = false;
    $canRemove = true;
    findDependencies( $vatIDsToRemove, $deps, $haveDeps, $canRemove );

    $errors = false;
    $showDeps = true;
    // If there are dependendant rules and/or products
    // then show confifmation dialog.
    if ( $haveDeps )
    {
        // Let the user choose another VAT to set

        $allVatTypes = eZVatType::fetchList( true, true );

        if ( ( count( $allVatTypes ) - count( $vatIDsToRemove ) ) == 0 )
        {
            $errorMsg = 'You cannot remove all VAT types. ' .
                        'If you do not neet to charge any VAT for your ' .
                        'products then just leave one VAT type and set ' .
                        'its percentage to zero.';
            $errors[] = ezi18n( 'kernel/shop/vattype', $errorMsg );
            $showDeps = false;
        }

        $tpl->setVariable( 'can_remove', $canRemove ); // true if we allow the removal
        $tpl->setVariable( 'show_dependencies', $showDeps ); // true if we'll show the VAT types' dependencies
        $tpl->setVariable( 'errors', $errors ); // array of error messages, false if there are no errors
        $tpl->setVariable( 'dependencies', $deps );
        $tpl->setVariable( 'vat_type_ids', join( ',', $vatIDsToRemove ) );
        $path = array( array( 'text' => ezi18n( 'kernel/shop', 'VAT types' ),
                              'url'  => false ) );

        $Result = array();
        $Result['path'] = $path;
        $Result['content'] = $tpl->fetch( "design:shop/removevattypes.tpl" );
        return;
    }
    else // otherwise just silently remove the VAT types.
    {
        $module->setCurrentAction( 'ConfirmRemoval' );
        // pass through
    }
}
// Do actually remove checked VAT types.
if ( $module->isCurrentAction( 'ConfirmRemoval' ) )
{
    $afterConfirmation = false;

    $vatIDsToRemove = $module->actionParameter( 'vatTypeIDList' );

    // The list of VAT types to remove is a string
    // if passed from the confirmation dialog
    // and an array if passed from another action.
    if ( is_string( $vatIDsToRemove ) )
    {
        $vatIDsToRemove = explode( ',', $vatIDsToRemove );
        $afterConfirmation = true;
    }

    if ( !$afterConfirmation )
        $errors = applyChanges( $module, $http );

    $db = eZDB::instance();
    $db->begin();
    foreach ( $vatIDsToRemove as $vatID )
    {
        $vatType = eZVatType::fetch( $vatID );
        if ( is_object( $vatType ) )
        {
            $vatType->removeThis();
        }
    }
    $db->commit();
}

$vatTypeArray = eZVatType::fetchList( true, true );

if ( is_array( $errors ) )
    $errors = array_unique( $errors );

$tpl->setVariable( "vattype_array", $vatTypeArray );
$tpl->setVariable( "module", $module );
$tpl->setVariable( 'errors', $errors );

$path = array();
$path[] = array( 'text' => ezi18n( 'kernel/shop', 'VAT types' ),
                 'url' => false );


$Result = array();
$Result['path'] = $path;
$Result['content'] = $tpl->fetch( "design:shop/vattype.tpl" );

?>
