<?php

class ezpRestTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Publish REST test suite" );
        $this->addTestSuite( 'ezpRestHttpRequestParserRegression' );
        $this->addTestSuite( 'ezpRestVersionRouteTest' );
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
        if ( !ezpExtensionHelper::load( 'rest' ) )
            trigger_error( __METHOD__ . ': extension is already loaded, this hints about missing cleanup in other tests that uses it!', E_USER_WARNING );
    }

    public function tearDown()
    {
        ezpExtensionHelper::unload( 'rest' );
        parent::tearDown();
    }
}

?>
