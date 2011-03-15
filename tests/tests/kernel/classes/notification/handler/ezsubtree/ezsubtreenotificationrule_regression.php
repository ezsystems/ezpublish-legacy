<?php
/**
 * File containing the eZSubTreeNotificationRuleRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZSubtreeNotificationRuleRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;
    protected $policy;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZSubTreeNotificationRule Regression Tests" );
    }

    public function setUp()
    {
        parent::setUp();

        $this->policy = eZPolicy::createNew(
            1, array( 'ModuleName' => 'foo', 'FunctionName' => 'bar' )
        );

        eZPolicyLimitation::createNew(
            $this->policy->attribute( 'id' ), 'Owner'
        );
    }

    public function tearDown()
    {
        eZPolicyLimitation::removeByID(
            $this->policy->attribute( 'id' )
        );

        eZPolicy::removeByID(
            $this->policy->attribute( 'id' )
        );

        $this->policy = null;

        parent::tearDown();
    }

    /**
     * Wrong use of array_intersect() in ezsubtreenotificationrule.php
     *
     * @link http://issues.ez.no/16248
     */
    public function testCorrectUsageArrayIntersect()
    {
        $access = eZSubtreeNotificationRule::checkObjectAccess(
            eZContentObject::fetch( 1 ),
            $this->policy->attribute( 'id' ),
            array( 14 )
        );

        $this->assertEquals( array( 14 ), $access );
    }
}

?>
