<?php
//
// Definition of eZPackage class
//
// Created on: <23-Jul-2003 12:34:55 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
  \class eZPackage ezpackagehandler.php
  \brief Maintains eZ publish packages

*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'lib/ezutils/classes/ezfile.php' );
include_once( 'lib/ezutils/classes/ezdir.php' );

define( 'EZ_PACKAGE_VERSION', '3.2-1' );
define( 'EZ_PACKAGE_DEVELOPMENT', true );

class eZPackage
{
    /*!
     Constructor
    */
    function eZPackage( $parameters = array(), $modifiedParameters = array() )
    {
        $this->setParameters( $parameters, $modifiedParameters );
    }

    /*!
     \private
    */
    function setParameters( $parameters = array(),
                            $modifiedParameters = array() )
    {
        $timestamp = mktime();
        $packaging = array( 'timestamp' => $timestamp,
                            'host' => $_SERVER['HOSTNAME'],
                            'packager' => false );
        $release = array( 'version' => array( 'number' => false,
                                              'release' => false ),
                          'timestamp' => false,
                          'licence' => false,
                          'state' => false,
                          'dependencies' => array( 'file-lists' => array() ),
                          'provides' => array() );
        $install = array( 'pre' => array(),
                          'post' => array() );
        $uninstall = array( 'pre' => array(),
                            'post' => array() );
        $defaults = array( 'name' => false,
                           'summary' => false,
                           'description' => false,
                           'vendor' => false,
                           'priority' => false,
                           'type' => false,
                           'extension' => false,

                           'maintainers' => array(),
                           'packaging' => $packaging,
                           'source' => false,
                           'documents' => array(),
                           'groups' => array(),
                           'changelog' => array(),
                           'release' => $release,
                           'install' => $install,
                           'uninstall' => $uninstall );
        $this->Parameters = array_merge( $defaults, $parameters );
        $this->ModifiedParameters = array();
        foreach ( $modifiedParameters as $name => $modifiedParameter )
        {
            if ( $modifiedParameter !== false )
                $this->ModifiedParameters[$name] = mktime();
        }
    }

    /*!
     \private
    */
    function &parseDOMTree( &$dom )
    {
        $root =& $dom->root();
        if ( !$root )
            return false;
        $parameters = array();
        $parameters['name'] = $root->elementTextContentByName( 'name' );
        $parameters['summary'] = $root->elementTextContentByName( 'summary' );
        $extensionNode =& $root->elementByName( 'extension' );
        if ( $extensionNode )
            $parameters['extension'] = $extensionNode->attributeValue( 'name' );
        $this->setParameters( $parameters );

        $releaseNode =& $root->elementByName( 'release' );
        $installNode =& $releaseNode->elementByName( 'install' );
        $installPreNode =& $installNode->elementByName( 'pre' );
        $installPreChildren =& $installPreNode->children();
        for ( $i = 0; $i < count( $installPreChildren ); ++$i )
        {
            $installPreChild =& $installPreChildren[$i];
            $this->importInstallPart( $installPreChild, true );
        }
    }

    /*!
     \private
    */
    function importInstallPart( &$child, $isPre )
    {
        $installType = $child->name();
        switch ( $installType )
        {
            case 'run':
            {
            } break;
            case 'database':
            {
            } break;
            case 'part':
            {
                $os = $child->attributeValue( 'os' );
                $name = $child->attributeValue( 'name' );
                $type = $child->attributeValue( 'type' );
                $filename = $child->attributeValue( 'filename' );
                $subdirectory = $child->attributeValue( 'sub-directory' );
                $content = false;
                if ( !$filename )
                {
                    $content =& $child->firstChild();
                }
                $this->appendInstall( 'part', $name, $os, $isPre,
                                      $filename, $subdirectory,
                                      array( 'type' => $type,
                                             'content' => $content ) );
            } break;
            case 'operation':
            {
            } break;
        }
    }

