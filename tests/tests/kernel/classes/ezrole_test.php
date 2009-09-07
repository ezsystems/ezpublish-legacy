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
        $anonymousUserID = eZUser::fetchByName( 'anonymous' )->attribute( 'contentobject_id' );
        $adminUserID = eZUser::fetchByName( 'admin' )->attribute( 'contentobject_id' );

        if ( !$anonymousUserID or !$adminUserID )
        {
            $this->fail( "Could not fetch users anonymous and/or admin" );
        }

        // test with an empty array
        $roles = eZRole::fetchByUser( array() );
        $this->assertType( 'array', $roles,
            "eZRole::fetchByUser with an empty array should have returned an array" );
        $this->assertEquals( 0, count( $roles ),
            "eZRole::fetchByUser with an empty array should have returned an empty array" );

        // test with users with no direct role assignment (group assignment)
        // should return an empty array since anonymous/admin role is assigned
        // by group
        $parameter = array( $anonymousUserID, $adminUserID );
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
        $parameter = array( $anonymousUserID, $adminUserID );
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
}
?>