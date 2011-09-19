<?php
/**
 * File containing the eZStaticCache class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * The eZStaticCache class manages the static cache system.
 *
 * This class can be used to generate static cache files usable
 * by the static cache system.
 *
 * Generating static cache is done by instantiating the class and then
 * calling generateCache(). For example:
 *
 * <code>
 * $staticCache = new eZStaticCache();
 * $staticCache->generateCache();
 * </code>
 *
 * To generate the URLs that must always be updated call generateAlwaysUpdatedCache()
 *
 * @package kernel
 */
class eZStaticCache implements ezpStaticCache
{
    /**
     * User-Agent string
     */
    const USER_AGENT = 'eZ Publish static cache generator';

    private static $actionList = array();

    /**
     * The name of the host to fetch HTML data from.
     *
     * @deprecated deprecated since version 4.4, site.ini.[SiteSettings].SiteURL is used instead
     * @var string
     */
    private $hostName;

    /**
     * The base path for the directory where static files are placed.
     *
     * @var string
     */
    private $staticStorage;

    /**
     * The maximum depth of URLs that will be cached.
     *
     * @var int
     */
    private $maxCacheDepth;

    /**
     * Array of URLs to cache.
     *
     * @var array(int=>string)
     */
    private $cachedURLArray = array();

    /**
     * An array with siteaccesses names that will be cached.
     *
     * @var array(int=>string)
     */
    private $cachedSiteAccesses = array();

    /**
     * An array with URLs that is to always be updated.
     *
     * @var array(int=>string)
     */
    private $alwaysUpdate;

    /**
     *  Initialises the static cache object with settings from staticcache.ini.
     */
    public function __construct()
    {
        $ini = eZINI::instance( 'staticcache.ini');
        $this->hostName = $ini->variable( 'CacheSettings', 'HostName' );
        $this->staticStorageDir = $ini->variable( 'CacheSettings', 'StaticStorageDir' );
        $this->maxCacheDepth = $ini->variable( 'CacheSettings', 'MaxCacheDepth' );
        $this->cachedURLArray = $ini->variable( 'CacheSettings', 'CachedURLArray' );
        $this->cachedSiteAccesses = $ini->variable( 'CacheSettings', 'CachedSiteAccesses' );
        $this->alwaysUpdate = $ini->variable( 'CacheSettings', 'AlwaysUpdateArray' );
    }

    /**
     * Getter method for {@link eZStaticCache::$hostName}
     *
     * @deprecated deprecated since version 4.4
     * @return string The currently configured host-name.
     */
    public function hostName()
    {
        return $this->hostName;
    }

    /**
     * Getter method for {@link eZStaticCache::$staticStorageDir}
     *
     * @return string The currently configured storage directory for the static cache.
     */
    public function storageDirectory()
    {
        return $this->staticStorageDir;
    }

    /**
     * Getter method for {@link eZStaticCache::$maxCacheDepth}
     *
     * @return int The maximum depth in the url which will be cached.
     */
    public function maxCacheDepth()
    {
        return $this->maxCacheDepth;
    }

    /**
     * Getter method for {@link eZStaticCache::$cachedSiteAccesses}
     *
     * @return array An array with site-access names that should be cached.
     */
    public function cachedSiteAccesses()
    {
        return $this->cachedSiteAccesses;
    }

    /**
     * Getter method for {@link eZStaticCache::$cachedURLArray}
     *
     * @return array An array with URLs that is to be cached statically, the URLs may contain wildcards.
     */
    public function cachedURLArray()
    {
        return $this->cachedURLArray;
    }

    /**
     * Getter method for {@link eZStaticCache::$alwaysUpdate}
     *
     * These URLs are configured with AlwaysUpdateArray in staticcache.ini.
     *
     * @see eZStaticCache::generateAlwaysUpdatedCache()
     * @return array An array with URLs that is to always be updated.
     */
    function alwaysUpdateURLArray()
    {
        return $this->alwaysUpdate;
    }

