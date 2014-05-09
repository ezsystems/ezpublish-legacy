<?php
/**
 * File containing the eZDBBasedClusterFileHandlerAbstractTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

/**
 * Abstract class that gathers common cluster file handlers tests
 */
abstract class eZDBBasedClusterFileHandlerAbstractTest extends eZClusterFileHandlerAbstractTest
{
    /**
     * @var eZMySQLiDB
     **/
    protected $db;

    /**
     * Tests the cluster shutdown handler.
     * Handlers that support stalecache should cleanup their generating files if any
     */
    public function testShutdownHandler()
    {
        // Call the cleanup handler called by eZExecution::cleanExit()
        self::assertFalse( eZClusterFileHandler::cleanupGeneratingFiles() );

        $path1 = 'var/tests/' . __FUNCTION__ . '/uncleanfile1.txt';
        $path2 = 'var/tests/' . __FUNCTION__ . '/uncleanfile2.txt';
        $path3 = 'var/tests/' . __FUNCTION__ . '/uncleanfile3.txt';

        // start generation of a couple files
        $file1 = eZClusterFileHandler::instance( $path1 );
        $file1->startCacheGeneration();
        $file1->storeContents( __METHOD__ );

        $file2 = eZClusterFileHandler::instance( $path2 );
        $file2->startCacheGeneration();
        $file2->storeContents( __METHOD__ );

        $file3 = eZClusterFileHandler::instance( $path3 );
        $file3->startCacheGeneration();
        $file3->storeContents( __METHOD__ );

        // terminate one of them
        $file2->endCacheGeneration();

        // check that the generating status is as expected
        self::assertStringEndsWith(    '.generating', $file1->filePath, '$file1 is not generating' );
        self::assertStringEndsNotWith( '.generating', $file2->filePath, '$file2 is generating' );
        self::assertStringEndsWith(    '.generating', $file3->filePath, '$file3 is not generating' );

        // Call the cleanup handler called by eZExecution::cleanExit()
        self::assertTrue( eZClusterFileHandler::cleanupGeneratingFiles() );

        // Check that all files are no longer marked as generating
        self::assertStringEndsNotWith( '.generating', $file1->filePath, '$file1 is still generating' );
        self::assertStringEndsNotWith( '.generating', $file2->filePath, '$file2 is still generating' );
        self::assertStringEndsNotWith( '.generating', $file3->filePath, '$file3 is still generating' );
    }

    /**
     * Test for the getFileList() method
     */
    public function testGetFileList()
    {
        self::markTestIncomplete();
    }

    /**
     * Test for the cleanPath() method
     *
     * @dataProvider providerForTestCleanPath
     */
    public function testCleanPath( $path, $expectedPath )
    {
        self::assertEquals( $expectedPath, call_user_func( array( $this->clusterClass, 'cleanpath' ), $path ) );
    }

    public static function providerForTestCleanPath()
    {
        return array(
            array( 'path\with\backslashes.txt',         'path/with/backslashes.txt' ),
            array( 'path//with///multiple/slashes.txt', 'path/with/multiple/slashes.txt' ),
            array( 'path/with/ending/slash/',           'path/with/ending/slash' )
        );
    }

    /**
     * Test for the fetchUnique() method
     */
    public function testFetchUnique()
    {
        $testFile = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $this->createFile( $testFile, "contents" );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $fetchedFile = $clusterHandler->fetchUnique();

        self::assertNotSame( $testFile, $fetchedFile, "A unique name should have been returned" );
        self::assertTrue( file_exists( $fetchedFile ), "The locally fetched unique file doesn't exist" );

        if ( file_exists( $testFile ) )
            unlink( $testFile );
        if ( file_exists( $fetchedFile ) )
            unlink( $fetchedFile );
    }

    /**
     * Test for the startCacheGeneration() method
     */
    public function testStartCacheGeneration()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';

        $ch = eZClusterFileHandler::instance( $path );
        $ch2 = eZClusterFileHandler::instance( $path );

        self::assertEquals( $path, $ch->filePath );

        $res = $ch->startCacheGeneration();
        self::assertTrue( $res );
        self::assertEquals( "$path.generating", $ch->filePath );

        $res = $ch2->startCacheGeneration();
        self::assertInternalType( 'integer', $res, "Calling startCacheGeneration for the second time should have returned the remaining cache generation time" );
        self::assertStringEndsNotWith( ".generating.generating", $ch->filePath );

