<?php
/**
 * File containing the eZPolicyTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZPolicyTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZPolicy Unit Tests" );
    }

    /**
     * Unit test for eZPolicy::fetchTemporaryCopy
     */
    public function testFetchTemporaryCopy()
    {
        // Get the first policy from the anonymous role
        $policyList = $this->getRole()->policyList();
        $policy = current( $policyList );

        // The first fetch should create the temporary copy
        $temporaryPolicy = eZPolicy::fetchTemporaryCopy( $policy->attribute( 'id' ) );
        $this->assertInstanceOf( 'eZPolicy', $temporaryPolicy,
            'The temporary policy isn\'t an eZPolicy' );
        $this->assertEquals( $policy->attribute( 'id' ), $temporaryPolicy->attribute( 'original_id' ),
            'The temporary policy\'s original_id should matche the ID of the attribute it was fetch as a copy of' );
        $temporaryPolicyID = $temporaryPolicy->attribute( 'id' );
        unset( $temporaryPolicy );

        // The second one should transparently fetch it
        $temporaryPolicy = eZPolicy::fetchTemporaryCopy( $policy->attribute( 'id' ) );
        $this->assertInstanceOf( 'eZPolicy', $temporaryPolicy,
            'The temporary policy isn\'t an eZPolicy' );
        $this->assertEquals( $policy->attribute( 'id' ), $temporaryPolicy->attribute( 'original_id' ),
            'The temporary policy\'s original_id should matche the ID of the attribute it was fetch as a copy of' );
        $this->assertEquals( $temporaryPolicyID, $temporaryPolicy->attribute( 'id' ) );
    }

    /**
     * Unit test for eZPolicy::saveTemporary()
     */
    public function testSaveTemporary()
    {
        // Get the first policy from the anonymous role
        $policyList = $this->getRole()->policyList();
        $policy = current( $policyList );
        $originalPolicyID = $policy->attribute( 'id' );

        // The first fetch should create the temporary copy
        $temporaryPolicy = eZPolicy::fetchTemporaryCopy( $policy->attribute( 'id' ) );

        $temporaryPolicy->saveTemporary();

        // Check that the temporary policy has been moved to original
        $this->assertEquals( 0, $temporaryPolicy->attribute( 'original_id' ) );

        // Check that the source policy has been removed
        $oldPolicy = eZPolicy::fetch( $originalPolicyID );
        $this->assertNull( $oldPolicy );
    }

    /**
     * Returns the anonymous role
     */
    protected function getRole()
    {
        $roles = eZUser::fetch( eZINI::instance()->variable( 'UserSettings', 'AnonymousUserID' ) )->roles();
        return current( $roles );
    }
}
?>
