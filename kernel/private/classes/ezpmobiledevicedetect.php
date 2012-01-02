<?php
/**
 * File containing the ezpMobileDeviceDetect abstract class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
        return ( eZINI::instance()->variable( 'SiteAccessSettings', 'DetectMobileDevice' ) === 'enabled' );
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

