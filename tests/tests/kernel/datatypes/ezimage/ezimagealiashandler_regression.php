<?php
/**
 * File containing the eZImageAliasHandlerRegression class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZImageAliasHandlerRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    /**
     * Regression test for issue #15155
     *
     * @link http://issues.ez.no/15155
     */
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

    /**
     * @return array
     */
    public function providerForTestImageNameObjectFalse()
    {
        return array(
            array(
                str_repeat( 'a', 180 ) . "This is a Very Long Name isn't it?",
                str_repeat( 'a', 180 ) . "This-is-a-Very-Long-1"
            ),
            array(
                str_repeat( 'a', 180 ) . "私は簡単にパブリッシュの記事で使用することができるようなも",
                str_repeat( 'a', 180 ) . "私は簡単にパ1"
            ),
            array(
                str_repeat( 'a', 180 ) . "私aはb簡c単dにeパfブgリhッシュの記事で使用することがで",
                str_repeat( 'a', 180 ) . "私aはb簡c単dにe1"
            )
        );
    }

    public function providerForTestImageNameByNodeObjectFalse()
    {
        return array(
            array(
                str_repeat( 'a', 180 ) . "This is a Very Long Name isn't it?",
                str_repeat( 'a', 180 ) . "This-is-a-Very-Long-"
            ),
            array(
                str_repeat( 'a', 180 ) . "私は簡単にパブリッシュの記事で使用することができるようなも",
                str_repeat( 'a', 180 ) . "私は簡単にパ"
            ),
            array(
                str_repeat( 'a', 180 ) . "私aはb簡c単dにeパfブgリhッシュの記事で使用することができるようなも",
                str_repeat( 'a', 180 ) . "私aはb簡c単dにe"
            )
        );
    }

    /**
     * @dataProvider providerForTestImageNameObjectFalse
     */
    public function testImageNameObjectFalse( $longName, $expects )
    {
        ezpINIHelper::setINISetting( 'site.ini', 'URLTranslator', 'TransformationGroup', 'urlalias_iri' );

        $handler = new eZImageAliasHandler( null );
        $language = "fre-FR";
        $contentVersionMock = $this->getMockBuilder( 'eZContentObjectVersion' )->disableOriginalConstructor()->getMock();
        $contentVersionMock->expects( $this->any() )->method( 'versionName' )->will( $this->returnValue( $longName ) );

        $name = $handler->imageName( null, $contentVersionMock, $language );

        $this->assertEquals( $expects, $name );

        ezpINIHelper::setINISetting( 'site.ini', 'URLTranslator', 'TransformationGroup', 'urlalias' );
    }

    /**
     * @dataProvider providerForTestImageNameByNodeObjectFalse
     */
    public function testImageNameByNodeObjectFalse( $longName, $expects )
    {
        ezpINIHelper::setINISetting( 'site.ini', 'URLTranslator', 'TransformationGroup', 'urlalias_iri' );
        $handler = new eZImageAliasHandler( null );
        $language = "fre-FR";

        $mainNodeMock = $this->getMockBuilder( 'eZContentObjectTreeNode' )->disableOriginalConstructor()->getMock();
        $mainNodeMock->expects( $this->any() )->method( 'getName' )->will( $this->returnValue( $longName ) );

        $name = $handler->imageNameByNode( null, $mainNodeMock, $language );

        $this->assertEquals( $expects, $name );

        ezpINIHelper::setINISetting( 'site.ini', 'URLTranslator', 'TransformationGroup', 'urlalias' );
    }
}

?>
