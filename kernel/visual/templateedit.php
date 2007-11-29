<?php
//
// Created on: <09-May-2003 10:44:02 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
//include_once( "kernel/common/eztemplatedesignresource.php" );
//include_once( 'lib/ezutils/classes/ezhttptool.php' );
//include_once( 'lib/ezi18n/classes/eztextcodec.php' );

$http = eZHTTPTool::instance();
$module = $Params['Module'];
$parameters = $Params["Parameters"];

if ( $http->hasPostVariable( 'Cancel' ) )
{
    return $Module->redirectTo( $http->postVariable( 'RedirectToURI' ) );
}

$ini = eZINI::instance();
$tpl = templateInit();

$Result = array();
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/design', 'Template edit' ) ) );

$template = "";
$i = 0;
foreach ( $parameters as $param )
{
    if ( $i > 0 )
        $template .= "/";
    $template .= "$param";
    $i++;
}

$siteAccess = $Params['SiteAccess'];
if( $siteAccess )
    $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $siteAccess );
else
    $siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );

$overrideArray = eZTemplateDesignResource::overrideArray( $siteAccess );

// Check if template already exists
$isExistingTemplate = false;
foreach ( $overrideArray as $overrideSetting )
{
    if ( $overrideSetting['base_dir'] . $overrideSetting['template'] == $template )
    {
        $isExistingTemplate = true;
        break;
    }
    elseif ( isset( $overrideSetting['custom_match'] ) )
    {
        foreach ( $overrideSetting['custom_match'] as $customMatch )
        {
            if ( $customMatch['match_file'] == $template )
            {
                $isExistingTemplate = true;
                break 2;
            }
        }
    }
}

if ( $isExistingTemplate == false )
{
    $tpl->setVariable( 'template', $template );
    $tpl->setVariable( 'template_exists', false );
    $tpl->setVariable( 'original_template', false );
    $tpl->setVariable( 'site_access', $siteAccess );

    $Result['content'] = $tpl->fetch( "design:visual/templateedit_error.tpl" );
    return;
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
                break 2;
            }
        }
    }
}

/* Check if we need to do characterset conversions for editting and saving
 * templates. */
$templateConfig = eZINI::instance( 'template.ini' );
$i18nConfig = eZINI::instance( 'i18n.ini' );

/* First we check the HTML Output Charset */
$outputCharset = eZTextCodec::internalCharset();

if ( $module->isCurrentAction( 'Save' ) )
{
    if ( $http->hasPostVariable( 'TemplateContent' ) )
    {
        $templateContent = $http->postVariable( 'TemplateContent' );

        if ( $templateConfig->variable( 'CharsetSettings', 'AutoConvertOnSave') == 'enabled' )
        {
            //include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
            $outputCharset = eZCharsetInfo::realCharsetCode( $outputCharset );
            if ( preg_match( '|{\*\?template.*charset=([a-zA-Z0-9-]*).*\?\*}|', $templateContent, $matches ) )
            {
                $templateContent = preg_replace( '|({\*\?template.*charset=)[a-zA-Z0-9-]*(.*\?\*})|',
                                                 '\\1'. $outputCharset. '\\2',
                                                 $templateContent );
            }
            else
            {
                $templateCharset = eZCharsetInfo::realCharsetCode( $templateConfig->variable( 'CharsetSettings', 'DefaultTemplateCharset') );
                if ( $templateCharset != $outputCharset )
                    $templateContent = "{*?template charset=$outputCharset?*}\n" . $templateContent;
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
            $codec = eZTextCodec::instance( $outputCharset, $templateCharset, false );
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

        $siteConfig = eZINI::instance( 'site.ini' );
        $filePermissions = $siteConfig->variable( 'FileSettings', 'StorageFilePermissions');
        @chmod( $template, octdec( $filePermissions ) );

        // Expire content view cache
        //include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearAllContentCache();

        $module->redirectTo( '/visual/templateview'. $originalTemplate );
        return eZModule::HOOK_STATUS_CANCEL_RUN;
    }
}


if ( $module->isCurrentAction( 'Discard' ) )
{
    $module->redirectTo( '/visual/templateview'. $originalTemplate );
    return eZModule::HOOK_STATUS_CANCEL_RUN;
}

// get the content of the template
$fileName = $template;

if ( !is_readable( $fileName ) )
{
    $tpl->setVariable( 'template', $template );
    $tpl->setVariable( 'template_exists', true );
    $tpl->setVariable( 'original_template', $originalTemplate );
    $tpl->setVariable( 'is_readable', false );
    $tpl->setVariable( 'site_access', $siteAccess );

    $Result['content'] = $tpl->fetch( "design:visual/templateedit_error.tpl" );
    return;
}

if ( !is_writable( $fileName ) )
{
    if ( $http->hasPostVariable( 'OpenReadOnly' ) )
    {
        $tpl->setVariable( 'is_writable', false );
    }
    else
    {
        $tpl->setVariable( 'template', $template );
        $tpl->setVariable( 'template_exists', true );
        $tpl->setVariable( 'original_template', $originalTemplate );
        $tpl->setVariable( 'is_readable', true );
        $tpl->setVariable( 'site_access', $siteAccess );

        $Result['content'] = $tpl->fetch( "design:visual/templateedit_error.tpl" );
        return;
    }
}
else
{
    $tpl->setVariable( 'is_writable', true );
}

$templateContent = file_get_contents( $fileName );

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
$codec = eZTextCodec::instance( $templateCharset, $outputCharset, false );
if ( $codec )
{
    $templateContent = $codec->convertString( $templateContent );
}

$tpl->setVariable( 'template', $template );
$tpl->setVariable( 'template_content', $templateContent );

$Result['content'] = $tpl->fetch( "design:visual/templateedit.tpl" );

?>
