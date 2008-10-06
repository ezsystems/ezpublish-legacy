<?php
/**
 * File containing the eZUtilsTestSuite class
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZUtilsTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZUtils Test Suite" );

        $this->addTestSuite( 'eZSysTest' );
        $this->addTestSuite( 'eZURITest' );
        $this->addTestSuite( 'eZURIRegression' );
        $this->addTestSuite( 'eZMutexRegression' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
