<?php
/**
 * File containing (site)access functionality
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Provides functions for siteaccess handling
 *
 * @package kernel
 */
class eZSiteAccess
{
    /**
     * Integer constants that identify the siteaccess matching used
     *
     * @since 4.4 Was earlier in access.php as normal constants
     */
    const TYPE_DEFAULT = 1;
    const TYPE_URI = 2;
    const TYPE_PORT = 3;
    const TYPE_HTTP_HOST = 4;
    const TYPE_INDEX_FILE = 5;
    const TYPE_STATIC = 6;
    const TYPE_SERVER_VAR = 7;
    const TYPE_URL = 8;
    const TYPE_HTTP_HOST_URI = 9;

    const SUBTYPE_PRE = 1;
    const SUBTYPE_POST = 2;

    /*!
     Constructor
    */
    function eZSiteAccess()
    {
    }

    static function siteAccessList()
    {
        $siteAccessList = array();
        $ini = eZINI::instance();
        $availableSiteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
        if ( !is_array( $availableSiteAccessList ) )
            $availableSiteAccessList = array();

        $serverSiteAccess = eZSys::serverVariable( $ini->variable( 'SiteAccessSettings', 'ServerVariableName' ), true );
        if ( $serverSiteAccess )
            $availableSiteAccessList[] = $serverSiteAccess;

        $availableSiteAccessList = array_unique( $availableSiteAccessList );
        foreach ( $availableSiteAccessList as $siteAccessName )
        {
            $siteAccessItem = array();
            $siteAccessItem['name'] = $siteAccessName;
            $siteAccessItem['id'] = eZSys::ezcrc32( $siteAccessName );
            $siteAccessList[] = $siteAccessItem;
        }
        return $siteAccessList;
    }

    /**
     * Returns path to site access
     *
     * @param string $siteAccess
     * @return string|false Return path to siteacces or false if invalid
     */
    static function findPathToSiteAccess( $siteAccess )
    {
        $ini = eZINI::instance();
        $siteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
        if ( !in_array( $siteAccess, $siteAccessList )  )
            return false;

        $currentPath = 'settings/siteaccess/' . $siteAccess;
        if ( file_exists( $currentPath ) )
            return $currentPath;

        $activeExtensions = eZExtension::activeExtensions();
        $baseDir = eZExtension::baseDirectory();
        foreach ( $activeExtensions as $extension )
        {
            $currentPath = $baseDir . '/' . $extension . '/settings/siteaccess/' . $siteAccess;
            if ( file_exists( $currentPath ) )
                return $currentPath;
        }

        return 'settings/siteaccess/' . $siteAccess;
    }

