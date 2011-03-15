<?php
/**
 * File containing the ezpRestDefaultRegexpPrefixFilter class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Default implementation of prefix filter interface.
 */
class ezpRestDefaultRegexpPrefixFilter extends ezpRestPrefixFilterInterface
{
    /**
     * Quoted version of the API prefix.
     * @var string
     */
    protected $apiPart;

    /**
     * Creates a new VersionToken object which describes the version token in API strings.
     *
     * @param ezcMvcRequest $request
     * @param string $apiPrefix
     */
    public function __construct( ezcMvcRequest $request, $apiPrefix )
    {
        $this->request = $request;
        self::$apiPrefix = $apiPrefix;
        $this->apiPart = preg_quote( self::$apiPrefix, '@' );
    }

    protected function getPrefixPattern()
    {
        return "@^{$this->apiPart}/(?:(?P<provider>[^(v\d+|/)]+)/)?(?:(?P<version>v\d+))?@";
    }

    /**
     * Returns the numerical version of the version token.
     *
     * @return int version value
     */
    protected function parseVersionValue( )
    {
        if ( empty ( $this->versionToken ) )
        {
            return null;
        }
        return (int)str_replace( 'v', '', $this->versionToken );
    }

    /**
     * Filters the request object for API provider name and version token.
     *
     * API provider name is assumed to be the first URI element.
     *
     * If version token exists, gets the numerical value of this token, and
     * filters the URI in the request object, removing said token.
     *
     * This lets us not to deal with any version token data, in our route
     * definitions. This is a benefit, since different systems, might have
     * different preferences for what the version token should look like.
     *
     * @return void
     */
    public function filter( )
    {
        if ( preg_match( $this->getPrefixPattern(), $this->request->uri, $tokenMatches ) )
        {
            $this->versionToken = isset( $tokenMatches['version'] ) ? $tokenMatches['version'] : '';
            $this->apiProviderToken = isset( $tokenMatches['provider'] ) ? $tokenMatches['provider'] : '';

            self::$version = $this->parseVersionValue();
            self::$apiProvider = empty( $this->apiProviderToken ) ? null : $this->apiProviderToken;
        }
    }


}
