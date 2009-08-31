<?php
/**
 * File containing the eZContentFunctionCollectionTest class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZContentFunctionCollectionTest extends ezpDatabaseTestCase
{

    public $backupGlobals = false;

    public function testFetchRelatedObjects()
    {
        $object1 = new ezpObject( 'article', 2 );
        $object1->title = __FUNCTION__ . ' A';
        $object1->publish();

        $object2 = new ezpObject( 'article', 2 );
        $object2->title = __FUNCTION__ . ' B';
        $object2->addContentObjectRelation( $object1->attribute( 'id' ) );
        $object2->publish();

        $ret = eZContentFunctionCollection::fetchRelatedObjects(
            $object2->attribute( 'id' ), false, true, false, false );

        $this->assertType( 'array', $ret );
        $this->assertArrayHasKey( 'result', $ret );
        $this->assertType( 'array', $ret['result'] );
        $this->assertTrue( count( $ret['result'] ) == 1 );
        $this->assertType( 'eZContentObject', $ret['result'][0] );
        $this->assertEquals( $object1->attribute( 'id' ), $ret['result'][0]->attribute( 'id' ) );
    }

    public function testFetchRelatedObjectsCount()
    {
        $object1 = new ezpObject( 'article', 2 );
        $object1->title = __FUNCTION__ . ' A';
        $object1->publish();

        $object2 = new ezpObject( 'article', 2 );
        $object2->title = __FUNCTION__ . ' B';
        $object2->addContentObjectRelation( $object1->attribute( 'id' ) );
        $object2->publish();

        $ret = eZContentFunctionCollection::fetchRelatedObjectsCount(
            $object2->attribute( 'id' ), false, true );

        $this->assertType( 'array', $ret );
        $this->assertArrayHasKey( 'result', $ret );
        $this->assertEquals( 1, $ret['result'] );
    }

    public function testFetchReverseRelatedObjects()
    {
        $object1 = new ezpObject( 'article', 2 );
        $object1->title = __FUNCTION__ . ' A';
        $object1->publish();

        $object2 = new ezpObject( 'article', 2 );
        $object2->title = __FUNCTION__ . ' B';
        $object2->addContentObjectRelation( $object1->attribute( 'id' ) );
        $object2->publish();

        $ret = eZContentFunctionCollection::fetchReverseRelatedObjects(
            $object1->attribute( 'id' ), false, true, false, false, false );

        $this->assertType( 'array', $ret );
        $this->assertArrayHasKey( 'result', $ret );
        $this->assertType( 'array', $ret['result'] );
        $this->assertTrue( count( $ret['result'] ) == 1 );
        $this->assertType( 'eZContentObject', $ret['result'][0] );
        $this->assertEquals( $object2->attribute( 'id' ), $ret['result'][0]->attribute( 'id' ) );
    }

    public function testFetchReverseRelatedObjectsCount()
    {
        $object1 = new ezpObject( 'article', 2 );
        $object1->title = __FUNCTION__ . ' A';
        $object1->publish();

        $object2 = new ezpObject( 'article', 2 );
        $object2->title = __FUNCTION__ . ' B';
        $object2->addContentObjectRelation( $object1->attribute( 'id' ) );
        $object2->publish();

        $ret = eZContentFunctionCollection::fetchReverseRelatedObjectsCount(
            $object1->attribute( 'id' ), false, true, false );

        $this->assertType( 'array', $ret );
        $this->assertArrayHasKey( 'result', $ret );
        $this->assertEquals( 1, $ret['result'] );
    }
}

?>