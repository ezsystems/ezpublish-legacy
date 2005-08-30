<?php
//
// Definition of eZURI class
//
// Created on: <10-Apr-2002 13:47:41 amos>
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

/*!
  \class eZURI ezuri.php
  \ingroup eZHTTP
  \brief Provides access to the HTTP uri

  The URI can be accessed one element at a time with element() and elements() and
  can be iterated with increase() and the current index returned with index(). Moving
  the beginning and end is done with toBeginning() and toEnd().
  The base can be retrieved with base() and the elements with elements().

  This class also supports the attribute system.

  \note The index starts at 0
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
    function setURIString( $uri, $fullInitialize = true )
    {
        if ( strlen( $uri ) > 0 and
             $uri[0] == '/' )
            $uri = substr( $uri, 1 );
        $this->URI = $uri;
        $this->URIArray = explode( '/', $uri );
        $this->Index = 0;

        if ( $fullInitialize )
        {
            $this->OriginalURI = $uri;

            $this->UserArray = array();

            foreach( array_keys( $this->URIArray ) as $key )
            {
                if ( isset( $this->URIArray[$key] ) && preg_match( "(^[\(][a-zA-Z0-9_]+[\)])", $this->URIArray[$key] ) )
                {
                    $this->UserArray[substr( $this->URIArray[$key], 1, strlen( $this->URIArray[$key] ) - 2 )] = $this->URIArray[$key+1];
                    unset( $this->URIArray[$key] );
                    unset( $this->URIArray[$key+1] );
                }
            }
            // Remake the URI without any user parameters
            $this->URI = implode( '/', $this->URIArray );

            include_once( 'lib/ezutils/classes/ezini.php' );
            $ini =& eZINI::instance( 'template.ini' );
            if ( $ini->variable( 'ControlSettings', 'AllowUserVariables' ) == 'false' )
            {
            $this->UserArray = array();
            }
        }
    }

    /*!
     \return the URI passed as to the object.
     \note the URI will not include the leading \c / if \a $withLeadingSlash is \c true.
    */
    function &uriString( $withLeadingSlash = false )
    {
        $uri = $this->URI;
        if ( $withLeadingSlash )
            $uri = "/$uri";
        return $uri;
    }

    /*!
     \return the URI passed to the object with user parameters (if any).
     \note the URI will not include the leading \c / if \a $withLeadingSlash is \c true.
    */
    function &originalURIString( $withLeadingSlash = false )
    {
        $uri = $this->OriginalURI;
        if ( $withLeadingSlash )
            $uri = "/$uri";
        return $uri;
    }

    /*!
     \return true if the URI is empty, ie it's equal to / or empty string.
    */
    function isEmpty()
    {
        return $this->URI == '' or $this->URI == '/';
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
    function &elements( $as_text = true )
    {
        $elements = array_slice( $this->URIArray, $this->Index );
        if ( $as_text )
        {
            $retValue = implode( '/', $elements );
            return $retValue;
        }
        else
            return $elements;
    }

    /*
     \return all user defined variables
    */
    function userParameters()
    {
        return $this->UserArray;
    }

    /*!
     Moves the index to the beginning.
    */
    function toBeginning()
    {
        $this->Index = 0;
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
        if ( $this->Index < 0 )
            $this->Index = 0;
    }

    /*!
     Removes all elements below the current index, recreates the URI string
     and sets index to 0.
    */
    function dropBase()
    {
        $elements = array_slice( $this->URIArray, $this->Index );
        $this->URIArray = $elements;
        $this->URI = implode( '/', $this->URIArray );
        $uri = $this->URI;
        foreach ( $this->UserArray as $name => $value )
        {
            $uri .= '/(' . $name . ')/' . $value;
        }
        $this->OriginalURI = $uri;
        $this->Index = 0;
    }

    /*!
     \return the current index.
    */
    function &index()
    {
        return $this->Index;
    }

    /*!
     \return the base string or the base elements as an array if $as_text is true.
     \sa elements
    */
    function &base( $as_text = true )
    {
        $elements = array_slice( $this->URIArray, 0, $this->Index );
        if ( $as_text )
        {
            $baseAsText = '/' . implode( '/', $elements );
            return $baseAsText;
        }
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
        if ( get_class( $uri ) != 'ezuri' )
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
        return array( 'element',
                      'base',
                      'tail',
                      'index',
                      'uri',
                      'original_uri' );
    }

    /*!
     \return true if the attribute $attr exist.
    */
    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    /*!
     \return the value for attribute $attr or null if it does not exist.
    */
    function &attribute( $attr )
    {
        switch ( $attr )
        {
            case 'element':
                $retValue =& $this->element();
                break;
            case 'tail':
                $retValue =& $this->elements();
                break;
            case 'base':
                $retValue =& $this->base();
                break;
            case 'index':
                $retValue =& $this->index();
                break;
            case 'uri':
                $retValue =& $this->uriString();
                break;
            case 'original_uri':
                $retValue =& $this->originalURIString();
                break;
            default:
            {
                eZDebug::writeError( "Attribute '$attr' does not exist", 'eZURI::attribute' );
                $retValue = null;
            } break;
        }
        return $retValue;
    }

    /*!
     \return the unique instance for the URI, if $uri is supplied it used as the global URI value.
    */
    function &instance( $uri = false )
    {
        $uri_obj =& $GLOBALS['eZURIInstance'];
        if ( $uri )
        {
            $uri_obj = new eZURI( $uri );
        }
        if ( get_class( $uri_obj ) != 'ezuri' )
        {
            $uri_obj = new eZURI( $uri );
        }
        return $uri_obj;
    }

    /*!
     Implementation of an 'ezurl' template operator.
     Makes valid ez publish urls to use in links.
    */
    function transformURI( &$href )
    {
        if ( preg_match( "#^[a-zA-Z0-9]+:#", $href ) || substr( $href, 0, 2 ) == '//' )
            return false;

        if ( strlen( $href ) == 0 )
            $href = '/';
        else if ( $href[0] == '#' )
        {
            $href = htmlspecialchars( $href );
            return true;
        }
        else if ( $href[0] != '/' )
        {
            $href = '/' . $href;
        }

        include_once( 'lib/ezutils/classes/ezsys.php' );
        $sys =& eZSys::instance();
        $href = $sys->indexDir() . $href;
        $href = preg_replace( "#^(//)#", "/", $href );
        $href = preg_replace( "#(^.*)(/+)$#", "\$1", $href );
        $href = htmlspecialchars( $href );

        return true;
    }

    /// The original URI string
    var $URI;
    /// The URI array
    var $URIArray;
    /// The current index
    var $Index;
    /// User defined template variables
    var $UserArray;
};

?>
