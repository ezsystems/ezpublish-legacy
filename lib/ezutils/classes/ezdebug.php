<?php
//
// Definition of eZDebug class
//
// Created on: <12-Feb-2002 11:00:54 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \defgroup eZUtils Utility classes */

/*!
  \class eZDebug ezdebug.php
  \ingroup eZUtils
  \brief Advanced debug/log system

  The eZ debug library is used to handle debug information. It
  can display information on screen and/or write it to log files.

  You can enable on-screen debug information for specific IP addresses.

  Timing points can be placed in the code to time the different sections of code.

  Each debug message can be turned on/off by using the showTypes() function.

  PHP error messages can also be shown using setHandleType().

  \code
  include_once( "lib/ezutils/classes/ezdebug.php" );

  // write a notice
  eZDebug::writeNotice( "Image found" );

  // write a warning
  eZDebug::writeWarning( "Image not found, using default" );

  // write an error
  eZDebug::writeError( "Object not found, bailing.." );

  // add a timing points
  eZDebug::addTimingPoint( "Module Found" );

  //.... code

  eZDebug::addTimingPoint( "Module loading" );

  // print the results on screen.
  eZDebug::printReport();

  \endcode
*/

include_once( "lib/ezutils/classes/ezini.php" );
include_once( "lib/ezutils/classes/ezsys.php" );

define( "EZ_LEVEL_NOTICE", 1 );
define( "EZ_LEVEL_WARNING", 2 );
define( "EZ_LEVEL_ERROR", 3 );
define( "EZ_LEVEL_TIMING_POINT", 4 );
define( "EZ_LEVEL_DEBUG", 5 );

define( "EZ_SHOW_NOTICE", 1 << (EZ_LEVEL_NOTICE - 1) );
define( "EZ_SHOW_WARNING", 1 << (EZ_LEVEL_WARNING - 1) );
define( "EZ_SHOW_ERROR", 1 << (EZ_LEVEL_ERROR - 1) );
define( "EZ_SHOW_TIMING_POINT", 1 << (EZ_LEVEL_TIMING_POINT - 1) );
define( "EZ_SHOW_DEBUG", 1 << (EZ_LEVEL_DEBUG - 1) );
define( "EZ_SHOW_ALL", EZ_SHOW_NOTICE | EZ_SHOW_WARNING | EZ_SHOW_ERROR | EZ_SHOW_TIMING_POINT | EZ_SHOW_DEBUG );

define( "EZ_HANDLE_NONE", 0 );
define( "EZ_HANDLE_FROM_PHP", 1 );
define( "EZ_HANDLE_TO_PHP", 2 );

define( "EZ_OUTPUT_MESSAGE_SCREEN", 1 );
define( "EZ_OUTPUT_MESSAGE_STORE", 2 );


class eZDebug
{
    /*!
      Creates a new debug object.
    */
    function eZDebug( )
    {
        $this->TmpTimePoints = array( EZ_LEVEL_NOTICE => array(),
                                      EZ_LEVEL_WARNING => array(),
                                      EZ_LEVEL_ERROR => array(),
                                      EZ_LEVEL_DEBUG => array() );

        $this->OutputFormat = array( EZ_LEVEL_NOTICE => array( "color" => "green",
                                                               "name" => "Notice" ),
                                     EZ_LEVEL_WARNING => array( "color" => "orange",
                                                                "name" => "Warning" ),
                                     EZ_LEVEL_ERROR => array( "color" => "red",
                                                              "name" => "Error" ),
                                     EZ_LEVEL_DEBUG => array( "color" => "brown",
                                                              "name" => "Debug" ),
                                     EZ_LEVEL_TIMING_POINT => array( "color" => "blue",
                                                                     "name" => "Timing" ) );
        $this->LogFiles = array( EZ_LEVEL_NOTICE => array( "var/log/",
                                                           "notice.log" ),
                                 EZ_LEVEL_WARNING => array( "var/log/",
                                                            "warning.log" ),
                                 EZ_LEVEL_ERROR => array( "var/log/",
                                                          "error.log" ),
                                 EZ_LEVEL_DEBUG => array( "var/log/",
                                                          "debug.log" ) );
        $this->MessageTypes = array( EZ_LEVEL_NOTICE,
                                     EZ_LEVEL_WARNING,
                                     EZ_LEVEL_ERROR,
                                     EZ_LEVEL_TIMING_POINT,
                                     EZ_LEVEL_DEBUG );
        $this->LogFileEnabled = array( EZ_LEVEL_NOTICE => true,
                                       EZ_LEVEL_WARNING => true,
                                       EZ_LEVEL_ERROR => true,
                                       EZ_LEVEL_TIMING_POINT => true,
                                       EZ_LEVEL_DEBUG => true );
        $this->ShowTypes = EZ_SHOW_ALL;
        $this->HandleType = EZ_HANDLE_NONE;
        $this->OldHandler = false;
        $this->UseCSS = false;
        $this->MessageOutput = EZ_OUTPUT_MESSAGE_STORE;
        $this->ScriptStart = eZDebug::timeToFloat( microtime() );
        $this->TimeAccumulatorList = array();
        $this->TimeAccumulatorGroupList = array();
    }

