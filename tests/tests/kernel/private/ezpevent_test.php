<?php
/**
 * File containing the ezpTopologicalSortTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class ezpEventTest extends ezpTestCase
{
    protected $event = null;

    public function setUp()
    {
        parent::setUp();
        ezpEvent::resetInstance();
        ezpINIHelper::setINISetting( 'site.ini', 'Event', 'Listeners', array(
                'test/notify@ezpEventTest::helperNotify',
                'test/filter@ezpEventTest::helperFilterNotNull',
        ) );
        $this->event = ezpEvent::getInstance();
    }
    
    public function tearDown()
    {
        ezpINIHelper::restoreINISettings();
        $this->event = null;
        ezpEvent::resetInstance();
        parent::tearDown();
    }

    /**
     * Test misc aspects of attach() and detach()
     */
    public function testAttachDetach()
    {
        // test attach (returned value, and that function is used)
        $id = $this->event->attach( 'test/attach', 'ezpEventTest::helperFilterInc' );
        $this->assertTrue( is_numeric( $id ) );
        $this->assertEquals( 2, $this->event->filter( 'test/attach', 1 ) );

        // test attach again with different callback format
        $id2 = $this->event->attach( 'test/attach', array( 'ezpEventTest', 'helperFilterInc' ) );
        $this->assertTrue( is_numeric( $id2 ) );
        $this->assertTrue( $id < $id2  );
        $this->assertEquals( 3, $this->event->filter( 'test/attach', 1 ) );
        
        // test detach on $id
        $this->assertTrue( $this->event->detach( 'test/attach', $id ) );
        $this->assertEquals( 2, $this->event->filter( 'test/attach', 1 ) );
        
        // test detach on invalid id
        $this->assertFalse( $this->event->detach( 'test/attach', 404 ) );

        // test detach on last $id
        $this->assertTrue( $this->event->detach( 'test/attach', $id2 ) );
        $this->assertEquals( 1, $this->event->filter( 'test/attach', 1 ) );
    }

    /**
     * Test that new instance does not inherit events
     * (when not reading from ini settings)
     */
    public function testNewInstance()
    {
        $event = new ezpEvent( false );

        // test filter
        $this->assertEquals( null, $event->filter( 'test/filter', null ) );

        // test notify
        $this->assertFalse( $event->notify( 'test/notify' ) );
    }

    /**
     * Test misc aspects of filter()
     */
    public function testFilter()
    {
        // test that events w/o listeners return value
        $this->assertFalse( $this->event->filter( 'test/filter_not_here', false ) );
        $this->assertTrue( $this->event->filter( 'test/filter_not_here', true ) );
        $this->assertEquals( null, $this->event->filter( 'test/filter_not_here', null ) );

        // test that events with listeners return true and that value gets set
        $this->assertFalse( $this->event->filter( 'test/filter', false ) );
        $this->assertTrue( $this->event->filter( 'test/filter', true ) );
        $this->assertTrue( $this->event->filter( 'test/filter', null ) );
    }

    /**
     * Test misc aspects of notify()
     */
    public function testNotify()
    {
        // make sure static var is null
        self::$internalTestNotify = null;

        // test that events w/o listeners return false
        $this->assertFalse( $this->event->notify( 'test/notify_not_here' ) );

        // test that events with listeners return true and that value gets set
        $this->assertTrue( $this->event->notify( 'test/notify', array( true ) ) );
        $this->assertTrue( self::$internalTestNotify );

        // same test with different value
        $this->assertTrue( $this->event->notify( 'test/notify', array( false ) ) );
        $this->assertFalse( self::$internalTestNotify );

        // cleanup
        self::$internalTestNotify = null;
    }

    /**
     * Helper function used by testNotify(), sets recived param on static member so test can check it
     *
     * @param mixed $variable
     */
    public static function helperNotify( $param1 )
    {
        self::$internalTestNotify = $param1;
    }

    /**
     * Helper function used by testFilter(), returns value unless it's null, then return true
     *
     * @param mixed $variable
     */
    public static function helperFilterNotNull( $variable )
    {
        if ( $variable === null )
            return true;
        return $variable;
    }

    /**
     * Helper function used by testAttachDetach() increases value by 1
     *
     * @param int $variable
     */
    public static function helperFilterInc( $variable )
    {
        return ++$variable;
    }

    protected static $internalTestNotify = null;
}
?>
