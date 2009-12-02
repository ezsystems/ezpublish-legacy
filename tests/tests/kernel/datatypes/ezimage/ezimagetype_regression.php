<?php
/**
 * File containing the eZImageTypeRegression class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZImageTypeRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZImageType Regression Tests" );
    }

    /**
     * Regression test for issue #14983
     *
     * @link http://issues.ez.no/14983
     **/
    public function testIssue14983()
    {
        $className = 'eZImageType test class';
        $classIdentifier = 'ezimagetype_test_class';
        $attributeName = 'Image';
        $attributeIdentifier = 'image';
        $attributeType = 'ezimage';
        $filePath = 'tests/tests/kernel/datatypes/ezimage/ezimagetype_regression_issue14983.png';

        $class = new ezpClass( $className, $classIdentifier, $className );
        $classAttribute = $class->add( $attributeName, $attributeIdentifier, $attributeType );
        $class->store();

        $object = new ezpObject( $classIdentifier, 2 );
        $object->name = __FUNCTION__;
        {
            $dataMap = $object->object->dataMap();
            $fileAttribute = $dataMap[$attributeIdentifier];
            {
                $dataType = new eZImageType();
                $dataType->fromString( $fileAttribute, $filePath );
            }
            $fileAttribute->store();
        }
        $object->publish();
        $object->refresh();

        $contentObjectAttributeID = $fileAttribute->attribute( "id" );
        $files = eZImageFile::fetchForContentObjectAttribute( $contentObjectAttributeID );
        $file = $files[0];

        // Read stored path, move to trash, and read stored path again
        $this->assertNotEquals( $file, null );

        $oldFile = $file;
        $object->object->removeThis();
        $object->refresh();
        $files = eZImageFile::fetchForContentObjectAttribute( $contentObjectAttributeID );
        $file = $files[0];

        $this->assertNotEquals( $oldFile, $file, 'The stored file should be renamed when trashed' );
    }
}

?>
