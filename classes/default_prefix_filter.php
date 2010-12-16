<?php
/**
 * File containing the ezpRestDefaultPrefixFilter class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Default implementation of prefix filter interface.
 */
class ezpRestDefaultPrefixFilter extends ezpRestPrefixFilterInterface
{
    /**
     * Creates a new VersionToken object which describes the version token in API strings.
     *
     * @param ezcMvcRequest $request
     * @param string $apiPrefix
     */
    public function __construct( ezcMvcRequest $request, $apiPrefix )
    {
        $this->request = $request;
        $this->apiPrefix = $apiPrefix;
    }

    protected function getVersionTokenPattern( )
    {
        $apiPart = preg_quote( $this->apiPrefix, '@' );
        return "@^{$apiPart}(/v\d+)@";
    }

    /**
     * Returns the numerical version of the version token.
     *
     * @return int version value
     */
    protected function parseVersionValue( )
    {
        return (int)str_replace( '/v', '', $this->versionToken );
    }

    /**
     * Filters the request object for version token.
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
        if ( preg_match( $this->getVersionTokenPattern(), $this->request->uri, $tokenMatches ) )
        {
            $this->versionToken = $tokenMatches[1];
            self::$version = $this->parseVersionValue();
        }
    }


}
