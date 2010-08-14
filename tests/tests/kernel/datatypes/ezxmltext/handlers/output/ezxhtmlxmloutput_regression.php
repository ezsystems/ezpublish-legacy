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
     * @note Test depends on template output!!
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
                    <paragraph>  <table><tr><td>foo</td></tr></table>  </paragraph>
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
     * Regression in renderParagraph() after #16184 causing last paragraphs to not be shown
     *
     * @link http://issues.ez.no/16814
     * @note Test depends on template output!!
     */
    public function testMissingParagraphs()
    {
        $XMLString = '<?xml version="1.0" encoding="utf-8"?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">
  <paragraph>
    <line>Haustsemesteret, 
      <strong>7,5 studiepoeng</strong>
    </line>
    <line><strong>FP 336 620 Naturfag, del 1</strong>: Individuell, skriftleg mappe (HSF Oppdrag/betalingsemne)</line>
  </paragraph>
  <paragraph>
    <line><strong>FP334 620 Naturfag, del 2</strong> : Individuell, skriftleg eksamen.</line>
    <line>Tid: 4 timar </line>
  </paragraph>
</section>';

        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );
        $result = $outputHandler->outputText();

        $html = ' <p>
Haustsemesteret, <b>7,5 studiepoeng</b> <br /><b>FP 336 620 Naturfag, del 1</b>: Individuell, skriftleg mappe (HSF Oppdrag/betalingsemne) </p> <p>
<b>FP334 620 Naturfag, del 2</b> : Individuell, skriftleg eksamen.<br />Tid: 4 timar  </p>';
        
        $this->assertEquals( $html, $result );
    }

    /**
     * Regression in renderParagraph() after #15310 causing lines to not have line breaks in ouput
     *
     * @link http://issues.ez.no/16814
     * @note Test depends on template output!!
     */
    public function testMissingBreak()
    {
        $XMLString = '<?xml version="1.0" encoding="utf-8"?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">
<paragraph>
  <line>Telephone #1</line>
  <line>Telephone #2</line>
</paragraph>
</section>';

        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );
        $result = $outputHandler->outputText();

        $html = '<p>
Telephone #1<br />Telephone #2</p>';

        $this->assertEquals( $html, $result );
    }

    /**
     * Tests that eZXML from ezp3 and ezp4 produces the same result
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

    /**
     * Regression in link rendering
     *
     * @link http://issues.ez.no/016541
     * @note Test depends on template output!!
     * @todo Waiting for feedback on original ezxmltext source for issue
     */
    private function tempTestMissingLink()
    {
        $XMLString = '<?xml version="1.0" encoding="utf-8"?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"><section><section><section><header anchor_name="nsw">New South Wales</header><paragraph xmlns:tmp="http://ez.no/namespaces/ezpublish3/temporary/"><link object_id="2">My Web Link (MWL)</link></paragraph></section></section></section></section>';

        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );
        $result = $outputHandler->outputText();
var_dump( $result );

// note: this link might be different depending on www / vhost settings, so we need to just
// check that link is present with strpos, remember that there are other anchor links in test as well for now
        $expected = '<a name="nsw" /><a name="toc_..." id="toc_..."></a><h3>New South Wales</h3>
<p><a href="/">My Web Link (MWL)</a></p>';

        //$this->assertEquals( $expected, $result );
    }

    /**
     * Bug in link rendering related to GET parameters (& double encoded to &amp;amp;)
     *
     * @link http://issues.ez.no/016668: links in ezxmltext double escapes.
     * @note Test depends on template output!!
     */
    public function testLinkEscape()
    {
        $url = '/index.php?c=6&kat=company';
        $urlID = eZURL::registerURL( $url );
        $XMLString = '<?xml version="1.0" encoding="utf-8"?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"><paragraph xmlns:tmp="http://ez.no/namespaces/ezpublish3/temporary/"><link url_id="' . $urlID . '">My link</link></paragraph></section>';

        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );
        $result = $outputHandler->outputText();

        $expected = '<p><a href="/index.php?c=6&amp;kat=company" target="_self">My link</a></p>';

        $this->assertEquals( $expected, $result );
    }
}
