#!/usr/bin/env php
<?php
/**
 * File containing the makestaticcache.php script.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

require_once 'autoload.php';

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

$ini = eZINI::instance();
if ( $ini->variable( 'ContentSettings', 'StaticCache' ) != 'enabled' )
{
    $cli->error( "You must first enable [ContentSettings] StaticCache in site.ini" );
    $script->shutdown( 1 );
}

$optionArray = array( 'iniFile'      => 'site.ini',
                      'iniSection'   => 'ContentSettings',
                      'iniVariable'  => 'StaticCacheHandler' );

$options = new ezpExtensionOptions( $optionArray );
$staticCacheHandler = eZExtension::getHandlerClass( $options );
$staticCacheHandler->generateCache( $force, false, $cli, false );

if ( !$force )
{
    $staticCacheHandler->generateAlwaysUpdatedCache( false, $cli, false );
}

call_user_func( array( $staticCacheHandler, 'executeActions' ) );
$script->shutdown();

?>
