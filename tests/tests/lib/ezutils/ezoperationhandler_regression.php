<?php
/**
 * File containing the eZOperationHandlerRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZOperationHandlerRegression extends ezpTestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    /**
     * Test scenario for issue #15883: Enable only before (or after) operation doesn't work (patch)
     *
     * Test outline
     * -------------
     * 1. Enable a partial operation in workflow.ini (before_content_read)
     * 2. Call eZOperationHandler::isOperationAvailable( 'content_read' )
     *
     * @result: false
     * @expected: true
     * @link http://issues.ez.no/15883
     * @group issue_15883
     */
    function testEnablingPartialOperation()
    {
        $wfINI = eZINI::instance( 'workflow.ini' );

        // before
        $wfINI->setVariable( 'OperationSettings', 'AvailableOperationList', array( 'before_content_read' ) );
        $this->assertTrue( eZOperationHandler::operationIsAvailable( 'content_read' ) );

        // after
        $wfINI->setVariable( 'OperationSettings', 'AvailableOperationList', array( 'after_content_read' ) );
        $this->assertTrue( eZOperationHandler::operationIsAvailable( 'content_read' ) );

        // complete
        $wfINI->setVariable( 'OperationSettings', 'AvailableOperationList', array( 'content_read' ) );
        $this->assertTrue( eZOperationHandler::operationIsAvailable( 'content_read' ) );

        // unknown one
        $this->assertFalse( eZOperationHandler::operationIsAvailable( 'foo_bar' ) );
    }
}

?>
