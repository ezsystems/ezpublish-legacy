<?php
/**
 * File containing the eZDBFileHandlerTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZDBFileHandlerTest extends eZDBBasedClusterFileHandlerAbstractTest
{
    /**
     * @var array
     */
    protected $sqlFiles = array( 'kernel/sql/mysql/cluster_schema.sql' );

    protected $clusterClass = 'eZDBFileHandler';

    /* // Commented since __construct breaks data providers
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZDBFileHandler Unit Tests" );
    }*/

    /**
     * Test setup
     *
     * Load an instance of file.ini
     * Assigns DB parameters for cluster
     */
    public function setUp()
    {
        if ( ezpTestRunner::dsn()->dbsyntax !== 'mysql' && ezpTestRunner::dsn()->dbsyntax !== 'mysqli' )
            self::markTestSkipped( "Not running MySQL, skipping" );

        parent::setUp();
        $this->db = eZDB::instance();
        ezpTestDatabaseHelper::insertSqlData( $this->db,  array( 'tests/tests/kernel/classes/clusterfilehandlers/sql/cluster_dfs_schema.sql' ) );

        // We need to clear the existing handler if it was loaded before the INI
        // settings changes
        if ( !eZClusterFileHandler::$globalHandler instanceof eZDBFileHandler )
            eZClusterFileHandler::$globalHandler = null;

        unset( $GLOBALS['eZClusterInfo'] );

        // Load database parameters for cluster
        // The same DSN than the relational database is used
        $fileINI = eZINI::instance( 'file.ini' );
        $this->previousFileHandler = $fileINI->variable( 'ClusteringSettings', 'FileHandler' );
        $fileINI->setVariable( 'ClusteringSettings', 'FileHandler', 'eZDBFileHandler' );

        $dsn = ezpTestRunner::dsn()->parts;
        switch ( $dsn['phptype'] )
        {
            case 'mysql':
                $backend = 'eZDBFileHandlerMysqlBackend';
                break;

            case 'mysqli':
                $backend = 'eZDBFileHandlerMysqliBackend';
                break;

            default:
                $this->markTestSkipped( "Unsupported database type '{$dsn['phptype']}'" );
        }
        $fileINI->setVariable( 'ClusteringSettings', 'DBBackend',  $backend );
        $fileINI->setVariable( 'ClusteringSettings', 'DBHost',     $dsn['host'] );
        $fileINI->setVariable( 'ClusteringSettings', 'DBPort',     $dsn['port'] );
        $fileINI->setVariable( 'ClusteringSettings', 'DBSocket',   $dsn['socket'] );
        $fileINI->setVariable( 'ClusteringSettings', 'DBName',     $dsn['database'] );
        $fileINI->setVariable( 'ClusteringSettings', 'DBUser',     $dsn['user'] );
        $fileINI->setVariable( 'ClusteringSettings', 'DBPassword', $dsn['password'] );

        // ezpTestDatabaseHelper::insertSqlData( $this->db, $this->sqlFiles );
    }

    public function tearDown()
    {
        // restore the previous file handler
        if ( $this->previousFileHandler !== null )
        {
            $fileINI = eZINI::instance( 'file.ini' );
            $fileINI->setVariable( 'ClusteringSettings', 'FileHandler', $this->previousFileHandler );
            $this->previousFileHandler = null;
            eZClusterFileHandler::$globalHandler = null;
        }

        parent::tearDown();
    }

    public function testDisconnect()
    {
        $handler = eZClusterFileHandler::instance();

        // the property ain't private as of now, but will be at some point
        $refHandler = new ReflectionObject( $handler );
        $refBackendProperty = $refHandler->getProperty( 'backend' );
        $refBackendProperty->setAccessible( true );

        self::assertNotNull( $refBackendProperty->getValue( $handler ) );

        $handler->disconnect();

        self::assertNull( $refBackendProperty->getValue( $handler ) );
    }

    /**
     * Test for the global {@see eZClusterFilehandler::preFork()} method
     */
    public function testPreFork()
    {
        $handler = eZClusterFileHandler::instance();

        $refHandler = new ReflectionObject( $handler );
        $refBackendProperty = $refHandler->getProperty( 'backend' );
        $refBackendProperty->setAccessible( true );

        self::assertNotNull( $refBackendProperty->getValue( $handler ) );

        eZClusterFileHandler::preFork();

        self::assertNull( $refBackendProperty->getValue( $handler ) );
    }
}
?>
