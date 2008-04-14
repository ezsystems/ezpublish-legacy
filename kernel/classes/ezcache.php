<?php
//
// Definition of eZCache class
//
// Created on: <09-Oct-2003 15:24:36 amos>
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

/*! \file ezcache.php
*/

/*!
  \class eZCache ezcache.php
  \brief Main class for dealing with caches in eZ publish.

  Has methods for clearing the various caches according
  to tag, id or all caches. It also has information for all the caches.

*/

include_once( 'lib/ezfile/classes/ezfilehandler.php' );
include_once( 'lib/ezfile/classes/ezdir.php' );
include_once( 'kernel/common/i18n.php' );

class eZCache
{
    /*!
     Constructor
    */
    function eZCache()
    {
    }

    /*!
     \static
     \return a list of all cache items in the system.
    */
    function fetchList()
    {
        $cacheList =& $GLOBALS['eZCacheList'];
        if ( !isset( $cacheList ) )
        {
            $ini =& eZINI::instance();
            $cacheList = array( array( 'name' => ezi18n( 'kernel/cache', 'Content view cache' ),
                                       'id' => 'content',
                                       'tag' => array( 'content' ),
                                       'enabled' => $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled',
                                       'path' => $ini->variable( 'ContentSettings', 'CacheDir' ) ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Global INI cache' ),
                                       'id' => 'global_ini',
                                       'tag' => array( 'ini' ),
                                       'enabled' => true,
                                       'path' => 'var/cache/ini',
                                       'function' => 'eZCacheClearGlobalINI' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'INI cache' ),
                                       'id' => 'ini',
                                       'tag' => array( 'ini' ),
                                       'enabled' => true,
                                       'path' => 'ini' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Codepage cache' ),
                                       'id' => 'codepage',
                                       'tag' => array( 'codepage' ),
                                       'enabled' => true,
                                       'path' => 'codepages' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Expiry cache' ),
                                       'id' => 'expiry',
                                       'tag' => array( 'content', 'template' ),
                                       'enabled' => true,
                                       'path' => 'expiry.php' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Class identifier cache' ),
                                       'id' => 'classid',
                                       'tag' => array( 'content' ),
                                       'expiry-key' => 'class-identifier-cache',
                                       'enabled' => true,
                                       'path' => false,
                                       'function' => 'eZCacheClearClassID'),
                                array( 'name' => ezi18n( 'kernel/cache', 'Sort key cache' ),
                                       'id' => 'sortkey',
                                       'tag' => array( 'content' ),
                                       'expiry-key' => 'sort-key-cache',
                                       'enabled' => true,
                                       'path' => false,
                                       'function' => 'eZCacheClearSortKey' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'URL alias cache' ),
                                       'id' => 'urlalias',
                                       'tag' => array( 'content' ),
                                       'enabled' => true,
                                       'path' => 'wildcard' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Character transformation cache' ),
                                       'id' => 'chartrans',
                                       'tag' => array( 'i18n' ),
                                       'enabled' => true,
                                       'path' => 'trans' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Image alias' ),
                                       'id' => 'imagealias',
                                       'tag' => array( 'image' ),
                                       'path' => false,
                                       'enabled' => true,
                                       'function' => 'eZCacheClearImageAlias' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Template cache' ),
                                       'id' => 'template',
                                       'tag' => array( 'template' ),
                                       'enabled' => $ini->variable( 'TemplateSettings', 'TemplateCompile' ) == 'enabled',
                                       'path' => 'template' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Template block cache' ),
                                       'id' => 'template-block',
                                       'tag' => array( 'template', 'content' ),
                                       'enabled' => $ini->variable( 'TemplateSettings', 'TemplateCache' ) == 'enabled',
                                       'path' => 'template-block',
                                       'function' => 'eZCacheClearTemplateBlockCache' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Template override cache' ),
                                       'id' => 'template-override',
                                       'tag' => array( 'template' ),
                                       'enabled' => true,
                                       'path' => 'override' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'RSS cache' ),
                                       'id' => 'rss_cache',
                                       'tag' => array( 'content' ),
                                       'enabled' => true,
                                       'path' => 'rss' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'User info cache' ),
                                       'id' => 'user_info_cache',
                                       'tag' => array( 'user' ),
                                       'enabled' => true,
                                       'path' => 'user-info' )
                                );
        }
        return $cacheList;
    }

    /*!
     \static
     Goes through the cache info list \a $cacheInfoList and finds all the unique tags.
     \return An array with tag strings.
     \param $cacheInfoList If \c false the list will automatically be fetched, if multiple
                           eZCache functions are called it is a good idea to call
                           fetchList() yourself and pass it as a parameter.
    */
    function fetchTagList( $cacheInfoList = false )
    {
        if ( !$cacheInfoList )
            $cacheInfoList = eZCache::fetchList();

        $tagEntries = array();
        foreach ( $cacheInfoList as $cacheInfo )
        {
            $tagList = $cacheInfo['tag'];
            if ( $tagList !== false )
                $tagEntries = array_merge( $tagEntries, $tagList );
        }
        return array_unique( $tagEntries );
    }

    /*!
     \static
     Goes through the cache info list \a $cacheInfoList and finds all the unique ids.
     \return An array with id strings.
     \param $cacheInfoList If \c false the list will automatically be fetched, if multiple
                           eZCache functions are called it is a good idea to call
                           fetchList() yourself and pass it as a parameter.
    */
    function fetchIDList( $cacheInfoList = false )
    {
        if ( !$cacheInfoList )
            $cacheInfoList = eZCache::fetchList();

        $idList = array();
        foreach ( $cacheInfoList as $cacheInfo )
        {
            $idList[] = $cacheInfo['id'];
        }
        return $idList;
    }

    /*!
     \static
     Finds all cache entries using tag \a $tagName.
     \return An array with cache items.
    */
    function fetchByTag( $tagName, $cacheInfoList = false )
    {
        if ( !$cacheInfoList )
            $cacheInfoList = eZCache::fetchList();

        $cacheEntries = array();
        foreach ( $cacheInfoList as $cacheInfo )
        {
            $tagList = $cacheInfo['tag'];
            if ( $tagList !== false and in_array( $tagName, $tagList ) )
                $cacheEntries[] = $cacheInfo;
        }
        return $cacheEntries;
    }

    /*!
     \static
     Finds the first entry with the ID \a $id.
     \return The cache info structure.
    */
    function fetchByID( $id, $cacheInfoList = false )
    {
        if ( !$cacheInfoList )
            $cacheInfoList = eZCache::fetchList();

        foreach ( $cacheInfoList as $cacheInfo )
        {
            if ( $cacheInfo['id'] == $id )
                return $cacheInfo;
        }
        return false;
    }

    /*!
     \static
     Finds the entries matching and ID in the list \a $idList.
     \return An array with cache info structures.
    */
    function fetchByIDList( $idList, $cacheInfoList = false )
    {
        if ( !$cacheInfoList )
            $cacheInfoList = eZCache::fetchList();

        $cacheList = array();
        foreach ( $cacheInfoList as $cacheInfo )
        {
            if ( in_array( $cacheInfo['id'], $idList ) )
                $cacheList[] = $cacheInfo;
        }
        return $cacheList;
    }

    /*!
     \static
     Clears all cache items.
    */
    function clearAll( $cacheList = false )
    {
        if ( !$cacheList )
            $cacheList = eZCache::fetchList();

        foreach ( $cacheList as $cacheItem )
        {
            eZCache::clearItem( $cacheItem );
        }
        return true;
    }

    /*!
     \static
     Finds all cache item which has the tag \a $tagName and clears them.
    */
    function clearByTag( $tagName, $cacheList = false )
    {
        if ( !$cacheList )
            $cacheList = eZCache::fetchList();

        $cacheItems = array();
        foreach ( $cacheList as $cacheItem )
        {
            if ( in_array( $tagName, $cacheItem['tag'] ) )
                $cacheItems[] = $cacheItem;
        }
        foreach ( $cacheItems as $cacheItem )
        {
            eZCache::clearItem( $cacheItem );
        }
        return true;
    }

    /*!
     \static
     Finds all cache item which has ID equal to one of the IDs in \a $idList.
     You can also submit a single id to \a $idList.
    */
    function clearByID( $idList, $cacheList = false )
    {
        if ( !$cacheList )
            $cacheList = eZCache::fetchList();

        $cacheItems = array();
        if ( !is_array( $idList ) )
            $idList = array( $idList );
        foreach ( $cacheList as $cacheItem )
        {
            if ( in_array( $cacheItem['id'], $idList ) )
                $cacheItems[] = $cacheItem;
        }
        foreach ( $cacheItems as $cacheItem )
        {
            eZCache::clearItem( $cacheItem );
        }
        return true;
    }

    /*!
     \private
     \static
     Clears the cache item \a $cacheItem.
    */
    function clearItem( $cacheItem )
    {
        if ( isset( $cacheItem['function'] ) )
        {
            $function= $cacheItem['function'];
            $function( $cacheItem );
        }
        else
        {
            // VS-DBFILE

            $cachePath = eZSys::cacheDirectory() . "/" . $cacheItem['path'];

            switch ( $cacheItem['id'] )
            {
                case 'template-block':
                case 'expiry':
                case 'content':
                case 'urlalias': // wildcard cache
                case 'rss_cache':
                case 'user_info_cache':
                    $isContentRelated = true;
                    break;
                default:
                    $isContentRelated = false;
            }

            if ( $isContentRelated )
            {
                require_once( 'kernel/classes/ezclusterfilehandler.php' );
                $fileHandler = eZClusterFileHandler::instance();
                $fileHandler->fileDelete( $cachePath );
                return;
            }

            if ( is_file( $cachePath ) )
            {
                $handler =& eZFileHandler::instance( false );
                $handler->unlink( $cachePath );
            }
            else
            {
                eZDir::recursiveDelete( $cachePath );
            }
        }
    }

    /*!
     \private
     Sets the image alias timestamp to the current timestamp,
     this causes all image aliases to be recreated on viewing.
    */
    function clearImageAlias( $cacheItem )
    {
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $expiryHandler =& eZExpiryHandler::instance();
        $expiryHandler->setTimestamp( 'image-manager-alias', time() );
        $expiryHandler->store();
    }

    /*!
     \private
     Removes all template block cache files and subtree entries.
    */
    function clearTemplateBlockCache( $cacheItem )
    {
        // remove existing cache
        $cachePath = eZSys::cacheDirectory() . "/" . $cacheItem['path'];

        // VS-DBFILE

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileDelete( $cachePath );

        // remove expiried 'subtree' cache
        include_once( 'kernel/classes/ezsubtreecache.php' );
        eZSubtreeCache::removeAllExpiryCacheFromDisk();
    }

    /*!
     \private
     Clears all content class identifier cache files from var/cache.
    */
    function clearClassID( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory();

        // VS-DBFILE

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileDeleteByRegex( $cachePath, 'class(attribute)?identifiers_.+$' );
    }

    /*!
     \private
     Clears all datatype sortkey cache files from var/cache.
    */
    function clearSortKey( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory();

        // VS-DBFILE

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileDeleteByRegex( $cachePath, 'sortkey_.+$' );
    }

    /*!
     \static
     Clear global ini cache
    */
    function clearGlobalINICache()
    {
        eZDir::recursiveDelete( 'var/cache/ini' );
    }
}

