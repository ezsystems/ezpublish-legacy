#!/usr/bin/env php
<?php
//
// Created on: <18-Mar-2003 17:06:45 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

//include_once( "lib/ezutils/classes/ezextension.php" );
//include_once( "lib/ezutils/classes/ezmodule.php" );
//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );
require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'debug-message' => '',
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();
require_once( 'kernel/common/i18n.php' );

$endl = $cli->endlineString();
$webOutput = $cli->isWebOutput();

function help()
{
    $argv = $_SERVER['argv'];
    $cli = eZCLI::instance();
    $cli->output( "Usage: " . $argv[0] . " [OPTION]... COMMAND [COMMAND OPTION]... [-- COMMAND [COMMAND OPTION]...]...\n" .
                  "eZ Publish package manager.\n" .
                  "\n" .
                  "Type " . $argv[0] . " help for command overview\n" .
                  "\n" .
                  "General options:\n" .
                  "  -h,--help            display this help and exit \n" .
                  "  -q,--quiet           do not give any output except when errors occur\n" .
                  "  -s,--siteaccess      selected siteaccess for operations, if not specified default siteaccess is used\n" .
                  "  -d,--debug           display debug output at end of execution\n" .
                  "  -c,--colors          display output using ANSI colors (default)\n" .
                  "  -l,--login USER      login with USER and use it for all operations\n" .
                  "  -p,--password PWD    use PWD as password for USER\n" .
                  "  -r,--repos REPOS     use REPOS for repository when accessing packages\n" .
                  "  --db-type TYPE       set type of db to use\n" .
                  "  --db-name NAME       set name of db to use\n" .
                  "  --db-user USER       set database user\n" .
                  "  --db-password PASSWD set password for database user\n" .
                  "  --db-socket SOCKET   set socket for db connection\n" .
                  "  --db-host HOST       set host name for db connection\n" .
                  "  --logfiles           create log files\n" .
                  "  --no-logfiles        do not create log files (default)\n" .
                  "  --no-colors          do not use ANSI coloring\n" );
}

function helpCreate()
{
    $cli = eZCLI::instance();
    $cli->output( "create: Create a new package.\n" .
                  "usage: create NAME [SUMMARY [VERSION [INSTALLTYPE]]] [PARAMETERS]\n" .
                  "\n" .
                  "SUMMARY:     A short summary of your package\n" .
                  "VERSION:     The version of your package, default is 1.0\n" .
                  "INSTALLTYPE: Use install (default) for a package that installs files or\n" .
                  "             import for a package that can only be imported.\n" .
                  "Parameters:\n"
                  );
}

function helpExport()
{
    $cli = eZCLI::instance();
    $cli->output( "export: Export a part of the eZ Publish installation into a package.\n" .
                  "usage: export TYPE [PARAMETERS]... [TYPE [PARAMETERS]...]...\n" .
                  "\n" .
                  "Options:\n" .
                  "  -o,--output FILE   export to file\n"
                  );
}

function helpInstall()
{
    $cli = eZCLI::instance();
    $cli->output( "import: Install an eZ Publish package.\n" .
                  "usage: install PACKAGE\n" .
                  "\n" .
                  "PACKAGE can be specified with just the name of the of package or\n" .
                  "the filename of the package. If just the name is used the package\n" .
                  "will be looked for by appending .ezpkg\n"
                  );
}

function helpImport()
{
    $cli = eZCLI::instance();
    $cli->output( "import: Import an eZ Publish package.\n" .
                  "usage: import PACKAGE [ARCHIVENAME]\n" .
                  "\n" .
                  "PACKAGE can be specified with just the name of the of package or\n" .
                  "the filename of the package. If just the name is used the package\n" .
                  "will be looked for by appending .ezpkg\n"
                  );
}

function helpList()
{
    $cli = eZCLI::instance();
    $cli->output( "list (ls): Lists all the packages\n" .
                  "If repository ID is given (-r option) it will show packages\n" .
                  "only from the given repository" .
                  "usage: list\n"
                  );
}

function helpInfo()
{
    $cli = eZCLI::instance();
    $cli->output( "info: Displays information on a given package.\n" .
                  "usage: info PACKAGE\n"
                  );
}

