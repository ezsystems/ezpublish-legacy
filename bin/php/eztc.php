#!/usr/bin/env php
<?php
//
// Created on: <02-Mar-2004 20:10:18 amos>
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

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Template Compiler\n" .
                                                         "\n" .
                                                         "./bin/php/eztc.php -snews --www-dir='/mypath' --index-file='/index.php' --access-path='news'" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[compile-directory:][www-dir:][index-file:][access-path:][force][full-url][no-full-url]",
                                "",
                                array( 'force' => "Force compilation of template whether it has changed or not",
                                       'compile-directory' => "Where to place compiled files,\ndefault is template/compiled in current cache directory",
                                       'full-url' => "Makes sure generated urls have http:// in them (i.e. global), used mainly by sites that include the eZ Publish HTML (e.g payment gateways)",
                                       'no-full-url' => "Makes sure generated urls are relative to the site. (default)",
                                       'www-dir' => "The part before the index.php in your URL, you should supply this if you are running in non-virtualhost mode",
                                       'index-file' => "The name of your index.php if you are running in non-virtualhost mode",
                                       'access-path' => "Extra access path" ) );
$sys = eZSys::instance();

$forceCompile = false;
$useFullURL = false;

if ( $options['www-dir'] )
{
    $sys->WWWDir = $options['www-dir'];
}
if ( $options['index-file'] )
{
    $sys->IndexFile = $options['index-file'];
}
if ( $options['access-path'] )
{
    $sys->AccessPath = array( $options['access-path'] );
}
if ( $options['force'] )
{
    $forceCompile = true;
}
if ( $options['full-url'] )
{
    $useFullURL = true;
}
if ( $options['no-full-url'] )
{
    $useFullURL = false;
}

$script->initialize();

$http = eZHTTPTool::instance();
$http->UseFullUrl = $useFullURL;


if ( count( $options['arguments'] ) > 0 )
{
    $ini = eZINI::instance();

    require_once( 'kernel/common/template.php' );
    $tpl = templateInit();

    $fileList = $options['arguments'];

    $script->setIterationData( '.', '~' );
    $script->setShowVerboseOutput( true );
    if ( $forceCompile )
        eZTemplateCompiler::setSettings( array( 'generate' => true ) );

    $files = array();
    foreach ( $fileList as $file )
    {
        $filename = basename( $file );
        if ( preg_match( "!^.+~$|^/?#.+#$|^\..+$!", $filename ) )
            continue;
        $files[] = $file;
    }

    $script->resetIteration( count( $files ) );
    foreach ( $files as $file )
    {
        if ( is_dir( $file ) )
        {
            $script->iterate( $cli, true, "Skipping directory: " . $cli->stylize( 'dir', $file ) );
        }
        else
        {
            $status = $tpl->compileTemplateFile( $file );
            $text = false;
            if ( $status )
                $text = "Compiled template file: " . $cli->stylize( 'file', $file );
            else
                $text = "Compilation failed: " . $cli->stylize( 'file', $file );
            $script->iterate( $cli, $status, $text );
        }
    }
}
else
{
    $ini = eZINI::instance();
    $standardDesign = $ini->variable( "DesignSettings", "StandardDesign" );
    $siteDesign = $ini->variable( "DesignSettings", "SiteDesign" );
    $additionalSiteDesignList = $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );

    $designList = array_merge( array( $standardDesign ), $additionalSiteDesignList, array( $siteDesign ) );

    require_once( 'kernel/common/template.php' );
    $tpl = templateInit();

    $script->setIterationData( '.', '~' );
    if ( $forceCompile )
        eZTemplateCompiler::setSettings( array( 'generate' => true ) );

    $extensionDirectory = eZExtension::baseDirectory();
    $designINI = eZINI::instance( 'design.ini' );
    $extensions = $designINI->variable( 'ExtensionSettings', 'DesignExtensions' );

    foreach ( $designList as $design )
    {
        $cli->output( "Compiling in design " . $cli->stylize( 'emphasize', $design ) );
        $baseDir = 'design/' . $design;

        $files = eZDir::recursiveFindRelative( '', "$baseDir/templates", "\.tpl" );
        $files = array_merge( $files, eZDir::recursiveFindRelative( '', "$baseDir/override/templates", "\.tpl" ) );

        foreach( $extensions as $extension )
        {
            $files = array_merge( $files, eZDir::recursiveFindRelative( '', "$extensionDirectory/$extension/$baseDir/templates", "\.tpl" ) );
            $files = array_merge( $files, eZDir::recursiveFindRelative( '', "$extensionDirectory/$extension/$baseDir/override/templates", "\.tpl" ) );
        }

        $script->resetIteration( count( $files ) );
        foreach ( $files as $file )
        {
            $status = $tpl->compileTemplateFile( $file );
            $text = false;
            if ( $status )
                $text = "Compiled template file: " . $cli->stylize( 'file', $file );
            else
                $text = "Compilation failed: " . $cli->stylize( 'file', $file );
            $script->iterate( $cli, $status, $text );
        }
    }
}

$script->shutdown();

?>
