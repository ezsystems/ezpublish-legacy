<?php
//
// Definition of eZTemplateSectionIterator class
//
// Created on: <26-Feb-2004 11:33:05 >
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file eztemplatesectioniterator.php
*/

/*!
  \class eZTemplateSectionIterator eztemplatesectioniterator.php
  \ingroup eZTemplateFunctions
  \brief The iterator item in a section loop which works as a proxy.

  The iterator provides transparent access to iterator items. It will
  redirect all attribute calls to the iterator item with the exception
  of a few internal values. The internal values are
  - item - The actual item, provides backwards compatability
  - key - The current key
  - index - The current index value (starts at 0 and increases with 1 for each element)
  - number - The current index value + 1 (starts at 1 and increases with 1 for each element)
  - sequence - The current sequence value
  - last - The last iterated element item
*/

class eZTemplateSectionIterator
{
    /*!
     Initializes the iterator with empty values.
    */
    function eZTemplateSectionIterator()
    {
        $this->InternalAttributes = array( 'item' => false,
                                           'key' => false,
                                           'index' => false,
                                           'number' => false,
                                           'sequence' => false,
                                           'last' => false );
        $this->InternalAttributeNames = array_keys( $this->InternalAttributes );
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
        $item =& $this->InternalAttributes['item'];
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
        if ( in_array( $name, $this->InternalAttributeNames ) )
            return true;
        $item =& $this->InternalAttributes['item'];
        if ( is_array( $item ) )
        {
            return in_array( $name, array_keys( $item ) );
        }
        else if ( is_object( $item ) and
                  method_exists( $item, 'hasAttribute' ) )
        {
            return $item->hasAttribute( $name );
        }
        return false;
    }

    /*!
     \return the attribute value of either the internal attributes or
             from the item value if the attribute exists for it.
    */
    function &attribute( $name )
    {
        if ( in_array( $name, $this->InternalAttributeNames ) )
        {
            unset( $tempValue );
            $tempValue =& $this->InternalAttributes[$name];
            return $tempValue;
        }
        $item =& $this->InternalAttributes['item'];
        if ( is_array( $item ) )
        {
            $arrayItem = $item[$name];
            return $arrayItem;
        }
        else if ( is_object( $item ) and
                  method_exists( $item, 'attribute' ) )
        {
            unset( $tempValue );
            $tempValue =& $item->attribute( $name );
            return $tempValue;
        }
        eZDebug::writeError( "Attribute '$name' does not exist", 'eZTemplateSectionIterator::attribute' );
        $tempValue = null;
        return $tempValue;
    }

    /*!
     Updates the iterator with the current iteration values.
    */
    function setIteratorValues( &$item, $key, $index, $number, $sequence, &$last )
    {
        $this->InternalAttributes['item'] =& $item;
        $this->InternalAttributes['key'] = $key;
        $this->InternalAttributes['index'] = $index;
        $this->InternalAttributes['number'] = $number;
        $this->InternalAttributes['sequence'] = $sequence;
        $this->InternalAttributes['last'] =& $last;
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
