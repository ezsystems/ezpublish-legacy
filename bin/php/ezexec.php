#!/usr/bin/env php
<?php
//
// Created on: <03-May-2004 07:52:57 amos>
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

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish Script Executor\n\n" .
                                                         "Allows execution of simple PHP scripts which uses eZ publish functionality,\n" .
                                                         "when the script is called all necessary initialization is done\n" .
                                                         "\n" .
                                                         "ezexec.php myscript.php" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "",
                                "[scriptfile]",
                                array() );
$script->initialize();

if ( count( $options['arguments'] ) < 1 )
{
    $script->shutdown( 1, "Missing script file" );
}

$scriptFile = $options['arguments'][0];

if ( !file_exists( $scriptFile ) )
    $script->shutdown( 1, "Could execute the script '$scriptFile', file was not found" );

$retCode = include( $scriptFile );

if ( $retCode != 1 )
    $script->setExitCode( 1 );

$script->shutdown();

?>
