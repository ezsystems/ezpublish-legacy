<?php
/**
 * File containing the eZURLWildcardTest class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZURLWildcardTest extends ezpDatabaseTestCase
{
    /**
     * Test case setup
     * Changes INI settings that affect eZURLWildcard to known and predictable
     * values
     */
    public function setUp()
    {
        $ini = eZINI::instance( 'site.ini' );
        $this->iniBackup['implementationBackup'] = $ini->variable( 'DatabaseSettings', 'DatabaseImplementation' );
        $this->iniBackup['serverBackup'] = $ini->variable( 'DatabaseSettings', 'DatabaseImplementation' );
        $this->iniBackup['databaseBackup'] = $ini->variable( 'DatabaseSettings', 'DatabaseImplementation' );
        $this->iniBackup['varDirBackup'] = $ini->variable( 'FileSettings', 'VarDir' );
        $ini->setVariable( 'DatabaseSettings', 'DatabaseImplementation', 'ezmysqli' );
        $ini->setVariable( 'DatabaseSettings', 'Server', 'localhost' );
        $ini->setVariable( 'DatabaseSettings', 'Database', 'testdb' );
        $ini->setVariable( 'FileSettings', 'VarDir', 'var/test' );

        parent::setUp();
    }

    /**
    * Test case teardown
    * Restores the INI settings changed in setUp
    **/
    public function tearDown()
    {
        $ini = eZINI::instance( 'site.ini' );
        $ini->setVariable( 'DatabaseSettings', 'DatabaseImplementation', $this->iniBackup['implementationBackup'] );
        $ini->setVariable( 'DatabaseSettings', 'Server', $this->iniBackup['serverBackup'] );
        $ini->setVariable( 'DatabaseSettings', 'Database', $this->iniBackup['databaseBackup'] );
        $ini->setVariable( 'FileSettings', 'VarDir', $this->iniBackup['varDirBackup'] );

        parent::tearDown();
    }

    /**
    * Tests eZURLWildcard constructor:
    * Checks that instanciated values are consistently read from the object
    **/
    public function testConstructor()
    {
        $row = array( 'source_url' => $sourceURL = 'test/*',
                      'destination_url' => $destinationURL = '/',
                      'type' => $type = eZURLWildcard::TYPE_DIRECT );
        $wildcard = new eZURLWildcard( $row );
        $this->assertEquals( $sourceURL, $wildcard->attribute( 'source_url' ), "Source URL doesn't match" );
        $this->assertEquals( $destinationURL, $wildcard->attribute( 'destination_url' ), "Destination URL doens't match" );
        $this->assertEquals( $type, $wildcard->attribute( 'type' ), "Type doesn't match" );
        $this->assertEquals( null, $wildcard->attribute( 'id' ), "ID should be NULL" );
    }

    /**
    * Tests the storage operation of a new wildcard
    * Checks that submitted data and stored data are identical, and that a
    * numerical ID has been assigned
    **/
    public function testStore()
    {
        $row = array( 'source_url' => $sourceURL = 'testStore/*',
                      'destination_url' => $destinationURL = '/',
                      'type' => $type = eZURLWildcard::TYPE_DIRECT );
        $wildcard = new eZURLWildcard( $row );
        $wildcard->store();

        $this->assertEquals( $sourceURL, $wildcard->attribute( 'source_url' ), "Source URL doesn't match" );
        $this->assertEquals( $destinationURL, $wildcard->attribute( 'destination_url' ), "Destination URL doens't match" );
        $this->assertEquals( $type, $wildcard->attribute( 'type' ), "Type doesn't match" );
        $this->assertType( 'int', $wildcard->attribute( 'id' ), "ID is not an integer" );
    }

    /**
     * Tests the asArray method
     * Checks that an array is returned, and that content matches the object's
     *
     * @depends testStore
     */
    public function testAsArray()
    {
        $wildcard = self::createWildcard( 'testAsArray/*', '/', eZURLWildcard::TYPE_DIRECT );

        $array = $wildcard->asArray();

        $this->assertType( 'array', $array );
        $this->assertEquals( $wildcard->attribute( 'source_url'), $array['source_url'] );
        $this->assertEquals( $wildcard->attribute( 'destination_url'), $array['destination_url'] );
        $this->assertEquals( $wildcard->attribute( 'type'), $array['type'] );
        $this->assertEquals( $wildcard->attribute( 'id'), $array['id'] );
    }

    /**
     * Tests the cleanup method
     *
     * Outline:
     * 1. Create several wildcards, with various destination_url prefixes
     * 2. Call cleanup with a prefix that matches most of the previously created wildcards
     * 3. Check that the matching wildcards were removed and the non matching ones weren't
     *
     * @depends testFetch
     **/
    public function testCleanup()
    {
        // 1. Create a matching and non-matching wildcard entries
        $matchingWildcard    = self::createWildcard( "testCleanup/*", '/', eZURLWildcard::TYPE_DIRECT );
        $nonMatchingWildcard = self::createWildcard( "keepTestCleanup/*", '/', eZURLWildcard::TYPE_DIRECT );

        // 2. Remove the wildcard prefixed with 'testCleanup'
        eZURLWildcard::cleanup( 'testCleanup' );

        // 3. Test if matching wildcards were removed, and non-matching ones were kept
        $this->assertNull( eZURLWildcard::fetch( $matchingWildcard->attribute( 'id' ) ), "Matching wildcard still exists" );
        $this->assertTrue( is_object( eZURLWildcard::fetch( $nonMatchingWildcard->attribute( 'id' ) ) ), "Non matching wildcard was removed" );
    }

    /**
     * Tests the fetchList method
     *
     * Outline:
     * 1. Remove all existing wildcards directly (using SQL)
     * 2. Create a set of wildcards
     * 3. Fetch the list
     * 4. Check that all returned wildcards are part of this list
     *
     * @todo Also test fetchList with an offset/limit
     */
    public function testFetchList()
    {
        // 1. Remove all existing wildcards
        self::removeAllWildcards();

        // 2. Create a set of wildcards
        for( $i = 0; $i < 20; $i++ )
        {
            $createdWildcards[] = self::createWildcard( "testFetchList/$i/*", '/', eZURLWildcard::TYPE_DIRECT );
        }

        // 3. Fetch the list
        $fetchedWildcards = eZURLWildcard::fetchList();

        // 4. Check if every fetched wildcard is in the creation list, and the opposite
        $this->assertEquals( count( $createdWildcards ), count( $fetchedWildcards ),
            "Mismatch between created and fetched wildcards count" );
        foreach( $createdWildcards as $createdWildcard )
        {
            foreach( $fetchedWildcards as $fKey => $fetchedWildcard )
            {
                // when we found the matching wildcard in the fetched list, we test its data and break out
                if ( $fetchedWildcard->attribute( 'id' ) == $createdWildcard->attribute( 'id' ) )
                {
                    $this->assertEquals( $createdWildcard->attribute( 'source_url'), $fetchedWildcard->attribute( 'source_url') );
                    $this->assertEquals( $createdWildcard->attribute( 'destination_url'),  $fetchedWildcard->attribute( 'destination_url') );
                    $this->assertEquals( $createdWildcard->attribute( 'type'), $fetchedWildcard->attribute( 'type') );
                    $this->assertEquals( $createdWildcard->attribute( 'id'), $fetchedWildcard->attribute( 'id') );

                    // the checked wildcard is removed for better performances
                    unset( $fetchedWildcards[$fKey] );
                    continue 2;
                }
            }
            $this->fail( "Created wildcard #" . $createdWildcard->attribute( 'id' ) . " not found in the fetched wildcards" );
        }
    }

    /**
    * Tests the removeByIDs method
    *
    * Outline:
    * 1. Create a set of wildcards
    * 2. Call removeByIDs with a part of this list
    * 3. Check each item of the list, and check if the removed ones were removed
    *
    * @depends testStore
    * @depends testFetch
    **/
    public function testRemoveByIDs()
    {
        self::removeAllWildcards();

        // 1. Create a set of wildcards
        for( $i = 0; $i < 20; $i++ )
        {
            $wildcard = self::createWildcard( "testRemoveByIDs/{$i}/*", '/', eZURLWildcard::TYPE_DIRECT );
            if ( $i % 2 == 0 )
                $wildcardsToRemove[ $wildcard->attribute( 'id' ) ] = $wildcard;
            else
                $wildcardsToKeep[ $wildcard->attribute( 'id' ) ] = $wildcard;
        }

        // 2. call removeByIDs
        eZURLWildcard::removeByIDs( array_keys( $wildcardsToRemove ) );

        // 3. Check if the removed wildcards were removed, and the kept ones were kept
        foreach( array_keys( $wildcardsToRemove ) as $wildcardID )
            $this->assertNull( eZURLWildcard::fetch( $wildcardID ), "A removed wildcard entry still exists" );
        foreach( array_keys( $wildcardsToKeep ) as $wildcardID )
            $this->assertTrue( is_object( eZURLWildcard::fetch( $wildcardID ) ), "A kept wildcard entry no longer exists" );
    }

    /**
    * Test for the fetch method
    *
    * Outline:
    * 1. Create a wildcard object
    * 2. Fetch this object
    * 3. Compare the values from the fetched object with the creation data
    **/
    public function testFetch()
    {
        // 1. Create a wildcard object
        $wildcard = self::createWildcard(
           $sourceURL = 'testFetch/*',
           $destinationURL = '/',
           $type = eZURLWildcard::TYPE_DIRECT );
        $id = $wildcard->attribute( 'id' );
        unset( $wildcard );

        // 2. Fetch the created object by its ID
        $fetchedWildcard = eZURLWildcard::fetch( $id );

        // 3. Test the data
        $this->assertTrue( is_object( $fetchedWildcard ), "Failed fetching the wildcard object by ID" );
        $this->assertEquals( $sourceURL, $fetchedWildcard->attribute( 'source_url' ) );
        $this->assertEquals( $destinationURL, $fetchedWildcard->attribute( 'destination_url' ) );
        $this->assertEquals( $type, $fetchedWildcard->attribute( 'type' ) );
        $this->assertEquals( $id, $fetchedWildcard->attribute( 'id' ) );
    }

    /**
    * Test for the fetchBySourceURL method
    *
    * Outline:
    * 1. Create a wildcard
    * 2. Call the method with this wildcard's source url
    * 3. Check if the objects have identical contents
    * 4. Call the method again with asObject = false
    * 5. Check if the returned array has the same contents
    **/
    public function testFetchBySourceURL()
    {
        // 1. Create a wildcard entry
        $createdWildcard = self::createWildcard(
            $sourceURL = 'testFetchBySourceURL/*',
            $destinationURL = '/',
            $type = eZURLWildcard::TYPE_DIRECT );

        // 2. Fetch by this source URL
        $fetchedWildcard = eZURLWildcard::fetchBySourceURL( $sourceURL );

        // 3. Check if the created wildcard and the fetched one are identical
        $this->assertTrue( is_object( $fetchedWildcard ), "Failed fetching the wildcard object by source URL" );
        $this->assertEquals( $sourceURL, $fetchedWildcard->attribute( 'source_url' ) );
        $this->assertEquals( $destinationURL, $fetchedWildcard->attribute( 'destination_url' ) );
        $this->assertEquals( $type, $fetchedWildcard->attribute( 'type' ) );

        // 4. Call the method again with asObject = false
        $fetchedWildcard = eZURLWildcard::fetchBySourceURL( $sourceURL, false );

        // 5. Check if the returned array has the same contents
        $this->assertType( 'array', $fetchedWildcard, "Failed fetching the wildcard object as an array" );
        $this->assertEquals( $sourceURL, $fetchedWildcard['source_url'] );
        $this->assertEquals( $destinationURL, $fetchedWildcard['destination_url'] );
        $this->assertEquals( $type, $fetchedWildcard['type'] );
    }

    /**
    * Tests the fetchListCount method
    *
    * Outline:
    * 1. Remove all existing wildcards
    * 2. Create a known number of wildcards
    * 3. Check if fetchListCount matches the number of created wildcards
    **/
    public function testFetchListCount()
    {
        // 1. Remove all existing wildcards
        $this->removeAllWildcards();

        // 2. Create a known number of wildcards
        for( $i = 0, $wildcardsCount = 20; $i < $wildcardsCount; $i++ )
        {
            self::createWildcard( "testFetchListCount/{$i}/*", '/', eZURLWildcard::TYPE_DIRECT );
        }

        // 3. Check if fetchListCount matches the number of created wildcards
        $this->assertEquals( $wildcardsCount, eZURLWildcard::fetchListCount(),
            "The return value from fetchListCount doesn't match the number of created wildcards" );
    }

    /**
    * Tests the removeAll method
    *
    * Outline:
    * 1. Create a set of wildcards
    * 2. Call removeall
    * 3. Check if all these wildcards were removed
    *
    * @depends testStore
    * @depends testFetchList
    **/
    public function testRemoveAll()
    {
        // 1. Create a set of wildcards
        for( $i = 0; $i < 10; $i++ )
        {
            self::createWildcard( "testRemoveAll/$i/*", '/', eZURLWildcard::TYPE_DIRECT );
        }

        // 2. Call removeAll
        eZURLWildcard::removeAll();

        // 3. Check if all wildcards are indeed gone
        $wildcards = eZURLWildcard::fetchList();
        $this->assertTrue( ( count( $wildcards ) == 0 ), count( $wildcards ) . " wildcards still exist in the database" );
    }

    /**
     * Test for the cacheInfo method
     *
     * Outline:
     * 1. Compare the cacheInfo return to the expected/known values
     **/
    public function testCacheInfo()
    {
        $cacheInfo = eZURLWildcard::cacheInfo();
        $this->assertType( 'array', $cacheInfo );
        $this->assertArrayHasKey( 'dir', $cacheInfo );
        $this->assertEquals( 'var/test/cache/wildcard', $cacheInfo['dir'] );
        $this->assertArrayHasKey( 'file', $cacheInfo );
        $this->assertEquals( 'wildcard_91ad0d5d8c1489e3961a781324a4e2f7', $cacheInfo['file'] );
        $this->assertArrayHasKey( 'path', $cacheInfo );
        $this->assertEquals( 'var/test/cache/wildcard/wildcard_91ad0d5d8c1489e3961a781324a4e2f7', $cacheInfo['path'] );
        $this->assertArrayHasKey( 'keys', $cacheInfo );
        $this->assertType( 'array', $cacheInfo['keys'] );
        $this->assertArrayHasKey( 'implementation', $cacheInfo['keys'] );
        $this->assertEquals( 'ezmysqli', $cacheInfo['keys']['implementation'] );
        $this->assertArrayHasKey( 'server', $cacheInfo['keys'] );
        $this->assertEquals( 'localhost', $cacheInfo['keys']['server'] );
        $this->assertArrayHasKey( 'database', $cacheInfo['keys'] );
        $this->assertEquals( 'testdb', $cacheInfo['keys']['database'] );
    }

    /**
    * Test for the cacheInfoDirectories method.
    * Might be useless as the method is most likely private
    *
    * Outline:
    * 2. Call the method
    * 3. Check that the parameters passed as references have the expected values
    *
    * @depends testCacheInfo
    **/
    public function testCacheInfoDirectories()
    {
        $wildcardCacheDir = $wildcardCacheFile = $wildcardCachePath = $wildcardKeys = null;

        eZURLWildcard::cacheInfoDirectories( $wildcardCacheDir,
            $wildcardCacheFile, $wildcardCachePath, $wildcardKeys );

        $this->assertEquals( $wildcardCacheDir, 'var/test/cache/wildcard' );
        $this->assertEquals( $wildcardCacheFile, 'wildcard_91ad0d5d8c1489e3961a781324a4e2f7' );
        $this->assertEquals( $wildcardCachePath, 'var/test/cache/wildcard/wildcard_91ad0d5d8c1489e3961a781324a4e2f7' );
        $this->assertType( 'array', $wildcardKeys );
        $this->assertEquals( $wildcardKeys['implementation'], 'ezmysqli' );
        $this->assertEquals( $wildcardKeys['server'], 'localhost' );
        $this->assertEquals( $wildcardKeys['database'], 'testdb' );
    }

    /**
     * Test for isCacheExpired
     *
     * Outline:
     * 1. Set the expiry timestamp for eZURLWildcard to a past value
     * 2. Check if the file is expired (should not be)
     * 3. Set the expiry timestamp for eZURLWildcard to a future value
     * 4. Check if the file is expired (should be)
     **/
    public function testIsCacheExpired()
    {
        $expiryHandler = eZExpiryHandler::instance();

        // 1. Set the expiry timestamp for eZURLWildcard to a past value
        $expiryHandler->setTimestamp( eZURLWildcard::CACHE_SIGNATURE, time() - 3600 );

        // 2. Check if the file is expired (should not be)
        $this->assertFalse( eZURLWildcard::isCacheExpired( time() ),
            "expiry timestamp is in the past, cache should not be expired" );

        // 3. Set the expiry timestamp for eZURLWildcard to a future value
        $expiryHandler->setTimestamp( eZURLWildcard::CACHE_SIGNATURE, time() + 3600 );

        // 4. Check if the file is expired (should be)
        $this->assertTrue( eZURLWildcard::isCacheExpired( time() ),
            "expiry timestamp is in the future, cache should be expired" );
    }

    /**
    * Test for expireCache
    *
    * Outline:
    * 1. Manually set the cache expiry timestamp to a date in the past
    * 2. Check that it is not expired
    * 3. Call expireCache
    * 4. Check that the cache is expired
    *
    * @depends testExpireCache
    **/
    public function testExpireCache()
    {
        $expiryHandler = eZExpiryHandler::instance();

        // 1. Manually set the cache expiry timestamp to a date in the past
        $expiryHandler->setTimestamp( eZURLWildcard::CACHE_SIGNATURE, time() - 3600 );

        // 2. Check that it is not expired
        $this->assertFalse( eZURLWildcard::isCacheExpired( time() - 1 ),
            "Expiry timestamp is in the past, cache should not be expired" );

        // 3. Call expireCache
        eZURLWildcard::expireCache();

        // 4. Check that the cache is expired
        $this->assertTrue( eZURLWildcard::isCacheExpired( time() - 1 ),
            "Cache should have been expired by expireCache()" );
    }

    /**
    * Test for the translate method
    **/
    public function testTranslate()
    {
        // Cleanup cache & existing wildcards
        eZURLWildcard::expireCache();
        self::removeAllWildcards();

        // Add a wildcard
        self::createWildcard( "testTranslate/*", '/', eZURLWildcard::TYPE_DIRECT );
        eZURLWildcard::expireCache();
        sleep(1);

        // Test an URI that should be translated
        $uri = eZURI::instance( 'testTranslate/foobar' );
        $ret = eZURLWildcard::translate( $uri );
        $this->assertTrue( $ret );
        $this->assertEquals( 'content/view/full/2', $uri->URI );

        // Test an URI that should NOT be translated
        $uri = eZURI::instance( 'unknownURI/foobar' );
        $ret = eZURLWildcard::translate( $uri );
        $this->assertFalse( $ret );
        $this->assertEquals( 'unknownURI/foobar', $uri->URI );
    }

    /**
    * Test for the createCache method
    *
    * Outline:
    * 1. Delete all wildcards
    * 2. Create a set of wildcards
    * 3. Generate caches
    **/
    public function testCreateCache()
    {
        // 1. Delete all wildcards
        self::removeAllWildcards();

        // 2. Create a set of wildcards
        for( $i = 0; $i < 200; $i++ )
        {
            self::createWildcard( "testCreateCache/{$i}/*", '/', eZURLWildcard::TYPE_DIRECT );
        }

        // 3. Generate the cache
        eZURLWildcard::createCache();

        // 4. Check if the cache index file exists
        $cacheIndex = eZURLWildcard::cacheIndexFile();
        $this->assertTrue( $cacheIndex->exists() );

        /* Incompatible with testLoadCacheIndex, since the same function name is used...
           This really is a poor way to implement caching :(
        include( $cacheIndex->name() )
        $function = eZURLWildcard::REGEXP_ARRAY_CALLBACK;
        $wildcards = $function();
        $this->assertType( 'array', $wildcards );
        $this->assertEquals( 200, count( $wildcards ) );
        */
    }

    /**
    * Test for the cacheIndexFile method
    *
    * Outline:
    * 1. Call the method
    * 2. Check that its return value is an instance of eZClusterFileHandler
    * 3. Check that the file the handler references is the cache index file
    **/
    public function testCacheIndexFile()
    {
        $handler = eZURLWildcard::cacheIndexFile();

        $this->assertTrue(
            $handler instanceof eZFSFileHandler
            or $handler instanceof eZDFSFileHandler
            or $handler instanceof eZDBFileHandler );

        $this->assertEquals( 'var/test/cache/wildcard/wildcard_91ad0d5d8c1489e3961a781324a4e2f7_index.php',
            $handler->name() );
    }

    /**
    * Test for the loadCacheIndex method
    *
    * Outline:
    * 1. Use the cache index handler (cacheIndexFile) to remove the cache
    * 2. Call the method and check if it returns false (it should)
    * 3. Call createCache to make sure the cache file exists
    * 4. Call loadCacheIndex and check that it returns true
    * 5. Check if the eZURLWilcardCachedReqexpArray function has been defined
    * 6. Check that eZURLWilcardCachedReqexpArray returns an array
    **/
    public function testLoadCacheIndex()
    {
        // 1. Use the cache index handler (cacheIndexFile) to remove the cache
        $cacheHandler = eZURLWildcard::cacheIndexFile();
        $cacheHandler->purge();

        // 2. Call the method and check if it returns false (it should)
        $return = eZURLWildcard::loadCacheIndex();
        $this->assertFalse( $return,
            "loadCacheIndex should have returned false since the file was removed" );

        // 3. Call createCache to make sure the cache file exists
        eZURLWildcard::createCache();

        // 4. Call loadCacheIndex and check that it returns true
        $return = eZURLWildcard::loadCacheIndex();
        $this->assertTrue( $return,
            "loadCacheIndex should have returned true since the file was created" );

        $function = eZURLWildcard::REGEXP_ARRAY_CALLBACK;

        // 5. Check if the eZURLWilcardCachedReqexpArray function has been defined
        $this->assertTrue( function_exists( $function ) );

        // 6. Check that eZURLWilcardCachedReqexpArray returns an array
        $this->assertType( 'array', $function(),
            "The cache callback should have returned an array" );
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
    * Helper function that removes all the wildcards from the database
    **/
    static public function removeAllWildcards()
    {
        eZDB::instance()->query( 'DELETE FROM ezurlwildcard' );
    }

    /** Holds locally changed INI values for further restoration
     * @var array
     **/
    var $iniBackup = array();
}
?>