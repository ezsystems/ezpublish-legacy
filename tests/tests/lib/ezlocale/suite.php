<?php
/**
 * File containing the eZLocaleTestSuite class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 *
 */

class eZLocaleTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZLocale Test Suite" );
        $this->addTestSuite( 'eZLocaleRegression' );
    }

    public static function suite()
    {
        return new self();
    }
}
?>