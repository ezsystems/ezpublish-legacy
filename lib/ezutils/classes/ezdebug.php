<?php
//
// Definition of eZDebug class
//
// Created on: <12-Feb-2002 11:00:54 bf>
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
  require_once( "lib/ezutils/classes/ezdebug.php" );

  // write a temporary debug message
  eZDebug::writeDebug( "Test" );

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

//include_once( "lib/ezutils/classes/ezsys.php" );

class eZDebug
{
    const LEVEL_NOTICE = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_ERROR = 3;
    const LEVEL_TIMING_POINT = 4;
    const LEVEL_DEBUG = 5;
    const LEVEL_STRICT = 6;

    const SHOW_NOTICE = 1; // 1 << (EZ_LEVEL_NOTICE - 1)
    const SHOW_WARNING = 2; // 1 << (EZ_LEVEL_WARNING - 1)
    const SHOW_ERROR = 4; // 1 << (EZ_LEVEL_ERROR - 1)
    const SHOW_TIMING_POINT = 8; // 1 << (EZ_LEVEL_TIMING_POINT - 1)
    const SHOW_DEBUG = 16; // 1 << (EZ_LEVEL_DEBUG - 1)
    const SHOW_STRICT = 32; // 1 << (EZ_LEVEL_STRICT - 1)
    const SHOW_ALL = 63; // EZ_SHOW_NOTICE | EZ_SHOW_WARNING | EZ_SHOW_ERROR | EZ_SHOW_TIMING_POINT | EZ_SHOW_DEBUG | EZ_SHOW_STRICT

    const HANDLE_NONE = 0;
    const HANDLE_FROM_PHP = 1;
    const HANDLE_TO_PHP = 2;

    const OUTPUT_MESSAGE_SCREEN = 1;
    const OUTPUT_MESSAGE_STORE = 2;

    const MAX_LOGFILE_SIZE = 204800; // 200*1024
    const MAX_LOGROTATE_FILES = 3;

    const XDEBUG_SIGNATURE = '--XDEBUG--';

    /*!
      Creates a new debug object.
    */
    function __construct( )
    {
        $this->TmpTimePoints = array( self::LEVEL_NOTICE => array(),
                                      self::LEVEL_WARNING => array(),
                                      self::LEVEL_ERROR => array(),
                                      self::LEVEL_DEBUG => array(),
                                      self::LEVEL_STRICT => array() );

        $this->OutputFormat = array( self::LEVEL_NOTICE => array( "color" => "green",
                                                               'style' => 'notice',
                                                               'xhtml-identifier' => 'ezdebug-first-notice',
                                                               "name" => "Notice" ),
                                     self::LEVEL_WARNING => array( "color" => "orange",
                                                                'style' => 'warning',
                                                                'xhtml-identifier' => 'ezdebug-first-warning',
                                                                "name" => "Warning" ),
                                     self::LEVEL_ERROR => array( "color" => "red",
                                                              'style' => 'error',
                                                              'xhtml-identifier' => 'ezdebug-first-error',
                                                              "name" => "Error" ),
                                     self::LEVEL_DEBUG => array( "color" => "brown",
                                                              'style' => 'debug',
                                                              'xhtml-identifier' => 'ezdebug-first-debug',
                                                              "name" => "Debug" ),
                                     self::LEVEL_TIMING_POINT => array( "color" => "blue",
                                                                     'style' => 'timing',
                                                                     'xhtml-identifier' => 'ezdebug-first-timing-point',
                                                                     "name" => "Timing" ),
                                     self::LEVEL_STRICT => array( "color" => "purple",
                                                              'style' => 'strict',
                                                              'xhtml-identifier' => 'ezdebug-first-strict',
                                                              'name' => 'Strict' ) );
        $this->LogFiles = array( self::LEVEL_NOTICE => array( "var/log/",
                                                           "notice.log" ),
                                 self::LEVEL_WARNING => array( "var/log/",
                                                            "warning.log" ),
                                 self::LEVEL_ERROR => array( "var/log/",
                                                          "error.log" ),
                                 self::LEVEL_DEBUG => array( "var/log/",
                                                          "debug.log" ),
                                 self::LEVEL_STRICT => array( 'var/log/',
                                                           'strict.log' ) );
        $this->MessageTypes = array( self::LEVEL_NOTICE,
                                     self::LEVEL_WARNING,
                                     self::LEVEL_ERROR,
                                     self::LEVEL_TIMING_POINT,
                                     self::LEVEL_DEBUG,
                                     self::LEVEL_STRICT );
        $this->MessageNames = array( self::LEVEL_NOTICE => 'Notice',
                                     self::LEVEL_WARNING => 'Warning',
                                     self::LEVEL_ERROR => 'Error',
                                     self::LEVEL_TIMING_POINT => 'TimingPoint',
                                     self::LEVEL_DEBUG => 'Debug',
                                     self::LEVEL_STRICT => 'Strict' );
        $this->LogFileEnabled = array( self::LEVEL_NOTICE => true,
                                       self::LEVEL_WARNING => true,
                                       self::LEVEL_ERROR => true,
                                       self::LEVEL_TIMING_POINT => true,
                                       self::LEVEL_DEBUG => true,
                                       self::LEVEL_STRICT => true );
        $this->AlwaysLog = array( self::LEVEL_NOTICE => false,
                                  self::LEVEL_WARNING => false,
                                  self::LEVEL_ERROR => true, // Error is on by default, due to its importance
                                  self::LEVEL_TIMING_POINT => false,
                                  self::LEVEL_DEBUG => false,
                                  self::LEVEL_STRICT => false );
        $this->GlobalLogFileEnabled = true;
        if ( isset( $GLOBALS['eZDebugLogFileEnabled'] ) )
        {
            $this->GlobalLogFileEnabled = $GLOBALS['eZDebugLogFileEnabled'];
        }
        $this->ShowTypes = self::SHOW_ALL;
        $this->HandleType = self::HANDLE_NONE;
        $this->OldHandler = false;
        $this->UseCSS = false;
        $this->MessageOutput = self::OUTPUT_MESSAGE_STORE;
        $this->ScriptStart = microtime( true );
        $this->TimeAccumulatorList = array();
        $this->TimeAccumulatorGroupList = array();
        $this->OverrideList = array();
        $this->topReportsList = array();
        $this->bottomReportsList = array();
    }

    function reset()
    {
        $this->DebugStrings = array();
        $this->TmpTimePoints = array( self::LEVEL_NOTICE => array(),
                                      self::LEVEL_WARNING => array(),
                                      self::LEVEL_ERROR => array(),
                                      self::LEVEL_DEBUG => array(),
                                      self::LEVEL_STRICT => array() );
        $this->TimeAccumulatorList = array();
        $this->TimeAccumulatorGroupList = array();
        $this->topReportsList = array();
        $this->bottomReportsList = array();
    }

    /*!
     \return the name of the message type.
    */
    function messageName( $messageType )
    {
        return $this->MessageNames[$messageType];
    }

    /*!
      Will return the current eZDebug object. If no object exists one will
      be created.
    */
    static function instance( )
    {
        if ( empty( $GLOBALS["eZDebugGlobalInstance"] ) )
        {
            $GLOBALS["eZDebugGlobalInstance"] = new eZDebug();
        }
        return $GLOBALS["eZDebugGlobalInstance"];
    }

