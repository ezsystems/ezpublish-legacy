<?php
/**
 * File containing the eZHTTPToolRegression class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZHTTPToolRegression extends ezpTestCase
{
    /**
     * If you send an HTTP request using eZHTTPTool::sendHTTPRequest( ) to an
     * URL with a domain name containing a dash ( -), it's misunderpreted and
     * doesn't get executed.
     *
     * @link http://issues.ez.no/10651
     */
    public function testSendRequestContainingDashes()
    {
        self::markTestSkipped( "Test disabled pending update." );
        $url = 'http://php-og.mgdm.net/';

        $this->assertInternalType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            eZHTTPTool::sendHTTPRequest( $url, 80, false, 'eZ Publish', false )
        );
    }

    /**
     * Some parts of eZ do not benefit from the enhanced checks implemented
     * in eZSys::isSSLNow(), especially when using an SSL reverse proxy
     * configured to send the HTTP_X_FORWARDED_PROTO header.
     *
     * @link http://issues.ez.no/21731
     */
    public function test_createRedirectUrl()
    {
        $path = '/a/root/rel/ative';
        self::assertEquals( 'http://example.com' . $path, eZHTTPTool::createRedirectUrl( $path, array() ) );

        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        self::assertEquals( 'https://example.com' . $path, eZHTTPTool::createRedirectUrl( $path, array() ) );
        unset( $_SERVER['HTTP_X_FORWARDED_PROTO'] );
    }
}

?>
