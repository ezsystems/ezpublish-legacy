<?php
//
// Created on: <09-May-2003 10:44:02 bf>
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

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];
$parameters =& $Params["Parameters"];

include_once( "kernel/common/template.php" );
include_once( "kernel/common/eztemplatedesignresource.php" );
include_once( 'lib/ezutils/classes/ezhttptool.php' );

$ini =& eZINI::instance();
$tpl =& templateInit();

$template = "";
$i = 0;
foreach ( $parameters as $param )
{
    if ( $i > 0 )
        $template .= "/";
    $template .= "$param";
    $i++;
}

$siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );

$overrideArray =& eZTemplatedesignresource::overrideArray( $siteAccess );


// Check if template already exists
$isExistingTemplate = false;
foreach ( $overrideArray as $overrideSetting )
{
    if ( $overrideSetting['base_dir'] . $overrideSetting['template'] == $template )
    {
        $isExistingTemplate = true;
    }
}

if ( $isExistingTemplate == false )
{
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
}

$originalTemplate = false;
foreach ( $overrideArray as $overrideSetting )
{
    if ( isset( $overrideSetting['custom_match'] ) )
    {
        foreach ( $overrideSetting['custom_match'] as $customMatch )
        {
            if ( $customMatch['match_file'] == $template )
            {
                $originalTemplate = $overrideSetting['template'];
                break;
            }
        }
        if ( $originalTemplate )
            break;
    }
}

// Find the main template for this override

if ( $module->isCurrentAction( 'Save' ) )
{
    if ( $http->hasPostVariable( 'TemplateContent' ) )
    {
        $fp = fopen( $template, 'w' );
        if ( $fp )
        {
            fwrite( $fp, $http->postVariable( 'TemplateContent' ) );
        }
        fclose( $fp );

        $siteConfig =& eZINI::instance( 'site.ini' );
        $filePermissions = $siteConfig->variable( 'FileSettings', 'StorageFilePermissions');
        @chmod( $template, octdec( $filePermissions ) );

        // Expire content cache
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-cache', mktime() );
        $handler->store();

        $module->redirectTo( '/setup/templateview'. $originalTemplate );
        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }
}

if ( $module->isCurrentAction( 'Discard' ) )
{
    $module->redirectTo( '/setup/templateview'. $originalTemplate );
    return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
}

// get the content of the template
{
    $fileName = $template;
    $fp = fopen( $fileName, 'rb' );
    if ( $fp )
    {
        $templateContent = fread( $fp, filesize( $fileName ) );
    }
    else
    {
        print( "Could not open file" );
    }
    fclose( $fp );
}

$tpl->setVariable( 'template', $template );
$tpl->setVariable( 'template_content', $templateContent );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:setup/templateedit.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/setup', 'Template edit' ) ) );
?>
