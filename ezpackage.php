#!/usr/bin/env php
<?php
//
// Created on: <18-Mar-2003 17:06:45 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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


include_once( "lib/ezutils/classes/ezextension.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'debug-message' => '',
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$endl = $cli->endlineString();
$webOutput = $cli->isWebOutput();

$siteaccess = false;
$debugOutput = false;
$useColors = false;
$isQuiet = false;
$useLogFiles = false;
$userLogin = false;
$userPassword = false;
$command = false;

$packageName = false;
$packageSummary = false;
$packageLicence = false;
$packageVersion = false;
$packageFile = false;
$outputFile = false;
$exportType = false;
$exportParameters = array();


$optionsWithData = array( 's', 'o', 'l', 'p' );
$longOptionsWithData = array( 'siteaccess', 'login', 'password' );

function help()
{
    $argv = $_SERVER['argv'];
    print( "Usage: " . $argv[0] . " [OPTION]... COMMAND\n" .
           "Handles ezpublish packages.\n" .
           "\n" .
           "Type " . $argv[0] . " help for command overview\n" .
           "\n" .
           "General options:\n" .
           "  -h,--help          display this help and exit \n" .
           "  -q,--quiet         do not give any output except errors occur\n" .
           "  -s,--siteaccess    selected siteaccess for operations, if not specified default siteaccess is used\n" .
           "  -d,--debug         display debug output at end of execution\n" .
           "  -c,--colors        display output using ANSI colors\n" .
           "  -l,--login USER    login with USER and use it for all operations\n" .
           "  -p,--password PWD  use PWD as password for USER\n" .
           "  --logfiles         create log files\n" .
           "  --no-logfiles      do not create log files (default)\n" .
           "  --no-colors        do not use ANSI coloring (default)\n" );
}

function helpCreate()
{
    print( "create: Create a new package.\n" .
           "usage: create NAME [SUMMARY [LICENCE [VERSION]]] [PARAMETERS]\n" .
           "\n" .
           "Parameters:\n"
           );
}

function helpExport()
{
    print( "export: Export a part of the eZ publish installation into a package.\n" .
           "usage: export TYPE [PARAMETERS]... [TYPE [PARAMETERS]...]...\n" .
           "\n" .
           "Options:\n" .
           "  -o,--output FILE   export to file\n"
           );
}

function helpImport()
{
    print( "import: Import an eZ publish package installation and install it.\n" .
           "usage: import PACKAGE\n" .
           "\n" .
           "PACKAGE can be specified with just the name of the of package or\n" .
           "the filename of the package. If just the name is used the package\n" .
           "will be looked for by appending .ezpkg\n"
           );
}

function helpList()
{
    print( "list (ls): Lists all packages in the repository.\n" .
           "usage: list\n"
           );
}

function helpInfo()
{
    print( "info: Displays information on a given package.\n" .
           "usage: info PACKAGE\n"
           );
}

function helpAdd()
{
    print( "add: Adds an eZ publish part to the package.\n" .
           "usage: add PACKAGE PART [PART PARAMETERS]...\n" .
           "\n" .
           "Note: Will open up a new release if no open releases exists yet."
           );
}

function helpDelete()
{
    print( "delete (del, remove, rm): Removes an eZ publish part from the package.\n" .
           "usage: delete PACKAGE PART [PART PARAMETERS]...\n" .
           "\n" .
           "Note: Will open up a new release if no open releases exists yet."
           );
}

function helpHelp()
{
    $argv = $_SERVER['argv'];
    print( "help: Displays help information on commands.\n" .
           "usage: help COMMAND\n" .
           "\n" .
           "Type \"" . $argv[0] . " help COMMAND\" for help on a specific command.\n" .
           "\n" .
           "Available commands:\n" .
           "   help (?, h)\n" .
           "   create\n" .
           "   import\n" .
           "   export\n" .
           "   add\n" .
           "   delete (del, remove, rm)\n" .
           "   list\n" .
           "   info\n"
           );
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        print( "Using siteaccess $siteaccess for cronjob" );
    }
    else
    {
        print( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

$commandAlias = array();
$commandAlias['help'] = array( '?', 'h' );
$commandAlias['delete'] = array( 'del', 'remove', 'rm' );
$commandAlias['list'] = array( 'ls' );
$commandMap = array();

foreach ( $commandAlias as $alias => $list )
{
    foreach ( $list as $commandName )
    {
        $commandMap[$commandName] = $alias;
    }
}

$readOptions = true;

for ( $i = 1; $i < count( $argv ); ++$i )
{
    $arg = $argv[$i];
    if ( $readOptions and
         strlen( $arg ) > 0 and
         $arg[0] == '-' )
    {
        if ( strlen( $arg ) > 1 and
             $arg[1] == '-' )
        {
            $flag = substr( $arg, 2 );
            if ( in_array( $flag, $longOptionsWithData ) )
            {
                $optionData = $argv[$i+1];
                ++$i;
            }
            if ( $flag == 'help' )
            {
                help();
                exit();
            }
            else if ( $flag == 'siteaccess' )
            {
                changeSiteAccessSetting( $siteaccess, $optionData );
            }
            else if ( $flag == 'debug' )
            {
                $debugOutput = true;
            }
            else if ( $flag == 'quiet' )
            {
                $isQuiet = true;
            }
            else if ( $flag == 'colors' )
            {
                $useColors = true;
            }
            else if ( $flag == 'no-colors' )
            {
                $useColors = false;
            }
            else if ( $flag == 'no-logfiles' )
            {
                $useLogFiles = false;
            }
            else if ( $flag == 'logfiles' )
            {
                $useLogFiles = true;
            }
            else if ( $flag == 'login' )
            {
                $userLogin = $optionData;
            }
            else if ( $flag == 'password' )
            {
                $userPassword = $optionData;
            }
        }
        else
        {
            $flag = substr( $arg, 1, 1 );
            $optionData = false;
            if ( in_array( $flag, $optionsWithData ) )
            {
                if ( strlen( $arg ) > 2 )
                {
                    $optionData = substr( $arg, 2 );
                }
                else
                {
                    $optionData = $argv[$i+1];
                    ++$i;
                }
            }
            if ( $flag == 'h' )
            {
                help();
                exit();
            }
            else if ( $flag == 'q' )
            {
                $isQuiet = true;
            }
            else if ( $flag == 'o' )
            {
                $outputFile = $optionData;
            }
            else if ( $flag == 'c' )
            {
                $useColors = true;
            }
            else if ( $flag == 'd' )
            {
                $debugOutput = true;
            }
            else if ( $flag == 's' )
            {
                changeSiteAccessSetting( $siteaccess, $optionData );
            }
            else if ( $flag == 'o' )
            {
                $outputFile = $optionData;
            }
            else if ( $flag == 'l' )
            {
                $userLogin = $optionData;
            }
            else if ( $flag == 'p' )
            {
                $userPassword = $optionData;
            }
        }
    }
    else
    {
        if ( $command === false )
        {
            $realCommand = $arg;
            // Check for alias
            if ( isset( $commandMap[$realCommand] ) )
                $command = $commandMap[$realCommand];
            else
                $command = $realCommand;
            if ( !in_array( $command,
                           array( 'help',
                                  'create', 'import', 'export',
                                  'add', 'delete',
                                  'list', 'info' ) ) )
            {
                help();
                exit();
            }
        }
        else
        {
            if ( $command == 'help' )
            {
                $realHelpTopic = $arg;
                // Check for alias
                if ( isset( $commandMap[$realHelpTopic] ) )
                    $helpTopic = $commandMap[$realHelpTopic];
                else
                    $helpTopic = $realHelpTopic;
                if ( $helpTopic == 'import' )
                    helpImport();
                else if ( $helpTopic == 'export' )
                    helpExport();
                else if ( $helpTopic == 'create' )
                    helpCreate();
                else if ( $helpTopic == 'add' )
                    helpAdd();
                else if ( $helpTopic == 'delete' )
                    helpDelete();
                else if ( $helpTopic == 'list' )
                    helpList();
                else if ( $helpTopic == 'info' )
                    helpInfo();
                else
                    helpHelp();
                exit();
            }
            else if ( $command == 'create' )
            {
                if ( $packageName === false )
                    $packageName = $arg;
                else if ( $packageSummary === false )
                    $packageSummary = $arg;
                else if ( $packageLicence === false )
                    $packageLicence = $arg;
                else if ( $packageVersion === false )
                    $packageVersion = $arg;
            }
            else if ( $command == 'import' )
            {
                if ( $packageFile === false )
                    $packageFile = $arg;
            }
            else if ( $command == 'export' )
            {
                if ( $exportType === false )
                {
                    $readOptions = false;
                    $exportType = $arg;
                }
                else
                    $exportParameters[] = $arg;
            }
        }
    }
}
$script->setUseDebugOutput( $debugOutput );

if ( $command == 'import' )
{
    if ( !$packageFile )
    {
        helpImport();
        exit();
    }
}
else if ( $command == 'export' )
{
    if ( !$exportType )
    {
        helpExport();
        exit();
    }
}
else if ( $command == 'create' )
{
    if ( !$packageName )
    {
        helpCreate();
        exit();
    }
}
else if ( $command == 'help' )
{
    helpHelp();
    exit();
}
else
{
    help();
    exit();
}

if ( $webOutput )
    $useColors = false;

$cli->setUseStyles( $useColors );

$script->setUseSiteAccess( $siteaccess );
$script->setUser( $userLogin, $userPassword );

$script->initialize();

include_once( 'kernel/classes/ezpackage.php' );

if ( $command == 'list' )
{
    $packages = eZPackage::fetchPackages();
    if ( count( $packages ) > 0 )
    {
        $cli->output( "The following packages are installed:" );
        foreach ( $packages as $package )
        {
            $cli->output( $package->attribute( 'name' ) . ' (' . $package->attribute( 'summary' ) . ')' );
        }
    }
    else
        $cli->output( "No packages are installed" );
}
else if ( $command == 'import' )
{
    $package =& eZPackage::fetchFromFile( $packageFile );
    if ( $package )
    {
        $package->install();
    }
    else
    {
        $cli->warning( "Could not open package file $packageFile" );
    }
}
else if ( $command == 'export' )
{
    $packageName = 'mytest';
    $packageSummary = 'hm';
    $packageExtension = 'myext';

    $package =& eZPackage::create( $packageName, array( 'summary' => $packageSummary,
                                                        'extension' => $packageExtension ) );

    $user =& eZUser::currentUser();
    $userObject = $user->attribute( 'contentobject' );

    $package->appendMaintainer( $userObject->attribute( 'name' ), 'jb@ez.no', 'lead' );

    $package->appendDocument( 'README' );
    $package->appendDocument( 'readme.html', 'text/html', false, 'end-user' );
    $package->appendDocument( 'INSTALL', false, 'unix', 'site-admin' );

    $package->appendGroup( 'design' );
    $package->appendGroup( 'community/forum' );

    $package->appendChange( 'Jan Borsodi', 'jb@ez.no', 'Added some stuff' );

    $package->setRelease( '1.0.5', '2', false, 'GPL', 'beta' );

// $package->appendFileList( array( array( 'role' => 'override',
//                                         'md5sum' => false,
//                                         'name' => 'forum.tpl' ) ),
//                           'template', false,
//                           array( 'design' => 'standard' ) );

// $package->appendInstall( 'part', 'Classes', false, true,
//                          array( 'content' => 'yup' ) );

    $exportList = array();
    $exportList[] = array( 'type' => $exportType,
                           'parameters' => $exportParameters );
    $package->handleExportList( $exportList );

    if ( $outputFile )
    {
        $package->storeToFile( $outputFile );
    }
    else
    {
        print( $package->toString() . "\n" );
    }
}
else if ( $command == 'create' )
{
    $package =& eZPackage::create( $packageName, array( 'summary' => $packageSummary ) );

    $user =& eZUser::currentUser();
    $userObject = $user->attribute( 'contentobject' );

    if ( !$packageLicence )
        $packageLicence = 'GPL';
    if ( !$packageVersion )
        $packageVersion = '1.0';

    $package->appendMaintainer( $userObject->attribute( 'name' ), $user->attribute( 'email' ), 'lead' );
    $package->appendDocument( 'README', false, false, false, true,
                              "$packageName README" .
                              "\n" .
                              "\n" .
                              "What is $packageName?\n" .
                              "--------" . str_repeat( '-', strlen( $packageName ) ) . "-\n" .
                              "$packageName is a ...\n" .
                              "\n" .
                              "Licence\n" .
                              "-------\n" .
                              "Insert licence here...\n" );
    $package->appendChange( $userObject->attribute( 'name' ), $user->attribute( 'email' ), 'Creation of package' );
    $package->setRelease( $packageVersion, '1', false, $packageLicence, 'alpha' );

// $package->appendFileList( array( array( 'role' => 'override',
//                                         'md5sum' => false,
//                                         'name' => 'forum.tpl' ) ),
//                           'template', false,
//                           array( 'design' => 'standard' ) );

// $package->appendInstall( 'part', 'Classes', false, true,
//                          array( 'content' => 'yup' ) );

    $package->store();
}

$script->setDebugMessage( "\n\n" . str_repeat( '#', 36 ) . $cli->style( 'emphasize' ) . " DEBUG " . $cli->style( 'emphasize-end' )  . str_repeat( '#', 36 ) . "\n" );

$script->shutdown();

?>