    /**
     * Goes trough the access matching rules and returns the access match.
     * The returned match is an associative array with:
     *  name     => string Name of the siteaccess (same as folder name)
     *  type     => int The constant that represent the matching used
     *  uri_part => array(string) List of path elements that was used in start of url for the match
     *
     * @since 4.4
     * @param eZURI $uri
     * @param string $host
     * @param string(numeric) $port
     * @param string $file Example '/index.php'
     * @return array
     */
    public static function match( eZURI $uri, $host, $port = 80, $file = '/index.php' )
    {
        eZDebugSetting::writeDebug( 'kernel-siteaccess', array( 'uri' => $uri,
                                                                'host' => $host,
                                                                'port' => $port,
                                                                'file' => $file ), __METHOD__ );
        $ini = eZINI::instance();
        if ( $ini->hasVariable( 'SiteAccessSettings', 'StaticMatch' ) )
        {
            $match = $ini->variable( 'SiteAccessSettings', 'StaticMatch' );
            if ( $match != '' )
            {
                $access = array( 'name' => $match,
                                 'type' => eZSiteAccess::TYPE_STATIC,
                                 'uri_part' => array() );
                return $access;
            }
        }

        list( $siteAccessList, $order ) =
            $ini->variableMulti( 'SiteAccessSettings', array( 'AvailableSiteAccessList', 'MatchOrder' ) );
        $access = array( 'name' => $ini->variable( 'SiteSettings', 'DefaultAccess' ),
                         'type' => eZSiteAccess::TYPE_DEFAULT,
                         'uri_part' => array() );

        if ( $order == 'none' )
            return $access;

        $order = $ini->variableArray( 'SiteAccessSettings', 'MatchOrder' );

        // Change the default type to eZSiteAccess::TYPE_URI if we're using URI MatchOrder.
        // This is to keep backward compatiblity with the ezurl operator. ezurl has since
        // rev 4949 added default siteaccess to generated URLs, even when there is
        // no siteaccess in the current URL.
        if ( in_array( 'uri', $order ) )
        {
            $access['type'] = eZSiteAccess::TYPE_URI;
        }

        foreach ( $order as $matchprobe )
        {
            $name = '';
            $type = '';
            $match_type = '';
            $uri_part = array();

            switch( $matchprobe )
            {
                case 'servervar':
                {
                    if ( $serversiteaccess = eZSys::serverVariable( $ini->variable( 'SiteAccessSettings', 'ServerVariableName' ), true ) )
                    {
                        $access['name'] = $serversiteaccess;
                        $access['type'] = eZSiteAccess::TYPE_SERVER_VAR;
                        return $access;
                    }
                    else
                        continue;
                } break;
                case 'port':
                {
                    if ( $ini->hasVariable( 'PortAccessSettings', $port ) )
                    {
                        $access['name'] = $ini->variable( 'PortAccessSettings', $port );
                        $access['type'] = eZSiteAccess::TYPE_PORT;
                        return $access;
                    }
                    else
                        continue;
                } break;
                case 'uri':
                {
                    $type = eZSiteAccess::TYPE_URI;
                    $match_type = $ini->variable( 'SiteAccessSettings', 'URIMatchType' );

                    if ( $match_type == 'map' )
                    {
                        if ( $ini->hasVariable( 'SiteAccessSettings', 'URIMatchMapItems' ) )
                        {
                            $match_item = $uri->element( 0 );
                            $matchMapItems = $ini->variableArray( 'SiteAccessSettings', 'URIMatchMapItems' );
                            foreach ( $matchMapItems as $matchMapItem )
                            {
                                $matchMapURI = $matchMapItem[0];
                                $matchMapAccess = $matchMapItem[1];
                                if ( $access['name']  == $matchMapAccess and in_array( $matchMapAccess, $siteAccessList ) )
                                {
                                    $uri_part = array( $matchMapURI );
                                }
                                if ( $matchMapURI == $match_item and in_array( $matchMapAccess, $siteAccessList ) )
                                {
                                    $uri->increase( 1 );
                                    $uri->dropBase();
                                    $access['name'] = $matchMapAccess;
                                    $access['type'] = $type;
                                    $access['uri_part'] = array( $matchMapURI );
                                    return $access;
                                }
                            }
                        }
                    }
                    else if ( $match_type == 'element' )
                    {
                        $match_index = $ini->variable( 'SiteAccessSettings', 'URIMatchElement' );
                        $elements = $uri->elements( false );
                        $elements = array_slice( $elements, 0, $match_index );
                        $name = implode( '_', $elements );
                        $uri_part = $elements;
                    }
                    else if ( $match_type == 'text' )
                    {
                        $match_item = $uri->elements();
                        $matcher_pre = $ini->variable( 'SiteAccessSettings', 'URIMatchSubtextPre' );
                        $matcher_post = $ini->variable( 'SiteAccessSettings', 'URIMatchSubtextPost' );
                    }
                    else if ( $match_type == 'regexp' )
                    {
                        $match_item = $uri->elements();
                        $matcher = $ini->variable( 'SiteAccessSettings', 'URIMatchRegexp' );
                        $match_num = $ini->variable( 'SiteAccessSettings', 'URIMatchRegexpItem' );
                    }
                    else
                        continue;
                } break;
                case 'host':
                {
                    $type = eZSiteAccess::TYPE_HTTP_HOST;
                    $match_type = $ini->variable( 'SiteAccessSettings', 'HostMatchType' );
                    $match_item = $host;
                    if ( $match_type == 'map' )
                    {
                        if ( $ini->hasVariable( 'SiteAccessSettings', 'HostMatchMapItems' ) )
                        {
                            $matchMapItems = $ini->variableArray( 'SiteAccessSettings', 'HostMatchMapItems' );
                            foreach ( $matchMapItems as $matchMapItem )
                            {
                                $matchMapHost = $matchMapItem[0];
                                $matchMapAccess = $matchMapItem[1];
                                if ( $matchMapHost == $host )
                                {
                                    $access['name'] = $matchMapAccess;
                                    $access['type'] = $type;
                                    return $access;
                                }
                            }
                        }
                    }
                    else if ( $match_type == 'element' )
                    {
                        $match_index = $ini->variable( 'SiteAccessSettings', 'HostMatchElement' );
                        $match_arr = explode( '.', $match_item );
                        $name = $match_arr[$match_index];
                    }
                    else if ( $match_type == 'text' )
                    {
                        $matcher_pre = $ini->variable( 'SiteAccessSettings', 'HostMatchSubtextPre' );
                        $matcher_post = $ini->variable( 'SiteAccessSettings', 'HostMatchSubtextPost' );
                    }
                    else if ( $match_type == 'regexp' )
                    {
                        $matcher = $ini->variable( 'SiteAccessSettings', 'HostMatchRegexp' );
                        $match_num = $ini->variable( 'SiteAccessSettings', 'HostMatchRegexpItem' );
                    }
                    else
                        continue;
                } break;
                case 'host_uri':
                {
                    $type = eZSiteAccess::TYPE_HTTP_HOST_URI;
                    if ( $ini->hasVariable( 'SiteAccessSettings', 'HostUriMatchMapItems' ) )
                    {
                        $match_item = $uri->element( 0 );
                        $matchMapItems = $ini->variableArray( 'SiteAccessSettings', 'HostUriMatchMapItems' );
                        $defaultHostMatchMethod = $ini->variable( 'SiteAccessSettings', 'HostUriMatchMethodDefault' );

                        foreach ( $matchMapItems as $matchMapItem )
                        {
                            $matchHost       = $matchMapItem[0];
                            $matchURI        = $matchMapItem[1];
                            $matchAccess     = $matchMapItem[2];
                            $matchHostMethod = isset( $matchMapItem[3] ) ? $matchMapItem[3] : $defaultHostMatchMethod;

                            if ( $matchURI !== '' && $matchURI !== $match_item )
                                continue;

                            switch( $matchHostMethod )
                            {
                                case 'strict':
                                {
                                    $hasHostMatch = ( $matchHost === $host );
                                } break;
                                case 'start':
                                {
                                    $hasHostMatch = ( strpos($host, $matchHost) === 0 );
                                } break;
                                case 'end':
                                {
                                    $hasHostMatch = ( strstr($host, $matchHost) === $matchHost );
                                } break;
                                case 'part':
                                {
                                    $hasHostMatch = ( strpos($host, $matchHost) !== false );
                                } break;
                                default:
                                {
                                    $hasHostMatch = false;
                                    eZDebug::writeError( "Unknown host_uri host match: $matchHostMethod", "access" );
                                } break;
                            }

                            if ( $hasHostMatch )
                            {
                                if ( $matchURI !== '' )
                                {
                                    $uri->increase( 1 );
                                    $uri->dropBase();
                                    $access['uri_part'] = array( $matchURI );
                                }
                                $access['name'] = $matchAccess;
                                $access['type'] = $type;
                                return $access;
                            }
                        }
                    }
                } break;
                case 'index':
                {
                    $type = eZSiteAccess::TYPE_INDEX_FILE;
                    $match_type = $ini->variable( 'SiteAccessSettings', 'IndexMatchType' );
                    $match_item = $file;
                    if ( $match_type == 'element' )
                    {
                        $match_index = $ini->variable( 'SiteAccessSettings', 'IndexMatchElement' );
                        $match_pos = strpos( $match_item, '.php' );
                        if ( $match_pos !== false )
                        {
                            $match_item = substr( $match_item, 0, $match_pos );
                            $match_arr = explode( '_', $match_item );
                            $name = $match_arr[$match_index];
                        }
                    }
                    else if ( $match_type == 'text' )
                    {
                        $matcher_pre = $ini->variable( 'SiteAccessSettings', 'IndexMatchSubtextPre' );
                        $matcher_post = $ini->variable( 'SiteAccessSettings', 'IndexMatchSubtextPost' );
                    }
                    else if ( $match_type == 'regexp' )
                    {
                        $matcher = $ini->variable( 'SiteAccessSettings', 'IndexMatchRegexp' );
                        $match_num = $ini->variable( 'SiteAccessSettings', 'IndexMatchRegexpItem' );
                    }
                    else
                        continue;
                } break;
                default:
                {
                    eZDebug::writeError( "Unknown access match: $matchprobe", "access" );
                } break;
            }

            if ( $match_type == 'regexp' )
                $name = self::matchRegexp( $match_item, $matcher, $match_num );
            else if ( $match_type == 'text' )
                $name = self::matchText( $match_item, $matcher_pre, $matcher_post );

            if ( isset( $name ) && $name != '' )
            {
                $name = preg_replace( array( '/[^a-zA-Z0-9]+/', '/_+/', '/^_/', '/_$/' ),
                                      array( '_', '_', '', '' ),
                                      $name );

                if ( in_array( $name, $siteAccessList ) )
                {
                    if ( $type == eZSiteAccess::TYPE_URI )
                    {
                        if ( $match_type == 'element' )
                        {
                            $uri->increase( $match_index );
                            $uri->dropBase();
                        }
                        else if ( $match_type == 'regexp' )
                        {
                            $uri->setURIString( $match_item );
                        }
                        else if ( $match_type == 'text' )
                        {
                            $uri->setURIString( $match_item );
                        }
                    }
                    $access['type']     = $type;
                    $access['name']     = $name;
                    $access['uri_part'] = $uri_part;
                    return $access;
                }
            }
        }
        return $access;
    }

