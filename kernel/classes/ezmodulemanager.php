<?php
//
// Definition of eZModuleManager class
//
// Created on: <19-Aug-2002 16:37:56 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file ezmodulemanager.php
*/

/*!
  \class eZModuleManager ezmodulemanager.php
  \brief The class eZModuleManager does

*/

class eZModuleManager
{
    /*!
     Constructor
    */
    function eZModuleManager()
    {

    }

    function aviableModules()
    {
        eZDebug::writeWarning( 'The function eZModuleManager::aviableModules is deprecated, use eZModuleManager::availableModules instead' );
        return eZModuleManager::availableModules();
    }

    function availableModules()
    {
        include_once( 'lib/ezutils/classes/ezmodule.php' );
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
