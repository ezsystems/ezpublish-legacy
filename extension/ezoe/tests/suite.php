<?php
/**
 * File containing the eZOETestSuite class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @package tests
 */

class eZOeTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Online Editor Test Suite" );
        $this->addTestSuite( 'eZOEXMLTextRegression' );
    }

    public static function suite()
    {
        return new self();
    }

    public function setUp()
    {
        parent::setUp();

        // make sure extension is enabled and settings are read
        // give a warning if it is already enabled
        if ( !ezpExtensionHelper::load( 'ezoe' ) )
            trigger_error( __METHOD__ . ': extension is already loaded, this hints about missing cleanup in other tests that uses it!', E_USER_WARNING );
    }

    public function tearDown()
    {
        ezpExtensionHelper::unload( 'ezoe' );
        parent::tearDown();
    }
}
?>