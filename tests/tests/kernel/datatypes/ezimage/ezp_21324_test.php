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

    /**
     * Tests that:
     * - given than an original image object is created with an image file,
     * - given that the original image object is edited, and the image is not changed
     * - given that a copy of the image object is done
     * - removing version one of the copy of the image object won't delete the image files used by
     *   the original image object
     */
    public function testRemoveCopyVersion()
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
     * Tests that:
     * - given than an original image object is created with an image file,
     * - given that the original image object is edited, and the image is not changed
     * - given that a copy of the image object is done
     * - removing the copy of the image object won't delete the image files used by the original image object
     */
    public function testRemoveCopy()
    {
        $originalImageObject = $this->createImage( "Original image" );
        $originalImageObject = $this->createNewVersion( $originalImageObject );
        $originalImageDataMap = $originalImageObject->fetchDataMap();
        $originalImageAttribute = $originalImageDataMap['image'];
        /** @var eZImageAliasHandler $originalImageAliasHandler */
        $originalImageAliasHandler = $originalImageAttribute->attribute( 'content' );

        $copyObject = $this->createCopy( $originalImageObject );
        $copyObjectDataMap = $copyObject->fetchDataMap();
        $copyImageAttribute = $copyObjectDataMap['image'];
        /** @var eZImageAliasHandler $originalImageAliasHandler */
        $copyImageAliasHandler = $copyImageAttribute->attribute( 'content' );

        foreach ( $originalImageAliasHandler->aliasList() as $alias )
        {
            self::assertFileExists( $alias['full_path'] );
        }

        $copyAliasList = $copyImageAliasHandler->aliasList();
        foreach ( $copyAliasList as $alias )
        {
            self::assertInstanceOf(
                'eZImageFile',
                eZImageFile::fetchByFilepath( $copyImageAttribute->attribute( 'id' ), $alias['full_path'] )
            );
        }
        $this->removeObject( $copyObject );

        foreach ( $originalImageAliasHandler->aliasList() as $alias )
        {
            self::assertFileExists( $alias['full_path'] );
        }
        // we want to be sure the ezimagefile entries are gone as well
        foreach ( $copyAliasList as $alias )
        {
            // @todo Failure: the object ain't referenced by the attribute, and can't be deleted. Add missing ref.
            self::assertNull(
                eZImageFile::fetchByFilepath( $copyImageAttribute->attribute( 'id' ), $alias['full_path'] )
            );
        }
    }

    /**
     * Tests that:
     * - given than an image object is created with an image file,
     * - given that the original image object is edited, and the image is changed
     * - removing the copy of the image object deletes image files used by both versions
     */
    public function testRemoveObject()
    {
        $originalImageObject = $this->createImage( "__METHOD__" );
        $originalImageDataMap = $originalImageObject->dataMap();
        $imageAliases = $originalImageDataMap['image']->attribute( 'content' )->aliasList();

        $originalImageObject = $this->createNewVersionWithImage( $originalImageObject );
        $originalImageDataMap = $originalImageObject->fetchDataMap();
        $imageAliases += $originalImageDataMap['image']->attribute( 'content' )->aliasList();

        foreach ( $imageAliases as $alias )
        {
            self::assertFileExists( $alias['full_path'] );
        }

        $this->removeObject( $originalImageObject );

        foreach ( $imageAliases as $alias )
        {
            self::assertFileNotExists( $alias['full_path'] );
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
        $version = $object->createNewVersion( false, true, false, false, eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
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

    protected function createNewVersionWithImage( eZContentObject $object )
    {
        $version = $object->createNewVersion( false, true, false, false, eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
        $contentObjectAttributes = $version->contentObjectAttributes();
        foreach ( $contentObjectAttributes as $contentObjectAttribute )
        {
            if ( $contentObjectAttribute->attribute( 'identifier' ) != 'ezimage' )
                continue;
            $contentObjectAttributes->fromString( self::IMAGE_FILE_PATH );
            $contentObjectAttributes->store();
            break;
        }

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

    public function removeObject( eZContentObject $contentObject )
    {
        $contentObject->purge();
    }
}
