<?php
/**
 * File containing ezpRestTestSuite class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

/**
 * Main test suite for REST
 */
class ezpRestTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Publish REST test suite" );
        $this->addTestSuite( 'ezpRestHttpRequestParserRegression' );
        $this->addTestSuite( 'ezpRestVersionRouteTest' );
        $this->addTestSuite( 'ezpRestControllerTest' );
        $this->addTestSuite( 'ezpRestApplicationCacheTest' );
        $this->addTestSuite( 'ezpRestRailsRouteTest' );
        $this->addTestSuite( 'ezpRestRegexpRouteTest' );
    }

    public static function suite()
    {
        return new self();
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}

?>