    function install()
    {
        $installs = $this->Parameters['install']['pre'];
        foreach ( $installs as $install )
        {
            $type = $install['type'];
            switch( $type )
            {
                case 'run':
                {
                } break;
                case 'database':
                {
                } break;
                case 'part':
                {
                    $name = $install['name'];
                    $os = $install['os'];
                    $filename = $install['filename'];
                    $subdirectory = $install['subdirectory'];
                    $parameters = $install['parameters'];
                    $partType = $parameters['type'];
                    $content = $parameters['content'];
                    $importHandler = 'kernel/classes/packagehandlers/' . $partType . '/' . $partType . 'exporthandler.php';
                    print( $importHandler . "<br/>\n" );
                    if ( file_exists( $importHandler ) )
                    {
                        include_once( $importHandler );
                        $importClass = $partType . 'ExportHandler';
                        if ( isset( $handlers[$partType] ) )
                        {
                            $handler =& $handlers[$partType];
                            $handler->reset();
                        }
                        else
                        {
                            $handler =& new $importClass;
                            $handlers[$partType] =& $handler;
                        }
                        if ( $handler->extractContentBeforeInstall() )
                        {
                            if ( !$content and
                                 $filename )
                            {
                                if ( $subdirectory )
                                    $filepath = $subdirectory . '/' . $filename . '.xml';
                                else
                                    $filepath = $filename . '.xml';

                                print( $filepath . "\n" );
                                $dom =& $this->fetchDOMFromFile( $filepath );
                                if ( $dom )
                                    $content =& $dom->root();
                                else
                                    print( "Failed fetching dom from file $filepath\n" );
                            }
                        }
                        $handler->install( $this, $parameters,
                                           $name, $os, $filename, $subdirectory,
                                           $content );
                    }
                } break;
                case 'operation':
                {
                } break;
            }
        }
    }

    function &create( $name, $parameters = array() )
    {
        $parameters['name'] = $name;
        $handler =& new eZPackage( $parameters, $parameters );
        return $handler;
    }

    function resetModification()
    {
        $this->ModifiedParameters = array();
    }

    function attribute( $attributeName, $attributeList = false )
    {
        $attributeValue = null;
        if ( array_key_exists( $attributeName, $this->Parameters ) )
            $attributeValue = $this->Parameters[$attributeName];
        if ( is_array( $attributeList ) )
        {
            foreach ( $attributeList as $attributeKey )
            {
                if ( !is_array( $attributeValue ) or
                     !array_key_exists( $attributeValue, $attributeKey ) )
                    break;
                $attributeValue = $attributeValue[$attributeKey];
            }
        }
        return $attributeValue;
    }

    function isModified( $attributeName, $attributeList = false )
    {
        $attributeValue = null;
        if ( array_key_exists( $attributeName, $this->ModifiedParameters ) )
            $attributeValue = $this->ModifiedParameters[$attributeName];
        if ( is_array( $attributeList ) )
        {
            foreach ( $attributeList as $attributeKey )
            {
                if ( !is_array( $attributeValue ) or
                     !array_key_exists( $attributeKey, $attributeValue ) )
                    break;
                $attributeValue = $attributeValue[$attributeKey];
            }
        }
        return $attributeValue;
    }

    function appendMaintainer( $name, $email, $role = false )
    {
        $index = count( $this->Parameters['maintainers'] );
        $this->Parameters['maintainers'][$index] = array( 'name' => $name,
                                                          'email' => $email,
                                                          'role' => $role );
        $this->ModifiedParameters['maintainers'][$index] = mktime();
    }

    function appendDocument( $name, $mimeType = false, $os = false, $audience = false,
                             $create = false, $data = false )
    {
        if ( !$mimeType )
            $mimeType = 'text/plain';
        $index = count( $this->Parameters['documents'] );
        $this->Parameters['documents'][$index] = array( 'name' => $name,
                                                        'mime-type' => $mimeType,
                                                        'os' => $os,
                                                        'create-document' => $create,
                                                        'data' => $data,
                                                        'audience' => $audience );
        $this->ModifiedParameters['documents'][$index] = mktime();
    }

    function appendGroup( $name )
    {
        $index = count( $this->Parameters['groups'] );
        $this->Parameters['groups'][$index] = array( 'name' => $name );
        $this->ModifiedParameters['groups'][$index] = mktime();
    }

