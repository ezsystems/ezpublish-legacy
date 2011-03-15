<?php
/**
 * File containing ezpContentDepthCriteria class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
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
