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

        $this->previousFileHandler = eZINI::instance( 'file.ini' )->variable( 'ClusteringSettings', 'FileHandler' );
        eZDFSFileHandlerTest::setUpDatabase();

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