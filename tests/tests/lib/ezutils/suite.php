<?php
/**
 * File containing the eZUtilsTestSuite class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZUtilsTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZUtils Test Suite" );

        $this->addTestSuite( 'eZSysTest' );
        $this->addTestSuite( 'eZSysRegressionTest' );
        $this->addTestSuite( 'eZURITest' );
        $this->addTestSuite( 'eZINITest' );
        $this->addTestSuite( 'eZURIRegression' );
        $this->addTestSuite( 'eZMutexRegression' );
        $this->addTestSuite( 'eZMailTest' );
        $this->addTestSuite( 'eZMailEzcTest' );
        $this->addTestSuite( 'eZDebugRegression' );
        $this->addTestSuite( 'eZPHPCreatorRegression' );
        $this->addTestSuite( 'eZHTTPToolRegression' );
        $this->addTestSuite( 'eZOperationHandlerRegression' );
        $this->addTestSuite( 'eZHTTPToolTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