    /**
     * Generates the caches for all URLs that must always be generated.
     *
     * @param bool $quiet If true then the function will not output anything.
     * @param eZCLI|false $cli The eZCLI object or false if no output can be done.
     * @param bool $delay
     */
    public function generateAlwaysUpdatedCache( $quiet = false, $cli = false, $delay = true )
    {
        foreach ( $this->alwaysUpdate as $uri )
        {
            if ( !$quiet and $cli )
                $cli->output( "caching: $uri ", false );
            $this->storeCache( $uri, $this->staticStorageDir, array(), false, $delay );
            if ( !$quiet and $cli )
                $cli->output( "done" );
        }
    }

    /**
     * Generates caches for all the urls of nodes in $nodeList.
     *
     * The associative array must have on of these entries:
     * - node_id - ID of the node
     * - path_identification_string - The path_identification_string from the node table, is used to fetch the node ID if node_id is missing.
     *
     * @param array $nodeList An array with node entries, each entry is either the node ID or an associative array.
     */
    public function generateNodeListCache( $nodeList )
    {
        $db = eZDB::instance();

        foreach ( $nodeList as $uri )
        {
            if ( is_array( $uri ) )
            {
                if ( !isset( $uri['node_id'] ) )
                {
                    eZDebug::writeError( "node_id is not set for uri entry " . var_export( $uri ) . ", will need to perform extra query to get node_id" );
                    $node = eZContentObjectTreeNode::fetchByURLPath( $uri['path_identification_string'] );
                    $nodeID = (int)$node->attribute( 'node_id' );
                }
                else
                {
                    $nodeID = (int)$uri['node_id'];
                }
            }
            else
            {
                $nodeID = (int)$uri;
            }
            $elements = eZURLAliasML::fetchByAction( 'eznode', $nodeID, true, true, true );
            foreach ( $elements as $element )
            {
                $path = $element->getPath();
                $this->cacheURL( '/' . $path );
            }
        }
    }

    /**
     * Generates the static cache from the configured INI settings.
     *
     * @param bool $force If true then it will create all static caches even if it is not outdated.
     * @param bool $quiet If true then the function will not output anything.
     * @param eZCLI|false $cli The eZCLI object or false if no output can be done.
     * @param bool $delay
     */
    public function generateCache( $force = false, $quiet = false, $cli = false, $delay = true )
    {
        $staticURLArray = $this->cachedURLArray();
        $db = eZDB::instance();
        $configSettingCount = count( $staticURLArray );
        $currentSetting = 0;

        // This contains parent elements which must checked to find new urls and put them in $generateList
        // Each entry contains:
        // - url - Url of parent
        // - glob - A glob string to filter direct children based on name
        // - org_url - The original url which was requested
        // - parent_id - The element ID of the parent (optional)
        // The parent_id will be used to quickly fetch the children, if not it will use the url
        $parentList = array();
        // A list of urls which must generated, each entry is a string with the url
        $generateList = array();
        foreach ( $staticURLArray as $url )
        {
            $currentSetting++;
            if ( strpos( $url, '*') === false )
            {
                $generateList[] = $url;
            }
            else
            {
                $queryURL = ltrim( str_replace( '*', '', $url ), '/' );
                $dir = dirname( $queryURL );
                if ( $dir == '.' )
                    $dir = '';
                $glob = basename( $queryURL );
                $parentList[] = array( 'url' => $dir,
                                       'glob' => $glob,
                                       'org_url' => $url );
            }
        }

        // As long as we have urls to generate or parents to check we loop
        while ( count( $generateList ) > 0 || count( $parentList ) > 0 )
        {
            // First generate single urls
            foreach ( $generateList as $generateURL )
            {
                if ( !$quiet and $cli )
                    $cli->output( "caching: $generateURL ", false );
                $this->cacheURL( $generateURL, false, !$force, $delay );
                if ( !$quiet and $cli )
                    $cli->output( "done" );
            }
            $generateList = array();

            // Then check for more data
            $newParentList = array();
            foreach ( $parentList as $parentURL )
            {
                if ( isset( $parentURL['parent_id'] ) )
                {
                    $elements = eZURLAliasML::fetchByParentID( $parentURL['parent_id'], true, true, false );
                    foreach ( $elements as $element )
                    {
                        $path = '/' . $element->getPath();
                        $generateList[] = $path;
                        $newParentList[] = array( 'parent_id' => $element->attribute( 'id' ) );
                    }
                }
                else
                {
                    if ( !$quiet and $cli and $parentURL['glob'] )
                        $cli->output( "wildcard cache: " . $parentURL['url'] . '/' . $parentURL['glob'] . "*" );
                    $elements = eZURLAliasML::fetchByPath( $parentURL['url'], $parentURL['glob'], true, true );
                    foreach ( $elements as $element )
                    {
                        $path = '/' . $element->getPath();
                        $generateList[] = $path;
                        $newParentList[] = array( 'parent_id' => $element->attribute( 'id' ) );
                    }
                }
            }
            $parentList = $newParentList;
        }
    }

