<?php
/**
 * File containing the eZINITest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZINITest extends ezpTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZINITest" );
    }

    /**
     * Test to make sure default override dirs only contain 'override' folder
     */
    public function testDefaultOverrideDirs()
    {
        $ini = new eZINI( 'site.ini', 'settings', null, null, true );
        $ini->resetOverrideDirs();

        // test that we only get one override dir and it's value is 'settings/override'
        $overrideDirs = $ini->overrideDirs();
        self::assertEquals( 1, count( $overrideDirs ) );
        self::assertEquals( 'override', $overrideDirs[0][0] );
        self::assertFalse( $overrideDirs[0][1] );
    }

    /**
     * Test to make sure default override dirs are same as raw structure after reset
     */
    public function testRawOverrideDirs()
    {
        $ini = new eZINI( 'site.ini', 'settings', null, null, true );
        $ini->resetOverrideDirs();

        // make sure raw structure is same as default structure
        self::assertEquals( eZINI::defaultOverrideDirs(), $ini->overrideDirs( false ), 'Override array should be same as default override array structure' );
    }

    /**
     * Test prepending siteaccess dirs
     */
    public function testOverrideDirScopesSiteaccess()
    {
        $ini = new eZINI( 'site.ini', 'settings', null, null, true );
        $ini->resetOverrideDirs();

        $ini->prependOverrideDir( "siteaccess/eng", false, 'siteaccess' );
        $ini->prependOverrideDir( "extension/ext1/siteaccess/eng", true );
        $ini->appendOverrideDir( "extension/ext3/siteaccess/eng", true );
        $ini->prependOverrideDir( "siteaccess/nor", false, 'siteaccess' );// will override first dir

        $overrideDirs = $ini->overrideDirs( false );
        self::assertEquals( 4, count( $ini->overrideDirs() ), 'There should have been three override dirs in total in this ini instance.' );
        self::assertEquals( 3, count( $overrideDirs['siteaccess'] ), 'There should have been two override dirs in siteaccess scope.' );

        self::assertTrue( $overrideDirs['siteaccess'][0][1], "This override dir '" . $overrideDirs['siteaccess'][0][0] . "' should have been global(true)" );

        self::assertEquals( "siteaccess/nor", $overrideDirs['siteaccess']['siteaccess'][0], "Siteaccess should have been overridden by identifier" );

        self::assertEquals( "extension/ext3/siteaccess/eng", $overrideDirs['siteaccess'][1][0] );
    }

    /**
     * Test prepending extension dirs
     */
    public function testOverrideDirScopesExtension()
    {
        $ini = new eZINI( 'site.ini', 'settings', null, null, true );
        $ini->resetOverrideDirs();

        $ini->prependOverrideDir( "extension/ext1/settings", true, 'extension:ext1', 'extension' );
        $ini->prependOverrideDir( "extension/ext2/settings", true, 'extension:ext2', 'extension' );
        $ini->prependOverrideDir( "extension/ext3/settings", true, 'extension:ext3', 'extension' );
        // should override prev use of :ext1
        $ini->prependOverrideDir( "extension/ext1/settings", true, 'extension:ext1', 'extension' );
        // will not be part of override dirs in output as it uses same identifier as an extension
        $ini->prependOverrideDir( "extension/ext1/settings", true, 'extension:ext1', 'sa-extension' );

        $overrideDirs = $ini->overrideDirs( false );
        self::assertEquals( 4, count( $ini->overrideDirs() ), 'There should have been four override dirs in total in this ini instance.' );
        self::assertEquals( 3, count( $overrideDirs['extension'] ), 'There should have been three override dirs in extension scope.' );
        self::assertEquals( 1, count( $overrideDirs['sa-extension'] ), 'There should have been three override dirs in sa-extension scope.' );
    }

    /**
     * Test prepending extension dirs and removing
     */
    public function testOverrideDirScopesExtensionRemove()
    {
        $ini = new eZINI( 'site.ini', 'settings', null, null, true );
        $ini->resetOverrideDirs();

        $ini->prependOverrideDir( "extension/ext1/settings", true, 'extension:ext1', 'extension' );
        $ini->prependOverrideDir( "extension/ext2/settings", true, 'extension:ext2', 'extension' );
        $ini->prependOverrideDir( "extension/ext3/settings", true, 'extension:ext3', 'extension' );
        $ini->prependOverrideDir( "extension/ext1/settings", true, 'extension:ext1', 'sa-extension' );

        $ini->removeOverrideDir( 'extension:ext1' );
        $success = $ini->removeOverrideDir( 'extension:ext1', 'sa-extension' );
        $failed  = $ini->removeOverrideDir( 'extension:ext8' );

        $overrideDirs = $ini->overrideDirs( false );
        self::assertEquals( 3, count( $ini->overrideDirs() ), 'There should have been three override dirs in total in this ini instance.' );
        self::assertEquals( 2, count( $overrideDirs['extension'] ), 'There should have been two override dirs in extension scope.' );
        self::assertEquals( 0, count( $overrideDirs['sa-extension'] ), 'There should have been 0 override dirs in sa-extension scope.' );
        self::assertTrue( $success, '$ini->removeOverrideDir( \'extension:ext1\', \'sa-extension\' ) should have been returned true as identifier does exist.' );
        self::assertFalse( $failed, '$ini->removeOverrideDir( \'extension:ext8\' ) should have been returned false as identifier does not exist.' );
    }
}

?>
