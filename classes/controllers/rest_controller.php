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
     * Checks if a response group has been provided in the reuqested REST URI
     * @param string $name Response group name
     * @return bool
     */
    protected function hasResponseGroup( $name )
    {
        $hasResponseGroup = false;
        if( isset( $this->request->variables['ResponseGroups'] ) )
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
        return $resGroups;
    }
    
    /**
     * Override to add the "requestedResponseGroups" variable for every REST requests
     * @see ezc/MvcTools/src/interfaces/ezcMvcController::createResult()
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
        
        return $res;
    }
}
?>
