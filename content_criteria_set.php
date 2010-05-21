<?php
/**
 * File containing the ezpContentCriteriaSet class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package API
 */

/**
 * This class handles a list of content query criteria
 * @package API
 */
class ezpContentCriteriaSet implements ArrayAccess, Countable
{
    /**
     * Array offset setter
     * Called when a criteria is added. Expects an empty array index syntax.
     *
     * @param mixed $offset
     * @param eZContentCriteria $value
     */
    public function OffsetSet( $offset, $value )
    {
        $this->criteria[] = $value;
    }

    /**
     * Array offset getter
     *
     * @param mixed $offset
     */
    public function OffsetGet( $offset ){}

    public function OffsetExists( $offset ){}

    public function OffsetUnset( $offset ){}

    /**
     * Returns the number of registered criteria
     * @note Implements the count() method of the Countable interface
     * @return int
     */
    public function count()
    {
        return count( $criteria );
    }

    private $criteria = array();
}
?>