<?php
/**
 * File containing the ezpRouteMethodNotAllowedException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpRouteMethodNotAllowedException extends ezcMvcToolsException
{
    /**
     * List of allowed methods when the exception was thrown
     *
     * @var array
     */
    protected $allowedMethods = array();

    /**
     * Constructor
     *
     * @param array $allowedMethods
     */
    public function __construct( array $allowedMethods = array() )
    {
        $this->allowedMethods = $allowedMethods;
        parent::__construct(
            "This method is not supported, allowed methods are: " . implode( ', ', $allowedMethods )
        );
    }

    /**
     * Returns the list of allowed methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return $this->allowedMethods;
    }

}
