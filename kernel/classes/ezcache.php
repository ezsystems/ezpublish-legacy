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
                                       'path' => 'template-block' ),
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
     Clears all cache items.
    */
    function clearAll()
    {
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
    function clearByTag( $tagName )
    {
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
    function clearByID( $idList )
    {
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
     Sets the image alias timestamp to the current timestamp,
     this causes all image aliases to be recreated on viewing.
    */
    function clearImageAlias( $cacheItem )
    {
        $expiryHandler = eZExpiryHandler::instance();
        $expiryHandler->setTimestamp( 'image-manager-alias', time() );
        $expiryHandler->store();
    }

    /*!
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
 Helper function for eZCache::clearImageAlias
*/
function eZCacheClearImageAlias( $cacheItem )
{
    eZCache::clearImageAlias( $cacheItem );
}

/*!
 */
function eZCacheClearClassID( $cacheItem )
{
    eZCache::clearClassID( $cacheItem );
}

/*!
 */
function eZCacheClearGlobalINI( $cacheItem )
{
    eZCache::clearGlobalINICache();
}


/*!
 */
function eZCacheClearSortKey( $cacheItem )
{
    eZCache::clearSortKey( $cacheItem );
}

?>
