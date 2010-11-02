<?php

class NeoLibSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "Neo lib" );
        $this->addTestFile( 'tests/neo_lib/console_input_test.php'  );

    }

    public static function suite()
    {
        return new NeoLibSuite();
    }
}

?>
