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
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
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
include_once( 'lib/ezi18n/classes/eztextcodec.php' );

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

// Find the main template for this override
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

/* Check if we need to do characterset conversions for editting and saving
 * templates. */
$templateConfig =& eZINI::instance( 'template.ini' );
$i18nConfig =& eZINI::instance( 'i18n.ini' );

/* First we check the HTML Output Charset */
$httpCharset = $i18nConfig->variable( 'CharacterSettings', 'HTTPCharset');


if ( $module->isCurrentAction( 'Save' ) )
{
    if ( $http->hasPostVariable( 'TemplateContent' ) )
    {
        $templateContent = $http->postVariable( 'TemplateContent' );

        if ( $templateConfig->variable( 'CharsetSettings', 'AutoConvertOnSave') == 'enabled' )
        {
            include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
            $httpCharset = eZCharsetInfo::realCharsetCode( $httpCharset );
            if ( preg_match( '|{\*\?template.*charset=([a-zA-Z0-9-]*).*\?\*}|', $templateContent, $matches ) )
            {
                $templateContent = preg_replace( '|({\*\?template.*charset=)[a-zA-Z0-9-]*(.*\?\*})|',
                                                 '\\1'. $httpCharset. '\\2',
                                                 $templateContent );
            }
            else
            {
                $templateContent = "{*?template charset=$httpCharset?*}\n". $templateContent;
            }
        }
        else
        {
            /* Here we figure out the characterset of the template. If there is a charset
             * associated with the template in the header we use that one, if not we fall
             * back to the INI setting "DefaultTemplateCharset". */
            if ( preg_match( '|{\*\?template.*charset=([a-zA-Z0-9-]*).*\?\*}|', $templateContent, $matches ) )
            {
                $templateCharset = $matches[1];
            }
            else
            {
                $templateCharset = $templateConfig->variable( 'CharsetSettings', 'DefaultTemplateCharset');
            }

            /* If we're saving a template after editting we need to convert it to the template's
             * Charset. */
            $codec =& eZTextCodec::instance( $httpCharset, $templateCharset, false );
            if ( $codec )
            {
                $templateContent = $codec->convertString( $templateContent );
            }
        }

        $fp = fopen( $template, 'w' );
        if ( $fp )
        {
            fwrite( $fp, $templateContent );
        }
        fclose( $fp );

        $siteConfig =& eZINI::instance( 'site.ini' );
        $filePermissions = $siteConfig->variable( 'FileSettings', 'StorageFilePermissions');
        @chmod( $template, octdec( $filePermissions ) );

        // Expire content view cache
        $viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );
        if ( $viewCacheEnabled )
        {
            eZContentObject::expireAllCache();
        }

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
    $templateContent = file_get_contents( $fileName );
    if ( !$templateContent )
    {
        print( "Could not open file" );
        $templateContent = "Could not open file";
    }
}

/* Here we figure out the characterset of the template. If there is a charset
 * associated with the template in the header we use that one, if not we fall
 * back to the INI setting "DefaultTemplateCharset". */
if ( preg_match('|{\*\?template.*charset=([a-zA-Z0-9-]*).*\?\*}|', $templateContent, $matches ) )
{
    $templateCharset = $matches[1];
}
else
{
    $templateCharset = $templateConfig->variable( 'CharsetSettings', 'DefaultTemplateCharset');
}

/* If we're loading a template for editting we need to convert it to the HTTP
 * Charset. */
$codec =& eZTextCodec::instance( $templateCharset, $httpCharset, false );
if ( $codec )
{
    $templateContent = $codec->convertString( $templateContent );
}

$tpl->setVariable( 'template', $template );
$tpl->setVariable( 'template_content', $templateContent );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:setup/templateedit.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/setup', 'Template edit' ) ) );
?>
