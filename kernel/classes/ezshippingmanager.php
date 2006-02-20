<?php
//
// Definition of eZShippingManager class
//
// Created on: <12-Dec-2005 12:00:06 vs>
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

/*! \file ezshippingmanager.php
*/

/*!
  \class eZShippingManager ezshippingmanager.php
  \brief The class eZShippingManager does
*/

class eZShippingManager
{
    /*!
     Constructor
    */
    function eZShippingManager()
    {
    }

    /*!
     \public
     \static
     */
    function getShippingInfo( $productCollectionID )
    {
        if ( !is_object( $handler = eZShippingManager::loadShippingHandler() ) )
            return null;

        return $handler->getShippingInfo( $productCollectionID );
    }

    /*!
     \public
     \static
     */
    function updateShippingInfo( $productCollectionID )
    {
        if ( is_object( $handler = eZShippingManager::loadShippingHandler() ) )
            return $handler->updateShippingInfo( $productCollectionID );
    }

    /*!
     Load shipping handler (if specified).

     \private
     \static
     \return true if no handler specified,
             false if a handler specified but could not be loaded,
             handler object if handler specified and found.
     */
    function loadShippingHandler()
    {
        $shopINI =& eZINI::instance( 'shop.ini' );

        if ( !$shopINI->hasVariable( 'ShippingSettings', 'Handler' ) )
            return true;

        $handlerName = $shopINI->variable( 'ShippingSettings', 'Handler' );
        $repositoryDirectories = $shopINI->variable( 'ShippingSettings', 'RepositoryDirectories' );
        $extensionDirectories = $shopINI->variable( 'ShippingSettings', 'ExtensionDirectories' );

        $baseDirectory = eZExtension::baseDirectory();
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/shippinghandlers';
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }

        $foundHandler = false;
        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            $includeFile = "$repositoryDirectory/{$handlerName}shippinghandler.php";

            if ( file_exists( $includeFile ) )
            {
                $foundHandler = true;
                break;
            }
        }

        if ( !$foundHandler )
        {
            eZDebug::writeError( "Shipping handler '$handlerName' not found, " .
                                 "searched in these directories: " .
                                 implode( ', ', $repositoryDirectories ),
                                 'eZShippingManager::loadShippingHandler' );
            return false;
        }

        require_once( $includeFile );
        $className = $handlerName . 'ShippingHandler';
        return new $className;
    }
}

?>
