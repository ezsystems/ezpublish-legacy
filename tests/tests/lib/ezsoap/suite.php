<?php

class eZSOAPTestSuite extends ezpTestSuite
{
    public function __construct( $params = false )
    {
        parent::__construct();

        $this->setName( "eZSOAP Test Suite" );
        $this->addTestSuite( 'eZSOAPClientTest' );
    }
}

?>