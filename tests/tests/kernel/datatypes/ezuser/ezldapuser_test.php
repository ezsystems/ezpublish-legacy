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
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupNameAttribute', 'ou' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMemberAttribute', 'ou' );
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
     * Test scenario for LDAP login using UseGroupAttribute
     *
     * Test Outline
     * ------------
     * 1. Set correct LDAPGroupMappingType
     * 2. Login with username and password
     * 3. Check returned value in $user
     *
     * @result: $user is an object of class eZUser, this means a successful login.
     * @expected: $user is an object of class eZUser, this means a successful login.
     */
    public function testLoginUserUseGroupAttribute()
    {
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'UseGroupAttribute' );

        $user = eZLDAPUser::loginUser( 'han.solo', 'leiaishot' );
        self::assertEquals( 'eZUser', get_class( $user ) );
    }

    /**
     * Test scenario for issue #xxxxx: LDAP login using SimpleMapping fails
     *
     * Test Outline
     * ------------
     * 1. Set LDAPGroupMappingType = SimpleMapping and mapping settings
     * 2. Login with username and password
     * 3. Check parent nodes of user object
     *
     * @result: User is placed under the node given by LDAPGroupRootNodeId
     * @expected: User is placed in the Editors and Guest Accounts group.
     * @link http://issues.ez.no/xxxxx
     */
    public function testLoginUserSimpleMapping()
    {
        self::markTestSkipped( "This test isn't done yet" );

        $GLOBALS['eZNotificationEventTypes']['ezpublish'] = 'eZPublishType';

        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'SimpleMapping' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupMap', array( 'StarWars' => 'Editors',
                                                                                'RebelAlliance' => 'Administrator Users',
                                                                                'Rogues' => 'Guest Accounts' ) );

        $user = eZLDAPUser::loginUser( 'chewbacca', 'aaawwwwrrrkk' );
        $contentObject = $user->attribute( 'contentobject' );
        self::assertEquals( array( 14, 12 ), $contentObject->attribute( 'parent_nodes' ) );
    }

    /**
     * Test scenario for issue #xxxxx: LDAP login using SimpleMapping fails
     *
     * Test Outline
     * ------------
     * 1. Set LDAPGroupMappingType = UseGroupAttribute
     * 2. Login with username and password
     * 3. Check parent nodes of user object
     *
     * @result: User is placed under the node given by LDAPGroupRootNodeId
     * @expected: User is placed under the node given by LDAPGroupRootNodeId
     *
     * 1. Set LDAPGroupMappingType = SimpleMapping and mapping settings
     * 2. Login with username and password
     * 3. Check parent nodes of user object
     *
     * @result: User is placed in the Editors group
     * @expected: User is placed in the Editors and Administrator Users groups.
     * @link http://issues.ez.no/xxxxx
     */
    public function testLoginUserSimpleMappingExistingUser()
    {
        self::markTestSkipped( "This test isn't done yet" );

        $GLOBALS['eZNotificationEventTypes']['ezpublish'] = 'eZPublishType';

        // First login using UseGroupAttribute, to get an existing user object
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'UseGroupAttribute' );

        // It should be placed under the node given by LDAPGroupRootNodeId
        $user = eZLDAPUser::loginUser( 'leia', 'bunhead' );
        $contentObject = $user->attribute( 'contentobject' );
        self::assertEquals( array( $this->ldapINI->variable( 'LDAPSettings', 'LDAPGroupRootNodeId' ) ),
                            $contentObject->attribute( 'parent_nodes' ) );


        // Then login using SimpleMapping
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'SimpleMapping' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupMap', array( 'StarWars' => 'Editors',
                                                                                'RebelAlliance' => 'Administrator Users',
                                                                                'Rogues' => 'Guest Accounts' ) );

        $user = eZLDAPUser::loginUser( 'leia', 'bunhead' );
        $contentObject = $user->attribute( 'contentobject' );
        self::assertEquals( array( 14, 13 ), $contentObject->attribute( 'parent_nodes' ) );
    }
}

?>
