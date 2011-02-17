<?php
/**
 * File containing ezpRestTestSuite class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
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
    }

    public static function suite()
    {
        return new self();
    }

    public function setUp()
    {
        parent::setUp();

        // make sure extension is enabled and settings are read
        ezpExtensionHelper::load( 'rest' );
    }

    public function tearDown()
    {
        ezpExtensionHelper::unload( 'rest' );
        parent::tearDown();
    }
}

?>
