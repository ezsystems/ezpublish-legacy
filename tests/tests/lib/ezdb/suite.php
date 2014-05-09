<?php
/**
 * File containing the eZDBTestSuite class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
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
        $this->addTestSuite( 'eZMySQLiDBFKTest' );
        $this->addTestSuite( 'eZMySQLCharsetTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