    /**
     * Generates the caches for the url $url using the currently configured storageDirectory().
     *
     * @param string $url The URL to cache, e.g /news
     * @param int|false $nodeID The ID of the node to cache, if supplied it will also cache content/view/full/xxx.
     * @param bool $skipExisting If true it will not unlink existing cache files.
     * @return bool
     */
    public function cacheURL( $url, $nodeID = false, $skipExisting = false, $delay = true )
    {
        // Check if URL should be cached
        if ( substr_count( $url, "/") >= $this->maxCacheDepth )
            return false;

        $doCacheURL = false;
        foreach ( $this->cachedURLArray as $cacheURL )
        {
            if ( $url == $cacheURL )
            {
                $doCacheURL = true;
                break;
            }
            else if ( strpos( $cacheURL, '*') !== false )
            {
                if ( strpos( $url, str_replace( '*', '', $cacheURL ) ) === 0 )
                {
                    $doCacheURL = true;
                    break;
                }
            }
        }

        if ( $doCacheURL == false )
        {
            return false;
        }

        $this->storeCache( $url, $this->staticStorageDir, $nodeID ? array( "/content/view/full/$nodeID" ) : array(), $skipExisting, $delay );

        return true;
    }

    /**
     * Stores the static cache for $url and hostname defined in site.ini.[SiteSettings].SiteURL for cached siteaccess
     * by fetching the web page using {@link eZHTTPTool::getDataByURL()} and storing the fetched HTML data.
     *
     * @param string $url The URL to cache, e.g /news
     * @param string $staticStorageDir The base directory for storing cache files.
     * @param array $alternativeStaticLocations
     * @param bool $skipUnlink If true it will not unlink existing cache files.
     * @param bool $delay
     */
    private function storeCache( $url, $staticStorageDir, $alternativeStaticLocations = array(), $skipUnlink = false, $delay = true )
    {
        $http = eZHTTPTool::instance();

        $dirs = array();

        foreach ( $this->cachedSiteAccesses as $cachedSiteAccess )
        {
            $dirs[] = $this->buildCacheDirPath( $cachedSiteAccess );
        }

        foreach ( $dirs as $dirParts )
        {
            foreach ( $dirParts as $dirPart )
            {
                $dir = $dirPart['dir'];
                $siteURL = $dirPart['site_url'];

                $cacheFiles = array();

                $cacheFiles[] = $this->buildCacheFilename( $staticStorageDir, $dir . $url );
                foreach ( $alternativeStaticLocations as $location )
                {
                    $cacheFiles[] = $this->buildCacheFilename( $staticStorageDir, $dir . $location );
                }

                // Store new content
                $content = false;
                foreach ( $cacheFiles as $file )
                {
                    if ( !$skipUnlink || !file_exists( $file ) )
                    {
                        // Deprecated since 4.4, will be removed in future version
                        $fileName = "http://{$this->hostName}{$dir}{$url}";

                        // staticcache.ini.[CacheSettings].HostName has been deprecated since version 4.4
                        // hostname is read from site.ini.[SiteSettings].SiteURL per siteaccess
                        // defined in staticcache.ini.[CacheSettings].CachedSiteAccesses
                        if ( !$this->hostName )
                        {
                            $fileName = "http://{$siteURL}{$url}";
                        }

                        if ( $delay )
                        {
                            $this->addAction( 'store', array( $file, $fileName ) );
                        }
                        else
                        {
                            // Generate content, if required
                            if ( $content === false )
                            {
                                if ( $http->getDataByURL( $fileName, true, eZStaticCache::USER_AGENT ) )
                                    $content = $http->getDataByURL( $fileName, false, eZStaticCache::USER_AGENT );
                            }
                            if ( $content === false )
                            {
                                eZDebug::writeNotice( "Could not grab content (from $fileName), is the hostname correct and Apache running?",
                                                      'Static Cache' );
                            }
                            else
                            {
                                eZStaticCache::storeCachedFile( $file, $content );
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Generates a full path to the cache file (index.html) based on the input parameters.
     *
     * @param string $staticStorageDir The storage for cache files.
     * @param string $url The URL for the current item, e.g /news
     * @return string The full path to the cache file (index.html).
     */
    private function buildCacheFilename( $staticStorageDir, $url )
    {
        $file = "{$staticStorageDir}{$url}/index.html";
        $file = preg_replace( '#//+#', '/', $file );
        return $file;
    }

    /**
     * Generates a cache directory parts including path, siteaccess name, site URL
     * depending on the match order type.
     *
     * @param string $siteAccess
     * @return array
     */
    private function buildCacheDirPath( $siteAccess )
    {
        $dirParts = array();

        $ini = eZINI::instance();

        $matchOderArray = $ini->variableArray( 'SiteAccessSettings', 'MatchOrder' );

        foreach ( $matchOderArray as $matchOrderItem )
        {
            switch ( $matchOrderItem )
            {
                case 'host_uri':
                    foreach ( $ini->variable( 'SiteAccessSettings', 'HostUriMatchMapItems' ) as $hostUriMatchMapItem )
                    {
                        $parts = explode( ';', $hostUriMatchMapItem );

                        if ( $parts[2] === $siteAccess  )
                        {
                            $dirParts[] = $this->buildCacheDirPart( ( $parts[0] ? '/' . $parts[0] : '' ) .
                                                                    ( $parts[1] ? '/' . $parts[1] : '' ), $siteAccess );
                        }
                    }
                    break;
                case 'host':
                    foreach ( $ini->variable( 'SiteAccessSettings', 'HostMatchMapItems' ) as $hostMatchMapItem )
                    {
                        $parts = explode( ';', $hostMatchMapItem );

                        if ( $parts[1] === $siteAccess  )
                        {
                            $dirParts[] = $this->buildCacheDirPart( ( $parts[0] ? '/' . $parts[0] : '' ), $siteAccess );
                        }
                    }
                    break;
                default:
                    $dirParts[] = $this->buildCacheDirPart( '/' . $siteAccess, $siteAccess );
                    break;
            }
        }

        return $dirParts;
    }

    /**
     * A helper method used to create directory parts array
     *
     * @param string $dir
     * @param string $siteAccess
     * @return array
     */
    private function buildCacheDirPart( $dir, $siteAccess )
    {
        return array( 'dir' => $dir,
                      'access_name' => $siteAccess,
                      'site_url' => eZSiteAccess::getIni( $siteAccess, 'site.ini' )->variable( 'SiteSettings', 'SiteURL' ) );
    }

    /**
     * Stores the cache file $file with contents $content.
     * Takes care of setting proper permissions on the new file.
     *
     * @param string $file
     * @param string $content
     */
    static function storeCachedFile( $file, $content )
    {
        $dir = dirname( $file );
        if ( !is_dir( $dir ) )
        {
            eZDir::mkdir( $dir, false, true );
        }

        $oldumask = umask( 0 );

        $tmpFileName = $file . '.' . md5( $file. uniqid( "ezp". getmypid(), true ) );

        // Remove files, this might be necessary for Windows
        @unlink( $tmpFileName );

        // Write the new cache file with the data attached
        $fp = fopen( $tmpFileName, 'w' );
        if ( $fp )
        {
            $comment = ( eZINI::instance( 'staticcache.ini' )->variable( 'CacheSettings', 'AppendGeneratedTime' ) === 'true' ) ? "<!-- Generated: " . date( 'Y-m-d H:i:s' ). " -->\n\n" : null;

            fwrite( $fp, $content . $comment );
            fclose( $fp );
            eZFile::rename( $tmpFileName, $file );

            $perm = eZINI::instance()->variable( 'FileSettings', 'StorageFilePermissions' );
            chmod( $file, octdec( $perm ) );
        }

        umask( $oldumask );
    }

    /**
     * Removes the static cache file (index.html) and its directory if it exists.
     * The directory path is based upon the URL $url and the configured static storage dir.
     *
     * @param string $url The URL for the current item, e.g /news
     */
    function removeURL( $url )
    {
        $dir = eZDir::path( array( $this->staticStorageDir, $url ) );

        @unlink( $dir . "/index.html" );
        @rmdir( $dir );
    }

    /**
     * This function adds an action to the list that is used at the end of the
     * request to remove and regenerate static cache files.
     *
     * @param string $action
     * @param array $parameters
     */
    private function addAction( $action, $parameters )
    {
        self::$actionList[] = array( $action, $parameters );
    }

    /**
     * This function goes over the list of recorded actions and excecutes them.
     */
    static function executeActions()
    {
        if ( empty( self::$actionList ) )
        {
            return;
        }

        $fileContentCache = array();
        $doneDestList = array();

        $ini = eZINI::instance( 'staticcache.ini');
        $clearByCronjob = ( $ini->variable( 'CacheSettings', 'CronjobCacheClear' ) == 'enabled' );

        if ( $clearByCronjob )
        {
            $db = eZDB::instance();
        }

        $http = eZHTTPTool::instance();

        foreach ( self::$actionList as $action )
        {
            list( $action, $parameters ) = $action;

            switch( $action ) {
                case 'store':
                    list( $destination, $source ) = $parameters;

                    if ( isset( $doneDestList[$destination] ) )
                        continue 2;

                    if ( $clearByCronjob )
                    {
                        $param = $db->escapeString( $destination . ',' . $source );
                        $db->query( 'INSERT INTO ezpending_actions( action, param ) VALUES ( \'static_store\', \''. $param . '\' )' );
                        $doneDestList[$destination] = 1;
                    }
                    else
                    {
                        if ( !isset( $fileContentCache[$source] ) )
                        {
                            if ( $http->getDataByURL( $source, true, eZStaticCache::USER_AGENT ) )
                                $fileContentCache[$source] = $http->getDataByURL( $source, false, eZStaticCache::USER_AGENT );
                            else
                                $fileContentCache[$source] = false;
                        }
                        if ( $fileContentCache[$source] === false )
                        {
                            eZDebug::writeNotice( 'Could not grab content, is the hostname correct and Apache running?', 'Static Cache' );
                        }
                        else
                        {
                            eZStaticCache::storeCachedFile( $destination, $fileContentCache[$source] );
                            $doneDestList[$destination] = 1;
                        }
                    }
                    break;
            }
        }
        self::$actionList = array();
    }
}

?>
