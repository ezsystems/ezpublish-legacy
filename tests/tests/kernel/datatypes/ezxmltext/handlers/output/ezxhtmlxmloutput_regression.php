<?php
/**
 * File containing the eZXHTMLXMLOutputRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
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
        $XMLString = '<?xml version="1.0" encoding="utf-8"?><section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
                    xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
                    xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"><paragraph><ul><li>
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

    /**
     * Bug in headings rendering when inside tables. Initial level is not respected.
     *
     * @link http://issues.ez.no/11536
     * @note XML is created by hand for the unit test
     */
    public function testHeadingsInsideTables()
    {
        $XMLString = '<?xml version="1.0" encoding="utf-8"?><section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"><section><header>Heading 1</header><section><section><header>Heading 3</header></section></section><section><header>Heading 2</header><paragraph xmlns:tmp="http://ez.no/namespaces/ezpublish3/temporary/"><table width="100%" border="1"><tr><td><section><header>Heading 1</header><section><header>Heading 2</header></section></section></td></tr></table></paragraph></section></section><section><header>Heading 1</header><section><section><header>Heading 3</header></section></section><section><header>Heading 2</header><paragraph xmlns:tmp="http://ez.no/namespaces/ezpublish3/temporary/"><table width="100%" border="1"><tr><td><section><section><section><section><section><header>Heading 5</header></section></section></section></section></section><section><section><section><section><header>Heading 4</header></section></section></section></section></td></tr></table></paragraph><section><header>Heading 3</header></section></section><section><header>Heading 2</header></section></section></section>';

        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );

        $this->assertEquals( '<a name="eztoc1" id="eztoc1"></a><h2>Heading 1</h2><a name="eztoc1_0_1" id="eztoc1_0_1"></a><h4>Heading 3</h4><a name="eztoc1_1" id="eztoc1_1"></a><h3>Heading 2</h3><table class="renderedtable" border="1" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="top">  <a name="eztoc2" id="eztoc2"></a><h2>Heading 1</h2><a name="eztoc2_2" id="eztoc2_2"></a><h3>Heading 2</h3>
  </td>
</tr>

</table>
<a name="eztoc3" id="eztoc3"></a><h2>Heading 1</h2><a name="eztoc3_2_2" id="eztoc3_2_2"></a><h4>Heading 3</h4><a name="eztoc3_3" id="eztoc3_3"></a><h3>Heading 2</h3><table class="renderedtable" border="1" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="top">  <a name="eztoc3_3_2_0_1" id="eztoc3_3_2_0_1"></a><h6>Heading 5</h6><a name="eztoc3_3_2_1" id="eztoc3_3_2_1"></a><h5>Heading 4</h5>
  </td>
</tr>

</table>
<a name="eztoc3_3_3" id="eztoc3_3_3"></a><h4>Heading 3</h4><a name="eztoc3_4" id="eztoc3_4"></a><h3>Heading 2</h3>', $outputHandler->outputText() );
    }


    /**
     * Test scenario for issue #18336: Alignment in table cells is not rendered
     * properly if RenderParagraphInTableCells=disabled
     *
     * With RenderParagraphInTableCells=enabled, check that the align attribute
     * of eZXML paragraph is still taken into account in the HTML paragraph
     *
     * @group issue_18336
     * @link http://issues.ez.no/18336
     * @note Test depends on template output!!
     */
    function testRenderAlignInCellsWithParagraph()
    {
        ezpINIHelper::setINISetting( 'ezxml.ini', 'ezxhtml', 'RenderParagraphInTableCells', 'enabled' );
        $xml = '<?xml version="1.0" encoding="utf-8"?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">
<paragraph><table width="100%" custom:summary="Test alignment" custom:caption=""><tr><td align="right"><paragraph align="left">align=left</paragraph></td></tr></table></paragraph>
</section>';
        $outputHandler = new eZXHTMLXMLOutput( $xml, false );
        $expected = '<table class="renderedtable" cellpadding="2" cellspacing="0" width="100%" summary="Test alignment">
<tr>
<td class=" text-right" valign="top">  <p class=" text-left">align=left</p>
  </td>
</tr>

</table>
';
        $this->assertEquals( $expected, $outputHandler->outputText() );
        ezpINIHelper::restoreINISettings();
    }

    /**
     * Test scenario for issue #18336: Alignment in table cells is not rendered
     * properly if RenderParagraphInTableCells=disabled
     *
     * With RenderParagraphInTableCells=disabled, check that the align
     * attribute of eZXML paragraph is taken into account while rendering the
     * table cell containing this paragraph.
     *
     * @group issue_18336
     * @link http://issues.ez.no/18336
     * @note Test depends on template output!!
     */
    function testRenderAlignInCellsWithoutParagraph()
    {
        ezpINIHelper::setINISetting( 'ezxml.ini', 'ezxhtml', 'RenderParagraphInTableCells', 'disabled' );
        $xml = '<?xml version="1.0" encoding="utf-8"?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">
<paragraph><table width="100%" custom:summary="Test alignment" custom:caption=""><tr><td align="right"><paragraph align="left">align=left</paragraph></td></tr></table></paragraph>
</section>';
        $outputHandler = new eZXHTMLXMLOutput( $xml, false );
        $expected = '<table class="renderedtable" cellpadding="2" cellspacing="0" width="100%" summary="Test alignment">
<tr>
<td class=" text-left" valign="top">  align=left
  </td>
</tr>

</table>
';
        $this->assertEquals( $expected, $outputHandler->outputText() );
        ezpINIHelper::restoreINISettings();
    }
}
