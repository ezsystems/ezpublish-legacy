<?php
/**
 * File containing the eZOEXMLTextRegression class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZOEXMLTextRegression extends ezpDatabaseTestCase
{
    /**
     * Test for issue #16605: Online Editor adds a lot of Non Breaking spaces (nbsp)
     * 
     * @link http://issues.ez.no/16605
     */
    public function testNonBreakSpaceInPre()
    {
        $xmlData = "<literal>    something();\n    return false;</literal>";

        // Step 1: Create folder
        $folder = new ezpObject( 'folder', 2 );
        $folder->name = 'Non breaking space Test for Pre tags';
        $folder->short_description = $xmlData;

        // Use handler directly as user does not have access to ezoe (and this is not db test case)
        $xml = $folder->short_description->attribute( 'xml_data' );
        $oeHandler = new eZOEXMLInput( $xml, false,  $folder->short_description );
        $xhtml     = $oeHandler->attribute( 'input_xml' );

        self::assertEquals( '&lt;pre&gt;    something();&lt;br /&gt;    return false;&lt;/pre&gt;&lt;p&gt;&lt;br /&gt;&lt;/p&gt;', $xhtml );
    }

    /**
     * Regression test for issue #18222 : unneeded paragraph in custom tags
     *
     * Check that an empty custom tags is transformed in a <div> tag that
     * contains an HTML paragraph
     *
     * @group issue_18222
     * @link http://issues.ez.no/18222
     */
    public function testAddParagraphInEmptyBlockCustomTagForEditing()
    {
        $xmlData = '<custom name="quote"/>';

        $folder = new ezpObject( 'folder', 2 );
        $folder->name = 'Test';
        $folder->short_description = $xmlData;

        $xml = $folder->short_description->attribute( 'xml_data' );
        $oeHandler = new eZOEXMLInput( $xml, false,  $folder->short_description );
        $xhtml = $oeHandler->attribute( 'input_xml' );
        self::assertEquals( '&lt;div class=&quot;ezoeItemCustomTag quote&quot; type=&quot;custom&quot;&gt;&lt;p&gt;quote&lt;/p&gt;&lt;/div&gt;&lt;p&gt;&lt;br /&gt;&lt;/p&gt;', $xhtml );
    }

    /**
     * Regression test for issue #18222 : unneeded paragraph in custom tags
     *
     * Check that the HTML code of an empty block custom tag is transformed in
     * an empty XML tags
     *
     * @group issue_18222
     * @link http://issues.ez.no/18222
     */
    public function testHandleEmptyBlockCustomTagHtml()
    {
        $parser = new eZOEInputParser();
        $html = '<div type="custom" class="ezoeItemCustomTag quote"><p>quote</p></div>';
        $dom = $parser->process( $html );
        self::assertInstanceOf( 'DomDocument', $dom );
        $xpath = new DomXPath( $dom );
        $custom = $xpath->query( '//custom[@name="quote"]' )->item( 0 );
        self::assertEquals( $custom->textContent, '', 'Custom tag should be empty' );
    }

    /**
     * Regression test for issue #18222 : unneeded paragraph in custom tags
     *
     * Check that the HTML code of a block custom tag with content is
     * transformed in a custom tag containing a paragraph
     *
     * @group issue_18222
     * @link http://issues.ez.no/18222
     */
    public function testHandleBlockCustomTagHtml()
    {
        $parser = new eZOEInputParser();
        $html = '<div type="custom" class="ezoeItemCustomTag quote"><p>May the Force be with you</p></div>';
        $dom = $parser->process( $html );
        self::assertInstanceOf( 'DomDocument', $dom );
        $xpath = new DomXPath( $dom );
        $custom = $xpath->query( '//custom[@name="quote"]/paragraph' )->item( 0 );
        self::assertEquals( $custom->textContent, 'May the Force be with you' );
    }


    /**
     * Test for issue #18220: non breaking spaces are wrongly encoded
     *
     * Check that non breaking spaces are encoded with the UTF8 character
     * instead of XML encoding of the HTML entity (&amp;nbsp;)
     *
     * @group issue_18220
     * @link http://issues.ez.no/18220
     */
    public function testNonBreakingSpaceEncoding()
    {
        $htmlData = '<p>French typography rules&nbsp;:</p>';
        $parser = new eZOEInputParser();
        $dom = $parser->process( $htmlData );
        self::assertInstanceOf( 'DomDocument', $dom );
        $xpath = new DomXPath( $dom );
        $paragraph = $xpath->query( '//paragraph' )->item( 0 );
        self::assertEquals( "French typography rules\xC2\xA0:", $paragraph->textContent );
    }

    /**
     * Test for issue #18220: non breaking spaces are wrongly encoded
     *
     * Check that wrongly encoded non breaking spaces are correctly decoded to
     * be use in Online Editor
     *
     * @group issue_18220
     * @link http://issues.ez.no/18220
     */
    public function testNonBreakingSpaceDecodingOldXmlEncoding()
    {
        $xmlData = '<?xml version="1.0" encoding="utf-8"?>';
        $xmlData .= '<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">';
        $xmlData .= "<paragraph>French typography&amp;nbsp;:</paragraph>";
        $xmlData .= "</section>";

        $folder = new ezpObject( 'folder', 2 );
        $folder->name = 'Non breaking space decoding of old (and bad) xml encoding (&amp;nbsp;)';
        $folder->short_description = '';

        $oeHandler = new eZOEXMLInput( $xmlData, false,  $folder->short_description );
        $xhtml = $oeHandler->attribute( 'input_xml' );
        self::assertEquals( '&lt;p&gt;French typography&amp;nbsp;:&lt;/p&gt;&lt;p&gt;&lt;br /&gt;&lt;/p&gt;', $xhtml );
    }

    /**
     * Test for issue #18220: non breaking spaces are wrongly encoded
     *
     * Check that utf8 non breaking spaces are correctly decoded to be use in
     * Online Editor.
     *
     * @group issue_18220
     * @link http://issues.ez.no/18220
     */
    public function testNonBreakingSpaceDecodingUtf8Encoding()
    {
        $xmlData = '<?xml version="1.0" encoding="utf-8"?>';
        $xmlData .= '<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/" xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/" xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">';
        $xmlData .= "<paragraph>French typography\xC2\xA0:</paragraph>";
        $xmlData .= "</section>";

        $folder = new ezpObject( 'folder', 2 );
        $folder->name = 'Non breaking space decoding of utf8 encoding';
        $folder->short_description = '';

        $oeHandler = new eZOEXMLInput( $xmlData, false,  $folder->short_description );
        $xhtml = $oeHandler->attribute( 'input_xml' );
        self::assertEquals( '&lt;p&gt;French typography&amp;nbsp;:&lt;/p&gt;&lt;p&gt;&lt;br /&gt;&lt;/p&gt;', $xhtml );
    }

    /**
     * Test for EZP-21986
     * Make sure a <u> tag is transformed into the underline custom tag.
     *
     * @link https://jira.ez.no/browse/EZP-21986
     */
    public function testParseUnderlineTag()
    {
        ezpINIHelper::setINISetting(
            'content.ini', 'CustomTagSettings',
            'AvailableCustomTags', array( 'underline' )
        );
        ezpINIHelper::setINISetting(
            'content.ini', 'CustomTagSettings',
            'IsInline', array( 'underline' => 'true' )
        );
        unset( $GLOBALS["eZXMLSchemaGlobalInstance"] );

        $htmlData = '<p>If I could <u type="custom" class="ezoeItemCustomTag underline">sleep</u> forever</p><p>I could forget about <u type="custom">everything</u></p>';
        $parser = new eZOEInputParser();
        $dom = $parser->process( $htmlData );
        self::assertInstanceOf( 'DomDocument', $dom );
        $xpath = new DomXPath( $dom );
        $underlines = $xpath->query( '//custom[@name="underline"]' );
        $underline = $underlines->item( 0 );
        self::assertEquals( "sleep", $underline->textContent );

        $underline = $underlines->item( 1 );
        self::assertEquals( "everything", $underline->textContent );

        ezpINIHelper::restoreINISettings();
        unset( $GLOBALS["eZXMLSchemaGlobalInstance"] );
    }

    /**
     * Test for EZP-21986
     * Make sure the custom tag underline is transformed into a <u> tag
     *
     * @link https://jira.ez.no/browse/EZP-21986
     */
    public function testCustomUnderlineToU()
    {
        ezpINIHelper::setINISetting(
            'content.ini', 'CustomTagSettings',
            'AvailableCustomTags', array( 'underline' )
        );
        ezpINIHelper::setINISetting(
            'content.ini', 'CustomTagSettings',
            'IsInline', array( 'underline' => 'true' )
        );

        $xmlData = '<?xml version="1.0" encoding="utf-8"?>';
        $xmlData .= '<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
            xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
            xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">
            <paragraph>If I could <custom name="underline">sleep</custom> forever</paragraph>
        </section>';

        $folder = new ezpObject( 'folder', 2 );
        $folder->name = 'The Dandy Warhols - Sleep';
        $folder->short_description = '';

        $oeHandler = new eZOEXMLInput( $xmlData, false, $folder->short_description );
        $xhtml = $oeHandler->attribute( 'input_xml' );
        self::assertEquals( '&lt;p&gt;If I could &lt;u class=&quot;ezoeItemCustomTag underline&quot; type=&quot;custom&quot;&gt;sleep&lt;/u&gt; forever&lt;/p&gt;&lt;p&gt;&lt;br /&gt;&lt;/p&gt;', $xhtml );

        ezpINIHelper::restoreINISettings();
    }

    /**
     * Tests for EZP-21348
     * @link https://jira.ez.no/browse/EZP-21348
     * @dataProvider providerMixedCaseStyleToAttribute
     */
    public function testMixedCaseStyleToAttribute( $html, $xpathSel, $attrName, $attrValue )
    {
        $parser = new eZOEInputParser();
        $dom = $parser->process( $html );
        $xpath = new DomXPath( $dom );
        $node = $xpath->query( $xpathSel )->item( 0 );
        $attr = $node->getAttributeNode( $attrName );

        self::assertInstanceOf( 'DOMAttr', $attr );
        self::assertEquals( $attrValue, $attr->value );
    }

    public function providerMixedCaseStyleToAttribute()
    {
        return array(
            array(
                '<table style="width: 100%"><tr><td>1</td><td>2</td></tr></table>',
                "//table[1]",
                "width",
                "100%"
            ),
            array(
                '<table style="WIDTH: 100%"><tr><td>1</td><td>2</td></tr></table>',
                "//table[1]",
                "width",
                "100%"
            ),
            array(
                '<table style="width: 42px"><tr><td>1</td><td>2</td></tr></table>',
                "//table[1]",
                "width",
                "42px"
            ),
            array(
                '<table style="WIDTH: 42px"><tr><td>1</td><td>2</td></tr></table>',
                "//table[1]",
                "width",
                "42px"
            ),
        );
    }


    /**
     * Tests for EZP-21346
     * @link https://jira.ez.no/browse/EZP-21346
     * @dataProvider providerMixedCaseAttributes
     */
    public function testMixedCaseAttributes( $html, $xpathSel, $attrNS, $attrLocalName, $attrValue )
    {
        $parser = new eZOEInputParser();
        $dom = $parser->process( $html );
        $xpath = new DomXPath( $dom );
        $node = $xpath->query( $xpathSel )->item( 0 );
        $attr = $node->getAttributeNodeNS( $attrNS, $attrLocalName );

        self::assertInstanceOf( 'DOMAttr', $attr );
        self::assertEquals( $attrValue, $attr->value );
    }

    public function providerMixedCaseAttributes()
    {
        return array(
            array(
                '<table><tr><td colSpan="2">Merged!</td></tr><td>1</td><td>2</td></tr></table>',
                "//td[1]",
                "http://ez.no/namespaces/ezpublish3/xhtml/",
                "colspan",
                "2"
            ),
            array(
                '<table><tr><td colspan="2">Merged!</td></tr><td>1</td><td>2</td></tr></table>',
                "//td[1]",
                "http://ez.no/namespaces/ezpublish3/xhtml/",
                "colspan",
                "2"
            ),
            array(
                '<table><tr><td COlSpAN="2">Merged!</td></tr><td>1</td><td>2</td></tr></table>',
                "//td[1]",
                "http://ez.no/namespaces/ezpublish3/xhtml/",
                "colspan",
                "2"
            )
        );
    }
}

?>
