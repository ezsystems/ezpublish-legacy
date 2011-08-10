<?php
/**
 * File containing the eZURI class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/**
 * Provides access to the HTTP uri

 * The URI can be accessed one element at a time with element() and elements() and
 * can be iterated with increase() and the current index returned with index(). Movin
 * the beginning and end is done with toBeginning() and toEnd().
 * The base can be retrieved with base() and the elements with elements().
 *
 * This class also supports the attribute system.
 *
 *
 * The index starts at 0
 *
 * @package eZHTTP
 */

class eZURI
{
    /**
     * The original URI string
     *
     * @var string
     */
    public $URI;

    /**
     * The URI array
     *
     * @var array
     */
    public $URIArray;

    /**
     * The current index
     *
     * @var int
     */
    public $Index;

    /**
     * User defined template variables
     *
     * @var array
     */
    public $UserArray;

    /**
     * URI transformation mode used by transformURI().
     *
     * @var string
     *
     * @see transformURI()
     * @see getTransformURIMode()
     * @see setTransformURIMode()
     */
    private static $transformURIMode = "relative";

    /**
     * Initializes with the URI string $uri. The URI string is split on / into an array.
     *
     * @param string $uri the URI to use
     * @return void
    */
    public function __construct( $uri )
    {
        $this->setURIString( $uri );
    }

    /**
     * Decodes a path string which is in IRI format and returns the new path in the internal encoding.
     *
     * More info on IRI here: {@link http://www.w3.org/International/O-URL-and-ident.html}
     *
     * @param string $str the string to decode
     * @return string decoded version of $str
     */
    public static function decodeIRI( $str )
    {
        $str = urldecode( $str ); // Decode %xx entries, we now have a utf-8 string
        $codec = eZTextCodec::instance( 'utf-8' ); // Make sure string is converted from utf-8 to internal encoding
        return $codec->convertString( $str );
    }

    /**
     * Encodes path string in internal encoding to a new string which conforms to the IRI specification.
     *
     * More info on IRI here: {@link http://www.w3.org/International/O-URL-and-ident.html}
     *
     * @param string $str the IRI to encode
     * @return string $str encoded as IRU
     */
    public static function encodeIRI( $str )
    {
        $codec = eZTextCodec::instance( false, 'utf-8' );
        $str = $codec->convertString( $str ); // Make sure the string is in utf-8
        $out = explode( "/", $str ); // Don't encode the slashes
        foreach ( $out as $i => $o )
        {
            if ( preg_match( "#^[\(]([a-zA-Z0-9_]+)[\)]#", $o, $m ) )
            {
                // Don't encode '(' and ')' in user parameters
                $out[$i] = '(' . urlencode( $m[1] ) . ')';
            }
            else
            {
                $out[$i] = urlencode( $o ); // Let PHP do the rest
            }
        }
        $tmp = join( "/", $out );
        // Don't encode '~' in URLs
        $tmp = str_replace( '%7E', '~', $tmp );
        return $tmp;
    }

    /**
     * Parse URL and encode/decode its path string
     *
     * @param string $url the URL to parse
     * @param boolean $encode tells to encode the URI
     * @return string the parsed url
     */
    public static function codecURL( $url, $encode )
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

    /**
     * Encodes path string of URL in internal encoding to a new string which conforms to the IRI specification.
     *
     * @param type $url
     * @return string the encoded url
     */
    public static function encodeURL( $url )
    {
        return eZURI::codecURL( $url, true );
    }

    /**
     * Decodes URL which has path string is in IRI format and returns the new URL with path in the internal encoding.
     *
     * @param string $url url to decode
     * @return string the decoded url
     */
    public static function decodeURL( $url )
    {
        return eZURI::codecURL( $url, false );
    }

    /**
     * Sets uri string for instance
     *
     * Sets the current URI string to $uri, the URI is then split into array elements
     * and index reset to 1.
     *
     * @param string $uri
     * @param boolean $fullInitialize
     * @return void
     */
    public function setURIString( $uri, $fullInitialize = true )
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

    /**
     * Get the uri string
     *
     * The URI will not include the leading / if $withLeadingSlash is true.
     *
     * @param boolean $withLeadingSlash prefix the uri with /
     * @return string the URI passed as to the object
     */
    public function uriString( $withLeadingSlash = false )
    {
        $uri = $this->URI;
        if ( $withLeadingSlash )
            $uri = "/$uri";
        return $uri;
    }

    /**
     * Return the original URI
     *
     * the URI will not include the leading / if $withLeadingSlash is true.
     *
     * @param boolean $withLeadingSlash prefix the uri with /
     * @return string the URI passed to the object with user parameters (if any).
     */
    public function originalURIString( $withLeadingSlash = false )
    {
        $uri = $this->OriginalURI;
        if ( $withLeadingSlash )
            $uri = "/$uri";
        return $uri;
    }

