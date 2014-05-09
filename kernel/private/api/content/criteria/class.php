<?php
/**
 * File containing the ezpContentClassCriteria class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package API
 */

/**
 * This class allows for configuration of a content class based criteria
 * @package API
 */
class ezpContentClassCriteria implements ezpContentCriteriaInterface
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

    public function translate()
    {
        return array(
            'type' => 'param',
            'name' => array( 'ClassFilterType', 'ClassFilterArray' ),
            'value' => array( 'include', $this->classes ),
        );
    }

    public function __toString()
    {
        if ( count( $this->classes ) == 0 )
            return 'N/A';
        elseif ( count( $this->classes ) == 1 )
            return "Content class is {$this->classes[0]}";
        else
            return "Content class is one of " .
                join( ', ', array_slice( $this->classes, 0, -1 ) ) .
                " or " . join( '', array_slice( $this->classes, 0, -1 ) );
    }

    public $classes = array();
}
?>
