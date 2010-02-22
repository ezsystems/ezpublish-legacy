<?php
//
// Definition of  class
//
// Created on: <25-Nov-2002 15:40:10 wy>
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


$module = $Params['Module'];

$http = eZHTTPTool::instance();

$discountGroupArray = eZDiscountRule::fetchList();

if ( $http->hasPostVariable( "AddDiscountGroupButton" ) )
{
    $params = array();
    $Module->redirectTo( $Module->functionURI( "discountgroupedit" ) );
    return;
}

if ( $http->hasPostVariable( "EditGroupButton" ) && $http->hasPostVariable( "EditGroupID" ) )
{
    $Module->redirectTo( $Module->functionURI( "discountgroupedit" ) . "/" . $http->postVariable( "EditGroupID" ) );
    return;
}

if ( $http->hasPostVariable( "RemoveDiscountGroupButton" ) )
{
    $discountRuleIDList = $http->postVariable( "discountGroupIDList" );

    $db = eZDB::instance();
    $db->begin();
    foreach ( $discountRuleIDList  as $discountRuleID )
    {
        eZDiscountRule::removeByID( $discountRuleID );
    }
    $db->commit();

    // we changed prices of products (no discount now) => remove content caches
    eZContentCacheManager::clearAllContentCache();

    $module->redirectTo( $module->functionURI( "discountgroup" ) . "/" );
    return;
}
$module->setTitle( "View discount group" );
$tpl = eZTemplate::factory();
$tpl->setVariable( "discountgroup_array", $discountGroupArray );
$tpl->setVariable( "module", $module );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/discountgroup.tpl" );
$Result['path'] = array( array( 'url' => '/shop/discountgroup/',
                                'text' => ezpI18n::tr( 'kernel/shop', 'Discount group' ) ) );
?>
