<?php
/**
 * File containing the eZXMLInputParserRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZXMLInputParserRegression extends ezpTestCase
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
     * Test for issue #018186: Special characters in custom-tags's xml attributes cause crash
     *
     * @link http://issues.ez.no/18186
     * @dataProvider providerForIssue18186
     */
    public function testIssue18186( $string, $expected )
    {
        $this->assertEquals(
            $expected,
            $this->parser->parseAttributes( $string )
        );
    }

    public static function providerForIssue18186()
    {
        return array(
            array(
                'attribute=" ; =x"',
                array( 'attribute' => '; =x' ),
            ),
            array(
                'attribute=" x =x"',
                array( 'attribute' => 'x =x' ),
            ),
            array(
                'attribute="x x=x"',
                array( 'attribute' => 'x x=x' ),
            ),
            array(
                'attribute="x x =x"',
                array( 'attribute' => 'x x =x' ),
            ),
        );
    }
}

?>
