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


include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'lib/ezutils/classes/ezdebugsetting.php' );
include_once( "lib/ezutils/classes/ezextension.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );

error_reporting ( E_ALL );

eZDebug::setHandleType( EZ_HANDLE_TO_PHP );

$endl = "<br/>";
$webOutput = true;
if ( isset( $argv ) )
{
    $endl = "\n";
    $webOutput = false;
}

$cronPart = false;
$siteaccess = false;
$debugOutput = false;
$useColors = false;
$isQuiet = false;

$colors = array( 'warning' => "\033[1;35m",
                 'error' => "\033[1;31m",
                 'success' => "\033[1;32m",
                 'emphasize' => "\033[1;38m",
                 'normal' => "\033[0;39m" );

$optionsWithData = array( 's' );
$longOptionsWithData = array( 'siteaccess' );

function help()
{
    print( "Usage: " . $argv[0] . " [OPTION]... [cronpart]\n" .
           "Executes eZ publish cronjobs.\n\n" .
           "  -h,--help          display this help and exit \n" .
           "  -q,--quiet         do not give any output except errors occur\n" .
           "  -s,--siteaccess    selected siteaccess for operations, if not specified default siteaccess is used\n" .
           "  -d,--debug         display debug output at end of execution\n" .
           "  -c,--colors        display output using ANSI colors\n" .
           "  --no-colors        do not use ANSI coloring (default)\n" );
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

for ( $i = 1; $i < count( $argv ); ++$i )
{
    $arg = $argv[$i];
    if ( strlen( $arg ) > 0 and
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
        }
    }
    else
    {
        if ( $cronPart === false )
            $cronPart = $arg;
    }
}

if ( $webOutput )
    $useColors = false;

if ( $useColors )
{
    $emphasizeText = $colors['emphasize'];
    $normalText = $colors['normal'];
    $errorText = $colors['error'];
    $warningText = $colors['warning'];
    $successText = $colors['success'];
}
else
{
    $emphasizeText = '';
    $normalText = '';
    $errorText = '';
    $warningText = '';
    $successText = '';
}

if ( $cronPart )
{
    if ( !$isQuiet )
        print( "Running cronjob part '$cronPart'$endl" );
}

/*!
 Reads settings from site.ini and passes them to eZDebug.
*/
function eZUpdateDebugSettings()
{
    global $debugOutput;
    $ini =& eZINI::instance();
    $debugSettings = array();
    $debugSettings['debug-enabled'] = $ini->variable( 'DebugSettings', 'DebugOutput' ) == 'enabled';
    $debugSettings['debug-by-ip'] = $ini->variable( 'DebugSettings', 'DebugByIP' ) == 'enabled';
    $debugSettings['debug-ip-list'] = $ini->variable( 'DebugSettings', 'DebugIPList' );
    $debugSettings['debug-enabled'] = $debugOutput;
    eZDebug::updateSettings( $debugSettings );
}

/*!
 Reads settings from i18n.ini and passes them to eZTextCodec.
*/
function eZUpdateTextCodecSettings()
{
    $ini =& eZINI::instance( 'i18n.ini' );
    $i18nSettings = array();
    $i18nSettings['internal-charset'] = $ini->variable( 'CharacterSettings', 'Charset' );
    $i18nSettings['http-charset'] = $ini->variable( 'CharacterSettings', 'HTTPCharset' );
    $i18nSettings['mbstring-extension'] = $ini->variable( 'CharacterSettings', 'MBStringExtension' ) == 'enabled';
    include_once( 'lib/ezi18n/classes/eztextcodec.php' );
    eZTextCodec::updateSettings( $i18nSettings );
}

// Initialize text codec settings
eZUpdateTextCodecSettings();

// Initialize debug settings
eZUpdateDebugSettings();

include_once( 'lib/ezutils/classes/ezexecution.php' );

function eZDBCleanup()
{
    if ( class_exists( 'ezdb' )
         and eZDB::hasInstance() )
    {
        $db =& eZDB::instance();
        $db->setIsSQLOutputEnabled( false );
    }
//     session_write_close();
}

function eZFatalError()
{
    global $webOutput;
    global $endl;
    global $errorText;
    global $normalText;
    $bold = '<b>';
    $unbold = '</b>';
    $par = '<p>';
    $unpar = '</p>';
    if ( !$webOutput )
    {
        $bold = $errorText;
        $unbold = $normalText;
        $par = '';
        $unpar = $endl;
    }

    eZDebug::setHandleType( EZ_HANDLE_NONE );
    print( $bold . "Fatal error" . $unbold . ": eZ publish did not finish it's request$endl" );
    print( $par . "The execution of eZ publish was abruptly ended, the debug output is present below." . $unpar );
    print( eZDebug::printReport( false, $webOutput, true ) );
}

eZExecution::addCleanupHandler( 'eZDBCleanup' );
eZExecution::addFatalErrorHandler( 'eZFatalError' );

eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );

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

$ini =& eZINI::instance( 'cronjob.ini' );
$scriptDirectories = $ini->variable( 'CronjobSettings', 'ScriptDirectories' );
$scriptGroup = 'CronjobSettings';
if ( $cronPart !== false )
    $scriptGroup = "CronjobPart-$cronPart";
$scripts = $ini->variable( $scriptGroup, 'Scripts' );

foreach ( $scripts as $script )
{
    foreach ( $scriptDirectories as $scriptDirectory )
    {
        $scriptFile = $scriptDirectory . '/' . $script;
        if ( file_exists( $scriptFile ) )
            break;
    }
    if ( file_exists( $scriptFile ) )
    {
        if ( !$isQuiet )
            print( "Running $emphasizeText$scriptFile$normalText$endl" );
        eZDebug::addTimingPoint( "Script $scriptFile starting" );
        include( $scriptFile );
        eZDebug::addTimingPoint( "Script $scriptFile done" );
    }
}

if ( $debugOutput or
     eZDebug::isDebugEnabled() )
    print( eZDebug::printReport( false, $webOutput, true ) );

eZExecution::cleanup();
eZExecution::setCleanExit();

?>
