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
 * being used through an ezpContent object, and usage examples can be found there.
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
    public function __construct()
    {
    }

    /**
     * Initializes a level one ezpContentFieldSet from an eZContentObject
     * @param eZContentObject $contentObject
     * @return ezpContentFieldSet
     */
    public static function fromContentObject( eZContentObject $contentObject )
    {
        $set = new ezpContentFieldSet();
        foreach( $contentObject->availableLanguages() as $language )
        {
            $set->childrenFieldSets[$language] =
                ezpContentFieldSet::fromDataMap( $contentObject->fetchDataMap( false, $language ) );
        }
        return $set;
    }

    /**
     * Initializes a level two ezpContentFieldSet from a content object data map
     * @param array $dataMap
     * @return ezpContentFieldSet
     */
    public static function fromDataMap( $dataMap )
    {
        $set = new ezpContentFieldSet();
        foreach( $dataMap as $attribute )
        {
            $identifier = $attribute->attribute( 'contentclass_attribute_identifier' );
            $set->fields[$identifier] = ezpContentField::fromContentObjectAttribute( $attribute );
        }
        return $set;
    }

    /**
     * Array exists handler. Can be used to check for existence of a language
     *
     * Again, this operation might not belong to the fields set (data map) but to the content itself
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
        if ( !isset( $this->childrenFieldSets[$offset] ) )
            throw new Exception( "Language $offset could not be found on this ezpContent" );

        return $this->childrenFieldSets[$offset];
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
    public function __set( $name, eZContentObjectAttribute $value )
    {
        $this->contentObjectAttributes[$name] = $value;
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
        // Request on a level 2 ezpFieldSet, that holds fields (attributes)
        if ( isset( $this->fields[$name] ) )
        {
            return $this->fields[$name];
        }
        // direct request on a level 1 ezpFieldSet, that holds sub ezpFieldSet, but has a default language
        else
        {
            if ( $this->activeLanguage === null )
                throw new Exception( "You need to set an active language in order to query fields directly" );
            else
            {
                return $this[$this->activeLanguage]->$name;
            }
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
     * Sets the currently active language when reading attribute(/object/node) properties
     * @param string $language Language locale (xxx-XX)
     * @return void
     */
    public function setActiveLanguage( $language )
    {
        $this->activeLanguage = $language;
    }

    /**
     * Reference to the parent field set
     * @var ezpContentFieldSet
     */
    protected $parentFieldSet;

    /**
     * Reference to the known children field sets
     * @var array( ezpContentFieldSet )
     */
    protected $childrenFieldSets;

    /**
     * Fields set content object attributes
     * array( identifier => eZContentObjectAttribute )
     */
    protected $fields;

    /**
     * Currently active language, as a locale
     * @var string
     */
    protected $activeLanguage = false;
}
?>