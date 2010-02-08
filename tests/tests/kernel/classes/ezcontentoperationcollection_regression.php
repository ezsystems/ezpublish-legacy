<?php
/**
 * File containing the eZContentOperationCollectionRegression class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZContentOperationCollectionRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZContentOperationCollection Regression Tests" );
    }

    public function setup()
    {
        parent::setup();
    }

    public function teardown()
    {
        parent::teardown();
    }

    /**
     * Test to verify that eznode_assignment entres get removed.
     *
     * @link http://issues.ez.no/15478
     */
    public function testRemoveAssignments()
    {
        $db = eZDB::instance();

        $testSet = array();

        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__;
        $folder->publish();
        $testSet[] = $folder;

        $childOne = new ezpObject( "folder", $folder->mainNode->node_id );
        $childOne->name = "ChildOne";
        $childOne->publish();
        $testSet[] = $childOne;

        $childTwo = new ezpObject( "folder", $folder->mainNode->node_id );
        $childTwo->name = "ChildTwo";
        $childTwo->publish();
        $testSet[] = $childTwo;

        $subChildOne = new ezpObject( 'article', $childOne->mainNode->node_id );
        $subChildOne->title = "SubChild";
        $subChildOne->publish();
        $testSet[] = $subChildOne;

        $subChildTwo = new ezpObject( 'article', $childOne->mainNode->node_id );
        $subChildTwo->title = "SubChildOther";
        $subChildTwo->publish();
        $testSet[] = $subChildTwo;

        $subChildThree = new ezpObject( 'article', $childOne->mainNode->node_id );
        $subChildThree->title = "SubChildThird";
        $subChildThree->publish();
        $testSet[] = $subChildThree;

        // Let's add another placement here
        $newPlacementSubChildOne = $subChildOne->addNode( $childTwo->mainNode->node_id );
        $testSet[] = $newPlacementSubChildOne;

        // $this->debugBasicNodeInfo( $testSet );

        $coId = $newPlacementSubChildOne->attribute( 'contentobject_id' );

        eZContentOperationCollection::removeAssignment( $childTwo->mainNode->node_id, $childTwo->id, array( $newPlacementSubChildOne ), false );

        $checkSql = "SELECT * FROM eznode_assignment WHERE contentobject_id = {$coId}";
        $result = $db->arrayQuery( $checkSql );

        // After removing one of the assignments, the number of node
        // assignments for the content object should be only one.
        $numberOfAssignments = count( $result );

        self::assertEquals( 1, $numberOfAssignments, "The number of node assignments are not correct, eznode_assignment table not cleaned up correctly." );
    }

    /**
     * Helper method to aid the development and test and verification of results.
     * 
     * The method will output information about the inputted objects and nodes
     *
     * @param mixed $testObjects (array=>ezpObject)
     * @return void
     */
    protected function debugBasicNodeInfo( $testObjects )
    {
        echo "\n";

        foreach ( $testObjects as $obj )
        {
            if ( $obj instanceof ezpObject )
            {
                printf( "%21s - [Node: %s] | [Object: %s]\n", $obj->name, $obj->mainNode->node_id, $obj->id );
            }
            else if ( $obj instanceof eZContentObjectTreeNode )
            {
                printf( "%21s - [Node: %s] | [Object: %s]\n", $obj->attribute( 'name' ), $obj->attribute( 'node_id' ), $obj->attribute( 'contentobject_id' ) );
            }
        }

        echo "\n";
    }
}
?>