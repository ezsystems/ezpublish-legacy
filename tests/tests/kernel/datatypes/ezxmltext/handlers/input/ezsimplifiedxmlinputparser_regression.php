<?php
/**
 * File containing the eZSimplifiedXMLInputParserRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZSimplifiedXMLInputParserRegression extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( __CLASS__ . " tests" );
    }

    /**
     * Test for issue #18149: some special characters breaks the HTML to eZXML
     * transformation
     *
     * @link http://issues.ez.no/18149
     * @group issue18149
     */
    public function testIssue18149()
    {
        $parser = new eZSimplifiedXMLInputParser( 0 );
        $html = 'beforeafter';
        $dom = $parser->process( $html );
        $this->assertInstanceOf( 'DomDocument', $dom );
        $paragraphs = $dom->getElementsByTagName( 'paragraph' );
        $this->assertEquals( 1, $paragraphs->length );
        $this->assertEquals( 'before after', $paragraphs->item( 0 )->textContent );
        $this->assertEquals( 1, count( $parser->getMessages() ) );
    }

}
