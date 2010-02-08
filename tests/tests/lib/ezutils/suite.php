<?php
/**
 * File containing the eZUtilsTestSuite class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZUtilsTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZUtils Test Suite" );

        $this->addTestSuite( 'eZSysTest' );
        $this->addTestSuite( 'eZURITest' );
        $this->addTestSuite( 'eZURIRegression' );
        $this->addTestSuite( 'eZMutexRegression' );
        $this->addTestSuite( 'eZMailTest' );
        $this->addTestSuite( 'eZMailEzcTest' );
        $this->addTestSuite( 'eZDebugRegression' );
        $this->addTestSuite( 'eZPHPCreatorRegression' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>