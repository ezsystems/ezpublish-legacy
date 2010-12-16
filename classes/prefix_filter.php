<?php
/**
 * File containing the ezpRestPrefixFilterInterface interface
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

abstract class ezpRestPrefixFilterInterface
{
    protected $request;
    protected $apiPrefix;
    /**
     * @var string Extracted version token.
     */
    protected $versionToken;

    /**
     * @var int The numerical version number
     */
    protected static $version = null;

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
     * Returns the PCRE pattern for version tokens.
     *
     * @abstract
     * @return string
     */
    abstract protected function getVersionTokenPattern();

    /**
     * Returns the numerical version of the version token.
     *
     * @abstract
     * @return int version value
     */
    abstract protected function parseVersionValue();

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
     * Filters the URI property of the given ezcMvcRequest object, removing
     * any version token from it.
     *
     * @abstract
     * @return void
     */
    public function filterRequestUri()
    {
        if ( $this->versionToken !== null )
        {
            $this->request->uri = str_replace( $this->versionToken, '', $this->request->uri );
        }
    }
}
