<?php
/**
 * File containing the eZSearchEngineRegression class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
