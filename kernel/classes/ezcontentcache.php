<?php
//
// Definition of eZContentCache class
//
// Created on: <12-Dec-2002 16:53:41 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.

//

/*! \file ezcontentcache.php
*/

/*!
  \class eZContentCache ezcontentcache.php
  \brief The class eZContentCache does

*/

include_once( 'lib/ezutils/classes/ezsys.php' );
include_once( "lib/ezfile/classes/ezdir.php" );

// The timestamp for the cache format, will expire
// cache which differs from this.
define( 'EZ_CONTENT_CACHE_CODE_DATE', 1064816011 );

class eZContentCache
{
    /*!
     Constructor
    */
    function eZContentCache()
    {
    }

    function cachePathInfo( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList, $layout, $cacheTTL = false,
                            $parameters = array() )
    {
        $md5Input = array( $nodeID );
        $md5Input[] = $offset;
        $md5Input = array_merge( $md5Input, $layout );
        sort( $roleList );
        $md5Input = array_merge( $md5Input, $roleList );
        sort( $discountList );
        $md5Input = array_merge( $md5Input, $discountList );
        if ( $cacheTTL == true )
            $md5Input = array_merge( $md5Input, "cache_ttl" );
        if ( isset( $parameters['view_parameters'] ) )
        {
            $viewParameters = $parameters['view_parameters'];
            ksort( $viewParameters );
            foreach ( $viewParameters as $viewParameterName => $viewParameter )
            {
                if ( !$viewParameter )
                    continue;
                $md5Input = array_merge( $md5Input, 'vp:' . $viewParameterName . '=' . $viewParameter );
            }
        }
        $md5Text = md5( implode( '-', $md5Input ) );
        $cacheFile = $nodeID . '-' . $md5Text . '.cache';
        $extraPath = eZDir::filenamePath( "$nodeID" );
        $ini =& eZINI::instance();
        $cacheDir = eZDir::path( array( eZSys::cacheDirectory(), $ini->variable( 'ContentSettings', 'CacheDir' ), $siteDesign, $viewMode, $language, $extraPath ) );
        $cachePath = eZDir::path( array( $cacheDir, $cacheFile ) );
        return array( 'dir' => $cacheDir,
                      'file' => $cacheFile,
                      'path' => $cachePath );
    }

    function exists( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList, $layout,
                     $parameters = array() )
    {
        $cachePathInfo = eZContentCache::cachePathInfo( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList,
                                                        $layout, false, $parameters );
        $cacheInfo = @stat( $cachePathInfo['path'] );

        if ( $cacheInfo )
        {
            $timestamp = $cacheInfo['mtime'];
            include_once( 'kernel/classes/ezcontentobject.php' );
            if ( eZContentObject::isCacheExpired( $timestamp ) )
            {
                eZDebugSetting::writeDebug( 'kernel-content-view-cache', 'cache expired #1' );
                return false;
            }
            eZDebugSetting::writeDebug( 'kernel-content-view-cache', "checking viewmode '$viewMode' #1" );
            if ( eZContentObject::isComplexViewModeCacheExpired( $viewMode, $timestamp ) )
            {
                eZDebugSetting::writeDebug( 'kernel-content-view-cache', "viewmode '$viewMode' cache expired #1" );
                return false;
            }
        }
        eZDebugSetting::writeDebug( 'kernel-content-view-cache', 'cache used #1' );
        return (bool) $cacheInfo;
    }

