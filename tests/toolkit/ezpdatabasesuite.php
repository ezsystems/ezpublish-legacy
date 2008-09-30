<?php
/**
 * File containing the ezpDatabaseTestSuite class
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

/**
 * Database backed test suite class.
 *
 * Inherit from this class if you want your test suite and all tests in the 
 * suite to interact with a database.
 */
class ezpDatabaseTestSuite extends ezpTestSuite
{
    /**
     * Holds paths to custom sql files
     *
     * @var array( array( string => string ) )
     */
    protected $sqlFiles = array();

    /**
     * Sets up the database enviroment
     */
    protected function setUp()
    {
        if ( !ezpTestRunner::dbPerTest() )
        {
            $dsn = ezpTestRunner::dsn();
            $this->sharedFixture = ezpTestDatabaseHelper::create( $dsn, $this->sqlFiles );
            eZDB::setInstance( $this->sharedFixture );
        }
    }
}
?>
