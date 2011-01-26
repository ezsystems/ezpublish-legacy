<?php
/**
 * File containing ezpCacheStorageCluster class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */
abstract class ezpCacheStorageCluster extends ezpCacheStorage implements ezcCacheStackableStorage, ezcCacheStackMetaDataStorage
{
    /**
     * Creates a new cache storage for a given location through eZ Publish cluster mechanism
     * Options can contain the 'ttl' ( Time-To-Life ). This is per default set
     * to 1 day.
     * @param string $location Path to the cache location inside the cluster
     * @param array(string=>string) $options Options for the cache.
     */
    public function __construct( $location, $options = array() )
    {
        $cacheDir = eZSys::cacheDirectory().'/'.$location;
        if( !file_exists( $cacheDir ) )
        {
            if( !eZDir::mkdir( $cacheDir, false, true ) )
            {
                throw new ezcBaseFilePermissionException(
                    $location,
                    ezcBaseFileException::WRITE,
                    'Cache location is not writeable.'
                );
            }
        }
        
        parent::__construct( $cacheDir );
        $this->properties['options'] = new ezpCacheStorageClusterOptions( $options );
    }
    
    /**
     * Fetch data from the cache.
     * This method does the fetching of the data itself. In this case, the
     * method simply includes the file from cluster and returns the value returned by the
     * include ( or false on failure ).
     *
     * @param string $filename The file to fetch data from.
     * @return mixed The fetched data or false on failure.
     */
    abstract protected function fetchData( $filename );

    /**
     * Serialize the data for storing.
     * Serializes a PHP variable ( except type resource and object ) to a
     * executable PHP code representation string.
     *
     * @param mixed $data Simple type or array
     * @return string The serialized data
     *
     * @throws ezcCacheInvalidDataException
     *         If the data submitted can not be handled by the implementation
     *         of {@link ezpCacheStorageCluster}
     */
    abstract protected function prepareData( $data );
    
    /**
     * Store data to the cache storage.
     * This method stores the given cache data into the cache, assigning the
     * ID given to it.
     *
     * The type of cache data which is expected by a ezcCacheStorage depends on
     * its implementation. In most cases strings and arrays will be accepted,
     * in some rare cases only strings might be accepted.
     *
     * Using attributes you can describe your cache data further. This allows
     * you to deal with multiple cache data at once later. Some ezcCacheStorage
     * implementations also use the attributes for storage purposes. Attributes
     * form some kind of "extended ID".
     *
     * @param string $id                        The item ID.
     * @param mixed $data                       The data to store.
     * @param array(string=>string) $attributes Attributes that describe the
     *                                          cached data.
     *
     * @return string           The ID of the newly cached data.
     *
     * @throws ezcBaseFilePermissionException
     *         If an already existsing cache file could not be unlinked to
     *         store the new data (may occur, when a cache item's TTL
     *         has expired and the file should be stored with more actual
     *         data). This exception means most likely that your cache directory
     *         has been corrupted by external influences (file permission
     *         change).
     * @throws ezcBaseFilePermissionException
     *         If the directory to store the cache file could not be created.
     *         This exception means most likely that your cache directory
     *         has been corrupted by external influences (file permission
     *         change).
     * @throws ezcBaseFileIoException
     *         If an error occured while writing the data to the cache. If this
     *         exception occurs, a serious error occured and your storage might
     *         be corruped (e.g. broken network connection, file system broken,
     *         ...).
     * @throws ezcCacheInvalidDataException
     *         If the data submitted can not be handled by the implementation
     *         of {@link ezcCacheStorage}. Most implementations can not
     *         handle objects and resources.
     */
    public function store( $id, $data, $attributes = array() )
    {
        
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
     *
     * @throws ezcBaseFilePermissionException
     *         If an already existsing cache file could not be unlinked.
     *         This exception means most likely that your cache directory
     *         has been corrupted by external influences (file permission
     *         change).
     */
    public function restore( $id, $attributes = array(), $search = false )
    {
        
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
     *
     * @throws ezcBaseFilePermissionException
     *         If an already existsing cache file could not be unlinked.
     *         This exception means most likely that your cache directory
     *         has been corrupted by external influences (file permission
     *         change).
     */
    public function delete( $id = null, $attributes = array(), $search = false )
    {
        
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
        
    }
    
    /**
     * Checks if the location property is valid.
     */
    protected function validateLocation()
    {
        
    }
}
?>
