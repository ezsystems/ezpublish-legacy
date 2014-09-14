<?php
/**
 * File containing the eZDebugRegression class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZDebugRegression extends ezpTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $GLOBALS['eZDebugEnabled'] = true;
    }

    protected function tearDown()
    {
        $GLOBALS['eZDebugEnabled'] = false;
        parent::tearDown();
    }

    /**
     * Test scenario for issue #13942: Bug in ezdebug.php / accumulatorStart()
     *
     * Test outline
     * -------------
     * 1. Call eZDebug::accumulatorStart
     * 2. Call eZDebug::accumulatorStart again with the same key
     *
     * @result: a fatal error occurs: "Using $this when not in object context"
     * @expected: no fatal error
     * @link http://issues.ez.no/13942
     * @group issue_13942
     */
    function testSubsequentAccumulatorStartRecursive()
    {
        $GLOBALS['eZDebugEnabled'] = true;

        eZDebug::accumulatorStart( __METHOD__, false, false, true );
        eZDebug::accumulatorStart( __METHOD__, false, false, true );
    }

    /**
     * Test scenario for issue #13955: eZDebug::accumulatorStop( $key, true ) does not decrement the recursive counter
     *
     * Test outline
     * -------------
     * 1. Start a debug accumulator 2 times
     * 2. Stop the debug accumulator 1 time
     *
     * @result: the debug accumulator's recursive counter is still set to 1
     * @expected: the debug accumulator's recursive counter is 0
     * @link http://issues.ez.no/13955
     * @group issue_13955
     */
    function testAccumulatorStopRecursiveCounter()
    {
        self::markTestSkipped( "Pending bugfix" );
        eZDebug::accumulatorStart( __METHOD__, false, false, true );
        eZDebug::accumulatorStart( __METHOD__, false, false, true );

        eZDebug::accumulatorStop( __METHOD__, true );

        $debug = eZDebug::instance();
        $this->assertEquals( 0, $debug->TimeAccumulatorList[__METHOD__]['recursive_counter'] );
    }

    /**
     * Test scenario for issue #13956: eZDebug::accumulatorStop( $key, true ) does not remove the recursive counter
     *
     * @link http://issues.ez.no/13956
     * @group issue_13956
     */
    function testAccumulatorStartMultipleResursiveCounter()
    {
        self::markTestSkipped( "Pending bugfix" );
        eZDebug::accumulatorStart( __METHOD__, false, false, true );
        eZDebug::accumulatorStart( __METHOD__, false, false, true );

        eZDebug::accumulatorStop( __METHOD__, true );
        eZDebug::accumulatorStop( __METHOD__, true );

        eZDebug::accumulatorStart( __METHOD__, false, false, true );

        $debug = eZDebug::instance();
        $this->assertEquals( 0, $debug->TimeAccumulatorList[__METHOD__]['recursive_counter'] );
    }

    /**
     * Test for EZP-23343
     *
     * Make sure an IPv6 ipaddress is in a network
     *
     * @link https://jira.ez.no/browse/EZP-23343
     */
    function testeZDebugIsIPInNetIPv6LocalHostNetworkRange()
    {
        $class = new ReflectionClass( 'eZDebug' );
        $methodIsAllowedByCurrentIP = $class->getMethod( 'isIPInNetIPv6' );
        $methodIsAllowedByCurrentIP->setAccessible( true );

        $object = new eZDebug();
        $this->assertEquals( true, $methodIsAllowedByCurrentIP->invokeArgs( $object, array( '::1', '::1/32' ) ) );
        $this->assertEquals( true, $methodIsAllowedByCurrentIP->invokeArgs( $object, array( '::2', '::1/32' ) ) );
        $this->assertEquals( true, $methodIsAllowedByCurrentIP->invokeArgs( $object, array( '::7', '::1/32' ) ) );

        $this->assertEquals( true, $methodIsAllowedByCurrentIP->invokeArgs( $object, array( '2001:db8:1234:0000:0000:0000:0000:0000', '2001:db8:1234::/48' ) ) );
        $this->assertEquals( true, $methodIsAllowedByCurrentIP->invokeArgs( $object, array( '2001:db8:1234:0000:0000:0000:0000:0007', '2001:db8:1234::/48' ) ) );
        $this->assertEquals( true, $methodIsAllowedByCurrentIP->invokeArgs( $object, array( '2001:db8:1234:ffff:ffff:ffff:ffff:ffff', '2001:db8:1234::/48' ) ) );
    }

    /**
     * Test for EZP-23343
     *
     * Make sure the 'packed' output of inet_pton
     * is transformed into a full binary representation
     *
     * @link https://jira.ez.no/browse/EZP-23343
     */
    function testeZDebugPackedToBin()
    {
        $class = new ReflectionClass( 'eZDebug' );
        $methodIsAllowedByCurrentIP = $class->getMethod( 'packedToBin' );
        $methodIsAllowedByCurrentIP->setAccessible( true );

        $object = new eZDebug();
        $this->assertEquals( '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001', $methodIsAllowedByCurrentIP->invokeArgs( $object, array( inet_pton( '::1' ) ) ) );
        $this->assertEquals( '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000111', $methodIsAllowedByCurrentIP->invokeArgs( $object, array( inet_pton( '::7' ) ) ) );
        $this->assertEquals( '00100000000000010000110110111000000100100011010000000000000000000000000000000000000000000000000000000000000000000000000000000000', $methodIsAllowedByCurrentIP->invokeArgs( $object, array( inet_pton( '2001:db8:1234::' ) ) ) );
        $this->assertEquals( '00100000000000010000110110111000000100100011010011111111111111111111111111111111111111111111111111111111111111111111111111111111', $methodIsAllowedByCurrentIP->invokeArgs( $object, array( inet_pton( '2001:db8:1234:ffff:ffff:ffff:ffff:ffff' ) ) ) );
    }

    /**
     * Test for EZP-23343
     *
     * Make sure debug output is displayed for an IPv6 localhost address
     *
     * @link https://jira.ez.no/browse/EZP-23343
     */
    function testDebugOutputIPv6LocalHost()
    {
        $class = new ReflectionClass( 'eZDebug' );
        $methodIsAllowedByCurrentIP = $class->getMethod( 'isAllowedByCurrentIP' );
        $methodIsAllowedByCurrentIP->setAccessible( true );

        $object = new eZDebug();
        $this->assertEquals( true, $methodIsAllowedByCurrentIP->invokeArgs( $object, array( array( '::1/32', 'commandline' ) ) ) );
    }

    /**
     * Test for EZP-23343
     *
     * Make sure debug output is displayed for an IPv6 public address
     *
     * @link https://jira.ez.no/browse/EZP-23343
     */
    function testDebugOutputIPv6PublicHost()
    {
        $class = new ReflectionClass( 'eZDebug' );
        $methodIsAllowedByCurrentIP = $class->getMethod( 'isAllowedByCurrentIP' );
        $methodIsAllowedByCurrentIP->setAccessible( true );

        $object = new eZDebug();
        $this->assertEquals( true, $methodIsAllowedByCurrentIP->invokeArgs( $object, array( array( '2001:db8:1234::/48', 'commandline' ) ) ) );
    }

    /**
     * Test for EZP-23343
     *
     * Make sure debug output is displayed for an IPv4 localhost address
     *
     * @link https://jira.ez.no/browse/EZP-23343
     */
    function testDebugOutputIPv4LocalHost()
    {
        $class = new ReflectionClass( 'eZDebug' );
        $methodIsAllowedByCurrentIP = $class->getMethod( 'isAllowedByCurrentIP' );
        $methodIsAllowedByCurrentIP->setAccessible( true );

        $object = new eZDebug();
        $this->assertEquals( true, $methodIsAllowedByCurrentIP->invokeArgs( $object, array( array( '127.0.0.1/32', 'commandline' ) ) ) );
    }

    /**
     * Test for EZP-23343
     *
     * Make sure debug output is displayed for an IPv4 public address
     *
     * @link https://jira.ez.no/browse/EZP-23343
     */
    function testDebugOutputIPv4PublicHost()
    {
        $class = new ReflectionClass( 'eZDebug' );
        $methodIsAllowedByCurrentIP = $class->getMethod( 'isAllowedByCurrentIP' );
        $methodIsAllowedByCurrentIP->setAccessible( true );

        $object = new eZDebug();
        $this->assertEquals( true, $methodIsAllowedByCurrentIP->invokeArgs( $object, array( array( '192.0.2.0/24', 'commandline' ) ) ) );
    }
}

?>
