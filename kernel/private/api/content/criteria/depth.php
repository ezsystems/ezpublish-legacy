<?php
/**
 * File containing ezpContentDepthCriteria class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Depth criteria
 * @package API
 */
class ezpContentDepthCriteria implements ezpContentCriteriaInterface
{
    /**
     * Maximum depth to dig while fetching
     * @var int
     */
    private $depth;

    public function __construct( $depth )
    {
        $this->depth = (int)$depth;
    }

    public function translate()
    {
        return array(
            'type'      => 'param',
            'name'      => array( 'Depth' ),
            'value'     => array( $this->depth )
        );
    }

    public function __toString()
    {
        return 'With depth '.$this->depth;
    }
}
?>
