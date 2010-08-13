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
        // make sure ezoe settings are read
        $ini = eZINI::instance();
        $activeExtensions = $ini->variable( 'ExtensionSettings', 'ActiveExtensions' );
        if ( !in_array( 'ezoe', $activeExtensions, true ) )
        {
            $this->ezoeIsLoaded = true;
            $activeExtensions[] = 'ezoe';
            $ini->setVariable( 'ExtensionSettings', 'ActiveExtensions', $activeExtensions );
            $extensionDirectory = eZExtension::baseDirectory();
            $ini->prependOverrideDir( $extensionDirectory . '/ezoe/settings', true, 'extension:ezoe', 'extension' );
        }
        parent::setUp();
    }
    
    public function tearDown()
    {
        if ( $this->ezoeIsLoaded )
        {
            $ini = eZINI::instance();
            $activeExtensions = $ini->variable( 'ExtensionSettings', 'ActiveExtensions' );
            $ini->setVariable( 'ExtensionSettings', 'ActiveExtensions', array_diff( $activeExtensions, array('ezoe') ) );
            $ini->removeOverrideDir( 'extension:ezoe' );
        }
        parent::tearDown();
    }
}
?>