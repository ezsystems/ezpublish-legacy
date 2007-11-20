#!/usr/bin/env php
<?php
//
// Created on: <28-Nov-2002 12:45:40 bf>
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
//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );
//include_once( "lib/ezutils/classes/ezini.php" );

$cli = eZCLI::instance();
$endl = $cli->endlineString();

$script = eZScript::instance( array( 'description' => ( "eZ Publish overridesettings generate.".
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
    $ini = eZINI::instance( 'site.ini', 'settings', null, null, true );
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
