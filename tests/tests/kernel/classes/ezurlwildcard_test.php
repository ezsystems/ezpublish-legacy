<?php
/**
 * File containing the eZURLWildcardTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

/**
 * @group urlWildcard
 */
class eZURLWildcardTest extends ezpDatabaseTestCase
{
    /**
     * Array with following keys :
     *  - source_url
     *  - destination_url
     *  - type
     * @var array
     */
    private $wildcardRow;

    /**
     * @var eZURLWildcard[]
     */
    private $wildcards;

    /**
     * @var eZURLWildcard[]
     */
    private $wildcardObjects;

    /**
     * Test case setup
     * Changes INI settings that affect eZURLWildcard to known and predictable
     * values
     */
    public function setUp()
    {
        parent::setUp();

        $this->wildcardRow[0] = array(
            'source_url' => 'test/*',
            'destination_url' => '/',
            'type' => eZURLWildcard::TYPE_DIRECT,
        );

        $this->wildcards[0] = new eZURLWildcard( $this->wildcardRow[0] );

        $this->generatedWildcards = 5;

        // Creating some wildcards
        for ( $i = 0; $i < $this->generatedWildcards; ++$i )
        {
            $source = "test" . ( $i % 2 ? 'Odd' : 'Pair' ) . "/$i/*";
            $this->wildcardObjects[$source] = self::createWildcard( $source, '/', eZURLWildcard::TYPE_DIRECT );
        }

        // Creating some wildcards which will be used for translate tests
        $this->wildcardObjects["foobar/*/*"] = self::createWildcard( "foobar/*/*", '/', eZURLWildcard::TYPE_DIRECT );
        $this->wildcardObjects["testTranslate1/*/*"] = self::createWildcard( "testTranslate1/*/*", 'foobar/{1}/{2}', eZURLWildcard::TYPE_DIRECT );
        $this->wildcardObjects["testTranslate2/*/abc"] = self::createWildcard( "testTranslate2/*/abc", 'foobar/{1}', eZURLWildcard::TYPE_FORWARD );
        $this->wildcardObjects["test/single-page"] = self::createWildcard( "test/single-page", 'foo/bar', eZURLWildcard::TYPE_FORWARD );
        $this->wildcardObjects["is/it/working/*"] = self::createWildcard( "is/it/working/*", 'yes/no/maybe', eZURLWildcard::TYPE_FORWARD );
        eZURLWildcard::expireCache();
    }

    /**
     * Test case teardown
     * Restores the INI settings changed in setUp
     */
    public function tearDown()
    {
        eZDB::instance()->query( 'DELETE FROM ezurlwildcard' );
        eZURLWildcard::expireCache();
        clearstatcache();
        sleep( 1 );

        parent::tearDown();
    }

    /**
     * Tests eZURLWildcard constructor:
     * Checks that instanciated values are consistently read from the object
     */
    public function testConstructor()
    {
        $wildcard = new eZURLWildcard( $this->wildcardRow[0] );
        $this->assertSame( 'test/*', $wildcard->attribute( 'source_url' ), "Source URL doesn't match" );
        $this->assertSame( '/', $wildcard->attribute( 'destination_url' ), "Destination URL doens't match" );
        $this->assertEquals( eZURLWildcard::TYPE_DIRECT, $wildcard->attribute( 'type' ), "Type doesn't match" );
        $this->assertSame( null, $wildcard->attribute( 'id' ), "ID should be NULL" );
    }

    /**
     * Tests the storage operation of a new wildcard
     * Checks that submitted data and stored data are identical, and that a
     * numerical ID has been assigned
     */
    public function testStore()
    {
        $wildcard = $this->wildcards[0];
        $wildcard->store();

        $this->assertSame( 'test/*', $wildcard->attribute( 'source_url' ), "Source URL doesn't match" );
        $this->assertSame( '/', $wildcard->attribute( 'destination_url' ), "Destination URL doens't match" );
        $this->assertEquals( eZURLWildcard::TYPE_DIRECT, $wildcard->attribute( 'type' ), "Type doesn't match" );
        $this->assertInternalType( 'int', $wildcard->attribute( 'id' ), "ID is not an integer" );
    }

    /**
     * Tests the asArray method
     * Checks that an array is returned, and that content matches the object's
     */
    public function testAsArray()
    {
        $array = $this->wildcards[0]->asArray();

        $this->assertInternalType( 'array', $array );
        $this->assertSame( 'test/*', $array['source_url'] );
        $this->assertSame( '/', $array['destination_url'] );
        $this->assertEquals( eZURLWildcard::TYPE_DIRECT, $array['type'] );
        $this->assertSame( null, $array['id'] );
    }

