<?php
//
// Definition of eZContentClassPackageHandler class
//
// Created on: <09-Mar-2004 16:11:42 kk>
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

/*! \file ezcontentobjectpackagehandler.php
*/

/*!
  \class eZContentObjectPackageHandler ezcontentobjectpackagehandler.php
  \brief Handles content objects in the package system

*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezpackagehandler.php' );

class eZContentObjectPackageHandler extends eZPackageHandler
{
    /*!
     Constructor
    */
    function eZContentObjectPackageHandler()
    {
        $this->eZPackageHandler( 'ezcontentobject',
                                 array( 'extract-install-content' => true ) );
    }

    /*!
     \reimp
     Returns an explanation for the content object install item.
    */
    function explainInstallItem( &$package, $installItem )
    {
        if ( $installItem['filename'] )
        {
            $filename = $installItem['filename'];
            $subdirectory = $installItem['sub-directory'];
            if ( $subdirectory )
                $filepath = $subdirectory . '/' . $filename . '.xml';
            else
                $filepath = $filename . '.xml';

            $filepath = $package->path() . '/' . $filepath;

            $dom =& $package->fetchDOMFromFile( $filepath );
            if ( $dom )
            {
                $content =& $dom->root();
                $objectName = $content->elementTextContentByName( 'name' );
                return array( 'description' => ezi18n( 'kernel/package', 'Content object %objectname', false,
                                                       array( '%objectname' => $objectName ) ) );
            }
        }
    }

    /*!
     Add Node list to ezcontentobject package handler.

     \param node id
     \param subtree (optional, default true )
    */
    function addNode( $nodeID, $isSubtree = true )
    {
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $this->RootNodeIDArray[] = $nodeID;
        $this->NodeIDArray[] = $nodeID;

        if ( $isSubtree )
        {
            $nodeArray =& eZContentObjectTreeNode::subtree( array( 'AsObject' => false ), $nodeID );
            foreach( $nodeArray as $node )
            {
                $this->NodeIDArray[] = $node['node_id'];
            }
        }
    }

    /*!
     Generate package based on NodeArray and input options

     \param package
     \param options
    */
    function generatePackage( &$package, $options )
    {
        $this->Package =& $package;
        $remoteIDArray = array();
        $this->NodeIDArray = array_unique( $this->NodeIDArray );
        foreach( $this->NodeIDArray as $nodeID )
        {
            $this->NodeObjectArray[(string)$nodeID] = eZContentObjectTreeNode::fetch( $nodeID );
        }

        foreach( $this->RootNodeIDArray as $nodeID )
        {
            $this->RootNodeObjectArray[(string)$nodeID] = eZContentObjectTreeNode::fetch( $nodeID );
        }

        $this->generateObjectArray( $options['node_assignment'] );

        $classIDArray = false;
        if ( $options['include_classes'] )
        {
            $remoteIDArray['class'] = array();
            $classIDArray =& $this->generateClassIDArray();

            include_once( 'kernel/classes/packagehandlers/ezcontentclass/ezcontentclasspackagehandler.php' );
            foreach ( $classIDArray as $classID )
            {
                eZContentClassPackageHandler::addClass( $package, $classID );
            }
        }

        $packageRoot = eZDOMDocument::createElementNode( 'content-object' );

        $objectListDOMNode = $this->createObjectListNode( $options );
        $packageRoot->appendChild( $objectListDOMNode );

        $overrideSettingsArray = false;
        $templateFilenameArray = false;
        if ( $options['include_templates'] )
        {
            $overrideSettingsListNode =& $this->generateOverrideSettingsArray( $options['site_access_array'] );
            $packageRoot->appendChild( $overrideSettingsListNode );

            $designTemplateListNode =& $this->generateTemplateFilenameArray();
            $packageRoot->appendChild( $designTemplateListNode );

            $fetchAliasListNode =& $this->generateFetchAliasArray();
            $packageRoot->appendChild( $fetchAliasListNode );
        }

        $siteAccessListDOMNode = $this->createSiteAccessListNode( $options );
        $packageRoot->appendChild( $siteAccessListDOMNode );

        $topNodeListDOMNode = $this->createTopNodeListDOMNode( $options );
        $packageRoot->appendChild( $topNodeListDOMNode );

        $filename = substr( md5( mt_rand() ), 0, 8 );
        $this->Package->appendInstall( 'ezcontentobject', false, false, true,
                                       $filename, 'ezcontentobject',
                                       array( 'content' => $packageRoot ) );
        $this->Package->appendInstall( 'ezcontentobject', false, false, false,
                                       $filename, 'ezcontentobject',
                                       array( 'content' => false ) );
    }

    /*!
     \private
     Create DOMNode for list of top nodes.

     \param options
    */
    function createTopNodeListDOMNode( $options )
    {
        $topNodeListDOMNode = eZDOMDocument::createElementNode( 'top-node-list' );

        foreach( $this->RootNodeObjectArray as $topNode )
        {
            $topNodeListDOMNode->appendChild( eZDOMDocument::createElementTextNode( 'top-node', $topNode->attribute( 'name' ),
                                                                                    array( 'node-id' => $topNode->attribute( 'node_id' ),
                                                                                           'remote-id' => $topNode->attribute( 'remote_id' ) ) ) );
        }

        return $topNodeListDOMNode;
    }

    /*!
     \private
     Create DOMNode for list of added siteaccesses.

     \param options
    */
    function createSiteAccessListNode( $options )
    {
        $siteAccessListDOMNode = eZDOMDocument::createElementNode( 'site-access-list' );
        foreach( $options['site_access_array'] as $siteAccess )
        {
            $siteAccessListDOMNode->appendChild( eZDOMDocument::createElementTextNode( 'site-access', $siteAccess ) );
        }

        return $siteAccessListDOMNode;
    }

    /*!
     \private
     Serializes and adds all contentobjects to package

     \param options
    */
    function createObjectListNode( $options )
    {
        if ( $options['versions'] == 'current' )
        {
            $version = true;
        }
        else
        {
            $version = false;
        }

        $objectArrayNode =& eZDOMDocument::createElementNode( 'object-list' );
        foreach( array_keys( $this->ObjectArray ) as $objectID )
        {
            $objectArrayNode->appendChild( $this->ObjectArray[$objectID]->serialize( $this->Package, $version, $options, $this->NodeObjectArray, $this->RootNodeIDArray ) );
        }

        return $objectArrayNode;
    }

    /*!
     \private
     Generate list of content objects to export, and store them to

     \param node_assignments, 'selected' or 'main'
    */
    function &generateObjectArray( $nodeAssignment )
    {
        foreach( array_keys( $this->NodeObjectArray ) as $key )
        {
            $contentNode =& $this->NodeObjectArray[$key];
            if ( $nodeAssignment == 'main' )
            {
                if ( $contentNode->attribute( 'main_node_id' ) == $contentNode->attribute( 'node_id' ) )
                {
                    $this->ObjectArray[(string)$contentNode->attribute( 'contentobject_id' )] =& $contentNode->object();
                }
            }
            else
            {
                $this->ObjectArray[(string)$contentNode->attribute( 'contentobject_id' )] =& $contentNode->object();
            }
        }
    }

    /*!
      \private
    */
    function &generateFetchAliasArray()
    {
        $fetchAliasListDOMNode = eZDOMDocument::createElementNode( 'fetch-alias-list' );

        foreach( array_keys( $this->TemplateFileArray ) as $siteAccess )
        {
            $aliasINI = eZINI::instance( 'fetchalias.ini', 'settings', null, null, true );
            $aliasINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            $aliasINI->loadCache();

            foreach ( $this->TemplateFileArray[$siteAccess] as $filename )
            {
                $fp = fopen( $filename, 'r' );
                if ( !$fp )
                {
                    eZDebug::writeError( 'Could not open ' . $filename . ' during content object export.',
                                         'eZContentObjectPackageHandler::generateFethAliasArray()' );
                    continue;
                }

                $str = fread( $fp, filesize( $filename ) );

                $matchArray = array();
                preg_match_all( "#.*fetch_alias\([ ]*([a-zA-Z0-9_]+)[ |,|)]+.*#U", $str, $matchArray, PREG_PATTERN_ORDER );

                foreach( $matchArray[1] as $fetchAlias )
                {
                    $fetchAliasDOMNode = eZDOMDocument::createElementNode( 'fetch-alias', array( 'name' => $fetchAlias,
                                                                                                 'site-access' => $siteAccess ) );

                    $fetchBlock =& $aliasINI->group( $fetchAlias );
                    foreach ( $fetchBlock['Constant'] as $matchKey => $value )
                    {
                        if ( strpos( $matchKey, 'class_' ) === 0 )
                        {
                            $contentClass = eZContentClass::fetch( $value );
                            $fetchBlock['Constant']['class_remote_id'] = $contentClass->attribute( 'remote_id' );
                        }
                        if ( strpos( $matchKey, 'node_' ) === 0 )
                        {
                            $contentTreeNode = eZContentObjectTreeNode::fetch( $value );
                            $fetchBlock['Constant']['node_remote_id'] = $contentTreeNode->attribute( 'remote_id' );
                        }
                        if ( strpos( $matchKey, 'parent_node_' ) === 0 )
                        {
                            $contentTreeNode = eZContentObjectTreeNode::fetch( $value );
                            $fetchBlock['Constant']['parent_node_remote_id'] = $contentTreeNode->attribute( 'remote_id' );
                        }
                        if ( strpos( $matchKey, 'object_' ) === 0 )
                        {
                            $contentObject = eZContentObject::fetch( $value );
                            $fetchBlock['Constant']['object_remote_id'] = $contentObject->attribute( 'remote_id' );
                        }
                    }
                    $fetchAliasDOMNode->appendChild( eZDOMDocument::createElementNodeFromArray( $fetchAlias,  $fetchBlock ) );
                    $fetchAliasListDOMNode->appendChild( $fetchAliasDOMNode );
                }
            }
        }
        return $fetchAliasListDOMNode;
    }

    /*!
     \private
    */
    function &generateTemplateFilenameArray()
    {
        $templateListDOMNode = eZDOMDocument::createElementNode( 'template-list' );

        include_once( 'kernel/common/eztemplatedesignresource.php' );

        foreach( array_keys( $this->OverrideSettingsArray ) as $siteAccess )
        {
            $this->TemplateFileArray[$siteAccess] = array();
            $overrideArray =& eZTemplateDesignResource::overrideArray( $siteAccess );

            foreach( $this->OverrideSettingsArray[$siteAccess] as $override )
            {
                $customMatcheArray = $overrideArray['/' . $override['Source']]['custom_match'];

                foreach( $customMatcheArray as $customMatch )
                {
                    if ( $customMatch['conditions'] == null )
                    {
                        $templateListDOMNode->appendChild( $this->createDOMNodeFromFile( $customMatch['match_file'], $siteAccess, 'design' ) );
                        $this->TemplateFileArray[$siteAccess][] = $customMatch['match_file'];
                    }
                    else if ( count( array_diff( $customMatch['conditions'], $override['Match'] ) ) == 0 &&
                              count( array_diff( $override['Match'], $customMatch['conditions'] ) ) == 0 )
                    {
                        $templateListDOMNode->appendChild( $this->createDOMNodeFromFile( $customMatch['match_file'], $siteAccess, 'design' ) );
                        $this->TemplateFileArray[$siteAccess][] = $customMatch['match_file'];
                    }
                }
            }
        }
        return $templateListDOMNode;

        //TODO : add templates included in templates here.
    }

    /*!
     \private
     Add file to repository and return DONNode description of file

     \param filename
     \param siteaccess
     \param filetype (optional)
    */
    function createDOMNodeFromFile( $filename, $siteAccess, $filetype = false )
    {
        $fileAttributes = array( 'site-access' => $siteAccess );
        if ( $filetype !== false )
        {
            $fileAttributes['file-type'] = $filetype;
        }

        $path = substr( $filename, strpos( $filename, '/', 7 ) );

        $fileDOMNode = eZDOMDocument::createElementNode( 'file', $fileAttributes );
        $fileDOMNode->appendChild( eZDOMDocument::createElementTextNode( 'original-path', $filename ) );
        $fileDOMNode->appendChild( eZDOMDocument::createElementTextNode( 'path', $path ) );

        eZDir::mkdir( eZDir::dirpath( $this->Package->packageRepositoryPath() . '/' .  eZContentObjectPackageHandler::contentObjectDirectory() . '/' . $path ),  eZDir::directoryPermission(),  true );
        eZFileHandler::copy( $filename, $this->Package->packageRepositoryPath() . '/' . eZContentObjectPackageHandler::contentObjectDirectory() . '/' . $path );

        return $fileDOMNode;
    }

    /*!
     \private
     Get all template overrides used by exported objects

     \param site access array
    */
    function &generateOverrideSettingsArray( $siteAccessArray )
    {
        foreach ( $siteAccessArray as $siteAccess )
        {
            $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
            $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            $overrideINI->loadCache();

            $matchBlock = false;
            $blockMatchArray = array();

            foreach( array_keys( $this->NodeObjectArray ) as $nodeID )
            {
                $contentNode =& $this->NodeObjectArray[$nodeID];
                foreach( array_keys( $overrideINI->groups() ) as $blockName )
                {
                    if ( isset( $blockMatchArray[$blockName] ) )
                    {
                        continue;
                    }

                    $matchSettings = $overrideINI->variable( $blockName, 'Match' );

                    $matchValue = array();
                    $validMatch = true;
                    foreach( array_keys( $matchSettings ) as $matchType )
                    {
                        switch( $matchType )
                        {
                            case 'object':
                            {
                                if ( $contentNode->attribute( 'contentobject_id' ) != $matchSettings[$matchType] )
                                {
                                    $validMatch = false;
                                }
                                else
                                {
                                    $contentObject = $contentNode->attribute( 'object' );
                                    $matchValue[$this->OverrideObjectRemoteID] = $contentObject->attribute( 'remote_id' );
                                }
                            } break;

                            case 'node':
                            {
                                if ( $nodeID != $matchSettings[$matchType] )
                                {
                                    $validMatch = false;
                                }
                                else
                                {
                                    $matchValue[$this->OverrideNodeRemoteID] = $contentNode->attribute( 'remote_id' );
                                }
                            } break;

                            case 'parent_node':
                            {
                                if ( $contentNode->attribute( 'parent_node_id' ) != $matchSettings[$matchType] )
                                {
                                    $validMatch = false;
                                }
                                else
                                {
                                    $parentNode = $contentNode->attribute( 'parent' );
                                    $matchValue[$this->OverrideParentNodeRemoteID] = $parentNode->attribute( 'remote_id' );
                                }
                            } break;

                            case 'class':
                            {
                                $contentObject =& $contentNode->attribute( 'object' );
                                if ( $contentObject->attribute( 'contentclass_id' ) != $matchSettings[$matchType] )
                                {
                                    $validMatch = false;
                                }
                                else
                                {
                                    $contentClass = $contentObject->attribute( 'content_class' );
                                    $matchValue[$this->OverrideClassRemoteID] = $contentClass->attribute( 'remote_id' );
                                }
                            } break;

                            case 'class_identifier':
                            {
                                $contentObject =& $contentNode->attribute( 'object' );
                                if ( $contentObject->attribute( 'class_identifier' ) != $matchSettings[$matchType] )
                                {
                                    $validMatch = false;
                                }
                            } break;

                            case 'section':
                            {
                                $contentObject =& $contentNode->attribute( 'object' );
                                if ( $contentObject->attribute( 'section_id' ) != $matchSettings[$matchType] )
                                {
                                    $validMatch = false;
                                }
                            } break;

                            case 'depth':
                            {
                                if ( $contentNode->attribute( 'depth' ) != $matchSettings[$matchType] )
                                {
                                    $validMatch = false;
                                }
                            } break;
                        }

                        if ( !$validMatch )
                        {
                            break;
                        }
                    }

                    if ( $validMatch )
                    {
                        $blockMatchArray[$blockName] = array_merge( $overrideINI->group( $blockName ),
                                                                    $matchValue );
                    }
                }
            }
            $this->OverrideSettingsArray[$siteAccess] = $blockMatchArray;
        }

        $overrideSettingsListDOMNode = eZDOMDocument::createElementNode( 'override-list' );
        foreach ( $this->OverrideSettingsArray as $siteAccess => $blockMatchArray )
        {
            foreach( $blockMatchArray as $blockName => $iniGroup )
            {
                $blockMatchNode =& eZDOMDocument::createElementNode( 'block', array( 'name' => $blockName,
                                                                                    'site-access' => $siteAccess ) );
                $blockMatchNode->appendChild( eZDOMDocument::createElementNodeFromArray( $blockName,  $iniGroup ) );
                $overrideSettingsListDOMNode->appendChild( $blockMatchNode );
            }
        }

        return $overrideSettingsListDOMNode;
    }

    /*!
     \private
     Get list of all class objects used in by the nodes in NodeArray
    */
    function &generateClassIDArray()
    {
        $classIDArray = array();
        foreach( array_keys( $this->NodeObjectArray ) as $key )
        {
            $contentObject =& $this->NodeObjectArray[$key]->object();
            $classIDArray[] = $contentObject->attribute( 'contentclass_id' );
        }

        return array_unique( $classIDArray );
    }

    /*!
     \reimp
     Uninstalls all previously installed content classes.
    */
    function uninstall( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      $installParameters,
                      &$installData )
    {
        //TODO
    }

    /*!
     \reimp
     Creates a new contentclass as defined in the xml structure.
    */
    function install( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, $installParameters,
                      &$installData )
    {
        $this->Package =& $package;
        $this->installContentObjects( $content->elementByName( 'object-list' ),
                                      $content->elementByName( 'top-node-list' ),
                                      $installParameters );

        $this->installTemplates( $content->elementByName( 'template-list' ),
                                 $package,
                                 $subdirectory,
                                 $installParameters );

        $this->installOverrides( $content->elementByName( 'override-list' ),
                                 $installParameters );

        $this->installFetchAliases( $content->elementByName( 'fetch-alias-list' ),
                                    $installParameters );
    }

    /*!
     \private

     Serialize and install content objects

     \param object-list DOMNode
     \param install parameters
    */
    function installContentObjects( $objectListNode, $topNodeListNode, $installParameters )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        foreach( $objectListNode->elementsByName( 'object' ) as $objectNode )
        {
            eZContentobject::unserialize( $this->Package, $objectNode, $installParameters, eZUser::currentUserID() );
        }
    }

    /*!
     \private

     Set and install templates

     \param template list
     \param package
     \param subdirectory
     \param install parameters.
    */
    function installTemplates( $templateList, &$package, $subdirectory, $installParameters )
    {
        if ( !$templateList )
        {
            return;
        }
        include_once( 'kernel/common/eztemplatedesignresource.php' );

        $siteAccessDesignPathArray = array();
        $templateRootPath = $package->path() . '/' . $subdirectory;
        foreach( $templateList->elementsByName( 'file' ) as $fileNode )
        {
            $originalSiteAccess = $fileNode->attributeValue( 'site-access' );
            $newSiteAccess = $installParameters['site_access_map'][$originalSiteAccess];
            if ( !isset( $siteAccessDesignPathArray[$newSiteAccess] ) )
            {
                $ini =& eZINI::instance( 'site.ini', 'settings', null, null, true );
                $ini->prependOverrideDir( "siteaccess/$newSiteAccess", false, 'siteaccess' );
                $ini->loadCache();

                $siteAccessDesignPathArray[$newSiteAccess] = eZTemplateDesignResource::designStartPath() . '/' . $ini->variable( "DesignSettings", "StandardDesign" );
            }

            $sourcePath = $templateRootPath . $fileNode->elementTextContentByName('path');
            $destinationPath = $siteAccessDesignPathArray[$newSiteAccess] . $fileNode->elementTextContentByName('path');

            eZDir::mkdir( eZDir::dirpath( $destinationPath ), false, true );
            eZFileHandler::copy( $sourcePath, $destinationPath );

            eZDebug::writeNotice( 'Copied: "' . $sourcePath . '" to: "' . $destinationPath . '"',
                                  'eZContentObjectPackageHandler::installTemplates()' );
        }
    }

    /*!
     \private

     Install overrides

     \param override list
     \param install parameters
    */
    function installOverrides( $overrideListNode, $parameters )
    {
        if ( !$overrideListNode )
        {
            return;
        }

        $overrideINIArray = array();
        foreach( $overrideListNode->elementsByName( 'block' ) as $blockNode )
        {
            $newSiteAccess = $parameters['site_access_map'][$blockNode->attributeValue( 'site-access' )];
            if ( !$newSiteAccess )
            {
                eZDebug::writeError( 'SiteAccess map for : ' . $blockNode->attributeValue( 'site-access' ) . ' not set.',
                                     'eZContentObjectPackageHandler::installOverrides()' );
                continue;
            }

            if ( !isset( $overrideINIArray[$newSiteAccess] ) )
            {
                $overrideINIArray[$newSiteAccess] = eZINI::instance( 'override.ini.append.php', "settings/siteaccess/$newSiteAccess", null, null, true );
            }

            $blockArray = array();
            $blockName = $blockNode->attributeValue( 'name' );
            $blockArray[$blockName] = eZDOMDocument::createArrayFromDOMNode( $blockNode->elementByName( $blockName ) );
            if ( isset( $blockArray[$blockName][$this->OverrideObjectRemoteID] ) )
            {
                $contentObject = eZContentObject::fetchByRemoteID( $blockArray[$blockName][$this->OverrideObjectRemoteID] );
                $blockArray[$blockName]['Match']['object'] = $contentObject->attribute( 'id' );
                unset( $blockArray[$blockName][$this->OverrideObjectRemoteID] );
                eZDebug::writeNotice( 'Found object id: "' . $blockArray[$blockName]['Match']['object'] . '" for matchblock "[' . $blockName . '][Match][object]"',
                                      'eZContentObjectPackageHandler::installOverrides()' );
            }
            if ( isset( $blockArray[$blockName][$this->OverrideNodeRemoteID] ) )
            {
                $contentNode = eZContentObjectTreeNode::fetchByRemoteID( $blockArray[$blockName][$this->OverrideNodeRemoteID] );
                $blockArray[$blockName]['Match']['node'] = $contentNode->attribute( 'node_id' );
                unset( $blockArray[$blockName][$this->OverrideNodeRemoteID] );
                eZDebug::writeNotice( 'Found node id: "' . $blockArray[$blockName]['Match']['node'] . '" for matchblock "[' . $blockName . '][Match][node]"',
                                      'eZContentObjectPackageHandler::installOverrides()' );
            }
            if ( isset( $blockArray[$blockName][$this->OverrideParentNodeRemoteID] ) )
            {
                $parentContentNode = eZContentObjectTreeNode::fetchByRemoteID( $blockArray[$blockName][$this->OverrideParentNodeRemoteID] );
                $blockArray[$blockName]['Match']['parent_node'] = $parentContentNode->attribute( 'node_id' );
                unset( $blockArray[$blockName][$this->OverrideParentNodeRemoteID] );
                eZDebug::writeNotice( 'Found parent node id: "' . $blockArray[$blockName]['Match']['parent_node'] . '" for matchblock "[' . $blockName . '][Match][parent_node]"',
                                      'eZContentObjectPackageHandler::installOverrides()' );
            }
            if ( isset( $blockArray[$blockName][$this->OverrideClassRemoteID] ) )
            {
                $contentClass = eZContentClass::fetchByRemoteID( $blockArray[$blockName][$this->OverrideClassRemoteID] );
                if ( !$contentClass )
                {
                    eZDebug::writeError( 'No content class found for RemoteID: ' . $blockArray[$blockName][$this->OverrideClassRemoteID],
                                         'eZContentObjectPackageHandler::installOverrides()' );
                    continue;
                }
                $blockArray[$blockName]['Match']['class'] = $contentClass->attribute( 'id' );
                unset( $blockArray[$blockName][$this->OverrideClassRemoteID] );
                eZDebug::writeNotice( 'Found class id: "' . $blockArray[$blockName]['Match']['class'] . '" for matchblock "[' . $blockName . '][Match][class]"',
                                      'eZContentObjectPackageHandler::installOverrides()' );
            }

            $overrideINIArray[$newSiteAccess]->setVariables( $blockArray );
        }

        foreach( $overrideINIArray as $siteAccess => $iniArray )
        {
            $overrideINIArray[$siteAccess]->save();
        }
    }

    /*!
     \private

     Install fetch alias overrides

     \param fetch alias  list
     \param install parameters
    */
    function installFetchAliases( $fetchAliasListNode, $parameters )
    {
        if ( !$fetchAliasListNode )
        {
            return;
        }

        $fetchAliasINIArray = array();
        foreach( $fetchAliasListNode->elementsByName( 'fetch-alies' ) as $blockNode )
        {
            $newSiteAccess = $parameters['site_access_map'][$blockNode->attributeValue( 'site-access' )];
            if ( !isset( $fetchAliasINIArray[$newSiteAccess] ) )
            {
                $fetchAliasINIArray[$newSiteAccess] = eZINI::instance( 'fetchalias.ini.append.php', "settings/siteaccess/$newSiteAccess", null, null, true );
            }

            $blockArray = array();
            $blockName = $blockNode->attributeValue( 'name' );
            $blockArray[$blockName] = eZDOMDocument::createArrayFromDOMNode( $blockNode->elementByName( $blockName ) );

            foreach( $blockArray[$blockName]['Constant'] as $matchKey => $value )
            {
                if ( strpos( $matchKey, 'class_' ) === 0 )
                {
                    $contentClass = eZContentClass::fetchByRemoteID( $blockArray[$blockName]['Constant']['class_remote_id'] );
                    $blockArray[$blockName]['Constant'][$matchKey] = $contentClass->attribute( 'id' );
                    unset( $blockArray[$blockName]['Constant']['class_remote_id'] );
                }
                if( strpos( $matchKey, 'node_' ) === 0 )
                {
                    $contentTreeNode = eZContentObjectTreeNode::fetchByRemoteID( $blockArray[$blockName]['Constant']['node_remote_id'] );
                    $blockArray[$blockName]['Constant'][$matchKey] = $contentTreeNode->attribute( 'node_id' );
                    unset( $blockArray[$blockName]['Constant']['node_remote_id'] );
                }
                if( strpos( $matchKey, 'parent_node_' ) === 0 )
                {
                    $contentTreeNode = eZContentObjectTreeNode::fetchByRemoteID( $blockArray[$blockName]['Constant']['parent_node_remote_id'] );
                    $blockArray[$blockName]['Constant'][$matchKey] = $contentTreeNode->attribute( 'node_id' );
                    unset( $blockArray[$blockName]['Constant']['parent_node_remote_id'] );
                }
                if( strpos( $matchKey, 'object_' ) === 0 )
                {
                    $contentObject = eZContentObject::fetchByRemoteID( $blockArray[$blockName]['Constant']['object_remote_id'] );
                    $blockArray[$blockName]['Constant'][$matchKey] = $contentTreeNode->attribute( 'id' );
                    unset( $blockArray[$blockName]['Constant']['object_remote_id'] );
                }
            }

            $fetchAliasINIArray[$newSiteAccess]->setVariables( $blockArray );
        }

        foreach( $fetchAliasINIArray as $siteAccess => $iniFetchAlias )
        {
            $fetchAliasINIArray[$siteAccess]->save();
        }
    }

    /*!
     \reimp
    */
    function add( $packageType, &$package, &$cli, $parameters )
    {
        //TODO, command line support
    }

    /*!
     \reimp
    */
    function handleAddParameters( $packageType, &$package, &$cli, $arguments )
    {
        //TODO, command line support
    }

    function contentObjectDirectory()
    {
        return 'ezcontentobject';
    }

    /*!
     \reimp
    */
    function createInstallNode( &$package, $export, &$installNode, $installItem, $installType )
    {
        if ( $installNode->attributeValue( 'type' ) == 'ezcontentobject' )
        {
            if ( $export )
            {
                $objectFile = $installItem['filename'] . '.xml';

                if ( $installItem['sub-directory'] )
                    $objectFile = $installItem['sub-directory'] . '/' . $objectFile;
                $originalPath = $package->path() . '/' . $objectFile;
                $exportPath = $export['path'];
                $installDirectory = $exportPath . '/' . eZContentObjectPackageHandler::contentObjectDirectory();
                if ( !file_exists(  $installDirectory ) )
                    eZDir::mkdir( $installDirectory, eZDir::directoryPermission(), true );

                include_once( 'lib/ezfile/classes/ezfile.php' );
                $eZXML = new eZXML();
                $domDocument = $eZXML->domTree( eZFile::getContents( $originalPath ) );
                $rootNode = $domDocument->root();
                $templateListNode = $rootNode->elementByName( 'template-list' );
                foreach( $templateListNode->elementsByName( 'file' ) as $fileNode )
                {
                    $newFilePath = $installDirectory . $fileNode->elementTextContentByName( 'path' );
                    if ( !file_exists( eZDir::dirpath( $newFilePath ) ) )
                    {
                        eZDir::mkdir( eZDir::dirpath( $newFilePath ), eZDir::directoryPermission(), true );
                    }
                    eZFileHandler::copy( $package->path() . '/' . eZContentObjectPackageHandler::contentObjectDirectory() . $fileNode->elementTextContentByName( 'path' ),
                                         $newFilePath );
                }
                eZFileHandler::copy( $originalPath, $installDirectory . '/' . $installItem['filename'] . '.xml' );
            }

        }
    }

    var $NodeIDArray = array();
    var $RootNodeIDArray = array();
    var $NodeObjectArray = array();
    var $ObjectArray = array();
    var $RootNodeObjectArray = array();
    var $OverrideSettingsArray = array();
    var $TemplateFileArray = array();
    var $Package = null;

    // Static class variables - replacing match values in override.ini
    var $OverrideObjectRemoteID = 'content_object_remote_id';
    var $OverrideNodeRemoteID = 'content_node_remote_id';
    var $OverrideParentNodeRemoteID = 'parent_content_node_remote_id';
    var $OverrideClassRemoteID = 'content_class_remote_id';
}

?>
