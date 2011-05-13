<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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
                                'text' => ezpI18n::tr( 'design/standard/toolbar', 'Toolbar management' ) ) );


?>
