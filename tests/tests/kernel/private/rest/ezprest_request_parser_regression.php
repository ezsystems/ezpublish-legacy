<?php
/**
 * File containing regression tests for ezpRestHttpRequestParser
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
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
        // make sure eZSys::serverPort() really runs
        unset( $GLOBALS['eZSysServerPort'] );
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
            file_put_contents( $expectedFileName, self::normalizedVarExport( $req ) );
            self::fail( 'Missing expected data file.' );
        }
        else
        {
            $expected = file_get_contents( $expectedFileName );
            self::assertEquals( $expected, self::normalizedVarExport( $req ), $expectedFileName );
        }
    }

    /*
     * Since PHP 5.4.30, DateTime are not serialized the same way as previous versions.
     * See http://php.net/ChangeLog-5.php#5.4.30 & https://bugs.php.net/bug.php?id=67308
     */
    private static function normalizedVarExport( $var )
    {
        $var = var_export( $var, true );
        if ( PHP_VERSION_ID < 50430 )
        {
            $var = preg_replace( '%(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})%', '$1.000000', $var );
        }
        return $var;
    }

    public static function suite()
    {
        return new ezpTestRegressionSuite( __CLASS__ );
    }
}
?>
