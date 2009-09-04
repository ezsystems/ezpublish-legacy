<?php
/**
 * File containing the eZContentObjectTest class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZContentObjectTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZContentObject Unit Tests" );
    }

    /**
     * Unit test for eZContentObject::fetchIDArray()
     *
     * Outline:
     * 1) Create more than 1000 objects
     * 2) Call fetchIDArray() on these object's ids
     * 3) Check that they were all returned
     **/
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
     **/
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
            "Overall relation count should be 10" );

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
    }
}

?>