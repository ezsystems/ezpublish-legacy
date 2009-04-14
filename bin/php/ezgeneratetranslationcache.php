#!/usr/bin/env php
<?php
//
// Created on: <6-Apr-2007 15:00:00 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

// Generate caches for translations
// file  bin/php/ezgeneratetranslationcache.php


/**************************************************************
* script initializing                                         *
***************************************************************/

require 'autoload.php';

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
