<?php
/**
 * File containing the eZExtensionWithOrderingTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZExtensionWithOrderingTest extends ezpTestCase
{
    public function setUp()
    {
        ezpINIHelper::setINISetting( 'site.ini', 'ExtensionSettings', 'ExtensionDirectory', 'tests/tests/kernel/classes/extensions/' );
        ezpINIHelper::setINISetting( 'site.ini', 'ExtensionSettings', 'ExtensionOrdering', 'enabled' );
    }
    public function tearDown()
    {
        ezpINIHelper::restoreINISettings();
    }

    public function testUnrelatedKeepOrder1()
    {
        ezpINIHelper::setINISetting( 'site.ini', 'ExtensionSettings', 'ActiveExtensions', array( 'ezmultiupload', 'ezfind' ) );
        $this->assertSame(
            array( 'ezmultiupload', 'ezfind' ),
            eZExtension::activeExtensions() );
    }

    public function testUnrelatedKeepOrder2()
    {
        ezpINIHelper::setINISetting( 'site.ini', 'ExtensionSettings', 'ActiveExtensions', array( 'ezfind', 'ezmultiupload' ) );
        $this->assertSame(
            array( 'ezfind', 'ezmultiupload' ),
            eZExtension::activeExtensions() );
    }

    public function testSimpleNoReordering()
    {
        ezpINIHelper::setINISetting( 'site.ini', 'ExtensionSettings', 'ActiveExtensions', array( 'ezjscore', 'ezfind' ) );
        $this->assertSame(
            array( 'ezjscore', 'ezfind' ),
            eZExtension::activeExtensions() );
    }

    public function testSimpleReordering()
    {
        ezpINIHelper::setINISetting( 'site.ini', 'ExtensionSettings', 'ActiveExtensions', array( 'ezfind', 'ezjscore' ) );
        $this->assertSame(
            array( 'ezjscore', 'ezfind' ),
            eZExtension::activeExtensions() );
    }

    public function testSimpleReorderingKeepInitialDummies()
    {
        ezpINIHelper::setINISetting( 'site.ini', 'ExtensionSettings', 'ActiveExtensions', array( 'dummy1', 'dummy2', 'dummy3', 'ezfind', 'ezjscore' ) );
        $this->assertSame(
            array( 'dummy1', 'dummy2', 'dummy3', 'ezjscore', 'ezfind' ),
            eZExtension::activeExtensions() );
    }

    public function testComplexReordering()
    {
        ezpINIHelper::setINISetting( 'site.ini', 'ExtensionSettings', 'ActiveExtensions', array( 'ezfind', 'ezflow', 'ezgmaplocation', 'ezjscore', 'ezmultiupload', 'ezoe', 'ezwebin', 'ezwt' ) );
        $activeExtensions = eZExtension::activeExtensions();
        foreach ( array( 'ezfind', 'ezflow', 'ezgmaplocation', 'ezjscore', 'ezmultiupload', 'ezoe', 'ezwebin', 'ezwt' ) as $extension )
            $$extension = array_search( $extension, $activeExtensions );

        $this->assertLessThan( $ezwt, $ezmultiupload );
        $this->assertLessThan( $ezflow, $ezfind );
        $this->assertLessThan( $ezwebin, $ezfind );
        $this->assertLessThan( $ezwebin, $ezflow );
        $this->assertLessThan( $ezwebin, $ezjscore );
        $this->assertLessThan( $ezgmaplocation, $ezjscore );
        $this->assertLessThan( $ezoe, $ezjscore );
        $this->assertLessThan( $ezfind, $ezjscore );
    }

    public function testCycleInvolvesNoReordering1()
    {
        ezpINIHelper::setINISetting( 'site.ini', 'ExtensionSettings', 'ActiveExtensions', array( 'cycle1', 'cycle2' ) );
        $this->assertSame(
            array( 'cycle1', 'cycle2' ),
            eZExtension::activeExtensions() );
    }

    public function testCycleInvolvesNoReordering2()
    {
        ezpINIHelper::setINISetting( 'site.ini', 'ExtensionSettings', 'ActiveExtensions', array( 'cycle2', 'cycle1' ) );
        $this->assertSame(
            array( 'cycle2', 'cycle1' ),
            eZExtension::activeExtensions() );
    }
}
?>
