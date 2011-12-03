<?php
/**
 * File containing ezpRestApplicationCacheTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

/**
 * This class contains unit tests for application cache
 */
class ezpRestApplicationCacheTest extends ezpRestTestCase
{
    public function __construct( $name = NULL, array $data = array(), $dataName = '' )
    {
        parent::__construct( $name, $data, $dataName );
    }

    /**
     * Provides INI settings for enabled application cache
     */
    public function cacheEnabledIniSettingsProvider()
    {
        $iniVariables1 = array(
            'CacheSettings'     => array(
                'ApplicationCache'              => 'enabled',
                'ApplicationCacheDefault'       => 'enabled',
            )
        );

        $iniVariables2 = array(
            'CacheSettings'     => array(
                'ApplicationCache'              => 'enabled',
                'ApplicationCacheDefault'       => 'disabled',
            ),
            'ezpRestTestController_CacheSettings'   => array(
                'ApplicationCache'              => 'enabled',
            )
        );

        $iniVariables3 = array(
            'CacheSettings'     => array(
                'ApplicationCache'              => 'enabled',
                'ApplicationCacheDefault'       => 'disabled',
            ),
            'ezpRestTestController_CacheSettings'   => array(
                'ApplicationCache'              => 'disabled',
            ),
            'ezpRestTestController_test_CacheSettings'   => array(
                'ApplicationCache'              => 'enabled',
            )
        );

        return array(
            array( $iniVariables1 ),
            array( $iniVariables2 ),
            array( $iniVariables3 )
        );
    }

    /**
     * Provides INI settings for disabled application cache
     */
    public function cacheDisabledIniSettingsProvider()
    {
        $iniVariables1 = array(
            'CacheSettings'     => array(
                'ApplicationCache'              => 'disabled',
            )
        );

        $iniVariables2 = array(
            'CacheSettings'     => array(
                'ApplicationCache'              => 'enabled',
                'ApplicationCacheDefault'       => 'disabled',
            )
        );

        $iniVariables3 = array(
            'CacheSettings'     => array(
                'ApplicationCache'              => 'enabled',
                'ApplicationCacheDefault'       => 'enabled',
            ),
            'ezpRestTestController_CacheSettings'   => array(
                'ApplicationCache'              => 'disabled',
            )
        );

        $iniVariables4 = array(
            'CacheSettings'     => array(
                'ApplicationCache'              => 'enabled',
                'ApplicationCacheDefault'       => 'enabled',
            ),
            'ezpRestTestController_CacheSettings'   => array(
                'ApplicationCache'              => 'enabled',
            ),
            'ezpRestTestController_test_CacheSettings'   => array(
                'ApplicationCache'              => 'disabled',
            )
        );

        return array(
            array( $iniVariables1 ),
            array( $iniVariables2 ),
            array( $iniVariables3 ),
            array( $iniVariables4 ),
        );
    }

    /**
     * @group restApplicationCache
     * @group restCache
     * @dataProvider cacheEnabledIniSettingsProvider
     * @param array $iniVariables
     */
    public function testIsCacheEnabled( array $iniVariables )
    {
        $this->restINI->setVariables( $iniVariables );
        $uri = $this->restINI->variable( 'System', 'ApiPrefix' ).'/test/rest/foo';
        $controller = $this->getTestControllerFromUri( $uri );

        $refObj = new ReflectionObject( $controller );
        $refINIProperty = $refObj->getProperty( 'restINI' );
        $refINIProperty->setAccessible( true );
        $refINIProperty->setValue( $controller, $this->restINI ); // Forced to do so as using Reflection API makes the object not to use the same singletons...
        $refMethod = $refObj->getMethod( 'isCacheEnabled' );
        $refMethod->setAccessible( true );

        self::assertTrue( $refMethod->invoke( $controller ), 'Rest application cache should be enabled with provided settings : '.print_r( $iniVariables, true ) );
        $this->restINI->load(); // Be sure to reset INI settings
    }

