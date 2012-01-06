<?php
/**
 * File containing the eZDFSClusterStaleCacheTest class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package tests
 */

/**
 * This test applies to the stalecache features for the eZDFS cluster handler
 * @package tests
 * @group cluster
 * @group eZDFS

 */
class eZDFSClusterStaleCacheTest extends eZClusterStaleCacheTest
{
    /**
     * @var string
     **/
    protected $DFSPath = 'var/dfsmount/';

    /**
     * @var array
     **/
    protected $sqlFiles = array( 'kernel/sql/', 'cluster_dfs_schema.sql' );

    protected $haveToRemoveDFSPath = false;

    public function setUp()
    {
        parent::setUp();

        if ( !( $this->sharedFixture instanceof eZMySQLDB ) and !( $this->sharedFixture instanceof eZMySQLiDB ) )
        {
            self::markTestSkipped( "Not using mysql interface, skipping" );
        }

        // We need to clear the existing handler if it was loaded before the INI
        // settings changes
        if ( isset( $GLOBALS['eZClusterFileHandler_chosen_handler'] ) and
            !$GLOBALS['eZClusterFileHandler_chosen_handler'] instanceof eZDFSFileHandler )
            unset( $GLOBALS['eZClusterFileHandler_chosen_handler'] );

        unset( $GLOBALS['eZClusterInfo'] );

        // Load database parameters for cluster
        // The same DSN than the relational database is used
        $fileINI = eZINI::instance( 'file.ini' );
        $this->previousFileHandler = $fileINI->variable( 'ClusteringSettings', 'FileHandler' );
        $fileINI->setVariable( 'ClusteringSettings', 'FileHandler', 'eZDFSFileHandler' );

        $dsn = ezpTestRunner::dsn()->parts;
        switch ( $dsn['phptype'] )
        {
            case 'mysql':
                $backend = 'eZDFSFileHandlerMySQLBackend';
                break;

            case 'mysqli':
                $backend = 'eZDFSFileHandlerMySQLiBackend';
                break;

            default:
                $this->markTestSkipped( "Unsupported database type '{$dsn['phptype']}'" );
        }
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

        if ( $this->haveToRemoveDFSPath )
        {
            eZDir::recursiveDelete( $this->DFSPath );
        }
        parent::tearDown();
    }

    public function testHasStaleCacheSupport()
    {
        self::assertTrue( eZClusterFileHandler::instance()->hasStaleCacheSupport() );
    }

}
?>