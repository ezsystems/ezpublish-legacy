<?php
//
// Definition of eZPackage class
//
// Created on: <23-Jul-2003 12:34:55 amos>
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

/*! \file ezpackagehandler.php
*/

/*!
  \group package The package manager system
  \ingroup package
  \class eZPackage ezpackagehandler.php
  \brief Maintains eZ publish packages

*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'lib/ezfile/classes/ezfile.php' );
include_once( 'lib/ezfile/classes/ezdir.php' );
include_once( 'lib/ezfile/classes/ezfilehandler.php' );

define( 'EZ_PACKAGE_VERSION', '3.4.0alpha1' );
define( 'EZ_PACKAGE_DEVELOPMENT', true );
define( 'EZ_PACKAGE_USE_CACHE', true );
define( 'EZ_PACKAGE_CACHE_CODEDATE', 1069339607 );

define( 'EZ_PACKAGE_STATUS_ALREADY_EXISTS', 1 );

class eZPackage
{
    /*!
     Constructor
    */
    function eZPackage( $parameters = array(), $modifiedParameters = array(),
                        $repositoryPath = false )
    {
        $this->setParameters( $parameters, $modifiedParameters );
        if ( !$repositoryPath )
            $repositoryPath = eZPackage::repositoryPath();
        $this->RepositoryPath = $repositoryPath;
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
                           'development' => EZ_PACKAGE_DEVELOPMENT,
                           'summary' => false,
                           'description' => false,
                           'vendor' => false,
                           'priority' => false,
                           'type' => false,
                           'extension' => false,
                           'is_installed' => false,
                           'is_active' => true,
                           'install_type' => 'install',
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
        $this->PolicyCache = array();
        $this->InstallData = array();
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

    /*!
     \static
     \return An associative array with the possible types for a package.
             Each entry contains an \c id and a \c name key.
    */
    function typeList()
    {
        $typeList =& $GLOBALS['eZPackageTypeList'];
        if ( !isset( $typeList ) )
        {
            $typeList = array();
            $ini =& eZINI::instance( 'package.ini' );
            $types =& $ini->variable( 'PackageSettings', 'TypeList' );
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
    function stateList()
    {
        $stateList =& $GLOBALS['eZPackageStateList'];
        if ( !isset( $stateList ) )
        {
            $stateList = array();
            $ini =& eZINI::instance( 'package.ini' );
            $states =& $ini->variable( 'PackageSettings', 'StateList' );
            foreach ( $states as $stateID => $stateName )
            {
                $stateList[] = array( 'name' => $stateName,
                                      'id' => $stateID);
            }
        }
        return $stateList;
    }

    function &create( $name, $parameters = array(), $repositoryPath = false )
    {
        $parameters['name'] = $name;
        $handler =& new eZPackage( $parameters, $parameters, $repositoryPath );
        return $handler;
    }

    function resetModification()
    {
        $this->ModifiedParameters = array();
    }

    /*!
     \return the attribuets for this package.
    */
    function attributes()
    {
        return array_merge( array( 'development',
                                   'name', 'summary', 'description',
                                   'vendor', 'priority', 'type',
                                   'extension', 'source',
                                   'version-number', 'release-number', 'release-timestamp',
                                   'maintainers', 'documents', 'groups',
                                   'file-list', 'file-count',
                                   'can_read', 'can_export', 'can_import', 'can_install',
                                   'changelog', 'dependencies',
                                   'is_installed', 'is_active', 'install_type',
                                   'thumbnail-list',
                                   'install', 'uninstall',
                                   'licence', 'state',
                                   'ezpublish-version', 'ezpublish-named-version', 'packaging-timestamp',
                                   'packaging-host', 'packaging-packager' ) );
    }

    /*!
     Sets the attribute named \a $attributeName to have the value \a $attributeValue.
    */
    function setAttribute( $attributeName, $attributeValue )
    {
        if ( !in_array( $attributeName,
                        array( 'development',
                               'name', 'summary', 'description',
                               'vendor', 'priority', 'type',
                               'install_type', 'is_active',
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
                         array( 'development',
                                'name', 'summary', 'description',
                                'vendor', 'priority', 'type',
                                'extension', 'source',
                                'version-number', 'release-number', 'release-timestamp',
                                'maintainers', 'documents', 'groups',
                                'file-list', 'file-count',
                                'can_read', 'can_export', 'can_import', 'can_install',
                                'changelog', 'dependencies',
                                'is_installed', 'is_active', 'install_type',
                                'thumbnail-list',
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
                       array( 'development',
                              'name', 'summary', 'description',
                              'vendor', 'priority', 'type',
                              'extension', 'source',
                              'version-number', 'release-number', 'release-timestamp',
                              'maintainers', 'documents', 'groups',
                              'file-list',
                              'changelog', 'dependencies',
                              'install', 'uninstall',
                              'is_installed', 'is_active', 'install_type',
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
        else if ( $attributeName == 'can_read' )
            return $this->canRead();
        else if ( $attributeName == 'can_export' )
            return $this->canExport();
        else if ( $attributeName == 'can_import' )
            return $this->canImport();
        else if ( $attributeName == 'can_install' )
            return $this->canInstall();
        else if ( $attributeName == 'file-count' )
            return $this->fileCount();
        else if ( $attributeName == 'thumbnail-list' )
            return $this->thumbnailList( 'default' );

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

    function canUsePolicyFunction( $functionName )
    {
		include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
		$currentUser =& eZUser::currentUser();
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
        $canUse =& $this->PolicyCache[$functionName];
        if ( !isset( $canUse ) )
        {
			include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
			$currentUser =& eZUser::currentUser();
			$accessResult = $currentUser->hasAccessTo( 'package', $functionName );
		    $limitationList = array();
		    $canUse = false;
			if ( $accessResult['accessWord'] == 'yes' )
			{
		    $canUse = true;
			}
			else if ( $accessResult['accessWord'] == 'limited' )
			{
				$allRoles = array();
			    $limitation =& $accessResult['policies'];
			    foreach ( array_keys( $limitation ) as $key )
			    {
			        $policy =& $limitation[$key];
			        $limitationList[] =& $policy->attribute( 'limitations' );
			    }
				$typeList = false;
	            foreach( $limitationList as $limitationArray )
	            {
	                foreach ( $limitationArray as $limitation )
	                {
	                    if ( $limitation->attribute( 'identifier' ) == 'Type' )
	                    {
	                        if ( !is_array( $typeList ) )
	                            $typeList = array();
	                        $typeList = array_merge( $typeList, $limitation->attribute( 'values_as_array' ) );
	                    }
					}
				}
				if ( $typeList === false )
				{
				    $canUse = true;
				}
				else
				{
				    $canUse = in_array( $this->attribute( 'type' ), $typeList );
				}
			}
        }
        return $canUse;
    }

	function fetchMaintainerRoleIDList( $packageType = false, $checkRoles = false )
	{
		$allRoles = false;
		if ( $checkRoles )
		{
			include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
			$currentUser =& eZUser::currentUser();
			$accessResult = $currentUser->hasAccessTo( 'package', 'create' );
		    $limitationList = array();
			if ( $accessResult['accessWord'] == 'limited' )
			{
				$allRoles = array();
			    $limitation =& $accessResult['policies'];
			    foreach ( array_keys( $limitation ) as $key )
			    {
			        $policy =& $limitation[$key];
			        $limitationList[] =& $policy->attribute( 'limitations' );
			    }
	            foreach( $limitationList as $limitationArray )
	            {
					$allowedType = true;
					$allowedRoles = false;
	                foreach ( $limitationArray as $limitation )
	                {
	                    if ( $limitation->attribute( 'identifier' ) == 'Role' )
	                    {
	                        $allowedRoles = $limitation->attribute( 'values_as_array' );
	                    }
	                    else if ( $limitation->attribute( 'identifier' ) == 'Type' )
	                    {
	                        $typeList = $limitation->attribute( 'values_as_array' );
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
		$ini =& eZINI::instance( 'package.ini' );
		$roleList = $ini->variable( 'MaintainerSettings', 'RoleList' );
		if ( $allRoles !== false )
		{
			$roleList = array_intersect( $roleList, $allRoles );
		}
		return $roleList;
	}

	function fetchMaintainerRoleList( $packageType = false, $checkRoles = false )
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

	function maintainerRoleListForRoles()
	{
		$ini =& eZINI::instance( 'package.ini' );
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

	function maintainerRoleName( $roleID )
	{
		$nameMap = array( 'lead' => 'Lead',
						  'developer' => 'Developer',
						  'designer' => 'Designer',
						  'contributor' => 'Contributor',
						  'tester' => 'Tester' );
		if ( isset( $nameMap[$roleID] ) )
			return $nameMap[$roleID];
		return false;
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
        {
            if ( file_exists( $file ) )
            {
                return md5_file( $file );
            }
            else
                eZDebug::writeError( "Could not open file $file for md5sum calculation" );
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
            $path = $this->currentRepositoryPath();
        $typeDir = $fileItem['type'];
        if ( $fileItem['type'] == 'design' )
            $typeDir .= '.' . $fileItem['design'];
        if ( $fileItem['role'] )
        {
            $typeDir .= '.' . $fileItem['role'];
            if ( $fileItem['role-value'] )
                $typeDir .= '-' . $fileItem['role-value'];
        }
        $path .= '/' . $this->attribute( 'name' ) . '/' . eZPackage::filesDirectory() . '/' . $collectionName . '/' . $typeDir;
        if ( $fileItem['subdirectory'] )
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

    function dependencyOperatorText( $dependencyItem )
    {
        return '=';
    }

    function createDependencyText( &$cli, $dependencyItem, $dependencySection )
    {
        $text = ( $cli->stylize( 'emphasize', $dependencyItem['type'] ) .
                  '(' .
                  $cli->stylize( 'emphasize', $dependencyItem['name'] ) .
                  ')' );
        if ( $dependencyItem['value'] )
            $text .= ' ' . $this->dependencyOperatorText( $dependencyItem ) .' ' . $cli->stylize( 'symbol', $dependencyItem['value'] );
        $handler =& $this->packageHandler( $dependencyItem['type'] );
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
    function dependencyItems( $dependencySection, $type = false, $name = false, $value = false )
    {
        if ( !in_array( $dependencySection,
                        array( 'provides', 'requires',
                               'obsoletes', 'conflicts' ) ) )
            return false;
        if ( !$type and !$name and !$value )
        {
            return $this->Parameters['dependencies'][$dependencySection];
        }
        else
        {
            $matches = array();
            $dependencyItems = $this->Parameters['dependencies'][$dependencySection];
            foreach ( $dependencyItems as $dependencyItem )
            {
                $found = false;
                if ( $type and $dependencyItem['type'] == $type )
                    $found = true;
                if ( !$found and $name and $dependencyItem['name'] == $name )
                    $found = true;
                if ( !$found and $value and $dependencyItem['value'] == $value )
                    $found = true;
                if ( $found )
                    $matches[] = $dependencyItem;
            }
            return $matches;
        }
    }

    /*!
     \return an array with install items which match the specified criterias.
    */
    function installItems( $type = false, $os = false, $name = false, $isInstall = true )
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
    function toString( $export = false )
    {
        $dom =& $this->domStructure( $export );
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
        $php->addVariable( 'CacheCodeDate', EZ_PACKAGE_CACHE_CODEDATE );
        $php->addSpace();
        $php->addVariable( 'Parameters', $this->Parameters, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT,
                           array( 'full-tree' => true ) );
        $php->addVariable( 'ModifiedParameters', $this->ModifiedParameters, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT,
                           array( 'full-tree' => true ) );
        $php->addVariable( 'InstallData', $this->InstallData, EZ_PHPCREATOR_VARIABLE_ASSIGNMENT,
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
        $export = false;
        if ( $exportFormat )
        {
            $export = array( 'path' => $path );
        }
        $result = $this->storeString( $filePath, $this->toString( $export ) );
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

    function export( $destinationPath )
    {
        $destinationPath .= '/' . $this->attribute( 'name' );
        $this->removePackageFiles( $destinationPath );
        $this->storePackageFiles( $destinationPath, false, true );
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

    function &import( $archiveName, &$packageName )
    {
        $tempDirPath = eZPackage::temporaryImportPath();
        if ( is_dir( $archiveName ) )
        {
//             $archivePath = $archiveName;
//             $package =& eZPackage::fetch( $packageName, $archivePath );
//             if ( $package )
//             {
//             }
            eZDebug::writeError( "Importing from directory is not supported yet." );
            return false;
        }
        else
        {
            $archivePath = $tempDirPath . '/' . $packageName;
            eZPackage::removePackageFiles( $archivePath );
            if ( !file_exists( $archivePath ) )
            {
                eZDir::mkdir( $archivePath, eZDir::directoryPermission(), true );
            }

            include_once( 'lib/ezfile/classes/ezarchivehandler.php' );

            $archive = eZArchiveHandler::instance( 'tar', 'gzip', $archiveName );
            $fileList = array();
            $fileList[] = eZPackage::definitionFilename();
            if ( !$archive->extractList( $fileList, $archivePath, '' ) )
            {
                eZDebug::writeError( "Failed extracting package definition file from $archivePath" );
                return false;
            }

            $package =& eZPackage::fetch( $packageName, $tempDirPath );
            eZPackage::removePackageFiles( $archivePath );
            if ( $package )
            {
                $packageName = $package->attribute( 'name' );

                $existingPackage =& eZPackage::fetch( $packageName );
                if ( $existingPackage )
                {
                    return EZ_PACKAGE_STATUS_ALREADY_EXISTS;
                }
                unset( $archive );
                unset( $package );

                $archivePath = eZPackage::repositoryPath();
                $archivePackagePath = $archivePath . '/' . $packageName;
                if ( !file_exists( $archivePackagePath ) )
                {
                    eZDir::mkdir( $archivePackagePath, eZDir::directoryPermission(), true );
                }
                $archive = eZArchiveHandler::instance( 'tar', 'gzip', $archiveName );
                $archive->extractModify( $archivePackagePath, '' );

                $package =& eZPackage::fetch( $packageName, $archivePath );
                if ( !$package )
                {
                    eZDebug::writeError( "Failed loading imported package $packageName from $archivePath" );
                }
            }
            else
            {
                eZDebug::writeError( "Failed loading temporary package $packageName from $archivePath" );
            }
        }

        return $package;
    }

    /*!
     \static
     \return the suffix for all package files.
    */
    function suffix()
    {
//         return 'tgz';
        return 'ezpkg';
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

            $siteConfig =& eZINI::instance( 'site.ini' );
            $filePermissions = $siteConfig->variable( 'FileSettings', 'StorageFilePermissions');
            @chmod( $file, octdec( $filePermissions ) );

            eZDebugSetting::writeNotice( 'kernel-ezpackage-store',
                                         "Stored file $filename",
                                         'eZPackage::storeString' );
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
            $fd = fopen( $filename, 'rb' );
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
        $fd = fopen( $filename, 'rb' );
        if ( $fd )
        {
            $xmlText = fread( $fd, filesize( $filename ) );
            fclose( $fd );

            $xml = new eZXML();
            $dom =& $xml->domTree( $xmlText );

            $package =& new eZPackage();
            $parameters = $package->parseDOMTree( $dom );

            if ( !$parameters )
                return false;

            if ( $package and
                 !EZ_PACKAGE_DEVELOPMENT )
            {
                $development = $package->attribute( 'development' );
                if ( $development )
                {
                    $cli =& eZCLI::instance();
                    $cli->warning( "Could not load package from file " . $cli->stylize( 'emphasize', $filename ) );
                    return false;
                }
            }

            $root =& $dom->root();
            if ( !$root )
                return false;

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
        $path = eZPackage::repositoryPath();
        if ( $packagePath )
            $path = $packagePath;
        $path .= '/' . $packageName;
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
            if ( $packagePath )
                $package->RepositoryPath = $packagePath;
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
//        $path = $this->currentRepositoryPath() . '/' . $packageName;
        $path = eZPackage::repositoryPath() . '/' . $packageName;
        $packageCachePath = $path . '/' . eZPackage::cacheDirectory() . '/package.php';

        $cacheExpired = false;
        if ( file_exists( $packageCachePath ) )
        {
            $cacheModification = filemtime( $packageCachePath );
            if ( $cacheModification >= $packageModification )
            {
                include( $packageCachePath );
                if ( !isset( $CacheCodeDate ) or
                     $CacheCodeDate != EZ_PACKAGE_CACHE_CODEDATE )
                {
                    $cacheExpired = true;
                    return false;
                }
                if ( isset( $Parameters ) and
                     isset( $ModifiedParameters ) and
                     isset( $InstallData ) )
                {
                    if ( !EZ_PACKAGE_DEVELOPMENT )
                    {
                        if ( !isset( $Parameters['development'] ) or
                             $Parameters['development'] )
                        {
                            $cli->warning( "Could not load package from cache " . $cli->stylize( 'emphasize', $packageName ) );
                            return false;
                        }
                    }

                    $package = new eZPackage( $Parameters, array(), $RepositoryPath );
                    $package->InstallData = $InstallData;
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
//         $path = eZPackage::repositoryPath();
        $path = $this->RepositoryPath;
        $path .= '/' . $this->attribute( 'name' );
        return $path;
    }

    /*!
     \return the path to the current repository.
    */
    function currentRepositoryPath()
    {
        return $this->RepositoryPath;
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
     Locates all dependent packages in the repository and returns an array with eZPackage objects.
    */
    function fetchDependentPackages( $parameters = array() )
    {
        $packages = array();
        $requiredType = null;
        $requiredName = null;
        if ( isset( $parameters['type'] ) )
            $requiredType = $parameters['type'];
        if ( isset( $parameters['name'] ) )
            $requiredName = $parameters['name'];

        $privides = $this->Parameters['dependencies']["provides"];

        if ( $privides != null )
        {
            foreach ( $privides as $privide )
            {
                $packageName = $privide['name'];
                if ( $requiredType !== null )
                {
                    $type = $privide['type'];
                    if ( $requiredType != $type )
                    {
                        continue;
                    }
                }

                if ( $requiredName !== null )
                {
                    if ( $requiredName != $packageName )
                        continue;
                }
                $package =& $this->fetch( $packageName );
                if ( !$package )
                    continue;
                $packages[] =& $package;
            }
        }
        return $packages;
    }

    /*!
     Locates all packages in the repository and returns an array with eZPackage objects.
    */
    function fetchPackages( $parameters = array(), $filterParams = array() )
    {
        $path = eZPackage::repositoryPath();
        if ( isset( $parameters['path'] ) )
            $path = $parameters['path'];
        /* if ( isset( $parameters['type'] ) )
            $type = $parameters['type'];*/
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
                        $package =& eZPackage::fetchFromCache( $file, $fileModification, $cacheExpired );
                    }
                    if ( !$package )
                    {
                        $package =& eZPackage::fetchFromFile( $filePath );
                        if ( $package and
                             $cacheExpired and
                             eZPackage::useCache() )
                        {
                            $package->storeCache( $dirPath . '/' . eZPackage::cacheDirectory() );
                        }
                    }
                    if ( !$package )
                        continue;
                    if ( !$package->attribute( 'is_active' ) )
                        continue;

                    if ( $requiredType !== null )
                    {
                        $type = $package->attribute( 'type' );
                        if ( $type != $requiredType )
                            return false;
                    }

                    if ( $requiredPriority !== null )
                    {
                        $type = $package->attribute( 'priority' );
                        if ( $priority != $requiredPriority )
                            return false;
                    }

                    if ( $requiredExtension !== null )
                    {
                        $type = $package->attribute( 'extension' );
                        if ( $extension != $requiredExtension )
                            return false;
                    }

                    if ( $requiredVendor !== null )
                    {
                        $type = $package->attribute( 'vendor' );
                        if ( $vendor != $requiredVendor )
                            return false;
                    }

                    $packages[] =& $package;
                }
            }
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

    function install( $installParameters = array() )
    {
        if ( $this->Parameters['install_type'] != 'install' )
            return;
        $installs = $this->Parameters['install'];
        if ( !isset( $installParameters['path'] ) )
            $installParameters['path'] = false;
        foreach ( $installs as $install )
        {
            $type = $install['type'];
            $name = $install['name'];
            $os = $install['os'];
            $filename = $install['filename'];
            $subdirectory = $install['sub-directory'];
            $parameters = $install;
            $content = false;
            if ( isset( $parameters['content'] ) )
                $content = $parameters['content'];
            $handler =& $this->packageHandler( $type );
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

                        $dom =& $this->fetchDOMFromFile( $filepath );
                        if ( $dom )
                            $content =& $dom->root();
                        else
                            eZDebug::writeError( "Failed fetching dom from file $filepath" );
                    }
                }
                $installData =& $this->InstallData[$type];
                if ( !isset( $installData ) )
                    $installData = array();
                $installResult = $handler->install( $this, $type, $parameters,
                                                    $name, $os, $filename, $subdirectory,
                                                    $content, $installParameters,
                                                    $installData );
            }
        }
        $this->Parameters['is_installed'] = true;
        $this->store();
    }

    function uninstall( $uninstallParameters = array() )
    {
        if ( $this->Parameters['install_type'] != 'install' )
            return;
        if ( !$this->Parameters['is_installed']  )
            return;
        $installs = $this->Parameters['uninstall'];
        if ( !isset( $installParameters['path'] ) )
            $installParameters['path'] = false;
        foreach ( $installs as $install )
        {
            $type = $install['type'];
            $name = $install['name'];
            $os = $install['os'];
            $filename = $install['filename'];
            $subdirectory = $install['sub-directory'];
            $parameters = $install;
            $content = false;
            if ( isset( $parameters['content'] ) )
                $content = $parameters['content'];
            $handler =& $this->packageHandler( $type );
            if ( $handler )
            {
                if ( isset( $this->InstallData[$type] ) )
                {
                    $installData =& $this->InstallData[$type];
                }
                else
                {
                    unset( $installData );
                    $installData = array();
                }
                $installResult = $handler->uninstall( $this, $type, $parameters,
                                                      $name, $os, $filename, $subdirectory,
                                                      $installParameters,
                                                      $installData );
            }
        }
        $this->InstallData = array();
        $this->Parameters['is_installed'] = false;
        $this->store();
    }

    /*!
     \private
    */
    function &parseDOMTree( &$dom )
    {
        $root =& $dom->root();
        if ( !$root )
            return false;

        if ( !EZ_PACKAGE_DEVELOPMENT )
        {
            // If it has a warning we don't read it in production mode
            if ( $root->elementByName( 'warning' ) )
            {
                return false;
            }
        }

        // Read basic info
        $parameters = array();
        $parameters['name'] = $root->elementTextContentByName( 'name' );
        $parameters['summary'] = $root->elementTextContentByName( 'summary' );
        $parameters['description'] = $root->elementTextContentByName( 'description' );
        $parameters['vendor'] = $root->elementTextContentByName( 'vendor' );
        $parameters['priority'] = $root->elementAttributeValueByName( 'priority', 'value' );
        $parameters['type'] = $root->elementAttributeValueByName( 'type', 'value' );
        $parameters['is_installed'] = false;
        $isInstalled =& $root->attributeValue( 'is_installed' ) == 'true';
        if ( $isInstalled )
            $parameters['is_installed'] = true;

        $parameters['is_active'] = true;
        $isActive =& $root->attributeValue( 'is_active' );
        if ( $isActive )
            $parameters['is_active'] = $isActive == 'true';

        $parameters['install_type'] = 'install';
        $installType =& $root->attributeValue( 'install_type' );
        if ( $installType )
            $parameters['install_type'] = $installType;
        $parameters['source'] = $root->elementTextContentByName( 'source' );
        $parameters['development'] = $root->attributeValue( 'development' ) == 'true';
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
        if ( is_array( $documentList ) )
        {
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
                    $fileVariableName = $fileListNode->attributeValue( 'variable-name' );
                    $fileRoleValue = $fileListNode->attributeValue( 'role-value' );
//                     $dirs =& $fileListNode->elementsByName( 'dir' );
//                     {
//                         if ( count( $dirs ) > 0 )
//                         {
//                             foreach ( array_keys( $dirs ) as $dirKey )
//                             {
//                                 $dirNode =& $dirs[$dirKey];
//                                 $dirName = $dirNode->attributeValue( 'name' );
//                                 $dirSubDirectory = $dirNode->attributeValue( 'sub-directory' );
//                                 $dirPath = $dirNode->attributeValue( 'path' );
//                                 $dirModified = $dirNode->attributeValue( 'modified' );
//                                 $this->appendFile( $dirName, $fileType, $fileRole,
//                                                    $fileDesign, $dirPath, $fileCollectionName,
//                                                    $dirSubDirectory, false, false, $dirModified );
//                             }
//                         }
//                     }
//                     unset( $dirs );
                    $files =& $fileListNode->elementsByName( 'file' );
                    if ( count( $files ) > 0 )
                    {
                        foreach ( array_keys( $files ) as $fileKey )
                        {
                            $fileNode =& $files[$fileKey];
                            $fileFileType = $fileNode->attributeValue( 'type' );
                            $fileName = $fileNode->attributeValue( 'name' );
                            if ( $fileNode->attributeValue( 'variable-name' ) )
                                $fileVariableName = $fileNode->attributeValue( 'variable-name' );
                            $fileSubDirectory = $fileNode->attributeValue( 'sub-directory' );
                            $filePath = $fileNode->attributeValue( 'path' );
                            $fileMD5 = $fileNode->attributeValue( 'md5sum' );
                            $fileModified = $fileNode->attributeValue( 'modified' );
                            $this->appendFile( $fileName, $fileType, $fileRole,
                                               $fileDesign, $filePath, $fileCollectionName,
                                               $fileSubDirectory, $fileMD5, false, $fileModified,
                                               $fileFileType,
                                               $fileRoleValue, $fileVariableName );
                        }
                    }
                    else
                    {
                        $fileModified = $fileListNode->attributeValue( 'modified' );
                        $this->appendFile( false, $fileType, $fileRole,
                                           $fileDesign, false, $fileCollectionName,
                                           false, false, false, $fileModified );
                    }
                    unset( $files );
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

        $installDataList =& $root->elementChildrenByName( 'install-data' );
        if ( $installDataList )
        {
            $this->InstallData = array();
            for ( $i = 0; $i < count( $installDataList ); ++$i )
            {
                $installDataNode =& $installDataList[$i];
                if ( is_object( $installDataNode ) &&
                     $installDataNode->attribute( 'name' ) == 'data' )
                {
                    $installDataType = $installDataNode->attributeValue( 'type' );
                    $installDataElements = $installDataNode->children();
                    $installData = array();
                    foreach ( array_keys( $installDataElements ) as $installDataElementKey )
                    {
                        $installDataElement =& $installDataElements[$installDataElementKey];
                        if ( $installDataElement->attribute( 'name' ) == 'element' )
                        {
                            $name = $installDataElement->attributeValue( 'name' );
                            $value = $installDataElement->attributeValue( 'value' );
                            $installData[$name] = $value;
                        }
                        else if ( $installDataElement->attribute( 'name' ) == 'array' )
                        {
                            $arrayName = $installDataElement->attributeValue( 'name' );
                            $installDataElementArray =& $installDataElement->children();
                            $array = array();
                            foreach ( array_keys( $installDataElementArray ) as $installDataElementArrayKey )
                            {
                                $installDataElementArrayElement =& $installDataElementArray[$installDataElementArrayKey];
                                $name = $installDataElementArrayElement->attributeValue( 'name' );
                                $value = $installDataElementArrayElement->attributeValue( 'value' );
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

        return true;
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
                $handler->parseDependencyNode( $this, $dependencyNode, $dependencyParameters, $dependencySection );
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
                $handler->parseInstallNode( $this, $installNode, $installParameters, $isInstall );
            }
            if ( count( $installParameters ) == 0 )
                $installParameters = false;

            $this->appendInstall( $installType, $installName, $installOS, $isInstall,
                                  $installFilename, $installSubdirectory,
                                  $installParameters, $installModified );
        }
    }

    /*!
     \return the dom document of the package.
    */
    function &domStructure( $export = false )
    {
        $exportFormat = false;
        if ( $export )
        {
            $exportFormat = true;
            $exportPath = $export['path'];
        }
        $dom = new eZDOMDocument();
        $root = $dom->createElementNode( 'package', array( 'version' => EZ_PACKAGE_VERSION,
                                                           'development' => ( EZ_PACKAGE_DEVELOPMENT ? 'true' : 'false' ) ) );
        $root->appendAttribute( $dom->createAttributeNode( 'ezpackage', 'http://ez.no/ezpackage', 'xmlns' ) );
        $dom->setRoot( $root );

        if ( EZ_PACKAGE_DEVELOPMENT )
            $root->appendChild( $dom->createElementTextNode( 'warning',
                                                             "This format was made with a development version and will not work with any release versions.\n" .
                                                             "The format of this file is also subject to change until the release version.\n" .
                                                             "Upgrades to the development format will not be supported." ) );

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
        $isInstalled = $this->attribute( 'is_installed' );
        $isActive= $this->attribute( 'is_active' );
        $installType = $this->attribute( 'install_type' );
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

        if ( !$exportFormat and $isInstalled )
            $root->appendAttribute( $dom->createAttributeNode( 'is_installed', 'true' ) );
        if ( !$exportFormat )
            $root->appendAttribute( $dom->createAttributeNode( 'is_active', ( $isActive ? 'true' : 'false' ) ) );
        $root->appendAttribute( $dom->createAttributeNode( 'install_type', $installType ) );

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

        // Handle files
        $filesNode =& $dom->createElementNode( 'files' );
        $filesNode->appendAttribute( $dom->createAttributeNode( 'ezfile', 'http://ez.no/ezpackage', 'xmlns' ) );
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
                unset( $fileThumbnailLists );
                $fileList = array();
                $fileDesignList = array();
                $fileINIList = array();
                $fileThumbnailList = array();
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
                    if ( !isset( $fileListNode ) )
                    {
                        $fileListAttributes = array( 'type' => $fileItem['type'] );
                        if ( $fileItem['type'] == 'design' )
                            $fileListAttributes['design'] = $fileItem['design'];
                        if ( $fileItem['role'] )
                            $fileListAttributes['role'] = $fileItem['role'];
                        if ( $fileItem['role-value'] )
                            $fileListAttributes['role-value'] = $fileItem['role-value'];
                        if ( $fileItem['variable-name'] )
                            $fileListAttributes['variable-name'] = $fileItem['variable-name'];
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
                    else
                    {
                        if ( $fileItem['modified'] and !$exportFormat )
                            $fileListNode->appendAttribute( $dom->createAttributeNode( 'modified', $fileItem['modified'] ) );
                    }
                    $copyFile = $fileItem['copy-file'];
                    if ( $export )
                    {
                        $typeDir = $fileItem['type'];
                        if ( $fileItem['type'] == 'design' )
                            $typeDir .= '.' . $fileItem['design'];
                        if ( $fileItem['role'] )
                        {
                            $typeDir .= '.' . $fileItem['role'];
                            if ( $fileItem['role-value'] )
                                $typeDir .= '-' . $fileItem['role-value'];
                        }
                        $path = $this->path() . '/' . eZPackage::filesDirectory() . '/' . $fileCollectionName . '/' . $typeDir;
                        $destinationPath = $exportPath . '/' . eZPackage::filesDirectory() . '/' . $fileCollectionName . '/' . $typeDir;
                        if ( $fileItem['subdirectory'] )
                        {
                            $path .= '/' . $fileItem['subdirectory'];
                            $destinationPath .= '/' . $fileItem['subdirectory'];
                        }
                        if ( $fileItem['name'] )
                            $path .= '/' . $fileItem['name'];
                        if ( $fileItem['path'] )
                            $path = $fileItem['path'];
                        if ( !file_exists( $destinationPath ) )
                            eZDir::mkdir( $destinationPath, eZDir::directoryPermission(), true );
                        if ( is_dir( $path ) )
                        {
                            $copiedFiles = eZDir::copy( $path, $destinationPath,
                                                        $fileItem['name'] != false, true, false, eZDir::temporaryFileRegexp() );
                            foreach ( $copiedFiles as $copiedFile )
                            {
                                $copiedFileName = $copiedFile;
                                $copiedSubdirectory = false;
                                if ( preg_match( '#^(.+)/([^/]+)$#', $copiedFile, $matches ) )
                                {
                                    $copiedSubdirectory = $matches[1];
                                    $copiedFileName = $matches[2];
                                }
                                $copiedFileAttributes = array( 'name' => $copiedFileName );
                                if ( $copiedSubdirectory and $fileItem['name'] )
                                    $copiedSubdirectory = $fileItem['name'] . '/' . $copiedSubdirectory;
                                else if ( $fileItem['name'] )
                                    $copiedSubdirectory = $fileItem['name'];
                                if ( is_dir( $destinationPath . '/' . $copiedFile ) )
                                {
                                    $copiedFileAttributes = array_merge( $copiedFileAttributes,
                                                                         array( 'type' => 'dir' ) );
                                }

                                if ( $fileItem['subdirectory'] and $copiedSubdirectory )
                                    $copiedSubdirectory = $fileItem['subdirectory'] . '/' . $copiedSubdirectory;
                                else if ( $fileItem['subdirectory'] )
                                    $copiedSubdirectory = $fileItem['subdirectory'];
                                $copiedMD5Sum = $this->md5sum( $destinationPath . '/' . $copiedFile );
                                if ( $copiedMD5Sum )
                                    $copiedFileAttributes['md5sum'] = $copiedMD5Sum;
                                if ( $copiedSubdirectory )
                                    $copiedFileAttributes['sub-directory'] = $copiedSubdirectory;
                                $fileListNode->appendChild( $dom->createElementNode( 'file', $copiedFileAttributes ) );
                            }
                        }
                        else
                        {
                            eZFileHandler::copy( $path, $destinationPath . '/' . $fileItem['name'] );
                        }
                    }
                    else if ( $copyFile )
                    {
                        $typeDir = $fileItem['type'];
                        if ( $fileItem['type'] == 'design' )
                            $typeDir .= '.' . $fileItem['design'];
                        if ( $fileItem['role'] )
                        {
                            $typeDir .= '.' . $fileItem['role'];
                            if ( $fileItem['role-value'] )
                                $typeDir .= '-' . $fileItem['role-value'];
                        }
                        $path = $this->path() . '/' . eZPackage::filesDirectory() . '/' . $fileCollectionName . '/' . $typeDir;
                        if ( $fileItem['subdirectory'] )
                            $path .= '/' . $fileItem['subdirectory'];
                        if ( !file_exists( $path ) )
                            eZDir::mkdir( $path, eZDir::directoryPermission(), true );
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

        $this->createDependencyTree( $export, $providesNode, 'provide', $dependencies['provides'] );
        $this->createDependencyTree( $export, $requiresNode, 'require', $dependencies['requires'] );
        $this->createDependencyTree( $export, $obsoletesNode, 'obsolete', $dependencies['obsoletes'] );
        $this->createDependencyTree( $export, $conflictsNode, 'conflict', $dependencies['conflicts'] );

        $root->appendChild( $dependencyNode );

        $installNode =& $dom->createElementNode( 'install' );
        $installNode->appendAttribute( $dom->createAttributeNode( 'ezinstall', 'http://ez.no/ezpackage', 'xmlns' ) );
        $uninstallNode =& $dom->createElementNode( 'uninstall' );
        $uninstallNode->appendAttribute( $dom->createAttributeNode( 'ezinstall', 'http://ez.no/ezpackage', 'xmlns' ) );

        $this->createInstallTree( $export, $installNode, $dom, $install, 'install' );
        $this->createInstallTree( $export, $uninstallNode, $dom, $uninstall, 'uninstall' );

        $root->appendChild( $installNode );
        $root->appendChild( $uninstallNode );

        if ( count( $this->InstallData ) > 0 )
        {
            $installDataNode =& $dom->createElementNode( 'install-data' );
            $installDataNode->appendAttribute( $dom->createAttributeNode( 'ezinstall', 'http://ez.no/ezpackage', 'xmlns' ) );
            foreach ( $this->InstallData as $installDataType => $installData )
            {
                if ( count( $installData ) > 0 )
                {
                    $dataNode =& $dom->createElementNode( 'data',
                                                          array( 'type' => $installDataType ) );
                    $installDataNode->appendChild( $dataNode );
                    foreach ( $installData as $installDataName => $installDataValue )
                    {
                        if ( is_array( $installDataValue ) )
                        {
                            $dataArrayNode =& $dom->createElementNode( 'array',
                                                                       array( 'name' => $installDataName ) );
                            $dataNode->appendChild( $dataArrayNode );
                            foreach ( $installDataValue as $installDataValueName => $installDataValueValue )
                            {
                                $dataArrayNode->appendChild( $dom->createElementNode( 'element',
                                                                                      array( 'name' => $installDataValueName,
                                                                                             'value' => $installDataValueValue ) ) );
                            }
                        }
                        else
                        {
                            $dataNode->appendChild( $dom->createElementNode( 'element',
                                                                             array( 'name' => $installDataName,
                                                                                    'value' => $installDataValue ) ) );
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
    function createInstallTree( $export, &$installNode, &$dom, $list, $installType )
    {
        foreach ( $list as $installItem )
        {
            $type = $installItem['type'];
//             if ( ( isset( $installItem['content'] ) and
//                    $installItem['content'] ) or
//                  $installItem['filename'] )
            {
                $installItemNode =& $dom->createElementNode( 'item',
                                                             array( 'type' => $type ) );
                $installNode->appendChild( $installItemNode );
                $content = false;
                if ( isset( $installItem['content'] ) )
                    $content = $installItem['content'];
                if ( $installItem['os'] )
                    $installItemNode->appendAttribute( $dom->createAttributeNode( 'os', $installItem['os'] ) );
                if ( $installItem['name'] )
                    $installItemNode->appendAttribute( $dom->createAttributeNode( 'name', $installItem['name'] ) );
                $installModified = $installItem['modified'];
                if ( !$export and $installModified )
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
                    $handler->createInstallNode( $this, $export, $installItemNode, $installItem, $installType );
                }
            }
        }
    }

    /*!
     Creates dependency xml elements as child of $dependenciesNode.
     The dependency elements are take from \a $list.
     \param $dependencyType Is either \c 'provide', \c 'require', \c 'obsolete' or \c 'conflict'
    */
    function createDependencyTree( $export, &$dependenciesNode, $dependencyType, $list )
    {
        foreach ( $list as $dependencyItem )
        {
            $dependencyNode =& eZDOMDocument::createElementNode( $dependencyType );
            $dependencyNode->appendAttribute( eZDOMDocument::createAttributeNode( 'type', $dependencyItem['type'] ) );
            $dependencyNode->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $dependencyItem['name'] ) );
            if ( $dependencyItem['value'] )
                $dependencyNode->appendAttribute( eZDOMDocument::createAttributeNode( 'value', $dependencyItem['value'] ) );
            $dependencyModified = $dependencyItem['modified'];
            if ( !$export and $dependencyModified )
                $dependencyNode->appendAttribute( eZDOMDocument::createAttributeNode( 'modified', $dependencyModified ) );
            $dependenciesNode->appendChild( $dependencyNode );
            $handler =& $this->packageHandler( $dependencyItem['name'] );
            if ( $handler )
            {
                $handler->createDependencyNode( $this, $export, $dependencyNode, $dependencyItem, $dependencyType );
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
