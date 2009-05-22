<?php
/**
 * File containing the eZKernelTestSuite class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZKernelTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Publish Kernel Test Suite" );

        $this->addTestSuite( 'eZContentObjectRegression' );
        $this->addTestSuite( 'eZURLAliasMlTest' );
        $this->addTestSuite( 'eZURLAliasMlRegression' );
        $this->addTestSuite( 'eZURLTypeRegression' );
        $this->addTestSuite( 'eZXMLTextRegression' );
        $this->addTestSuite( 'eZApproveTypeRegression' );
        $this->addTestSuite( 'eZMultiPriceTypeRegression' );
        $this->addTestSuite( 'eZContentObjectStateTest' );
        $this->addTestSuite( 'eZContentObjectStateGroupTest' );
        $this->addTestSuite( 'eZDFSFileHandlerTest' );
        // $this->addTestSuite( 'eZWebDAVBackendContentRegressionTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>