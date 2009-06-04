<?php
/**
 * File containing the eZURLAliasMlTest class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZURLAliasMlTest extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "URL Alias ML Unit Tests" );

        $this->siteIni = eZINI::instance();
    }

    public function setUp()
    {
        parent::setUp();
        $this->language = eZContentLanguage::addLanguage( "nor-NO", "Norsk" );

        // Make sure all tests are done using utf-8 as charset
        $this->charset = $GLOBALS['eZTextCodecInternalCharsetReal'];
        $GLOBALS['eZTextCodecInternalCharsetReal'] = 'utf-8';
    }

    public function tearDown()
    {
        $GLOBALS['eZTextCodecInternalCharsetReal'] = $this->charset;
        eZContentLanguage::removeLanguage( $this->language->ID );
        parent::tearDown();
    }

    public function testStrtolower()
    {
        $testString = "ÂLL WORK AND NO PLAY MAKES ウーラ A DULL BOY.";
        $testStringLowerCase = "âll work and no play makes ウーラ a dull boy.";
        $resultString = eZURLAliasML::strtolower( $testString );

        self::assertEquals( $testStringLowerCase, $resultString );
    }

    public function testCreate()
    {
        $text = "test";
        $action = "eznode:5";
        $parentID = 2;
        $language = 2;

        $url = eZURLAliasML::create( $text, $action, $parentID, $language );

        self::assertEquals( $text, $url->attribute( 'text' ) );
        self::assertEquals( $action, $url->attribute( 'action' ) );
        self::assertEquals( $parentID, $url->attribute( 'parent' ) );
        self::assertEquals( $language, $url->attribute( 'lang_mask' ) );
    }

    public function testRemoveByAction()
    {
        $nodeID = mt_rand();
        $action = "eznode:$nodeID";

        $url = eZURLAliasML::create( __FUNCTION__, $action, 1, 2 );
        $url->store();

        $db = eZDB::instance();
        $query = "SELECT * from ezurlalias_ml where action = '$action'";

        // Make sure we have one and only one url
        $result = $db->arrayQuery( $query );

        if ( count( $result ) !== 1 )
            self::fail( "There was already an url with same action ($action) as our test in the database." );

        // Remove the url and verify that it's gone
        eZURLAliasML::removeByAction( "eznode", $nodeID );
        $result = $db->arrayQuery( $query );

        self::assertEquals( count( $result ), 0 );
    }

    public function testGetChildren()
    {
        $action = "eznode:" . mt_rand();

        $parent = eZURLAliasML::create( __FUNCTION__ . "Parent", $action, 1, 2 );
        $parent->store();

        $child1 = eZURLAliasML::create( __FUNCTION__ . "Child1", $action, $parent->ID, 2 );
        $child1->store();

        $child2 = eZURLAliasML::create( __FUNCTION__ . "Child2", $action, $parent->ID, 2 );
        $child2->store();

        $child3 = eZURLAliasML::create( __FUNCTION__ . "Child3", $action, $parent->ID, 4 );
        $child3->store();

        $children = $parent->getChildren();

        // Number of children should be 2 (child3 has different language and
        // should not be included in getChildren()).
        self::assertEquals( 2, count( $children ) );

        self::assertEquals( (int) $child1->attribute( 'id' ), (int) $children[0]->attribute( 'id' ) );
        self::assertEquals( (int) $child2->attribute( 'id' ), (int) $children[1]->attribute( 'id' ) );
    }

    public function testGetPath()
    {
        $action = "eznode:" . mt_rand();
        $expectedPath = "/testGetPathParent/testGetPathChild1/testGetPathChild2";

        $parent = eZURLAliasML::create( __FUNCTION__ . "Parent", $action, 1, 2 );
        $parent->store();

        $child1 = eZURLAliasML::create( __FUNCTION__ . "Child1", $action, $parent->ID, 2 );
        $child1->store();

        $child2 = eZURLAliasML::create( __FUNCTION__ . "Child2", $action, $child1->ID, 4 );
        $child2->store();

        self::assertEquals( $expectedPath, $child2->getPath() );
    }

    public function testFindUniqueText()
    {
        $action = "eznode:" . mt_rand();
        $childName = __FUNCTION__ . mt_rand() . " Child";

        // Create a few children
        $child1 = eZURLAliasML::create( $childName, $action, 0, 2 );
        $child1->store();

        $child2 = eZURLAliasML::create( $childName . "2", $action, 0, 2 );
        $child2->store();

        $child3 = eZURLAliasML::create( $childName . "3", $action, 0, 4 );
        $child3->store();

        // Since "Child", "Child2" and "Child3" is taken we should get "Child4" back.
        $uniqueText = eZURLAliasML::findUniqueText( 0, $childName, "" );
        self::assertEquals( $childName . "4", $uniqueText );

        // By specifing action it should not check URLs which has the same
        // action. This means we get the the name back, without any changes to it.
        $uniqueText = eZURLAliasML::findUniqueText( 0, $childName, $action );
        self::assertEquals( $childName, $uniqueText );

        // Specify language of ezurlalias_ml entries we should check the text
        // uniqeness against.
        $uniqueText = eZURLAliasML::findUniqueText( 0, $childName . "3", "", false, 4 );
        self::assertEquals( $childName . "32", $uniqueText );
    }

    public function testFindUniqueText_ReservedWords()
    {
        // Make sure a text with the same name as the "content" module will be
        // changed to "content2"
        $uniqueText = eZURLAliasML::findUniqueText( 0, "content", "" );
        self::assertEquals( "content2", $uniqueText );
    }

    public function testSetLangMaskAlwaysAvailable()
    {
        $nodeID = mt_rand();

        // Create an ezurlalias_ml entry
        $url = eZURLAliasML::create( __FUNCTION__ . $nodeID, "eznode:" . $nodeID, 0, 2 );
        $url->store();

        // Update lang_mask by setting always available,
        eZURLAliasML::setLangMaskAlwaysAvailable( 2, "eznode", $nodeID );

        // Verify that language mask was increased to 3.
        $urls = eZURLAliasML::fetchByAction( 'eznode', $nodeID );
        self::assertEquals( 3, (int) $urls[0]->attribute( 'lang_mask' ) );

        // Update lang_mask by removing always available,
        eZURLAliasML::setLangMaskAlwaysAvailable( false, "eznode", $nodeID );

        // Verify that language mask was reduced back to 2.
        $urls = eZURLAliasML::fetchByAction( 'eznode', $nodeID );
        self::assertEquals( 2, (int) $urls[0]->attribute( 'lang_mask' ) );
    }

    //
    public function testChoosePrioritizedRow()
    {
        // TEST SETUP --------------------------------------------------------
        $ini = eZINI::instance();

        // Make sure to preserve ini settings in case other tests depend on them
        $orgShowUntranslatedObjects = $ini->variable( 'RegionalSettings', 'ShowUntranslatedObjects' );
        $orgSiteLanguageList = $ini->variable( 'RegionalSettings', 'SiteLanguageList' );

        // Make sure we can see all languages
        $ini->setVariable( 'RegionalSettings', 'ShowUntranslatedObjects', 'enabled' );

        $action = "eznode:" . mt_rand();
        $name = __FUNCTION__ . mt_rand();

        // Create an english entry
        $url1 = eZURLAliasML::create( $name . " en", $action, 0, 2 );
        $url1->store();

        // Create a norwegian entry
        $url2 = eZURLAliasML::create( $name . " no", $action, 0, 4 );
        $url2->store();

        // Fetch the created entries. choosePrioritizedRow() wants rows from the
        // database so our eZURLAliasML objects wont work.
        $db = eZDB::instance();
        $rows = $db->arrayQuery( "SELECT * FROM ezurlalias_ml where action = '$action'" );
        // -------------------------------------------------------------------


        // TEST PART 1 - NORMAL PRIORITIZATION -------------------------------
        // The order of the language array also determines the prioritization.
        // In this case 'eng-GB' should be prioritized before 'nor-NO'.
        $languageList = array( "eng-GB", "nor-NO" );
        $ini->setVariable( 'RegionalSettings', 'SiteLanguageList', $languageList );

        eZContentLanguage::clearPrioritizedLanguages();
        $row = eZURLAliasML::choosePrioritizedRow( $rows );

        // The prioritzed language should be 'eng-GB' (lang_mask = 2)
        self::assertEquals( 2, $row["lang_mask"] );
        // -------------------------------------------------------------------


        // TEST PART 2 - REVERSED PRIORITIZATION -----------------------------
        // Reverse the order of the specified languages, this will also
        // reverse the priority.
        $languageList = array_reverse( $languageList );
        $ini->setVariable( 'RegionalSettings', 'SiteLanguageList', $languageList );

        eZContentLanguage::clearPrioritizedLanguages();
        $row = eZURLAliasML::choosePrioritizedRow( $rows );

        // The prioritzed language should be 'nor-NO' (lang_mask = 4)
        self::assertEquals( 4, $row["lang_mask"] );
        // -------------------------------------------------------------------


        // TEST TEAR DOWN ----------------------------------------------------
        $ini->setVariable( 'RegionalSettings', 'ShowUntranslatedObjects', $orgShowUntranslatedObjects );
        $ini->setVariable( 'RegionalSettings', 'SiteLanguageList', $orgSiteLanguageList );
        // -------------------------------------------------------------------
    }

    public function testActionToURL()
    {
        $action1 = "nop:5";
        $action2 = "module:content";
        $action3 = "eznode:500";

        $invalidAction1 = "eznode:invalid";
        $invalidAction2 = "unknownaction:invalid";
        $invalidAction3 = "this is a invalid action";

        // Test valid actions
        self::assertEquals( "/", eZURLAliasML::actionToURL( $action1 ) );
        self::assertEquals( "content",  eZURLAliasML::actionToURL( $action2 ) );
        self::assertEquals( "content/view/full/500", eZURLAliasML::actionToURL( $action3 ) );

        // Test invalid actions
        self::assertEquals( false, eZURLAliasML::actionToURL( $invalidAction1 ) );
        self::assertEquals( false, eZURLAliasML::actionToURL( $invalidAction2 ) );
        self::assertEquals( false, eZURLAliasML::actionToURL( $invalidAction3 ) );
    }

    public function testURLToAction()
    {
        $url1 = "content/view/full/123";
        $url2 = "user/register";

        $invalidUrl1 = "/content/view/full/123";
        $invalidUrl2 = "unknown/module";

        // Test valid urls
        self::assertEquals( "eznode:123", eZURLAliasML::urlToAction( $url1 ) );
        self::assertEquals( "module:user/register", eZURLAliasML::urlToAction( $url2 ) );

        // Test invalid urls
        self::assertEquals( false, eZURLAliasML::urlToAction( $invalidUrl1 ) );
        self::assertEquals( false, eZURLAliasML::urlToAction( $invalidUrl2 ) );
    }

    public function testCleanURL()
    {
        $url1 = "/content/view/full/2";
        $url2 = "/////content/view/full/2/";
        $url3 = "/content/view/full/2///";
        $url4 = "///content/view/full/2///";
        $url5 = "/content/view//full/2/";

        self::assertEquals( "content/view/full/2", eZURLAliasML::cleanURL( $url1 ) );
        self::assertEquals( "content/view/full/2", eZURLAliasML::cleanURL( $url2 ) );
        self::assertEquals( "content/view/full/2", eZURLAliasML::cleanURL( $url3 ) );
        self::assertEquals( "content/view/full/2", eZURLAliasML::cleanURL( $url4 ) );
        self::assertEquals( "content/view//full/2", eZURLAliasML::cleanURL( $url5 ) );

        // Make sure funky characters doesn't get messed up
        $invalidUrl = "/ウーラ/";
        self::assertEquals( "ウーラ", eZURLAliasML::cleanURL( $invalidUrl ) );
    }

    public function testSanitizeURL()
    {
        $url1 = "/content/view/full/2";
        $url2 = "/////content/view/full/2/";
        $url3 = "/content/view/full/2///";
        $url4 = "///content/view/full/2///";
        $url5 = "///content///view////full//2///";

        self::assertEquals( "content/view/full/2", eZURLAliasML::sanitizeURL( $url1 ) );
        self::assertEquals( "content/view/full/2", eZURLAliasML::sanitizeURL( $url2 ) );
        self::assertEquals( "content/view/full/2", eZURLAliasML::sanitizeURL( $url3 ) );
        self::assertEquals( "content/view/full/2", eZURLAliasML::sanitizeURL( $url4 ) );
        self::assertEquals( "content/view/full/2", eZURLAliasML::sanitizeURL( $url5 ) );

        // Make sure funky characters doesn't get messed up
        $invalidUrl = "//ウ//ー//ラ//";
        self::assertEquals( "ウ/ー/ラ", eZURLAliasML::sanitizeURL( $invalidUrl ) );
    }

    public function testConvertToAlias_Unicode()
    {
        $this->saveOriginalURLTranslationSettings();

        // We set the below ini settings to make sure they are not accidentally
        // overriden in somewhere in the test installation.
        // $ini = eZINI::instance();
        $this->siteIni->setVariable( 'URLTranslator', 'WordSeparator', 'dash' );
        $this->siteIni->setVariable( 'URLTranslator', 'TransformationGroup', 'urlalias_iri' );

        // ---------------------------------------------------------------- //
        // Not safe characters, all of these should be removed.
        $e1 = " &;/:=?%[]()+#";
        $e1Result = "_1";

        // Safe characters. No char should be removed.
        // (dot isallowed exepct beginning/end of url).
        $e2 = "$-_.!*',{}^§±@";
        $e2Result = $e2;

        // Random selection of funky characters. No chars should be removed.
        $e3 = "ウңҏѫあギᄍㄇᠢ⻲㆞ญ฿";
        $e3Result = $e3;

        // Make sure multiple dots are turned into a seperator (-)
        $e4 = "..a...........b..";
        $e4Result = "a-b";

        self::assertEquals( $e1Result, eZURLAliasML::convertToAlias( $e1 ) );
        self::assertEquals( $e2Result, eZURLAliasML::convertToAlias( $e2 ) );
        self::assertEquals( $e3Result, eZURLAliasML::convertToAlias( $e3 ) );
        self::assertEquals( $e4Result, eZURLAliasML::convertToAlias( $e4 ) );
        // ---------------------------------------------------------------- //

        // Restore ini settings to their original values
        $this->loadOriginalURLTranslationSettings();
    }

    public function testConvertToAlias_NonUnicode()
    {
        $this->saveOriginalURLTranslationSettings();

        // We set the below ini settings to make sure they are not accidentally
        // overriden in somewhere in the test installation.
        $this->siteIni->setVariable( 'URLTranslator', 'WordSeparator', 'dash' );
        $this->siteIni->setVariable( 'URLTranslator', 'TransformationGroup', 'urlalias' );

        // ---------------------------------------------------------------- //
        // Not safe characters, all of these should be removed.
        $e1 = " &;/:=?[]()+#/{}$*',^§±@";
        $e1Result = "_1";

        // Safe characters. No char should be removed.
        // (dot is allowed except beginning/end of url). Double dots are removed.
        $e2 = "abcdefghijklmnopqrstuvwxyz0123456789_.a";
        $e2Result = "abcdefghijklmnopqrstuvwxyz0123456789_.a";

        // Random selection of funky characters. All chars should be removed.
        $e3 = "ウңҏѫあギᄍㄇᠢ⻲㆞ญ฿";
        $e3Result = "_1";

        // Make sure multiple dots are turned into a seperator (-) (dot is
        // allowed exepct beginning/end of url).
        $e4 = "..a...........b..";
        $e4Result = "a-b";

        self::assertEquals( $e1Result, eZURLAliasML::convertToAlias( $e1 ) );
        self::assertEquals( $e2Result, eZURLAliasML::convertToAlias( $e2 ) );
        self::assertEquals( $e3Result, eZURLAliasML::convertToAlias( $e3 ) );
        self::assertEquals( $e4Result, eZURLAliasML::convertToAlias( $e4 ) );
        // ---------------------------------------------------------------- //

        $this->loadOriginalURLTranslationSettings();
    }

    public function testConvertToAlias_Compat()
    {
        $this->saveOriginalURLTranslationSettings();

        // We set the below ini settings to make sure they are not accidentally
        // overriden in somewhere in the test installation.
        $this->siteIni->setVariable( 'URLTranslator', 'WordSeparator', 'underscore' );
        $this->siteIni->setVariable( 'URLTranslator', 'TransformationGroup', 'urlalias_compat' );


        // ---------------------------------------------------------------- //
        // Not safe characters, all of these should be removed.
        $e1 = " &;/:=?[]()+#/{}$*',^§±@.!_";
        $e1Result = "_1";

        // Safe characters. No char should be removed.
        $e2 = "abcdefghijklmnopqrstuvwxyz0123456789";
        $e2Result = $e2;

        // Random selection of funky characters. All chars should be removed.
        $e3 = "ウңҏѫあギᄍㄇᠢ⻲㆞ญ฿";
        $e3Result = "_1";

        // Make sure uppercase chars gets converted to lowercase.
        $e4 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $e4Result = "abcdefghijklmnopqrstuvwxyz";

        // Make sure multiple dots are turned into a seperator (-) (dot is
        // allowed exepct beginning/end of url).
        $e5 = "..a...........b..";
        $e5Result = "a_b";

        self::assertEquals( $e1Result, eZURLAliasML::convertToAlias( $e1 ) );
        self::assertEquals( $e2Result, eZURLAliasML::convertToAlias( $e2 ) );
        self::assertEquals( $e3Result, eZURLAliasML::convertToAlias( $e3 ) );
        self::assertEquals( $e4Result, eZURLAliasML::convertToAlias( $e4 ) );
        self::assertEquals( $e5Result, eZURLAliasML::convertToAlias( $e5 ) );
        // ---------------------------------------------------------------- //


        $this->loadOriginalURLTranslationSettings();
    }

    public function testNodeIDFromAction()
    {
        $action1 = "eznod:2";   // not valid action
        $action2 = " ";  // not valid action
        $action3 = "eznode;2";  // not valid action
        $action4 = "ezblaa:2";  // not valid action
        $action5 = "eznode:2";  // valid action

        self::assertEquals( false, eZURLAliasML::nodeIDFromAction( $action1 ) );
        self::assertEquals( false, eZURLAliasML::nodeIDFromAction( $action2 ) );
        self::assertEquals( false, eZURLAliasML::nodeIDFromAction( $action3 ) );
        self::assertEquals( false, eZURLAliasML::nodeIDFromAction( $action4 ) );
        self::assertEquals( (int) 2, eZURLAliasML::nodeIDFromAction( $action5 ) );
    }

    /* -----------------------------------------------------------------------
     * HELPER FUNCTIONS
     * -----------------------------------------------------------------------
     */

     /**
      * Saves the values of WordSeparator and TransformationGroup in site.ini
      *
      * The value of these two settings are saved as member variables of this
      * class. This method together with loadOriginalURLTranslationSettings()
      * can save/restore  these two ini settings.
      */
     private function saveOriginalURLTranslationSettings()
     {
         $ini = eZINI::instance();
         $this->siteIni = $ini;

         $this->orginialWordSeparator = $ini->variable( 'URLTranslator', 'WordSeparator' );
         $this->orginialTransformationGroup = $ini->variable( 'URLTranslator', 'TransformationGroup' );
     }

     /**
      * Restores the values of previously saved WordSeparator and TransformationGroup.
      *
      * You have to call saveOriginalURLTranslationSettings() before using these method
      * or you will mess up both ini settings.
      */
     private function loadOriginalURLTranslationSettings()
     {
         $ini = eZINI::instance();
         $this->siteIni = $ini;

         $ini->setVariable( 'URLTranslator', 'WordSeparator', $this->orginialWordSeparator );
         $ini->setVariable( 'URLTranslator', 'TransformationGroup', $this->orginialTransformationGroup );
     }
}

?>
