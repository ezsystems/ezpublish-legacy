<?php
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
include_once( "kernel/classes/ezdiscountsubrule.php" );
include_once( "kernel/classes/ezdiscountsubrulevalue.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module =& $Params["Module"];
if ( !isset( $Params["DiscountRuleID"] ) )
{
    $module->setExitStatus( EZ_MODULE_STATUS_FAILED );
    return;
}
else
{
    $discountRuleID = $Params["DiscountRuleID"];
}

$discountSubRuleID = null;
if ( isset( $Params["DiscountSubRuleID"] ) )
    $discountSubRuleID = $Params["DiscountSubRuleID"];

if ( is_numeric( $discountSubRuleID ) )
{
    $discountSubRule =& eZDiscountSubRule::fetch( $discountSubRuleID );
}
else
{
    $discountSubRule =& eZDiscountSubRule::create( $discountRuleID );
    $discountSubRule->store();
    $discountSubRuleID = $discountSubRule->attribute( "id" );
    $module->redirectTo( $module->functionURI( "discountsubruleedit" ) . "/" . $discountRuleID . "/" . $discountSubRuleID );
    return;
}

$http =& eZHttpTool::instance();
$storedClassValues =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountSubRuleID, 0 );
$storeClassID = array();
if ( $storedClassValues != null )
{
    foreach ( $storedClassValues as $storedClassValue )
    {
        $classID = $storedClassValue->attribute( 'value' );
        $storeClassID[] = $classID;
    }
}
$storedSectionValues =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountSubRuleID, 1 );
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
    eZDiscountSubRule::remove( $discountSubRuleID );
    $module->redirectTo( $module->functionURI( "discountruleedit" ) . "/" . $discountRuleID );
    return;
}

if ( $http->hasPostVariable( "StoreButton" ) )
{
    eZDiscountSubRuleValue::removeBySubRuleID ( $discountSubRuleID );
    if ( $http->hasPostVariable( "discountsubrule_name" ) )
    {
        $name = $http->postVariable( "discountsubrule_name" );
    }
    $discountSubRule->setAttribute( "name", $name );
    if ( $http->hasPostVariable( "discountsubrule_percent" ) )
    {
        $percent = $http->postVariable( "discountsubrule_percent" );
    }
    $discountSubRule->setAttribute( "discount_percent", $percent );

    if ( $http->hasPostVariable( "Contentclasses" ) )
    {
        $classIDList = $http->postVariable( "Contentclasses" );
        $withLimitation = true;
        foreach ( $classIDList as $classID )
        {
            if ( $classID == -1 )
                $withLimitation = false;
        }
        if ( $withLimitation )
        {
             foreach ( $classIDList as $classID )
             {
                 $subRuleValue =& eZDiscountSubRuleValue::create( $discountSubRuleID, $classID );
                 $subRuleValue->store();
             }
             $discountSubRule->setAttribute( 'limitation', null );
        }
    }
    if ( $http->hasPostVariable( "Sections" ) )
    {
        $sectionIDList = $http->postVariable( "Sections" );
        $withLimitation = true;
        foreach ( $sectionIDList as $sectionID )
        {
            if ( $sectionID == -1 )
                $withLimitation = false;
        }
        if ( $withLimitation )
        {
             foreach ( $sectionIDList as $sectionID )
             {
                 $subRuleValue =& eZDiscountSubRuleValue::create( $discountSubRuleID, $sectionID, 1 );
                 $subRuleValue->store();
             }
             $discountSubRule->setAttribute( 'limitation', null );
        }
    }
    $discountSubRule->store();
    $module->redirectTo( $module->functionURI( "discountrulemembershipview" ) . "/". $discountRuleID );
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
$module->setTitle( "Adding discount sub rule" );
$tpl =& templateInit();
$tpl->setVariable( "module", $module );
$tpl->setVariable( "discountrule_id", $discountRuleID );
$tpl->setVariable( "discountsubrule", $discountSubRule );
$tpl->setVariable( "product_class_list", $productClassList );
$tpl->setVariable( "section_list", $sectionList );
$tpl->setVariable( "stored_section_id", $storedSectionID );
$tpl->setVariable( "stored_class_id", $storedClassID );

$sectionLimitationList =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountSubRule->attribute( 'id' ), 1, false );
$sectionList = array();
foreach ( $sectionLimitationList as $limitation )
{
    $sectionList[] = $limitation['value'];
}
$tpl->setVariable( "section_limitation_list", $sectionList );
if ( count( $sectionList ) > 0 )
    $tpl->setVariable( "section_any_selected", false );
else
    $tpl->setVariable( "section_any_selected", true );


$classLimitationList =& eZDiscountSubRuleValue::fetchBySubRuleID( $discountSubRule->attribute( 'id' ), 0, false );
$classList = array();
foreach ( $classLimitationList as $limitation )
{
    $classList[] = $limitation['value'];
}
$tpl->setVariable( "class_limitation_list", $classLimitationList );
if ( count( $classList ) > 0 )
    $tpl->setVariable( "class_any_selected", false );
else
    $tpl->setVariable( "class_any_selected", true );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/discountsubruleedit.tpl" );
?>
