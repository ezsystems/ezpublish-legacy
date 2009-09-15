<?php
/**
 * File containing the eZImageAliasHandlerRegression class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZImageAliasHandlerRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZImageAliasHandler Regression Tests" );
    }

    /**
     * Regression test for issue #15155
     *
     * @link http://issues.ez.no/15155
     **/
    public function testIssue15155()
    {
        // figure out the max versions for images
        $contentINI = eZINI::instance( 'content.ini' );
        $versionlimit = $contentINI->variable( 'VersionManagement', 'DefaultVersionHistoryLimit' );
        $limitList =  eZContentClass::classIDByIdentifier( $contentINI->variable( 'VersionManagement', 'VersionHistoryClass' ) );
        $classID = 5; // image class, can remain hardcoded, I guess
        foreach ( $limitList as $key => $value )
        {
            if ( $classID == $key )
            {
                $versionlimit = $value;
            }
        }
        if ( $versionlimit < 2 )
        {
            $versionlimit = 2;
        }

        $baseImagePath = dirname( __FILE__ ) . '/ezimagealiashandler_regression_issue15155.png';
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

        $contentObjectID = $image->object->attribute( 'id' );

        $dataMap = eZContentObject::fetch( $contentObjectID )->dataMap();
        $originalAliases[1] = $image->image->imageAlias( 'original' );

        for( $i = 2; $i <= 20; $i++ )
        {
            // Create a new image file
            $imagePath = sprintf( $imagePattern, md5($i), $i );
            copy( $baseImagePath, $imagePath );
            $toDelete[] = $imagePath;

            $newVersion = $image->createNewVersion();
            $dataMap = $newVersion->dataMap();
            $dataMap['image']->fromString( $imagePath );
            ezpObject::publishContentObject( $image->object, $newVersion );
            $image->refresh();

            $originalAliases[$i] = $image->image->imageAlias( 'original' );

            if ( $i > $versionlimit )
            {
                $removeVersion = $i - $versionlimit;
                $aliasPath = $originalAliases[$removeVersion]['url'];
                $this->assertFalse( file_exists( $aliasPath ), "Alias $aliasPath for version $removeVersion should have been removed" );
            }
        }

        array_map( 'unlink', $toDelete );
        $image->purge();
    }
}

?>