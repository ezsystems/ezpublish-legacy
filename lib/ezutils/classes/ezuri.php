<?php
//
// Definition of eZURI class
//
// Created on: <10-Apr-2002 13:47:41 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
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
     \static
     Decodes a path string which is in IRI format and returns the new path in the internal encoding.

     More info on IRI here: http://www.w3.org/International/O-URL-and-ident.html
     */
    static function decodeIRI( $str )
    {
        $str = urldecode( $str ); // Decode %xx entries, we now have a utf-8 string
        $codec = eZTextCodec::instance( 'utf-8' ); // Make sure string is converted from utf-8 to internal encoding
        return $codec->convertString( $str );
    }

    /*!
     \static
     Encodes path string in internal encoding to a new string which conforms to the IRI specification.

     More info on IRI here: http://www.w3.org/International/O-URL-and-ident.html
     */
    static function encodeIRI( $str )
    {
        $codec = eZTextCodec::instance( false, 'utf-8' );
        $str = $codec->convertString( $str ); // Make sure the string is in utf-8
        $out = explode( "/", $str ); // Don't encode the slashes
        foreach ( $out as $i => $o )
        {
            $out[$i] = urlencode( $o ); // Let PHP do the rest
        }
        return join( "/", $out );
    }

    /*!
     \static
     Parse URL and encode/decode its path string.
     */
    static function codecURL( $url, $encode )
    {
        $originalLocale = setlocale( LC_CTYPE, "0" );
        setlocale( LC_CTYPE, 'C' );
        // Parse URL and encode the path.
        $data = parse_url( $url );
        setlocale( LC_CTYPE, $originalLocale );

        if ( isset( $data['path'] ) )
        {
            if ( $encode )
                $data['path'] = eZURI::encodeIRI( $data['path'] ); // Make sure it is encoded to IRI format
            else
                $data['path'] = eZURI::decodeIRI( $data['path'] ); // Make sure it is dencoded to internal encoding
        }

        // Reconstruct the URL
        $host    = '';
        $preHost = '';
        if ( isset( $data['user'] ) )
        {
            if ( isset( $data['pass'] ) )
                $preHost .= $data['user'] . ':' . $data['pass'] . '@';
            else
                $preHost .= $data['user'] . '@';
        }
        if ( isset( $data['host'] ) )
        {
            if ( isset( $data['port'] ) )
                $host = $preHost . $data['host'] . ':' . $data['port'];
            else
                $host = $preHost . $data['host'];
        }
        $url = '';
        if ( isset( $data['scheme'] ) )
            $url = $data['scheme'] . '://' . $host;
        else if ( strlen( $host ) > 0 )
            $url = '//' . $host;
        if ( isset( $data['path'] ) )
        {
            $url .= $data['path'];
        }
        if ( isset( $data['query'] ) )
        {
            $url .= '?' . $data['query'];
        }
        if ( isset( $data['fragment'] ) )
        {
            $url .= '#' . $data['fragment'];
        }

        return $url;
    }

    /*!
     \static
     Encodes path string of URL in internal encoding to a new string which conforms to the IRI specification.
     */
    static function encodeURL( $url )
    {
        return eZURI::codecURL( $url, true );
    }

    /*!
     Decodes URL which has path string is in IRI format and returns the new URL with path in the internal encoding.
     */
    static function decodeURL( $url )
    {
        return eZURI::codecURL( $url, false );
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

        $uri = eZURI::decodeIRI( $uri );

        $this->URI = $uri;
        $this->URIArray = explode( '/', $uri );
        $this->Index = 0;

        if ( $fullInitialize )
        {
            $this->OriginalURI = $uri;
            $this->UserArray = array();

            $ini = eZINI::instance( 'template.ini' );

            if ( $ini->variable( 'ControlSettings', 'OldStyleUserVariables' ) == 'enabled' )
            {
                foreach( array_keys( $this->URIArray ) as $key )
                {
                    if ( isset( $this->URIArray[$key] ) && preg_match( "(^[\(][a-zA-Z0-9_]+[\)])", $this->URIArray[$key] ) )
                    {
                        $this->UserArray[substr( $this->URIArray[$key], 1, strlen( $this->URIArray[$key] ) - 2 )] = $this->URIArray[$key+1];
                        unset( $this->URIArray[$key] );
                        unset( $this->URIArray[$key+1] );
                    }
                }
            }
            else
            {
                unset( $paramName );
                unset( $paramValue );
                foreach( array_keys( $this->URIArray ) as $key )
                {
                    if ( isset( $this->URIArray[$key] ) )
                    {
                        if ( preg_match( "/^[\(][a-zA-Z0-9_]+[\)]/", $this->URIArray[$key] ) )
                        {
                            if ( isset( $paramName ) and isset( $paramValue ) )
                            {
                                $this->UserArray[ $paramName ] = $paramValue;
                                unset( $paramName );
                                unset( $paramValue );
                            }
                            $paramName = substr( $this->URIArray[$key], 1, strlen( $this->URIArray[$key] ) - 2 );
                            if ( isset( $this->URIArray[$key+1] ) )
                            {
                                $this->UserArray[ $paramName ] = $this->URIArray[$key+1];
                                unset( $this->URIArray[$key+1] );
                            }
                            else
                                $this->UserArray[ $paramName ] = "";
                            unset( $this->URIArray[$key] );
                        }
                        else
                        {
                            if ( isset( $paramName ) )
                            {
                                if ( !empty( $this->URIArray[$key] ) )
                                    $this->UserArray[ $paramName ] .= '/' . $this->URIArray[$key];
                                unset( $this->URIArray[$key] );
                            }
                        }
                    }
                }
            }

            // Remake the URI without any user parameters
            $this->URI = implode( '/', $this->URIArray );

            $ini = eZINI::instance( 'template.ini' );
            if ( $ini->variable( 'ControlSettings', 'AllowUserVariables' ) == 'false' )
            {
                $this->UserArray = array();
            }
            // Convert filter string to current locale
            $this->convertFilterString();
        }
    }

    /*!
     \return the URI passed as to the object.
     \note the URI will not include the leading \c / if \a $withLeadingSlash is \c true.
    */
    function uriString( $withLeadingSlash = false )
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
    function originalURIString( $withLeadingSlash = false )
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
        $ret = null;
        return $ret;
    }

    /*!
     \return all elements as a string, this is all elements after the current index.
     If $as_text is false the returned item is an array.
    */
    function elements( $as_text = true )
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

    /*!
     Converts filter string to current locale.
     When an user types in browser url like:
     "/content/view/full/2/(namefilter)/a"
     'a' letter should be urldecoded and converted from utf-8 to current locale.
    */
    function convertFilterString()
    {
        foreach ( array_keys( $this->UserArray ) as $paramKey )
        {
            if ( $paramKey == 'namefilter' )
            {
                $char = $this->UserArray[$paramKey];
                $char = urldecode( $char );

                $codec = eZTextCodec::instance( 'utf-8', false );
                if ( $codec )
                    $char = $codec->convertString( $char );
            }
        }
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
     \note $uri must be a eZURI object
    */
    function matchBase( $uri )
    {
        if ( !( $uri instanceof eZURI ) )
        {
            return false;
        }
        if ( count( $this->URIArray ) == 0 or
             count( $uri->URIArray ) == 0 )
        {
            return false;
        }
        for ( $i = 0; $i < count( $this->URIArray ); ++$i )
        {
            if ( $this->URIArray[$i] != $uri->URIArray[$i] )
            {
                return false;
            }
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
    function attribute( $attr )
    {
        switch ( $attr )
        {
            case 'element':
                return $this->element();
                break;
            case 'tail':
                return $this->elements();
                break;
            case 'base':
                return $this->base();
                break;
            case 'index':
                return $this->index();
                break;
            case 'uri':
                return $this->uriString();
                break;
            case 'original_uri':
                return $this->originalURIString();
                break;
            default:
            {
                eZDebug::writeError( "Attribute '$attr' does not exist", 'eZURI::attribute' );
                return null;
            } break;
        }
    }

    /*!
     \return the unique instance for the URI, if $uri is supplied it used as the global URI value.
    */
    static function instance( $uri = false )
    {
        // If $uri is false we assume the caller wants eZSys::requestURI()
        if ( $uri === false or $uri == eZSys::requestURI() )
        {
            if ( !isset( $GLOBALS['eZURIRequestInstance'] ) )
            {
                $GLOBALS['eZURIRequestInstance'] = new eZURI( eZSys::requestURI() );
            }
            return $GLOBALS['eZURIRequestInstance'];
        }

        return new eZURI( $uri );
    }

    /*!
     Implementation of an 'ezurl' template operator.
     Makes valid ez publish urls to use in links.
    */
    static function transformURI( &$href, $ignoreIndexDir = false, $serverURL = 'relative' )
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

        $sys = eZSys::instance();
        $dir = !$ignoreIndexDir ? $sys->indexDir() : $sys->wwwDir();
        $serverURL = $serverURL === 'full' ? $sys->serverURL() : '' ;
        $href = $serverURL . $dir . $href;
        if ( !$ignoreIndexDir )
        {
            $href = preg_replace( "#^(//)#", "/", $href );
            $href = preg_replace( "#(^.*)(/+)$#", "\$1", $href );
        }
        $href = htmlspecialchars( $href );

        if ( $href == "" )
            $href = "/";

        return true;
    }

    /// The original URI string
    public $URI;
    /// The URI array
    public $URIArray;
    /// The current index
    public $Index;
    /// User defined template variables
    public $UserArray;
};

?>
