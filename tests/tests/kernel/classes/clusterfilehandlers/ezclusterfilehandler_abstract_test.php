<?php
/**
 * File containing the eZClusterFileHandlerTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @package tests
 */

/**
 * Abstract class that gathers common cluster file handlers tests
 */
abstract class eZClusterFileHandlerAbstractTest extends ezpDatabaseTestCase
{
    /**
     * Tested cluster class
     * Must be overriden by any implementation
     * @var string eZFSFileHandler
     */
    protected $clusterClass = false;

    protected $backupGlobals = false;

    public function setUp()
    {
        // Verify that the clusterClass for each implementation is properly defined
        if ( $this->clusterClass === false )
            $this->markTestSkipped( "Test class " . get_class( $this ) . " does not provide the clusterClass property" );

        return parent::setup();
    }

    /**
     * Helper function that creates a cluster file
     * @param string $path
     * @param mixed $contents
     * @param array $params Extra parameters. Possible keys: scope, datatype.
     */
    protected function createFile( $path, $contents = false, $params = array() )
    {
        if ( $contents === false )
            $contents = md5( time() );

        $scope = isset( $params['scope'] ) ? $params['scope'] : false;
        $datatype = isset( $params['datatype'] ) ? $params['datatype'] : false;

        $ch = eZClusterFileHandler::instance( $path );
        $ch->storeContents( $contents, $scope, $datatype );
        $ch->loadMetaData( true );

        return $ch;
    }

    /**
     * Deletes one or more local files
     * @param mixed $path
     *        Path to the local file. Give as many as you like (variable params)
     *        Can also be an array of path
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
        self::assertNull( $clusterFileHandler->size() );
        self::assertNull( $clusterFileHandler->mtime() );

        // existing file
        $file =  'var/tests/' . __FUNCTION__ . '/existing-file.txt';
        $this->createFile( $file, md5( time() ) );
        $ch = eZClusterFileHandler::instance( $file );
        self::assertEquals( 32,    $ch->size() );
        self::assertInternalType( 'integer', $ch->mtime() );
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
        // call two times to cover the GLOBALS copy
        $ch = eZClusterFileHandler::instance();
        self::assertFalse( $ch->filePath, "Path to empty instance should have been null" );
        unset( $ch );

        // non existing file
        $path = 'var/tests/' . __FUNCTION__ . '/nofile.txt';
        $ch = eZClusterFileHandler::instance( $path );
        self::assertEquals( $path, $ch->filePath, "Path to non-existing file should have been the path itself" );
        self::assertFalse( $ch->exists(), "File should not exist" );
        unset( $ch );
        if ( file_exists( $path ) )
            unlink( $path );

        // existing file
        $path = 'var/tests/' . __FUNCTION__ . '/file1.txt';
        $this->createFile( $path, md5( time() ) );
        $ch = eZClusterFileHandler::instance( $path );
        self::assertEquals( $path, $ch->filePath, "Path to existing file should have been the path itself" );
        self::assertTrue( $ch->exists(), "File should exist" );
        if ( file_exists( $path ) )
            unlink( $path );
        $ch = eZClusterFileHandler::instance( $path );
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
        self::assertFileExists( $path );
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
        $path = 'var/tests/' . __FUNCTION__ . '/nofile.txt';
        $ch = eZClusterFileHandler::instance();
        self::assertFalse( $ch->fileFetch( $path ) );
        self::assertFileNotExists( $path );
    }

    /**
     * Test for the fetch() method with an existing file
     */
    public function testFetchExistingFile()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';

        // create the file
        $this->createFile( $path, md5( time() ) );

