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
     * Default response groups returned by the controller
     * @var array
     */
    private $defaultResponsegroups = array();
    
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
        $res = parent::createResult();
        $resGroups = $this->getResponseGroups();
        $res->variables['requestedResponseGroups'] = $resGroups;
        
        if ( $res instanceof ezpRestMvcResult )
        {
            $res->responseGroups = $resGroups;
        }
        
        // Add debug infos to output if debug is enabled
        if( ezpRestDebugHandler::instance()->isDebugEnabled() )
        {
            $res->variables['debug'] = null;
        }
        
        return $res;
    }
}
?>
