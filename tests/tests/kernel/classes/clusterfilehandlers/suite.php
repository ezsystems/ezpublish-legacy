<?php
/**
 * File containing the eZClusterTestSuite class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZClusterTestSuite extends ezpDatabaseTestSuite
{
    protected $sqlFiles = array(
        array( 'kernel/sql/', 'cluster_db_schema.sql' ),
        array( 'kernel/sql/', 'cluster_dfs_schema.sql' ),
    );

    public function setUp()
    {
        self::$isDatabaseSetup = false;
        parent::setUp();
    }

    public function __construct()
    {
        parent::__construct();
        $this->setName( 'eZ Publish Cluster Test Suite' );

        $this->addTestSuite( 'eZFSFileHandlerTest' );
        $this->addTestSuite( 'eZFS2FileHandlerTest' );
        $this->addTestSuite( 'eZDFSFileHandlerTest' );
        $this->addTestSuite( 'eZDBFileHandlerTest' );
        $this->addTestSuite( 'eZDFSFileHandlerDFSBackendTest' );

        $this->addTestSuite( 'eZDBClusterStaleCacheTest' );
        $this->addTestSuite( 'eZDFSClusterStaleCacheTest' );
        $this->addTestSuite( 'eZFS2ClusterStaleCacheTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