    /**
     * Tests the fetchList method
     *
     * Outline:
     * 1. Fetch the list
     * 2. Check that all returned wildcards are part of this list
     *
     * @todo Also test fetchList with an offset/limit
     */
    public function testFetchList()
    {
        // 1. Fetch the list
        $fetchedWildcards = eZURLWildcard::fetchList();

        // 2. Check if every fetched wildcard is in the creation list, and the opposite
        $this->assertSame( count( $this->wildcardObjects ), count( $fetchedWildcards ), "Mismatch between created and fetched wildcards count" );
        foreach ( $fetchedWildcards as $fetchedWildcard )
        {
            $wildcard = $this->wildcardObjects[$fetchedWildcard->attribute( 'source_url' )];
            $this->assertEquals( $wildcard->attribute( 'source_url' ), $fetchedWildcard->attribute( 'source_url' ) );
            $this->assertEquals( $wildcard->attribute( 'destination_url' ),  $fetchedWildcard->attribute( 'destination_url' ) );
            $this->assertEquals( $wildcard->attribute( 'type' ), $fetchedWildcard->attribute( 'type' ) );
            $this->assertEquals( $wildcard->attribute( 'id' ), $fetchedWildcard->attribute( 'id' ) );
        }
    }

    /**
     * Test for the fetch method
     */
    public function testFetch()
    {
        $fetchedWildcard = eZURLWildcard::fetch( $id = $this->wildcardObjects['testPair/0/*']->attribute( 'id' ) );

        $this->assertTrue( is_object( $fetchedWildcard ), "Failed fetching the wildcard object by ID" );
        $this->assertSame( 'testPair/0/*', $fetchedWildcard->attribute( 'source_url' ) );
        $this->assertEquals( '/', $fetchedWildcard->attribute( 'destination_url' ) );
        $this->assertEquals( eZURLWildcard::TYPE_DIRECT, $fetchedWildcard->attribute( 'type' ) );
        $this->assertEquals( $id, $fetchedWildcard->attribute( 'id' ) );
    }

    /**
     * Tests the cleanup method
     *
     * @depends testFetch
     */
    public function testCleanup()
    {
        eZURLWildcard::cleanup( 'testPair/0' );

        // Test if matching wildcards were removed, and non-matching ones were kept
        $this->assertNull( eZURLWildcard::fetch( $this->wildcardObjects['testPair/0/*']->attribute( 'id' ) ), "Matching wildcard still exists" );
        $this->assertTrue( is_object( eZURLWildcard::fetch( $this->wildcardObjects['testOdd/1/*']->attribute( 'id' ) ) ), "Non matching wildcard was removed" );
    }

    /**
     * Tests the removeByIDs method
     *
     * @depends testFetch
     */
    public function testRemoveByIDs()
    {
        // Removing odd wildcards
        for ( $i = 0; $i < $this->generatedWildcards; ++$i )
        {
            if ( $i % 2 )
            {
                $wildcardsToRemove[] = $this->wildcardObjects["testOdd/$i/*"]->attribute( 'id' );
            }
        }
        eZURLWildcard::removeByIDs( $wildcardsToRemove );

        // Check if the removed wildcards were removed, and the kept ones were kept
        for ( $i = 0; $i < $this->generatedWildcards; ++$i )
        {
            if ( $i % 2 )
            {
                $this->assertNull( eZURLWildcard::fetch( $this->wildcardObjects["testOdd/$i/*"]->attribute( 'id' ) ), "A removed wildcard entry still exists" );
            }
            else
            {
                $this->assertTrue( is_object( eZURLWildcard::fetch( $this->wildcardObjects["testPair/$i/*"]->attribute( 'id' ) ) ), "A kept wildcard entry no longer exists" );
            }
        }
    }

    /**
     * Test for the fetchBySourceURL method returning an object
     */
    public function testFetchBySourceURLAsObject()
    {
        $wildcard = eZURLWildcard::fetchBySourceURL( 'testPair/0/*' );

        $this->assertEquals( $this->wildcardObjects['testPair/0/*'], $wildcard );
    }

    /**
     * Test for the fetchBySourceURL method returning an array
     */
    public function testFetchBySourceURLAsArray()
    {
        $fetchedWildcard = eZURLWildcard::fetchBySourceURL( 'testPair/0/*', false );

        $this->assertInternalType( 'array', $fetchedWildcard, "Failed fetching the wildcard object as an array" );
        $this->assertSame( 'testPair/0/*', $fetchedWildcard['source_url'] );
        $this->assertSame( '/', $fetchedWildcard['destination_url'] );
        $this->assertEquals( eZURLWildcard::TYPE_DIRECT, $fetchedWildcard['type'] );
    }