    /**
     * @group restApplicationCache
     * @group restCache
     * @dataProvider cacheDisabledIniSettingsProvider
     * @param array $iniVariables
     */
    public function testIsCacheDisabled( array $iniVariables )
    {
        $this->restINI->setVariables( $iniVariables );
        $uri = $this->restINI->variable( 'System', 'ApiPrefix' ).'/test/rest/foo';
        $controller = $this->getTestControllerFromUri( $uri );

        $refObj = new ReflectionObject( $controller );
        $refINIProperty = $refObj->getProperty( 'restINI' );
        $refINIProperty->setAccessible( true );
        $refINIProperty->setValue( $controller, $this->restINI );
        $refMethod = $refObj->getMethod( 'isCacheEnabled' );
        $refMethod->setAccessible( true );

        self::assertFalse( $refMethod->invoke( $controller ), 'Rest application cache should be disabled with provided settings : '.print_r( $iniVariables, true ) );
        $this->restINI->load(); // Be sure to reset INI settings
    }

    /**
     * Provides INI settings for application cache TTL
     */
    public function cacheTTLProvider()
    {
        $iniVariables1 = array(
            'CacheSettings'     => array(
                'DefaultCacheTTL'               => '30'
            )
        );

        $iniVariables2 = array(
            'CacheSettings'     => array(
                'DefaultCacheTTL'               => '30'
            ),
            'ezpRestTestController_CacheSettings'   => array(
                'CacheTTL'              => '300',
            )
        );

        $iniVariables3 = array(
            'CacheSettings'     => array(
                'DefaultCacheTTL'               => '30'
            ),
            'ezpRestTestController_CacheSettings'   => array(
                'CacheTTL'              => '300',
            ),
            'ezpRestTestController_test_CacheSettings'   => array(
                'CacheTTL'              => '3600',
            )
        );

        return array(
            array( $iniVariables1 ),
            array( $iniVariables2 ),
            array( $iniVariables3 )
        );
    }

    /**
     * @group restApplicationCache
     * @group restCache
     * @dataProvider cacheTTLProvider
     * @param array $iniVariables
     */
    public function testGetActionTTL( array $iniVariables )
    {
        $this->restINI->setVariables( $iniVariables );
        $uri = $this->restINI->variable( 'System', 'ApiPrefix' ).'/test/rest/foo';
        $controller = $this->getTestControllerFromUri( $uri );
        $routingInfos = $controller->getRouter()->getRoutingInformation();

        $refObj = new ReflectionObject( $controller );
        $refINIProperty = $refObj->getProperty( 'restINI' );
        $refINIProperty->setAccessible( true );
        $refINIProperty->setValue( $controller, $this->restINI ); // Forced to do so as using Reflection API makes the object not to use the same singletons...
        $refMethod = $refObj->getMethod( 'getActionTTL' );
        $refMethod->setAccessible( true );

        $actionSectionName = $routingInfos->controllerClass.'_'.$routingInfos->action.'_CacheSettings';
        $controllerSectionName = $routingInfos->controllerClass.'_CacheSettings';
        if( $this->restINI->hasVariable( $actionSectionName, 'CacheTTL' ) )
        {
            $validTTL = (int)$this->restINI->variable( $actionSectionName, 'CacheTTL' );
            self::assertSame( $refMethod->invoke( $controller ), $validTTL, 'Rest application cache TTL should be '.$validTTL );
        }
        else if( $this->restINI->hasVariable( $controllerSectionName, 'CacheTTL' ) )
        {
            $validTTL = (int)$this->restINI->variable( $controllerSectionName, 'CacheTTL' );
            self::assertSame( $refMethod->invoke( $controller ), $validTTL, 'Rest application cache TTL should be '.$validTTL );
        }
        else
        {
            $validTTL = (int)$this->restINI->variable( 'CacheSettings', 'DefaultCacheTTL' );
            self::assertSame( $refMethod->invoke( $controller ), $validTTL, 'Rest application cache TTL should be '.$validTTL );
        }

        $this->restINI->load(); // Be sure to reset INI settings
    }

