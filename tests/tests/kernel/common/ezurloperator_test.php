<?php
/**
 * File containing the eZURLOperatorTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZURLOperatorTest extends ezpTestCase
{
    public function setUp()
    {
        $_POST = $_GET = $_COOKIE = array();
    }

    public function tearDown()
    {
        $_POST = $_GET = $_COOKIE = array();
    }

    public function testeZHTTPOperatorGet()
    {
        $this->runModify( 'ezhttp', 'get' );
    }

    public function testeZHTTPOperatorPost()
    {
        $this->runModify( 'ezhttp', 'post' );
    }

    public function testeZHTTPOperatorCookie()
    {
        $this->runModify( 'ezhttp', 'cookie' );
    }

    public function testeZHTTPOperatorSession()
    {
        $this->runModify( 'ezhttp', 'session' );
    }

    private function runModify( $operatorName, $method )
    {
        $operator = new eZURLOperator();
        $tpl = eZTemplate::instance();

        $operatorValue = null;

        $argument = 'zeargument';
        $expectedResult = 'zevalue';

        switch( $method )
        {
            case 'get'    : $_GET[$argument]     = $expectedResult; break;
            case 'post'   : $_POST[$argument]    = $expectedResult; break;
            case 'cookie' : $_COOKIE[$argument]  = $expectedResult; break;
            case 'session': $_SESSION[$argument] = $expectedResult; break;
        }

        $operatorParameters = array(
            array( array( 1, $argument, false, ), ),
            array( array( 1, $method, false, ), ),
        );

        $namedParameters = array(
            'quote_val'  => $argument,
            'server_url' => $method
        );

        $operator->modify(
            $tpl, $operatorName, $operatorParameters, '', '', $operatorValue, $namedParameters
        );

        $this->assertEquals( $expectedResult, $operatorValue );
    }
}

?>
