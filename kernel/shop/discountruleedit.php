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
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module =& $Params["Module"];
$discountRuleID = null;
if ( isset( $Params["DiscountRuleID"] ) )
    $discountRuleID = $Params["DiscountRuleID"];

if ( is_numeric( $discountRuleID ) )
{
    $discountRule =& eZDiscountRule::fetch( $discountRuleID );
}
else
{
    $discountRule =& eZDiscountRule::create();
    $discountRule->store();
    $discountRuleID = $discountRule->attribute( "id" );
    $module->redirectTo( $module->functionURI( "discountruleedit" ) . "/" . $discountRuleID );
    return;
}

$http =& eZHttpTool::instance();
if ( $http->hasPostVariable( "DiscardButton" ) )
{
    $module->redirectTo( $module->functionURI( "discountrule" ) . "/" );
    return;
}
if ( $http->hasPostVariable( "ApplyButton" ) )
{
    if ( $http->hasPostVariable( "discount_name" ) )
    {
        $name = $http->postVariable( "discount_name" );
    }
    $discountRule->setAttribute( "name", $name );
    if ( $http->hasPostVariable( "discount_percent" ) )
    {
        $percent = $http->postVariable( "discount_percent" );
    }
    $discountRule->setAttribute( "discount_percent", $percent );
    $discountRule->store();
    $module->redirectTo( $module->functionURI( "discountrule" ) . "/" );
    return;
}

$module->setTitle( "Editing discount rule" );
$tpl =& templateInit();
$tpl->setVariable( "module", $module );
$tpl->setVariable( "discount_rule", $discountRule );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/discountruleedit.tpl" );

?>
