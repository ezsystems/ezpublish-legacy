<?php
/**
 * File containing the eZFileDownloadTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZFileRenameTest extends ezpTestCase
{
    private $warningEnabledOrig;

    private $fileDir;

    private $file;

    private $destFile;

    private $content;

    public function setUp()
    {
        parent::setUp();
        $this->fileDir = dirname( __FILE__ ) . "/data";
        $this->file = "$this->fileDir/file.txt";
        $this->destFile = "$this->fileDir/file2.txt";
        $this->content = file_get_contents( $this->file );
        // Disabling PHP E_Warning conversion to exceptions
        $this->warningEnabledOrig = PHPUnit_Framework_Error_Warning::$enabled;
        PHPUnit_Framework_Error_Warning::$enabled = false;
    }

    public function tearDown()
    {
        // If file has been renamed, restore it at original place
        if ( file_exists( $this->destFile ) )
            rename( $this->destFile, $this->file );
        // If file has been removed, restore it as well
        if ( !file_exists( $this->file ) )
            file_put_contents ( $this->file, $this->content );
        PHPUnit_Framework_Error_Warning::$enabled = $this->warningEnabledOrig;
        parent::tearDown();
    }

    /**
     * @group ezfile
     * @group ezfilerename
     */
    public function testRename()
    {
        self::assertTrue( eZFile::rename( $this->file, $this->destFile ) );
        self::assertTrue( file_exists( $this->destFile ) );
        self::assertSame( $this->content , file_get_contents( $this->destFile ) );
    }

    /**
     * @group ezfile
     * @group ezfilerename
     */
    public function testRenameWithDirectory()
    {
        $this->destFile = "$this->fileDir/somedir/file.txt";
        self::assertTrue( eZFile::rename( $this->file, $this->destFile, true ) );
        self::assertTrue( file_exists( $this->destFile ) );
        self::assertSame( $this->content , file_get_contents( $this->destFile ) );
    }

    /**
     * @group ezfile
     * @group ezfilerename
     */
    public function testRenameFails()
    {
        self::assertFalse( @eZFile::rename( $this->file, "/non/existing/dir/file.txt" ) );
    }

    /**
     * @group ezfile
     * @group ezfilerename
     */
    public function testRenameFailsWithFlags()
    {
        self::assertFalse(
            @eZFile::rename(
                $this->file,
                "/non/existing/dir/file.txt",
                false,
                eZFile::CLEAN_ON_FAILURE | eZFile::APPEND_DEBUG_ON_FAILURE
            )
        );
        self::assertFalse( file_exists( $this->file ) );
        // Note: can't test that debug has been properly called here...
    }
}
?>
