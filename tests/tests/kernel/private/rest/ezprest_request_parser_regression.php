<?php
/**
 * File containing regression tests for ezpRestHttpRequestParser
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 *
 */

/**
 * Class for testing series of expected (virtual) HTTP input and expected
 * request output.
 *
 * The class will create the expected output on first run, if this does not
 * already exist.
 *
 * Class is based off the earlier regression testing framework from
 * eZ Components.
 */
class ezpRestHttpRequestParserRegression extends ezpRegressionTest
{
    public function __construct()
    {
        $this->readDirRecursively( dirname( __FILE__ ) . '/request_parser_data', $this->files, 'data' );

        parent::__construct();
    }

    public function setUp()
    {
        $this->serverArray = $_SERVER;
        $this->getArray = $_GET;
        $this->postArray = $_POST;
        $this->filesArray  = $_FILES;
        $this->requestArray  = $_REQUEST;
        $this->cookieArray  = $_COOKIE;
    }

    public function tearDown()
    {
        $_SERVER = $this->serverArray;
        $_GET = $this->getArray;
        $_POST = $this->postArray;
        $_FILES  = $this->filesArray;
        $_REQUEST = $this->requestArray;
        $_COOKIE = $this->cookieArray;
    }

    public function testRunRegression( $name )
    {
        include $name;
        $_SERVER = $server;
        $_GET = $get;
        $_POST = $post;
        $_FILES  = $files;
        $_REQUEST = $request;
        $_COOKIE = $cookies;

        $requestParser = new ezpRestHttpRequestParser();
        $req = $requestParser->createRequest();

        $expectedFileName = $name . '.exp';
        if ( !file_exists( $expectedFileName ) )
        {
            file_put_contents( $expectedFileName, var_export( $req, true ) );
            self::fail( 'Missing expected data file.' );
        }
        else
        {
            $expected = file_get_contents( $expectedFileName );
            self::assertEquals( $expected, var_export( $req, true ) );
        }
    }

    public static function suite()
    {
        return new ezpTestRegressionSuite( __CLASS__ );
    }
}
?>
