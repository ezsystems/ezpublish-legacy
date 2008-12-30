<?php
//
// Definition of eZContentClassPackageHandler class
//
// Created on: <09-Mar-2004 16:11:42 kk>
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

/*! \file ezcontentobjectpackagehandler.php
*/

/*!
  \class eZContentObjectPackageHandler ezcontentobjectpackagehandler.php
  \brief Handles content objects in the package system

*/

class eZContentObjectPackageHandler extends eZPackageHandler
{
    const MAX_LISTED_OBJECTS = 30;

    // If number of objects in the package is bigger than this constant,
    // they are stored in separate files to prevent memory overflow.
    // 'null' means always use separate files
    const STORE_OBJECTS_TO_SEPARATE_FILES_THRESHOLD = 100;

    const INSTALL_OBJECTS_ERROR_RANGE_FROM = 1;
    const INSTALL_OBJECTS_ERROR_RANGE_TO = 100;
    const UNINSTALL_OBJECTS_ERROR_RANGE_FROM = 101;
    const UNINSTALL_OBJECTS_ERROR_RANGE_TO = 200;

    /*!
     Constructor
    */
    function eZContentObjectPackageHandler()
    {
        $this->eZPackageHandler( 'ezcontentobject',
                                 array( 'extract-install-content' => true ) );
    }

    /*!
       Fetches object stored in separate xml file
    */
    function fetchObjectFromFile( $objectFileNode )
    {
        $fileName = $objectFileNode->getAttribute( 'filename' );
        $filePath = $this->Package->path() . '/' . $this->contentObjectDirectory() . '/' . $fileName;
        $dom = $this->Package->fetchDOMFromFile( $filePath );

        if ( $dom )
        {
            $objectNode = $dom->documentElement;
        }
        else
        {
            eZDebug::writeError( "Can't fetch object from package file: $filePath", 'eZContentObjectPackageHandler::getObjectNodeFromFile' );
            $objectNode = false;
        }

        return $objectNode;
    }

    function getRealObjectNode( $objectNode )
    {
        if ( $objectNode->localName == 'object' )
        {
            $realObjectNode = $objectNode;
        }
        else
        {
            $realObjectNode = $this->fetchObjectFromFile( $objectNode );
        }
        return $realObjectNode;
    }

    /*!
     Returns an explanation for the content object install item.

     The explanaition is actually a list having the following structure:
          array( array( 'description' => 'Content object Foo' ),
                 array( 'description' => 'Content object Bar' ),
                 array( 'description' => 'Content object Baz' ) );

     When number of items in the above list is too high,
     the following array is returned instead:
         array( 'description' => 'NNN content objects' );


    */
    function explainInstallItem( $package, $installItem, $requestedInfo = array() )
    {
        $this->Package = $package;

        if ( $installItem['filename'] )
        {
            $filename = $installItem['filename'];
            $subdirectory = $installItem['sub-directory'];
            if ( $subdirectory )
                $filepath = $subdirectory . '/' . $filename . '.xml';
            else
                $filepath = $filename . '.xml';

            $filepath = $package->path() . '/' . $filepath;

            $dom = $package->fetchDOMFromFile( $filepath );

            if ( !$dom )
                return null;

            $content = $dom->documentElement;
            $objectListNode = $content->getElementsByTagName( 'object-list' )->item( 0 );
            if ( $objectListNode )
            {
                $realObjectNodes = $objectListNode->getElementsByTagName( 'object' );
            }
            else
            {
                // If objects are stored in separate files (new format)
                $objectListNode = $content->getElementsByTagName( 'object-files-list' )->item( 0 );
                $objectNodes = $objectListNode->getElementsByTagName( 'object-file' );

                if ( count( $objectNodes ) > self::MAX_LISTED_OBJECTS )
                {
                    return array( 'description' => ezi18n( 'kernel/package', '%number content objects', false,
                                                           array( '%number' => count( $objectNodes ) ) ) );
                }

                $realObjectNodes = array();
                foreach( $objectNodes as $objectNode )
                {
                    $realObjectNode = $this->fetchObjectFromFile( $objectNode );
                    if ( !$realObjectNode )
                        continue;

                    $realObjectNodes[] = $realObjectNode;
                }
            }

            // create descriptions array
            $objectNames = array();
            foreach( $realObjectNodes as $objectNode )
            {
                $objectName =
                    $objectNode->getAttribute( 'name' ) .
                    ' (' . $objectNode->getAttributeNS( 'http://ez.no/ezobject', 'class_identifier' ) .')';

                // get info about translations.
                $languageInfo = array();
                $versionList = $objectNode->getElementsByTagName( 'version-list' )->item( 0 );
                $versions = $versionList->getElementsByTagName( 'version' );
                foreach( $versions as $version )
                {
                    $versionInfo = $version->getElementsByTagName( 'object-translation' );
                    foreach( $versionInfo as $info )
                    {
                            $languageInfo[] = $info->getAttribute( 'language' );
                    }
                }

                $objectNames[] = array( 'description' =>
                                         ezi18n( 'kernel/package', 'Content object %objectname', false,
                                                 array( '%objectname' => $objectName ) ),
                                        'language_info' => $languageInfo );
            }
            return $objectNames;
        }
    }