    /**
     * Tests the fetchListCount method
     */
    public function testFetchListCount()
    {
        $this->assertEquals( count( $this->wildcardObjects ), eZURLWildcard::fetchListCount(),
            "The return value from fetchListCount doesn't match the number of created wildcards" );
    }

    /**
     * Tests the removeAll method
     *
     * @depends testFetchListCount
     */
    public function testRemoveAll()
    {
        eZURLWildcard::removeAll();

        $this->assertEquals( 0, $count = eZURLWildcard::fetchListCount(), $count . " wildcards still exist in the database" );
    }

    /**
     * Test for the translate method using a direct type
     */
    public function testTranslateDirect()
    {
        $uri = eZURI::instance( 'testTranslate1/foo/bar' );
        $this->assertTrue( eZURLWildcard::translate( $uri ) );
        $this->assertEquals( 'content/view/full/2', $uri->URI );
    }

    /**
     * Test for the translate method using a forward type
     */
    public function testTranslateForward()
    {
        // Test a FORWARD wildcard:
        //  - the return should contain the forward URL
        //  - the return value should be the transformed URI
        $uri = eZURI::instance( 'testTranslate2/xyz/abc' );
        $this->assertEquals( 'foobar/xyz', eZURLWildcard::translate( $uri ) );
        $this->assertEquals( 'error/301', $uri->URI );
    }

    /**
     * Test for the translate method using an unknown URI
     */
    public function testTranslateUnknownURI()
    {
        $uri = eZURI::instance( 'unknownURI/foobar' );
        $this->assertFalse( eZURLWildcard::translate( $uri ) );
        $this->assertEquals( 'unknownURI/foobar', $uri->URI );
    }

    /**
     * Tests an identified error until eZ Publish 4.2:
     * a cascaded translation (uri => translation1 => translation2) will fail
     * with a fatal error if the two matching wildcards are located in different
     * cache files
     *
     * Test outline:
     * 1. Create a wildcard of DIRECT type that translates A to B
     * 2. Create more than 100 dummy wildcards
     * 3. Create a wildcard of DIRECT type that translates B to C
     *
     * Expected: the test fails at #3 with a fatal error "Cannot redeclare ezurlwilcardcachedtranslate"
     */
    public function testDoubleTranslation()
    {
        self::createWildcard( "testDoubleTranslation1/*", 'testDoubleTranslation2/{1}', eZURLWildcard::TYPE_DIRECT );
        // create more than 100 wildcards
        for ( $i = 0; $i <= 100; $i++ )
        {
            self::createWildcard( "testDoubleTranslationDummy{$i}/*", '/', eZURLWildcard::TYPE_DIRECT );
        }
        self::createWildcard( "testDoubleTranslation2/*", 'testDoubleTranslation3/{1}', eZURLWildcard::TYPE_DIRECT );

        $uri = eZURI::instance( 'testDoubleTranslation1/foobar' );

        // will fail
        $this->assertFalse( eZURLWildcard::translate( $uri ) );
    }

    /**
     * Helper method that creates a wildcard
     *
     * @param string $sourceURL
     * @param string $destinationURL
     * @param int $type
     * @return eZURLWildcard the created wildcard persistent object
     */
    static public function createWildcard( $sourceURL, $destinationURL, $type )
    {
        $row = array( 'source_url' => $sourceURL,
                      'destination_url' => $destinationURL,
                      'type' => $type );
        $wildcard = new eZURLWildcard( $row );
        $wildcard->store();

        return $wildcard;
    }

    /**
     * @covers eZURLWildcard::wildcardExists
     */
    public function testWildcardExists()
    {
        self::assertFalse( eZURLWildcard::wildcardExists( __FUNCTION__ . md5( microtime() ) ) );
        self::assertTrue(
            eZURLWildcard::wildcardExists(
                $this->wildcardObjects['test/single-page']->attribute( 'source_url' )
            )
        );
        self::assertTrue(
            eZURLWildcard::wildcardExists(
                $this->wildcardObjects['is/it/working/*']->attribute( 'source_url' )
            )
        );
        self::assertFalse(
            eZURLWildcard::wildcardExists( 'is/it/' )
        );
        self::assertTrue(
            eZURLWildcard::wildcardExists( 'is/it/working/now' )
        );
        self::assertTrue(
            eZURLWildcard::wildcardExists( 'is/it/working/now/yes' )
        );

    }
}
?>
