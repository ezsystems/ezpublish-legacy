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

$cli =& eZCLI::instance();

$cli->startup();

$endl = $cli->endlineString();
$webOutput = $cli->isWebOutput();

$siteaccess = false;
$debugOutput = false;
$useColors = false;
$isQuiet = false;
$useLogFiles = true;
$userLogin = false;
$userPassword = false;
$command = false;

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
           "  --no-logfiles      do not create log files\n" .
           "  --no-colors        do not use ANSI coloring (default)\n" );
}

function helpExport()
{
    print( "export: Export a part of the eZ publish installation into a package.\n" .
           "usage: export TYPE [PARAMETERS]... [TYPE [PARAMETERS]...]...\n" .
           "\n" .
           "Options:\n" .
           "  -o,--output FILE   export to file "
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

function helpHelp()
{
    $argv = $_SERVER['argv'];
    print( "help: Displays help information on commands.\n" .
           "usage: help COMMAND\n" .
           "\n" .
           "Type \"" . $argv[0] . " help COMMAND\" for help on a specific command.\n" .
           "\n" .
           "Available commands:\n" .
           "    help\n" .
           "    import\n" .
           "    export\n"
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
            $command = $arg;
            if ( !in_array( $command,
                           array( 'help', 'import', 'export' ) ) )
            {
                help();
                exit();
            }
        }
        else
        {
            if ( $command == 'help' )
            {
                $helpTopic = $arg;
                if ( $helpTopic == 'import' )
                    helpImport();
                else if ( $helpTopic == 'export' )
                    helpExport();
                else
                    helpHelp();
                exit();
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
else if ( $command == 'help' )
{
    helpHelp();
    exit();
}

if ( $webOutput )
    $useColors = false;

$cli->setUseStyles( $useColors );

$cli->initialize();

// Check for extension
include_once( 'lib/ezutils/classes/ezextension.php' );
include_once( 'kernel/common/ezincludefunctions.php' );
eZExtension::activateExtensions();
// Extension check end

include_once( "access.php" );

if ( $siteaccess )
{
    $access = array( 'name' => $siteaccess,
                     'type' => EZ_ACCESS_TYPE_STATIC );
}
else
{
    $ini =& eZINI::instance();
    $siteaccess = $ini->variable( 'SiteSettings', 'DefaultAccess' );
    $access = array( 'name' => $siteaccess,
                     'type' => EZ_ACCESS_TYPE_DEFAULT );
}

$access = changeAccess( $access );
$GLOBALS['eZCurrentAccess'] =& $access;

// include ezsession override implementation
include_once( "lib/ezutils/classes/ezsession.php" );
include_once( 'lib/ezdb/classes/ezdb.php' );
$db =& eZDB::instance();
if ( $db->isConnected() )
{
    eZSessionStart();
}

include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

if ( $userLogin and $userPassword )
{
    $userID = eZUser::loginUser( $userLogin, $userPassword );
    if ( !$userID )
    {
        $cli->warning( 'Failed to login with user ' . $userLogin );
        eZExecution::cleanup();
        eZExecution::setCleanExit();
    }
}

// Initialize module loading
$moduleRepositories = array();
$moduleINI =& eZINI::instance( 'module.ini' );
$globalModuleRepositories = $moduleINI->variable( 'ModuleSettings', 'ModuleRepositories' );
$extensionRepositories = $moduleINI->variable( 'ModuleSettings', 'ExtensionRepositories' );
$extensionDirectory = eZExtension::baseDirectory();
$globalExtensionRepositories = array();
foreach ( $extensionRepositories as $extensionRepository )
{
    $modulePath = $extensionDirectory . '/' . $extensionRepository . '/modules';
    if ( file_exists( $modulePath ) )
    {
        $globalExtensionRepositories[] = $modulePath;
    }
}
$moduleRepositories = array_merge( $moduleRepositories, $globalModuleRepositories, $globalExtensionRepositories );
eZModule::setGlobalPathList( $moduleRepositories );

include_once( 'kernel/classes/ezpackagehandler.php' );

if ( $command == 'import' )
{
    $package =& eZPackageHandler::fetchFromFile( $packageFile );
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

    $package =& eZPackageHandler::create( $packageName, array( 'summary' => $packageSummary,
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

if ( $db->isConnected() )
{
    eZSessionRemove();
}

if ( $debugOutput or
     eZDebug::isDebugEnabled() )
    print( "\n\n" . str_repeat( '#', 36 ) . $cli->style( 'emphasize' ) . " DEBUG " . $cli->style( 'emphasize-end' )  . str_repeat( '#', 36 ) . "\n" . eZDebug::printReport( false, $webOutput, true ) );

eZExecution::cleanup();
eZExecution::setCleanExit();

?>
