<?php
/**
 * File containing the eZContentClassTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZContentClassTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    /*public function __construct()
    {
        parent::__construct();
        $this->setName( "eZContentClass Unit Tests" );
    }*/

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Unit test for eZContentClass::classIDByIdentifier()
     * @dataProvider providerForTestClassIDByIdentifier
     */
    public function testClassIDByIdentifier( $sourceArray, $expectedArray )
    {
        $transformedClassArray = eZContentClass::classIDByIdentifier( $sourceArray );
        $this->assertEquals( $expectedArray, $transformedClassArray );
    }

    /**
     * Data provider for self::testClassIDByIdentifier()
     * @see testClassIDByIdentifier()
     */
    public static function providerForTestClassIDByIdentifier()
    {
        return array(
            array( array( 1, 'article', 'image', 12, 13 ), array( 1, 2, 5, 12, 13 ) ),
            array( array( 'article', 'image', 'comment' ), array( 2, 5, 13 ) ),
        );
    }

    /**
     * Unit test for eZContentClass::versionHistoryLimit()
     * @dataProvider providerForTestVersionHistoryLimit
     */
    public function testVersionHistoryLimit( $INISettings, $class, $expectedLimit )
    {
        // change the INI limit settings
        foreach( $INISettings as $settings )
        {
            list( $INIVariable, $INIValue ) = $settings;
            ezpINIHelper::setINISetting( 'content.ini', 'VersionManagement', $INIVariable, $INIValue );
        }

        $limit = eZContentClass::versionHistoryLimit( $class );
        $this->assertEquals( $expectedLimit, $limit );

        ezpINIHelper::restoreINISettings();
    }

    /**
     * Data provider for testVersionHistoryLimit()
     */
    public static function providerForTestVersionHistoryLimit()
    {
        return array(
            // default limit with the article ID as string
            array(
                array(),
                'article', 10
            ),

            // custom default limit (15) with the article ID as INT
            array(
                array( array( 'DefaultVersionHistoryLimit', 15 ) ),
                2, 15
            ),

            // custom limits for article (7) and folder (5)
            array(
                array( array( 'VersionHistoryClass', array( 'article' => 7, 1 => 5 ) ) ),
                'article', 7
            ),

            // error case, unknown class, should return the default limit
            array(
                array(),
                'foobaredclassname', 10
            ),

            // error case again, unknown class, with a custom default limit
            array(
                array( array( 'DefaultVersionHistoryLimit', 12 ) ),
                'foobaredclassname', 12
            ),
        );
    }

    /**
     * Unit test for eZContentClass::versionHistoryLimit() with object parameters
     * 
     * Replica of testVersionHistoryLimit() but you cannot make calls
     * to the eZ API which relies on a database, as this is not present
     * in the provider methods.
     */
    public function testVersionHistoryLimitWithObjectParameter()
    {
        // different custom limits (article: 13, image: 6) and object as a parameter
        $INISettings = array( array( 'VersionHistoryClass', array( 'article' => 13, 'image' => 6 ) ) );
        $class = eZContentClass::fetchByIdentifier( 'image' );
        $expectedLimit = 6;

        // change the INI limit settings
        foreach( $INISettings as $settings )
        {
            list( $INIVariable, $INIValue ) = $settings;
            ezpINIHelper::setINISetting( 'content.ini', 'VersionManagement', $INIVariable, $INIValue );
        }

        $limit = eZContentClass::versionHistoryLimit( $class );
        self::assertEquals( $expectedLimit, $limit );

        ezpINIHelper::restoreINISettings();
    }
}

?>