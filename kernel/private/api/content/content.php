<?php
/**
 * File containing the ezpContent class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
 * $article->setActiveLanguage( 'eng-GB' );
 * $article->fields->title = 'foo';
 * $article->fields->name = 'bar';
 *
 * $article->active_language = 'fre-FR';
 * $article->fields->title = 'foo';
 * $article->fields->name = 'bar';
 * </code>
 *
 * @property-read string classIdentifier The content class identifier
 * @property-read int datePublished The date the object was initially published, as a UNIX timestamp
 * @property-read int dateModified The date the object was last modified, as a UNIX timestamp
 * @property-read string name The object's name in the active language
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
    public static function fromNode( eZContentObjectTreeNode $node, $checkAccess = true )
    {
        $object = $node->object();
        if ( $checkAccess && !$object->attribute( 'can_read' ) )
        {
            throw new ezpContentAccessDeniedException( $object->attribute( 'id' ) );
        }

        $content = new ezpContent();
        $content->fields = ezpContentFieldSet::fromContentObject( $object );
        $content->object = $object;
        $content->locations = ezpContentLocation::fromNode( $node );

        return $content;
    }

    /**
     * Instanciates an ezpContent from an eZContentObjectTreenode Id
     * @param int $nodeId
     * @return ezpContent
     */
    public static function fromNodeId( $nodeId, $checkAccess = true )
    {
        $node = eZContentObjectTreeNode::fetch( $nodeId );
        if ( $node instanceof eZContentObjectTreeNode )
            return self::fromNode( $node, $checkAccess );
        else
            throw new ezpContentNotFoundException( "Unable to find node with ID $nodeId" );
    }

    /**
     * Instanciates an ezpContent from an eZContentObject Id
     * @param int $objectId
     * @return ezpContent
     */
    public static function fromObjectId( $objectId, $checkAccess = true )
    {
        $object = eZContentObject::fetch( $objectId );
        if ( $object instanceof eZContentObject )
            return self::fromObject( $object, $checkAccess );
        else
            throw new ezpContentNotFoundException( "Unable to find an eZContentObject with ID $objectId" );
    }

    /**
     * Instanciates an ezpContent from an eZContentObject
     * @param eZContentObject $objectId
     * @return ezpContent
     */
    public static function fromObject( eZContentObject $object, $checkAccess = true )
    {
        if ( $checkAccess && !$object->attribute( 'can_read' ) )
        {
            throw new ezpContentAccessDeniedException( $object->attribute( 'id' ) );
        }

        $content = new ezpContent();
        $content->fields = ezpContentFieldSet::fromContentObject( $object );

        $content->object = $object;

        return $content;
    }

    /**
     * Sets the currently active language for attributes handling
     * @param string $activeLanguage Locale, as xxx-XX
     * @return void
     */
    public function setActiveLanguage( $language )
    {
        $this->fields->setActiveLanguage( $language );
        $this->activeLanguage = $language;
    }

    public function __get( $property )
    {
        switch( $property )
        {
            case 'classIdentifier':
                return $this->object->attribute( 'class_identifier' );
                break;
            case 'owner':
                return $this->object->attribute( 'owner' );
                break;
            case 'name':
                if ( $this->activeLanguage !== null )
                    return $this->object->name( false, $this->activeLanguage );
                else
                    return $this->object->name();
                break;
            case 'datePublished':
                return $this->object->attribute( 'published' );
                break;
            case 'dateModified':
                return $this->object->attribute( 'modified' );
                break;
            default:
                if( $this->object->hasAttribute( $property ) )
                    return $this->object->attribute( $property );
                else
                    throw new ezcBasePropertyNotFoundException( $property );
        }
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

    /**
     * Actual content object
     * @var eZContentObject
     */
    protected $object;

    /**
     * Active content language for this object
     * @var string
     */
    public $activeLanguage;
}
?>