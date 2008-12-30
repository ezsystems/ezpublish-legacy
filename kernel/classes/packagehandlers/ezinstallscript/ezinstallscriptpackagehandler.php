<?php
//
// Definition of eZInstallScriptPackageHandler class
//
// Created on: <16-Feb-2006 11:15:42 ks>
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

/*! \file ezinstallscripterpackagehandler.php
*/

/*!
  \class eZInstallScriptPackageHandler ezinstallscriptpackagehandler.php
  \brief Empty handler to support package custom install scripts.

*/


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
     Returns an explanation for the extension install item.
    */
    function explainInstallItem( $package, $installItem, $requestedInfo = array() )
    {
        $itemPath = $package->path() . '/' . $installItem['sub-directory'];
        $xmlPath = $itemPath . '/' . $installItem['filename'] . '.xml';

        $dom =& $package->fetchDOMFromFile( $xmlPath );
        if ( !$dom )
            return false;

        $mainNode =& $dom->documentElement;

        $description = $mainNode->getAttribute( 'description' );
        if ( !$description )
            return false;

        return array( 'description' => ezi18n( 'kernel/package', 'Install script: %description', false,
                                               array( '%description' => $description ) ) );
    }

    /*!
     Do nothing
    */
    function uninstall( $package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      $content, &$installParameters,
                      &$installData )
    {
        return true;
    }

    /*!
     Do nothing
    */
    function install( $package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      $content, &$installParameters,
                      &$installData )
    {
        return true;
    }

    function add( $packageType, $package, $cli, $parameters )
    {
        $siteINI = eZINI::instance();
        $extensionDir = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' );

        //$cli->output( var_export( $parameters, true ) );
        foreach ( $parameters as $scriptItem )
        {
            $cli->output( 'adding install script ' . $cli->style( 'dir' ) . $scriptItem['filename'] .  $cli->style( 'dir-end' ) );

            $sourceDir = $scriptItem['source-directory'];
            $targetDir = $package->path() . '/' . $scriptItem['sub-directory'];

            eZDir::mkdir( $targetDir, false, true );
            eZDir::copy( $sourceDir, $targetDir, false );

            $package->appendInstall( 'ezinstallscript', false, false, true,
                                     $scriptItem['filename'], $scriptItem['sub-directory'],
                                     array( 'content' => false ) );
        }
    }

    function handleAddParameters( $packageType, $package, $cli, $arguments )
    {
        $scriptArgumentList = array_chunk( $arguments, 3 );
        $params = array();

        foreach ( $scriptArgumentList as $scriptArguments )
        {
            if ( count( $scriptArguments ) < 3 )
            {
                break;
            }

            $script = array(
                'sub-directory' => $scriptArguments[0],
                'filename' => $scriptArguments[1],
                'source-directory' => $scriptArguments[2]
            );

            if ( !file_exists( $script['source-directory'] ) )
            {
                $cli->error( 'install script source directory ' . $cli->style( 'dir' ) . $script['source-directory'] . $cli->style( 'dir-end' ) . 'does not exist' );
                return false;
            }

            $installFile = $script['source-directory'] . '/' . $script['filename'] . '.xml';
            if ( !file_exists( $installFile ) )
            {
                $cli->error( 'install script ' . $cli->style( 'dir' ) . $script['filename'] . '.xml' . $cli->style( 'dir-end' ) . 'does not exist in source dir' );
                return false;
            }

            $params[] = $script;
        }

        return $params;
    }
}

?>
