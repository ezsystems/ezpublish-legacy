<?php
/**
 * File containing the eZContentFunctionCollectionTest class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 * @group ezpDatabaseTestCase
 */

class eZContentFunctionCollectionTest extends ezpDatabaseTestCase
{

    public $backupGlobals = false;

    /**
     * Unit test for eZContentFunctionCollection::fetchRelatedObjects
     */
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

        $this->assertInternalType( 'array', $ret );
        $this->assertArrayHasKey( 'result', $ret );
        $this->assertInternalType( 'array', $ret['result'] );
        $this->assertTrue( count( $ret['result'] ) == 1 );
        $this->assertInstanceOf( 'eZContentObject', $ret['result'][0] );
        $this->assertEquals( $object1->attribute( 'id' ), $ret['result'][0]->attribute( 'id' ) );
    }

    /**
     * Unit test for eZContentFunctionCollection::fetchRelatedObjectsCount
     */
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

        $this->assertInternalType( 'array', $ret );
        $this->assertArrayHasKey( 'result', $ret );
        $this->assertEquals( 1, $ret['result'] );
    }

    /**
     * Unit test for eZContentFunctionCollection::fetchReverseRelatedObjects
     */
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

        $this->assertInternalType( 'array', $ret );
        $this->assertArrayHasKey( 'result', $ret );
        $this->assertInternalType( 'array', $ret['result'] );
        $this->assertTrue( count( $ret['result'] ) == 1 );
        $this->assertInstanceOf( 'eZContentObject', $ret['result'][0] );
        $this->assertEquals( $object2->attribute( 'id' ), $ret['result'][0]->attribute( 'id' ) );
    }

    /**
     * Unit test for eZContentFunctionCollection::fetchReverseRelatedObjectsCount
     */
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

        $this->assertInternalType( 'array', $ret );
        $this->assertArrayHasKey( 'result', $ret );
        $this->assertEquals( 1, $ret['result'] );
    }

    /**
     * Unit test for eZContentFunctionCollection::fetchKeywordCount
     */
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
        foreach ( array( $class1ID, $class2ID ) as $contentClassID )
        {
            $count = eZContentFunctionCollection::fetchKeywordCount( 'k', $contentClassID );
            $this->assertInternalType( 'array', $count );
            $this->assertArrayHasKey( 'result', $count );
            $this->assertEquals( 3, $count['result'] );
        }

        // fetch count for prefix 'k' on both classes
        $count = eZContentFunctionCollection::fetchKeywordCount( 'k', array( $class1ID, $class2ID ) );
        $this->assertInternalType( 'array', $count );
        $this->assertArrayHasKey( 'result', $count );
        $this->assertEquals( 6, $count['result'] );

        
     
        // Create placeholder folders for objects
        $folder1 = new ezpObject( 'folder', 2 );
        $folder1->title = __FUNCTION__ . ' A';
        $folder1->publish();

        $folder2 = new ezpObject( 'folder', 2 );
        $folder2->title = __FUNCTION__ . ' B';
        $folder2->publish();

        $folder3 = new ezpObject( 'folder', $folder1->mainNode->node_id );
        $folder3->title = __FUNCTION__ . ' A';
        $folder3->publish();

        
        // Create an objects to test function
        $object0 = new ezpObject( $class1Identifier, 2 );
        $object0->title = __FUNCTION__ . ' A ';
        $object0->keywords = "keyword7";
        $object0->publish();
        
        $object1 = new ezpObject( $class1Identifier, $folder1->mainNode->node_id );
        $object1->title = __FUNCTION__ . ' A ';
        $object1->keywords = "keyword7";
        $object1->publish();
        
        $object2 = new ezpObject( $class1Identifier, $folder2->mainNode->node_id );
        $object2->title = __FUNCTION__ . ' C ';
        $object2->keywords = "keyword7";
        $object2->publish();
        
        $object3 = new ezpObject( $class1Identifier, $folder3->mainNode->node_id );
        $object3->title = __FUNCTION__ . ' D ';
        $object3->keywords = "keyword7";
        $object3->publish();
        
        // Fetch keyword count for class 1, not specifying parent node
        $count = eZContentFunctionCollection::fetchKeywordCount( 'keyword7', $class1ID );
        $this->assertInternalType( 'array', $count );
        $this->assertArrayHasKey( 'result', $count );
        $this->assertEquals( 4, $count['result'] );
        
        // Fetch keyword count for class 1, directly below parentNodeId (rootNode)
        $count = eZContentFunctionCollection::fetchKeywordCount( 'keyword7', $class1ID, false, 2 );
        $this->assertInternalType( 'array', $count );
        $this->assertArrayHasKey( 'result', $count );
        $this->assertEquals( 1, $count['result'] );
        
        // Fetch keyword count for class 1, directly below parentNodeId (specific folder)
        $count = eZContentFunctionCollection::fetchKeywordCount( 'keyword7', $class1ID, false, $folder1->mainNode->node_id );
        $this->assertInternalType( 'array', $count );
        $this->assertArrayHasKey( 'result', $count );
        $this->assertEquals( 1, $count['result'] );
        
        // Fetch keyword count for class 1, from root folder, with depth equal to 2
        $count = eZContentFunctionCollection::fetchKeywordCount( 'keyword7', $class1ID, false, 2, true, false, 2 );
        $this->assertInternalType( 'array', $count );
        $this->assertArrayHasKey( 'result', $count );
        $this->assertEquals( 3, $count['result'] );
        
        // Fetch keyword count for class 1, from sepecific node, with depth equal to 2
        $count = eZContentFunctionCollection::fetchKeywordCount( 'keyword7', $class1ID, false, $folder1->mainNode->node_id, true, false, 2 );
        $this->assertInternalType( 'array', $count );
        $this->assertArrayHasKey( 'result', $count );
        $this->assertEquals( 2, $count['result'] );
        
        // Fetch keyword count for class 1, from root folder, with depth equal to 0 (unlimited)
        $count = eZContentFunctionCollection::fetchKeywordCount( 'keyword7', $class1ID, false, 2, true, false, 0 );
        $this->assertInternalType( 'array', $count );
        $this->assertArrayHasKey( 'result', $count );
        $this->assertEquals( 4, $count['result'] );
        
        // Fetch keyword count for class 1, from specific folder, with depth equal to 0 (unlimited)
        $count = eZContentFunctionCollection::fetchKeywordCount( 'keyword7', $class1ID, false, $folder1->mainNode->node_id, true, false, 0 );
        $this->assertInternalType( 'array', $count );
        $this->assertArrayHasKey( 'result', $count );
        $this->assertEquals( 2, $count['result'] );
    }

    /**
     * Unit test for eZContentFunctionCollection::fetchKeyword
     */
    public function testFetchKeyword()
    {
        $class1Identifier = __FUNCTION__ . '_1';

        // First create two content classes with a keyword attribute
        $class1 = new ezpClass( $class1Identifier, $class1Identifier, '<title>' );
        $class1->add( 'Title', 'title', 'ezstring' );
        $class1->add( 'Keywords', 'keywords', 'ezkeyword' );
        $class1->store();
        $class1ID = $class1->class->attribute( 'id' );

        // Create placeholder folders for objects
        $folder1 = new ezpObject( 'folder', 2 );
        $folder1->title = __FUNCTION__ . ' A';
        $folder1->publish();

        $folder2 = new ezpObject( 'folder', 2 );
        $folder2->title = __FUNCTION__ . ' B';
        $folder2->publish();

        $folder3 = new ezpObject( 'folder', $folder1->mainNode->node_id );
        $folder3->title = __FUNCTION__ . ' A';
        $folder3->publish();

        // Create an objects to test function
        $object0 = new ezpObject( $class1Identifier, 2 );
        $object0->title = __FUNCTION__ . ' A ';
        $object0->keywords = "keyword1, keyword2, keyword3";
        $object0->publish();
        
        $object1 = new ezpObject( $class1Identifier, $folder1->mainNode->node_id );
        $object1->title = __FUNCTION__ . ' A ';
        $object1->keywords = "keyword1, keyword2, keyword3";
        $object1->publish();
        
        $object2 = new ezpObject( $class1Identifier, $folder2->mainNode->node_id );
        $object2->title = __FUNCTION__ . ' C ';
        $object2->keywords = "keyword1, keyword2, keyword3";
        $object2->publish();
        
        $object3 = new ezpObject( $class1Identifier, $folder3->mainNode->node_id );
        $object3->title = __FUNCTION__ . ' D ';
        $object3->keywords = "keyword1, keyword2, keyword3";
        $object3->publish();
        
        // Fetch keywords for class 1, on all scope
        $keywords = eZContentFunctionCollection::fetchKeyword(
            'k', $class1ID, 0, 20 );
        $this->assertInternalType( 'array', $keywords );
        $this->assertArrayHasKey( 'result', $keywords );
        $this->assertInternalType( 'array', $keywords['result'] );
        $this->assertEquals( 12, count( $keywords['result'] ) );
        foreach ( $keywords['result'] as $result )
        {
            $this->assertInternalType( 'array', $result );
            $this->assertArrayHasKey( 'keyword', $result );
            $this->assertArrayHasKey( 'link_object', $result );
            $this->assertInstanceOf( 'eZContentObjectTreeNode', $result['link_object'] );
        }
        
        // Fetch keyword1 for class 1, not specifying parent node
        $keywords = eZContentFunctionCollection::fetchKeyword(
            'keyword1', $class1ID, 0, 20 );
        $this->assertInternalType( 'array', $keywords );
        $this->assertArrayHasKey( 'result', $keywords );
        $this->assertInternalType( 'array', $keywords['result'] );
        $this->assertEquals( 4, count( $keywords['result'] ) );
        foreach ( $keywords['result'] as $result )
        {
            $this->assertInternalType( 'array', $result );
            $this->assertArrayHasKey( 'keyword', $result );
            $this->assertArrayHasKey( 'link_object', $result );
            $this->assertInstanceOf( 'eZContentObjectTreeNode', $result['link_object'] );
        }
        
        // Fetch keyword1 for class 1, directly below parentNodeId (rootNode)
        $keywords = eZContentFunctionCollection::fetchKeyword(
            'keyword1', $class1ID, 0, 20, false, array(), 2 );
        $this->assertInternalType( 'array', $keywords );
        $this->assertArrayHasKey( 'result', $keywords );
        $this->assertInternalType( 'array', $keywords['result'] );
        $this->assertEquals( 1, count( $keywords['result'] ) );
        foreach ( $keywords['result'] as $result )
        {
            $this->assertInternalType( 'array', $result );
            $this->assertArrayHasKey( 'keyword', $result );
            $this->assertArrayHasKey( 'link_object', $result );
            $this->assertInstanceOf( 'eZContentObjectTreeNode', $result['link_object'] );
        }
        
        // Fetch keywords for class 1, directly below parentNodeId (specific folder)
        $keywords = eZContentFunctionCollection::fetchKeyword(
            'keyword1', $class1ID, 0, 20, false, array(), $folder1->mainNode->node_id );
        $this->assertInternalType( 'array', $keywords );
        $this->assertArrayHasKey( 'result', $keywords );
        $this->assertInternalType( 'array', $keywords['result'] );
        $this->assertEquals( 1, count( $keywords['result'] ) );
        foreach ( $keywords['result'] as $result )
        {
            $this->assertInternalType( 'array', $result );
            $this->assertArrayHasKey( 'keyword', $result );
            $this->assertArrayHasKey( 'link_object', $result );
            $this->assertInstanceOf( 'eZContentObjectTreeNode', $result['link_object'] );
        }
        
        // Fetch keywords for class 1, from root folder, with depth equal to 2
        $keywords = eZContentFunctionCollection::fetchKeyword(
            'keyword1', $class1ID, 0, 20, false, array(), 2, true, false, 2 );
        $this->assertInternalType( 'array', $keywords );
        $this->assertArrayHasKey( 'result', $keywords );
        $this->assertInternalType( 'array', $keywords['result'] );
        $this->assertEquals( 3, count( $keywords['result'] ) );
        foreach ( $keywords['result'] as $result )
        {
            $this->assertInternalType( 'array', $result );
            $this->assertArrayHasKey( 'keyword', $result );
            $this->assertArrayHasKey( 'link_object', $result );
            $this->assertInstanceOf( 'eZContentObjectTreeNode', $result['link_object'] );
        }

        // Fetch keywords for class 1, from sepecific node, with depth equal to 2
        $keywords = eZContentFunctionCollection::fetchKeyword(
            'keyword1', $class1ID, 0, 20, false, array(), $folder1->mainNode->node_id, true, false, 2 );
        $this->assertInternalType( 'array', $keywords );
        $this->assertArrayHasKey( 'result', $keywords );
        $this->assertInternalType( 'array', $keywords['result'] );
        $this->assertEquals( 2, count( $keywords['result'] ) );
        foreach ( $keywords['result'] as $result )
        {
            $this->assertInternalType( 'array', $result );
            $this->assertArrayHasKey( 'keyword', $result );
            $this->assertArrayHasKey( 'link_object', $result );
            $this->assertInstanceOf( 'eZContentObjectTreeNode', $result['link_object'] );
        }
        
        // Fetch keywords for class 1, from root folder, with depth equal to 0 (unlimited)
        $keywords = eZContentFunctionCollection::fetchKeyword(
            'keyword1', $class1ID, 0, 20, false, array(), 2, true, false, 0 );
        $this->assertInternalType( 'array', $keywords );
        $this->assertArrayHasKey( 'result', $keywords );
        $this->assertInternalType( 'array', $keywords['result'] );
        $this->assertEquals( 4, count( $keywords['result'] ) );
        foreach ( $keywords['result'] as $result )
        {
            $this->assertInternalType( 'array', $result );
            $this->assertArrayHasKey( 'keyword', $result );
            $this->assertArrayHasKey( 'link_object', $result );
            $this->assertInstanceOf( 'eZContentObjectTreeNode', $result['link_object'] );
        }

        // Fetch keywords for class 1, from specific folder, with depth equal to 0 (unlimited)
        $keywords = eZContentFunctionCollection::fetchKeyword(
            'keyword1', $class1ID, 0, 20, false, array(), $folder1->mainNode->node_id, true, false, 0 );
        $this->assertInternalType( 'array', $keywords );
        $this->assertArrayHasKey( 'result', $keywords );
        $this->assertInternalType( 'array', $keywords['result'] );
        $this->assertEquals( 2, count( $keywords['result'] ) );
        foreach ( $keywords['result'] as $result )
        {
            $this->assertInternalType( 'array', $result );
            $this->assertArrayHasKey( 'keyword', $result );
            $this->assertArrayHasKey( 'link_object', $result );
            $this->assertInstanceOf( 'eZContentObjectTreeNode', $result['link_object'] );
        }
    }
}

?>
