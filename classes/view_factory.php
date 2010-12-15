<?php
/**
 * File containing the ezpContentRestController class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */
/**
 * View factory class
 *
 */
class ezpRestViewFactory
{
    /**
     * Creates a view object associated with controller
     *
     * @param ezcMvcRoutingInformation $routeInfo
     * @param ezcMvcRequest $request
     * @param ezcMvcResult $result
     * @return ezcMvcView|null
     */
    static public function create( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result )
    {
        $view = null;

        $controller = new $routeInfo->controllerClass( $routeInfo->action, $request );
        if ( $controller instanceof ezcMvcController )
        {
            if ( method_exists( $controller, 'loadView' ) )
                $view = $controller->loadView( $result );
        }

        return $view;

    }
}
 
