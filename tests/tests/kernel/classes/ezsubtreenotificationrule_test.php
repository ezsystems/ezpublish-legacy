<?php
/**
 * File containing the eZSubtreeNotificationRuleTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZSubtreeNotificationRuleTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZSubtreeNotificationRule Unit Tests" );
    }

    /**
     * Unit test for eZSubtreeNotificationRule::fetchUserList()
     */
    public function testFetchUserList()
    {
        // Add a notification rule for admin on root
        $adminUserID = eZUser::fetchByName( 'admin' )->attribute( 'contentobject_id' );
        $rule = new eZSubtreeNotificationRule( array(
            'user_id' => $adminUserID,
            'use_digest' => 0,
            'node_id' => 2 ) );
        $rule->store();

        // Create a content object below node #2
        $article = new ezpObject( 'article', 2 );
        $article->title = __FUNCTION__;
        $article->publish();
        $articleContentObject = $article->object;

        $list = eZSubtreeNotificationRule::fetchUserList( array( 2, 43 ), $articleContentObject );
        $this->assertInternalType( 'array', $list,
            "Return value should have been an array" );
        $this->assertEquals( 1, count( $list ),
            "Return value should have one item" );
        $this->assertInternalType( 'array', $list[0] );
        $this->assertArrayHasKey( 'user_id', $list[0] );
        $this->assertArrayHasKey( 'use_digest', $list[0] );
        $this->assertArrayHasKey( 'address', $list[0] );
        $this->assertEquals( 14, $list[0]['user_id'] );
        $this->assertEquals( 0, $list[0]['use_digest'] );
        $this->assertEquals( 'nospam@ez.no', $list[0]['address'] );
    }
}

?>
