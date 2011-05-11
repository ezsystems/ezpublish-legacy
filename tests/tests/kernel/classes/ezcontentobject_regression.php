<?php
/**
 * File containing the eZContentObjectRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZContentObjectRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZContentObject Regression Tests" );
    }

    /**
     * Test regression for #13815: eZContentObject->fetchAttributesByIdentifier
     * fails if $languageArray contains non string values
     *
     * This tests verifies that eZContentObject->fetchAttributesByIdentifier
     * can handle non string values inside the $languageArray parameter.
     *
     * @link http://issues.ez.no/13815
     */
    public function testfetchAttributesByIdentifier()
    {
        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__;
        $folder->short_description = "123";
        $folder->publish();

        $identifiers = array( 'name' );

        $objects = $folder->fetchAttributesByIdentifier( $identifiers, false, false );
        $objects2 = $folder->fetchAttributesByIdentifier( $identifiers, false, array( false ) );

        self::assertEquals( $objects, $objects2 );
    }

    /**
     * Test scenario for issue #13552: Broken datamap caching when copying
     * content objects
     *
     * This test verifies that the content object attributes are fresh and not
     * cached versions of the original object. We can achieve this by comparing
     * the content object attribute ids, and make sure they are not the same.
     *
     */
    public function testDataMapFreshOnCopyObject()
    {
        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__;
        $folder->short_description = "123";
        $folder->publish();

        $attrObj1 = self::compareObjectAttributeIds( $folder->object );

        $newObject = self::copyObject( $folder->object, 2 );
        $attrObjCopy = self::compareObjectAttributeIds( $newObject );

        $res = array_intersect_assoc( $attrObj1, $attrObjCopy );

        // If the intersection of key-valye pairs in the two arrays are empty,
        // all the attribute ids are different in the two objects, and we have
        // fresh copy
        self::assertTrue( empty( $res ), "The copied object does contain fresh content object attributes" );
    }

     /**
     * Test mainNode()
     *
     * create object like this structure:
     *
     * rootnode(folder) - objectNode1(folder) - obj(article, language: default and nor-NO)
     *                  \ objectNode2(folder) /
     */
    public function testIssue016395()
    {
        //create two nodes with one object
        $parentNode = new ezpObject( 'folder', 2 );
        $parentNode->publish();

        $objectNode1 = new ezpObject( 'folder', $parentNode->mainNode->node_id );
        $objectNode1->publish();
        $objectNode2 = new ezpObject( 'folder', $parentNode->mainNode->node_id );
        $objectNode2->publish();

        $obj = new ezpObject( 'article', $objectNode1->mainNode->node_id );
        $obj->title = 'English article';
        $obj->body = 'This an English article';
        $obj->publish();

        $obj->addNode( $objectNode2->mainNode->node_id );
        //create a translation
        $languageData = array();
        $languageData['title'] = 'Norsk artikkel';
        $languageData['body'] = 'Dette er en norsk artikkel.';
        $obj->addTranslation( 'nor-NO', $languageData );

        //assert the main language and translation language
        $objectMainNode = $obj->object->mainNode();
        $this->assertEquals( 'English article', $objectMainNode ->getName() );
        $this->assertEquals( 'Norsk artikkel', $objectMainNode->getName( 'nor-NO' ) );
        $tempLanguage = $objectMainNode->currentLanguage();
        $objectMainNode->setCurrentLanguage( 'nor-NO' );
        $this->assertEquals( 'Norsk artikkel', $objectMainNode->attribute( 'name' ) );
        $objectMainNode->setCurrentLanguage( $tempLanguage );
    }


    /**
     * Create a copy of an object.
     *
     * The basis for this method is taken from kernel/content/copy.php
     *
     * @todo Merge this method into kernel wrapper's object class.
     *
     * @param eZContentObject $object
     * @param int $newParentNodeID
     * @return eZContentObject
     */
    public static function copyObject( $object, $newParentNodeID )
    {
        $newParentNode = eZContentObjectTreeNode::fetch( $newParentNodeID );

        $db = eZDB::instance();
        $db->begin();
        $newObject = $object->copy( true );
        // We should reset section that will be updated in updateSectionID().
        // If sectionID is 0 than the object has been newly created
        $newObject->setAttribute( 'section_id', 0 );
        $newObject->store();

        $curVersion        = $newObject->attribute( 'current_version' );
        $curVersionObject  = $newObject->attribute( 'current' );
        $newObjAssignments = $curVersionObject->attribute( 'node_assignments' );
        unset( $curVersionObject );

        // remove old node assignments
        foreach( $newObjAssignments as $assignment )
        {
            $assignment->purge();
        }

        // and create a new one
        $nodeAssignment = eZNodeAssignment::create( array(
                                                         'contentobject_id' => $newObject->attribute( 'id' ),
                                                         'contentobject_version' => $curVersion,
                                                         'parent_node' => $newParentNodeID,
                                                         'is_main' => 1
                                                         ) );
        $nodeAssignment->store();

        // publish the newly created object
        eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newObject->attribute( 'id' ),
                                                                  'version'   => $curVersion ) );
        // Update "is_invisible" attribute for the newly created node.
        $newNode = $newObject->attribute( 'main_node' );
        eZContentObjectTreeNode::updateNodeVisibility( $newNode, $newParentNode );

        $db->commit();
        return $newObject;
    }

    public static function compareObjectAttributeIds( $object )
    {
        $dataMap = $object->dataMap();
        $attrIdMap = array();

        foreach( $dataMap as $attr )
        {
            $attrIdMap[$attr->contentClassAttributeIdentifier()] = $attr->attribute( 'id' );
        }
        return $attrIdMap;
    }

    /**
     * Regression test for issue #16299, where a fatal error would occur if an unknown sort_order parameter is given
     * @see http://issues.ez.no/16299
     */
    public function testIssue16299()
    {
        // Create a folder object that will be used as a target for relations
        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__;
        $folder->short_description = __METHOD__;
        $folder->publish();

        // Create two links & two articles that will be related to the folder created above
        foreach( array( 1, 2 ) as $index )
        {
            $varName = "link{$index}";
            $$varName = new ezpObject( "link", 2 );
            $$varName->name = "Link " . __FUNCTION__ . " #{$index}";
            $$varName->location = 'http://ez.no/';
            $$varName->addContentObjectRelation( $folder->id );
            $$varName->publish();
        }
        foreach( array( 1, 2 ) as $index )
        {
            $varName = "article{$index}";
            $$varName = new ezpObject( "article", 2 );
            $$varName->title = "Article " .__FUNCTION__ . " #{$index}";
            $$varName->intro = __METHOD__;
            $$varName->addContentObjectRelation( $folder->id );
            $$varName->publish();
        }

        // Call the fetch reverse_related_objects fetch function
        // The array( 'foo', false ) sort_by item should be ignored
        $result = eZFunctionHandler::execute(
            'content', 'reverse_related_objects',
            array(
                'object_id' => $folder->id,
                'sort_by' => array( array( 'name', true ), array( 'foo', false ) ) ) );

        self::assertType( 'array', $result );
        self::assertEquals( 4, count( $result ), "Expecting 4 objects fetched" );

        // Sort by name:
        self::assertEquals( $article1->id, $result[0]->attribute( 'id' ) );
        self::assertEquals( $article2->id, $result[1]->attribute( 'id' ) );
        self::assertEquals( $link1->id,    $result[2]->attribute( 'id' ) );
        self::assertEquals( $link2->id,    $result[3]->attribute( 'id' ) );

        // Call the fetch reverse_related_objects fetch function
        // The array( 'foo', false ) sort_by item should be ignored, and random sorting should occur
        // This validates the behaviour with only one parameter, as the code's different
        $result = eZFunctionHandler::execute(
            'content', 'reverse_related_objects',
            array(
                'object_id' => $folder->id,
                'sort_by' => array( 'foo', false ) ) );
        self::assertType( 'array', $result );
        self::assertEquals( 4, count( $result ), "Expecting 4 objects fetched" );

        // Call the fetch reverse_related_objects fetch function
        // The array( 'foo', false ) sort_by item should be ignored, and random sorting should occur
        // This validates the behaviour with only one (correct) parameter
        $result = eZFunctionHandler::execute(
        'content', 'reverse_related_objects',
        array(
            'object_id' => $folder->id,
            'sort_by' => array( 'class_identifier', true ) ) );
        self::assertType( 'array', $result );
        self::assertEquals( 4, count( $result ), "Expecting 4 objects fetched" );
    }

    /**
     * Test scenario for issue #15985 : Debug notices when translating an 
     * object to a new language
     *
     * @group issue_15985
     */
    public function testIssue15985()
    {
        $folder = new ezpObject( "folder", 2, 14, 1, 'eng-GB' );
        $folder->name = __FUNCTION__;
        $folder->object->setAttribute( 'always_available', 1 );
        $folder->publish();

        $nameNorNO = $folder->object->versionLanguageName( 1, 'nor-NO' );
        self::assertType( 'string', $nameNorNO, 'Expecting to get a string (not false)' );
        self::assertEquals( __FUNCTION__, $nameNorNO );
    }
}

?>
