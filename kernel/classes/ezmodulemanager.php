<?php
//
// Definition of eZModuleManager class
//
// Created on: <19-Aug-2002 16:37:56 sp>
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

/*! \file ezmodulemanager.php
*/

/*!
  \class eZModuleManager ezmodulemanager.php
  \brief The class eZModuleManager does

*/

class eZModuleManager
{
    static function aviableModules()
    {
        eZDebug::writeWarning( 'The function eZModuleManager::aviableModules is deprecated, use eZModuleManager::availableModules instead' );
        return eZModuleManager::availableModules();
    }

    static function availableModules()
    {
        $pathList = eZModule::globalPathList();
        $modules = array();
        foreach ( $pathList as $pathItem )
        {
            if ( $handle = opendir( $pathItem ) )
            {
                while ( false !== ( $file = readdir( $handle ) ) )
                {
                    if ( is_dir( $pathItem . '/' . $file ) && file_exists( $pathItem . '/' . $file . '/module.php' )  )
                    {
                        $modules[] = $file;
                    }
                }
                closedir( $handle );
            }
        }
        return $modules;
    }
}

?>
