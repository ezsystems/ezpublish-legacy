<?php

class eZXMLTextRegression extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZXMLText Datatype Regression Tests" );
    }

    public static function suite()
    {
        return new ezpTestSuite( __CLASS__ );
    }

    public function setUp()
    {
        parent::setUp();
        $this->language = eZContentLanguage::addLanguage( "nor-NO", "Norsk" );
    }

    /**
     * Test scenario for issue #13492: Links are lost after removing version
     *
     * Test Outline
     * ------------
     * 1. Create a Folder in English containing a link (in the short_description attribute).
     * 2. Translate Folder into Norwegian containing another link (not the same link as above.)
     * 3. Remove Folder version 1. (Version 2 is created when translating).
     *
     * @result: short_description in version 2 will have an empty link.
     * @expected: short_description should contain same link as in version 1.
     * @link http://issues.ez.no/13492
     */
    public function testLinksAcrossTranslations()
    {
        $xmlDataEng = '<link href="/some-where-random">a link</link>';
        $xmlDataNor = '<link href="/et-tilfeldig-sted">en link</link>';

        // Step 1: Create folder
        $folder = new ezpObject( "folder", 2 );
        $folder->name = "Folder Eng";
        $folder->short_description = $xmlDataEng;
        $folder->publish();

        $version1Xml = $folder->short_description->attribute('output')->attribute( 'output_text' );

        // Step 2: Translate folder
        $languageCode = "nor-NO";
        $trData = array( "name" => "Folder Nor",
                         "short_description" => $xmlDataNor );
        $folder->addTranslation( $languageCode, $trData ); // addTranslation() publishes too.

        // Step 3: Remove version 1
        $version1 = eZContentObjectVersion::fetchVersion( 1, $folder->id );
        $version1->removeThis();

        // Grab current versions data and make sure it's fresh.
        $folder->refresh();
        $version2Xml = $folder->short_description->attribute('output')->attribute( 'output_text' );

        self::assertEquals( $version1Xml, $version2Xml );
    }
}

?>
