<?php
//
// Definition of  class
//
// Created on: <25-Nov-2002 15:40:10 wy>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
include_once( "kernel/classes/ezdiscountrule.php" );
include_once( "kernel/classes/ezuserdiscountrule.php" );
include_once( "kernel/classes/ezdiscountsubrule.php" );
include_once( "kernel/classes/ezdiscountsubrulevalue.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module =& $Params["Module"];
$discountRuleID = null;
if ( isset( $Params["DiscountRuleID"] ) )
    $discountRuleID = $Params["DiscountRuleID"];

if ( is_numeric( $discountRuleID ) )
{
    $discountRule =& eZDiscountRule::fetch( $discountRuleID );
}


$http =& eZHttpTool::instance();

if ( $http->hasPostVariable( "AddSubRuleButton" ) )
{
    $params = array();
    $params[] = $discountRuleID;
    $module->run( "discountsubruleedit", $params );
    return;
}

if ( $http->hasPostVariable( "RemoveSubRuleButton" ) )
{
    $discountSubRuleIDList = $http->postVariable( "removeSubRuleList" );

    foreach ( $discountSubRuleIDList  as $discountSubRuleID )
    {
        eZDiscountSubRule::remove( $discountSubRuleID );
    }
    $module->redirectTo( $module->functionURI( "discountrulemembershipview" ) . "/" . $discountRuleID );
    return;
}

if ( $http->hasPostVariable( "AddCustomerButton" ) )
{
    $http->setSessionVariable( "BrowseFromPage", "/shop/discountrulemembershipview/" . $discountRuleID . "/" );

    $http->setSessionVariable( "BrowseActionName", "AddCustomer" );
    $http->setSessionVariable( "BrowseReturnType", "ObjectID" );

    $module->redirectTo( "/content/browse/5/" );
    return;
}

// Add customer or customer group to this rule
if ( $http->hasPostVariable( "BrowseActionName" ) and
     $http->postVariable( "BrowseActionName" ) == "AddCustomer" )
{
    $selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );
    $userIDArray = eZUserDiscountRule::fetchUserID( $discountRuleID );
    foreach ( $selectedObjectIDArray as $objectID )
    {
        if ( !in_array(  $objectID, $userIDArray ) )
        {
            $userRule =& eZUserDiscountRule::create( $discountRuleID, $objectID );
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
$membershipList = eZUserDiscountRule::fetchByRuleID( $discountRuleID );
$customers = array();
foreach ( $membershipList as $membership )
{
    $customers[] = eZContentObject::fetch( $membership->attribute( 'contentobject_id' ) );
}

$ruleList =& eZDiscountSubRule::fetchByRuleID( $discountRuleID );

$subRuleList = array();
foreach ( $ruleList as $rule )
{
    $name = $rule->attribute( 'name' );
    $percent = $rule->attribute( 'discount_percent' );
    $limitation = $rule->attribute( 'limitation' );
    $discountSubRuleID = $rule->attribute( 'id' );
    if ( $limitation != '*' )
    {
         eZDebug::writeDebug( "1234");
        $ruleValues =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountSubRuleID );
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
        $sectionRuleValues =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountSubRuleID, true );
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
    }
    $item = array( "name" => $name,
                   "discount_percent" => $percent,
                   "id" => $discountSubRuleID,
                   "limitation" => $limitation );
    $subRuleList[] = $item;
}
$tpl =& templateInit();
$tpl->setVariable( "module", $module );
$tpl->setVariable( "customers", $customers );
$tpl->setVariable( "discountrule", $discountRule );
$tpl->setVariable( "subrule_list", $subRuleList );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/discountrulemembershipview.tpl" );
?>
