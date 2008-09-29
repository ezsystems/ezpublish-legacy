<?php

class eZTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Publish Test Suite" );
        $this->addTest( eZKernelTestSuite::suite() );
        $this->addTest( eZUtilsTestSuite::suite() );
    }

    public static function suite()
    {
        return new self();
    }
}
?>
