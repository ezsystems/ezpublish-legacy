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

eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );

$endl = "<br/>";
if ( isset( $argv ) )
    $endl = "\n";

$cronPart = false;

for ( $i = 1; $i < count( $argv ); ++$i )
{
    $arg = $argv[$i];
    if ( strlen( $arg ) > 0 and
         $arg[0] == '-' )
    {
    }
    else
    {
        if ( $cronPart === false )
            $cronPart = $arg;
    }
}

if ( $cronPart )
    print( "Running cronjob part '$cronPart'$endl" );

// Initialize module loading
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
    eZDebug::setHandleType( EZ_HANDLE_NONE );
    print( "<b>Fatal error</b>: eZ publish did not finish it's request<br/>" );
    print( "<p>The execution of eZ publish was abruptly ended, the debug output is present below.</p>" );
    print( eZDebug::printReport( false, true, true ) );
}

eZExecution::addCleanupHandler( 'eZDBCleanup' );
eZExecution::addFatalErrorHandler( 'eZFatalError' );

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
        print( "Running $scriptFile$endl" );
        include( $scriptFile );
    }
}

eZExecution::cleanup();
eZExecution::setCleanExit();

?>