    /**
     * @group restApplicationCache
     * @group restCache
     */
    public function testGetCacheLocation()
    {
        $uri = $this->restINI->variable( 'System', 'ApiPrefix' ).'/test/rest/foo';
        $controller = $this->getTestControllerFromUri( $uri );
        $routingInfos = $controller->getRouter()->getRoutingInformation();

        // Cache location should be : (<cacheDirectory>/)<apiName>/v<apiVersion>/<controllerClass>/<action>
        $pattern = '@^(?P<apiName>[^/]+)/v(?P<apiVersion>[^/]+)/(?P<controllerClass>[^/]+)/(?P<action>[^/]+)@';
        $result = preg_match( $pattern, $controller->getCacheLocation(), $matches );
        self::assertEquals( $matches['apiName'], ezpRestPrefixFilterInterface::getApiProviderName() );
        self::assertEquals( $matches['apiVersion'], ezpRestPrefixFilterInterface::getApiVersion() );
        self::assertEquals( $matches['controllerClass'], $routingInfos->controllerClass );
        self::assertEquals( $matches['action'], $routingInfos->action );
    }

    /**
     * Provides request variables (internal and content)
     * @return array
     */
    public function cacheIdVariableProvider()
    {
        $internalVariables1 = array( 'dummyVar' => 'bar' );
        $contentVariables1 = array();

        $internalVariables2 = array(
            'dummyVar'          => 'bar',
            'ResponseGroups'    => array( 'OneResponseGroup', 'AnotherOne' )
        );
        $contentVariables2 = array();

        $internalVariables3 = array(
            'dummyVar'          => 'bar',
            'ResponseGroups'    => array( 'OneResponseGroup', 'AnotherOne' )
        );
        $contentVariables3 = array( 'Translation' => 'eng-GB' );

        $internalVariables4 = array();
        $contentVariables4 = array();

        $internalVariables5 = array();
        $contentVariables5 = array(
            'Translation'       => 'fre-FR',
            'Foo'               => 'Bar'
        );

        return array(
            array( $internalVariables1, $contentVariables1 ),
            array( $internalVariables2, $contentVariables2 ),
            array( $internalVariables3, $contentVariables3 ),
            array( $internalVariables4, $contentVariables4 ),
            array( $internalVariables5, $contentVariables5 ),
        );
    }

    /**
     * @group restApplicationCache
     * @group restCache
     * @dataProvider cacheIdVariableProvider
     * @param array $internalVariables
     * @param array $contentVariables
     */
    public function testGenerateCacheId( array $internalVariables, array $contentVariables )
    {
        /*
         * The cache ID is a MD5 hash and takes into account :
         *  - API Name
         *  - API Version
         *  - Controller class
         *  - Action
         *  - Internal variables (passed parameters, ResponseGroups...)
         *  - Content variables (Translation...)
         */
        $uri = $this->restINI->variable( 'System', 'ApiPrefix' ).'/test/rest/foo';
        $request = new ezpRestRequest();
        $request->uri = $uri;
        $request->variables = $internalVariables;
        $request->contentVariables = $contentVariables;
        $controller = $this->getTestControllerFromRequest( $request );
        $routingInfos = $controller->getRouter()->getRoutingInformation();
        $apiName = ezpRestPrefixFilterInterface::getApiProviderName();
        $apiVersion = ezpRestPrefixFilterInterface::getApiVersion();

        /*
         * Reproduce the hash algorythm
         */
        $allInternalVariables = array_merge( $internalVariables, $contentVariables );
        $aCacheId = array( $apiName, $apiVersion, $routingInfos->controllerClass, $routingInfos->action );
        foreach( $allInternalVariables as $name => $val )
        {
            if( is_array( $val ) )
                $aCacheId[] = $name.'='.implode( ',', $val );
            else
                $aCacheId[] = $name.'='.$val;
        }

        $cacheId = implode( '-', $aCacheId );
        $hashedCacheId = md5( $cacheId );

        $refObj = new ReflectionObject( $controller );
        $refMethod = $refObj->getMethod( 'generateCacheId' );
        $refMethod->setAccessible( true );
        self::assertSame( $hashedCacheId, $refMethod->invoke( $controller ),
                          'Cache ID algo must take into account : API Name, API Version, Controller class, Action, Internal variables, Content variables' );

        // Compare currently generated hash with the previous one. Must be different
        static $previousHash;
        self::assertNotEquals( $hashedCacheId, $previousHash, 'Cache IDs must be unique !' );
        $previousHash = $hashedCacheId;
    }

