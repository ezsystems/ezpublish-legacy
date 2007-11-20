#!/usr/bin/env php
<?php
//
// Created on: <9-Jul-2007 14:00:25 dp>
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
//include_once( 'kernel/classes/datatypes/ezcountry/ezcountrytype.php' );

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Country update script\n\n" .
                                                        "Upgrades db table in addition with upgrade from 3.8.x to 3.9.x\n" .
                                                        "Fixes bug with apllying VAT rules" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );
$script->startup();

$options = $script->getOptions(  );

$script->initialize();

$db = eZDB::instance();

$countries = $db->query( "SELECT country from ezvatrule;" );
$iniCountries = eZCountryType::fetchCountryList();

foreach ($countries as $country)
{
    foreach ( $iniCountries as $iniCountry )
    {
        if ( $iniCountry['Name'] == $country ) {
            $countryCode = $iniCountry['Alpha2'] ;
            $db->query( "UPDATE TABLE ezvatrule SET country_code=$countryCode WHERE country_code=$country;" );
        }
    }
}

$script->shutdown();

?>