    /**
     * Match a regex expression
     *
     * @since 4.4
     * @param string $text
     * @param string $reg
     * @param int $num
     * @return string|null
     */
    static function matchRegexp( &$text, $reg, $num )
    {
        $reg = str_replace( '/', "\\/", $reg );
        if ( preg_match( "/$reg/", $text, $regs ) && $num < count( $regs ) )
        {
            $text = str_replace( $regs[$num], '', $text );
            return $regs[$num];
        }
        return null;
    }

    /**
     * Match a text string with pre or/or post text strings
     *
     * @since 4.4
     * @param string $text
     * @param string $match_pre
     * @param string $match_post
     * @return string|null
     */
    static function matchText( &$text, $match_pre, $match_post )
    {
        $ret = null;
        if ( $match_pre !== '' )
        {
            $pos = strpos( $text, $match_pre );
            if ( $pos === false )
                return null;

            $ret = substr( $text, $pos + strlen( $match_pre ) );
            $text = substr( $text, 0, $pos );
        }
        if ( $match_post !== '' )
        {
            $pos = strpos( $ret, $match_post );
            if ( $pos === false )
                return null;

            $text .= substr( $ret, $pos + 1 );
            $ret = substr( $ret, 0, $pos );
        }
        return $ret;
    }

    /**
     * Re-initialises the current site access
     * If a siteaccess is set, then executes {@link eZSiteAccess::load()}
     *
     * @return bool True if re-initialisation was successful
     */
    static function reInitialise()
    {
        if ( isset( $GLOBALS['eZCurrentAccess'] ) )
        {
            self::load( $GLOBALS['eZCurrentAccess'] );
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Changes the site access to what's defined in $access. It will change the
     * access path in eZSys and prepend an override dir to eZINI
     * Note: does not load extensions, use {@link eZSiteAccess::load()} if you want that
     *
     * @since 4.4
     * @param array $access An associative array with 'name' (string), 'type' (int) and 'uri_part' (array).
     *                      See {@link eZSiteAccess::match()} for array structure definition
     * @param eZINI|null $siteINI Optional parameter to be able to only do change on specific instance of site.ini
     *                   hence skip changing eZSys access paths (but not siteaccess, see {@link eZSiteAccess::load()})
     * @return array The $access parameter
     */
    static function change( array $access, eZINI $siteINI = null )
    {
        $name = $access['name'];
        $GLOBALS['eZCurrentAccess'] =& $access;
        if ( $siteINI !== null )
        {
            $ini = $siteINI;
        }
        else
        {
            $ini = eZINI::instance();
        }

        $ini->prependOverrideDir( "siteaccess/$name", false, 'siteaccess', 'siteaccess' );

        /* Make sure extension siteaccesses are prepended */
        eZExtension::prependExtensionSiteAccesses( $name, $ini );

        $ini->loadCache();

        // change some global settings if $siteINI is null
        if ( $siteINI === null )
        {
            eZSys::clearAccessPath();
            if ( !isset( $access['uri_part'] ) || $access['uri_part'] === null )
            {
                if ( $ini->hasVariable('SiteSettings', 'SiteUriParts') )
                    $access['uri_part'] = $ini->variable('SiteSettings', 'SiteUriParts');
                else if ( isset( $access['type'] ) && $access['type'] === eZSiteAccess::TYPE_URI )
                    $access['uri_part'] = array( $access['name'] );
                else
                    $access['uri_part'] = array();
            }
            eZSys::setAccessPath( $access['uri_part'], $name );

            eZUpdateDebugSettings();
            eZDebugSetting::writeDebug( 'kernel-siteaccess', "Updated settings to use siteaccess '$name'", __METHOD__ );
        }

        return $access;
    }

    /**
     * Reloads extensions and changes siteaccess globally
     * If you only want changes on a instance of ini, use {@link eZSiteAccess::getIni()}
     *
     * - clears all in-memory caches used by the INI system
     * - re-builds the list of paths where INI files are searched for
     * - runs {@link eZSiteAccess::change()}
     * - re-searches module paths {@link eZModule::setGlobalPathList()}
     *
     * @since 4.4
     * @param array $access An associative array with 'name' (string), 'type' (int) and 'uri_part' (array).
     *                      See {@link eZSiteAccess::match()} for array structure definition
     * @param eZINI|null $siteINI Optional parameter to be able to only do change on specific instance of site.ini
     *                            If set, then global siteacceess will not be changed as well.
     * @return array The $access parameter
     */
    static function load( array $access, eZINI $siteINI = null )
    {
        $currentSiteAccess = $GLOBALS['eZCurrentAccess'];
        unset( $GLOBALS['eZCurrentAccess'] );

        // Clear all ini override dirs
        if ( $siteINI instanceof eZINI )
        {
            $siteINI->resetOverrideDirs();
        }
        else
        {
            eZINI::resetAllInstances();
            eZExtension::clearActiveExtensionsMemoryCache();
            eZTemplateDesignResource::clearInMemoryCache();
        }

        // Reload extensions, siteaccess and access extensions
        eZExtension::activateExtensions( 'default', $siteINI );
        $access = self::change( $access, $siteINI );
        eZExtension::activateExtensions( 'access', $siteINI );

        // Restore current (old) siteacces if changes where only to be applied to locale instance of site.ini
        if ( $siteINI instanceof eZINI )
        {
            $GLOBALS['eZCurrentAccess'] = $currentSiteAccess;
        }
        else
        {
            $moduleRepositories = eZModule::activeModuleRepositories();
            eZModule::setGlobalPathList( $moduleRepositories );
        }

        return $access;
    }

    /**
     * Loads ini environment for a specific siteaccess
     *
     * eg: $ini = eZSiteAccess::getIni( 'eng', 'site.ini' );
     *
     * @since 4.4
     * @param string $siteAccess
     * @param string $settingFile
     * @return eZINI
     */
    static function getIni( $siteAccess, $settingFile = 'site.ini' )
    {
        // return global if siteaccess is same as requested or false
        if ( isset( $GLOBALS['eZCurrentAccess']['name'] )
          && $GLOBALS['eZCurrentAccess']['name'] === $siteAccess )
        {
            return eZINI::instance( $settingFile );
        }
        else if ( !$siteAccess )
        {
            return eZINI::instance( $settingFile );
        }

        // create a site ini instance using $useLocalOverrides = true
        $siteIni = new eZINI( 'site.ini', 'settings', null, null, true );

        // create a dummy access definition (not used as long as $siteIni is sent to self::load() )
        $access = array( 'name' => $siteAccess,
                         'type' => eZSiteAccess::TYPE_STATIC,
                         'uri_part' => array() );

        // Load siteaccess but on our locale instance of site.ini only
        $access = self::load( $access, $siteIni );

        // if site.ini, return with no further work needed
        if ( $settingFile === 'site.ini' )
        {
            return $siteIni;
        }

        // load settings file with $useLocalOverrides = true
        $ini = new eZINI( $settingFile,'settings', null, null, true );
        $ini->setOverrideDirs( $siteIni->overrideDirs( false ) );
        $ini->load();

        return $ini;
    }

    /**
     * Get current siteaccess data if set, see {@link eZSiteAccess::match()} for array structure
     *
     * @since 4.4
     * return array|null
     */
    static function current()
    {
        if ( isset( $GLOBALS['eZCurrentAccess']['name'] ) )
            return $GLOBALS['eZCurrentAccess'];
        return null;
    }

    /**
     * Gets siteaccess name by language based on site.ini\[RegionalSettings]\LanguageSA[]
     * if defined otherwise by convention ( eng-GB -> eng ), in both cases sa needs to
     * be in site.ini\[SiteAccessSettings]\RelatedSiteAccessList[] as well to be valid.
     *
     * @since 4.5
     * @param string $language eg: eng-GB
     * @return string|null
     */
    public static function saNameByLanguage( $language )
    {
        $ini = eZINI::instance();
        if ( $ini->hasVariable( 'RegionalSettings', 'LanguageSA' ) )
        {
            $langMap = $ini->variable( 'RegionalSettings', 'LanguageSA' );
            if ( !isset( $langMap[$language] ) )
            {
                return null;
            }
            $sa = $langMap[$language];
        }
        else
        {
            $sa = explode( '-', $language );
            $sa = $sa[0];
        }

        if ( in_array( $sa, $ini->variable( 'SiteAccessSettings', 'RelatedSiteAccessList' ) ) )
        {
            return $sa;
        }
        eZDebug::writeWarning("Tried to find siteaccess based on '$language' but '$sa' is not a valid RelatedSiteAccessList[]", __METHOD__ );
        return null;
    }

    /**
     * Checks if site access debug is enabled
     *
     * @since 4.4
     * @deprecated Should use debug.ini conditions instead of extra settings
     * @return bool
     */
    static function debugEnabled()
    {
        $ini = eZINI::instance();
        return $ini->variable( 'SiteAccessSettings', 'DebugAccess' ) === 'enabled';
    }

    /**
     * Checks if extra site access debug is enabled
     *
     * @since 4.4
     * @deprecated Should use debug.ini conditions instead of extra settings
     * @return bool
     */
    static function extraDebugEnabled()
    {
        $ini = eZINI::instance();
        return $ini->variable( 'SiteAccessSettings', 'DebugExtraAccess' ) === 'enabled';
    }
}

?>
