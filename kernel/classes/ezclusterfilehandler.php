<?php
//
// Definition of eZClusterFileHandler class
//
// Created on: <07-Mar-2006 16:14:02 vs>
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

class eZClusterFileHandler
{
    /**
     * \public
     * \static
     * \return filehandler
     */
    static function instance( $filename = false )
    {
        if( $filename !== false )
        {
            $optionArray = array( 'iniFile'      => 'file.ini',
                                  'iniSection'   => 'ClusteringSettings',
                                  'iniVariable'  => 'FileHandler',
                                  'handlerParams'=> array( $filename ) );

            $options = new ezpExtensionOptions( $optionArray );

            $handler = eZExtension::getHandlerClass( $options );

            return $handler;
        }
        else
        {
            // return Filehandler from GLOBALS based on ini setting.
            if ( !isset( $GLOBALS['eZClusterFileHandler_chosen_handler'] ) )
            {
                $optionArray = array( 'iniFile'      => 'file.ini',
                                      'iniSection'   => 'ClusteringSettings',
                                      'iniVariable'  => 'FileHandler' );

                $options = new ezpExtensionOptions( $optionArray );

                $handler = eZExtension::getHandlerClass( $options );

                $GLOBALS['eZClusterFileHandler_chosen_handler'] = $handler;
            }
            else
                $handler = $GLOBALS['eZClusterFileHandler_chosen_handler'];

            return $handler;
        }
    }

    /**
     * \public
     * \static
     * \return list of directories used to search cluster file handlers for.
     */
    static function searchPathArray()
    {
        if ( !isset( $GLOBALS['eZClusterFileHandler_search_path_array'] ) )
        {
            $fileINI = eZINI::instance( 'file.ini' );
            $searchPathArray = array( 'kernel/classes/clusterfilehandlers',
                                      'kernel/private/classes/clusterfilehandlers' );
            if ( $fileINI->hasVariable( 'ClusteringSettings', 'ExtensionDirectories' ) )
            {
                $extensionDirectories = $fileINI->variable( 'ClusteringSettings', 'ExtensionDirectories' );
                $baseDirectory = eZExtension::baseDirectory();
                foreach ( $extensionDirectories as $extensionDirectory )
                {
                    $customSearchPath = $baseDirectory . '/' . $extensionDirectory . '/clusterfilehandlers';
                    if ( file_exists( $customSearchPath ) )
                        $searchPathArray[] = $customSearchPath;
                }
            }

            $GLOBALS['eZClusterFileHandler_search_path_array'] = $searchPathArray;
        }

        return $GLOBALS['eZClusterFileHandler_search_path_array'];
    }
}

?>
