<?php
/**
 * File containing the eZXMLInputParserTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZXMLInputParserTest extends ezpTestCase
{
    /**
     * Parser used for the tests.
     *
     * @var eZXMLInputParser
     */
    protected $parser;

    public function setUp()
    {
        $this->parser = new eZXMLInputParser;
    }

    /**
     * Test for argument parsing with double quotes
     */
    public function testDoubleQuotedAttributeParsing()
    {
        $this->assertEquals(
            array( "foo" => "bar" ),
            $this->parser->parseAttributes( 'foo="bar"' )
        );
    }

    /**
     * Test for argument parsing with single quotes
     */
    public function testSingleQuotedAttributeParsing()
    {
        $this->assertEquals(
            array( "foo" => "bar" ),
            $this->parser->parseAttributes( "foo='bar'" )
        );
    }

    /**
     * Test for argument parsing with no quotes
     */
    public function testNoQuoteAttributeParsing()
    {
        $this->assertEquals(
            array( "foo" => "bar" ),
            $this->parser->parseAttributes( "foo=bar" )
        );
    }

    /**
     * Test for argument parsing with uppercase name
     */
    public function testUppercaseNameAttributeParsing()
    {
        $this->assertEquals(
            array( "foo" => "bar" ),
            $this->parser->parseAttributes( "FOO='bar'" )
        );
    }

    /**
     * Test for argument parsing with empty values
     *
     * @dataProvider providerForTestEmptyAttributeParsing
     */
    public function testEmptyAttributeParsing( $string )
    {
        $this->assertEquals(
            array(),
            $this->parser->parseAttributes( $string )
        );
    }

    public static function providerForTestEmptyAttributeParsing()
    {
        return array(
            array( null ),
            array( "" ),
            array( "foo=''" ),
            array( 'foo=""' ),
            array( 'foo="    "' ),
            array( ' foo="" bar baz' ),
        );
    }

    /**
     * Test for argument parsing with various additional spaces
     *
     * @dataProvider providerForTestAdditionalSpacesAttributeParsing
     */
    public function testAdditionalSpacesAttributeParsing( $string )
    {
        $this->assertEquals(
            array( "foo" => "bar" ),
            $this->parser->parseAttributes( $string )
        );
    }

    public static function providerForTestAdditionalSpacesAttributeParsing()
    {
        return array(
            // Single quotes
            array( " foo='bar'" ),
            array( "foo ='bar'" ),
            array( "foo= 'bar'" ),
            array( "foo=' bar'" ),
            array( "foo='bar '" ),
            array( "foo='bar' " ),
            array( "  foo='bar' " ),
            array( "  foo  =  '  bar  ' " ),

            // Single quotes
            array( ' foo="bar"' ),
            array( 'foo ="bar"' ),
            array( 'foo= "bar"' ),
            array( 'foo=" bar"' ),
            array( 'foo="bar "' ),
            array( 'foo="bar" ' ),
            array( '  foo="bar" ' ),
            array( '  foo  =  "  bar  " ' ),

            // No quotes
            array( ' foo=bar' ),
            array( 'foo =bar' ),
            array( 'foo= bar' ),
            array( 'foo=bar ' ),
            array( '  foo=bar ' ),
            array( '  foo  =    bar   ' ),
        );
    }

    /**
     * Test for argument parsing with various special characters
     *
     * @dataProvider providerForTestSpecialCharactersAttributeParsing
     */
    public function testSpecialCharactersAttributeParsing( $string, $expected )
    {
        $this->assertEquals(
            $expected,
            $this->parser->parseAttributes( $string )
        );
    }

    public static function providerForTestSpecialCharactersAttributeParsing()
    {
        return array(
            array(
                'héllowørld="héllowørld"',
                array( 'héllowørld' => 'héllowørld' ),
            ),
            array(
                '2foo="bar"',
                array(),
            ),
            array(
                'foo2="bar"',
                array( 'foo2' => 'bar' ),
            ),
            array(
                '·foo="bar"',
                array(),
            ),
            array(
                'foo·="foo·"',
                array( 'foo·' => 'foo·' ),
            ),
            array(
                'Ωþà‿="Ωþà‿"',
                array( 'Ωþà‿' => 'Ωþà‿' ),
            ),
            array(
                '神="神"',
                array( '神' => '神' ),
            ),
            array(
                'الله="الله"',
                array( 'الله' => 'الله' ),
            ),
        );
    }
}

?>
