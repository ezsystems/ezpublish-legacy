<?php
//
// Created on: <08-May-2003 11:16:19 bf>
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

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];
$parameters =& $Params["Parameters"];

include_once( "kernel/common/template.php" );
include_once( "kernel/common/eztemplatedesignresource.php" );
include_once( 'lib/ezutils/classes/ezhttptool.php' );

$ini =& eZINI::instance();
$tpl =& templateInit();

$template = "";
foreach ( $parameters as $param )
{
    $template .= "/$param";
}

if ( $module->isCurrentAction( 'SelectCurrentSiteAccess' ) )
{
    if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    {
        $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $http->postVariable( 'CurrentSiteAccess' ) );
    }
}

if ( $module->isCurrentAction( 'NewOverride' ) )
{
    $module->redirectTo( '/setup/templatecreate'. $template );
    return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
}

// Fetch siteaccess settings for the selected override
// Default to first defined siteacces if none are selected
if ( !$http->hasSessionVariable( 'eZTemplateAdminCurrentSiteAccess' ) )
{
    $siteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
    $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $siteAccessList[0] );
}

$siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );
$overrideArray =& eZTemplatedesignresource::overrideArray( $siteAccess );

$templateSettings = false;
if ( isset( $overrideArray[$template] ) )
{
    $templateSettings = $overrideArray[$template];
}

$tpl->setVariable( 'template_settings', $templateSettings );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:setup/templateview.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/setup', 'Template view' ) ) );
?>
