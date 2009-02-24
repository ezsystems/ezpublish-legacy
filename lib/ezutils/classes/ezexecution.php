<?php
//
// Definition of eZExecution class
//
// Created on: <29-Nov-2002 11:24:42 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*! \file
*/

/*!
  \class eZExecution ezexecution.php
  \brief Handles proper script execution, fatal error detection and handling.

  By registering a fatal error handler it's possible for the PHP script to
  catch fatal errors, such as "Call to a member function on a non-object".

  By registering a cleanup handler it's possible to make sure the script can
  end properly.
*/

class eZExecution
{
    /*!
     Sets the clean exit flag to on,
     this notifies the exit handler that everything finished properly.
    */
    static function setCleanExit()
    {
        self::$hasCleanExit = true;
    }

    /*!
     Calls the cleanup handlers to make sure that the script is ready to exit.
    */
    static function cleanup()
    {
        $handlers = eZExecution::cleanupHandlers();
        foreach ( $handlers as $handler )
        {
            if ( is_callable( $handler ) )
                call_user_func( $handler );
            else
                eZDebug::writeError('Could not call cleanup handler, is it a static public function?', 'eZExecution::cleanup');
        }
    }

    /*!
     Adds a cleanup handler to the end of the list,
     \a $handler must contain the name of the function to call.
     The function is called at the end of the script execution to
     do some cleanups.
    */
    static function addCleanupHandler( $handler )
    {
        self::registerShutdownHandler();
        self::$cleanupHandlers[] = $handler;
    }

    /*!
     \return An array with cleanup handlers.
    */
    static function cleanupHandlers()
    {
        return self::$cleanupHandlers;
    }

    /*!
     Adds a fatal error handler to the end of the list,
     \a $handler must contain the name of the function to call.
     The handler will be called whenever a fatal error occurs,
     which usually happens when the script did not finish.
    */
    static function addFatalErrorHandler( $handler )
    {
        self::registerShutdownHandler();
        self::$fatalErrorHandlers[] = $handler;
    }

    /*!
     \return An array with fatal error handlers.
    */
    static function fatalErrorHandlers()
    {
        return self::$fatalErrorHandlers;
    }

    /*!
     \return true if the request finished properly.
    */
    static function isCleanExit()
    {
        return self::$hasCleanExit;
    }

    /*!
     Sets the clean exit flag and exits the page.
     Use this if you want premature exits instead of the \c exit function.
    */
    static function cleanExit()
    {
        eZExecution::cleanup();
        eZExecution::setCleanExit();
        exit;
    }

    /*!
     Exit handler which called after the script is done, if it detects
     that eZ Publish did not exit cleanly it will issue an error message
     and display the debug.
    */
    static function uncleanShutdownHandler()
    {
        // Need to change the current directory, since this information is lost
        // when the callbackfunction is called. eZDocumentRoot is set in ::registerShutdownHandler
        if ( self::$eZDocumentRoot !== null )
        {
            chdir( self::$eZDocumentRoot );
        }

        if ( eZExecution::isCleanExit() )
            return;
        eZExecution::cleanup();
        $handlers = eZExecution::fatalErrorHandlers();
        foreach ( $handlers as $handler )
        {
            if ( is_callable( $handler ) )
                call_user_func( $handler );
            else

                eZDebug::writeError('Could not call fatal error handler, is it a static public function?', 'eZExecution::uncleanShutdownHandler');
        }
    }

    /*!
     Register ::uncleanShutdownHandler as shutdown function
    */
    static public function registerShutdownHandler( $documentRoot = false )
    {
        if ( !self::$shutdownHandle )
        {
            register_shutdown_function( array('eZExecution', 'uncleanShutdownHandler') );
            /*
                see:
                - http://www.php.net/manual/en/function.session-set-save-handler.php
                - http://bugs.php.net/bug.php?id=33635
                - http://bugs.php.net/bug.php?id=33772
            */
            register_shutdown_function( 'session_write_close' );
            set_exception_handler( array( 'eZExecution', 'defaultExceptionHandler' ) );
            self::$shutdownHandle = true;
        }

        // Needed by the error handler, since the current directory is lost when
        // the callback function eZExecution::uncleanShutdownHandler is called.
        if ( $documentRoot )
        {
            self::$eZDocumentRoot = $documentRoot;
        }
        else if ( self::$eZDocumentRoot === null )
        {
            self::$eZDocumentRoot = str_replace( 
                DIRECTORY_SEPARATOR . 'lib' . 
                DIRECTORY_SEPARATOR . 'ezutils'. 
                DIRECTORY_SEPARATOR . 'classes',
                '',
                dirname( __FILE__ )
            );
        }
    }

    /**
     * Installs the default Exception handler
     *
     * @params Exception the exception
     * @return void
     */
    static public function defaultExceptionHandler( Exception $e )
    {
        if( PHP_SAPI != 'cli' )
        {
            header( 'HTTP/1.x 500 Internal Server Error' );
            header( 'Content-Type: text/html' );

            echo "An unexpected error has occurred. Please contact the webmaster.<br/>";
        }
        else
        {
            $cli = eZCLI::instance();
            $cli->error( "An unexpected error has occurred. Please contact the webmaster.");
        }

        eZLog::write( 'Unexpected error, the message was : ' . $e->getMessage(), 'error.log' );

        eZExecution::cleanup();
        eZExecution::setCleanExit();
        exit( 1 );
    }

    static private $eZDocumentRoot = null;
    static private $hasCleanExit = false;
    static private $shutdownHandle = false;
    static private $fatalErrorHandlers = array();
    static private $cleanupHandlers = array();
}


?>
