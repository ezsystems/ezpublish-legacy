<?php
/**
 * File containing ezpRestCacheStorageApcCluster class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */
class ezpRestCacheStorageApcCluster extends ezcCacheStorageApcPlain
{
    /**
     * @var eZExpiryHandler
     */
    protected $expiryHandler;

    /**
     * Flag indicating if we need to force internal registry storage.
     * Default is false
     * @var bool
     */
    public $forceStoreRegistry = false;

    public function __construct( $location = null, array $options = array() )
    {
        eZExpiryHandler::registerShutdownFunction();
        $this->expiryHandler = eZExpiryHandler::instance();

        parent::__construct( $location, $options );
    }

    /**
     * Override from {@link ezcCacheStorageMemory::restore()}
     * to add a cluster cache control, driven by {@link eZExpiryHandler} (expiry.php)
     * @see lib/ezc/Cache/src/storage/ezcCacheStorageMemory::restore()
     */
    public function restore( $id, $attributes = array(), $search = false )
    {
        $ttl = (int)eZINI::instance( 'rest.ini' )->variable( 'CacheSettings', 'RouteApcCacheTTL' );
        $currentTime = time();
        $expiryTime = $this->getExpiryTime( $id );

        // Check if cache is marked as expired in expiry.php
        // Mandatory in clustered environment for cache control
        $isCacheInternallyExpired = false;
        if( $expiryTime < ( $currentTime - $ttl ) )
        {
            $isCacheInternallyExpired = true;
        }

        if( !$isCacheInternallyExpired ) // Cache not marked as expired in expiry.php, defer cache retore to parent class
        {
            return parent::restore( $id, $attributes, $search );
        }
        else // Cache is expired in expiry.php, return false as we must regenerate it
        {
            parent::delete( $id, $attributes, $search ); // Directly go to the parent not to force registry store, to avoid potential cache slam
            return false;
        }
    }

    /**
     * Returns cluster expiry timestamp for given $id
     * @param $id
     */
    protected function getExpiryTime( $id )
    {
        $storedTimeStamp = $this->expiryHandler->hasTimestamp( $id ) ? $this->expiryHandler->timestamp( $id ) : false;
        $expiryTime = $storedTimeStamp !== false ? $storedTimeStamp : 0;

        return $expiryTime;
    }

    /**
     * Override to avoid unnecessary registry storage and so potential cache slam (from APC 3.1.3p1).
     * To force registry storage, {@see self::forceStoreRegistry} must be set to true
     * @see lib/ezc/Cache/src/storage/ezcCacheStorageMemory::storeSearchRegistry()
     */
    protected function storeSearchRegistry()
    {
        $location = $this->properties['location'];
        if( $this->forceStoreRegistry )
        {
            if( isset( $this->searchRegistry[$location] ) )
            {
                $this->forceStoreRegistry = false;
                parent::storeSearchRegistry();
            }
        }
    }

    /**
     * Direct store instruction.
     * Makes the registry to be stored
     * @see lib/ezc/Cache/src/storage/ezcCacheStorageMemory::store()
     */
    public function store( $id, $data, $attributes = array() )
    {
        $this->forceStoreRegistry = true;
        $storeResult = parent::store( $id, $data, $attributes );

        // Update clustered expiry timestamp
        $expiryTime = time() + $this->properties['options']['ttl'];
        $this->expiryHandler->setTimestamp( ezpRestRouter::ROUTE_CACHE_KEY, $expiryTime );

        return $storeResult;
    }

    /**
     * Direct delete instruction. Registry will be stored
     * @see lib/ezc/Cache/src/storage/ezcCacheStorageMemory::delete()
     */
    public function delete( $id = null, $attributes = array(), $search = false )
    {
        $this->forceStoreRegistry = true;
        parent::delete( $id, $attributes, $search );
    }
}
?>
