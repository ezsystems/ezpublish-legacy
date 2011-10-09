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
     * PUT & DELETE variables
     *
     * @var array
     */
    public $inputVariables;

    /**
     * Original request method
     *
     * @var string
     */
    public $originalMethod;

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
     * @param array $inputVariables The PUT & DELETE input variables that are available in the request
     * @param string $originalMethod
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
     */
    public function __construct( $date = null, $protocol = '',
        $host = '', $uri = '', $requestId = '', $referrer = '',
        $variables = array(), $get = array(), $post = array(), $inputVariables = array(),
        $originalMethod = '', $contentVariables = array(), $isEncrypted = false, $body = '',
        $files = null, $accept = null, $agent = null, $authentication = null,
        $raw = null, $cookies = array(), $isFatal = false )
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
        $this->inputVariables = $inputVariables;
        $this->originalMethod = $originalMethod;
        $this->contentVariables = $contentVariables;
        $this->isEncrypted = $isEncrypted;
        $this->body = $body;
        $this->files = $files;
        $this->accept = $accept;
        $this->agent = $agent;
        $this->authentication = $authentication;
        $this->raw = $raw;
        $this->cookies = $cookies;
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
            $array['post'], $array['inputVariables'], $array['originalMethod'], $array['contentVariables'],
            $array['isEncrypted'], $array['body'], $array['files'], $array['accept'], $array['agent'],
            $array['authentication'], $array['raw'], $array['cookies'],
            $array['isFatal'] );
    }

    /**
     * Returns base URI with protocol and host (e.g. http://myhost.com/foo/bar)
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
}
?>
