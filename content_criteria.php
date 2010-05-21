<?php
/**
 * Content list criteria mechanism.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
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

    /**
     * Create a location criteria
     * @return ezpContentLocationCriteria
     */
    public static function location()
    {
        $this->accept = new ezpContentCriteriaSet();
        $this->deny   = new ezpContentCriteriaSet();
    }

    /**
     * Create a field criteria
     *
     * @param mixed $fieldIdentifier
     *
     * @return ezpContentFieldCriteria
     */
    public static function field( $fieldIdentifier )
    {
        return new ezpContentFieldCriteria( $fieldIdentifier );
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
     * Accept (positive) criteria list
     * @var ezpContentCriteriaSet
     */
    private $accept;

    /**
     * Deny (negative) criteria list
     * @var ezpContentCriteriaSet
     */
    private $deny;
}
?>