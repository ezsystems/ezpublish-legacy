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

function help()
{
    $argv = $_SERVER['argv'];
    print( "Usage: " . $argv[0] . " [OPTION]... COMMAND [-- COMMAND]...\n" .
           "eZ publish package manager.\n" .
           "\n" .
           "Type " . $argv[0] . " help for command overview\n" .
           "\n" .
           "General options:\n" .
           "  -h,--help          display this help and exit \n" .
           "  -q,--quiet         do not give any output except when errors occur\n" .
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
           "usage: create NAME [SUMMARY [VERSION [LICENCE]]] [PARAMETERS]\n" .
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
    print( "add: Adds an eZ publish item to the package.\n" .
           "usage: add PACKAGE ITEM [ITEMPARAMETERS]...\n" .
           "\n" .
           "Items:\n" .
           "  group: Add categorization groups\n" .
           "  ezcontentclass: Add contentclass definitions\n" .
           "Note: Will open up a new release if no open releases exists yet.\n"
           );
}

function helpSet()
{
    print( "set: Sets an attribute in the package.\n" .
           "usage: set PACKAGE ATTRIBUTE ATTRIBUTEVALUE\n" .
           "\n" .
           "Attributes:\n" .
           "  summary     :\n" .
           "  description :\n" .
           "  vendor      :\n" .
           "  priority    :\n" .
           "  type        :\n" .
           "  extension   :\n" .
           "  source      :\n" .
           "  version     :\n" .
           "  licence     :\n" .
           "  state       :\n" .
           "Note: Will open up a new release if no open releases exists yet.\n"
           );
}

function helpDelete()
{
    print( "delete (del, remove, rm): Removes an eZ publish item from the package.\n" .
           "usage: delete PACKAGE ITEM [ITEMPARAMETERS]...\n" .
           "\n" .
           "Note: Will open up a new release if no open releases exists yet.\n"
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
           "   set\n" .
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

$siteaccess = false;
$debugOutput = false;
$useColors = false;
$isQuiet = false;
$useLogFiles = false;
$userLogin = false;
$userPassword = false;
$command = false;

// $packageName = false;
// $packageAttribute = false;
// $packageAttributeValue = false;
// $packagePart = false;
// $packagePartParameters = array();
// $packageSummary = false;
// $packageLicence = false;
// $packageVersion = false;
// $packageFile = false;

$commandList = array();
$commandItem = array();

function resetCommandItem( &$commandItem )
{
    $commandItem = array( 'command' => false,
                          'name' => false,
                          'attribute' => false,
                          'attribute-value' => false,
                          'item' => false,
                          'item-parameters' => array(),
                          'summary' => false,
                          'licence' => false,
                          'version' => false,
                          'file' => false );
}

function appendCommandItem( &$commandList, &$commandItem )
{
    $commandList[] = $commandItem;
}

resetCommandItem( $commandItem );

$optionsWithData = array( 's', 'o', 'l', 'p' );
$longOptionsWithData = array( 'siteaccess', 'login', 'password' );

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
    if ( $arg == '--' )
    {
        appendCommandItem( $commandList, $commandItem );
        resetCommandItem( $commandItem );
    }
    else if ( $readOptions and
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
        if ( $commandItem['command'] === false )
        {
            $realCommand = $arg;
            // Check for alias
            if ( isset( $commandMap[$realCommand] ) )
                $commandItem['command'] = $commandMap[$realCommand];
            else
                $commandItem['command'] = $realCommand;
            if ( !in_array( $commandItem['command'],
                           array( 'help',
                                  'create', 'import', 'export',
                                  'add', 'set', 'delete',
                                  'list', 'info' ) ) )
            {
                help();
                exit();
            }
        }
        else
        {
            if ( $commandItem['command'] == 'help' )
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
                else if ( $helpTopic == 'set' )
                    helpSet();
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
            else if ( $commandItem['command'] == 'create' )
            {
                if ( $commandItem['name'] === false )
                    $commandItem['name'] = $arg;
                else if ( $commandItem['summary'] === false )
                    $commandItem['summary'] = $arg;
                else if ( $commandItem['version'] === false )
                    $commandItem['version'] = $arg;
                else if ( $commandItem['licence'] === false )
                    $commandItem['licence'] = $arg;
            }
            else if ( $commandItem['command'] == 'set' )
            {
                if ( $commandItem['name'] === false )
                    $commandItem['name'] = $arg;
                else if ( $commandItem['attribute'] === false )
                    $commandItem['attribute'] = $arg;
                else if ( $commandItem['attribute-value'] === false )
                    $commandItem['attribute-value'] = $arg;
            }
            else if ( $commandItem['command'] == 'add' )
            {
                if ( $commandItem['name'] === false )
                    $commandItem['name'] = $arg;
                else if ( $commandItem['item'] === false )
                    $commandItem['item'] = $arg;
                else
                    $commandItem['item-parameters'][] = $arg;
            }
            else if ( $commandItem['command'] == 'info' )
            {
                if ( $commandItem['name'] === false )
                    $commandItem['name'] = $arg;
            }
            else if ( $commandItem['command'] == 'import' )
            {
                if ( $package['file'] === false )
                    $package['file'] = $arg;
            }
            else if ( $commandItem['command'] == 'export' )
            {
            }
        }
    }
}
$script->setUseDebugOutput( $debugOutput );

appendCommandItem( $commandList, $commandItem );

// Check all commands
foreach ( $commandList as $commandItem )
{
    if ( $commandItem['command'] == 'import' )
    {
        if ( !$commandItem['file'] )
        {
            helpImport();
            exit();
        }
    }
    else if ( $commandItem['command'] == 'add' )
    {
        if ( !$commandItem['name'] and
             !$commandItem['item'] )
        {
            helpSet();
            exit();
        }
    }
    else if ( $commandItem['command'] == 'set' )
    {
        if ( !$commandItem['name'] and
             !$commandItem['attribute'] and
             !$commandItem['attribute-value'] )
        {
            helpSet();
            exit();
        }
    }
    else if ( $commandItem['command'] == 'create' )
    {
        if ( !$commandItem['name'] )
        {
            helpCreate();
            exit();
        }
    }
    else if ( $commandItem['command'] == 'info' )
    {
        if ( !$commandItem['name'] )
        {
            helpInfo();
            exit();
        }
    }
    else if ( in_array( $commandItem['command'],
                        array( 'list' ) ) )
    {
    }
    else if ( $commandItem['command'] == 'help' )
    {
        helpHelp();
        exit();
    }
    else
    {
        help();
        exit();
    }
}

if ( $webOutput )
    $useColors = true;

$cli->setUseStyles( $useColors );
$script->setDebugMessage( "\n\n" . str_repeat( '#', 36 ) . $cli->style( 'emphasize' ) . " DEBUG " . $cli->style( 'emphasize-end' )  . str_repeat( '#', 36 ) . "\n" );

$script->setUseSiteAccess( $siteaccess );
$script->setUser( $userLogin, $userPassword );

$script->initialize();

include_once( 'kernel/classes/ezpackage.php' );

foreach ( $commandList as $commandItem )
{
    $command = $commandItem['command'];

    if ( $command == 'list' )
    {
        $packages = eZPackage::fetchPackages();
        if ( count( $packages ) > 0 )
        {
            $cli->output( "The following packages are installed:" );
            foreach ( $packages as $package )
            {
                $cli->output( $package->attribute( 'name' ) . '-' . $package->attribute( 'version-number' ) . '-' . $package->attribute( 'release-number' ) . ' (' . $package->attribute( 'summary' ) . ')' );
            }
        }
        else
            $cli->output( "No packages are installed" );
    }
    else if ( $command == 'info' )
    {
        $package =& eZPackage::fetch( $commandItem['name'] );
        if ( $package )
        {
            $cli->output( "Name        : " . $package->attribute( 'name' ) . str_repeat( ' ', 30 - strlen( $package->attribute( 'name' ) ) ) . "Vendor  : " . $package->attribute( 'vendor' ) );
            $cli->output( "Version     : " . $package->attribute( 'version-number' ) . str_repeat( ' ', 30 - strlen( $package->attribute( 'version-number' ) ) ) . "Source  : " . $package->attribute( 'source' ) );
            $cli->output( "Release     : " . $package->attribute( 'release-number' ) . str_repeat( ' ', 30 - strlen( $package->attribute( 'release-number' ) ) ) . "Licence : " . $package->attribute( 'licence' ) );
            $cli->output( "Summary     : " . $package->attribute( 'summary' ) . str_repeat( ' ', 30 - strlen( $package->attribute( 'summary' ) ) ) . "State   : " . $package->attribute( 'state' ) );
            $cli->output( "eZ publish  : " . $package->attribute( 'ezpublish-named-version' ) .
                          " (" . $package->attribute( 'ezpublish-version' ) . ")" );
            $cli->output( "Description : " . $package->attribute( 'description' ) );
        }
        else
            $cli->output( "package " . $commandItem['name'] . " is not installed" );
    }
    else if ( $command == 'add' )
    {
        $package =& eZPackage::fetch( $commandItem['name'] );
        if ( $package )
        {
            $itemType = $commandItem['item'];
            switch ( $itemType )
            {
                case 'group':
                {
                    $groups = $commandItem['item-parameters'];
                    if ( count( $groups ) > 0 )
                    {
                        foreach ( $groups as $group )
                        {
                            $package->appendGroup( $group );
                            $cli->notice( "Added to group $group" );
                        }
                        $package->store();
                    }
                    else
                        $cli->error( "No groups supplied" );
                } break;
                default:
                {
                    $handler =& $package->packageHandler( $itemType );
                    if ( is_object( $handler ) )
                    {
                        $parameters = $handler->handleAddParameters( $package, $cli, $commandItem['item-parameters'] );
                        if ( $parameters )
                        {
                            $handler->add( $package, $cli, $parameters );
                            $package->store();
                        }
                    }
                    else
                        $cli->error( "Unknown package item type $itemType" );
                } break;
            }
        }
        else
            $cli->output( "package " . $commandItem['name'] . " is not installed" );
    }
    else if ( $command == 'set' )
    {
        $packageAttributes = array( 'summary',
                                    'description',
                                    'vendor',
                                    'priority',
                                    'type',
                                    'extension',
                                    'source',
                                    'version',
//                                 'licence',
                                    'state' );
        if ( !in_array( $commandItem['attribute'], $packageAttributes ) )
        {
            helpSet();
        }
        else
        {
            $package =& eZPackage::fetch( $commandItem['name'] );
            if ( $package )
            {
                switch ( $commandItem['attribute'] )
                {
                    case 'summary':
                    case 'description':
                    case 'vendor':
                    case 'extension':
                    case 'source':
//                 case 'licence':
                    case 'state':
                    {
                        $package->setAttribute( $commandItem['attribute'], $commandItem['attribute-value'] );
                        $cli->notice( "Attribute " . $cli->style( 'emphasize' ) . $commandItem['attribute'] . $cli->style( 'emphasize-end' ) .
                                      " was set to " . $cli->style( 'emphasize' ) . $commandItem['attribute-value'] . $cli->style( 'emphasize-end' ) );
                    } break;
                }
                $package->store();
            }
            else
                $cli->output( "package " . $commandItem['name'] . " is not installed" );
        }
    }
    else if ( $command == 'import' )
    {
        $cli->notice( 'Disabled for now' );
//     $package =& eZPackage::fetchFromFile( $packageFile );
//     if ( $package )
//     {
// //         $package->install();
//     }
//     else
//     {
//         $cli->warning( "Could not open package file $packageFile" );
//     }
    }
    else if ( $command == 'export' )
    {
        $cli->notice( 'Disabled for now' );
//     $packageName = 'mytest';
//     $packageSummary = 'hm';
//     $packageExtension = 'myext';

//     $package =& eZPackage::create( $packageName, array( 'summary' => $packageSummary,
//                                                         'extension' => $packageExtension ) );

//     $user =& eZUser::currentUser();
//     $userObject = $user->attribute( 'contentobject' );

//     $package->appendMaintainer( $userObject->attribute( 'name' ), 'jb@ez.no', 'lead' );

//     $package->appendDocument( 'README' );
//     $package->appendDocument( 'readme.html', 'text/html', false, 'end-user' );
//     $package->appendDocument( 'INSTALL', false, 'unix', 'site-admin' );

//     $package->appendGroup( 'design' );
//     $package->appendGroup( 'community/forum' );

//     $package->appendChange( 'Jan Borsodi', 'jb@ez.no', 'Added some stuff' );

//     $package->setRelease( '1.0.5', '2', false, 'GPL', 'beta' );

// // $package->appendFileList( array( array( 'role' => 'override',
// //                                         'md5sum' => false,
// //                                         'name' => 'forum.tpl' ) ),
// //                           'template', false,
// //                           array( 'design' => 'standard' ) );

// // $package->appendInstall( 'part', 'Classes', false, true,
// //                          array( 'content' => 'yup' ) );

//     $exportList = array();
//     $exportList[] = array( 'type' => $exportType,
//                            'parameters' => $exportParameters );
//     $package->handleExportList( $exportList );

//     if ( $outputFile )
//     {
//         $package->storeToFile( $outputFile );
//     }
//     else
//     {
//         print( $package->toString() . "\n" );
//     }
    }
    else if ( $command == 'create' )
    {
        $package =& eZPackage::create( $commandItem['name'], array( 'summary' => $commandItem['summary'] ) );

        $user =& eZUser::currentUser();
        $userObject = $user->attribute( 'contentobject' );

        if ( !$commandItem['licence'] )
            $commandItem['licence'] = 'GPL';
        if ( !$commandItem['version'] )
            $commandItem['version'] = '1.0';

        $package->setRelease( $commandItem['version'], '1', false,
                              $commandItem['licence'], 'alpha' );
        $package->appendMaintainer( $userObject->attribute( 'name' ), $user->attribute( 'email' ), 'lead' );
        $package->appendDocument( 'README', false, false, false, true,
                                  $commandItem['name'] . " README" .
                                  "\n" .
                                  "\n" .
                                  "What is " . $commandItem['name'] . "?\n" .
                                  "--------" . str_repeat( '-', strlen( $commandItem['name'] ) ) . "-\n" .
                                  $commandItem['name'] . " is a ...\n" .
                                  "\n" .
                                  "Licence\n" .
                                  "-------\n" .
                                  "Insert licence here...\n" );
        $package->appendChange( $userObject->attribute( 'name' ), $user->attribute( 'email' ), 'Creation of package' );

// $package->appendFileList( array( array( 'role' => 'override',
//                                         'md5sum' => false,
//                                         'name' => 'forum.tpl' ) ),
//                           'template', false,
//                           array( 'design' => 'standard' ) );

// $package->appendInstall( 'part', 'Classes', false, true,
//                          array( 'content' => 'yup' ) );

        $package->store();
        $cli->output( "Created package " . $commandItem['name'] );
        $cli->output( "Use 'ezpm.php add' and 'ezpm.php set' to change and add settings to the package." );
    }
}

$script->shutdown();

?>
