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
abstract class eZClusterStaleCacheTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    protected $previousFileHandler;

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
        self::assertTrue( eZClusterFileHandler::cleanupGeneratingFiles(), 'eZClusterFileHandler::cleanupGeneratingFiles() returned false' );

        // Check that all files are no longer marked as generating
        self::assertStringEndsNotWith( '.generating', $file1->filePath, '$file1 is still generating' );
        self::assertStringEndsNotWith( '.generating', $file2->filePath, '$file2 is still generating' );
        self::assertStringEndsNotWith( '.generating', $file3->filePath, '$file3 is still generating' );
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

        self::deleteLocalFiles( $path );
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

        self::deleteLocalFiles( $path );
    }

    /**
     * Tests the stalecache behaviour through processCache()
     */
    public function testStaleCache()
    {
        eZDir::recursiveDelete( 'var/tests/'  . __FUNCTION__ );
        $i = 0;

        $path = 'var/tests/'  . __FUNCTION__ . '/cache.txt';
        $content = array( __METHOD__, 2, 3, 4 );
        $newContent = array( __METHOD__, 5, 6, 7 );
        $extradata = array( 'content' => $content );

        // Create the cache item, and expire it
        $ch = eZClusterFileHandler::instance( $path );
        $result = $ch->processCache(
            array( $this, 'processCacheRetrieveCallback' ),
            array( $this, 'processCacheGenerateCallback' ),
            null, null, $extradata );
        $ch->loadMetaData( true );
        self::assertEquals( $extradata['content'], $result );
        self::assertTrue( $ch->exists(), "Cache file '$path' doesn't exist" );
        $ch->delete();
        $ch->loadMetaData( true );

        // Re-process the deleted item without a generate callback (stay in generation mode)
        $chGenerate = eZClusterFileHandler::instance( $path );
        $result = $chGenerate->processCache(
            array( $this, 'processCacheRetrieveCallback' ),
            null,
            null, null, $extradata );
        self::assertNotEquals( $content, $result, "Generation start" );
        self::assertInstanceOf( 'eZClusterFileFailure', $result, "Generation start" );
        $chGenerate->abortCacheGeneration();

        // call the same function another time to trigger stalecache
        $ch = eZClusterFileHandler::instance( $path );
        $result = $ch->processCache(
            array( $this, 'processCacheRetrieveCallback' ),
            array( $this, 'processCacheGenerateCallback' ),
            null, null, $extradata );
        self::assertEquals( $content, $result, "Stalecache content" );

        // delete the local cache file to trigger DB based stale cache
        $ch->deleteLocal();
        clearstatcache( $path );
        $result = $ch->processCache(
            array( $this, 'processCacheRetrieveCallback' ),
            array( $this, 'processCacheGenerateCallback' ),
            null, null, $extradata );
        self::assertEquals( $content, $result, "Stalecache content" );
        unset( $ch );

        // store the new cache contents
        $chGenerate->storeCache(
            self::processCacheGenerateCallback( $path, array('content' => $newContent ) ) );
        $chGenerate->loadMetaData( true );
        unset( $chGenerate );

        // re-request the cache a last time and check that it matches the new content
        $ch = eZClusterFileHandler::instance( $path );
        $result = $ch->processCache(
            array( $this, 'processCacheRetrieveCallback' ),
            array( $this, 'processCacheGenerateCallback' ),
            null, null, $extradata );
        self::assertEquals( $newContent, $result, "New content" );
        unset( $ch );

        // re-request the cache after making the local file expire (make it older)
        $ch = eZClusterFileHandler::instance( $path );
        touch( $path, strtotime( '-1 hour' ) );
        clearstatcache( $path );
        $result = $ch->processCache(
            array( $this, 'processCacheRetrieveCallback' ),
            array( $this, 'processCacheGenerateCallback' ),
            null, null, $extradata );
        self::assertEquals( $newContent, $result, "New content" );
        unset( $ch );

        /* This test isn't really useful: we delete the local & DB file, and will of course end-up waiting for the generation to be finished
           Possibilities:
           - 'generate' mode instead of a 'wait' one
           - reduce the timeout to a few seconds, and check that the wait has occured
        */

        /*// expire cache completely again
        $ch = eZClusterFileHandler::instance( $path );
        $ch->delete();
        $ch->purge();
        $ch->loadMetaData( true );
        self::assertFalse( $ch->exists(), "Cache file exists #3" );
        unset( $ch );

        // re-process it without a generate callback (stay in generation mode)
        $chGenerate = eZClusterFileHandler::instance( $path );
        $result = $chGenerate->processCache(
          array( $this, 'processCacheRetrieveCallback' ),
          null,
          null, null, $extradata );
        self::assertNotEquals( $content, $result, "Generation start" );
        self::assertInstanceOf( 'eZClusterFileFailure', $result, "Generation start" );

        // call the same function another time to trigger stalecache
        $ch = eZClusterFileHandler::instance( $path );
        $result = $ch->processCache(
          array( $this, 'processCacheRetrieveCallback' ),
          array( $this, 'processCacheGenerateCallback' ),
          null, null, $extradata );
        self::assertEquals( $content, $result, "Stalecache content" );

        // store the new cache contents
        $chGenerate->storeCache(
            self::processCacheGenerateCallback( $path, array('content' => $newContent ) ) );
        $chGenerate->loadMetaData( true );
        unset( $chGenerate );*/

        self::deleteLocalFiles( $path );
    }

    /**
     * Generate callback used by {@link testProcessCache()}
     *
     * Will store the 'content' key from $extraData as the cached content
     */
    public function processCacheGenerateCallback( $path, $extraData )
    {
        /** Add random content ?
         * Idea: use extra data to carry options around from {@link testProcessCache}
         */
        return array(
            'content' => $extraData['content'],
            'scope' => 'test',
            'datatype' => 'text/plain',
            'store' => true // required because eZFS2 doesn't store by default. See the todo at the end of the processCache method.
        );
    }

    /**
     * Retrieve callback used by {@link testProcessCache()}
     */
    public function processCacheRetrieveCallback( $path, $mtime, $extraData )
    {
        // Return the file's content ? A way to really manage expiry MUST be used
        // See examples in nodeViewFunctions
        // Must return what was cached by the generate callback
        return include( $path );
    }

    /**
     * Deletes one or more local files
     * @param mixed $path
     *        Path to the local file. Give as many as you like (variable params)
     *        Can also be an array of path
     * @todo Refactor. Copy/Paste of code is bad.
     */
    protected static function deleteLocalFiles( $path )
    {
        foreach( func_get_args() as $item )
        {
            if ( !is_array( $item ) )
                $item = array( $item );
            foreach( $item as $path )
            {
                if ( file_exists( $path ) )
                    unlink( $path );
            }
        }
    }

}
?>
