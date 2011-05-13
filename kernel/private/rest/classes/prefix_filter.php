<?php
/**
 * File containing the ezpRestPrefixFilterInterface interface
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

abstract class ezpRestPrefixFilterInterface
{
    protected $request;
    /**
     * @var string Extracted version token.
     */
    protected $versionToken;

    /**
     * @var string Extracted api provider token.
     */
    protected $apiProviderToken;

    /**
     * @var int The numerical version number
     */
    protected static $version = null;

    /**
     * @var string Container for extracted API provider name.
     */
    protected static $apiProvider = null;

    /**
     * @var string Container for a global API prefix e.g /api
     */
    protected static $apiPrefix = null;

    /**
     * Creates a new VersionToken object which describes the version token in
     * API strings.
     *
     * @abstract
     * @param ezcMvcRequest $request
     * @param string $apiPrefix The API prefix of HTTP requests going to the REST interface.
     */
    abstract public function __construct( ezcMvcRequest $request, $apiPrefix );

    /**
     * Returns the numerical version of the version token.
     *
     * @abstract
     * @return int version value
     */
    abstract protected function parseVersionValue();

    /**
     * Filters the request object for API provider and version token.
     *
     * The API provider is by default assumed to be the first URI element. Other
     * custom implementations of this interface are free to choose otherwise.
     *
     * If version token exists, gets the numerical value of this token, and
     * filters the URI in the request object, removing said token.
     *
     * This lets us not to deal with any version token data, in our route
     * definitions. This is a benefit, since different systems, might have
     * different preferences for what the version token should look like.
     *
     * @abstract
     * @return void
     */
    abstract public function filter();

    /**
     * Returns the version number of the API call.
     *
     * If no version is found in the request, it defaults to '1'.
     *
     * @return int The version number of the API call.
     */
    public static function getApiVersion()
    {
        if ( self::$version === null )
        {
            return 1;
        }
        return self::$version;
    }

    /**
     * Returns the name of the referenced API provider for the current query.
     *
     * @static
     * @return false|string The identifier of the API provider used in this query.
     */
    public static function getApiProviderName()
    {
        if ( self::$apiProvider === null )
        {
            return false;
        }
        return self::$apiProvider;
    }

    /**
     * Returns a global API prefix
     *
     * @static
     * @return false|string
     */
    public static function getApiPrefix()
    {
        if ( self::$apiPrefix === null )
        {
            return false;
        }
        return self::$apiPrefix;
    }

    /**
     * Filters the URI property of the given ezcMvcRequest object, removing
     * any version token from it.
     *
     * @return void
     */
    public function filterRequestUri()
    {
        if ( !empty( $this->versionToken ) )
        {
            // Remove the first occurrence of version token
            $versionSearch = '/' . $this->versionToken;
            $versionPos = strpos( $this->request->uri, $versionSearch );
            if ( $versionPos !== false )
            {
                $this->request->uri = substr_replace( $this->request->uri, '', $versionPos, strlen( $versionSearch ) );
            }
        }
        if ( !empty( $this->apiProviderToken ) )
        {
            // Remove the first occurrence of API provider token
            $providerSearch = '/' . $this->apiProviderToken;
            $providerPos = strpos( $this->request->uri, $providerSearch );
            if ( $providerPos !== false )
            {
                $this->request->uri = substr_replace( $this->request->uri, '', $providerPos, strlen( $providerSearch ) );
            }
        }
    }
}