    function appendChange( $person, $email, $changes )
    {
        $timestamp = mktime();
        if ( !is_array( $changes ) )
            $changes = array( $changes );
        $index = count( $this->Parameters['changelog'] );
        $this->Parameters['changelog'][$index] = array( 'timestamp' => $timestamp,
                                                        'person' => $person,
                                                        'email' => $email,
                                                        'changes' => $changes );
        $this->ModifiedParameters['changelog'][$index] = mktime();
    }

    function appendFileList( $files, $role = false, $subDirectory = false,
                             $parameters = false )
    {
        $this->Parameters['release']['provides']['file-lists'][] = array( 'files' => $files,
                                                                          'role' => $role,
                                                                          'sub-directory' => $subDirectory,
                                                                          'parameters' => $parameters );
    }

    function appendInstall( $type, $name, $os = false, $isPre = true,
                            $filename = false, $subdirectory = false,
                            $parameters = false )
    {
        $installEntry = array( 'type' => $type,
                               'name' => $name,
                               'os' => $os,
                               'filename' => $filename,
                               'subdirectory' => $subdirectory,
                               'parameters' => $parameters );
        $prePost = 'pre';
        if ( !$isPre )
            $prePost = 'post';
        $this->Parameters['install'][$prePost][] = $installEntry;
        if ( $installEntry['filename'] )
        {
            $content = $installEntry['parameters']['content'];
            if ( get_class( $content ) == 'ezdomnode' )
            {
                $partContentNode =& $content;
                $subdirectory = false;
                if ( $installEntry['subdirectory'] )
                {
                    $subdirectory = $installEntry['subdirectory'];
                }
                $filePath = $installEntry['filename'] . '.xml';
                if ( $subdirectory )
                {
                    if ( !file_exists( $subdirectory ) )
                        eZDir::mkdir( $subdirectory, 0777, true );
                    $filePath = $subdirectory . '/' . $filePath;
                }
                $partDOM = new eZDOMDocument();
                $partDOM->setRoot( $partContentNode );
                print( "Storing $filePath\n" );
                $this->storeDOM( $filePath, $partDOM );
            }
        }
    }

    /*!
     Sets the packager of this release.
    */
    function setPackager( $timestamp = false, $host = false, $packager = false )
    {
        if ( $timestamp )
            $this->Parameters['packaging']['timestamp'] = $timestamp;
        if ( $host )
            $this->Parameters['packaging']['host'] = $host;
        if ( $packager )
            $this->Parameters['packaging']['packager'] = $packager;
    }

    /*!
     Sets various release information. If the value is set to \c false it is not updated.
     \param $version The version number, eg. 1.0, 2.3.5
     \param $release The release number, usually starts at 1 and increments for updates on the same version
     \param $timestamp The timestamp of the release
     \param $licence The licence of the package, eg. GPL, LGPL etc.
     \param $state The sate of the release, e.g alpha, beta, stable etc.
    */
    function setRelease( $version = false, $release = false, $timestamp = false,
                         $licence = false, $state = false )
    {
        if ( $version )
        {
            $this->Parameters['release']['version']['number'] = $version;
            $this->ModifiedParameters['release']['version']['number'] = mktime();
        }
        if ( $release )
        {
            $this->Parameters['release']['version']['release'] = $release;
            $this->ModifiedParameters['release']['version']['release'] = mktime();
        }
        if ( $timestamp )
        {
            $this->Parameters['release']['timestamp'] = $timestamp;
            $this->ModifiedParameters['release']['timestamp'] = mktime();
        }
        if ( $licence )
        {
            $this->Parameters['release']['licence'] = $licence;
            $this->ModifiedParameters['release']['licence'] = mktime();
        }
        if ( $state )
        {
            $this->Parameters['release']['state'] = $state;
            $this->ModifiedParameters['release']['state'] = mktime();
        }
    }

