<?php
/**
 * File containing the ezpLanguageSwitcher class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Utility class for transforming URLs between siteaccesses.
 *
 * This class will generate URLs for various siteaccess, and translate
 * URL-aliases into other languages as necessary.
 */
class ezpLanguageSwitcher implements ezpLanguageSwitcherCapable
{
    protected $origUrl;
    protected $userParamString;
    protected $queryString;

    protected $destinationSiteAccess;
    protected $destinationLocale;

    protected $baseDestinationUrl;

    protected $destinationSiteAccessIni;

    function __construct( $params = null )
    {
        if ( $params === null )
        {
            return $this;
        }

        // Removing the first part, which is the SA param.
        array_shift( $params['Parameters'] );
        $this->origUrl = join( $params['Parameters'] , '/' );

        $this->userParamString = '';
        $userParams = $params['UserParameters'];
        foreach ( $userParams as $key => $value )
        {
            $this->userParamString .= "/($key)/$value";
        }

        $this->queryString = isset( $params['QueryString'] ) ? $params['QueryString'] : '';
    }

    /**
     * Get instance siteaccess specific site.ini
     *
     * @return void
     */
    protected function getSiteAccessIni()
    {
        if ( $this->destinationSiteAccessIni === null )
        {
            $this->destinationSiteAccessIni = eZSiteAccess::getIni( $this->destinationSiteAccess, 'site.ini' );
        }
        return $this->destinationSiteAccessIni;
    }

    /**
     * Checks if the given $url points to a module.
     *
     * We use this method to check whether we should pass on the original URL
     * to the destination translation siteaccess.
     *
     * @param string $url
     * @return bool
     */
    protected function isUrlPointingToModule( $url )
    {
        // Grab the first URL element, representing the possible module name
        $urlElements = explode( '/', $url );
        $moduleName = $urlElements[0];

        // Look up for a match in the module list
        $moduleIni = eZINI::instance( 'module.ini' );
        $availableModules = $moduleIni->variable( 'ModuleSettings', 'ModuleList' );
        return in_array( $moduleName, $availableModules, true );
    }

    /**
     * Checks if the current content object locale is available in destination siteaccess.
     *
     * This is used to check whether we should pass on the original URL to the
     * destination translation siteaccess, when no translation of an object
     * exists in the destination locale.
     *
     * If the current content object locale exists as a fallback in the
     * destination siteaccess, the original URL should be available there as
     * well.
     *
     * @return bool
     */
    protected function isLocaleAvailableAsFallback()
    {
        $currentContentObjectLocale = eZINI::instance()->variable( 'RegionalSettings', 'ContentObjectLocale' );
        $saIni = $this->getSiteAccessIni();
        $siteLanguageList = $saIni->variable( 'RegionalSettings', 'SiteLanguageList' );
        return in_array( $currentContentObjectLocale, $siteLanguageList, true );
    }


    /**
     * Prepend PathPrefix from the current SA to url, if applicable
     *
     * @param  string $url
     *
     * @return string The url with pathprefix prepended
     */
    protected static function addPathPrefixIfNeeded( $url )
    {
        $siteINI = eZINI::instance( 'site.ini' );
        if ( $siteINI->hasVariable( 'SiteAccessSettings', 'PathPrefix' ) )
        {
            $pathPrefix = $siteINI->variable( 'SiteAccessSettings', 'PathPrefix' );
            if ( !empty( $pathPrefix ) )
            {
                $url = $pathPrefix . '/' . $url;
            }
        }
        return $url;
    }