    function reset()
    {
        $this->DebugStrings = array();
        $this->TmpTimePoints = array( EZ_LEVEL_NOTICE => array(),
                                      EZ_LEVEL_WARNING => array(),
                                      EZ_LEVEL_ERROR => array(),
                                      EZ_LEVEL_DEBUG => array() );
        $this->TimeAccumulatorList = array();
        $this->TimeAccumulatorGroupList = array();
    }

    /*!
      Will return the current eZDebug object. If no object exists one will
      be created.
    */
    function &instance( )
    {
        $impl =& $GLOBALS["eZDebugGlobalInstance"];

        $class =& get_class( $impl );
        if ( $class != "ezdebug" )
        {
            $impl = new eZDebug();
        }
        return $impl;
    }

    /*!
     \static
     Returns true if the message type $type can be shown.
    */
    function showMessage( $type )
    {
        $debug =& eZDebug::instance();
        return $debug->ShowTypes & $type;
    }

    /*!
     Determines how PHP errors are handled. If $type is EZ_HANDLE_TO_PHP all error messages
     is sent to PHP using trigger_error(), if $type is EZ_HANDLE_FROM_PHP all error messages
     from PHP is fetched using a custom error handler and output as a usual eZDebug message.
     If $type is EZ_HANDLE_NONE there is no error exchange between PHP and eZDebug.
    */
    function setHandleType( $type )
    {
        if ( !isset( $this ) or
             get_class( $this ) != "ezdebug" )
            $this =& eZDebug::instance();
        if ( $type != EZ_HANDLE_TO_PHP and
             $type != EZ_HANDLE_FROM_PHP )
            $type = EZ_HANDLE_NONE;
        if ( $type == $this->HandleType )
            return;

        if ( $this->HandleType == EZ_HANDLE_FROM_PHP )
            restore_error_handler();
        switch ( $type )
        {
            case EZ_HANDLE_FROM_PHP:
            {
                set_error_handler( "eZDebugErrorHandler" );
            } break;

            case EZ_HANDLE_TO_PHP:
            {
                restore_error_handler();
            } break;

            case EZ_HANDLE_NONE:
            {
            }
        }
        $this->HandleType = $type;
    }

    /*!
     \static
     Sets types to be shown to $types and returns the old show types.
     If $types is not supplied the current value is returned and no change is done.
     $types is one or more of EZ_SHOW_NOTICE, EZ_SHOW_WARNING, EZ_SHOW_ERROR, EZ_SHOW_TIMING_POINT
     or'ed together.
    */
    function showTypes( $types = false )
    {
        if ( !isset( $this ) or
             get_class( $this ) != "ezdebug" )
            $this =& eZDebug::instance();
        if ( $types === false )
            return $this->ShowTypes;
        $old_types = $this->ShowTypes;
        $this->ShowTypes = $types;
        return $old_types;
    }

    /*!
     Handles PHP errors, creates notice, warning and error messages for
     the various PHP error types.
    */
    function errorHandler( $errno, $errstr, $errfile, $errline )
    {
        if ( error_reporting() == 0 ) // @ error-control operator is used
            return;
        if ( !eZDebug::isDebugEnabled() )
            return;
        $str = "$errstr in $errfile on line $errline";
        $errnames =& $GLOBALS["eZDebugPHPErrorNames"];
        if ( !is_array( $errnames ) )
        {
            $errnames = array( E_ERROR => "E_ERROR",
                               E_PARSE => "E_PARSE",
                               E_CORE_ERROR => "E_CORE_ERROR",
                               E_COMPILE_ERROR => "E_COMPILE_ERROR",
                               E_USER_ERROR => "E_USER_ERROR",
                               E_WARNING => "E_WARNING",
                               E_CORE_WARNING => "E_CORE_WARNING",
                               E_COMPILE_WARNING => "E_COMPILE_WARNING",
                               E_USER_WARNING => "E_USER_WARNING",
                               E_NOTICE => "E_NOTICE",
                               E_USER_NOTICE => "E_USER_NOTICE" );
        }
        $errname = "unknown";
        if ( isset( $errnames[$errno] ) )
            $errname = $errnames[$errno];
        switch ( $errno )
        {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            {
                eZDebug::writeError( $str, "PHP" );
            } break;

            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
            case E_NOTICE:
            {
                eZDebug::writeWarning( $str, "PHP" );
            } break;

            case E_USER_NOTICE:
            {
                eZDebug::writeNotice( $str, "PHP" );
            } break;
        }
    }

