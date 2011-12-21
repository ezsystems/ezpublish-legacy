<?php
/**
 * File containing the ezpMobileDeviceDetectTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class ezpMobileDeviceDetectTest extends ezpTestCase
{
    protected $mobileDeviceDetect;

    public function setUp()
    {
        parent::setUp();

        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A405 Safari/7534.48.3';
        $_SERVER['HTTP_ACCEPT'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';

        // Enable mobile device detection
        ezpINIHelper::setINISetting( 'site.ini', 'SiteAccessSettings', 'DetectMobileDevice', 'enabled' );

        $this->mobileDeviceDetect = $this->getMock( 'ezpMobileDeviceDetect', null, array( $this->getMock( 'ezpMobileDeviceRegexpFilter', null ) ) );
    }

    public function tearDown()
    {
        parent::tearDown();

        ezpINIHelper::restoreINISettings();

        $this->mobileDeviceDetect = null;
    }

    /**
     * Tests if mobile device detection is enabled
     *
     */
    public function testIsEnabled()
    {
        $this->assertTrue( $this->mobileDeviceDetect->isEnabled() );
    }

    /**
     * Tests whether mobile device detection process went fine
     *
     */
    public function testProcess()
    {
        $this->mobileDeviceDetect->process();

        $this->assertTrue( $this->mobileDeviceDetect->isMobileDevice() );
        $this->assertEquals( 'IPhoneDevice', $this->mobileDeviceDetect->getUserAgentAlias() );

    }

    /**
     * Tests if mobile device was detected properly
     *
     */
    public function testIsMobileDevice()
    {
        $this->mobileDeviceDetect->process();

        $this->assertTrue( $this->mobileDeviceDetect->isMobileDevice() );
    }

    /**
     * Tests if mobile User Agent alias was read properly from the site.ini.[SiteAccessSettings].MobileUserAgentRegexps
     *
     */
    public function testGetUserAgentAlias()
    {
        $this->mobileDeviceDetect->process();

        $this->assertEquals( 'IPhoneDevice', $this->mobileDeviceDetect->getUserAgentAlias() );
    }

    /**
     * Tests if currently used mobile device detection filter is returned correctly
     *
     */
    public function testGetFilter()
    {
        $mobileDeviceDetectFilter = $this->mobileDeviceDetect->getFilter();

        $this->assertNotNull( $mobileDeviceDetectFilter );
        $this->assertInstanceOf( 'ezpMobileDeviceDetectFilterInterface', $mobileDeviceDetectFilter );

    }
}
