<?php
//
// Definition of eZPackageHandler class
//
// Created on: <01-Aug-2003 17:11:07 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
    function install( $package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      $content, &$installParameters,
                      &$installData )
    {
    }

    /*!
     \pure
     Uninstalls the package item
    */
    function uninstall( $package, $installType, $parameters,
                        $name, $os, $filename, $subdirectory,
                        $content, &$installParameters,
                        &$installData )
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
     Returns an explanation (human readable) for the install item \a $installItem.
     The explanation is an array with the following items.
     - description - Textual description of what will be installed

     Use $requestedInfo to request portion of info.

     \note This must be implemented for package handlers.
    */
    function explainInstallItem( $package, $installItem, $requestedInfo = array() )
    {
    }

    /*!
     \pure
     Adds items defined in \a $parameters to the package \a $package.
    */
    function add( $packageType, $package, $cli, $parameters )
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
    function handleAddParameters( $packageType, $package, $cli, $arguments )
    {
    }

//     /*!
//      \pure
//      Inteprets the parameters defined in \a $parameters and adds items to \a $package.
//     */
//     function handle( $package, $parameters )
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
    function createDependencyNode( $package, &$dependencyNode, $dependencyItem, $dependencySection )
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
    function parseDependencyNode( $package, &$dependencyNode, &$dependencyParameters, $dependencySection )
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
    function createDependencyText( $package, $dependencyItem, $dependencySection )
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
    function createInstallNode( $package, $installNode, $installItem, $installType )
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
    function parseInstallNode( $package, $installNode, &$installParameters, $isInstall )
    {
    }

    /*!
        Helper function to process install errors.
        Decides to skip current element or not when cycling thru elements
        Also skips element where error has occured if action is not choosen
    */

    function isErrorElement( $elementID, &$installParameters )
    {
        if ( $elementID == $installParameters['error']['element_id'] )
        {
            // If action not set - skip this element
            if ( !isset( $installParameters['error']['choosen_action'] ) )
            {
                 $installParameters['error'] = array();
                 return false;
            }
            return true;
        }
        return false;

    }

    /*!
        Helper function to process install errors.
        \return choosen action code.
        If $resetError is false, array should be manually reset in handler.
    */

    static function errorChoosenAction( $errorCode, &$installParameters, $description, $handlerType, $resetError = true )
    {
        if ( isset( $installParameters['non-interactive'] ) && $installParameters['non-interactive'] )
        {
            if ( $description )
            {
                eZDebug::writeNotice( $description, 'Package installation conflict' );
            }
            return eZPackage::NON_INTERACTIVE;
        }

        if ( isset( $installParameters['error_default_actions'][$handlerType][$errorCode] ) )
        {
            if ( $resetError && count( $installParameters['error'] ) )
                $installParameters['error'] = array();
            return $installParameters['error_default_actions'][$handlerType][$errorCode];
        }

        if ( isset( $installParameters['error']['error_code'] ) &&
             $installParameters['error']['error_code'] == $errorCode )
        {
            if ( isset( $installParameters['error']['choosen_action'] ) )
            {
                $choosenAction = $installParameters['error']['choosen_action'];
                if ( $resetError )
                    $installParameters['error'] = array();
                return $choosenAction;
            }
        }
        return false;
    }

}

?>
