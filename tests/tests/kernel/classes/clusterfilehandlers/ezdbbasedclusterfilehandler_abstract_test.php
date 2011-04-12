<?php
/**
 * File containing the eZDBBasedClusterFileHandlerAbstractTest class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package tests
 */

/**
 * Abstract class that gathers common cluster file handlers tests
 */
abstract class eZDBBasedClusterFileHandlerAbstractTest extends eZClusterFileHandlerAbstractTest
{
    /**
     * @var eZMySQLDB
     */
    protected $db;

    /**
     * Tests the cluster shutdown handler.
     * Handlers that support stalecache should cleanup their generating files if any
     */
    public function testShutdownHandler()
    {
        $path1 = 'var/tests/' . __FUNCTION__ . 'uncleanfile1.txt';
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
        $this->assertStringEndsWith(    '.generating', $file1->filePath, '$file1 is not generating' );
        $this->assertStringEndsNotWith( '.generating', $file2->filePath, '$file2 is generating' );
        $this->assertStringEndsWith(    '.generating', $file3->filePath, '$file3 is not generating' );

        // Call the cleanup handler called by eZExecution::cleanExit()
        eZClusterFileHandler::cleanupGeneratingFiles();

        // Check that all files are no longer marked as generating
        $this->assertStringEndsNotWith( '.generating', $file1->filePath, '$file1 is still generating' );
        $this->assertStringEndsNotWith( '.generating', $file2->filePath, '$file2 is still generating' );
        $this->assertStringEndsNotWith( '.generating', $file3->filePath, '$file3 is still generating' );
    }

    /**
     * Test for the getFileList() method
     */
    public function testGetFileList()
    {
        self::markTestIncomplete();
    }

    /**
     * Test for the disconnect() method
     */
    abstract public function testDisconnect();

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
        self::assertType( 'integer', $res, "Calling startCacheGeneration for the second time should have returned the remaining cache generation time" );
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

        self::assertTrue( $res );
        self::assertEquals( $path, $ch->filePath );
        self::assertTrue( $ch->exists() );
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
    }
}
?>
