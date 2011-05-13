<?php
/**
 * File containing the eZSOAPTestSuite class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZSOAPTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();

        $this->setName( "eZSOAP Test Suite" );
        $this->addTestSuite( 'eZSOAPClientTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
