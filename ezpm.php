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
    $cli =& eZCLI::instance();
    $cli->output( "Usage: " . $argv[0] . " [OPTION]... COMMAND [-- COMMAND]...\n" .
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
    $cli =& eZCLI::instance();
    $cli->output( "create: Create a new package.\n" .
                  "usage: create NAME [SUMMARY [VERSION [LICENCE]]] [PARAMETERS]\n" .
                  "\n" .
                  "Parameters:\n"
                  );
}

function helpExport()
{
    $cli =& eZCLI::instance();
    $cli->output( "export: Export a part of the eZ publish installation into a package.\n" .
                  "usage: export TYPE [PARAMETERS]... [TYPE [PARAMETERS]...]...\n" .
                  "\n" .
                  "Options:\n" .
                  "  -o,--output FILE   export to file\n"
                  );
}

function helpImport()
{
    $cli =& eZCLI::instance();
    $cli->output( "import: Import an eZ publish package installation and install it.\n" .
                  "usage: import PACKAGE\n" .
                  "\n" .
                  "PACKAGE can be specified with just the name of the of package or\n" .
                  "the filename of the package. If just the name is used the package\n" .
                  "will be looked for by appending .ezpkg\n"
                  );
}

function helpList()
{
    $cli =& eZCLI::instance();
    $cli->output( "list (ls): Lists all packages in the repository.\n" .
                  "usage: list\n"
                  );
}

function helpInfo()
{
    $cli =& eZCLI::instance();
    $cli->output( "info: Displays information on a given package.\n" .
                  "usage: info PACKAGE\n"
                  );
}

function helpAdd()
{
    $cli =& eZCLI::instance();
    $cli->output( "add: Adds an eZ publish item to the package.\n" .
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
    $cli =& eZCLI::instance();
    $cli->output( "set: Sets an attribute in the package.\n" .
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
    $cli =& eZCLI::instance();
    $cli->output( "delete (del, remove, rm): Removes an eZ publish item from the package.\n" .
                  "usage: delete PACKAGE ITEM [ITEMPARAMETERS]...\n" .
                  "\n" .
                  "Note: Will open up a new release if no open releases exists yet.\n"
                  );
}

function helpHelp()
{
    $argv = $_SERVER['argv'];
    $cli =& eZCLI::instance();
    $cli->output( "help: Displays help information on commands.\n" .
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
    $cli =& eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        $cli->notice( "Using siteaccess $siteaccess for cronjob" );
    }
    else
    {
        $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

$siteaccess = false;
$debugOutput = false;
$allowedDebugLevels = false;
$useDebugAccumulators = false;
$useDebugTimingpoints = false;
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
                if ( strlen( $arg ) > 2 )
                {
                    $levels = explode( ',', substr( $arg, 2 ) );
                    $allowedDebugLevels = array();
                    foreach ( $levels as $level )
                    {
                        if ( $level == 'all' )
                        {
                            $useDebugAccumulators = true;
                            $allowedDebugLevels = false;
                            break;
                        }
                        if ( $level == 'accumulator' )
                        {
                            $useDebugAccumulators = true;
                            continue;
                        }
                        if ( $level == 'timing' )
                        {
                            $useDebugTimingpoints = true;
                            continue;
                        }
                        if ( $level == 'error' )
                            $level = EZ_LEVEL_ERROR;
                        else if ( $level == 'warning' )
                            $level = EZ_LEVEL_WARNING;
                        else if ( $level == 'debug' )
                            $level = EZ_LEVEL_DEBUG;
                        else if ( $level == 'notice' )
                            $level = EZ_LEVEL_NOTICE;
                        else if ( $level == 'timing' )
                            $level = EZ_LEVEL_TIMING;
                        $allowedDebugLevels[] = $level;
                    }
                }
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
            $readOptions = false;
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
                else if ( $arg[0] == '-' )
                {
                    $infoOptions = substr( $arg, 1 );
                    if ( preg_match( '#[dfi]#', $infoOptions ) )
                    {
                        if ( !isset( $commandItem['info-types'] ) )
                            $commandItem['info-types'] = array();
                        if ( strpos( $infoOptions, 'd' ) !== false )
                            $commandItem['info-types'][] = 'dependency';
                        if ( strpos( $infoOptions, 'f' ) !== false )
                            $commandItem['info-types'][] = 'file';
                        if ( strpos( $infoOptions, 'i' ) !== false )
                            $commandItem['info-types'][] = 'info';
                    }
                }
            }
            else if ( $commandItem['command'] == 'import' )
            {
                if ( $commandItem['name'] === false )
                    $commandItem['name'] = $arg;
            }
            else if ( $commandItem['command'] == 'export' )
            {
                if ( $commandItem['name'] === false )
                    $commandItem['name'] = $arg;
                else if ( $arg == '-d' )
                {
                    ++$i;
                    $commandItem['export-directory'] = $argv[$i];
                }
            }
        }
    }
}
$script->setUseDebugOutput( $debugOutput );
$script->setAllowedDebugLevels( $allowedDebugLevels );
$script->setUseDebugAccumulators( $useDebugAccumulators );
$script->setUseDebugTimingPoints( $useDebugTimingpoints );


appendCommandItem( $commandList, $commandItem );

