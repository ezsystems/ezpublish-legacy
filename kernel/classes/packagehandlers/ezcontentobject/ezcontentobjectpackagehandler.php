<?php
//
// Definition of eZContentClassPackageHandler class
//
// Created on: <09-Mar-2004 16:11:42 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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

/*! \file ezcontentobjectpackagehandler.php
*/

/*!
  \class eZContentObjectPackageHandler ezcontentobjectpackagehandler.php
  \brief Handles content objects in the package system

*/

define( 'EZ_PACKAGE_CONTENTOBJECT__MAX_LISTED_OBJECTS_NUMBER', 30 );

// If number of objects in the package is bigger than this constant,
// they are stored in separate files to prevent memory overflow.
// 'null' means always use separate files
define( 'EZ_PACKAGE_CONTENTOBJECT__STORE_OBJECTS_TO_SEPARATE_FILES_THRESHOLD', 100 );

define( 'EZ_PACKAGE_CONTENTOBJECT__INSTALL_OBJECTS_ERROR_RANGE_FROM', 1 );
define( 'EZ_PACKAGE_CONTENTOBJECT__INSTALL_OBJECTS_ERROR_RANGE_TO', 100 );
define( 'EZ_PACKAGE_CONTENTOBJECT__UNINSTALL_OBJECTS_ERROR_RANGE_FROM', 101 );
define( 'EZ_PACKAGE_CONTENTOBJECT__UNINSTALL_OBJECTS_ERROR_RANGE_TO', 200 );

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
       Fetches object stored in separate xml file
    */
    function fetchObjectFromFile( $objectFileNode )
    {
        $fileName = $objectFileNode->getAttribute( 'filename' );
        $filePath = $this->Package->path() . '/' . $this->contentObjectDirectory() . '/' . $fileName;
        $dom =& $this->Package->fetchDOMFromFile( $filePath );

        if ( $dom )
        {
            $objectNode =& $dom->root();
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
        if ( $objectNode->nodeName == 'object' )
        {
            $realObjectNode =& $objectNode;
        }
        else
        {
            $realObjectNode = $this->fetchObjectFromFile( $objectNode );
        }
        return $realObjectNode;
    }

    /*!
     \reimp
     Returns an explanation for the content object install item.

     The explanaition is actually a list having the following structure:
          array( array( 'description' => 'Content object Foo' ),
                 array( 'description' => 'Content object Bar' ),
                 array( 'description' => 'Content object Baz' ) );

     When number of items in the above list is too high,
     the following array is returned instead:
         array( 'description' => 'NNN content objects' );


    */
    function explainInstallItem( &$package, $installItem )
    {
        $this->Package =& $package;

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

            if ( !$dom )
                return null;

            $content =& $dom->root();
            $objectListNode = $content->elementByName( 'object-list' );
            if ( $objectListNode )
            {
                $realObjectNodes =& $objectListNode->Children;
            }
            else
            {
                // If objects are stored in separate files (new format)
                $objectListNode = $content->elementByName( 'object-files-list' );
                $objectNodes = $objectListNode->Children;

                if ( count( $objectNodes ) > EZ_PACKAGE_CONTENTOBJECT__MAX_LISTED_OBJECTS_NUMBER )
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

                    $realObjectNodes[] =& $realObjectNode;
                    unset( $realObjectNode );
                }
            }

            // create descriptions array
            $objectNames = array();
            foreach( $realObjectNodes as $objectNode )
            {
                // We use attributeValue() method to get value of 'ezremote:class_identifier' attribute
                // since getAttribute() does not support specifying prefixes.
                $objectName =
                    $objectNode->getAttribute( 'name' ) .
                    ' (' . $objectNode->attributeValue( 'class_identifier' ) .')';
                $objectNames[] = array( 'description' =>
                                         ezi18n( 'kernel/package', 'Content object %objectname', false,
                                                 array( '%objectname' => $objectName ) ) );
            }
            return $objectNames;
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
            $overrideSettingsListNode =& $this->generateOverrideSettingsArray( $options['site_access_array'], $options['minimal_template_set'] );
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

        $path = $this->Package->path() . '/' . $this->contentObjectDirectory();
        if ( !file_exists( $path ) )
                eZDir::mkdir( $path, eZDir::directoryPermission(), true );

        // Store objects to separate files or not
        $storeToMultiple = count( $this->ObjectArray ) >= EZ_PACKAGE_CONTENTOBJECT__STORE_OBJECTS_TO_SEPARATE_FILES_THRESHOLD ? true : false;
        if ( $storeToMultiple )
            $objectListNode = eZDOMDocument::createElementNode( 'object-files-list' );
        else
            $objectListNode = eZDOMDocument::createElementNode( 'object-list' );

        foreach( array_keys( $this->ObjectArray ) as $objectID )
        {
            $objectNode = $this->ObjectArray[$objectID]->serialize( $this->Package, $version, $options, $this->NodeObjectArray, $this->RootNodeIDArray );

            if ( $storeToMultiple )
            {
                $fileName = 'object-' . $objectNode->getAttribute( 'remote_id' ) . '.xml';
                $filePath = $path . '/' . $fileName;

                $objectFileNode = eZDOMDocument::createElementNode( 'object-file' );
                $objectFileNode->setAttribute( 'filename', $fileName );
                $objectListNode->appendChild( $objectFileNode );

                $partDOM = new eZDOMDocument();
                $partDOM->setRoot( $objectNode );
                $this->Package->storeDOM( $filePath, $partDOM );
                unset( $partDOM );
                unset( $objectFileNode );
            }
            else
            {
                $objectListNode->appendChild( $objectNode );
            }
            unset( $objectNode );
        }

        return $objectListNode;
    }

    /*!
     \private
     Generate list of content objects to export, and store them to

     \param node_assignments, 'selected' or 'main'
    */
    function generateObjectArray( $nodeAssignment )
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
        $registeredAliases = array();

        foreach( array_keys( $this->TemplateFileArray ) as $siteAccess )
        {
            $aliasINI =& eZINI::instance( 'fetchalias.ini', 'settings', null, null, true );
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
                    $fetchAliasDOMNode = eZDOMDocument::createElementNode( 'fetch-alias', array( 'name' => $fetchAlias,
                                                                                                  'site-access' => $siteAccess ) );

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

        $destinationPath = $this->Package->path() . '/' .  eZContentObjectPackageHandler::contentObjectDirectory() . '/' . $path;
        eZDir::mkdir( eZDir::dirpath( $destinationPath ),  eZDir::directoryPermission(),  true );
        eZFileHandler::copy( $filename, $destinationPath );

        return $fileDOMNode;
    }

    /*!
     \private
     Get all template overrides used by exported objects

     \param site access array
    */
    function &generateOverrideSettingsArray( $siteAccessArray, $minimalTemplateSet )
    {
        $datatypeHash = array();
        $simpleMatchList = array();
        $regexpMatchList = array();
        foreach ( $siteAccessArray as $siteAccess )
        {
            $overrideINI =& eZINI::instance( 'override.ini', 'settings', null, null, true );
            $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            $overrideINI->loadCache();

            $matchBlock = false;
            $blockMatchArray = array();

            foreach( array_keys( $this->NodeObjectArray ) as $nodeID )
            {
                // Extract some information that will be used
                unset( $contentNode, $contentObject, $contentClass );
                $contentNode =& $this->NodeObjectArray[$nodeID];
                $contentObject =& $contentNode->attribute( 'object' );
                $contentClass =& $contentObject->attribute( 'content_class' );
                $attributeList = $contentClass->fetchAttributes( false, false, false );
                $datatypeList = array();
                foreach ( $attributeList as $attribute )
                {
                    $datatypeList[] = $attribute['data_type_string'];
                    if ( !isset( $datatypeHash[$attribute['data_type_string']] ) )
                    {
                        include_once( 'kernel/classes/ezdatatype.php' );
                        $datatype = eZDataType::create( $attribute['data_type_string'] );
                        $datatypeHash[$attribute['data_type_string']] =& $datatype;
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

        $overrideSettingsListDOMNode = eZDOMDocument::createElementNode( 'override-list' );
        foreach ( $this->OverrideSettingsArray as $siteAccess => $blockMatchArray )
        {
            foreach( $blockMatchArray as $blockName => $iniGroup )
            {
                unset( $blockMatchNode );
                $blockMatchNode = eZDOMDocument::createElementNode( 'block', array( 'name' => $blockName,
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
        $classIDArray = array_unique( $classIDArray );
        return $classIDArray;
    }

    /*!
     \reimp
     Uninstalls all previously installed content objects.
    */
    function uninstall( &$package, $installType, $parameters,
                        $name, $os, $filename, $subdirectory,
                        &$content, &$installParameters,
                        &$installData )
    {
        $this->Package =& $package;

        if ( isset( $installParameters['error']['error_code'] ) )
            $errorCode = $installParameters['error']['error_code'];
        else
            $errorCode = false;

        // Error codes reserverd for content object uninstallation
        if ( !$errorCode || ( $errorCode >= EZ_PACKAGE_CONTENTOBJECT__UNINSTALL_OBJECTS_ERROR_RANGE_FROM &&
                              $errorCode <= EZ_PACKAGE_CONTENTOBJECT__UNINSTALL_OBJECTS_ERROR_RANGE_TO ) )
        {
            $objectListNode = $content->elementByName( 'object-list' );
            if ( !$objectListNode )
            {
                $objectListNode = $content->elementByName( 'object-files-list' );
            }
            $objectNodes = array_reverse( $objectListNode->Children );

            foreach( $objectNodes as $objectNode )
            {
                $realObjectNode = $this->getRealObjectNode( $objectNode );

                $objectRemoteID = $realObjectNode->getAttribute( 'remote_id' );
                $name = $realObjectNode->attributeValue( 'name' );

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
                        $choosenAction = $this->errorChoosenAction( EZ_PACKAGE_CONTENTOBJECT_ERROR_MODIFIED,
                                                                    $installParameters );

                        if ( $choosenAction == EZ_PACKAGE_CONTENTOBJECT_KEEP )
                        {
                            continue;
                        }
                        if ( $choosenAction != EZ_PACKAGE_CONTENTOBJECT_DELETE )
                        {
                            $installParameters['error'] = array( 'error_code' => EZ_PACKAGE_CONTENTOBJECT_ERROR_MODIFIED,
                                                                 'element_id' => $objectRemoteID,
                                                                 'description' => ezi18n( 'kernel/package',
                                                                                          "Object '%objectname' has been modified since installation. Are you sure you want to remove it?",
                                                                                          false, array( '%objectname' => $name ) ),
                                                                 'actions' => array( EZ_PACKAGE_CONTENTOBJECT_DELETE => ezi18n( 'kernel/package', 'Remove' ),
                                                                                     EZ_PACKAGE_CONTENTOBJECT_KEEP => ezi18n( 'kernel/package', 'Keep object' ) ) );
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
                        $choosenAction = $this->errorChoosenAction( EZ_PACKAGE_CONTENTOBJECT_ERROR_HAS_CHILDREN,
                                                                    $installParameters );

                        if ( $choosenAction == EZ_PACKAGE_CONTENTOBJECT_KEEP )
                        {
                            continue;
                        }
                        if ( $choosenAction != EZ_PACKAGE_CONTENTOBJECT_DELETE )
                        {
                            $installParameters['error'] = array( 'error_code' => EZ_PACKAGE_CONTENTOBJECT_ERROR_HAS_CHILDREN,
                                                                 'element_id' => $objectRemoteID,
                                                                 'description' => ezi18n( 'kernel/package',
                                                                                          "Object '%objectname' has %childrencount sub-item(s) that will be removed.",
                                                                                          false, array( '%objectname' => $name,
                                                                                                        '%childrencount' => $childrenCount ) ),
                                                                 'actions' => array( EZ_PACKAGE_CONTENTOBJECT_DELETE => ezi18n( 'kernel/package', "Remove object and it's sub-item(s)" ),
                                                                                     EZ_PACKAGE_CONTENTOBJECT_KEEP => ezi18n( 'kernel/package', 'Keep object' ) ) );
                            return false;
                        }
                    }

                    eZContentObjectTreeNode::removeSubtrees( $assignedNodeIDArray, false );

                    //include_once( 'kernel/classes/ezcontentobjectoperations.php' );

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
     \reimp
     Creates a new contentobject as defined in the xml structure.
    */
    function install( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, &$installParameters,
                      &$installData )
    {
        $this->Package =& $package;

        if ( isset( $installParameters['error']['error_code'] ) )
            $errorCode = $installParameters['error']['error_code'];
        else
            $errorCode = false;

        // Error codes reservered for content object installation
        if ( !$errorCode || ( $errorCode >= EZ_PACKAGE_CONTENTOBJECT__INSTALL_OBJECTS_ERROR_RANGE_FROM &&
                              $errorCode <= EZ_PACKAGE_CONTENTOBJECT__INSTALL_OBJECTS_ERROR_RANGE_TO ) )
        {
            $objectListNode = $content->elementByName( 'object-list' );
            if ( !$objectListNode )
            {
                $objectListNode = $content->elementByName( 'object-files-list' );
            }
            $objectNodes = $objectListNode->Children;

            if ( !$this->installContentObjects( $objectNodes,
                                                $content->elementByName( 'top-node-list' ),
                                                $installParameters ) )
                return false;
            $errorCode = false;
        }

        if ( !$this->installTemplates( $content->elementByName( 'template-list' ),
                                       $package,
                                       $subdirectory,
                                       $installParameters ) )
            return false;


        if ( !$this->installOverrides( $content->elementByName( 'override-list' ),
                                       $installParameters ) )
            return false;

        if ( !$this->installFetchAliases( $content->elementByName( 'fetch-alias-list' ),
                                          $installParameters ) )
            return false;

        return true;
    }

    /*!
     \private

     Serialize and install content objects

     \param object-list DOMNode
     \param install parameters
    */
    function installContentObjects( $objectNodes, $topNodeListNode, &$installParameters )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
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
                 !$this->isErrorElement( $realObjectNode->attributeValue( 'remote_id' ), $installParameters ) )
            {
                continue;
            }

            //we are here, it means we'll try to install some object.
            if ( !$firstInstalledID )
            {
                $firstInstalledID = $realObjectNode->attributeValue( 'remote_id' );
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
            if ( $objectNode->nodeName == 'object' )
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

            $object =& eZContentObject::fetchByRemoteID( $remoteID );
            if ( is_object( $object ) )
            {
                $object->postUnserialize( $package );
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
                $nodeInfo =& $suspendedNodeInfo['nodeinfo'];
                $nodeInfo['parent_node'] = $parentNode->attribute( 'node_id' );

                $existNodeAssignment = eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                           null,
                                                           $nodeInfo );
                $nodeInfo['priority'] = $suspendedNodeInfo['priority'];
                if( !is_object( $existNodeAssignment ) )
                {
                    $nodeAssignment =& eZNodeAssignment::create( $nodeInfo );
                    $nodeAssignment->store();
                }

                $contentObject = eZContentObject::fetch( $nodeInfo['contentobject_id'] );
                if ( is_object( $contentObject ) && $contentObject->attribute( 'current_version' ) == $nodeInfo['contentobject_version'] )
                {
                    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
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
    function installTemplates( $templateList, &$package, $subdirectory, &$installParameters )
    {
        if ( !$templateList )
        {
            return true;
        }
        include_once( 'kernel/common/eztemplatedesignresource.php' );

        $siteAccessDesignPathArray = array();
        $templateRootPath = $package->path() . '/' . $subdirectory;
        foreach( $templateList->elementsByName( 'file' ) as $fileNode )
        {
            $originalSiteAccess = $fileNode->attributeValue( 'site-access' );
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
                $ini =& eZINI::instance( 'site.ini', 'settings', null, null, true );
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

            $sourcePath = $templateRootPath . $fileNode->elementTextContentByName('path');
            $destinationPath = $siteAccessDesignPathArray[$newSiteAccess] . $fileNode->elementTextContentByName('path');

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
        foreach( $overrideListNode->elementsByName( 'block' ) as $blockNode )
        {
            if ( isset( $parameters['site_access_map'][$blockNode->attributeValue( 'site-access' )] ) )
            {
                $newSiteAccess = $parameters['site_access_map'][$blockNode->attributeValue( 'site-access' )];
            }
            else
            {
                $newSiteAccess = $parameters['site_access_map']['*'];
            }

            if ( !$newSiteAccess )
            {
                eZDebug::writeError( 'SiteAccess map for : ' . $blockNode->attributeValue( 'site-access' ) . ' not set.',
                                     'eZContentObjectPackageHandler::installOverrides()' );
                continue;
            }

            if ( !isset( $overrideINIArray[$newSiteAccess] ) )
            {
                $overrideINIArray[$newSiteAccess] =& eZINI::instance( 'override.ini.append.php', "settings/siteaccess/$newSiteAccess", null, null, true );
            }

            $blockArray = array();
            $blockName = $blockNode->attributeValue( 'name' );
            $blockArray[$blockName] = eZDOMDocument::createArrayFromDOMNode( $blockNode->elementByName( $blockName ) );

            if ( isset( $blockArray[$blockName][$this->OverrideObjectRemoteID] ) )
            {
                $contentObject =& eZContentObject::fetchByRemoteID( $blockArray[$blockName][$this->OverrideObjectRemoteID] );
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
        foreach( $fetchAliasListNode->elementsByName( 'fetch-alias' ) as $blockNode )
        {
            if ( isset( $parameters['site_access_map'][$blockNode->attributeValue( 'site-access' )] ) )
            {
                $newSiteAccess = $parameters['site_access_map'][$blockNode->attributeValue( 'site-access' )];
            }
            else
            {
                $newSiteAccess = $parameters['site_access_map']['*'];
            }

            if ( !isset( $fetchAliasINIArray[$newSiteAccess] ) )
            {
                $fetchAliasINIArray[$newSiteAccess] =& eZINI::instance( 'fetchalias.ini.append.php', "settings/siteaccess/$newSiteAccess", null, null, true );
            }

            $blockArray = array();
            $blockName = $blockNode->attributeValue( 'name' );
            $blockArray[$blockName] = eZDOMDocument::createArrayFromDOMNode( $blockNode->elementByName( $blockName ) );

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
                        $contentObject =& eZContentObject::fetchByRemoteID( $blockArray[$blockName]['Constant']['object_remote_id'] );
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

    /*!
     \reimp
    */
    function add( $packageType, &$package, &$cli, $parameters )
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
                    $node =& $nodeIDItem['node'];
                else
                    $node = eZContentObjectTreeNode::fetch( $nodeIDItem['id'] );
                $cli->notice( "Adding node /" . $node->attribute( 'path_identification_string' ) . " to package" );
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

    /*!
     \reimp
    */
    function handleAddParameters( $packageType, &$package, &$cli, $arguments )
    {
        return $this->handleParameters( $packageType, $package, $cli, 'add', $arguments );
    }

    /*!
     \private
    */
    function handleParameters( $packageType, &$package, &$cli, $type, $arguments )
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
            include_once( 'kernel/classes/ezcontentlanguage.php' );
            $languageList = eZContentLanguage::fetchLocaleList();
        }
        if ( count( $siteAccessList ) == 0 )
        {
            $ini =& eZINI::instance();
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
     \reimp
    */
    /*function createInstallNode( &$package, &$installNode, $installItem, $installType )
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
                foreach( $templateListNode ? $templateListNode->elementsByName( 'file' ) : array() as $fileNode )
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
    */

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
