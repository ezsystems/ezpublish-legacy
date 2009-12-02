<?php
/**
 * File containing the eZBinaryFileTypeRegression class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZBinaryFileTypeRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZBinaryFileType Regression Tests" );
    }

    /**
     * Regression test for issue #14983
     *
     * @link http://issues.ez.no/14983
     **/
    public function testIssue14983()
    {
        $className = 'eZBinaryFileType test class';
        $classIdentifier = 'ezbinaryfiletype_test_class';
        $attributeName = 'File';
        $attributeIdentifier = 'file';
        $attributeType = 'ezbinaryfile';
        $filePath = 'tests/tests/kernel/datatypes/ezbinaryfile/ezbinaryfiletype_regression_issue14983.txt';

        $class = new ezpClass( $className, $classIdentifier, $className );
        $classAttribute = $class->add( $attributeName, $attributeIdentifier, $attributeType );
        $class->store();

        $object = new ezpObject( $classIdentifier, 2 );
        $object->name = __FUNCTION__;
        {
            $dataMap = $object->object->dataMap();
            $fileAttribute = $dataMap[$attributeIdentifier];
            {
                $dataType = new eZBinaryFileType();
                $dataType->fromString( $fileAttribute, $filePath );
            }
            $fileAttribute->store();
        }
        $object->publish();
        $object->refresh();

        $contentObjectAttributeID = $fileAttribute->attribute( "id" );
        $files = eZBinaryFile::fetch( $contentObjectAttributeID );
        foreach ( $files as $file )
        {
            // Read stored path, move to trash, and read stored path again
            $this->assertNotEquals( $file, null );

            $storedFileInfo = $file->storedFileInfo();
            $storedFilePath = $storedFileInfo['filepath'];
            $version = $file->attribute( 'version' );

            $object->object->removeThis();
            $object->refresh();
            $file = eZBinaryFile::fetch( $contentObjectAttributeID, $version );

            $storedFileInfo = $file->storedFileInfo();
            $storedFilePathAfterTrash = $storedFileInfo['filepath'];

            $this->assertNotEquals( $storedFilePath, $storedFilePathAfterTrash, 'The stored file should be renamed when trashed' );
        }
    }
}

?>
