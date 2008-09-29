<?php

class eZUtilsTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZUtils Test Suite" );
        $this->addTest( eZSysTest::suite() );
        $this->addTest( eZURITest::suite() );
        $this->addTest( eZURIRegression::suite() );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
