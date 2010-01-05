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
    protected $backupGlobals = false; // If true, user object publishing fails

    /**
     * @var eZINI
     **/
    protected $ldapINI;

    /**
     * @var ezpObject
     **/
    protected $mainGroup;

    /**
     * @var integer
     **/
    protected $mainGroupNodeId;

    /**
     * @var integer
     **/
    protected $starWarsGroupNodeId;

    /**
     * @var integer
     **/
    protected $rebelGroupNodeId;

    /**
     * @var integer
     **/
    protected $rogueGroupNodeId;

    /**
     * @var integer
     **/
    protected $empireGroupNodeId;

    /**
     * @var integer
     **/
    protected $sithGroupNodeId;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZUser Datatype LDAP Tests" );
    }

    public static function ldapIsEnabled()
    {
        return function_exists( 'ldap_search' );
    }

    public function setUp()
    {
        parent::setUp();

        if ( !self::ldapIsEnabled() )
        {
            return;
        }

        // Setup the LDAP user data
        require_once( 'tests/tests/kernel/datatypes/ezuser/setup_accounts.php' );

        // Setup user groups:
        // LDAP users
        //  StarWars
        //   RebelAlliance
        //   Rogues
        //   GalacticEmpire
        //    Sith
        $this->mainGroup = new ezpObject( 'user_group', 5 );
        $this->mainGroup->name = 'LDAP users';
        $mainGroupObjId = $this->mainGroup->publish();
        $this->mainGroupNodeId = $this->mainGroup->mainNode->node_id;

        $starWarsGroup = new ezpObject( 'user_group', (int)($this->mainGroupNodeId) );
        $starWarsGroup->name = 'StarWars';
        $starWarsGroup->publish();
        $this->starWarsGroupNodeId = $starWarsGroup->mainNode->node_id;

        $rebelGroup = new ezpObject( 'user_group', (int)($this->starWarsGroupNodeId) );
        $rebelGroup->name = 'RebelAlliance';
        $rebelGroup->publish();
        $this->rebelGroupNodeId = $rebelGroup->mainNode->node_id;

        $rogueGroup = new ezpObject( 'user_group', (int)($this->starWarsGroupNodeId) );
        $rogueGroup->name = 'Rogues';
        $rogueGroup->publish();
        $this->rogueGroupNodeId = $rogueGroup->mainNode->node_id;

        $empireGroup = new ezpObject( 'user_group', (int)($this->starWarsGroupNodeId) );
        $empireGroup->name = 'GalacticEmpire';
        $empireGroup->publish();
        $this->empireGroupNodeId = $empireGroup->mainNode->node_id;

        $sithGroup = new ezpObject( 'user_group', (int)($this->empireGroupNodeId) );
        $sithGroup->name = 'Sith';
        $sithGroup->publish();
        $this->sithGroupNodeId = $sithGroup->mainNode->node_id;

        // Setup default settings, change these in each test when needed
        $this->ldapINI = eZINI::instance( 'ldap.ini' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPDebugTrace', 'disabled' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPVersion', 3 );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPFollowReferrals', 0 );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPEnabled', 'true' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPServer', 'phpuc.ez.no' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPPort', 389 );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPBaseDn', 'dc--phpuc,dc--ez,dc--no' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPBindUser', '' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPBindPassword', '' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPSearchScope', 'sub' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPEqualSign', '--' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPSearchFilters', array() );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPLoginAttribute', 'uid' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupType', 'id' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroup', array( $mainGroupObjId ) );

        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupRootNodeId', $this->mainGroupNodeId );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'UseGroupAttribute' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupBaseDN', 'dc--phpuc,dc--ez,dc--no' );
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

    public function tearDown()
    {
        $this->mainGroup->remove();

        parent::tearDown();
    }

    /**
     * Test scenario for LDAP login using UseGroupAttribute
     *
     * Test Outline
     * ------------
     * 1. Set correct LDAPGroupMappingType
     * 2. Login with username and password
     * 3. Check parent nodes of user object
     *
     * @result: User is placed under the node given by LDAPGroupRootNodeId
     * @expected: User is placed under the node given by LDAPGroupRootNodeId
     */
    public function testLoginUserUseGroupAttributeNoGroupMatch()
    {
        if ( !self::ldapIsEnabled() )
        {
            $this->markTestSkipped( 'LDAP is not loaded' );
            return;
        }

        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'UseGroupAttribute' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupAttributeType', 'name' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupAttribute', 'ou' );

        $user = eZLDAPUser::loginUser( 'jabba.thehutt', 'wishihadlegs' );
        $contentObject = $user->attribute( 'contentobject' );
        self::assertEquals( array( $this->ldapINI->variable( 'LDAPSettings', 'LDAPGroupRootNodeId' ) ),
                            $contentObject->attribute( 'parent_nodes' ) );
    }

    /**
     * Test scenario for LDAP login using UseGroupAttribute
     *
     * Test Outline
     * ------------
     * 1. Set correct LDAPGroupMappingType
     * 2. Login with username and password
     * 3. Check parent nodes of user object
     *
     * @result: User is placed in the StarWars, Rogues and RebelAlliance groups.
     * @expected: User is placed in the StarWars, Rogues and RebelAlliance groups.
     */
    public function testLoginUserUseGroupAttributeHasGroupMatch()
    {
        if ( !self::ldapIsEnabled() )
        {
            $this->markTestSkipped( 'LDAP is not loaded' );
            return;
        }

        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'UseGroupAttribute' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupAttributeType', 'name' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupAttribute', 'ou' );

        $user = eZLDAPUser::loginUser( 'han.solo', 'leiaishot' );
        $contentObject = $user->attribute( 'contentobject' );
        $parentNodeIDs = $contentObject->attribute( 'parent_nodes' );
        sort( $parentNodeIDs );
        self::assertEquals( array( $this->starWarsGroupNodeId, $this->rebelGroupNodeId, $this->rogueGroupNodeId ),
                            $parentNodeIDs );
    }

    /**
     * Test scenario for LDAP login using UseGroupAttribute, and editing the local user object
     *
     * Test Outline
     * ------------
     * 1. Set correct LDAPGroupMappingType
     * 2. Login with username and password
     * 3. Check parent nodes of user object
     * 4. Edit the object, verify it has changed
     * 5. Login again, verify that the locally changed data was overwritten by LDAP data and that the version number has increased
     *
     * @result:
     *   User is placed in the StarWars, Rogues and RebelAlliance groups.
     *   Last name is 'Cola'
     *   Version number is '2'
     *   Last name is 'Solo'
     *   Version number is '2'
     *   (Meaning: The object was updated, but no new version created.)
     * @expected:
     *   User is placed in the StarWars, Rogues and RebelAlliance groups.
     *   Last name is 'Cola'
     *   Version number is '2'
     *   Last name is 'Solo'
     *   Version number is '3'
     */
    public function testLoginUserUseGroupAttributeEditUserObject()
    {
        if ( !self::ldapIsEnabled() )
        {
            $this->markTestSkipped( 'LDAP is not loaded' );
            return;
        }

        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'UseGroupAttribute' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPCreateMissingGroups', 'disabled' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupAttributeType', 'name' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupAttribute', 'ou' );

        $user = eZLDAPUser::loginUser( 'han.solo', 'leiaishot' );
        $contentObject = $user->attribute( 'contentobject' );
        $parentNodeIDs = $contentObject->attribute( 'parent_nodes' );
        sort( $parentNodeIDs );
        self::assertEquals( array( $this->starWarsGroupNodeId, $this->rebelGroupNodeId, $this->rogueGroupNodeId ),
                            $parentNodeIDs );

        // Edit the local user object, change last name
        $version = $contentObject->createNewVersion();
        $contentObjectAttributes = $version->contentObjectAttributes();
        foreach ( $contentObjectAttributes as $attribute )
        {
            if ( $attribute->attribute( 'contentclass_attribute_identifier' ) == 'last_name' )
            {
                $attribute->setAttribute( 'data_text', 'Cola' );
                $attribute->store();
                break;
            }
        }
        $contentObjectID = $contentObject->attribute( 'id' );
        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                     'version' => $version->attribute( 'version' ) ) );
        $contentObject = eZContentObject::fetch( $contentObjectID );
        $dataMap = $contentObject->dataMap();
        self::assertEquals( 'Cola', $dataMap['last_name']->attribute( 'data_text' ) );
        self::assertEquals( '2', $version->attribute( 'version' ) );

        // Login again, verify that the locally changed data was overwritten by LDAP data and that the version number has increased
        eZUser::logoutCurrent();
        $user = eZLDAPUser::loginUser( 'han.solo', 'leiaishot' );
        $contentObject = $user->attribute( 'contentobject' );
        $dataMap = $contentObject->dataMap();
        $version = $contentObject->currentVersion();
        self::assertEquals( 'Solo', $dataMap['last_name']->attribute( 'data_text' ) );
        self::assertEquals( '3', $version->attribute( 'version' ) );
    }

    /**
     * Test scenario for LDAP login using SimpleMapping
     *
     * Test Outline
     * ------------
     * 1. Set LDAPGroupMappingType = SimpleMapping and mapping settings
     * 2. Login with username and password
     * 3. Check parent nodes of user object
     *
     * @result: User is placed in the RebelAlliance and Rogues groups.
     * @expected: User is placed in the RebelAlliance and Rogues groups.
     */
    public function testLoginUserSimpleMapping()
    {
        if ( !self::ldapIsEnabled() )
        {
            $this->markTestSkipped( 'LDAP is not loaded' );
            return;
        }

        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'SimpleMapping' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupNameAttribute', 'ou' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMemberAttribute', 'seeAlso' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupMap', array( 'StarWars' => 'StarWars',
                                                                                'RebelAlliance' => 'RebelAlliance',
                                                                                'Rogues' => 'Rogues' ) );
        $this->ldapINI->setVariable( 'LDAPSettings', 'KeepGroupAssignment', 'disabled' );

        $user = eZLDAPUser::loginUser( 'chewbacca', 'aaawwwwrrrkk' );
        $contentObject = $user->attribute( 'contentobject' );
        $parentNodeIDs = $contentObject->attribute( 'parent_nodes' );
        sort( $parentNodeIDs );
        self::assertEquals( array( $this->rebelGroupNodeId, $this->rogueGroupNodeId ),
                            $parentNodeIDs );
    }

    /**
     * Test scenario for LDAP login using SimpleMapping, moving a user when the groups change
     *
     * Test Outline
     * ------------
     * 1. Set LDAPGroupMappingType = SimpleMapping but add no mappings
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
     * @result: User is placed in the RebelAlliance group
     * @expected: User is placed in the RebelAlliance group
     *
     * 1. Change mapping settings, RebelAlliance => StarWars
     * 2. Login with username and password
     * 3. Check parent nodes of user object
     *
     * @result: User is placed in the StarWars group
     * @expected: User is placed in the StarWars group
     */
    public function testLoginUserSimpleMappingExistingUser()
    {
        if ( !self::ldapIsEnabled() )
        {
            $this->markTestSkipped( 'LDAP is not loaded' );
            return;
        }

        // First login, to get an existing user object
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'SimpleMapping' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupNameAttribute', 'ou' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMemberAttribute', 'seeAlso' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupMap', array() );
        $this->ldapINI->setVariable( 'LDAPSettings', 'KeepGroupAssignment', 'disabled' );

        // The user should be placed under the node given by LDAPGroupRootNodeId
        $user = eZLDAPUser::loginUser( 'leia', 'bunhead' );
        $contentObject = $user->attribute( 'contentobject' );
        self::assertEquals( array( $this->ldapINI->variable( 'LDAPSettings', 'LDAPGroupRootNodeId' ) ),
                            $contentObject->attribute( 'parent_nodes' ) );

        // Then login again, with correct group mapping
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupMap', array( 'StarWars' => 'StarWars',
                                                                                'RebelAlliance' => 'RebelAlliance',
                                                                                'Rogues' => 'Rogues' ) );

        // The user should have moved to the RebelAlliance group
        $user = eZLDAPUser::loginUser( 'leia', 'bunhead' );
        $contentObject = $user->attribute( 'contentobject' );
        self::assertEquals( array( $this->rebelGroupNodeId ),
                            $contentObject->attribute( 'parent_nodes' ) );

        // Change mapping and login again
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupMap', array( 'StarWars' => 'StarWars',
                                                                                'RebelAlliance' => 'StarWars',
                                                                                'Rogues' => 'Rogues' ) );

        // The user should have moved to the StarWars group
        $user = eZLDAPUser::loginUser( 'leia', 'bunhead' );
        $contentObject = $user->attribute( 'contentobject' );
        self::assertEquals( array( $this->starWarsGroupNodeId ),
                            $contentObject->attribute( 'parent_nodes' ) );
    }

    /**
     * Test scenario for LDAP login using GetGroupsTree
     * for issue #15334: LDAP GetGroupsTree should be able to find groups in multiple tree levels
     *
     * Test Outline
     * ------------
     * 1. Set LDAPGroupMappingType = GetGroupsTree
     * 2. Login with username and password
     * 3. Check parent nodes of user object
     * 4. Login with username and password for another user
     * 5. Check parent nodes of user object
     *
     * @result:
     *   The first user is placed in the newly created Empire and Sith groups
     *   (the following assertions are not executed)
     * @expected:
     *   The first user is placed in the existing Empire and Sith groups
     *   The second user has two node assignments
     *   The first assignment is the existing RebelAlliance group
     *   The second assignment is the newly created Jedi group
     * @link http://issues.ez.no/15334
     */
    public function testLoginUserGetGroupsTree()
    {
        if ( !self::ldapIsEnabled() )
        {
            $this->markTestSkipped( 'LDAP is not loaded' );
            return;
        }

        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'GetGroupsTree' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupNameAttribute', 'ou' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMemberAttribute', 'seeAlso' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'KeepGroupAssignment', 'disabled' );

        // The Empire and Sith groups already exist
        $user = eZLDAPUser::loginUser( 'darth.vader', 'whosyourdaddy' );
        $contentObject = $user->attribute( 'contentobject' );
        $parentNodeIDs = $contentObject->attribute( 'parent_nodes' );
        sort( $parentNodeIDs );
        self::assertEquals( array( $this->empireGroupNodeId, $this->sithGroupNodeId ),
                            $parentNodeIDs );

        // Change the root node id, in order to create the Jedi group under the StarWars group
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupRootNodeId', $this->starWarsGroupNodeId );

        // Try a user with a group that is not in ezp
        $user = eZLDAPUser::loginUser( 'obi.wan', 'thesearenotthedroids' );
        $contentObject = $user->attribute( 'contentobject' );
        $parentNodeIDs = $contentObject->attribute( 'parent_nodes' );

        // The user should be assigned to 2 groups
        self::assertEquals( 2, count( $parentNodeIDs ) );

        // The RebelAlliance group already exists
        $node0 = eZContentObjectTreeNode::fetch( $parentNodeIDs[0] );
        self::assertEquals( array( $this->rebelGroupNodeId, $this->starWarsGroupNodeId, 'RebelAlliance' ),
                            array( $parentNodeIDs[0], $node0->attribute( 'parent_node_id' ), $node0->attribute( 'name' ) ) );

        // The Jedi group is created by the login handler
        $node1 = eZContentObjectTreeNode::fetch( $parentNodeIDs[1] );
        self::assertEquals( array( $this->starWarsGroupNodeId, 'Jedi' ),
                            array( $node1->attribute( 'parent_node_id' ), $node1->attribute( 'name' ) ) );
    }

    /**
     * Test scenario for ...
     *
     * Test Outline
     * ------------
     * 1. ...
     *
     * @result: ...
     * @expected: ...
     */
/*    public function testLoginUser...()
    {
        self::markTestSkipped( "This test isn't done yet" );
    }*/

}

?>
