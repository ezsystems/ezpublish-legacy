<?php

class eZUtilsTestSuite extends ezpDatabaseTestSuite
{
    public function __construct( $params = false )
    {
        parent::__construct( $params );
        $this->setName( "eZUtils Test Suite" );
        $this->addTest( eZSysTest::suite() );
        $this->addTest( eZURITest::suite() );
        $this->addTest( eZURIRegression::suite() );
    }

    public static function suite( $params = false )
    {
        return new self( $params );
    }
}

?>