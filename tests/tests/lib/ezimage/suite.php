<?php
/**
 * File containing the eZImageTestSuite class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZImageTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZImage Test Suite" );

        $this->addTestSuite( 'eZImageManagerTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
