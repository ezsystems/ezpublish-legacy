<?php
/**
 * File containing the eZClusterFileHandlerTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

/**
 * Abstract class that gathers common cluster file handlers tests
 */
abstract class eZClusterFileHandlerAbstractTest extends ezpDatabaseTestCase
{
    public function setUp()
    {
        // Verify that the clusterClass for each implementation is properly defined
        if ( $this->clusterClass === false )
            $this->markTestSkipped( "Test class " . get_class( $this ) . " does not indicate the clusterClass property" );

        return parent::setup();
    }

    /**
     * Tests the cluster shutdown handler.
     * Handlers that support stalecache should cleanup their generating files if any
     */
    public function testShutdownHandler()
    {
        // start generation of a couple files
        $file1 = eZClusterFileHandler::instance( 'var/tests/cache/uncleanfile1.txt' );
        $file1->startCacheGeneration();
        $file1->storeContents( __METHOD__ );

        $file2 = eZClusterFileHandler::instance( 'var/tests/cache/uncleanfile2.txt' );
        $file2->startCacheGeneration();
        $file2->storeContents( __METHOD__ );

        $file3 = eZClusterFileHandler::instance( 'var/tests/cache/uncleanfile3.txt' );
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
     * Helper function that creates a cluster file
     */
    protected function createFile( $path, $contents, $params = array() )
    {
        $ch = eZClusterFileHandler::instance( $path );
        $ch->storeContents( $contents );
    }

    /**
     * Tests the loadMetaData method
     *
     * 1. Load metadata for a non existing file*
     *    Expected: no return value
     * 2.
     */
    public function testMetadata()
    {
        // non existing file
        $clusterFileHandler = eZClusterFileHandler::instance( 'var/tests/' . __FUNCTION__ . '/non-existing.txt' );
        $this->assertNull( $clusterFileHandler->size() );
        $this->assertNull( $clusterFileHandler->mtime() );

        // existing file
        $file =  'var/tests/' . __FUNCTION__ . '/existing-file.txt';
        $this->createFile( $file, md5( time() ) );
        $ch = eZClusterFileHandler::instance( $file );
        $this->assertEquals( 32,    $ch->size() );
        $this->assertType( 'integer', $ch->mtime() );
    }

    /**
     * Test for the instance method
     * 1. Check that calling instance with no file is coherent (no filePath property)
     * 1. Check that calling instance on a non-existing is coherent (no filePath property)
     * 2. Check that calling instance on an existing file makes the data available
     */
    public function testInstance()
    {
        // no parameter
        $ch = eZClusterFileHandler::instance();
        self::assertFalse( $ch->filePath, "Path to empty instance should have been null" );
        unset( $ch );

        // non existing file
        $path = 'var/tests/' . __FUNCTION__ . '/nofile.txt';
        $ch = eZClusterFileHandler::instance( $path );
        self::assertEquals( $path, $ch->filePath, "Path to non-existing file should have been the path itself" );
        self::assertFalse( $ch->exists(), "File should not exist" );
        unset( $ch );

        // existing file
        $path = 'var/tests/' . __FUNCTION__ . '/file1.txt';
        $this->createFile( $path, md5( time() ) );
        $ch = eZClusterFileHandler::instance( $path );
        self::assertEquals( $path, $ch->filePath, "Path to existing file should have been the path itself" );
        self::assertTrue( $ch->exists(), "File should exist" );
    }

    /**
     * Test for the fileFetch() method with an existing file
     *
     * 1. Store a new file to the cluster using content
     * 2. Call fileFetch on this file
     * 3. Check that the local file exists
     */
    public function testFileFetchExistingFile()
    {
        // 1. Store a new file
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $this->createFile( $path, md5( time() ) );

        // 2. Call fileFetch() on this file
        $ch = eZClusterFileHandler::instance();
        $ch->fileFetch( $path );

        // 3. Check that the local file exists
        $this->assertFileExists( $path );
        unlink( $path );
    }

    /**
     * Test for the fileFetch() method with a non-existing file
     *
     * 1. Call fileFetch() on a non-existing file
     * 2. Check that the return value is false
     */
    public function testFileFetchNonExistingFile()
    {
        $ch = eZClusterFileHandler::instance();
        $this->assertFalse( $ch->fileFetch( 'var/tests/' . __FUNCTION__ . '/nofile.txt' ) );
    }

    /**
     * Tested cluster class
     * Must be overriden by any implementation
     * @var string eZFSFileHandler, eZDBFileHandler...
     */
    protected $clusterClass = false;
}
?>