function helpAdd()
{
    $cli = eZCLI::instance();
    $cli->output( "add: Adds an eZ Publish item to the package.\n" .
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
    $cli = eZCLI::instance();
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
    $cli = eZCLI::instance();
    $cli->output( "delete (del, remove, rm): Removes an eZ Publish item from the package.\n" .
                  "usage: delete PACKAGE ITEM [ITEMPARAMETERS]...\n" .
                  "\n" .
                  "Note: Will open up a new release if no open releases exists yet.\n"
                  );
}

function helpHelp()
{
    $argv = $_SERVER['argv'];
    $cli = eZCLI::instance();
    $cli->output( "help: Displays help information on commands.\n" .
                  "usage: help COMMAND\n" .
                  "\n" .
                  "Type \"" . $argv[0] . " help COMMAND\" for help on a specific command.\n" .
                  "\n" .
                  "Available commands:\n" .
                  "   help (?, h)\n" .
                  "   create\n" .
                  "   install\n" .
                  "   import\n" .
                  "   export\n" .
                  "   add\n" .
                  "   set\n" .
//                  "   delete (del, remove, rm)\n" .
                  "   list\n" .
                  "   info\n"
                  );
}

function changeSiteAccessSetting( $siteAccess )
{
    global $isQuiet;
    $cli = eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $siteAccess) )
    {
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteAccess for nice url update" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $siteAccess does not exist, using default siteaccess" );
    }
}

$siteaccess = false;
$debugOutput = false;
$allowedDebugLevels = false;
$useDebugAccumulators = false;
$useDebugTimingpoints = false;
$useIncludeFiles = false;
$useColors = true;
$isQuiet = false;
$useLogFiles = false;
$userLogin = false;
$userPassword = false;
$command = false;
$repositoryID = false;

$dbUser = false;
$dbPassword = false;
$dbSocket = false;
$dbHost = false;
$dbType = false;
$dbName = false;

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

function resetCommandItem()
{
    $commandItem = array( 'command' => false,
                          'name' => false,
                          'attribute' => false,
                          'attribute-value' => false,
                          'item' => false,
                          'item-parameters' => array(),
                          'summary' => false,
                          'installtype' => false,
                          'version' => false,
                          'file' => false );
    return $commandItem;
}

$commandItem = resetCommandItem();

$optionsWithData = array( 's', 'o', 'l', 'p', 'r' );
$longOptionsWithData = array( 'siteaccess', 'login', 'password', 'repos',
                              'db-type', 'db-name', 'db-user', 'db-password', 'db-socket', 'db-host' );

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
        $commandList[]= $commandItem;
        $commandItem = resetCommandItem();
    }
    else if ( $readOptions and
         strlen( $arg ) > 0 and
         $arg[0] == '-' )
    {
        if ( strlen( $arg ) > 1 and
             $arg[1] == '-' )
        {
            $flag = substr( $arg, 2 );
            if ( preg_match( '#^([^=]+)=(.+)$#', $flag, $matches ) )
            {
                $flag = $matches[1];
                $optionData = $matches[2];
            }
            else if ( in_array( $flag, $longOptionsWithData ) )
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
                changeSiteAccessSetting( $optionData );
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
            else if ( $flag == 'repos' )
            {
                $repositoryID = $optionData;
            }
            else if ( $flag == 'db-user' )
            {
                $dbUser = $optionData;
            }
            else if ( $flag == 'db-password' )
            {
                $dbPassword = $optionData;
            }
            else if ( $flag == 'db-socket' )
            {
                $dbSocket = $optionData;
            }
            else if ( $flag == 'db-host' )
            {
                $dbHost = $optionData;
            }
            else if ( $flag == 'db-type' )
            {
                $dbType = $optionData;
            }
            else if ( $flag == 'db-name' )
            {
                $dbName = $optionData;
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
                            $useDebugTimingpoints = true;
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
                        if ( $level == 'include' )
                        {
                            $useIncludeFiles = true;
                        }
                        if ( $level == 'error' )
                            $level = eZDebug::LEVEL_ERROR;
                        else if ( $level == 'warning' )
                            $level = eZDebug::LEVEL_WARNING;
                        else if ( $level == 'debug' )
                            $level = eZDebug::LEVEL_DEBUG;
                        else if ( $level == 'notice' )
                            $level = eZDebug::LEVEL_NOTICE;
                        else if ( $level == 'timing' )
                            $level = eZDebug::EZ_LEVEL_TIMING;
                        $allowedDebugLevels[] = $level;
                    }
                }
            }
            else if ( $flag == 's' )
            {
                changeSiteAccessSetting( $optionData );
            }
            else if ( $flag == 'l' )
            {
                $userLogin = $optionData;
            }
            else if ( $flag == 'p' )
            {
                $userPassword = $optionData;
            }
            else if ( $flag == 'r' )
            {
                $repositoryID = $optionData;
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
                                  'create', 'import', 'install', 'export',
                                  'add', 'set', 'delete',
                                  'list', 'info' ) ) )
            {
                help();
                exit( 1 );
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
                else if ( $helpTopic == 'install' )
                    helpInstall();
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
                else if ( $commandItem['installtype'] === false )
                    $commandItem['installtype'] = strtolower( $arg );
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
                else if ( !isset( $commandItem['archive'] ) )
                    $commandItem['archive'] = $arg;
            }
            else if ( $commandItem['command'] == 'install' )
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
$script->setIsQuiet( $isQuiet );
$script->setUseDebugOutput( $debugOutput );
$script->setAllowedDebugLevels( $allowedDebugLevels );
$script->setUseDebugAccumulators( $useDebugAccumulators );
$script->setUseDebugTimingPoints( $useDebugTimingpoints );
$script->setUseIncludeFiles( $useIncludeFiles );


