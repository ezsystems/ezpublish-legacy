<?php

class eZSOAPTestSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct( $params = false )
    {
        parent::__construct();

        $this->setName( "eZSOAP Test Suite" );
        $this->addTestSuite( 'eZSOAPClientTest' );
    }
}

?>