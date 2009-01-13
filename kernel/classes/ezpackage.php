<?php
//
// Definition of eZPackage class
//
// Created on: <23-Jul-2003 12:34:55 amos>
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

/*! \file
*/

/*!
  \defgroup package The package manager system
  \ingroup package
  \class eZPackage ezpackagehandler.php
  \brief Maintains eZ Publish packages

*/

class eZPackage
{
    const VERSION = '3.5.2';
    const DEVELOPMENT = false;
    const USE_CACHE = true;
    const CACHE_CODE_DATE = 1069339607;

    const STATUS_ALREADY_EXISTS = 1;

    const NON_INTERACTIVE = -1;

    /*!
     Constructor
    */
    function eZPackage( $parameters = array(), $repositoryPath = false )
    {
        $this->setParameters( $parameters );
        if ( !$repositoryPath )
            $repositoryPath = eZPackage::repositoryPath();
        $this->RepositoryPath = $repositoryPath;
        $this->RepositoryInformation = null;
    }

    /*!
     Removes the package directory and all it's subfiles/directories.
    */
    function remove()
    {
        $path = $this->path();
        if ( file_exists( $path ) )
        {
            eZDir::recursiveDelete( $path );
        }
        $this->setInstalled( false );
    }

    /*!
     \private
    */
    function setParameters( $parameters = array() )
    {
        $timestamp = time();
        if ( isset( $_SERVER['HOSTNAME'] ) )
            $host = $_SERVER['HOSTNAME'];
        else if ( isset( $_SERVER['HTTP_HOST'] ) )
            $host = $_SERVER['HTTP_HOST'];
        else
            $host = 'localhost';
        $packaging = array( 'timestamp' => $timestamp,
                            'host' => $host,
                            'packager' => false );
        $ezpublishVersion = eZPublishSDK::version( true );
        $ezpublishNamedVersion = eZPublishSDK::version( false, false, true );
        $ezpublish = array( 'version' => $ezpublishVersion,
                            'named-version' => $ezpublishNamedVersion );
        $defaults = array( 'name' => false,
                           'development' => eZPackage::DEVELOPMENT,
                           'summary' => false,
                           'description' => false,
                           'vendor' => false,
                           'vendor-dir' => false,
                           'priority' => false,
                           'type' => false,
                           'extension' => false,
                           'install_type' => 'install',
                           'ezpublish' => $ezpublish,
                           'maintainers' => array(),
                           'packaging' => $packaging,
                           'source' => false,
                           'documents' => array(),
                           'groups' => array(),
                           'changelog' => array(),
                           'file-list' => array(),
                           'simple-file-list' => array(),
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
        $this->PolicyCache = array();
        $this->InstallData = array();
        $this->Parameters = array_merge( $defaults, $parameters );
    }

    /*!
     \static
     \return An associative array with the possible types for a package.
             Each entry contains an \c id and a \c name key.
    */
    static function typeList()
    {
        $typeList =& $GLOBALS['eZPackageTypeList'];
        if ( !isset( $typeList ) )
        {
            $typeList = array();
            $ini = eZINI::instance( 'package.ini' );
            $types = $ini->variable( 'PackageSettings', 'TypeList' );
            foreach ( $types as $typeID => $typeName )
            {
                $typeList[] = array( 'name' => $typeName,
                                     'id' => $typeID );
            }
        }
        return $typeList;
    }

    /*!
     \static
     \return An associative array with the possible states for a package.
             Each entry contains an \c id and a \c name key.
    */
    static function stateList()
    {
        $stateList =& $GLOBALS['eZPackageStateList'];
        if ( !isset( $stateList ) )
        {
            $stateList = array();
            $ini = eZINI::instance( 'package.ini' );
            $states = $ini->variable( 'PackageSettings', 'StateList' );
            foreach ( $states as $stateID => $stateName )
            {
                $stateList[] = array( 'name' => $stateName,
                                      'id' => $stateID);
            }
        }
        return $stateList;
    }

    /*!
     \param $repositoryID The id (string) of the repository to create the package in.
                          If \c false it will use the \c local repository.
    */
    static function create( $name, $parameters = array(), $repositoryPath = false, $repositoryID = false )
    {
        $parameters['name'] = $name;
        $handler = new eZPackage( $parameters, $repositoryPath );

        // New packages always use local repository
        if ( $repositoryID === false )
            $repositoryID = 'local';
        $repositoryInformation = $handler->repositoryInformation( $repositoryID );
        if ( $repositoryPath !== false )
            $repositoryInformation['path'] = $repositoryPath;
        $handler->setCurrentRepositoryInformation( $repositoryInformation );
        return $handler;
    }

    /*!
     \return the attributes for this package.
    */
    function attributes()
    {
        return array( 'is_local',
                      'development',
                      'name', 'summary', 'description',
                      'vendor', 'vendor-dir', 'priority', 'type',
                      'extension', 'source',
                      'version-number', 'release-number', 'release-timestamp',
                      'maintainers', 'documents', 'groups',
                      'simple-file-list', 'file-list', 'file-count',
                      'can_read', 'can_export', 'can_import', 'can_install',
                      'changelog', 'dependencies',
                      'is_installed',
                      'install_type',
                      'thumbnail-list',
                      'install', 'uninstall',
                      'licence', 'state',
                      'ezpublish-version', 'ezpublish-named-version', 'packaging-timestamp',
                      'packaging-host', 'packaging-packager' );
    }

    /*!
     Sets the attribute named \a $attributeName to have the value \a $attributeValue.
    */
    function setAttribute( $attributeName, $attributeValue )
    {
        if ( !in_array( $attributeName,
                        array( 'development',
                               'name', 'summary', 'description',
                               'vendor', 'vendor-dir', 'priority', 'type',
                               'install_type',
                               'extension', 'source',
                               'licence', 'state' ) ) )
            return false;
        if ( array_key_exists( $attributeName, $this->Parameters ) and
             !is_array( $this->Parameters[$attributeName] ))
        {
            $this->Parameters[$attributeName] = $attributeValue;
            return true;
        }
        return false;
    }

    /*!
     \return \c true if the attribute named \a $attributeName exists.
    */
    function hasAttribute( $attributeName /*, $attributeList = false*/ )
    {
        return in_array( $attributeName, $this->attributes() );
    }

    /*!
     \return the value of the attribute named \a $attributeName.
    */
    function attribute( $attributeName /*, $attributeList = false*/ )
    {
        if ( in_array( $attributeName,
                       array( 'development',
                              'name', 'summary', 'description',
                              'vendor', 'vendor-dir', 'priority', 'type',
                              'extension', 'source',
                              'version-number', 'release-number', 'release-timestamp',
                              'maintainers', 'documents', 'groups',
                              'simple-file-list', 'file-list',
                              'changelog', 'dependencies',
                              'install', 'uninstall',
                              'install_type',
                              'licence', 'state', 'settings-files' ) ) )
            return $this->Parameters[$attributeName];
        else if ( $attributeName == 'is_installed' )
            return $this->isInstalled;
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
        else if ( $attributeName == 'can_read' )
        {
            return $this->canRead();
        }
        else if ( $attributeName == 'can_export' )
        {
            return $this->canExport();
        }
        else if ( $attributeName == 'can_import' )
        {
            return $this->canImport();
        }
        else if ( $attributeName == 'can_install' )
        {
            return $this->canInstall();
        }
        else if ( $attributeName == 'file-count' )
        {
            return $this->fileCount();
        }
        else if ( $attributeName == 'thumbnail-list' )
        {
            return $this->thumbnailList( 'default' );
        }
        else if ( $attributeName == 'is_local' )
        {
            $repositoryInformation = $this->currentRepositoryInformation();
            return ( $repositoryInformation['type'] == 'local' );
        }

        eZDebug::writeError( "No such attribute: $attributeName for eZPackage", 'eZPackage::attribute' );
        return null;
    }

    static function canUsePolicyFunction( $functionName )
    {
        $currentUser = eZUser::currentUser();
        $accessResult = $currentUser->hasAccessTo( 'package', $functionName );
        if ( in_array( $accessResult['accessWord'], array( 'yes', 'limited' ) ) )
        {
            return true;
        }
        return false;
    }

    function canRead()
    {
        return $this->canUsePackagePolicyFunction( 'read' );
    }

    function canExport()
    {
        return $this->canUsePackagePolicyFunction( 'export' );
    }

    function canImport()
    {
        return $this->canUsePackagePolicyFunction( 'import' );
    }

    function canInstall()
    {
        return $this->canUsePackagePolicyFunction( 'install' );
    }

    function canUsePackagePolicyFunction( $functionName )
    {
        if ( !isset( $this->PolicyCache[$functionName] ) )
        {
            $currentUser = eZUser::currentUser();
            $accessResult = $currentUser->hasAccessTo( 'package', $functionName );
            $limitationList = array();
            $canUse = false;
            if ( $accessResult['accessWord'] == 'yes' )
            {
                $this->PolicyCache[$functionName] = true;
            }
            else if ( $accessResult['accessWord'] == 'limited' )
            {
                $allRoles = array();
                $limitationList = $accessResult['policies'];
                $typeList = false;
                foreach( $limitationList as $limitationArray )
                {
                    foreach ( $limitationArray as $key => $limitation )
                    {
                        if ( $key == 'Type' )
                        {
                            if ( !is_array( $typeList ) )
                                $typeList = array();
                            $typeList = array_merge( $typeList, $limitation );
                        }
                    }
                }
                if ( $typeList === false )
                {
                    $this->PolicyCache[$functionName] = true;
                }
                else
                {
                    $this->PolicyCache[$functionName] = in_array( $this->attribute( 'type' ), $typeList );
                }
            }
        }
        return $this->PolicyCache[$functionName];
    }

    static function fetchMaintainerRoleIDList( $packageType = false, $checkRoles = false )
    {
        $allRoles = false;
        if ( $checkRoles )
        {
            $currentUser = eZUser::currentUser();
            $accessResult = $currentUser->hasAccessTo( 'package', 'create' );
            $limitationList = array();
            if ( $accessResult['accessWord'] == 'limited' )
            {
                $allRoles = array();
                $limitationList = $accessResult['policies'];
                foreach( $limitationList as $limitationArray )
                {
                    $allowedType = true;
                    $allowedRoles = false;
                    foreach ( $limitationArray as $key => $limitation )
                    {
                        if ( $key == 'Role' )
                        {
                            $allowedRoles = $limitation;
                        }
                        else if ( $key == 'Type' )
                        {
                            $typeList = $limitation;
                            if ( $packageType === false )
                            {
                                $allowedType = in_array( $packageType, $typeList );
                            }
                        }
                    }
                    if ( $allowedType and
                         count( $allowedRoles ) > 0 )
                    {
                        $allRoles = array_merge( $allRoles, $allowedRoles );
                    }
                }
            }
        }
        if ( is_array( $allRoles ) and count( $allRoles ) == 0 )
            return array();
        $ini = eZINI::instance( 'package.ini' );
        $roleList = $ini->variable( 'MaintainerSettings', 'RoleList' );
        if ( $allRoles !== false )
        {
            $roleList = array_intersect( $roleList, $allRoles );
        }
        return $roleList;
    }

    static function fetchMaintainerRoleList( $packageType = false, $checkRoles = false )
    {
        $roleList = eZPackage::fetchMaintainerRoleIDList( $packageType, $checkRoles );
        $roleNameList = array();
        foreach ( $roleList as $roleID )
        {
            $roleName = eZPackage::maintainerRoleName( $roleID );
            $roleNameList[] = array( 'name' => $roleName,
                                     'id' => $roleID );
        }
        return $roleNameList;
    }

    static function maintainerRoleListForRoles()
    {
        $ini = eZINI::instance( 'package.ini' );
        $roleList = $ini->variable( 'MaintainerSettings', 'RoleList' );
        $roleNameList = array();
        foreach ( $roleList as $roleID )
        {
            $roleName = eZPackage::maintainerRoleName( $roleID );
            $roleNameList[] = array( 'name' => $roleName,
                                     'id' => $roleID );
        }
        return $roleNameList;
    }

    static function maintainerRoleName( $roleID )
    {
        require_once "kernel/common/i18n.php";
        $nameMap = array( 'lead' => ezi18n( 'kernel/package', 'Lead' ),
                          'developer' => ezi18n( 'kernel/package', 'Developer' ),
                          'designer' => ezi18n( 'kernel/package', 'Designer' ),
                          'contributor' => ezi18n( 'kernel/package', 'Contributor' ),
                          'tester' => ezi18n( 'kernel/package', 'Tester' ) );
        if ( isset( $nameMap[$roleID] ) )
            return $nameMap[$roleID];
        return false;
    }

    function appendMaintainer( $name, $email, $role = false )
    {
        $this->Parameters['maintainers'][] = array( 'name' => $name,
                                                    'email' => $email,
                                                    'role' => $role );
    }

    function appendDocument( $name, $mimeType = false, $os = false, $audience = false,
                             $create = false, $data = false )
    {
        if ( !$mimeType )
            $mimeType = 'text/plain';
        $this->Parameters['documents'][] = array( 'name' => $name,
                                                  'mime-type' => $mimeType,
                                                  'os' => $os,
                                  //                'create-document' => $create,
                                                  'data' => $data,
                                                  'audience' => $audience );
        if ( $create )
        {
            eZFile::create( $name, $this->path() . '/' . eZPackage::documentDirectory(),
                            $data );
        }
    }

    function appendGroup( $name )
    {
        $index = count( $this->Parameters['groups'] );
        $this->Parameters['groups'][$index] = array( 'name' => $name );
    }

    function appendChange( $person, $email, $changes,
                           $release = false, $timestamp = null )
    {
        if ( $timestamp === null )
            $timestamp = time();
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
                                                  'release' => $release );
    }

