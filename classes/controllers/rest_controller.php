<?php
/**
 * File containing ezpRestMvcController class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */

/**
 * Abstract class that must be extended by every REST controller
 */
abstract class ezpRestMvcController extends ezcMvcController
{
    /**
     * Cache ID for ezcCache
     * @var string
     */
    const CACHE_ID = 'ezpRestMvcController';
    
    /**
     * Default response groups returned by the controller
     * @var array
     */
    private $defaultResponsegroups = array();
    
    /**
     * @var eZINI
     */
    protected $restINI;
    
    /**
     * Constructor
     * @param string $action
     * @param ezcMvcRequest $request
     */
    public function __construct( $action, ezcMvcRequest $request )
    {
        $this->restINI = eZINI::instance( 'rest.ini' );
        parent::__construct( $action, $request );
    }
    
    /**
     * Checks if a response group has been provided in the requested REST URI
     * @param string $name Response group name
     * @return bool
     */
    protected function hasResponseGroup( $name )
    {
        $hasResponseGroup = false;
        
        // First check in default response groups
        if( in_array( $name, $this->defaultResponsegroups ) )
        {
            $hasResponseGroup = true;
        }
        else if( isset( $this->request->variables['ResponseGroups'] ) )
        {
            $hasResponseGroup = in_array( $name, $this->request->variables['ResponseGroups'] );
        }
        
        return $hasResponseGroup;
    }
    
    /**
     * Returns requested response groups
     * @return array
     */
    protected function getResponseGroups()
    {
        $resGroups = $this->request->variables['ResponseGroups'];
        for ($i = 0, $iMax = count( $this->defaultResponsegroups ); $i < $iMax; ++$i)
        {
            if( !in_array( $this->defaultResponsegroups[$i], $resGroups ) )
                $resGroups[] = $this->defaultResponsegroups[$i];
        }
        
        return $resGroups;
    }
    
    /**
     * Sets default response groups
     * @param array $defaultResponseGroups
     * @return void
     */
    protected function setDefaultResponseGroups( array $defaultResponseGroups )
    {
        $this->defaultResponsegroups = $defaultResponseGroups;
    }
    
    /**
     * Checks if a content variable has been provided in requested REST URI
     * @param string $name Content variable name
     * @return bool
     */
    protected function hasContentVariable( $name )
    {
        $hasContentVariable = false;
        if( isset( $this->request->contentVariables[$name] ) )
        {
            $hasContentVariable = true;
        }
        
        return $hasContentVariable;
    }
    
    /**
     * Returns requested content variable, is it set
     * @param string $name Content variable name
     * @return string|null
     */
    protected function getContentVariable( $name )
    {
        $contentVariable = null;
        if( isset( $this->request->contentVariables[$name] ) )
        {
            $contentVariable = $this->request->contentVariables[$name];
        }
        
        return $contentVariable;
    }
    
    /**
     * Returns all provided content variables in requested REST URI
     * @return array
     */
    protected function getAllContentVariables()
    {
        return $this->request->contentVariables;
    }
    
    /**
     * Override to add the "requestedResponseGroups" variable for every REST requests
     * @see lib/ezc/MvcTools/src/interfaces/ezcMvcController::createResult()
     */
    public function createResult()
    {
        $apiName = ezpRestPrefixFilterInterface::getApiProviderName();
        $apiVersion = ezpRestPrefixFilterInterface::getApiVersion();
        $routingInfos = $this->getRouter()->getRoutingInformation();
        
        $cacheOptions = array( 'ttl' => $this->getActionTTL() );
        ezcCacheManager::createCache( self::CACHE_ID, $this->getCacheLocation(), 'ezpRestCacheStorageClusterObject', $cacheOptions );
        
        $cache = ezcCacheManager::getCache( self::CACHE_ID );
        $controllerCacheId = $this->generateCacheId();
        $isCacheEnabled = $this->isCacheEnabled();
        
        // Try to restore application cache.
        // If expired or not yet available, generate it and store it
        $cache->isCacheEnabled = $isCacheEnabled;
        if( ( $res = $cache->restore( $controllerCacheId, $cacheAttributes ) ) === false )
        {
            $res = parent::createResult();
            $resGroups = $this->getResponseGroups();
            $res->variables['requestedResponseGroups'] = $resGroups;
            
            if ( $res instanceof ezpRestMvcResult )
            {
                $res->responseGroups = $resGroups;
            }
            
            if( $isCacheEnabled )
                $cache->store( $controllerCacheId, $res );
        }
        
        // Add debug infos to output if debug is enabled
        if( ezpRestDebugHandler::isDebugEnabled() )
        {
            $res->variables['debug'] = ezpRestDebugHandler::getInstance()->getReport();
        }

        return $res;
    }
    