        $ch->abortCacheGeneration();
    }

    /**
     * Test for the endCacheGeneration() method
     */
    public function testEndCacheGeneration()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';

        $ch = eZClusterFileHandler::instance( $path );
        $res = $ch->startCacheGeneration();
        $ch->storeContents( 'contents' );
        $res = $ch->endCacheGeneration();

        self::assertTrue( $res, "endCacheGeneration didn't return true" );
        self::assertEquals( $path, $ch->filePath );
        self::assertTrue( $ch->exists(), "$path does not exist" );
    }

    /**
     * Test for the endCacheGeneration() method
     */
    public function testEndCacheGenerationNonGeneratingFile()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';

        $ch = eZClusterFileHandler::instance( $path );
        $ch->storeContents( 'contents' );
        $res = $ch->endCacheGeneration();

        self::assertFalse( $res );
        self::assertEquals( $path, $ch->filePath );
        self::assertTrue( $ch->exists() );
    }

    /**
     * Test for the abortCacheGeneration() method
     */
    public function testAbortCacheGeneration()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';

        $ch = eZClusterFileHandler::instance( $path );
        $res = $ch->startCacheGeneration();
        $ch->storeContents( 'contents' );
        $ch->abortCacheGeneration();

        self::assertEquals( $path, $ch->filePath );
        self::assertFalse( $ch->exists() );
    }


    /**
     * Test for the cacheGenerationTimeout() method
     */
    public function testCheckCacheGenerationTimeout()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';

        $ch = eZClusterFileHandler::instance( $path );
        $res = $ch->startCacheGeneration();
        $ch->storeContents( 'contents' );

        self::assertTrue( $ch->checkCacheGenerationTimeout() );

        $ch->abortCacheGeneration();
    }

    /**
     * Tests the name trunk automatic creation (at backend level)
     * @dataProvider providerForTestNameTrunk
     */
    public function testNameTrunk( $path, $scope, $expectedNameTrunk, $expectedCacheType )
    {
        // postgres doesn't use nametrunk
        if ( ezpTestRunner::dsn()->parts['phptype'] == 'postgresql' )
            self::markTestSkipped( "name_trunk isn't used by postgresql" );

        $ch = self::createFile( $path, false, array( 'scope' => $scope ) );
        self::assertEquals( $expectedNameTrunk, $ch->metaData['name_trunk'] );
        self::assertEquals( $expectedCacheType, $ch->cacheType );
    }

    public static function providerForTestNameTrunk()
    {
        return array(
            // view cache file
            array(
                'var/plain_site/cache/content/plain_site/2-a54e7f5dba0d9df9de22904d309754b8.cache',
                'viewcache',
                'var/plain_site/cache/content/plain_site/2-',
                'viewcache'
            ),

            // template block with subtree expiry
            array(
                'var/plain_site/cache/template-block/subtree/1/cache/1/1/0/110322645.cache',
                'template-block',
                'var/plain_site/cache/template-block/subtree/1/cache/',
                'cacheblock'
            ),

            // misc cache
            array(
                'var/plain_site/cache/classidentifiers_fc45544cdb917d072c104b67248009e1.php',
                'classidentifiers',
                'var/plain_site/cache/classidentifiers_fc45544cdb917d072c104b67248009e1.php',
                'misc'
            ),
        );
    }

    /**
     * Validates the cache feature used by the loadMetaData() method
     */
    public function testLoadMetaDataCache()
    {
        $class = $this->clusterClass;

        $files = array();

        // generate more files than the cache limit so that the
        $iMax = constant( "$class::INFOCACHE_MAX" ) + 10;
        for( $i = 1; $i <= $iMax; $i++ )
        {
            $ch = eZClusterFileHandler::instance( "var/tests/" . __FUNCTION__ . "/{$i}.txt" );
            $ch->loadMetaData();
            self::assertInstanceOf( $class, $ch, "Object #{$i} is not a cluster file handler" );
            $files[] = $ch->filePath;
        }

        // reload the same files to trigger loading from cache
        foreach( $files as $filePath )
        {
            $ch = eZClusterFileHandler::instance( $filePath );
            $ch->loadMetaData();
            self::assertInstanceOf( $class, $ch, "Object #{$i} is not a cluster file handler" );
        }
    }

    /**
     * Unit test for fileDeleteLocal()
     */
    public function testFiledeleteLocal()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $ch = $this->createFile( $path );
        $ch->fetch();

        self::assertFileExists( $path );

        $ch->fileDeleteLocal( $path );

        self::assertFileNotExists( $path );
    }

    /**
     * Unit test for deleteLocal()
     */
    public function testDeleteLocal()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $ch = $this->createFile( $path );
        $ch->fetch();

        self::assertFileExists( $path );

        $ch->deleteLocal( $path );

        self::assertFileNotExists( $path );
    }

    public function testRequiresClusterizing()
    {
        self::assertTrue( eZClusterFileHandler::instance()->requiresClusterizing() );
    }
}
?>
