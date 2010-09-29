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
        'tests/tests/kernel/classes/clusterfilehandlers/sql/cluster_dfs_schema.sql',
        'kernel/sql/mysql/cluster_schema.sql',
    );

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Publish Cluster Test Suite" );

        $this->addTestSuite( 'eZFSFileHandlerTest' );
        $this->addTestSuite( 'eZDFSFileHandlerTest' );
        $this->addTestSuite( 'eZDBFileHandlerTest' );
        $this->addTestSuite( 'eZDFSFileHandlerDFSBackendTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
