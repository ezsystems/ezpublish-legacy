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
include_once( 'lib/ezfile/classes/ezfilehandler.php' );

define( 'EZ_PACKAGE_VERSION', '3.2-1' );
define( 'EZ_PACKAGE_DEVELOPMENT', true );
define( 'EZ_PACKAGE_USE_CACHE', false );

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
        if ( isset( $_SERVER['HOSTNAME'] ) )
            $host = $_SERVER['HOSTNAME'];
        else
            $host = $_SERVER['HTTP_HOST'];
        $packaging = array( 'timestamp' => $timestamp,
                            'host' => $host,
                            'packager' => false );
        include_once( 'lib/version.php' );
        $ezpublishVersion = eZPublishSDK::version( true );
        $ezpublishNamedVersion = eZPublishSDK::version( false, true );
        $ezpublish = array( 'version' => $ezpublishVersion,
                            'named-version' => $ezpublishNamedVersion );
        $defaults = array( 'name' => false,
                           'summary' => false,
                           'description' => false,
                           'vendor' => false,
                           'priority' => false,
                           'type' => false,
                           'extension' => false,
                           'ezpublish' => $ezpublish,
                           'maintainers' => array(),
                           'packaging' => $packaging,
                           'source' => false,
                           'documents' => array(),
                           'groups' => array(),
                           'changelog' => array(),
                           'file-list' => array(),
                           'version-number' => false,
                           'release-number' => false,
                           'release-timestamp' => false,
                           'licence' => false,
                           'state' => false,
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
                        if ( $handler->extractInstallContent() )
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
        if ( !in_array( $attributeName,
                        array( 'name', 'summary', 'description',
                               'vendor', 'priority', 'type',
                               'extension', 'source',
                               'licence', 'state' ) ) )
            return false;
        if ( array_key_exists( $attributeName, $this->Parameters ) and
             !is_array( $this->Parameters[$attributeName] ))
        {
            $this->Parameters[$attributeName] = $attributeValue;
            $this->ModifiedParameters[$attributeName] = mktime();
            return true;
        }
        return false;
    }

    /*!
     \return \c true if the attribute named \a $attributeName exists.
    */
    function hasAttribute( $attributeName /*, $attributeList = false*/ )
    {
        return in_array( $attributeName,
                         array( 'name', 'summary', 'description',
                                'vendor', 'priority', 'type',
                                'extension', 'source',
                                'version-number', 'release-number', 'release-timestamp',
                                'maintainers', 'documents', 'groups',
                                'file-list',
                                'changelog', 'dependencies',
                                'install', 'uninstall',
                                'licence', 'state',
                                'ezpublish-version', 'ezpublish-named-version', 'packaging-timestamp',
                                'packaging-host', 'packaging-packager' ) );
    }

    /*!
     \return the value of the attribute named \a $attributeName.
    */
    function attribute( $attributeName /*, $attributeList = false*/ )
    {
        if ( in_array( $attributeName,
                       array( 'name', 'summary', 'description',
                              'vendor', 'priority', 'type',
                              'extension', 'source',
                              'version-number', 'release-number', 'release-timestamp',
                              'maintainers', 'documents', 'groups',
                              'file-list',
                              'changelog', 'dependencies',
                              'install', 'uninstall',
                              'licence', 'state' ) ) )
            return $this->Parameters[$attributeName];
        else if ( $attributeName == 'ezpublish-version' )
            return $this->Parameters['ezpublish']['version'];
        else if ( $attributeName == 'ezpublish-named-version' )
            return $this->Parameters['ezpublish']['named-version'];
        else if ( $attributeName == 'packaging-timestamp' )
            return $this->Parameters['packaging']['timestamp'];
        else if ( $attributeName == 'packaging-host' )
            return $this->Parameters['packaging']['host'];
        else if ( $attributeName == 'packaging-packager' )
            return $this->Parameters['packaging']['packager'];

        eZDebug::writeError( "No such attribute: $attributeName for eZPackage", 'eZPackage::attribute' );
        return null;
    }

    function isModified( $attributeName /*, $attributeList = false*/ )
    {
        if ( in_array( $attributeName,
                       array( 'name', 'summary', 'description',
                              'vendor', 'priority', 'type',
                              'extension', 'source',
                              'version-number', 'release-number', 'release-timestamp',
                              'licence', 'state' ) ) )
        {
            if ( array_key_exists( $attributeName, $this->ModifiedParameters ) )
                return $this->ModifiedParameters[$attributeName];
            else
                return null;
        }
        else if ( $attributeName == 'ezpublish-version' )
        {
            if ( array_key_exists( 'ezpublish', $this->ModifiedParameters ) and
                 array_key_exists( 'version', $this->ModifiedParameters['ezpublish'] ) )
                return $this->ModifiedParameters['ezpublish']['version'];
            else
                return null;
        }
        else if ( $attributeName == 'ezpublish-named-version' )
        {
            if ( array_key_exists( 'ezpublish', $this->ModifiedParameters ) and
                 array_key_exists( 'named-version', $this->ModifiedParameters['ezpublish'] ) )
                return $this->ModifiedParameters['ezpublish']['named-version'];
            else
                return null;
        }
        else if ( $attributeName == 'packaging-timestamp' )
        {
            if ( array_key_exists( 'packaging', $this->ModifiedParameters ) and
                 array_key_exists( 'timestamp', $this->ModifiedParameters['packaging'] ) )
                return $this->ModifiedParameters['packaging']['timestamp'];
            else
                return null;
        }
        else if ( $attributeName == 'packaging-host' )
        {
            if ( array_key_exists( 'packaging', $this->ModifiedParameters ) and
                 array_key_exists( 'host', $this->ModifiedParameters['packaging'] ) )
                return $this->ModifiedParameters['packaging']['host'];
            else
                return null;
        }
        else if ( $attributeName == 'packaging-packager' )
        {
            if ( array_key_exists( 'packaging', $this->ModifiedParameters ) and
                 array_key_exists( 'packager', $this->ModifiedParameters['packaging'] ) )
                return $this->ModifiedParameters['packaging']['packager'];
            else
                return null;
        }

        eZDebug::writeError( "No such attribute: $attributeName for eZPackage", 'eZPackage::isModified' );
        return null;

//         $attributeValue = null;
//         if ( array_key_exists( $attributeName, $this->ModifiedParameters ) )
//             $attributeValue = $this->ModifiedParameters[$attributeName];
//         if ( is_array( $attributeList ) )
//         {
//             foreach ( $attributeList as $attributeKey )
//             {
//                 if ( !is_array( $attributeValue ) or
//                      !array_key_exists( $attributeKey, $attributeValue ) )
//                     break;
//                 $attributeValue = $attributeValue[$attributeKey];
//             }
//         }
//         return $attributeValue;
    }

    function appendMaintainer( $name, $email, $role = false,
                               $modified = null )
    {
        if ( $modified === null )
            $modified = mktime();
        $this->Parameters['maintainers'][] = array( 'name' => $name,
                                                    'email' => $email,
                                                    'role' => $role,
                                                    'modified' => $modified );
    }

    function appendDocument( $name, $mimeType = false, $os = false, $audience = false,
                             $create = false, $data = false,
                             $modified = null )
    {
        if ( !$mimeType )
            $mimeType = 'text/plain';
        if ( $modified === null )
            $modified = mktime();
        $this->Parameters['documents'][] = array( 'name' => $name,
                                                  'mime-type' => $mimeType,
                                                  'os' => $os,
                                                  'create-document' => $create,
                                                  'data' => $data,
                                                  'audience' => $audience,
                                                  'modified' => $modified );
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
        if ( !$release )
            $release = $this->Parameters['release-number'];
        if ( !$release )
            $release = 1;
        $this->Parameters['changelog'][] = array( 'timestamp' => $timestamp,
                                                  'person' => $person,
                                                  'email' => $email,
                                                  'changes' => $changes,
                                                  'release' => $release,
                                                  'modified' => $modified );
    }

    /*!
     Appends a new bugfix dependency to the dependency section \a $dependencySection.
     The bug is identified by the parameter \a $bugID which is an ID taken from
     the ez.no bug list.
     \param $summary An optional parameter which has a summary of the bug
     \param $modified Modification timestamp, see appendDependency
    */
    function appendBugfix( $dependencySection, $bugID,
                           $summary = false, $modified = null )
    {
        $parameters = array( 'summary' => $summary );
        $name = 'bugid';
        $this->appendDependency( $dependencySection, 'bugfix',
                                 $name, $bugID,
                                 $parameters, $modified );
    }

    function md5sum( $file )
    {
        if ( function_exists( 'md5_file' ) )
            return md5_file( $file );
        else
        {
            $fd = @fopen( $file, 'r' );
            if ( $fd )
            {
                $data = '';
                while ( !@feof( $fd ) )
                {
                    $data .= @fread( $fd, 4096 );
                }
                @fclose( $fd );
                return md5( $data );
            }
        }
        return false;
    }

    function appendFile( $file, $type, $role,
                         $design, $filePath, $collection,
                         $subDirectory = null, $md5 = null,
                         $copyFile = false, $modified = null )
    {
        if ( $modified === null )
            $modified = mktime();
        if ( !$collection )
            $collection = 'default';
        if ( $subDirectory === null )
        {
            $subDirectory = false;
            if ( preg_match( '#^(.+)/([^/]+)$#', $file, $matches ) )
            {
                $subDirectory = $matches[1];
                $file = $matches[2];
            }
        }
        $fileItem = array( 'name' => $file,
                           'subdirectory' => $subDirectory,
                           'type' => $type,
                           'role' => $role,
                           'path' => $filePath,
                           'design' => $design,
                           'copy-file' => $copyFile,
                           'modified' => $modified );
        if ( $md5 === null )
        {
            $md5 = $this->md5sum( $filePath );
        }
        $fileItem['md5'] = $md5;
        $this->Parameters['file-list'][$collection][] = $fileItem;
    }

    /*!
     Appends a new \c provides dependency.
     \note This function is only a convenience function to the general appendDependency() function.
    */
    function appendProvides( $type, $name, $value,
                             $parameters = false, $modified = null )
    {
        $this->appendDependency( 'provides', $type, $name, $value,
                                 $parameters, $modified );
    }

    /*!
     Appends a new dependency item to the section \a $dependencySection.
     The dependency is identified by the type \a $type, name \$name
     and value \$a value.
     \param $dependencySection Can be one of \c provides, \c requires, \c obsoletes, \c conflicts
     \param $parameters A list of data specific to the dependency type.
     \param $modified Timestamp which says when the item was modified,
                      if set to \c null the modification is NOW.
                      if \c false it is not used.
    */
    function appendDependency( $dependencySection, $type, $name, $value,
                               $parameters = false, $modified = null )
    {
        if ( !in_array( $dependencySection,
                        array( 'provides', 'requires',
                               'obsoletes', 'conflicts' ) ) )
            return false;
        if ( $modified === null )
            $modified = mktime();
        $parameters['type'] = $type;
        $parameters['name'] = $name;
        $parameters['value'] = $value;
        $parameters['modified'] = $modified;
        $this->Parameters['dependencies'][$dependencySection][] = $parameters;
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
            $content = false;
            if ( isset( $installEntry['content'] ) )
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
            $this->Parameters['version-number'] = $version;
            $this->ModifiedParameters['version-number'] = $modification['number'];
        }
        if ( $release )
        {
            $this->Parameters['release-number'] = $release;
            $this->ModifiedParameters['release-number'] = $modification['release'];
        }
        if ( $timestamp )
        {
            $this->Parameters['release-timestamp'] = $timestamp;
            $this->ModifiedParameters['release-timestamp'] = $modification['timestamp'];
        }
        if ( $licence )
        {
            $this->Parameters['licence'] = $licence;
            $this->ModifiedParameters['licence'] = $modification['licence'];
        }
        if ( $state )
        {
            $this->Parameters['state'] = $state;
            $this->ModifiedParameters['state'] = $modification['state'];
        }
    }

    /*!
     \private
     \return the package as a string, the string is in xml format.
    */
    function toString( $exportFormat = false, $path = false )
    {
        $dom =& $this->domStructure( $exportFormat, $path );
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
        return $this->storePackageFiles( $path );
    }

    /*!
     Stores the current package in the repository.
    */
    function storePackageFiles( $path, $storeCache = true, $exportFormat = false )
    {
        if ( !file_exists( $path ) )
        {
            eZDir::mkdir( $path, eZDir::directoryPermission(), true );
        }
        $filePath = $path . '/' . eZPackage::definitionFilename();
        $result = $this->storeString( $filePath, $this->toString( $exportFormat, $path ) );
        $this->cleanup();
        if ( $storeCache )
            $this->storeCache( $path . '/' . $this->cacheDirectory() );
        return $result;
    }

    function removePackageFiles( $path )
    {
        if ( file_exists( $path ) )
        {
            eZDir::recursiveDelete( $path );
        }
    }

    function archive( $archiveName, $destinationPath = false )
    {
        $tempPath = eZPackage::temporaryExportPath() . '/' . $this->attribute( 'name' );
        $this->removePackageFiles( $tempPath );
        $this->storePackageFiles( $tempPath, false, true );

        include_once( 'lib/ezfile/classes/ezarchivehandler.php' );

        $archivePath = $archiveName;
        if ( $destinationPath )
            $archivePath = $destinationPath . '/' . $archiveName;
        $archive = eZArchiveHandler::instance( 'tar', 'gzip', $archivePath );
        $packageBaseDirectory = $tempPath;
        $fileList = array();
        $fileList[] = $packageBaseDirectory;
        $archive->createModify( $fileList, '', $packageBaseDirectory );

//         $this->removePackageFiles( $tempPath );
        return $archivePath;
    }

    function import( $archiveName, $packageName )
    {
        $tempPath = eZPackage::temporaryImportPath() . '/' . $packageName;
        eZPackage::removePackageFiles( $tempPath );
        if ( !file_exists( $tempPath ) )
        {
            eZDir::mkdir( $tempPath, eZDir::directoryPermission(), true );
        }

        include_once( 'lib/ezfile/classes/ezarchivehandler.php' );

        $archive = eZArchiveHandler::instance( 'tar', 'gzip', $archiveName );
        $fileList = array();
        $fileList[] = eZPackage::definitionFilename();
        $archive->extractList( $fileList, $tempPath, '' );

        $package =& eZPackage::fetch( $packageName, $tempPath );
        eZPackage::removePackageFiles( $tempPath );
        if ( $package )
        {
            $packageName = $package->attribute( 'name' );
            unset( $archive );
            unset( $package );

            $archive = eZArchiveHandler::instance( 'tar', 'gzip', $archiveName );
            $tempPath = eZPackage::temporaryImportPath() . '/' . $packageName;
            $archive->extractModify( $tempPath, '' );

            $package =& eZPackage::fetch( $packageName, $tempPath );

//             eZPackage::removePackageFiles( $tempPath );
        }

        return $package;
    }

    /*!
     \static
     \return the suffix for all package files.
    */
    function suffix()
    {
        return 'tgz';
//         return 'ezpkg';
//         return 'ezp';
//         return 'ezpm';
    }

    function exportName()
    {
        return $this->attribute( 'name' ) . '-' . $this->attribute( 'version-number' ) . '-' . $this->attribute( 'release-number' ) . '.' . eZPackage::suffix();
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
    function &fetch( $packageName, $packagePath = false )
    {
        $path = eZPackage::repositoryPath() . '/' . $packageName;
        if ( $packagePath )
            $path = $packagePath;
        $filePath = $path . '/' . eZPackage::definitionFilename();
        if ( file_exists( $filePath ) )
        {
            $fileModification = filemtime( $filePath );
            $package = false;
            $cacheExpired = false;
            if ( eZPackage::useCache() )
                $package =& eZPackage::fetchFromCache( $packageName, $fileModification, $cacheExpired );
            if ( $package )
                return $package;
            $package =& eZPackage::fetchFromFile( $filePath );
            if ( $cacheExpired and
                 eZPackage::useCache() )
            {
                $package->storeCache( $path . '/' . eZPackage::cacheDirectory() );
            }
            return $package;
        }
        return false;
    }

    function useCache()
    {
        return EZ_PACKAGE_USE_CACHE;
    }

    /*!
     \private
    */
    function &fetchFromCache( $packageName, $packageModification, &$cacheExpired )
    {
        $path = eZPackage::repositoryPath() . '/' . $packageName;
        $packageCachePath = $path . '/' . eZPackage::cacheDirectory() . '/package.php';
        $cacheExpired = false;
        if ( file_exists( $packageCachePath ) )
        {
            $cacheModification = filemtime( $packageCachePath );
            if ( $cacheModification >= $packageModification )
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
     \return the directory name for temporary export packages, used in conjunction with eZSys::cacheDirectory().
    */
    function temporaryExportPath()
    {
        $path = eZDir::path( array( eZSys::cacheDirectory(),
                                    'packages',
                                    'export' ) );
        return $path;
    }

    /*!
     \static
     \return the directory name for temporary import packages, used in conjunction with eZSys::cacheDirectory().
    */
    function temporaryImportPath()
    {
        $path = eZDir::path( array( eZSys::cacheDirectory(),
                                    'packages',
                                    'import' ) );
        return $path;
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
     \return the name of the package definition file.
    */
    function definitionFilename()
    {
        return 'package.xml';
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
     \static
     \return the name of the documents directory for cached package data.
    */
    function filesDirectory()
    {
        return 'files';
    }

    /*!
     Locates all packages in the repository and returns an array with eZPackage objects.
    */
    function fetchPackages( $parameters = array() )
    {
        $path = eZPackage::repositoryPath();
        $packages = array();
        if ( file_exists( $path ) )
        {
            $dir = opendir( $path );
            while( ( $file = readdir( $dir ) ) !== false )
            {
                if ( $file == '.' or
                     $file == '..' )
                    continue;
                $dirPath = $path . '/' . $file;
                if ( !is_dir( $dirPath ) )
                    continue;
                $filePath = $dirPath . '/' . eZPackage::definitionFilename();
                if ( file_exists( $filePath ) )
                {
                    $fileModification = filemtime( $filePath );
                    $name = $file;
                    $packageCachePath = $dirPath . '/' . eZPackage::cacheDirectory() . '/package.php';
                    unset( $package );
                    $package = false;
                    $cacheExpired = false;
                    if ( eZPackage::useCache() )
                        $package =& eZPackage::fetchFromCache( $file, $fileModification, $cacheExpired );
                    if ( !$package )
                    {
                        $package =& eZPackage::fetchFromFile( $filePath );
                        if ( $cacheExpired and
                             eZPackage::useCache() )
                        {
                            $package->storeCache( $dirPath . '/' . eZPackage::cacheDirectory() );
                        }
                    }
                    if ( !$package )
                        continue;
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

        // Read files
        $filesList =& $root->elementChildrenByName( 'files' );
        if ( $filesList )
        {
            foreach ( array_keys( $filesList ) as $fileCollectionKey )
            {
                $fileCollectionNode =& $filesList[$fileCollectionKey];
                $fileCollectionName = $fileCollectionNode->attributeValue( 'name' );
                $fileLists =& $fileCollectionNode->elementsByName( 'file-list' );
                foreach ( array_keys( $fileLists ) as $fileListKey )
                {
                    $fileListNode =& $fileLists[$fileListKey];
                    $fileType = $fileListNode->attributeValue( 'type' );
                    $fileDesign = $fileListNode->attributeValue( 'design' );
                    $fileRole = $fileListNode->attributeValue( 'role' );
                    $files =& $fileListNode->elementsByName( 'file' );
                    foreach ( array_keys( $files ) as $fileKey )
                    {
                        $fileNode =& $files[$fileKey];
                        $fileName = $fileNode->attributeValue( 'name' );
                        $fileSubDirectory = $fileNode->attributeValue( 'sub-directory' );
                        $filePath = $fileNode->attributeValue( 'path' );
                        $fileMD5 = $fileNode->attributeValue( 'md5sum' );
                        $fileModified = $fileNode->attributeValue( 'modified' );
                        $this->appendFile( $fileName, $fileType, $fileRole,
                                           $fileDesign, $filePath, $fileCollectionName,
                                           $fileSubDirectory, $fileMD5, false, $fileModified );
                    }
                }
            }
        }

        // Read release info
        $versionNode =& $root->elementByName( 'version' );
        $versionNumber = false;
        $versionRelease = false;
        if ( $versionNode )
        {
            $versionNumber = $versionNode->elementTextContentByName( 'number' );
            $versionRelease = $versionNode->elementTextContentByName( 'release' );
        }
        $licence = $root->elementTextContentByName( 'licence' );
        $state = $root->elementTextContentByName( 'state' );
        $releaseModifications = array();
        if ( $versionNode )
        {
            $releaseNumberModification = $versionNode->elementAttributeValueByName( 'number', 'modified' );
            if ( $releaseNumberModification )
                $releaseModifications['number'] = $releaseNumberModification;
            $releaseReleaseModification = $versionNode->elementAttributeValueByName( 'release', 'modified' );
            if ( $releaseReleaseModification )
                $releaseModifications['release'] = $releaseReleaseModification;
        }
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
        if ( $dependenciesNode )
        {
            $providesList =& $dependenciesNode->elementChildrenByName( 'provides' );
            $requiresList =& $dependenciesNode->elementChildrenByName( 'requires' );
            $obsoletesList =& $dependenciesNode->elementChildrenByName( 'obsoletes' );
            $conflictsList =& $dependenciesNode->elementChildrenByName( 'conflicts' );
            $this->parseDependencyTree( $providesList, 'provides' );
            $this->parseDependencyTree( $requiresList, 'requires' );
            $this->parseDependencyTree( $obsoletesList, 'obsoletes' );
            $this->parseDependencyTree( $conflictsList, 'conflicts' );
        }

        $installList =& $root->elementChildrenByName( 'install' );
        $uninstallList =& $root->elementChildrenByName( 'uninstall' );
        $this->parseInstallTree( $installList, true );
        $this->parseInstallTree( $uninstallList, false );
    }

    /*!
     \private
    */
    function parseDependencyTree( &$dependenciesList, $dependencySection )
    {
        foreach ( array_keys( $dependenciesList ) as $dependencyKey )
        {
            $dependencyNode =& $dependenciesList[$dependencyKey];
            $dependencyType = $dependencyNode->attributeValue( 'type' );
            $dependencyName = $dependencyNode->attributeValue( 'name' );
            $dependencyValue = $dependencyNode->attributeValue( 'value' );
            $dependencyModified = $dependencyNode->attributeValue( 'modified' );

            $dependencyParameters = array();
            $handler =& $this->packageHandler( $dependencyType );
            if ( $handler )
            {
                $handler->parseDependencyNode( $dependencyNode, $dependencyParameters, $dependencySection );
            }
            if ( count( $dependencyParameters ) == 0 )
                $dependencyParameters = false;
            $this->appendDependency( $dependencySection, $dependencyType, $dependencyName, $dependencyValue,
                                     $dependencyParameters, $dependencyModified );
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
            $installType = $installNode->attributeValue( 'type' );
            $installName = $installNode->attributeValue( 'name' );
            $installModified = $installNode->attributeValue( 'modified' );
            $installFilename = $installNode->attributeValue( 'filename' );
            $installSubdirectory = $installNode->attributeValue( 'sub-directory' );
            $installOS = $installNode->attributeValue( 'os' );

            $handler =& $this->packageHandler( $installType );
            $installParameters = array();
            if ( $handler )
            {
                $handler->parseInstallNode( $installNode, $installParameters, $isInstall );
            }
            if ( count( $installParameters ) == 0 )
                $installParameters = false;

            $this->appendInstall( $installType, $installName, $installOS, $isInstall,
                                  $installFilename, $installSubdirectory,
                                  $installParameters, $installModified );
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
    function &domStructure( $exportFormat = false, $exportPath = false )
    {
        $dom = new eZDOMDocument();
        $root = $dom->createElementNode( 'package', array( 'version' => EZ_PACKAGE_VERSION,
                                                           'development' => ( EZ_PACKAGE_DEVELOPMENT ? 'true' : 'false' ) ) );
        $root->appendAttribute( $dom->createAttributeNode( 'ezpackage', 'http://ez.no/ezpackage', 'xmlns' ) );
        $dom->setRoot( $root );

        $name = $this->attribute( 'name' );
        $nameAttributes = array();
        if ( !$exportFormat and $this->isModified( 'name' ) )
            $nameAttributes['modified'] = $this->isModified( 'name' );
        $summary = $this->attribute( 'summary' );
        $summaryAttributes = array();
        if ( !$exportFormat and $this->isModified( 'summary' ) )
            $summaryAttributes['modified'] = $this->isModified( 'summary' );
        $description = $this->attribute( 'description' );
        $descriptionAttributes = array();
        if ( !$exportFormat and $this->isModified( 'description' ) )
            $descriptionAttributes['modified'] = $this->isModified( 'description' );
        $vendor = $this->attribute( 'vendor' );
        $vendorAttributes = array();
        if ( !$exportFormat and $this->isModified( 'vendor' ) )
            $vendorAttributes['modified'] = $this->isModified( 'vendor' );
        $priority = $this->attribute( 'priority' );
        $priorityAttributes = array( 'value' => $priority );
        if ( !$exportFormat and $this->isModified( 'priority' ) )
            $priorityAttributes['modified'] = $this->isModified( 'priority' );
        $type = $this->attribute( 'type' );
        $typeAttributes = array( 'value' => $type );
        if ( !$exportFormat and $this->isModified( 'type' ) )
            $typeAttributes['modified'] = $this->isModified( 'type' );
        $extension = $this->attribute( 'extension' );
        $extensionAttributes = array( 'name' => $extension );
        if ( !$exportFormat and $this->isModified( 'extension' ) )
            $extensionAttributes['modified'] = $this->isModified( 'extension' );
        $source = $this->attribute( 'source' );
        $sourceAttributes = array();
        if ( !$exportFormat and $this->isModified( 'source' ) )
            $sourceAttributes['modified'] = $this->isModified( 'source' );

//         $ezpublish = $this->attribute( 'ezpublish' );
        $ezpublishVersion = $this->attribute( 'ezpublish-version' );
        $ezpublishNamedVersion = $this->attribute( 'ezpublish-named-version' );

        $packagingTimestamp = $this->attribute( 'packaging-timestamp' );
        $packagingHost = $this->attribute( 'packaging-host' );
        $packagingPackager = $this->attribute( 'packaging-packager' );

        $maintainers = $this->attribute( 'maintainers' );
//         $packaging = $this->attribute( 'packaging' );
        $documents = $this->attribute( 'documents' );
        $groups = $this->attribute( 'groups' );

//         $release = $this->attribute( 'release' );
        $versionNumber = $this->attribute( 'version-number' );
        $releaseNumber = $this->attribute( 'release-number' );
        $releaseTimestamp = $this->attribute( 'release-timestamp' );

        $licence = $this->attribute( 'licence' );
        $state = $this->attribute( 'state' );

        $fileList = $this->attribute( 'file-list' );
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
        $ezpublishNode->appendChild( $dom->createElementTextNode( 'version', $ezpublishVersion ) );
        $ezpublishNode->appendChild( $dom->createElementTextNode( 'named-version', $ezpublishNamedVersion ) );
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
                if ( !$exportFormat and $maintainer['modified'] )
                    $maintainerNode->appendAttribute( $dom->createAttributeNode( 'modified', $maintainer['modified'] ) );
                ++$index;
            }
            $root->appendChild( $maintainersNode );
        }

        $packagingNode =& $dom->createElementNode( 'packaging' );
        $packagingNode->appendAttribute( $dom->createAttributeNode( 'ezpackaging', 'http://ez.no/ezpackage', 'xmlns' ) );
        $packagingNode->appendChild( $dom->createElementTextNode( 'timestamp', $packagingTimestamp ) );
        $packagingNode->appendChild( $dom->createElementTextNode( 'host', $packagingHost ) );
        if ( $packagingPackager )
            $packagingNode->appendChild( $dom->createElementTextNode( 'packager', $packagingPackager ) );
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
                if ( !$exportFormat and $document['modified'] )
                    $documentNode->appendAttribute( $dom->createAttributeNode( 'modified', $document['modified'] ) );
                if ( $document['os'] )
                    $documentNode->appendAttribute( $dom->createAttributeNode( 'os', $document['os'] ) );
                if ( $document['audience'] )
                    $documentNode->appendAttribute( $dom->createAttributeNode( 'audience', $document['audience'] ) );
                $documentsNode->appendChild( $documentNode );
                if ( $exportFormat )
                {
                    $documentFilePath = $this->path() . '/' . eZPackage::documentDirectory() . '/' . $document['name'];
                    if ( file_exists( $documentFilePath ) )
                    {
                        if ( !file_exists( $exportPath . '/' . eZPackage::documentDirectory() ) )
                            eZDir::mkdir( $exportPath . '/' . eZPackage::documentDirectory(), eZDir::directoryPermission(), true );
                        eZFileHandler::copy( $documentFilePath, $exportPath . '/' . eZPackage::documentDirectory() . '/' . $document['name'] );
                    }
                    else
                        eZDebug::writeError( "Could not export document " . $document['name'] . ", file not found",
                                             'eZPackage::domStructure' );
                }
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
                if ( !$exportFormat and $groupModified )
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
                if ( !$exportFormat and $changeEntry['modified'] )
                    $changeEntryNode->appendAttribute( $dom->createAttributeNode( 'modified', $changeEntry['modified'] ) );
                $changelogNode->appendChild( $changeEntryNode );
                ++$index;
            }
            $root->appendChild( $changelogNode );
        }

        $filesNode =& $dom->createElementNode( 'files' );
        $hasFileItems = false;
        foreach ( $fileList as $fileCollectionName => $fileCollection )
        {
            if ( count( $fileCollection ) > 0 )
            {
                $hasFileItems = true;
                $collectionAttributes = array();
                if ( $fileCollectionName )
                    $collectionAttributes['name'] = $fileCollectionName;
                $fileCollectionNode =& $dom->createElementNode( 'collection',
                                                                $collectionAttributes );
                unset( $fileLists );
                unset( $fileDesignLists );
                $fileList = array();
                $fileDesignList = array();
                foreach ( $fileCollection as $fileItem )
                {
                    if ( $fileItem['type'] == 'design' )
                        $fileListNode =& $fileDesignLists[$fileItem['design']][$fileItem['role']];
                    else
                        $fileListNode =& $fileLists[$fileItem['type']][$fileItem['role']];
                    if ( !isset( $fileListNode ) )
                    {
                        $fileListAttributes = array( 'type' => $fileItem['type'] );
                        if ( $fileItem['type'] == 'design' )
                            $fileListAttributes['design'] = $fileItem['design'];
                        if ( $fileItem['role'] )
                            $fileListAttributes['role'] = $fileItem['role'];
                        $fileListNode = $dom->createElementNode( 'file-list',
                                                                 $fileListAttributes );
                        $fileCollectionNode->appendChild( $fileListNode );
                    }
                    $fileAttributes = array( 'name' => $fileItem['name'] );
                    if ( $fileItem['subdirectory'] )
                        $fileAttributes['sub-directory'] = $fileItem['subdirectory'];
                    if ( !$exportFormat )
                        $fileAttributes['path'] = $fileItem['path'];
                    if ( $fileItem['md5'] )
                        $fileAttributes['md5sum'] = $fileItem['md5'];
                    if ( $fileItem['modified'] and !$exportFormat )
                         $fileAttributes['modified'] = $fileItem['modified'];
                    if ( $fileItem['name'] )
                    {
                        $fileListNode->appendChild( $dom->createElementNode( 'file', $fileAttributes ) );
                    }
                    $copyFile = $fileItem['copy-file'];
                    if ( $copyFile )
                    {
                        $typeDir = $fileItem['type'];
                        if ( $fileItem['type'] == 'design' )
                            $typeDir .= '.' . $fileItem['design'];
                        if ( $fileItem['role'] )
                            $typeDir .= '.' . $fileItem['role'];
                        $path = $this->path() . '/' . eZPackage::filesDirectory() . '/' . $fileCollectionName . '/' . $typeDir;
                        if ( $fileItem['subdirectory'] )
                            $path .= '/' . $fileItem['subdirectory'];
                        if ( !file_exists( $path ) )
                            eZDir::mkdir( $path, eZDir::directoryPermission(), true );
                        if ( is_dir( $fileItem['path'] ) )
                            eZDir::copy( $fileItem['path'], $path,
                                         $fileItem['name'] != false, true, false, eZDir::temporaryFileRegexp() );
                        else
                            eZFileHandler::copy( $fileItem['path'], $path . '/' . $fileItem['name'] );
                    }
                }
                $filesNode->appendChild( $fileCollectionNode );
            }
        }
        if ( $hasFileItems )
            $root->appendChild( $filesNode );

        $versionNode =& $dom->createElementNode( 'version' );
        $versionNode->appendAttribute( $dom->createAttributeNode( 'ezversion', 'http://ez.no/ezpackage', 'xmlns' ) );
        $numberAttributes = array();
        if ( !$exportFormat and $this->isModified( 'version-number' ) )
            $numberAttributes['modified'] = $this->isModified( 'version-number' );
        $versionNode->appendChild( $dom->createElementTextNode( 'number', $versionNumber,
                                                                $numberAttributes ) );
        $releaseAttributes = array();
        if ( !$exportFormat and $this->isModified( 'release-number' ) )
            $releaseAttributes['modified'] = $this->isModified( 'release-number' );
        $versionNode->appendChild( $dom->createElementTextNode( 'release', $releaseNumber,
                                                                $releaseAttributes ) );
        $root->appendChild( $versionNode );
        if ( $releaseTimestamp )
        {
            $timestampAttributes = array();
            if ( !$exportFormat and $this->isModified( 'release-timestamp' ) )
                $timestampAttributes['modified'] = $this->isModified( 'release-timestamp' );
            $root->appendChild( $dom->createElementTextNode( 'timestamp', $releaseTimestamp,
                                                             $timestampAttributes ) );
        }
        $licenceAttributes = array();
        if ( !$exportFormat and $this->isModified( 'licence' ) )
            $licenceAttributes['modified'] = $this->isModified( 'licence' );
        if ( $licence )
            $root->appendChild( $dom->createElementTextNode( 'licence', $licence,
                                                             $licenceAttributes ) );
        $stateAttributes = array();
        if ( !$exportFormat and $this->isModified( 'state' ) )
            $stateAttributes['modified'] = $this->isModified( 'state' );
        if ( $state )
            $root->appendChild( $dom->createElementTextNode( 'state', $state,
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

        $this->createDependencyTree( $exportFormat, $providesNode, 'provide', $dependencies['provides'] );
        $this->createDependencyTree( $exportFormat, $requiresNode, 'require', $dependencies['requires'] );
        $this->createDependencyTree( $exportFormat, $obsoletesNode, 'obsolete', $dependencies['obsoletes'] );
        $this->createDependencyTree( $exportFormat, $conflictsNode, 'conflict', $dependencies['conflicts'] );

        $root->appendChild( $dependencyNode );

        $installNode =& $dom->createElementNode( 'install' );
        $installNode->appendAttribute( $dom->createAttributeNode( 'ezinstall', 'http://ez.no/ezpackage', 'xmlns' ) );
        $uninstallNode =& $dom->createElementNode( 'uninstall' );
        $uninstallNode->appendAttribute( $dom->createAttributeNode( 'ezinstall', 'http://ez.no/ezpackage', 'xmlns' ) );

        $this->createInstallTree( $exportFormat, $installNode, $dom, $install, 'install' );
        $this->createInstallTree( $exportFormat, $uninstallNode, $dom, $uninstall, 'uninstall' );

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
    function createInstallTree( $exportFormat, &$installNode, &$dom, $list, $installType )
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
                if ( !$exportFormat and $installModified )
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
    function createDependencyTree( $exportFormat, &$dependenciesNode, $dependencyType, $list )
    {
        foreach ( $list as $dependencyItem )
        {
            $dependencyNode =& eZDOMDocument::createElementNode( $dependencyType );
            $dependencyNode->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $dependencyItem['name'] ) );
            $dependencyNode->appendAttribute( eZDOMDocument::createAttributeNode( 'value', $dependencyItem['value'] ) );
            $dependencyModified = $dependencyItem['modified'];
            if ( !$exportFormat and $dependencyModified )
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
        $handler = false;
        if ( eZExtension::findExtensionType( array( 'ini-name' => 'package.ini',
                                                    'repository-group' => 'PackageSettings',
                                                    'repository-variable' => 'RepositoryDirectories',
                                                    'extension-group' => 'PackageSettings',
                                                    'extension-variable' => 'ExtensionDirectories',
                                                    'subdir' => 'packagehandlers',
                                                    'extension-subdir' => 'packagehandlers',
                                                    'suffix-name' => 'packagehandler.php',
                                                    'type-directory' => true,
                                                    'type' => $handlerName,
                                                    'alias-group' => 'PackageSettings',
                                                    'alias-variable' => 'HandlerAlias' ),
                                             $result ) )
        {
//         $ini =& eZINI::instance( 'package.ini' );
//         $repository = $ini->variable( 'PackageSettings', 'PackageHandlerRepository' );
//         $handlerFile = $repository . '/' . $handlerName . '/' . $handlerName . 'packagehandler.php';
//         $handler = false;
            $handlerFile = $result['found-file-path'];
            if ( file_exists( $handlerFile ) )
            {
                include_once( $handlerFile );
                $handlerClassName = $result['type'] . 'PackageHandler';
                if ( isset( $handlers[$result['type']] ) )
                {
                    $handler =& $handlers[$result['type']];
                    $handler->reset();
                }
                else
                {
                    $handler =& new $handlerClassName;
                    $handlers[$result['type']] =& $handler;
                }
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
