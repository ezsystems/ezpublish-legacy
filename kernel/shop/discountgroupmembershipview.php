<?php
//
// Definition of  class
//
// Created on: <25-Nov-2002 15:40:10 wy>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "kernel/common/template.php" );
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezdiscountrule.php" );
include_once( "kernel/classes/ezuserdiscountrule.php" );
include_once( "kernel/classes/ezdiscountsubrule.php" );
include_once( "kernel/classes/ezdiscountsubrulevalue.php" );
include_once( "kernel/classes/ezcontentbrowse.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module =& $Params["Module"];
$discountGroupID = null;
if ( isset( $Params["DiscountGroupID"] ) )
    $discountGroupID = $Params["DiscountGroupID"];

if ( is_numeric( $discountGroupID ) )
{
    $discountGroup =& eZDiscountRule::fetch( $discountGroupID );
}


$http =& eZHttpTool::instance();

if ( $http->hasPostVariable( "AddRuleButton" ) )
{
    $params = array();
    $params[] = $discountGroupID;
    $module->run( "discountruleedit", $params );
    return;
}

if ( $http->hasPostVariable( "RemoveRuleButton" ) )
{
    $discountRuleIDList = $http->postVariable( "removeRuleList" );

    foreach ( $discountRuleIDList  as $discountRuleID )
    {
        eZDiscountSubRule::remove( $discountRuleID );
    }
    $module->redirectTo( $module->functionURI( "discountgroupview" ) . "/" . $discountGroupID );
    return;
}

if ( $http->hasPostVariable( "AddCustomerButton" ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'AddCustomer',
                                    'description_template' => 'design:shop/browse_discountcustomer.tpl',
                                    'keys' => array( 'discountgroup_id' => $discountGroupID ),
                                    'content' => array( 'discountgroup_id' => $discountGroupID ),
                                    'from_page' => "/shop/discountgroupview/$discountGroupID" ),
                             $module );
    return;
}

// Add customer or customer group to this rule
if ( $module->isCurrentAction( 'AddCustomer' ) )
{
    $selectedObjectIDArray = eZContentBrowse::result( 'AddCustomer' );
    $userIDArray = eZUserDiscountRule::fetchUserID( $discountGroupID );
    foreach ( $selectedObjectIDArray as $objectID )
    {
        if ( !in_array(  $objectID, $userIDArray ) )
        {
            $userRule =& eZUserDiscountRule::create( $discountGroupID, $objectID );
            $userRule->store();
        }
    }
}
if ( $http->hasPostVariable( "RemoveCustomerButton" ) )
{
    if (  $http->hasPostVariable( "CustomerIDArray" ) )
    {
        $customerIDArray = $http->postVariable( "CustomerIDArray" );
        foreach ( $customerIDArray as $customerID )
        {
            eZUserDiscountRule::removeUser( $customerID );
        }
    }
}
$module->setTitle( "View membership" );
$membershipList = eZUserDiscountRule::fetchByRuleID( $discountGroupID );
$customers = array();
foreach ( $membershipList as $membership )
{
    $customers[] = eZContentObject::fetch( $membership->attribute( 'contentobject_id' ) );
}

$ruleList =& eZDiscountSubRule::fetchByRuleID( $discountGroupID );

$ruleArray = array();
foreach ( $ruleList as $rule )
{
    $name = $rule->attribute( 'name' );
    $percent = $rule->attribute( 'discount_percent' );
    $limitation = $rule->attribute( 'limitation' );
    $discountRuleID = $rule->attribute( 'id' );
    if ( $limitation != '*' )
    {
        $ruleValues =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountRuleID );
        if ( $ruleValues != null )
        {
            $limitation = "Class ";
            foreach ( $ruleValues as $ruleValue )
            {
                $classID = $ruleValue->attribute( 'value' );
                $class =& eZContentClass::fetch( $classID );
                $className = $class->attribute( 'name' );
                $limitation .= "'". $className . "' ";
            }
        }
        $sectionRuleValues =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountRuleID, 1 );
        if ( $sectionRuleValues != null )
        {
            $limitation .= "  in  section ";
            foreach ( $sectionRuleValues as $sectionRuleValue )
            {
                $sectionID = $sectionRuleValue->attribute( 'value' );
                $section =& eZSection::fetch( $sectionID );
                $sectionName = $section->attribute( 'name' );
                $limitation .= "'".$sectionName . "' ";
            }
        }
        $productRuleValues =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountRuleID, 2 );

        if ( $productRuleValues != null )
        {
            $limitation = "Product: ";
            foreach ( $productRuleValues as $productRuleValue )
            {
                $objectID = $productRuleValue->attribute( 'value' );
                $product =& eZContentObject::fetch( $objectID );
                $productName = $product->attribute( 'name' );
                $limitation .= "'".$productName . "' ";
            }
        }
    }
    else
    {
        $limitation = "Any products";
    }
    $item = array( "name" => $name,
                   "discount_percent" => $percent,
                   "id" => $discountRuleID,
                   "limitation" => $limitation );
    $ruleArray[] = $item;
}
$tpl =& templateInit();
$tpl->setVariable( "module", $module );
$tpl->setVariable( "customers", $customers );
$tpl->setVariable( "discountgroup", $discountGroup );
$tpl->setVariable( "rule_list", $ruleArray );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/discountgroupmembershipview.tpl" );
$Result['path'] = array( array( 'url' => '/shop/discountgroup/',
                                'text' => ezi18n( 'kernel/shop', 'Group view of discount rule' ) ) );
?>
