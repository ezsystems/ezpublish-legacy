#!/usr/bin/env php
<?php
//
// Created on: <28-Nov-2002 12:45:40 bf>
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

set_time_limit( 0 );

include_once( "lib/ezutils/classes/ezini.php" );
include_once( "lib/ezutils/classes/ezdebug.php" );

$ini =& eZINI::instance();

print( "Override settings\n" );

if ( isset( $argv[1] ) )
{
    print( "Using siteacces: " . $argv[1] . "\n" );
    $siteAccess = $argv[1];
    $ini =& eZINI::instance( 'site.ini', 'settings', null, null, true );
    $ini->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
    $ini->loadCache();

    $siteBase = $ini->variable( "DesignSettings", "SiteDesign" );
    print( "Looking for override templates in: $siteBase\n" );

    $nodeOverrideFileArray =& eZDir::recursiveFindRelative( "design/" . $siteBase . "/override/templates/", "",  "tpl" );
    print( "The following override templates where found:\n\n" );
    $overrideTxt = "";


    foreach ( $nodeOverrideFileArray as $overrideFile )
    {
        if ( preg_match( "#^(.*)\/(([0-9a-z_]+)_([0-9a-z]+)_([0-9a-z_]+))\.tpl$#", $overrideFile, $matchParts ) )
        {
            $matchFile = $overrideFile;

            //strip first slash
            if ( preg_match( "#^/(.*)$#", $matchFile, $matches ) )
                $matchFile = $matches[1];

            if ( preg_match( "#^/(.*)$#", $matchParts[1], $matches ) )
                $matchParts[1] = $matches[1];
            if ( $matchParts[1] != "" )
                $matchParts[1] .= "/";

            $overrideTxt .= "[" . $matchParts[2] . "]\n";
            $overrideTxt .= "Source=" . $matchParts[1] . $matchParts[3] . ".tpl\n";
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
    print( "Please supply the siteacces you want to generate overrides for\n" );
}

?>
