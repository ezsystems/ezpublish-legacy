<?php
//
// Definition of Command Line Interface functions
//
// Created on: <05-Aug-2003 13:00:00 amos>
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

/*! \file cli.php
*/

include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'lib/ezutils/classes/ezdebugsetting.php' );

define( 'EZ_CLI_TERMINAL_ENDOFLINE_STRING', "\n" );

class eZCLI
{
    function eZCLI()
    {
        $endl = "<br/>";
        $webOutput = true;
        if ( isset( $_SERVER['argv'] ) )
        {
            $endl = EZ_CLI_TERMINAL_ENDOFLINE_STRING;
            $webOutput = false;
        }
        $this->EndlineString = $endl;
        $this->WebOutput = $webOutput;
        $this->TerminalStyles = array( 'warning' => "\033[1;35m",
                                       'warning-end' => "\033[0;39m",
                                       'error' => "\033[1;31m",
                                       'error-end' => "\033[0;39m",
                                       'notice' => "\033[1;32m",
                                       'notice-end' => "\033[0;39m",
                                       'debug' => "\033[1;30m",
                                       'debug-end' => "\033[0;39m",
                                       'timing' => "\033[1;34m",
                                       'timing-end' => "\033[0;39m",
                                       'success' => "\033[1;32m",
                                       'success-end' => "\033[0;39m",
                                       'emphasize' => "\033[1;38m",
                                       'emphasize-end' => "\033[0;39m",
                                       'mark' => "\033[1;30m",
                                       'mark-end' => "\033[0;39m",
                                       'bold' => "\033[1;38m",
                                       'bold-end' => "\033[0;39m",
                                       'paragraph' => "\033[0;39m",
                                       'paragraph-end' => "\033[0;39m",
                                       'normal' => "\033[0;39m",
                                       'normal-end' => "\033[0;39m" );
        $this->WebStyles = array( 'warning' => "\033[1;35m",
                                  'warning-end' => "\033[0;39m",
                                  'error' => "\033[1;31m",
                                  'error-end' => "\033[0;39m",
                                  'notice' => "\033[1;38m",
                                  'notice-end' => "\033[0;39m",
                                  'debug' => "\033[1;30m",
                                  'debug-end' => "\033[0;39m",
                                  'timing' => "\033[1;34m",
                                  'timing-end' => "\033[0;39m",
                                  'success' => "\033[1;32m",
                                  'success-end' => "\033[0;39m",
                                  'emphasize' => "\033[1;38m",
                                  'emphasize-end' => "\033[0;39m",
                                  'mark' => "\033[1;30m",
                                  'mark-end' => "\033[0;39m",
                                  'bold' => "\033[1;38m",
                                  'bold-end' => "\033[0;39m",
                                  'paragraph' => "\033[0;39m",
                                  'paragraph-end' => "\033[0;39m",
                                  'normal' => "\033[0;39m",
                                  'normal-end' => "\033[0;39m" );
        $this->EmptyStyles = array();
        foreach ( $this->TerminalStyles as $styleName => $styleValue )
        {
            $this->EmptyStyles[$styleName] = false;
        }
        $this->UseStyles = false;
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
        // Initialize text codec settings
        eZUpdateTextCodecSettings();

        // Initialize debug settings
        eZUpdateDebugSettings();

        include_once( 'lib/ezutils/classes/ezexecution.php' );

        eZExecution::addCleanupHandler( 'eZDBCleanup' );
        eZExecution::addFatalErrorHandler( 'eZFatalError' );

        eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );
    }

    function endlineString()
    {
        return $this->EndlineString;
    }

    function isWebOutput()
    {
        return $this->WebOutput;
    }

    function terminalStyle( $name )
    {
        return $this->TerminalStyles[$name];
    }

    function webStyle( $name )
    {
        return $this->WebStyles[$name];
    }

    function terminalStyles()
    {
        return $this->TerminalStyles;
    }

    function webStyles()
    {
        return $this->WebStyles;
    }

    function emptyStyles()
    {
        return $this->EmptyStyles;
    }

    function style( $name )
    {
        if ( $this->UseStyles )
        {
            if ( $this->isWebOutput() )
                return $this->terminalStyle( $name );
            else
                return $this->webStyle( $name );
        }
        return false;
    }

    function setUseStyles( $useStyles )
    {
        $this->UseStyles = $useStyles;
    }

    function useStyles()
    {
        return $this->UseStyles;
    }

    function notice( $string, $addEOL = true )
    {
        print( $string );
        if ( $addEOL )
            print( $this->endlineString() );
    }

    function warning( $string, $addEOL = true )
    {
        print( $string );
        if ( $addEOL )
            print( $this->endlineString() );
    }

    function &instance()
    {
        $implementation =& $GLOBALS['eZCLIInstance'];
        if ( !isset( $implementation ) or
             get_class( $implementation ) != 'ezcli' )
        {
            $implementation = new eZCLI();
        }
        return $implementation;
    }
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
    $bold = $cli->style( 'bold' );
    $unbold = $cli->style( 'bold-end' );
    $par = $cli->style( 'paragraph' );
    $unpar = $cli->style( 'paragraph-end' );

    eZDebug::setHandleType( EZ_HANDLE_NONE );
    print( $bold . "Fatal error" . $unbold . ": eZ publish did not finish it's request$endl" );
    print( $par . "The execution of eZ publish was abruptly ended, the debug output is present below." . $unpar );
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
