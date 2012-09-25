<?php
/**
 * File containing the eZDBClusterStaleCacheTest class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package tests
 */

/**
 * This test applies to the stalecache features for the eZDB cluster handler
 * @package tests
 * @group cluster
 * @group eZDB
 */
class eZDBClusterStaleCacheTest extends eZClusterStaleCacheTest
{
    /**
     * @var array
     **/
    protected $sqlFiles = array( 'kernel/sql/mysql/cluster_schema.sql' );

    /**
     * Test setup
     *
     * Load an instance of file.ini
     * Assigns DB parameters for cluster
     **/
    public function setUp()
    {
        parent::setUp();

        // We need to clear the existing handler if it was loaded before the INI
        // settings changes
        eZClusterFileHandler::resetHandler();

        unset( $GLOBALS['eZClusterInfo'] );

        // Load database parameters for cluster
        // The same DSN than the relational database is used

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

        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'FileHandler', 'eZDBFileHandler' );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBHost', $dsn['host'] );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBPort', $dsn['port'] );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBSocket', $dsn['socket'] );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBName', $dsn['database'] );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBUser', $dsn['user'] );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBPassword', $dsn['password'] );
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'DBBackend', $backend );

        // ezpTestDatabaseHelper::insertSqlData( $this->sharedFixture, $this->sqlFiles );

        $this->db = $this->sharedFixture;
    }

    public function tearDown()
    {
        ezpINIHelper::restoreINISettings();
        eZClusterFileHandler::resetHandler();

        parent::tearDown();
    }
}
?>
