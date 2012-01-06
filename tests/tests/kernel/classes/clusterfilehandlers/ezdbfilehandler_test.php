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
                $backend = 'eZDBFileHandlerMysqlBackend';
                break;

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
        if ( isset( $GLOBALS['eZClusterFileHandler_chosen_handler'] ) and
            !$GLOBALS['eZClusterFileHandler_chosen_handler'] instanceof eZDBFileHandler )
            unset( $GLOBALS['eZClusterFileHandler_chosen_handler'] );

        unset( $GLOBALS['eZClusterInfo'] );

        // Load database parameters for cluster
        // The same DSN than the relational database is used
        $fileINI = eZINI::instance( 'file.ini' );
        $this->previousFileHandler = $fileINI->variable( 'ClusteringSettings', 'FileHandler' );
        $fileINI->setVariable( 'ClusteringSettings', 'FileHandler', 'eZDBFileHandler' );
        $fileINI->setVariable( 'ClusteringSettings', 'DBBackend',  $backend );
        $fileINI->setVariable( 'ClusteringSettings', 'DBHost',     $dsn['host'] );
        $fileINI->setVariable( 'ClusteringSettings', 'DBPort',     $dsn['port'] );
        $fileINI->setVariable( 'ClusteringSettings', 'DBSocket',   $dsn['socket'] );
        $fileINI->setVariable( 'ClusteringSettings', 'DBName',     $dsn['database'] );
        $fileINI->setVariable( 'ClusteringSettings', 'DBUser',     $dsn['user'] );
        $fileINI->setVariable( 'ClusteringSettings', 'DBPassword', $dsn['password'] );

        $this->db = $this->sharedFixture;
    }

    public function tearDown()
    {
        // restore the previous file handler
        if ( $this->previousFileHandler !== null )
        {
            $fileINI = eZINI::instance( 'file.ini' );
            $fileINI->setVariable( 'ClusteringSettings', 'FileHandler', $this->previousFileHandler );
            $this->previousFileHandler = null;
            if ( isset( $GLOBALS['eZClusterFileHandler_chosen_handler'] ) )
                unset( $GLOBALS['eZClusterFileHandler_chosen_handler'] );
        }

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
