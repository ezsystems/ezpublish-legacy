<?php
/**
 * File containing the ezpRestRequest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package rest
 */

/**
 * Class mimicking ezcMvcRequest with distinct containers for GET and POST variables.
 *
 * The current implementation is a tentative implementation, for long term
 * usage, we are likely to use dedicated structs such as for cookie. This in
 * addition or alternatively to a more selective parser, which could cherry pick
 * variables depending on request type, context and so forth.
 */
class ezpRestRequest extends ezcMvcRequest
{
    /**
     * GET variables
     *
     * @var array
     */
    public $get;

    /**
     * POST variables
     *
     * @var array
     */
    public $post;

    /**
     * Original request method
     *
     * @var string
     */
    public $originalProtocol;

    /**
     * Variables related to content, extracted from GET
     *
     * @var array
     */
    public $contentVariables;

    /**
     * Signifies whether the request was made over an encrypted connection.
     *
     * @var bool
     */
    public $isEncrypted;

    /**
     * Constructs a new ezpRestRequest.
     *
     * @param DateTime $date
     * @param string $protocol
     * @param string $host
     * @param string $uri
     * @param string $requestId
     * @param string $referrer
     * @param array $variables Containing request variables set by the router
     * @param array $get The GET variables which are available in the request
     * @param array $post The POST variables that are available in the request
     * @param array $contentVariables GET variables related to eZ Publish content
     * @param bool $isEncrypted Is the request made over an encrypted connection
     * @param string $body
     * @param array(ezcMvcRequestFile) $files
     * @param ezcMvcRequestAccept $accept
     * @param ezcMvcRequestUserAgent $agent
     * @param ezcMvcRequestAuthentication $authentication
     * @param ezcMvcRawRequest $raw
     * @param array(ezcMvcRequestCookie) $cookies
     * @param bool $isFatal
     * @param string|null $originalProtocol Uses $protocol if null
     * @return ezpRestRequest
     *
     */
    public function __construct( $date = null, $protocol = '',
        $host = '', $uri = '', $requestId = '', $referrer = '',
        $variables = array(), $get = array(), $post = array(),
        $contentVariables = array(), $isEncrypted = false, $body = '',
        $files = null, $accept = null, $agent = null, $authentication = null,
        $raw = null, $cookies = array(), $isFatal = false, $originalProtocol = null )
    {
        $this->date = $date;
        $this->protocol = $protocol;
        $this->host = $host;
        $this->uri = $uri;
        $this->requestId = $requestId;
        $this->referrer = $referrer;
        $this->variables = $variables;
        $this->get = $get;
        $this->post = $post;
        $this->contentVariables = $contentVariables;
        $this->isEncrypted = $isEncrypted;
        $this->body = $body;
        $this->files = $files;
        $this->accept = $accept;
        $this->agent = $agent;
        $this->authentication = $authentication;
        $this->raw = $raw;
        $this->cookies = $cookies;
        $this->originalProtocol = ( $originalProtocol === null ? $protocol : $originalProtocol );
    }

    /**
     * Returns a new instance of this class with the data specified by $array.
     *
     * $array contains all the data members of this class in the form:
     * array('member_name'=>value).
     *
     * __set_state makes this class exportable with var_export.
     * var_export() generates code, that calls this method when it
     * is parsed with PHP.
     *
     * @param array(string=>mixed) $array
     * @return ezpRestRequest
     */
    static public function __set_state( array $array )
    {
        return new ezpRestRequest( $array['date'], $array['protocol'],
            $array['host'], $array['uri'], $array['requestId'],
            $array['referrer'], $array['variables'], $array['get'],
            $array['post'], $array['contentVariables'], $array['isEncrypted'],
            $array['body'], $array['files'], $array['accept'], $array['agent'],
            $array['authentication'], $array['raw'], $array['cookies'],
            $array['isFatal'], $array['originalProtocol'] );
    }

    /**
     * Returns base URI with protocol and host (e.g. http://myhost.com/foo/bar)
     *
     * @return string
     */
    public function getBaseURI()
    {
        $hostUri = $this->getHostURI();
        $apiName = ezpRestPrefixFilterInterface::getApiProviderName();
        $apiPrefix = eZINI::instance( 'rest.ini' )->variable( 'System', 'ApiPrefix');
        $uri = str_replace( $apiPrefix, $apiPrefix.'/'.$apiName, $this->uri );
        $baseUri = $hostUri.$uri;

        return $baseUri;
    }

    /**
     * Returns the host with the protocol
     *
     * @return string
     */
    public function getHostURI()
    {
        $protIndex = strpos( $this->protocol, '-' );
        $protocol = substr( $this->protocol, 0, $protIndex );
        $hostUri = $protocol.'://'.$this->host;

        return $hostUri;
    }

    /**
     * Returns current content variables as a regular query string (e.g. "foo=bar&this=that")
     *
     * @param bool $withQuestionMark If true, the question mark ("?") will be added
     * @return string
     */
    public function getContentQueryString( $withQuestionMark = false )
    {
        $queryString = '';
        $aParams = array();
        foreach( $this->contentVariables as $name => $value )
        {
            if( $value !== null )
                $aParams[] = $name.'='.$value;
        }

        if( !empty( $aParams ) )
        {
            $queryString  = $withQuestionMark ? '?' : '';
            $queryString .= implode( '&', $aParams );
        }
        return $queryString;
    }

    /**
     * Get parsed request body based on content type as a php hash.
     *
     * In PUT / DELETE currently only supports application/x-www-form-urlencoded and application/json,
     * for anything else use ->body atm. If POST then ->post is returned.
     *
     * @todo Add some sort of configurable lazy loaded request body handler for parsing misc content type.
     * @return array|null Null on unsupported protocol or content type.
     */
    public function getParsedBody()
    {
        if ( $this->originalProtocol === 'http-put' ||  $this->originalProtocol === 'http-delete' )
        {
            if ( !isset( $this->raw['CONTENT_TYPE'] ) )
                return null;

            if ( empty( $this->body ) )
                return array();

            if ( strpos( $this->raw['CONTENT_TYPE'], 'application/x-www-form-urlencoded' ) === 0 )
            {
                parse_str( $this->body, $parsedBody );
                return $parsedBody;
            }
            else if ( strpos( $this->raw['CONTENT_TYPE'], 'application/json' ) === 0 )
            {
                return json_decode( $this->body, true );
            }
        }
        else if ( $this->originalProtocol === 'http-post' )
        {
            return $this->post;
        }
        return null;
    }
}
?>
