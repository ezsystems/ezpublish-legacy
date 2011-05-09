<?php
/**
 * File containing the eZOEXMLTextRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZOEXMLTextRegression extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZXMLText Datatype OE Regression Tests" );
    }

    public function setUp()
    {
        parent::setUp();
    }

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
        $oeHandler = new eZOEXMLInput( $folder->short_description->attribute('xml_data'), false,  $folder->short_description );
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

        $oeHandler = new eZOEXMLInput( $folder->short_description->attribute('xml_data'), false,  $folder->short_description );
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

}

?>