    /**
     * Remove PathPrefix from url, if applicable (present in siteaccess and matched in url)
     *
     * @param  eZINI  $saIni eZINI instance of site.ini for the siteaccess to check
     * @param  string $url
     *
     * @return bool   true if no PathPrefix exists, or removed from url. false if not removed.
     */
    protected static function removePathPrefixIfNeeded( eZINI $saIni, &$url )
    {
        if ( $saIni->hasVariable( 'SiteAccessSettings', 'PathPrefix' ) )
        {
            $pathPrefix = $saIni->variable( 'SiteAccessSettings', 'PathPrefix' );
            if ( !empty( $pathPrefix ) )
            {
                if ( ( strpos( $url, $pathPrefix . '/' ) === 0 ) || ( $pathPrefix === $url ) )
                {
                    $url = substr( $url, strlen( $pathPrefix ) + 1 );
                }
                else
                {
                    // PathPrefix exists, but not matched in url.
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Returns URL alias for the specified <var>$locale</var>
     *
     * @return void
     */
    public function destinationUrl()
    {
        $nodeId = $this->origUrl;
        $urlAlias = '';

        if ( !is_numeric( $this->origUrl ) )
        {
            if ( !$this->isUrlPointingToModule( $this->origUrl ) )
            {
                $this->origUrl = self::addPathPrefixIfNeeded( $this->origUrl );
            }

            $nodeId = eZURLAliasML::fetchNodeIDByPath( $this->origUrl );
        }

        $siteLanguageList = $this->getSiteAccessIni()->variable( 'RegionalSettings', 'SiteLanguageList' );
        // set prioritized languages of destination SA, and fetch corresponding (prioritized) URL alias
        eZContentLanguage::setPrioritizedLanguages( $siteLanguageList );
        $destinationElement = eZURLAliasML::fetchByAction( 'eznode', $nodeId, false, true );

        eZContentLanguage::clearPrioritizedLanguages();

        if ( empty( $destinationElement ) || ( !isset( $destinationElement[0] ) && !( $destinationElement[0] instanceof eZURLAliasML ) ) )
        {
            // If the return of fetchByAction is empty, it can mean a couple
            // of different things:
            // Either we are looking at a module, and we should pass the
            // original URL on
            //
            // Or we are looking at URL which does not exist in the
            // destination siteaccess, for instance an untranslated object. In
            // which case we will point to the root of the site, unless it is
            // available as a fallback.
            if ( $nodeId )
            {
                $urlAlias = $this->origUrl;

                // if applicable, remove destination PathPrefix from url
                if ( !self::removePathPrefixIfNeeded( $this->getSiteAccessIni(), $urlAlias ) )
                {
                    // If destination siteaccess has a PathPrefix but url is not matched,
                    // also check current SA's prefix, and remove if it matches.
                    self::removePathPrefixIfNeeded( eZINI::instance( 'site.ini' ), $urlAlias );
                }
            }
            else
            {
                if ( $this->isUrlPointingToModule( $this->origUrl ) )
                {
                    $urlAlias = $this->origUrl;
                }
            }
        }
        else
        {
            // Translated object found, forwarding to new URL.
            $urlAlias = $destinationElement[0]->getPath( $this->destinationLocale, $siteLanguageList );

            // if applicable, remove destination PathPrefix from url
            self::removePathPrefixIfNeeded( $this->getSiteAccessIni(), $urlAlias );

            $urlAlias .= $this->userParamString;
        }

        $this->baseDestinationUrl = rtrim( $this->baseDestinationUrl, '/' );

        $ini = eZINI::instance();

        if ( $GLOBALS['eZCurrentAccess']['type'] === eZSiteAccess::TYPE_URI &&
             !( $ini->variable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess' ) === "enabled" &&
                $ini->variable( 'SiteSettings', 'DefaultAccess' ) == $this->destinationSiteAccess ) )
        {
            $finalUrl = $this->baseDestinationUrl . '/' . $this->destinationSiteAccess . '/' . $urlAlias;
        }
        else
        {
            $finalUrl = $this->baseDestinationUrl . '/' . $urlAlias;
        }
        if ( $this->queryString != '' )
        {
            $finalUrl .= '?' . $this->queryString;
        }
        return $finalUrl;
    }

    /**
     * Sets the siteaccess name, $saName, we want to redirect to.
     *
     * @param string $saName
     * @return void
     */
    public function setDestinationSiteAccess( $saName )
    {
        $this->destinationSiteAccess = $saName;
    }

    /**
     * This is a hook which is called by the language switcher module on
     * implementation classes.
     *
     * In this implementation it is doing initialisation as an example.
     *
     * @return void
     */
    public function process()
    {
        $saIni = $this->getSiteAccessIni();
        $this->destinationLocale = $saIni->variable( 'RegionalSettings', 'ContentObjectLocale' );

        // Detect the type of siteaccess we are dealing with. Initially URI and Host are supported.
        // We don't want the siteaccess part here, since we are inserting our siteaccess name.
        $indexFile = trim( eZSys::indexFile( false ), '/' );
        switch ( $GLOBALS['eZCurrentAccess']['type'] )
        {
            case eZSiteAccess::TYPE_URI:
                eZURI::transformURI( $host, true, 'full' );
                break;

            default:
                $host = $saIni->variable( 'SiteSettings', 'SiteURL' );
                $host = eZSys::serverProtocol()."://".$host;
                break;
        }
        $this->baseDestinationUrl = "{$host}{$indexFile}";
    }

    /**
     * Creates an array of corresponding language switcher links and logical names.
     *
     * This mapping is set up in site.ini.[RegionalSettings].TranslationSA.
     * The purpose of this method is to assist creation of language switcher
     * links into the available translation siteaccesses on the system.
     *
     * This is used by the language_switcher template operator.
     *
     * @param string $url
     * @return void
     */
    public static function setupTranslationSAList( $url = null )
    {
        $ini = eZINI::instance();
        if ( !$ini->hasVariable( 'RegionalSettings', 'TranslationSA' ) )
        {
            return array();
        }

        $ret = array();
        $translationSiteAccesses = $ini->variable( 'RegionalSettings', 'TranslationSA' );
        foreach ( $translationSiteAccesses as $siteAccessName => $translationName )
        {
            $switchLanguageLink = "/switchlanguage/to/{$siteAccessName}/";
            if ( $url !== null && ( is_string( $url ) || is_numeric( $url ) ) )
            {
                $switchLanguageLink .= $url;
            }
            $ret[$siteAccessName] = array(
                'url' => $switchLanguageLink,
                'text' => $translationName,
                'locale' => eZSiteAccess::getIni( $siteAccessName )->variable( 'RegionalSettings', 'ContentObjectLocale' )
             );
        }
        return $ret;
    }
}

?>
