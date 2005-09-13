#!/usr/bin/env php
<?php
//
// Created on: <28-Nov-2002 12:45:40 bf>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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
include_once( "lib/ezutils/classes/ezini.php" );

$cli =& eZCLI::instance();
$endl = $cli->endlineString();

$script =& eZScript::instance( array( 'description' => ( "eZ publish overridesettings generate.".
                                                         "\n" .
                                                         "generateoverridesettings.php" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "", "" );
$script->initialize();

$hasSiteAccess = $options['siteaccess'] ? true : false;

print( "Override settings\n" );
if ( $hasSiteAccess )
{
    $siteAccess = $options['siteaccess'];
    print( "Using siteacces: " . $siteAccess . "\n" );
    $ini =& eZINI::instance( 'site.ini', 'settings', null, null, true );
    $ini->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
    $ini->loadCache();

    $siteBase = $ini->variable( "DesignSettings", "SiteDesign" );
    print( "Looking for override templates in: $siteBase\n" );

    $nodeOverrideFileArray = eZDir::recursiveFindRelative( "design/" . $siteBase . "/override/templates/", "",  "tpl" );
    print( "The following override templates where found:\n\n" );
    $overrideTxt = "";
    foreach ( $nodeOverrideFileArray as $overrideFile )
    {
        if ( preg_match( "#^/(.*)\/(([0-9a-z_]+)_([0-9a-z]+)_([0-9a-z_]++))\.tpl$#", $overrideFile, $matchParts ) )
        {
            $matchFile = $overrideFile;
            if ( preg_match( "#^/(.*)$#", $matchFile, $matches ) )
                $matchFile = $matches[1];
            $overrideTxt .= "[" . $matchParts[2] . "]\n";
            $overrideTxt .= "Source=" . $matchParts[1] . "/" . $matchParts[3] . ".tpl\n";
            $overrideTxt .= "MatchFile=" . $matchFile . "\n";
            $overrideTxt .= "Subdir=templates\n";
            $overrideTxt .= "Match[" .$matchParts[4] . "]=" . $matchParts[5] . "\n";
            $overrideTxt .= "\n";
        }
    }

    print( "\n" );
    // Generate override for node view
    print( $overrideTxt );
}
else
{
    $cli->error( "Please supply the siteacces you want to generate overrides for\n" );
    $script->shutdown( 1 );
}
$script->shutdown();

?>