    function &domStructure()
    {
        print_r( $this->ModifiedParameters );
        $dom = new eZDOMDocument();
        $root = $dom->createElementNode( 'package', array( 'version' => EZ_PACKAGE_VERSION,
                                                           'development' => ( EZ_PACKAGE_DEVELOPMENT ? 'true' : 'false' ) ) );
        $root->appendAttribute( $dom->createAttributeNode( 'ezpackage', 'http://ez.no/ezpackage', 'xmlns' ) );
        $dom->setRoot( $root );

        $name = $this->attribute( 'name' );
        $nameAttributes = array();
        if ( $this->isModified( 'name' ) )
            $nameAttributes['modified'] = $this->isModified( 'name' );
        $summary = $this->attribute( 'summary' );
        $summaryAttributes = array();
        if ( $this->isModified( 'summary' ) )
            $summaryAttributes['modified'] = $this->isModified( 'summary' );
        $description = $this->attribute( 'description' );
        $descriptionAttributes = array();
        if ( $this->isModified( 'description' ) )
            $descriptionAttributes['modified'] = $this->isModified( 'description' );
        $vendor = $this->attribute( 'vendor' );
        $vendorAttributes = array();
        if ( $this->isModified( 'vendor' ) )
            $vendorAttributes['modified'] = $this->isModified( 'vendor' );
        $priority = $this->attribute( 'priority' );
        $priorityAttributes = array( 'value' => $priority );
        if ( $this->isModified( 'priority' ) )
            $priorityAttributes['modified'] = $this->isModified( 'priority' );
        $type = $this->attribute( 'type' );
        $typeAttributes = array( 'value' => $type );
        if ( $this->isModified( 'type' ) )
            $typeAttributes['modified'] = $this->isModified( 'type' );
        $extension = $this->attribute( 'extension' );
        $extensionAttributes = array( 'name' => $extension );
        if ( $this->isModified( 'extension' ) )
            $extensionAttributes['modified'] = $this->isModified( 'extension' );

        $maintainers = $this->attribute( 'maintainers' );
        $packaging = $this->attribute( 'packaging' );
        $source = $this->attribute( 'source' );
        $documents = $this->attribute( 'documents' );
        $groups = $this->attribute( 'groups' );
        $release = $this->attribute( 'release' );
        $install = $this->attribute( 'install' );
        $uninstall = $this->attribute( 'uninstall' );
        $changelogs = $this->attribute( 'changelog' );

        $root->appendChild( $dom->createElementTextNode( 'name', $name,
                                                         $nameAttributes ) );
        if ( $summary )
            $root->appendChild( $dom->createElementTextNode( 'summary', $summary,
                                                             $summaryAttributes ) );
        if ( $description )
            $root->appendChild( $dom->createElementTextNode( 'description', $description,
                                                             $descriptionAttributes ) );
        if ( $vendor )
            $root->appendChild( $dom->createElementTextNode( 'vendor', $vendor,
                                                             $vendorAttributes ) );
        if ( $priority )
            $root->appendChild( $dom->createElementNode( 'priority', $priorityAttributes ) );
        if ( $type )
            $root->appendChild( $dom->createElementNode( 'type', $typeAttributes ) );
        if ( $extension )
            $root->appendChild( $dom->createElementNode( 'extension', $extensionAttributes ) );

        $ezpublishNode =& $dom->createElementNode( 'ezpublish' );
        $ezpublishNode->appendAttribute( $dom->createAttributeNode( 'ezpublish', 'http://ez.no/ezpublish', 'xmlns' ) );
        include_once( 'lib/version.php' );
        $ezpublishNode->appendChild( $dom->createElementTextNode( 'version', eZPublishSDK::version( true ) ) );
        $ezpublishNode->appendChild( $dom->createElementTextNode( 'named-version', eZPublishSDK::version( false, true ) ) );
        $root->appendChild( $ezpublishNode );

        if ( count( $maintainers ) > 0 )
        {
            $maintainersNode =& $dom->createElementNode( 'maintainers' );
            $maintainersNode->appendAttribute( $dom->createAttributeNode( 'ezmaintainer', 'http://ez.no/ezpackage', 'xmlns' ) );
            $index = 0;
            foreach ( $maintainers as $maintainer )
            {
                $maintainerNode =& $dom->createElementNode( 'maintainer' );
                $maintainerNode->appendChild( $dom->createElementTextNode( 'name', $maintainer['name'] ) );
                $maintainerNode->appendChild( $dom->createElementTextNode( 'email', $maintainer['email'] ) );
                if ( $maintainer['role'] )
                    $maintainerNode->appendChild( $dom->createElementTextNode( 'role', $maintainer['role'] ) );
                $maintainersNode->appendChild( $maintainerNode );
                if ( $this->isModified( 'maintainers', array( $index ) ) )
                    $maintainerNode->appendAttribute( $dom->createAttributeNode( 'modified', $this->isModified( 'maintainers', array( $index ) ) ) );
                ++$index;
            }
            $root->appendChild( $maintainersNode );
        }

        $packagingNode =& $dom->createElementNode( 'packaging' );
        $packagingNode->appendAttribute( $dom->createAttributeNode( 'ezpackaging', 'http://ez.no/ezpackage', 'xmlns' ) );
        $packagingNode->appendChild( $dom->createElementTextNode( 'timestamp', $packaging['timestamp'] ) );
        $packagingNode->appendChild( $dom->createElementTextNode( 'host', $packaging['host'] ) );
        if ( $packaging['packager'] )
            $packagingNode->appendChild( $dom->createElementTextNode( 'packager', $packaging['packager'] ) );
        $root->appendChild( $packagingNode );

//         $root->appendChild( $dom->createElementNode( 'signature' ) );

        if ( count( $documents ) > 0 )
        {
            $documentsNode =& $dom->createElementNode( 'documents' );
            $index = 0;
            foreach ( $documents as $document )
            {
                $documentNode =& $dom->createElementNode( 'document',
                                                          array( 'mime-type' => $document['mime-type'],
                                                                 'name' => $document['name'] ) );
                if ( $this->isModified( 'documents', array( $index ) ) )
                    $documentNode->appendAttribute( $dom->createAttributeNode( 'modified', $this->isModified( 'documents', array( $index ) ) ) );
                if ( $document['os'] )
                    $documentNode->appendAttribute( $dom->createAttributeNode( 'os', $document['os'] ) );
                if ( $document['audience'] )
                    $documentNode->appendAttribute( $dom->createAttributeNode( 'audience', $document['audience'] ) );
                $documentsNode->appendChild( $documentNode );
                if ( $document['create-document'] )
                {
                    eZFile::create( $document['name'], $this->path() . '/' . eZPackage::documentDirectory(),
                                    $document['data'] );
                }
                ++$index;
            }
            $root->appendChild( $documentsNode );
        }

        if ( count( $groups ) > 0 )
        {
            $groupsNode =& $dom->createElementNode( 'groups' );
            foreach ( $groups as $group )
            {
                $groupNode =& $dom->createElementNode( 'group', array( 'name' => $group['name'] ) );
                $groupsNode->appendChild( $groupNode );
            }
            $root->appendChild( $groupsNode );
        }

        if ( count( $changelogs ) > 0 )
        {
            $changelogsNode =& $dom->createElementNode( 'changelogs' );
            $changelogsNode->appendAttribute( $dom->createAttributeNode( 'ezchangelog', 'http://ez.no/ezpackage', 'xmlns' ) );
            $index = 0;
            foreach ( $changelogs as $changelog )
            {
                $changelogNode =& $dom->createElementNode( 'entry' );
                $changelogNode->appendAttribute( $dom->createAttributeNode( 'timestamp', $changelog['timestamp'] ) );
                $changelogNode->appendAttribute( $dom->createAttributeNode( 'person', $changelog['person'] ) );
                $changelogNode->appendAttribute( $dom->createAttributeNode( 'email', $changelog['email'] ) );
                foreach ( $changelog['changes'] as $change )
                {
                    $changelogNode->appendChild( $dom->createElementTextNode( 'change', $change ) );
                }
                if ( $this->isModified( 'changelog', array( $index ) ) )
                    $changelogNode->appendAttribute( $dom->createAttributeNode( 'modified', $this->isModified( 'changelog', array( $index ) ) ) );
                $changelogsNode->appendChild( $changelogNode );
                ++$index;
            }
            $root->appendChild( $changelogsNode );
        }

        $releaseNode =& $dom->createElementNode( 'release' );
        $versionNode =& $dom->createElementNode( 'version' );
        $versionNode->appendAttribute( $dom->createAttributeNode( 'ezversion', 'http://ez.no/ezpackage', 'xmlns' ) );
        $numberAttributes = array();
        if ( $this->isModified( 'release', array( 'version', 'number' ) ) )
            $numberAttributes['modified'] = $this->isModified( 'release', array( 'version', 'number' ) );
        $versionNode->appendChild( $dom->createElementTextNode( 'number', $release['version']['number'],
                                                                $numberAttributes ) );
        $releaseAttributes = array();
        if ( $this->isModified( 'release', array( 'version', 'release' ) ) )
            $releaseAttributes['modified'] = $this->isModified( 'release', array( 'version', 'release' ) );
        $versionNode->appendChild( $dom->createElementTextNode( 'release', $release['version']['release'],
                                                                $releaseAttributes ) );
        $releaseNode->appendChild( $versionNode );
        if ( $release['timestamp'] )
        {
//             $release['timestamp'] = mktime();
            $timestampAttributes = array();
            if ( $this->isModified( 'release', array( 'timestamp' ) ) )
                $timestampAttributes['modified'] = $this->isModified( 'release', array( 'timestamp' ) );
            $releaseNode->appendChild( $dom->createElementTextNode( 'timestamp', $release['timestamp'],
                                                                    $timestampAttributes ) );
        }
        $licenceAttributes = array();
        if ( $this->isModified( 'release', array( 'licence' ) ) )
            $licenceAttributes['modified'] = $this->isModified( 'release', array( 'licence' ) );
        if ( $release['licence'] )
            $releaseNode->appendChild( $dom->createElementTextNode( 'licence', $release['licence'],
                                                                    $licenceAttributes ) );
        $stateAttributes = array();
        if ( $this->isModified( 'release', array( 'state' ) ) )
            $stateAttributes['modified'] = $this->isModified( 'release', array( 'state' ) );
        if ( $release['state'] )
            $releaseNode->appendChild( $dom->createElementTextNode( 'state', $release['state'],
                                                                    $stateAttributes ) );

        $providesNode =& $dom->createElementNode( 'provides' );
        $providesNode->appendAttribute( $dom->createAttributeNode( 'ezprovision', 'http://ez.no/ezpackage', 'xmlns' ) );
        if ( isset( $release['provides']['file-lists'] ) )
        {
            foreach ( $release['provides']['file-lists'] as $fileList )
            {
                $fileListNode =& $dom->createElementNode( 'file-list' );
                if ( $fileList['role'] )
                    $fileListNode->appendAttribute( $dom->createAttributeNode( 'role', $fileList['role'] ) );
                if ( $fileList['sub-directory'] )
                    $fileListNode->appendAttribute( $dom->createAttributeNode( 'sub-directory', $fileList['sub-directory'] ) );
                foreach ( $fileList['parameters'] as $parameterName => $parameterValue )
                {
                    $fileListNode->appendAttribute( $dom->createAttributeNode( $parameterName, $parameterValue ) );
                }
                $providesNode->appendChild( $fileListNode );
                foreach ( $fileList['files'] as $file )
                {
                    $fileNode =& $dom->createElementNode( 'file', array( 'name' => $file['name'] ) );
                    if ( $file['role'] )
                        $fileNode->appendAttribute( $dom->createAttributeNode( 'role', $file['role'] ) );
                    if ( $file['sub-directory'] )
                        $fileNode->appendAttribute( $dom->createAttributeNode( 'sub-directory', $file['sub-directory'] ) );
                    if ( $file['md5sum'] )
                        $fileNode->appendAttribute( $dom->createAttributeNode( 'md5sum', $file['md5sum'] ) );
                    $fileListNode->appendChild( $fileNode );
                }
            }
        }
        $releaseNode->appendChild( $providesNode );

        $installNode =& $dom->createElementNode( 'install' );
        $installNode->appendAttribute( $dom->createAttributeNode( 'ezinstall', 'http://ez.no/ezpackage', 'xmlns' ) );
        $installPreNode =& $dom->createElementNode( 'pre' );
        $installNode->appendChild( $installPreNode );
        $installPostNode =& $dom->createElementNode( 'post' );
        $installNode->appendChild( $installPostNode );
        $this->handleInstallPart( $installPreNode, $dom, $install['pre'] );
        $this->handleInstallPart( $installPostNode, $dom, $install['post'] );

        $releaseNode->appendChild( $installNode );

        $root->appendChild( $releaseNode );

        return $dom;
    }