    function restore( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList, $layout,
                      $parameters = array() )
    {
        $result = array();
        $cachePathInfo = eZContentCache::cachePathInfo( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList,
                                                        $layout, false, $parameters );
        $cacheDir = $cachePathInfo['dir'];
        $cacheFile = $cachePathInfo['file'];
        $cachePath = $cachePathInfo['path'];
        $timestamp = false;

        $fileInfo = @stat( $cachePath );
        if ( $fileInfo )
        {
            $timestamp = $fileInfo['mtime'];
            include_once( 'kernel/classes/ezcontentobject.php' );
            if ( eZContentObject::isCacheExpired( $timestamp ) )
            {
                eZDebugSetting::writeDebug( 'kernel-content-view-cache', 'cache expired #2' );
                return false;
            }
            eZDebugSetting::writeDebug( 'kernel-content-view-cache', "checking viewmode '$viewMode' #1" );
            if ( eZContentObject::isComplexViewModeCacheExpired( $viewMode, $timestamp ) )
            {
                eZDebugSetting::writeDebug( 'kernel-content-view-cache', "viewmode '$viewMode' cache expired #2" );
                return false;
            }

        }

        if ( $viewMode == 'pdf' )
        {
            return $cachePath;
        }

        eZDebugSetting::writeDebug( 'kernel-content-view-cache', 'cache used #2' );

        $fileName = $cacheDir . "/" . $cacheFile;
        $fp = fopen( $fileName, 'r' );

        $contents = fread( $fp, filesize( $fileName ) );
        $cachedArray = unserialize( $contents );
        fclose( $fp );

        $cacheTTL = $cachedArray['cache_ttl'];

        // Check if cache has expired
        if ( $cacheTTL > 0 )
        {
            $expiryTime = $timestamp + $ttlTime;
            if ( time() > $expiryTime )
            {
                return false;
            }
        }

        // Check for template language timestamp
        $cacheCodeDate = $cachedArray['cache_code_date'];
        if ( $cacheCodeDate != EZ_CONTENT_CACHE_CODE_DATE )
            return false;

        $viewMode = $cachedArray['content_info']['viewmode'];

        $res =& eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'node', $nodeID ),
                              array( 'view_offset', $offset ),
                              array( 'viewmode', $viewMode )
                              ) );
        $result['content_info'] = $cachedArray['content_info'];
        $result['content'] = $cachedArray['content'];

        $result['view_parameters'] = $cachedArray['content_info']['view_parameters'];

        foreach ( array( 'path', 'node_id', 'section_id', 'navigation_part' ) as $item )
        {
            if ( isset( $cachedArray[$item] ) )
            {
                $result[$item] = $cachedArray[$item];
            }
        }

        // set section id
        include_once( 'kernel/classes/ezsection.php' );
        eZSection::setGlobalID( $cachedArray['section_id'] );
        return $result;
    }

    function store( $siteDesign, $objectID, $classID, $classIdentifier,
                    $nodeID, $parentNodeID, $nodeDepth, $urlAlias, $viewMode, $sectionID,
                    $language, $offset, $roleList, $discountList, $layout, $navigationPartIdentifier,
                    $result, $cacheTTL = -1,
                    $parameters = array() )
    {
        $cachePathInfo = eZContentCache::cachePathInfo( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList,
                                                        $layout, false, $parameters );
        $cacheDir = $cachePathInfo['dir'];
        $cacheFile = $cachePathInfo['file'];

        $serializeArray = array();

        if ( isset( $parameters['view_parameters']['offset'] ) )
        {
            $offset = $parameters['view_parameters']['offset'];
        }
        $viewParameters = false;
        if ( isset( $parameters['view_parameters'] ) )
        {
            $viewParameters = $parameters['view_parameters'];
        }
        $contentInfo = array( 'site_design' => $siteDesign,
                              'node_id' => $nodeID,
                              'parent_node_id' => $parentNodeID,
                              'node_depth' => $nodeDepth,
                              'url_alias' => $urlAlias,
                              'object_id' => $objectID,
                              'class_id' => $classID,
                              'class_identifier' => $classIdentifier,
                              'navigation_part' => $navigationPartIdentifier,
                              'viewmode' => $viewMode,
                              'language' => $language,
                              'offset' => $offset,
                              'view_parameters' => $viewParameters,
                              'role_list' => $roleList,
                              'discount_list' => $discountList,
                              'section_id' => $result['section_id'] );

        $serializeArray['content_info'] = $contentInfo;

        foreach ( array( 'path', 'node_id', 'section_id', 'navigation_part' ) as $item )
        {
            if ( isset( $result[$item] ) )
            {
                $serializeArray[$item] = $result[$item];
            }
        }

        $serializeArray['cache_ttl'] = $cacheTTL;

        $serializeArray['cache_code_date'] = EZ_CONTENT_CACHE_CODE_DATE;
        $serializeArray['content'] = $result['content'];

        $serializeString = serialize( $serializeArray );

        if ( !file_exists( $cacheDir ) )
        {
            include_once( 'lib/ezfile/classes/ezdir.php' );
            $ini =& eZINI::instance();
            $perm = octdec( $ini->variable( 'FileSettings', 'StorageDirPermissions' ) );
            eZDir::mkdir( $cacheDir, $perm, true );
        }
        $path = $cacheDir . '/' . $cacheFile;
        $oldumask = umask( 0 );
        $pathExisted = file_exists( $path );
        $ini =& eZINI::instance();
        $perm = octdec( $ini->variable( 'FileSettings', 'StorageFilePermissions' ) );
        $fp = @fopen( $path, "w" );
        if ( !$fp )
        {
            eZDebug::writeError( "Could not open file '$path' for writing, perhaps wrong permissions" );
        }
        if ( $fp and !$pathExisted )
        {
            chmod( $path, $perm );
        }
        umask( $oldumask );

        if ( $fp )
        {
            fwrite( $fp, $serializeString );
            fclose( $fp );
        }

        return $fp;
    }

    function calculateCleanupValue( $nodeCount )
    {
        $ini =& eZINI::instance();
        $viewModes = $ini->variableArray( 'ContentSettings', 'CachedViewModes' );
        $languages =& eZContentTranslation::fetchLocaleList();
        $contentINI =& eZINI::instance( "content.ini" );


        if ( $contentINI->hasVariable( 'VersionView', 'AvailableSiteDesigns' ) )
        {
            $sitedesignList = $contentINI->variableArray( 'VersionView', 'AvailableSiteDesigns' );
        }
        else if ( $contentINI->hasVariable( 'VersionView', 'AvailableSiteDesignList' ) )
        {
            $sitedesignList = $contentINI->variable( 'VersionView', 'AvailableSiteDesignList' );
        }

        $value = $nodeCount * count( $viewModes ) * count( $languages ) * count( $sitedesignList );
        return $value;
    }

    function inCleanupThresholdRange( $value )
    {
        $ini =& eZINI::instance();
        $threshold = $ini->variable( 'ContentSettings', 'CacheThreshold' );
        return ( $value < $threshold );
    }

    /*!
     \static
     Removes all cache files for the node aliases in the list \a $nodeAliasList.
     An alias entry consists of a path to the node using node IDs.
    */
    function subtreeCleanup( $nodeAliasList )
    {
        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();

        foreach ( $nodeAliasList as $node )
        {
            $branch = preg_replace( '@/[^/]+$@', '', $node );
            $alias = $db->escapeString( $branch );

            $entries = $db->arrayQuery( "SELECT cache_file FROM ezsubtree_expiry WHERE subtree LIKE '$alias/%'" );
            foreach ( $entries as $entry )
            {
                @unlink( $entry['cache_file'] );
            }
            $db->query( "DELETE FROM ezsubtree_expiry WHERE subtree LIKE '$alias/%'" );
        }
    }

    function cleanup( $nodeList )
    {
//         print( "cleanup" );
        $ini =& eZINI::instance();
        $cacheBaseDir = eZDir::path( array( eZSys::cacheDirectory(), $ini->variable( 'ContentSettings', 'CacheDir' ) ) );
        $viewModes = $ini->variableArray( 'ContentSettings', 'CachedViewModes' );
        $languages =& eZContentTranslation::fetchLocaleList();
//        $languages = $ini->variableArray( 'ContentSettings', 'TranslationList' );

        $contentINI =& eZINI::instance( "content.ini" );
        if ( $contentINI->hasVariable( 'VersionView', 'AvailableSiteDesigns' ) )
        {
            $siteDesigns = $contentINI->variableArray( 'VersionView', 'AvailableSiteDesigns' );
        }
        else if ( $contentINI->hasVariable( 'VersionView', 'AvailableSiteDesignList' ) )
        {
            $siteDesigns = $contentINI->variable( 'VersionView', 'AvailableSiteDesignList' );
        }

//         eZDebug::writeDebug( $viewModes, 'viewmodes' );
//         eZDebug::writeDebug( $siteDesigns, 'siteDesigns' );
//         eZDebug::writeDebug( $languages, 'languages' );
//         eZDebug::writeDebug( $nodeList, 'nodeList' );
        foreach ( $siteDesigns as $siteDesign )
        {
            foreach ( $viewModes as $viewMode )
            {
                foreach ( $languages as $language )
                {
                    foreach ( $nodeList as $nodeID )
                    {
                        $extraPath = eZDir::filenamePath( "$nodeID" );
                        $cacheDir = eZDir::path( array( $cacheBaseDir, $siteDesign, $viewMode, $language, $extraPath ) );
//                     eZDebug::writeDebug( $cacheDir, 'cacheDir' );
                        if ( !file_exists( $cacheDir ) )
                            continue;
//                     eZDebug::writeDebug( "$cacheDir exists", 'cacheDir' );
                        $dir = opendir( $cacheDir );
                        if ( !$dir )
                            continue;
                        while ( ( $file = readdir( $dir ) ) !== false )
                        {
                            if ( $file == '.' or
                                 $file == '..' )
                                continue;
                            if ( preg_match( "/^$nodeID" . "-.*\\.cache$/", $file ) )
                            {
                                $cacheFile = eZDir::path( array( $cacheDir, $file ) );
                                eZDebugSetting::writeDebug( 'kernel-content-view-cache', "Removing cache file '$cacheFile'", 'eZContentCache::cleanup' );
                                unlink( $cacheFile );
                                // Write log message to storage.log
                                include_once( 'lib/ezutils/classes/ezlog.php' );
                                eZLog::writeStorageLog( $cacheFile );
                            }
                        }
                        closedir( $dir );
                    }
                }
            }
        }
    }

}

?>
