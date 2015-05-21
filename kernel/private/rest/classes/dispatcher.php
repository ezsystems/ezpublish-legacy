<?php
/**
 * File containing the ezpMvcConfigurableDispatcher class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpMvcConfigurableDispatcher extends ezcMvcConfigurableDispatcher
{

    /**
     * Runs through the request, by using the configuration to obtain correct handlers.
     *
     * Override ezcMvcConfigurableDispatcher::run() to handle
     * ezpRouteMethodNotAllowedException exception thrown when a route was
     * found but an unexpected method is used.
     */
    public function run()
    {
        // initialize infinite loop counter
        $redirects = 0;

        // create the request
        $requestParser = $this->getRequestParser();
        $request = $requestParser->createRequest();

        // start of the request loop
        do
        {
            // do the infinite loop check
            $this->checkRedirectLimit( $redirects );
            $continue = false;

            // run pre-routing filters
            $this->configuration->runPreRoutingFilters( $request );

            // create the router from the configuration
            $router = $this->getRouter( $request );

            // router creates routing information
            try
            {
                $routingInformation = $router->getRoutingInformation();
            }
            catch ( ezcMvcRouteNotFoundException $e )
            {
                $request = $this->getFatalRedirectRequest( $request, new ezcMvcResult, $e );
                $continue = true;
                continue;
            }
            // here's the reason to override ezcMvcConfigurableDispatcher::run()
            catch ( ezpRouteMethodNotAllowedException $e )
            {
                $request = $this->getFatalRedirectRequest( $request, new ezcMvcResult, $e );
                $continue = true;
                continue;
            }
            // end diff


            // run request filters
            $filterResult = $this->configuration->runRequestFilters( $routingInformation, $request );

            if ( $filterResult instanceof ezcMvcInternalRedirect )
            {
                $request = $filterResult->request;
                $continue = true;
                continue;
            }

            // create the controller
            $controller = $this->getController( $routingInformation, $request );

            // run the controller
            try
            {
                $result = $controller->createResult();
            }
            catch ( Exception $e )
            {
                $request = $this->getFatalRedirectRequest( $request, new ezcMvcResult, $e );
                $continue = true;
                continue;
            }

            if ( $result instanceof ezcMvcInternalRedirect )
            {
                $request = $result->request;
                $continue = true;
                continue;
            }
            if ( !$result instanceof ezcMvcResult )
            {
                throw new ezcMvcControllerException( "The action '{$routingInformation->action}' of controller '{$routingInformation->controllerClass}' did not return an ezcMvcResult object." );
            }

            $this->configuration->runResultFilters( $routingInformation, $request, $result );

            if ( $result->status !== 0 )
            {
                $response = new ezcMvcResponse;
                $response->status = $result->status;
            }
            else
            {
                // want the view manager to use my filters
                $view = $this->getView( $routingInformation, $request, $result );

                // create the response
                try
                {
                    $response = $view->createResponse();
                }
                catch ( Exception $e )
                {
                    $request = $this->getFatalRedirectRequest( $request, $result, $e );
                    $continue = true;
                    continue;
                }
            }
            $this->configuration->runResponseFilters( $routingInformation, $request, $result, $response );

            // create the response writer
            $responseWriter = $this->getResponseWriter( $routingInformation, $request, $result, $response );

            // handle the response
            $responseWriter->handleResponse();
        }
        while ( $continue );
    }
}