    /*!
     Add Node list to ezcontentobject package handler.

     \param nodeID node id
     \param isSubtree subtree (optional, default true )
    */
    function addNode( $nodeID, $isSubtree = true )
    {
        $this->RootNodeIDArray[] = $nodeID;
        $this->NodeIDArray[] = $nodeID;

        if ( $isSubtree )
        {
            $nodeArray = eZContentObjectTreeNode::subTreeByNodeID( array( 'AsObject' => false ), $nodeID );
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
    function generatePackage( $package, $options )
    {
        $this->Package = $package;
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
            $classIDArray = $this->generateClassIDArray();

            foreach ( $classIDArray as $classID )
            {
                eZContentClassPackageHandler::addClass( $package, $classID );
            }
        }

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $packageRoot = $dom->createElement( 'content-object' );
        $dom->appendChild( $packageRoot );

        $objectListDOMNode = $this->createObjectListNode( $options );
        $importedObjectListDOMNode = $dom->importNode( $objectListDOMNode, true );
        $packageRoot->appendChild( $importedObjectListDOMNode );

        $overrideSettingsArray = false;
        $templateFilenameArray = false;
        if ( $options['include_templates'] )
        {
            $overrideSettingsListNode = $this->generateOverrideSettingsArray( $options['site_access_array'], $options['minimal_template_set'] );
            $importedOverrideSettingsListNode = $dom->importNode( $overrideSettingsListNode, true );
            $packageRoot->appendChild( $importedOverrideSettingsListNode );

            $designTemplateListNode = $this->generateTemplateFilenameArray();
            $importedDesignTemplateListNode = $dom->importNode( $designTemplateListNode, true );
            $packageRoot->appendChild( $importedDesignTemplateListNode );

            $fetchAliasListNode = $this->generateFetchAliasArray();
            $importedFetchAliasListNode = $dom->importNode( $fetchAliasListNode, true );
            $packageRoot->appendChild( $importedFetchAliasListNode );
        }

        $siteAccessListDOMNode = $this->createSiteAccessListNode( $options );
        $importedSiteAccessListDOMNode = $dom->importNode( $siteAccessListDOMNode, true );
        $packageRoot->appendChild( $importedSiteAccessListDOMNode );

        $topNodeListDOMNode = $this->createTopNodeListDOMNode( $options );
        $importedTopNodeListDOMNode = $dom->importNode( $topNodeListDOMNode, true );
        $packageRoot->appendChild( $importedTopNodeListDOMNode );

        //$filename = substr( md5( mt_rand() ), 0, 8 );
        $filename = 'contentobjects';
        $this->Package->appendInstall( 'ezcontentobject', false, false, true,
                                       $filename, $this->contentObjectDirectory(),
                                       array( 'content' => $packageRoot ) );
        $this->Package->appendInstall( 'ezcontentobject', false, false, false,
                                       $filename, $this->contentObjectDirectory(),
                                       array( 'content' => false ) );
    }

    /*!
     \private
     Create DOMNode for list of top nodes.

     \param options
    */
    function createTopNodeListDOMNode( $options )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $topNodeListDOMNode = $dom->createElement( 'top-node-list' );
        $dom->appendChild( $topNodeListDOMNode );

        foreach( $this->RootNodeObjectArray as $rootNode )
        {
            unset( $topNode );
            $topNode = $dom->createElement( 'top-node', $rootNode->attribute( 'name' ) );
            $topNode->setAttribute( 'node-id', $rootNode->attribute( 'node_id' ) );
            $topNode->setAttribute( 'remote-id', $rootNode->attribute( 'remote_id' ) );
            $topNodeListDOMNode->appendChild( $topNode );
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
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $siteAccessListDOMNode = $dom->createElement( 'site-access-list' );
        $dom->appendChild( $siteAccessListDOMNode );

        foreach( $options['site_access_array'] as $siteAccess )
        {
            unset( $siteAccessNode );
            $siteAccessNode = $dom->createElement( 'site-access', $siteAccess );
            $siteAccessListDOMNode->appendChild( $siteAccessNode );
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

        $path = $this->Package->path() . '/' . $this->contentObjectDirectory();
        if ( !file_exists( $path ) )
                eZDir::mkdir( $path, false, true );

        $dom = new DOMDocument( '1.0', 'utf-8' );

        // Store objects to separate files or not
        $storeToMultiple = count( $this->ObjectArray ) >= self::STORE_OBJECTS_TO_SEPARATE_FILES_THRESHOLD ? true : false;
        if ( $storeToMultiple )
            $objectListNode = $dom->createElement( 'object-files-list' );
        else
            $objectListNode = $dom->createElement( 'object-list' );

        $dom->appendChild( $objectListNode );

        foreach( array_keys( $this->ObjectArray ) as $objectID )
        {
            $objectNode = $this->ObjectArray[$objectID]->serialize( $this->Package, $version, $options, $this->NodeObjectArray, $this->RootNodeIDArray );

            if ( $storeToMultiple )
            {
                $fileName = 'object-' . $objectNode->getAttribute( 'remote_id' ) . '.xml';
                $filePath = $path . '/' . $fileName;

                $objectFileNode = $dom->createElement( 'object-file' );
                $objectFileNode->setAttribute( 'filename', $fileName );
                $objectListNode->appendChild( $objectFileNode );

                $partDOM = new DOMDocument( '1.0', 'utf-8' );
                $partDOM->formatOutput = true;
                $importedObjectNode = $partDOM->importNode( $objectNode, true );
                $partDOM->appendChild( $importedObjectNode );
                $this->Package->storeDOM( $filePath, $partDOM );
                unset( $partDOM );
                unset( $objectFileNode );
            }
            else
            {
                $importedObjectNode = $dom->importNode( $objectNode, true );
                $objectListNode->appendChild( $importedObjectNode );
            }
            unset( $objectNode );
        }

        return $objectListNode;
    }

    /*!
     \private
     Generate list of content objects to export, and store them to

     \param nodeAssignment which node assignments to include, either 'selected' or 'main'
    */
    function generateObjectArray( $nodeAssignment )
    {
        foreach( $this->NodeObjectArray as $contentNode )
        {
            if ( $nodeAssignment == 'main' )
            {
                if ( $contentNode->attribute( 'main_node_id' ) == $contentNode->attribute( 'node_id' ) )
                {
                    $this->ObjectArray[(string)$contentNode->attribute( 'contentobject_id' )] = $contentNode->object();
                }
            }
            else
            {
                $this->ObjectArray[(string)$contentNode->attribute( 'contentobject_id' )] = $contentNode->object();
            }
        }
    }

    /*!
      \private
    */
    function &generateFetchAliasArray()
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $fetchAliasListDOMNode = $dom->createElement( 'fetch-alias-list' );
        $registeredAliases = array();

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
                    if ( isset( $registeredAliases[$fetchAlias] ) )
                    {
                        continue;
                    }
                    $registeredAliases[$fetchAlias] = true;

                    unset( $fetchAliasDOMNode );
                    $fetchAliasDOMNode = $dom->createElement( 'fetch-alias' );
                    $fetchAliasDOMNode->setAttribute( 'name', $fetchAlias );
                    $fetchAliasDOMNode->setAttribute( 'site-access', $siteAccess );

                    $fetchBlock = $aliasINI->group( $fetchAlias );
                    if ( isset( $fetchBlock['Constant'] ) )
                    {
                        foreach ( $fetchBlock['Constant'] as $matchKey => $value )
                        {
                            if ( strpos( $matchKey, 'class_' ) === 0 &&
                                 is_int( $value ) )
                            {
                                $contentClass = eZContentClass::fetch( $value );
                                $fetchBlock['Constant']['class_remote_id'] = $contentClass->attribute( 'remote_id' );
                            }
                            if ( strpos( $matchKey, 'node_' ) === 0 &&
                                 is_int( $value ) )
                            {
                                $contentTreeNode = eZContentObjectTreeNode::fetch( $value );
                                $fetchBlock['Constant']['node_remote_id'] = $contentTreeNode->attribute( 'remote_id' );
                            }
                            if ( strpos( $matchKey, 'parent_node_' ) === 0 &&
                                 is_int( $value ) )
                            {
                                $contentTreeNode = eZContentObjectTreeNode::fetch( $value );
                                $fetchBlock['Constant']['parent_node_remote_id'] = $contentTreeNode->attribute( 'remote_id' );
                            }
                            if ( strpos( $matchKey, 'object_' ) === 0 &&
                                 is_int( $value ) )
                            {
                                $contentObject = eZContentObject::fetch( $value );
                                $fetchBlock['Constant']['object_remote_id'] = $contentObject->attribute( 'remote_id' );
                            }
                        }
                    }
                    $importedNode = $dom->importNode( eZContentObjectPackageHandler::createElementNodeFromArray( $fetchAlias,  $fetchBlock ), true );
                    $fetchAliasDOMNode->appendChild( $importedNode );
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
        $dom = new DOMDocument( '1.0', 'utf-8' );

        $templateListDOMNode = $dom->createElement( 'template-list' );
        $dom->appendChild( $templateListDOMNode );

        foreach( array_keys( $this->OverrideSettingsArray ) as $siteAccess )
        {
            $this->TemplateFileArray[$siteAccess] = array();
            $overrideArray = eZTemplateDesignResource::overrideArray( $siteAccess );

            foreach( $this->OverrideSettingsArray[$siteAccess] as $override )
            {
                $customMatchArray = $overrideArray['/' . $override['Source']]['custom_match'];

                foreach( $customMatchArray as $customMatch )
                {
                    if ( $customMatch['conditions'] == null )
                    {
                        //$templateListDOMNode->appendChild( $this->createDOMNodeFromFile( $customMatch['match_file'], $siteAccess, 'design' ) );
                        //$this->TemplateFileArray[$siteAccess][] = $customMatch['match_file'];
                    }
                    else if ( count( array_diff( $customMatch['conditions'], $override['Match'] ) ) == 0 &&
                              count( array_diff( $override['Match'], $customMatch['conditions'] ) ) == 0 )
                    {
                        unset( $node );
                        $node = $this->createDOMNodeFromFile( $customMatch['match_file'], $siteAccess, 'design' );
                        $importedNode = $dom->importNode( $node, true );
                        $templateListDOMNode->appendChild( $importedNode );
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
     \param siteAccess
     \param filetype (optional)
    */
    function createDOMNodeFromFile( $filename, $siteAccess, $filetype = false )
    {
        $path = substr( $filename, strpos( $filename, '/', 7 ) );

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $fileDOMNode = $dom->createElement( 'file' );
        $fileDOMNode->setAttribute( 'site-access', $siteAccess );

        if ( $filetype !== false )
        {
            $fileDOMNode->setAttribute( 'file-type', $filetype );
        }

        $dom->appendChild( $fileDOMNode );
        $originalPathNode = $dom->createElement( 'original-path', $filename );
        $fileDOMNode->appendChild( $originalPathNode );
        $pathNode = $dom->createElement( 'path', $path );
        $fileDOMNode->appendChild( $pathNode );

        $destinationPath = $this->Package->path() . '/' .  eZContentObjectPackageHandler::contentObjectDirectory() . '/' . $path;
        eZDir::mkdir( eZDir::dirpath( $destinationPath ),  false,  true );
        eZFileHandler::copy( $filename, $destinationPath );

        return $fileDOMNode;
    }

    /*!
     \private
     Get all template overrides used by exported objects

     \param siteAccessArray site access array
    */
    function &generateOverrideSettingsArray( $siteAccessArray, $minimalTemplateSet )
    {
        $datatypeHash = array();
        $simpleMatchList = array();
        $regexpMatchList = array();
        foreach ( $siteAccessArray as $siteAccess )
        {
            $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
            $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            $overrideINI->loadCache();

            $matchBlock = false;
            $blockMatchArray = array();

            foreach( array_keys( $this->NodeObjectArray ) as $nodeID )
            {
                // Extract some information that will be used
                unset( $contentNode, $contentObject, $contentClass );
                $contentNode = $this->NodeObjectArray[$nodeID];
                $contentObject = $contentNode->attribute( 'object' );
                $contentClass = $contentObject->attribute( 'content_class' );
                $attributeList = $contentClass->fetchAttributes( false, false, false );
                $datatypeList = array();
                foreach ( $attributeList as $attribute )
                {
                    $datatypeList[] = $attribute['data_type_string'];
                    if ( !isset( $datatypeHash[$attribute['data_type_string']] ) )
                    {
                        $datatype = eZDataType::create( $attribute['data_type_string'] );
                        $datatypeHash[$attribute['data_type_string']] = $datatype;
                        if ( !method_exists( $datatype, 'templateList' ) )
                            continue;
                        $templateList = $datatype->templateList();
                        if ( $templateList === false )
                            continue;
                        foreach ( $templateList as $templateMatch )
                        {
                            if ( is_string( $templateMatch ) )
                            {
                                $simpleMatchList[] = $templateMatch;
                            }
                            else if ( is_array( $templateMatch ) )
                            {
                                if ( $templateMatch[0] == 'regexp' )
                                {
                                    $regexpMatchList[] = $templateMatch[1];
                                }
                            }
                        }
                    }
                }
                $datatypeText = implode( '|', array_unique( $datatypeList ) );

                foreach( array_keys( $overrideINI->groups() ) as $blockName )
                {
                    if ( isset( $blockMatchArray[$blockName] ) )
                    {
                        continue;
                    }

                    $blockData = $overrideINI->group( $blockName );
                    $sourceName = $blockData['Source'];
                    $matchSettings = false;
                    if ( isset( $blockData['Match'] ) )
                        $matchSettings = $blockData['Match'];

                    $matchValue = array();
                    $validMatch = true;
                    $hasMatchType = false;
                    if ( $matchSettings )
                    {
                        foreach( array_keys( $matchSettings ) as $matchType )
                        {
                            switch( $matchType )
                            {
                                case 'object':
                                {
                                    $hasMatchType = true;
                                    if ( $contentNode->attribute( 'contentobject_id' ) != $matchSettings[$matchType] )
                                    {
                                        $validMatch = false;
                                    }
                                    else
                                    {
                                        $matchValue[$this->OverrideObjectRemoteID] = $contentObject->attribute( 'remote_id' );
                                    }
                                } break;

                                case 'node':
                                {
                                    $hasMatchType = true;
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
                                    $hasMatchType = true;
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
                                    $hasMatchType = true;
                                    if ( $contentObject->attribute( 'contentclass_id' ) != $matchSettings[$matchType] )
                                    {
                                        $validMatch = false;
                                    }
                                    else
                                    {
                                        $matchValue[$this->OverrideClassRemoteID] = $contentClass->attribute( 'remote_id' );
                                    }
                                } break;

                                case 'class_identifier':
                                {
                                    $hasMatchType = true;
                                    if ( $contentObject->attribute( 'class_identifier' ) != $matchSettings[$matchType] )
                                    {
                                        $validMatch = false;
                                    }
                                } break;

                                case 'section':
                                {
                                    $hasMatchType = true;
                                    if ( $contentObject->attribute( 'section_id' ) != $matchSettings[$matchType] )
                                    {
                                        $validMatch = false;
                                    }
                                } break;

                                case 'depth':
                                {
                                    $hasMatchType = true;
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
                    }
                    else
                    {
                        $validMatch = false;
                    }

                    if ( !$hasMatchType )
                    {
                        // Datatype match, we include overrides for datatype templates
                        if ( preg_match( "#^content/datatype/[a-zA-Z]+/(" . $datatypeText . ")\\.tpl$#", $sourceName ) )
                        {
                            $validMatch = true;
                            $hasMatchType = true;
                        }
                        else if ( in_array( $sourceName, $simpleMatchList ) )
                        {
                            $validMatch = true;
                            $hasMatchType = true;
                        }
                        else
                        {
                            foreach ( $regexpMatchList as $regexpMatch )
                            {
                                if ( preg_match( $regexpMatch, $sourceName ) )
                                {
                                    $validMatch = true;
                                    $hasMatchType = true;
                                }
                            }
                        }
                    }

                    if ( $validMatch )
                    {
                        if ( !$minimalTemplateSet or
                             $hasMatchType )
                        {
                            $blockMatchArray[$blockName] = array_merge( $blockData,
                                                                        $matchValue );
                        }
                    }
                }
            }
            $this->OverrideSettingsArray[$siteAccess] = $blockMatchArray;
        }

        $dom = new DOMDocument( '1.0', 'utf-8' );

        $overrideSettingsListDOMNode = $dom->createElement( 'override-list' );
        $dom->appendChild( $overrideSettingsListDOMNode );
        foreach ( $this->OverrideSettingsArray as $siteAccess => $blockMatchArray )
        {
            foreach( $blockMatchArray as $blockName => $iniGroup )
            {
                unset( $blockMatchNode );
                $blockMatchNode = $dom->createElement( 'block' );
                $blockMatchNode->setAttribute( 'name', $blockName );
                $blockMatchNode->setAttribute( 'site-access', $siteAccess );
                $importedNode = $dom->importNode( eZContentObjectPackageHandler::createElementNodeFromArray( $blockName, $iniGroup ), true );
                $blockMatchNode->appendChild( $importedNode );
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
        foreach( $this->NodeObjectArray as $nodeObject )
        {
            $contentObject = $nodeObject->object();
            $classIDArray[] = $contentObject->attribute( 'contentclass_id' );
        }
        $classIDArray = array_unique( $classIDArray );
        return $classIDArray;
    }

    /*!
     Uninstalls all previously installed content objects.
    */
    function uninstall( $package, $installType, $parameters,
                        $name, $os, $filename, $subdirectory,
                        $content, &$installParameters,
                        &$installData )
    {
        $this->Package = $package;

        if ( isset( $installParameters['error']['error_code'] ) )
            $errorCode = $installParameters['error']['error_code'];
        else
            $errorCode = false;

        // Error codes reserverd for content object uninstallation
        if ( !$errorCode || ( $errorCode >= self::UNINSTALL_OBJECTS_ERROR_RANGE_FROM &&
                              $errorCode <= self::UNINSTALL_OBJECTS_ERROR_RANGE_TO ) )
        {
            $objectListNode = $content->getElementsByTagName( 'object-list' )->item( 0 );
            if ( $objectListNode )
            {
                $objectNodes = $objectListNode->getElementsByTagName( 'object' );
            }
            else
            {
                $objectListNode = $content->getElementsByTagName( 'object-files-list' )->item( 0 );
                $objectNodes = $objectListNode->getElementsByTagName( 'object-file' );
            }

            // loop intentionally from the last until the first
            // objects need to be uninstalled in reverse order of installation
            for ( $i = $objectNodes->length - 1; $i >=0; $i-- )
            {
                $objectNode = $objectNodes->item( $i );
                $realObjectNode = $this->getRealObjectNode( $objectNode );

                $objectRemoteID = $realObjectNode->getAttribute( 'remote_id' );
                $name = $realObjectNode->getAttribute( 'name' );

                if ( isset( $installParameters['error']['error_code'] ) &&
                     !$this->isErrorElement( $objectRemoteID, $installParameters ) )
                    continue;

                if ( isset( $object ) )
                {
                    eZContentObject::clearCache( $object->attribute( 'id' ) );
                    unset( $object );
                }
                $object = eZContentObject::fetchByRemoteID( $objectRemoteID );

                if ( $object !== null )
                {
                    $modified = $object->attribute( 'modified' );
                    $published = $object->attribute( 'published' );
                    if ( $modified > $published )
                    {
                        $choosenAction = $this->errorChoosenAction( eZContentObject::PACKAGE_ERROR_MODIFIED,
                                                                    $installParameters, false, $this->HandlerType );

                        if ( $choosenAction == eZContentObject::PACKAGE_KEEP )
                        {
                            continue;
                        }
                        if ( $choosenAction != eZContentObject::PACKAGE_DELETE )
                        {
                            $installParameters['error'] = array( 'error_code' => eZContentObject::PACKAGE_ERROR_MODIFIED,
                                                                 'element_id' => $objectRemoteID,
                                                                 'description' => ezi18n( 'kernel/package',
                                                                                          "Object '%objectname' has been modified since installation. Are you sure you want to remove it?",
                                                                                          false, array( '%objectname' => $name ) ),
                                                                 'actions' => array( eZContentObject::PACKAGE_DELETE => ezi18n( 'kernel/package', 'Remove' ),
                                                                                     eZContentObject::PACKAGE_KEEP => ezi18n( 'kernel/package', 'Keep object' ) ) );
                            return false;
                        }
                    }

                    $assignedNodes = $object->attribute( 'assigned_nodes' );
                    $assignedNodeIDArray = array();
                    foreach( $assignedNodes as $node )
                    {
                        $assignedNodeIDArray[] = $node->attribute( 'node_id' );
                    }
                    if ( count( $assignedNodeIDArray ) == 0 )
                        continue;
                    $info = eZContentObjectTreeNode::subtreeRemovalInformation( $assignedNodeIDArray );
                    $childrenCount = $info['total_child_count'];

                    if ( $childrenCount > 0 )
                    {
                        $choosenAction = $this->errorChoosenAction( eZContentObject::PACKAGE_ERROR_HAS_CHILDREN,
                                                                    $installParameters, false, $this->HandlerType );

                        if ( $choosenAction == eZContentObject::PACKAGE_KEEP )
                        {
                            continue;
                        }
                        if ( $choosenAction != eZContentObject::PACKAGE_DELETE )
                        {
                            $installParameters['error'] = array( 'error_code' => eZContentObject::PACKAGE_ERROR_HAS_CHILDREN,
                                                                 'element_id' => $objectRemoteID,
                                                                 'description' => ezi18n( 'kernel/package',
                                                                                          "Object '%objectname' has %childrencount sub-item(s) that will be removed.",
                                                                                          false, array( '%objectname' => $name,
                                                                                                        '%childrencount' => $childrenCount ) ),
                                                                 'actions' => array( eZContentObject::PACKAGE_DELETE => ezi18n( 'kernel/package', "Remove object and its sub-item(s)" ),
                                                                                     eZContentObject::PACKAGE_KEEP => ezi18n( 'kernel/package', 'Keep object' ) ) );
                            return false;
                        }
                    }

                    eZContentObjectTreeNode::removeSubtrees( $assignedNodeIDArray, false );

                    //eZContentObjectOperations::remove( $object->attribute( 'id' ) );
                }
                else
                {
                    eZDebug::writeNotice( "Can't uninstall object '$name': object not found", 'eZContentObjectPackageHandler::uninstall' );
                }

                unset( $realObjectNode );
            }
        }
        return true;
    }

    /*!
     Creates a new contentobject as defined in the xml structure.
    */
    function install( $package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      $content, &$installParameters,
                      &$installData )
    {
        $this->Package = $package;

        if ( isset( $installParameters['error']['error_code'] ) )
            $errorCode = $installParameters['error']['error_code'];
        else
            $errorCode = false;

        // Error codes reservered for content object installation
        if ( !$errorCode || ( $errorCode >= self::INSTALL_OBJECTS_ERROR_RANGE_FROM &&
                              $errorCode <= self::INSTALL_OBJECTS_ERROR_RANGE_TO ) )
        {
            $objectListNode = $content->getElementsByTagName( 'object-list' )->item( 0 );
            if ( $objectListNode )
            {
                $objectNodes = $objectListNode->getElementsByTagName( 'object' );
            }
            else
            {
                $objectListNode = $content->getElementsByTagName( 'object-files-list' )->item( 0 );
                $objectNodes = $objectListNode->getElementsByTagName( 'object-file' );
            }


            if ( !$this->installContentObjects( $objectNodes,
                                                $content->getElementsByTagName( 'top-node-list' )->item( 0 ),
                                                $installParameters ) )
                return false;
            $errorCode = false;
        }

        if ( !$this->installTemplates( $content->getElementsByTagName( 'template-list' )->item( 0 ),
                                       $package,
                                       $subdirectory,
                                       $installParameters ) )
            return false;


        if ( !$this->installOverrides( $content->getElementsByTagName( 'override-list' )->item( 0 ),
                                       $installParameters ) )
            return false;

        if ( !$this->installFetchAliases( $content->getElementsByTagName( 'fetch-alias-list' )->item( 0 ),
                                          $installParameters ) )
            return false;

        return true;
    }

    /*!
     \private

     Serialize and install content objects

     \param objectNodes object-list DOMNode
     \param topNodeListNode
     \param installParameters install parameters
    */
    function installContentObjects( $objectNodes, $topNodeListNode, &$installParameters )
    {
        if ( isset( $installParameters['user_id'] ) )
            $userID = $installParameters['user_id'];
        else
            $userID = eZUser::currentUserID();

        $handlerType = $this->handlerType();
        $firstInstalledID = null;

        foreach( $objectNodes as $objectNode )
        {
            $realObjectNode = $this->getRealObjectNode( $objectNode );

            // Cycle until we reach an element where error has occured.
            // If action has been choosen, try install this item again, else skip it.
            if ( isset( $installParameters['error']['error_code'] ) &&
                 !$this->isErrorElement( $realObjectNode->getAttribute( 'remote_id' ), $installParameters ) )
            {
                continue;
            }

            //we are here, it means we'll try to install some object.
            if ( !$firstInstalledID )
            {
                $firstInstalledID = $realObjectNode->getAttribute( 'remote_id' );
            }

            $newObject = eZContentObject::unserialize( $this->Package, $realObjectNode, $installParameters, $userID, $handlerType );
            if ( !$newObject )
            {
                return false;
            }

            if ( is_object( $newObject ) )
            {
                eZContentObject::clearCache( $newObject->attribute( 'id' ) );
                unset( $newObject );
            }
            unset( $realObjectNode );

            if ( isset( $installParameters['error'] ) && count( $installParameters['error'] ) )
            {
                $installParameters['error'] = array();
            }
        }

        $this->installSuspendedNodeAssignment( $installParameters );
        $this->installSuspendedObjectRelations( $installParameters );

        // Call postUnserialize on all installed objects
        foreach( $objectNodes as $objectNode )
        {
            if ( $objectNode->localName == 'object' )
            {
                $remoteID = $objectNode->getAttribute( 'remote_id' );
            }
            else
            {
                $remoteID = substr( $objectNode->getAttribute( 'filename' ), 7, 32 );
            }

            // Begin from the object that we started from in the previous cycle
            if ( $firstInstalledID && $remoteID != $firstInstalledID )
            {
                continue;
            }
            else
            {
                $firstInstalledID = null;
            }

            $object = eZContentObject::fetchByRemoteID( $remoteID );
            if ( is_object( $object ) )
            {
                $object->postUnserialize( $this->Package );
                eZContentObject::clearCache( $object->attribute( 'id' ) );
            }
            unset( $object );
        }

        return true;
    }

    /*!
    \private

    \param install parameters
    */
    function installSuspendedNodeAssignment( &$installParameters )
    {
        if ( !isset( $installParameters['suspended-nodes'] ) )
        {
            return;
        }
        foreach ( $installParameters['suspended-nodes'] as $parentNodeRemoteID => $suspendedNodeInfo )
        {
            $parentNode = eZContentObjectTreeNode::fetchByRemoteID( $parentNodeRemoteID );
            if ( $parentNode !== null )
            {
                $nodeInfo = $suspendedNodeInfo['nodeinfo'];
                $nodeInfo['parent_node'] = $parentNode->attribute( 'node_id' );

                $existNodeAssignment = eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                           null,
                                                           $nodeInfo );
                $nodeInfo['priority'] = $suspendedNodeInfo['priority'];
                if( !is_object( $existNodeAssignment ) )
                {
                    $nodeAssignment = eZNodeAssignment::create( $nodeInfo );
                    $nodeAssignment->store();
                }

                $contentObject = eZContentObject::fetch( $nodeInfo['contentobject_id'] );
                if ( is_object( $contentObject ) && $contentObject->attribute( 'current_version' ) == $nodeInfo['contentobject_version'] )
                {
                   eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $nodeInfo['contentobject_id'],
                                                                              'version' =>  $nodeInfo['contentobject_version'] ) );
                }
                if ( isset( $nodeInfo['is_main'] ) && $nodeInfo['is_main'] )
                {
                    $existingMainNode = eZContentObjectTreeNode::fetchByRemoteID( $nodeInfo['parent_remote_id'], false );
                    if ( $existingMainNode )
                    {
                        eZContentObjectTreeNode::updateMainNodeID( $existingMainNode['node_id'],
                                                                   $nodeInfo['contentobject_id'],
                                                                   $nodeInfo['contentobject_version'],
                                                                   $nodeInfo['parent_node'] );
                    }
                }
            }
            else
            {
                eZDebug::writeError( 'Can not find parent node by remote-id ID = ' . $parentNodeRemoteID, 'eZContentObjectPackageHandler::installSuspendedNodeAssignment()' );
            }
            unset( $installParameters['suspended-nodes'][$parentNodeRemoteID] );
        }
    }

    /*!
     \private

     Installs suspended content object relations (need for complex content-relations structure)

     \param install parameters
    */
    function installSuspendedObjectRelations( &$installParameters )
    {
        if ( !isset( $installParameters['suspended-relations'] ) )
        {
            return;
        }
        foreach( $installParameters['suspended-relations'] as $suspendedObjectRelation )
        {
            $contentObjectID =        $suspendedObjectRelation['contentobject-id'];
            $contentObjectVersionID = $suspendedObjectRelation['contentobject-version'];

            $contentObjectVersion = eZContentObjectVersion::fetchVersion( $contentObjectVersionID, $contentObjectID );
            if ( is_object( $contentObjectVersion ) )
            {
                $relatedObjectRemoteID = $suspendedObjectRelation['related-object-remote-id'];
                $relatedObject = eZContentObject::fetchByRemoteID( $relatedObjectRemoteID );
                $relatedObjectID = ( $relatedObject !== null ) ? $relatedObject->attribute( 'id' ) : null;

                if ( $relatedObjectID )
                {
                    $relatedObject->addContentObjectRelation( $relatedObjectID, $contentObjectVersionID, $contentObjectID );
                }
                else
                {
                    eZDebug::writeError( 'Can not find related object by remote-id ID = ' . $relatedObjectRemoteID, 'eZContentObjectPackageHandler::installSuspendedObjectRelations()' );
                }
            }
        }
        unset( $installParameters['suspended-relations'] );
    }

    /*!
     \private

     Set and install templates

     \param template list
     \param package
     \param subdirectory
     \param install parameters.
    */
    function installTemplates( $templateList, $package, $subdirectory, &$installParameters )
    {
        if ( !$templateList )
        {
            return true;
        }
        $siteAccessDesignPathArray = array();
        $templateRootPath = $package->path() . '/' . $subdirectory;
        foreach( $templateList->getElementsByTagName( 'file' ) as $fileNode )
        {
            $originalSiteAccess = $fileNode->getAttribute( 'site-access' );
            if ( isset( $installParameters['site_access_map'][$originalSiteAccess] ) )
            {
                $newSiteAccess = $installParameters['site_access_map'][$originalSiteAccess];
            }
            else
            {
                $newSiteAccess = $installParameters['site_access_map']['*'];
            }

            if ( !isset( $siteAccessDesignPathArray[$newSiteAccess] ) )
            {
                $ini = eZINI::instance( 'site.ini', 'settings', null, null, true );
                $ini->prependOverrideDir( "siteaccess/$newSiteAccess", false, 'siteaccess' );
                $ini->loadCache();

                if ( isset( $installParameters['design_map'] ) )
                {
                    $designMap = $installParameters['design_map'];
                    if ( isset( $designMap[$originalSiteAccess] ) )
                        $siteAccessDesignPathArray[$newSiteAccess] = eZTemplateDesignResource::designStartPath() . '/' . $designMap[$originalSiteAccess];
                    else
                        $siteAccessDesignPathArray[$newSiteAccess] = eZTemplateDesignResource::designStartPath() . '/' . $designMap['*'];
                }
                else
                {
                    $siteAccessDesignPathArray[$newSiteAccess] = eZTemplateDesignResource::designStartPath() . '/' . $ini->variable( "DesignSettings", "StandardDesign" );
                }
            }

            $path = '';
            foreach( $fileNode->childNodes as $pathNode )
            {
                if ( $pathNode->nodeName == 'path' )
                {
                    $path = $pathNode->nodeValue;
                    break;
                }
            }

            $sourcePath = $templateRootPath . $path;
            $destinationPath = $siteAccessDesignPathArray[$newSiteAccess] . $path;

            eZDir::mkdir( eZDir::dirpath( $destinationPath ), false, true );
            if ( !eZFileHandler::copy( $sourcePath, $destinationPath ) )
                return false;

//             eZDebug::writeNotice( 'Copied: "' . $sourcePath . '" to: "' . $destinationPath . '"',
//                                   'eZContentObjectPackageHandler::installTemplates()' );
        }
        return true;
    }

    /*!
     \private

     Install overrides

     \param override list
     \param install parameters
    */
    function installOverrides( $overrideListNode, &$parameters )
    {
        if ( !$overrideListNode )
        {
            return true;
        }

        $overrideINIArray = array();
        foreach( $overrideListNode->getElementsByTagName( 'block' ) as $blockNode )
        {
            if ( isset( $parameters['site_access_map'][$blockNode->getAttribute( 'site-access' )] ) )
            {
                $newSiteAccess = $parameters['site_access_map'][$blockNode->getAttribute( 'site-access' )];
            }
            else
            {
                $newSiteAccess = $parameters['site_access_map']['*'];
            }

            if ( !$newSiteAccess )
            {
                eZDebug::writeError( 'SiteAccess map for : ' . $blockNode->getAttribute( 'site-access' ) . ' not set.',
                                     'eZContentObjectPackageHandler::installOverrides()' );
                continue;
            }

            if ( !isset( $overrideINIArray[$newSiteAccess] ) )
            {
                $overrideINIArray[$newSiteAccess] = eZINI::instance( 'override.ini.append.php', "settings/siteaccess/$newSiteAccess", null, null, true );
            }

            $blockArray = array();
            $blockName = $blockNode->getAttribute( 'name' );
            $blockArray[$blockName] = eZContentObjectPackageHandler::createArrayFromDOMNode( $blockNode->getElementsByTagName( $blockName )->item( 0 ) );

            if ( isset( $blockArray[$blockName][$this->OverrideObjectRemoteID] ) )
            {
                $contentObject = eZContentObject::fetchByRemoteID( $blockArray[$blockName][$this->OverrideObjectRemoteID] );
                $blockArray[$blockName]['Match']['object'] = $contentObject->attribute( 'id' );
                unset( $blockArray[$blockName][$this->OverrideObjectRemoteID] );
//                 eZDebug::writeNotice( 'Found object id: "' . $blockArray[$blockName]['Match']['object'] . '" for matchblock "[' . $blockName . '][Match][object]"',
//                                       'eZContentObjectPackageHandler::installOverrides()' );
            }
            if ( isset( $blockArray[$blockName][$this->OverrideNodeRemoteID] ) )
            {
                $contentNode = eZContentObjectTreeNode::fetchByRemoteID( $blockArray[$blockName][$this->OverrideNodeRemoteID] );
                $blockArray[$blockName]['Match']['node'] = $contentNode->attribute( 'node_id' );
                unset( $blockArray[$blockName][$this->OverrideNodeRemoteID] );
//                 eZDebug::writeNotice( 'Found node id: "' . $blockArray[$blockName]['Match']['node'] . '" for matchblock "[' . $blockName . '][Match][node]"',
//                                       'eZContentObjectPackageHandler::installOverrides()' );
            }
            if ( isset( $blockArray[$blockName][$this->OverrideParentNodeRemoteID] ) )
            {
                $parentContentNode = eZContentObjectTreeNode::fetchByRemoteID( $blockArray[$blockName][$this->OverrideParentNodeRemoteID] );
                $blockArray[$blockName]['Match']['parent_node'] = $parentContentNode->attribute( 'node_id' );
                unset( $blockArray[$blockName][$this->OverrideParentNodeRemoteID] );
//                 eZDebug::writeNotice( 'Found parent node id: "' . $blockArray[$blockName]['Match']['parent_node'] . '" for matchblock "[' . $blockName . '][Match][parent_node]"',
//                                       'eZContentObjectPackageHandler::installOverrides()' );
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
//                 eZDebug::writeNotice( 'Found class id: "' . $blockArray[$blockName]['Match']['class'] . '" for matchblock "[' . $blockName . '][Match][class]"',
//                                       'eZContentObjectPackageHandler::installOverrides()' );
            }

            $overrideINIArray[$newSiteAccess]->setVariables( $blockArray );
        }

        foreach( $overrideINIArray as $siteAccess => $iniArray )
        {
            $overrideINIArray[$siteAccess]->save();
        }

        return true;
    }

    /*!
     \private

     Install fetch alias overrides

     \param fetch alias  list
     \param install parameters
    */
    function installFetchAliases( $fetchAliasListNode, &$parameters )
    {
        if ( !$fetchAliasListNode )
        {
            return true;
        }

        $fetchAliasINIArray = array();
        foreach( $fetchAliasListNode->getElementsByTagName( 'fetch-alias' ) as $blockNode )
        {
            if ( isset( $parameters['site_access_map'][$blockNode->getAttribute( 'site-access' )] ) )
            {
                $newSiteAccess = $parameters['site_access_map'][$blockNode->getAttribute( 'site-access' )];
            }
            else
            {
                $newSiteAccess = $parameters['site_access_map']['*'];
            }

            if ( !isset( $fetchAliasINIArray[$newSiteAccess] ) )
            {
                $fetchAliasINIArray[$newSiteAccess] = eZINI::instance( 'fetchalias.ini.append.php', "settings/siteaccess/$newSiteAccess", null, null, true );
            }

            $blockArray = array();
            $blockName = $blockNode->getAttribute( 'name' );
            $blockArray[$blockName] = eZContentObjectPackageHandler::createArrayFromDOMNode( $blockNode->getElementsByTagName( $blockName )->item( 0 ) );

            //$blockArray[$blockName] = $blockArray[$blockName][0];

            if ( isset( $blockArray[$blockName]['Constant'] ) && is_array( $blockArray[$blockName]['Constant'] ) && count( $blockArray[$blockName]['Constant'] ) > 0 )
            {
                foreach( $blockArray[$blockName]['Constant'] as $matchKey => $value )
                {
                    if ( strpos( $matchKey, 'class_' ) === 0 &&
                         is_int( $value ) )
                    {
                        $contentClass = eZContentClass::fetchByRemoteID( $blockArray[$blockName]['Constant']['class_remote_id'] );
                        $blockArray[$blockName]['Constant'][$matchKey] = $contentClass->attribute( 'id' );
                        unset( $blockArray[$blockName]['Constant']['class_remote_id'] );
                    }
                    if( strpos( $matchKey, 'node_' ) === 0 &&
                        is_int( $value ) )
                    {
                        $contentTreeNode = eZContentObjectTreeNode::fetchByRemoteID( $blockArray[$blockName]['Constant']['node_remote_id'] );
                        $blockArray[$blockName]['Constant'][$matchKey] = $contentTreeNode->attribute( 'node_id' );
                        unset( $blockArray[$blockName]['Constant']['node_remote_id'] );
                    }
                    if( strpos( $matchKey, 'parent_node_' ) === 0 &&
                        is_int( $value ) )
                    {
                        $contentTreeNode = eZContentObjectTreeNode::fetchByRemoteID( $blockArray[$blockName]['Constant']['parent_node_remote_id'] );
                        $blockArray[$blockName]['Constant'][$matchKey] = $contentTreeNode->attribute( 'node_id' );
                        unset( $blockArray[$blockName]['Constant']['parent_node_remote_id'] );
                    }
                    if( strpos( $matchKey, 'object_' ) === 0 &&
                        is_int( $value ) )
                    {
                        $contentObject = eZContentObject::fetchByRemoteID( $blockArray[$blockName]['Constant']['object_remote_id'] );
                        $blockArray[$blockName]['Constant'][$matchKey] = $contentTreeNode->attribute( 'id' );
                        unset( $blockArray[$blockName]['Constant']['object_remote_id'] );
                    }
                }
            }

            $fetchAliasINIArray[$newSiteAccess]->setVariables( $blockArray );
        }

        foreach( $fetchAliasINIArray as $siteAccess => $iniFetchAlias )
        {
            $fetchAliasINIArray[$siteAccess]->save();
        }

        return true;
    }

    function add( $packageType, $package, $cli, $parameters )
    {
        $options = array();
        foreach ( $parameters['node-list'] as $nodeItem )
        {
            $nodeIDList = $nodeItem['node-id-list'];
            foreach ( $nodeIDList as $nodeIDItem )
            {
                $this->addNode( $nodeIDItem['id'], $nodeIDItem['subtree'] );
                unset( $node );
                $node = false;
                if ( isset( $nodeIDItem['node'] ) )
                {
                    $node = $nodeIDItem['node'];
                }
                else
                {
                    $node = eZContentObjectTreeNode::fetch( $nodeIDItem['id'] );
                }
                $cli->notice( "Adding node /" . $node->pathWithNames() . " to package" );
            }
        }
        $options['include_classes'] = $parameters['include-classes'];
        $options['include_templates'] = $parameters['include-templates'];
        $options['node_assignment'] = $parameters['node-assignment-type'];
        $options['site_access_array'] = $parameters['siteaccess-list'];
        $options['language_array'] = $parameters['language-list'];
        $options['versions'] = $parameters['version-type'];
        $options['related_objects'] = $parameters['related-type'];
        $options['embed_objects'] = $parameters['embed-type'];
        $options['minimal_template_set'] = $parameters['minimal-template-set'];
        $this->generatePackage( $package, $options );
    }

    function handleAddParameters( $packageType, $package, $cli, $arguments )
    {
        return $this->handleParameters( $packageType, $package, $cli, 'add', $arguments );
    }

    /*!
     \private
    */
    function handleParameters( $packageType, $package, $cli, $type, $arguments )
    {
        $nodeList = array();
        $includeClasses = true;
        $includeTemplates = true;
        $siteAccessList = array();
        $nodeAssignmentType = 'main';
        $relatedObjectType = 'selected';
        $embedObjectType = 'selected';
        $versionType = 'current';
        $languageList = array();
        $minimalTemplateSet = false;
        $nodeItem = array( 'node-id-list' => array() );
        $longOptions = array( 'include-classes' => 'include-classes',
                              'include-templates' => 'include-templates',
                              'exclude-classes' => 'exclude-classes',
                              'exclude-templates' => 'exclude-templates',
                              'language' => 'language',
                              'current-version' => 'current-version',
                              'all-versions' => 'all-versions',
                              'node-main' => 'node-main',
                              'node-selected' => 'node-selected',
                              'siteaccess' => 'siteaccess',
                              'minimal-template-set' => 'minimal-template-set' );
        $shortOptions = array();
        $error = false;
        foreach ( $arguments as $argument )
        {
            if ( $argument[0] == '-' )
            {
                if ( strlen( $argument ) > 1 and
                     $argument[1] == '-' )
                {
                    $option = substr( $argument, 2 );
                    $valuePos = strpos( $option, '=' );
                    $optionValue = false;
                    if ( $valuePos !== false )
                    {
                        $optionValue = substr( $option, $valuePos + 1 );
                        $option = substr( $option, 0, $valuePos );
                    }
                    if ( isset( $longOptions[$option] ) )
                        $optionName = $longOptions[$option];
                    else
                        $optionName = false;
                }
                else
                {
                    $option = substr( $argument, 1 );
                    if ( isset( $shortOptions[$option] ) )
                        $optionName = $shortOptions[$option];
                    else
                        $optionName = false;
                }
                if ( $optionName == 'include-classes' or $optionName == 'exclude-classes' )
                {
                    if ( count( $nodeItem['node-id-list'] ) > 0 )
                    {
                        $nodeList[] = $nodeItem;
                        $nodeItem['node-id-list'] = array();
                    }
                    $includeClasses = ( $optionName == 'include-classes' );
                }
                else if ( $optionName == 'include-templates' or $optionName == 'exclude-templates' )
                {
                    if ( count( $nodeItem['node-id-list'] ) > 0 )
                    {
                        $nodeList[] = $nodeItem;
                        $nodeItem['node-id-list'] = array();
                    }
                    $includeTemplates = ( $optionName == 'include-templates' );
                }
                else if ( $optionName == 'node-main' )
                {
                    $nodeAssignmentType = 'main';
                }
                else if ( $optionName == 'node-selected' )
                {
                    $nodeAssignmentType = 'selected';
                }
                else if ( $optionName == 'siteaccess' )
                {
                    $siteAccessList = explode( ',', $optionValue );
                }
                else if ( $optionName == 'language' )
                {
                    $languageList = explode( ',', $optionValue );
                }
                else if ( $optionName == 'current-version' )
                {
                    $versionType = 'current';
                }
                else if ( $optionName == 'all-versions' )
                {
                    $versionType = 'all';
                }
                else if ( $optionName == 'minimal-template-set' )
                {
                    $minimalTemplateSet = true;
                }
            }
            else
            {
                $nodeID = false;
                $subtree = false;
                if ( is_numeric( $argument ) )
                {
                    $nodeID = (int)$argument;
                    $node = eZContentObjectTreeNode::fetch( $nodeID );
                    if ( !is_object( $node ) )
                    {
                        $error = true;
                        $nodeID = false;
                        $cli->notice( "Could not find content-node using ID " . $cli->stylize( 'emphasize', $nodeID ) );
                    }
                }
                else
                {
                    $path = $argument;
                    if ( preg_match( "#(.+)/\*$#", $path, $matches ) )
                    {
                        $path = $matches[1];
                        $subtree = true;
                    }
                    $node = eZContentObjectTreeNode::fetchByURLPath( $path );
                    if ( is_object( $node ) )
                    {
                        $nodeID = $node->attribute( 'node_id' );
                    }
                    else
                    {
                        $cli->notice( "Could not find content-node using path " . $cli->stylize( 'emphasize', $path ) );
                        $error = true;
                    }
                }
                if ( $nodeID )
                {
                    $nodeItem['node-id-list'][] = array( 'id' => $nodeID,
                                                         'subtree' => $subtree,
                                                         'node' => &$node );
                }
                if ( $error )
                    return false;
            }
        }
        if ( count( $nodeItem['node-id-list'] ) > 0 )
        {
            $nodeList[] = $nodeItem;
        }
        if ( count( $nodeList ) == 0 )
        {
            $cli->error( "No objects chosen" );
            return false;
        }
        if ( count( $languageList ) == 0 )
        {
            // The default is to fetch all languages
            $languageList = eZContentLanguage::fetchLocaleList();
        }
        if ( count( $siteAccessList ) == 0 )
        {
            $ini = eZINI::instance();
            $siteAccessList[] = $ini->variable( 'SiteSettings', 'DefaultAccess' );
        }
        return array( 'node-list' => $nodeList,
                      'include-classes' => $includeClasses,
                      'include-templates' => $includeTemplates,
                      'siteaccess-list' => $siteAccessList,
                      'language-list' => $languageList,
                      'node-assignment-type' => $nodeAssignmentType,
                      'related-type' => $relatedObjectType,
                      'embed-type' => $embedObjectType,
                      'version-type' => $versionType,
                      'minimal-template-set' => $minimalTemplateSet,
                      );
    }

    function contentObjectDirectory()
    {
        return 'ezcontentobject';
    }

    /*!
      \static
      Creates DOMNodeElement recursivly from recursive array
    */
    static function createElementNodeFromArray( $name, $array )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $node = $dom->createElement( $name );
        $dom->appendChild( $node );

        foreach ( $array as $arrayKey => $value )
        {
            if ( is_array( $value ) and
                 count( $valueKeys = array_keys( $value ) ) > 0 )
            {
                if ( is_int( $valueKeys[0] ) )
                {
                    foreach( $value as $child )
                    {
                        unset( $childNode );
                        unset( $importedChildNode );
                        $childNode = eZContentObjectPackageHandler::createElementNodeFromArray( $arrayKey, $child );
                        $importedChildNode = $dom->importNode( $childNode, true );
                        $node->appendChild( $importedChildNode );
                    }
                }
                else
                {
                        unset( $valueNode );
                        unset( $importedValueNode );
                        $valueNode = eZContentObjectPackageHandler::createElementNodeFromArray( $arrayKey, $value );
                        $importedValueNode = $dom->importNode( $valueNode, true );
                        $node->appendChild( $importedValueNode );
                }
            }
            else
            {
                $node->setAttribute( $arrayKey, $value );
            }
        }
        return $node;
    }

    /*!
      \static
      Creates recursive array from DOMNodeElement
    */
    static function createArrayFromDOMNode( $domNode )
    {
        if ( !$domNode )
        {
            return null;
        }

        $retArray = array();
        foreach ( $domNode->childNodes as $childNode )
        {
            if ( $childNode->nodeType != XML_ELEMENT_NODE )
            {
                continue;
            }

            if ( !isset( $retArray[$childNode->localName] ) )
            {
                $retArray[$childNode->localName] = array();
            }

            // If the node has children we create an array for this element
            // and append to it, if not we assign it directly
            if ( $childNode->hasChildNodes() )
            {
                $retArray[$childNode->localName][] = eZContentObjectPackageHandler::createArrayFromDOMNode( $childNode );
            }
            else
            {
                $retArray[$childNode->localName] = eZContentObjectPackageHandler::createArrayFromDOMNode( $childNode );
            }
        }
        foreach( $domNode->attributes as $attributeNode )
        {
            $retArray[$attributeNode->name] = $attributeNode->value;
        }

        return $retArray;
    }

    public $NodeIDArray = array();
    public $RootNodeIDArray = array();
    public $NodeObjectArray = array();
    public $ObjectArray = array();
    public $RootNodeObjectArray = array();
    public $OverrideSettingsArray = array();
    public $TemplateFileArray = array();
    public $Package = null;

    // Static class variables - replacing match values in override.ini
    public $OverrideObjectRemoteID = 'content_object_remote_id';
    public $OverrideNodeRemoteID = 'content_node_remote_id';
    public $OverrideParentNodeRemoteID = 'parent_content_node_remote_id';
    public $OverrideClassRemoteID = 'content_class_remote_id';
}

?>
