<?php
//
// Definition of eZPackageHandler class
//
// Created on: <01-Aug-2003 17:11:07 amos>
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

/*! \file ezpackagehandler.php
*/

/*!
  \class eZPackageHandler ezpackagehandler.php
  \brief The class eZPackageHandler does

*/

class eZPackageHandler
{
    /*!
     Constructor
    */
    function eZPackageHandler( $handlerType, $parameters = array() )
    {
        $parameters = array_merge( array( 'extract-install-content' => false ),
                                   $parameters );
        $this->ExtractInstallContent = $parameters['extract-install-content'];
        $this->HandlerType = $handlerType;
    }

    /*!
     \virtual
     \return true if the content of the install item should be extracted
             from disk before the install() function is called.
    */
    function extractInstallContent()
    {
        return $this->ExtractInstallContent;
    }

    /*!
     \pure
     Installs the package item
    */
    function install( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, $installParameters )
    {
    }

    /*!
     \return the name of the type this handler works for.
    */
    function handlerType()
    {
        return $this->HandlerType;
    }

    /*!
     \virtual
     Resets all data in the handler so that it's ready to handle a new package.
    */
    function reset()
    {
    }

    /*!
     \pure
     Adds items defined in \a $parameters to the package \a $package.
    */
    function add( $packageType, &$package, $parameters )
    {
    }

    /*!
     \pure
     Called when command line parameters must be handled by the package handler.
     This function must return an array with values which can easily be used in the add() function.
     \param $packageType The type that was specified when using the add command,
                         which is either the name of this handler or an alias for it.
     \param $package     The package object.
     \param $cli         Command line interface object, can be used to output errors etc.
     \param $arguments   An array with string values taken from the command line after the add command.
    */
    function handleAddParameters( $packageType, &$package, &$cli, $arguments )
    {
    }

//     /*!
//      \pure
//      Inteprets the parameters defined in \a $parameters and adds items to \a $package.
//     */
//     function handle( &$package, $parameters )
//     {
//     }

    /*!
     \pure
     Fills in extra information on the dependency node \a $dependencyNode which is
     specific to the current handler.
     \param $package The current package.
     \param $dependencyItem Contains all variables for the dependency
     \param $dependencySection The section for the dependency, can be \c 'provide', \c 'require', \c 'obsolete' or \c 'conflict'
    */
    function createDependencyNode( &$package, $export, &$dependencyNode, $dependencyItem, $dependencySection )
    {
    }

    /*!
     \pure
     Parses the XML node \c $dependencyNode and fills in extra information not handled
     by the package parser.
     \param $package The current package.
     \param $dependencyParameters Reference to an array with must be filled with specific data for the current handler.
     \param $dependencySection The section for the dependency, can be \c 'provide', \c 'require', \c 'obsolete' or \c 'conflict'
    */
    function parseDependencyNode( &$package, &$dependencyNode, &$dependencyParameters, $dependencySection )
    {
    }

    /*!
     \virtual
     Creates a text specific for the dependency item \a $dependencyItem and returns it.
     \param $package The current package.
     \param $dependencyItem Associative array with dependency values.
     \param $dependencySection The section for the dependency, can be \c 'provide', \c 'require', \c 'obsolete' or \c 'conflict'
     \return \c false if no special text is required.
    */
    function createDependencyText( &$package, $dependencyItem, $dependencySection )
    {
    }

    /*!
     \pure
     Fills in extra information on the install node \a $installNode which is
     specific to the current handler.
     \param $package The current package.
     \param $installItem Contains all variables for the install
     \param $installType The type of install, can be \c 'install' or \c 'uninstall'
    */
    function createInstallNode( &$package, $export, &$installNode, $installItem, $installType )
    {
    }

    /*!
     \pure
     Parses the XML node \c $installNode and fills in extra information not handled
     by the package parser.
     \param $package The current package.
     \param $installParameters Reference to an array which must be filled with specific data for the current handler.
     \param $isInstall Is \c true if this is an install node, \c false if it is an uninstall node
    */
    function parseInstallNode( &$package, &$installNode, &$installParameters, $isInstall )
    {
    }
}

?>
