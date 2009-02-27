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

        if ( !isset( $destinationElement[0] ) && !( $destinationElement[0] instanceof eZURLAliasML ) )
        {
            return false;
        }

        $urlAlias = $destinationElement[0]->getPath( $this->destinationLocale );
        $urlAlias .= $this->userParamString;

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

    public function setDestinationSiteAccess( $saName )
    {
        $this->destinationSiteAccess = $saName;
    }

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
}

?>