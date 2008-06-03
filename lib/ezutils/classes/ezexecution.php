<?php
//
// Definition of eZExecution class
//
// Created on: <29-Nov-2002 11:24:42 amos>
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

/*! \file ezexecution.php
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
        $GLOBALS['eZExecutionCleanExit'] = true;
    }

    /*!
     Calls the cleanup handlers to make sure that the script is ready to exit.
    */
    static function cleanup()
    {
        $handlers = eZExecution::cleanupHandlers();
        foreach ( $handlers as $handler )
        {
            if ( function_exists( $handler ) )
                $handler();
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
        $handlers =& eZExecution::cleanupHandlers();
        $handlers[] = $handler;
    }

    /*!
     \return An array with cleanup handlers.
    */
    static function &cleanupHandlers()
    {
        $handlers =& $GLOBALS['eZExecutionCleanupHandlers'];
        if ( !isset( $handlers ) )
            $handlers = array();
        return $handlers;
    }

    /*!
     Adds a fatal error handler to the end of the list,
     \a $handler must contain the name of the function to call.
     The handler will be called whenever a fatal error occurs,
     which usually happens when the script did not finish.
    */
    static function addFatalErrorHandler( $handler )
    {
        $handlers =& eZExecution::fatalErrorHandlers();
        $handlers[] = $handler;
    }

    /*!
     \return An array with fatal error handlers.
    */
    static function &fatalErrorHandlers()
    {
        $handlers =& $GLOBALS['eZExecutionFatalErrorHandlers'];
        if ( !isset( $handlers ) )
            $handlers = array();
        return $handlers;
    }

    /*!
     \return true if the request finished properly.
    */
    static function isCleanExit()
    {
        return $GLOBALS['eZExecutionCleanExit'];
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

}


/*!
 Exit handler which called after the script is done, if it detects
 that eZ Publish did not exit cleanly it will issue an error message
 and display the debug.
*/
function eZExecutionUncleanShutdownHandler()
{
    // Need to change the current directory, since this information is lost
    // when the callbackfunction is called. eZDocumentRoot is set in index.php.
    if ( isset( $GLOBALS['eZDocumentRoot'] ) )
    {
        $documentRoot = $GLOBALS['eZDocumentRoot'];
        chdir( $documentRoot );
    }

    if ( eZExecution::isCleanExit() )
        return;
    eZExecution::cleanup();
    $handlers =& eZExecution::fatalErrorHandlers();
    foreach ( $handlers as $handler )
    {
        if ( function_exists( $handler ) )
            $handler();
    }
}

register_shutdown_function( 'eZExecutionUncleanShutdownHandler' );

$GLOBALS['eZExecutionCleanExit'] = false;

?>