    static function md5sum( $file )
    {
        if ( function_exists( 'md5_file' ) )
        {
            if ( file_exists( $file ) )
            {
                return md5_file( $file );
            }
            else
            {
                eZDebug::writeError( "Could not open file $file for md5sum calculation" );
            }
        }
        else
        {
            $fd = @fopen( $file, 'rb' );
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

    function fileStorePath( $fileItem, $collectionName, $path = false, $installVariables = array() )
    {
        $type = $fileItem['type'];
        $variableName = $fileItem['variable-name'];
        if ( $type == 'file' )
        {
            $pathArray = array( $path, $fileItem['subdirectory'] );
            $pathArray[] = $fileItem['name'];
            $path = eZDir::path( $pathArray );
        }
        else if ( $type == 'design' )
        {
            $roleFileName = false;
            $design = $fileItem['design'];
            switch ( $fileItem['role'] )
            {
                case 'template':
                {
                    $roleFileName = 'templates';
                } break;
                case 'image':
                {
                    $roleFileName = 'images';
                } break;
                case 'stylesheet':
                {
                    $roleFileName = 'stylesheets';
                } break;
                case 'font':
                {
                    $roleFileName = 'fonts';
                } break;
            }
            if ( $variableName and
                 isset( $installVariables[$variableName] ) )
                $design = $installVariables[$variableName];
            $pathArray = array( $path, 'design', $design, $roleFileName, $fileItem['subdirectory'] );
            if ( $fileItem['file-type'] != 'dir' )
                $pathArray[] = $fileItem['name'];
            $path = eZDir::path( $pathArray );
        }
        else if ( $type == 'ini' )
        {
            $roleValue = false;
            $roleFileName = false;
            switch ( $fileItem['role'] )
            {
                case 'override':
                {
                    $roleFileName = 'override';
                } break;
                case 'siteaccess':
                {
                    $roleFileName = 'siteaccess';
                    $roleValue = $fileItem['role-value'];
                    if ( $variableName and
                         isset( $installVariables[$variableName] ) )
                        $roleValue = $installVariables[$variableName];
                } break;
                case 'standard':
                default:
                {
                    $roleFileName = '';
                } break;
            }
            $pathArray = array( $path, 'settings', $roleFileName, $roleValue, $fileItem['subdirectory'] );
            if ( $fileItem['file-type'] != 'dir' )
                $pathArray[] = $fileItem['name'];
            $path = eZDir::path( $pathArray );
        }
        return $path;
    }

    function fileItemPath( $fileItem, $collectionName, $path = false )
    {
        if ( !$path )
        {
            $repositoryInformation = $this->currentRepositoryInformation();
            $path = $repositoryInformation['path'];
        }
        $typeDir = $fileItem['type'];
        if ( $fileItem['type'] == 'design' )
            $typeDir .= '.' . $fileItem['design'];
        if ( isset( $fileItem['role'] ) && $fileItem['role'] )
        {
            $typeDir .= '.' . $fileItem['role'];
            if ( $fileItem['role-value'] )
                $typeDir .= '-' . $fileItem['role-value'];
        }
        $path .= '/' . $this->attribute( 'name' ) . '/' . eZPackage::filesDirectory() . '/' . $collectionName . '/' . $typeDir;
        if ( isset( $fileItem['subdirectory'] ) && $fileItem['subdirectory'] )
            $path .= '/' . $fileItem['subdirectory'];
        $path .= '/' . $fileItem['name'];
        return $path;
    }

    function fileList( $collectionName )
    {
        $fileCollections = $this->Parameters['file-list'];
        if ( isset( $fileCollections[$collectionName] ) )
            return $fileCollections[$collectionName];
        return false;
    }

    function thumbnailList( $collectionName )
    {
        $thumbnails = array();
        $fileList = $this->fileList( $collectionName );
        if ( !is_array( $fileList ) )
            return $thumbnails;

        foreach ( $fileList as $fileItem )
        {
            if ( $fileItem['type'] == 'thumbnail' )
            {
                $thumbnails[] = $fileItem;
            }
        }
        return $thumbnails;
    }

    function fileCount()
    {
        $count = 0;
        foreach ( $this->Parameters['file-list'] as $collection )
        {
            $count += count( $collection );
        }
        return $count;
    }

    function appendFile( $file, $type, $role,
                         $design, $filePath, $collection,
                         $subDirectory = null, $md5 = null,
                         $copyFile = false, $modified = null, $fileType = false,
                         $roleValue = false, $variableName = false,
                         $packagePath = false )
    {
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
        if ( $packagePath )
            $subDirectory = $packagePath;

        $fileItem = array( 'name' => $file,
                           'subdirectory' => $subDirectory,
                           'type' => $type,
                           'role' => $role,
                           'role-value' => $roleValue,
                           'variable-name' => $variableName,
                           'path' => $filePath,
                           'file-type' => $fileType,
                           'design' => $design );
        if ( $md5 === null )
        {
            $md5 = $this->md5sum( $filePath );
        }
        $fileItem['md5'] = $md5;
        $this->Parameters['file-list'][$collection][] = $fileItem;

        if ( $copyFile )
        {
            // copying file
            $typeDir = $type;
            if ( $type == 'design' )
                $typeDir .= '.' . $fileItem['design'];
            if ( $role )
            {
                $typeDir .= '.' . $role;
                if ( $roleValue )
                    $typeDir .= '-' . $roleValue;
            }
            $path = $this->path() . '/' . eZPackage::filesDirectory() . '/' . $collection . '/' . $typeDir;
            if ( $subDirectory )
                $path .= '/' . $subDirectory;
            if ( !file_exists( $path ) )
                eZDir::mkdir( $path, false, true );

            if ( is_dir( $fileItem['path'] ) )
            {
                eZDir::copy( $fileItem['path'], $path,
                             $fileItem['name'] != false, true, false, eZDir::temporaryFileRegexp() );
            }
            else
            {
                eZFileHandler::copy( $fileItem['path'], $path . '/' . $fileItem['name'] );
            }
        }
    }

    /*!
     Appends a new \c provides dependency.
     \note This function is only a convenience function to the general appendDependency() function.
    */
    function appendProvides( $type, $name, $value, $parameters = false )
    {
        $dependencyParameters = array( 'type'  => $type,
                                       'name'  => $name,
                                       'value' => $value );

        if ( $parameters !== false )
            $dependencyParameters = array_merge( $dependencyParameters, $parameters );

        $this->appendDependency( 'provides', $dependencyParameters );
    }

    /*!
     Appends a new dependency item to the section \a $dependencySection.
     \param $dependencySection Can be one of \c provides, \c requires, \c obsoletes, \c conflicts
     \param $parameters A list of data specific to the dependency type.
    */
    function appendDependency( $dependencySection, $parameters )
    {
        if ( !in_array( $dependencySection,
                        array( 'provides', 'requires',
                               'obsoletes', 'conflicts' ) ) )
            return false;

        $this->Parameters['dependencies'][$dependencySection][] = $parameters;
    }

    function dependencyOperatorText( $dependencyItem )
    {
        return '=';
    }

    function createDependencyText( $cli, $dependencyItem, $dependencySection )
    {
        $text = ( $cli->stylize( 'emphasize', $dependencyItem['type'] ) .
                  '(' .
                  $cli->stylize( 'emphasize', $dependencyItem['name'] ) .
                  ')' );
        if ( $dependencyItem['value'] )
            $text .= ' ' . $this->dependencyOperatorText( $dependencyItem ) .' ' . $cli->stylize( 'symbol', $dependencyItem['value'] );
        $handler = $this->packageHandler( $dependencyItem['type'] );
        if ( $handler )
        {
            $specialText = $handler->createDependencyText( $this, $dependencyItem, $dependencySection );
            if ( $specialText )
                $text .= ' ( ' . $specialText . ' ) ';
        }
        return $text;
    }

    function groupDependencyItemsByType( $dependencyItems )
    {
        $types = array();
        foreach ( $dependencyItems as $dependencyItem )
        {
            if ( !isset( $types[$dependencyItem['type']] ) )
                $types[$dependencyItem['type']] = array();
            $types[$dependencyItem['type']][] = $dependencyItem;
        }
        return $types;
    }

    /*!
     \return an array with dependency items which match the specified criterias.
    */
    function dependencyItems( $dependencySection, $parameters = false )
    {
        if ( !in_array( $dependencySection,
                        array( 'provides', 'requires',
                               'obsoletes', 'conflicts' ) ) )
            return false;

        if ( $parameters === false )
        {
            return $this->Parameters['dependencies'][$dependencySection];
        }

        $matches = array();
        $dependencyItems = $this->Parameters['dependencies'][$dependencySection];
        foreach ( $dependencyItems as $dependencyItem )
        {
            $found = true;

            foreach ( $parameters as $paramName => $paramValue )
            {
                if ( !isset( $dependencyItem[$paramName] ) ||
                     $dependencyItem[$paramName] != $paramValue )
                {
                    $found = false;
                    break;
                }
            }

            if ( $found )
                $matches[] = $dependencyItem;
        }

        return $matches;
    }

    /*!
     \return an array with install items which match the specified criterias.
    */
    function installItemsList( $type = false, $os = false, $name = false, $isInstall = true )
    {
        $installName = 'install';
        if ( !$isInstall )
            $installName = 'uninstall';
        if ( !$name and !$type and !$os )
        {
            return $this->Parameters[$installName];
        }
        else
        {
            $matches = array();
            $installItems = $this->Parameters[$installName];
            foreach ( $installItems as $installItem )
            {
                $found = false;
                if ( $name and $installItem['name'] == $name )
                    $found = true;
                if ( !$found and $type and $installItem['type'] == $type )
                    $found = true;
                if ( !$found )
                {
                    if ( $os )
                    {
                        if ( !$installItem['os'] )
                            $found = true;
                        else if ( $os and $installItem['os'] == $os )
                            $found = true;
                    }
                    else
                        $found = true;
                }
                if ( $found )
                    $matches[] = $installItem;
            }
            return $matches;
        }
    }

    function appendInstall( $type, $name, $os = false, $isInstall = true,
                            $filename = false, $subdirectory = false,
                            $parameters = false )
    {
        $installEntry = $parameters;
        $installEntry['type'] = $type;
        $installEntry['name'] = $name;
        $installEntry['os'] = $os;
        $installEntry['filename'] = $filename;
        $installEntry['sub-directory'] = $subdirectory;
        if ( $installEntry['filename'] )
        {
            $content = false;
            if ( isset( $installEntry['content'] ) )
                $content = $installEntry['content'];
            if ( $content instanceof DOMElement )
            {
                $path = $this->path();
                if ( $installEntry['sub-directory'] )
                {
                    $path .= '/' . $installEntry['sub-directory'];
                }
                $filePath = $path . '/' . $installEntry['filename'] . '.xml';
                if ( !file_exists( $path ) )
                {
                    eZDir::mkdir( $path, false, true );
                }
                $partDOM = new DOMDocument( '1.0', 'utf-8' );
                $partDOM->formatOutput = true;
                $contentImport = $partDOM->importNode( $content, true );
                $partDOM->appendChild( $contentImport );
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
                         $licence = false, $state = false )
    {
        if ( $version !== false )
        {
            $this->Parameters['version-number'] = $version;
        }
        if ( $release !== false )
        {
            $this->Parameters['release-number'] = $release;
        }
        if ( $timestamp !== false )
        {
            $this->Parameters['release-timestamp'] = $timestamp;
        }
        if ( $licence !== false )
        {
            $this->Parameters['licence'] = $licence;
        }
        if ( $state !== false )
        {
            $this->Parameters['state'] = $state;
        }
    }

    /*!
     \private
     \return the package as a string, the string is in xml format.
    */
    function toString()
    {
        $dom = $this->domStructure();
        $string = $dom->saveXML();
        return $string;
    }

    /*!
     \private
     Stores a cached version of the package in the cache directory
     under the repository for the package.
    */
    function storeCache( $directory = false )
    {
        if ( !file_exists( $directory ) )
            eZDir::mkdir( $directory, false, true );
        $php = new eZPHPCreator( $directory, 'package.php' );
        $php->addComment( "Automatically created cache file for the package format\n" .
                          "Do not modify this file" );
        $php->addSpace();
        $php->addVariable( 'CacheCodeDate', eZPackage::CACHE_CODE_DATE );
        $php->addSpace();
        $php->addVariable( 'Parameters', $this->Parameters, eZPHPCreator::VARIABLE_ASSIGNMENT,
                           array( 'full-tree' => true ) );
        $php->addVariable( 'InstallData', $this->InstallData, eZPHPCreator::VARIABLE_ASSIGNMENT,
                           array( 'full-tree' => true ) );
        $php->addVariable( 'RepositoryPath', $this->RepositoryPath );
        $php->store();
    }

    /*!
     Stores the current package in the repository.
    */
    function store()
    {
        $path = $this->path();
        return $this->storePackageFile( $path );
    }

    /*!
     Stores the current package definition file to the directory \a $path.
    */
    function storePackageFile( $path, $storeCache = true )
    {
        if ( !file_exists( $path ) )
        {
            eZDir::mkdir( $path, false, true );
        }
        $filePath = $path . '/' . eZPackage::definitionFilename();

        $packageFileString = $this->toString();
        $result = $this->storeString( $filePath, $packageFileString );

        if ( $storeCache )
            $this->storeCache( $path . '/' . $this->cacheDirectory() );
        return $result;
    }

    /*!
     Recursively deletes \a $path
    */
    static function removeFiles( $path )
    {
        if ( file_exists( $path ) )
        {
            eZDir::recursiveDelete( $path );
        }
    }

    /*!
     Exports the package as a gzip compressed tarball to the directory \a $archivePath
    */
    function exportToArchive( $archivePath )
    {
        $temporaryExportPath = eZPackage::temporaryExportPath();
        $tempPath = $temporaryExportPath . '/' . $this->attribute( 'name' );
        $this->removeFiles( $tempPath );

        // Create package temp dir and copy package's XML file there
        $this->storePackageFile( $tempPath, false );

        // Copy package's directories
        $directoryList = array( $this->documentDirectory(),
                                $this->filesDirectory(),
                                $this->simpleFilesDirectory(),
                                $this->settingsDirectory() );
        $installItems = $this->Parameters['install'];
        foreach( $installItems as $installItem )
        {
            if ( !in_array( $installItem['sub-directory'], $directoryList ) )
                $directoryList[] = $installItem['sub-directory'];
        }

        $path = $this->path();
        foreach( $directoryList as $dirName )
        {
            $destDir = $tempPath;
            $dir = $path . '/' . $dirName;
            if ( file_exists( $dir ) )
                eZDir::copy( $dir, $destDir );
        }

        $tarArchivePath = $temporaryExportPath . '/archive.tmp';
        $tarArchive = ezcArchive::open( $tarArchivePath, ezcArchive::TAR_USTAR );
        $tarArchive->truncate();

        $prefix = $tempPath . '/';
        $fileList = array();
        eZDir::recursiveList( $tempPath, $tempPath, $fileList );

        foreach ( $fileList as $fileInfo )
        {
            $path = $fileInfo['type'] === 'dir' ?
                $fileInfo['path'] . '/' . $fileInfo['name'] . '/' :
                $fileInfo['path'] . '/' . $fileInfo['name'];
            $tarArchive->append( array( $path ), $prefix );
        }

        $tarArchive->close();

        copy( $tarArchivePath, "compress.zlib://$archivePath" );

        unlink( $tarArchivePath );

        $this->removeFiles( $tempPath );
        return $archivePath;
    }

    /*!
     Imports a package from a gzip compressed tarball file \a $archiveName
    */
    static function &import( $archiveName, $packageName, $dbAvailable = true, $repositoryID = false )
    {
        $tempDirPath = eZPackage::temporaryImportPath();
        if ( is_dir( $archiveName ) )
        {
            eZDebug::writeError( "Importing from directory is not supported." );
            $retValue = false;
            return $retValue;
        }
        else
        {
            $archivePath = $tempDirPath . '/' . $packageName;
            eZPackage::removeFiles( $archivePath );
            if ( !file_exists( $archivePath ) )
            {
                eZDir::mkdir( $archivePath, false, true );
            }

            $archiveOptions = new ezcArchiveOptions( array( 'readOnly' => true ) );
            $archive = ezcArchive::open( "compress.zlib://$archiveName", null, $archiveOptions );

            $fileList = array();
            $fileList[] = eZPackage::definitionFilename();

            // Search for the files we want to extract
            foreach( $archive as $entry )
            {
                if ( in_array( $entry->getPath(), $fileList ) )
                {
                    if ( !$archive->extractCurrent( $archivePath ) )
                    {
                        eZDebug::writeError( "Failed extracting package definition file from $archivePath" );
                        return false;
                    }
                }
            }

            $package = eZPackage::fetch( $packageName, $tempDirPath, false, $dbAvailable );
            eZPackage::removeFiles( $archivePath );

            if ( $package )
            {
                $packageName = $package->attribute( 'name' );

                if ( !$repositoryID )
                    $repositoryID = $package->attribute( 'vendor-dir' );

                $existingPackage = eZPackage::fetch( $packageName, false, false, $dbAvailable );
                if ( $existingPackage )
                {
                    $retValue = eZPackage::STATUS_ALREADY_EXISTS;
                    return $retValue;
                }

                unset( $package );

                $fullRepositoryPath = eZPackage::repositoryPath() . '/' . $repositoryID;
                $packagePath = $fullRepositoryPath . '/' . $packageName;
                if ( !file_exists( $packagePath ) )
                {
                    eZDir::mkdir( $packagePath, false, true );
                }
                $archive->extract( $packagePath );

                $package = eZPackage::fetch( $packageName, $fullRepositoryPath, false, $dbAvailable );
                if ( !$package )
                {
                    eZDebug::writeError( "Failed loading imported package $packageName from $fullRepositoryPath" );
                }
            }
            else
            {
                eZDebug::writeError( "Failed loading temporary package $packageName" );
            }

            return $package;
        }
    }

    /*!
     \static
     \return the suffix for all package files.
    */
    static function suffix()
    {
        return 'ezpkg';
    }

    /*!
     \return the file name for an exported archive of the current package
    */
    function exportName()
    {
        return $this->attribute( 'name' ) . '-' . $this->attribute( 'version-number' ) . '-' . $this->attribute( 'release-number' ) . '.' . eZPackage::suffix();
    }

    /*!
     Stores the current package to the file \a $filename.
    */
    function storeToFile( $filename )
    {
        $dom = $this->domStructure();
        return eZPackage::storeDOM( $filename, $dom );
    }

    /*!
     Applies the storage file permissions specified in site.ini to the file \a $filename
    */
    static function applyStorageFilePermissions( $filename )
    {
        $siteConfig = eZINI::instance( 'site.ini' );
        $filePermissions = $siteConfig->variable( 'FileSettings', 'StorageFilePermissions');
        chmod( $filename, octdec( $filePermissions ) );
    }

    /*!
     Stores the DOM tree \a $dom to the file \a $filename.
    */
    static function storeDOM( $filename, $dom )
    {
        $bytes = $dom->save( $filename );

        if ( $bytes !== false )
        {
            eZPackage::applyStorageFilePermissions( $filename );

            eZDebugSetting::writeNotice( 'kernel-ezpackage-store',
                                         "Stored file $filename",
                                         'eZPackage::storeDOM' );
            return true;
        }
        else
        {
            eZDebug::writeError( "Saving DOM tree to $filename failed", 'eZPackage::storeDOM' );
        }

        return false;
    }

    /*!
     \private
     \static
     Stores the string data \a $data into the file \a $filename.
     \return \c true if successful.
    */
    static function storeString( $filename, $data )
    {
        $file = @fopen( $filename, 'w' );
        if ( $file )
        {
            fwrite( $file, $data );
            fclose( $file );

            eZPackage::applyStorageFilePermissions( $filename );

            eZDebugSetting::writeNotice( 'kernel-ezpackage-store',
                                         "Stored file $filename",
                                         'eZPackage::storeString' );
            return true;
        }
        else
        {
            eZDebug::writeError( "Failed to write package '$filename'" );
        }
        return false;
    }


    /*!
     \private
     Loads the contents of the file \a $filename and parses it into a DOM tree.
     The DOM tree is returned.
    */
    static function fetchDOMFromFile( $filename )
    {
        if ( file_exists( $filename ) )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $dom->preserveWhiteSpace = false;
            $success = $dom->load( $filename );

            if ( !$success )
            {
                return false;
            }
            else
            {
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
    static function fetchFromFile( $filename )
    {
        $dom = eZPackage::fetchDOMFromFile( $filename );

        if ( $dom === false )
        {
            return false;
        }

        $package = new eZPackage();
        $parameters = $package->parseDOMTree( $dom );
        if ( !$parameters )
        {
            return false;
        }

        return $package;
    }

    /*!
     \static
     Tries to load the package named \a $packageName from the repository
     and returns the package object.
     \param $repositoryID Determines in which repositories the package should be searched for,
                          if set to \c true it means only look in local packages, \c false means
                          look in all repositories.
     \param $dbAvailable  Do we have a database to fetch additional package info, like installed state.
                          (false in setup wizard)
     \return \c false if no package could be found.
    */
    static function fetch( $packageName, $packagePath = false, $repositoryID = false, $dbAvailable = true )
    {
        $packageRepositories = eZPackage::packageRepositories( array( 'path' => $packagePath ) );

        if ( $repositoryID === true )
            $repositoryID = 'local';

        foreach ( $packageRepositories as $packageRepository )
        {
            if ( $repositoryID !== false and
                 $packageRepository['id'] != $repositoryID )
                continue;
            $path = $packageRepository['path'];

            $path .= '/' . $packageName;
            $filePath = $path . '/' . eZPackage::definitionFilename();

            if ( file_exists( $filePath ) )
            {
                $fileModification = filemtime( $filePath );
                $package = false;
                $cacheExpired = false;

                if ( eZPackage::useCache() )
                {
                    $package = eZPackage::fetchFromCache( $path, $fileModification, $cacheExpired );
                }

                if ( $package )
                {
                    $package->setCurrentRepositoryInformation( $packageRepository );
                }
                else
                {
                    $package = eZPackage::fetchFromFile( $filePath );

                    if ( $package )
                    {
                        $package->setCurrentRepositoryInformation( $packageRepository );
                        if ( $packagePath )
                            $package->RepositoryPath = $packagePath;
                        if ( $cacheExpired and
                             eZPackage::useCache() )
                        {
                            $package->storeCache( $path . '/' . eZPackage::cacheDirectory() );
                        }
                    }
                }
                if ( $dbAvailable )
                    $package->getInstallState();

                return $package;
            }
        }
        return false;
    }

    static function useCache()
    {
        return eZPackage::USE_CACHE;
    }

    /*!
     \private
    */
    static function fetchFromCache( $packagePath, $packageModification, &$cacheExpired )
    {
        $packageCachePath = $packagePath . '/' . eZPackage::cacheDirectory() . '/package.php';

        if ( file_exists( $packageCachePath ) )
        {
            $cacheModification = filemtime( $packageCachePath );
            if ( $cacheModification >= $packageModification )
            {
                include( $packageCachePath );
                if ( !isset( $CacheCodeDate ) or
                     $CacheCodeDate != eZPackage::CACHE_CODE_DATE )
                {
                    $cacheExpired = true;
                    return false;
                }
                if ( isset( $Parameters ) and
                     isset( $InstallData ) )
                {
                    $cacheExpired = false;
                    $package = new eZPackage( $Parameters, $RepositoryPath );
                    $package->InstallData = $InstallData;
                    return $package;
                }
            }
            else
                $cacheExpired = true;
        }
        $cacheExpired = true;
        return false;
    }

    /*!
     \return the full path to this package.
    */
    function path()
    {
        $path = $this->currentRepositoryPath();
        $path .= '/' . $this->attribute( 'name' );
        return $path;
    }

    /*!
     \return the path to the current repository.
    */
    function currentRepositoryPath()
    {
        $repositoryInformation = $this->currentRepositoryInformation();
        if ( $repositoryInformation )
            return $repositoryInformation['path'];
        return $this->RepositoryPath;
    }

    /*!
     \static
     \return the directory name for temporary export packages, used in conjunction with eZSys::cacheDirectory().
    */
    static function temporaryExportPath()
    {
        $path = eZDir::path( array( eZSys::cacheDirectory(),
                                    'packages',
                                    'export' . eZUser::currentUserID() ) );
        return $path;
    }

    /*!
     \static
     \return the directory name for temporary import packages, used in conjunction with eZSys::cacheDirectory().
    */
    static function temporaryImportPath()
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
    static function repositoryPath()
    {
        $ini = eZINI::instance();
        $packageIni = eZINI::instance( 'package.ini' );

        return eZDir::path( array( 'var',
                                   $ini->variable( 'FileSettings', 'StorageDir' ),
                                   $packageIni->variable( 'RepositorySettings', 'RepositoryDirectory' ) ) );
    }

    /*!
     \static
     \return the name of the cache directory for cached package data.
    */
    static function cacheDirectory()
    {
        return '.cache';
    }

    /*!
     \static
     \return the name of the package definition file.
    */
    static function definitionFilename()
    {
        return 'package.xml';
    }

    /*!
     \static
     \return the name of the documents directory for cached package data.
    */
    static function documentDirectory()
    {
        return 'documents';
    }

    /*!
     \static
     \return the name of the documents directory for cached package data.
    */
    static function filesDirectory()
    {
        return 'files';
    }

    /*!
     \private
     Get local simple file path
    */
    static function simpleFilesDirectory()
    {
        return 'simplefiles';
    }

    static function settingsDirectory()
    {
        return 'settings';
    }

    /*!
     Locates all dependent packages in the repository and returns an array with eZPackage objects.
     \param $dependencyType is the name of a dependency sub-node. (ie. 'provides', 'requires' etc...)
    */
    function fetchDependentPackages( $dependencyType, &$failedList )
    {
        $packages = array();
        $provides = $this->Parameters['dependencies'][$dependencyType];

        if ( $provides != null )
        {
            foreach ( $provides as $provide )
            {
                // fetch only dependent packages, not package items.
                if ( $provide['type'] == 'ezpackage' )
                {
                    // TODO: Add fetching from URL (not here ?)
                    $package = $this->fetch( $provide['name'] );

                    if ( !$package )
                    {
                        $failedList[] = $provide['name'];
                        continue;
                    }
                    $packages[] =& $package;
                }
            }
        }
        return $packages;
    }

    /*!
     \static
     \return an array with repositories which can contain packages.

     Each repository entry is an array with the following keys.
     - path The path to the repository relative from the eZ Publish installation
     - id   Unique identifier for this repository
     - name Human readable string identifying this repository, the name is translatable
     - type What kind of repository, currently supports local or global.
    */
    static function packageRepositories( $parameters = array() )
    {
        require_once "kernel/common/i18n.php";
        if ( isset( $parameters['path'] ) and $parameters['path'] )
        {
            $path = $parameters['path'];
            $packageRepositories = array( array( 'path' => $path,
                                             'id' => 'local',
                                             'name' => ezi18n( 'kernel/package', 'Local' ),
                                             'type' => 'local' ) );
        }
        else
        {
            $repositoryPath = eZPackage::repositoryPath();
            $packageRepositories = array( array( 'path' => $repositoryPath . '/local',
                                                 'id' => 'local',
                                                 'name' => ezi18n( 'kernel/package', 'Local' ),
                                                 'type' => 'local' ) );

            $subdirs = eZDir::findSubitems( $repositoryPath, 'd' );
            foreach( $subdirs as $dir )
            {
                if ( $dir == 'local' )
                    continue;

                $packageRepositories[] = array( 'path' => $repositoryPath . '/' . $dir,
                                                'id' => $dir,
                                                'name' => $dir,
                                                'type' => 'global' );
            }
        }
        return $packageRepositories;
    }

    /*!
     \static
     \return information on the repository with ID $repositoryID or \c false if does not exist.
    */
    static function repositoryInformation( $repositoryID )
    {
        $packageRepositories = eZPackage::packageRepositories();
        foreach ( $packageRepositories as $packageRepository )
        {
            if ( $packageRepository['id'] == $repositoryID )
                return $packageRepository;
        }
        return false;
    }

    /*!
     Sets the current repository information for the package.
     \sa currentRepositoryInformation, packageRepositories
    */
    function setCurrentRepositoryInformation( $information )
    {
        $this->RepositoryInformation = $information;
    }

    /*!
     \return the current repository information for the package, this
             will contain information of where the package was found.
     See packageRepositories too see what the information will contain.
     \note The return information can be \c null in some cases when the package is not properly initialized.
    */
    function currentRepositoryInformation()
    {
        return $this->RepositoryInformation;
    }

    /*!
     Locates all packages in the repository and returns an array with eZPackage objects.

     \param parameters
     \param filterArray
    */
    static function fetchPackages( $parameters = array(), $filterArray = array() )
    {
        $packageRepositories = eZPackage::packageRepositories( $parameters );

        $packages = array();

        $requiredType = null;
        $requiredPriority = null;
        $requiredVendor = null;
        $requiredExtension = null;
        if ( isset( $filterArray['type'] ) )
            $requiredType = $filterArray['type'];
        if ( isset( $filterArray['priority'] ) )
            $requiredPriority = $filterArray['priority'];
        if ( isset( $filterArray['vendor'] ) )
            $requiredVendor = $filterArray['vendor'];
        if ( isset( $filterArray['extension'] ) )
            $requiredExtension = $filterArray['extension'];
        $repositoryID = false;
        if ( isset( $parameters['repository_id'] ) )
            $repositoryID = $parameters['repository_id'];
        $dbAvailable = true;
        if ( isset( $parameters['db_available'] ) )
            $dbAvailable = $parameters['db_available'];

        foreach ( $packageRepositories as $packageRepository )
        {
            if ( strlen( $repositoryID ) == 0 or
                 $repositoryID == $packageRepository['id'] )
            {
                $path = $packageRepository['path'];
                if ( file_exists( $path ) )
                {
                    $fileList = array();
                    $dir = opendir( $path );
                    while( ( $file = readdir( $dir ) ) !== false )
                    {
                        if ( $file == '.' or
                             $file == '..' )
                            continue;
                        $fileList[] = $file;
                    }
                    closedir( $dir );
                    sort( $fileList );
                    foreach ( $fileList as $file )
                    {
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
                            {
                                $package = eZPackage::fetchFromCache( $dirPath, $fileModification, $cacheExpired );
                            }
                            if ( !$package )
                            {
                                $package = eZPackage::fetchFromFile( $filePath );
                                if ( $package and
                                     $cacheExpired and
                                     eZPackage::useCache() )
                                {
                                    $package->storeCache( $dirPath . '/' . eZPackage::cacheDirectory() );
                                }
                            }
                            if ( !$package )
                                continue;

                            if ( $dbAvailable )
                                $package->getInstallState();

                            if ( $requiredType !== null )
                            {
                                $type = $package->attribute( 'type' );
                                if ( $type != $requiredType )
                                    continue;
                            }

                            if ( $requiredPriority !== null )
                            {
                                $type = $package->attribute( 'priority' );
                                if ( $priority != $requiredPriority )
                                    continue;
                            }

                            if ( $requiredExtension !== null )
                            {
                                $type = $package->attribute( 'extension' );
                                if ( $extension != $requiredExtension )
                                    continue;
                            }

                            if ( $requiredVendor !== null )
                            {
                                $type = $package->attribute( 'vendor' );
                                if ( $vendor != $requiredVendor )
                                    continue;
                            }

                            $package->setCurrentRepositoryInformation( $packageRepository );

                            $packages[] =& $package;
                        }
                    }
                }
            }
        }
        return $packages;
    }

    /*!
     Install specified install item in package

     \param Item index
     \param parameters
    */
    function installItem( $item, &$installParameters )
    {
        $type = $item['type'];
        $name = $item['name'];
        $os = $item['os'];
        $filename = $item['filename'];
        $subdirectory = $item['sub-directory'];
        $content = false;
        if ( isset( $item['content'] ) )
            $content = $item['content'];
        $handler = $this->packageHandler( $type );
        $installResult = false;
        if ( $handler )
        {
            if ( $handler->extractInstallContent() )
            {
                if ( !$content and
                     $filename )
                {
                    if ( $subdirectory )
                        $filepath = $subdirectory . '/' . $filename . '.xml';
                    else
                        $filepath = $filename . '.xml';

                    $filepath = $this->path() . '/' . $filepath;

                    $dom = eZPackage::fetchDOMFromFile( $filepath );
                    if ( $dom )
                    {
                        $content = $dom->documentElement;
                    }
                    else
                    {
                        eZDebug::writeError( "Failed fetching dom from file $filepath", 'eZPackage::installItem' );
                    }
                }
            }
            $installData =& $this->InstallData[$type];
            if ( !isset( $installData ) )
                $installData = array();
            $installResult = $handler->install( $this, $type, $item,
                                                $name, $os, $filename, $subdirectory,
                                                $content, $installParameters,
                                                $installData );
        }
        return $installResult;
    }

    /*!
     Install all install items in package
    */
    function install( &$installParameters )
    {
        if ( $this->Parameters['install_type'] != 'install' )
            return;
        $installItems = $this->Parameters['install'];
        if ( !isset( $installParameters['path'] ) )
            $installParameters['path'] = false;
        $installResult = true;
        foreach ( $installItems as $item )
        {
            if ( !$this->installItem( $item, $installParameters ) )
            {
                eZDebug::writeDebug( $item, 'item which failed installing' );
                $installResult = false;
            }
        }
        $this->setInstalled();
        return $installResult;
    }

    function uninstallItem( $item, &$uninstallParameters )
    {
        $type = $item['type'];
        $name = $item['name'];
        $os = $item['os'];
        $filename = $item['filename'];
        $subdirectory = $item['sub-directory'];
        $content = false;
        if ( isset( $item['content'] ) )
            $content = $item['content'];

        $handler = $this->packageHandler( $type );
        if ( $handler )
        {
            if ( $handler->extractInstallContent() )
            {
                if ( !$content and
                     $filename )
                {
                    if ( $subdirectory )
                        $filepath = $subdirectory . '/' . $filename . '.xml';
                    else
                        $filepath = $filename . '.xml';

                    $filepath = $this->path() . '/' . $filepath;

                    $dom = eZPackage::fetchDOMFromFile( $filepath );
                    if ( $dom )
                    {
                        $content = $dom->documentElement;
                    }
                    else
                    {
                        eZDebug::writeError( "Failed fetching dom from file $filepath", 'eZPackage::uninstallItem' );
                    }
                }
            }

            if ( isset( $this->InstallData[$type] ) )
            {
                $installData =& $this->InstallData[$type];
            }
            else
            {
                unset( $installData );
                $installData = array();
            }
            $uninstallResult = $handler->uninstall( $this, $type, $item,
                                                  $name, $os, $filename, $subdirectory,
                                                  $content, $uninstallParameters,
                                                  $installData );
        }
        return $uninstallResult;
    }

    /*!
     Install all install items in package
    */
    function uninstall( $uninstallParameters = array() )
    {
        if ( $this->Parameters['install_type'] != 'install' )
            return;
        if ( !$this->isInstalled() )
            return;
        $uninstallItems = $this->uninstallItemsList();
        if ( !isset( $installParameters['path'] ) )
            $installParameters['path'] = false;

        $uninstallResult = true;
        foreach ( $uninstallItems as $item )
        {
            if ( !$this->uninstallItem( $item, $uninstallParameters ) )
            {
                $uninstallResult = false;
            }
        }

        $this->InstallData = array();
        $this->setInstalled( false );
        return $uninstallResult;
    }

    /*!
     \private
    */
    function parseDOMTree( DOMDocument $dom )
    {
        $root = $dom->documentElement;

        // Read basic info
        $parameters = array();
        $parameters['name'] = $root->getElementsByTagName( 'name' )->item( 0 )->textContent;
        $vendorNode = $root->getElementsByTagName( 'vendor' )->item( 0 );
        $parameters['vendor'] = is_object( $vendorNode ) ? $vendorNode->textContent : false;
        $parameters['summary'] = $root->getElementsByTagName( 'summary' )->item( 0 )->textContent;
        $parameters['description'] = $root->getElementsByTagName( 'description' )->item( 0 )->textContent;
        $priorities = $root->getElementsByTagName( 'priority' );
        if ( $priorities->length > 0 )
        {
            $parameters['priority'] = $priorities->item( 0 )->getAttribute( 'value' );
        }
        $parameters['type'] = $root->getElementsByTagName( 'type' )->item( 0 )->getAttribute( 'value' );

        if ( $parameters['vendor'] )
        {
           // Creating nice vendor directory name
           $trans = eZCharTransform::instance();
           $parameters['vendor-dir'] = $trans->transformByGroup( $parameters['vendor'], 'urlalias' );
        }
        else
        {
            $parameters['vendor-dir'] = 'local';
        }

        $parameters['install_type'] = 'install';
        $installType = $root->getAttribute( 'install_type' );
        if ( $installType )
            $parameters['install_type'] = $installType;
        $sourceNodes = $root->getElementsByTagName( 'source' );
        if ( $sourceNodes->length > 0 )
        {
            $parameters['source'] = $sourceNodes->item( 0 )->textContent;
        }
        $parameters['development'] = $root->getAttribute( 'development' ) == 'true';
        $extensionNode = $root->getElementsByTagName( 'extension' )->item( 0 );
        if ( $extensionNode )
            $parameters['extension'] = $extensionNode->getAttribute( 'name' );
        $ezpublishNode = $root->getElementsByTagName( 'ezpublish' )->item( 0 );
        $parameters['ezpublish']['version'] = $ezpublishNode->getElementsByTagName( 'version' )->item( 0 )->textContent;
        $parameters['ezpublish']['named-version'] = $ezpublishNode->getElementsByTagName( 'named-version' )->item( 0 )->textContent;
        $this->setParameters( $parameters );

        // Read maintainers
        $maintainersNode = $root->getElementsByTagName( 'maintainers' )->item( 0 );
        if ( $maintainersNode )
        {
            $maintainerNodes = $maintainersNode->getElementsByTagName( 'maintainer' );
            foreach ( $maintainerNodes as $maintainerNode )
            {
                $maintainerName = $maintainerNode->getElementsByTagName( 'name' )->item( 0 )->textContent;
                $maintainerEmail = $maintainerNode->getElementsByTagName( 'email' )->item( 0 )->textContent;
                $maintainerRole = $maintainerNode->getElementsByTagName( 'role' )->item( 0 )->textContent;
                $this->appendMaintainer( $maintainerName, $maintainerEmail, $maintainerRole );
            }
        }

        // Read packaging info
        $packagingNode = $root->getElementsByTagName( 'packaging' )->item( 0 );
        $packagingTimestamp = $packagingNode->getElementsByTagName( 'timestamp' )->item( 0 )->textContent;
        $packagingHost = $packagingNode->getElementsByTagName( 'host' )->item( 0 )->textContent;
        $packagerNodes = $packagingNode->getElementsByTagName( 'packager' );
        if ( $packagerNodes->length > 0 )
        {
            $packagingPackager = $packagerNodes->item( 0 )->textContent;
        }
        else
        {
            $packagingPackager = false;
        }
        $this->setPackager( $packagingTimestamp, $packagingHost, $packagingPackager );

        // Read documents
        $documentsNode = $root->getElementsByTagName( 'documents' )->item( 0 );
        $documentNodes = $documentsNode->getElementsByTagName( 'document' );

        foreach ( $documentNodes as $documentNode )
        {
            $documentName = $documentNode->getAttribute( 'name' );
            $documentMimeType = $documentNode->getAttribute( 'mime-type' );
            $documentOS = $documentNode->getAttribute( 'os' );
            $documentAudience = $documentNode->getAttribute( 'audience' );
            $this->appendDocument( $documentName, $documentMimeType,
                                   $documentOS, $documentAudience,
                                   false, false );
        }

        // Read changelog
        $changelogNode = $root->getElementsByTagName( 'changelog' )->item( 0 );
        if ( $changelogNode )
        {
            $changelogEntryNodes = $changelogNode->getElementsByTagName( 'entry' );
            foreach ( $changelogEntryNodes as $changelogEntryNode )
            {
                $changelogTimestamp = $changelogEntryNode->getAttribute( 'timestamp' );
                $changelogPerson = $changelogEntryNode->getAttribute( 'person' );
                $changelogEmail = $changelogEntryNode->getAttribute( 'email' );
                $changelogRelease = $changelogEntryNode->getAttribute( 'release' );
                $changelogChangeList = $changelogEntryNode->getElementsByTagName( 'change' )->item( 0 )->textContent;
                $this->appendChange( $changelogPerson, $changelogEmail, $changelogChangeList,
                                     $changelogRelease, $changelogTimestamp );
            }
        }

        // Read simple files
        $this->Parameters['simple-file-list'] = array();
        $simpleFilesNode = $root->getElementsByTagName( 'simple-files' )->item( 0 );
        if ( $simpleFilesNode && $simpleFilesNode->hasChildNodes() )
        {
            $simpleFileNodes = $simpleFilesNode->getElementsByTagName( 'simple-file' );
            foreach ( $simpleFileNodes as $simpleFileNode )
            {
                $key = $simpleFileNode->getAttribute( 'key' );
                $originalPath = $simpleFileNode->getAttribute( 'original-path' );
                $packagePath = $simpleFileNode->getAttribute( 'package-path' );
                $this->Parameters['simple-file-list'][$key] = array( 'original-path' => $originalPath,
                                                                     'package-path' => $packagePath );
            }
        }

        // Read files
        $filesList = $root->getElementsByTagName( 'files' );
        if ( $filesList )
        {
            foreach ( $filesList as $fileCollectionNode )
            {
                $fileCollectionName = $fileCollectionNode->getAttribute( 'name' );
                $fileLists = $fileCollectionNode->getElementsByTagName( 'file-list' );
                foreach ( $fileLists as $fileListNode )
                {
                    $fileType = $fileListNode->getAttribute( 'type' );
                    $fileDesign = $fileListNode->getAttribute( 'design' );
                    $fileRole = $fileListNode->getAttribute( 'role' );
                    $fileVariableName = $fileListNode->getAttribute( 'variable-name' );
                    $fileRoleValue = $fileListNode->getAttribute( 'role-value' );
                    $files = $fileListNode->getElementsByTagName( 'file' );
                    if ( count( $files ) > 0 )
                    {
                        foreach ( $files as $fileNode )
                        {
                            $fileFileType = $fileNode->getAttribute( 'type' );
                            $fileName = $fileNode->getAttribute( 'name' );
                            if ( $fileNode->getAttribute( 'variable-name' ) )
                                $fileVariableName = $fileNode->getAttribute( 'variable-name' );
                            $fileSubDirectory = $fileNode->getAttribute( 'sub-directory' );
                            $filePath = $fileNode->getAttribute( 'path' );
                            $fileMD5 = $fileNode->getAttribute( 'md5sum' );
                            $this->appendFile( $fileName, $fileType, $fileRole,
                                               $fileDesign, $filePath, $fileCollectionName,
                                               $fileSubDirectory, $fileMD5, false, null,
                                               $fileFileType,
                                               $fileRoleValue, $fileVariableName );
                        }
                    }
                    else
                    {
                        $this->appendFile( false, $fileType, $fileRole,
                                           $fileDesign, false, $fileCollectionName,
                                           false, false, false, null );
                    }
                    unset( $files );
                }
            }
        }
        // Read release info
        $xpath = new DOMXPath( $dom );
        $versionNode = $xpath->query( 'version' )->item( 0 );
        $versionNumber = false;
        $versionRelease = false;
        if ( $versionNode )
        {
            $versionNumber = $versionNode->getElementsByTagName( 'number' )->item( 0 )->textContent;
            $versionRelease = $versionNode->getElementsByTagName( 'release' )->item( 0 )->textContent;
        }
        $licence = $root->getElementsByTagName( 'licence' )->item( 0 )->textContent;
        $state = $root->getElementsByTagName( 'state' )->item( 0 )->textContent;
        $this->setRelease( $versionNumber, $versionRelease, false,
                           $licence, $state );

        $dependenciesNode = $root->getElementsByTagName( 'dependencies' )->item( 0 );
        if ( $dependenciesNode )
        {
            $providesNode = $dependenciesNode->getElementsByTagName( 'provides' )->item( 0 );
            $providesList = $providesNode->getElementsByTagName( 'provide' );
            $requiresNode = $dependenciesNode->getElementsByTagName( 'requires' )->item( 0 );
            $requiresList = $requiresNode->getElementsByTagName( 'require' );
            $obsoletesNode = $dependenciesNode->getElementsByTagName( 'obsoletes' )->item( 0 );
            $obsoletesList = $obsoletesNode->getElementsByTagName( 'obsolete' );
            $conflictsNode = $dependenciesNode->getElementsByTagName( 'conflicts' )->item( 0 );
            $conflictsList = $conflictsNode->getElementsByTagName( 'conflict' );
            $this->parseDependencyTree( $providesList, 'provides' );
            $this->parseDependencyTree( $requiresList, 'requires' );
            $this->parseDependencyTree( $obsoletesList, 'obsoletes' );
            $this->parseDependencyTree( $conflictsList, 'conflicts' );
        }

        $settingsNode = $root->getElementsByTagName( 'settings' )->item( 0 );

        if ( $settingsNode )
        {
            $settingsFileNodes = $settingsNode->getElementsByTagName( 'settings-file' );
            $this->Parameters['settings-files'] = array();

            foreach( $settingsFileNodes as $settingsFileNode )
            {
                $this->Parameters['settings-files'][] = $settingsFileNode->getAttribute( 'filename' );
            }
        }

        $installNode = $root->getElementsByTagName( 'install' )->item( 0 );
        $installList = $installNode->getElementsByTagName( 'item' );
        $uninstallNode = $root->getElementsByTagName( 'uninstall' )->item( 0 );
        $uninstallList = $uninstallNode->getElementsByTagName( 'item' );
        $this->parseInstallTree( $installList, true );
        $this->parseInstallTree( $uninstallList, false );

        $installDataList = $root->getElementsByTagName( 'install-data' );
        if ( $installDataList )
        {
            $this->InstallData = array();
            foreach( $installDataList as $installDataNode )
            {
                if ( is_object( $installDataNode ) &&
                     $installDataNode->getAttribute( 'name' ) == 'data' )
                {
                    $installDataType = $installDataNode->getAttribute( 'type' );
                    $installDataElements = $installDataNode->getElementsByTagName( 'data' );
                    $installData = array();
                    foreach ( $installDataElements as $installDataElement )
                    {
                        if ( $installDataElement->attribute( 'name' ) == 'element' )
                        {
                            $name = $installDataElement->getAttribute( 'name' );
                            $value = $installDataElement->getAttribute( 'value' );
                            $installData[$name] = $value;
                        }
                        else if ( $installDataElement->attribute( 'name' ) == 'array' )
                        {
                            $arrayName = $installDataElement->getAttribute( 'name' );
                            $installDataElementArray = $installDataElement->childNodes;
                            $array = array();
                            foreach ( $installDataElementArray as $installDataElementArrayElement )
                            {
                                $name = $installDataElementArrayElement->getAttribute( 'name' );
                                $value = $installDataElementArrayElement->getAttribute( 'value' );
                                $array[$name] = $value;
                            }
                            $installData[$arrayName] = $array;
                        }
                    }
                    if ( count( $installData ) > 0 )
                        $this->InstallData[$installDataType] = $installData;
                }
            }
        }

        $retValue = true;
        return $retValue;
    }

    /*!
     \private
    */
    function parseDependencyTree( $dependenciesList, $dependencySection )
    {
        foreach ( $dependenciesList as $dependencyNode )
        {
            $dependencyType = $dependencyNode->getAttribute( 'type' );
            $dependencyAttributes = $dependencyNode->attributes;
            $dependencyParameters = array();
            for ( $i = 0; $i < $dependencyAttributes->length; $i++ )
            {
                $dependencyAttribute = $dependencyAttributes->item( $i );
                $dependencyParameters[$dependencyAttribute->name] = $dependencyAttribute->value;
            }

            $additionalDependencyParameters = array();
            $handler = $this->packageHandler( $dependencyType );
            if ( $handler )
            {
                $handler->parseDependencyNode( $this, $dependencyNode, $additionalDependencyParameters, $dependencySection );
            }

            if ( count( $additionalDependencyParameters ) > 0 )
                $dependencyParameters = array_merge( $dependencyParameters, $additionalDependencyParameters );

            $this->appendDependency( $dependencySection, $dependencyParameters );
        }
    }

    /*!
     \private
    */
    function parseInstallTree( $installList, $isInstall )
    {
        foreach( $installList as $installNode )
        {
            $installType = $installNode->getAttribute( 'type' );
            $installName = $installNode->getAttribute( 'name' );
            $installFilename = $installNode->getAttribute( 'filename' );
            $installSubdirectory = $installNode->getAttribute( 'sub-directory' );
            $installOS = $installNode->getAttribute( 'os' );

            $handler = $this->packageHandler( $installType );
            $installParameters = array();
            if ( $handler )
            {
                $handler->parseInstallNode( $this, $installNode, $installParameters, $isInstall );
            }
            if ( count( $installParameters ) == 0 )
                $installParameters = false;

            $this->appendInstall( $installType, $installName, $installOS, $isInstall,
                                  $installFilename, $installSubdirectory,
                                  $installParameters );
        }
    }

    /*!
     \return the dom document of the package.
    */
    function &domStructure()
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $dom->formatOutput = true;
        $root = $dom->createElement( 'package' );
        $root->setAttribute( 'version', eZPackage::VERSION );
        $root->setAttribute( 'development', ( eZPackage::DEVELOPMENT ? 'true' : 'false' ) );
        $dom->appendChild( $root );

        if ( eZPackage::DEVELOPMENT )
        {
            $warningText = "This format was made with a development version and will not work with any release versions.\n" .
                           "The format of this file is also subject to change until the release version.\n" .
                           "Upgrades to the development format will not be supported.";

            $warningNode = $dom->createElement( 'warning' );
            $warningNode->appendChild( $dom->createTextNode( $warningText ) );
            $root->appendChild( $warningNode );
        }

        $name = $this->attribute( 'name' );
        $summary = $this->attribute( 'summary' );
        $description = $this->attribute( 'description' );
        $priority = $this->attribute( 'priority' );
        $type = $this->attribute( 'type' );
        $extension = $this->attribute( 'extension' );
        $installType = $this->attribute( 'install_type' );
        $vendorName = $this->attribute( 'vendor' );
        $source = $this->attribute( 'source' );

        $ezpublishVersion = $this->attribute( 'ezpublish-version' );
        $ezpublishNamedVersion = $this->attribute( 'ezpublish-named-version' );

        $packagingTimestamp = $this->attribute( 'packaging-timestamp' );
        $packagingHost = $this->attribute( 'packaging-host' );
        $packagingPackager = $this->attribute( 'packaging-packager' );

        $maintainers = $this->attribute( 'maintainers' );
        $documents = $this->attribute( 'documents' );
        $groups = $this->attribute( 'groups' );

        $versionNumber = $this->attribute( 'version-number' );
        $releaseNumber = $this->attribute( 'release-number' );
        $releaseTimestamp = $this->attribute( 'release-timestamp' );

        $licence = $this->attribute( 'licence' );
        $state = $this->attribute( 'state' );

        $simpleFileList = $this->attribute( 'simple-file-list' );
        $fileList = $this->attribute( 'file-list' );
        $dependencies = $this->attribute( 'dependencies' );
        $install = $this->attribute( 'install' );
        $uninstall = $this->attribute( 'uninstall' );
        $changelog = $this->attribute( 'changelog' );

        $rootNameTextNode = $dom->createElement( 'name' );
        $rootNameTextNode->appendChild( $dom->createTextNode( $name ) );
        $root->appendChild( $rootNameTextNode );

        if ( $summary )
        {
            $rootSummaryTextNode = $dom->createElement( 'summary' );
            $rootSummaryTextNode->appendChild( $dom->createTextNode( $summary ) );
            $root->appendChild( $rootSummaryTextNode );
        }

        if ( $description )
        {
            $rootDescriptionTextNode = $dom->createElement( 'description' );
            $rootDescriptionTextNode->appendChild( $dom->createTextNode( $description ) );
            $root->appendChild( $rootDescriptionTextNode );
        }

        if ( $vendorName )
        {
            $rootVendorTextNode = $dom->createElement( 'vendor' );
            $rootVendorTextNode->appendChild( $dom->createTextNode( $vendorName ) );
            $root->appendChild( $rootVendorTextNode );
        }

        if ( $priority )
        {
            $rootPriorityTextNode = $dom->createElement( 'priority' );
            $rootPriorityTextNode->setAttribute( 'value', $priority );
            $root->appendChild( $rootPriorityTextNode );
        }

        if ( $type )
        {
            $rootTypeTextNode = $dom->createElement( 'type' );
            $rootTypeTextNode->setAttribute( 'value', $type );
            $root->appendChild( $rootTypeTextNode );
        }

        if ( $extension )
        {
            $rootExtensionTextNode = $dom->createElement( 'extension' );
            $rootExtensionTextNode->setAttribute( 'name', $extension );
            $root->appendChild( $rootExtensionTextNode );
        }

        if ( $source )
        {
            $rootSourceTextNode = $dom->createElement( 'source' );
            $rootSourceTextNode->appendChild( $dom->createTextNode( $source ) );
            $root->appendChild( $rootSourceTextNode );
        }

        $root->setAttribute( 'install_type', $installType );

        $ezpublishNode = $dom->createElement( 'ezpublish' );

        $ezpublishVersionTextNode = $dom->createElement( 'version' );
        $ezpublishVersionTextNode->appendChild( $dom->createTextNode( $ezpublishVersion ) );
        $ezpublishNode->appendChild( $ezpublishVersionTextNode );

        $ezpublishNamedVersionTextNode = $dom->createElement( 'named-version' );
        $ezpublishNamedVersionTextNode->appendChild( $dom->createTextNode( $ezpublishNamedVersion ) );
        $ezpublishNode->appendChild( $ezpublishNamedVersionTextNode );

        $root->appendChild( $ezpublishNode );

        if ( count( $maintainers ) > 0 )
        {
            $maintainersNode = $dom->createElement( 'maintainers' );
            foreach ( $maintainers as $maintainer )
            {
                unset( $maintainerNode );
                $maintainerNode = $dom->createElement( 'maintainer' );

                unset( $maintainerName );
                $maintainerName = $dom->createElement( 'name' );
                $maintainerName->appendChild( $dom->createTextNode( $maintainer['name'] ) );
                $maintainerNode->appendChild( $maintainerName );

                unset( $maintainerEmail );
                $maintainerEmail = $dom->createElement( 'email' );
                $maintainerEmail->appendChild( $dom->createTextNode( $maintainer['email'] ) );
                $maintainerNode->appendChild( $maintainerEmail );
                if ( $maintainer['role'] )
                {
                    unset( $maintainerRole );
                    $maintainerRole = $dom->createElement( 'role' );
                    $maintainerRole->appendChild( $dom->createTextNode( $maintainer['role'] ) );
                    $maintainerNode->appendChild( $maintainerRole );
                }

                $maintainersNode->appendChild( $maintainerNode );
            }
            $root->appendChild( $maintainersNode );
        }

        $packagingNode = $dom->createElement( 'packaging' );

        $packagingTimestampNode = $dom->createElement( 'timestamp' );
        $packagingTimestampNode->appendChild( $dom->createTextNode( $packagingTimestamp ) );
        $packagingNode->appendChild( $packagingTimestampNode );

        $packagingHostNode = $dom->createElement( 'host' );
        $packagingHostNode->appendChild( $dom->createTextNode( $packagingHost ) );
        $packagingNode->appendChild( $packagingHostNode );
        if ( $packagingPackager )
        {
            $packagingPackagerNode = $dom->createElement( 'packager' );
            $packagingPackagerNode->appendChild( $dom->createTextNode( $packagingPackager ) );
            $packagingNode->appendChild( $packagingPackagerNode );
        }

        $root->appendChild( $packagingNode );

        if ( count( $documents ) > 0 )
        {
            $documentsNode = $dom->createElement( 'documents' );
            foreach ( $documents as $document )
            {
                unset( $documentNode );
                $documentNode = $dom->createElement( 'document' );
                $documentNode->setAttribute( 'mime-type', $document['mime-type'] );
                $documentNode->setAttribute( 'name', $document['name'] );
                if ( $document['os'] )
                    $documentNode->setAttribute( 'os', $document['os'] );

                if ( $document['audience'] )
                    $documentNode->setAttribute( 'audience', $document['audience'] );

                $documentsNode->appendChild( $documentNode );
            }
            $root->appendChild( $documentsNode );
        }

        if ( count( $groups ) > 0 )
        {
            $groupsNode = $dom->createElement( 'groups' );
            foreach ( $groups as $group )
            {
                unset( $groupNode );
                $groupNode = $dom->createElement( 'group' );
                $groupNode->setAttribute( 'name', $group['name'] );
                $groupsNode->appendChild( $groupNode );
            }
            $root->appendChild( $groupsNode );
        }

        if ( count( $changelog ) > 0 )
        {
            $changelogNode = $dom->createElement( 'changelog' );
            foreach ( $changelog as $changeEntry )
            {
                unset( $changeEntryNode );
                $changeEntryNode = $dom->createElement( 'entry' );
                $changeEntryNode->setAttribute( 'timestamp', $changeEntry['timestamp'] );
                $changeEntryNode->setAttribute( 'person', $changeEntry['person'] );
                $changeEntryNode->setAttribute( 'email', $changeEntry['email'] );
                $changeEntryNode->setAttribute( 'release', $changeEntry['release'] );

                foreach ( $changeEntry['changes'] as $change )
                {
                    unset( $changeEntryChange );
                    $changeEntryChange = $dom->createElement( 'change' );
                    $changeEntryChange->appendChild( $dom->createTextNode( $change ) );
                    $changeEntryNode->appendChild( $changeEntryChange );
                }
                $changelogNode->appendChild( $changeEntryNode );
            }
            $root->appendChild( $changelogNode );
        }

        // Avoid a PHP warning if 'simple-file-list' is not an array
        if ( is_array( $this->Parameters['simple-file-list'] ) )
        {
            $rootSimpleFiles = $dom->createElement( 'simple-files' );
            foreach( $this->Parameters['simple-file-list'] as $key => $value )
            {
                $simpleFileNode = $dom->createElement( 'simple-file' );
                $simpleFileNode->setAttribute( 'key', $key );
                $simpleFileNode->setAttribute( 'original-path', $value['original-path'] );
                $simpleFileNode->setAttribute( 'package-path', $value['package-path'] );
                $rootSimpleFiles->appendChild( $simpleFileNode );
                unset( $simpleFileNode );
            }
            $root->appendChild( $rootSimpleFiles );
        }
        else
        {
            $rootSimpleFiles = $dom->createElement( 'simple-files' );
            $root->appendChild( $rootSimpleFiles );
        }

        // Handle files
        $filesNode = $dom->createElement( 'files' );

        $hasFileItems = false;
        foreach ( $fileList as $fileCollectionName => $fileCollection )
        {
            if ( count( $fileCollection ) > 0 )
            {
                $hasFileItems = true;

                unset( $fileCollectionNode );
                $fileCollectionNode = $dom->createElement( 'collection' );
                $fileCollectionNode->setAttribute( 'name', $fileCollectionName );

                unset( $fileLists );
                unset( $fileDesignLists );
                unset( $fileThumbnailLists );
                $fileList = array();
                $fileDesignList = array();
                $fileINIList = array();
                $fileThumbnailList = array();
                $fileListNode = null;
                foreach ( $fileCollection as $fileItem )
                {
                    if ( $fileItem['type'] == 'design' )
                        $fileListNode =& $fileDesignLists[$fileItem['design']][$fileItem['role']][$fileItem['role-value']][$fileItem['variable-name']];
                    else if ( $fileItem['type'] == 'ini' )
                        $fileListNode =& $fileINILists[$fileItem['role']][$fileItem['role-value']][$fileItem['variable-name']];
                    else if ( $fileItem['type'] == 'thumbnail' )
                        $fileListNode =& $fileThumbnailLists[$fileItem['role']];
                    else
                        $fileListNode =& $fileLists[$fileItem['type']][$fileItem['role']][$fileItem['role-value']][$fileItem['variable-name']];

                    if ( !$fileListNode ||
                         $fileListNode->getAttribute( 'type' ) != $fileItem['type'] ||
                         $fileListNode->getAttribute( 'role' ) != $fileItem['role'] ||
                         $fileListNode->getAttribute( 'role-value' ) != $fileItem['role-value'] ||
                         $fileListNode->getAttribute( 'variable-name' ) != $fileItem['variable-name'] )
                    {
                        unset( $fileListNode );
                        $fileListNode = $dom->createElement( 'file-list' );
                        $fileListNode->setAttribute( 'type', $fileItem['type'] );

                        if ( $fileItem['type'] == 'design' )
                            $fileListNode->setAttribute( 'design', $fileItem['design'] );
                        if ( $fileItem['role'] )
                            $fileListNode->setAttribute( 'role', $fileItem['role'] );
                        if ( $fileItem['role-value'] )
                            $fileListNode->setAttribute( 'role-value', $fileItem['role-value'] );
                        if ( $fileItem['variable-name'] )
                            $fileListNode->setAttribute( 'variable-name', $fileItem['variable-name'] );

                        $fileCollectionNode->appendChild( $fileListNode );
                    }

                    if ( $fileItem['name'] )
                    {
                        unset( $fileListFile );
                        $fileListFile = $dom->createElement( 'file' );
                        $fileListFile->setAttribute( 'path', $fileItem['path'] );
                        $fileListFile->setAttribute( 'name', $fileItem['name'] );

                        if ( $fileItem['md5'] )
                            $fileListFile->setAttribute( 'md5sum', $fileItem['md5'] );

                        if ( $fileItem['subdirectory'] )
                            $fileListFile->setAttribute( 'sub-directory', $fileItem['subdirectory'] );

                        $fileListNode->appendChild( $fileListFile );
                    }
                }
                $filesNode->appendChild( $fileCollectionNode );
            }
        }
        if ( $hasFileItems )
            $root->appendChild( $filesNode );

        $versionNode = $dom->createElement( 'version' );
        $versionNumberTextNode = $dom->createElement( 'number' );
        $versionNumberTextNode->appendChild( $dom->createTextNode( $versionNumber ) );
        $versionNode->appendChild( $versionNumberTextNode );
        $versionReleaseNumberTextNode = $dom->createElement( 'release' );
        $versionReleaseNumberTextNode->appendChild( $dom->createTextNode( $releaseNumber ) );
        $versionNode->appendChild( $versionReleaseNumberTextNode );
        $root->appendChild( $versionNode );

        if ( $releaseTimestamp )
        {
            $rootTimestampTextNode = $dom->createElement( 'timestamp' );
            $rootTimestampTextNode->appendChild( $dom->createTextNode( $releaseTimestamp ) );
            $root->appendChild( $rootTimestampTextNode );
        }

        if ( $licence )
        {
            $rootLicenceTextNode = $dom->createElement( 'licence' );
            $rootLicenceTextNode->appendChild( $dom->createTextNode( $licence ) );
            $root->appendChild( $rootLicenceTextNode );
        }

        if ( $state )
        {
            $rootStateTextNode = $dom->createElement( 'state' );
            $rootStateTextNode->appendChild( $dom->createTextNode( $state ) );
            $root->appendChild( $rootStateTextNode );
        }

        $dependencyNode = $dom->createElement( 'dependencies' );

        $providesNode = $dom->createElement( 'provides' );
        $dependencyNode->appendChild( $providesNode );
        $requiresNode = $dom->createElement( 'requires' );
        $dependencyNode->appendChild( $requiresNode );
        $obsoletesNode = $dom->createElement( 'obsoletes' );
        $dependencyNode->appendChild( $obsoletesNode );
        $conflictsNode = $dom->createElement( 'conflicts' );
        $dependencyNode->appendChild( $conflictsNode );

        $this->createDependencyTree( $providesNode, 'provide', $dependencies['provides'] );
        $this->createDependencyTree( $requiresNode, 'require', $dependencies['requires'] );
        $this->createDependencyTree( $obsoletesNode, 'obsolete', $dependencies['obsoletes'] );
        $this->createDependencyTree( $conflictsNode, 'conflict', $dependencies['conflicts'] );

        $root->appendChild( $dependencyNode );

        $installNode = $dom->createElement( 'install' );

        $uninstallNode = $dom->createElement( 'uninstall' );

        $this->createInstallTree( $installNode, $dom, $install, 'install' );
        $this->createInstallTree( $uninstallNode, $dom, $uninstall, 'uninstall' );

        $root->appendChild( $installNode );
        $root->appendChild( $uninstallNode );

        if ( count( $this->InstallData ) > 0 )
        {
            $installDataNode = $dom->createElement( 'install-data' );
            foreach ( $this->InstallData as $installDataType => $installData )
            {
                if ( count( $installData ) > 0 )
                {
                    unset( $dataNode );
                    $dataNode = $dom->createElement( 'data' );
                    $dataNode->setAttribute( 'type', $installDataType );
                    $installDataNode->appendChild( $dataNode );
                    foreach ( $installData as $installDataName => $installDataValue )
                    {
                        if ( is_array( $installDataValue ) )
                        {
                            unset( $dataArrayNode );
                            $dataArrayNode = $dom->createElement( 'array' );
                            $dataArrayNode->setAttribute( 'name', $installDataName );
                            $dataNode->appendChild( $dataArrayNode );
                            foreach ( $installDataValue as $installDataValueName => $installDataValueValue )
                            {
                                unset( $dataArrayElement );
                                $dataArrayElement = $dom->createElement( 'element' );
                                $dataArrayElement->setAttribute( 'name', $installDataValueName );
                                $dataArrayElement->setAttribute( 'value', $installDataValueValue );
                                $dataArrayNode->appendChild( $dataArrayElement );
                            }
                        }
                        else
                        {
                            unset( $dataArrayElement );
                            $dataArrayElement = $dom->createElement( 'element' );
                            $dataArrayElement->setAttribute( 'name', $installDataName );
                            $dataArrayElement->setAttribute( 'value', $installDataValue );
                            $dataNode->appendChild( $dataArrayElement );
                        }
                    }
                }
            }
            $root->appendChild( $installDataNode );
        }

        return $dom;
    }

    /*!
     \private
     Creates xml elements as children of the main node \a $installNode.
     The install elements are taken from \a $list.
     \param $installType Is either \c 'install' or \c 'uninstall'
    */
    function createInstallTree( $installNode, $dom, $list, $installType )
    {
        foreach ( $list as $installItem )
        {
            $type = $installItem['type'];
            unset( $installItemNode );
            $installItemNode = $dom->createElement( 'item' );
            $installItemNode->setAttribute( 'type', $type );
            $installNode->appendChild( $installItemNode );

            if ( $installItem['os'] )
                $installItemNode->setAttribute( 'os', $installIItem['os'] );

            if ( $installItem['name'] )
                $installItemNode->setAttribute( 'name', $installIItem['name'] );

            if ( $installItem['filename'] )
            {
                $installItemNode->setAttribute( 'filename', $installItem['filename'] );

                if ( $installItem['sub-directory'] )
                    $installItemNode->setAttribute( 'sub-directory', $installItem['sub-directory'] );
            }
            else if ( isset( $installItem['content'] ) && $installItem['content'] instanceof DOMElement )
            {
                $importedContentNode = $installItemNode->ownerDocument->importNode( $installItem['content'], true );
                $installItemNode->appendChild( $importedContentNode );
            }

            $handler = $this->packageHandler( $type );
            if ( $handler )
            {
                $handler->createInstallNode( $this, $installItemNode, $installItem, $installType );
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
        $dom = $dependenciesNode->ownerDocument;

        foreach ( $list as $dependencyItem )
        {
            unset( $dependencyNode );
            $dependencyNode = $dom->createElement( $dependencyType );
            $dependencyNode->setAttribute( 'type', $dependencyItem['type'] );
            $dependencyNode->setAttribute( 'name', $dependencyItem['name'] );
            if ( $dependencyItem['value'] )
                $dependencyNode->setAttribute( 'value', $dependencyItem['value'] );
            $dependenciesNode->appendChild( $dependencyNode );
            $handler = $this->packageHandler( $dependencyItem['name'] );
            if ( $handler )
            {
                $handler->createDependencyNode( $this, $dependencyNode, $dependencyItem, $dependencyType );
            }
        }
    }

    /*!
     \return the package handler object for the handler named \a $handlerName.
    */
    static function packageHandler( $handlerName )
    {
        if ( !isset( $GLOBALS['eZPackageHandlers'] ) )
        {
            $handlers = array();
        }
        else
        {
            $handlers = $GLOBALS['eZPackageHandlers'];
        }
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
            $handlerFile = $result['found-file-path'];
            if ( file_exists( $handlerFile ) )
            {
                include_once( $handlerFile );
                $handlerClassName = $result['type'] . 'PackageHandler';
                if ( isset( $handlers[$result['type']] ) )
                {
                    $handler = $handlers[$result['type']];
                    $handler->reset();
                }
                else
                {
                    $handler = new $handlerClassName;
                    $handlers[$result['type']] = $handler;
                }
            }
        }
        $GLOBALS['eZPackageHandlers'] = $handlers;
        return $handler;
    }

    /*!
     Append File to package assosiated with key. The file will be available during installation using the same key.

     \param key
     \param file path
    */
    function appendSimpleFile( $key, $filepath )
    {
        if ( !isset( $this->Parameters['simple-file-list'] ) )
        {
            $this->Parameters['simple-file-list'] = array();
        }

        $suffix = eZFile::suffix( $filepath );
        //$sourcePath = $fileInfo['original-path'];
        $packagePath = eZPackage::simpleFilesDirectory() . '/' . substr( md5( mt_rand() ), 0, 8 ) . '.' . $suffix;
        $destinationPath = $this->path() . '/' . $packagePath;
        eZDir::mkdir( eZDir::dirpath( $destinationPath ), false, true );

       //SP DBfile
        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileFetch( $filepath );

        eZFileHandler::copy( $filepath, $destinationPath );

        $this->Parameters['simple-file-list'][$key] = array( 'original-path' => $filepath,
                                                             'package-path' => $packagePath );
    }

    /*!
     Get complete path to file by file key

     \param file key

     \return complete file path
    */
    function simpleFilePath( $fileKey )
    {
        if ( !isset( $this->Parameters['simple-file-list'] ) )
        {
            return false;
        }
        if ( !isset( $this->Parameters['simple-file-list'][$fileKey] ) )
        {
            return false;
        }

        return $this->path() . '/' . $this->Parameters['simple-file-list'][$fileKey]['package-path'];
    }

    /**
     * Returns package version.
     *
     * Combines package version number and release number.
     *
     * \static
     * \return Package version (string).
    */
    function getVersion()
    {
        return $this->Parameters['version-number'] . '-' . $this->Parameters['release-number'];
    }

    /*!
     Sets installed/uninstalled state of the package
     \param installed
    */
    function setInstalled( $installed = true )
    {
        if ( $this->Parameters['install_type'] != 'install' )
            return;

        $name = $this->Parameters['name'];
        $version = $this->getVersion();
        $db = eZDB::instance();
        if ( $installed )
        {
            if ( !$this->getInstallState() )
            {
                $timestamp = time();
                $db->query( "INSERT INTO ezpackage ( name, version, install_date ) VALUES ( '$name', '$version', '$timestamp' )" );
                $this->isInstalled = true;
            }
        }
        else
        {
            $db->query( "DELETE FROM ezpackage WHERE name='$name' AND version='$version'" );
            $this->isInstalled = false;
        }
    }

    function isInstalled()
    {
        return $this->isInstalled;
    }

    function getInstallState()
    {
        // TODO installation date

        if ( $this->Parameters['install_type'] != 'install' )
            return;

        $name = $this->Parameters['name'];
        $version = $this->getVersion();

        $db = eZDB::instance();
        $result = $db->arrayQuery( "SELECT count(*) AS count FROM ezpackage WHERE name='$name' AND version='$version'" );

        if ( !count( $result ) )
            return false;
        $installed = $result[0]['count'] == '0' ? false : true;
        $this->isInstalled = $installed;
        return $installed;
    }

    /*!
     \static
     Fetch info about languages for packages specified in $packageNameList.
     Return ex:  array( 'eng-GB', 'rus-RU', ... );
                 if $withLanguageNames == true
                    array( 'eng-GB' => "English",
                           'rus-RU' => "Russian",
                           ... );
    */
    static function languageInfoFromPackageList( $packageNameList, $withLanguageNames = false )
    {
        $languageInfo = array();
        foreach( $packageNameList as $packageName )
        {
            $package = eZPackage::fetch( $packageName, false, false, false );
            if( is_object( $package ) )
            {
                $packageLanguageInfo = $package->languageInfo( $withLanguageNames );
                // merge arrays
                if( $withLanguageNames )
                {
                    // we have array like 'locale' => 'name'. can use array_merge
                    $languageInfo = array_merge( $languageInfo, $packageLanguageInfo );
                }
                else
                {
                    foreach( $packageLanguageInfo as $languageLocale )
                    {
                        if( !in_array( $languageLocale, $languageInfo ) )
                        {
                            $languageInfo[] = $languageLocale;
                        }
                    }
                }
            }
            else
            {
                eZDebug::writeWarning( "Unable to fetch package '$packageName'", 'eZPackage::languageInfoFromPackageList' );
            }
        }

        return $languageInfo;
    }

    /*!
     Fetch info about languages for package.
    */
    function languageInfo( $withLanguageNames = false )
    {
        $langaugeInfo = array();

        $classHandler  = eZPackage::packageHandler( 'ezcontentclass' );
        $objectHandler = eZPackage::packageHandler( 'ezcontentobject' );

        $explainClassInfo = array( 'language_info' );

        $packageItems = $this->installItemsList();
        foreach( $packageItems as $item )
        {
            $itemLanguageInfo = array();

            if( $item['type'] == 'ezcontentclass' )
            {
                $classInfo = $classHandler->explainInstallItem( $this, $item, $explainClassInfo );
                $itemLanguageInfo = isset( $classInfo['language_info'] ) ? $classInfo['language_info'] : array();
            }
            else if( $item['type'] == 'ezcontentobject' )
            {
                $objectsInfo = $objectHandler->explainInstallItem( $this, $item );

                // merge objects info
                foreach( $objectsInfo as $objectInfo )
                {
                    $objectLanguages = isset( $objectInfo['language_info'] ) ? $objectInfo['language_info'] : array();
                    foreach( $objectLanguages as $objectLanguage )
                    {
                        if( !in_array( $objectLanguage, $itemLanguageInfo ) )
                        {
                            $itemLanguageInfo[] = $objectLanguage;
                        }
                    }
                }
            }

            // merge class and objects infos
            foreach( $itemLanguageInfo as $languageLocale )
            {
                if( !in_array( $languageLocale, $langaugeInfo ) )
                {
                    $langaugeInfo[] = $languageLocale;
                }
            }
        }

        if( $withLanguageNames )
        {
            $langaugeInfoWithNames = array();
            foreach( $langaugeInfo as $languageLocale )
            {
                $language = eZContentLanguage::fetchByLocale( $languageLocale );
                $languageName = $language->attribute( 'name' );
                $langaugeInfoWithNames[$languageLocale] = $languageName;
            }

            $langaugeInfo = $langaugeInfoWithNames;
        }

        return $langaugeInfo;
    }

    function defaultLanguageMap()
    {
        $languageMap = array();
        $packageLanguages = $this->languageInfo();
        foreach( $packageLanguages as $language )
        {
            $languageMap[$language] = $language;
        }

        return $languageMap;
    }

    public $isInstalled = false;
    /// \privatesection
    /// All interal data
    public $Parameters;
    /// Controls which data has been modified
}

?>
