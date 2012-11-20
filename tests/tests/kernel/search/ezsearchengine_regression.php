<?php
/**
 * File containing the eZSearchEngineRegression class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZSearchEngineRegression extends ezpTestCase
{
    /**
     * @var eZSearchEngine
     */
    private $searchEngine;

    public function setUp()
    {
        $this->searchEngine = new eZSearchEngine;
    }

    /**
     * Test scenario for issue EZP-19684
     * @dataProvider providerForTestSplitString
     */
    public function testSplitString( $originalString, $convertedString )
    {
        $this->assertSame( $convertedString, $this->searchEngine->splitString( $originalString ) );
    }

    public function providerForTestSplitString()
    {
        return array(
            array( "", array() ),
            array( "   ", array() ),
            array( "L'avertissement", array( "L", "avertissement") ),
            array( "L’avertissement", array( "L", "avertissement") ),
            array( "Hello world", array( "Hello", "world" ) ),
            array( "  Hello   world  ", array( "Hello", "world" ) ),
            array( "  'Hello''world'  ", array( "Hello", "world" ) ),
            array( "  'Hello'   'world'  ", array( "Hello", "world" ) ),
            array( "  'Hello' ''  'world'  ", array( "Hello", "world" ) ),
            array( '  "Hello""world"  ', array( "Hello", "world" ) ),
            array( '  "Hello"   "world"  ', array( "Hello", "world" ) ),
            array( '  "Hello" ""  "world"  ', array( "Hello", "world" ) ),
            array( '  ‘Hello’   ‘world’  ', array( "Hello", "world" ) ),
            array( '  ‘Hello’ ‘’  ‘world’  ', array( "Hello", "world" ) ),
            array( '  ‟Hello„‟world„  ', array( "Hello", "world" ) ),
            array( '  ‟Hello„   ‟world„  ', array( "Hello", "world" ) ),
            array( '  ‟Hello„ ‟„  ’world„  ', array( "Hello", "world" ) ),
        );
    }
}
