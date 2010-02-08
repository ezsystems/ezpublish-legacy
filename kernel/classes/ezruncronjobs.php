<?php
//
// Created on: <26-Jun-2008 10:16:45 oh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*!
 Class containing helper functions to execute cronjob parts.
*/
class eZRunCronjobs
{
    /*!
     \static
     Function for running a cronjob script.
    */
    static function runScript( $cli, $scriptFile )
    {
        $scriptMutex = new eZMutex( $scriptFile );
        $lockTS = $scriptMutex->lockTS();
        $runScript = false;
        if ( $lockTS === false )
        {
            if ( $scriptMutex->lock() )
            {
                $scriptMutex->setMeta( 'pid', getmypid() );
                $runScript = true;
            }
            else
            {
                $cli->error( 'Failed to aquire cronjob part lock: ' . $scriptFile );
            }
        }
        // If the cronjob part has been blocked for  2 * eZRunCronjobs_MaxScriptExecutionTime,
        // force stealing of the cronjob part
        else if ( $lockTS < time() - 2 * eZRunCronjobs_MaxScriptExecutionTime )
        {
            $cli->output( 'Forcing to steal the mutex lock: ' . $scriptFile );
            $runScript = eZRunCronjobs::stealMutex( $cli, $scriptMutex, true );
        }
        else if ( $lockTS < time() - eZRunCronjobs_MaxScriptExecutionTime )
        {
            $cli->output( 'Trying to steal the mutex lock: ' . $scriptFile );
            $runScript = eZRunCronjobs::stealMutex( $cli, $scriptMutex );
        }
        else
        {
            $cli->output( 'Cronjob part locked by other process: ' . $scriptMutex->meta( 'pid' ) );
        }
        if ( $runScript )
        {
            global $script;
            global $isQuiet;
            global $cronPart;
            include( $scriptFile );
            $scriptMutex->unlock();
        }
    }

    /*!
     \static
     \private

     Steal a script mutex

     \param cli
     \param script mutex to steal
     \param force stealing of mutex ( optional, false by default )

     \return true if mutex is stole successfully
    */
    static function stealMutex( $cli, $scriptMutex, $force = false )
    {
        $cli->output( 'Stealing mutex. Old process has run too long.' );
        $oldPid = $scriptMutex->meta( 'pid' );
        if ( $force )
        {
            if ( is_numeric( $oldPid ) &&
                 $oldPid != 0 &&
                 function_exists( 'posix_kill' ) )
            {
                $cli->output( 'Killing process: ' . $oldPid );
                posix_kill( $oldPid, 9 );
            }
        }
        if ( $scriptMutex->steal( $force ) )
        {
            $scriptMutex->setMeta( 'pid', getmypid() );
            return true;
        }
        else
        {
            $cli->error( 'Failed to steal cronjob part lock.' );
        }
        return false;
    }
}

?>