<?php
/**
 * File containing the ezpMobileDeviceRegexpFilter class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Default implementation of mobile device detect interface.
 */
class ezpMobileDeviceRegexpFilter implements ezpMobileDeviceDetectFilterInterface
{
    /**
     * Container for the HTTP User Agent string
     *
     * @var #E#V_SERVER|string
     */
    protected $httpUserAgent;

    /**
     * Container for the HTTP Accept string
     *
     * @var #E#V_SERVER|string
     */
    protected $httpAccept;

    /**
     * Container for the User Agent alias
     *
     * @var string
     */
    protected $userAgentAlias = '';

    /**
     * Holds the information whether current device is mobile or not
     *
     * @var bool
     */
    protected $isMobileDevice = false;

    /**
     * Construct
     *
     */
    public function __construct()
    {
        $this->httpUserAgent = '';
        if ( isset( $_SERVER['HTTP_USER_AGENT'] ) )
        {
            $this->httpUserAgent = $_SERVER['HTTP_USER_AGENT'];
        }

        $this->httpAccept = '';
        if ( isset( $_SERVER['HTTP_ACCEPT'] ) )
        {
            $this->httpAccept = $_SERVER['HTTP_ACCEPT'];
        }
    }

    /**
     * Processes the User Agent string and determines whether it is a mobile device or not
     * Needs to set boolean value for @see ezpMobileDeviceDetectFilterInterface::isMobileDevice()
     * and optionally user agent alias @see ezpMobileDeviceDetectFilterInterface::getUserAgentAlias()
     *
     */
    public function process()
    {
        $ini = eZINI::instance();

        if ( isset( $_SERVER['HTTP_X_WAP_PROFILE'] )
                || isset( $_SERVER['HTTP_PROFILE'] )
                    || strpos( $this->httpAccept, 'text/vnd.wap.wml' ) > 0
                        || strpos( $this->httpAccept, 'application/vnd.wap.xhtml+xml' ) > 0)
        {
            $this->isMobileDevice = true;
        }
        else if ( in_array( strtolower( substr( $this->httpUserAgent, 0, 4 ) ),
                            explode( '|', $ini->variable( 'SiteAccessSettings', 'MobileUserAgentCodes' ) ) ) )
        {
            $this->isMobileDevice = true;
        }
        else
        {
            foreach ( $ini->variable( 'SiteAccessSettings', 'MobileUserAgentRegexps' ) as $userAgentAlias => $regexp )
            {
                if ( preg_match( $regexp, $this->httpUserAgent ) )
                {
                    $this->isMobileDevice = true;
                    $this->userAgentAlias = $userAgentAlias;

                    break;
                }
            }
        }
    }

    /**
     * Handles redirection to the mobile optimized interface
     *
     */
    public function redirect()
    {
        $http = eZHTTPTool::instance();
        $currentSiteAccess = eZSiteAccess::current();

        if ( $http->hasGetVariable( 'notmobile' ) )
        {
            setcookie( 'eZMobileDeviceDetect', 1, time() + (int)eZINI::instance()->variable( 'SiteAccessSettings', 'MobileDeviceDetectCookieTimeout' ), '/' );

            $http->redirect( eZSys::indexDir() );

            eZExecution::cleanExit();
        }

        if ( !isset( $_COOKIE['eZMobileDeviceDetect'] )
                && !in_array( $currentSiteAccess['name'], eZINI::instance()->variable( 'SiteAccessSettings', 'MobileSiteAccessList'  ) ) )
        {
            $currentUrl = eZSys::serverURL() . eZSys::requestURI();
            $redirectUrl = eZINI::instance()->variable( 'SiteAccessSettings', 'MobileSiteAccessURL' );

            // Do not redirect if already on the redirect url
            if ( strpos( $currentUrl, $redirectUrl ) !== 0 )
            {
                // Default siteaccess name needs to be removed from the uri when redirecting
                $uri = explode( '/', ltrim( eZSys::requestURI(), '/' ) );

                if ( array_shift( $uri ) == $currentSiteAccess['name'] )
                {
                    $http->redirect( $redirectUrl . '/' . implode( '/', $uri ) );
                }
                else
                {
                    $http->redirect( $redirectUrl . eZSys::requestURI() );
                }
            }

            eZExecution::cleanExit();
        }
    }

    /**
     * Returns true if current device is mobile
     *
     * @return bool
     */
    public function isMobileDevice()
    {
        return $this->isMobileDevice;
    }

    /**
     * Returns mobile User Agent alias defined in the site.ini.[SiteAccessSettings].MobileUserAgentRegexps
     *
     * @return string
     */
    public function getUserAgentAlias()
    {
        return $this->userAgentAlias;
    }
}
