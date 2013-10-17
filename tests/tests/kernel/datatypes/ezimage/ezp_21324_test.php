<?php
/**
 * File containing the eZImageAliasHandlerRegression class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZImageEZP21324Test extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;
    const CLASS_IDENTIFIER = 'ezimage_ezp21324_test_class';
    const IMAGE_FILE_PATH = "tests/tests/kernel/datatypes/ezimage/ezimagetype_regression_issue14983.png";

    public function setUp()
    {
        parent::setUp();
        $imageClass = new ezpClass( "eZImageEZP21324 test class", self::CLASS_IDENTIFIER, "<name>" );
        $imageClass->add( "Name", "name", "ezstring" );
        $imageClass->add( "Image", "image", "ezimage" );
        $imageClass->store();
    }

    public function testIssue()
    {
        $originalImageObject = $this->createImage( "Original image" );
        $originalImageObject = $this->createNewVersion( $originalImageObject );
        $originalImageDataMap = $originalImageObject->fetchDataMap();
        /** @var eZImageAliasHandler $originalImageAliasHandler */
        $originalImageAliasHandler = $originalImageDataMap['image']->attribute( 'content' );

        $copyObject = $this->createCopy( $originalImageObject );

        foreach ( $originalImageAliasHandler->aliasList() as $alias )
        {
            self::assertFileExists( $alias['full_path'] );
        }

        $this->deleteVersion( $copyObject->version( 1 ) );

        foreach ( $originalImageAliasHandler->aliasList() as $alias )
        {
            self::assertFileExists( $alias['full_path'] );
        }
    }

    /**
     * @param string $name
     * @return eZContentObject
     */
    protected function createImage( $name )
    {
        $imageObject = new ezpObject( self::CLASS_IDENTIFIER, 2 );
        $imageObject->name = $name;
        $imageObject->image = self::IMAGE_FILE_PATH;
        $imageObject->publish();

        return $this->forceFetchContentObject( $imageObject->attribute( 'id' ) );
    }

    protected function createNewVersion( eZContentObject $object )
    {
        $version = $object->createNewVersion( false, true, eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
        $operationResult = eZOperationHandler::execute(
            'content',
            'publish',
            array(
                'object_id' => $object->attribute( 'id' ),
                'version' => $version->attribute( 'version' )
            )
        );

        self::assertEquals( 1, $operationResult['status'] );

        return $this->forceFetchContentObject( $object->attribute( 'id' ) );
    }

    /**
     * Clears cache for an object and fetches it
     *
     * @param eZContentObject $object
     *
     * @return eZContentObject
     */
    protected function forceFetchContentObject( $contentObjectId )
    {
        eZContentObject::clearCache( $contentObjectId );
        return eZContentObject::fetch( $contentObjectId );
    }

    /**
     * Creates a copy of $sourceObject
     * @param eZContentObject $sourceObject
     * @return eZContentObject
     */
    protected function createCopy( eZContentObject $sourceObject )
    {
        $db = eZDB::instance();
        $db->begin();
        $newObject = $sourceObject->copy();
        $newObject->setAttribute( 'section_id', 0 );
        $newObject->store();

        $curVersion        = $newObject->attribute( 'current_version' );
        $curVersionObject  = $newObject->attribute( 'current' );
        $newObjAssignments = $curVersionObject->attribute( 'node_assignments' );

        // remove old node assignments
        foreach ( $newObjAssignments as $assignment )
        {
            $assignment->purge();
        }

        // and create a new one
        $nodeAssignment = eZNodeAssignment::create(
            array(
                'contentobject_id' => $newObject->attribute( 'id' ),
                'contentobject_version' => $curVersion,
                'parent_node' => 2,
                'is_main' => 1
            )
        );
        $nodeAssignment->store();

        // publish the newly created object
        eZOperationHandler::execute(
            'content',
            'publish',
            array(
                'object_id' => $newObject->attribute( 'id' ),
                'version'   => $curVersion
            )
        );

        $db->commit();

        return $this->forceFetchContentObject( $newObject->attribute( 'id' ) );
    }

    protected function deleteVersion( eZContentObjectVersion $version )
    {
        $version->removeThis();
    }
}
