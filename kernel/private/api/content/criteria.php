<?php
/**
 * Content list criteria mechanism.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package API
 */

/**
 * This class is used to instantiate and manipulate content listing criterias.
 * Each returned criteria will have its own particular filtering methods, as seen in the example below
 *
 * Example:
 * <code>
 * $criteria = new ezpContentCriteria();
 * $criteria->accept[] = ezpContentCriteria::location->subtree(  ezpContentLocation::fromUrl( ‘/articles’ ) );
 * $criteria->accept[] = ezpContentCriteria::field( 'title' )->like( 'foo' );
 * $criteria->accept[] = ezpContentCriteria::field( 'summary' )->startsWith( 'abc' );
 * $criteria->deny[]   = ezpContentCriteria::field( 'title' )->startsWith( 'excludedString' );
 * $articles = ezpRepository::query( $criteria )
 * </code>
 *
 * @package API
 */
class ezpContentCriteria
{
    public function __construct()
    {
        $this->accept = new ezpContentCriteriaSet();
        $this->deny   = new ezpContentCriteriaSet();
    }

    /**
     * Creates a location criteria
     * @return ezpContentLocationCriteria
     */
    public static function location()
    {
        return new ezpContentLocationCriteria();
    }

    /**
     * Creates a field criteria
     * @param mixed $fieldIdentifier
     * @return ezpContentFieldCriteria
     */
    public static function field( $fieldIdentifier )
    {
        return new ezpContentFieldCriteria( $fieldIdentifier );
    }

    /**
     * Creates a content class criteria
     * @return ezpContentClassCriteria
     */
    public static function contentClass()
    {
        return new ezpContentClassCriteria();
    }

    /**
     * Creates a limit criteria
     * @return ezpContentLimitCriteria
     */
    public static function limit()
    {
        return new ezpContentLimitCriteria();
    }

    /**
     * Creates a sorting criteria
     * @param string $sortKey The sort key. Only non-attribute keys are supported (see {@link http://goo.gl/xvJMM})
     * @return ezpContentSortingCriteria
     */
    public static function sorting( $sortKey, $sortOrder )
    {
        return new ezpContentSortingCriteria( $sortKey, $sortOrder );
    }

    /**
     * Creates a depth criteria
     * @param int $depth The maximum level of depth that should be explored (1 by default)
     */
    public static function depth( $depth = 1 )
    {
        return new ezpContentDepthCriteria( $depth );
    }

    /**
     * Static method magic method
     * Handles requests for custom criteria
     *
     * @param string $method Custom criteria name
     * @param array $arguments Custom criteria arguments
     */
    public static function __callStatic( $method, $arguments )
    {
    }

    /**
     * Custom criteria factory.
     *
     * @note This is a PHP < 5.3 replacement for __callStatic. Use the static call if you use PHP 5.3+
     *
     * @param string $method
     * @return ezpContentCriteriaInterface
     */
    public static function custom( $method, $arguments )
    {
    }

    /**
     * Human readable string representation of the criteria, for debugging purpose
     */
    public function __toString()
    {
        $acceptCriteriaCount = count( $this->accept );
        $denyCriteriaCount = count( $this->deny );

        $string = "Criteria\n";
        $string .=  "- $acceptCriteriaCount accept criteria";
        if ( $acceptCriteriaCount > 0 )
        {
            $string .= ":\n";
            foreach( $this->accept as $acceptCriteria )
                $string .= "    - {$acceptCriteria}\n";
        }
        else
            $string .= "\n";

        $string .=  "- $denyCriteriaCount deny criteria";
        if ( $denyCriteriaCount > 0 )
        {
            $string .= ":\n";
            foreach( $this->deny as $denyCriteria )
                $string .= "    - {$denyCriteria}\n";
        }
        else
            $string .= "\n";

        return $string;
    }

    /**
     * Accept (positive) criteria list
     * @var ezpContentCriteriaSet
     */
    public $accept;

    /**
     * Deny (negative) criteria list
     * @var ezpContentCriteriaSet
     */
    public $deny;
}
?>