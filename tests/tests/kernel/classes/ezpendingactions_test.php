<?php
/**
 * File containing the eZPendingActions class tests
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZPendingActionsTest extends ezpDatabaseTestCase
{
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
     * Unit test for fetchByAction() method
     */
    public function testFetchByAction()
    {
        // Insert several fixtures at one time. Can't use @dataProvider to do that
        $fixtures = $this->providerForTestFecthByAction();
        foreach( $fixtures as $fixture )
        {
            $this->insertPendingAction( $fixture[0], $fixture[1], $fixture[2] );
        }

        $res = eZPendingActions::fetchByAction( 'test' );
        $this->assertInternalType( PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $res );
        foreach($res as $row)
        {
            $this->assertInstanceOf( 'eZPendingActions', $row );
        }

        unset($res);

        $dateFilter = array( '<=', time() );
        $res = eZPendingActions::fetchByAction( 'test', $dateFilter );
        $this->assertInternalType( PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $res );
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
     * Inserts a pending action
     * @param $action
     * @param $created
     * @param $params
     */
    private function insertPendingAction( $action, $created, $params )
    {
        $row = array(
            'action'      => $action,
            'created'     => $created,
            'param'       => $params
        );

        $obj = new eZPendingActions( $row );
        $obj->store();
        unset( $obj );
    }

    /**
     * Test for bad date filter token in eZPendingActions::fetchByAction()
     * @param $badFilter
     * @dataProvider providerForTestBadDateFilter
     */
    public function testBadDateFilter( $badFilter )
    {
        $res = eZPendingActions::fetchByAction( 'test', $badFilter );
        $this->assertNull( $res );
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
        // Insert several fixtures at one time. Can't use @dataProvider to do that
        $fixtures = $this->providerForTestFecthByAction();
        foreach( $fixtures as $fixture )
        {
            $this->insertPendingAction( $fixture[0], $fixture[1], $fixture[2] );
        }

        eZPendingActions::removeByAction( 'test' );
        $res = eZPendingActions::fetchByAction( 'test' );
        $this->assertTrue( empty( $res ) );
    }
}

?>
