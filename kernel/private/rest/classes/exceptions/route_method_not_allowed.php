<?php
/**
 * File containing the ezpRouteMethodNotAllowedException class.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