/*!
  Helper function for eZCache::clearImageAlias.
  \note Static functions in classes cannot be used as callback functions in PHP 4, that is why we need this helper.
*/
function eZCacheClearImageAlias( $cacheItem )
{
    eZCache::clearImageAlias( $cacheItem );
}

/*!
  Helper function for eZCache::clearClassID.
  \note Static functions in classes cannot be used as callback functions in PHP 4, that is why we need this helper.
*/
function eZCacheClearClassID( $cacheItem )
{
    eZCache::clearClassID( $cacheItem );
}

/*!
  Helper function for eZCache::clearGlobalINICache.
  \note Static functions in classes cannot be used as callback functions in PHP 4, that is why we need this helper.
*/
function eZCacheClearGlobalINI( $cacheItem )
{
    eZCache::clearGlobalINICache();
}


/*!
  Helper function for eZCache::clearSortKey.
  \note Static functions in classes cannot be used as callback functions in PHP 4, that is why we need this helper.
*/
function eZCacheClearSortKey( $cacheItem )
{
    eZCache::clearSortKey( $cacheItem );
}

/*!
  Helper function for eZCache::clearTemplateBlockCache.
  \note Static functions in classes cannot be used as callback functions in PHP 4, that is why we need this helper.
*/
function eZCacheClearTemplateBlockCache( $cacheItem )
{
    eZCache::clearTemplateBlockCache( $cacheItem );
}

?>
