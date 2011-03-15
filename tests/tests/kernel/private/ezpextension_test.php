<?php
/**
 * File containing the ezpTopologicalSortTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class ezpExtensionTest extends ezpTestCase
{
    protected $data = array();

    public function setUp()
    {
        ezpINIHelper::setINISetting( 'site.ini', 'ExtensionSettings', 'ExtensionDirectory', 'tests/tests/kernel/classes/extensions' );
        self::clearActiveExtensionsCache();
        parent::setUp();
    }

    public function tearDown()
    {
        ezpINIHelper::restoreINISettings();
        eZCache::clearByID( 'active_extensions' );
        parent::tearDown();
    }

    /**
     * @dataProvider providerForGetLoadingOrderTest
     */
    public function testGetLoadingOrder( $extensionName, $expectedResult )
    {
        $extension = ezpExtension::getInstance( $extensionName );
        $loadingOrder = $extension->getLoadingOrder();
        $this->assertSame( $expectedResult, $loadingOrder );
    }

    public static function providerForGetLoadingOrderTest()
    {
        return array(

            // valid extension.xml
            array(
                'ezfind',
                array( 'before' => array( 'ezwebin', 'ezflow' ), 'after' => array( 'ezjscore' ) ),
            ),

            // only 'requires' dependency
            array(
                'ezdeprequires',
                array( 'before' => array(), 'after' => array( 'ezjscore' ) ),
            ),

            // only 'uses' dependency
            array(
                'ezdepuses',
                array( 'before' => array(), 'after' => array( 'ezjscore' ) ),
            ),

            // only 'extends' dependency
            array(
                'ezdepextends',
                array( 'before' => array( 'ezwebin', 'ezflow' ), 'after' => array() ),
            ),

            // invalid XML
            array(
                'ezdepinvalid',
                null,
            ),
        );
    }

    /**
     * @dataProvider providerForGetInfoTest
     */
    public function testGetInfo( $extensionName, $expectedResult )
    {
        $extension = ezpExtension::getInstance( $extensionName );
        $info = $extension->getInfo();
        $this->assertSame( $expectedResult, $info );
    }

    public static function providerForGetInfoTest()
    {
        $ezInfoNewArray = array(
            'name' => "New eZ Info",
            'version' => '2.0',
            'copyright' => "Copyright © 2010 eZ Systems AS.",
            'license' => "GNU General Public License v2.0",
            'info_url' => "http://ez.no",
            'Includes the following third-party software' => array(
                'name' => 'Software 1',
                'version' => '1.1',
                'copyright' => 'Some company.',
                'license' => 'Apache License, Version 2.0',
                'info_url' => 'http://company.com'
            ),
            'Includes the following third-party software (2)' => array(
                'name' => 'Software 2',
                'version' => '2.0',
                'copyright' => 'Some other company.',
                'license' => 'GNU Public license V2.0',
            ),
        );

        $ezInfoOldArray = array(
            'Name' => "Old eZ Info",
            'Version' => '1.0',
            'Copyright' => "Copyright © 2010 eZ Systems AS.",
            'Info_url' => "http://ez.no",
            'License' => "GNU General Public License v2.0",
            'Includes the following third-party software' => array(
                'name' => 'Software 1',
                'Version' => '1.1',
                'copyright' => 'Some company.',
                'license' => 'Apache License, Version 2.0',
                'info_url' => 'http://company.com',
             ),
            'Includes the following third-party software (2)' => array(
                'name' => 'Software 2',
                'Version' => '2.0',
                'copyright' => 'Some other company.',
                'license' => 'GNU Public license V2.0',
            ),
        );

        return array(
            // valid and complete extension.xml
            array( 'ezinfonew', $ezInfoNewArray ),

            // invalid extension.xml
            array( 'ezinfoinvalid', null ),

            // extension using ezinfo.php
            array( 'ezinfoold', $ezInfoOldArray )
        );
    }

    /**
     * @todo Move to a common extension testing class
     */
    private static function clearActiveExtensionsCache()
    {
        eZCache::clearByID( 'active_extensions' );

        // currently required so that cache will actually be considered expired
        // this is a design issue in eZExpiryHandler we need to address soon as it deeply impacts testing any feature
        // that relies on it, and also impacts runtime on high-trafic sites.
        sleep( 2 );
    }
}
?>
