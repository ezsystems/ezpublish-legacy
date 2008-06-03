#!/usr/bin/env php
<?php
//
// Created on: <9-Jul-2007 14:00:25 dp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
$script = eZScript::instance( array( 'description' => ( "eZ Publish Country update script\n\n" .
                                                        "Upgrades db table in addition with upgrade from 3.9.2 to 3.9.3\n" .
                                                        "Fixes bug with aplying VAT rules" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );
$script->startup();

$options = $script->getOptions(  );

$script->initialize();

$db = eZDB::instance();

$countries = $db->arrayQuery( "SELECT country_code from ezvatrule;" );
$iniCountries = eZCountryType::fetchCountryList();

$updatedRules = 0;

foreach ( $countries as $country )
{
    foreach ( $iniCountries as $iniCountry )
    {
        if ( $iniCountry['Name'] == $country['country_code'] )
        {
            $countryName = $country['country_code'];
            $countryCode = $iniCountry['Alpha2'];
            $db->query( "UPDATE ezvatrule SET country_code='" . $db->escapeString( $countryCode ) . "' WHERE country_code='" . $db->escapeString( $countryName ) . "'" );
            $updatedRules++;
        }
    }
}

$cli->output( 'Updated VAT rules: ' . $updatedRules );

$script->shutdown();

?>
