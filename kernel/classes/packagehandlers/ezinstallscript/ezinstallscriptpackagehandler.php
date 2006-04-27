<?php
//
// Definition of eZInstallScriptPackageHandler class
//
// Created on: <16-Feb-2006 11:15:42 ks>
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

/*! \file ezinstallscripterpackagehandler.php
*/

/*!
  \class eZInstallScriptPackageHandler ezinstallscriptpackagehandler.php
  \brief Empty handler to support package custom install scripts.

*/

include_once( 'kernel/classes/ezpackagehandler.php' );


class eZInstallScriptPackageHandler extends eZPackageHandler
{
    /*!
     Constructor
    */
    function eZInstallScriptPackageHandler()
    {
        $this->eZPackageHandler( 'ezinstallscript',
                                 array( 'extract-install-content' => false ) );
    }

    /*!
     \reimp
     Returns an explanation for the extension install item.
    */
    function explainInstallItem( &$package, $installItem )
    {
        $itemPath = $package->path() . '/' . $installItem['sub-directory'];
        $xmlPath = $itemPath . '/' . $installItem['filename'] . '.xml';

        $dom =& $package->fetchDOMFromFile( $xmlPath );
        if ( $dom )
            $mainNode =& $dom->root();

        $description = $mainNode->getAttribute( 'description' );
        if ( $description )
        {
            return array( 'description' => ezi18n( 'kernel/package', 'Install script: %description', false,
                                                       array( '%description' => $description ) ) );
        }
    
        return false;
    }

    /*!
     \reimp
     Do nothing
    */
    function uninstall( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, &$installParameters,
                      &$installData )
    {
        return true;
    }

    /*!
     \reimp
     Do nothing
    */
    function install( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, &$installParameters,
                      &$installData )
    {
        return true;
    }
}

?>
