<?php
/**
 * File containing ezpApiTestSuite class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
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
