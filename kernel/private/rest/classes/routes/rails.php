<?php
/**
 * File containing ezpMvcRailsRoute class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */
class ezpMvcRailsRoute extends ezcMvcRailsRoute
{
    /**
     * Holds protocol string.
     *
     * @var string
     */
    protected $protocol;

    /**
     * Constructs a new ezpMvcRailsRoute with $pattern for $protocol.
     *
     * Accepted protocol format: http-get, http-post, http-put, http-delete
     * @see ezcMvcHttpRequestParser::processProtocol();
     *
     * @param string $pattern
     * @param string $controllerClassName
     * @param string $action
     * @param string $protocol
     * @param array $defaultValues
     */
    public function __construct( $pattern, $controllerClassName, $action = null, $protocol = 'http-get', array $defaultValues = array() )
    {
        $this->protocol = $protocol;
        parent::__construct( $pattern, $controllerClassName, $action, $defaultValues );
    }

    /**
     * Evaluates the URI against this route and protocol.
     *
     * @param ezcMvcRequest $request
     * @return ezcMvcRoutingInformation|null
     */
    public function matches( ezcMvcRequest $request )
    {
        if ( $request->protocol == $this->protocol )
            return parent::matches( $request );

        return null;
    }
}
?>
