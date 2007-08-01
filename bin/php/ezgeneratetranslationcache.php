#!/usr/bin/env php
<?php
//
// Created on: <`6-Apr-2007 15:00:00 dl>
//
// Copyright (C) 1999-2007 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

// Generate caches for translations
// file  bin/php/ezgeneratetranslationcache.php


/**************************************************************
* script initializing                                         *
***************************************************************/

include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "\n" .
                                                         "This script will generate caches for translations.\n" .
                                                         "Default usage: ./bin/php/ezgeneratetranslationcache -s setup\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => false,
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

include_once( 'lib/ezi18n/classes/eztstranslator.php' );
include_once( 'lib/ezi18n/classes/eztranslatormanager.php' );

/**************************************************************
* process options                                             *
***************************************************************/

//
// 'ts-list' option
//
$translations = split( ' ', $scriptOptions['ts-list'] );
$translations = eZTSTranslator::fetchList( $translations );


/**************************************************************
* do the work
***************************************************************/

$cli->output( $cli->stylize( 'blue', "Processing: " ), false );

$ini =& eZINI::instance();

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