$commandList[] = $commandItem;

// Check all commands
foreach ( $commandList as $commandItem )
{
    if ( $commandItem['command'] == 'add' )
    {
        if ( !$commandItem['name'] and
             !$commandItem['item'] )
        {
            helpAdd();
            exit( 1 );
        }
    }
    else if ( $commandItem['command'] == 'set' )
    {
        if ( !$commandItem['name'] and
             !$commandItem['attribute'] and
             !$commandItem['attribute-value'] )
        {
            helpSet();
            exit( 1 );
        }
    }
    else if ( $commandItem['command'] == 'create' )
    {
        if ( !$commandItem['name'] )
        {
            helpCreate();
            exit( 1 );
        }
    }
    else if ( $commandItem['command'] == 'info' )
    {
        if ( !$commandItem['name'] )
        {
            helpInfo();
            exit( 1 );
        }
    }
    else if ( $commandItem['command'] == 'export' )
    {
        if ( !$commandItem['name'] )
        {
            helpExport();
            exit( 1 );
        }
    }
    else if ( $commandItem['command'] == 'import' )
    {
        if ( !$commandItem['name'] )
        {
            helpImport();
            exit( 1 );
        }
    }
    else if ( $commandItem['command'] == 'install' )
    {
        if ( !$commandItem['name'] )
        {
            helpInstall();
            exit( 1 );
        }
    }
    else if ( in_array( $commandItem['command'],
                        array( 'list' ) ) )
    {
    }
    else if ( $commandItem['command'] == 'help' )
    {
        helpHelp();
        exit( 1 );
    }
    else
    {
        help();
        exit( 1 );
    }
}

if ( $webOutput )
    $useColors = true;

$cli->setUseStyles( $useColors );
$script->setDebugMessage( "\n\n" . str_repeat( '#', 36 ) . $cli->style( 'emphasize' ) . " DEBUG " . $cli->style( 'emphasize-end' )  . str_repeat( '#', 36 ) . "\n" );

$script->setUseSiteAccess( $siteaccess );

// Check the database settings and initialize them as current settings
if ( $dbUser !== false or $dbHost !== false or $dbSocket !== false or
     $dbType !== false or $dbName !== false )
{
    if ( $dbUser === false )
    {
        $cli->error( "No --db-user specified, cannot connect without a user." );
        $script->shutdown( 1 );
    }

    if ( $dbType === false )
    {
        $cli->error( "No --db-type specified, cannot connect without a specific type." );
        $script->shutdown( 1 );
    }

    $params = array( 'use_defaults' => false,
                     'server' => $dbHost,
                     'user' => $dbUser,
                     'socket' => $dbSocket,
                     'password' => $dbPassword,
                     'database' => $dbName );
    $db = eZDB::instance( $dbType,
                           $params,
                           true );

    if ( !$db->isConnected() )
    {
        $str = "Failed to connnect to database: $dbType://$dbUser@$dbHost";
        $cli->error( $str );
        $script->shutdown( 1 );
    }
    eZDB::setInstance( $db );

    // Only continue if the database is using the same version as the PHP code
    $rows = $db->arrayQuery( "SELECT * FROM ezsite_data WHERE name = 'ezpublish-version'" );
    if ( count( $rows ) > 0 )
    {
        $version = $rows[0]['value'];
        //include_once( 'lib/version.php' );
        if ( version_compare( $version, eZPublishSDK::version() ) != 0 )
        {
            $cli->error( "Version '$version' in database '$dbName' is different from the running version " . eZPublishSDK::version() );
            $script->shutdown( 1 );
        }
    }
}

$script->setUser( $userLogin, $userPassword );

