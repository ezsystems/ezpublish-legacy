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

    /**
     * Unit test for eZContentFunctionCollection::fetchRelatedObjects
     **/
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

    /**
     * Unit test for eZContentFunctionCollection::fetchRelatedObjectsCount
     **/
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

    /**
     * Unit test for eZContentFunctionCollection::fetchReverseRelatedObjects
     **/
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

    /**
     * Unit test for eZContentFunctionCollection::fetchReverseRelatedObjectsCount
     **/
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

    /**
     * Unit test for eZContentFunctionCollection::fetchKeywordCount
     **/
    public function testFetchKeywordCount()
    {
        $class1Identifier = __FUNCTION__ . '_1';
        $class2Identifier = __FUNCTION__ . '_2';

        // First create two content classes with a keyword attribute
        $class1 = new ezpClass( $class1Identifier, $class1Identifier, '<title>' );
        $class1->add( 'Title', 'title', 'ezstring' );
        $class1->add( 'Keywords', 'keywords', 'ezkeyword' );
        $class1->store();
        $class1ID = $class1->class->attribute( 'id' );

        $class2 = new ezpClass( $class2Identifier, $class2Identifier, '<title>' );
        $class2->add( 'Title', 'title', 'ezstring' );
        $class2->add( 'Keywords', 'keywords', 'ezkeyword' );
        $class2->store();
        $class2ID = $class2->class->attribute( 'id' );

        // Create an instance of each of these classes with attributes
        $object = new ezpObject( $class1Identifier, 2 );
        $object->title = __FUNCTION__;
        $object->keywords = "keyword1, keyword2, keyword3";
        $object->publish();

        $object = new ezpObject( $class2Identifier, 2 );
        $object->title = __FUNCTION__;
        $object->keywords = "keyword4, keyword5, keyword6";
        $object->publish();

        // fetch count for prefix 'k' on class 1
        foreach( array( $class1ID, $class2ID ) as $contentClassID )
        {
            $count = eZContentFunctionCollection::fetchKeywordCount( 'k', $contentClassID );
            $this->assertType( 'array', $count );
            $this->assertArrayHasKey( 'result', $count );
            $this->assertEquals( 3, $count['result'] );
        }

        // fetch count for prefix 'k' on both classes
        $count = eZContentFunctionCollection::fetchKeywordCount( 'k', array( $class1ID, $class2ID ) );
        $this->assertType( 'array', $count );
        $this->assertArrayHasKey( 'result', $count );
        $this->assertEquals( 6, $count['result'] );
    }

    /**
     * Unit test for eZContentFunctionCollection::fetchKeyword
     **/
    public function testFetchKeyword()
    {
        $class1Identifier = __FUNCTION__ . '_1';

        // First create two content classes with a keyword attribute
        $class1 = new ezpClass( $class1Identifier, $class1Identifier, '<title>' );
        $class1->add( 'Title', 'title', 'ezstring' );
        $class1->add( 'Keywords', 'keywords', 'ezkeyword' );
        $class1->store();
        $class1ID = $class1->class->attribute( 'id' );

        // Create an instance of each of these classes with attributes
        $object = new ezpObject( $class1Identifier, 2 );
        $object->title = __FUNCTION__;
        $object->keywords = "keyword1, keyword2, keyword3";
        $object->publish();

        // Fetch keywords for class 1
        $keywords = eZContentFunctionCollection::fetchKeyword(
            'k', $class1ID, 0, 20 );
        $this->assertType( 'array', $keywords );
        $this->assertArrayHasKey( 'result', $keywords );
        $this->assertType( 'array', $keywords['result'] );
        $this->assertEquals( 3, count( $keywords['result'] ) );
        foreach( $keywords['result'] as $result )
        {
            $this->assertType( 'array', $result );
            $this->assertArrayHasKey( 'keyword', $result );
            $this->assertArrayHasKey( 'link_object', $result );
            $this->assertType( 'eZContentObjectTreeNode', $result['link_object'] );
        }
    }
}

?>