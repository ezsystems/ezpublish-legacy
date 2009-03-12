<?php
/**
 * File containing the ezpLanguageSwitcher class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
* Utility class for transforming URLs between siteaccesses.
* 
* This class will generate URLs for various siteaccess, and translate
* URL-aliases into other languages as necessary.
*/
class ezpLanguageSwitcher
{
    protected $origUrl;
    protected $userParamString;

    protected $destinationSiteAccess;
    protected $destinationLocale;

    protected $baseDestinationUrl;

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
    }

    /**
     * Get instance siteaccess specific site.ini
     *
     * @param string $sa
     * @return void
     */
    protected function getSiteAccessIni()
    {
        $saPath = eZSiteAccess::findPathToSiteAccess(  $this->destinationSiteAccess );
        return eZINI::fetchFromFile( $saPath . '/site.ini' );
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
     * Returns URL alias for the specified <var>$locale</var>
     *
     * @param string $url 
     * @param string $locale 
     * @return void
     */
    public function destinationUrl()
    {
        $nodeId = $this->origUrl;
        if ( !is_numeric( $this->origUrl ) )
        {
            $nodeId = eZURLAliasML::fetchNodeIDByPath( $this->origUrl );
        }
        $destinationElement = eZURLAliasML::fetchByAction( 'eznode', $nodeId, $this->destinationLocale, false );

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

            if ( $this->isUrlPointingToModule( $this->origUrl ) ||
                 $this->isLocaleAvailableAsFallback() )
            {
                // We have a module, we're keeping the orignal url.
                $urlAlias = $this->origUrl;
            }
            else
            {
                // We probably have an untranslated object, which is not
                // available with SiteLanguageList setting, we direct to root.
                $urlAlias = '';
            }
        }
        else
        {
            // Translated object found, forwarding to new URL.
            $urlAlias = $destinationElement[0]->getPath( $this->destinationLocale );
            $urlAlias .= $this->userParamString;
        }

        if ( $GLOBALS['eZCurrentAccess']['type'] === EZ_ACCESS_TYPE_URI )
        {
            $finalUrl = $this->baseDestinationUrl . '/' . $this->destinationSiteAccess . '/' . $urlAlias;
        }
        else
        {
            $finalUrl = $this->baseDestinationUrl . '/' . $urlAlias;
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
        $indexFile = eZSys::indexFile( false );
        switch ( $GLOBALS['eZCurrentAccess']['type'] )
        {
            case EZ_ACCESS_TYPE_URI:
                $host = eZSys::hostname();
                break;

            case EZ_ACCESS_TYPE_HTTP_HOST:
                $host = $saIni->variable( 'SiteSettings', 'SiteURL' );
                break;
        }
        $this->baseDestinationUrl = "http://{$host}{$indexFile}";
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
            $ret[] = array( 'url' => $switchLanguageLink,
                            'text' => $translationName
                          );
        }
        return $ret;
    }
}

?>