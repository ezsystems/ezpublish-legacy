<?php
include_once("autoload.php");
require_once 'template_test.php';

include_once ("Base/src/base.php");
include_once("tests/neo_lib/suite.php");


class MainSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "Main suite" );
        $this->addTest( TemplateTest::suite() );
        $this->addTest( NeoLibSuite::suite() );
        #$this->addTestFile( 'tests/console_input_test.php'  );
        #$this->addTestFile('tests/sel_test.php');

    }

    public static function suite()
    {
        return new MainSuite();
    }
}

?>
