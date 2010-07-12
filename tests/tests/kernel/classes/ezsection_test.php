<?php
/**
 * File containing the eZSection class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

/**
 * Test case for eZSection class
 */
class eZSectionTest extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZSession Tests" );
    }

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * test fetchByIdentifier function
     */
    public function testFetchByIdentifier()
    {
        global $eZContentSectionObjectCache;

        $section = new eZSection( array() );
        $section->setAttribute( 'name', 'Test Section' );
        $section->setAttribute( 'section_identifier', 'test_section' );
        $section->store();

        $sectionID = $section->attribute( 'id' );

        // assert that if the cache is set after fetching
        $section2 = eZSection::fetchByIdentifier( 'test_section' );
        $this->assertEquals( $sectionID, $section2->attribute( 'id' ) );

        // assert that object is cached
        $this->assertNotNull( $eZContentSectionObjectCache['test_section'] );
        $this->assertNotNull( $eZContentSectionObjectCache[$sectionID] );

        // assert that the two object refer to same object
        $this->assertSame( $eZContentSectionObjectCache[$sectionID] , $section2 );
        $this->assertSame( eZSection::fetch( $sectionID ) , $section2 );
    }

}