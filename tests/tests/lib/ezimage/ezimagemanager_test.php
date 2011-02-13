<?php

class eZImageManagerTest extends ezpTestCase
{
    public static function gdIsEnabled()
    {
        return function_exists( 'gd_info' );
    }

    public static function imageMagickIsEnabled()
    {
        $imageIni = eZINI::instance( 'image.ini' );
        return ( in_array( 'ImageMagick', $imageIni->variable( 'ImageConverterSettings', 'ImageConverters' ) ) and
                 $imageIni->variable( 'ImageMagick', 'IsEnabled' ) == 'true' );
    }

    public function setUp()
    {
        parent::setUp();

        $this->imageIni = eZINI::instance( 'image.ini' );
    }

    /**
     * Test scenario for image alias using filters from multiple image handlers
     * for issue #15773: Infinite loop in ImageManager when using filters from multiple image handlers
     *
     * Test Outline
     * ------------
     * 1. Setup alias with filters from multiple image handlers
     * 2. Load image manager
     * 3. Backup max_execution_time, set it to 60 seconds
     * 4. Convert image
     * 5. Restore max_execution_time
     *
     * @result:
     *   The operation times out after 60 seconds
     * @expected:
     *   The conversion call returns true
     * @link http://issues.ez.no/15773
     */
    public function testMultiHandlerAlias()
    {
        if ( !self::gdIsEnabled() or !self::imageMagickIsEnabled() )
            $this->markTestSkipped( 'GD and/or ImageMagick are not enabled' );

        $aliasList = $this->imageIni->variable( 'AliasSettings', 'AliasList' );
        array_push( $aliasList, 'multihandler' );
        $this->imageIni->setVariable( 'AliasSettings', 'AliasList', $aliasList );
        $this->imageIni->setVariable( 'multihandler', 'Reference', '' );
        $this->imageIni->setVariable( 'multihandler', 'Filters', array( 'luminance/gray', 'filter/swirl=210' ) );

        $sourcePath = 'tests/tests/lib/ezimage/data/andernach.jpg';
        $targetPath = 'tests/tests/lib/ezimage/data/andernach_result.jpg';
        $img = eZImageManager::instance();
        $img->readINISettings();

        $timeLimit = ini_get( 'max_execution_time' );
        set_time_limit( 60 );
        $result = $img->convert( $sourcePath, $targetPath, 'multihandler' );
        set_time_limit( $timeLimit );

        $this->assertEquals( true, $result );
    }
}

?>
