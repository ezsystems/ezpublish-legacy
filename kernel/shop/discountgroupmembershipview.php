<?php
//
// Definition of  class
//
// Created on: <25-Nov-2002 15:40:10 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
//include_once( "kernel/classes/ezcontentobject.php" );
//include_once( "kernel/classes/ezdiscountrule.php" );
//include_once( "kernel/classes/ezuserdiscountrule.php" );
//include_once( "kernel/classes/ezdiscountsubrule.php" );
//include_once( "kernel/classes/ezdiscountsubrulevalue.php" );
//include_once( "kernel/classes/ezcontentbrowse.php" );
//include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module = $Params['Module'];
$discountGroupID = null;
if ( isset( $Params["DiscountGroupID"] ) )
    $discountGroupID = $Params["DiscountGroupID"];

$discountGroup = eZDiscountRule::fetch( $discountGroupID );
if( is_null( $discountGroup ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}


$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( "AddRuleButton" ) )
{
    return $module->redirectTo( $module->functionURI( 'discountruleedit' ) . '/' . $discountGroupID );
}

if ( $http->hasPostVariable( "RemoveRuleButton" ) )
{
    $discountRuleIDList = $http->postVariable( "removeRuleList" );

    $db = eZDB::instance();
    $db->begin();
    foreach ( $discountRuleIDList  as $discountRuleID )
    {
        eZDiscountSubRuleValue::removeBySubRuleID ( $discountRuleID );
        eZDiscountSubRule::remove( $discountRuleID );
    }
    $db->commit();

    // we changed prices => remove content cache
    //include_once( 'kernel/classes/ezcontentcachemanager.php' );
    eZContentCacheManager::clearAllContentCache();

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

    $db = eZDB::instance();
    $db->begin();
    foreach ( $selectedObjectIDArray as $objectID )
    {
        if ( !in_array(  $objectID, $userIDArray ) )
        {
            $userRule = eZUserDiscountRule::create( $discountGroupID, $objectID );
            $userRule->store();
        }
    }
    $db->commit();

    // because we changed users, we have to remove content cache
    //include_once( 'kernel/classes/ezcontentcachemanager.php' );
    eZContentCacheManager::clearAllContentCache();
}
if ( $http->hasPostVariable( "RemoveCustomerButton" ) )
{
    if (  $http->hasPostVariable( "CustomerIDArray" ) )
    {
        $customerIDArray = $http->postVariable( "CustomerIDArray" );

        $db = eZDB::instance();
        $db->begin();
        foreach ( $customerIDArray as $customerID )
        {
            eZUserDiscountRule::removeUser( $customerID );
        }
        $db->commit();
    }

    //include_once( 'kernel/classes/ezcontentcachemanager.php' );
    eZContentCacheManager::clearAllContentCache();
}

$membershipList = eZUserDiscountRule::fetchByRuleID( $discountGroupID );
$customers = array();
foreach ( $membershipList as $membership )
{
    $customers[] = eZContentObject::fetch( $membership->attribute( 'contentobject_id' ) );
}

$ruleList = eZDiscountSubRule::fetchByRuleID( $discountGroupID );

$ruleArray = array();
foreach ( $ruleList as $rule )
{
    $name = $rule->attribute( 'name' );
    $percent = $rule->attribute( 'discount_percent' );
    $limitation = $rule->attribute( 'limitation' );
    $discountRuleID = $rule->attribute( 'id' );
    if ( $limitation != '*' )
    {
        $ruleValues = eZDiscountSubRuleValue::fetchBySubRuleID( $discountRuleID );
        if ( $ruleValues != null )
        {
            $limitation = ezi18n( 'kernel/shop', 'Classes' ).' ';
            $firstLoop = true;
            foreach ( $ruleValues as $ruleValue )
            {
                $classID = $ruleValue->attribute( 'value' );
                $class = eZContentClass::fetch( $classID );
                if ( $class )
                {
                    if ( !$firstLoop )
                    {
                        $limitation .= ', ';
                    }
                    else
                    {
                        $firstLoop = false;
                    }
                    $className = $class->attribute( 'name' );
                    $limitation .= "'". $className . "'";
                }
            }
        }
        else
        {
            $limitation = ezi18n( 'kernel/shop', 'Any class' );
        }
        $sectionRuleValues = eZDiscountSubRuleValue::fetchBySubRuleID( $discountRuleID, 1 );
        if ( $sectionRuleValues != null )
        {
            $limitation .= ' '.ezi18n( 'kernel/shop', 'in sections' ).' ';
            $firstLoop = true;
            foreach ( $sectionRuleValues as $sectionRuleValue )
            {
                $sectionID = $sectionRuleValue->attribute( 'value' );
                $section = eZSection::fetch( $sectionID );
                if ( $section )
                {
                    if ( !$firstLoop )
                    {
                        $limitation .= ', ';
                    }
                    else
                    {
                        $firstLoop = false;
                    }
                    $sectionName = $section->attribute( 'name' );
                    $limitation .= "'".$sectionName . "'";
                }
            }
        }
        else
        {
            $limitation .= ' '.ezi18n( 'kernel/shop', 'in any section' );
        }
        $productRuleValues = eZDiscountSubRuleValue::fetchBySubRuleID( $discountRuleID, 2 );

        if ( $productRuleValues != null )
        {
            $limitation = ezi18n( 'kernel/shop', 'Products' ).' ';
            $firstLoop = true;
            foreach ( $productRuleValues as $productRuleValue )
            {
                $objectID = $productRuleValue->attribute( 'value' );
                $product = eZContentObject::fetch( $objectID );
                if ( $product )
                {
                    if ( !$firstLoop )
                    {
                        $limitation .= ', ';
                    }
                    else
                    {
                        $firstLoop = false;
                    }
                    $productName = $product->attribute( 'name' );
                    $limitation .= "'".$productName . "'";
                }
            }
        }
    }
    else
    {
        $limitation = ezi18n( 'kernel/shop', 'Any product' );
    }

    $item = array( "name" => $name,
                   "discount_percent" => $percent,
                   "id" => $discountRuleID,
                   "limitation" => $limitation );
    $ruleArray[] = $item;
}
$tpl = templateInit();
$tpl->setVariable( "module", $module );
$tpl->setVariable( "customers", $customers );
$tpl->setVariable( "discountgroup", $discountGroup );
$tpl->setVariable( "rule_list", $ruleArray );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/discountgroupmembershipview.tpl" );
$Result['path'] = array( array( 'url' => '/shop/discountgroup/',
                                'text' => ezi18n( 'kernel/shop', 'Group view of discount rule' ) ) );
?>
