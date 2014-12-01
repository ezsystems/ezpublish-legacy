<?php
/**
 * File containing the eZHTTPToolTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZHTTPToolTest extends ezpTestCase
{
    /**
     * Provider for testGetDataByURL
     *
     * NB: This relies on network connection to soap.critmon1.ez.no
     */
    public static function providerTestGetDataByURL()
    {
        return array(
            array( 'Error: this web page does only understand POST methods', 'http://soap.critmon1.ez.no' ),
            array( 'Error: this web page does only understand POST methods', 'https://soap.critmon1.ez.no' ),
            array( true, 'http://soap.critmon1.ez.no', true ),
            array( true, 'https://soap.critmon1.ez.no', true ),
        );
    }

    /**
     * @dataProvider providerTestGetDataByURL
     */
    public function testGetDataByURL( $expectedDataResult, $url, $justCheckURL = false, $userAgent = false )
    {
        $this->assertEquals( eZHTTPTool::getDataByURL( $url, $justCheckURL, $userAgent ), $expectedDataResult );

        // There's no way to test the whole method without refactoring it.
        if ( extension_loaded( 'curl' ) )
        {
            $this->markTestIncomplete( 'cURL behaviour tested, not fopen()' );
        }
        else
        {
            $this->markTestIncomplete( 'fopen() behaviour tested, not cURL' );
        }
    }
}

?>
