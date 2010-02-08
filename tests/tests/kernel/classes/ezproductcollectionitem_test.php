<?php
/**
 * File containing the eZProductCollectionItemTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZProductCollectionItemTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZProductCollectionItem Unit Tests" );
    }

    /**
    * Unit test for eZProductCollectionItem::cleanupList()
    *
    * Outline:
    * 1) Create 40 eZProductCollectionItem objects with a product_collection_id
    *    from 1 to 4
    * 2) Call cleanupList with (1, 2) as a parameter
    * 4) Check that the 20 matching items have been removed
    * 5) Check that the 20 other, non-matching items haven't been removed
    **/
    public function testCleanupList()
    {
        // Create a few collections
        $row = array(
            'product_collection_id' => 0,
            'contentobject_id' => 1,
            'item_count' => 1,
            'price' => 50,
            'is_vat_inc' => 1,
            'vat_value'=> 19.6,
            'discount' => 0,
            'name' => __FUNCTION__ );

        $deleteIDArray = $keepIDArray = array();
        for( $i = 1; $i < 40; $i++ )
        {
            $row['productcollection_id'] = ceil( $i / 10 );
            $row['name'] = __FUNCTION__ . ":" . $row['productcollection_id'] . "-$i";
            $item = new eZProductCollectionItem( $row );
            $item->store();
            if ( $row['productcollection_id'] <= 2 )
            {
                $deleteIDArray[] = $item->attribute( 'id' );
            }
            else
            {
                $keepIDArray[] = $item->attribute( 'id' );
            }
        }

        eZProductCollectionItem::cleanupList( array( 1, 2 ) );

        // Check that each item of deleteIDArray has been removed
        foreach( $deleteIDArray as $id )
        {
            $this->assertNull( eZProductCollectionItem::fetch( $id ) );
        }

        // And check that each item of remainingIDArray hasn't been deleted
        foreach( $keepIDArray as $id )
        {
            $this->assertType( 'eZProductCollectionItem', eZProductCollectionItem::fetch( $id ) );
        }
    }
}
?>