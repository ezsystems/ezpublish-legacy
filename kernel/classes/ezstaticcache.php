<?php
//
// Definition of eZStaticClass class
//
// Created on: <12-Jan-2005 10:29:21 dr>
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

/*! \file ezstaticcache.php
*/

/*!
  \class eZStaticCache ezstaticcache.php
  \brief Manages the static cache system.

  This class can be used to generate static cache files usable
  by the static cache system.

  Generating static cache is done by instatiating the class and then
  calling generateCache(). For example:
  \code
  $staticCache = new eZStaticCache();
  $staticCache->generateCache();
  \endcode

  To generate the URLs that must always be updated call generateAlwaysUpdatedCache()

*/

//include_once( 'lib/ezutils/classes/ezini.php' );
//include_once( 'kernel/classes/ezurlaliasml.php' );

class eZStaticCache
{
    const USER_AGENT = 'eZ Publish static cache generator';

    /*!
     Initialises the static cache object with settings from staticcache.ini.
    */
    function eZStaticCache()
    {
        $ini = eZINI::instance( 'staticcache.ini');
        $this->HostName = $ini->variable( 'CacheSettings', 'HostName' );
        $this->StaticStorageDir = $ini->variable( 'CacheSettings', 'StaticStorageDir' );
        $this->MaxCacheDepth = $ini->variable( 'CacheSettings', 'MaxCacheDepth' );
        $this->CachedURLArray = $ini->variable( 'CacheSettings', 'CachedURLArray' );
        $this->CachedSiteAccesses = $ini->variable( 'CacheSettings', 'CachedSiteAccesses' );
        $this->AlwaysUpdate = $ini->variable( 'CacheSettings', 'AlwaysUpdateArray' );
    }

    /*!
     \return The currently configured host-name.
    */
    function hostName()
    {
        return $this->HostName;
    }

    /*!
     \return The currently configured storage directory for the static cache.
    */
    function storageDirectory()
    {
        return $this->StaticStorageDir;
    }

    /*!
     \return The maximum depth in the url which will be cached.
    */
    function maxCacheDepth()
    {
        return $this->MaxCacheDepth;
    }

    /*!
     \return An array with site-access names that should be cached.
    */
    function cachedSiteAccesses()
    {
        return $this->CachedSiteAccesses;
    }

    /*!
     \return An array with URLs that is to be cached statically, the URLs may contain wildcards.
    */
    function cachedURLArray()
    {
        return $this->CachedURLArray;
    }

    /*!
     \return An array with URLs that is to always be updated.
     \note These URLs are configured with \c AlwaysUpdateArray in \c staticcache.ini.
     \sa generateAlwaysUpdatedCache()
    */
    function alwaysUpdateURLArray()
    {
        return $this->AlwaysUpdate;
    }

    /*!
     Generates the caches for all URLs that must always be generated.

     \sa alwaysUpdateURLArray().
    */
    function generateAlwaysUpdatedCache( $quiet = false, $cli = false, $delay = true )
    {
        $hostname = $this->HostName;
        $staticStorageDir = $this->StaticStorageDir;

        foreach ( $this->AlwaysUpdate as $uri )
        {
            if ( !$quiet and $cli )
                $cli->output( "caching: $uri ", false );
            $this->storeCache( $uri, $hostname, $staticStorageDir, array(), false, $delay );
            if ( !$quiet and $cli )
                $cli->output( "done" );
        }
    }

    /*!
     Generates caches for all the urls of nodes in $nodeList.
     $nodeList is an array with node entries, each entry is either the node ID or an associative array.
     The associative array must have on of these entries:
     - node_id - ID of the node
     - path_identification_string - The path_identification_string from the node table, is used to fetch the node ID  if node_id is missing.
     */
    function generateNodeListCache( $nodeList )
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

    /*!
     Generates the static cache from the configured INI settings.

     \param $force If \c true then it will create all static caches even if it is not outdated.
     \param $quiet If \c true then the function will not output anything.
     \param $cli The eZCLI object or \c false if no output can be done.
    */
    function generateCache( $force = false, $quiet = false, $cli = false, $delay = true )
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

    /*!
     \private
     Generates the caches for the url \a $url using the currently configured hostName() and storageDirectory().

     \param $url The URL to cache, e.g \c /news
     \param $nodeID The ID of the node to cache, if supplied it will also cache content/view/full/xxx.
     \param $skipExisting If \c true it will not unlink existing cache files.
    */
    function cacheURL( $url, $nodeID = false, $skipExisting = false, $delay = true )
    {
        // Set default hostname
        $hostname = $this->HostName;
        $staticStorageDir = $this->StaticStorageDir;

        // Check if URL should be cached
        if ( substr_count( $url, "/") >= $this->MaxCacheDepth )
            return false;

        $doCacheURL = false;
        foreach ( $this->CachedURLArray as $cacheURL )
        {
            if ( $url == $cacheURL )
            {
                $doCacheURL = true;
            }
            else if ( strpos( $cacheURL, '*') !== false )
            {
                if ( strpos( $url, str_replace( '*', '', $cacheURL ) ) === 0 )
                {
                    $doCacheURL = true;
                }
            }
        }

        if ( $doCacheURL == false )
        {
            return false;
        }

        $this->storeCache( $url, $hostname, $staticStorageDir, $nodeID ? array( "/content/view/full/$nodeID" ) : array(), $skipExisting, $delay );

        return true;
    }

