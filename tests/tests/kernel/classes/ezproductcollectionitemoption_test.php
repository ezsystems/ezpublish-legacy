<?php
/**
 * File containing the eZProductCollectionItemOptionTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZProductCollectionItemOptionTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZProductCollectionItemOption Unit Tests" );
    }

    /**
    * Unit test for eZProductCollectionItem::cleanupList()
    *
    * Outline:
    * 1) Create 40 eZProductCollectionItemOption objects with an item_id
    *    from 1 to 4
    * 2) Call cleanupList with (1, 2) as a parameter
    * 4) Check that the 20 matching items have been removed
    * 5) Check that the 20 other, non-matching items haven't been removed
    **/
    public function testCleanupList()
    {
        // Create a few collections
        $row = array(
            'item_id' => null,
            'option_item_id' => 1,
            'object_attribute_id' => 1,
            'name' => __FUNCTION__,
            'value' => __FUNCTION__,
            'price' => 5.5 );

        $deleteIDArray = $keepIDArray = array();
        for( $i = 1; $i <= 40; $i++ )
        {
            $row['item_id'] = ceil( $i / 10 );
            $item = new eZProductCollectionItemOption( $row );
            $item->store();
        }

        eZProductCollectionItemOption::cleanupList( array( 1, 2 ) );

        // Check that each item of $deleteIDArray has been removed
        foreach( array( 1, 2 ) as $itemID )
        {
            $options = eZProductCollectionItemOption::fetchList( $itemID );
            $this->assertEquals( 0, count( $options ) );
        }

        // And check that each item of $keepIDArray hasn't been deleted
        foreach( array( 3, 4 ) as $itemID )
        {
            $options = eZProductCollectionItemOption::fetchList( $itemID );
            $this->assertEquals( 10, count( $options ) );
            foreach( $options as $option )
            {
                $this->assertType( 'eZProductCollectionItemOption', $option );
            }
        }
    }
}
?>