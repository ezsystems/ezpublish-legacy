<?php
/**
 * File containing the eZXHTMLXMLOutputRegression class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZXHTMLXMLOutputRegression extends ezpDatabaseTestCase
{
    protected $insertDefaultData = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( __CLASS__ . " tests" );
    }

    /**
     * Regression in renderParagraph() after preserveWhiteSpace=false was removed.
     *
     * @link http://issues.ez.no/15888
     * @note XML is created by hand for the unit test
     *       Also note that whitespace between li and a might be incorrect, but is not covered by issue 15888
     */
    public function testRenderParagraph()
    {
        $XMLString = '<?xml version="1.0" encoding="utf-8"?> <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
                    xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
                    xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">
                    <paragraph><ul><li>
                    <paragraph><link target="_blank" url_id="296">Accéder à la plate-forme boursière</link></paragraph>
                    </li></ul></paragraph>
                    </section>';

        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );
        $result = $outputHandler->outputText();

        $expectedResult = ' 
<ul>

<li> <a href="/" target="_blank">Accéder à la plate-forme boursière</a> </li>

</ul>
 ';

        $this->assertEquals( $expectedResult, $result );
    }

    /**
     * Regression in renderParagraph() after preserveWhiteSpace=false
     * was removed with block tags
     *
     * @link http://issues.ez.no/16184
     * @note XML is created by hand for the unit test
     */
    public function testRenderParagraphBlockTags()
    {
        $XMLString = '<?xml version="1.0" encoding="utf-8"?> <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
                    xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
                    xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">
                    <paragraph> <table><tr><td>foo</td></tr></table> </paragraph>
                    </section>';

        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );
        $result = $outputHandler->outputText();

        $XMLString = '<?xml version="1.0" encoding="utf-8"?> <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
                    xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
                    xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">
                    <paragraph><table><tr><td>foo</td></tr></table></paragraph>
                    </section>';

        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );
        $expectedResult = $outputHandler->outputText();

        $this->assertEquals( $expectedResult, $result );
    }

    /**
     * Tests that eZXML from ezp3 and ezp4 produces the same
     * result
     * 
     * @link http://issues.ez.no/16184
     * @note XML is from actual customer xml
     */
    public function testeZP3eZP4XMLSameResult()
    {
        $XMLString = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>foo</paragraph>\n  <paragraph>\n    <embed size=\"medium\"\n           object_id=\"1\" />foo\n    <strong>foo</strong>\n  </paragraph>\n  <paragraph>\n    <ul>\n      <li>\n        <paragraph>\n          <strong>foo</strong>foo\n          <strong>\n            <emphasize>foo</emphasize>\n          </strong>\n        </paragraph>\n      </li>\n      <li>\n        <paragraph>\n          <strong>\n            <emphasize>foo</emphasize>\n          </strong>foo</paragraph>\n      </li>\n    </ul>\n  </paragraph>\n</section>";

        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );
        $ezp3Result = $outputHandler->outputText();

        $XMLString = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\" xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>foo</paragraph>\n  <paragraph>\n    <embed size=\"medium\" object_id=\"1\" />foo\n    <strong>foo</strong>\n  </paragraph>\n  <paragraph>\n    <ul>\n      <li>\n        <paragraph>\n          <strong>foo</strong>foo\n          <strong>\n            <emphasize>foo</emphasize>\n          </strong>\n        </paragraph>\n      </li>\n      <li>\n        <paragraph>\n          <strong>\n            <emphasize>foo</emphasize>\n          </strong>foo</paragraph>\n      </li>\n    </ul>\n  </paragraph>\n</section>\n";

        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );
        $ezp4Result = $outputHandler->outputText();

        $this->assertEquals( $ezp3Result, $ezp4Result );
    }
}