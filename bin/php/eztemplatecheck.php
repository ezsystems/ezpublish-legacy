#!/usr/bin/env php
<?php
//
// Created on: <29-Jul-2004 13:53:15 amos>
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

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish Template Syntax Checker\n" .
                                                         "\n" .
                                                         "./bin/php/eztemplatecheck.php -sadmin\n" .
                                                         "or\n" .
                                                         "./bin/php/eztemplatecheck.php design/" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "", "[FILE*]", array() );
$sys =& eZSys::instance();

$script->initialize();

$result = true;

if ( count( $options['arguments'] ) > 0 )
{
    $ini =& eZINI::instance();

    include_once( 'kernel/common/template.php' );
    $tpl =& templateInit();

    $fileList = array();

    foreach ( $options['arguments'] as $file )
    {
        if ( is_dir( $file ) )
        {
            $fileList = array_merge( $fileList, eZDir::recursiveFindRelative( '', $file, "\.tpl" ) );
        }
        else if ( is_file( $file ) )
        {
            $fileList[] = $file;
        }
    }
    $fileList = array_unique( $fileList );

    $script->setIterationData( '.', '~' );
    $script->setShowVerboseOutput( true );

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
            $status = $tpl->validateTemplateFile( $file );
            $text = false;
            if ( $status )
                $text = "Template file valid: " . $cli->stylize( 'file', $file );
            else
                $text = "Template file invalid: " . $cli->stylize( 'file', $file );
            if ( !$status )
                $result = false;
            $script->iterate( $cli, $status, $text );
        }
    }
}
else
{
    $ini =& eZINI::instance();
    $standardDesign =& $ini->variable( "DesignSettings", "StandardDesign" );
    $siteDesign =& $ini->variable( "DesignSettings", "SiteDesign" );
    $additionalSiteDesignList = $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );

    $designList = array_merge( array( $standardDesign ), $additionalSiteDesignList, array( $siteDesign ) );

    include_once( 'kernel/common/template.php' );
    $tpl =& templateInit();

    $script->setIterationData( '.', '~' );
    $script->setShowVerboseOutput( true );

    foreach ( $designList as $design )
    {
        $cli->output( "Validating in design " . $cli->stylize( 'emphasize', $design ) );
        $baseDir = 'design/' . $design;
        $files = eZDir::recursiveFindRelative( $baseDir, 'templates', "\.tpl" );
        $files = array_merge( $files, eZDir::recursiveFindRelative( $baseDir, 'override/templates', "\.tpl" ) );
        $script->resetIteration( count( $files ) );
        foreach ( $files as $fileRelative )
        {
            $file = $baseDir . '/' . $fileRelative;
            $status = $tpl->validateTemplateFile( $file );
            $text = false;
            if ( $status )
                $text = "Template file valid: " . $cli->stylize( 'file', $file );
            else
                $text = "Template file invalid: " . $cli->stylize( 'file', $file );
            if ( !$status )
                $result = false;
            $script->iterate( $cli, $status, $text );
        }
    }
}

if ( !$result )
{
    $script->shutdown( 1, "Some templates did not validate" );
}
else
{
    $script->shutdown();
}

?>