    /*!
     \static
     Returns true if the message type $type can be shown.
    */
    static function showMessage( $type )
    {
        $debug = eZDebug::instance();
        return $debug->ShowTypes & $type;
    }

    /*!
     \return \c true if the debug level \a $level should always log to file.
    */
    static function alwaysLogMessage( $level )
    {
        $instance = eZDebug::instance();
        // If there is a global setting for this get the value
        // and unset it globally
        if ( isset( $GLOBALS['eZDebugAlwaysLog'] ) )
        {
            $instance->AlwaysLog = $GLOBALS['eZDebugAlwaysLog'] + $instance->AlwaysLog;
            unset( $GLOBALS['eZDebugAlwaysLog'] );
        }

        if ( !isset( $instance->AlwaysLog[$level] ) )
        {
            return false;
        }
        return $instance->AlwaysLog[$level];
    }

    /*!
     Determines how PHP errors are handled. If $type is self::HANDLE_TO_PHP all error messages
     is sent to PHP using trigger_error(), if $type is self::HANDLE_FROM_PHP all error messages
     from PHP is fetched using a custom error handler and output as a usual eZDebug message.
     If $type is self::HANDLE_NONE there is no error exchange between PHP and eZDebug.
    */
    static function setHandleType( $type )
    {
        $instance = eZDebug::instance();

        if ( $type != self::HANDLE_TO_PHP and
             $type != self::HANDLE_FROM_PHP )
            $type = self::HANDLE_NONE;
        if ( extension_loaded( 'xdebug' ) and
             $type == self::HANDLE_FROM_PHP )
            $type = self::HANDLE_NONE;
        if ( $type == $instance->HandleType )
            return $instance->HandleType;

        if ( $instance->HandleType == self::HANDLE_FROM_PHP )
            restore_error_handler();
        switch ( $type )
        {
            case self::HANDLE_FROM_PHP:
            {
                set_error_handler( "eZDebugErrorHandler" );
            } break;

            case self::HANDLE_TO_PHP:
            {
                restore_error_handler();
            } break;

            case self::HANDLE_NONE:
            {
            }
        }
        $oldHandleType = $instance->HandleType;
        $instance->HandleType = $type;
        return $oldHandleType;
    }

    /*!
     \static
     Sets types to be shown to $types and returns the old show types.
     If $types is not supplied the current value is returned and no change is done.
     $types is one or more of self::SHOW_NOTICE, self::SHOW_WARNING, self::SHOW_ERROR, self::SHOW_TIMING_POINT
     or'ed together.
    */
    static function showTypes( $types = false )
    {
        $instance = eZDebug::instance();

        if ( $types === false )
        {
            return $instance->ShowTypes;
        }
        $old_types = $instance->ShowTypes;
        $instance->ShowTypes = $types;
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
        if ( empty( $GLOBALS["eZDebugPHPErrorNames"] ) )
        {
            $GLOBALS["eZDebugPHPErrorNames"] =
                array( E_ERROR => "E_ERROR",
                       E_PARSE => "E_PARSE",
                       E_CORE_ERROR => "E_CORE_ERROR",
                       E_COMPILE_ERROR => "E_COMPILE_ERROR",
                       E_USER_ERROR => "E_USER_ERROR",
                       E_WARNING => "E_WARNING",
                       E_CORE_WARNING => "E_CORE_WARNING",
                       E_COMPILE_WARNING => "E_COMPILE_WARNING",
                       E_USER_WARNING => "E_USER_WARNING",
                       E_NOTICE => "E_NOTICE",
                       E_USER_NOTICE => "E_USER_NOTICE",
                       E_STRICT => "E_STRICT" );
        }
        $errname = "unknown";
        if ( isset( $GLOBALS["eZDebugPHPErrorNames"][$errno] ) )
        {
            $errname = $GLOBALS["eZDebugPHPErrorNames"][$errno];
        }
        switch ( $errno )
        {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            {
                $this->writeError( $str, "PHP" );
            } break;

            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
            case E_NOTICE:
            {
                $this->writeWarning( $str, "PHP" );
            } break;

            case E_USER_NOTICE:
            {
                $this->writeNotice( $str, "PHP" );
            } break;

            case E_STRICT:
            {
                return $this->writeStrict( $str, "PHP" );
            } break;
        }
    }

    /*!
      \static
      Writes a strict debug message.

      The global variable \c 'eZDebugStrict' will be set to \c true if the notice is added.
      \param $label This label will be associated with the strict message, e.g. to say where the message came from.
      \param $backgroundClass A string defining the class to use in the HTML debug output.
    */
    static function writeStrict( $string, $label = "", $backgroundClass = "" )
    {
        $alwaysLog = eZDebug::alwaysLogMessage( self::LEVEL_STRICT );
        $enabled = eZDebug::isDebugEnabled();
        if ( !$alwaysLog and !$enabled )
            return;

        $show = eZDebug::showMessage( self::SHOW_STRICT );
        if ( !$alwaysLog and !$show )
            return;

        if ( is_object( $string ) || is_array( $string ) )
             $string = eZDebug::dumpVariable( $string );

        $GLOBALS['eZDebugStrict'] = true;
        if ( !isset( $GLOBALS['eZDebugStrictCount'] ) )
            $GLOBALS['eZDebugStrictCount'] = 0;
        ++$GLOBALS['eZDebugStrictCount'];

        $debug = eZDebug::instance();
        if ( $debug->HandleType == self::HANDLE_TO_PHP )
        {
            // If we get here only because of $alwaysLog we should not trigger a PHP error
            if ( $enabled and $show )
            {
                // we can't trigger E_STRICT but we can let the default error handler handle it
                // see http://www.php.net/manual/en/function.set-error-handler.php#69218
                return false;
            }
        }
        else
        {
            $debug->write( $string, self::LEVEL_STRICT, $label, $backgroundClass, $alwaysLog );
            return true;
        }
    }

    /*!
      \static
      Writes a debug notice.

      The global variable \c 'eZDebugNotice' will be set to \c true if the notice is added.
      \param $label This label will be associated with the notice, e.g. to say where the notice came from.
      \param $backgroundClass A string defining the class to use in the HTML debug output.
    */
    static function writeNotice( $string, $label = "", $backgroundClass = "" )
    {
        $alwaysLog = eZDebug::alwaysLogMessage( self::LEVEL_NOTICE );
        $enabled = eZDebug::isDebugEnabled();
        if ( !$alwaysLog and !$enabled )
            return;

        $show = eZDebug::showMessage( self::SHOW_NOTICE );
        if ( !$alwaysLog and !$show )
            return;

        if ( is_object( $string ) || is_array( $string ) )
             $string = eZDebug::dumpVariable( $string );

        $GLOBALS['eZDebugNotice'] = true;
        if ( !isset( $GLOBALS['eZDebugNoticeCount'] ) )
            $GLOBALS['eZDebugNoticeCount'] = 0;
        ++$GLOBALS['eZDebugNoticeCount'];

        $debug = eZDebug::instance();
        if ( $debug->HandleType == self::HANDLE_TO_PHP )
        {
            // If we get here only because of $alwaysLog we should not trigger a PHP error
            if ( $enabled and $show )
            {
                if ( $label )
                    $string = "$label: $string";
                trigger_error( $string, E_USER_NOTICE );
            }
        }
        else
        {
            $debug->write( $string, self::LEVEL_NOTICE, $label, $backgroundClass, $alwaysLog );
        }
    }

