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
        $this->addTest( eZSysTest::suite() );
        $this->addTest( eZURITest::suite() );
        $this->addTest( eZURIRegression::suite() );
        $this->addTest( eZMutexRegression::suite() );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
