<?php
/**
 * File containing the eZProductCollectionTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZProductCollectionTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    /**
    * Unit test for eZProductCollection::cleanupList()
    *
    * Outline:
    * 1) Create 10 eZProductCollection objects
    * 2) Pick 5 random ID of created items
    * 3) Call cleanupList() on these 5 items
    * 4) Check that the 5 items have been removed
    * 5) Check that the 5 other items haven't been removed
    **/
    public function testCleanupList()
    {
        // Create a few collections
        $row = array( 'created' => time(), 'currency_code' => 'EUR' );
        $collection = new eZProductCollection( $row );
        $collection->store();

        $collectionIDArray[] = $collection->attribute( 'id' );

        for( $i = 0; $i < 1500; $i++ )
        {
            $newCollection = $collection->copy();
            $collectionIDArray[] = $newCollection->attribute( 'id' );
        }

        // pick a few random ID to delete
        $deleteIDArray = array_rand( $collectionIDArray, round( count( $collectionIDArray ) / 2 ) );
        $remainingIDArray = array_diff( $collectionIDArray, $deleteIDArray );

        eZProductCollection::cleanupList( $deleteIDArray );

        // Check that each item of deleteIDArray has been removed
        foreach( $deleteIDArray as $id )
        {
            $this->assertNull( eZProductCollection::fetch( $id ) );
        }

        // And check that each item of remainingIDArray hasn't been deleted
        foreach( $remainingIDArray as $id )
        {
            $this->assertType( 'eZProductCollection', eZProductCollection::fetch( $id ) );
        }
    }
}
?>