    /*!
      \static
      Writes a debug warning.

      The global variable \c 'eZDebugWarning' will be set to \c true if the notice is added.
      \param $label This label will be associated with the notice, e.g. to say where the notice came from.
    */
    static function writeWarning( $string, $label = "", $backgroundClass = "" )
    {
        $alwaysLog = eZDebug::alwaysLogMessage( self::LEVEL_WARNING );
        $enabled = eZDebug::isDebugEnabled();
        if ( !$alwaysLog and !$enabled )
            return;

        $show = eZDebug::showMessage( self::SHOW_WARNING );
        if ( !$alwaysLog and !$show )
            return;

        if ( is_object( $string ) || is_array( $string ) )
            $string = eZDebug::dumpVariable( $string );

        $GLOBALS['eZDebugWarning'] = true;
        if ( !isset( $GLOBALS['eZDebugWarningCount'] ) )
            $GLOBALS['eZDebugWarningCount'] = 0;
        ++$GLOBALS['eZDebugWarningCount'];

        $debug = eZDebug::instance();
        if ( $debug->HandleType == self::HANDLE_TO_PHP )
        {
            // If we get here only because of $alwaysLog we should not trigger a PHP error
            if ( $enabled and $show )
            {
                if ( $label )
                    $string = "$label: $string";
                trigger_error( $string, E_USER_WARNING );
            }
        }
        else
        {
            $debug->write( $string, self::LEVEL_WARNING, $label, $backgroundClass, $alwaysLog );
        }
    }

    /*!
      \static
      Writes a debug error.

      The global variable \c 'eZDebugError' will be set to \c true if the notice is added.
      \param $label This label will be associated with the notice, e.g. to say where the notice came from.
    */
    static function writeError( $string, $label = "", $backgroundClass = "" )
    {
        $alwaysLog = eZDebug::alwaysLogMessage( self::LEVEL_ERROR );
        $enabled = eZDebug::isDebugEnabled();
        if ( !$alwaysLog and !$enabled )
            return;

        $show = eZDebug::showMessage( self::SHOW_ERROR );
        if ( !$alwaysLog and !$show )
            return;

        if ( is_object( $string ) || is_array( $string ) )
            $string = eZDebug::dumpVariable( $string );

        $GLOBALS['eZDebugError'] = true;
        if ( !isset( $GLOBALS['eZDebugErrorCount'] ) )
            $GLOBALS['eZDebugErrorCount'] = 0;
        ++$GLOBALS['eZDebugErrorCount'];

        $debug = eZDebug::instance();
        if ( $debug->HandleType == self::HANDLE_TO_PHP )
        {
            // If we get here only because of $alwaysLog we should not trigger a PHP error
            if ( $enabled and $show )
            {
                if ( $label )
                    $string = "$label: $string";
                trigger_error( $string, E_USER_ERROR );
            }
        }
        else
        {
            $debug->write( $string, self::LEVEL_ERROR, $label, $backgroundClass, $alwaysLog );
        }
    }

    /*!
      \static
      Writes a debug message.

      The global variable \c 'eZDebugDebug' will be set to \c true if the notice is added.
      \param $label This label will be associated with the notice, e.g. to say where the notice came from.
    */
    static function writeDebug( $string, $label = "", $backgroundClass = "" )
    {
        $alwaysLog = eZDebug::alwaysLogMessage( self::LEVEL_DEBUG );
        $enabled = eZDebug::isDebugEnabled();
        if ( !$alwaysLog and !$enabled )
            return;

        $show = eZDebug::showMessage( self::SHOW_DEBUG );
        if ( !$alwaysLog and !$show )
            return;

        if ( is_object( $string ) || is_array( $string ) )
            $string = eZDebug::dumpVariable( $string );

        $GLOBALS['eZDebugDebug'] = true;
        if ( !isset( $GLOBALS['eZDebugDebugCount'] ) )
            $GLOBALS['eZDebugDebugCount'] = 0;
        ++$GLOBALS['eZDebugDebugCount'];

        $debug = eZDebug::instance();
        if ( $debug->HandleType == self::HANDLE_TO_PHP )
        {
            // If we get here only because of $alwaysLog we should not trigger a PHP error
            if ( $enabled and $show )
            {
                if ( $label )
                    $string = "$label: $string";
                trigger_error( $string, E_USER_NOTICE );
            }
        }
        else
        {
            $debug->write( $string, self::LEVEL_DEBUG, $label, $backgroundClass, $alwaysLog );
        }
    }

    /*!
      \static
      \private
      Dumps the variables contents using the var_dump function
    */
    static function dumpVariable( $var )
    {
        // If we have var_export (PHP >= 4.2.0) we use the instead
        // provides better output, doesn't require output buffering
        // and doesn't get mangled by Xdebug

        // dl: we should always use 'var_dump' since 'var_export' is
        // unable to handle recursion properly.
        //if ( function_exists( 'var_export' ) )
        //    return var_export( $var, true );

        ob_start();
        var_dump( $var );
        $variableContents = '';
        if ( extension_loaded( 'xdebug' ) )
           $variableContents = self::XDEBUG_SIGNATURE;
        $variableContents .= ob_get_contents();
        ob_end_clean();
        return $variableContents;
    }

    /*!
     Enables/disables the use of external CSS. If false a <style> tag is output
     before the debug list. Default is to use internal css.
    */
    static function setUseExternalCSS( $use )
    {
        eZDebug::instance()->UseCSS = $use;
    }

    /*!
     Determines the way messages are output, the \a $output parameter
     is self::OUTPUT_MESSAGE_SCREEN and self::OUTPUT_MESSAGE_STORE ored together.
    */
    function setMessageOutput( $output )
    {
        $this->MessageOutput = $output;
    }

    /*!
    */
    function setStoreLog( $store )
    {
        $this->StoreLog = $store;
    }

    /*!
      Adds a new timing point for the benchmark report.
    */
    static function addTimingPoint( $description = "" )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        if ( !eZDebug::showMessage( self::SHOW_TIMING_POINT ) )
            return;
        $debug = eZDebug::instance();

