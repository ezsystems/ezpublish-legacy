<?php
/**
 * File containing the eZOETestSuite class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZOeTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Online Editor Test Suite" );

        $this->addTestSuite( 'eZOEXMLTextRegression' );
    }

    public static function suite()
    {
        return new self();
    }
}
?>