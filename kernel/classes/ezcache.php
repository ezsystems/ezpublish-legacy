<?php
//
// Definition of eZCache class
//
// Created on: <09-Oct-2003 15:24:36 amos>
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
            $cacheList = array( array( 'name' => 'Content view cache',
                                       'id' => 'content',
                                       'tag' => array( 'content' ),
                                       'enabled' => $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled',
                                       'path' => $ini->variable( 'ContentSettings', 'CacheDir' ) ),
                                array( 'name' => 'Global INI cache',
                                       'id' => 'global_ini',
                                       'tag' => array( 'ini' ),
                                       'enabled' => true,
                                       'path' => 'var/cache/ini',
                                       'function' => 'eZCacheClearGlobalINI' ),
                                array( 'name' => 'INI cache',
                                       'id' => 'ini',
                                       'tag' => array( 'ini' ),
                                       'enabled' => true,
                                       'path' => 'ini' ),
                                array( 'name' => 'Codepage cache',
                                       'id' => 'codepage',
                                       'tag' => array( 'codepage' ),
                                       'enabled' => true,
                                       'path' => 'codepages' ),
                                array( 'name' => 'Expiry cache',
                                       'id' => 'expiry',
                                       'tag' => array( 'content', 'template' ),
                                       'enabled' => true,
                                       'path' => 'expiry.php' ),
                                array( 'name' => 'Class identifier cache',
                                       'id' => 'classid',
                                       'tag' => array( 'content' ),
                                       'enabled' => true,
                                       'path' => false,
                                       'function' => 'eZCacheClearClassID'),
                                array( 'name' => 'Sort key cache',
                                       'id' => 'sortkey',
                                       'tag' => array( 'content' ),
                                       'enabled' => true,
                                       'path' => false,
                                       'function' => 'eZCacheClearSortKey' ),
                                array( 'name' => 'URL alias cache',
                                       'id' => 'urlalias',
                                       'tag' => array( 'content' ),
                                       'enabled' => true,
                                       'path' => 'wildcard' ),
                                array( 'name' => 'Image alias',
                                       'id' => 'imagealias',
                                       'tag' => array( 'image' ),
                                       'path' => false,
                                       'enabled' => true,
                                       'function' => 'eZCacheClearImageAlias' ),
                                array( 'name' => 'Template cache',
                                       'id' => 'template',
                                       'tag' => array( 'template' ),
                                       'enabled' => $ini->variable( 'TemplateSettings', 'TemplateCompile' ) == 'enabled',
                                       'path' => 'template' ),
                                array( 'name' => 'Template block cache',
                                       'id' => 'template-block',
                                       'tag' => array( 'template', 'content' ),
                                       'enabled' => $ini->variable( 'TemplateSettings', 'TemplateCache' ) == 'enabled',
                                       'path' => 'template-block',
                                       'function' => 'eZCacheClearTemplateBlockCache' ),
                                array( 'name' => 'Template override cache',
                                       'id' => 'template-override',
                                       'tag' => array( 'template' ),
                                       'enabled' => true,
                                       'path' => 'override' ),
                                array( 'name' => 'RSS cache',
                                       'id' => 'rss_cache',
                                       'tag' => array( 'content' ),
                                       'enabled' => true,
                                       'path' => 'rss' )
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
            $cachePath = eZSys::cacheDirectory() . "/" . $cacheItem['path'];
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
        $expiryHandler = eZExpiryHandler::instance();
        $expiryHandler->setTimestamp( 'image-manager-alias', time() );
        $expiryHandler->store();
    }

    /*!
     \private
     Removes all template block cache files and subtree entries.
    */
    function clearTemplateBlockCache( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory() . "/" . $cacheItem['path'];
        if ( is_file( $cachePath ) )
        {
            $handler =& eZFileHandler::instance( false );
            $handler->unlink( $cachePath );
        }
        else
        {
            eZDir::recursiveDelete( $cachePath );
        }

        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();

        $rows = $db->arrayQuery( "SELECT count( cache_file ) AS count FROM ezsubtree_expiry" );
        $count = $rows[0]['count'];
        $offset = 0;
        $limit = 50;
        while ( $offset < $count )
        {
            $entries = $db->arrayQuery( "SELECT cache_file FROM ezsubtree_expiry", array( 'offset' => $offset, 'limit' => $limit ) );
            if ( count( $entries ) == 0 )
                break;
            foreach ( $entries as $entry )
            {
                @unlink( $entry['cache_file'] );
            }
            $offset += count( $entries );
        }

        $db->query( "DELETE FROM ezsubtree_expiry" );
    }

    /*!
     \private
     Clears all content class identifier cache files from var/cache.
    */
    function clearClassID( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory();

        $files = array();
        if ( $dh = opendir( $cachePath ) )
        {
            while ( false !== ( $file = readdir( $dh ) ) )
            {
                if ( $file != "." && $file != ".." )
                {
                    $files[] = $file;
                }
            }
            closedir( $dh );
        }

        foreach ( $files as $file )
        {
            if ( strpos( $file, 'classidentifiers_' ) === 0 )
                unlink( "$cachePath/$file" );
        }
    }

    /*!
     \private
     Clears all datatype sortkey cache files from var/cache.
    */
    function clearSortKey( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory();

        $files = array();
        if ( $dh = opendir( $cachePath ) )
        {
            while ( false !== ( $file = readdir( $dh ) ) )
            {
                if ( $file != "." && $file != ".." )
                {
                    $files[] = $file;
                }
            }
            closedir( $dh );
        }

        foreach ( $files as $file )
        {
            if ( strpos( $file, 'sortkey_' ) === 0 )
                unlink( "$cachePath/$file" );
        }
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
