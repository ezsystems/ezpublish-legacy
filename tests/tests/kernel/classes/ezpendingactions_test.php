<?php
/**
 * File containing the eZPendingActions class tests
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @author Jerome Vieilledent 
 * @package tests
 */

class eZPendingActionsTest extends ezpDatabaseTestCase
{
    public function setUp()
    {
        
        parent::setUp();
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }
    
    /**
     * Unit test for eZPersistentObject implementation
     */
    public function testPersistentObjectInterface()
    {
        $this->assertTrue( is_subclass_of( 'eZPendingActions', 'eZPersistentObject' ) );
        $this->assertTrue( method_exists( 'eZPendingActions', 'definition' ) );
    }
    
    /**
     * Unit test for good eZPersistentObject (ORM) implementation for ezsite_data table
     */
    public function testORMImplementation()
    {
        $def = eZPendingActions::definition();
        $this->assertEquals( 'eZPendingActions', $def['class_name'] );
        $this->assertEquals( 'ezpending_actions', $def['name'] );
        
        $fields = $def['fields'];
        $this->assertArrayHasKey( 'action', $fields );
        $this->assertArrayHasKey( 'created', $fields );
        $this->assertArrayHasKey( 'param', $fields );
    }
    
    /**
     * Test for inserting a pending action
     * @dataProvider providerForTestFecthByAction
     * @param $action
     * @param $created
     * @param $params
     */
    public function testInsertPendingAction( $action, $created, $params )
    {
        $row = array(
            'action'      => $action,
            'created'     => $created,
            'params'      => $params
        );
        
        $obj = new eZPendingActions( $row );
        $obj->store();
        unset( $obj );
    }
    
    /**
     * Unit test for fetchByAction() method
     */
    public function testFetchByAction()
    {
        
        $res = eZPendingActions::fetchByAction( 'test' );
        $this->assertType( PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $res );
        foreach($res as $row)
        {
            $this->assertType( 'eZPendingActions', $row );
        }
        
        unset($res);
        
        $dateFilter = array( '<=', time() );
        $res = eZPendingActions::fetchByAction( 'test', $dateFilter );
        $this->assertType( PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $res );
    }
    
    /**
     * Data provider for self::testFetchByAction()
     * @see testFetchByAction()
     */
    public function providerForTestFecthByAction()
    {
        $time = time();
        
        return array(
            array( 'test', $time, 'Some params' ),
            array( 'test', $time+10, 'Other params' ),
            array( 'test', $time+20, '' )
        );
    }
    
    /**
     * Test for bad date filter token in eZPendingActions::fetchByAction()
     * @param $badFilter
     * @dataProvider providerForTestBadDateFilter
     * @expectedException InvalidArgumentException
     */
    public function testBadDateFilter( $badFilter )
    {
        $res = eZPendingActions::fetchByAction( 'test', $badFilter );
    }
    
    /**
     * Provider for self::testBadDateFilter()
     * @see testBadDateFilter()
     */
    public function providerForTestBadDateFilter()
    {
        return array(
            array( array( time(), '=' ) ), // Wrong order
            array( array( '<=', time(), 'foobar' ) ), // Wrong entries count
            array( array( '<>', time() ) ) // Invalid token
        );
    }
    
    /**
     * Test for eZPendingActions::removeByAction()
     */
    public function testRemoveByAction()
    {
        eZPendingActions::removeByAction( 'test' );
        $res = eZPendingActions::fetchByAction( 'test' );
        $this->assertTrue( empty( $res ) );
    }
}

?>