<?php
//
// Definition of eZScript class
//
// Created on: <06-Aug-2003 11:06:35 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file ezscript.php
*/

/*!
  \class eZScript ezscript.php
  \brief Handles the basics of script execution

  By using this class for script execution startup, initializing
  and shutdown the amount code required to write a new script is
  reduced significantly.

  It is also recommended to use the eZCLI class in addition to this
  class.

  What this class will handle is:
  - Startup of database
  - Startup/shutdown of session
  - Debug initialize and display
  - Text codec initialize

  This class consists of the static functions startup(), initialize()
  and shutdown().

  A typical usage:
\code
$script =& eZScript::instance();

$script->startup();

// Read arguments and modify script accordingly

$script->initialize();

// Do the actual script here

$script->shutdown(); // Finish execution

\endcode

*/

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'access.php' );

class eZScript
{
    /*!
     Constructor
    */
    function eZScript( $settings = array() )
    {
        $settings = array_merge( array( 'debug-message' => false,
                                        'debug-output' => false,
                                        'debug-include' => false,
                                        'debug-levels' => false,
                                        'debug-accumulator' => false,
                                        'debug-timing' => false,
                                        'use-session' => false,
                                        'use-extensions' => false,
                                        'use-modules' => false,
                                        'user' => false,
                                        'site-access' => false ),
                                 $settings );
        $this->DebugMessage = $settings['debug-message'];
        $this->UseDebugOutput = $settings['debug-output'];
        $this->AllowedDebugLevels = $settings['debug-levels'];
        $this->UseDebugAccumulators = $settings['debug-accumulator'];
        $this->UseDebugTimingPoints = $settings['debug-timing'];
        $this->UseIncludeFiles = $settings['debug-include'];
        $this->UseSession = $settings['use-session'];
        $this->UseModules = $settings['use-modules'];
        $this->UseExtensions = $settings['use-extensions'];
        $this->User = $settings['user'];
        $this->SiteAccess = $settings['site-access'];
        $this->ExitCode = false;
        $this->IsQuiet = false;
    }

    /*!
     \static
    */
    function startup()
    {
        error_reporting ( E_ALL );

        eZDebug::setHandleType( EZ_HANDLE_TO_PHP );
    }

    function initialize()
    {
        while( @ob_end_clean() );
        include_once( "lib/ezutils/classes/ezdebugsetting.php" );

        $debugINI =& eZINI::instance( 'debug.ini' );
        eZDebugSetting::setDebugINI( $debugINI );

        // Initialize text codec settings
        eZUpdateTextCodecSettings();

        // Initialize debug settings
        eZUpdateDebugSettings();

        // Set the different permissions/settings.
        include_once( 'lib/ezi18n/classes/ezcodepage.php' );
        $ini =& eZINI::instance();
        $iniFilePermission = $ini->variable( 'FileSettings', 'StorageFilePermissions' );
        $iniDirPermission = $ini->variable( 'FileSettings', 'StorageDirPermissions' );
        $iniVarDirectory = eZSys::cacheDirectory() ;

        eZCodepage::setPermissionSetting( array( 'file_permission' => octdec( $iniFilePermission ),
                                                 'dir_permission'  => octdec( $iniDirPermission ),
                                                 'var_directory'   => $iniVarDirectory ) );

        include_once( 'lib/ezutils/classes/ezexecution.php' );

        eZExecution::addCleanupHandler( 'eZDBCleanup' );
        eZExecution::addFatalErrorHandler( 'eZFatalError' );

        eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );

        if ( $this->UseExtensions )
        {
            // Check for extension
            include_once( 'lib/ezutils/classes/ezextension.php' );
            include_once( 'kernel/common/ezincludefunctions.php' );
            eZExtension::activateExtensions();
            // Extension check end
        }

        include_once( "access.php" );
        $siteaccess = $this->SiteAccess;
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

        if ( $this->UseSession )
        {
            // include ezsession override implementation
            include_once( "lib/ezutils/classes/ezsession.php" );
            include_once( 'lib/ezdb/classes/ezdb.php' );
            $db =& eZDB::instance();
            if ( $db->isConnected() )
            {
                eZSessionStart();
            }
        }

        if ( $this->User )
        {
            $userLogin = $this->User['login'];
            $userPassword = $this->User['password'];
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

            if ( $userLogin and $userPassword )
            {
                $userID = eZUser::loginUser( $userLogin, $userPassword );
                if ( !$userID )
                {
                    $cli =& eZCLI::instance();
                    if ( $this->isLoud() )
                        $cli->warning( 'Failed to login with user ' . $userLogin );
                    include_once( 'lib/ezutils/classes/ezexecution.php' );
                    eZExecution::cleanup();
                    eZExecution::setCleanExit();
                }
            }
        }