    /**
     * Returns cache location for current API/version/controller/action
     * @return string Path in the cluster
     */
    public function getCacheLocation()
    {
        $apiName = ezpRestPrefixFilterInterface::getApiProviderName();
        $apiVersion = ezpRestPrefixFilterInterface::getApiVersion();
        $routingInfos = $this->getRouter()->getRoutingInformation();
        $cacheLocation = $apiName.'/v'.$apiVersion.'/'.$routingInfos->controllerClass.'/'.$routingInfos->action;
        
        return $cacheLocation;
    }
    
    /**
     * Returns cache TTL value for current action as set in rest.ini
     * Default value will be [CacheSettings].DefaultCacheTTL.
     * This can be refined by setting a [<controllerClass>_<action>_CacheSettings] section (see comments in rest.ini).
     * @return int TTL in seconds
     */
    private function getActionTTL()
    {
        $ttl = $this->restINI->variable( 'CacheSettings', 'DefaultCacheTTL' );
        $routingInfos = $this->getRouter()->getRoutingInformation();
        
        // Check if we have TTL settings for this controller/action
        $actionSectionName = $routingInfos->controllerClass.'_'.$routingInfos->action.'_CacheSettings';
        $controllerSectionName = $routingInfos->controllerClass.'_CacheSettings';
        if( $this->restINI->hasVariable( $actionSectionName, 'CacheTTL' ) )
        {
            $ttl = $this->restINI->variable( $actionSectionName, 'CacheTTL' );
        }
        else if( $this->restINI->hasVariable( $controllerSectionName, 'CacheTTL' ) ) // Nothing at controller/action level, check at controller level
        {
            $ttl = $this->restINI->variable( $controllerSectionName, 'CacheTTL' );
        }
        
        return (int)$ttl;
    }
    
    /**
     * Generates unique cache ID for current request.
     * The cache ID is a MD5 hash and takes into account :
     *  - API Name
     *  - API Version
     *  - Controller class
     *  - Action
     *  - Internal variables (passed parameters, ResponseGroups...)
     *  - Content variables (Translation...)
     * @return string
     */
    private function generateCacheId()
    {
        $apiName = ezpRestPrefixFilterInterface::getApiProviderName();
        $apiVersion = ezpRestPrefixFilterInterface::getApiVersion();
        $routingInfos = $this->getRouter()->getRoutingInformation();
        
        $aCacheId = array( $apiName, $apiVersion, $routingInfos->controllerClass, $routingInfos->action );
        // Add internal variables, caught in the URL. See ezpRestHttpRequestParser::fillVariables()
        // Also add content variables
        $allInternalVariables = array_merge( $this->request->variables, $this->getAllContentVariables() );
        foreach( $allInternalVariables as $name => $val )
        {
            if( is_array( $val ) )
                $aCacheId[] = $name.'='.implode( ',', $val );
            else
                $aCacheId[] = $name.'='.$val;
        }
        
        $cacheId = implode( '-', $aCacheId );
        return md5( $cacheId );
    }
    
    /**
     * Checks if application cache is enabled for this controller/action, as set in rest.ini
     * Default value will be [CacheSettings].ApplicationCache
     * This can be refined by setting a [<controllerClass>_<action>_CacheSettings] section (see comments in rest.ini).
     * @return bool
     */
    private function isCacheEnabled()
    {
        $isCacheActivated = $this->restINI->variable( 'CacheSettings', 'ApplicationCache' ) === 'enabled';
        $routingInfos = $this->getRouter()->getRoutingInformation();
        
        // Check if we have a specific setting for this controller/action
        $actionSectionName = $routingInfos->controllerClass.'_'.$routingInfos->action.'_CacheSettings';
        $controllerSectionName = $routingInfos->controllerClass.'_CacheSettings';
        if( $this->restINI->hasVariable( $actionSectionName, 'ApplicationCache' ) )
        {
            $isCacheActivated = $this->restINI->variable( $actionSectionName, 'ApplicationCache' ) === 'enabled';
        }
        else if ( $this->restINI->hasVariable( $controllerSectionName, 'ApplicationCache' ) ) // Nothing at controller/action level, check at controller level
        {
            $isCacheActivated = $this->restINI->variable( $controllerSectionName, 'ApplicationCache' ) === 'enabled';
        }
        
        return $isCacheActivated;
    }
}
?>
