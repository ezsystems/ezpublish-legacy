<?php

class eZKernelTestSuite extends ezpDatabaseTestSuite
{
    public function __construct( $params = false )
    {
        parent::__construct( $params );

        $this->setName( "eZ Publish Kernel Test Suite" );
        $this->addTest( eZContentObjectRegression::suite() );
        $this->addTest( eZURLAliasMlTest::suite() );
        $this->addTest( eZURLAliasMlRegression::suite() );
        $this->addTest( eZURLTypeRegression::suite() );
        $this->addTest( eZXMLTextRegression::suite() );
    }

    public static function suite( $params = false )
    {
        return new self( $params );
    }
}

?>