<?php
/**
 * File containing the eZContentClassTest class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZContentClassTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    /*public function __construct()
    {
        parent::__construct();
        $this->setName( "eZContentClass Unit Tests" );
    }*/
    
    public function setUp()
    {
        parent::setUp();
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }
    
    /**
     * Unit test for eZContentClass::classIDByIdentifier()
     * @dataProvider providerForTestClassIDByIdentifier
     */
    public function testClassIDByIdentifier( $sourceArray, $expectedArray )
    {
        $transformedClassArray = eZContentClass::classIDByIdentifier( $sourceArray );
        $this->assertEquals( $expectedArray, $transformedClassArray );
    }
    
    /**
     * Data provider for self::testClassIDByIdentifier()
     * @see testClassIDByIdentifier()
     */
    public static function providerForTestClassIDByIdentifier()
    {
        return array(
            array( array( 1, 'article', 'image', 12, 13 ), array( 1, 2, 5, 12, 13 ) ),
            array( array( 'article', 'image', 'comment' ), array( 2, 5, 13 ) ),
        );
    }
}

?>