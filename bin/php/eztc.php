#!/usr/bin/env php
<?php
//
// Created on: <02-Mar-2004 20:10:18 amos>
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

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => 'eZ publish Template Compiler',
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[compile-directory:]",
                                "",
                                array( 'compile-directory' => 'Where to place compiled files, default is var/cache/template/compiled' ) );
$script->initialize();

$ini =& eZINI::instance();
$standardDesign =& $ini->variable( "DesignSettings", "StandardDesign" );
$siteDesign =& $ini->variable( "DesignSettings", "SiteDesign" );
$additionalSiteDesignList = $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );

$designList = array_merge( array( $standardDesign ), $additionalSiteDesignList, array( $siteDesign ) );

include_once( 'kernel/common/template.php' );
$tpl =& templateInit();

$script->setIterationData( '.', '~' );

foreach ( $designList as $design )
{
    $cli->output( "Compiling in design " . $cli->stylize( 'emphasize', $design ) );
    $baseDir = 'design/' . $design;
    $files = eZDir::recursiveFindRelative( $baseDir, 'templates', "\.tpl" );
    $files = array_merge( $files, eZDir::recursiveFindRelative( $baseDir, 'override/templates', "\.tpl" ) );
    $script->resetIteration( count( $files ) );
    foreach ( $files as $fileRelative )
    {
        $file = $baseDir . '/' . $fileRelative;
        $status = $tpl->compileTemplateFile( $file );
        $text = false;
        if ( $status )
            $text = "Compiled template file: $file";
        else
            $text = "Compilation failed: $file";
        $script->iterate( $cli, $status, $text );
    }
}

$script->initialize();

$script->shutdown();

?>
