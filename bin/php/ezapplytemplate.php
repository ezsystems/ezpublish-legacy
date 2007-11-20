#!/usr/bin/env php
<?php
//
// Created on: <19-Mar-2004 09:51:56 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Code Template Generator\n\n" .
                                                        "This will apply any template blocks it finds in files\n" .
                                                        "and writes back the new file\n" .
                                                        "\n" .
                                                        "The return code is set to 0 if no changes occured, 1 if a file is changed\n" .
                                                        "or 2 if an error occurs" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[a|all][check-only]",
                                "[file]",
                                array( 'all' => 'Go trough all files defined in codetemplate.ini',
                                       'check-only' => 'Will only check if the files will be changed or have errors' ) );
$script->initialize();

if ( !$options['all'] and count( $options['arguments'] ) < 1 )
{
    $cli->error( "Need at least one file" );
    $script->shutdown( 1 );
}

//include_once( 'kernel/classes/ezcodetemplate.php' );

$hasErrors = false;
$hasModified = false;

$tpl = new eZCodeTemplate();

if ( $options['all'] )
{
    $files = $tpl->allCodeFiles();
}
else
{
    $files = $options['arguments'];
}

foreach ( $files as $file )
{
    $status = $tpl->apply( $file, $options['check-only'] );
    if ( $status == eZCodeTemplate::STATUS_OK )
    {
        $cli->output( "Updated " . $cli->stylize( 'file', $file ) );
        $hasModified = true;
    }
    else if ( $status == eZCodeTemplate::STATUS_NO_CHANGE )
    {
        $cli->output( "No change in " . $cli->stylize( 'file', $file ) );
    }
    else if ( $status == eZCodeTemplate::STATUS_FAILED )
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
