<?php
//
// Definition of eZURI class
//
// Created on: <10-Apr-2002 13:47:41 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZURI ezuri.php
  \ingroup eZHTTP
  \brief Provides access to the HTTP uri

  The URI can be accessed one element at a time with element() and elements() and
  can be iterated with increase() and the current index returned with index(). Moving
  the beginning and end is done with toBeginning() and toEnd().
  The base can be retrieved with base() and the elements with elements().

  This class also supports the attribute system.

  \note The index starts at 1
  \todo Add support for modifiyin elements and outputting as an URI string
  \todo Add range checking
*/

class eZURI
{
    /*!
     Initializes with the URI string $uri. The URI string is split on / into an array.
    */
    function eZURI( $uri )
    {
        $this->setURIString( $uri );
    }

    /*!
     Sets the current URI string to $uri, the URI is then split into array elements
     and index reset to 1.
    */
    function setURIString( $uri )
    {
        $this->URI = $uri;
        $this->URIArray = explode( "/", $uri );
        $this->Index = 1;
    }

    /*!
     \return true if the URI is empty, ie it's equal to / or empty string.
    */
    function isEmpty()
    {
        return $this->URI == "" or $this->URI == "/";
    }

    /*!
     \return the element at $index.
     If $relative is true the index is relative to the current index().
    */
    function element( $index = 0, $relative = true )
    {
        $pos = $index;
        if ( $relative )
            $pos += $this->Index;
        if ( isset( $this->URIArray[$pos] ) )
            return $this->URIArray[$pos];
        else
            return null;
    }

    /*!
     \return all elements as a string, this is all elements after the current index.
     If $as_text is false the returned item is an array.
    */
    function elements( $as_text = true )
    {
        $elements = array_slice( $this->URIArray, $this->Index );
        if ( $as_text )
            return implode( "/", $elements );
        else
            return $elements;
    }

    /*!
     Moves the index to the beginning.
    */
    function toBeginning()
    {
        $this->Index = 1;
    }

    /*!
     Moves the index to the end.
    */
    function toEnd()
    {
        $this->Index = count( $this->URIArray );
    }

    /*!
     Moves the index 1 step up or $num if specified.
    */
    function increase( $num = 1 )
    {
        $this->Index += $num;
    }

    /*!
     \return the current index.
    */
    function index()
    {
        return $this->Index;
    }

    /*!
     \return the base string or the base elements as an array if $as_text is true.
     \sa elements
    */
    function base( $as_text = true )
    {
        $elements = array_slice( $this->URIArray, 1, $this->Index - 1 );
        if ( $as_text )
            return "/" . implode( "/", $elements );
        else
            return $elements;
    }

    /*!
     Tries to match the base of $uri against this base and returns the result.
     A match is made if all elements of this object match the base elements of
     the $uri object, this means that $uri's base may be longer than this base but
     not shorter.
     \note $uri must be a ezuri object
    */
    function matchBase( &$uri )
    {
        if ( get_class( $uri ) != "ezuri" )
            return false;
        if ( count( $this->URIArray ) == 0 or
             count( $uri->URIArray ) == 0 )
            return false;
        for ( $i = 0; $i < count( $this->URIArray ); ++$i )
        {
            if ( $this->URIArray[$i] != $uri->URIArray[$i] )
                return false;
        }
        return true;
    }

    /*!
     \return the attributes for this object.
    */
    function attributes()
    {
        return array( "element", "base", "tail", "index" );
    }

    /*!
     \return true if the attribute $attr exist.
    */
    function hasAttribute( $attr )
    {
        return $attr == "element" or $attr == "base" or $attr == "tail" or $attr == "index";
    }

    /*!
     \return the value for attribute $attr or null if it does not exist.
    */
    function &attribute( $attr )
    {
        switch ( $attr )
        {
            case "element":
                return $this->element();
            case "tail":
                return $this->elements();
            case "base":
                return $this->base();
            case "index":
                return $this->index();
        }
        return null;
    }

    /*!
     \return the unique instance for the URI, if $uri is supplied it used as the global URI value.
    */
    function &instance( $uri = false )
    {
        $uri_obj =& $GLOBALS["eZURIInstance"];
        if ( get_class( $uri_obj ) != "ezuri" )
        {
            $uri_obj = new eZURI( $uri );
        }
        return $uri_obj;
    }

    /// The original URI string
    var $URI;
    /// The URI array
    var $URIArray;
    /// The current index
    var $Index;
};

?>
