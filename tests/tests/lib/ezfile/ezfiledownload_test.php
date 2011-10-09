<?php
/**
 * File containing the eZFileDownloadTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZFileDownloadTest extends ezpTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->file = dirname( __FILE__ ) . "/data/file.txt";
        $this->content = file_get_contents( $this->file );
    }

    public function testDownload()
    {
        ob_start();
        $this->assertTrue( eZFile::downloadContent( $this->file ) );
        $this->assertEquals( $this->content , ob_get_clean() );
    }

    public function testDownloadOffset()
    {
        ob_start();
        $this->assertTrue( eZFile::downloadContent( $this->file, 8000 ) );
        $this->assertEquals( substr( $this->content, -192 ) , ob_get_clean() );
    }

    public function testDownloadSize()
    {
        ob_start();
        $this->assertTrue( eZFile::downloadContent( $this->file, 0, 100 ) );
        $this->assertEquals( substr( $this->content, 0, 100 ) , ob_get_clean() );
    }

    public function testDownloadOffsetSize()
    {
        ob_start();
        $this->assertTrue( eZFile::downloadContent( $this->file, 8000, 100 ) );
        $this->assertEquals( substr( $this->content, 8000, 100 ) , ob_get_clean() );
    }

    public function testDownloadOffsetSizeTooHigh()
    {
        ob_start();
        $this->assertTrue( eZFile::downloadContent( $this->file, 8000, 192 ) );
        $this->assertEquals( substr( $this->content, 8000 ) , ob_get_clean() );
    }

    public function testDownloadOffsetSizeWayTooHigh()
    {
        ob_start();
        $this->assertTrue( eZFile::downloadContent( $this->file, 8000, 1e5 ) );
        $this->assertEquals( substr( $this->content, 8000 ) , ob_get_clean() );
    }

    public function testDownloadOffsetTooBig()
    {
        ob_start();
        $this->assertTrue( eZFile::downloadContent( $this->file, 8193 ) );
        $this->assertEquals( "" , ob_get_clean() );
    }

    public function testDownloadNoFile()
    {
        ob_start();
        $this->assertFalse( eZFile::downloadContent( "unexisting.txt" ) );
        $this->assertEquals( "" , ob_get_clean() );
    }
}

?>
