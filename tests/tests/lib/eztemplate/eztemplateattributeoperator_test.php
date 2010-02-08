<?php
/**
 * File containing tests for eZTemplateAttributeOperator
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 *
 */

class eZTemplateAttributeOperatorTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZTemplateAttributeOperator Unit Tests" );
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Tests that the output process works with objects.
     * 
     * There should be no crash from casting errors.
     *
     */
    public function testDisplayVariableWithObject()
    {
        $db = eZDB::instance();

        // STEP 1: Add test folder
        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__;
        $folder->publish();

        $nodeId = $folder->mainNode->node_id;

        $node = eZContentObjectTreeNode::fetch( $nodeId );
        $attrOp = new eZTemplateAttributeOperator();
        $outputTxt = '';

        try
        {
            $attrOp->displayVariable( $node, false, true, 2, 0, $outputTxt );
        }
        catch ( PHPUnit_Framework_Error $e )
        {
            self::fail( "eZTemplateAttributeOperator raises an error when working with objects." );
        }

        self::assertNotNull( $outputTxt, "Output text is empty." );
        // this is an approxmiate test. The output shoudl contain the name of the object it has been generated correctly.
        self::assertContains( __FUNCTION__, $outputTxt, "There is something wrong with the output of the attribute operator. Object name not found." );
    }
}
?>