<?php
//
// Definition of Toolbarlist class
//
// Created on: <05-Mar-2004 13:05:16 wy>
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

/*! \file
*/


$http = eZHTTPTool::instance();

$currentSiteAccess = false;
if ( $http->hasSessionVariable( 'eZTemplateAdminCurrentSiteAccess' ) )
    $currentSiteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );

$module = $Params['Module'];
if ( $Params['SiteAccess'] )
    $currentSiteAccess = $Params['SiteAccess'];

$ini = eZINI::instance();
$siteAccessList = $ini->variable( 'SiteAccessSettings', 'RelatedSiteAccessList' );

if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    $currentSiteAccess = $http->postVariable( 'CurrentSiteAccess' );

if ( !in_array( $currentSiteAccess, $siteAccessList ) )
    $currentSiteAccess = $siteAccessList[0];

if ( $http->hasPostVariable( 'SelectCurrentSiteAccessButton' ) )
{
    $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $currentSiteAccess );
}

$toolbarIni = eZINI::instance( "toolbar.ini", null, null, null, true );
$toolbarIni->prependOverrideDir( "siteaccess/$currentSiteAccess", false, 'siteaccess' );
$toolbarIni->loadCache();

if ( $toolbarIni->hasVariable( "Toolbar", "AvailableToolBarArray" ) )
{
    $toolbarArray =  $toolbarIni->variable( "Toolbar", "AvailableToolBarArray" );
}
$tpl = eZTemplate::factory();

$tpl->setVariable( 'toolbar_list', $toolbarArray );
$tpl->setVariable( 'siteaccess_list', $siteAccessList );
$tpl->setVariable( 'current_siteaccess', $currentSiteAccess );

$Result = array();
$Result['content'] = $tpl->fetch( "design:visual/toolbarlist.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::translate( 'design/standard/toolbar', 'Toolbar management' ) ) );


?>
