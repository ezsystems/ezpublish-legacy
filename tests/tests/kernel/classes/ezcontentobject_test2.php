<?php
/**
 * File containing the eZContentObjectTest2 class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZContentObjectTest2 extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;
    protected $article;

    public function setUp()
    {
        parent::setUp();

        $this->article = new ezpObject( "article", 2, eZUser::fetchByName( 'anonymous' )->attribute( 'contentobject_id' ) );
        $this->article->title = "Article for " . __CLASS__;
        $this->article->publish();
        $this->article->addTranslation( "nor-NO", array( "title" => "Norsk title of article for " . __CLASS__ ) );
    }

    public function tearDown()
    {
        $this->article->remove();
        parent::tearDown();
    }

    /**
     * Test for eZContentObject::versions(), fetching all of them
     */
    public function testFetchAllVersionsAsObject()
    {
        $versions = $this->article->object->versions();
        $this->assertEquals( 2, count( $versions ) );
        $this->assertType( 'eZContentObjectVersion' , $versions[0] );
        $this->assertType( 'eZContentObjectVersion' , $versions[1] );
    }

    /**
     * Test for eZContentObject::versions(), fetching all of them, returns rows
     */
    public function testFetchAllVersionsAsRows()
    {
        $versions = $this->article->object->versions( false );
        $this->assertEquals( 2, count( $versions ) );
        $this->assertType( 'array' , $versions[0] );
        $this->assertType( 'array' , $versions[1] );
    }

    /**
     * Test for eZContentObject::versions(), fetching versions with 'published' status
     */
    public function testFetchVersionsWithPublishedStatus()
    {
        $versions = $this->article->object->versions( true, array( 'conditions' => array( 'status' => eZContentObjectVersion::STATUS_PUBLISHED ) ));
        $this->assertEquals( 1, count( $versions ) );
        $this->assertEquals( eZContentObjectVersion::STATUS_PUBLISHED, $versions[0]->Status );
        $this->assertEquals( 2, $versions[0]->Version );
    }

    /**
     * Test for eZContentObject::versions(), fetching versions with creator (matching)
     */
    public function testFetchVersionsWithMatchingCreator()
    {
        $creatorID = eZUser::fetchByName( 'anonymous' )->attribute( 'contentobject_id' );
        $versions = $this->article->object->versions( true, array( 'conditions' => array( 'creator_id' => $creatorID ) ) );
        $this->assertEquals( 2, count( $versions ) );
        $this->assertEquals( $creatorID, $versions[0]->CreatorID );
        $this->assertEquals( $creatorID, $versions[1]->CreatorID );
    }

    /**
     * Test for eZContentObject::versions(), fetching versions with creator (not matching)
     */
    public function testFetchVersionsWithNonMatchingCreator()
    {
        $creatorID = eZUser::fetchByName( 'admin' )->attribute( 'contentobject_id' );
        $versions = $this->article->object->versions( true, array( 'conditions' => array( 'creator_id' => $creatorID ) ) );
        $this->assertTrue( empty( $versions ) );
    }

    /**
     * Test for eZContentObject::versions(), fetching versions with 'archived' status
     */
    public function testFetchVersionsWithArchivedStatus()
    {
        $versions = $this->article->object->versions( true, array( 'conditions' => array( 'status' => eZContentObjectVersion::STATUS_ARCHIVED ) ));
        $this->assertEquals( 1, count( $versions ) );
        $this->assertEquals( eZContentObjectVersion::STATUS_ARCHIVED, $versions[0]->Status );
        $this->assertEquals( 1, $versions[0]->Version );
    }

    /**
     * Test for eZContentObject::fetchList(), returning objects
     */
    public function testFetchListAsObjects()
    {
        $eZContentObjectDefinition = eZContentObject::definition();
        $objects = eZContentObject::fetchList(
            true,
            array(
                $eZContentObjectDefinition['name'] . ".id" => $this->article->id
            )
        );
        $this->assertSame( 1, count( $objects ) );
        $this->assertType( 'eZContentObject' , $objects[0] );
        $this->assertEquals( $this->article->id, $objects[0]->attribute( 'id' )  );
    }

    /**
     * Test for eZContentObject::fetchList(), returning rows
     */
    public function testFetchListAsRows()
    {
        $eZContentObjectDefinition = eZContentObject::definition();
        $objects = eZContentObject::fetchList(
            false,
            array(
                $eZContentObjectDefinition['name'] . ".id" => $this->article->id
            )
        );
        $this->assertSame( 1, count( $objects ) );
        $this->assertType( 'array' , $objects[0] );
        $this->assertEquals( $this->article->id, $objects[0]['id'] );
    }

    /**
     * Test for eZContentObject::fetchList(), returning objects with published status
     */
    public function testFetchListWithPublishedStatus()
    {
        $eZContentObjectDefinition = eZContentObject::definition();
        $objects = eZContentObject::fetchList(
            true,
            array(
                $eZContentObjectDefinition['name'] . ".id" => $this->article->id,
                'status' => eZContentObject::STATUS_PUBLISHED
            )
        );
        $this->assertSame( 1, count( $objects ) );
        $this->assertType( 'eZContentObject' , $objects[0] );
        $this->assertEquals( $this->article->id, $objects[0]->attribute( 'id' )  );
    }

    /**
     * Test for eZContentObject::fetchList(), returning objects with archived status

     */
    public function testFetchListWithArchivedStatus()
    {
        $this->article->setAttribute( 'status', eZContentObject::STATUS_ARCHIVED );
        $this->article->store();

        $eZContentObjectDefinition = eZContentObject::definition();
        $objects = eZContentObject::fetchList(
            true,
            array(
                $eZContentObjectDefinition['name'] . ".id" => $this->article->id,
                'status' => eZContentObject::STATUS_ARCHIVED
            )
        );
        $this->assertSame( 1, count( $objects ) );
    }

    /**
     * Test for eZContentObject::fetchListCount(), using content object ID
     */
    public function testFetchListCountOnObjectID()
    {
        $eZContentObjectDefinition = eZContentObject::definition();
        $count = eZContentObject::fetchListCount(
            array(
                $eZContentObjectDefinition['name'] . ".id" => $this->article->id
            )
        );
        $this->assertEquals( 1, $count );
    }

    /**
     * Test for eZContentObject::fetchListCount(), using content object ID + published status
     */
    public function testFetchListCountOnObjectIDAndPublishedStatus()
    {
        $eZContentObjectDefinition = eZContentObject::definition();
        $count = eZContentObject::fetchListCount(
            array(
                $eZContentObjectDefinition['name'] . ".id" => $this->article->id,
                'status' => eZContentObject::STATUS_PUBLISHED
            )
        );
        $this->assertEquals( 1, $count );
    }

    /**
     * Test for eZContentObject::fetchListCount(), using content object ID + archived status
     */
    public function testFetchListCountOnObjectIDAndArchivedStatus()
    {
        $this->article->setAttribute( 'status', eZContentObject::STATUS_ARCHIVED );
        $this->article->store();

        $eZContentObjectDefinition = eZContentObject::definition();
        $count = eZContentObject::fetchListCount(
            array(
                $eZContentObjectDefinition['name'] . ".id" => $this->article->id,
                'status' => eZContentObject::STATUS_ARCHIVED
            )
        );
        $this->assertEquals( 1, $count );
    }
}

?>
