<?php
/**
 * File containing the eZMySQLCharsetTest class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package lib
 * @subpackage Tests
 */

class eZMySQLCharsetTest extends ezpTestCase
{
    public function testKnownCharsetMapTo01()
    {
        $this->assertSame( "utf8", eZMySQLCharset::mapTo( "utf-8" ) );
    }

    public function testKnownCharsetMapTo02()
    {
        $this->assertSame( "latin1", eZMySQLCharset::mapTo( "iso-8859-1" ) );
    }

    public function testKnownUppercaseCharsetMapTo()
    {
        $this->assertSame( "utf8", eZMySQLCharset::mapTo( "UTF-8" ) );
    }

    public function testUnknownCharsetMapTo()
    {
        $this->assertSame( "unknown", eZMySQLCharset::mapTo( "unknown" ) );
    }

    public function testUnknownUppercaseCharsetMapTo()
    {
        $this->assertSame( "uNknOwN", eZMySQLCharset::mapTo( "uNknOwN" ) );
    }

    public function testKnownCharsetMapFrom01()
    {
        $this->assertSame( "utf-8", eZMySQLCharset::mapFrom( "utf8" ) );
    }

    public function testKnownCharsetMapFrom02()
    {
        $this->assertSame( "iso-8859-1", eZMySQLCharset::mapFrom( "latin1" ) );
    }

    public function testKnownUppercaseCharsetMapFrom()
    {
        $this->assertSame( "utf-8", eZMySQLCharset::mapFrom( "UTF8" ) );
    }

    public function testUnknownCharsetMapFrom()
    {
        $this->assertSame( "unknown", eZMySQLCharset::mapFrom( "unknown" ) );
    }

    public function testUnknownUppercaseCharsetMapFrom()
    {
        $this->assertSame( "uNknOwN", eZMySQLCharset::mapFrom( "uNknOwN" ) );
    }
}
?>
