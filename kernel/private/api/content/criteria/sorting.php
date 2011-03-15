<?php
/**
 * File containing ezpContentSortingCriteria class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */

/**
 * Sorting criteria
 * @package API
 */
class ezpContentSortingCriteria implements ezpContentCriteriaInterface
{
    /**
     * Any of the non-attribute supported fields
     * @see http://goo.gl/xvJMM
     * @var string
     */
    private $sortKey;

    /**
     * Sort order
     * @var bool true means ASC, false means DESC, just like in the fetch content/list function
     */
    private $sortOrder;

    public function __construct( $sortKey, $sortOrder )
    {
        $this->sortKey = $sortKey;
        $this->sortOrder = ( $sortOrder == 'asc' ) ? true : false;
    }

    public function translate()
    {
        return array(
            'type'      => 'param',
            'name'      => array( 'SortBy' ),
            'value'     => array( array( $this->sortKey, $this->sortOrder ) )
        );
    }

    public function __toString()
    {
        $sortOrderString = $this->sortOrder ? 'asc' : 'desc';
        return 'With sortKey '.$this->sortKey.' and '.$sortOrderString.' sortOrder';
    }
}
?>
