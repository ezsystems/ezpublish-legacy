<?php
/**
 * File containing the eZContentObjectTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */
class eZContentObjectTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct( $name = NULL, array $data = array(), $dataName = '' )
    {
        parent::__construct( $name, $data, $dataName );
        $this->setName( "eZContentObject Unit Tests" );
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        eZContentObject::clearCache();
        parent::tearDown();
    }

    /**
     * Unit test for eZContentObject::fetchIDArray()
     *
     * Outline:
     * 1) Create more than 1000 objects
     * 2) Call fetchIDArray() on these object's ids
     * 3) Check that they were all returned
     */
    public function testFetchIDArray()
    {
        $contentObjectIDArray = array();

        $object = new ezpObject( "article", 2 );
        $object->title = __FUNCTION__;
        $object->publish();
        $contentObjectIDArray[] = $object->attribute( 'id' );

        for( $i = 0; $i < 20; $i++ )
        {
            $newObject = $object->copy();
            $contentObjectIDArray[] = $newObject->attribute( 'id' );
        }

        $contentObjects = eZContentObject::fetchIDArray( $contentObjectIDArray );

        // we will store the objects we find in this array
        $matchedContentObjectsIDArray = array();
        foreach( $contentObjectIDArray as $contentObjectID )
        {
            $this->assertArrayHasKey( $contentObjectID, $contentObjects,
                "eZContentObject #{$contentObjectID} should be a key from the return value" );
            $this->assertType( 'eZContentObject', $contentObjects[$contentObjectID],
                "Key for eZContentObject #{$contentObjectID} isn't an eZContentObject" );
            $this->assertEquals( $contentObjectID, $contentObjects[$contentObjectID]->attribute( 'id' ),
                "eZContentObject's id for key #{$contentObjectID} doesn't match" );
            $matchedContentObjectsIDArray[] = $contentObjectID;
        }

        // Check that all the objects from the original array were returned
        $this->assertTrue( count( array_diff( $matchedContentObjectsIDArray, $contentObjectIDArray ) ) == 0,
            "Not all objects from original ID array were returned" );
    }

    /**
     * Unit test for eZContentObject::relatedObjectCount()
     *
     * Outline:
     * 1) Create a content class with ezobjectrelation and ezobjectrelationlist
     *    attributes
     * 2) Create objects and relate them to each of these attributes and to the
     *    object itself (common)
     * 3) Check that attribute count is correct on each attribute and globally
     */
    public function testRelatedObjectCount()
    {
        // Create a test content class
        $class = new ezpClass( __FUNCTION__, __FUNCTION__, 'name' );
        $class->add( 'Name', 'name', 'ezstring' );
        $attributes['single_relation_1'] =
            $class->add( 'Single relation #1', 'single_relation_1', 'ezobjectrelation' )
            ->attribute( 'id' );
        $attributes['single_relation_2'] =
            $class->add( 'Single relation #2', 'single_relation_2', 'ezobjectrelation' )
            ->attribute( 'id' );
        $attributes['multiple_relations_1'] =
            $class->add( 'Multiple relations #1', 'multiple_relations_1', 'ezobjectrelationlist' )
            ->attribute( 'id' );
        $attributes['multiple_relations_2'] =
            $class->add( 'Multiple relations #2', 'multiple_relations_2', 'ezobjectrelationlist' )
            ->attribute( 'id' );
        $class->store();

        // create a few articles we will relate our object to
        $relatedObjects = array();
        for( $i = 0; $i < 10; $i++ )
        {
            $article = new ezpObject( 'article', 2 );
            $article->title = "Related object #'$i for " . __FUNCTION__;
            $article->publish();
            $relatedObjects[] = $article->attribute( 'id' );
        }

        // Create a test object with various relations (some objects are related
        // to multiple attributes in order to test reverse relations):
        // - 1 relation (IDX 0) on single_relation_1
        // - 1 relation (IDX 1) on single_relation_2
        // - 2 relations (IDX 2, 3) on multiple_relations_1
        // - 2 relations (IDX 4, 5) on multiple_relations_2
        // - 6 object level relations ((IDX 6, 7, 8, 9)
        $object = new ezpObject( __FUNCTION__, 2 );
        $object->name = __FUNCTION__;
        $object->single_relation_1 = $relatedObjects[0];
        $object->single_relation_2 = $relatedObjects[1];
        $object->multiple_relations_1 = array( $relatedObjects[0], $relatedObjects[2], $relatedObjects[3] );
        $object->multiple_relations_2 = array( $relatedObjects[1], $relatedObjects[4], $relatedObjects[5] );
        $object->addContentObjectRelation( $relatedObjects[0] );
        $object->addContentObjectRelation( $relatedObjects[1] );
        $object->addContentObjectRelation( $relatedObjects[6] );
        $object->addContentObjectRelation( $relatedObjects[7] );
        $object->addContentObjectRelation( $relatedObjects[8] );
        $object->addContentObjectRelation( $relatedObjects[9] );
        $object->publish();

        // Create 2 more objects with relations to $relatedObjects[9]
        // in order to test reverse related objects
        $otherObject1 = new ezpObject( __FUNCTION__, 2 );
        $otherObject1->name = "Reverse test object #1 for " . __FUNCTION__;
        $otherObject1->single_relation_1 = $relatedObjects[9];
        $otherObject1->publish();

        $otherObject2 = new ezpObject( __FUNCTION__, 2 );
        $otherObject2->name = "Reverse test object #2 for " . __FUNCTION__;
        $otherObject2->single_relation_2 = $relatedObjects[9];
        $otherObject2->publish();

        $contentObject = eZContentObject::fetch( $object->attribute( 'id' ) );

        $paramAllRelations = array( 'AllRelations' => true );
        $paramAttributeRelations = array( 'AllRelations' => eZContentObject::RELATION_ATTRIBUTE );
        $paramCommonRelations = array( 'AllRelations' => eZContentObject::RELATION_COMMON );

        // Test overall relation count
        $this->assertEquals(
            14, $contentObject->relatedObjectCount( false, false, false, $paramAllRelations ),
            "Overall relation count should be 14" );

        // Test relation count for each attribute
        $this->assertEquals(
            1, $contentObject->relatedObjectCount( false, $attributes['single_relation_1'], false, $paramAttributeRelations ),
            "Relation count on attribute single_relation_1 should be 1" );
        $this->assertEquals(
            1, $contentObject->relatedObjectCount( false, $attributes['single_relation_2'], false, $paramAttributeRelations ),
            "Relation count on attribute single_relation_2 should be 1" );
        $this->assertEquals(
            3, $contentObject->relatedObjectCount( false, $attributes['multiple_relations_1'], false, $paramAttributeRelations ),
            "Relation count on attribute multiple_relations_1 should be 3" );
        $this->assertEquals(
            3, $contentObject->relatedObjectCount( false, $attributes['multiple_relations_2'], false, $paramAttributeRelations ),
            "Relation count on attribute multiple_relations_2 should be 3" );

        // Test common level relation count
        $this->assertEquals(
            6, $contentObject->relatedObjectCount( false, false, false, $paramCommonRelations ),
            "Common relations count should be 6" );

        // Test reverse relation count on $relatedObject[9]
        // This object is related to:
        // - the main $object on common level
        // - one object on single_relation_1
        // - another object on single_relation_2
        $relatedContentObject = eZContentObject::fetch( $relatedObjects[9] );
        $this->assertEquals(
            3, $relatedContentObject->relatedObjectCount( false, false, true, $paramAllRelations ),
            "Overall reverse relation count should be 3" );
        $this->assertEquals(
            1, $relatedContentObject->relatedObjectCount( false, false, true, $paramCommonRelations ),
            "Common reverse relation count should be 1" );
        $this->assertEquals(
            1, $relatedContentObject->relatedObjectCount( false, $attributes['single_relation_1'], true, $paramAttributeRelations ),
            "Attribute reverse relation count on single_relation_1 should be 1" );
        $this->assertEquals(
            1, $relatedContentObject->relatedObjectCount( false, $attributes['single_relation_2'], true, $paramAttributeRelations ),
            "Attribute reverse relation count on single_relation_2 should be 1" );

        // Test that trashed objects are not counted as related (issue #15142)
        $trashObject = eZContentObject::fetch( $relatedObjects[9] );
        $trashObject->removeThis();
        $this->assertEquals(
            13, $contentObject->relatedObjectCount( false, false, false, $paramAllRelations ),
            "Relation count after move to trash should be 13" );

        // Empty the trash
        /*foreach( eZContentObjectTrashNode::trashList() as $node )
        {
            eZContentObjectTrashNode::purgeForObject( $node->attribute( 'contentobject_id' ) );
        }*/
    }

    /**
     * Unit test for {@link eZContentObject::fetchByNodeID()}
     */
    public function testFetchByNodeIDAsObject()
    {
        // First test with only one nodeID, as object
        $fetchedObject = eZContentObject::fetchByNodeID( 2 );
        $this->assertType( 'eZContentObject', $fetchedObject, 'eZContentObject::fetchByNodeID() must return an eZContentObject instance if only 1 nodeID is passed as param' );
        $this->assertEquals( 2, $fetchedObject->attribute( 'main_node_id' ) );
    }

    /**
     * Unit test for {@link eZContentObject::fetchByNodeID()}
     */
    public function testFetchByNodeIDAsRow()
    {
        $fetchedObject = eZContentObject::fetchByNodeID( 2, false );
        $def = eZContentObject::definition();
        self::assertType( PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY , $fetchedObject, "eZContentObject::fetchByNodeID() with \$asObject=false must return an array" );
        foreach ( $def['fields'] as $key => $fieldDef )
        {
            self::assertArrayHasKey( $key, $fetchedObject, "eZContentObject::fetchByNodeID() with \$asObject=false must return an array with '$key' key" );
        }
        $this->assertEquals( 'eZ Publish', $fetchedObject['name'] );
    }

    /**
     * Unit test for {@link eZContentObject::fetchByNodeID()}
     */
    public function testFetchByNodeIDNonExistentAsObject()
    {
        self::assertNull( eZContentObject::fetchByNodeID( 0 ), 'eZContentObject::fetchByNodeID() must return NULL if no object can be found in Database' );
    }

    /**
     * Unit test for {@link eZContentObject::fetchByNodeID()}
     */
    public function testFetchByNodeIDNonExistentAsRow()
    {
        self::assertNull( eZContentObject::fetchByNodeID( 0, false ), 'eZContentObject::fetchByNodeID() must return NULL if no object can be found in Database' );
    }

    /**
     * Unit test for {@link eZContentObject::fetchByNodeID()}
     */
    public function testFetchByNodeIDArrayNonExistentAsObject()
    {
        self::assertSame( array(), eZContentObject::fetchByNodeID( array( 0 ) ), 'eZContentObject::fetchByNodeID() must return an empty array if no object can be found in Database while providing an array' );
    }

    /**
     * Unit test for {@link eZContentObject::fetchByNodeID()}
     */
    public function testFetchByNodeIDArrayNonExistentAsRow()
    {
        self::assertSame( array(), eZContentObject::fetchByNodeID( array( 0 ), false ), 'eZContentObject::fetchByNodeID() must return an empty array if no object can be found in Database while providing an array' );
    }

    /**
     * Unit test for {@link eZContentObject::fetchByNodeID()}
     *
     * @see http://issues.ez.no/17946
     * @group issue17946
     */
    public function testFetchByNodeIDArrayAsObject()
    {
        $fetchedObjects = eZContentObject::fetchByNodeID( array( 2 ) );
        self::assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY,
            $fetchedObjects,
            "eZContentObject::fetchByNodeID() must return an array of eZContentObject instances indexed by nodeIds if an array of nodeIds is passed as param"
        );

        self::assertType( 'eZContentObject', $fetchedObjects[2], "eZContentObject::fetchByNodeID() must return an array indexed by nodeIds of eZContentObject instances if an array of nodeIds is passed as param" );
    }

    /**
     * Unit test for {@link eZContentObject::fetchByNodeID()}
     *
     * @see http://issues.ez.no/17946
     * @group issue17946
     */
    public function testFetchByNodeIDArrayAsRow()
    {
        $fetchedObjects = eZContentObject::fetchByNodeID( array( 2 ), false );
        self::assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY,
            $fetchedObjects,
            "eZContentObject::fetchByNodeID() must return an array of eZContentObject instances if an array of nodeIds is passed as param"
        );

        self::assertType( PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $fetchedObjects[2], "eZContentObject::fetchByNodeID() must return an array indexed by nodeIds of eZContentObject array representation if an array of nodeIds is passed as param" );
    }
}

?>
