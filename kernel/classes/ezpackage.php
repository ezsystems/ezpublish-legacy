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
                          'state' => false );
        $defaults = array( 'name' => false,
                           'summary' => false,
                           'description' => false,
                           'vendor' => false,
                           'priority' => false,
                           'type' => false,
                           'extension' => false,
                           'ezpublish' => array( 'version' => false,
                                                 'named-version' => false ),

                           'maintainers' => array(),
                           'packaging' => $packaging,
                           'source' => false,
                           'documents' => array(),
                           'groups' => array(),
                           'changelog' => array(),
                           'release' => $release,
                           'dependencies' => array( 'provides' => array(),
                                                    'requires' => array(),
                                                    'obsoletes' => array(),
                                                    'conflicts' => array() ),
                           'install' => array(),
                           'uninstall' => array() );
        $this->Parameters = array_merge( $defaults, $parameters );
        $this->ModifiedParameters = array();
        foreach ( $modifiedParameters as $name => $modifiedParameter )
        {
            if ( $modifiedParameter !== false )
                $this->ModifiedParameters[$name] = mktime();
        }
    }

    /*!
     Removes some temporary variables.
    */
    function cleanup()
    {
        $documents =& $this->Parameters['documents'];
        foreach ( array_keys( $documents ) as $documentKey )
        {
            $document =& $documents[$documentKey];
            unset( $document['create-document'] );
            unset( $document['data'] );
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
                    $subdirectory = $install['sub-directory'];
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

    /*!
     Sets the attribute named \a $attributeName to have the value \a $attributeValue.
    */
    function setAttribute( $attributeName, $attributeValue )
    {
        if ( array_key_exists( $attributeName, $this->Parameters ) and
             !is_array( $this->Parameters[$attributeName] ))
        {
            $this->Parameters[$attributeName] = $attributeValue;
            $this->ModifiedParameters[$attributeName] = mktime();
        }
    }

    /*!
     \return the value of the attribute named \a $attributeName.
     If \a $attributeList is supplied and the value of the attribute is an array,
     it will fetch the value of keys specified in the list.
    */
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
                     !array_key_exists( $attributeKey, $attributeValue ) )
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

    function appendMaintainer( $name, $email, $role = false,
                               $modified = null )
    {
        $index = count( $this->Parameters['maintainers'] );
        $this->Parameters['maintainers'][$index] = array( 'name' => $name,
                                                          'email' => $email,
                                                          'role' => $role );
        if ( $modified === null )
            $modified = mktime();
        $this->ModifiedParameters['maintainers'][$index] = $modified;
    }

    function appendDocument( $name, $mimeType = false, $os = false, $audience = false,
                             $create = false, $data = false,
                             $modified = null )
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
        if ( $modified === null )
            $modified = mktime();
        $this->ModifiedParameters['documents'][$index] = $modified;
    }

    function appendGroup( $name, $modified = null )
    {
        if ( $modified === null )
            $modified = mktime();
        $index = count( $this->Parameters['groups'] );
        $this->Parameters['groups'][$index] = array( 'name' => $name,
                                                     'modified' => $modified );
    }

    function appendChange( $person, $email, $changes,
                           $release = false, $timestamp = null, $modified = null )
    {
        if ( $timestamp === null )
            $timestamp = mktime();
        if ( $modified === null )
            $modified = mktime();
        if ( !is_array( $changes ) )
            $changes = array( $changes );
        $index = count( $this->Parameters['changelog'] );
        if ( !$release )
            $release = $this->Parameters['release']['version']['release'];
        if ( !$release )
            $release = 1;
        $this->Parameters['changelog'][$index] = array( 'timestamp' => $timestamp,
                                                        'person' => $person,
                                                        'email' => $email,
                                                        'changes' => $changes,
                                                        'release' => $release );
        $this->ModifiedParameters['changelog'][$index] = $modified;
    }

    function appendProvides( $name, $value,
                             $parameters = false, $modified = null )
    {
        $this->appendDependency( 'provides', $name, $value,
                                 $parameters, $modified );
    }

    function appendDependency( $dependencyName, $name, $value,
                               $parameters = false, $modified = null )
    {
        if ( $modified === null )
            $modified = mktime();
        $parameters['name'] = $name;
        $parameters['value'] = $value;
        $parameters['modified'] = $modified;
        $this->Parameters['dependencies'][$dependencyName][] = $parameters;
    }

    function appendFileList( $files, $role = false, $subDirectory = false,
                             $parameters = false, $modified = null )
    {
        if ( $modified === null )
            $modified = mktime();
        $index = count( $this->Parameters['dependencies']['provides'] );
        $parameters['name'] = 'file-list';
        $parameters['files'] = $files;
        $parameters['role'] = $role;
        $parameters['sub-directory'] = $subDirectory;
        $this->Parameters['dependencies']['provides'][$index] = $parameters;
        $this->ModifiedParameters['dependencies']['provides'][$index] = $modified;
    }

    function appendInstall( $type, $name, $os = false, $isInstall = true,
                            $filename = false, $subdirectory = false,
                            $parameters = false, $modified = null )
    {
        if ( $modified === null )
            $modified = mktime();
        $installEntry = $parameters;
        $installEntry['type'] = $type;
        $installEntry['name'] = $name;
        $installEntry['modified'] = $modified;
        $installEntry['os'] = $os;
        $installEntry['filename'] = $filename;
        $installEntry['sub-directory'] = $subdirectory;
        if ( $installEntry['filename'] )
        {
            $content = $installEntry['content'];
            if ( get_class( $content ) == 'ezdomnode' )
            {
                $partContentNode =& $content;
                $path = $this->path();
                if ( $installEntry['sub-directory'] )
                {
                    $path .= '/' . $installEntry['sub-directory'];
                }
                $filePath = $path . '/' . $installEntry['filename'] . '.xml';
                if ( !file_exists( $path ) )
                    eZDir::mkdir( $path, eZDir::directoryPermission(), true );
                $partDOM = new eZDOMDocument();
                $partDOM->setRoot( $partContentNode );
                $this->storeDOM( $filePath, $partDOM );
                $installEntry['content'] = false;
            }
        }
        $installName = 'install';
        if ( !$isInstall )
            $installName = 'uninstall';
        $this->Parameters[$installName][] = $installEntry;
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
                         $licence = false, $state = false,
                         $modification = false )
    {
        if ( !$modification )
            $modification = array();
        $time = mktime();
        $modification = array_merge( array( 'number' => $time,
                                            'release' => $time,
                                            'timestamp' => $time,
                                            'licence' => $time,
                                            'state' => $time ),
                                     $modification );
        if ( $version )
        {
            $this->Parameters['release']['version']['number'] = $version;
            $this->ModifiedParameters['release']['version']['number'] = $modification['number'];
        }
        if ( $release )
        {
            $this->Parameters['release']['version']['release'] = $release;
            $this->ModifiedParameters['release']['version']['release'] = $modification['release'];
        }
        if ( $timestamp )
        {
            $this->Parameters['release']['timestamp'] = $timestamp;
            $this->ModifiedParameters['release']['timestamp'] = $modification['timestamp'];
        }
        if ( $licence )
        {
            $this->Parameters['release']['licence'] = $licence;
            $this->ModifiedParameters['release']['licence'] = $modification['licence'];
        }
        if ( $state )
        {
            $this->Parameters['release']['state'] = $state;
            $this->ModifiedParameters['release']['state'] = $modification['state'];
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
        $this->cleanup();
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
     \static
     Tries to load the package definition from file \a $filename
     and create a package object from it.
     \return \c false if it could be fetched.
    */
    function &fetchFromFile( $filename )
    {
        if ( !file_exists( $filename ) )
        {
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

    /*!
     \static
     Tries to load the package named \a $packageName from the repository
     and returns the package object.
     \return \c false if no package could be found.
    */
    function &fetch( $packageName )
    {
        $path = eZPackage::repositoryPath() . '/' . $packageName;
        $filePath = $path . '/package.xml';
        if ( file_exists( $filePath ) )
        {
            $fileModification = filemtime( $filePath );
            $packageCachePath = $path . '/' . eZPackage::cacheDirectory() . '/package.php';
            $cacheExpired = false;
            if ( file_exists( $packageCachePath ) )
            {
                $cacheModification = filemtime( $packageCachePath );
                if ( $cacheModification >= $fileModification )
                {
                    include( $packageCachePath );
                    if ( isset( $Parameters ) and
                         isset( $ModifiedParameters ) )
                    {
                        $package = new eZPackage( $Parameters );
                        $package->ModifiedParameters = $ModifiedParameters;
                        return $package;
                    }
                }
                else
                    $cacheExpired = true;
            }
            $package =& eZPackage::fetchFromFile( $filePath );
            if ( $cacheExpired )
            {
                $package->storeCache( $path . '/' . eZPackage::cacheDirectory() );
            }
            return $package;
        }
        return false;
    }

//     function handleExportList( $exportList )
//     {
//         $handlers = array();
//         foreach ( $exportList as $exportItem )
//         {
//             $exportType = $exportItem['type'];
//             $exportParameters = $exportItem['parameters'];
//             $exportHandler = 'kernel/classes/packagehandlers/' . $exportType . '/' . $exportType . 'exporthandler.php';
//             if ( file_exists( $exportHandler ) )
//             {
//                 include_once( $exportHandler );
//                 $exportClass = $exportType . 'ExportHandler';
//                 if ( isset( $handlers[$exportType] ) )
//                 {
//                     $handler =& $handlers[$exportType];
//                     $handler->reset();
//                 }
//                 else
//                 {
//                     $handler =& new $exportClass;
//                     $handlers[$exportType] =& $handler;
//                 }
//                 $handler->handle( $this, $exportParameters );
//             }
//         }
//     }

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

    function parseModification( &$node, $elementName, $attributeName = false, $attributeList = array() )
    {
        if ( !$attributeName )
            $attributeName = $elementName;
        $modification = $node->elementAttributeValueByName( $elementName, 'modified' );
        if ( $modification )
        {
            $reference =& $this->ModifiedParameters[$attributeName];
            foreach ( $attributeList as $attributeItem )
            {
                $reference =& $reference[$attributeItem];
            }
            $reference = $modification;
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

        // Read basic info
        $parameters = array();
        $parameters['name'] = $root->elementTextContentByName( 'name' );
        $parameters['summary'] = $root->elementTextContentByName( 'summary' );
        $parameters['description'] = $root->elementTextContentByName( 'description' );
        $parameters['vendor'] = $root->elementTextContentByName( 'vendor' );
        $parameters['priority'] = $root->elementAttributeValueByName( 'priority', 'value' );
        $parameters['type'] = $root->elementAttributeValueByName( 'type', 'value' );
        $parameters['source'] = $root->elementTextContentByName( 'source' );
        $extensionNode =& $root->elementByName( 'extension' );
        if ( $extensionNode )
            $parameters['extension'] = $extensionNode->attributeValue( 'name' );
        $ezpublishNode =& $root->elementByName( 'ezpublish' );
        $parameters['ezpublish']['version'] = $ezpublishNode->elementTextContentByName( 'version' );
        $parameters['ezpublish']['named-version'] = $ezpublishNode->elementTextContentByName( 'named-version' );
        $this->setParameters( $parameters );
        $this->parseModification( $root, 'name' );
        $this->parseModification( $root, 'summary' );
        $this->parseModification( $root, 'description' );
        $this->parseModification( $root, 'vendor' );
        $this->parseModification( $root, 'priority' );
        $this->parseModification( $root, 'type' );
        $this->parseModification( $root, 'extension' );
        $this->parseModification( $root, 'source' );

        // Read maintainers
        $maintainerList =& $root->elementChildrenByName( 'maintainers' );
        foreach ( array_keys( $maintainerList ) as $maintainerKey )
        {
            $maintainerNode =& $maintainerList[$maintainerKey];
            $maintainerModified = $maintainerNode->attributeValue( 'modified' );
            $maintainerName = $maintainerNode->elementTextContentByName( 'name' );
            $maintainerEmail = $maintainerNode->elementTextContentByName( 'email' );
            $maintainerRole = $maintainerNode->elementTextContentByName( 'role' );
            $this->appendMaintainer( $maintainerName, $maintainerEmail, $maintainerRole,
                                     $maintainerModified );
        }

        // Read packaging info
        $packagingNode =& $root->elementByName( 'packaging' );
        $packagingTimestamp = $packagingNode->elementTextContentByName( 'timestamp' );
        $packagingHost = $packagingNode->elementTextContentByName( 'host' );
        $packagingPackager = $packagingNode->elementTextContentByName( 'packager' );
        $this->setPackager( $packagingTimestamp, $packagingHost, $packagingPackager );

        // Read documents
        $documentList =& $root->elementChildrenByName( 'documents' );
        foreach ( array_keys( $documentList ) as $documentKey )
        {
            $documentNode =& $documentList[$documentKey];
            $documentModified = $documentNode->attributeValue( 'modified' );
            $documentName = $documentNode->attributeValue( 'name' );
            $documentMimeType = $documentNode->attributeValue( 'mime-type' );
            $documentOS = $documentNode->attributeValue( 'os' );
            $documentAudience = $documentNode->attributeValue( 'audience' );
            $this->appendDocument( $documentName, $documentMimeType,
                                   $documentOS, $documentAudience,
                                   false, false,
                                   $documentModified );
        }

        // Read changelog
        $changelogList =& $root->elementChildrenByName( 'changelog' );
        foreach ( array_keys( $changelogList ) as $changelogKey )
        {
            $changelogEntryNode =& $changelogList[$changelogKey];
            $changelogModified = $changelogEntryNode->attributeValue( 'modified' );
            $changelogTimestamp = $changelogEntryNode->attributeValue( 'timestamp' );
            $changelogPerson = $changelogEntryNode->attributeValue( 'person' );
            $changelogEmail = $changelogEntryNode->attributeValue( 'email' );
            $changelogRelease = $changelogEntryNode->attributeValue( 'release' );
            $changelogChangeList = $changelogEntryNode->elementsTextContentByName( 'change' );
            $this->appendChange( $changelogPerson, $changelogEmail, $changelogChangeList,
                                 $changelogRelease, $changelogTimestamp, $changelogModified );
        }

        // Read release info
        $versionNode =& $root->elementByName( 'version' );
        $versionNumber = $versionNode->elementTextContentByName( 'number' );
        $versionRelease = $versionNode->elementTextContentByName( 'release' );
        $licence = $root->elementTextContentByName( 'licence' );
        $state = $root->elementTextContentByName( 'state' );
        $releaseModifications = array();
        $releaseNumberModification = $versionNode->elementAttributeValueByName( 'number', 'modified' );
        if ( $releaseNumberModification )
            $releaseModifications['number'] = $releaseNumberModification;
        $releaseReleaseModification = $versionNode->elementAttributeValueByName( 'release', 'modified' );
        if ( $releaseReleaseModification )
            $releaseModifications['release'] = $releaseReleaseModification;
        $releaseLicenceModification = $root->elementAttributeValueByName( 'licence', 'modified' );
        if ( $releaseLicenceModification )
            $releaseModifications['licence'] = $releaseLicenceModification;
        $releaseStateModification = $root->elementAttributeValueByName( 'state', 'modified' );
        if ( $releaseStateModification )
            $releaseModifications['state'] = $releaseStateModification;
        $this->setRelease( $versionNumber, $versionRelease, false,
                           $licence, $state,
                           $releaseModifications );

        $dependenciesNode =& $root->elementByName( 'dependencies' );
        $providesList =& $dependenciesNode->elementChildrenByName( 'provides' );
        $requiresList =& $dependenciesNode->elementChildrenByName( 'requires' );
        $obsoletesList =& $dependenciesNode->elementChildrenByName( 'obsoletes' );
        $conflictsList =& $dependenciesNode->elementChildrenByName( 'conflicts' );
        $this->parseDependencyTree( $providesList, 'provides' );
        $this->parseDependencyTree( $requiresList, 'requires' );
        $this->parseDependencyTree( $obsoletesList, 'obsoletes' );
        $this->parseDependencyTree( $conflictsList, 'conflicts' );

        $installList =& $root->elementChildrenByName( 'install' );
        $uninstallList =& $root->elementChildrenByName( 'uninstall' );
        $this->parseInstallTree( $installList, true );
        $this->parseInstallTree( $uninstallList, false );
    }

    /*!
     \private
    */
    function parseDependencyTree( &$node, $name )
    {
        foreach ( array_keys( $dependenciesList ) as $dependencyKey )
        {
            $dependencyNode =& $dependenciesList[$dependencyKey];
        }
    }

    /*!
     \private
    */
    function parseInstallTree( &$installList, $isInstall )
    {
        for ( $i = 0; $i < count( $installList ); ++$i )
        {
            $installNode =& $installList[$i];
        }
//         $installType = $child->name();
//         switch ( $installType )
//         {
//             case 'run':
//             {
//             } break;
//             case 'database':
//             {
//             } break;
//             case 'part':
//             {
//                 $os = $child->attributeValue( 'os' );
//                 $name = $child->attributeValue( 'name' );
//                 $type = $child->attributeValue( 'type' );
//                 $filename = $child->attributeValue( 'filename' );
//                 $subdirectory = $child->attributeValue( 'sub-directory' );
//                 $modified = $child->attributeValue( 'modified' );
//                 $content = false;
//                 if ( !$filename )
//                 {
//                     $content =& $child->firstChild();
//                 }
//                 $this->appendInstall( 'part', $name, $os, true,
//                                       $filename, $subdirectory,
//                                       array( 'type' => $type,
//                                              'content' => $content ),
//                                       $modified );
//             } break;
//             case 'operation':
//             {
//             } break;
//         }
    }

    /*!
     \return the dom document of the package.
    */
    function &domStructure()
    {
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
        $source = $this->attribute( 'source' );
        $sourceAttributes = array();
        if ( $this->isModified( 'source' ) )
            $sourceAttributes['modified'] = $this->isModified( 'source' );

        $ezpublish = $this->attribute( 'ezpublish' );
        $maintainers = $this->attribute( 'maintainers' );
        $packaging = $this->attribute( 'packaging' );
        $documents = $this->attribute( 'documents' );
        $groups = $this->attribute( 'groups' );
        $release = $this->attribute( 'release' );
        $dependencies = $this->attribute( 'dependencies' );
        $install = $this->attribute( 'install' );
        $uninstall = $this->attribute( 'uninstall' );
        $changelog = $this->attribute( 'changelog' );

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
        if ( $source )
            $root->appendChild( $dom->createElementTextNode( 'source', $source,
                                                             $sourceAttributes ) );

        $ezpublishNode =& $dom->createElementNode( 'ezpublish' );
        $ezpublishNode->appendAttribute( $dom->createAttributeNode( 'ezpublish', 'http://ez.no/ezpublish', 'xmlns' ) );
        if ( !$ezpublish['version'] )
        {
            include_once( 'lib/version.php' );
            $ezpublish['version'] = eZPublishSDK::version( true );
            $ezpublish['named-version'] = eZPublishSDK::version( false, true );
        }
        $ezpublishNode->appendChild( $dom->createElementTextNode( 'version', $ezpublish['version'] ) );
        $ezpublishNode->appendChild( $dom->createElementTextNode( 'named-version', $ezpublish['named-version'] ) );
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
                if ( isset( $document['create-document'] ) and
                     $document['create-document'] )
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
                $groupAttributes = array( 'name' => $group['name'] );
                $groupModified = $group['modified'];
                if ( $groupModified )
                    $groupAttributes['modified'] = $groupModified;
                $groupNode =& $dom->createElementNode( 'group', $groupAttributes );
                $groupsNode->appendChild( $groupNode );
            }
            $root->appendChild( $groupsNode );
        }

        if ( count( $changelog ) > 0 )
        {
            $changelogNode =& $dom->createElementNode( 'changelog' );
            $changelogNode->appendAttribute( $dom->createAttributeNode( 'ezchangelog', 'http://ez.no/ezpackage', 'xmlns' ) );
            $index = 0;
            foreach ( $changelog as $changeEntry )
            {
                $changeEntryNode =& $dom->createElementNode( 'entry' );
                $changeEntryNode->appendAttribute( $dom->createAttributeNode( 'timestamp', $changeEntry['timestamp'] ) );
                $changeEntryNode->appendAttribute( $dom->createAttributeNode( 'person', $changeEntry['person'] ) );
                $changeEntryNode->appendAttribute( $dom->createAttributeNode( 'email', $changeEntry['email'] ) );
                $changeEntryNode->appendAttribute( $dom->createAttributeNode( 'release', $changeEntry['release'] ) );
                foreach ( $changeEntry['changes'] as $change )
                {
                    $changeEntryNode->appendChild( $dom->createElementTextNode( 'change', $change ) );
                }
                if ( $this->isModified( 'changelog', array( $index ) ) )
                    $changeEntryNode->appendAttribute( $dom->createAttributeNode( 'modified', $this->isModified( 'changelog', array( $index ) ) ) );
                $changelogNode->appendChild( $changeEntryNode );
                ++$index;
            }
            $root->appendChild( $changelogNode );
        }

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
        $root->appendChild( $versionNode );
        if ( $release['timestamp'] )
        {
            $timestampAttributes = array();
            if ( $this->isModified( 'release', array( 'timestamp' ) ) )
                $timestampAttributes['modified'] = $this->isModified( 'release', array( 'timestamp' ) );
            $root->appendChild( $dom->createElementTextNode( 'timestamp', $release['timestamp'],
                                                                    $timestampAttributes ) );
        }
        $licenceAttributes = array();
        if ( $this->isModified( 'release', array( 'licence' ) ) )
            $licenceAttributes['modified'] = $this->isModified( 'release', array( 'licence' ) );
        if ( $release['licence'] )
            $root->appendChild( $dom->createElementTextNode( 'licence', $release['licence'],
                                                                    $licenceAttributes ) );
        $stateAttributes = array();
        if ( $this->isModified( 'release', array( 'state' ) ) )
            $stateAttributes['modified'] = $this->isModified( 'release', array( 'state' ) );
        if ( $release['state'] )
            $root->appendChild( $dom->createElementTextNode( 'state', $release['state'],
                                                                    $stateAttributes ) );

        $dependencyNode =& $dom->createElementNode( 'dependencies' );
        $dependencyNode->appendAttribute( $dom->createAttributeNode( 'ezdependency', 'http://ez.no/ezpackage', 'xmlns' ) );

//         if ( isset( $release['provides']['file-lists'] ) )
//         {
//             foreach ( $release['provides']['file-lists'] as $fileList )
//             {
//                 $fileListNode =& $dom->createElementNode( 'file-list' );
//                 if ( $fileList['role'] )
//                     $fileListNode->appendAttribute( $dom->createAttributeNode( 'role', $fileList['role'] ) );
//                 if ( $fileList['sub-directory'] )
//                     $fileListNode->appendAttribute( $dom->createAttributeNode( 'sub-directory', $fileList['sub-directory'] ) );
//                 foreach ( $fileList['parameters'] as $parameterName => $parameterValue )
//                 {
//                     $fileListNode->appendAttribute( $dom->createAttributeNode( $parameterName, $parameterValue ) );
//                 }
//                 $providesNode->appendChild( $fileListNode );
//                 foreach ( $fileList['files'] as $file )
//                 {
//                     $fileNode =& $dom->createElementNode( 'file', array( 'name' => $file['name'] ) );
//                     if ( $file['role'] )
//                         $fileNode->appendAttribute( $dom->createAttributeNode( 'role', $file['role'] ) );
//                     if ( $file['sub-directory'] )
//                         $fileNode->appendAttribute( $dom->createAttributeNode( 'sub-directory', $file['sub-directory'] ) );
//                     if ( $file['md5sum'] )
//                         $fileNode->appendAttribute( $dom->createAttributeNode( 'md5sum', $file['md5sum'] ) );
//                     $fileListNode->appendChild( $fileNode );
//                 }
//             }
//         }

        $providesNode =& $dependencyNode->appendChild( $dom->createElementNode( 'provides' ) );
        $requiresNode =& $dependencyNode->appendChild( $dom->createElementNode( 'requires' ) );
        $obsoletesNode =& $dependencyNode->appendChild( $dom->createElementNode( 'obsoletes' ) );
        $conflictsNode =& $dependencyNode->appendChild( $dom->createElementNode( 'conflicts' ) );

        $this->createDependencyTree( $providesNode, 'provide', $dependencies['provides'] );
        $this->createDependencyTree( $requiresNode, 'require', $dependencies['requires'] );
        $this->createDependencyTree( $obsoletesNode, 'obsolete', $dependencies['obsoletes'] );
        $this->createDependencyTree( $conflictsNode, 'conflict', $dependencies['conflicts'] );

        $root->appendChild( $dependencyNode );

        $installNode =& $dom->createElementNode( 'install' );
        $installNode->appendAttribute( $dom->createAttributeNode( 'ezinstall', 'http://ez.no/ezpackage', 'xmlns' ) );
        $uninstallNode =& $dom->createElementNode( 'uninstall' );
        $uninstallNode->appendAttribute( $dom->createAttributeNode( 'ezinstall', 'http://ez.no/ezpackage', 'xmlns' ) );

        $this->createInstallTree( $installNode, $dom, $install, 'install' );
        $this->createInstallTree( $uninstallNode, $dom, $uninstall, 'uninstall' );

        $root->appendChild( $installNode );
        $root->appendChild( $uninstallNode );

        return $dom;
    }

    /*!
     \private
     Creates xml elements as children of the main node \a $installNode.
     The install elements are taken from \a $list.
     \param $installType Is either \c 'install' or \c 'uninstall'
    */
    function createInstallTree( &$installNode, &$dom, $list, $installType )
    {
        foreach ( $list as $installItem )
        {
            $type = $installItem['type'];
            if ( $installItem['content'] or
                 $installItem['filename'] )
            {
                $installItemNode =& $dom->createElementNode( 'item',
                                                             array( 'type' => $type ) );
                $installNode->appendChild( $installItemNode );
                $content = $installItem['content'];
                if ( $installItem['os'] )
                    $installItemNode->appendAttribute( $dom->createAttributeNode( 'os', $installItem['os'] ) );
                if ( $installItem['name'] )
                    $installItemNode->appendAttribute( $dom->createAttributeNode( 'name', $installItem['name'] ) );
                $installModified = $installItem['modified'];
                if ( $installModified )
                    $installItemNode->appendAttribute( $dom->createAttributeNode( 'modified', $installModified ) );
                if ( $installItem['filename'] )
                {
                    $installItemNode->appendAttribute( $dom->createAttributeNode( 'filename', $installItem['filename'] ) );
                    if ( $installItem['sub-directory'] )
                    {
                        $installItemNode->appendAttribute( $dom->createAttributeNode( 'sub-directory', $installItem['sub-directory'] ) );
                    }
                }
                else
                {
                    $installItemNode->appendChild( $content );
                }

                $handler =& $this->packageHandler( $type );
                if ( $handler )
                {
                    $handler->createInstallNode( $installItemNode, $installItem, $installType );
                }
            }
        }
    }

    /*!
     Creates dependency xml elements as child of $dependenciesNode.
     The dependency elements are take from \a $list.
     \param $dependencyType Is either \c 'provide', \c 'require', \c 'obsolete' or \c 'conflict'
    */
    function createDependencyTree( &$dependenciesNode, $dependencyType, $list )
    {
        foreach ( $list as $dependencyItem )
        {
            $dependencyNode =& eZDOMDocument::createElementNode( $dependencyType );
            $dependencyNode->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $dependencyItem['name'] ) );
            $dependencyNode->appendAttribute( eZDOMDocument::createAttributeNode( 'value', $dependencyItem['value'] ) );
            $dependencyModified = $dependencyItem['modified'];
            if ( $dependencyModified )
                $dependencyNode->appendAttribute( eZDOMDocument::createAttributeNode( 'modified', $dependencyModified ) );
            $dependenciesNode->appendChild( $dependencyNode );
            $handler =& $this->packageHandler( $dependencyItem['name'] );
            if ( $handler )
            {
                $handler->createDependencyNode( $dependencyNode, $dependencyItem, $dependencyType );
            }
        }
    }

    /*!
     \return the package handler object for the handler named \a $handlerName.
    */
    function &packageHandler( $handlerName )
    {
        $handlers =& $GLOBALS['eZPackageHandlers'];
        if ( !isset( $handlers ) )
            $handlers = array();
        $ini =& eZINI::instance( 'package.ini' );
        $repository = $ini->variable( 'PackageSettings', 'PackageHandlerRepository' );
        $handlerFile = $repository . '/' . $handlerName . '/' . $handlerName . 'packagehandler.php';
        $handler = false;
        if ( file_exists( $handlerFile ) )
        {
            include_once( $handlerFile );
            $handlerClassName = $handlerName . 'PackageHandler';
            if ( isset( $handlers[$handlerName] ) )
            {
                $handler =& $handlers[$handlerName];
                $handler->reset();
            }
            else
            {
                $handler =& new $handlerClassName;
                $handlers[$handlerName] =& $handler;
            }
        }
        return $handler;
    }

    /// \privatesection
    /// All interal data
    var $Parameters;
    /// Controls which data has been modified
    var $ModifiedParameters;
}

?>
