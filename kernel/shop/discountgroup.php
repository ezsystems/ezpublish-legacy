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
include_once( "kernel/classes/ezdiscountrule.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module =& $Params["Module"];

$http =& eZHttpTool::instance();

$discountGroupArray =& eZDiscountRule::fetchList();

if ( $http->hasPostVariable( "AddDiscountGroupButton" ) )
{
    $params = array();
    $module->run( "discountgroupedit", $params );
    return;
}

if ( $http->hasPostVariable( "RemoveDiscountGroupButton" ) )
{
    $discountRuleIDList = $http->postVariable( "discountGroupIDList" );

    foreach ( $discountRuleIDList  as $discountRuleID )
    {
        eZDiscountRule::remove( $discountRuleID );
    }
    $module->redirectTo( $module->functionURI( "discountgroup" ) . "/" );
    return;
}
$module->setTitle( "View discount group" );
$tpl =& templateInit();
$tpl->setVariable( "discountgroup_array", $discountGroupArray );
$tpl->setVariable( "module", $module );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/discountgroup.tpl" );
$Result['path'] = array( array( 'url' => '/shop/discountgroup/',
                                'text' => ezi18n( 'kernel/shop', 'Discount group' ) ) );
?>
