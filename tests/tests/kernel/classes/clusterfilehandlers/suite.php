<?php
/**
 * File containing the eZKernelTestSuite class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZClusterTestSuite extends ezpDatabaseTestSuite
{
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
