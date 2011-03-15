<?php
/**
 * File containing (site)access functions
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package index
 * @deprecated since 4.4, this file and its compatibility functions/constants will be removed
 */


/**
 * Integer constants that identify the siteaccess matching used
 *
 * @see eZSiteAccess class constants
 * @deprecated Since 4.4
 */
define( 'EZ_ACCESS_TYPE_DEFAULT', 1 );
define( 'EZ_ACCESS_TYPE_URI', 2 );
define( 'EZ_ACCESS_TYPE_PORT', 3 );
define( 'EZ_ACCESS_TYPE_HTTP_HOST', 4 );
define( 'EZ_ACCESS_TYPE_INDEX_FILE', 5 );
define( 'EZ_ACCESS_TYPE_STATIC', 6 );
define( 'EZ_ACCESS_TYPE_SERVER_VAR', 7 );
define( 'EZ_ACCESS_TYPE_URL', 8 );

define( 'EZ_ACCESS_SUBTYPE_PRE', 1 );
define( 'EZ_ACCESS_SUBTYPE_POST', 2 );

/**
 * Goes trough the access matching rules and returns the access match.
 * The returned match is an associative array with:
 *  name     => string Name of the siteaccess (same as folder name)
 *  type     => int The constant that represent the matching used
 *  uri_part => array(string) List of path elements that was used in start of url for the match
 *
 * @see eZSiteAccess::match()
 * @deprecated Since 4.4
 * @param eZURI $uri
 * @param string $host
 * @param string(numeric) $port
 * @param string $file Example '/index.php'
 * @return array
 */
function accessType( eZURI $uri, $host, $port, $file )
{
    return eZSiteAccess::match( $uri, $host, $port, $file );
}

/**
 * Changes the site access to what's defined in $access. It will change the
 * access path in eZSys and prepend an override dir to eZINI
 *
 * @see eZSiteAccess::change()
 * @deprecated Since 4.4
 * @param array $access An associative array with 'name' (string), 'type' (int) and 'uri_part' (array).
 * @return array The $access parameter
 */
function changeAccess( array $access )
{
    return eZSiteAccess::change( $access );
}

/**
 * Match a regex expression
 *
 * @see eZSiteAccess::matchRegexp()
 * @deprecated Since 4.4
 * @param string $text
 * @param string $reg
 * @param int $num
 * @return string|null
 */
function accessMatchRegexp( &$text, $reg, $num )
{
    return eZSiteAccess::matchRegexp( $text, $reg, $num );
}

/**
 * Match a text string with pre or/or post text strings
 *
 * @see eZSiteAccess::matchText()
 * @deprecated Since 4.4
 * @param string $text
 * @param string $match_pre
 * @param string $match_post
 * @return string|null
 */
function accessMatchText( &$text, $match_pre, $match_post )
{
    return eZSiteAccess::matchText( $text, $match_pre, $match_post );
}

/**
 * Checks if site access debug is enabled
 *
 * @see eZSiteAccess::debugEnabled()
 * @deprecated Since 4.4
 * @return bool
 */
function accessDebugEnabled()
{
    return eZSiteAccess::debugEnabled();
}

/**
 * Checks if extra site access debug is enabled
 *
 * @see eZSiteAccess::extraDebugEnabled()
 * @deprecated Since 4.4
 * @return bool
 */
function accessExtraDebugEnabled()
{
    return eZSiteAccess::extraDebugEnabled();
}

/**
 * Checks if access is allowed to a module/view based on site.ini[SiteAccessRules]Rules settings
 *
 * @see eZModule::accessAllowed()
 * @deprecated Since 4.4
 * @param eZURI $uri
 * @return array An associative array with:
 *   'result'       => bool   Indicates if access is allowed
 *   'module'       => string Module name
 *   'view'         => string View name
 *   'view_checked' => bool   Indicates if view access has been checked
 */
function accessAllowed( eZURI $uri )
{
    return eZModule::accessAllowed( $uri );
}

?>
