<?php
/**
 * File containing ezpRestApplicationCacheTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
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
        self::assertTrue( $hashedCacheId != $previousHash, 'Cache IDs must be unique !' );
        $previousHash = $hashedCacheId;
    }
}
?>
