<?php
/**
 * File containing the ezpMvcUnsupportedMethodStatus class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class ezpMvcUnsupportedMethodStatus extends ezpRestStatusResponse
{
    /**
     * Possible methods
     *
     * @var array
     */
    public $possibleMethods;

    /**
     * Construct an ezpMvcUnsupportedMethodStatus object
     *
     * @param array $possibleMethods Possible methods, e.g.: array( "http-get", "http-post", "http-delete" )
     */
    public function __construct( array $possibleMethods = array() )
    {
        $methods = $possibleMethods;
        foreach ( $methods as &$method )
        {
            $method = strtoupper( str_replace( "http-", "", $method ) );
        }

        $methods = implode( ",", $methods );

        parent::__construct( 405, "This method is not supported, allowed methods are: $methods' ", array( "Allow" => $methods ) );
    }
}
