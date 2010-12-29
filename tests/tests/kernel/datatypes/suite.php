<?php
/**
 * File containing the eZKernelTestSuite class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZDatatypeTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Publish Datatypes Test Suite" );

        $this->addTestSuite( 'eZMatrixDatatypeTest' );
        $this->addTestSuite( 'eZStringDatatypeTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
