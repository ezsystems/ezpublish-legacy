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

    public function setUp()
    {
        parent::setUp();

        // Setup the LDAP user data
        require_once( 'tests/tests/kernel/datatypes/ezuser/setup_accounts.php' );

        // Setup user groups:
        // LDAP users
        //  StarWars
        //   RebelAlliance
        //   Rogues
        //   GalacticEmpire
        //    SithLords
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
        $sithGroup->name = 'SithLords';
        $sithGroup->publish();
        $this->sithGroupNodeId = $sithGroup->mainNode->node_id;

        // Setup default settings, change these in each test when needed
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
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupType', 'id' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroup', array( $mainGroupObjId ) );

        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupRootNodeId', $this->mainGroupNodeId );
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
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'UseGroupAttribute' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupAttributeType', 'name' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupAttribute', 'ou' );

        $user = eZLDAPUser::loginUser( 'han.solo', 'leiaishot' );
        $contentObject = $user->attribute( 'contentobject' );
        self::assertEquals( array( $this->starWarsGroupNodeId, $this->rogueGroupNodeId, $this->rebelGroupNodeId ),
                            $contentObject->attribute( 'parent_nodes' ) );
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
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'SimpleMapping' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupNameAttribute', 'ou' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMemberAttribute', 'seeAlso' );
        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPUserGroupMap', array( 'StarWars' => 'StarWars',
                                                                                'RebelAlliance' => 'RebelAlliance',
                                                                                'Rogues' => 'Rogues' ) );
        $this->ldapINI->setVariable( 'LDAPSettings', 'KeepGroupAssignment', 'disabled' );

        $user = eZLDAPUser::loginUser( 'chewbacca', 'aaawwwwrrrkk' );
        $contentObject = $user->attribute( 'contentobject' );
        self::assertEquals( array( $this->rebelGroupNodeId, $this->rogueGroupNodeId ),
                            $contentObject->attribute( 'parent_nodes' ) );
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
     */
    public function testLoginUserSimpleMappingExistingUser()
    {
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
    }

    /**
     * Test scenario for issue #xxxxx: LDAP login using GetGroupsTree fails
     *
     * Test Outline
     * ------------
     * 1. Set LDAPGroupMappingType = GetGroupsTree
     * 2. Login with username and password
     * 3. Check parent nodes of user object
     *
     * @result: User is placed under the newly created groups StarWars, GalacticEmpire and SithLords.
     *          SithLords is placed under the node given by LDAPGroupRootNodeId.
     * @expected: User is placed under the newly created groups StarWars, GalacticEmpire and SithLords.
     *            SithLords is placed under GalacticEmpire.
     * @link http://issues.ez.no/xxxxx
     */
    public function testLoginUserGetGroupsTree()
    {
        self::markTestSkipped( "This test isn't done yet" );

        $this->ldapINI->setVariable( 'LDAPSettings', 'LDAPGroupMappingType', 'GetGroupsTree' );

        $user = eZLDAPUser::loginUser( 'darth.vader', 'whosyourdaddy' );
        $contentObject = $user->attribute( 'contentobject' );
        $parentNodeIDs = $contentObject->attribute( 'parent_nodes' );
        $parentArray = array();
        foreach ( $parentNodeIDs as $nodeID )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            $parentArray[] = $nodeID . ':' . $node->attribute( 'parent_node_id' ) . ':' . $node->attribute( 'name' );
        }
        self::assertEquals( array( '59:5:StarWars', '60:5:GalacticEmpire', '61:60:SithLords' ), $parentArray );
    }

}

?>
