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

/*! \file ezcontentcache.php
*/

/*!
  \class eZContentCache ezcontentcache.php
  \brief The class eZContentCache does

*/

include_once( 'lib/ezutils/classes/ezsys.php' );
include_once( 'lib/ezutils/classes/ezdir.php' );

// The timestamp for the cache format, will expire
// cache which differs from this.
define( 'EZ_CONTENT_CACHE_CODE_DATE', 1054816011 );

class eZContentCache
{
    /*!
     Constructor
    */
    function eZContentCache()
    {
    }

    function cachePathInfo( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList, $layout, $cacheTTL = false )
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
        $md5Text = md5( implode( '-', $md5Input ) );
        $cacheFile = $nodeID . '-' . $md5Text . '.php';
        $extraPath = eZDir::filenamePath( "$nodeID" );
        $ini =& eZINI::instance();
        $cacheDir = eZDir::path( array( eZSys::cacheDirectory(), $ini->variable( 'ContentSettings', 'CacheDir' ), $siteDesign, $viewMode, $language, $extraPath ) );
        $cachePath = eZDir::path( array( $cacheDir, $cacheFile ) );
        return array( 'dir' => $cacheDir,
                      'file' => $cacheFile,
                      'path' => $cachePath );
    }

    function exists( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList, $layout )
    {
        $cachePathInfo = eZContentCache::cachePathInfo( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList, $layout );
        $cacheExists = file_exists( $cachePathInfo['path'] );
        if ( $cacheExists )
        {
            $timestamp = filemtime( $cachePathInfo['path'] );
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
        return $cacheExists;
    }

    function restore( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList, $layout )
    {
        $result = array();
        $cachePathInfo = eZContentCache::cachePathInfo( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList, $layout );
        $cacheDir = $cachePathInfo['dir'];
        $cacheFile = $cachePathInfo['file'];
        $timestamp = false;
        $cachePath = $cachePathInfo['path'];

        if ( file_exists( $cachePath ) )
        {
            $timestamp = filemtime( $cachePath );
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
        eZDebugSetting::writeDebug( 'kernel-content-view-cache', 'cache used #2' );
        include_once( 'lib/ezutils/classes/ezphpcreator.php' );
        $php = new eZPHPCreator( $cacheDir, $cacheFile );

        $values =& $php->restore( array( 'content_info' => 'contentInfo',
                                         'content_path' => 'contentPath',
                                         'content_data' => 'contentData',
                                         'node_id' => 'nodeID',
                                         'section_id' => 'sectionID',
                                         'cache_ttl' => array( 'name' => 'cache_ttl',
                                                               'required' => false ),
                                         'cache_code_date' => array( 'name' => 'eZContentCacheCodeDate',
                                                                     'required' => false,
                                                                     'default' => false ),
                                         'navigation_part_identifier' => 'navigationPartIdentifier'
                                         ) );

        // Check if ttl is expired
        if ( isset( $values['cache_ttl'] ) )
        {
            $ttlTime = $values['cache_ttl'];

            // Check if cache has expired
            if ( $ttlTime > 0 )
            {
                $expiryTime = $timestamp + $ttlTime;
                if ( mktime() > $expiryTime )
                {
                    return false;
                }
            }
        }

        // Check for template language timestamp
        $cacheCodeDate = $values['cache_code_date'];
        if ( $cacheCodeDate != EZ_CONTENT_CACHE_CODE_DATE )
            return false;

        $viewMode = $values['content_info']['viewmode'];

        $res =& eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'node', $nodeID ),
                              array( 'view_offset', $offset ),
                              array( 'viewmode', $viewMode )
                              ) );
        $result['content_info'] = $values['content_info'];
        $result['content'] = $values['content_data'];
        if ( isset( $values['content_path'] ) )
            $result['path'] = $values['content_path'];

        if ( isset( $values['node_id'] ) )
        {
            $result['node_id'] = $values['node_id'];
        }

        if ( isset( $values['section_id'] ) )
        {
            $result['section_id'] = $values['section_id'];
        }

        if ( isset( $values['navigation_part_identifier'] ) )
        {
            $result['navigation_part'] = $values['navigation_part_identifier'];
        }

        return $result;
    }

    function store( $siteDesign, $objectID, $classID,
                    $nodeID, $parentNodeID, $nodeDepth, $urlAlias, $viewMode, $sectionID,
                    $language, $offset, $roleList, $discountList, $layout, $navigationPartIdentifier,
                    $result, $cacheTTL = 0 )
    {
        $cachePathInfo = eZContentCache::cachePathInfo( $siteDesign, $nodeID, $viewMode, $language, $offset, $roleList, $discountList, $layout );
        $cacheDir = $cachePathInfo['dir'];
        $cacheFile = $cachePathInfo['file'];
        include_once( 'lib/ezutils/classes/ezphpcreator.php' );
        $php = new eZPHPCreator( $cacheDir, $cacheFile );
        $contentInfo = array( 'site_design' => $siteDesign,
                              'node_id' => $nodeID,
                              'parent_node_id' => $parentNodeID,
                              'node_depth' => $nodeDepth,
                              'url_alias' => $urlAlias,
                              'object_id' => $objectID,
                              'class_id' => $classID,
                              'section_id' => $sectionID,
                              'navigation_part_identifier' => $navigationPartIdentifier,
                              'viewmode' => $viewMode,
                              'language' => $language,
                              'offset' => $offset,
                              'role_list' => $roleList,
                              'discount_list' => $discountList,
                              'section_id' => $result['section_id'] );
        $php->addVariable( 'contentInfo', $contentInfo );
        if ( isset( $result['path'] ) )
        {
            $php->addVariable( 'contentPath', $result['path'] );
        }

        if ( isset( $result['node_id'] ) )
        {
            $php->addVariable( 'nodeID', $result['node_id'] );
        }

        if ( isset( $result['section_id'] ) )
        {
            $php->addVariable( 'sectionID', $result['section_id'] );
        }

        if ( isset( $result['navigation_part'] ) )
        {
            $php->addVariable( 'navigationPartIdentifier', $result['navigation_part'] );
        }

        if ( isset( $cacheTTL ) and ( $cacheTTL !=  -1 ) )
        {
            $php->addVariable( 'cache_ttl', $cacheTTL );
        }

        $php->addComment( 'Timestamp for the cache format' );
        $php->addVariable( 'eZContentCacheCodeDate', EZ_CONTENT_CACHE_CODE_DATE );

        $php->addSpace();
        $php->addCodePiece( "ob_start();\n" );
        $php->addText( $result['content'] );
        $php->addCodePiece( "\$contentData = ob_get_contents();\n" );
        $php->addCodePiece( "ob_end_clean();\n" );
        $php->addSpace();
        $php->addCodePiece( "include_once( 'kernel/classes/ezsection.php' );\n" .
                            "eZSection::setGlobalID( \$contentInfo['section_id'] );\n" );

        return $php->store();
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
                            if ( preg_match( "/^$nodeID" . "-.*\\.php$/", $file ) )
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
