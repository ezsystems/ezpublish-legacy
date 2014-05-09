<?php
/**
 * File containing the eZDFSClusterStaleCacheTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
     * Path to the DFS mount
     * Obtained via eZDFSFileHandlerTest::getDfsPath
     * @var string
     */
    protected $DFSPath;

    /**
     * @var array
     **/
    protected $sqlFiles = array( 'kernel/sql/', 'cluster_dfs_schema.sql' );

    protected $haveToRemoveDFSPath = false;

    public function setUp()
    {
        parent::setUp();

        $this->previousFileHandler = eZINI::instance( 'file.ini' )->variable( 'ClusteringSettings', 'FileHandler' );
        eZClusterFileHandler::resetHandler();

        $this->DFSPath = eZDFSFileHandlerTest::getDfsPath();
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
            eZClusterFileHandler::resetHandler();
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
