<?php
//
// Definition of eZINIAddonPackageHandler class
//
// Created on: <23-Jul-2003 16:11:42 amos>
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

/*! \file ezcontentclasspackagehandler.php
*/

/*!
  \class eZINIAddonPackageHandler ezcontentclasspackagehandler.php
  \brief Handles content classes in the package system

*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/ezpackagehandler.php' );

class eZINIAddonPackageHandler extends eZPackageHandler
{
    /*!
     Constructor
    */
    function eZINIAddonPackageHandler()
    {
        $this->eZPackageHandler( 'eziniaddon',
                                 array( 'extract-install-content' => true ) );
    }

    /*!
     \reimp
     Creates a new override setting for the specified override.

     \param installParameters - optional value
            array( 'site_access_map' => array( <package site access> => <install site access> ) )
    */
    function install( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, $installParameters,
                      &$installData )
    {
        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();

        $siteAccess = $content->attributeValue( 'site-access' );
        if ( isset( $installParameters['site_access_map'] ) &&
             isset( $installParameters['site_access_map'][$siteAccess] ) )
        {
            $siteAccess = $installParameters['site_access_map'][$siteAccess];
        }

        $filename = $content->attributeValue( 'filename' );

        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance( $filename, 'settings', null, null, true );
        $ini->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
        $ini->loadCache();

        $blocks =& $content->elementByName( 'blocks' );
        $blockArray =& $blocks->elementsByName( 'block' );

        foreach ( $blockArray as $block )
        {
            $blockname = $block->attributeValue( 'name' );

            $blockVariableArray = $block->elementsByName( 'block-variable' );
            foreach( $blockVariableArray as $blockVariable )
            {
                $variableName = $blockVariable->attributeValue( 'name' );
                $variableValues = $blockVariable->elementsByName( 'value' );

                if ( count( $variableValues ) == 1 )
                {
                    $value = eZINIAddonPackageHandler::currentID( $variableValues[0], $db );
                    $ini->setVariable( $blockname, $variableName, $value );
                }
                else
                {
                    $valueArray = array();
                    foreach( $variableValues as $variableNode )
                    {
                        $valueName = $variableNode->attributeValue( 'name' );
                        $value = eZINIAddonPackageHandler::currentID( $variableNode, $db );
                        $valueArray[$valueName] = $value;
                    }
                    $ini->setVariable( $blockname, $variableName, $valueArray );
                }
            }
        }

        $ini->save();

        return true;
    }

    /*!
     \static get current id of value node

     \param value DOMNode
     \param db connection
    */
    function &currentID( &$valueNode, &$db )
    {
        $remoteIDType =& $valueNode->attributeValue( 'remote-id' );
        $value =& $valueNode->textContent();

        if ( $remoteIDType !== false )
        {
            switch( $remoteIDType )
            {
                case 'class':
                {
                    $result = $db->arrayQuery( 'SELECT id FROM ezcontentclass WHERE remote_id=\'' . $value . '\'' );
                } break;

                case 'node':
                {
                    $result = $db->arrayQuery( 'SELECT node_id FROM ezcontentobject_tree WHERE remote_id=\'' . $value . '\'' );
                } break;

                case 'object':
                {
                    $result = $db->arrayQuery( 'SELECT id FROM ezcontentobject WHERE remote_id=\'' . $value . '\'' );
                } break;

                default:
                {
                    eZDebug::writeError( 'Unknown remote id type ' . $remoteIDType,
                                         'eZINIAddonPackageHandler::currentID()' );
                } break;
            }

            if ( count( $result ) != 1 )
            {
                eZDebug::writeError( 'Invalid result fetching id from ' . $remoteIDType . ', remote_id: ' . $value,
                                     'eZINIAddonPackageHandler::currentID()' );
            }
            else
            {
                $value = $result[0][0];
            }
        }

        return $value;
    }

    /*!
     \static
     Adds the content of the ini override to the package

     \param package
     \param ini filename, ex: site.ini
     \param iniOverrideArray structure  array( <site_access> => array( <ini_block_name> => array( <ini_block_values> ) ) )
     \param remoteIDArrat structure: array( <class|node|object> => array( <id> => <remote_id> ) )
    */
    function addOverrideAddon( &$package, $filename, &$iniOverrideArray, $remoteIDArray )
    {
        foreach( array_keys( $iniOverrideArray ) as $siteAccess )
        {
            $iniNode =& eZINIAddonPackageHandler::iniDOMTree( $filename, $siteAccess, $iniOverrideArray[$siteAccess], $remoteIDArray );
            if ( !$overrideNode )
            {
                continue;
            }

            $package->appendInstall( 'eziniaddon', false, false, true,
                                     $siteAccess . '-' . $filename, 'eziniaddon',
                                     array( 'content' => $iniNode ) );
            $package->appendInstall( 'eziniaddon', false, false, false,
                                     $siteAccess . '-' . $filename, 'eziniaddon',
                                     array( 'content' => false ) );
        }
    }

    /*!
     \static

     Create DOMNode from inioverride

     \param ini filename
     \param siteaccess
     \param ini values, struct: array( <ini_block_name> => array( <ini_block_values> ) )
     \param remoteID array

     \return DOMNode, false if fails
    */
    function iniDOMTree( $filename, $siteAccess, &$blockArray, $remoteIDArray )
    {
        if ( !$filename || !$siteAccess || !$blockArray )
        {
            return false;
        }

        $iniNode =& eZDOMDocument::createElementNode( 'ini-addon', array( 'site-access' => $siteAccess,
                                                                          'filename' => $filename ) );

        $blocksNode =& eZDOMDocument::createElementNode( 'blocks' );
        $iniNode->appendChild( $blocksNode );
        foreach( array_keys( $blockArray ) as $blockName )
        {
            $block =& $blockArray[$blockName];
            $blockNode =& eZDOMDocument::createElementNode( 'block', array( 'name' => $blockName ) );
            $blocksNode->appendChild( $blockNode );

            foreach( array_keys( $block ) as $blockVariable )
            {
                $variableValue =& $block[$blockVariable];
                $variableNode =& eZDomDocument::createElementNode( 'block-variable', array( 'name' => $blockVariable ) );
                $blockNode->appendChild( $variableNode );

                if ( is_array( $variableValue ) )
                {
                    foreach( array_keys( $variableValue) as $valueName )
                    {
                        $value = $variableValue[$valueName];
                        $valueNode =& eZDomDocument::createElementNode( 'value', array( 'name' => $valueName ) );
                        $variableNode->appendChild( $valueNode );
                        $remoteID = false;
                        if ( is_int( $value ) )
                        {
                            if ( strpos( $valueName, 'class' ) !== false )
                            {
                                $value = $remoteIDArray['class'][(string)$value];
                                $remoteID = 'class';
                            }
                            else if( strpos( $valueName, 'node' ) !== false )
                            {
                                $value = $remoteIDArray['node'][(string)$value];
                                $remoteID = 'node';
                            }
                            else if ( strpos( $valueName, 'object' ) !== false )
                            {
                                $value = $remoteIDArray['class'][(string)$value];
                                $remoteID = 'object';
                            }
                            else
                            {
                                eZDebug::writeNotice( 'Could not interpetit ' . $valueName . ': ' . $value,
                                                      'eZINIAddonPackageHandler::iniDOMTree()' );
                            }
                        }
                        if ( $remoteID )
                        {
                            $valueNode->appendAttribute( eZDomDocument::createAttributeNode( 'remote-id', $remoteID ) );
                        }
                        $valueNode->appendChild( eZDomDocument::createTextNode( $value ) );
                    }
                }
                else
                {
                    $remoteID = false;
                    if ( is_int( $variableValue ) )
                    {
                        if ( strpos( $blockVariable, 'class' ) !== false )
                        {
                            $variableValue = $remoteIDArray['class'][(string)$value];
                            $remoteID = 'class';
                        }
                        else if( strpos( $blockVariable, 'node' ) !== false )
                        {
                            $variableValue = $remoteIDArray['node'][(string)$value];
                            $remoteID = 'node';
                        }
                        else if ( strpos( $blockVariable, 'object' ) !== false )
                        {
                            $variableValue = $remoteIDArray['class'][(string)$value];
                            $remoteID = 'object';
                        }
                        else
                        {
                            eZDebug::writeNotice( 'Could not interpetit ' . $blockVariable . ': ' . $variableValue,
                                                  'eZINIAddonPackageHandler::iniDOMTree()' );
                        }
                    }
                    $valueNode =& eZDomDocument::createElementNode( 'value' );
                    $variableNode->appendChild( $valueNode );
                    if ( $remoteID )
                    {
                        $valueNode->appendAttribute( eZDomDocument::createAttributeNode( 'remote-id', $remoteID ) );
                    }
                    $valueNode->appendChild( eZDomDocument::createTextNode( $variableValue ) );
                }
            }
        }
        return $iniNode;
    }
}
?>
