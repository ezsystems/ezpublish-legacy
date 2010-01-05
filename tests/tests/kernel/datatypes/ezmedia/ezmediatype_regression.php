<?php
/**
 * File containing the eZMediaTypeRegression class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
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
     **/
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
}

?>
