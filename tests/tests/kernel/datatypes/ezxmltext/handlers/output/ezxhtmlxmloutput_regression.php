<?php
/**
 * File containing the eZXHTMLXMLOutputRegression class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

require_once( 'kernel/common/template.php' );

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

    /**
     * Additional test to check how handler handles tables and content
     *
     * @link http://issues.ez.no/16184
     * @note XML source is demo data from 3.10.1 webin install object id 66
     */
    public function testWithEzp3Table()
    {
        // string from 3.10.1
        $XMLString = '<?xml version="1.0" encoding="utf-8"?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
         xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
         xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">
  <paragraph>
    <embed size="medium"
           class="highlighted_object"
           align="center"
           object_id="152" />
  </paragraph>
  <paragraph>
    <embed size="medium"
           class="horizontally_listed_sub_items"
           align="center"
           custom:offset="0"
           custom:limit="5"
           object_id="142" />
  </paragraph>
  <paragraph>
    <table class="default"
           width="100%"
           border="0">
      <tr>
        <td />
        <td />
      </tr>
      <tr>
        <td xhtml:width="50%">
          <paragraph>
            <custom name="factbox"
                    custom:title="Content from Grenland"
                    custom:align="left">
              <paragraph><link url_id="9">
                  <embed size="small"
                         object_id="167" />
                </link>The content in this eZ Publish release is provided by <link target="_blank"
                      url_id="9">VisitGrenland</link>. </paragraph>
            </custom>
          </paragraph>
        </td>
        <td>
          <paragraph>
            <custom name="quote"
                    custom:align="right"
                    custom:author="Henrik Ibsen">
              <paragraph>community is like a ship; everyone ought be prepared to take the helm.</paragraph>
            </custom>
          </paragraph>
        </td>
      </tr>
    </table>
  </paragraph>
</section>
';        

        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );
        $result = $outputHandler->outputText();

        // string generated by 4.0 based on xml above
        $XMLString = '<?xml version="1.0" encoding="utf-8"?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/"><paragraph><embed size="medium" class="highlighted_object" align="center" object_id="152"/></paragraph><paragraph><embed size="medium" class="horizontally_listed_sub_items" align="center" object_id="142" custom:offset="0" custom:limit="5"/></paragraph><paragraph><table class="default" border="0" width="100%"><tr><td/><td/></tr><tr><td xhtml:width="50%"><paragraph><custom name="factbox" custom:title="Content from Grenland" custom:align="left"><paragraph><link url_id="9"><embed size="small" object_id="155"/></link>The content in this eZ Publish release is provided by <link target="_blank" url_id="9">VisitGrenland</link>. </paragraph></custom></paragraph></td><td><paragraph><custom name="quote" custom:align="right" custom:author="Henrik Ibsen"><paragraph>community is like a ship; everyone ought be prepared to take the helm.</paragraph></custom></paragraph></td></tr></table></paragraph></section>
';
        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );
        $expectedResult = $outputHandler->outputText();
        
        /* Here is reference output from 4.0 (templates differ so this is just to give some hints on what is wrong if this test fails):
<div class="object-center highlighted_object">    <a href="/test40/index.php/ezwebin_site_admin/Company/Contact">Contact</a></div>
<div class="object-center horizontally_listed_sub_items">    <a href="/test40/index.php/ezwebin_site_admin/Company/News/Lyon-encourages-talents">Lyon encourages talents</a></div>
<table class="default"  border="0" cellpadding="2" cellspacing="0"  width="100%">

<tr>

<td valign="top">  &nbsp;
  </td>

<td valign="top">  &nbsp;
  </td>

</tr>

<tr>

<td valign="top" width="50%">  
<div align="center">
<table bgcolor="#eeeeee">
<tr>
    <td>
    
<div class="object-right">
<div class="imageright"><a href="http://www.aderly.com/?xtor=AL-93" target="_self"><img src="/test40/var/ezwebin_site/storage/images/media/images/only-lyon/1030-1-eng-GB/Only-Lyon_small.jpg" alt="" /></a>
<div style="width: 100px;">

</div>
</div>
</div>
<p>
The content in this eZ Publish release is provided by <a href="http://www.aderly.com/?xtor=AL-93" target="_blank">VisitGrenland</a>. 

</p>

    </td>
</tr>
</table>
</div>
  </td>

<td valign="top">  <blockquote><span class="hide">"</span>
<p>
community is like a ship; everyone ought be prepared to take the helm.
</p>
<span class="hide">"</span></blockquote>

  </td>

</tr>

</table>
*/
        $this->assertEquals( $expectedResult, $result );
    }
}