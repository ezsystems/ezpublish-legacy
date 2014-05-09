<?php
/**
 * File containing the ezpMobileDeviceDetect abstract class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * ezpMobileDeviceDetect class implementation
 */
class ezpMobileDeviceDetect
{
    /**
     * @var ezpMobileDeviceDetectFilterInterface The mobile device filter object container
     */
    protected $filter;

    /**
     * Construct
     *
     * @param ezpMobileDeviceDetectFilterInterface $filter
     */
    public function __construct( ezpMobileDeviceDetectFilterInterface $filter )
    {
        $this->filter = $filter;
    }

    /**
     * Checks whether mobile device detection is enabled or not
     *
     * @static
     * @return bool
     */
    public static function isEnabled()
    {
        if ( eZINI::instance()->variable( 'SiteAccessSettings', 'DetectMobileDevice' ) === 'enabled' )
        {
            $mobileSaList = eZINI::instance()->variable( 'SiteAccessSettings', 'MobileSiteAccessList' );

            if ( !empty( $mobileSaList ) )
            {
                return true;
            }
            else
            {
                eZDebug::writeError(
                    "DetectMobileDevice is enabled and MobileSiteAccessList is empty, please check your site.ini configuration",
                    __METHOD__
                );
            }
        }

        return false;
    }

    /**
     * Processes the User Agent string and determines whether it is a mobile device or not
     *
     */
    public function process()
    {
        $this->filter->process();
    }

    /**
     * Handles redirection to the mobile optimized interface
     *
     */
    public function redirect()
    {
        $this->filter->redirect();
    }

    /**
     * Returns true if current device is mobile
     *
     * @return bool
     */
    public function isMobileDevice()
    {
        return $this->filter->isMobileDevice();
    }

    /**
     * Returns mobile User Agent alias defined in the site.ini.[SiteAccessSettings].MobileUserAgentRegexps
     *
     * @return string
     */
    public function getUserAgentAlias()
    {
        return $this->filter->getUserAgentAlias();
    }

    /**
     * Returns currently used mobile device detection filter
     *
     * @return ezpMobileDeviceDetectFilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }
}

