<?php
/**
 * File containing the eZMediaTypeRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZMediaTypeRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZMediaType Regression Tests" );
    }

    /**
     * Regression test for issue #14983
     *
     * @link http://issues.ez.no/14983
     */
    public function testIssue14983()
    {
        $className = 'eZMediaType test class';
        $classIdentifier = 'ezmediatype_test_class';
        $attributeName = 'Media';
        $attributeIdentifier = 'media';
        $attributeType = 'ezmedia';
        $filePath = 'tests/tests/kernel/datatypes/ezmedia/ezmediatype_regression_issue14983.flv';

        $class = new ezpClass( $className, $classIdentifier, $className );
        $classAttribute = $class->add( $attributeName, $attributeIdentifier, $attributeType );
        $class->store();

        $object = new ezpObject( $classIdentifier, 2 );
        $object->name = __FUNCTION__;
        {
            $dataMap = $object->object->dataMap();
            $fileAttribute = $dataMap[$attributeIdentifier];
            {
                $dataType = new eZMediaType();
                $dataType->fromString( $fileAttribute, $filePath );
            }
            $fileAttribute->store();
        }
        $object->publish();
        $object->refresh();

        $contentObjectAttributeID = $fileAttribute->attribute( "id" );
        $files = eZMedia::fetch( $contentObjectAttributeID, null );
        foreach ( $files as $file )
        {
            // Read stored path, move to trash, and read stored path again
            $this->assertNotEquals( $file, null );

            $storedFileInfo = $file->storedFileInfo();
            $storedFilePath = $storedFileInfo['filepath'];
            $version = $file->attribute( 'version' );

            $object->object->removeThis();
            $object->refresh();
            $file = eZMedia::fetch( $contentObjectAttributeID, $version );

            $storedFileInfo = $file->storedFileInfo();
            $storedFilePathAfterTrash = $storedFileInfo['filepath'];

            $this->assertNotEquals( $storedFilePath, $storedFilePathAfterTrash, 'The stored file should be renamed when trashed' );
        }
    }

    /**
     * Regression test for issue 16400
     * @link http://issues.ez.no/16400
     * @return unknown_type
     */
    public function testIssue16400()
    {
        $className = 'Media test class';
        $classIdentifier = 'media_test_class';
        $filePath = 'tests/tests/kernel/datatypes/ezmedia/ezmediatype_regression_issue16400.flv';
        eZFile::create( $filePath );
        $attributeName = 'Media';
        $attributeIdentifier = 'media';
        $attributeType = 'ezmedia';
        //1. test method fetchByContentObjectID

        $class = new ezpClass( $className, $classIdentifier, $className );
        $class->add( $attributeName, $attributeIdentifier, $attributeType );
        $attribute = $class->class->fetchAttributeByIdentifier( $attributeIdentifier );

        $attribute->setAttribute( 'can_translate', 0 );
        $class->store();

        $object = new ezpObject( $classIdentifier, 2 );
        $dataMap = $object->object->dataMap();
        $fileAttribute = $dataMap[$attributeIdentifier];
        $dataType = new eZMediaType();
        $dataType->fromString( $fileAttribute, $filePath );

        $fileAttribute->store();
        $object->publish();
        $object->refresh();

        //verify fetchByContentObjectID
        $mediaObject = eZMedia::fetch( $fileAttribute->attribute( 'id' ), 1 );
        $medias = eZMedia::fetchByContentObjectID( $object->object->attribute( 'id' ) );
        $this->assertEquals( $mediaObject->attribute( 'filename' ), $medias[0]->attribute( 'filename' ) );
        $medias = eZMedia::fetchByContentObjectID( $object->object->attribute( 'id' ),
                                                    $fileAttribute->attribute( 'language_code' ) );
        $this->assertEquals( $mediaObject->attribute( 'filename' ), $medias[0]->attribute( 'filename' ) );

        //2. test issue
        // create translation
        $contentObject = $object->object;
        $storedFileName = $mediaObject->attribute( 'filename' );
        $version = $contentObject->createNewVersionIn( 'nor-NO',
                                                        $fileAttribute->attribute( 'language_code' )  );
        $version->setAttribute( 'status', eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
        $version->store();
        $version->removeThis();
        $sys = eZSys::instance();
        $dir = $sys->storageDirectory();
        //verify the file is deleted
        $storedFilePath = $dir . '/original/video/' . $storedFileName;
        $file = eZClusterFileHandler::instance( $storedFilePath );
        $this->assertTrue( $file->exists( $storedFilePath ) );
        if ( $file->exists( $storedFilePath ) )
            unlink( $storedFilePath );
    }
}

?>