    function handleInstallPart( &$installNode, &$dom, $list )
    {
        foreach ( $list as $item )
        {
            $type = $item['type'];
            switch ( $type )
            {
                case 'run':
                {
                } break;
                case 'database':
                {
                } break;
                case 'part':
                {
                    if ( $item['parameters']['content'] )
                    {
                        $content = $item['parameters']['content'];
                        $partNode =& $dom->createElementNode( 'part' );
                        $partType = $item['parameters']['type'];
                        $partNode->appendAttribute( $dom->createAttributeNode( 'type', $partType ) );
                        if ( $item['os'] )
                            $partNode->appendAttribute( $dom->createAttributeNode( 'os', $item['os'] ) );
                        if ( $item['name'] )
                            $partNode->appendAttribute( $dom->createAttributeNode( 'name', $item['name'] ) );
                        if ( get_class( $content ) == 'ezdomnode' )
                        {
                            $partContentNode =& $content;
                        }
                        if ( $item['filename'] )
                        {
                            $partNode->appendAttribute( $dom->createAttributeNode( 'filename', $item['filename'] ) );
                            if ( $item['subdirectory'] )
                            {
                                $partNode->appendAttribute( $dom->createAttributeNode( 'sub-directory', $item['subdirectory'] ) );
                            }
                        }
                        else
                        {
                            $partNode->appendChild( $partContentNode );
                        }
                        $installNode->appendChild( $partNode );
                    }
                } break;
                case 'operation':
                {
                } break;
            }
        }
    }

