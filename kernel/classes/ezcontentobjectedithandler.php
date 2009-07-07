<?php
//
// Definition of eZContentObjectEditHandler class
//
// Created on: <20-Dec-2005 14:19:36 hovik>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

/*! \file ezcontentobjectedithandler.php
*/

/*!
  \class eZContentObjectEditHandler ezcontentobjectedithandler.php
  \brief The class eZContentObjectEditHandler provides a framework for handling
  content/edit view specific things in extensions.

*/

class eZContentObjectEditHandler
{
    /*!
     Constructor
    */
    function eZContentObjectEditHandler()
    {
    }

    /*!
     \abstract

     Override this function in the extension to handle edit input parameters.
    */
    function fetchInput( $http, &$module, &$class, $object, &$version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage )
    {
    }

    /*!
     \abstract

     Return list of HTTP postparameters which should trigger store action.
    */
    static function storeActionList()
    {
        return array();
    }

    /*!
     \abstract

     Do content object publish operations.
    */
    function publish( $contentObjectID, $contentObjectVersion )
    {
    }

    /*!
     \static
     Initialize all extension input handler.
    */
    static function initialize()
    {
        $contentINI = eZINI::instance( 'content.ini' );
        foreach( array_unique( $contentINI->variable( 'EditSettings', 'ExtensionDirectories' ) ) as $extensionDirectory )
        {
            $fileName = eZExtension::baseDirectory() . '/' . $extensionDirectory . '/content/' . $extensionDirectory . 'handler.php';
            if ( file_exists( $fileName ) )
            {
                include_once( $fileName );
                $className = $extensionDirectory . 'Handler';
                $storeActionList = call_user_func_array( array( $className, 'storeActionList' ), array() );
                foreach( $storeActionList as $storeAction )
                {
                    eZContentObjectEditHandler::addStoreAction( $storeAction );
                }
            }
            else
            {
                eZDebug::writeError( 'Cound not find content object edit handler ( defined in content.ini ) : ' . $fileName );
            }
        }
    }

    /*!
     \static
     Calls all extension object edit input handler, and executes this the fetchInput function
    */
    static function executeInputHandlers( &$module, &$class, $object, &$version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage )
    {
        $http = eZHTTPTool::instance();
        $contentINI = eZINI::instance( 'content.ini' );
        foreach( array_unique( $contentINI->variable( 'EditSettings', 'ExtensionDirectories' ) ) as $extensionDirectory )
        {
            $fileName = eZExtension::baseDirectory() . '/' . $extensionDirectory . '/content/' . $extensionDirectory . 'handler.php';
            if ( file_exists( $fileName ) )
            {
                include_once( $fileName );
                $className = $extensionDirectory . 'Handler';
                $inputHandler = new $className();
                call_user_func_array( array( $inputHandler, 'fetchInput' ),
                                      array( $http, &$module, &$class, $object, &$version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage ) );
            }
        }
    }

    /*!
     \static
     Calls all publish functions.
    */
    static function executePublish( $contentObjectID, $contentObjectVersion )
    {
        $contentINI = eZINI::instance( 'content.ini' );
        foreach( array_unique( $contentINI->variable( 'EditSettings', 'ExtensionDirectories' ) ) as $extensionDirectory )
        {
            $fileName = eZExtension::baseDirectory() . '/' . $extensionDirectory . '/content/' . $extensionDirectory . 'handler.php';
            if ( file_exists( $fileName ) )
            {
                include_once( $fileName );
                $className = $extensionDirectory . 'Handler';
                $inputHandler = new $className();
                call_user_func_array( array( $inputHandler, 'publish' ),
                                      array( $contentObjectID, $contentObjectVersion ) );
            }
        }
    }

    /*!
     \static
     Set custom HTTP post parameters which should trigger store acrtions.

     \param HTTP post parameter name
    */
    static function addStoreAction( $name )
    {
        if ( !isset( $GLOBALS['eZContentObjectEditHandler_StoreAction'] ) )
        {
            $GLOBALS['eZContentObjectEditHandler_StoreAction'] = array();
        }
        $GLOBALS['eZContentObjectEditHandler_StoreAction'][] = $name;
    }

    /*!
     \static
     Check if any HTTP input trigger store action
    */
    static function isStoreAction()
    {
        if ( !isset( $GLOBALS['eZContentObjectEditHandler_StoreAction'] ) )
            return 0;
        return count( array_intersect( array_keys( $_POST ), $GLOBALS['eZContentObjectEditHandler_StoreAction'] ) ) > 0;
    }
}

?>