// Check all commands
foreach ( $commandList as $commandItem )
{
    if ( $commandItem['command'] == 'add' )
    {
        if ( !$commandItem['name'] and
             !$commandItem['item'] )
        {
            helpAdd();
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
    else if ( $commandItem['command'] == 'export' )
    {
        if ( !$commandItem['name'] )
        {
            helpExport();
            exit();
        }
    }
    else if ( $commandItem['command'] == 'import' )
    {
        if ( !$commandItem['name'] )
        {
            helpImport();
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
            $cli->output( "The following packages are in the repository:" );
            foreach ( $packages as $package )
            {
                $cli->output( $package->attribute( 'name' ) . '-' . $package->attribute( 'version-number' ) . '-' . $package->attribute( 'release-number' ) . ' (' . $package->attribute( 'summary' ) . ')' );
            }
        }
        else
            $cli->output( "No packages are available in the repository" );
    }
    else if ( $command == 'info' )
    {
        $package =& eZPackage::fetch( $commandItem['name'] );
        if ( $package )
        {
            $showInfo = false;
            $showFiles = false;
            $showDependencies = false;
            if ( isset( $commandItem['info-types'] ) )
            {
                $showInfo = in_array( 'info', $commandItem['info-types'] );
                $showFiles = in_array( 'file', $commandItem['info-types'] );
                $showDependencies = in_array( 'dependency', $commandItem['info-types'] );
            }
            else
                $showInfo = true;
            if ( $showInfo )
            {
                $cli->output( "Name        : " . $package->attribute( 'name' ) . str_repeat( ' ', 30 - strlen( $package->attribute( 'name' ) ) ) . "Vendor  : " . $package->attribute( 'vendor' ) );
                $cli->output( "Version     : " . $package->attribute( 'version-number' ) . str_repeat( ' ', 30 - strlen( $package->attribute( 'version-number' ) ) ) . "Source  : " . $package->attribute( 'source' ) );
                $cli->output( "Release     : " . $package->attribute( 'release-number' ) . str_repeat( ' ', 30 - strlen( $package->attribute( 'release-number' ) ) ) . "Licence : " . $package->attribute( 'licence' ) );
                $cli->output( "Summary     : " . $package->attribute( 'summary' ) . str_repeat( ' ', 30 - strlen( $package->attribute( 'summary' ) ) ) . "State   : " . $package->attribute( 'state' ) );
                $cli->output( "eZ publish  : " . $package->attribute( 'ezpublish-named-version' ) .
                              " (" . $package->attribute( 'ezpublish-version' ) . ")" );
                $cli->output( "Description : " . $package->attribute( 'description' ) );
            }
            if ( $showDependencies )
            {
                $i = 0;
                foreach ( array( 'provides', 'requires', 'obsoletes', 'conflicts' ) as $dependencySection )
                {
                    $dependencyItems = $package->dependencyItems( $dependencySection, false, false, false );
                    if ( count( $dependencyItems ) == 0 )
                        continue;
                    if ( $i > 0 )
                        $cli->output();
                    $cli->output( $dependencySection . ':' );
                    $dependencyTypes = $package->groupDependencyItemsByType( $dependencyItems );
                    foreach ( $dependencyTypes as $dependencyTypeName => $dependencyItems )
                    {
                        foreach ( $dependencyItems as $dependencyItem )
                        {
                            $dependencyText = $package->createDependencyText( $cli, $dependencyItem, $dependencySection );
                            $cli->output( $dependencyText );
                        }
                    }
                    ++$i;
                }
            }
//             print_r( $package->Parameters );
        }
        else
            $cli->output( "package " . $commandItem['name'] . " is not in the repository" );
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
                        $realItemType = $handler->handlerType();
                        $parameters = $handler->handleAddParameters( $itemType, $package, $cli, $commandItem['item-parameters'] );
                        if ( $parameters )
                        {
                            $handler->add( $itemType, $package, $cli, $parameters );
                            $package->store();
                        }
                    }
                    else
                        $cli->error( "Unknown package item type $itemType" );
                } break;
            }
        }
        else
            $cli->output( "package " . $commandItem['name'] . " is not in the repository" );
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
                $cli->output( "package " . $commandItem['name'] . " is not in repository" );
        }
    }
    else if ( $command == 'import' )
    {
        $archiveNames = array();
        $archiveName = $commandItem['name'];
        $archiveNames[] = $archiveName;
        if ( !file_exists( $archiveName ) )
        {
            $archiveName .= '.' . eZPackage::suffix();
            $archiveNames[] = $archiveName;
        }
        if ( file_exists( $archiveName ) )
        {
            $package =& eZPackage::import( $archiveName, $commandItem['name'] );
            if ( $package )
            {
                $cli->notice( "Package " . $package->attribute( 'name' ) . " sucessfully imported" );
            }
            else
                $cli->error( "Failed importing package $archiveName" );
        }
        else
            $cli->error( "Could not open package " . $commandItem['name'] . ", none of these files were found: " . implode( ',', $archiveNames ) );
    }
    else if ( $command == 'export' )
    {
        $package =& eZPackage::fetch( $commandItem['name'] );
        if ( isset( $commandItem['export-directory'] ) )
        {
            $exportDirectory = $commandItem['export-directory'];
            if ( !file_exists( $exportDirectory ) )
            {
                $cli->notice( "The directory " . $cli->style( 'dir' ) . $exportDirectory . $cli->style( 'dir-end' ) . " does not exist, cannot export package" );
            }
            else
            {
                $package->export( $exportDirectory );
                $cli->notice( "Package " . $package->attribute( 'name' ) . " exported to directory " . $cli->stylize( 'dir', $exportDirectory ) );
            }
        }
        else
        {
            $exportPath = $package->archive( $package->exportName() );
            $cli->notice( "Package " . $package->attribute( 'name' ) . " exported to file " . $cli->stylize( 'file', $exportPath ) );
        }
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

        $package->store();
        $cli->output( "Created package " . $commandItem['name'] );
        $cli->output( "Use 'ezpm.php add' and 'ezpm.php set' to change and add settings to the package." );
    }
}

$script->shutdown();

?>
