<?php
/**
 * File containing the eZINIAddonPackageHandler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZINIAddonPackageHandler ezcontentclasspackagehandler.php
  \brief Handles content classes in the package system

*/

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
     Creates a new override setting for the specified override.

     \param installParameters - optional value
            array( 'site_access_map' => array( <package site access> => <install site access> ) )
    */
    function install( $package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      $content, $installParameters,
                      &$installData )
    {
        $db = eZDB::instance();

        $siteAccess = $content->getAttribute( 'site-access' );
        if ( isset( $installParameters['site_access_map'] ) &&
             isset( $installParameters['site_access_map'][$siteAccess] ) )
        {
            $siteAccess = $installParameters['site_access_map'][$siteAccess];
        }

        $filename = $content->getAttribute( 'filename' );

        $ini = eZINI::instance( $filename, 'settings', null, null, true );
        $ini->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
        $ini->loadCache();

        $blocks =& $content->elementByName( 'blocks' );
        $blockArray =& $blocks->getElementsByTagName( 'block' );

        foreach ( $blockArray as $block )
        {
            $blockname = $block->getAttribute( 'name' );

            $blockVariableArray = $block->getElementsByTagName( 'block-variable' );
            foreach( $blockVariableArray as $blockVariable )
            {
                $variableName = $blockVariable->getAttribute( 'name' );
                $variableValues = $blockVariable->getElementsByTagName( 'value' );

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
                        $valueName = $variableNode->getAttribute( 'name' );
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
    static function currentID( $valueNode, $db )
    {
        $remoteIDType = $valueNode->getAttribute( 'remote-id' );
        $value = $valueNode->textContent;

        if ( $remoteIDType !== false )
        {
            switch( $remoteIDType )
            {
                case 'class':
                {
                    $result = $db->arrayQuery( 'SELECT id FROM ezcontentclass WHERE remote_id=\'' . $db->escapeString( $value ) . '\'' );
                } break;

                case 'node':
                {
                    $result = $db->arrayQuery( 'SELECT node_id FROM ezcontentobject_tree WHERE remote_id=\'' . $db->escapeString( $value ) . '\'' );
                } break;

                case 'object':
                {
                    $result = $db->arrayQuery( 'SELECT id FROM ezcontentobject WHERE remote_id=\'' . $db->escapeString( $value ) . '\'' );
                } break;

                default:
                {
                    eZDebug::writeError( 'Unknown remote id type ' . $remoteIDType, __METHOD__ );
                } break;
            }

            if ( count( $result ) != 1 )
            {
                eZDebug::writeError( 'Invalid result fetching id from ' . $remoteIDType . ', remote_id: ' . $value, __METHOD__ );
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
    function addOverrideAddon( $package, $filename, &$iniOverrideArray, $remoteIDArray )
    {
        foreach( array_keys( $iniOverrideArray ) as $siteAccess )
        {
            $iniNode = eZINIAddonPackageHandler::iniDOMTree( $filename, $siteAccess, $iniOverrideArray[$siteAccess], $remoteIDArray );
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

        $iniNode = eZDOMDocument::createElementNode( 'ini-addon', array( 'site-access' => $siteAccess,
                                                                          'filename' => $filename ) );

        $blocksNode = eZDOMDocument::createElementNode( 'blocks' );
        $iniNode->appendChild( $blocksNode );
        foreach( array_keys( $blockArray ) as $blockName )
        {
            $block =& $blockArray[$blockName];
            unset( $blockNode );
            $blockNode = eZDOMDocument::createElementNode( 'block', array( 'name' => $blockName ) );
            $blocksNode->appendChild( $blockNode );

            foreach( array_keys( $block ) as $blockVariable )
            {
                $variableValue =& $block[$blockVariable];
                unset( $variableNode );
                $variableNode = eZDomDocument::createElementNode( 'block-variable', array( 'name' => $blockVariable ) );
                $blockNode->appendChild( $variableNode );

                if ( is_array( $variableValue ) )
                {
                    foreach( array_keys( $variableValue) as $valueName )
                    {
                        $value = $variableValue[$valueName];
                        unset( $valueNode );
                        $valueNode = eZDomDocument::createElementNode( 'value', array( 'name' => $valueName ) );
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
                                eZDebug::writeNotice( 'Could not interpret ' . $valueName . ': ' . $value, __METHOD__ );
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
                            eZDebug::writeNotice( 'Could not interpret ' . $blockVariable . ': ' . $variableValue, __METHOD__ );
                        }
                    }
                    unset( $valueNode );
                    $valueNode = eZDomDocument::createElementNode( 'value' );
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