        // Fetch the file, and test for existence
        $ch = eZClusterFileHandler::instance( $path );
        $ch->fetch();
        self::assertFileExists( $path );
    }

    /**
     * Test for the fetch() method with a non existing file
     */
    public function testFetchNonExistingFile()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/nofile.txt';

        // Fetch the file, and test for existence
        $ch = eZClusterFileHandler::instance( $path );
        $ch->fetch();
        self::assertFileNotExists( $path );
    }

    /**
     * Test for the fileStore() method with the delete option
     */
    public function testFileStoreWithDelete()
    {
        // create local file on disk
        $directory = 'var/tests/' . __FUNCTION__;
        $localFile = $directory . '/file.txt';
        eZFile::create( 'file.txt', $directory, md5( time() ) );

        // 1. First store to cluster, with delete option
        $ch = eZClusterFileHandler::instance();
        $ch->fileStore( $localFile, 'test', true, 'text/plain' );

        // 2. Check that the created file exists
        $ch2 = eZClusterFileHandler::instance( $localFile );
        self::assertTrue( $ch2->exists() );
        if ( !$ch instanceof eZFSFileHandler )
        {
            self::assertEquals( 'text/plain', $ch2->metaData['datatype'] );
            self::assertEquals( 'test', $ch2->metaData['scope'] );
            self::assertFileNotExists( $localFile );
        }
    }

    /**
     * Test for the fileStore() method with the delete option
     */
    public function testFileStoreWithoutDelete()
    {
        // create local file on disk
        $directory = 'var/tests/' . __FUNCTION__;
        $localFile = $directory . '/file.txt';
        eZFile::create( 'file.txt', $directory, md5( time() ) );

        // 1. First store to cluster, with delete option
        $ch = eZClusterFileHandler::instance();
        $ch->fileStore( $localFile, 'test', false, 'text/plain' );

        // 2. Check that the created file exists
        $ch2 = eZClusterFileHandler::instance( $localFile );
        self::assertTrue( $ch2->exists() );
        if ( !$ch instanceof eZFSFileHandler )
        {
            self::assertEquals( 'text/plain', $ch2->metaData['datatype'] );
            self::assertEquals( 'test', $ch2->metaData['scope'] );
            self::assertFileExists( $localFile );
        }

        self::deleteLocalFiles( $localFile );
    }


    /**
     * Test for the fileStoreContents() method
     */
    public function testFileStoreContents()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';

        $ch = eZClusterFileHandler::instance();
        $ch->fileStoreContents( $path, md5( time() ), 'test', 'text/plain' );

        self::assertTrue( $ch->fileExists( $path ) );
    }

    /**
     * Test for the storeContents() method
     */
    public function testStoreContents()
    {
        $file = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $contents = md5( time() );

        // 1. Store the file to cluster
        $ch = eZClusterFileHandler::instance( $file );
        $ch->storeContents( $contents, 'test', 'plain/text', false );
        $ch->loadMetaData( true );
        self::assertTrue( $ch->exists() );
        self::assertEquals( $contents, $ch->fetchContents() );
    }

    /**
     * Test for the storeContents() method with a local copy
     */
    public function testStoreContentsWithLocalCopy()
    {
        $file = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $contents = md5( time() );

        // 1. Store the file to cluster
        $ch = eZClusterFileHandler::instance( $file );
        $ch->storeContents( $contents, 'test', 'plain/text', true );
        $ch->loadMetaData( true );
        self::assertTrue( $ch->exists() );
        if ( !$this instanceof eZFSFileHandlerTest )
            self::assertFileExists( $file );
    }

    /**
     * Test for the processCache() method
     */
    public function testProcessCacheOne()
    {
        $path = 'var/tests/'  . __FUNCTION__ . '/cache.txt';
        $extradata = array( 'content' => array( __METHOD__, 2, 3, 4 ) );

        $ch = eZClusterFileHandler::instance( $path );
        $result = $ch->processCache(
            array( $this, 'processCacheRetrieveCallback' ),
            array( $this, 'processCacheGenerateCallback' ),
            null, null, $extradata );
        $ch->loadMetaData( true );
        self::assertEquals( $extradata['content'], $result );
        self::assertTrue( $ch->exists(), "Cache file '$path' doesn't exist" );

        // expire it
        $ch->delete();
        $ch->loadMetaData( true );
        self::assertFalse( $ch->exists(), "Cache file exists #2" );
        unset( $ch );

        self::deleteLocalFiles( $path );
    }

    /**
     * processCache() test: cache doesn't exist and no generate callback
     */
    public function testProcessCacheTwo()
    {
        $path = 'var/tests/'  . __FUNCTION__ . '/cache.txt';

        $expected = new eZClusterFileFailure( 2, "Manual generation of file data is required, calling storeCache is required" );

        $ch = eZClusterFileHandler::instance( $path );
        $result = $ch->processCache(
            array( $this, 'processCacheRetrieveCallback' ),
            null,
            null, null, array() );
        $ch->loadMetaData( true );
        self::assertEquals( $expected, $result );
        $ch->abortCacheGeneration();

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
     * Test for the isFileExpired() method
     */
    public function testIsFileExpired()
    {
        $fname = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $ch = eZClusterFileHandler::instance();

        // Negative mtime: expired
        $mtime = -1;
        $expiry = -1;
        $curtime = time();
        $ttl = null;
        $result = $ch::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        self::assertTrue( $result, "negative mtime: expired expected" );

        // FALSE mtime: expired
        $mtime = false;
        $expiry = -1;
        $curtime = time();
        $ttl = null;
        $result = $ch::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        self::assertTrue( $result, "false mtime: expired expected" );

        // NULL TTL + mtime < expiry: expired
        $mtime = time() - 3600; // mtime < expiry
        $expiry = time();
        $curtime = time();
        $ttl = null;
        $result = $ch::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        self::assertTrue( $result,
            "no TTL + mtime < expiry: expired expected" );

        // NULL TTL + mtime > expiry: not expired
        $mtime = time();
        $expiry = time() - 3600; // expires in the future
        $curtime = time();
        $ttl = null;
        $result = $ch::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        self::assertFalse( $result,
            "no TTL + mtime > expiry: not expired expected" );

        // TTL != null, mtime < curtime - ttl: expired
        $mtime = time();
        $expiry = -1; // disable expiry check
        $curtime = time();
        $ttl = 60; // 60 seconds TTL
        $result = $ch::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        self::assertFalse( $result,
            "TTL + ( mtime < ( curtime - ttl ) ): !expired expected" );

        // TTL != null, mtime > curtime - ttl: not expired
        $mtime = time() - 90; // old file
        $expiry = -1; // disable expiry check
        $curtime = time();
        $ttl = 60; // 60 seconds TTL
        $result = $ch::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        self::assertTrue( $result,
            "TTL + ( mtime > ( curtime - ttl ) ): expired expected" );

        // TTL != null, mtime < expiry: expired
        $mtime = time() - 90; // old file
        $expiry = time(); // file is expired
        $curtime = time();
        $ttl = 60; // 60 seconds TTL
        $result = $ch::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        self::assertTrue( $result,
            "TTL + ( mtime < expiry ): expired expected" );

        // TTL != null, mtime > expiry: not expired
        $mtime = time();
        $expiry = time() - 90;
        $curtime = time();
        $ttl = 60;
        $result = $ch::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        self::assertFalse( $result,
            "TTL + ( mtime > expiry ): !expired expected" );
    }

    /**
     * Test for the isExpired() method
     */
    public function testIsExpired()
    {
        // file will be created with current time as mtime()
        $path = 'var/tests/' . __FUNCTION__. '/file.txt';
        $this->createFile( $path, md5( time() ) );

        $clusterHandler = eZClusterFileHandler::instance( $path );

        // expiry date < mtime / no TTL: !expired
        self::assertFalse( $clusterHandler->isExpired( $expiry = time() - 3600, time(), null ),
            "expiry date < mtime, no TTL, !expired expected" );

        // expiry date > mtime / no TTL: !expired
        self::assertTrue( $clusterHandler->isExpired( $expiry = time() + 3600, time(), null ),
            "expiry date > mtime, no TTL, expired expected" );

        // mtime < curtime - ttl: !expired
        self::assertFalse( $clusterHandler->isExpired( $expiry = -1, time(), 60 ),
            "mtime < curtime - ttl: !expired expected" );

        // mtime > curtime - ttl: expired
        self::assertTrue( $clusterHandler->isExpired( $expiry = -1, time(), -60 ),
            "mtime > curtime - ttl: expired expected" );
    }

    /**
     * Test for the storeCache() method
     */
    public function testStoreCache()
    {
        self::markTestIncomplete();
    }

    /**
     * Test for the processFile() method
     */
    public function testProcessFile()
    {
        self::markTestIncomplete();
    }

    /**
     * Test for the fileFetchContents() method
     */
    public function testFileFetchContents()
    {
        // Create a file
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $contents = __METHOD__;
        $this->createFile( $path, $contents );

        $ch = eZClusterFileHandler::instance();
        self::assertEquals( $contents, $ch->fileFetchContents( $path ) );
    }

    /**
     * Test for the fileFetchContents() method on a non-existing file
     */
    public function testFileFetchContentsNonExistingFile()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';

        $ch = eZClusterFileHandler::instance();
        self::assertFalse( $ch->fileFetchContents( $path ) );
    }

    /**
     * Test for the fetchContents() method
     */
    public function testFetchContents()
    {
        // Create a file
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $contents = __METHOD__;
        $this->createFile( $path, $contents );

        $ch = eZClusterFileHandler::instance( $path );
        self::assertEquals( $contents, $ch->fetchContents() );
    }

    /**
     * Test for the fetchContents() method with a non-existing file
     */
    public function testFetchContentsNonExistingFile()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';

        $ch = eZClusterFileHandler::instance( $path );
        self::assertFalse( $ch->fetchContents() );
    }

    /**
     * Test for the stat() method
     */
    public function testStat()
    {
        $ch = $this->createFile( 'var/tests/' . __FUNCTION__ . '/file.txt' );
        self::assertEquals( $ch->metaData, $ch->stat(), "stat didn't return the metadata" );
        self::deleteLocalFiles( $ch->filePath );
    }

    /**
     * Test for the size() method
     */
    public function testSize()
    {
        self::markTestIncomplete();
    }

    /**
     * Test for the mtime() method
     */
    public function testMtime()
    {
        self::markTestIncomplete();
    }

    /**
     * Test for the name() method
     */
    public function testName()
    {
        $path = 'var/test/' . __FUNCTION__ . '/file.txt';
        $ch = $this->createFile( $path );
        self::assertEquals( $path, $ch->name() );
    }

    /**
     * Test for the fileDeleteByDirList() method
     */
    public function testFileDeleteByDirList()
    {
        // Create a set of files in 3 different folders that will be deleted
        $folders = array( 'folder1', 'folder2', 'folder3' );
        $prefix = 'var/tests/' . __FUNCTION__;
        $deleteSuffix = 'fileToDelete';
        $keepSuffix = 'fileToKeep';
        $deleteFiles = $keepFiles = array();

        foreach( $folders as $folder )
        {
            for( $i = 0; $i < 5; $i++ )
            {
                $path = "{$prefix}/{$folder}/{$deleteSuffix}-" . uniqid();
                self::createFile( $path );
                $deleteFiles[] = $path;

                $path = "{$prefix}/{$folder}/{$keepSuffix}-" . uniqid();
                self::createFile( $path );
                $keepFiles[] = $path;
            }
        }

        $ch = eZClusterFileHandler::instance();
        $ch->fileDeleteByDirList( $folders, $prefix, $deleteSuffix );

        // check if all the deleteFiles were deleted
        foreach ( $deleteFiles as $file )
        {
            self::assertFalse( $ch->fileExists( $file ), "deleteFile $file SHOULD have been deleted" );
        }

        // check if all the keepFiles were kept
        foreach ( $keepFiles as $file )
        {
            self::assertTrue( $ch->fileExists( $file ), "deleteFile $file SHOULD NOT have been deleted" );
        }

        self::deleteLocalFiles( $keepFiles, $deleteFiles );
    }

    /**
     * Test for the fileDelete() method
     */
    public function testFileDelete()
    {
        // Create a file
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $this->createFile( $path, md5( time() ) );

        $ch = eZClusterFileHandler::instance();

        // Check if it exists
        self::assertTrue( $ch->fileExists( $path ), "$path does not exist" );

        // Delete the file
        $ch->fileDelete( $path );

        // Re-check the file
        clearstatcache( $path );
        self::assertFalse( $ch->fileExists( $path ), "$path still exists" );
    }

    /**
     * Test for the delete() method
     */
    public function testFileDeleteDirectory()
    {
        // Create a set of files in a directory
        $directory = 'var/tests/' . __FUNCTION__;
        $files = array();

        for( $i = 0; $i < 20; $i++ )
        {
            $path = "{$directory}/{$i}.txt";
            $this->createFile( $path );
            $files[] = $path;
        }

        eZClusterFileHandler::instance()->fileDelete( $directory, '' );

        foreach( $files as $file )
        {
            $ch = eZClusterFileHandler::instance( $file );
            $ch->loadMetadata( true );
            self::assertFalse( $ch->exists(), "$file still exists after deletion" );
        }
    }

    /**
     * Test for the fileDelete() method with a prefix + wildcard
     */
    public function testFileDeleteWithWildcard()
    {
        $prefix = 'var/tests/' . __FUNCTION__;
        $suffix1 = 'file';
        $suffix2 = 'otherfile';
        $prefix1 = "{$prefix}/{$suffix1}";
        $prefix2 = "{$prefix}/{$suffix2}";
        $fileset1 = array();
        $fileset2 = array();

        // Create a set of files
        for( $i = 1; $i <= 10; $i++ )
        {
            $path = "{$prefix1}-{$i}.txt";
            $this->createFile( $path );
            $fileset1[] = $path;
        }

        // create a set of files we won't delete
        for( $i = 1; $i <= 10; $i++ )
        {
            $path = "{$prefix2}-{$i}.txt";
            $this->createFile( $path );
            $fileset2[] = $path;
        }

        $ch = eZClusterFileHandler::instance();

        // delete the files from fileset1
        $ch->fileDelete( $prefix, $suffix1 );

        // Check that the files in $fileset1 were deleted
        foreach( $fileset1 as $file )
        {
            clearstatcache( $file );
            self::assertFalse( $ch->fileExists( $file ), "$file still exists" );
        }

        // Check that the files in $fileset2 haven't been deleted
        foreach( $fileset2 as $file )
        {
            clearstatcache( $file );
            self::assertTrue( $ch->fileExists( $file ), "$file no longer exists" );
        }

        $this->deleteLocalFiles( $fileset1, $fileset2 );
    }

    /**
     * Test for the delete() method
     */
    public function testDelete()
    {
        // Create a file
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $this->createFile( $path, md5( time() ) );

        $ch = eZClusterFileHandler::instance( $path );

        // Check if it exists
        self::assertTrue( $ch->exists(), "$path doesn't exist after creation"  );

        // Delete the file
        $ch->delete();

        // Re-check the file
        $ch->loadMetadata( true );
        self::assertFalse( $ch->exists(), "$path still exists after deletion" );
    }

    /**
     * Test for the exists() method
     */
    public function testExists()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';

        $ch = eZClusterFileHandler::instance( $path );

        // file hasn't been created yet
        self::assertFalse( $ch->exists(), "The file hasn't been created, exists() should have returned false" );

        $this->createFile( $path, md5( time() ) );
        $ch->loadMetaData( true );

        self::assertTrue( $ch->exists(), "The  file been created, exists() should have returned true" );

        self::deleteLocalFiles( $path );
    }

    /**
     * Test for the fileExists() method
     */
    public function testFileExists()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';

        $ch = eZClusterFileHandler::instance();

        // file hasn't been created yet
        self::assertFalse( $ch->fileExists( $path ), "The file hasn't been created, exists() should have returned false" );

        $this->createFile( $path, md5( time() ) );
        $ch->loadMetaData( true );

        self::assertTrue( $ch->fileExists( $path ), "The  file been created, exists() should have returned true" );

        self::deleteLocalFiles( $path );
    }

    /**
     * Test for the stat() method
     */
    public function testPassthrough()
    {
        self::markTestIncomplete( "Deprecated" );
    }

    /**
     * Test for the fileCopy() method
     */
    public function testFileCopy()
    {
        // Create a file
        $sourcePath = 'var/tests/' . __FUNCTION__ . '/file-source.txt';
        $destinationPath = 'var/tests/' . __FUNCTION__ . '/file-target.txt';

        $this->createFile( $sourcePath, 'contents' );

        $ch = eZClusterFileHandler::instance();

        // check existence on both
        self::assertTrue( $ch->fileExists( $sourcePath ), "Source file doesn't exist" );
        self::assertFalse( $ch->fileExists( $destinationPath ), "Destination file already exists" );

        // copy the file
        $ch->fileCopy( $sourcePath, $destinationPath );

        // check existence on both
        self::assertTrue( $ch->fileExists( $sourcePath ), "Source file no longer exists" );
        self::assertTrue( $ch->fileExists( $destinationPath ), "Destination file doesn't exist" );

        self::deleteLocalFiles( $sourcePath, $destinationPath );
    }

    /**
     * Test for the fileLinkCopy() method
     */
    public function testFileLinkCopy()
    {
        self::markTestIncomplete();
    }

    /**
     * Test for the fileMove() method
     */
    public function testFileMove()
    {
        // Create a file
        $sourcePath = 'var/tests/' . __FUNCTION__ . '/file-source.txt';
        $destinationPath = 'var/tests/' . __FUNCTION__ . '/file-target.txt';

        $this->createFile( $sourcePath, 'contents' );

        $ch = eZClusterFileHandler::instance();

        // check existence on both
        self::assertTrue( $ch->fileExists( $sourcePath ), "Source file doesn't exist" );
        self::assertFalse( $ch->fileExists( $destinationPath ), "Destination file already exists" );

        // copy the file
        $ch->fileMove( $sourcePath, $destinationPath );

        // check existence on both
        self::assertFalse( $ch->fileExists( $sourcePath ), "Source file still exists" );
        self::assertTrue( $ch->fileExists( $destinationPath ), "Destination file doesn't exist" );

        self::deleteLocalFiles( $sourcePath, $destinationPath );
    }

    /**
     * Test for the move() method
     */
    public function testMove()
    {
        // Create a file
        $sourcePath = 'var/tests/' . __FUNCTION__ . '/file-source.txt';
        $destinationPath = 'var/tests/' . __FUNCTION__ . '/file-target.txt';

        $this->createFile( $sourcePath, 'contents' );

        $ch = eZClusterFileHandler::instance( $sourcePath );

        // check existence on both
        self::assertTrue( $ch->fileExists( $sourcePath ), "Source file doesn't exist" );
        self::assertFalse( $ch->fileExists( $destinationPath ), "Destination file already exists" );

        // copy the file
        $ch->move( $destinationPath );

        // check existence on both
        self::assertFalse( $ch->fileExists( $sourcePath ), "Source file still exists" );
        self::assertTrue( $ch->fileExists( $destinationPath ), "Destination file doesn't exist" );

        self::deleteLocalFiles( $sourcePath, $destinationPath );
    }

    /**
     * Test for the fetch() function
     */
    public function testFetch()
    {
        // fetch file1.txt, a non existing file
        $path1 = 'var/tests/' . __FUNCTION__ . '/file1.txt';
        $ch1 = eZClusterFileHandler::instance( $path1 );
        $ch1->fetch();
        self::assertFalse( $ch1->exists() );

        // create file2.txt file and fetch it
        $path2 = 'var/tests/' . __FUNCTION__ . '/file2.txt';
        $this->createFile( $path2 );
        $ch2 = eZClusterFileHandler::instance( $path2 );
        unset( $ch2 );

        // reload the file from scratch
        $ch2 = eZClusterFileHandler::instance( $path2 );
        $ch2->fetch();
        self::assertFileExists( $path2, "$path2 doesn't exist (\$noLocalCache=true) locally" );
        unset( $ch2 );

        // reload the file from scratch, and fetch with noLocalCache = true
        $ch2 = eZClusterFileHandler::instance( $path2 );
        $ch2->fetch( true );
        self::assertFileExists( $path2, "$path2 doesn't exist (\$noLocalCache=true) locally" );
    }

    public function testRequiresClusterizing()
    {
        self::assertFalse( eZClusterFileHandler::instance()->requiresClusterizing() );
    }

    public function testPurgeSingleFile()
    {
        $path = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $ch = self::createFile( $path, false );
        $ch->delete();
        $ch->purge();

        $chValidate = eZClusterFileHandler::instance( $path );
        $chValidate->loadMetadata( true );

        self::assertFileNotExists( $path );
        self::assertFalse( $chValidate->metaData );

        self::deleteLocalFiles( $path );
    }

    public function testPurgeMultipleFiles()
    {
        $basePath = 'var/tests/' . __FUNCTION__;

        // create multiple files in a folder for deletion
        for( $i = 0; $i < 10; $i++ )
        {
            $files[$i] = "$basePath/set1/{$i}.txt";
            self::createFile( $files[$i] )->delete();
        }
        // and a few other files to check if we don't delete anything that shouldn't be
        for( $i = 0; $i < 5; $i++ )
        {
            $otherFiles[$i] = "$basePath/set2/{$i}.txt";
            self::createFile( $otherFiles[$i] );
        }

        $clusterHandler = eZClusterFileHandler::instance( "$basePath/set1/" );
        $clusterHandler->purge();

        // check if files supposed to be deleted were
        foreach( $files as $file )
        {
            $ch = eZClusterFileHandler::instance( $file );
            $ch->loadMetaData( true );
            self::assertFileNotExists( $file );
            self::assertFalse( $ch->metaData, "File $file still exists" );
        }

        // and if files not supposed to be deleted weren't
        foreach( $otherFiles as $file )
        {
            $ch = eZClusterFileHandler::instance( $file );
            $ch->loadMetaData( true );
            self::assertInternalType( 'array', $ch->metaData, "File $file no longer exists" );
        }

        self::deleteLocalFiles( $files, $otherFiles);
    }

    /**
     * fileDeleteLocal has no effect on FS based handlers (no remote copy)
     */
    public function testFileDeleteLocal()
    {
        $path = 'var/tests/'.__FUNCTION__.'/file.txt';

        $ch = self::createFile( $path );
        self::assertFileExists( $path );
        eZClusterFileHandler::instance()->fileDeleteLocal( $path );
        self::assertFileExists( $path );
    }

    /**
     * deleteLocal has no effect on FS based handlers (no remote copy)
     */
    public function testDeleteLocal()
    {
        $path = 'var/tests/'.__FUNCTION__.'/file.txt';

        $ch = self::createFile( $path );
        self::assertFileExists( $path );
        $ch->deleteLocal();
        self::assertFileExists( $path );
    }
}
?>
