<?php
/**
 * File containing the eZSimplifiedXMLInputParserRegression class
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
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

    /**
     * Test for EZP-22517
     *
     * Checks that the header level is kept.
     *
     * @link https://jira.ez.no/browse/EZP-22517
     */
    public function testHeaderCustomTag()
    {
        $parser = new eZSimplifiedXMLInputParser( 0, eZXMLInputParser::ERROR_NONE );
        $input = '<header level="3">h3</header>
<custom name="factbox" custom:title="factbox" custom:align="right">
<header level="2">h2</header>
</custom>';
        $dom = $parser->process( $input );
        self::assertInstanceOf( 'DomDocument', $dom );
        $xpath = new DomXPath( $dom );
        $header3 = $xpath->query( '/section/section/section/section/header' );
        $header2 = $xpath->query( '/section/section/section/paragraph/custom/header' );

        self::assertEquals( 1, $header3->length );
        self::assertEquals( "h3", $header3->item( 0 )->textContent );
        self::assertEquals( 1, $header2->length );
        self::assertEquals( "h2", $header2->item( 0 )->textContent );
    }

}
