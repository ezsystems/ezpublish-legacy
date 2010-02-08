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

require_once( "kernel/common/template.php" );
$module = $Params['Module'];
$discountGroupID = null;
if ( isset( $Params["DiscountGroupID"] ) )
    $discountGroupID = $Params["DiscountGroupID"];

if ( is_numeric( $discountGroupID ) )
{
    $discountGroup = eZDiscountRule::fetch( $discountGroupID );
}
else
{
    $discountGroup = eZDiscountRule::create();
    $discountGroupID = $discountGroup->attribute( "id" );
}

$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( "DiscardButton" ) )
{
    $module->redirectTo( $module->functionURI( "discountgroup" ) . "/" );
    return;
}
if ( $http->hasPostVariable( "ApplyButton" ) )
{
    if ( $http->hasPostVariable( "discount_group_name" ) )
    {
        $name = $http->postVariable( "discount_group_name" );
    }
    $discountGroup->setAttribute( "name", $name );
    $discountGroup->store();
    $module->redirectTo( $module->functionURI( "discountgroup" ) . "/" );
    return;
}

$module->setTitle( "Editing discount group" );
$tpl = templateInit();
$tpl->setVariable( "module", $module );
$tpl->setVariable( "discount_group", $discountGroup );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/discountgroupedit.tpl" );

?>