    /**
     * @group restApplicationCache
     * @group restClusterCache
     * @group restCache
     */
    public function testClusterCache()
    {
        $uri = $this->restINI->variable( 'System', 'ApiPrefix' ).'/test/rest/foo';
        $request = new ezpRestRequest();
        $request->uri = $uri;
        $request->variables = array( 'ResponseGroups' => array() );
        $request->contentVariables = array();
        $controller = $this->getTestControllerFromRequest( $request );

        // Be sure the cache has been activated
        $ttl = 1; // TTL will be 1 second
        $iniVariables = array(
            'CacheSettings'     => array(
                'ApplicationCache'              => 'enabled',
                'ApplicationCacheDefault'       => 'enabled',
            ),
            'ezpRestTestController_test_CacheSettings'   => array(
                'ApplicationCache'              => 'enabled',
                'CacheTTL'                      => (string)$ttl,
            )
        );
        $this->restINI->setVariables( $iniVariables );
        $controller->setRestINI( $this->restINI );

        $res = $controller->createResult(); // Should generate some cache in the cluster
        self::assertInstanceOf( 'ezpRestMvcResult' , $res, 'REST action must return ezpRestMvcResult object' );
        $cacheLocation = eZSys::cacheDirectory().'/rest/'.$controller->cacheLocation;
        $cacheFile = $controller->cacheId.'-.cache'; // FIXME, as file name generation depends on ezcCacheStorageFile class
        $clusterFile = eZClusterFileHandler::instance( $cacheLocation.'/'.$cacheFile );
        self::assertTrue( $clusterFile->exists(), 'REST Application cache file is not available in the cluster' );

        // Generate the result a second time, to be sure that object coming from cache is the same
        $res2 = $controller->createResult();
        self::assertInstanceOf( 'ezpRestMvcResult', $res2, 'Result extracted from REST application cache must be the same type as the one generated without cache' );
        self::assertEquals( $res, $res2, 'Result extracted from REST application cache must be the same than the one generated without cache' );

        // Now test expiry
        // Sleep for a while. Add 1 second to the $ttl value to be sure to force cache generation
        $mtime = $clusterFile->mtime();
        sleep( $ttl + 1 );
        $res3 = $controller->createResult();
        $clusterFile->loadMetadata( true );
        $newMtime = $clusterFile->mtime();
        self::assertGreaterThan( $mtime, $newMtime );

        $this->restINI->load();
    }

    /**
     * @group restApplicationCache
     * @group restClusterCache
     * @group restCache
     */
    public function testManageCache()
    {
        $cacheOptions = array( 'ttl' => 2 );
        $cacheID = 'test_id';
        $cacheKey = 'myUniqueCacheKey';
        $cacheLocation = 'myLocation';
        $data = array(
            'foo'       => 'bar',
            'baz'       => 123,
            'boolean'   => true
        );

        ezcCacheManager::createCache( $cacheID, $cacheLocation, 'ezpRestCacheStorageClusterObject', $cacheOptions );
        $cache = ezcCacheManager::getCache( $cacheID );
        $cacheContent = $cache->restore( $cacheKey ); // Should be false as we didn't write anything yet
        self::assertFalse( $cacheContent, 'Cache should be empty before generation' );

        // Remaining lifetime should be 0 as cache does not exist yet
        self::assertSame( 0, $cache->getRemainingLifetime( $cacheKey ) );

        // Store the cache
        $cache->store( $cacheID, $data );

        $cacheContent = $cache->restore( $cacheKey );
        self::assertSame( $data, $cacheContent, 'Invalid cache retrieval !' );

        // Now check if it is present in the cluster
        $cacheFullLocation = eZSys::cacheDirectory().'/rest/'.$cacheLocation;
        $cacheFile = $cacheKey.'-.cache';
        self::assertTrue( eZClusterFileHandler::instance()->fileExists( $cacheFullLocation.'/'.$cacheFile ), 'REST cache file has not been written in the cluster' );

        self::assertSame( 1, $cache->countDataItems( $cacheKey ) );
        self::assertGreaterThan( 0, $cache->getRemainingLifetime( $cacheKey ), 'Invalid remaining lifetime for REST cache' );

        $cache->delete( $cacheKey ); // Test file deletion
        self::assertFalse( eZClusterFileHandler::instance()->fileExists( $cacheFullLocation.'/'.$cacheFile ), 'REST cache file has not been deleted from the cluster' );
    }
}
?>
