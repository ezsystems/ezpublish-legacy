<?php
/**
 * File containing the ezpContentClassCriteria class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package API
 */

/**
 * This class allows for configuration of a content class based criteria
 * @package API
 */
class ezpContentClassCriteria
{
    /**
     * Sets a class name criteria.
     * Content will only be accepted if it is of the given class
     *
     * @param string $contentClassName
     * @return ezpContentClassCriteria
     */
    public function is( $contentClassName )
    {
        $this->classes[] = $contentClassName;
        return $this;
    }

    public $classes = array();
}
?>