<?php
/**
 * File containing ezpApiTestSuite class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */

/**
 * Main test suite for eZPublish public API
 */
class ezpApiTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Publish API test suite" );
        $this->addTestSuite( 'ezpContentRegression' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
