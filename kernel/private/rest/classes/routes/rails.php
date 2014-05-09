<?php
/**
 * File containing ezpMvcRailsRoute class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */
class ezpMvcRailsRoute extends ezcMvcRailsRoute
{
    /**
     * Array containing the map between the protocol and corresponding action
     *
     * @var array
     */
    protected $protocolActionMap = array();

    /**
     * Constructs a new ezpMvcRailsRoute with $pattern for protocols used as
     * keys in $protocolActionMap
     *
     * Examples:
     * <code>
     * $route = new ezpMvcRailsRoute(
     *      '/content/node/:nodeId',
     *      'ezpRestContentController'
     *      array(
     *          'http-get' => 'viewContent',
     *          'http-delete' => 'deleteContent'
     *      )
     * );
     * </code>
     *
     * will define the route /content/node/:nodeId and a different method in
     * the controller will be called depending on the used HTTP verb. If
     * $protocolActionMap is a string, we assume the mapping is done for
     * http-get unless another protocol is indicated in deprecated param $protocol
     * (kept to not introduce a BC break)
     *
     * @param string $pattern
     * @param string $controllerClassName
     * @param array|string $protocolActionMap
     * @param array $defaultValues
     * @param null|string $protocol (deprecated) Match specific protocol if
     *                              $protocolActionMap is a string, eg: 'http-get';
     */
    public function __construct( $pattern, $controllerClassName, $protocolActionMap, array $defaultValues = array(), $protocol = null )
    {
        if ( is_string( $protocolActionMap ) )
        {
            if ( $protocol === null )
            {
                $protocolActionMap = array( 'http-get' => $protocolActionMap );
            }
            else
            {
                // compatibility with 4.6 route definition
                $protocolActionMap = array( $protocol => $protocolActionMap );
            }
        }
        if ( !isset( $protocolActionMap['http-options'] ) )
        {
            $protocolActionMap['http-options'] = 'httpOptions';
        }
        $this->protocolActionMap = $protocolActionMap;
        parent::__construct( $pattern, $controllerClassName, '', $defaultValues );
    }

    /**
     * Evaluates the URI against this route and allowed protocols.
     *
     * @param ezcMvcRequest $request
     * @return ezcMvcRoutingInformation|null
     * @throw ezpRouteMethodNotAllowedException if a route is found but a wrong
     *                                          HTTP verb is used
     */
    public function matches( ezcMvcRequest $request )
    {
        if ( $this->match( $request, $matches ) )
        {
            if ( !isset( $this->protocolActionMap[$request->protocol] ) )
            {
                throw new ezpRouteMethodNotAllowedException( $this->getSupportedHTTPMethods() );
            }
            $request->variables = array_merge( $this->defaultValues, $request->variables, $matches );
            if ( $request->protocol === 'http-options' )
            {
                $request->variables['supported_http_methods'] = $this->getSupportedHTTPMethods();
            }
            return new ezcMvcRoutingInformation( $this->pattern, $this->controllerClassName, $this->protocolActionMap[$request->protocol] );
        }

        return null;
    }

    /**
     * Returns an array containing the HTTP methods supported by the route
     * based on $this->protocolActionMap
     *
     * @return array(string)
     */
    protected function getSupportedHTTPMethods()
    {
        $methods = array_keys( $this->protocolActionMap );
        foreach ( $methods as &$method )
        {
            $method = strtoupper( str_replace( 'http-', '', $method ) );
        }
        return $methods;
    }

}
?>