    /**
     * Check if there URI is set
     *
     * @return boolean true if the URI is empty, ie it's equal to / or empty string.
     */
    public function isEmpty()
    {
        return $this->URI == '' or $this->URI == '/';
    }

    /**
     * Get element index from uri
     *
     * @param integer $index the index of URI to return
     * @param boolean $relative if index is relative to the internal pointer
     * @return string|null the element at $index
     */
    public function element( $index = 0, $relative = true )
    {
        $pos = $index;
        if ( $relative )
            $pos += $this->Index;
        if ( isset( $this->URIArray[$pos] ) )
            return $this->URIArray[$pos];
        $ret = null;
        return $ret;
    }

    /**
     * Return all URI elements
     *
     * @param boolean $as_text return the elements as string
     * @return array|string all elements as string/array depending on $as_text
     */
    public function elements( $as_text = true )
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

    /**
     * Converts filter string to current locale. When an user types in browser
     * url like: "/content/view/full/2/(namefilter)/a" 'a' letter should be
     * urldecoded and converted from utf-8 to current locale.
     *
     * @return string converted string
     */
    public function convertFilterString()
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

    /**
     * Get user variables
     *
     * @return array all the user defined variables
     */
    public function userParameters()
    {
        return $this->UserArray;
    }

    /**
     * Reset the internal pointer
     *
     * @return void
     */
    public function toBeginning()
    {
        $this->Index = 0;
    }

    /**
     * Moves the index to the end.
     *
     * @return void
     */
    public function toEnd()
    {
        $this->Index = count( $this->URIArray );
    }

    /**
     * Moves the index 1 step up or $num if specified.
     *
     * @param int $num number of steps to move pointer
     * @return void
     */
    public function increase( $num = 1 )
    {
        $this->Index += $num;
        if ( $this->Index < 0 )
            $this->Index = 0;
    }

    /**
     * Removes all elements below the current index, recreates the URI string
     * and sets index to 0.
     *
     * @return void
     */
    public function dropBase()
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

    /**
     * Return the current position of pointer
     *
     * @return int the current pointer position.
     */
    public function index()
    {
        return $this->Index;
    }

    /**
     * Return the elements before pointer
     *
     * @param type $as_text return as text or array
     * @return string|array the base string or the base elements as an array if $as_text is true.
     */
    public function base( $as_text = true )
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

    /**
     * Tries to match the base of $uri against this base and returns the result.
     * A match is made if all elements of this object match the base elements of
     * the $uri object, this means that $uri's base may be longer than this base but
     * not shorter.
     *
     * @param eZURI $uri the uri to match against
     * @return boolean
     */
    public function matchBase( eZURI $uri )
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

    /**
     * Export all attributes of the object
     *
     * @return array the attributes for the object
     */
    public function attributes()
    {
        return array( 'element',
                      'base',
                      'tail',
                      'index',
                      'uri',
                      'original_uri',
                      'query_string' );
    }

    /**
     * Check if attribute exsits
     *
     * @param string $attr the attrbiute to check if exists
     * @return boolean
     */
    public function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    /**
     * Get value for an attribute
     *
     * @param string $attr
     * @return boolean the value for attribute $attr or null if it does not exist.
     */
    public function attribute( $attr )
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
            case 'query_string':
                return eZSys::queryString();
                break;
            default:
            {
                eZDebug::writeError( "Attribute '$attr' does not exist", __METHOD__ );
                return null;
            } break;
        }
    }

    /**
     * Returns a shared instance of the eZURI class IF $uri is false or the same as current
     * request uri, if not then a new non shared instance is created.
     *
     * @param false|string $uri Shared uri instance if false
     * @return eZURI
     */
    public static function instance( $uri = false )
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

    /**
     * Implementation of an 'ezurl' template operator
     * Makes valid eZ Publish urls to use in links
     *
     * @param string &$href
     * @param boolean $ignoreIndexDir
     * @param string $serverURL full|relative
     * @return string the link to use
     */
    public static function transformURI( &$href, $ignoreIndexDir = false, $serverURL = null )
    {
        if ( $serverURL === null )
        {
            $serverURL = self::$transformURIMode;
        }

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
        $href = str_replace( '&amp;amp;', '&amp;', htmlspecialchars( $href ) );

        if ( $href == "" )
            $href = "/";

        return true;
    }

    /**
     * Returns the current mode used for transformURI().
     *
     * @see transformURI()
     * @see setTransformURIMode()
     *
     * @return string
     */
    public static function getTransformURIMode()
    {
        return self::$transformURIMode;
    }

    /**
     * Sets the current mode used for transformURI() to $mode.
     *
     * @see transformURI()
     * @see getTransformURIMode()
     *
     * @param string $mode
     */
    public static function setTransformURIMode( $mode )
    {
        self::$transformURIMode = $mode;
    }

}

?>
