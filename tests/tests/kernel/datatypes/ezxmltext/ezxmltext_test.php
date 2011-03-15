<?php
/**
 * File containing the eZXMLTextTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZXMLTextTest extends ezpTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZXMLText Datatype Tests" );
    }

    /**
     * Test scenario for issue #015023: <header level="-1"> generate fatal error taking a lot of server resource
     *
     * Test Outline
     * ------------
     * 1. Parse invalid xml using eZSimplifiedXMLInputParser
     * 2. Make sure we get a valid DOMDocument back with content
     * 3. Make sure resulting xmlText string matches a valid type
     *
     * @result: Error will be thrown in parser
     * @expected: level should be corrected to level 1 resulting in one section tag
     * @link http://issues.ez.no/015023
     */
    public function testInvalidHeaderLevel()
    {
        $parser = new eZSimplifiedXMLInputParser( 2, eZXMLInputParser::ERROR_ALL, eZXMLInputParser::ERROR_ALL, true );

        $document = $parser->process( '<header level="-1">Fatal error test</header>' );
        $this->assertTrue( $document instanceof DOMDocument, 'Parser error: ' . $parser->getMessages() );

        $root = $document->documentElement;        
        $this->assertTrue( $root->hasChildNodes(), 'Content missing, xml document is empty' );

        $xmlString = eZXMLTextType::domString( $document );
        $this->assertEquals( '<?xml version="1.0" encoding="utf-8"?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"><section><header>Fatal error test</header></section></section>
', $xmlString );
    }
}

?>
