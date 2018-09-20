<?php
/**
 * File containing the eZXMLInputParserTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
            array( "FOO" => "bar" ),
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

    /**
     * Test for argument parsing with many attributes
     *
     * @dataProvider providerForTestManyAttributesParsing
     */
    public function testManyAttributesParsing( $string, $expected )
    {
        $this->assertEquals(
            $expected,
            $this->parser->parseAttributes( $string )
        );
    }

    public static function providerForTestManyAttributesParsing()
    {
        $result = array(
            'ie' => 'MS9.0',
            'firefox' => '16.0.2',
            'chrome' => 'Something',
            'opera' => 'twelve',
        );

        return array(
            array(
                'ie=MS9.0 firefox=16.0.2 chrome=Something opera=twelve',
                $result
            ),
            array(
                'ie="MS9.0" firefox="16.0.2" chrome="Something" opera="twelve"',
                $result
            ),
            array(
                'ie=\'MS9.0\' firefox=\'16.0.2\' chrome=\'Something\' opera=\'twelve\'',
                $result
            ),
            array(
                'ie=MS9.0 firefox="16.0.2" chrome="Something" opera="twelve"',
                $result
            ),
            array(
                'ie=MS9.0 firefox=\'16.0.2\' chrome=\'Something\' opera=\'twelve\'',
                $result
            ),
            array(
                'ie="MS9.0" firefox=16.0.2 chrome=\'Something\' opera=twelve',
                $result
            ),
        );
    }

    /**
     * Test for argument parsing with empty values
     *
     * @dataProvider providerForTestConvertNumericEntities
     */
    public function testConvertNumericEntities( $string, $expected )
    {
        $this->assertEquals(
            $expected,
            $this->parser->convertNumericEntities( $string )
        );
    }

    public static function providerForTestConvertNumericEntities()
    {
        $convmap = array( 0x0, 0x2FFFF, 0, 0xFFFF );

        return array(
            // BC values, these were working even before EZP-25243:
            // 1. Nothing to convert here
            array( 42, 42 ),
            array( '', '' ),
            array( "Ph'nglui mglw'nafh Cthulhu R'lyeh wgah'nagl fhtagn.",
                   "Ph'nglui mglw'nafh Cthulhu R'lyeh wgah'nagl fhtagn."
            ),
            // 2. Character entity references, should not be touched
            array( "Ph&quot;nglui mglw&quot;nafh Cthulhu R&quot;lyeh wgah&quot;nagl fhtagn.",
                   "Ph&quot;nglui mglw&quot;nafh Cthulhu R&quot;lyeh wgah&quot;nagl fhtagn."
            ),
            array( "A&amp;B&sect;C&copy;D&auml;E&oslash;&fnof; &Psi;",
                   "A&amp;B&sect;C&copy;D&auml;E&oslash;&fnof; &Psi;"
            ),
            // 3. Numeric character references (decimal)
            array( "Ph&#039;nglui mglw&#39;nafh Cthulhu R&#039;lyeh wgah&#0039;nagl fhtagn.",
                   "Ph'nglui mglw'nafh Cthulhu R'lyeh wgah'nagl fhtagn."
            ),
            array( "&#38;A&#0228; &#255;Bc&#0042;&#191;-&#0197;/&#185;\&#173;",
                   mb_decode_numericentity( "&#38;A&#0228; &#255;Bc&#0042;&#191;-&#0197;/&#185;\&#173;", $convmap, 'UTF-8' )
            ),

            // These are working only after EZP-25243:
            // 4. Numeric character references (hexadecimal)
            array( "I&#xe4;! I&#0xE4;! Cthulhu Fhtagn!",
                   mb_decode_numericentity( "I&#xe4;! I&#0xE4;! Cthulhu Fhtagn!", $convmap, 'UTF-8' )
            ),
            array( "snafu &#x1;&#x0F1; &#x004Ef; &#x460; &#xd0B; &#xFaF; &#x;069c",
                   mb_decode_numericentity( "snafu &#x1;&#x0F1; &#x004Ef; &#x460; &#xd0B; &#xFaF; &#x;069c", $convmap, 'UTF-8' )
            ),
        );
    }
}

?>
