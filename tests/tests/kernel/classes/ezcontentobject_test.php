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
        $this->setName( "eZContentObject Regression Tests" );
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
}

?>