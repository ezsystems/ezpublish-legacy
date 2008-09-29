<?php

class eZTestSuite extends ezpTestSuite
{
    public function __construct( $params = false )
    {
        parent::__construct();
        $this->setName( "eZ Publish Test Suite" );
        $this->addTest( eZKernelTestSuite::suite( $params ) );
        $this->addTest( eZUtilsTestSuite::suite( $params ) );
    }

    public static function suite( $params )
    {
        return new self( $params );
    }
}
?>
