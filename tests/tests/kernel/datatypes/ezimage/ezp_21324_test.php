<?php
/**
 * File containing the eZImageAliasHandlerRegression class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
        $originalImageAttributeId = $originalImageAttribute->attribute( 'id' );
        $originalImageAliasHandler = $originalImageAttribute->attribute( 'content' );
        $originalAliasList = $originalImageAliasHandler->aliasList();

        // generate medium alias on v2
        $originalImageV1MediumAliasFile = $originalImageAliasHandler->imageAlias( 'medium');

        foreach ( $originalAliasList as $alias )
        {
            self::assertImageFileExists( $originalImageAttributeId, $alias['full_path'] );
        }

        $copyImage = $this->createCopy( $originalImageObject );

        $copyImageV1DataMap = $copyImage->version( 1 )->dataMap();
        $copyImageV1Attribute = $copyImageV1DataMap['image'];
        $copyImageV1AttributeId = $copyImageV1Attribute->attribute( 'id' );
        $copyImageV1AliasHandler = $copyImageV1Attribute->attribute( 'content' );

        $copyImageV1AliasList = $copyImageV1AliasHandler->aliasList();
        foreach ( $copyImageV1AliasList as $alias )
        {
            self::assertImageFileExists( $copyImageV1AttributeId, $alias['full_path'] );
        }

        $copyObjectV2DataMap = $copyImage->fetchDataMap();
        $copyImageV2Attribute = $copyObjectV2DataMap['image'];
        $copyImageV2AttributeId = $copyImageV2Attribute->attribute( 'id' );
        $copyImageV2AliasList = $copyImageV2Attribute->attribute( 'content' )->aliasList();
        foreach ( $copyImageV2AliasList as $alias )
        {
            self::assertImageFileExists( $copyImageV2AttributeId, $alias['full_path'] );
        }

        $this->removeObject( $copyImage );

        foreach ( $originalAliasList as $alias )
        {
            self::assertImageFileExists( $originalImageAttributeId, $alias['full_path'] );
        }

        // fails
        foreach ( $copyImageV1AliasList as $alias )
        {
            self::assertImageFileNotExists( $copyImageV2AttributeId, $alias['full_path'] );
        }

        foreach ( $copyImageV2AliasList as $alias )
        {
            self::assertImageFileNotExists( $copyImageV2AttributeId, $alias['full_path'] );
        }

        // Medium alias from original image should not be referenced since v1 was published
        self::assertImageFileNotExists( $copyImageV2AttributeId, $originalImageV1MediumAliasFile['full_path'] );
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

    /**
     * Checks that there is an image file for $contentObjectAttributeId & $file, and that $file exists
     */
    protected static function assertImageFileExists( $contentObjectAttributeId, $file )
    {
        self::assertInstanceOf(
            'eZImageFile',
            self::fetchImageFile( $contentObjectAttributeId, $file )
        );
    }

    /**
     * Checks that there is NOT an eZImageFile for $contentObjectAttributeId & $file, and that $file does NOT exist
     */
    protected static function assertImageFileNotExists( $contentObjectAttributeId, $file )
    {
        self::assertNull( self::fetchImageFile( $contentObjectAttributeId, $file ) );
    }

    /**
     * @param int $contentObjectId
     * @param string $file
     * @return eZImageFile|null
     */
    protected static function fetchImageFile( $contentObjectAttributeId, $file )
    {
        return eZImageFile::fetchObject(
            eZImageFile::definition(),
            null,
            array(
                'contentobject_attribute_id' => $contentObjectAttributeId,
                'filepath' => $file
            )
        );
    }
}
