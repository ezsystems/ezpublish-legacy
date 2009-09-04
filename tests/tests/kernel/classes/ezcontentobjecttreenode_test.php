<?php
/**
 * File containing the eZContentObjectRegression class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZContentObjectTreeNodeTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZContentObjectTreeNode Unit Tests" );
    }

    /**
     * Unit test for eZContentObjectTreeNodeTest::testFetchAliasesFromNodeList()
     *
     * Outline:
     * 1) Create a few objects/nodes
     * 2) Call the method with the list of these nodes' ids as a parameter
     * 3) Check that the returned value matches the provided parameter
     */
    public function testFetchAliasesFromNodeList()
    {
        $nodeArray = array();

        // 1) Create a few objects/nodes
        for( $i = 0; $i < 5; $i++ )
        {
            $object = new ezpObject( 'article', 2 );
            $object->title = "Object #{$i} for " . __FUNCTION__;
            $object->publish();

            $nodeID = $object->mainNode->attribute( 'node_id' );
            $nodeArray[ $nodeID ] = array(
                'node_id' => $nodeID,
                'path_identification_string' => $object->mainNode->attribute( 'path_identification_string' ),
            );
        }
        $nodeIDArray = array_keys( $nodeArray );

        // 2) Call the method with the above list
        $aliasList = eZContentObjectTreeNode::fetchAliasesFromNodeList( $nodeIDArray );
        $this->assertEquals( count( $nodeArray ), count( $aliasList ) );
        foreach( $aliasList as $alias )
        {
            $this->assertArrayHasKey( 'node_id', $alias );
            $this->assertArrayHasKey( 'path_identification_string', $alias );

            $nodeID = $alias['node_id'];
            $baseNodeData = $nodeArray[$nodeID];

            $this->assertEquals( $baseNodeData['node_id'], $alias['node_id'] );
            $this->assertEquals( $baseNodeData['path_identification_string'], $alias['path_identification_string'] );
        }
    }
}
?>