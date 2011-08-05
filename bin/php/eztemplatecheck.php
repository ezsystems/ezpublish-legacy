#!/usr/bin/env php
<?php
/**
 * File containing the eztemplatecheck.php script.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Template Syntax Checker\n" .
                                                        "\n" .
                                                        "./bin/php/eztemplatecheck.php -sadmin\n" .
                                                        "or\n" .
                                                        "./bin/php/eztemplatecheck.php design/" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "", "[FILE*]", array() );
$sys = eZSys::instance();

$script->initialize();

$result = true;

if ( count( $options['arguments'] ) > 0 )
{
    $ini = eZINI::instance();
    $tpl = eZTemplate::factory();

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
    $ini = eZINI::instance();
    $standardDesign = $ini->variable( "DesignSettings", "StandardDesign" );
    $siteDesign = $ini->variable( "DesignSettings", "SiteDesign" );
    $additionalSiteDesignList = $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );

    $designList = array_merge( array( $standardDesign ), $additionalSiteDesignList, array( $siteDesign ) );

    $tpl = eZTemplate::factory();

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
