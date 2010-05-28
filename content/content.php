<?php
/**
 * File containing the ezpContent class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package API
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
 * @package API
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
     * Instanciates an ezpContent from an eZContentObjectTreeNode
     * @param eZContentObjectTreeNode $node
     * @return ezpContent
     */
    public static function fromNode( eZContentObjectTreeNode $node )
    {
        $content = new ezpContent();

        // from a node, we need:
        // - fields (data_map) - DONE
        // - locations (nodes, including alternative ones)
        $content->fields = ezpContentFieldSet::fromContentObject( $node->object() );

        return $content;
    }

    /**
     * Instanciates an ezpContent from an eZContentObjectTreenode Id
     * @param int $nodeId
     * @return ezpContent
     */
    public static function fromNodeId( $nodeId )
    {
        $node = eZContentObjectTreeNode::fetch( 2 );
        if ( $node instanceof eZContentObjectTreeNode )
        {
            return self::fromNode( $node );
        }
        else
        {
            throw new ezcBaseExtension( "Unable to find node with ID $nodeId" );
        }
    }

    /**
     * Sets the currently active language for attributes handling
     * @param string $activeLanguage Locale, as xxx-XX
     * @return void
     */
    public function setActiveLanguage( $language )
    {
        $this->fields->setActiveLanguage( $language );
    }

    /**
     * Content (object) attributes
     * @var ezpContentFieldSet
     */
    public $fields;

    /**
     * Content locations
     * @var ezpContentLocationSet
     */
    public $locations;
}
?>