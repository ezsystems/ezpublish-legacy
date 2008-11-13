<?php
/**
 * File containing the eZContentObjectStateGroupTest class
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZContentObjectStateGroupTest extends PHPUnit_Framework_TestCase
{
    public function providerCreateWithInvalidIdentifier()
    {
        return array(
            array( 'WithUpperCaseChars' ),
            array( str_repeat( 'x', 46 ) )
        );
    }

    /**
     * @dataProvider providerCreateWithInvalidIdentifier
     */
    public function testCreateWithInvalidIdentifier( $identifier )
    {
        $row = array( 'identifier' => $identifier );
        $stateGroup = new eZContentObjectStateGroup( $row );
        $messages = array();
        $this->assertFalse( $stateGroup->isValid( $messages ), "Invalid state group identifier '$identifier' was accepted" );
    }

    public function providerCreateWithvalidIdentifier()
    {
        return array(
            array( 'lowercasechars' ),
            array( str_repeat( 'x', 45 ) )
        );
    }

    /**
     * @dataProvider providerCreateWithValidIdentifier
     */
    public function testCreateWithvalidIdentifier( $identifier )
    {
        $row = array( 'identifier' => $identifier );
        $stateGroup = new eZContentObjectStateGroup( $row );
        $messages = array();
        $this->assertTrue( $stateGroup->isValid( $messages ), "Valid state group identifier '$identifier' was refused" );
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( "eZContentObjectStateGroupTest" );
    }
}

?>