    /*!
     \private
     Stores the static cache for \a $url and \a $hostname by fetching the web page using
     fopen() and storing the fetched HTML data.

     \param $url The URL to cache, e.g \c /news
     \param $hostname The name of the host which serves web pages dynamically, see hostName().
     \param $staticStorageDir The base directory for storing cache files, see storageDirectory().
     \param $alternativeStaticLocations An array with additional URLs that should also be cached.
     \param $skipUnlink If \c true it will not unlink existing cache files.
    */
    function storeCache( $url, $hostname, $staticStorageDir, $alternativeStaticLocations = array(), $skipUnlink = false, $delay = true )
    {
        if ( is_array( $this->CachedSiteAccesses ) and count ( $this->CachedSiteAccesses ) )
        {
            $dirs = array();
            foreach ( $this->CachedSiteAccesses as $dir )
            {
                $dirs[] = '/' . $dir ;
            }
        }
        else
        {
            $dirs = array ('');
        }
        $http = eZHTTPTool::instance();

        foreach ( $dirs as $dir )
        {
            $cacheFiles = array();
            if ( !is_dir( $staticStorageDir . $dir ) )
            {
                eZDir::mkdir( $staticStorageDir . $dir, 0777, true );
            }

            $cacheFiles[] = $this->buildCacheFilename( $staticStorageDir, $dir . $url );
            foreach ( $alternativeStaticLocations as $location )
            {
                $cacheFiles[] = $this->buildCacheFilename( $staticStorageDir, $dir . $location );
            }

            /* Store new content */
            $content = false;
            foreach ( $cacheFiles as $file )
            {
                if ( !$skipUnlink || !file_exists( $file ) )
                {
                    $fileName = "http://$hostname$dir$url";
                    if ( $delay )
                    {
                        $this->addAction( 'store', array( $file, $fileName ) );
                    }
                    else
                    {
                        /* Generate content, if required */
                        if ( $content === false )
                        {
                            $content = $http->getDataByURL( $fileName, false, USER_AGENT );
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

    /*!
     \private
     \param $staticStorageDir The storage for cache files.
     \param $url The URL for the current item, e.g \c /news
     \return The full path to the cache file (index.html) based on the input parameters.
    */
    function buildCacheFilename( $staticStorageDir, $url )
    {
        $file = "{$staticStorageDir}{$url}/index.html";
        $file = preg_replace( '#//+#', '/', $file );
        return $file;
    }

    /*!
     \private
     \static
     Stores the cache file \a $file with contents \a $content.
     Takes care of setting proper permissions on the new file.
    */
    static function storeCachedFile( $file, $content )
    {
        $dir = dirname( $file );
        if ( !is_dir( $dir ) )
        {
            eZDir::mkdir( $dir, 0777, true );
        }

        $oldumask = umask( 0 );

        $tmpFileName = $file . '.' . md5( $file. uniqid( "ezp". getmypid(), true ) );

        /* Remove files, this might be necessary for Windows */
        @unlink( $tmpFileName );

        /* Write the new cache file with the data attached */
        $fp = fopen( $tmpFileName, 'w' );
        if ( $fp )
        {
            fwrite( $fp, $content . '<!-- Generated: '. date( 'Y-m-d H:i:s' ). " -->\n\n" );
            fclose( $fp );
            //include_once( 'lib/ezfile/classes/ezfile.php' );
            eZFile::rename( $tmpFileName, $file );
        }

        umask( $oldumask );
    }

    /*!
     Removes the static cache file (index.html) and its directory if it exists.
     The directory path is based upon the URL \a $url and the configured static storage dir.
     \param $url The URL for the curren item, e.g \c /news
    */
    function removeURL( $url )
    {
        $dir = eZDir::path( array( $this->StaticStorageDir, $url ) );

        @unlink( $dir . "/index.html" );
        @rmdir( $dir );
    }

    /*!
     \private
     This function adds an action to the list that is used at the end of the
     request to remove and regenerate static cache files.
    */
    function addAction( $action, $parameters )
    {
        if (! isset( $GLOBALS['eZStaticCache-ActionList'] ) ) {
            $GLOBALS['eZStaticCache-ActionList'] = array();
        }
        $GLOBALS['eZStaticCache-ActionList'][] = array( $action, $parameters );
    }

    /*!
     \static
     This function goes over the list of recorded actions and excecutes them.
    */
    static function executeActions()
    {
        if (! isset( $GLOBALS['eZStaticCache-ActionList'] ) ) {
            return;
        }

        $fileContentCache = array();
        $doneDestList = array();

        $ini = eZINI::instance( 'staticcache.ini');
        $clearByCronjob = ( $ini->variable( 'CacheSettings', 'CronjobCacheClear' ) == 'enabled' );

        if ( $clearByCronjob )
        {
            //include_once( "lib/ezdb/classes/ezdb.php" );
            $db = eZDB::instance();
        }

        $http = eZHTTPTool::instance();

        foreach ( $GLOBALS['eZStaticCache-ActionList'] as $action )
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
                            $fileContentCache[$source] = $http->getDataByURL( $source, false, USER_AGENT );
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
        $GLOBALS['eZStaticCache-ActionList'] = array();
    }

    /// \privatesection
    /// The name of the host to fetch HTML data from.
    public $HostName;
    /// The base path for the directory where static files are placed.
    public $StaticStorage;
    /// The maximum depth of URLs that will be cached.
    public $MaxCacheDepth;
    /// Array of URLs to cache.
    public $CachedURLArray;
    /// An array with URLs that is to always be updated.
    public $AlwaysUpdate;
}

?>
