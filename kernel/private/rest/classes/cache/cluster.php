<?php
/**
 * File containing ezpRestCacheStorageCluster class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */
abstract class ezpRestCacheStorageCluster extends ezpRestCacheStorageFile implements ezcCacheStackableStorage, ezcCacheStackMetaDataStorage
{
    /**
     * Flag indicating if cache is enabled or not.
     * This flag should be set by the cache caller.
     * Default is true
     * @var bool
     */
    public $isCacheEnabled = true;

    /**
     * Cluster file handler instance for cache file
     * @var eZClusterFileHandlerInterface
     */
    protected $clusterCacheFile;

    /**
     * Creates a new cache storage for a given location through eZ Publish cluster mechanism
     * Options can contain the 'ttl' ( Time-To-Life ). This is per default set
     * to 1 day.
     * @param string $location Path to the cache location inside the cluster
     * @param array(string=>string) $options Options for the cache.
     */
    public function __construct( $location, $options = array() )
    {
        $apiName = ezpRestPrefixFilterInterface::getApiProviderName();
        $apiVersion = ezpRestPrefixFilterInterface::getApiVersion();
        $location = eZSys::cacheDirectory().'/rest/'.$location;
        if( !file_exists( $location ) )
        {
            if( !eZDir::mkdir( $location, false, true ) )
            {
                throw new ezcBaseFilePermissionException(
                    $location,
                    ezcBaseFileException::WRITE,
                    'Cache location is not writeable.'
                );
            }
        }

        parent::__construct( $location );
        $this->properties['options'] = new ezpCacheStorageClusterOptions( $options );
    }

    /**
     * (non-PHPdoc)
     * @see lib/ezc/Cache/src/storage/ezcCacheStorageFile::store()
     */
    public function store( $id, $data, $attributes = array() )
    {
        $fileName = $this->properties['location']
                  . $this->generateIdentifier( $id, $attributes );

        if( !isset( $this->clusterCacheFile ) )
            $this->clusterCacheFile = eZClusterFileHandler::instance( $fileName );

        $dataStr = $this->prepareData( $data );
        $aFileData = array(
            'scope'        => 'rest-cluster-cache',
            'binarydata'   => $dataStr
        );
        $this->clusterCacheFile->storeCache( $aFileData );

        return $id;
    }

    /**
     * Restore data from the cache.
     * Restores the data associated with the given cache and
     * returns it. Please see {@link ezcCacheStorage::store()}
     * for more detailed information of cachable datatypes.
     *
     * During access to cached data the caches are automatically
     * expired. This means, that the ezcCacheStorage object checks
     * before returning the data if it's still actual. If the cache
     * has expired, data will be deleted and false is returned.
     *
     * You should always provide the attributes you assigned, although
     * the cache storages must be able to find a cache ID even without
     * them. BEWARE: Finding cache data only by ID can be much
     * slower than finding it by ID and attributes.
     *
     * @param string $id                        The item ID.
     * @param array(string=>string) $attributes Attributes that describe the
     *                                          cached data.
     * @param bool $search                      Whether to search for items
     *                                          if not found directly. Default is
     *                                          false.
     *
     * @return mixed The cached data on success, otherwise false.
     */
    public function restore( $id, $attributes = array(), $search = false )
    {
        // If cache is explicitely disabled, we don't try to process it
        if( $this->isCacheEnabled === false )
        {
            return false;
        }

        $fileName = $this->properties['location']
                  . $this->generateIdentifier( $id, $attributes );

        $this->clusterCacheFile = eZClusterFileHandler::instance( $fileName );
        $result = $this->clusterCacheFile->processCache(
            array( $this, 'clusterRetrieve' ),
            null, // We won't call any generate callback as we're using ezcCache mechanism, so it's up to the cache caller to generate
            $this->properties['options']['ttl'],
            null,
            compact( 'id', 'attributes', 'fileName' )
        );

        if ( !$result instanceof eZClusterFileFailure )
        {
            return $result;
        }

        return false;
    }

    /**
     * Retrieve callback for cluster processCache() method
     * @param string $file Filepath
     * @param int $mtime File modification time
     * @param array $args Extra args passed to the cluster processCache() method
     * @return string
     */
    public function clusterRetrieve( $file, $mtime, $args )
    {
        return $this->fetchData( $file );
    }

    /**
     * Delete data from the cache.
     * Purges the cached data for a given ID and or attributes. Using an ID
     * purges only the cache data for just this ID.
     *
     * Additional attributes provided will matched additionally. This can give
     * you an immense speed improvement against just searching for ID ( see
     * {@link ezcCacheStorage::restore()} ).
     *
     * If you only provide attributes for deletion of cache data, all cache
     * data matching these attributes will be purged.
     *
     * @param string $id                        The item ID.
     * @param array(string=>string) $attributes Attributes that describe the
     *                                          cached data.
     * @param bool $search                      Whether to search for items
     *                                          if not found directly. Default is
     *                                          false.
     */
    public function delete( $id = null, $attributes = array(), $search = false )
    {
        $fileName = $this->properties['location']
                  . $this->generateIdentifier( $id, $attributes );

        if( !isset( $this->clusterCacheFile ) )
            $this->clusterCacheFile = eZClusterFileHandler::instance( $fileName );

        $this->clusterCacheFile->purge();

        return $id;
    }

    /**
     * Return the number of items in the cache matching a certain criteria.
     * This method determines if cache data described by the given ID and/or
     * attributes exists. It returns the number of cache data items found.
     *
     * @param string $id                        The item ID.
     * @param array(string=>string) $attributes Attributes that describe the item
     * @return int The number of cache data items found matching the criteria
     */
    public function countDataItems( $id = null, $attributes = array() )
    {
        $fileName = $this->properties['location']
                  . $this->generateIdentifier( $id, $attributes );

        if( !isset( $this->clusterCacheFile ) )
            $this->clusterCacheFile = eZClusterFileHandler::instance( $fileName );

        $count = 0;
        if( $this->clusterCacheFile->exists() )
        {
            $count = 1;
        }

        return $count;
    }

    /**
     * Returns the time ( in seconds ) that remains for a cache object,
     * before it gets outdated. In case the cache object is already
     * outdated or does not exist, this method returns 0.
     *
     * @param string $id                        The item ID.
     * @param array(string=>string) $attributes Attributes that describe the
     * @access public
     * @return int The remaining lifetime ( 0 if nonexists or outdated ).
     */
    public function getRemainingLifetime( $id, $attributes = array() )
    {
        $fileName = $this->properties['location']
                  . $this->generateIdentifier( $id, $attributes );

        if( !isset( $this->clusterCacheFile ) )
            $this->clusterCacheFile = eZClusterFileHandler::instance( $fileName );

        $ttl = $this->options->ttl;
        $remaining = 0;
        $curTime = time();
        if( $this->clusterCacheFile->exists() && !$this->clusterCacheFile->isExpired( -1, $curTime, $ttl) )
        {
            if( $ttl !== false )
            {
                $lifetime = $curTime - $this->clusterCacheFile->mtime();
                if( $lifetime < $ttl )
                    $remaining = $ttl - $lifetime;
            }
        }

        return $remaining;
    }

    /**
     * Aborts current cache generation
     * Useful in case of a problem during generation of content (ie. exception)
     * @return void
     * @throws ezpCacheClusterException
     */
    public function abortCacheGeneration()
    {
        if ( !isset( $this->clusterCacheFile ) )
        {
            throw new ezpCacheClusterException( "Can't abort generation of non-existent cluster cache" );
        }

        $this->clusterCacheFile->abortCacheGeneration();
    }
}
?>
