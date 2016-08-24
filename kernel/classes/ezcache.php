<?php
/**
 * File containing the {@link eZCache} class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * eZCache ezcache.php
 *
 * Main class for dealing with caches in eZ Publish.
 *
 * Has methods for clearing the various caches according
 * to tag, id or all caches. It also has information for all the caches.
 *
 * @package kernel
 */
class eZCache
{
    /**
     * Return a list of all default cache items in the system.
     *
     * @return array The list of cache items
     */
    static function fetchList()
    {
        $all = self::fetchAll();
        $list = array();
        foreach ($all as $item)
        {
            if ( !isset( $item['default'] ) || $item['default'] )
            {
                $list[] = $item;
            }
        }
        return $list;
    }

    /**
     * Return a list of all non-default cache items in the system.
     *
     * @return array The list of cache items
     */
    static function fetchNonDefault()
    {
        $all = self::fetchAll();
        $list = array();
        foreach ($all as $item)
        {
            if ( isset( $item['default'] ) && !$item['default'] )
            {
                $list[] = $item;
            }
        }
        return $list;
    }

    /**
     * Return a list of all cache items in the system.
     *
     * @return array The list of cache items
     */
    static function fetchAll()
    {
        static $cacheList = null;
        if ( $cacheList === null )
        {
            $ini = eZINI::instance();
            $textToImageIni = eZINI::instance( 'texttoimage.ini' );
            $cacheList = array( array( 'name' => ezpI18n::tr( 'kernel/cache', 'Content view cache' ),
                                       'id' => 'content',
                                       'is-clustered' => true,
                                       'tag' => array( 'content' ),
                                       'expiry-key' => 'content-view-cache',
                                       'enabled' => $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled',
                                       'path' => $ini->variable( 'ContentSettings', 'CacheDir' ),
                                       'function' => array( 'eZCache', 'clearContentCache' ) ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Global INI cache' ),
                                       'id' => 'global_ini',
                                       'tag' => array( 'ini' ),
                                       'enabled' => true,
                                       'path' => eZSys::iniCachePath(),
                                       'function' => array( 'eZCache', 'clearGlobalINICache' ),
                                       'purge-function' => array( 'eZCache', 'clearGlobalINICache' ) ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'INI cache' ),
                                       'id' => 'ini',
                                       'tag' => array( 'ini' ),
                                       'enabled' => true,
                                       'path' => 'ini' ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Codepage cache' ),
                                       'id' => 'codepage',
                                       'tag' => array( 'codepage' ),
                                       'enabled' => true,
                                       'path' => 'codepages' ),
/* Entry is disabled since it does not make sense to remove it, it is not a cache file.
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Expiry cache' ),
                                       'id' => 'expiry',
                                       'tag' => array( 'content', 'template' ),
                                       'enabled' => true,
                                       'path' => 'expiry.php',
                                       'function' => array( 'eZCache', 'clearExpiry' ) ),*/
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Class identifier cache' ),
                                       'id' => 'classid',
                                       'tag' => array( 'content' ),
                                       'expiry-key' => 'class-identifier-cache',
                                       'enabled' => true,
                                       'path' => false,
                                       'is-clustered' => true,
                                       'function' => array( 'eZCache', 'clearClassID' ),
                                       'purge-function' => array( 'eZCache', 'clearClassID' ) ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Sort key cache' ),
                                       'id' => 'sortkey',
                                       'tag' => array( 'content' ),
                                       'expiry-key' => 'sort-key-cache',
                                       'enabled' => true,
                                       'path' => false,
                                       'function' => array( 'eZCache', 'clearSortKey' ),
                                       'purge-function' => array( 'eZCache', 'clearSortKey' ),
                                       'is-clustered' => true ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'URL alias cache' ),
                                       'id' => 'urlalias',
                                       'is-clustered' => true,
                                       'tag' => array( 'content' ),
                                       'enabled' => true,
                                       'path' => 'wildcard' ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Character transformation cache' ),
                                       'id' => 'chartrans',
                                       'tag' => array( 'i18n' ),
                                       'enabled' => true,
                                       'path' => 'trans' ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Image alias' ),
                                       'id' => 'imagealias',
                                       'tag' => array( 'image' ),
                                       'path' => false,
                                       'enabled' => true,
                                       // imagealias should not be cleared by default as it is quite expensive
                                       'default' => false,
                                       'function' => array( 'eZCache', 'clearImageAlias' ),
                                       'purge-function' => array( 'eZCache', 'purgeImageAlias' ),
                                       'is-clustered' => true ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Template cache' ),
                                       'id' => 'template',
                                       'tag' => array( 'template' ),
                                       'enabled' => $ini->variable( 'TemplateSettings', 'TemplateCompile' ) == 'enabled',
                                       'path' => 'template',
                                       'function' => array( 'eZCache', 'clearTemplateCompileCache' ),
                                       'purge-function' => array( 'eZCache', 'clearTemplateCompileCache' ) ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Template block cache' ),
                                       'id' => 'template-block',
                                       'is-clustered' => true,
                                       'tag' => array( 'template', 'content' ),
                                       'expiry-key' => 'global-template-block-cache',
                                       'enabled' => $ini->variable( 'TemplateSettings', 'TemplateCache' ) == 'enabled',
                                       'path' => 'template-block',
                                       'function' => array( 'eZCache', 'clearTemplateBlockCache' ) ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Template override cache' ),
                                       'id' => 'template-override',
                                       'tag' => array( 'template' ),
                                       'enabled' => true,
                                       'path' => 'override',
                                       'function' => array( 'eZCache', 'clearTemplateOverrideCache' ) ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Text to image cache' ),
                                       'id' => 'texttoimage',
                                       'tag' => array( 'template' ),
                                       'enabled' => $textToImageIni->variable( 'ImageSettings', 'UseCache' ) == 'enabled',
                                       'path' => $textToImageIni->variable( 'PathSettings', 'CacheDir' ),
                                       'function' => array( 'eZCache', 'clearTextToImageCache' ),
                                       'purge-function' => array( 'eZCache', 'purgeTextToImageCache' ),
                                       'is-clustered' => true ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'RSS cache' ),
                                       'id' => 'rss_cache',
                                       'is-clustered' => true,
                                       'tag' => array( 'content' ),
                                       'enabled' => true,
                                       'path' => 'rss' ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'User info cache' ),
                                       'id' => 'user_info_cache',
                                       'is-clustered' => true,
                                       'tag' => array( 'user' ),
                                       'expiry-key' => 'user-info-cache',
                                       'enabled' => true,
                                       'path' => 'user-info',
                                       'function' => array( 'eZCache', 'clearUserInfoCache' ) ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Content tree menu (browser cache)' ),
                                       'id' => 'content_tree_menu',
                                       'tag' => array( 'content' ),
                                       'path' => false,
                                       'enabled' => true,
                                       'function' => array( 'eZCache', 'clearContentTreeMenu' ),
                                       'purge-function' => array( 'eZCache', 'clearContentTreeMenu' ) ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'State limitations cache' ),
                                       'is-clustered' => true,
                                       'id' => 'state_limitations',
                                       'tag' => array( 'content' ),
                                       'expiry-key' => 'state-limitations',
                                       'enabled' => true,
                                       'path' => false,
                                       'function' => array( 'eZCache', 'clearStateLimitations' ),
                                       'purge-function' => array( 'eZCache', 'clearStateLimitations' ) ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Content Language cache' ),
                                       'is-clustered' => true,
                                       'id' => 'content_language',
                                       'tag' => array( 'content' ),
                                       'enabled' => true,
                                       'path' => false,
                                       'function' => array( 'eZContentLanguage', 'expireCache' ),
                                       'purge-function' => array( 'eZContentLanguage', 'expireCache' ) ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Design base cache' ),
                                       'id' => 'design_base',
                                       'tag' => array( 'template' ),
                                       'enabled' => $ini->variable( 'DesignSettings', 'DesignLocationCache' ) == 'enabled',
                                       'path' => false,
                                       'function' => array( 'eZCache', 'clearDesignBaseCache' ),
                                       'purge-function' => array( 'eZCache', 'clearDesignBaseCache' ) ),
                                /**
                                 * caches the list of active extensions (per siteaccess and global)
                                 * @see eZExtension::activeExtensions()
                                 */
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'Active extensions cache' ),
                                       'id' => 'active_extensions',
                                       'tag' => array( 'ini' ),
                                       'expiry-key' => 'active-extensions-cache',
                                       'enabled' => true,
                                       'path' => false,
                                       'function' => array( 'eZCache', 'clearActiveExtensions' ),
                                       'purge-function' => array( 'eZCache', 'clearActiveExtensions' ) ),

                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'TS Translation cache' ),
                                       'id' => 'translation',
                                       'tag' => array( 'i18n' ),
                                       'enabled' => true,
                                       'expiry-key' => 'ts-translation-cache',
                                       'path' => 'translation',
                                       'function' => array( 'eZCache', 'clearTSTranslationCache' )
                                ),
                                array( 'name' => ezpI18n::tr( 'kernel/cache', 'SSL Zones cache' ),
                                       'id' => 'sslzones',
                                       'tag' => array( 'ini' ),
                                       'enabled' => eZSSLZone::enabled(),
                                       'path' => false,
                                       'function' => array( 'eZSSLZone', 'clearCache' ),
                                       'purge-function' => array( 'eZSSLZone', 'clearCache' )
                                ),
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

                if ( $ini->hasVariable( $name, 'default' ) )
                    $cacheItem['default'] = $ini->variable( $name, 'default' );
                else
                    $cacheItem['default'] = true;

                if ( $ini->hasVariable( $name, 'class' ) )
                    $cacheItem['function'] = array( $ini->variable( $name, 'class' ), 'clearCache' );

                if ( $ini->hasVariable( $name, 'purgeClass' ) )
                    $cacheItem['purge-function'] = array( $ini->variable( $name, 'purgeClass' ), 'purgeCache' );

                $cacheList[] = $cacheItem;
            }
        }
        return $cacheList;
    }

    /**
     * Goes through the cache info list $cacheInfoList and finds all the unique tags.
     *
     * @param bool|array $cacheInfoList If false the list will automatically be fetched, if multiple
     * eZCache functions are called it is a good idea to call fetchList() yourself and pass it as a parameter.
     *
     * @return array An array with tag strings.
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

    /**
     * Goes through the cache info list $cacheInfoList and finds all the unique ids.
     *
     * @param bool|array $cacheInfoList If false the list will automatically be fetched, if multiple
     * eZCache functions are called it is a good idea to call fetchList() yourself and pass it as a parameter.
     *
     * @return array An array with id strings.
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

    /**
     * Finds all cache entries using tag $tagName.
     *
     * @param string $tagName The tag name, or comma-separated list of names
     * @param bool|array $cacheInfoList The list of cache info per entry
     * @return array An array with cache items.
     */
    static function fetchByTag( $tagName, $cacheInfoList = false )
    {
        if ( !$cacheInfoList )
            $cacheInfoList = eZCache::fetchList();

        $cacheEntries = array();
        $tagList = explode( ',', $tagName );
        foreach ( $cacheInfoList as $cacheInfo )
        {
            if ( !is_array( $cacheInfo['tag'] ) )
                continue;

            $matchingTags = array_intersect( $tagList, $cacheInfo['tag'] );
            if ( !empty( $matchingTags ) )
                $cacheEntries[] = $cacheInfo;
        }

        return $cacheEntries;
    }

    /**
     * Finds the first entry with the ID $id.
     *
     * @param string $id The entry id.
     * @param array|bool The list of cache info per entry
     * @return array The cache info structure.
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

    /**
     * Finds the entries matching and ID in the list \a $idList.
     *
     * @param array $idList The list of cache ID
     * @param array|bool The list of cache info per entry
     * @return array An array with cache info structures.
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

    /**
     * Clears all cache items.
     *
     * @param bool $cacheList
     * @return bool True
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

    /**
     * Finds all cache item which has the tag \a $tagName and clears them.
     *
     * @param string $tagName The tag name
     * @param bool|array $cacheList The list of caches, default false
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

    /**
     * Finds all cache item which has ID equal to one of the IDs in $idList.
     * You can also submit a single id to $idList.
     *
     * @param array $idList The cache ID list
     * @param bool|array $cacheList The list of caches, default false
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

    /**
     *
     * Clears or purges the cache item $cacheItem.
     *
     * If $purge is true then the system will ensure the entries are removed from
     * local storage or database backend, otherwise it will use possible optimizations
     * which might only invalidate the cache entry directly or use global expiry values.
     *
     * @param $cacheItem Cache item array taken from fetchList()
     * @param $purge     Controls whether clearing/invalidation or purge is used.
     * @param $reporter  Callback which is called when the system has purged files from the system, called with filename and purge count as parameters.
     * @param $iterationSleep The amount of microseconds to sleep between each purge iteration, false means no sleep.
     * @param $iterationMax   The maximum number of items to purge in one iteration, false means use default limit.
     * @param $expiry         A timestamp which is matched against all cache items, if the modification of the cache is older than the expiry the cache is purged, false means no expiry checking.
     */
    static function clearItem( $cacheItem, $purge = false, $reporter = false, $iterationSleep = false, $iterationMax = false, $expiry = false )
    {
        // Get the global expiry value if one is set and compare it with supplied $expiry value.
        // Use the largest value of the two.
        if ( isset( $cacheItem['expiry-key'] ) )
        {
            $key = $cacheItem['expiry-key'];
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
        $functionName = false;
        if ( $purge && isset( $cacheItem['purge-function'] ) )
            $functionName = 'purge-function';
        else if ( !$purge && isset( $cacheItem['function'] ) )
            $functionName = 'function';
        if ( $functionName )
        {
            $function = $cacheItem[$functionName];
            if ( is_callable( $function ) )
                call_user_func_array( $function, array( $cacheItem ) );
            else
                eZDebug::writeError("Could not call cache item $functionName for id '$cacheItem[id]', is it a static public function?", __METHOD__ );
        }
        else
        {
            if ( !isset( $cacheItem['path'] ) || strlen( $cacheItem['path'] ) < 1 )
            {
                eZDebug::writeError( "No path specified for cache item '$cacheItem[name]', can not clear cache.", __METHOD__ );
                return;
            }

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

            if ( !file_exists( $cachePath ) )
            {
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

    /**
     * Sets the image alias timestamp to the current timestamp,
     * this causes all image aliases to be recreated on viewing.
     */
    static function clearImageAlias( $cacheItem )
    {
        $expiryHandler = eZExpiryHandler::instance();
        $expiryHandler->setTimestamp( 'image-manager-alias', time() );
        $expiryHandler->store();
        ezpEvent::getInstance()->notify( 'image/invalidateAliases' );
    }

    /**
     * Purges the image aliases of all ezimage attribute. The original image is
     * kept.
     *
     * @param array $cacheItem
     * @access public
     */
    static function purgeImageAlias( $cacheItem )
    {
        // 1. fetch ezcontentclass having an ezimage attribute
        // 2. fetch objects of these classes
        // 3. purge image alias for all version

        $imageContentClassAttributes = eZContentClassAttribute::fetchList(
            true,
            array(
                'data_type' => 'ezimage',
                'version' => eZContentClass::VERSION_STATUS_DEFINED
            )
        );
        $classIds = array();
        $attributeIdentifiersByClass = array();
        foreach ( $imageContentClassAttributes as $ccAttr )
        {
            $identifier = $ccAttr->attribute( 'identifier' );
            $ccId = $ccAttr->attribute( 'contentclass_id' );
            if ( !isset( $attributeIdentifiersByClass[$ccId] ) )
            {
                $attributeIdentifiersByClass[$ccId] = array();
            }
            $attributeIdentifiersByClass[$ccId][] = $identifier;
            $classIds[] = $ccId;

        }

        $subTreeParams = array(
            'ClassFilterType' => 'include',
            'ClassFilterArray' => $classIds,
            'MainNodeOnly' => true,
            'IgnoreVisibility' => true,
            'LoadDataMap' => false,
            'Limit' => 100,
            'Offset' => 0
        );
        $count = 0;
        while ( true )
        {
            $nodes = eZContentObjectTreeNode::subTreeByNodeID( $subTreeParams, 1 );
            if ( empty( $nodes ) )
            {
                break;
            }
            foreach ( $nodes as $node )
            {
                call_user_func( $cacheItem['reporter'], '', $count );
                $object = $node->attribute( 'object' );
                self::purgeImageAliasForObject(
                    $cacheItem, $object, $attributeIdentifiersByClass[$object->attribute( 'contentclass_id' )]
                );
                $count++;
            }
            eZContentObject::clearCache();
            $subTreeParams['Offset'] += $subTreeParams['Limit'];
        }
        self::clearImageAlias( $cacheItem );
    }

    /**
     * The purge the image aliases in all versions of the content object.
     *
     * @param array $cacheItem
     * @param eZContentObject $object
     * @param array $imageIdentifiers array of ezimage attribute identifiers
     */
    private static function purgeImageAliasForObject( array $cacheItem, eZContentObject $object, array $imageIdentifiers )
    {
        $versions = $object->attribute( 'versions' );
        foreach ( $versions as $version )
        {
            $dataMap = $version->attribute( 'data_map' );
            foreach ( $imageIdentifiers as $identifier )
            {
                $attr = $dataMap[$identifier];
                if ( !$attr instanceof eZContentObjectAttribute )
                {
                    eZDebug::writeError( "Missing attribute $identifier in object " . $object->attribute( 'id' ) . ", version " . $version->attribute( 'version' ) . ". This indicates data corruption.", __METHOD__ );
                }
                elseif ( $attr->attribute( 'has_content' ) )
                {
                    $attr->attribute( 'content' )->purgeAllAliases();
                }
            }
        }
    }

    /**
     * Sets the content tree menu timestamp to the current date and time,
     * this is used as a GET parameter in the content/treemenu requests and thus
     * forces a browser to load the content tree menu from a server rather than
     * to use a cached copy.
     */
    static function clearContentTreeMenu( $cacheItem )
    {
        $expiryHandler = eZExpiryHandler::instance();
        $expiryHandler->setTimestamp( 'content-tree-menu', time() );
        $expiryHandler->store();
    }

    /**
     * Removes all template block cache files and subtree entries.
     */
    static function clearTemplateBlockCache( $cacheItem )
    {
        $expiryHandler = eZExpiryHandler::instance();
        $expiryHandler->setTimestamp( 'global-template-block-cache', time() );
        $expiryHandler->store();
    }

    /**
     * Removes all template override cache files, subtree entries
     * and clears in memory override cache.
     *
     * @since 4.2
    */
    static function clearTemplateOverrideCache( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory() . '/' . $cacheItem['path'];
        eZDir::recursiveDelete( $cachePath );
        eZTemplateDesignResource::clearInMemoryOverrideArray();
    }

    /**
     * Clears all content class identifier cache files from var/cache.
     */
    static function clearClassID( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory();

        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileDelete( $cachePath, 'classidentifiers_' );
        $fileHandler->fileDelete( $cachePath, 'classattributeidentifiers_' );
        eZContentClass::expireCache();
        ezpEvent::getInstance()->notify( 'content/class/cache/all' );
    }

    /**
     * Clears all datatype sortkey cache files from var/cache.
     */
    static function clearSortKey( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory();

        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileDelete( $cachePath, 'sortkey_' );
    }

    /**
     * Clears all user-info caches by setting a new expiry value for the key *user-info-cache*.
     */
    static function clearUserInfoCache( $cacheItem )
    {
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-info-cache', time() );
        $handler->store();
        ezpEvent::getInstance()->notify( 'user/cache/all' );
    }

    /**
     * Clears all content caches by setting a new expiry value for the key *content-view-cache*.
     */
    static function clearContentCache( $cacheItem )
    {
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-view-cache', time() );
        $handler->store();
        ezpEvent::getInstance()->notify( 'content/cache/all' );
    }

    /**
     * Clear global ini cache
     */
    static function clearGlobalINICache( $cacheItem )
    {
        eZDir::recursiveDelete( $cacheItem['path'] );
    }

    /**
     * Clear Template Compile cache
     */
    static function clearTemplateCompileCache()
    {
        eZDir::recursiveDelete( eZTemplateCompiler::compilationDirectory() );
    }

    /**
     * Clear texttoimage cache
     */
    static function clearTextToImageCache( $cacheItem )
    {
        $fileHandler = eZClusterFileHandler::instance( $cacheItem['path'] );
        $fileHandler->delete();
    }

    /**
     * Purge texttoimage cache
     */
    static function purgeTextToImageCache( $cacheItem )
    {
        $fileHandler = eZClusterFileHandler::instance( $cacheItem['path'] );
        $fileHandler->purge();
    }

    /**
     * Clears all state limitation cache files.
     */
    static function clearStateLimitations( $cacheItem )
    {
        $cachePath = eZSys::cacheDirectory();

        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileDelete( $cachePath, 'statelimitations_' );
        ezpEvent::getInstance()->notify( 'content/state/cache/all' );
    }

    /**
     * Clears active extensions list cache
     */
    static function clearActiveExtensions( $cacheItem )
    {
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( $cacheItem['expiry-key'], time() );
        $handler->store();

        eZExtension::clearActiveExtensionsCache();
        eZExtension::clearActiveExtensionsMemoryCache();
    }

    /**
     * Clears the design base cache
     *
     * @param $cacheItem array the cache item that describes the cache tag/id
     */
    public static function clearDesignBaseCache( $cacheItem )
    {
        eZClusterFileHandler::instance()->fileDelete(
            eZSys::cacheDirectory(),
            eZTemplateDesignResource::DESIGN_BASE_CACHE_NAME
        );
    }

    /**
     * Clears the .ts translation cache
     * @param array $cacheItem
     * @return void
     */
    public static function clearTSTranslationCache( $cacheItem )
    {
        eZTSTranslator::expireCache();
    }
}
