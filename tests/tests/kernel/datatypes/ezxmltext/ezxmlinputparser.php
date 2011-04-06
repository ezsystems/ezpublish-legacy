<?php
/**
 * File containing the eZXMLInputParserTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
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
            array(),
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
}

?>