$script->initialize();

//include_once( 'kernel/classes/ezpackage.php' );

$alreadyCreated = false;

$createdPackages = array();

foreach ( $commandList as $commandItem )
{
    $command = $commandItem['command'];

    if ( $command == 'list' )
    {
        $fetchParameters = array();
        if ( $repositoryID )
        {
            $fetchParameters['repository_id'] = $repositoryID;
            $cli->output( "The list of packages in the repository " . $cli->stylize( 'dir', $fetchParameters['repository_id'] ) . ":" );
        }
        else
             $cli->output( "The list of all packages:" );

        $packages = eZPackage::fetchPackages( $fetchParameters );
        if ( count( $packages ) > 0 )
        {
            foreach ( $packages as $package )
            {
                $packageRepInfo = $package->currentRepositoryInformation();
                $cli->output( '[' . $packageRepInfo['id'] . '] ' . $package->attribute( 'name' ) . '  ver.' . $package->attribute( 'version-number' ) . '-' . $package->attribute( 'release-number' ) . ' (' . $cli->stylize( 'emphasize', $package->attribute( 'summary' ) ) . ')' );
            }
        }
        else
            $cli->output( "No packages are available" );
    }
    else if ( $command == 'info' )
    {
        if ( isset( $createdPackages[$commandItem['name']] ) )
            $package =& $createdPackages[$commandItem['name']];
        else
            $package = eZPackage::fetch( $commandItem['name'] );
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
                $cli->output( "eZ Publish  : " . $package->attribute( 'ezpublish-named-version' ) .
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
        }
        else
            $cli->output( "package " . $commandItem['name'] . " is not in the repository" );
    }
    else if ( $command == 'add' )
    {
        if ( isset( $createdPackages[$commandItem['name']] ) )
            $package =& $createdPackages[$commandItem['name']];
        else
            $package = eZPackage::fetch( $commandItem['name'] );
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
                            if ( !$isQuiet )
                                $cli->notice( "Added to group $group" );
                        }
                        $package->store();
                    }
                    else
                    {
                        $cli->error( "No groups supplied" );
                    }
                } break;
                default:
                {
                    $handler = $package->packageHandler( $itemType );
                    if ( is_object( $handler ) )
                    {
                        $realItemType = $handler->handlerType();
                        $parameters = $handler->handleAddParameters( $itemType, $package, $cli, $commandItem['item-parameters'] );
                        if ( $parameters )
                        {
                            $handler->add( $itemType, $package, $cli, $parameters );
                            $package->store();
                        }
                        else
                        {
                            $cli->error( "Failed adding items to package" );
                            $script->setExitCode( 1 );
                            break 2;
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
                                    'state' );
        if ( !in_array( $commandItem['attribute'], $packageAttributes ) )
        {
            helpSet();
            $script->setExitCode( 1 );
        }
        else
        {
            if ( isset( $createdPackages[$commandItem['name']] ) )
                $package =& $createdPackages[$commandItem['name']];
            else
                $package = eZPackage::fetch( $commandItem['name'] );
            if ( $package )
            {
                switch ( $commandItem['attribute'] )
                {
                    case 'summary':
                    case 'description':
                    case 'vendor':
                    case 'extension':
                    case 'source':
                    case 'type':
                    case 'priority':
                    case 'state':
                    {
                        $package->setAttribute( $commandItem['attribute'], $commandItem['attribute-value'] );
                        if ( !$isQuiet )
                            $cli->notice( "Attribute " . $cli->style( 'symbol' ) . $commandItem['attribute'] . $cli->style( 'emphasize-end' ) .
                                          " was set to " . $cli->style( 'symbol' ) . $commandItem['attribute-value'] . $cli->style( 'emphasize-end' ) );
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
        if ( isset( $commandItem['archive'] ) )
            $archiveNames[] = $commandItem['archive'];
        $archiveNames[] = $commandItem['name'];
        $archiveNames[] = $commandItem['name'] . '.' . eZPackage::suffix();

        $archiveName = '';
        foreach( $archiveNames as $name )
        {
            if ( file_exists( $name ) )
            {
                $archiveName = $name;
                break;
            }
        }

        if ( $archiveName )
        {
            $package = eZPackage::import( $archiveName, $commandItem['name'], true, $repositoryID );

            if ( $package == eZPackage::STATUS_ALREADY_EXISTS )
            {
                $cli->notice( "Package " . $cli->stylize( 'emphasize', $archiveName ) . " is already imported " );
                $package = false;
            }

            if ( $package )
            {
                $cli->notice( "Package " . $cli->stylize( 'emphasize', $package->attribute( 'name' ) ) . " sucessfully imported" );
            }
        }
        else
            $cli->error( "Could not import package " . $commandItem['name'] . ", none of these files were found: " . implode( ', ', $archiveNames ) );
    }
    else if ($command == 'install' )
    {
        $package = eZPackage::fetch( $commandItem['name'] );
        if ( $package )
        {
            $user = eZUser::currentUser();
            $installParameters = array( 'site_access_map' => array( '*' => $siteaccess ),
                                        'top_nodes_map' => array( '*' => 2 ),
                                        'design_map' => array( '*' => $siteaccess ),
                                        'restore_dates' => true,
                                        'user_id' => $user->attribute( 'contentobject_id' ),
                                        'non-interactive' => true,
                                        'language_map' => $package->defaultLanguageMap() );
            $result = $package->install( $installParameters );
            if ( $result )
                $cli->notice( "Package " . $cli->stylize( 'emphasize', $package->attribute( 'name' ) ) . " sucessfully installed" );
            else
                $cli->error( "Failed to install package " . $cli->stylize( 'emphasize', $package->attribute( 'name' ) ) );
        }
        else
            $cli->error( "Could not open package " . $commandItem['name'] );
    }
    else if ( $command == 'export' )
    {
        if ( isset( $createdPackages[$commandItem['name']] ) )
            $package =& $createdPackages[$commandItem['name']];
        else
            $package = eZPackage::fetch( $commandItem['name'] );
        if ( $package )
        {
            if ( isset( $commandItem['export-directory'] ) )
            {
                $exportDirectory = $commandItem['export-directory'];
                if ( !file_exists( $exportDirectory ) )
                {
                    if ( !$isQuiet )
                        $cli->notice( "The directory " . $cli->style( 'dir' ) . $exportDirectory . $cli->style( 'dir-end' ) . " does not exist, cannot export package" );
                }
                else
                {
                    //include_once( 'lib/ezutils/classes/ezsys.php' );
                    $package->exportToArchive( $exportDirectory . eZSys::fileSeparator() . $package->exportName() );
                    if ( !$isQuiet )
                        $cli->notice( "Package " . $cli->stylize( 'symbol', $package->attribute( 'name' ) ) . " exported to directory " . $cli->stylize( 'dir', $exportDirectory ) );
                }
            }
            else
            {
                $exportPath = $package->exportToArchive( $package->exportName() );
                if ( !$isQuiet )
                    $cli->notice( "Package " . $cli->stylize( 'symbol', $package->attribute( 'name' ) ) . " exported to file " . $cli->stylize( 'file', $exportPath ) );
            }
        }
        else
            $cli->error( "Could not locate package " . $cli->stylize( 'emphasize', $commandItem['name'] ) );
    }
    else if ( $command == 'create' )
    {
        if ( $alreadyCreated )
            $cli->output();
        $package = eZPackage::create( $commandItem['name'],
                                      array( 'summary' => $commandItem['summary'] ),
                                      false, $repositoryID );

        require_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $user = eZUser::currentUser();
        $userObject = $user->attribute( 'contentobject' );

        $commandItem['licence'] = 'GPL';
        if ( !in_array( $commandItem['installtype'], array( 'install', 'import' ) ) )
            $commandItem['installtype'] = 'install';
        if ( !$commandItem['version'] )
            $commandItem['version'] = '1.0';

        $package->setRelease( $commandItem['version'], '1', false,
                              $commandItem['licence'], 'alpha' );
        $package->setAttribute( 'install_type', $commandItem['installtype'] );
        if ( $userObject )
            $package->appendMaintainer( $userObject->attribute( 'name' ), $user->attribute( 'email' ), 'lead' );
        //include_once( 'kernel/classes/ezpackagecreationhandler.php' );
        eZPackageCreationHandler::appendLicence( $package );
        if ( $userObject )
            $package->appendChange( $userObject->attribute( 'name' ), $user->attribute( 'email' ), 'Creation of package' );

        $package->store();
        $text = "Created package " . $cli->stylize( 'symbol', $commandItem['name'] ) . "-" . $cli->stylize( 'symbol', $commandItem['version'] );
        if ( $commandItem['summary'] )
            $text .= " " . $cli->stylize( 'archive', $commandItem['summary'] );
        $cli->output( $text );
        $alreadyCreated = true;
        $createdPackages[$commandItem['name']] =& $package;
    }
}

$cli->output();

$script->shutdown();

?>
