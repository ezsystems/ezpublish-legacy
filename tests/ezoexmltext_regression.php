<?php
/**
 * File containing the eZOEXMLTextRegression class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
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
        $this->language = eZContentLanguage::addLanguage( "nor-NO", "Norsk" );
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
        $folder->publish();

        // use handler directly as user does not have access to ezoe
        $oeHandler = new eZOEXMLInput( $folder->short_description->attribute('xml_data'), false,  $folder->short_description );
        $xhtml     = $oeHandler->attribute( 'input_xml' );

        self::assertEquals( '&lt;pre&gt;    something();&lt;br /&gt;    return false;&lt;/pre&gt;&lt;p&gt;&lt;br /&gt;&lt;/p&gt;', $xhtml );
    }
}

?>
