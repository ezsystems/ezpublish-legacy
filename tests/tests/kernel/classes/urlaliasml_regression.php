<?php
/**
 * File containing the eZURLAliasMlRegression class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZURLAliasMlRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = FALSE;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "URL Alias ML Regressions" );
    }

    public function setUp()
    {
        parent::setUp();
        $this->language = eZContentLanguage::addLanguage( "nor-NO", "Norsk" );
    }

    /**
     * Builds SQL statement required to fetch all url elements for a set of nodes.
     * If $defaultLanguage is set only url elements of that language_id are
     * returned.
     *
     * @param array $actionValues
     * @param boolean $defaultLanguage
     * @return string
    */
    public static function buildSql( array $actionValues, $defaultLanguage = false, $extraConditions = false )
    {
        $action = 'eznode';
        $actionList = array();

        foreach ( $actionValues as $value )
        {
            $actionList[] = "'{$action}:{$value}'";
        }
        $actionSqlInText = '( ' . join( ', ', $actionList ) . ' )';

        $columns = "SELECT *";
        $fromDb = "FROM ezurlalias_ml";
        $conditions = "WHERE action IN {$actionSqlInText}";

        if ( !empty( $defaultLanguage ) and is_numeric( $defaultLanguage ) )
        {
            $langFilter = " AND ( lang_mask & {$defaultLanguage} > 0)";
            $conditions .= $langFilter;
        }

        if ( $extraConditions !== false )
        {
            foreach( $extraConditions as $column => $value )
            {
                $conditions .= " AND $column = $value ";
            }
        }

        $query = "$columns $fromDb $conditions ORDER BY id";
        return $query;
    }

    /**
     * Test that url alias table is intact when translation is added to node with children
     */
    public function testURLAliasTranslatedFolderWithChildren()
    {
        // Create a new folder node
        $folder = new ezpObject( "folder", 2 );
        $parentName = __FUNCTION__ . " folder";
        $folder->name = $parentName;
        $folder->short_description = __FUNCTION__ . " short description";
        $parentObjId = $folder->publish();

        $parentNodeId = $folder->mainNode->node_id;

        // Add a child to the newly created node.
        $newArticle = new ezpObject( "article", (int)$parentNodeId );
        $newArticleName = __FUNCTION__ . " article";
        $newArticle->title = $newArticleName;
        $newArticle->intro = __FUNCTION__ . " intro";
        $newArticle->body = __FUNCTION__ . " body";
        $newArticleObjId = $newArticle->publish();

        $newArticleNodeId = $newArticle->mainNode->node_id;

        // Add translation of parent node
        // @TODO: Use a plain site as default, then add translation to the site,
        // if not nor-NO needs to be added to the site by hand.
        $newLanguageCode = "nor-NO";
        $newTranslationName = 'Norsk ' . __FUNCTION__ . " folder";
        $trData = array( "name" => $newTranslationName );
        $folder->addTranslation( $newLanguageCode, $trData );

        $db = eZDB::instance();
        $topLanguageId = (int) eZContentLanguage::topPriorityLanguage()->attribute( 'id' );
        $query = self::buildSql( array( 2, $parentNodeId, $newArticleNodeId ), $topLanguageId );
        $queryResult = $db->arrayQuery( $query );

        $lastId = false;

        foreach ( $queryResult as $urlElement )
        {
            $id = (int) $urlElement['id'];
            $parent = (int) $urlElement['parent'];

            if ( $parent === 0 )
            {
                $lastId = false;
            }

            if ( $lastId !== false )
            {
                self::assertEquals( $lastId, $parent );
            }
            $lastId = $id;
        }
    }

    /**
     * Test that url alias table is intact when translation is added to a node
     * which has been renamed and have children.
     */
    public function testURLAliasTranslatedRenamedFolderWithChildren()
    {
        $folder = new ezpObject( "folder", 2 );
        $parentName = __FUNCTION__ . " folder";
        $folder->name = $parentName;
        $folder->short_description = __FUNCTION__ . " short description";
        $parentObjId = $folder->publish();

        $parentNodeId = $folder->mainNode->node_id;

        // Add a child to the newly created node.
        $newArticle = new ezpObject( "article", (int)$parentNodeId );
        $newArticleName = __FUNCTION__ . " article";
        $newArticle->title = $newArticleName;
        $newArticle->intro = __FUNCTION__ . " intro";
        $newArticle->body = __FUNCTION__ . " body";
        $newArticleObjId = $newArticle->publish();

        $newArticleNodeId = $newArticle->mainNode->node_id;

        // Rename parent node
        $folder->name = "Renamed " . __FUNCTION__ . " folder";
        $folder->publish();

        // Add translation of parent node
        $newLanguageCode = "nor-NO";
        $newTranslationName = 'Norsk ' . __FUNCTION__ . " folder";
        $trData = array( "name" => $newTranslationName );
        $folder->addTranslation( $newLanguageCode, $trData );

        // Verify table structure
        $db = eZDB::instance();
        $topLanguageId = (int)eZContentLanguage::topPriorityLanguage()->attribute( 'id' );
        $query = self::buildSql( array( 2, $parentNodeId, $newArticleNodeId ), $topLanguageId );
        $queryResult = $db->arrayQuery( $query );

        $lastId = false;

        foreach ( $queryResult as $urlElement )
        {
            $id = (int)$urlElement['id'];
            $parent = (int)$urlElement['parent'];

            if ( $parent === 0 )
            {
                $lastId = false;
            }

            if ( $lastId !== false )
            {
                self::assertEquals( $lastId, $parent, "Parent id does not point to the previous parent element with same lang_mask. Broken chain detected." );
            }
            $lastId = $id;
        }
    }

    /**
     * Tests that a node can override another node's history URL.
     *
     * This test will make sure that a ezurlalias_ml history element
     * (is_original = 0) will be re-used (the id won't change) when
     * creating a new node with the same URL as the history element.
     */
    public function testURLAliasReplacement()
    {
        $folderName = "Test Folder " . mt_rand();
        $childName = "Test Child " . mt_rand();
        $db = eZDB::instance();

        // STEP 1: Create test folder
        $folder = new ezpObject( "folder", 2 );
        $folder->name = $folderName;
        $folder->publish();
        $folderNodeID = $folder->mainNode->node_id;

        // STEP 2: Create child node of folder in step 1.
        $child1 = new ezpObject( "article", $folderNodeID );
        $child1->title = $childName;
        $child1->publish();
        $child1NodeID = $child1->mainNode->node_id;

        // STEP 3: Rename child created in step 2.
        $child1->title = "$childName renamed";
        $child1->publish();

        // Extract the ezurlalias_ml history element id for child1. This
        // history element will be reused for child2 a bit further down.
        $query = self::buildSql( array( $child1NodeID ), false, array( "is_original" => 0 ) );
        $child1Result = $db->arrayQuery( $query );

        // STEP 4: Add a second child to the test folder.
        $child2 = new ezpObject( "article", $folderNodeID );
        $child2->title = $childName;
        $child2->publish();

        // Extract ezurlalias_ml id for child2
        $query = self::buildSql( array( $child2->mainNode->node_id ) );
        $child2Result = $db->arrayQuery( $query );

        // STEP 5: Make sure the old history element id and newly created
        // elment is the same.
        self::assertEquals( $child1Result[0]['id'], $child2Result[0]['id'] );
    }

    /**
     * Tests if having non ascii characters in the ezcontentobject name will
     * generate correct URLs.
     */
    public function testNonASCIICharsInName()
    {
        // We add a random number to the end of the name and URL so we
        // are sure to get unique URLs without any crud (__1, __2, etc).
        // Without unique URLs we cannot predict what the URL for the name
        // will be.
        $randomNumber = mt_rand();
        $nonAsciiName = "Noñ äcsíí ©ha®ß ïn ñámé ウ… " . $randomNumber;
        $nonAsciiNameURL = "Noñ-äcsíí-©ha®ß-ïn-ñámé-ウ…-" . $randomNumber;

        // Before we start set the correct URL transformation settings.
        $ini = eZINI::instance();

        $orginialWordSeparator = $ini->variable( 'URLTranslator', 'WordSeparator' );
        $ini->setVariable( 'URLTranslator', 'WordSeparator', 'dash' );

        $orginialTransformationGroup = $ini->variable( 'URLTranslator', 'TransformationGroup' );
        $ini->setVariable( 'URLTranslator', 'TransformationGroup', 'urlalias_iri' );

        // STEP 1: Create test folder
        $folder = new ezpObject( "folder", 2 );
        $folder->name = "Test Folder: $nonAsciiName";
        $folder->publish();

        // STEP 2: Create sub folder with apostrophe in the name
        $subfolder = new ezpObject( "folder", $folder->mainNode->node_id );
        $subfolder->name = "$nonAsciiName";
        $subfolder->publish();

        // STEP 3: Add a child node
        $subfolder->addNode( 2 );

        // STEP 4: Test for success:
        $this->assertEquals( $nonAsciiNameURL, $subfolder->nodes[1]->pathWithNames() );


        // Restore ini settings to their original values
        $ini->setVariable( 'URLTranslator', 'WordSeparator', $orginialWordSeparator );
        $ini->setVariable( 'URLTranslator', 'TransformationGroup', $orginialTransformationGroup );
    }

    /**
     * Test the generated url aliases when a move with name A is moved to another
     * container object with an already existing child element, with name A.
     *
     * This test makes sure the parent id in ezurlalias_ml gets updated when moving
     * a node into a new container that already contains a node with the same name
     * as the node that is being moved.
     *
     * Test Outline
     * ------------
     * 1. Create Folder1
     * 2. Create Child1 as child of Folder1
     * 3. Create Folder2
     * 4. Create Child2 - with same name as Child1 - as child of Folder2
     * 5. Move Child2 from Folder2 into Folder1.
     *
     * @DEFINITION: Child2 should have its url alias adjusted to
     * avoid name and url collisions with Child1.
     */
    public function testURLAliasMoveNodesWithSameName()
    {
        // STEP 1: Create folder1
        $folder1 = new ezpObject( "folder", 2 );
        $folder1->name = "Test Folder 1: " . __FUNCTION__;
        $folder1->publish();

        // STEP 2: Create child1 with folder1 as parent
        $child1 = new ezpObject( "article", $folder1->mainNode->node_id );
        $child1->title = "Test Child: " . __FUNCTION__;
        $child1->publish();

        // STEP 3: Create folder2
        $folder2 = new ezpObject( "folder", 2 );
        $folder2->name = "Test Folder 2: " . __FUNCTION__;
        $folder2->publish();

        // STEP 4: Create child2 with exact same name as child1 and folder2 as its parent
        $child2 = new ezpObject( "article", $folder2->mainNode->node_id );
        $child2->title = "Test Child: " . __FUNCTION__;
        $child2->publish();

        // STEP 5: Move child2 into folder1
        $child2->mainNode->move( $folder1->mainNode->node_id );


        // Extract id for folder1 from ezurlalias_ml
        $db = eZDB::instance();
        $query = self::buildSql( array( $folder1->mainNode->node_id ) );
        $folderUrlEntry = $db->arrayQuery( $query );

        // Extract text for child1 from ezurlalias_ml
        $query = self::buildSql( array( $child1->mainNode->node_id ), false, array( 'is_original' => 1 ) );
        $child1UrlEntry = $db->arrayQuery( $query );

        // Extract parent id for child2 from ezurlalias_ml
        $query = self::buildSql( array( $child2->mainNode->node_id ), false, array( 'is_original' => 1 ) );
        $child2UrlEntry = $db->arrayQuery( $query );

        // Since child2 has been moved to folder1, child2's parent ID should
        // equal folder1's ID.
        self::assertEquals( $folderUrlEntry[0]['id'], $child2UrlEntry[0]['parent'] );
        self::assertNotEquals( $child1UrlEntry[0]['id'], $child2UrlEntry[0]['text'] );
    }

    /**
     * Test old url aliases forward to the new the current alias of a node.
     */
    public function testURLAliasForwardingHistoryElements()
    {
        /*
            ================
            = Test outline =
            ================

            1. Create folder
            2. Create folder=>MyElement
            3. Rename MyElement -> MyElementA
            4. Rename MyElement -> MyElementB
            5. Rename MyElement -> MyElementC
        */
        $db = eZDB::instance();

        $folder = new ezpObject( "folder", 2 );
        $parentName = __FUNCTION__;
        $folder->name = $parentName;
        $folder->publish();

        // ==========
        // = Name 0 =
        // ==========

        $myElement = new ezpObject( "article", $folder->mainNode->node_id );
        $name0 = "MyElement";
        $myElement->title = $name0;
        $myElement->publish();

        $myElementId = $myElement->mainNode->node_id;

        $q = self::buildSql( array( $myElementId ) );

        // ==========
        // = Name 1 =
        // ==========

        $name1 = "MyElementA";
        $myElement->title = $name1;
        $myElement->publish();

        $resultSet = $db->arrayQuery( $q );
        $validData1 = self::verifyURLAliasElementHistoryEntry( $name0, $name1, $resultSet );
        self::assertTrue( $validData1 );


        // ==========
        // = Name 2 =
        // ==========

        $name2 = "MyElementB";
        $myElement->title = $name2;
        $myElement->publish();

        $resultSet = $db->arrayQuery( $q );
        $validData2 = self::verifyURLAliasElementHistoryEntry( $name1, $name2, $resultSet );
        self::assertTrue( $validData2 );


        // ==========
        // = Name 3 =
        // ==========

        $name3 = "MyElementC";
        $myElement->title = $name3;
        $myElement->publish();

        $resultSet = $db->arrayQuery( $q );
        $validData3 = self::verifyURLAliasElementHistoryEntry( $name2, $name3, $resultSet );
        self::assertTrue( $validData3 );


        // ==========
        // = Name 4 =
        // ==========

        $name4 = "MyElementD";
        $myElement->title = $name4;
        $myElement->publish();

        $resultSet = $db->arrayQuery( $q );
        $validData4 = self::verifyURLAliasElementHistoryEntry( $name3, $name4, $resultSet );
        self::assertTrue( $validData4 );
    }

    /**
     * Function that verifies structure of url elements. It will check that old
     * element $oldName point to the $newName of the most current url element.
     *
     * Note: This method should be run immeditately after creation of the new url
     * element if not a history element might have been reused for another name.
     *
     * Test Outline
     * ------------
     * 1. Check that oldname now is a history element is_original = 0
     * 2. Remember id of old element
     * 3. Find new element and that is the most recent, and not a custom
     *    alias is_original = 1, is_alias = 0
     * 4. Remember id of new element
     * 5. Verify that old element links to new element
     *
     * @param string $oldName
     * @param string $newName
     * @param array $urlElementData
     * @return boolean
     */
    public static function verifyURLAliasElementHistoryEntry( $oldName, $newName, $urlElementData )
    {
        $oldElementValid = false;
        $oldId = false;
        $newElementValid = false;
        $newId = false;
        $linkToNew = false;

        foreach ( $urlElementData as $element )
        {
            $text = $element['text'];
            $id = (int)$element['id'];
            $link = (int)$element['link'];
            $isOriginal = (int)$element['is_original'] === 0 ? false : true;
            $isAlias = (int)$element['is_alias'] === 0 ? false : true;

            if ( !$oldElementValid and $text == $oldName )
            {
                if ( !$isOriginal )
                {
                    $oldElementValid = true;
                    $linkToNew = $link;
                    $oldId = $id;
                }
            }

            if ( !$newElementValid and $text == $newName )
            {
                if ( $isOriginal and !$isAlias )
                {
                    $newElementValid = true;
                    $newId = $id;
                }
            }
        }

        $validHistoryElement = ( $linkToNew === $newId );
        return $validHistoryElement;
    }

    /**
     * Test that translated uri elements reuse and update existing uri element,
     * when the new translation name is the same as an already existing name in
     * a different translation.
     *
     * Test Outline
     * ------------
     * 1. Add folder
     * 2. Add ChildNode of folder
     * 3. Add translation Child (nor-NO) to ChildNode with same name as ChildNode
     * 4. ChildNode entry reused and its lang_mask updated to 6
     *    ( = 2 + 4; eng + nor )
     */
    public function testTranslatedURLAliasElementWithExistingName()
    {
        $db = eZDB::instance();

        // STEP 1: Add test folder
        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__;
        $folder->publish();

        // STEP 2: Add child below folder
        $child = new ezpObject( "article", $folder->mainNode->node_id );
        $child->title = "Child";
        $child->publish();

        // STEP 3: Add translation to child with same name
        $translationAttributes = array( "title" => "Child" );
        $child->addTranslation( "nor-NO", $translationAttributes );

        // STEP 4: Verify that lang_mask was updated to 6.
        $query = self::buildSql( array( $child->mainNode->node_id ) );
        $result = $db->arrayQuery( $query );

        self::assertEquals( (int) $result[0]['lang_mask'], 6 );
    }

    /**
     * Test that the language mask of an url alias element is downgraded
     * when a translation is removed.
     *
     * Test Outline
     * ------------
     * 1. Add a Folder.
     * 2. Add a ChildNode of folder.
     * 3. Add a translation (nor-NO) to ChildNode (lang_mask should be 6 now).
     * 4. Remove ChildNode's translation (nor-NO).
     * 5. Make sure lang_mask was decreased back down to 2.
     */
    public function testURLAliasEntryWhenTranslationRemoved()
    {
        $db = eZDB::instance();

        // STEP 1: Add test folder
        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__;
        $folder->publish();

        // STEP 2: Add child below folder
        $child = new ezpObject( "article", $folder->mainNode->node_id );
        $child->title = "Child";
        $child->publish();

        // STEP 3: Add translation to child with the same name
        $translationAttributes = array( "title" => "Child" );
        $child->addTranslation( "nor-NO", $translationAttributes );

        // STEP 4: Remove nor-NO translation
        $child->removeTranslation( "nor-NO" );

        // STEP 5: Verify that lang_mask has been decreased back down to 2.
        $query = self::buildSql( array( $child->mainNode->node_id ) );
        $result = $db->arrayQuery( $query );

        self::assertEquals( 2, (int) $result[0]['lang_mask'] );
    }

    /**
     * Test when a language is added it does not update link fields of history
     * elements in different languages.
     *
     * @todo This test must be updated when @see eZURLAliasML has been updated
     * to not modify lang_mask field of history elements.
     *
     * @DEFINITION: New translations should not update link field of
     *              history entries in another language
     *
     *              This is new defined behaviour in 4.0.1, 4.1.0 and up
     */
    public function testURLAliasTranslationLinkValues()
    {
        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__;
        $parentObjId = $folder->publish();

        $article = new ezpObject( "article", $folder->mainNode->node_id );
        $validNames[0] = "ChildNode";
        $article->title = $validNames[0];
        $article->publish();

        // Building up some url history
        $validNames[1] = "ChildNodeRenamed";
        $article->title = $validNames[1];
        $article->publish();

        $validNames[2] = "ChildNodeRenamedA";
        $article->title = $validNames[2];
        $article->publish();

        $validNames[3] = "ChildNodeRenamedB";
        $article->title = $validNames[3];
        $article->publish();

        // Add translation of parent node
        $newLanguageCode = "nor-NO";
        $newTranslationName = "Norsk ChildNode";
        $trData = array( "title" => $newTranslationName );
        $article->addTranslation( $newLanguageCode, $trData );

        $db = eZDB::instance();
        $topLanguageId = (int)eZContentLanguage::topPriorityLanguage()->attribute( 'id' );

        $curEngElementSql = self::buildSql( array( $article->mainNode->node_id ), $topLanguageId,
                                            array( 'is_original' => 1,
                                                   'is_alias' => 0 ) );

        $curEngElement = $db->arrayQuery( $curEngElementSql );
        // There should only be one result here
        // (since we know that are are no other occurences of the node3
        // we just created)

        self::assertEquals( 1, count( $curEngElement ),
            "More than one active entry for the current node detected." );

        $curEngId = (int)$curEngElement[0]['id'];

        $historyElementsSql = self::buildSql( array( $article->mainNode->node_id ), false,
                                              array( 'is_original' => 0,
                                                     'is_alias' => 0 ) );

        $possibleHistoryElements = $db->arrayQuery( $historyElementsSql );

        // Since lang_mask might not be correct in the first version, we must
        // filter out the ones we know we just created
        foreach ( $possibleHistoryElements as $histEl )
        {
            if ( in_array( $histEl['text'], $validNames, true ) )
            {
                // We have a history element in the language we want
                // Check that the link is pointint to the correct element
                // We assert all occurences, but only one error is enough for
                // the test to fail

                self::assertEquals( (int)$histEl['link'], $curEngId,
                    "Link element does not point to the correct new entry." );
            }
        }
    }

    /**
     * Test that history elements keep the language associated with them
     * @DEFINITION: This is new defined behaviour in 4.0.1, 4.1.0 and up
     */
    public function testURLAliasHistoryElementsRetainLangMask()
    {
        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__;
        $parentObjId = $folder->publish();

        $article = new ezpObject( "article", $folder->mainNode->node_id );
        $article->title = "ChildNode";
        $article->publish();

        // Building up some url history
        $article->title = "ChildNodeRenamed";
        $article->publish();

        $expectedLangMask = $article->language_mask;

        $db = eZDB::instance();

        $historyElementsSql = self::buildSql( array( $article->mainNode->node_id ), false,
                                              array( 'is_original' => 0,
                                                     'is_alias' => 0 ) );

        $historyElements = $db->arrayQuery( $historyElementsSql );

        foreach ( $historyElements as $element )
        {
            $langMask = (int)$element['lang_mask'];
            self::assertEquals( $expectedLangMask, $langMask,
                "lang_mask of history element is not the same" );
        }
    }

    /**
     * Test that when new translated url elements are added that its children
     * are not updated with new parent id, if lang_mask is different
     *
     * Test Outline
     * ------------
     * 1. Create a folder
     * 2. Create a child with the folder as parent
     * 3. Rename the folder
     * 4. Add a translation to folder
     * 5. Make sure child's ezurlalias_ml parent id points to folder's
     *    ezurlalias_ml id for the latest entry with the same language.
     *
     * @DEFINITION: This is new defined behaviour in 4.0.1, 4.1.0 and up
     */
    public function testURLAliasTranslationChildNodeParentEntries()
    {
        $db = eZDB::instance();
        // STEP 1: Create folder
        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__ . mt_rand();
        $folder->publish();

        // STEP 2: Create child with folder as parent
        $child = new ezpObject( "folder", $folder->mainNode->node_id );
        $child->name = "Child";
        $child->publish();

        // STEP 3: Rename folder
        $folder->name = $folder->name . " (renamed)";
        $folder->publish();

        // Extract the ezurlalias_ml id for the renamed entry of folder
        $query = self::buildSql( array( $folder->mainNode->node_id ), false,
                                 array( "is_original" => 1 ) );
        $result = $db->arrayQuery( $query );
        $folderUrlAliasId = $result[0]['id'];

        // STEP 4: Add a norwegian translation to folder
        $attributes = array( 'name' => __FUNCTION__ . "(norwegian)" );
        $folder->addTranslation( 'nor-NO', $attributes );

        // STEP 5: Verify child's parent id.
        $query = self::buildSql( array( $child->mainNode->node_id ) );
        $result = $db->arrayQuery( $query );
        $childUrlAliasParentId = $result[0]['parent'];

        self::assertEquals( (int) $childUrlAliasParentId, (int) $folderUrlAliasId );
    }

    /**
     * Test that url data for translations in all node locations are removed,
     * when a translations is removed.
     */
    public function testNodeRemovalMultipleLocationsWithTranslations()
    {
        $folder1 = new ezpObject( "folder", 2 );
        $folder1->name = __FUNCTION__;
        $folder1->publish();

        $child = new ezpObject( "article", $folder1->mainNode->node_id );
        $child->title = "Tester";
        $child->publish();

        $languageCode = "nor-NO";
        $trData = array( "title" => "NorTester" );
        $child->addTranslation( $languageCode, $trData );

        $folder2 = new ezpObject( "folder", 2 );
        $folder2->name = __FUNCTION__ . "-Other";
        $folder2->publish();

        $newPlacement = $child->addNode( $folder2->mainNode->node_id );
        $child->removeTranslation( $languageCode );

        // Verify that all norwegian url entries are also removed
        $language = eZContentLanguage::fetchByLocale( $languageCode, false );
        $languageId = (int)$language->attribute( 'id' );
        $fetchNorUrlEntriesSql = self::buildSql( array(
                                                        $child->mainNode->node_id,
                                                        $newPlacement->attribute( 'node_id' )
                                                    ),
                                              $languageId
                                            );
        $db = eZDB::instance();
        $norUrlEntries = $db->arrayQuery( $fetchNorUrlEntriesSql );

        self::assertEquals( 0, count( $norUrlEntries ),
            "There should be no nor-NO url entries left" );
    }

    /**
     * Test that url lookup falls back to system url when url path lookup fails.
     */
    public function testPathWithNamesFallback()
    {
        $fallbackFolder = new ezpObject( "folder", 2 );
        $fallbackFolder->name = __FUNCTION__;
        $fallbackFolder->publish();

        // We simulate a failing url lookup by removing the entry from the table.
        eZURLAliasML::removeByAction( "eznode", $fallbackFolder->mainNode->node_id );

        $expectedPath = 'content/view/full/' . $fallbackFolder->mainNode->node_id;

        self::assertEquals( $expectedPath, $fallbackFolder->mainNode->node->pathWithNames(),
                            "The expected fallback system-url was not generated" );
    }

    /**
     * Tests that updateSubTreePath() does not mess up a previously defined
     * alias for a node.
     */
    public function testUpdateSubTreePathLeavesAliasesAlone()
    {
        $random = mt_rand();

        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__ . " Folder " . $random;
        $folder->publish();

        $child = new ezpObject( "article", $folder->mainNode->node_id );
        $child->title = __FUNCTION__ . " Child " . $random;
        $child->publish();

        $folderURL = eZURLAliasML::fetchByAction( 'eznode', $child->mainNode->node_id );

        // Create an alias element using storePath()
        $path = "ThisIsATestAlias_$random";
        $action = "eznode:" . $child->mainNode->node_id;
        $alias = eZURLALiasML::storePath( $path, $action, false, $folderURL[0]->attribute( 'id' ) );

        $child->mainNode->updateSubTreePath();

        // Refetch the alias ezurlalias_ml element
        $db = eZDB::instance();
        $actionValues = array( $child->mainNode->node_id );
        $extraConditions = array( 'id' => $alias['element']->attribute( 'id' ) );
        $aliases = $db->arrayQuery( self::buildSql( $actionValues, false, $extraConditions ) );

        self::assertEquals( 1, (int) $aliases[0]['is_alias'] );
        self::assertEquals( 1, (int) $aliases[0]['is_original'] );
    }

    /**
     * Test that store path does not reparent children of entries with same action
     * if they are custom aliases
     */
    public function testStorePathReparentAliasEntries()
    {
        // Create a real folder
        $r = mt_rand();
        $theRealFolder = new ezpObject( "folder", 2 );
        $theRealFolder->name = __FUNCTION__ . $r;
        $theRealFolder->publish();

        // Create a real article in real folder
        $myNode = new ezpObject( "article", $theRealFolder->mainNode->node_id );
        $myNode->title = "MyNode";
        $myNode->publish();

        // We fetch the url path element of our real folder entry,
        // in order to create an alias to it
        $realFolderUrl  = eZURLAliasML::fetchByAction( "eznode", $theRealFolder->mainNode->node_id );
        $realFolderUrlId = $realFolderUrl[0]->attribute( 'id' );

        $myNodeUrl  = eZURLAliasML::fetchByAction( "eznode", $myNode->mainNode->node_id );
        $myNodeUrlId = $myNodeUrl[0]->attribute( 'id' );

        // We create a custom url alias for the real folder under $realFolderAliasPath
        // Note the first path element will be a virtual nop:
        $realFolderAliasPath = "VirtualPath/AliasToTheRealFolder{$r}";
        $action = "eznode:" . $theRealFolder->mainNode->node_id;
        $realFolderAlias = eZURLAliasML::storePath( $realFolderAliasPath, $action, false, $realFolderUrlId );
        $realFolderAliasId = $realFolderAlias['element']->attribute( 'id' );

        /*
           We create a custom url alias for MyNode, which is located underneath
           the alias for real folder, in the url path

           \
           |-- TheRealFolder                (node a)
           |   `-- MyNode                   (node b)
           `-- VirtualPath                  (nop:)
               `-- AliasToTheRealFolder     (node a)
                   `-- AliasToMyNode        (node b)

        */
        // $myNodeAliasPath = "{$realFolderAliasPath}/AliasToMyNode{$r}";
        $myNodeAliasPath = "AliasToMyNode{$r}";
        $myNodeAction = "eznode:" . $myNode->mainNode->node_id;
        $myNodeAlias = eZURLAliasML::storePath( $myNodeAliasPath, $myNodeAction, false, $myNodeUrlId, false, $realFolderAliasId);
        $myNodeAliasOriginalParent = $myNodeAlias['element']->attribute( 'parent' );

        // We republish the real folder, not strictly necessary to change the
        // but it is more illustrative.
        $theRealFolder->name = __FUNCTION__ . $r . "Renamed";
        $theRealFolder->publish();

        // Assert that our alias to MyNode was indeed placed underneath $realFolderAliasPath
        self::assertEquals( $realFolderAliasId, $myNodeAliasOriginalParent );

        $db = eZDB::instance();
        $q = self::buildSql( array( $myNode->mainNode->node_id ), false, array( 'is_alias' => 1, 'is_original' => 1 ) );
        $myNodeAliasPostChange = $db->arrayQuery( $q );
        $myNodeAliasPostChangeParent = $myNodeAliasPostChange[0]['parent'];

        // Make sure the the alias to MyNode have not been moved in the url path
        // after publishing the parent of the real node.
        self::assertEquals( $myNodeAliasOriginalParent, $myNodeAliasPostChangeParent,
                            "Parent have custom url alias have been changed inadvertently." );
    }

    /**
     * Test regression for issue #12720: Unable to edit a node
     *
     * Test Outline
     * ------------
     * 1. Create a folder, "Root folder"
     * 2. Translate "Root folder" into Norwegian
     * 3. Create a child node of "Root folder"
     * 4. Rename "Root folder" in Norwegian.
     * 5. Rename "Root folder" again in Norwegian.
     * 6. Add another translation to the child
     * 7. Rename "Root folder"
     *
     * @result: Fatal error: A database transaction in eZ Publish failed.
     *          Query error: Duplicate entry '38-ec7cb3d81cfaafb249b33071c6c9e2be' 
     *          for key 1. Query: UPDATE ezurlalias_ml SET parent = 38 WHERE parent = 39
     * @expected: No fatal error 
     * @link http://issues.ez.no/12720
     */
    public function testTransactionErrorWhenEditingNode()
    {
        $jpn = eZContentLanguage::addLanguage( "jpn-JP", "Japanese" );

        // STEP 1: Create a folder, "Root folder"
        $rootFolder = new ezpObject( "folder", 2 );
        $rootFolder->name = "Root folder";
        $rootFolder->publish();

        // STEP 2: Translate "Root folder" into Norwegian
        $trData = array( "name" => "Root folder Norwegian" );
        $rootFolder->addTranslation( "nor-NO", $trData );

        // STEP 3: Create a child node of "Root folder"
        $child = new ezpObject( "folder", $rootFolder->mainNode->node_id );
        $child->name = "Child";
        $child->publish();

        // STEP 4: Rename "Root folder" in Norwegian.
        $rootFolder->refresh();
        $newVersion = $rootFolder->createNewVersion( false, true, 'nor-NO' );
        $norDataMap = $rootFolder->fetchDataMap( $newVersion->attribute( 'version' ), "nor-NO" );
        $norDataMap['name']->setAttribute( 'data_text', "Root Folder Norwegian Renamed" );
        $norDataMap['name']->store();
        ezpObject::publishContentObject( $rootFolder->object, $newVersion );

        // STEP 5: Rename "Root folder" again in Norwegian.
        $rootFolder->refresh();
        $newVersion = $rootFolder->createNewVersion( false, true, 'nor-NO' );
        $norDataMap = $rootFolder->fetchDataMap( $newVersion->attribute( 'version' ), "nor-NO" );
        $norDataMap['name']->setAttribute( 'data_text', "Root Folder Norwegian Renamed again" );
        $norDataMap['name']->store();
        ezpObject::publishContentObject( $rootFolder->object, $newVersion );

        // STEP 6: Add another translation to the child
        $trData = array( "name" => "Child Japanese" );
        $child->addTranslation( "jpn-JP", $trData );

        // STEP 7: Rename "Root folder"
        $rootFolder->refresh();
        $newVersion = $rootFolder->createNewVersion( false, true, 'eng-GB' );
        $dataMap = $rootFolder->fetchDataMap( $newVersion->attribute( 'version' ), "eng-GB" );
        $dataMap['name']->setAttribute( 'data_text', "Root folder English renamed" );
        $dataMap['name']->store();
        ezpObject::publishContentObject( $rootFolder->object, $newVersion );
    }

    /**
     * Test for URL-alias issue which could occur when a parent entry was split
     * across multiple IDs, and children with various language masks did not
     * receive the correct parent id. Such a case with a split parent id, made
     * it impossible to handle this situation correctly.
     * 
     * The fix constitutes of always creating the current entries for an action
     * in the same id.
     *
     * @link http://issues.ez.no/12720
    */
    public static function testSplitParentSyndrome()
    {
        $db = eZDB::instance();
        // Ordered array containing the current name for an entry at level,
        // where level is the same as the index.
        $nameStructure = array();
        $nameStructure[] = array( __FUNCTION__ );

        // STEP 1: Create Root folder
        $rootFolder = new ezpObject( "folder", 2 );
        $rootFolder->name = $nameStructure[0][0];
        $rootFolder->publish();

        $rootId = $rootFolder->mainNode->node_id;

        // STEP 2: Create Child article
        $nameStructure[] = array( "ChildNode" );
        $child = new ezpObject( "article", $rootFolder->mainNode->node_id );
        $child->title = $nameStructure[1][0];
        $child->publish();

        $childId = $child->mainNode->node_id;

        $query = self::buildSql( array( 2, $rootId, $childId ) );
        $rows = $db->arrayQuery( $query );
        $result = self::verifyUrlEntryParentStructure( $nameStructure, $rows );
        self::assertTrue( $result, "Fail after step 2" );

        // STEP 4: Renaming root folder
        // Updating the name structure on renames
        $nameStructure[0] = array( "TestContainerA" );
        $rootFolder->name = $nameStructure[0][0];
        $rootFolder->publish();

        // STEP 5: Renaming root folder
        $nameStructure[0] = array( "TestContainerB" );
        $rootFolder->name = $nameStructure[0][0];
        $rootFolder->publish();

        $rows = $db->arrayQuery( $query );
        $result = self::verifyUrlEntryParentStructure( $nameStructure, $rows );
        self::assertTrue( $result, "Fail after step 5"  );

        // STEP 6: Rename root folder back to name in history.
        $nameStructure[0] = array( "TestContainer" );
        $rootFolder->name = $nameStructure[0][0];
        $rootFolder->publish();

        $rows = $db->arrayQuery( $query );
        $result = self::verifyUrlEntryParentStructure( $nameStructure, $rows );
        self::assertTrue( $result, "Fail after step 6"  );

        // STEP 7: Translate root folder into norwegian.
        $nameStructure[0][] = "TestBinge";
        $trData = array( "name" => $nameStructure[0][1] );
        $rootFolder->addTranslation( "nor-NO", $trData );

        $rows = $db->arrayQuery( $query );
        $result = self::verifyUrlEntryParentStructure( $nameStructure, $rows );
        self::assertTrue( $result, "Fail after step 7"  );

        // STEP 8: Translate childe article into norwegian
        $nameStructure[1][] = "BarneNode";
        $trData = array( "title" => $nameStructure[1][1] );
        $child->addTranslation( "nor-NO", $trData );

        $rows = $db->arrayQuery( $query );
        $result = self::verifyUrlEntryParentStructure( $nameStructure, $rows );
        self::assertTrue( $result, "Fail after step 8"  );

        // STEP 9: We rename the Norwegian translation of the child element to the
        //         same name as the English translation
        unset( $nameStructure[1][1] );
        $child->refresh();
        $newVersion = $child->createNewVersion( false, true, 'nor-NO' );
        $norDataMap = $child->fetchDataMap( $newVersion->attribute( 'version' ), "nor-NO" );
        $norDataMap['title']->setAttribute( 'data_text', $nameStructure[1][0] );
        $norDataMap['title']->store();
        ezpObject::publishContentObject( $child->object, $newVersion );

        $rows = $db->arrayQuery( $query );
        $result = self::verifyUrlEntryParentStructure( $nameStructure, $rows );
        self::assertTrue( $result, "Fail after step 9"  );

        // STEP 10: We remove Norwegian translation.
        $child->refresh();
        $child->removeTranslation( "nor-NO" );

        $rows = $db->arrayQuery( $query );
        $result = self::verifyUrlEntryParentStructure( $nameStructure, $rows );
        self::assertTrue( $result, "Fail after step 10"  );
    }

    /**
     * Helper method to validate the parent structure of url entries.
     * 
     * This method will look at the parent references and validate this to the
     * actual id values of the parent entries.
     * 
     * @param array $nameTable 
     * @param array $urlEntryData 
     */
    public static function verifyUrlEntryParentStructure( $nameTable, $urlEntryData )
    {
        $parentStructure = array();
        $translationStructure = array();

        foreach ( $nameTable as $level => $names )
        {
            foreach ( $names as $name )
            {
                $entry = self::urlEntryForName( $name, $urlEntryData );
                if ( !$entry )
                    return false;

                $isOriginal = (int)$entry['is_original'] === 0 ? false : true;
                if ( !$isOriginal )
                    return false;

                $id = (int)$entry['id'];
                $langMask = (int)$entry['lang_mask'];
                $d = eZContentLanguage::decodeLanguageMask( $langMask );

                foreach ( $d['language_list'] as $realLang )
                {
                    if ( !isset( $parentStructure[$level] ) )
                    {
                        $parentStructure[$level] = array( "languages" => array( $realLang => $id ),
                                                          "always_available" => $d['always_available'] );
                    }
                    else
                    {
                        $parentStructure[$level]["languages"][$realLang] = $id;
                    }

                    $parent = (int)$entry['parent'];
                    if ( $parent === 0 )
                        continue;

                    $parentLevel = $level - 1;
                    $langEntries = $parentStructure[$parentLevel]["languages"];
                    $parentLang = -1;
                    $langMatch = false;

                    foreach ( $langEntries as $lang => $realParentId )
                    {
                        $langMatch = ( $lang & $realLang ) > 0;
                        if ( !$langMatch )
                            continue;

                        $parentLang = $realParentId;
                        break;
                    }

                    if ( ( $langMatch && $parent !== $parentLang ) || ( !$langMatch && !$parentStructure[$parentLevel]['always_available'] ) )
                        return false;
                }
            }
        }
        return true;
    }


    /**
     * Helper method to fetch an url entry for a given text from a db result set.
     *
     * @param string $name 
     * @param array $urlEntryData 
     */
    public static function urlEntryForName( $name, $urlEntryData )
    {
        $ret = false;
        foreach ( $urlEntryData as $entry )
        {
            $text = $entry['text'];
            if ( $text === $name )
            {
                $ret = $entry;
                break;
            }
        }
        return $ret;
    }

    /**
     * Tests a problem which arose when a combined URL entry,
     * representing several translations are split up, by one translation being
     * changed to to an earlier history entry, of that same entry.
     *
     */
    function testURLAliasSplitParentTranslation()
    {
        $db = eZDB::instance();

        // STEP 1: Add test folder
        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__;
        $folder->publish();

        // STEP 2: Add child below folder
        $child = new ezpObject( "folder", $folder->mainNode->node_id );
        $child->name = "Child";
        $child->publish();

        // Sub-sub children disabled for now, might be used in future, for 
        // further assertions.
        // // STEP 2a: Add a sub-sub child
        // $subChild1 = new ezpObject( 'article', $child->mainNode->node_id );
        // $subChild1->title = "SubChild";
        // $subChild1->publish();
        // 
        // // STEP 2b: Add a sub-sub child
        // $subChild2 = new ezpObject( 'article', $child->mainNode->node_id );
        // $subChild2->title = "SubChildOther";
        // $subChild2->publish();
        // 
        // // STEP 2ba: Adding sub-sub child translation
        // $norSubChild2Trans = array( "title" => "SubChildOtherNor" );
        // $subChild2->addTranslation( "nor-NO", $norSubChild2Trans );
        // 
        // // STEP 2c: Add a sub-sub child
        // $subChild3 = new ezpObject( 'article', $child->mainNode->node_id );
        // $subChild3->title = "SubChildThird";
        // $subChild3->publish();
        // 
        // // STEP 2ca: Addubg sub-sub child translation
        // $norSubChild3Trans = array( "title" => "SubChildThird" );
        // $subChild3->addTranslation( "nor-NO", $norSubChild3Trans );

        // STEP 3: Add translation to child with the same name
        $translationAttributes = array( "name" => "Child" );
        $child->addTranslation( "nor-NO", $translationAttributes );

        // STEP 4: Update the translation
        $child->refresh();
        $newVersion = $child->createNewVersion( false, true, 'nor-NO' );
        $norDataMap = $child->fetchDataMap( $newVersion->attribute( 'version' ), "nor-NO" );
        $norDataMap['name']->setAttribute( 'data_text', 'NorChildChanged' );
        $norDataMap['name']->store();
        ezpObject::publishContentObject( $child->object, $newVersion );

        // STEP 5: 
        $child->refresh();
        $child->name = "Renamed child";
        $child->publish();

        // STEP 6: 
        $child->refresh();
        $child->name = "Child changed";
        $child->publish();

        // STEP 7:
        $child->refresh();
        $newVersion = $child->createNewVersion( false, true, 'nor-NO' );
        $norDataMap = $child->fetchDataMap( $newVersion->attribute( 'version' ), "nor-NO" );
        $norDataMap['name']->setAttribute( 'data_text', 'NorChildChanged again' );
        $norDataMap['name']->store();
        ezpObject::publishContentObject( $child->object, $newVersion );

        // STEP 8:
        $child->refresh();
        $newVersion = $child->createNewVersion( false, true, 'nor-NO' );
        $norDataMap = $child->fetchDataMap( $newVersion->attribute( 'version' ), "nor-NO" );
        $norDataMap['name']->setAttribute( 'data_text', 'Child changed' );
        $norDataMap['name']->store();
        ezpObject::publishContentObject( $child->object, $newVersion );

        // STEP 9:
        $child->refresh();
        $newVersion = $child->createNewVersion( false, true, 'nor-NO' );
        $norDataMap = $child->fetchDataMap( $newVersion->attribute( 'version' ), "nor-NO" );
        $norDataMap['name']->setAttribute( 'data_text', 'NorChildChanged again' );
        $norDataMap['name']->store();
        ezpObject::publishContentObject( $child->object, $newVersion );

        $query = self::buildSql( array( $child->mainNode->node_id ) );
        $result = $db->arrayQuery( $query );

        $initialTranslationChild = self::urlEntryForName( "Child-changed", $result );
        $translationChild = self::urlEntryForName( 'NorChildChanged-again', $result );

        self::assertEquals( (int)$initialTranslationChild['id'], (int)$translationChild['id'], "Current translations of the same node need to have the same id." );
    }
}

?>