    /*!
     \private
     \return the package as a string, the string is in xml format.
    */
    function toString()
    {
        $dom =& $this->domStructure();
        $string = $dom->toString();
        return $string;
    }

    /*!
     \private
     Stores a cached version of the package in the cache directory
     under the repository for the package.
    */
    function storeCache( $directory )
    {
        if ( !file_exists( $directory ) )
            eZDir::mkdir( $directory, eZDir::directoryPermission(), true );
        include_once( 'lib/ezutils/classes/ezphpcreator.php' );
        $php =& new eZPHPCreator( $directory, 'package.php' );
        $php->addComment( "Automatically created cache file for the package format\n" .
                          "Do not modify this file" );
        $php->addSpace();
        $php->addVariable( 'Parameters', $this->Parameters, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT,
                           array( 'full-tree' => true ) );
        $php->addVariable( 'ModifiedParameters', $this->ModifiedParameters, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT,
                           array( 'full-tree' => true ) );
        $php->store();
    }

    /*!
     Stores the current package in the repository.
    */
    function store()
    {
        $path = eZPackage::repositoryPath() . '/' . $this->attribute( 'name' );
        if ( !file_exists( $path ) )
        {
            eZDir::mkdir( $path, eZDir::directoryPermission(), true );
        }
        $filePath = $path . '/package.xml';
        $result = $this->storeString( $filePath, $this->toString() );
        $this->storeCache( $path . '/' . $this->cacheDirectory() );
        return $result;
    }

