<?php
//
// Definition of eZClusterFileHandler class
//
// Created on: <07-Mar-2006 16:14:02 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezclusterfilehandler.php
*/

class eZClusterFileHandler
{
    /**
     * \public
     * \static
     * \return filehandler
     */
    function instance( $filename = false )
    {
        // Determine handler to use and cache its name in a global variable.
        if ( !isset( $GLOBALS['eZClusterFileHandler_chosen_handler_class'] ) )
        {
            require_once( 'lib/ezutils/classes/ezini.php' );
            $fileINI = eZINI::instance( 'file.ini' );
            $handlerName = 'ezfs';
            if ( $fileINI->hasVariable( 'ClusteringSettings', 'FileHandler' ) )
                $handlerName = $fileINI->variable( 'ClusteringSettings', 'FileHandler' );
            $handlerClass = $handlerName . 'filehandler';
            require_once( 'kernel/classes/clusterfilehandlers/' . $handlerClass . '.php' );
            $GLOBALS['eZClusterFileHandler_chosen_handler_class'] = $handlerClass;
        }

        if ( $filename !== false )
        {
            // return new FileHandler based on INI setting.
            $handlerClass = $GLOBALS['eZClusterFileHandler_chosen_handler_class'];
            return new $handlerClass( $filename );
        }
        else
        {
            // return Filehandler from GLOBALS based on ini setting.
            if ( !isset( $GLOBALS['eZClusterFileHandler_chosen_handler'] ) )
            {
                $handlerClass = $GLOBALS['eZClusterFileHandler_chosen_handler_class'];
                $handler = new $handlerClass;
                $GLOBALS['eZClusterFileHandler_chosen_handler'] = $handler;
            }
            else
                $handler = $GLOBALS['eZClusterFileHandler_chosen_handler'];

            return $handler;
        }
    }
}

?>
