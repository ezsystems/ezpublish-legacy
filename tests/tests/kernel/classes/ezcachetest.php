<?php
/**
 * File containing the eZCacheTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZCacheTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;
    const CLASS_IDENTIFIER = 'ezcache_test_class';
    const IMAGE_FILE_PATH = "tests/tests/kernel/datatypes/ezimage/ezimagetype_regression_issue14983.png";

    public function setUp()
    {
        parent::setUp();
        $imageClass = new ezpClass( self::CLASS_IDENTIFIER, self::CLASS_IDENTIFIER, "<name>" );
        $imageClass->add( "Name", "name", "ezstring" );
        $imageClass->add( "Image", "image", "ezimage" );
        $imageClass->store();
    }

    public function testPurgeImageAliasForObject()
    {
        $imageObject = $this->createImage( "Original image" );

        // generate a couple aliases
        $dataMap = $imageObject->dataMap();
        $aliasHandler = $dataMap['image']->attribute( 'content' );
        foreach ( array( 'small', 'medium', 'large' ) as $aliasName )
        {
            $alias = $aliasHandler->attribute( $aliasName );
            $aliasFiles[] = $alias['url'];
        }

        // create a new version
        $imageObject = $this->createNewVersionWithImage( $imageObject );

        // generate a couple aliases
        $dataMap = $imageObject->dataMap();
        $imageAttribute = $dataMap['image'];
        $imageAttributeId = $imageAttribute->attribute( 'id' );
        $aliasHandler = $imageAttribute->attribute( 'content' );
        $aliasFiles = array();
        foreach ( array( 'small', 'medium', 'large' ) as $aliasName )
        {
            $alias = $aliasHandler->attribute( $aliasName );
            $aliasFiles[] = $alias['url'];
        }

        // we will check that the original alias wasn't removed
        $originalAlias = $aliasHandler->attribute( 'original' );
        $originalAliasFile = $originalAlias['url'];
        unset( $originalAlias );

        $aliasFiles = array_unique( $aliasFiles );
        foreach ( $aliasFiles as $aliasFile )
        {
            self::assertImageFileExists( $imageAttributeId, $aliasFile );
        }
        self::assertImageFileExists( $imageAttributeId, $originalAliasFile );

        eZCache::purgeImageAlias( array( 'reporter' => function(){} ) );

        foreach ( $aliasFiles as $aliasFile )
        {
            self::assertImageFileNotExists( $imageAttributeId, $aliasFile );
        }
        self::assertImageFileExists( $imageAttributeId, $originalAliasFile );
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

    protected function createNewVersionWithImage( eZContentObject $object )
    {
        $version = $object->createNewVersion( false, true, false, false, eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
        $contentObjectAttributes = $version->contentObjectAttributes();
        foreach ( $contentObjectAttributes as $contentObjectAttribute )
        {
            if ( $contentObjectAttribute->attribute( 'contentclass_attribute_identifier' ) != 'image' )
                continue;
            $contentObjectAttribute->fromString( self::IMAGE_FILE_PATH );
            $contentObjectAttribute->store();
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
     * Checks that there is an image file for $contentObjectAttributeId & $file, and that $file exists
     */
    protected static function assertImageFileExists( $contentObjectAttributeId, $file )
    {
        self::assertInstanceOf(
            'eZImageFile',
            self::fetchImageFile( $contentObjectAttributeId, $file )
        );
        self::assertFileExists( $file );
    }

    /**
     * Checks that there is NOT an eZImageFile for $contentObjectAttributeId & $file, and that $file does NOT exist
     */
    protected static function assertImageFileNotExists( $contentObjectAttributeId, $file )
    {
        self::assertNull( self::fetchImageFile( $contentObjectAttributeId, $file ) );
        self::assertFileNotExists( $file );
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