        $time = microtime( true );
        $usedMemory = 0;
        if ( function_exists( "memory_get_usage" ) )
            $usedMemory = memory_get_usage();
        $tp = array( "Time" => $time,
                     "Description" => $description,
                     "MemoryUsage" => $usedMemory );
        $debug->TimePoints[] = $tp;
        $desc = "Timing Point: $description";
        foreach ( array( self::LEVEL_NOTICE, self::LEVEL_WARNING, self::LEVEL_ERROR, self::LEVEL_DEBUG, self::LEVEL_STRICT ) as $lvl )
        {
            if ( isset( $debug->TmpTimePoints[$lvl] ) )
                $debug->TmpTimePoints[$lvl] = array();
            if ( $debug->TmpTimePoints[$lvl] === false and
                 $debug->isLogFileEnabled( $lvl ) )
            {
                $files = $debug->logFiles();
                $file = $files[$lvl];
                $debug->writeFile( $file, $desc, $lvl );
            }
            else
                array_push( $debug->TmpTimePoints[$lvl],  $tp );
        }
        $debug->write( $description, self::LEVEL_TIMING_POINT );
    }

    /*!
      \private
      Writes a debug log message.
    */
    function write( $string, $verbosityLevel = self::LEVEL_NOTICE, $label = "", $backgroundClass = "", $alwaysLog = false )
    {
        $enabled = eZDebug::isDebugEnabled();
        if ( !$alwaysLog and !$enabled )
            return;
        switch ( $verbosityLevel )
        {
            case self::LEVEL_NOTICE:
            case self::LEVEL_WARNING:
            case self::LEVEL_ERROR:
            case self::LEVEL_DEBUG:
            case self::LEVEL_TIMING_POINT:
            case self::LEVEL_STRICT:
                break;

            default:
                $verbosityLevel = self::LEVEL_ERROR;
            break;
        }
        if ( $this->MessageOutput & self::OUTPUT_MESSAGE_SCREEN and $enabled )
        {
            print( "$verbosityLevel: $string ($label)\n" );
        }
        $files = $this->logFiles();
        $fileName = false;
        if ( isset( $files[$verbosityLevel] ) )
            $fileName = $files[$verbosityLevel];
        if ( $this->MessageOutput & self::OUTPUT_MESSAGE_STORE or $alwaysLog )
        {
            if ( ! eZDebug::isLogOnlyEnabled() and $enabled )
            {
                $ip = eZSys::serverVariable( 'REMOTE_ADDR', true );
                if ( !$ip )
                    $ip = eZSys::serverVariable( 'HOSTNAME', true );
                $this->DebugStrings[] = array( "Level" => $verbosityLevel,
                                               "IP" => $ip,
                                               "Time" => time(),
                                               "Label" => $label,
                                               "String" => $string,
                                               "BackgroundClass" => $backgroundClass );
            }

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
                                $this->writeFile( $fileName, $desc, $verbosityLevel, $alwaysLog );
                            }
                        }
                    }
                    $this->TmpTimePoints[$verbosityLevel] = false;
                }
                if ( $this->isLogFileEnabled( $verbosityLevel ) )
                {
                    $string = "$label:\n$string";
                    $this->writeFile( $fileName, $string, $verbosityLevel, $alwaysLog );
                }
            }
        }
    }

    /*!
     \static
     \return the maxium size for a log file in bytes.
    */
    static function maxLogSize()
    {
        if ( isset( $GLOBALS['eZDebugMaxLogSize'] ) )
        {
            return $GLOBALS['eZDebugMaxLogSize'];
        }
        return self::MAX_LOGFILE_SIZE;
    }

    /*!
     \static
     Sets the maxium size for a log file to \a $size.
    */
    static function setMaxLogSize( $size )
    {
        $GLOBALS['eZDebugMaxLogSize'] = $size;
    }

    /*!
     \static
     \return the maxium number of logrotate files to keep.
    */
    static function maxLogrotateFiles()
    {
        if ( isset( $GLOBALS['eZDebugMaxLogrotateFiles'] ) )
        {
            return $GLOBALS['eZDebugMaxLogrotateFiles'];
        }
        return self::MAX_LOGROTATE_FILES;
    }

    /*!
     \static
     Sets the maxium number of logrotate files to keep to \a $files.
    */
    static function setLogrotateFiles( $files )
    {
        $GLOBALS['eZDebugMaxLogrotateFiles'] = $files;
    }

    /*!
     \static
     Rotates logfiles so the current logfile is backed up,
     old rotate logfiles are rotated once more and those that
     exceed maxLogrotateFiles() will be removed.
     Rotated files will get the extension .1, .2 etc.
    */
    static function rotateLog( $fileName )
    {
        $maxLogrotateFiles = eZDebug::maxLogrotateFiles();
        for ( $i = $maxLogrotateFiles; $i > 0; --$i )
        {
            $logRotateName = $fileName . '.' . $i;
            if ( @file_exists( $logRotateName ) )
            {
                if ( $i == $maxLogrotateFiles )
                {
                    @unlink( $logRotateName );
//                     print( "@unlink( $logRotateName )<br/>" );
                }
                else
                {
                    $newLogRotateName = $fileName . '.' . ($i + 1);
                    //include_once( 'lib/ezfile/classes/ezfile.php' );
                    eZFile::rename( $logRotateName, $newLogRotateName );
//                     print( "@rename( $logRotateName, $newLogRotateName )<br/>" );
                }
            }
        }
        if ( @file_exists( $fileName ) )
        {
            $newLogRotateName = $fileName . '.' . 1;
            //include_once( 'lib/ezfile/classes/ezfile.php' );
            eZFile::rename( $fileName, $newLogRotateName );
//             print( "@rename( $fileName, $newLogRotateName )<br/>" );
            return true;
        }
        return false;
    }

    /*!
     \private
     Writes the log message $string to the file $fileName.
    */
    function writeFile( &$logFileData, &$string, $verbosityLevel, $alwaysLog = false )
    {
        $enabled = eZDebug::isDebugEnabled();
        if ( !$alwaysLog and !$enabled )
            return;
        if ( !$alwaysLog and !$this->isLogFileEnabled( $verbosityLevel ) )
            return;
        $oldHandleType = eZDebug::setHandleType( self::HANDLE_TO_PHP );
        $logDir = $logFileData[0];
        $logName = $logFileData[1];
        $fileName = $logDir . $logName;
        if ( !file_exists( $logDir ) )
        {
            //include_once( 'lib/ezfile/classes/ezdir.php' );
            eZDir::mkdir( $logDir, false, true );
        }
        $oldumask = @umask( 0 );
        $fileExisted = @file_exists( $fileName );
        if ( $fileExisted and
             filesize( $fileName ) > eZDebug::maxLogSize() )
        {
            if ( eZDebug::rotateLog( $fileName ) )
                $fileExisted = false;
        }
        $logFile = @fopen( $fileName, "a" );
        if ( $logFile )
        {
            $time = strftime( "%b %d %Y %H:%M:%S", strtotime( "now" ) );
            $ip = eZSys::serverVariable( 'REMOTE_ADDR', true );
            if ( !$ip )
                $ip = eZSys::serverVariable( 'HOSTNAME', true );
            $notice = "[ " . $time . " ] [" . $ip . "] " . $string . "\n";
            @fwrite( $logFile, $notice );
            @fclose( $logFile );
            if ( !$fileExisted )
            {
                $ini = eZINI::instance();
                $permissions = octdec( $ini->variable( 'FileSettings', 'LogFilePermissions' ) );
                @chmod( $fileName, $permissions );
            }
            @umask( $oldumask );
        }
        else
        {
            @umask( $oldumask );
            $logEnabled = $this->isLogFileEnabled( $verbosityLevel );
            $this->setLogFileEnabled( false, $verbosityLevel );
            if ( $verbosityLevel != self::LEVEL_ERROR or
                 $logEnabled )
            {
                eZDebug::setHandleType( $oldHandleType );
                $this->writeError( "Cannot open log file '$fileName' for writing\n" .
                                   "The web server must be allowed to modify the file.\n" .
                                   "File logging for '$fileName' is disabled." , 'eZDebug::writeFile' );
            }
        }
        eZDebug::setHandleType( $oldHandleType );
    }

    /*!
     \static
     Enables or disables logging to file for a given message type.
     If \a $types is not supplied it will do the operation for all types.
    */
    static function setLogFileEnabled( $enabled, $types = false )
    {
        $instance = eZDebug::instance();
        if ( $types === false )
        {
            $types = $instance->messageTypes();
        }
        if ( !is_array( $types ) )
        {
            $types = array( $types );
        }
        foreach ( $types as $type )
        {
            $instance->LogFileEnabled[$type] = $enabled;
        }
    }

    /*!
     \return true if the message type \a $type has logging to file enabled.
     \sa isGlobalLogFileEnabled, setIsLogFileEnabled
    */
    function isLogFileEnabled( $type )
    {
        if ( !$this->isGlobalLogFileEnabled() )
            return false;
        return $this->LogFileEnabled[$type];
    }

    /*!
     \return true if the message type \a $type has logging to file enabled.
     \sa isLogFileEnabled, setIsGlobalLogFileEnabled
    */
    function isGlobalLogFileEnabled()
    {
        return $this->GlobalLogFileEnabled;
    }

    /*!
     Sets whether the logfile \a $type is enabled or disabled to \a $enabled.
     \sa isLogFileEnabled
    */
    function setIsLogFileEnabled( $type, $enabled )
    {
        $this->LogFileEnabled[$type] = $enabled;
    }

    /*!
     Sets whether logfiles are enabled or disabled globally. Sets the value to \a $enabled.
     \sa isLogFileEnabled, isGlobalLogFileEnabled
    */
    function setIsGlobalLogFileEnabled( $enabled )
    {
        $this->GlobalLogFileEnabled = $enabled;
    }

    /*!
     Sets whether debug output should be logged only
    */
    function setLogOnly( $enabled )
    {
        $GLOBALS['eZDebugLogOnly'] = $enabled;
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
     where each key is the debug level (self::LEVEL_NOTICE, self::LEVEL_WARNING or self::LEVEL_ERROR or self::LEVEL_DEBUG).
    */
    function logFiles()
    {
        return $this->LogFiles;
    }

    /*!
     \static
     \return true if debug should be enabled.
     \note Will return false until the real settings has been updated with updateSettings()
    */
    static function isDebugEnabled()
    {
        if ( isset( $GLOBALS['eZDebugEnabled'] ) )
        {
            return $GLOBALS['eZDebugEnabled'];
        }

        return false;
    }

    /*!
     \static
     \return true if there should only be logging of debug strings to file.
     \note Will return false until the real settings has been updated with updateSettings()
    */
    static function isLogOnlyEnabled()
    {
        if ( isset( $GLOBALS['eZDebugLogOnly'] ) )
        {
            return $GLOBALS['eZDebugLogOnly'];
        }

        return false;
    }

    /*!
     \static
     Determine if an ipaddress is in a network. E.G. 120.120.120.120 in 120.120.0.0/24.
     \return true or false.
    */
    static function isIPInNet( $ipaddress, $network, $mask = 24 )
    {
        $lnet = ip2long( $network );
        $lip = ip2long( $ipaddress );
        $binnet = str_pad( decbin( $lnet ), 32, "0", "STR_PAD_LEFT" );
        $firstpart = substr($binnet,0,$mask);
        $binip = str_pad( decbin( $lip ), 32, "0", "STR_PAD_LEFT" );
        $firstip = substr( $binip, 0, $mask );
        return( strcmp( $firstpart, $firstip ) == 0 );
    }

    /*!
     \static
     Updates the settings for debug handling with the settings array \a $settings.
     The array must contain the following keys.
     - debug-enabled - boolean which controls debug handling
     - debug-by-ip   - boolean which controls IP controlled debugging
     - debug-ip-list - array of IPs which gets debug
     - debug-by-user - boolean which controls userID controlled debugging
     - debug-user-list - array of UserIDs which gets debug
    */
    static function updateSettings( $settings )
    {
        // Make sure errors are handled by PHP when we read, including our own debug output.
        $oldHandleType = eZDebug::setHandleType( self::HANDLE_TO_PHP );

        if ( isset( $settings['debug-log-files-enabled'] ) )
        {
            $GLOBALS['eZDebugLogFileEnabled'] = $settings['debug-log-files-enabled'];
            if ( isset( $GLOBALS["eZDebugGlobalInstance"] ) )
                $GLOBALS["eZDebugGlobalInstance"]->GlobalLogFileEnabled = $settings['debug-log-files-enabled'];
        }

        if ( isset( $settings['debug-styles'] ) )
        {
            $GLOBALS['eZDebugStyles'] = $settings['debug-styles'];
        }

        if ( isset( $settings['always-log'] ) and
             is_array( $settings['always-log'] ) )
        {
            $GLOBALS['eZDebugAlwaysLog'] = $settings['always-log'];
        }

        if ( isset( $settings['log-only'] ) )
        {
            $GLOBALS['eZDebugLogOnly'] = ( $settings['log-only'] == 'enabled' );
        }

        $GLOBALS['eZDebugAllowedByIP'] = $settings['debug-by-ip'] ? self::isAllowedByCurrentIP( $settings['debug-ip-list'] ) : true;

        // updateSettings is meant to be called before the user session is started
        // so we do not take debug-by-user into account yet, but store the debug-user-list in $GLOBALS
        // so it can be used in the final check, done by checkDebugByUser()
        if ( isset( $settings['debug-by-user'] ) && $settings['debug-by-user'] )
        {
            $GLOBALS['eZDebugUserIDList'] = $settings['debug-user-list'] ? $settings['debug-user-list'] : array();
        }

        $GLOBALS['eZDebugAllowed'] = $GLOBALS['eZDebugAllowedByIP'];
        $GLOBALS['eZDebugEnabled'] = $settings['debug-enabled'] && $GLOBALS['eZDebugAllowedByIP'];

        eZDebug::setHandleType( $oldHandleType );
    }

    /*!
      \static
      Final checking for debug by user id.
      Checks if we should enable debug.

      Returns false if debug-by-user is not active, was already checked before
      or if there is no current user. Returns true otherwise.
    */
    static function checkDebugByUser()
    {
        if ( !isset( $GLOBALS['eZDebugUserIDList'] ) ||
             !is_array( $GLOBALS['eZDebugUserIDList'] ) )
        {
            return false;
        }
        else
        {
            $currentUserID = eZUser::currentUserID();

            if ( !$currentUserID )
            {
                return false;
            }
            else
            {
                $GLOBALS['eZDebugAllowedByUser'] = in_array( $currentUserID, $GLOBALS['eZDebugUserIDList'] );

                if ( $GLOBALS['eZDebugAllowed'] )
                {
                    $GLOBALS['eZDebugAllowed'] = $GLOBALS['eZDebugAllowedByUser'];
                }

                if ( $GLOBALS['eZDebugEnabled'] )
                {
                    $GLOBALS['eZDebugEnabled'] = $GLOBALS['eZDebugAllowedByUser'];
                }

                unset( $GLOBALS['eZDebugUserIDList'] );

                return true;
            }
        }
    }

    /*!
      \static
      Prints the debug report
    */
    static function printReport( $newWindow = false, $as_html = true, $returnReport = false,
                           $allowedDebugLevels = false, $useAccumulators = true, $useTiming = true, $useIncludedFiles = false )
    {
        if ( !eZDebug::isDebugEnabled() )
            return null;

        $debug = eZDebug::instance();
        $report = $debug->printReportInternal( $as_html, $returnReport & $newWindow, $allowedDebugLevels, $useAccumulators, $useTiming, $useIncludedFiles );

        if ( $newWindow == true )
        {
            $debugFilePath = eZDir::path( array( eZSys::varDirectory(), 'cache', 'debug.html' ) );
            $debugFileURL = $debugFilePath;
            eZURI::transformURI( $debugFileURL, true );
            print( "
<SCRIPT LANGUAGE='JavaScript'>
<!-- hide this script from old browsers

function showDebug()
{
    var debugWindow;

    if  (navigator.appName == \"Microsoft Internet Explorer\")
    {
        //Microsoft Internet Explorer
        debugWindow = window.open( '$debugFileURL', 'ezdebug', 'width=500,height=550,status,scrollbars,resizable,screenX=0,screenY=20,left=20,top=40');
        debugWindow.document.close();
        debugWindow.location.reload();
    }
    else if (navigator.appName == \"Opera\")
    {
        //Opera
        debugWindow = window.open( '', 'ezdebug', 'width=500,height=550,status,scrollbars,resizable,screenX=0,screenY=20,left=20,top=40');
        debugWindow.location.href=\"$debugFileURL\";
        debugWindow.navigate(\"$debugFileURL\");
    }
    else
    {
        //Mozilla, Firefox, etc.
        debugWindow = window.open( '', 'ezdebug', 'width=500,height=550,status,scrollbars,resizable,screenX=0,screenY=20,left=20,top=40');
        debugWindow.document.location.href=\"$debugFileURL\";
    };
}

showDebug();

// done hiding from old browsers -->
</SCRIPT>
" );
            $header = "<html><head><title>eZ debug</title></head><body>";
            $footer = "</body></html>";
            $fp = fopen( $debugFilePath, "w+" );

            fwrite( $fp, $header );
            fwrite( $fp, $report );
            fwrite( $fp, $footer );
            fclose( $fp );
        }
        else
        {
            if ( $returnReport )
                return $report;
        }
        return null;
    }

    /*!
      \private
     Returns the microtime as a float value. $mtime must be in microtime() format.
    */
    static function timeToFloat( $mtime )
    {
        $tTime = explode( " ", $mtime );
        ereg( "0\.([0-9]+)", "" . $tTime[0], $t1 );
        $time = $tTime[1] . "." . $t1[1];
        return $time;
    }

    /*!
     Sets the time of the start of the script ot \a $time.
     If \a $time is not supplied it gets the current \c microtime( true ).
     This is used to calculate total execution time and percentages.
    */
    static function setScriptStart( $time = false )
    {
        if ( $time == false )
            $time = microtime( true );
        $debug = eZDebug::instance();
        $debug->ScriptStart = $time;
    }

    /*!
      Creates an accumulator group with key \a $key and group name \a $name.
      If \a $name is not supplied name is taken from \a $key.
    */
    static function createAccumulatorGroup( $key, $name = false )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        if ( $name == '' or
             $name === false )
            $name = $key;
        $debug = eZDebug::instance();
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
    static function createAccumulator( $key, $inGroup = false, $name = false )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        if ( $name == '' or
             $name === false )
            $name = $key;
        $debug = eZDebug::instance();
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
    static function accumulatorStart( $key, $inGroup = false, $name = false, $recursive = false )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        $debug = eZDebug::instance();
        $key = $key === false ? 'Default Debug-Accumulator' : $key;
        if ( ! array_key_exists( $key, $debug->TimeAccumulatorList ) )
        {
            $debug->createAccumulator( $key, $inGroup, $name );
        }

        if ( $recursive )
        {
            if ( isset( $debug->TimeAccumulatorList[$key]['recursive_counter'] ) )
            {
                $debug->TimeAccumulatorList[$key]['recursive_counter']++;
                return;
            }
            $debug->TimeAccumulatorList[$key]['recursive_counter'] = 0;
        }

        $debug->TimeAccumulatorList[$key]['temp_time'] = microtime( true );
    }

    /*!
     Stops a previous time count and adds the total time to the accumulator \a $key.
    */
    static function accumulatorStop( $key, $recursive = false )
    {
        if ( !eZDebug::isDebugEnabled() )
            return;
        $debug = eZDebug::instance();
        $stopTime = microtime( true );
        $key = $key === false ? 'Default Debug-Accumulator' : $key;
        if ( ! array_key_exists( $key, $debug->TimeAccumulatorList ) )
        {
            eZDebug::writeWarning( "Accumulator '$key' does not exist, run eZDebug::accumulatorStart first", 'eZDebug::accumulatorStop' );
            return;
        }
        $accumulator = $debug->TimeAccumulatorList[$key];
        if ( $recursive )
        {
            if ( isset( $accumulator['recursive_counter'] ) )
            {
                if ( $accumulator['recursive_counter'] > 0 )
                {
                    $accumulator['recursive_counter']--;
                    return;
                }
            }
        }
        $diffTime = $stopTime - $accumulator['temp_time'];
        $accumulator['time'] = $accumulator['time'] + $diffTime;
        ++$accumulator['count'];
        $debug->TimeAccumulatorList[$key] = $accumulator;
    }


    /*!
      \private
      Prints a full debug report with notice, warnings, errors and a timing report.
    */
    function printReportInternal( $as_html = true, $returnReport = true, $allowedDebugLevels = false,
                                  $useAccumulators = true, $useTiming = true, $useIncludedFiles = false )
    {
        $styles = array( 'strict' => false,
                         'strict-end' => false,
                         'warning' => false,
                         'warning-end' => false,
                         'error' => false,
                         'error-end' => false,
                         'debug' => false,
                         'debug-end' => false,
                         'notice' => false,
                         'notice-end' => false,
                         'timing' => false,
                         'timing-end' => false,
                         'mark' => false,
                         'mark-end' => false,
                         'emphasize' => false,
                         'emphasize-end' => false,
                         'bold' => false,
                         'bold-end' => false );
        if ( isset( $GLOBALS['eZDebugStyles'] ) )
            $styles = $GLOBALS['eZDebugStyles'];
        if ( !$allowedDebugLevels )
            $allowedDebugLevels = array( self::LEVEL_NOTICE, self::LEVEL_WARNING, self::LEVEL_ERROR,
                                         self::LEVEL_DEBUG, self::LEVEL_TIMING_POINT, self::LEVEL_STRICT );
        $endTime = microtime( true );

        if ( $returnReport )
        {
            ob_start();
        }

        if ( $as_html )
        {
            echo "<div id=\"debug\"><table style='border: 1px dashed black; background-color: #fefefe;' summary='Layout table for eZ Publish debug output'>";
            echo "<tr><th><h1>eZ debug</h1></th></tr>";

            echo "<tr><td>";

            if ( !$this->UseCSS )
            {
                echo "<STYLE TYPE='text/css'>
                <!--
td.debugheader
{
    background-color : #eeeeee;
    border-top : 1px solid #444488;
    border-bottom : 1px solid #444488;
    font-size : 65%;
    font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
}

pre.debugtransaction
{
    background-color : #f8f6d8;
}

td.timingpoint1
{
    background-color : #ffffff;
    border-top : 1px solid #444488;
    font-size : 65%;
    font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
}

td.timingpoint2
{
    background-color : #eeeeee;
    border-top : 1px solid #444488;
    font-size : 65%;
    font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
}

-->
</STYLE>";
            }
            echo "<table style='border: 1px lightgray;' cellspacing='0' summary='Table for actual debug output, shows notices, warnings and errors.'>";
        }

        $this->printTopReportsList();

        $hasLevel = array( self::LEVEL_NOTICE => false,
                           self::LEVEL_WARNING => false,
                           self::LEVEL_ERROR => false,
                           self::LEVEL_TIMING_POINT => false,
                           self::LEVEL_DEBUG => false,
                           self::LEVEL_STRICT => false );

        foreach ( $this->DebugStrings as $debug )
        {
            if ( !in_array( $debug['Level'], $allowedDebugLevels ) )
                continue;
            $time = strftime ("%b %d %Y %H:%M:%S", strtotime( "now" ) );

            $outputData = $this->OutputFormat[$debug["Level"]];
            if ( is_array( $outputData ) )
            {
                $identifierText = '';
                if ( !$hasLevel[$debug['Level']] )
                {
                    $hasLevel[$debug['Level']] = true;
                    $identifierText = ' id="' . $outputData['xhtml-identifier'] . '"';
                }
                $color = $outputData["color"];
                $name = $outputData["name"];
                $label = $debug["Label"];
                $bgclass = $debug["BackgroundClass"];
                $pre = ($bgclass != '' ? " class='$bgclass'" : '');
                if ( $as_html )
                {
                    $label = htmlspecialchars( $label );

                    $contents = '';
                    if ( extension_loaded( 'xdebug' ) && ( strncmp( self::XDEBUG_SIGNATURE, $debug['String'], strlen( self::XDEBUG_SIGNATURE ) ) === 0 ) )
                        $contents = substr( $debug['String'], strlen( self::XDEBUG_SIGNATURE ) );
                    else
                        $contents = htmlspecialchars( $debug['String'] );

                    echo "<tr><td class='debugheader' valign='top'$identifierText><b><span style='color: $color'>$name:</span> $label</b></td>
                                    <td class='debugheader' valign='top'>$time</td></tr>
                                    <tr><td colspan='2'><pre$pre>" .  $contents . "</pre></td></tr>";
                }
                else
                {
                    echo $styles[$outputData['style']] . "$name:" . $styles[$outputData['style'].'-end'] . " ";
                    echo $styles['bold'] . "($label)" . $styles['bold-end'] . "\n" . $debug["String"] . "\n\n";
                }
            }
            flush();
        }
        if ( $as_html )
        {
            echo "</table>";

            echo "<h2>Timing points:</h2>";
            echo "<table id='timingpoints' style='border: 1px dashed black;' cellspacing='0' summary='Tabel of timingpoint stats.'><tr><th>Checkpoint</th><th>Elapsed</th><th>Rel. Elapsed</th><th>Memory</th><th>Rel. Memory</th></tr>";
        }
        $startTime = false;
        $elapsed = 0.00;
        $relElapsed = 0.00;
        if ( $useTiming )
        {
            for ( $i = 0; $i < count( $this->TimePoints ); ++$i )
            {
                $point = $this->TimePoints[$i];
                $nextPoint = false;
                if ( isset( $this->TimePoints[$i + 1] ) )
                    $nextPoint = $this->TimePoints[$i + 1];
                $time = $point["Time"];
                $nextTime = false;
                if ( $startTime === false )
                    $startTime = $time;
                $elapsed = $time - $startTime;

                $relMemory = 0;
                $memory = $point["MemoryUsage"];
                // Calculate relative time and memory usage
                if ( $nextPoint !== false )
                {
                    $nextTime = $nextPoint["Time"];
                    $relElapsed = $nextTime - $time;
                    $relElapsed = number_format( $relElapsed, $this->TimingAccuracy ) . " sec";

                    $nextMemory = $nextPoint["MemoryUsage"];
                    $relMemory = $nextMemory - $memory;
                    $relMemory = number_format( $relMemory / 1024, $this->TimingAccuracy ) . " KB";
                }
                else
                {
                    $relElapsed = $as_html ? '&nbsp;' : '';
                    $relMemory = $as_html ? '&nbsp;' : '';
                }

                // Convert memory usage to human readable
                $memory = number_format( $memory / 1024, $this->TimingAccuracy ) . " KB";
                $elapsed = number_format( $elapsed, $this->TimingAccuracy ) . " sec";

                if ( $i % 2 == 0 )
                    $class = "timingpoint1";
                else
                    $class = "timingpoint2";

                if ( $as_html )
                {
                    echo "<tr><td class='$class'>" . $point["Description"] . "</td>
                          <td class='$class' align='right'>$elapsed</td><td class='$class' align='right'>$relElapsed</td>
                          <td class='$class' align='right'>$memory</td><td class='$class' align='right'>$relMemory</td></tr>";
                }
                else
                {
                    echo $point["Description"] . ' ' . $elapsed . $relElapsed . "\n";
                }
            }

            if ( count( $this->TimePoints ) > 0 )
            {
                $totalElapsed = $endTime - $startTime;

                if ( $as_html )
                {
                    echo "<tr><td><b>Total runtime:</b></td><td><b>" .
    number_format( ( $totalElapsed ), $this->TimingAccuracy ) . " sec</b></td><td></td></tr>";
                }
                else
                {
                    echo "Total runtime: " .
    number_format( ( $totalElapsed ), $this->TimingAccuracy ) . " sec\n";
                }
            }
            else
            {
                if ( $as_html )
                    echo "<tr><td> No timing points defined</td><td>";
                else
                    echo "No timing points defined\n";
            }

            if ( function_exists( 'memory_get_peak_usage' ) )
            {
                $peakMemory = memory_get_peak_usage();
                if ( $as_html )
                {
                    echo "<tr><td><b>Peak memory usage:</b></td><td><b>" .
                        number_format( $peakMemory / 1024, $this->TimingAccuracy ) . " KB</b></td></tr>";
                }
                else
                {
                    echo "Peak memory usage: " .
                        number_format( $peakMemory / 1024, $this->TimingAccuracy ) . " KB\n";
                }
            }
        }
        if ( $as_html )
        {
            echo "</table>";
        }

        if ( $useIncludedFiles )
        {
            if ( $as_html )
                echo "<h2>Included files:</h2><table style='border: 1px dashed black;' cellspacing='0' summary='Tabel list of included templates used in the processing of this page.'><tr><th>File</th></tr>";
            else
                echo $styles['emphasize'] . "Includes" . $styles['emphasize-end'] . "\n";
            $phpFiles = get_included_files();
            $j = 0;
            $currentPathReg = preg_quote( realpath( "." ) );
            foreach ( $phpFiles as $phpFile )
            {
                if ( preg_match( "#^$currentPathReg/(.+)$#", $phpFile, $matches ) )
                    $phpFile = $matches[1];
                if ( $as_html )
                {
                    if ( $j % 2 == 0 )
                        $class = "timingpoint1";
                    else
                        $class = "timingpoint2";
                    ++$j;
                    echo "<tr><td class=\"$class\">$phpFile</td></tr>";
                }
                else
                {
                    echo "$phpFile\n";
                }
            }
            if ( $as_html )
                echo "</table>";
        }

        if ( $as_html )
        {
            echo "<h2>Time accumulators:</h2>";
            echo "<table id='timeaccumulators' style='border: 1px dashed black;' cellspacing='0' summary='Table with detailed list of time accumulators'><tr><th>&nbsp;Accumulator</th><th>&nbsp;Elapsed</th><th>&nbsp;Percent</th><th>&nbsp;Count</th><th>&nbsp;Average</th></tr>";
            $i = 0;
        }

        $scriptEndTime = microtime( true );
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

        if ( $useAccumulators )
        {
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
                    echo "<tr><td class='$class'><b>$groupName</b></td>";
                else
                    echo "Group " . $styles['mark'] . "$groupName:" . $styles['mark-end'] . " ";
                if ( array_key_exists( 'time_data', $group ) )
                {
                    $groupData = $group['time_data'];
                    $groupElapsed = number_format( ( $groupData['time'] ), $this->TimingAccuracy );
                    $groupPercent = number_format( ( $groupData['time'] * 100.0 ) / $totalElapsed, 1 );
                    $groupCount = $groupData['count'];
                    $groupAverage = number_format( ( $groupData['time'] / $groupData['count'] ), $this->TimingAccuracy );
                    if ( $as_html )
                    {
                        echo ( "<td class=\"$class\">$groupElapsed sec</td>".
                                         "<td class=\"$class\" align=\"right\"> $groupPercent%</td>".
                                         "<td class=\"$class\" align=\"right\"> $groupCount</td>".
                                         "<td class=\"$class\" align=\"right\"> $groupAverage sec</td>" );
                    }
                    else
                    {
                        echo $styles['emphasize'] . "$groupElapsed" . $styles['emphasize-end'] . " sec ($groupPercent%), $groupAverage avg sec ($groupCount)";
                    }
                }
                else if ( $as_html )
                {
                    echo ( "<td class=\"$class\"></td>".
                                     "<td class=\"$class\"></td>".
                                     "<td class=\"$class\"></td>".
                                     "<td class=\"$class\"></td>" );
                }
                if ( $as_html )
                    echo "</tr>";
                else
                    echo "\n";

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

                        echo ( "<tr>" .
                                         "<td class=\"$class\">$childName</td>" .
                                         "<td class=\"$class\">$childElapsed sec</td>" .
                                         "<td class=\"$class\" align=\"right\">$childPercent%</td>" .
                                         "<td class=\"$class\" align=\"right\">$childCount</td>" .
                                         "<td class=\"$class\" align=\"right\">$childAverage sec</td>" .
                                         "</tr>" );
                    }
                    else
                    {
                        echo "$childName: " . $styles['emphasize'] . $childElapsed . $styles['emphasize-end'] . " sec ($childPercent%), $childAverage avg sec ($childCount)\n";
                    }
                }
            }
        }
        if ( $as_html )
        {
            echo "<tr><td><b>Total script time:</b></td><td><b>" . number_format( ( $totalElapsed ), $this->TimingAccuracy ) . " sec</b></td><td></td></tr>";
        }
        else
        {
            echo "\nTotal script time: " . $styles['emphasize'] . number_format( ( $totalElapsed ), $this->TimingAccuracy ) . $styles['emphasize-end'] . " sec\n";
        }

        if ( $as_html )
        {
            echo "</table>";
        }

        $this->printBottomReportsList();

        if ( $as_html )
        {
            echo "</td></tr></table></div>";
        }

        if ( $returnReport )
        {
            $returnText = ob_get_contents();
            ob_end_clean();
            return $returnText;
        }
        else
        {
            return NULL;
        }
    }

    /*!
     Appends report to 'top' reports list.
    */
    static function appendTopReport( $reportName, $reportContent )
    {
        $debug = eZDebug::instance();
        $debug->topReportsList[$reportName] = $reportContent;
    }

    /*!
     Prints all 'top' reports
    */
    static function printTopReportsList()
    {
        $debug = eZDebug::instance();
        $reportNames = array_keys( $debug->topReportsList );
        foreach ( $reportNames as $reportName )
        {
            echo $debug->topReportsList[$reportName];
        }
    }

    /*!
     Appends report to 'bottom' reports list.
    */
    static function appendBottomReport( $reportName, $reportContent )
    {
        $debug = eZDebug::instance();
        $debug->bottomReportsList[$reportName] = $reportContent;
    }

    /*!
     Prints all 'bottom' reports
    */
    static function printBottomReportsList()
    {
        $debug = eZDebug::instance();
        $reportNames = array_keys( $debug->bottomReportsList );
        foreach ( $reportNames as $reportName )
        {
            echo $debug->bottomReportsList[$reportName];
        }
    }

    /*!
     If debugging is allowed, given the limitations of the DebugByIP and DebugByUser settings.
    */
    function isDebugAllowed()
    {

    }

    /*!
     If debugging is allowed for the current IP address.
    */
    private static function isAllowedByCurrentIP( $allowedIpList )
    {
        $ipAddress = eZSys::serverVariable( 'REMOTE_ADDR', true );
        if ( $ipAddress )
        {
            foreach( $allowedIpList as $itemToMatch )
            {
                if ( preg_match("/^(([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+))(\/([0-9]+)$|$)/", $itemToMatch, $matches ) )
                {
                    if ( $matches[6] )
                    {
                        if ( self::isIPInNet( $ipAddress, $matches[1], $matches[7] ) )
                        {
                            return true;
                        }
                    }
                    else
                    {
                        if ( $matches[1] == $ipAddress )
                        {
                            return true;
                        }
                    }
                }
            }

            return false;
        }
        else
        {
            return eZSys::isShellExecution() && in_array( 'commandline', $allowedIpList );
        }
    }

    /// \privatesection
    /// String array containing the debug information
    public $DebugStrings = array();

    /// Array which contains the time points
    public $TimePoints = array();

    /// Array which contains the temporary time points
    public $TmpTimePoints;

    /// Array wich contains time accumulators
    public $TimeAccumulatorList = array();

    /// Determines which debug messages should be shown
    public $ShowTypes;

    /// Determines what to do with php errors, ignore, fetch or output
    public $HandleType;

    /// An array of the outputformats for the different debug levels
    public $OutputFormat;

    /// An array of logfiles used by the debug class with each key being the debug level
    public $LogFiles;

    /// How many places behing . should be displayed when showing times
    public $TimingAccuracy = 4;

    /// How many places behing . should be displayed when showing percentages
    public $PercentAccuracy = 4;

    /// Whether to use external CSS or output own CSS. True if external is to be used.
    public $UseCSS;

    /// Determines how messages are output (screen/log)
    public $MessageOutput;

    /// A list of message types
    public $MessageTypes;

    /// A map with message types and whether they should do file logging.
    public $LogFileEnabled;

    /// Controls whether logfiles are used at all.
    public $GlobalLogFileEnabled;

    /// The time when the script was started
    public $ScriptStart;

    /// A list of override directories
    public $OverrideList;

    /// A list of debug reports that appears at the bottom of debug output
    public $bottomReportsList;

    /// A list of debug reports that appears at the top of debug output
    public $topReportsList;
}

/*!
  Helper function for eZDebug, called whenever a PHP error occurs.
  The error is then handled by the eZDebug class.
*/

function eZDebugErrorHandler( $errno, $errstr, $errfile, $errline )
{
    if ( $GLOBALS['eZDebugRecursionFlag'] )
    {
        print( "Fatal debug error: A recursion in debug error handler was detected, aborting debug message.<br/>" );
        $GLOBALS['eZDebugRecursionFlag'] = false;
        return;
    }

    $GLOBALS['eZDebugRecursionFlag'] = true;
    $debug = eZDebug::instance();
    $result = $debug->errorHandler( $errno, $errstr, $errfile, $errline );
    $GLOBALS['eZDebugRecursionFlag'] = false;
    return $result;
}
$GLOBALS['eZDebugRecursionFlag'] = false;

?>
