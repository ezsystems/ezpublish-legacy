<?php
/**
 * File containing the eZDBFileHandlerTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

/**
 * eZDBFileHandler tests
 * @group cluster
 * @group eZDB
 */
class eZDBFileHandlerTest extends eZDBBasedClusterFileHandlerAbstractTest
{
    /**
     * @var array
     **/
    protected $sqlFiles = array( array( 'kernel/sql/', 'cluster_db_schema.sql' ) );

    protected $clusterClass = 'eZDBFileHandler';

    /**
     * Test setup
     *
     * Load an instance of file.ini
     * Assigns DB parameters for cluster
     **/
    public function setUp()
    {
        parent::setUp();

        $dsn = ezpTestRunner::dsn()->parts;
        switch ( $dsn['phptype'] )
        {
            case 'mysql':
            case 'mysqli':
                $backend = 'eZDBFileHandlerMysqliBackend';
                break;

            case 'postgresql':
                $backend = 'eZDBFileHandlerPostgresqlBackend';
                break;

            default:
                $this->markTestSkipped( "Unsupported database type '{$dsn['phptype']}'" );
        }

        // We need to clear the existing handler if it was loaded before the INI
        // settings changes
        eZClusterFileHandler::resetHandler();

        // We need to clear the existing handler if it was loaded before the INI
        // settings changes
        eZClusterFileHandler::resetHandler();

        unset( $GLOBALS['eZClusterInfo'] );

        // Load database parameters for cluster
        // The same DSN than the relational database is used
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'FileHandler', $this->clusterClass );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBHost', $dsn['host'] );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBPort', $dsn['port'] );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBSocket', $dsn['socket'] );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBName', $dsn['database'] );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBUser', $dsn['user'] );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBPassword', $dsn['password'] );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBBackend', $backend );

        $this->db = $this->sharedFixture;
    }

    public function tearDown()
    {
        ezpINIHelper::restoreINISettings();
        eZClusterFileHandler::resetHandler();

        parent::tearDown();
    }

    public function testPurgeMultipleFiles()
    {
        // create multiple files in a folder for deletion
        for( $i = 0; $i < 10; $i++ )
        {
            $files[$i] = 'var/tests/' . __FUNCTION__ . "/MultipleFiles/{$i}.txt";
            $ch = $this->createFile( $files[$i] );
            $ch->fetch();
            $ch->delete();
        }
        // and a few other files to check if we don't delete anything that shouldn't be
        for( $i = 0; $i < 5; $i++ )
        {
            $otherFiles[$i] = 'var/tests/' . __FUNCTION__ . "/OtherFiles/{$i}.txt";
            $ch = $this->createFile( $otherFiles[$i] );
            $ch->fetch();
            self::assertFileExists( $otherFiles[$i] );
        }

        $clusterHandler = eZClusterFileHandler::instance( 'var/tests/' . __FUNCTION__ . '/MultipleFiles');
        $clusterHandler->purge();

        // check if files supposed to be deleted were
        foreach( $files as $file )
        {
            self::assertFalse( $clusterHandler->fileExists( $file ), "DB file $file still exists" );
            self::assertFileNotExists( $file );
        }

        // and if files not supposed to be deleted weren't
        foreach( $otherFiles as $file )
        {
            self::assertTrue( $clusterHandler->fileExists( $file ), "DB file $file has been removed" );
            self::assertFileExists( $file );
        }

        // remove all of 'em
        self::deleteLocalFiles( array_merge( $files, $otherFiles ) );
    }

    public function testPurgeSingleFile()
    {
        $testFile = 'var/tests/' . __FUNCTION__ . '/file.txt';
        $this->createFile( $testFile );

        $clusterHandler = eZClusterFileHandler::instance( $testFile );
        $clusterHandler->delete();
        $clusterHandler->purge();

        self::assertFalse( $clusterHandler->fileExists( $testFile ), "File still exists" );
        self::assertFileNotExists( $testFile, "Local file still exists" );

        self::deleteLocalFiles( $testFile );
    }
}
?>
