<?php
/**
 * File containing the ezpContent class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package API/Content
 */

/**
 * Class that represents a content item.
 *
 * A content item is an abstract representation of a contentobject + contentobjecttreenode + attributes
 * Provides full access to a content's data:
 * - fields (attributes) per language
 * - content metadata (publishing date, owner, etc)
 * - content locations
 *
 * Example:
 * <code>
 * $article = ezpContent::create( "article" );
 * $article->fields->title = 'My article has a title';
 * $article->fields->body = 'My article also has a body';
 * $article->locations[] = $locationObject;
 *
 * // publish using service object
 * </code>
 */
class ezpContent
{
    /**
     * Instanciates a new content item
     *
     * @param string $contentType The content class (article, folder, etc)
     * @return ezpContent
     */
    public static function create( $contentType )
    {
    }

    /**
     * Content (object) attributes
     * @var ezpContentFieldSet
     */
    public $fields;

    /**
     * Content locations
     * @var ezpLocationSet
     */
    public $locations;
}
?>