    /*!
     Stores the current package to the file \a $filename.
    */
    function storeToFile( $filename )
    {
        print( "Storing package $filename\n" );
        return $this->storeString( $filename, $this->toString() );
    }

    /*!
     Stores the DOM tree \a $dom to the file \a $filename.
     The DOM tree will be turned into a string before being stored.
    */
    function storeDOM( $filename, $dom )
    {
        $data = $dom->toString();
        $this->storeString( $filename, $data );
    }

    /*!
     \private
     \static
     Stores the string data \a $data into the file \a $filename.
     \return \c true if successful.
    */
    function storeString( $filename, $data )
    {
        $file = fopen( $filename, 'w' );
        if ( $file )
        {
            fwrite( $file, $data );
            fclose( $file );
            return true;
        }
        return false;
    }


    /*!
     \private
     Loads the contents of the file \a $filename and parses it into a DOM tree.
     The DOM tree is returned.
    */
    function &fetchDOMFromFile( $filename )
    {
        if ( file_exists( $filename ) )
        {
            $fd = fopen( $filename, 'r' );
            if ( $fd )
            {
                $xmlText = fread( $fd, filesize( $filename ) );
                fclose( $fd );

                $xml = new eZXML();
                $dom =& $xml->domTree( $xmlText );
                return $dom;
            }
        }
        return false;
    }

