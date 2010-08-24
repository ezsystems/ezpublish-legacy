<?php
/**
 * File containing the eZOETestSuite class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
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
        $this->ezoeIsLoaded = null;
    }

    public static function suite()
    {
        return new self();
    }

    public function setUp()
    {
        // make sure ezoe is enabled and settings are read
        $this->ezoeWasLoaded = ezpExtensionHelper::load( 'ezoe' );

        parent::setUp();
    }

    public function tearDown()
    {
        if ( $this->ezoeWasLoaded )
        {
            $this->ezoeWasLoaded = !ezpExtensionHelper::unload( 'ezoe' );
        }
        parent::tearDown();
    }
}
?>