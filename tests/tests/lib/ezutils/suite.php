<?php
/**
 * File containing the eZUtilsTestSuite class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
    }

    public static function suite()
    {
        return new self();
    }
}

?>
