<?php
//
// Definition of eZExecution class
//
// Created on: <29-Nov-2002 11:24:42 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
     Constructor
    */
    function eZExecution()
    {
    }

    /*!
     Sets the clean exit flag to on,
     this notifies the exit handler that everything finished properly.
    */
    function setCleanExit()
    {
        $GLOBALS['eZExecutionCleanExit'] = true;
    }

    /*!
     Calls the cleanup handlers to make sure that the script is ready to exit.
    */
    function cleanup()
    {
        $handlers =& eZExecution::cleanupHandlers();
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
    function addCleanupHandler( $handler )
    {
        $handlers =& eZExecution::cleanupHandlers();
        $handlers[] = $handler;
    }

    /*!
     \return An array with cleanup handlers.
    */
    function &cleanupHandlers()
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
    function addFatalErrorHandler( $handler )
    {
        $handlers =& eZExecution::fatalErrorHandlers();
        $handlers[] = $handler;
    }

    /*!
     \return An array with fatal error handlers.
    */
    function &fatalErrorHandlers()
    {
        $handlers =& $GLOBALS['eZExecutionFatalErrorHandlers'];
        if ( !isset( $handlers ) )
            $handlers = array();
        return $handlers;
    }

    /*!
     \return true if the request finished properly.
    */
    function isCleanExit()
    {
        return $GLOBALS['eZExecutionCleanExit'];
    }

    /*!
     Sets the clean exit flag and exits the page.
     Use this if you want premature exits instead of the \c exit function.
    */
    function cleanExit()
    {
        eZExecution::cleanup();
        eZExecution::setCleanExit();
        exit;
    }

}


/*!
 Exit handler which called after the script is done, if it detects
 that eZ publish did not exit cleanly it will issue an error message
 and display the debug.
*/
function eZExecutionUncleanShutdownHandler()
{
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
