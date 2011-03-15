#!/usr/bin/env php
<?php
/**
 * File containing the makestaticcache.php script.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish static cache generator\n" .
                                                        "\n" .
                                                        "./bin/makestaticcache.php --siteaccess user" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[f|force]",
                                "",
                                array( 'force' => "Force generation of cache files even if they already exist." ) );

$force = $options['force'];

$script->initialize();

$staticCache = new eZStaticCache();
$staticCache->generateCache( $force, false, $cli, false );

if ( !$force )
{
    $staticCache->generateAlwaysUpdatedCache( false, $cli, false );
}

eZStaticCache::executeActions();

$script->shutdown();

?>
