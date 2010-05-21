<?php
/**
 * File containing the ezpContentFieldSet class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package API
 */

/**
 * Allows for reading/writing of content fields (attributes) through an array like interface. This class is aimed at
 * being used through an ezpContent object, and the following examples will therefore use it.
 *
 * Example 1, with a one language object:
 * <code>
 * $article = ezpContent::create( 'article' );
 * $article->fields->title = 'foo';
 * $article->fields->name = 'bar';
 * </code>
 *
 * Example 2, with a multilingual object:
 * <code>
 * $article = ezpContent::create( 'article' );
 * $article->fields['eng-GB']->title = 'foo';
 * $article->fields['eng-GB']->name = 'bar';
 * $article->fields['fre-FR']->title = 'foo';
 * $article->fields['fre-FR']->name = 'bar';
 * </code>
 *
 * Example 3, with a multilingual object but an alternative syntax
 * <code>
 * $article = ezpContent::create( 'article' );
 *
 * $article->active_language = 'eng-GB';
 * $article->fields->title = 'foo';
 * $article->fields->name = 'bar';
 *
 * $article->active_language = 'fre-FR';
 * $article->fields->title = 'foo';
 * $article->fields->name = 'bar';
 * </code>
 *
 * @package API
 */
class ezpContentFieldSet implements ArrayAccess
{
    /**
     * Initializes the fields set with a content object + language
     * @param int $contentObjectID
     * @param string $locale If not provided, uses the default one for the active siteaccess
     */
    public function __construct( $contentObjectID, $locale = null )
    {

    }

    /**
     * Array exists handler. Can be used to check for existence of a language
     *
     * @note Again, this operation might not belong to the fields set (data map) but to the content itself
    */
    public function offsetExists( $offset )
    {
    }

    /**
     * Array set hander.
     * Will set the requested language's ezpFieldSet. Is that any good ?
     * Might be used to enable a language on a content item:
     * <code>
     * $article->fields['fre-FR'] = true
     * </code>
     * Semantically speaking, this belongs to the content, not the fields. Maybe no need to implement that.
     */
    public function offsetSet( $offset, $value )
    {
    }

    /**
     * Array get handler.
     * Will return the requested language's ezpFieldSet
     *
     * @return ezpContentFieldSet
     */
    public function offsetGet( $offset )
    {
        // This needs to check if this language can be instanciated (e.g. is active on the installation)
        if ( isset( $this->childrenFieldSets[$offset] ) )
        {
            return $this->childrenFieldSets[$offset];
        }
    }

    /**
     * Unset array handler.
     * Should we allow a user to remove a language by using that syntax:
     * <code>
     * unset( $content->fields['eng-GB'] );
     * </code>
     */
    public function offsetUnset( $offset )
    {
    }

    /**
     * Setter. Used to set attributes values.
     */
    public function __set( $name, $value )
    {
        // Check if the attribute is a valid one
        if ( isset( $this->contentObjectAttributes[$name] ) )
        {
            // needs to adapt itself to the given parameter (state object, string, etc)
            // fromString will do for now
            $this->contentObjectAttributes[$name]->fromString( $value );
        }
    }

    /**
     * Getter. Used to get attributes values.
     * @return mixed
     */
    public function __get( $name )
    {
        // Check if the attribute is a valid one
        if ( isset( $this->contentObjectAttributes[$name] ) )
        {
            // needs to return something more sensitive, probably the state object for the attribute's content
            return $this->contentObjectAttributes[$name]->toString();
        }
    }

    /**
     * Isset handler.
     * Could be used to check if an attribute has content:
     * <code>
     * if ( isset( $content->fields->title ) )  <= no title
     * </code>
     *
     * @see ezContentObjectAttribute->hasContent()
     */
    public function __isset( $name )
    {
        return isset( $this->contentObjectAttributes[$name] );
    }

    /**
     * Reference to the parent field set
     * @var ezpContentFieldSet
     */
    private $parentFieldSet;

    /**
     * Reference to the known children field sets
     * @var array( ezpContentFieldSet )
     */
    private $childrenFieldSets;

    /**
     * Fields set content object attributes
     * array( identifier => eZContentObjectAttribute )
     */
    private $contentObjectAttributes;
}
?>