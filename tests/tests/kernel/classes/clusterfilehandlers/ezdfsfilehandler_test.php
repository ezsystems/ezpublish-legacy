<?php
/**
 * File containing the eZDFSFileHandlerTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @package tests
 */

/**
 * eZDFSFileHandler tests
 * @group cluster
 * @group eZDFS
 */
class eZDFSFileHandlerTest extends eZDBBasedClusterFileHandlerAbstractTest
{
    /**
     * @var string
     **/
    protected static $DFSPath = 'var/dfsmount/';

    protected $backupGlobals = false;

    protected $haveToRemoveDFSPath = false;

    /**
     * @var array
     **/
    protected $sqlFiles = array( 'kernel/sql/', 'cluster_dfs_schema.sql' );

    protected $clusterClass = 'eZDFSFileHandler';

    protected static $tableDefault = 'ezdfsfile';

    protected static $tableCache = 'ezdfsfile_cache';

    /* // Commented since __construct breaks data providers
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZDFSClusterFileHandler Unit Tests" );
    }*/

    /**
     * Test setup
     *
     * Load an instance of file.ini
     * Assigns DB parameters for cluster
     **/
    public function setUp()
    {
        parent::setUp();
        eZClusterFileHandler::resetHandler();

        self::setUpDatabase( $this->clusterClass );

        if ( !file_exists( self::$DFSPath ) )
        {
            eZDir::doMkdir( self::$DFSPath, 0755 );
            $this->haveToRemoveDFSPath = true;
        }

        $this->db = eZDB::instance();
    }

