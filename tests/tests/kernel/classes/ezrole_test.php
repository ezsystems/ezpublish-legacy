<?php
/**
 * File containing the eZRoleTest class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZRoleTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZRole Unit Tests" );
    }

    /**
     * Unit test for eZRole::fetchByUser
     **/
    public function testFetchByUser()
    {
        // test with an empty array
        $roles = eZRole::fetchByUser( array() );
        $this->assertType( 'array', $roles,
            "eZRole::fetchByUser with an empty array should have returned an array" );
        $this->assertEquals( 0, count( $roles ),
            "eZRole::fetchByUser with an empty array should have returned an empty array" );

        // test with users with no direct role assignment (group assignment)
        // should return an empty array since anonymous/admin role is assigned
        // by group
        $parameter = array( self::$anonymousUserID, self::$adminUserID );
        $roles = eZRole::fetchByUser( $parameter );
        $this->assertType( 'array', $roles,
            "eZRole::fetchByUser with admin & anonymous should have returned an array" );
        $this->assertEquals( 0, count( $roles ),
            "eZRole::fetchByUser with admin & anonymous should have returned an empty array" );
        foreach( $roles as $role )
        {
            $this->assertType( 'eZRole', $role, "Returned items should be roles" );
        }

        // test with the same users, but recursively: should return anonymous
        // and administrator roles
        $parameter = array( self::$anonymousUserID, self::$adminUserID );
        $roles = eZRole::fetchByUser( $parameter, true );
        $this->assertType( 'array', $roles,
            "recursive eZRole::fetchByUser with admin & anonymous should have returned an array" );
        $this->assertEquals( 2, count( $roles ),
            "recursive eZRole::fetchByUser with admin & anonymous should have returned an empty array" );
        foreach( $roles as $role )
        {
            $this->assertType( 'eZRole', $role, "Returned items should be roles" );
        }
    }

    /**
     * Unit test for eZRole::fetchIDListByUser()
     **/
    public function testFetchIDListByUser()
    {
        // fetch roles ID for anonymous group
        $roles = eZRole::fetchIDListByUser( array( self::$anonymousGroupID ) );
        $this->assertType( 'array', $roles, "The method should have returned an array" );
        $this->assertEquals( 1, count( $roles ), "The array should contain one item" );
        $this->assertEquals( 1, $roles[0], "The returned role ID should be 1 (anonymous role)" );
    }

    static $anonymousGroupID = 42;
    static $anonymousUserID = 10;
    static $adminUserID = 14;
}
?>