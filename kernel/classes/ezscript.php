<?php
//
// Definition of eZScript class
//
// Created on: <06-Aug-2003 11:06:35 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
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
$script = eZScript::instance();

$script->startup();

// Read arguments and modify script accordingly

$script->initialize();

// Do the actual script here

$script->shutdown(); // Finish execution

\endcode

*/

require_once( 'access.php' );

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
                                        'description' => 'eZ Publish script',
                                        'site-access' => false,
                                        'min_version' => false,
                                        'max_version' => false ),
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
        $this->Description = $settings['description'];
        $this->MinVersion = $settings['min_version'];
        $this->MaxVersion = $settings['max_version'];
        $this->ExitCode = false;
        $this->IsQuiet = false;
        $this->ShowVerbose = false;
        $this->IsInitialized = false;
        $this->CurrentOptions = false;
        $this->CurrentOptionConfig = false;
        $this->CurrentStandardOptions = false;
        $this->CurrentExcludeOptions = false;
        $this->CurrentOptionHelp = false;

        $this->IterationTrueString = '.';
        $this->IterationFalseString = '~';
        $this->IterationNumericStrings = false;
        $this->IterationWrapNumeric = false;
        $this->IterationIndex = 0;
        $this->IterationColumn = 0;
        $this->IterationColumnMax = 70;
        $this->IterationMax = false;
        $this->InitializationErrorMessage = 'unknown error';
    }

    /*!
     Checks if the script is run on correct eZ Publish version.
    */
    function validateVersion()
    {
        $versionValidated = false;
        $ezversion = eZPublishSDK::version();
        if ( $this->MinVersion !== false )
        {
            if ( $this->MaxVersion !== false )
            {
                if ( version_compare( $this->MinVersion, $ezversion , 'le' ) &&
                     version_compare( $this->MaxVersion, $ezversion , 'ge' ) )
                {
                    return true;
                }
                return false;
            }
            if ( version_compare( $this->MinVersion, $ezversion , 'le' ) )
            {
                return true;
            }
            return false;
        }
        else
        {
            if ( version_compare( $this->MaxVersion, $ezversion , 'ge' ) )
            {
                return true;
            }
            return false;
        }
    }

    /*!
     Checks if the script is run in CLI mode, if not it exits with a warning.
     The PHP local is also initialized if it is used.

     Call this at the very start of your script and always before getOptions() and initialize().
    */
    function startup()
    {
        error_reporting( E_ALL );

        eZDebug::setHandleType( eZDebug::HANDLE_TO_PHP );

        if ( php_sapi_name() != 'cli' )
        {
            $cli = eZCLI::instance();
            $cli->output( "PHP is currently using the '" . php_sapi_name() . "' interface. Make sure it is using the 'cli' interface." );
            exit( 1 );
        }

        $ini = eZINI::instance();
        $phpLocale = trim( $ini->variable( 'RegionalSettings', 'SystemLocale' ) );
        if ( $phpLocale != '' )
        {
            setlocale( LC_ALL, explode( ',', $phpLocale ) );
        }

        // Set correct site timezone
        $timezone = $ini->variable( "TimeZoneSettings", "TimeZone" );
        if ( $timezone )
        {
            date_default_timezone_set( $timezone );
        }
    }

    /*!
     Initializes all settings which are required for the script to run,
     must be called after startup() and getOptions().

     If you modify the eZScript object using the set* functions you must make sure that
     is done before this function is called.
    */
    function initialize()
    {
        if( ob_get_length() != 0 )
            ob_end_clean();
        $debugINI = eZINI::instance( 'debug.ini' );
        eZDebugSetting::setDebugINI( $debugINI );

        // Initialize text codec settings
        $this->updateTextCodecSettings();

        // Initialize debug settings
        $this->updateDebugSettings( $this->UseDebugOutput );

        // Set the different permissions/settings.
        $ini = eZINI::instance();
        $iniFilePermission = $ini->variable( 'FileSettings', 'StorageFilePermissions' );
        $iniDirPermission = $ini->variable( 'FileSettings', 'StorageDirPermissions' );
        $iniVarDirectory = eZSys::cacheDirectory() ;

        eZCodePage::setPermissionSetting( array( 'file_permission' => octdec( $iniFilePermission ),
                                                 'dir_permission'  => octdec( $iniDirPermission ),
                                                 'var_directory'   => $iniVarDirectory ) );


        eZExecution::addCleanupHandler( 'eZDBCleanup' );
        eZExecution::addFatalErrorHandler( 'eZFatalError' );

        eZDebug::setHandleType( eZDebug::HANDLE_FROM_PHP );

        if ( $this->UseExtensions )
        {
            // Check for extension
            require_once( 'kernel/common/ezincludefunctions.php' );
            eZExtension::activateExtensions( 'default' );
            // Extension check end
        }

        require_once( "access.php" );
        $siteaccess = $this->SiteAccess;
        if ( $siteaccess )
        {
            $access = array( 'name' => $siteaccess,
                             'type' => EZ_ACCESS_TYPE_STATIC );
        }
        else
        {
            $ini = eZINI::instance();
            $siteaccess = $ini->variable( 'SiteSettings', 'DefaultAccess' );
            $access = array( 'name' => $siteaccess,
                             'type' => EZ_ACCESS_TYPE_DEFAULT );
        }

        $access = changeAccess( $access );
        require_once( 'kernel/common/i18n.php' );

        if ( $this->UseExtensions )
        {
            // Check for siteaccess extension
            eZExtension::activateExtensions( 'access' );
            // Extension check end
        }

        // Set the global setting which is read by the session lib
        $GLOBALS['eZSiteBasics']['session-required'] = $this->UseSession;

        if ( $this->UseSession )
        {
            $db = eZDB::instance();
            if ( $db->isConnected() )
            {
                eZSession::start();
            }
            else
            {
                $this->IsInitialized = false;
                $this->InitializationErrorMessage = 'database error: ' . $db->errorMessage();
                return;
            }
        }

        if ( $this->User )
        {
            $userLogin = $this->User['login'];
            $userPassword = $this->User['password'];
            if ( $userLogin and $userPassword )
            {
                $userID = eZUser::loginUser( $userLogin, $userPassword );
                if ( !$userID )
                {
                    $cli = eZCLI::instance();
                    if ( $this->isLoud() )
                        $cli->warning( 'Failed to login with user ' . $userLogin );
                    eZExecution::cleanup();
                    eZExecution::setCleanExit();
                }
            }
        }

        // Initialize module handling
        if ( $this->UseModules )
        {
            $moduleRepositories = eZModule::activeModuleRepositories( $this->UseExtensions );
            eZModule::setGlobalPathList( $moduleRepositories );
        }
        $this->IsInitialized = true;
    }

    function isInitialized()
    {
        return $this->IsInitialized;
    }

    function initializationError()
    {
        return $this->InitializationErrorMessage;
    }

    /*!
     Shuts down the currently running script, the following things will be done:
     - Remove current session (if sessions are used)
     - Print debug messages (if debug is enabled)
     - Call cleanup function using eZExecution
     - Sets the clean exit flag, that way an exit, die or other stops will not issue an error

     If an exit code is set, PHP will exit with that code set (this means that this function never returns),
     otherwise this function returns normally.
    */
    function shutdown( $exitCode = false, $exitText = false )
    {
        $cli = eZCLI::instance();
        if ( class_exists( 'eZDB' )
             and eZDB::hasInstance() )
        {
            $db = eZDB::instance( false, array( 'show_errors' => false ) );
            // Perform transaction check
            $transactionCounterCheck = eZDB::checkTransactionCounter();
            if ( isset( $transactionCounterCheck['error'] ) )
                $cli->error( $transactionCounterCheck['error'] );

            if ( $this->UseSession and
                 $db->isConnected() )
            {
                eZUser::logoutCurrent();
                eZSession::remove();
            }
        }

        $webOutput = $cli->isWebOutput();

        if ( $this->UseDebugOutput or
             eZDebug::isDebugEnabled() )
        {
            if ( $this->DebugMessage )
                fputs( STDERR, $this->DebugMessage );
            fputs( STDERR, eZDebug::printReport( false, $webOutput, true,
                                                 $this->AllowedDebugLevels, $this->UseDebugAccumulators,
                                                 $this->UseDebugTimingPoints, $this->UseIncludeFiles ) );
        }

        eZExecution::cleanup();
        eZExecution::setCleanExit();
        $this->IsInitialized = false;
        if ( $exitCode !== false )
            $this->ExitCode = $exitCode;
        if ( $this->ExitCode !== false )
        {
            if ( $exitText !== false )
                $cli->output( $exitText );
            exit( $this->ExitCode );
        }
    }

    /*!
     Sets the text message which is shown before the debug list.
     There will be a default message which should suit most scripts.
     \note This requires that setUseDebugOutput is set to true or that
           the user has enabled debug in the arguments.
    */
    function setDebugMessage( $message )
    {
        $this->DebugMessage = $message;
    }

    /*!
     Sets whether debug output should be enabled or not.
     \note This can also be called by the argument parser if the user specifies to show debug.
    */
    function setUseDebugOutput( $useDebug )
    {
        $this->UseDebugOutput = $useDebug;
    }

    /*!
     Sets whether accumulators should be shown on debug output or not.
     \note This requires that setUseDebugOutput is set to true or that
           the user has enabled debug in the arguments.
    */
    function setUseDebugAccumulators( $useAccumulators )
    {
        $this->UseDebugAccumulators = $useAccumulators;
    }

    /*!
     Sets whether timing points should be shown on debug output or not.
     \note This requires that setUseDebugOutput is set to true or that
           the user has enabled debug in the arguments.
    */
    function setUseDebugTimingPoints( $useTimingPoints )
    {
        $this->UseDebugTimingPoints = $useTimingPoints;
    }

    /*!
     Sets whether include files should be shown on debug output or not.
     \note This requires that setUseDebugOutput is set to true or that
           the user has enabled debug in the arguments.
    */
    function setUseIncludeFiles( $useIncludeFiles )
    {
        $this->UseIncludeFiles = $useIncludeFiles;
    }

    /*!
     Sets which debug levels are to be shown on debug output, this must be an array
     with EZ_LEVEL_* definitions taken from eZDebug.
     \note This requires that setUseDebugOutput is set to true or that
           the user has enabled debug in the arguments.
    */
    function setAllowedDebugLevels( $allowedDebugLevels )
    {
        $this->AllowedDebugLevels = $allowedDebugLevels;
    }

    /*!
     Sets whether session is to be used or not.
     \note This will only work if it is set before initialized() is called.
     \note If session is enabled the current session data will be removed on shutdown().
    */
    function setUseSession( $useSession )
    {
        $this->UseSession = $useSession;
    }

    /*!
     Sets whether extension support is to be added or not.
     \note This will only work if it is set before initialized() is called.
    */
    function setUseExtensions( $useExtensions )
    {
        $this->UseExtensions = $useExtensions;
    }

    /*!
     Sets the current site access to \a $siteAccess.
     \note This will only work if it is set before initialized() is called.
     \note This will be filled in if getOptions() is used and the user specifices it in the arguments.
    */
    function setUseSiteAccess( $siteAccess )
    {
        $this->SiteAccess = $siteAccess;
    }

    /*!
     \return the currently set siteaccess or \c false if none is set.
    */
    function usedSiteAccess()
    {
        return $this->SiteAccess;
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
     Controls whether verbose output is used or not, use \c false to turn it off,
     \c true to turn it on or a number to select the verbose level (\c true == 1).
     The actual behaviour of verbose output depends on the script, however enabling
     it will make sure iteration looping displays the iteration name instead of a dot.
    */
    function setShowVerboseOutput( $verbose )
    {
        if ( $verbose === true )
            $verbose = 1;
        $this->ShowVerbose = $verbose;
    }

    /*!
     \return the verbosity level for the script, will be \c false or a number in the range 1 and up.
    */
    function verboseOutputLevel()
    {
        return $this->ShowVerbose;
    }

    /*!
     \return the currently set options if getOptions() has been run or \c false if no options are set.
    */
    function currentOptions()
    {
        return $this->CurrentOptions;
    }

    /*!
     \return the current option configuration, this will be a mix of the standard options and script specified.
    */
    function currentOptionConfig()
    {
        return $this->CurrentOptionConfig;
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
        $cli = eZCLI::instance();
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

    function setIterationData( $trueString, $falseString,
                               $numericStrings = false, $wrapNumeric = false )
    {
        $this->IterationTrueString = $trueString;
        $this->IterationFalseString = $falseString;
        $this->IterationNumericStrings = $numericStrings;
        $this->IterationWrapNumeric = $wrapNumeric;
    }

    function resetIteration( $iterationMax = false, $startIndex = 0 )
    {
        $this->IterationIndex = $startIndex;
        $this->IterationColumn = 0;
        $this->IterationMax = $iterationMax;
    }

    function iterate( $cli, $status, $text = false )
    {
        if ( !$this->IterationNumericStrings )
            $status = (bool)$status;
        if ( $this->verboseOutputLevel() === false or
             $text === false )
        {
            if ( is_bool( $status ) )
            {
                $statusText = $status ? $this->IterationTrueString : $this->IterationFalseString;
            }
            else
            {
                if ( $this->IterationWrapNumeric )
                    $status = $status % count( $this->IterationNumericStrings );
                if ( $status < count( $this->IterationNumericStrings ) )
                    $statusText = $this->IterationNumericStrings[$status];
                else
                    $statusText = ' ';
            }
            $endLine = false;
            $changeLine = false;
            ++$this->IterationIndex;
            ++$this->IterationColumn;
            $iterationColumn = $this->IterationColumn;
            if ( $this->IterationColumn >= $this->IterationColumnMax )
            {
                $this->IterationColumn = 0;
                $changeLine = true;
            }
            if ( $this->IterationMax !== false )
            {
                if ( $this->IterationIndex >= $this->IterationMax )
                {
                    $this->IterationColumn = 0;
                    $changeLine = true;
                }
            }
            if ( $changeLine )
            {
                if ( $this->IterationMax !== false )
                {
                    $spacing = $this->IterationColumnMax - $iterationColumn;
                    $percent = ( $this->IterationIndex * 100 ) / $this->IterationMax;
                    if ( $percent > 100.0 )
                        $percent = 100;
                    else
                        $spacing += 1;
                    $percentText = number_format( $percent, 2 ) . '%';
                    $statusText .= str_repeat( ' ', $spacing );
                    $statusText .= $percentText;
                }
                $endLine = true;
            }
            $cli->output( $statusText, $endLine );
        }
        else
        {
            $statusLevel = $status;
            if ( is_bool( $status ) )
                $statusLevel = $status ? 0 : 1;
            if ( $statusLevel > 0 )
            {
                --$statusLevel;
                $statusLevels = array( 'warning', 'failure' );
                if ( $statusLevel > count( $statusLevels ) )
                    $statusLevel = count( $statusLevels ) - 1;
                $levelText = $statusLevels[$statusLevel];
                $cli->output( $cli->stylize( $levelText, $text ) );
            }
            else
            {
                $cli->output( $text );
            }
        }
    }

    function showHelp( $useStandardOptions = false, $optionConfig = false, $optionHelp = false, $argumentConfig = false, $arguments = false )
    {
        if ( $useStandardOptions === false )
        {
            $useStandardOptions = $this->CurrentStandardOptions;
        }
        if ( $optionConfig === false )
        {
            $optionConfig = $this->CurrentOptionConfig;
        }
        if ( $optionHelp === false )
        {
            $optionHelp = $this->CurrentOptionHelp;
        }
        if ( $argumentConfig === false )
        {
            $argumentConfig = $this->ArgumentConfig;
        }
        $optionList = array();
        foreach ( $optionConfig['list'] as $configItem )
        {
            if ( in_array( $configItem['name'], $this->CurrentExcludeOptions ) )
                continue;
            $optionText = '-';
            if ( $configItem['is-long-option'] )
                $optionText .= '-';
            $optionText .= $configItem['name'];
            if ( $configItem['has-value'] and $configItem['is-long-option'] )
                $optionText .= "=VALUE";
            $hasMultipleValues = ( $configItem['quantifier']['min'] > 1 or
                                   $configItem['quantifier']['max'] === false or
                                   $configItem['quantifier']['max'] > 1 );
            if ( $hasMultipleValues )
                $optionText .= "...";
            $optionDescription = '';
            if ( isset( $optionHelp[$configItem['name']] ) )
                $optionDescription = $optionHelp[$configItem['name']];
            $optionList[] = array( $optionText, $optionDescription );
        }
        if ( $arguments === false )
        {
            $arguments = $_SERVER['argv'];
            $program = $arguments[0];
        }
        $cli = eZCLI::instance();
        $generalOptionList = array();
        $generalOptionList = array();
        if ( $useStandardOptions )
        {
            $generalOptionList[] = array( '-h,--help', 'display this help and exit');
            $generalOptionList[] = array( '-q,--quiet', 'do not give any output except when errors occur' );
            if ( $useStandardOptions['siteaccess'] )
                $generalOptionList[] = array( '-s,--siteaccess', "selected siteaccess for operations,\nif not specified default siteaccess is used" );
            if ( $useStandardOptions['debug'] )
                $generalOptionList[] = array( '-d,--debug...', ( "display debug output at end of execution,\n" .
                                                                 "the following debug items can be controlled: \n" .
                                                                 "all, accumulator, include, timing, error, warning, debug, notice or strict." ) );
            if ( $useStandardOptions['colors'] )
            {
                $generalOptionList[] = array( '-c,--colors', 'display output using ANSI colors (default)' );
                $generalOptionList[] = array( '--no-colors', 'do not use ANSI coloring' );
            }
            if ( $useStandardOptions['user'] )
            {
                $generalOptionList[] = array( '-l,--login USER', 'login with USER and use it for all operations' );
                $generalOptionList[] = array( '-p,--password PWD', 'use PWD as password for USER' );
            }
            if ( $useStandardOptions['log'] )
            {
                $generalOptionList[] = array( '--logfiles', 'create log files' );
                $generalOptionList[] = array( '--no-logfiles', 'do not create log files (default)' );
            }
            if ( $useStandardOptions['verbose'] )
            {
                $generalOptionList[] = array( '-v,--verbose...', "display more information, \nused multiple times will increase amount of information" );
            }
        }
        $description = $this->Description;
        $helpText =  "Usage: " . $program;
        if ( count( $optionList ) > 0 or count( $generalOptionList ) > 0 )
        {
            $helpText .= " [OPTION]...";
        }
        if ( $argumentConfig && isset( $argumentConfig['list'] ) && is_array( $argumentConfig['list'] ) )
        {
            foreach ( $argumentConfig['list'] as $argumentItem )
            {
                $argumentName = strtoupper( $argumentItem['name'] );
                $quantifier = $argumentItem['quantifier'];
                if ( $quantifier['min'] > 1 or $quantifier['max'] === false or $quantifier['max'] > 1 )
                    $helpText .= " [$argumentName]...";
                else
                    $helpText .= " [$argumentName]";
            }
        }
        if ( $description )
            $helpText .= "\n" . $description . "\n";
        if ( count( $generalOptionList ) > 0 )
        {
            $helpText .= "\nGeneral options:\n";
            $maxLength = 0;
            foreach ( $generalOptionList as $optionItem )
            {
                $maxLength = max( strlen( $optionItem[0] ), $maxLength );
            }
            $spacingLength = $maxLength + 2;
            foreach ( $generalOptionList as $optionItem )
            {
                $option = $optionItem[0];
                $optionDescription = $optionItem[1];
                $optionLines = explode( "\n", $option );
                $optionDescriptionLines = explode( "\n", $optionDescription );
                $count = max( count( $optionLines ), count( $optionDescriptionLines ) );
                for ( $i = 0; $i < $count; ++$i )
                {
                    $optionText = '';
                    if ( isset( $optionLines[$i] ) )
                        $optionText = $optionLines[$i];
                    $optionDescriptionText = '';
                    if ( isset( $optionDescriptionLines[$i] ) )
                        $optionDescriptionText = $optionDescriptionLines[$i];
                    $spacing = $spacingLength - strlen( $optionText );
                    if ( $optionText or $optionDescriptionText )
                        $helpText .= '  ';
                    $helpText .= $optionText;
                    if ( $i > 0 )
                        $spacing += 2;
                    if ( $optionDescriptionText )
                        $helpText .= str_repeat( ' ', $spacing ) . $optionDescriptionText;
                    $helpText .= "\n";
                }
            }
        }
        if ( count( $optionList ) > 0 )
        {
            $helpText .= "\nOptions:\n";
            $maxLength = 0;
            foreach ( $optionList as $optionItem )
            {
                $maxLength = max( strlen( $optionItem[0] ), $maxLength );
            }
            $spacingLength = $maxLength + 2;
            foreach ( $optionList as $optionItem )
            {
                $option = $optionItem[0];
                $optionDescription = $optionItem[1];
                $optionLines = explode( "\n", $option );
                $optionDescriptionLines = explode( "\n", $optionDescription );
                $count = max( count( $optionLines ), count( $optionDescriptionLines ) );
                for ( $i = 0; $i < $count; ++$i )
                {
                    $optionText = '';
                    if ( isset( $optionLines[$i] ) )
                        $optionText = $optionLines[$i];
                    $optionDescriptionText = '';
                    if ( isset( $optionDescriptionLines[$i] ) )
                        $optionDescriptionText = $optionDescriptionLines[$i];
                    $spacing = $spacingLength - strlen( $optionText );
                    if ( $optionText or $optionDescriptionText )
                        $helpText .= '  ';
                    $helpText .= $optionText;
                    if ( $i > 0 )
                        $spacing += 2;
                    if ( $optionDescriptionText )
                        $helpText .= str_repeat( ' ', $spacing ) . $optionDescriptionText;
                    $helpText .= "\n";
                }
            }
        }
        $cli->output( $helpText );
    }

    /*!
     Parse command line into options array. If stanadrd options are in use, carry
     out the associated task (eg. switch siteaccess ir logged-in user)
     /param $config see ezcli::parseOptionString
     /param $argumentConfig  see ezcli::getOptions (unused for now)
     /param $optionHelp string echoed to screen when script invoked with -h/--help
     /param $arguments array of arguments. If false, command line is parsed automatically
     /param $useStandardOptions true or an array of standard options to be used.
       standard options are: 'debug', 'colors', 'log', 'siteaccess', 'verbose', 'user' (false), and can be set to false to be disabled
    */
    function getOptions( $config = '', $argumentConfig = '', $optionHelp = false,
                         $arguments = false, $useStandardOptions = true )
    {
        if ( is_string( $config ) )
            $config = eZCLI::parseOptionString( $config, $tmpConfig );
        if ( is_string( $argumentConfig ) )
            $argumentConfig = eZCLI::parseOptionString( $argumentConfig, $tmpArgumentConfig );

        if ( $useStandardOptions )
        {
            if ( !is_array( $useStandardOptions ) )
                $useStandardOptions = array();
            $useStandardOptions = array_merge( array( 'debug' => true,
                                                      'colors' => true,
                                                      'log' => true,
                                                      'siteaccess' => true,
                                                      'verbose' => true,
                                                      'user' => false ),
                                               $useStandardOptions );
        }

        if ( $useStandardOptions )
        {
            $optionConfig = $config;
            $excludeOptions = array();
            $optionString = "[h|help][q|quiet]";
            $excludeOptions[] = 'h';
            $excludeOptions[] = 'help';
            $excludeOptions[] = 'q';
            $excludeOptions[] = 'quiet';
            if ( $useStandardOptions['debug'] )
            {
                $optionString .= "[d;*|debug;*]";
                $excludeOptions[] = 'd';
                $excludeOptions[] = 'debug';
            }
            if ( $useStandardOptions['colors'] )
            {
                $optionString .= "[c|colors][no-colors]";
                $excludeOptions[] = 'c';
                $excludeOptions[] = 'colors';
                $excludeOptions[] = 'no-colors';
            }
            if ( $useStandardOptions['log'] )
            {
                $optionString .= "[logfiles][no-logfiles]";
                $excludeOptions[] = 'logfiles';
                $excludeOptions[] = 'no-logfiles';
            }
            if ( $useStandardOptions['siteaccess'] )
            {
                $optionString .= "[s:|siteaccess:]";
                $excludeOptions[] = 's';
                $excludeOptions[] = 'siteaccess';
            }
            if ( $useStandardOptions['user'] )
            {
                $optionString .= "[l:|login:][p:|password:]";
                $excludeOptions[] = 'l';
                $excludeOptions[] = 'login';
                $excludeOptions[] = 'p';
                $excludeOptions[] = 'password';
            }
            if ( $useStandardOptions['verbose'] )
            {
                $optionString .= "[v*|verbose*]";
                $excludeOptions[] = 'v';
                $excludeOptions[] = 'verbose';
            }
            $config = eZCLI::parseOptionString( $optionString, $optionConfig );
        }
        $cli = eZCLI::instance();
        $options = $cli->getOptions( $config, $argumentConfig, $arguments );
        $this->CurrentOptionConfig = $config;
        $this->CurrentOptions = $options;
        $this->CurrentStandardOptions = $useStandardOptions;
        $this->CurrentExcludeOptions = $excludeOptions;
        $this->CurrentOptionHelp = $optionHelp;
        $this->ArgumentConfig = $argumentConfig;
        if ( !$options )
        {
            if ( !$this->IsInitialized )
                $this->initialize();
            $this->shutdown( 1 );
        }
        if ( $useStandardOptions )
        {
            if ( $options['quiet'] )
                $this->setIsQuiet( true );
            $useColors = true;
            if ( $options['colors'] )
                $useColors = true;
            if ( $options['no-colors'] )
                $useColors = false;
            $cli->setUseStyles( $useColors );
            if ( $options['debug'] )
            {
                $levels = array();
                foreach ( $options['debug'] as $debugOption )
                {
                    $levels = array_merge( $levels, explode( ',', $debugOption ) );
                }
                $allowedDebugLevels = array();
                $useDebugAccumulators = false;
                $useDebugTimingpoints = false;
                $useIncludeFiles = false;
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
                    if ( $level == 'strict' )
                        $level = eZDebug::LEVEL_STRICT;
                    else if ( $level == 'error' )
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
                $this->setUseDebugOutput( true );
                $this->setAllowedDebugLevels( $allowedDebugLevels );
                $this->setUseDebugAccumulators( $useDebugAccumulators );
                $this->setUseDebugTimingPoints( $useDebugTimingpoints );
                $this->setUseIncludeFiles( $useIncludeFiles );
                $this->setDebugMessage( "\n\n" . str_repeat( '#', 36 ) . $cli->style( 'emphasize' ) . " DEBUG " . $cli->style( 'emphasize-end' )  . str_repeat( '#', 36 ) . "\n" );
            }
            if ( count( $options['verbose'] ) > 0 )
            {
                $this->setShowVerboseOutput( count( $options['verbose'] ) );
            }
            if ( $options['help'] )
            {
                if ( !$this->IsInitialized )
                    $this->initialize();
                $this->showHelp();
                $this->shutdown( 0 );
            }
            if ( isset( $options['siteaccess'] ) and $options['siteaccess'] )
                $this->setUseSiteAccess( $options['siteaccess'] );

            if ( isset( $options['login'] ) and $options['login'] )
                $this->setUser( $options['login'], isset( $options['password'] ) ? $options['password'] : false );
        }
        return $options;
    }

    /**
     * Returns a shared instance of the eZScript class.
     *
     * @param $settings array Used by the first generated instance, but ignored for subsequent calls.
     * @return eZScript
     */
    static function instance( $settings = array() )
    {
        if ( !isset( $GLOBALS['eZScriptInstance'] ) or
             !( $GLOBALS['eZScriptInstance'] instanceof eZScript ) )
        {
            $GLOBALS['eZScriptInstance'] = new eZScript( $settings );
        }
        return $GLOBALS['eZScriptInstance'];
    }

    /*!
     \static
     Reads settings from site.ini and passes them to eZDebug.
    */
    function updateDebugSettings( $useDebug = null )
    {
        global $debugOutput;
        global $useLogFiles;
        $ini = eZINI::instance();
        $cli = eZCLI::instance();
        $debugSettings = array();
        $debugSettings['debug-enabled'] = ( $ini->variable( 'DebugSettings', 'DebugOutput' ) == 'enabled' and
                                            $ini->variable( 'DebugSettings', 'ScriptDebugOutput' ) == 'enabled' );
        if ( $useDebug !== null )
            $debugSettings['debug-enabled'] = $useDebug;
        $debugSettings['debug-by-ip'] = $ini->variable( 'DebugSettings', 'DebugByIP' ) == 'enabled';
        $debugSettings['debug-ip-list'] = $ini->variable( 'DebugSettings', 'DebugIPList' );
        if ( isset( $debugOutput ) )
            $debugSettings['debug-enabled'] = $debugOutput;
        $debugSettings['debug-log-files-enabled'] = $useLogFiles;
        if ( $cli->useStyles() and
             !$cli->isWebOutput() )
        {
            $debugSettings['debug-styles'] = $cli->terminalStyles();
        }
        $logList = $ini->variable( 'DebugSettings', 'AlwaysLog' );
        $logMap = array( 'notice' => eZDebug::LEVEL_NOTICE,
                         'warning' => eZDebug::LEVEL_WARNING,
                         'error' => eZDebug::LEVEL_ERROR,
                         'debug' => eZDebug::LEVEL_DEBUG,
                         'strict' => eZDebug::LEVEL_STRICT );
        $debugSettings['always-log'] = array();
        foreach ( $logMap as $name => $level )
        {
            $debugSettings['always-log'][$level] = in_array( $name, $logList );
        }
        eZDebug::updateSettings( $debugSettings );
    }

    /*!
     \static
     Reads settings from i18n.ini and passes them to eZTextCodec.
    */
    function updateTextCodecSettings()
    {
        $ini = eZINI::instance( 'i18n.ini' );
        $i18nSettings = array();
        $i18nSettings['internal-charset'] = $ini->variable( 'CharacterSettings', 'Charset' );
        $i18nSettings['http-charset'] = $ini->variable( 'CharacterSettings', 'HTTPCharset' );
        $i18nSettings['mbstring-extension'] = $ini->variable( 'CharacterSettings', 'MBStringExtension' ) == 'enabled';
        eZTextCodec::updateSettings( $i18nSettings );
    }

    /// \privatesection
    public $InitializationErrorMessage;
    public $DebugMessage;
    public $UseDebugOutput;
    public $UseSession;
    public $UseExtensions;
    public $UseModules;
    public $User;
    public $SiteAccess;
    public $ExitCode;
    public $IsQuiet;
    public $ShowVerbose;
}

function eZDBCleanup()
{
    if ( class_exists( 'ezdb' )
         and eZDB::hasInstance() )
    {
        $db = eZDB::instance();
        $db->setIsSQLOutputEnabled( false );
    }
//     session_write_close();
}

function eZFatalError()
{
    $cli = eZCLI::instance();
    $endl = $cli->endlineString();
    $webOutput = $cli->isWebOutput();
    $bold = $cli->style( 'bold' );
    $unbold = $cli->style( 'bold-end' );
    $par = $cli->style( 'paragraph' );
    $unpar = $cli->style( 'paragraph-end' );

    $allowedDebugLevels = true;
    $useDebugAccumulators = true;
    $useDebugTimingpoints = true;

    eZDebug::setHandleType( eZDebug::HANDLE_NONE );
    if ( !$webOutput )
        fputs( STDERR, $endl );
    fputs( STDERR, $bold . "Fatal error" . $unbold . ": eZ Publish did not finish its request$endl" );
    fputs( STDERR, $par . "The execution of eZ Publish was abruptly ended, the debug output is present below." . $unpar . $endl );
    fputs( STDERR, eZDebug::printReport( false, $webOutput, true ) );
}

/*!
  Dummy function, required by some scripts in eZ Publish.
*/
function eZUpdateDebugSettings( $useDebug = null )
{
}

/*!
  Dummy function, required by some scripts in eZ Publish.
*/
function eZUpdateTextCodecSettings()
{
}

?>