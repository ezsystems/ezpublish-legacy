#!/usr/bin/env php
<?php
//
// Created on: <19-Mar-2004 09:51:56 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
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
$script =& eZScript::instance( array( 'description' => ( "eZ publish Code Template Generator\n\n" .
                                                         "This will apply any template blocks it finds in files\n" .
                                                         "and writes back the new file\n" .
                                                         "\n" .
                                                         "The return code is set to 0 if no changes occured, 1 if a file is changed\n" .
                                                         "or 2 if an error occurs" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "",
                                "[file]",
                                array() );
$script->initialize();

if ( count( $options['arguments'] ) < 1 )
{
    $cli->error( "Need at least one file" );
    $script->shutdown( 1 );
}

include_once( 'kernel/classes/ezcodetemplate.php' );

$hasErrors = false;
$hasModified = false;

$tpl = new eZCodeTemplate();
foreach ( $options['arguments'] as $file )
{
    $status = $tpl->apply( $file );
    if ( $status == EZ_CLASS_TEMPLATE_STATUS_OK )
    {
        $cli->output( "Updated " . $cli->stylize( 'file', $file ) );
        $hasModified = true;
    }
    else if ( $status == EZ_CLASS_TEMPLATE_STATUS_NO_CHANGE )
    {
        $cli->output( "No change in " . $cli->stylize( 'file', $file ) );
    }
    else if ( $status == EZ_CLASS_TEMPLATE_STATUS_FAILED )
    {
        $cli->output( "Template errors for " . $cli->stylize( 'file', $file ) );
        $hasErrors = true;
    }
}

if ( $hasErrors )
    $script->shutdown( 2 );
else if ( $hasModified )
    $script->shutdown( 1 );

$script->shutdown();

?>
