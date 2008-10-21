<?php

class eZSOAPTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();

        $this->setName( "eZSOAP Test Suite" );
        $this->addTestSuite( 'eZSOAPClientTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>