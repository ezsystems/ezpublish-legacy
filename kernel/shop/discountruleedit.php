<?php
//
// Created on: <25-Nov-2002 15:40:10 wy>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
include_once( "kernel/classes/ezdiscountsubrule.php" );
include_once( "kernel/classes/ezdiscountsubrulevalue.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module =& $Params["Module"];
if ( !isset( $Params["DiscountGroupID"] ) )
{
    $module->setExitStatus( EZ_MODULE_STATUS_FAILED );
    return;
}
else
{
    $discountGroupID = $Params["DiscountGroupID"];
}

$discountRuleID = null;
if ( isset( $Params["DiscountRuleID"] ) )
    $discountRuleID = $Params["DiscountRuleID"];

if ( is_numeric( $discountRuleID ) )
{
    $discountRule =& eZDiscountSubRule::fetch( $discountRuleID );
}
else
{
    $discountRule =& eZDiscountSubRule::create( $discountGroupID );
    $discountRule->store();
    $discountRuleID = $discountRule->attribute( "id" );
    $module->redirectTo( $module->functionURI( "discountruleedit" ) . "/" . $discountGroupID . "/" . $discountRuleID );
    return;
}

$http =& eZHttpTool::instance();
$storedClassValues =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountRuleID, 0 );
$storeClassID = array();
if ( $storedClassValues != null )
{
    foreach ( $storedClassValues as $storedClassValue )
    {
        $classID = $storedClassValue->attribute( 'value' );
        $storeClassID[] = $classID;
    }
}
$storedSectionValues =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountRuleID, 1 );
$storeSectionID = array();
if ( $storedSectionValues != null )
{
    foreach ( $storedSectionValues as $storedSectionValue )
    {
        $sectionID = $storedSectionValue->attribute( 'value' );
        $storeSectionID[] = $sectionID;
    }
}

if ( $http->hasPostVariable( "DiscardButton" ) )
{
    //eZDiscountSubRule::remove( $discountRuleID );
    $module->redirectTo( $module->functionURI( "discountgroupview" ) . "/" . $discountGroupID );
    return;
}
if ( $http->hasPostVariable( "StoreButton" ) )
{
    eZDiscountSubRuleValue::removeBySubRuleID ( $discountRuleID );
    if ( $http->hasPostVariable( "discountrule_name" ) )
    {
        $name = $http->postVariable( "discountrule_name" );
        $discountRule->setAttribute( "name", $name );
    }
    if ( $http->hasPostVariable( "discountrule_percent" ) )
    {
        $percent = $http->postVariable( "discountrule_percent" );
        if ( $percent <= 100 )
        {
            $discountRule->setAttribute( "discount_percent", $percent );
        }
        else
        {
            //Do not update the percent.
        }
    }
    $limitation = false;
    if ( $http->hasPostVariable( "Contentclasses" ) )
    {
        $withClassLimitation = true;
        $classIDList = $http->postVariable( "Contentclasses" );
        foreach ( $classIDList as $classID )
        {
            if ( $classID == -1 )
                $withClassLimitation = false;
        }
        if ( $withClassLimitation )
        {
             foreach ( $classIDList as $classID )
             {
                 $ruleValue =& eZDiscountSubRuleValue::create( $discountRuleID, $classID );
                 $ruleValue->store();
             }
             $discountRule->setAttribute( 'limitation', null );
             $limitation = true;
        }
    }
    if ( $http->hasPostVariable( "Sections" ) )
    {
        $sectionIDList = $http->postVariable( "Sections" );
        $withSectionLimitation = true;
        foreach ( $sectionIDList as $sectionID )
        {
            if ( $sectionID == -1 )
                $withSectionLimitation = false;
        }
        if ( $withSectionLimitation )
        {
             foreach ( $sectionIDList as $sectionID )
             {
                 $ruleValue =& eZDiscountSubRuleValue::create( $discountRuleID, $sectionID, 1 );
                 $ruleValue->store();
             }
             $discountRule->setAttribute( 'limitation', null );
             $limitation = true;
        }
    }

    if ( !$limitation )
        $discountRule->setAttribute( 'limitation', '*' );
    $discountRule->store();
    $module->redirectTo( $module->functionURI( "discountgroupview" ) . "/". $discountGroupID );
    return;
}

$classList =& eZContentClass::fetchList();
$productClassList = array();
foreach ( $classList as $class )
{
    $include = false;
    $classAttributes =& $class->fetchAttributes();
    foreach (  $classAttributes as  $classAttribute )
    {
        $dataType = $classAttribute->attribute( 'data_type_string' );
        if ( $dataType == "ezprice" )
        {
            $include = true;
        }
    }
    if ( $include )
    {
        $productClassList[] = $class;
    }
}

$sectionList =& eZSection::fetchList();
$module->setTitle( "Adding discount rule" );
$tpl =& templateInit();
$tpl->setVariable( "module", $module );
$tpl->setVariable( "discountgroup_id", $discountGroupID );
$tpl->setVariable( "discountrule", $discountRule );
$tpl->setVariable( "product_class_list", $productClassList );
$tpl->setVariable( "section_list", $sectionList );
$tpl->setVariable( "stored_section_id", $storedSectionID );
$tpl->setVariable( "stored_class_id", $storedClassID );

$sectionLimitationList =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountRule->attribute( 'id' ), 1, false );
$sectionIDList = array();
foreach ( $sectionLimitationList as $limitation )
{
    $sectionIDList[] = $limitation['value'];
}
$tpl->setVariable( "section_limitation_list", $sectionIDList );

if ( count( $sectionIDList ) > 0 )
    $tpl->setVariable( "section_any_selected", false );
else
    $tpl->setVariable( "section_any_selected", true );


$classLimitationList =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountRule->attribute( 'id' ), 0, false );
$classIDList = array();
foreach ( $classLimitationList as $limitation )
{
    $classIDList[] = $limitation['value'];
}
$tpl->setVariable( "class_limitation_list", $classIDList );

if ( count( $classIDList ) > 0 )
    $tpl->setVariable( "class_any_selected", false );
else
    $tpl->setVariable( "class_any_selected", true );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/discountruleedit.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', 'Editing rule' ) ) );
?>