        // Initialize module handling
        if ( $this->UseModules )
        {
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
            include_once( 'lib/ezutils/classes/ezmodule.php' );
            eZModule::setGlobalPathList( $moduleRepositories );
        }
    }

    function shutdown()
    {
        $db =& eZDB::instance();

        if ( $this->UseSession and
             $db->isConnected() )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            eZUser::logoutCurrent();
            eZSessionRemove();
        }

        $cli =& eZCLI::instance();
        $webOutput = $cli->isWebOutput();

        if ( $this->UseDebugOutput or
             eZDebug::isDebugEnabled() )
        {
            if ( $this->DebugMessage )
                print( $this->DebugMessage );
            print( eZDebug::printReport( false, $webOutput, true,
                                         $this->AllowedDebugLevels, $this->UseDebugAccumulators,
                                         $this->UseDebugTimingPoints, $this->UseIncludeFiles ) );
        }

        include_once( 'lib/ezutils/classes/ezexecution.php' );
        eZExecution::cleanup();
        eZExecution::setCleanExit();
        if ( $this->ExitCode !== false )
            exit( $this->ExitCode );
    }

    function setDebugMessage( $message )
    {
        $this->DebugMessage = $message;
    }

    function setUseDebugOutput( $useDebug )
    {
        $this->UseDebugOutput = $useDebug;
    }

    function setUseDebugAccumulators( $useAccumulators )
    {
        $this->UseDebugAccumulators = $useAccumulators;
    }

    function setUseDebugTimingPoints( $useTimingPoints )
    {
        $this->UseDebugTimingPoints = $useTimingPoints;
    }

    function setUseIncludeFiles( $useIncludeFiles )
    {
        $this->UseIncludeFiles = $useIncludeFiles;
    }

    function setAllowedDebugLevels( $allowedDebugLevels )
    {
        $this->AllowedDebugLevels = $allowedDebugLevels;
    }

    function setUseSession( $useSession )
    {
        $this->UseSession = $useSession;
    }

    function setUseExtensions( $useExtensions )
    {
        $this->UseExtensions = $useExtensions;
    }

    function setUseSiteAccess( $siteAccess )
    {
        $this->SiteAccess = $siteAccess;
    }

    function setUseModules( $useModules )
    {
        $this->UseModules = $useModules;
    }

    function setUser( $userLogin, $userPassword )
    {
        $this->User = array( 'login' => $userLogin,
                             'password' => $userPassword );
    }

    /*!
     Sets the current exit code which will be set with an exit() call in shutdown().
     If you don't want shutdown() to exit automatically set it to \c false.
    */
    function setExitCode( $code = false )
    {
        $this->ExitCode = $code;
    }

    function exitCode()
    {
        return $this->ExitCode;
    }

    /*!
     Sets whether any output should be used or not.
     \sa isQuiet, isLoud
     \note it will also call eZCLI::setIsQuiet()
    */
    function setIsQuiet( $isQuiet )
    {
        $cli =& eZCLI::instance();
        $this->IsQuiet = $isQuiet;
        $cli->setIsQuiet( $isQuiet );
    }

    /*!
     \return \c true if output is not allowed.
     \sa isLoud
    */
    function isQuiet()
    {
        return $this->IsQuiet;
    }

    /*!
     \return \c true if output is allowed.
     \sa isQuiet
    */
    function isLoud()
    {
        return !$this->IsQuiet;
    }

    function &instance( $settings = array() )
    {
        $implementation =& $GLOBALS['eZScriptInstance'];
        if ( !isset( $implementation ) or
             get_class( $implementation ) != 'ezscript' )
        {
            $implementation = new eZScript( $settings );
        }
        return $implementation;
    }

    /// \privatesection
    var $DebugMessage;
    var $UseDebugOutput;
    var $UseSession;
    var $UseExtensions;
    var $UseModules;
    var $User;
    var $SiteAccess;
    var $ExitCode;
    var $IsQuiet;
}

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
    $cli =& eZCLI::instance();
    $endl = $cli->endlineString();
    $webOutput = $cli->isWebOutput();
    $bold = $cli->style( 'bold' );
    $unbold = $cli->style( 'bold-end' );
    $par = $cli->style( 'paragraph' );
    $unpar = $cli->style( 'paragraph-end' );

    $allowedDebugLevels = true;
    $useDebugAccumulators = true;
    $useDebugTimingpoints = true;

//     $script =& $GLOBALS['eZScriptInstance'];
//     if ( isset( $script ) )
//     {
//         $allowedDebugLevels = $script->AllowedDebugLevels;
//         $useDebugAccumulators = $script->UseDebugAccumulators;
//         $useDebugTimingpoints = $script->UseDebugTimingPoints;
//     }

    eZDebug::setHandleType( EZ_HANDLE_NONE );
    if ( !$webOutput )
        print( $endl );
    print( $bold . "Fatal error" . $unbold . ": eZ publish did not finish it's request$endl" );
    print( $par . "The execution of eZ publish was abruptly ended, the debug output is present below." . $unpar . $endl );
    print( eZDebug::printReport( false, $webOutput, true ) );
}

/*!
     Reads settings from site.ini and passes them to eZDebug.
*/
function eZUpdateDebugSettings()
{
    global $debugOutput;
    global $useLogFiles;
    $ini =& eZINI::instance();
    $cli =& eZCLI::instance();
    $debugSettings = array();
    $debugSettings['debug-enabled'] = $ini->variable( 'DebugSettings', 'DebugOutput' ) == 'enabled';
    $debugSettings['debug-by-ip'] = $ini->variable( 'DebugSettings', 'DebugByIP' ) == 'enabled';
    $debugSettings['debug-ip-list'] = $ini->variable( 'DebugSettings', 'DebugIPList' );
    $debugSettings['debug-enabled'] = $debugOutput;
    $debugSettings['debug-log-files-enabled'] = $useLogFiles;
    if ( $cli->useStyles() and
         !$cli->isWebOutput() )
    {
        $debugSettings['debug-styles'] = $cli->terminalStyles();
    }
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

?>
