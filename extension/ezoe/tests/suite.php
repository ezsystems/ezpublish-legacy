<?php
/**
 * File containing the eZOETestSuite class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
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