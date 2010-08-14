<?php
/**
 * File containing the eZSiteData class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @author Jerome Vieilledent 
 * @package tests
 */

class eZSiteDataTest extends ezpDatabaseTestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }
    
    /**
     * Unit test for eZPersistentObject implementation
     */
    public function testPersistentObjectInterface()
    {
        $this->assertTrue( is_subclass_of( 'eZSiteData', 'eZPersistentObject' ) );
        $this->assertTrue( method_exists( 'eZSiteData', 'definition' ) );
    }
    
    /**
     * Unit test for good eZPersistentObject (ORM) implementation for ezsite_data table
     */
    public function testORMImplementation()
    {
        $def = eZSiteData::definition();
        $this->assertEquals( 'eZSiteData', $def['class_name'] );
        $this->assertEquals( 'ezsite_data', $def['name'] );
        
        $fields = $def['fields'];
        $this->assertArrayHasKey( 'name', $fields );
        $this->assertArrayHasKey( 'value', $fields );
    }
    
    /**
     * Unit test for fetchByName() method
     */
    public function testFetchByName()
    {
        $name = 'foo';
        $row = array(
            'name'      => $name,
            'value'     => 'bar'
        );
        
        $obj = new eZSiteData( $row );
        $obj->store();
        unset( $obj );
        
        $res = eZSiteData::fetchByName( $name );
        $this->assertType( 'eZSiteData', $res );
        
        $res->remove();
    }
}

?>
