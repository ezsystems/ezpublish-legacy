<?php
/**
 * File containing the eZImageFileRegression class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZImageFileRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( 'eZImageFile Regression Tests' );
    }

    /**
     * Regression test for issue #16078
     *
     * @link http://issues.ez.no/16078
     **/
    public function testIssue16078()
    {
        $classID = 5; // image class, can remain hardcoded, I guess
        $baseImagePath = dirname( __FILE__ ) . '/ezimagefile_regression_issue16078.png';
        $parts = pathinfo( $baseImagePath );
        $imagePattern = $parts['dirname'] . DIRECTORY_SEPARATOR . $parts['filename'] . '_%s_%d.' . $parts['extension'];
        $toDelete = array();

        // Create version 1
        $imagePath = sprintf( $imagePattern, md5(1), 1 );
        copy( $baseImagePath, $imagePath );
        $toDelete[] = $imagePath;
        $image = new ezpObject( 'image', 43 );
        $image->name = __FUNCTION__;
        $image->image = $imagePath;
        $image->publish();
        $image->refresh();

        $publishedDataMap = $image->object->dataMap();
        $files = eZImageFile::fetchForContentObjectAttribute( $publishedDataMap['image']->attribute( 'id' ) );
        $publishedImagePath = $files[0];

        // Create a new image file
        $imagePath = sprintf( $imagePattern, md5(2), 2 );
        copy( $baseImagePath, $imagePath );
        $toDelete[] = $imagePath;

        // Create version 2 in another language, and remove it
        $languageCode = 'nor-NO';
        $version = self::addTranslationDontPublish( $image, $languageCode );
        $version->removeThis();

        // Check that the original file still exists
        $this->assertTrue( file_exists( $publishedImagePath ), 'The image file from version 1 should still exist when version 2 is removed' );

        array_map( 'unlink', $toDelete );
        $image->purge();
    }

    /**
     * Helper function for testIssue16078(), based on ezpObject::addTranslation().
     *
     * @param object $object
     * @param string $newLanguageCode
     * @return version in new language
     **/
    public function addTranslationDontPublish( $object, $newLanguageCode )
    {
        // Make sure to refresh the objects data.
        $object->refresh();

        $object->object->cleanupInternalDrafts();
        $publishedDataMap = $object->object->dataMap();
        $version = $object->object->createNewVersionIn( $newLanguageCode, 'eng-GB' ); // Create new translation based on eng-GB
        $version->setAttribute( 'status', eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
        $version->store();

        $newVersion = $object->object->version( $version->attribute( 'version' ) );
        $newVersionAttributes = $newVersion->contentObjectAttributes( $newLanguageCode );
        $versionDataMap = array();
        foreach( $newVersionAttributes as $attribute )
        {
            $versionDataMap[$attribute->contentClassAttributeIdentifier()] = $attribute;
        }

        // Start updating new version
        $version->setAttribute( 'modified', time() );
        $version->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );

        $db = eZDB::instance();
        $db->begin();

        $version->store();

        foreach ( $publishedDataMap as $attr => $value )
        {
            $versionDataMap[$attr]->setAttribute( 'data_text', $value->attribute( 'data_text' ) );
            $versionDataMap[$attr]->store();
        }

        $db->commit();

        return $version;
    }

}

?>
