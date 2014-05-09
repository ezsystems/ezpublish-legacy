#!/usr/bin/env php
<?php
/**
 * File containing the ezgeneratetranslationcache.php script.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

// Generate caches for translations
// file  bin/php/ezgeneratetranslationcache.php


/**************************************************************
* script initializing                                         *
***************************************************************/

require_once 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "\n" .
                                                        "This script will generate caches for translations.\n" .
                                                        "Default usage: ./bin/php/ezgeneratetranslationcache -s setup\n" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true,
                                     'user' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "[ts-list:]",
                                      "",
                                      array( 'ts-list' => "A list of translations to generate caches for, for example 'rus-RU nor-NO'\n".
                                                          "By default caches for all translations will be generated" ),
                                      false,
                                      array( 'user' => true )
                                     );
$script->initialize();

/**************************************************************
* process options                                             *
***************************************************************/

//
// 'ts-list' option
//
$translations = isset( $scriptOptions['ts-list'] ) ? explode( ' ', $scriptOptions['ts-list'] ) : array();
$translations = eZTSTranslator::fetchList( $translations );


/**************************************************************
* do the work
***************************************************************/

$cli->output( $cli->stylize( 'blue', "Processing: " ), false );

$ini = eZINI::instance();

foreach( $translations as $translation )
{
    $cli->output( "$translation->Locale ", false );

    $ini->setVariable( 'RegionalSettings', 'Locale', $translation->Locale );
    eZTranslationCache::resetGlobals();

    $translation->load( '' );
}

$cli->output( "", true );

$script->shutdown( 0 );

?>