    public static function setUpDatabase()
    {
        $dsn = ezpTestRunner::dsn()->parts;
        switch ( $dsn['phptype'] )
        {
            case 'mysql':
            case 'mysqli':
                $backend = 'eZDFSFileHandlerMySQLiBackend';
                break;

            case 'postgresql':
                $backend = 'eZDFSFileHandlerPostgresqlBackend';
                if ( !class_exists( 'eZDFSFileHandlerPostgresqlBackend' ) )
                    self::markTestSkipped( "Missing extension 'ezpostgresqlcluster', skipping PostgreSQL DFS tests" );
                break;

            default:
                self::markTestSkipped( "Unsupported database type '{$dsn['phptype']}'" );
        }

        // We need to clear the existing handler if it was loaded before the INI
        // settings changes
        eZClusterFileHandler::resetHandler();

        unset( $GLOBALS['eZClusterInfo'] );

        // Load database parameters for cluster
        // The same DSN than the relational database is used
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'FileHandler', 'eZDFSFileHandler' );
        ezpINIHelper::setINISetting( 'file.ini', 'eZDFSClusteringSettings', 'DBHost', $dsn['host'] );
        ezpINIHelper::setINISetting( 'file.ini', 'eZDFSClusteringSettings', 'DBPort', $dsn['port'] );
        ezpINIHelper::setINISetting( 'file.ini', 'eZDFSClusteringSettings', 'DBSocket', $dsn['socket'] );
        ezpINIHelper::setINISetting( 'file.ini', 'eZDFSClusteringSettings', 'DBName', $dsn['database'] );
        ezpINIHelper::setINISetting( 'file.ini', 'eZDFSClusteringSettings', 'DBUser', $dsn['user'] );
        ezpINIHelper::setINISetting( 'file.ini', 'eZDFSClusteringSettings', 'DBPassword', $dsn['password'] );
        ezpINIHelper::setINISetting( 'file.ini', 'eZDFSClusteringSettings', 'MountPointPath', self::getDfsPath() );
        ezpINIHelper::setINISetting( 'file.ini', 'eZDFSClusteringSettings', 'DBBackend', $backend );
    }

    public function tearDown()
    {
        ezpINIHelper::restoreINISettings();
        eZClusterFileHandler::resetHandler();

        if ( $this->haveToRemoveDFSPath )
        {
            eZDir::recursiveDelete( self::$DFSPath );
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
        $escapedFilePath = $this->db->escapeString( $filePath );
        $sql = "SELECT * FROM " . self::$tableDefault .
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
        $escapedFilePath = $this->db->escapeString( $filePath );
        $sql = "SELECT * FROM " . self::$tableDefault .
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
        $this->db->query( 'DELETE FROM ' . self::$tableDefault .
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
     *        Valid keys:datatype, scope, mtime, expired and remove
     *        if remove is set to true, the file will be removed before it is
     *        created
     * @return void
     **/
    protected function createFile( $filePath, $fileContents = 'foobar', $params = array() )
    {
        $datatype = isset( $params['datatype'] ) ? $params['datatype'] : 'text/test';
        $scope = isset( $params['scope'] ) ? $params['scope'] : 'test';
        $mtime = isset( $params['mtime'] ) ? $params['mtime'] : time();
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
        $sql = "INSERT INTO " . self::$tableDefault .
               "      ( name,        name_trunk,  name_hash,   datatype,    scope,    size,    mtime,    expired )" .
               "VALUES( '$filePath', '$filePath', '$nameHash', '$datatype', '$scope', '$size', '$mtime', '$expired' )";
        $this->db->query( $sql );

        // create DFS file
        $path = $this->makeDFSPath( $filePath );
        eZFile::create( basename( $path ), dirname( $path ), $fileContents );

        // create local file
        if ( $createLocalFile )
            eZFile::create( basename( $filePath ), dirname( $filePath ), $fileContents );

        return eZClusterFileHandler::instance( $filePath );
    }

    /**
     * Returns the DFS path for a given file
     * @param string $filePath local file path
     * @return string DFS file path
     **/
    protected function makeDFSPath( $filePath )
    {
        return self::$DFSPath . $filePath;
    }

    /**
     * Constructor test
     * Will test if the constructor returns the correct instance
     **/
    public function testConstructor()
    {
        $clusterHandler = eZClusterFileHandler::instance();
        self::assertInternalType( 'object', $clusterHandler,
            "eZClusterFileHandler::instance() didn't return an object" );
        self::assertInstanceOf( 'eZDFSFileHandler', $clusterHandler,
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

        self::assertTrue( $this->DBFileExistsAndIsValid( $testFile ), "The DB file does not exist or is not valid" );
        self::assertTrue( $this->DFSFileExists( $testFile ), "The DFS file does not exist" );
        self::assertTrue( $this->localFileExists( $testFile ), "The local file no longer exists. It should not have been removed" );

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

        self::assertTrue( $this->DBFileExistsAndIsValid( $testFile ), "The DB file does not exist or is not valid" );
        self::assertTrue( $this->DFSFileExists( $testFile ), "The DFS file does not exist" );
        self::assertTrue( !$this->localFileExists( $testFile ), "The local file still exists. It should have been removed" );

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

        self::assertTrue( $this->DBFileExistsAndIsValid( $testFile ), "The DB file does not exist or is not valid" );
        self::assertTrue( $this->DFSFileExists( $testFile ), "The DFS file does not exist" );

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

        self::assertTrue( $this->DBFileExistsAndIsValid( $testFile ), "The DB file does not exist or is not valid" );
        self::assertTrue( $this->DFSFileExists( $testFile ), "The DFS file does not exist" );
        self::assertTrue( !$this->localFileExists( $testFile ), "The local file exists" );

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

        self::assertTrue( $this->DBFileExistsAndIsValid( $testFile ), "The DB file does not exist or is not valid" );
        self::assertTrue( $this->DFSFileExists( $testFile ), "The DFS file does not exist" );
        self::assertTrue( $this->localFileExists( $testFile ), "The local file does not exist" );

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

        self::assertTrue( $doesExist, "The file should exist on DFS+DB" );
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

        self::assertFalse( $doesExist, "The file should NOT exist on DFS+DB" );
    }

    /**
     * Tests fileExists on an existing file
     **/
    public function testExistsExistingFile()
    {
        $testFile = 'var/testFileForTestExistsExistingFile.txt';

        $this->createFile( $testFile, 'This is the content' );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );

        self::assertTrue( $clusterHandler->exists(), "The file should exist on DFS+DB" );

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

        self::assertFalse( $clusterHandler->exists(), "The file should NOT exist on DFS+DB" );
    }

    public function testFetchUniqueExistingFile()
    {
        $testFile = 'var/testFileForTestFetchUniqueExistingFile.txt';
        $this->createFile( $testFile, "contents" );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $fetchedFile = $clusterHandler->fetchUnique();

        self::assertNotSame( $testFile, $fetchedFile, "A unique name should have been returned" );
        self::assertTrue( $this->localFileExists( $fetchedFile ), "The locally fetched unique file doesn't exist" );

        $this->removeFile( $testFile );
        $this->removeFile( $fetchedFile );
    }

    public function testFetchUniqueNonExistingFile()
    {
        $testFile = 'var/testFileForTestFetchUniqueNonExistingFile.txt';
        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $fetchedFile = $clusterHandler->fetchUnique();

        self::assertFalse( $fetchedFile, "fetchUnique should have returned false" );

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

        self::assertEquals( $contents, $fetchedContents, "Fetched contents mismatches" );

        $this->removeFile( $testFile );
    }

    public function testFileFetchContentsNonExistingFile()
    {
        $testFile = 'var/testFileForTestFileFetchContentsNonExistingFile.txt';
        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance();
        $fetchedContents = $clusterHandler->fileFetchContents( $testFile );

        self::assertFalse( $fetchedContents );

        $this->removeFile( $testFile );
    }

    public function testFetchContentsExistingFile()
    {
        $testFile = 'var/testFileForTestFetchContentsExistingFile.txt';
        $contents = 'This is the file contents';
        $this->createFile( $testFile, $contents );

        $clusterHandler = eZClusterFileHandler::instance( $testFile);
        $fetchedContents = $clusterHandler->fetchContents();

        self::assertEquals( $contents, $fetchedContents, "Fetched contents mismatches" );

        $this->removeFile( $testFile );
    }

    public function testFetchContentsNonExistingFile()
    {
        $testFile = 'var/testFileForTestFileFetchContentsNonExistingFile.txt';
        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $fetchedContents = $clusterHandler->fetchContents();

        self::assertFalse( $fetchedContents );

        $this->removeFile( $testFile );
    }

    public function testIsExpiredNegativeMtime()
    {
        // negative mtime: expired
        // file will be created with current time as mtime()
        $testFile = 'var/testFileForTestIsExpiredWithNegativeMtime.txt';
        $this->createFile( $testFile, 'contents', array( 'mtime' => time() * -1 ) );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        self::assertTrue( $clusterHandler->isExpired( $expiry = time() - 3600, time(), null ),
            "negative mtime, no TTL, expired expected" );

        $this->removeFile( $testFile );
    }

    public function testIsLocalFileExpired()
    {
        // file will be created with current time as mtime()
        $testFile = 'var/testFileForTestIsLocalFileExpired.txt';
        $this->createFile( $testFile, 'foobar', array( 'create_local_file' => true ) );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        self::assertFalse( $clusterHandler->isLocalFileExpired( $expiry = time() - 3600, time(), null ),
            "mtime > expiry, !expired expected" );
        self::assertTrue( $clusterHandler->isLocalFileExpired( $expiry = time() + 3600, time(), null ),
            "mtime < expiry, expired expected" );

        $this->removeFile( $testFile );
    }

    public function testIsDBFileExpired()
    {
        // file will be created with current time as mtime()
        $testFile = 'var/testFileForTestIsLocalFileExpired.txt';
        $this->createFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        self::assertFalse( $clusterHandler->isDBFileExpired( $expiry = time() - 3600, time(), null ),
            "mtime > expiry, !expired expected" );
        self::assertTrue( $clusterHandler->isDBFileExpired( $expiry = time() + 3600, time(), null ),
            "mtime < expiry, expired expected" );

        $this->removeFile( $testFile );
    }

    public function testStatExistingFile()
    {
        $testFile = 'var/testStatExistingFile.txt';
        $this->createFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $stat = $clusterHandler->stat();
        self::assertInternalType( 'array', $stat );
        self::assertArrayHasKey( 'name', $stat );

        $this->removeFile( $testFile );
    }

    public function testStatNonExistingFile()
    {
        $testFile = 'var/testStatNonExistingFile.txt';
        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $stat = $clusterHandler->stat();
        self::assertFalse( $stat );

        $this->removeFile( $testFile );
    }

    public function testSizeExistingFile()
    {
        $testFile = 'var/testSizeExistingFile.txt';
        $contents = 'mycontents';

        $this->createFile( $testFile, $contents );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $size = $clusterHandler->size();
        self::assertInternalType( "int", $size );
        self::assertEquals( strlen( $contents ), $size );

        $this->removeFile( $testFile );
    }

    public function testSizeNonExistingFile()
    {
        $testFile = 'var/testSizeNonExistingFile.txt';

        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $size = $clusterHandler->size();
        self::assertTrue( $size === null);
    }

    public function testMtimeExistingFile()
    {
        $testFile = 'var/testMtimeExistingFile.txt';
        $contents = 'mycontents';
        $curtime = time();

        $this->createFile( $testFile, $contents, array( 'mtime' => $curtime) );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $mtime = $clusterHandler->mtime();
        self::assertInternalType( 'int', $mtime );
        self::assertEquals( $curtime, $mtime );

        $this->removeFile( $testFile );
    }

    public function testMtimeNonExistingFile()
    {
        $testFile = 'var/testMtimeNonExistingFile.txt';

        $this->removeFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $mtime = $clusterHandler->mtime();
        self::assertTrue( $mtime === null);
    }

    public function testName()
    {
        $testFile = 'var/testNameExistingFile.txt';

        $this->createFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $name = $clusterHandler->name();
        self::assertInternalType( 'string', $name );
        self::assertEquals( $testFile, $name );

        $this->removeFile( $testFile );
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

        self::assertTrue( $this->DBFileExistsAndIsValid( $testFileCopy ), "DB file should exist" );
        self::assertTrue( $this->DFSFileExists( $testFileCopy ), "DFS file should exist" );

        $this->removeFile( $testFile );
        $this->removeFile( $testFileCopy );
    }

    public static function getDfsPath()
    {
        return self::$DFSPath;
    }
}
?>
