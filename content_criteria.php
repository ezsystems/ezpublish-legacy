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
 *
 * Example:
 * <code>
 * $criteria = new ezpContentCriteria();
 * $criteria->criteria[] = ezpContentCriteria::location->subtree(  ezpContentLocation::fromUrl( ‘/articles’ ) );
 * $criteria->criteria[] = ezpContentCriteria::field( 'title' )->like( 'foo' );
 * $criteria->criteria[] = ezpContentCriteria::field( 'summary' )->startsWith( 'abc' );
 * $articles = ezpRepository::query( $criteria )
 * </code>
 *
 * @package API
 */
class ezpContentCriteria
{
}
?>