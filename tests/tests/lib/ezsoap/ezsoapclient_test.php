<?php
/**
 * File containing the eZSOAPClientTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZSOAPClientTest extends ezpTestCase
{
    public static function providerTestSoapClientConstructorUseSSL()
    {
        return array(
            array( 80, false ),
            array( 80, false, false ),
            array( 80, true, true ),
            array( 443, true ),
            array( 443, false, false ),
            array( 443, true, true ),
            array( 'ssl', true ),
            array( 'ssl', true, true ),
            array( 'ssl', false, false ),
        );
    }

    /**
     * @dataProvider providerTestSoapClientConstructorUseSSL
     */
    public function testSoapClientConstructorUseSSL( $port, $expectedUseSSLResult, $useSSL = null )
    {
        $client = new eZSOAPClient( 'soap.example.com', '/', $port, $useSSL );
        $this->assertEquals( $this->readAttribute( $client, 'UseSSL' ), $expectedUseSSLResult );
    }

    public static function providerTestSoapClientConstructorPort()
    {
        return array(
            array( 80, 80 ),
            array( 443, 443 ),
            array( 'ssl', 443 )
        );
    }

    /**
     * @dataProvider providerTestSoapClientConstructorPort
     */
    public function testSoapClientConstructorPort( $port, $expectedPortResult )
    {
        $client = new eZSOAPClient( 'soap.example.com', '/', $port );
        $this->assertEquals( $this->readAttribute( $client, 'Port' ), $expectedPortResult );
    }
}

?>
