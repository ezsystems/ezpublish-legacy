<?php
/**
 * File containing the eZTemplateSectionIterator class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZTemplateSectionIterator eztemplatesectioniterator.php
  \ingroup eZTemplateFunctions
  \brief The iterator item in a section loop which works as a proxy.

  The iterator provides transparent access to iterator items. It will
  redirect all attribute calls to the iterator item with the exception
  of a few internal values. The internal values are
  - item - The actual item, provides backwards compatibility
  - key - The current key
  - index - The current index value (starts at 0 and increases with 1 for each element)
  - number - The current index value + 1 (starts at 1 and increases with 1 for each element)
  - sequence - The current sequence value
  - last - The last iterated element item
*/

class eZTemplateSectionIterator
{
    public $InternalAttributes;
    /**
     * Initializes the iterator with empty values.
     */
    public function __construct()
    {
        $this->InternalAttributes = array( 'item' => false,
                                           'key' => false,
                                           'index' => false,
                                           'number' => false,
                                           'sequence' => false,
                                           'last' => false );
    }

    /*!
     \return the value of the current item for the template system to use.
    */
    function templateValue()
    {
        $value = $this->InternalAttributes['item'];
        return $value;
    }

    /*!
     \return a merged list of attributes from both the internal attributes and the items attributes.
    */
    function attributes()
    {
        $attributes = array();
        $item = $this->InternalAttributes['item'];
        if ( is_array( $item ) )
        {
            $attributes = array_keys( $item );
        }
        else if ( is_object( $item ) and
                  method_exists( $item, 'attributes' ) )
        {
            $attributes = $item->attributes();
        }
        $attributes = array_merge( $this->InternalAttributes, $attributes );
        $attributes = array_unique( $attributes );
        return $attributes;
    }

    /*!
     \return \c true if the attribute \a $name exists either in
             the internal attributes or in the item value.
    */
    function hasAttribute( $name )
    {
        switch ( $name )
        {
            case "item":
            case "key":
            case "index":
            case "number":
            case "sequence":
            case "last":
                return true;
        }
        $item = $this->InternalAttributes['item'];
        if ( is_array( $item ) )
        {
            return array_key_exists( $name, $item );
        }
        if ( is_object( $item ) && method_exists( $item, 'hasAttribute' ) )
        {
            return $item->hasAttribute( $name );
        }
        return false;
    }

    /*!
     \return the attribute value of either the internal attributes or
             from the item value if the attribute exists for it.
    */
    function attribute( $name )
    {
        switch ( $name )
        {
            case "item":
            case "key":
            case "index":
            case "number":
            case "sequence":
            case "last":
                return $this->InternalAttributes[$name];
        }
        $item = $this->InternalAttributes['item'];
        if ( is_array( $item ) )
        {
            return $item[$name];
        }
        if ( is_object( $item ) && method_exists( $item, 'attribute' ) )
        {
            return $item->attribute( $name );
        }
        eZDebug::writeError( "Attribute '$name' does not exist", __METHOD__ );
        return null;
    }

    /*!
     Updates the iterator with the current iteration values.
    */
    function setIteratorValues( $item, $key, $index, $number, $sequence, &$last )
    {
        $this->InternalAttributes['item'] = $item;
        $this->InternalAttributes['key'] = $key;
        $this->InternalAttributes['index'] = $index;
        $this->InternalAttributes['number'] = $number;
        $this->InternalAttributes['sequence'] = $sequence;
        $this->InternalAttributes['last'] = $last;
    }

    /*!
     Updates the current sequence value to \a $sequence.
    */
    function setSequence( $sequence )
    {
        $this->InternalAttributes['sequence'] = $sequence;
    }
}

?>
