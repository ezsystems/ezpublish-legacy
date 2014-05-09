<?php
/**
 * File containing ezpMvcRegexpRoute class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Override of ezcMvcRegexpRoute.
 * Necessary to be able to be mixed with rails-like routes
 */
class ezpMvcRegexpRoute extends ezcMvcRegexpRoute
{
    /**
     * Array containing the map between the protocol and corresponding action
     *
     * @var array
     */
    protected $protocolActionMap = array();

    /**
     * Constructs a new ezpMvcRegexpRoute with $pattern for protocols used as
     * keys in $protocolActionMap
     *
     * Examples:
     * <code>
     * $route = new ezpMvcRegexpRoute(
     *      REGEXP,
     *      'ezpRestContentController'
     *      array(
     *          'http-get' => 'viewContent',
     *          'http-delete' => 'deleteContent'
     *      )
     * );
     * </code>
     *
     * will define the route with the REGEXP and a different method in
     * the controller will be called depending on the used HTTP verb. If
     * $protocolActionMap is a string, we assume the mapping is done for
     * http-get (kept to not introduce a BC break)
     *
     * @param string $pattern
     * @param string $controllerClassName
     * @param array|string $protocolActionMap
     * @param array $defaultValues
     */
    public function __construct( $pattern, $controllerClassName, $protocolActionMap, array $defaultValues = array() )
    {
        if ( is_string( $protocolActionMap ) )
        {
            $protocolActionMap = array( 'http-get' => $protocolActionMap );
        }
        if ( !isset( $protocolActionMap['http-options'] ) )
        {
            $protocolActionMap['http-options'] = 'httpOptions';
        }
        $this->protocolActionMap = $protocolActionMap;
        parent::__construct( $pattern, $controllerClassName, '', $defaultValues );
    }

    /**
     * Little fix to allow mixed regexp and rails routes in the router
     * @see lib/ezc/MvcTools/src/routes/ezcMvcRegexpRoute::prefix()
     */
    public function prefix( $prefix )
    {
        // Detect the Regexp delimiter
        $patternDelim = $this->pattern[0];

        // Add the Regexp delimiter to the prefix
        $prefix = $patternDelim . $prefix . $patternDelim;
        parent::prefix( $prefix );
    }


    /**
     * Evaluates the URI against this route and allowed protocols
     *
     * The method first runs the match. If the regular expression matches, it
     * cleans up the variables to only include named parameters.  it then
     * creates an object containing routing information and returns it. If the
     * route's pattern did not match it returns null.
     *
     * @param ezcMvcRequest $request
     * @return null|ezcMvcRoutingInformation
     */
    public function matches( ezcMvcRequest $request )
    {
        if ( $this->pregMatch( $request, $matches ) )
        {
            foreach ( $matches as $key => $match )
            {
                if ( is_numeric( $key ) )
                {
                    unset( $matches[$key] );
                }
            }
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
