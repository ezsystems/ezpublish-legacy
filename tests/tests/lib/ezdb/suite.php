<?php
/**
 * File containing the eZDBTestSuite class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZDBTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZDB Test Suite" );

        $this->addTestSuite( 'eZPostgreSQLDBTest' );
        $this->addTestSuite( 'eZDBInterfaceTest' );
        $this->addTestSuite( 'eZMySQLDBFKTest' );
        $this->addTestSuite( 'eZMySQLCharsetTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
