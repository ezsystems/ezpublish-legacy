#!/usr/bin/env php
<?php
//
// Created on: <14-Jan-2005 09:27:13 dr>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish static cache generator\n" .
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

include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'lib/ezfile/classes/ezdir.php' );
include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'kernel/classes/ezstaticcache.php' );

$staticCache = new eZStaticCache();
$staticCache->generateCache( $force, false, $cli, false );

if ( !$force )
{
    $staticCache->generateAlwaysUpdatedCache( false, $cli, false );
}

eZStaticCache::executeActions();

$script->shutdown();

?>
