<?php
/**
 * File containing the eZLDAPUserTest class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZLDAPUserTest extends ezpDatabaseTestCase
{
    /**
     * @var eZINI
     **/
    protected $ldapINI;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZUser Datatype LDAP Tests" );
    }

    public function setUp()
    {
        parent::setUp();

        $this->ldapINI = eZINI::instance( 'ldap.ini' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPDebugTrace', 'enabled' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPVersion', 3 );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPFollowReferrals', 0 );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPEnabled', 'true' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPServer', 'ezctest.ez.no' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPPort', 389 );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPBaseDn', 'dc--ezctest,dc--ez,dc--no' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPBindUser', '' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPBindPassword', '' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPSearchScope', 'sub' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPEqualSign', '--' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPSearchFilters', array() );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPLoginAttribute', 'uid' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupType', 'name' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroup', 'Users' );

        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupRootNodeId', 5 );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'UseGroupAttribute' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupBaseDN', 'dc--ezctest,dc--ez,dc--no' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupClass', 'organizationalUnit' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupNameAttribute', 'cn' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMemberAttribute', 'member' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupDescriptionAttribute', 'description' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupMap', array() );

        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupAttributeType', 'name' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupAttribute', 'ou' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPFirstNameAttribute', 'givenname' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPFirstNameIsCommonName', 'false' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPLastNameAttribute', 'sn' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPEmailAttribute', 'mail' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPEmailEmptyAttributeSuffix', '' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'Utf8Encoding', 'false' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'KeepGroupAssignment', 'disabled' );
    }

    /**
     * Test scenario for issue #13492: Links are lost after removing version
     *
     * Test Outline
     * ------------
     * 1. Create a Folder in English containing a link (in the short_description attribute).
     * 2. Translate Folder into Norwegian containing another link (not the same link as above.)
     * 3. Remove Folder version 1. (Version 2 is created when translating).
     *
     * @result: short_description in version 2 will have an empty link.
     * @expected: short_description should contain same link as in version 1.
     * @link http://issues.ez.no/13492
     */
    public function testLoginUserUseGroupAttribute()
    {
//         self::markTestSkipped( "This test isn't done yet" );

        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'UseGroupAttribute' );

        $user = eZLDAPUser::loginUser( 'han.solo', 'leiaishot' );

        self::assertEquals( 'eZUser', get_class( $user ) );
    }
}

?>
