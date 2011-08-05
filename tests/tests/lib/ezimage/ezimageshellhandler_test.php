<?php
/**
 * File containing the eZImageShellHandlerTest class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZImageShellHandlerTest extends ezpTestCase
{
    public function setUp()
    {
        parent::setUp();

        exec( eZINI::instance( "image.ini" )->variable( "ImageMagick", "Executable" ) . " -version", $output, $returnValue );
        if ( $returnValue !== 0 )
        {
            $this->markTestSkipped( 'ImageMagick is not installed' );
        }

        $this->imageManager = eZImageManager::instance();
        $this->imageManager->readINISettings();

        ezpINIHelper::setINISetting( 'image.ini', 'ImageConverterSettings', 'ImageConverters', array( 'ImageMagick' ) );
    }

    public function tearDown()
    {
        ezpINIHelper::restoreINISettings();
        unlink( "tests/tests/lib/ezimage/data/andernach_small.jpg" );
        parent::tearDown();
    }

    /**
     * Test scenario for issue #15406: eZ cannot convert images with ImageMagick on PHP 5.3
     * @link http://issues.ez.no/15406
     */
    public function testIssue15406()
    {
        $this->imageManager->convert( "tests/tests/lib/ezimage/data/andernach.jpg", $dest = "tests/tests/lib/ezimage/data/andernach_result.jpg", "small" );
        $this->assertTrue( file_exists( "tests/tests/lib/ezimage/data/andernach_small.jpg" ) );
    }
}
?>
