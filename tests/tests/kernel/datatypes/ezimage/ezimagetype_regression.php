<?php
/**
 * File containing the eZImageTypeRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZImageTypeRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    const IMAGE_FILE_PATH = "tests/tests/kernel/datatypes/ezimage/ezimagetype_regression_issue14983.png";

    /**
     * @var ezpClass
     */
    private $imageClass;

    /**
     * @var ezpObject
     */
    private $imageObject;

    /**
     * Image content object attribute
     * @var eZContentObjectAttribute
     */
    private $fileAttribute;

    public function __construct( $name = null, array $data = array(), $dataName = '' )
    {
        parent::__construct( $name, $data, $dataName );
        $this->setName( "eZImageType Regression Tests" );
    }

    public function setUp()
    {
        parent::setUp();
        $classIdentifier = "ezimagetype_test_class";

        $this->imageClass = new ezpClass( "eZImageType test class", $classIdentifier, "eZImageType test class" );
        $this->imageClass->add( "Image", "image", "ezimage" );
        $this->imageClass->store();

        $this->imageObject = new ezpObject( $classIdentifier, 2 );
        $this->imageObject->name = __METHOD__;
        $dataMap = $this->imageObject->object->dataMap();
        $this->fileAttribute = $dataMap["image"];
        $dataType = new eZImageType();
        $dataType->fromString( $this->fileAttribute, self::IMAGE_FILE_PATH );
        $this->fileAttribute->store();

        $this->imageObject->publish();
        $this->imageObject->refresh();
    }

    public function tearDown()
    {
        $this->fileAttribute = null;
        $this->imageObject->remove();
        $this->imageObject = null;
        eZContentClassOperations::remove( $this->imageClass->id );
        $this->imageClass = null;
        parent::tearDown();
    }

    /**
     * Regression test for issue #14983
     * Linked to #17781
     *
     * @link http://issues.ez.no/14983
     * @link http://issues.ez.no/17781
     * @group issue14983
     * @group issue17781
     */
    public function testIssue14983()
    {
        $files = eZImageFile::fetchForContentObjectAttribute( $this->fileAttribute->attribute( "id" ), true );
        self::assertType( PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $files );
        $file = $files[0];
        unset( $files );

        // Read stored path, move to trash, and read stored path again
        self::assertType( 'eZImageFile', $file );

        $oldFile = $file;
        $this->imageObject->object->removeThis();
        $this->imageObject->refresh();
        $files = eZImageFile::fetchForContentObjectAttribute( $this->fileAttribute->attribute( "id" ), true );
        self::assertType( PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $files );
        $file = $files[0];
        unset( $files );

        self::assertType( 'eZImageFile', $file );
        self::assertTrue( strpos( $file->attribute( "filepath" ), '/trashed') !== false, "The stored file should be renamed when trashed" );
    }

    /**
     * Regression test for issue #17781
     * @link http://issues.ez.no/17781
     * @group issue17781
     */
    public function testRestoreImageTrashed()
    {
        $this->imageObject->refresh();
        $dataMap = $this->imageObject->dataMap();
        self::assertArrayHasKey( "image", $dataMap );
        $untrashedBasename = $dataMap["image"]->content()->directoryPath();
        unset( $dataMap );

        /*
         * 1. Move the object to trash with eZContentObject::removeThis()
         * 2. Refresh (clear in-memory cache...)
         * 3. Artificially restore the object attributes
         * 4. Refresh
         */
        $this->imageObject->removeThis(); // Now image dir is different (see self::testIssue14983())
        $this->imageObject->refresh();
        $this->imageObject->restoreObjectAttributes();
        $this->imageObject->refresh();
        $dataMap = $this->imageObject->dataMap();
        self::assertArrayHasKey( "image", $dataMap );
        self::assertSame( $untrashedBasename, $dataMap["image"]->content()->directoryPath() );
    }
}
?>
