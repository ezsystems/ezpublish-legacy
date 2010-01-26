<?php
//
// Definition of eZCache class
//
// Created on: <09-Oct-2003 15:24:36 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
  \class eZCache ezcache.php
  \brief Main class for dealing with caches in eZ Publish.

  Has methods for clearing the various caches according
  to tag, id or all caches. It also has information for all the caches.

*/

require_once( 'kernel/common/i18n.php' );

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
    static function fetchList()
    {
        static $cacheList = null;
        if ( $cacheList === null )
        {
            $ini = eZINI::instance();
            $textToImageIni = eZINI::instance( 'texttoimage.ini' );
            $cacheList = array( array( 'name' => ezi18n( 'kernel/cache', 'Content view cache' ),
                                       'id' => 'content',
                                       'is-clustered' => true,
                                       'tag' => array( 'content' ),
                                       'expiry-key' => 'content-view-cache',
                                       'enabled' => $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled',
                                       'path' => $ini->variable( 'ContentSettings', 'CacheDir' ),
                                       'function' => array( 'eZCache', 'clearContentCache' ) ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Global INI cache' ),
                                       'id' => 'global_ini',
                                       'tag' => array( 'ini' ),
                                       'enabled' => true,
                                       'path' => 'var/cache/ini',
                                       'function' => array( 'eZCache', 'clearGlobalINICache' ) ),
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
/* Entry is disabled since it does not make sense to remove it, it is not a cache file.
                                array( 'name' => ezi18n( 'kernel/cache', 'Expiry cache' ),
                                       'id' => 'expiry',
                                       'tag' => array( 'content', 'template' ),
                                       'enabled' => true,
                                       'path' => 'expiry.php',
                                       'function' => array( 'eZCache', 'clearExpiry' ) ),*/
                                array( 'name' => ezi18n( 'kernel/cache', 'Class identifier cache' ),
                                       'id' => 'classid',
                                       'tag' => array( 'content' ),
                                       'expiry-key' => 'class-identifier-cache',
                                       'enabled' => true,
                                       'path' => false,
                                       'function' => array( 'eZCache', 'clearClassID' ) ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Sort key cache' ),
                                       'id' => 'sortkey',
                                       'tag' => array( 'content' ),
                                       'expiry-key' => 'sort-key-cache',
                                       'enabled' => true,
                                       'path' => false,
                                       'function' => array( 'eZCache', 'clearSortKey' ) ),
                                array( 'name' => ezi18n( 'kernel/cache', 'URL alias cache' ),
                                       'id' => 'urlalias',
                                       'is-clustered' => true,
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
                                       'function' => array( 'eZCache', 'clearImageAlias' ) ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Template cache' ),
                                       'id' => 'template',
                                       'tag' => array( 'template' ),
                                       'enabled' => $ini->variable( 'TemplateSettings', 'TemplateCompile' ) == 'enabled',
                                       'path' => 'template' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Template block cache' ),
                                       'id' => 'template-block',
                                       'is-clustered' => true,
                                       'tag' => array( 'template', 'content' ),
                                       'expiry-key' => 'global-template-block-cache',
                                       'enabled' => $ini->variable( 'TemplateSettings', 'TemplateCache' ) == 'enabled',
                                       'path' => 'template-block',
                                       'function' => array( 'eZCache', 'clearTemplateBlockCache' ) ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Template override cache' ),
                                       'id' => 'template-override',
                                       'tag' => array( 'template' ),
                                       'enabled' => true,
                                       'path' => 'override',
                                       'function' => array( 'eZCache', 'clearTemplateOverrideCache' ) ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Text to image cache' ),
                                       'id' => 'texttoimage',
                                       'tag' => array( 'template' ),
                                       'enabled' => $textToImageIni->variable( 'ImageSettings', 'UseCache' ) == 'enabled',
                                       'path' => $textToImageIni->variable( 'PathSettings', 'CacheDir' ),
                                       'function' => array( 'eZCache', 'clearTextToImageCache' ),
                                       'purge-function' => array( 'eZCache', 'purgeTextToImageCache' ) ),
                                array( 'name' => ezi18n( 'kernel/cache', 'RSS cache' ),
                                       'id' => 'rss_cache',
                                       'is-clustered' => true,
                                       'tag' => array( 'content' ),
                                       'enabled' => true,
                                       'path' => 'rss' ),
                                array( 'name' => ezi18n( 'kernel/cache', 'User info cache' ),
                                       'id' => 'user_info_cache',
                                       'is-clustered' => true,
                                       'tag' => array( 'user' ),
                                       'expiry-key' => 'user-access-cache',
                                       'enabled' => true,
                                       'path' => 'user-info',
                                       'function' => array( 'eZCache', 'clearUserInfoCache' ) ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Content tree menu (browser cache)' ),
                                       'id' => 'content_tree_menu',
                                       'tag' => array( 'content' ),
                                       'path' => false,
                                       'enabled' => true,
                                       'function' => array( 'eZCache', 'clearContentTreeMenu' ) ),
                                array( 'name' => ezi18n( 'kernel/cache', 'State limitations cache' ),
                                       'id' => 'state_limitations',
                                       'tag' => array( 'content' ),
                                       'expiry-key' => 'state-limitations',
                                       'enabled' => true,
                                       'path' => false,
                                       'function' => array( 'eZCache', 'clearStateLimitations' ) ),
                                array( 'name' => ezi18n( 'kernel/cache', 'Design base cache' ),
                                       'id' => 'design_base',
                                       'tag' => array( 'template' ),
                                       'enabled' => true,
                                       'path' => false,
                                       'function' => array( 'eZCache', 'clearDesignBaseCache' ) )
                                );

            // Append cache items defined (in ini) by extensions, see site.ini[Cache] for details
            foreach ( $ini->variable( 'Cache', 'CacheItems' ) as $cacheItemKey )
            {
            	$name = 'Cache_' . $cacheItemKey;
            	if ( !$ini->hasSection( $name ) )
            	{
            		eZDebug::writeWarning( "Missing site.ini section: '$name', skipping!", __METHOD__ );
            		continue;
            	}

            	$cacheItem = array();
            	if ( $ini->hasVariable( $name, 'name' ) )
            	    $cacheItem['name'] = $ini->variable( $name, 'name' );
                else
                    $cacheItem['name'] = ucwords( $cacheItemKey );

            	if ( $ini->hasVariable( $name, 'id' ) )
            	    $cacheItem['id'] = $ini->variable( $name, 'id' );
                else
                    $cacheItem['id'] = $cacheItemKey;

            	if ( $ini->hasVariable( $name, 'isClustered' ) )
            	    $cacheItem['is-clustered'] = $ini->variable( $name, 'isClustered' );
                else
                    $cacheItem['is-clustered'] = false;

            	if ( $ini->hasVariable( $name, 'tags' ) )
            	    $cacheItem['tag'] = $ini->variable( $name, 'tags' );
                else
                    $cacheItem['tag'] = array();

            	if ( $ini->hasVariable( $name, 'expiryKey' ) )
            	    $cacheItem['expiry-key'] = $ini->variable( $name, 'expiryKey' );

            	if ( $ini->hasVariable( $name, 'enabled' ) )
            	    $cacheItem['enabled'] = $ini->variable( $name, 'enabled' );
                else
                    $cacheItem['enabled'] = true;

            	if ( $ini->hasVariable( $name, 'path' ) )
            	    $cacheItem['path'] = $ini->variable( $name, 'path' );
                else
                    $cacheItem['path'] = false;

            	if ( $ini->hasVariable( $name, 'class' ) )
            	    $cacheItem['function'] = array( $ini->variable( $name, 'class' ), 'clearCache' );

            	if ( $ini->hasVariable( $name, 'purgeClass' ) )
            	    $cacheItem['purge-function'] = array( $ini->variable( $name, 'purgeClass' ), 'purgeCache' );

            	$cacheList[] = $cacheItem;
            }
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
    static function fetchTagList( $cacheInfoList = false )
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
    static function fetchIDList( $cacheInfoList = false )
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
    static function fetchByTag( $tagName, $cacheInfoList = false )
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
    static function fetchByID( $id, $cacheInfoList = false )
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
    static function fetchByIDList( $idList, $cacheInfoList = false )
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
    static function clearAll( $cacheList = false )
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
    static function clearByTag( $tagName, $cacheList = false )
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
    static function clearByID( $idList, $cacheList = false )
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
     Clears or purges the cache item \a $cacheItem.

     If $purge is true then the system will ensure the entries are removed from local storage or database backend, otherwise it will use possible optimizations which might only invalidate the cache entry directly or use global expiry values.

     \param $cacheItem Cache item array taken from fetchList()
     \param $purge     Controls whether clearing/invalidation or purge is used.
     \param $reporter  Callback which is called when the system has purged files from the system, called with filename and purge count as parameters.
     \param $iterationSleep The amount of microseconds to sleep between each purge iteration, false means no sleep.
     \param $iterationMax   The maximum number of items to purge in one iteration, false means use default limit.
     \param $expiry         A timestamp which is matched against all cache items, if the modification of the cache is older than the expiry the cache is purged, false means no expiry checking.
    */
    static function clearItem( $cacheItem, $purge = false, $reporter = false, $iterationSleep = false, $iterationMax = false, $expiry = false )
    {
        // Get the global expiry value if one is set and compare it with supplied $expiry value.
        // Use the largest value of the two.
        if ( isset( $cacheItem['expiry-key'] ) )
        {
            $key = $cacheItem['expiry-key'];
            eZExpiryHandler::registerShutdownFunction();
            $expiryHandler = eZExpiryHandler::instance();
            $keyValue = $expiryHandler->getTimestamp( $key );
            if ( $keyValue !== false )
            {
                if ( $expiry !== false )
                    $expiry = max( $expiry, $keyValue );
                else
                    $expiry = $keyValue;
            }
        }

        $cacheItem['purge']          = $purge;
        $cacheItem['reporter']       = $reporter;
        $cacheItem['iterationSleep'] = $iterationSleep;
        $cacheItem['iterationMax']   = $iterationMax;
        $cacheItem['expiry']         = $expiry;
        $functionName = 'function';
        if ( $purge )
            $functionName = 'purge-function';
        if ( isset( $cacheItem[$functionName] ) )
        {
            $function = $cacheItem[$functionName];
            if ( is_callable( $function ) )
                call_user_func_array( $function, array( $cacheItem ) );
            else
                eZDebug::writeError("Could not call cache item $functionName for id '$cacheItem[id]', is it a static public function?", __METHOD__ );
        }
        else
        {
            $cachePath = eZSys::cacheDirectory() . "/" . $cacheItem['path'];

            if ( isset( $cacheItem['is-clustered'] ) )
                $isClustered = $cacheItem['is-clustered'];
            else
                $isClustered = false;

            if ( $isClustered )
            {
                $fileHandler = eZClusterFileHandler::instance( $cachePath );
                if ( $purge )
                    $fileHandler->purge( $reporter, $iterationSleep, $iterationMax, $expiry );
                else
                    $fileHandler->delete();
                return;
            }

            if ( is_file( $cachePath ) )
            {
                $handler = eZFileHandler::instance( false );
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
     \static
     Sets the image alias timestamp to the current timestamp,
     this causes all image aliases to be recreated on viewing.
    */
    static function clearImageAlias( $cacheItem )
    {
        eZExpiryHandler::registerShutdownFunction();
        $expiryHandler = eZExpiryHandler::instance();
        $expiryHandler->setTimestamp( 'image-manager-alias', time() );
        $expiryHandler->store();
    }

    /*!
     \private
     \static
     Sets the content tree menu timestamp to the current date and time,
     this is used as a GET parameter in the content/treemenu requests and thus
     forces a browser to load the content tree menu from a server rather than
     to use a cached copy.
    */
    static function clearContentTreeMenu( $cacheItem )
    {
        eZExpiryHandler::registerShutdownFunction();
        $expiryHandler = eZExpiryHandler::instance();
        $expiryHandler->setTimestamp( 'content-tree-menu', time() );
        $expiryHandler->store();
    }

    /*!
     \private
     \static
     Removes all template block cache files and subtree entries.
    */
    static function clearTemplateBlockCache( $cacheItem )
    {
        eZExpiryHandler::registerShutdownFunction();
        $expiryHandler = eZExpiryHandler::instance();
        $expiryHandler->setTimestamp( 'global-template-block-cache', time() );
        $expiryHandler->store();
    }

    /**
     * Removes all template override cache files, subtree entries
     * and clears in memory override cache.
     *
     * @static
     * @since 4.2
     * @access private
    */
    static function clearTemplateOverrideCache( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory() . '/' . $cacheItem['path'];
        eZDir::recursiveDelete( $cachePath );
        eZTemplateDesignResource::clearInMemoryOverrideArray();
    }

    /*!
     \private
     \static
     Clears all content class identifier cache files from var/cache.
    */
    static function clearClassID( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory();

        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileDelete( $cachePath, 'classidentifiers_' );
        $fileHandler->fileDelete( $cachePath, 'classattributeidentifiers_' );
    }

    /*!
     \private
     \static
     Clears all datatype sortkey cache files from var/cache.
    */
    static function clearSortKey( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory();

        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileDelete( $cachePath, 'sortkey_' );
    }

    /*!
     \private
     \static
     Clears the expiry cache file *var/cache/expiry.php*.
    */
/* Code is disabled since it does not make sense to remove it, it is not a cache file.
    function clearExpiry( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory();

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $fileHandler = eZClusterFileHandler::instance( $cachePath . '/expiry.php' );
        $fileHandler->delete();
        $fileHandler->deleteLocal();
    }*/

    /*!
     \private
     \static
     Clears all user-info caches by setting a new expiry value for the key *user-access-cache*.
    */
    static function clearUserInfoCache( $cacheItem )
    {
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-access-cache', time() );
        $handler->store();
    }

    /*!
     \private
     \static
     Clears all content caches by setting a new expiry value for the key *content-view-cache*.
    */
    static function clearContentCache( $cacheItem )
    {
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-view-cache', time() );
        $handler->store();
    }

    /*!
     \private
     \static
     Clear global ini cache
    */
    static function clearGlobalINICache( $cacheItem )
    {
        eZDir::recursiveDelete( $cacheItem['path'] );
    }

    /*!
     \private
     \static
     Clear texttoimage cache
    */
    static function clearTextToImageCache( $cacheItem )
    {
        $fileHandler = eZClusterFileHandler::instance( $cacheItem['path'] );
        $fileHandler->delete();
    }

    /*!
     \private
     \static
     Purge texttoimage cache
    */
    static function purgeTextToImageCache( $cacheItem )
    {
        $fileHandler = eZClusterFileHandler::instance( $cacheItem['path'] );
        $fileHandler->purge();
    }

    /*!
     \private
     \static
     Clears all state limitation cache files.
    */
    static function clearStateLimitations( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory();

        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileDelete( $cachePath, 'statelimitations_' );
    }

    /**
     * Clears the design base cache
     *
     * @param $cacheItem array the cache item that describes the cache tag/id
     */
    public static function clearDesignBaseCache( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory();

        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileDeleteByWildcard( $cachePath . '/' . eZTemplateDesignResource::DESIGN_BASE_CACHE_NAME . '*' );
    }
}

/*!
  Helper function for eZCache::clearImageAlias.
  \note Static functions in classes cannot be used as callback functions in PHP 4, that is why we need this helper.
  \deprecated Callback to static class function is now done directly.
*/
function eZCacheClearImageAlias( $cacheItem )
{
    eZCache::clearImageAlias( $cacheItem );
}

/*!
  Helper function for eZCache::clearClassID.
  \note Static functions in classes cannot be used as callback functions in PHP 4, that is why we need this helper.
  \deprecated Callback to static class function is now done directly.
*/
function eZCacheClearClassID( $cacheItem )
{
    eZCache::clearClassID( $cacheItem );
}

/*!
  Helper function for eZCache::clearGlobalINICache.
  \note Static functions in classes cannot be used as callback functions in PHP 4, that is why we need this helper.
  \deprecated Callback to static class function is now done directly.
*/
function eZCacheClearGlobalINI( $cacheItem )
{
    eZCache::clearGlobalINICache( $cacheItem );
}


/*!
  Helper function for eZCache::clearSortKey.
  \note Static functions in classes cannot be used as callback functions in PHP 4, that is why we need this helper.
  \deprecated Callback to static class function is now done directly.
*/
function eZCacheClearSortKey( $cacheItem )
{
    eZCache::clearSortKey( $cacheItem );
}

/*!
  Helper function for eZCache::clearTemplateBlockCache.
  \note Static functions in classes cannot be used as callback functions in PHP 4, that is why we need this helper.
  \deprecated Callback to static class function is now done directly.
*/
function eZCacheClearTemplateBlockCache( $cacheItem )
{
    eZCache::clearTemplateBlockCache( $cacheItem );
}

/*!
  Helper function for eZCache::clearContentTreeMenu.
  \note Static functions in classes cannot be used as callback functions in PHP 4, that is why we need this helper.
  \deprecated Callback to static class function is now done directly.
*/
function eZCacheClearContentTreeMenu( $cacheItem )
{
    eZCache::clearContentTreeMenu( $cacheItem );
}

?>