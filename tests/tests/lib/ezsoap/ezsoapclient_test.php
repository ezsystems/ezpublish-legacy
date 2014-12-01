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

    /**
     * Provider for testSoapClientSend
     *
     * NB: This relies on network connection to soap.critmon1.ez.no
     */
    public static function providerTestSoapClientSend()
    {
        return array(
            array( 'bb4a091369e40cbf682a278cfd35f04a', 'soap.critmon1.ez.no', '/', 80, 'hostID', 'network_namespace' ),
            array( 'bb4a091369e40cbf682a278cfd35f04a', 'soap.critmon1.ez.no', '/', 443, 'hostID', 'network_namespace' ),
        );
    }

    /**
     * @dataProvider providerTestSoapClientSend
     */
    public function testSoapClientSend( $expectedSendResult, $server, $path, $port, $name, $namespace, $parameters = array() )
    {
        $client = new eZSOAPClient( $server, $path, $port );
        $request = new eZSOAPRequest( $name, $namespace, $parameters );
        $response = $client->send( $request );
        $this->assertEquals( $response->value(), $expectedSendResult );
    }
}

?>