    /*!
     Tries to load the package definition from file \a $filename
     and create a package object from it.
     \return \c false if it could be fetched.
    */
    function &fetchFromFile( $filename )
    {
        if ( !file_exists( $filename ) )
        {
            $filename .= '.ezpkg';
            if ( !file_exists( $filename ) )
                return false;
        }
        $fd = fopen( $filename, 'r' );
        if ( $fd )
        {
            $xmlText = fread( $fd, filesize( $filename ) );
            fclose( $fd );

            $xml = new eZXML();
            $dom =& $xml->domTree( $xmlText );

            $package =& new eZPackage();
            $parameters = $package->parseDOMTree( $dom );

            return $package;
        }
    }

    function handleExportList( $exportList )
    {
        $handlers = array();
        foreach ( $exportList as $exportItem )
        {
            $exportType = $exportItem['type'];
            $exportParameters = $exportItem['parameters'];
            $exportHandler = 'kernel/classes/packagehandlers/' . $exportType . '/' . $exportType . 'exporthandler.php';
            if ( file_exists( $exportHandler ) )
            {
                include_once( $exportHandler );
                $exportClass = $exportType . 'ExportHandler';
                if ( isset( $handlers[$exportType] ) )
                {
                    $handler =& $handlers[$exportType];
                    $handler->reset();
                }
                else
                {
                    $handler =& new $exportClass;
                    $handlers[$exportType] =& $handler;
                }
                $handler->handle( $this, $exportParameters );
            }
        }
    }

    /*!
     \return the full path to this package.
    */
    function path()
    {
        $path = eZPackage::repositoryPath();
        $path .= '/' . $this->attribute( 'name' );
        return $path;
    }

    /*!
     \static
     \return the directory name for packages, used in conjunction with eZSys::storageDirectory().
    */
    function repositoryDirectory()
    {
        $ini =& eZINI::instance( 'package.ini' );
        return $ini->variable( 'RepositorySettings', 'RepositoryDirectory' );
    }

    /*!
     \static
     \return the path to the package repository.
    */
    function repositoryPath()
    {
        $path = eZDir::path( array( eZSys::storageDirectory(),
                                    eZPackage::repositoryDirectory() ) );
        return $path;
    }

    /*!
     \static
     \return the name of the cache directory for cached package data.
    */
    function cacheDirectory()
    {
        return '.cache';
    }

    /*!
     \static
     \return the name of the documents directory for cached package data.
    */
    function documentDirectory()
    {
        return 'documents';
    }

    /*!
     Locates all packages in the repository and returns an array with eZPackage objects.
    */
    function fetchPackages()
    {
        $path = eZPackage::repositoryPath();
        $packages = array();
        if ( file_exists( $path ) )
        {
            $dir = opendir( $path );
            while( ( $file = readdir( $dir ) ) !== false )
            {
                $dirPath = $path . '/' . $file;
                if ( !is_dir( $dirPath ) )
                    continue;
                $filePath = $dirPath . '/package.xml';
                if ( file_exists( $filePath ) )
                {
                    $name = $file;
                    $packageCachePath = $dirPath . '/' . eZPackage::cacheDirectory() . '/package.php';
                    if ( file_exists( $packageCachePath ) )
                    {
                        include( $packageCachePath );
                        if ( isset( $Parameters ) )
                        {
                            $package = new eZPackage( $Parameters );
                        }
                    }
                    if ( !$package )
                    {
                        $package =& eZPackage::fetchFromFile( $filePath );
                        $package->storeCache( $dirPath . '/' . eZPackage::cacheDirectory() );
                    }
                    $packages[] =& $package;
                }
            }
            closedir( $dir );
        }
        return $packages;
    }

    /// \privatesection
    /// All interal data
    var $Parameters;
    /// Controls which data has been modified
    var $ModifiedParameters;
}

?>
