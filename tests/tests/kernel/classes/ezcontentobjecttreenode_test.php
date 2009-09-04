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
     * Unit test for eZContentObjectTreeNode::fetchAliasesFromNodeList()
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

    /**
     * Unit test for eZContentObjectTreeNode::fetchMainNodeArray
     *
     * Outline:
     * 1) Call the function with an empty array and check that the output is null
     * 2) Create a few objects/nodes
     * 3) Call the method with these objects' IDs as a parameter
     * 3) Check that the returned values are eZContentObjectTreeNodes, and match
     *    the parameter list
     *
     * @todo Also test with $asObject = false
     **/
    public function testFindMainNodeArray()
    {
        // 1) Check that the method returns null on an empty array
        $this->assertNull( eZContentObjectTreeNode::findMainNodeArray( array() ) );

        // 2) Create a few objects/nodes
        $objectIDArray = $objectsArray = $nodesIDArray = array();
        for( $i = 0; $i < 5; $i++ )
        {
            $object = new ezpObject( 'article', 2 );
            $object->title = "Test object #{$i} for " . __FUNCTION__;
            $object->publish();

            $objectID = $object->attribute( 'id' );
            $objectsArray[$objectID] = $object;
        }
        $objectsIDArray = array_keys( $objectsArray );

        // 2) Call the method
        $mainNodeArray = eZContentObjectTreeNode::findMainNodeArray( $objectsIDArray );

        // 3) Check the result
        $this->assertEquals( count( $objectsArray ), count( $mainNodeArray ),
             "Return value count doesn't matche parameter count" );
        foreach( $mainNodeArray as $mainNode )
        {
            $mainNodeContentObjectID = $mainNode->attribute( 'contentobject_id' );

            $this->assertType( 'eZContentObjectTreeNode', $mainNode );
            $this->assertTrue( in_array( $mainNodeContentObjectID, $objectsIDArray ),
                "A returned node's contentobject_id isn't part of the original parameters" );
        }
    }
}
?>