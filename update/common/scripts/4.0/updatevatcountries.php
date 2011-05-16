#!/usr/bin/env php
<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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
