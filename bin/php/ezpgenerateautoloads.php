#!/usr/bin/env php
<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

if ( file_exists( "config.php" ) )
{
    require "config.php";
}

// Setup, includes
//{
if ( !@include( 'ezc/Base/base.php' ) )
{
    require "Base/src/base.php";
}

require 'lib/ezutils/classes/ezautoloadgenerator.php';

function __autoload( $className )
{
    ezcBase::autoload( $className );
}
//}

// Setup console parameters
//{
$params = new ezcConsoleInput();

$helpOption = new ezcConsoleOption( 'h', 'help' );
$helpOption->mandatory = false;
$helpOption->shorthelp = "Show help information";
$params->registerOption( $helpOption );

$targetOption = new ezcConsoleOption( 't', 'target', ezcConsoleInput::TYPE_STRING );
$targetOption->mandatory = false;
$targetOption->default = "autoload";
$targetOption->shorthelp = "The directory to where the generated autoload file should be written.";
$params->registerOption( $targetOption );

$verboseOption = new ezcConsoleOption( 'v', 'verbose', ezcConsoleInput::TYPE_NONE );
$verboseOption->mandatory = false;
$verboseOption->shorthelp = "Whether or not to display more information.";
$params->registerOption( $verboseOption );

$dryrunOption = new ezcConsoleOption( 'n', 'dry-run', ezcConsoleInput::TYPE_NONE );
$dryrunOption->mandatory = false;
$dryrunOption->shorthelp = "Whether a new file autoload file should be written.";
$params->registerOption( $dryrunOption );

$kernelFilesOption = new ezcConsoleOption( 'k', 'kernel', ezcConsoleInput::TYPE_NONE );
$kernelFilesOption->mandatory = false;
$kernelFilesOption->shorthelp = "If an autoload array for the kernel files should be generated.";
$params->registerOption( $kernelFilesOption );

$extensionFilesOption = new ezcConsoleOption( 'e', 'extension', ezcConsoleInput::TYPE_NONE );
$extensionFilesOption->mandatory = false;
$extensionFilesOption->shorthelp = "If an autoload array for the extensions should be generated.";
$params->registerOption( $extensionFilesOption );

$testFilesOption = new ezcConsoleOption( 's', 'tests', ezcConsoleInput::TYPE_NONE );
$testFilesOption->mandatory = false;
$testFilesOption->shorthelp = "If an autoload array for the tests should be generated.";
$params->registerOption( $testFilesOption );

$excludeDirsOption = new ezcConsoleOption( '', 'exclude', ezcConsoleInput::TYPE_STRING );
$excludeDirsOption->mandatory = false;
$excludeDirsOption->shorthelp = "Folders to exclude from the class search.";
$params->registerOption( $excludeDirsOption );

// Process console parameters
try
{
    $params->process();
}
catch ( ezcConsoleOptionException $e )
{
    print( $e->getMessage(). "\n" );
    print( "\n" );

    echo $params->getHelpText( 'Autoload file generator.' ) . "\n";

    echo "\n";
    exit();
}

if ( $helpOption->value === true )
{
    echo $params->getHelpText( 'Autoload file generator.' ) . "\n";
    exit();
}
//}

if ( $excludeDirsOption->value !== false )
{
    $excludeDirs = explode( ' ', $excludeDirsOption->value );
}
else
{
    $excludeDirs = false;
}


$autoloadGenerator = new eZAutoloadGenerator( getcwd(),
                                              $kernelFilesOption->value,
                                              $extensionFilesOption->value,
                                              $testFilesOption->value,
                                              $verboseOption->value,
                                              !$dryrunOption->value,
                                              $targetOption->value,
                                              $excludeDirs );
try {
    $autoloadGenerator->buildAutoloadArrays();
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

?>
