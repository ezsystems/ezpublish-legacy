<?php
/**
 * File containing the eZDebugRegression class
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZDebugRegression extends ezpTestCase
{
    protected function setUp()
    {
        $GLOBALS['eZDebugEnabled'] = true;
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
        eZDebug::accumulatorStart( __METHOD__, false, false, true );
        eZDebug::accumulatorStart( __METHOD__, false, false, true );

        eZDebug::accumulatorStop( __METHOD__, true );
        eZDebug::accumulatorStop( __METHOD__, true );

        eZDebug::accumulatorStart( __METHOD__, false, false, true );

        $debug = eZDebug::instance();
        $this->assertEquals( 0, $debug->TimeAccumulatorList[__METHOD__]['recursive_counter'] );
    }
}

?>