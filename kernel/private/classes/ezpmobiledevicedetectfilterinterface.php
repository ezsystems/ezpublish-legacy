<?php
/**
 * File containing the ezpMobileDeviceDetectFilterInterface interface
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

interface ezpMobileDeviceDetectFilterInterface
{
    /**
     * Processes the User Agent string and determines whether it is a mobile device or not
     * Needs to set boolean value for @see ezpMobileDeviceDetectFilterInterface::isMobileDevice()
     * and optionally user agent alias @see ezpMobileDeviceDetectFilterInterface::getUserAgentAlias()
     *
     * @abstract
     */
    public function process();

    /**
     * Handles redirection to the mobile optimized interface
     *
     * @abstract
     */
    public function redirect();

    /**
     * Returns true if current device is mobile
     *
     * @abstract
     * @return bool
     */
    public function isMobileDevice();

    /**
     * Returns mobile User Agent alias defined in the site.ini.[SiteAccessSettings].MobileUserAgentRegexps
     *
     * @abstract
     * @return string
     */
    public function getUserAgentAlias();
}
