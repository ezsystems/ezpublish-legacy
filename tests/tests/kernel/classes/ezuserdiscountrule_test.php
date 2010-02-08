<?php
/**
 * File containing the eZUserDiscountRule class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZUserDiscountRuleTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZDiscountRule Unit Tests" );
    }

    /**
     * Unit test for eZDiscountRule::fetchByUserIDArray()
     **/
    public function testFetchByUserIDArray()
    {
        // Create 5 few discount rules
        $discountRules = array();
        for( $i = 0; $i < 5; $i++ )
        {
            $row = array( 'name' => __FUNCTION__ . " #{$i}" );
            $rule = new eZDiscountRule( $row );
            $rule->store();
            $discountRules[] = $rule;
        }

        // Create 5 user discount rules for 3 different user IDs
        $usersDiscountRules = array();
        foreach( array( 1, 3 ) as $userID )
        {
            $usersDiscountRules[$userID] = array();
            $userDiscountRules =& $usersDiscountRules[$userID];
            foreach( $discountRules as $discountRule )
            {
                $row = array(
                    'discountrule_id' => $discountRule->attribute( 'id' ),
                    'contentobject_id' => $userID,
                );
                $userDiscountRule = new eZUserDiscountRule( $row );
                $userDiscountRule->store();
                $userDiscountRules[] = $userDiscountRule;
            }
        }

        // fetch the discount rules for user #1 and #2. This will match 10
        // eZUserDiscountRule, and return the 5 rules, since no duplicates will
        // be returned
        $rules = eZUserDiscountRule::fetchByUserIDArray( array( 1, 2 ) );
        $this->assertType( 'array', $rules,
            "Return value should have been an array" );
        $this->assertEquals( 5, count( $rules ),
            "Return value should contain 5 items" );
        foreach( $rules as $rule )
        {
            $this->assertType( 'eZDiscountRule', $rule );
        }

    }
}
?>