    /*!
      \static
      Writes a debug notice.
    */
    function writeNotice( $string, $label="" )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        if ( !eZDebug::showMessage( EZ_SHOW_NOTICE ) )
            return;
        if ( is_object( $string ) || is_array( $string ) )
             $string =& eZDebug::dumpVariable( $string );

        $debug =& eZDebug::instance();
        if ( $debug->HandleType == EZ_HANDLE_TO_PHP )
            trigger_error( $string, E_USER_NOTICE );
        else
            $debug->write( $string, EZ_LEVEL_NOTICE, $label );
    }

    /*!
      \static
      Writes a debug warning.
    */
    function writeWarning( $string, $label="" )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        if ( !eZDebug::showMessage( EZ_SHOW_WARNING ) )
            return;
        if ( is_object( $string ) || is_array( $string ) )
            $string =& eZDebug::dumpVariable( $string );

        $debug =& eZDebug::instance();
        if ( $debug->HandleType == EZ_HANDLE_TO_PHP )
            trigger_error( $string, E_USER_WARNING );
        else
            $debug->write( $string, EZ_LEVEL_WARNING, $label );
    }

    /*!
      \static
      Writes a debug error.
    */
    function writeError( $string, $label="" )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        if ( !eZDebug::showMessage( EZ_SHOW_ERROR ) )
            return;
        if ( is_object( $string ) || is_array( $string ) )
            $string =& eZDebug::dumpVariable( $string );

        $debug =& eZDebug::instance();
        if ( $debug->HandleType == EZ_HANDLE_TO_PHP )
            trigger_error( $string, E_USER_ERROR );
        else
            $debug->write( $string, EZ_LEVEL_ERROR, $label );
    }

    /*!
      \static
      Writes a debug message.
    */
    function writeDebug( $string, $label="" )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        if ( !eZDebug::showMessage( EZ_SHOW_ERROR ) )
            return;
        if ( is_object( $string ) || is_array( $string ) )
            $string =& eZDebug::dumpVariable( $string );

        $debug =& eZDebug::instance();
        if ( $debug->HandleType == EZ_HANDLE_TO_PHP )
            trigger_error( $string, E_USER_NOTICE );
        else
            $debug->write( $string, EZ_LEVEL_DEBUG, $label );
    }

    /*!
      \static
      \private
      Dumps the variables contents using the dump_var function
    */
    function &dumpVariable( $var )
    {
        // save the buffer contents
        $variableContents = "";
        $buffer =& ob_get_contents();
        if ( strlen( $buffer ) > 0 )
            ob_end_clean();

        ob_start();
        var_dump( $var );
        $variableContents .= ob_get_contents();
        ob_end_clean();

        // fill the buffer with the old values
        ob_start();
        print( $buffer );

        return $variableContents;
    }

    /*!
     Enables/disables the use of external CSS. If false a <style> tag is output
     before the debug list. Default is to use internal css.
    */
    function setUseExternalCSS( $use )
    {
        if ( !isset( $this ) or
             get_class( $this ) != "ezdebug" )
            $this =& eZDebug::instance();
        $this->UseCSS = $use;
    }

    /*!
     Determines the way messages are output, the \a $output parameter
     is EZ_OUTPUT_MESSAGE_SCREEN and EZ_OUTPUT_MESSAGE_STORE ored together.
    */
    function setMessageOutput( $output )
    {
        if ( !isset( $this ) or
             get_class( $this ) != "ezdebug" )
            $this =& eZDebug::instance();
        $this->MessageOutput = $output;
    }

    /*!
    */
    function setStoreLog( $store )
    {
        if ( !isset( $this ) or
             get_class( $this ) != "ezdebug" )
            $this =& eZDebug::instance();
        $this->StoreLog = $store;
    }

    /*!
      Adds a new timing point for the benchmark report.
    */
    function addTimingPoint( $description = "" )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        if ( !eZDebug::showMessage( EZ_SHOW_TIMING_POINT ) )
            return;
        $debug =& eZDebug::instance();

        $time = microtime();
        $tp = array( "Time" => $time,
                     "Description" => $description );
        $debug->TimePoints[] = $tp;
        $desc = "Timing Point: $description";
        foreach ( array( EZ_LEVEL_NOTICE, EZ_LEVEL_WARNING, EZ_LEVEL_ERROR, EZ_LEVEL_DEBUG ) as $lvl )
        {
            if ( isset( $debug->TmpTimePoints[$lvl] ) )
                $debug->TmpTimePoints[$lvl] = array();
            if ( $debug->TmpTimePoints[$lvl] === false and
                 $debug->isLogFileEnabled( $lvl ) )
            {
                $files =& $debug->logFiles();
                $file = $files[$lvl];
                $debug->writeFile( $file, $desc, $lvl );
            }
            else
                array_push( $debug->TmpTimePoints[$lvl],  $tp );
        }
        $debug->write( $description, EZ_LEVEL_TIMING_POINT );
    }

    /*!
      Writes a debug log message.
    */
    function write( $string, $verbosityLevel = EZ_LEVEL_NOTICE, $label="" )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        switch ( $verbosityLevel )
        {
            case EZ_LEVEL_NOTICE:
            case EZ_LEVEL_WARNING:
            case EZ_LEVEL_ERROR:
            case EZ_LEVEL_DEBUG:
            case EZ_LEVEL_TIMING_POINT:
                break;

            default:
                $verbosityLevel = EZ_LEVEL_ERROR;
            break;
        }
        if ( $this->MessageOutput & EZ_OUTPUT_MESSAGE_SCREEN )
        {
            print( "$verbosityLevel: $string ($label)\n" );
        }
        $files =& $this->logFiles();
        $fileName = false;
        if ( isset( $files[$verbosityLevel] ) )
            $fileName = $files[$verbosityLevel];
        if ( $this->MessageOutput & EZ_OUTPUT_MESSAGE_STORE )
        {
            $this->DebugStrings[] = array( "Level" => $verbosityLevel,
                                           "IP" => eZSys::serverVariable( 'REMOTE_ADDR', true ),
                                           "Time" => time(),
                                           "Label" => $label,
                                           "String" => $string );

            if ( $fileName !== false )
            {
                $timePoints = $this->TmpTimePoints[$verbosityLevel];
                if ( is_array( $timePoints ) )
                {
                    if ( $this->isLogFileEnabled( $verbosityLevel ) )
                    {
                        foreach ( $timePoints as $tp )
                        {
                            $desc = "Timing Point: " . $tp["Description"];
                            if ( $this->isLogFileEnabled( $verbosityLevel ) )
                            {
                                $this->writeFile( $fileName, $desc, $verbosityLevel );
                            }
                        }
                    }
                    $this->TmpTimePoints[$verbosityLevel] = false;
                }
                if ( $this->isLogFileEnabled( $verbosityLevel ) )
                {
                    $this->writeFile( $fileName, $string, $verbosityLevel );
                }
            }
        }
    }

    /*!
     \private
     Writes the log message $string to the file $fileName.
    */
    function writeFile( &$logFileData, &$string, $verbosityLevel )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        if ( !$this->isLogFileEnabled( $verbosityLevel ) )
            return;
        $logDir = $logFileData[0];
        $logName = $logFileData[1];
        $fileName = $logDir . $logName;
        if ( !file_exists( $logDir ) )
        {
            include_once( 'lib/ezutils/classes/ezdir.php' );
            eZDir::mkdir( $logDir, 0775, true );
        }
        $oldumask = @umask( 0 );
        $fileExisted = @file_exists( $fileName );
        $logFile = @fopen( $fileName, "a" );
        if ( $logFile )
        {
            $time = strftime( "%b %d %Y %H:%M:%S", strtotime( "now" ) );
            $notice = "[ " . $time . " ] [" . eZSys::serverVariable( 'REMOTE_ADDR', true ) . "] " . $string . "\n";
            @fwrite( $logFile, $notice );
            @fclose( $logFile );
            if ( !$fileExisted )
                @chmod( $fileName, 0664 );
            @umask( $oldumask );
        }
        else
        {
            @umask( $oldumask );
            $logEnabled = $this->isLogFileEnabled( $verbosityLevel );
            $this->setLogFileEnabled( false, $verbosityLevel );
            if ( $verbosityLevel != EZ_LEVEL_ERROR or
                 $logEnabled )
            {
                $this->writeError( "Cannot open log file '$fileName' for writing\n" .
                                   "The web server must be allowed to modify the file.\n" .
                                   "File logging for '$fileName' is disabled." , 'eZDebug::writeFile' );
            }
        }
    }

    /*!
     Enables or disables logging to file for a given message type.
     If \a $types is not supplied it will do the operation for all types.
    */
    function setLogFileEnabled( $enabled, $types = false )
    {
        if ( $types === false )
            $types =& $this->messageTypes();
        if ( !is_array( $types ) )
            $types = array( $types );
        foreach ( $types as $type )
        {
            $this->LogFileEnabled[$type] = $enabled;
        }
    }

    /*!
     \return true if the message type \a $type has logging to file enabled.
    */
    function isLogFileEnabled( $type )
    {
        return $this->LogFileEnabled[$type];
    }

    /*!
     \return an array with the available message types.
    */
    function messageTypes()
    {
        return $this->MessageTypes;
    }

    /*!
     Returns an associative array of all the log files used by this class
     where each key is the debug level (EZ_LEVEL_NOTICE, EZ_LEVEL_WARNING or EZ_LEVEL_ERROR or EZ_LEVEL_DEBUG).
    */
    function &logFiles()
    {
        return $this->LogFiles;
    }

    /*!
     \static
     \return true if debug should be enabled.
    */
    function isDebugEnabled()
    {
        $debugEnabled =& $GLOBALS['eZDebugEnabled'];
        if ( isset( $debugEnabled ) )
            return $debugEnabled;
        if ( !eZINI::isLoaded() )
            return true;
        $ini =& eZINI::instance();

        $debugIPArray = $ini->variableArray( "DebugSettings", "DebugIP" );

        $debugEnabled = true;

        if ( !in_array( "enabled", $debugIPArray ) )
        {
            if ( in_array( eZSys::serverVariable( 'REMOTE_ADDR', true ), $debugIPArray ) )
            {
            }
            else if ( in_array( "disabled", $debugIPArray ) )
            {
                $debugEnabled = false;
                $debug =& eZDebug::instance();
                $debug->reset();
            }
        }
        return $debugEnabled;
    }

    /*!
      \static
      Prints the debug report
    */
    function &printReport( $newWindow = false, $as_html = true, $returnReport = false )
    {
        if ( !eZDebug::isDebugEnabled() )
            return null;

        $debug =& eZDebug::instance();
        $report =& $debug->printReportInternal( $as_html );


        if ( $newWindow == true )
        {
            print( "
<SCRIPT LANGUAGE='JavaScript'>
<!-- hide this script from old browsers

function showDebug( fileName, title )
{
  debugWindow = window.open( '/cache/debug.html', 'ezdebug', 'width=500,height=550,status,scrollbars,resizable,screenX=0,screenY=20,left=20,top=40');
  debugWindow.document.close();
  debugWindow.location.reload();
}

showDebug();

ezdebug.reload();


// done hiding from old browsers -->
</SCRIPT>
" );
            $header = "<html><head><title>eZ debug</title></head><body>";
            $footer = "</body></html>";
            $fp = fopen( "cache/debug.html", "w+");

            fwrite( $fp, $header );
            fwrite( $fp, $report );
            fwrite( $fp, $footer );
            fclose( $fp );
        }
        else
        {
            if ( !$returnReport )
                print( $report );
            else
                return $report;
        }
        return null;
    }

    /*!
      \private
     Returns the microtime as a float value. $mtime must be in microtime() format.
    */
    function &timeToFloat( $mtime )
    {
        $tTime = explode( " ", $mtime );
        ereg( "0\.([0-9]+)", "" . $tTime[0], $t1 );
        $time = $tTime[1] . "." . $t1[1];
        return $time;
    }

    /*!
     Sets the time of the start of the script ot \a $mtime.
     If \a $mtime is not supplied it gets the current \c microtime().
     This is used to calculate total execution time and percentages.
    */
    function setScriptStart( $mtime = false )
    {
        if ( $mtime == false )
            $mtime = microtime();
        $time = eZDebug::timeToFloat( microtime() );
        $debug =& eZDebug::instance();
        $debug->ScriptStart = $time;
    }

    /*!
      Creates an accumulator group with key \a $key and group name \a $name.
      If \a $name is not supplied name is taken from \a $key.
    */
    function createAccumulatorGroup( $key, $name = false )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        if ( $name == '' or
             $name === false )
            $name = $key;
        $debug =& eZDebug::instance();
        if ( !array_key_exists( $key, $debug->TimeAccumulatorList ) )
            $debug->TimeAccumulatorList[$key] = array( 'name' => $name,  'time' => 0, 'count' => 0, 'is_group' => true, 'in_group' => false );
        if ( !array_key_exists( $key, $debug->TimeAccumulatorGroupList ) )
            $debug->TimeAccumulatorGroupList[$key] = array();
    }

    /*!
     Creates a new accumulator entry if one does not already exist and initializes with default data.
     If \a $name is not supplied name is taken from \a $key.
     If \a $inGroup is supplied it will place the accumulator under the specified group.
    */
    function createAccumulator( $key, $inGroup = false, $name = false )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        if ( $name == '' or
             $name === false )
            $name = $key;
        $debug =& eZDebug::instance();
        $isGroup = false;
        if ( array_key_exists( $key, $debug->TimeAccumulatorList ) and
             array_key_exists( $key, $debug->TimeAccumulatorGroupList ) )
            $isGroup = true;
        $debug->TimeAccumulatorList[$key] = array( 'name' => $name,  'time' => 0, 'count' => 0, 'is_group' => $isGroup, 'in_group' => $inGroup );
        if ( $inGroup !== false )
        {
            $groupKeys = array();
            if ( array_key_exists( $inGroup, $debug->TimeAccumulatorGroupList ) )
                $groupKeys = $debug->TimeAccumulatorGroupList[$inGroup];
            $debug->TimeAccumulatorGroupList[$inGroup] = array_unique( array_merge( $groupKeys, array( $key ) ) );
            if ( array_key_exists( $inGroup, $debug->TimeAccumulatorList ) )
                $debug->TimeAccumulatorList[$inGroup]['is_group'] = true;
        }
    }

    /*!
     Starts an time count for the accumulator \a $key.
     You can also specify a name which will be displayed.
    */
    function accumulatorStart( $key, $inGroup = false, $name = false )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        $debug =& eZDebug::instance();
        if ( ! array_key_exists( $key, $debug->TimeAccumulatorList ) )
        {
            $debug->createAccumulator( $key, $inGroup, $name );
        }

        $accumulator =& $debug->TimeAccumulatorList[$key];
        $accumulator['temp_time'] = $debug->timeToFloat( microtime() );
    }

    /*!
     Stops a previous time count and adds the total time to the accumulator \a $key.
    */
    function accumulatorStop( $key )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        $debug =& eZDebug::instance();
        $stopTime = $debug->timeToFloat( microtime() );
        if ( ! array_key_exists( $key, $debug->TimeAccumulatorList ) )
        {
            eZDebug::writeWarning( 'Accumulator $key does not exists, run eZDebug::accumulatorStart first', 'eZDebug::accumulatorStop' );
            return;
        }
        $accumulator =& $debug->TimeAccumulatorList[$key];
        $diffTime = $stopTime - $accumulator['temp_time'];
        $accumulator['time'] = $accumulator['time'] + $diffTime;
        ++$accumulator['count'];
    }


    /*!
      \private
      Prints a full debug report with notice, warnings, errors and a timing report.
    */
    function &printReportInternal( $as_html = true )
    {
        $endTime = microtime();
        $returnText = "";
        if ( $as_html )
        {
            $returnText .= "<table style='border: 1px dashed black;' bgcolor=\"#fefefe\">";
            $returnText .= "<tr><th><h1>eZ debug</h1></th></tr>";
            $returnText .= "<tr><td>";

            if ( !$this->UseCSS )
            {
                $returnText .= "<STYLE TYPE='text/css'>
                <!--
td.debugheader
\{
    background-color : #eeeeee;
    border-top : 1px solid #444488;
    border-bottom : 1px solid #444488;
    font-size : 65%;
    font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
\}

td.timingpoint1
\{
	background-color : #ffffff;
	border-top : 1px solid #444488;
	font-size : 65%;
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
\}

td.timingpoint2
\{
	background-color : #eeeeee;
	border-top : 1px solid #444488;
	font-size : 65%;
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
\}

-->
</STYLE>";
            }
            $returnText .= "<table style='border: 1px light gray;' cellspacing='0'>";
        }

        foreach ( $this->DebugStrings as $debug )
        {
            $time = strftime ("%b %d %Y %H:%M:%S", strtotime( "now" ) );

            $outputData = $this->OutputFormat[$debug["Level"]];
            if ( is_array( $outputData ) )
            {
                $color = $outputData["color"];
                $name = $outputData["name"];
                $label = $debug["Label"];
                if ( $as_html )
                {
                    $returnText .= "<tr><td class='debugheader' valign='top'><b><font color=\"$color\">$name:</font> $label</b></td>
                                    <td class='debugheader' valign='top'>$time</td></tr>
                                    <tr><td colspan='2'><pre>" .  htmlspecialchars( $debug["String"] )  . "</pre></td></tr>";
                }
                else
                {
                    $returnText .= "$name: ($label)\n" . $debug["String"] . "\n\n";
                }
            }
        }
        if ( $as_html )
        {
            $returnText .= "</table>";

            $returnText .= "<h2>Timing points:</h2>";
            $returnText .= "<table style='border: 1px dashed black;' cellspacing='0'><tr><th>Checkpoint</th><th>Elapsed</th><th>Rel. Elapsed</th></tr>";
        }
        $startTime = false;
        $elapsed = 0.00;
        $relElapsed = 0.00;
        for ( $i = 0; $i < count( $this->TimePoints ); ++$i )
        {
            $point = $this->TimePoints[$i];
            $nextPoint = false;
            if ( isset( $this->TimePoints[$i + 1] ) )
               $nextPoint = $this->TimePoints[$i + 1];
            $time = $this->timeToFloat( $point["Time"] );
            $nextTime = false;
            if ( $nextPoint !== false )
                $nextTime = $this->timeToFloat( $nextPoint["Time"] );
            if ( $startTime === false )
                $startTime = $time;
            $elapsed = $time - $startTime;
            $relElapsed = $nextTime - $time;

            if ( $i % 2 == 0 )
               $class = "timingpoint1";
            else
               $class = "timingpoint2";

            if ( $as_html )
            {
                $returnText .= "<tr><td class='$class'>" . $point["Description"] . "</td><td class='$class'>" .
                               number_format( ( $elapsed ), $this->TimingAccuracy ) . " sec</td><td class='$class'>".
                               ( empty( $nextPoint ) ? "&nbsp;" : number_format( ( $relElapsed ), $this->TimingAccuracy ) . " sec" ) . "</td>"
                               . "</tr>";
            }
            else
            {
                $returnText .= $point["Description"] .
                               number_format( ( $elapsed ), $this->TimingAccuracy ) . " sec".
                               ( empty( $nextPoint ) ? "" : number_format( ( $relElapsed ), $this->TimingAccuracy ) . " sec" ) . "\n";
            }
        }

        if ( count( $this->TimePoints ) > 0 )
        {
            $tTime = explode( " ", $endTime );
            ereg( "0\.([0-9]+)", "" . $tTime[0], $t1 );
            $endTime = $tTime[1] . "." . $t1[1];

            $totalElapsed = $endTime - $startTime;

            if ( $as_html )
            {
                $returnText .= "<tr><td><b>Total runtime:</b></td><td><b>" .
                       number_format( ( $totalElapsed ), $this->TimingAccuracy ) . " sec</b></td><td></td></tr>";
            }
            else
            {
                $returnText .= "Total runtime: " .
                       number_format( ( $totalElapsed ), $this->TimingAccuracy ) . " sec\n";
            }
        }
        else
        {
            if ( $as_html )
                $returnText .= "<tr><td> No timing points defined</td><td>";
            else
                $returnText .= "No timing points defined\n";
        }
        if ( $as_html )
        {
            $returnText .= "</table>";


        }

        if ( $as_html )
        {
            $returnText .= "<h2>Time accumulators:</h2>";
            $returnText .= "<table style='border: 1px dashed black;' cellspacing='0'><tr><th>&nbsp;Accumulator</th><th>&nbsp;Elapsed</th><th>&nbsp;Percent</th><th>&nbsp;Count</th><th>&nbsp;Average</tr>";
            $i = 0;
        }

        $scriptEndTime = eZDebug::timeToFloat( microtime() );
        $totalElapsed = $scriptEndTime - $this->ScriptStart;
        $timeList = $this->TimeAccumulatorList;
        $groups = $this->TimeAccumulatorGroupList;
        $groupList = array();
        foreach ( $groups as $groupKey => $keyList )
        {
            if ( count( $keyList ) == 0 and
                 !array_key_exists( $groupKey, $timeList ) )
                continue;
            $groupList[$groupKey] = array( 'name' => $groupKey );
            if ( array_key_exists( $groupKey, $timeList ) )
            {
                if ( $timeList[$groupKey]['time'] != 0 )
                    $groupList[$groupKey]['time_data'] = $timeList[$groupKey];
                $groupList[$groupKey]['name'] = $timeList[$groupKey]['name'];
                unset( $timeList[$groupKey] );
            }
            $groupChildren = array();
            foreach ( $keyList as $timeKey )
            {
                if ( array_key_exists( $timeKey, $timeList ) )
                {
                    $groupChildren[] = $timeList[$timeKey];
                    unset( $timeList[$timeKey] );
                }
            }
            $groupList[$groupKey]['children'] = $groupChildren;
        }
        if ( count( $timeList ) > 0 )
        {
            $groupList['general'] = array( 'name' => 'General',
                                           'children' => $timeList );
        }

        $j = 0;
        foreach ( $groupList as $group )
        {
            if ( $j % 2 == 0 )
               $class = "timingpoint1";
            else
               $class = "timingpoint2";
            ++$j;
            $groupName = $group['name'];
            $groupChildren = $group['children'];
            if ( count( $groupChildren ) == 0 and
                 !array_key_exists( 'time_data', $group ) )
                continue;
            if ( $as_html )
                $returnText .= "<tr><td class='$class'><b>$groupName</b></td>";
            else
                $returnText .= "Group $groupName: ";
            if ( array_key_exists( 'time_data', $group ) )
            {
                $groupData = $group['time_data'];
                $groupElapsed = number_format( ( $groupData['time'] ), $this->TimingAccuracy );
                $groupPercent = number_format( ( $groupData['time'] * 100.0 ) / $totalElapsed, 1 );
                $groupCount = $groupData['count'];
                $groupAverage = number_format( ( $groupData['time'] / $groupData['count'] ), $this->TimingAccuracy );
                if ( $as_html )
                {
                    $returnText .= ( "<td class=\"$class\">$groupElapsed sec</td>".
                                     "<td class=\"$class\" align=\"right\"> $groupPercent%</td>".
                                     "<td class=\"$class\" align=\"right\"> $groupCount</td>".
                                     "<td class=\"$class\" align=\"right\"> $groupAverage sec</td>" );
                }
                else
                {
                    $returnText .= "$groupElapsed sec ($groupPercent%), $groupAverage avg sec ($groupCount)";
                }
            }
            else if ( $as_html )
            {
                    $returnText .= ( "<td class=\"$class\"></td>".
                                     "<td class=\"$class\"></td>".
                                     "<td class=\"$class\"></td>".
                                     "<td class=\"$class\"></td>" );
            }
            if ( $as_html )
                $returnText .= "</tr>";
            else
                $returnText .= "\n";

            $i = 0;
            foreach ( $groupChildren as $child )
            {
                $childName = $child['name'];
                $childElapsed = number_format( ( $child['time'] ), $this->TimingAccuracy );
                $childPercent = number_format( ( $child['time'] * 100.0 ) / $totalElapsed, $this->PercentAccuracy );
                $childCount = $child['count'];
                $childAverage = 0.0;
                if ( $childCount > 0 )
                {
                    $childAverage = $child['time'] / $childCount;
                }
                $childAverage = number_format( $childAverage, $this->PercentAccuracy );

                if ( $as_html )
                {
                    if ( $i % 2 == 0 )
                        $class = "timingpoint1";
                    else
                        $class = "timingpoint2";
                    ++$i;

                    $returnText .= ( "<tr>" .
                                     "<td class=\"$class\">$childName</td>" .
                                     "<td class=\"$class\">$childElapsed sec</td>" .
                                     "<td class=\"$class\" align=\"right\">$childPercent%</td>" .
                                     "<td class=\"$class\" align=\"right\">$childCount</td>" .
                                     "<td class=\"$class\" align=\"right\">$childAverage sec</td>" .
                                     "</tr>" );
                }
                else
                {
                    $returnText .= "$childName: $childElapsed sec ($childPercent%), $childAverage avg sec ($childCount)";
                }
            }
        }
        if ( $as_html )
        {
            $returnText .= "<tr><td><b>Total script time:</b></td><td><b>" . number_format( ( $totalElapsed ), $this->TimingAccuracy ) . " sec</b></td><td></td></tr>";
        }
        else
        {
            $returnText .= "Total script time: " . number_format( ( $totalElapsed ), $this->TimingAccuracy ) . " sec\n";
        }
        if ( $as_html )
        {
            $returnText .= "</table>";
            $returnText .= "</td></tr></table>";
        }

        return $returnText;
    }



    /// \privatesection
    /// String array containing the debug information
    var $DebugStrings = array();

    /// Array which contains the time points
    var $TimePoints = array();

    /// Array which contains the temporary time points
    var $TmpTimePoints;

    /// Array wich contains time accumulators
    var $TimeAccumulatorList = array();

    /// Determines which debug messages should be shown
    var $ShowTypes;

    /// Determines what to do with php errors, ignore, fetch or output
    var $HandleType;

    /// An array of the outputformats for the different debug levels
    var $OutputFormat;

    /// An array of logfiles used by the debug class with each key being the debug level
    var $LogFiles;

    /// How many places behing . should be displayed when showing times
    var $TimingAccuracy = 4;

    /// How many places behing . should be displayed when showing percentages
    var $PercentAccuracy = 4;

    /// Whether to use external CSS or output own CSS. True if external is to be used.
    var $UseCSS;

    /// Determines how messages are output (screen/log)
    var $MessageOutput;

    /// A list of message types
    var $MessageTypes;

    /// A map with message types and whether they should do file logging.
    var $LogFileEnabled;

    /// The time when the script was started
    var $ScriptStart;
}

/*!
  Helper function for eZDebug, called whenever a PHP error occurs.
  The error is then handled by the eZDebug class.
*/

function eZDebugErrorHandler( $errno, $errstr, $errfile, $errline )
{
    $debug =& eZDebug::instance();
    $debug->errorHandler( $errno, $errstr, $errfile, $errline );
}

?>
