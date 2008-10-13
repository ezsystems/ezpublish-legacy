<?php

class eZMailTest extends ezpTestCase
{
    public static function providerTestValidate()
    {
        return array(
            array( 'kc@ez.no',      1 ),
            array( 'kc+list@ez.no', 1 ),
            array( "kc'@ez.no",     false ),
            array( "k..c'@ez.no",   false ),
            array( ".kc@ez.no",     false ),
        );
    }

    /**
     * @dataProvider providerTestValidate
     */
    public function testValidate( $email, $valid )
    {
        $this->assertEquals( $valid, eZMail::validate( $email ) );
    }
}

?>