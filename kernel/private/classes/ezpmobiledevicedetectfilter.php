<?php
/**
 * File containing the ezpMobileDeviceDetectFilter class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class ezpMobileDeviceDetectFilter
{
    /**
     * Returns an instance of the ezpMobileDeviceDetectFilterInterface class
     *
     * @static
     * @return ezpMobileDeviceDetectFilterInterface|null
     */
    public static function getFilter()
    {
        $mobileDeviceFilterClass = eZINI::instance()->variable( 'SiteAccessSettings', 'MobileDeviceFilterClass' );

        $mobileDeviceDetectFilter = class_exists( $mobileDeviceFilterClass) ? new $mobileDeviceFilterClass : null;

        if ( $mobileDeviceDetectFilter instanceof ezpMobileDeviceDetectFilterInterface )
            return $mobileDeviceDetectFilter;

        return null;
    }
}
