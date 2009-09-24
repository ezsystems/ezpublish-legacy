<?php
/**
 * File containing the eZContentObjectStateTest class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZDFSFileHandlerTest extends ezpDatabaseTestCase
{
    /**
     * @var eZINI
     **/
    protected $fileINI;

    /**
     * @var eZMySQLDB
     **/
    protected $db;

    /**
     * @var string
     **/
    protected $DFSPath = 'var/dfsmount/';

    protected $backupGlobals = false;

    protected $haveToRemoveDFSPath = false;

    /**
     * @var array
     **/
    protected $sqlFiles = array( 'tests/tests/kernel/classes/clusterfilehandlers/sql/cluster_dfs_schema.sql' );

    protected $previousFileHandler;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZDFSClusterFileHandler Unit Tests" );
    }

    /**
     * Test setup
     *
     * Load an instance of file.ini
     * Assigns DB parameters for cluster
     **/
    public function setUp()
    {
        parent::setUp();

        if ( !( $this->sharedFixture instanceof eZMySQLDB ) )
        {
            self::markTestSkipped( "Not using mysql interface, skipping" );
        }

        // We need to clear the existing handler if it was loaded before the INI
        // settings changes
        if ( isset( $GLOBALS['eZClusterFileHandler_chosen_handler'] ) and
            !$GLOBALS['eZClusterFileHandler_chosen_handler'] instanceof eZDFSFileHandler )
            unset( $GLOBALS['eZClusterFileHandler_chosen_handler'] );

        // Load database parameters for cluster
        // The same DSN than the relational database is used
        $fileINI = eZINI::instance( 'file.ini' );
        $this->previousFileHandler = $fileINI->variable( 'ClusteringSettings', 'FileHandler', 'eZDFSFileHandler' );
        $fileINI->setVariable( 'ClusteringSettings', 'FileHandler', 'eZDFSFileHandler' );

        $dsn = ezpTestRunner::dsn()->parts;
        $fileINI->setVariable( 'eZDFSClusteringSettings', 'DBHost',    $dsn['host'] );
        $fileINI->setVariable( 'eZDFSClusteringSettings', 'DBPort',    $dsn['port'] );
        $fileINI->setVariable( 'eZDFSClusteringSettings', 'DBSocket',  $dsn['socket'] );
        $fileINI->setVariable( 'eZDFSClusteringSettings', 'DBName',    $dsn['database'] );
        $fileINI->setVariable( 'eZDFSClusteringSettings', 'DBUser',    $dsn['user'] );
        $fileINI->setVariable( 'eZDFSClusteringSettings', 'DBPassword', $dsn['password'] );
        $fileINI->setVariable( 'eZDFSClusteringSettings', 'MountPointPath', $this->DFSPath );

        if ( !file_exists( $this->DFSPath ) )
        {
            eZDir::doMkdir( $this->DFSPath, 0755 );
            $this->haveToRemoveDFSPath = true;
        }

        ezpTestDatabaseHelper::insertSqlData( $this->sharedFixture, $this->sqlFiles );

        $this->db = $this->sharedFixture;
    }

    public function tearDown()
    {
        // restore the previous file handler
        $fileINI = eZINI::instance( 'file.ini' );
        $fileINI->setVariable( 'ClusteringSettings', 'FileHandler', $this->previousFileHandler );
        $this->previousFileHandler = null;
        if ( isset( $GLOBALS['eZClusterFileHandler_chosen_handler'] ) )
            unset( $GLOBALS['eZClusterFileHandler_chosen_handler'] );

        if ( $this->haveToRemoveDFSPath )
        {
            eZDir::recursiveDelete( $this->DFSPath );
        }
        parent::tearDown();
    }

    /**
     * Tests if the local file $filePath exists
     *
     * @param string $filePath
     * @return bool
     */
    protected function localFileExists( $filePath )
    {
        clearstatcache();
        return file_exists( $filePath );
    }

    /**
     * Manually tests if the DB file $filePath exists
     *
     * @param string $filePath
     * @return bool
     */
    protected function DBFileExists( $filePath )
    {
        $escapedFilePath = mysql_real_escape_string( $filePath );
        $sql = "SELECT * FROM " . eZDFSFileHandlerMySQLBackend::TABLE_METADATA .
               " WHERE name_hash = MD5('{$escapedFilePath}')";
        $rows = $this->db->arrayQuery( $sql );
        if ( count( $rows ) == 1 )
            return true;
        return false;
    }

    /**
     * Manually tests if the DB file $filePath exists and is valid
     *
     * @param string $filePath
     * @return bool
     */
    protected function DBFileExistsAndIsValid( $filePath )
    {
        $escapedFilePath = mysql_real_escape_string( $filePath );
        $sql = "SELECT * FROM " . eZDFSFileHandlerMySQLBackend::TABLE_METADATA .
               " WHERE name LIKE '{$escapedFilePath}'";
        $rows = $this->db->arrayQuery( $sql );
        if ( count( $rows ) == 1 )
            return ( $rows[0]['expired'] != 1 );
        else
            return false;
    }

    /**
     * Tests if the file exists on the NFS mount point
     * @param string $filePath
     * @return bool
     */
    protected function DFSFileExists( $filePath )
    {
        clearstatcache();
        return file_exists( $this->makeDFSPath( $filePath ) );
    }

    /**
     * Removes the test file $filePath on FS, DFS and DB
     **/
    protected function removeFile( $filePath )
    {
        $DFSPath = $this->makeDFSPath( $filePath );
        if ( file_exists( $filePath ) )
            unlink( $filePath );
        if ( file_exists( $DFSPath ) and is_file( $DFSPath ) )
            unlink( $DFSPath );
        $this->db->query( 'DELETE FROM ' . eZDFSFileHandlerMySQLBackend::TABLE_METADATA .
                          ' WHERE name_hash = \'' . md5( $filePath ) . '\'' );
    }

    /**
     * Creates the file $filePath with contents $fileContents on DB + DFS
     *
     * The existing file will be removed unless $params[remove] is set to false
     *
     * @param string $filePath relative file path
     * @param string $fileContents file's content
     * @param array  $params
     *        Optional parameters for creation.
     *        Valid keys:datatype, scope, mtime, status, expired and remove
     *        if remove is set to true, the file will be removed before it is
     *        created
     * @return void
     **/
    protected function createFile( $filePath, $fileContents = 'foobar', $params = array() )
    {
        $datatype = isset( $params['datatype'] ) ? $params['datatype'] : 'text/test';
        $scope = isset( $params['scope'] ) ? $params['scope'] : 'test';
        $mtime = isset( $params['mtime'] ) ? $params['mtime'] : time();
        $status = isset( $params['status'] ) ? $params['status'] : 0;
        $expired = isset( $params['expired'] ) ? $params['expired'] : 0;
        $createLocalFile = isset( $params['create_local_file'] ) ? $params['create_local_file'] : false;
        $remove = isset( $params['remove'] ) ? $params['remove'] : true;

        if ( $remove )
        {
            $this->removeFile( $filePath );
        }

        $nameHash = md5( $filePath );
        $size = strlen( $fileContents );

        // create DB file
        $sql = "INSERT INTO " . eZDFSFileHandlerMySQLBackend::TABLE_METADATA .
               "      ( name,        name_trunk,  name_hash,   datatype,    scope,    size,    mtime,    expired,    status )" .
               "VALUES( '$filePath', '$filePath', '$nameHash', '$datatype', '$scope', '$size', '$mtime', '$expired', '$status' )";
        $this->db->query( $sql );

        // create DFS file
        $path = $this->makeDFSPath( $filePath );
        eZFile::create( basename( $path ), dirname( $path ), $fileContents );

        // create local file
        if ( $createLocalFile )
            eZFile::create( basename( $filePath ), dirname( $filePath ), $fileContents );
    }

    /**
     * Returns the DFS path for a given file
     * @param string $filePath local file path
     * @return string DFS file path
     **/
    protected function makeDFSPath( $filePath )
    {
        return $this->DFSPath . $filePath;
    }

    /**
     * Constructor test
     * Will test if the constructor returns the correct instance
     **/
    public function testConstructor()
    {
        $clusterHandler = eZClusterFileHandler::instance();
        $this->assertType( 'object', $clusterHandler,
            "eZClusterFileHandler::instance() didn't return an object" );
        $this->assertTrue( $clusterHandler instanceof eZDFSFileHandler,
            "eZClusterFileHandler::instance() didn't return an eZDFSFileHandler" );
    }

    /**
     * Tests storage of a new, non existent file to cluster
     **/
    public function testFileStore()
    {
        $testFile = 'var/testStore.txt';

        // create the file locally
        if ( $fp = fopen( $testFile, 'w') )
            fclose( $fp );
        else
            self::fail( "Unable to create the local test file" );

        // this stores the file to DFS+DB, and does NOT remove the local file
        $clusterHandler = eZClusterFileHandler::instance();
        $clusterHandler->fileStore( $testFile, $scope = 'test', $delete = false, $datatype = 'text/test' );

        $this->assertTrue( $this->DBFileExistsAndIsValid( $testFile ), "The DB file does not exist or is not valid" );
        $this->assertTrue( $this->DFSFileExists( $testFile ), "The DFS file does not exist" );
        $this->assertTrue( $this->localFileExists( $testFile ), "The local file no longer exists. It should not have been removed" );

        $this->removeFile( $testFile );
    }

    /**
    * Tests the fileStore() method, removing the local file afterwards
    **/
    public function testFileStoreDeleteFile()
    {
        $testFile = 'var/testStore.txt';

        // create the file locally
        if ( $fp = fopen( $testFile, 'w') )
            fclose( $fp );
        else
            self::fail( "Unable to create the local test file" );

        // this stores the file to DFS+DB, and does remove the local file
        $clusterHandler = eZClusterFileHandler::instance();
        $clusterHandler->fileStore( $testFile, $scope = 'test', $delete = true, $datatype = 'text/test' );

        $this->assertTrue( $this->DBFileExistsAndIsValid( $testFile ), "The DB file does not exist or is not valid" );
        $this->assertTrue( $this->DFSFileExists( $testFile ), "The DFS file does not exist" );
        $this->assertTrue( !$this->localFileExists( $testFile ), "The local file still exists. It should have been removed" );

        $this->removeFile( $testFile );
    }

    /**
     * Tests storage of a new file given a filename and binary content
     **/
    public function testFileStoreContents()
    {
        $testFile = "var/testStoreContents.txt";
        $testFileContents = 'file\'s content';
        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance();
        $clusterHandler->fileStoreContents( $testFile, $testFileContents, $scope = 'test', $datatype = 'text/test' );

        $this->assertTrue( $this->DBFileExistsAndIsValid( $testFile ), "The DB file does not exist or is not valid" );
        $this->assertTrue( $this->DFSFileExists( $testFile ), "The DFS file does not exist" );

        $this->removeFile( $testFile );
    }

    /**
    * Tests the non cluster-static method that stores a file's content
    **/
    public function testStoreContentsWithoutLocalStore()
    {
        $testFile = "var/testStoreContents.txt";
        $testFileContents = 'file\'s content';
        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $clusterHandler->storeContents( $testFileContents, $scope = 'test', $datatype = 'text/test', $storeLocally = false );

        $this->assertTrue( $this->DBFileExistsAndIsValid( $testFile ), "The DB file does not exist or is not valid" );
        $this->assertTrue( $this->DFSFileExists( $testFile ), "The DFS file does not exist" );
        $this->assertTrue( !$this->localFileExists( $testFile ), "The local file exists" );

        $this->removeFile( $testFile );
    }

    /**
     * Tests the non cluster-static method that stores a file's content
     **/
    public function testStoreContentsWithLocalStore()
    {
        $testFile = "var/testStoreContents.txt";
        $testFileContents = 'file\'s content';
        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $clusterHandler->storeContents( $testFileContents, $scope = 'test', $datatype = 'text/test', $storeLocally = true );

        $this->assertTrue( $this->DBFileExistsAndIsValid( $testFile ), "The DB file does not exist or is not valid" );
        $this->assertTrue( $this->DFSFileExists( $testFile ), "The DFS file does not exist" );
        $this->assertTrue( $this->localFileExists( $testFile ), "The local file does not exist" );

        $this->removeFile( $testFile );
    }

    /**
    * Tests the fileFetch method.
    *
    * Should locally fetch a file located on DB+DFS
    **/
    public function testFileFetchExistingFile()
    {
        $testFile = 'var/testFileForTestFileFetch.txt';

        $this->createFile( $testFile, 'This is the content' );

        // after fetching the file, it should exist locally
        $clusterHandler = eZClusterFileHandler::instance();
        $fetchedFile = $clusterHandler->fileFetch( $testFile );

        $this->assertSame( $testFile, $fetchedFile, "Fetched filename mismatch" );
        $this->assertTrue( $this->localFileExists( $testFile ), "Local file $testFile could not be found" );

        $this->removeFile( $testFile );
    }

    /**
     * Tests the fileFetch method on a non existing file
     *
     * Should locally fetch a file located on DB+DFS
     **/
    public function testFileFetchNonExistingFile()
    {
        $testFile = 'var/testFileForTestFileFetchNonExistingFile.txt';

        $this->removeFile( $testFile );

        // fileFetch() should return false as the file doesn't exist
        $clusterHandler = eZClusterFileHandler::instance();
        $fetchedFile = $clusterHandler->fileFetch( $testFile );

        $this->assertFalse( $fetchedFile, "eZDFS told that the file exists. It sure doesn't" );
        $this->assertFalse( $this->localFileExists( $testFile ), "FS file exists" );
    }

    /**
     * Tests eZDFSFileHandler->fetch() on an existing file
     **/
    public function testFetchExistingFile()
    {
        $testFile = 'var/testFileForTestFetchExistingFile.txt';
        $this->createFile( $testFile, 'This is the content' );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $fetchedFile = $clusterHandler->fetch();
        $this->assertSame( $testFile, $fetchedFile, "Fetched filename was not returned" );
        $this->assertTrue( $this->localFileExists( $testFile ), "Local file doesn't exist" );

        $this->removeFile( $testFile );
    }

    /**
     * Tests eZDFSFileHandler->fetch() on a non-existing file
     **/
    public function testFetchNonExistingFile()
    {
        $testFile = 'var/testFileForTestFetchExistingFile.txt';
        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $fetchedFile = $clusterHandler->fetch();
        $this->assertFalse( $fetchedFile, "Fetching should have failed" );
        $this->assertFalse( $this->localFileExists( $testFile ), "Local file does exist" );

        $this->removeFile( $testFile );
    }

    /**
     * Tests fileExists on an existing file
     **/
    public function testFileExistsExistingFile()
    {
        $testFile = 'var/testFileForTestExistsExistingFile.txt';

        $this->createFile( $testFile, 'This is the content' );

        $clusterHandler = eZClusterFileHandler::instance();
        $doesExist = $clusterHandler->fileExists( $testFile );

        $this->assertTrue( $doesExist, "The file should exist on DFS+DB" );
    }

    /**
     * Tests eZDFSFileHandler::fileExists() on a non-existing file
     **/
    public function testFileExistsNonExistingFile()
    {
        $testFile = 'var/testFileForTestExistsExistingFile.txt';

        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance();
        $doesExist = $clusterHandler->fileExists( $testFile );

        $this->assertFalse( $doesExist, "The file should NOT exist on DFS+DB" );
    }

    /**
     * Tests fileExists on an existing file
     **/
    public function testExistsExistingFile()
    {
        $testFile = 'var/testFileForTestExistsExistingFile.txt';

        $this->createFile( $testFile, 'This is the content' );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );

        $this->assertTrue( $clusterHandler->exists(), "The file should exist on DFS+DB" );

        $this->removeFile( $testFile );
    }

    /**
     * Tests eZDFSFileHandler::fileExists() on a non-existing file
     **/
    public function testExistsNonExistingFile()
    {
        $testFile = 'var/testFileForTestExistsExistingFile.txt';

        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );

        $this->assertFalse( $clusterHandler->exists(), "The file should NOT exist on DFS+DB" );
    }

    public function testFetchUniqueExistingFile()
    {
        $testFile = 'var/testFileForTestFetchUniqueExistingFile.txt';
        $this->createFile( $testFile, "contents" );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $fetchedFile = $clusterHandler->fetchUnique();

        $this->assertNotSame( $testFile, $fetchedFile, "A unique name should have been returned" );
        $this->assertTrue( $this->localFileExists( $fetchedFile ), "The locally fetched unique file doesn't exist" );

        $this->removeFile( $testFile );
        $this->removeFile( $fetchedFile );
    }

    public function testFetchUniqueNonExistingFile()
    {
        $testFile = 'var/testFileForTestFetchUniqueNonExistingFile.txt';
        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $fetchedFile = $clusterHandler->fetchUnique();

        $this->assertFalse( $fetchedFile, "fetchUnique should have returned false" );

        $this->removeFile( $testFile );
        $this->removeFile( $fetchedFile );
    }

    public function testFileFetchContentsExistingFile()
    {
        $testFile = 'var/testFileForTestFileFetchContentsExistingFile.txt';
        $contents = 'This is the file contents';
        $this->createFile( $testFile, $contents );

        $clusterHandler = eZClusterFileHandler::instance();
        $fetchedContents = $clusterHandler->fileFetchContents( $testFile );

        $this->assertEquals( $contents, $fetchedContents, "Fetched contents mismatches" );

        $this->removeFile( $testFile );
    }

    public function testFileFetchContentsNonExistingFile()
    {
        $testFile = 'var/testFileForTestFileFetchContentsNonExistingFile.txt';
        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance();
        $fetchedContents = $clusterHandler->fileFetchContents( $testFile );

        $this->assertFalse( $fetchedContents );

        $this->removeFile( $testFile );
    }

    public function testFetchContentsExistingFile()
    {
        $testFile = 'var/testFileForTestFetchContentsExistingFile.txt';
        $contents = 'This is the file contents';
        $this->createFile( $testFile, $contents );

        $clusterHandler = eZClusterFileHandler::instance( $testFile);
        $fetchedContents = $clusterHandler->fetchContents();

        $this->assertEquals( $contents, $fetchedContents, "Fetched contents mismatches" );

        $this->removeFile( $testFile );
    }

    public function testFetchContentsNonExistingFile()
    {
        $testFile = 'var/testFileForTestFileFetchContentsNonExistingFile.txt';
        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $fetchedContents = $clusterHandler->fetchContents();

        $this->assertFalse( $fetchedContents );

        $this->removeFile( $testFile );
    }

    public function testIsFileExpired()
    {
        $fname = __METHOD__;

        // Negative mtime: expired
        $mtime = -1;
        $expiry = -1;
        $curtime = time();
        $ttl = null;
        $result = eZDFSFileHandler::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        $this->assertTrue( $result, "negative mtime: expired expected" );

        // FALSE mtime: expired
        $mtime = false;
        $expiry = -1;
        $curtime = time();
        $ttl = null;
        $result = eZDFSFileHandler::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        $this->assertTrue( $result, "false mtime: expired expected" );

        // NULL TTL + mtime < expiry: expired
        $mtime = time() - 3600; // mtime < expiry
        $expiry = time();
        $curtime = time();
        $ttl = null;
        $result = eZDFSFileHandler::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        $this->assertTrue( $result,
            "no TTL + mtime < expiry: expired expected" );

        // NULL TTL + mtime > expiry: not expired
        $mtime = time();
        $expiry = time() - 3600; // expires in the future
        $curtime = time();
        $ttl = null;
        $result = eZDFSFileHandler::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        $this->assertFalse( $result,
            "no TTL + mtime > expiry: not expired expected" );

        // TTL != null, mtime < curtime - ttl: expired
        $mtime = time();
        $expiry = -1; // disable expiry check
        $curtime = time();
        $ttl = 60; // 60 seconds TTL
        $result = eZDFSFileHandler::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        $this->assertFalse( $result,
            "TTL + ( mtime < ( curtime - ttl ) ): !expired expected" );

        // TTL != null, mtime > curtime - ttl: not expired
        $mtime = time() - 90; // old file
        $expiry = -1; // disable expiry check
        $curtime = time();
        $ttl = 60; // 60 seconds TTL
        $result = eZDFSFileHandler::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        $this->assertTrue( $result,
            "TTL + ( mtime > ( curtime - ttl ) ): expired expected" );

        // TTL != null, mtime < expiry: expired
        $mtime = time() - 90; // old file
        $expiry = time(); // file is expired
        $curtime = time();
        $ttl = 60; // 60 seconds TTL
        $result = eZDFSFileHandler::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        $this->assertTrue( $result,
            "TTL + ( mtime < expiry ): expired expected" );

        // TTL != null, mtime > expiry: not expired
        $mtime = time();
        $expiry = time() - 90;
        $curtime = time();
        $ttl = 60;
        $result = eZDFSFileHandler::isFileExpired( $fname, $mtime, $expiry, $curtime, $ttl);
        $this->assertFalse( $result,
            "TTL + ( mtime > expiry ): !expired expected" );
    }

    public function testIsExpired()
    {
        // file will be created with current time as mtime()
        $testFile = 'var/testFileForTestIsExpired.txt';
        $this->createFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );

        // expiry date < mtime / no TTL: !expired
        $this->assertFalse( $clusterHandler->isExpired( $expiry = time() - 3600, time(), null ),
            "expiry date < mtime, no TTL, !expired expected" );

        // expiry date > mtime / no TTL: !expired
        $this->assertTrue( $clusterHandler->isExpired( $expiry = time() + 3600, time(), null ),
            "expiry date > mtime, no TTL, expired expected" );

        // mtime < curtime - ttl: !expired
        $this->assertFalse( $clusterHandler->isExpired( $expiry = -1, time(), 60 ),
            "mtime < curtime - ttl: !expired expected" );

        // mtime > curtime - ttl: expired
        $this->assertTrue( $clusterHandler->isExpired( $expiry = -1, time(), -60 ),
            "mtime > curtime - ttl: expired expected" );
    }

    public function testIsExpiredNegativeMtime()
    {
        // negative mtime: expired
        // file will be created with current time as mtime()
        $testFile = 'var/testFileForTestIsExpiredWithNegativeMtime.txt';
        $this->createFile( $testFile, 'contents', array( 'mtime' => time() * -1 ) );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $this->assertTrue( $clusterHandler->isExpired( $expiry = time() - 3600, time(), null ),
            "negative mtime, no TTL, expired expected" );

        $this->removeFile( $testFile );
    }

    public function testIsLocalFileExpired()
    {
        // file will be created with current time as mtime()
        $testFile = 'var/testFileForTestIsLocalFileExpired.txt';
        $this->createFile( $testFile, 'foobar', array( 'create_local_file' => true ) );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $this->assertFalse( $clusterHandler->isLocalFileExpired( $expiry = time() - 3600, time(), null ),
            "mtime > expiry, !expired expected" );
        $this->assertTrue( $clusterHandler->isLocalFileExpired( $expiry = time() + 3600, time(), null ),
            "mtime < expiry, expired expected" );

        $this->removeFile( $testFile );
    }

    public function testIsDBFileExpired()
    {
        // file will be created with current time as mtime()
        $testFile = 'var/testFileForTestIsLocalFileExpired.txt';
        $this->createFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $this->assertFalse( $clusterHandler->isDBFileExpired( $expiry = time() - 3600, time(), null ),
            "mtime > expiry, !expired expected" );
        $this->assertTrue( $clusterHandler->isDBFileExpired( $expiry = time() + 3600, time(), null ),
            "mtime < expiry, expired expected" );

        $this->removeFile( $testFile );
    }

    public function testStatExistingFile()
    {
        $testFile = 'var/testStatExistingFile.txt';
        $this->createFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $stat = $clusterHandler->stat();
        $this->assertType( PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $stat );
        $this->assertArrayHasKey( 'name', $stat );

        $this->removeFile( $testFile );
    }

    public function testStatNonExistingFile()
    {
        $testFile = 'var/testStatNonExistingFile.txt';
        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $stat = $clusterHandler->stat();
        $this->assertFalse( $stat );

        $this->removeFile( $testFile );
    }

    public function testSizeExistingFile()
    {
        $testFile = 'var/testSizeExistingFile.txt';
        $contents = 'mycontents';

        $this->createFile( $testFile, $contents );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $size = $clusterHandler->size();
        $this->assertType( PHPUnit_Framework_Constraint_IsType::TYPE_INT, $size );
        $this->assertEquals( strlen( $contents ), $size );

        $this->removeFile( $testFile );
    }

    public function testSizeNonExistingFile()
    {
        $testFile = 'var/testSizeNonExistingFile.txt';

        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $size = $clusterHandler->size();
        $this->assertTrue( $size === null);
    }

    public function testMtimeExistingFile()
    {
        $testFile = 'var/testMtimeExistingFile.txt';
        $contents = 'mycontents';
        $curtime = time();

        $this->createFile( $testFile, $contents, array( 'mtime' => $curtime) );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $mtime = $clusterHandler->mtime();
        $this->assertType( PHPUnit_Framework_Constraint_IsType::TYPE_INT, $mtime );
        $this->assertEquals( $curtime, $mtime );

        $this->removeFile( $testFile );
    }

    public function testMtimeNonExistingFile()
    {
        $testFile = 'var/testMtimeNonExistingFile.txt';

        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $mtime = $clusterHandler->mtime();
        $this->assertTrue( $mtime === null);
    }

    public function testName()
    {
        $testFile = 'var/testNameExistingFile.txt';

        $this->createFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $name = $clusterHandler->name();
        $this->assertType( PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $name );
        $this->assertEquals( $testFile, $name );

        $this->removeFile( $testFile );
    }

    public function testFileDelete()
    {
        $testFile = 'var/testFileDelete.txt';

        $this->createFile( $testFile );

        // the file should exist on DFS
        $this->assertTrue( $this->DFSFileExists( $testFile ), "File no longer exists on DFS" );

        // the file should be valid in DB
        $this->assertTrue( $this->DBFileExistsAndIsValid( $testFile ), "File is still valid in DB" );

        $clusterHandler = eZClusterFileHandler::instance();
        $clusterHandler->fileDelete( $testFile );

        // the file should still be on DFS
        $this->assertTrue( $this->DFSFileExists( $testFile ), "File no longer exists on DFS" );

        // the file should not longer be valid on DB (expired)
        $this->assertFalse( $this->DBFileExistsAndIsValid( $testFile ), "File is still valid in DB" );
    }

    public function testDelete()
    {
        $testFile = 'var/testDelete.txt';

        $this->createFile( $testFile );

        // the file should exist on DFS
        $this->assertTrue( $this->DFSFileExists( $testFile ), "File no longer exists on DFS" );

        // the file should be valid in DB
        $this->assertTrue( $this->DBFileExistsAndIsValid( $testFile ), "File is still valid in DB" );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $clusterHandler->delete();

        // the file should still be on DFS
        $this->assertTrue( $this->DFSFileExists( $testFile ), "File no longer exists on DFS" );

        // the file should not longer be valid on DB (expired)
        $this->assertFalse( $this->DBFileExistsAndIsValid( $testFile ), "File is still valid in DB" );
    }

    public function testFileDeleteLocal()
    {
        $testFile = 'var/testFileDeleteLocal.txt';

        $clusterHandler = eZClusterFileHandler::instance();
        $clusterHandler->fileStoreContents( $testFile, 'contents', 'text/test', 'test' );
        $clusterHandler->fileFetch( $testFile );

        // test if the local file was correctly fetched
        $this->assertTrue( $this->localFileExists( $testFile ), "Local file should exist" );

        // delete the locally fetched file
        $clusterHandler->fileDeleteLocal( $testFile );

        $this->assertFalse( $this->localFileExists( $testFile ), "Local file should no longer exist" );
    }

    public function testDeleteLocal()
    {
        $testFile = 'var/testDeleteLocal.txt';

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $clusterHandler->storeContents( 'contents', 'text/test', 'test', $storeLocally = true );

        // test if the local file was correctly fetched
        $this->assertTrue( $this->localFileExists( $testFile ), "Local file should exist" );

        // delete the locally fetched file
        $clusterHandler->deleteLocal( $testFile );

        $this->assertFalse( $this->localFileExists( $testFile ), "Local file should no longer exist" );
    }

    /**
    * Expects the file we copy to to exists
    **/
    public function testFileCopy()
    {
        $testFile = 'var/testFileCopy.txt';
        $testFileCopy = 'var/testFileCopyCopy.txt';
        $this->createFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance();
        $clusterHandler->fileCopy( $testFile, $testFileCopy );

        $this->assertTrue( $this->DBFileExistsAndIsValid( $testFileCopy ), "DB file should exist" );
        $this->assertTrue( $this->DFSFileExists( $testFileCopy ), "DFS file should exist" );

        $this->removeFile( $testFile );
        $this->removeFile( $testFileCopy );
    }

    /**
     * Expects the old name to no longer exist and the new one to exist
     **/
    public function testFileMove()
    {
        $testFile = 'var/testFileMove.txt';
        $testFileMoved = 'var/testFileMoveMoved.txt';
        $this->createFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance();
        $clusterHandler->fileMove( $testFile, $testFileMoved );

        $this->assertFalse( $this->DBFileExists( $testFile ), "old DB file should no longer exist" );
        $this->assertTrue( $this->DBFileExists( $testFileMoved ), "new DB file should exist" );
        $this->assertFalse( $this->DFSFileExists( $testFile ), "old DFS file should exist" );
        $this->assertTrue( $this->DFSFileExists( $testFileMoved ), "new DFS file should exist" );

        $this->removeFile( $testFile );
        $this->removeFile( $testFileMoved );
    }

    public function testMove()
    {
        $testFile = 'var/testMove.txt';
        $testFileMoved = 'var/testMoveMoved.txt';
        $this->createFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $clusterHandler->move( $testFileMoved );

        $this->assertFalse( $this->DBFileExists( $testFile ), "old DB file should no longer exist" );
        $this->assertTrue( $this->DBFileExists( $testFileMoved ), "new DB file should exist" );
        $this->assertFalse( $this->DFSFileExists( $testFile ), "old DFS file should exist" );
        $this->assertTrue( $this->DFSFileExists( $testFileMoved ), "new DFS file should exist" );

        $this->removeFile( $testFile );
        $this->removeFile( $testFileMoved );
    }

    public function testPurgeSingleFile()
    {
        $this->createFile( $testFile = 'var/testPurge.txt',
                           'contents',
                           array( 'expired' => 1 ) );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $clusterHandler->purge();

        $this->assertFalse( $this->DBFileExists( $testFile ), "DB file still exists" );
        $this->assertFalse( $this->DFSFileExists( $testFile ), "DFS file still exists" );
        $this->assertFalse( $this->localFileExists( $testFile ), "local file still exists" );

        $this->removeFile( $testFile );
    }

    public function testPurgeMultipleFiles()
    {
        $createParams = array( 'expired' => 1, 'create_local_file' => 1 );

        // create multiple files in a folder for deletion
        for( $i = 0; $i < 10; $i++ )
        {
            $files[$i] = "var/testPurge/MultipleFiles-{$i}";
            $this->createFile( $files[$i], 'foocontent', $createParams );
        }
        // and a few other files to check if we don't delete anything that shouldn't be
        for( $i = 0; $i < 5; $i++ )
        {
            $otherFiles[$i] = "var/testOtherFiles/File-{$i}";
            $this->createFile( $otherFiles[$i], 'foocontent', $createParams );
        }

        $clusterHandler = eZClusterFileHandler::instance( 'var/testPurge' );
        $clusterHandler->purge();

        // check if files supposed to be deleted were
        foreach( $files as $file )
        {
            $this->assertFalse( $this->DBFileExists( $file ), "DB file $file still exists" );
            $this->assertFalse( $this->DFSFileExists( $file ), "DFS file $file still exists" );
            $this->assertFalse( $this->localFileExists( $file ), "local file $file still exists" );
        }

        // and if files not supposed to be deleted weren't
        foreach( $otherFiles as $file )
        {
            $this->assertTrue( $this->DBFileExists( $file ), "DB file $file has been removed" );
            $this->assertTrue( $this->DFSFileExists( $file ), "DFS file $file has been removed" );
            $this->assertTrue( $this->localFileExists( $file ), "local file $file has been removed" );
        }

        // remove all of 'em
        foreach( array_merge( $files, $otherFiles ) as $file )
        {
            $this->removeFile( $file );
        }
    }

    public function testRequiresClusterizing()
    {
        $handler = eZClusterFileHandler::instance();
        $this->assertTrue( $handler->requiresClusterizing() );
    }
}
?>