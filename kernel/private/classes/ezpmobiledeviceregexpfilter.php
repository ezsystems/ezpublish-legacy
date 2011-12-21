<?php
/**
 * File containing the ezpMobileDeviceRegexpFilter class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
        $this->httpUserAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->httpAccept = $_SERVER['HTTP_ACCEPT'];
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
            $http->redirect( eZINI::instance()->variable( 'SiteAccessSettings', 'MobileSiteAccessURL' ) );

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
