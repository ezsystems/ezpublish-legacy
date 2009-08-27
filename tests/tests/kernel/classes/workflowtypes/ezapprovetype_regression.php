<?php
/**
 * File containing the eZApproveTypeRegression class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZApproveTypeRegression extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZApproveType Regression Tests" );
    }

    public function setUp()
    {
        parent::setUp();

        $adminObjectID = eZUser::fetchByName( 'admin' )->attribute( 'contentobject_id' );

        // Create approval workflow and set up pre publish trigger
        $this->workflow = $this->createApprovalWorkFlow( $adminObjectID );
        $this->trigger = $this->createTrigger( $this->workflow->attribute( 'id' ) );

        // Log in as a user who needs approval.
        $this->currentUser = eZUser::currentUser();
        $anonymous = eZUser::fetchByName( 'anonymous' );
        eZUser::setCurrentlyLoggedInUser( $anonymous, $anonymous->attribute( 'contentobject_id' ) );
    }

    public function tearDown()
    {
        // Remove trigger
        eZTrigger::removeTriggerForWorkflow( $this->workflow->attribute( 'id' ) );

        // Log in as whoever was logged in
        eZUser::setCurrentlyLoggedInUser( $this->currentUser, $this->currentUser->attribute( 'id' ) );

        // Important that the parent method is run AFTER changes are done to the database.
        parent::tearDown();

    }

    /**
     * Test regression for issue #13952: Workflow cronjob gives fatal error if
     * node is moved to different location before approval.
     *
     * Test Outline
     * ------------
     * 1. Create a folder
     * 2. Approve folder
     * 3. Create child of folder
     * 4. Approve child
     * 5. Create a new version and re-publish the child
     * 6. Move child to root
     * 7. Approve child 
     * 8. Run approval cronjob
     *
     * @result: Fatal error: Call to a member function attribute() on a non-object in 
     *          /www/trunk/kernel/content/ezcontentoperationcollection.php on line 313
     * @expected: No fatal error 
     * @link http://issues.ez.no/13952
     */
    public function testApprovalFatalErrorWhenMoving()
    {
        $anonymousObjectID = eZUser::fetchByName( 'anonymous' )->attribute( 'contentobject_id' );

        // STEP 1: Create a folder
        $folder = new ezpObject( "folder", 2, $anonymousObjectID );
        $folder->name = "Parent folder (needs approval)";
        $folder->publish();

        // STEP 2: Approve folder
        $collaborationItem = eZCollaborationItem::fetch( 1 );
        $this->approveCollaborationItem( $collaborationItem );
        $this->runWorkflow();

        // STEP 3: Create child of folder
        $child = new ezpObject( "folder", $folder->mainNode->node_id, $anonymousObjectID );
        $child->name = "Child folder (needs approval)";
        $child->publish();

        // STEP 4: Approve child
        $collaborationItem = eZCollaborationItem::fetch( 2 );
        $this->approveCollaborationItem( $collaborationItem );
        $this->runWorkflow();

        // STEP 5: Re-publish child
        $newVersion = $child->createNewVersion();
        ezpObject::publishContentObject( $child->object, $newVersion );

        // STEP 6: Move child to root
        $child->mainNode->move( 2 );

        // STEP 7: Approve child again
        $collaborationItem = eZCollaborationItem::fetch( 3 );
        $this->approveCollaborationItem( $collaborationItem );

        // STEP 8: Run approval cronjob
        $this->runWorkflow();
    }

    function createApprovalWorkFlow( $approvalUserID )
    {
        $workflow = eZWorkflow::create( $approvalUserID );
        $workflow->setAttribute( "name", "eZApproveTypeRegression Workflow" );
        $workflow->store();

        $workflowID = $workflow->attribute( "id" );
        $workflowVersion = $workflow->attribute( "version" );

        $groupID = 1;
        $groupName = "Standard";
        $ingroup = eZWorkflowGroupLink::create( $workflowID, $workflowVersion, $groupID, $groupName );
        $ingroup->store();

        $approveEvent = eZWorkflowEvent::create( $workflowID, "event_ezapprove" );
        $approveEventType = $approveEvent->eventType();

        $approveEventType->initializeEvent( $approveEvent );

        // Affected sections, -1 == All sections
        $approveEvent->setAttribute( "data_text1", "-1" );
        // Affected languages, 0 == All languages
        $approveEvent->setAttribute( "data_int2", 0 );
        // Affected versions
        $approveEvent->setAttribute( "data_int3", eZApproveType::VERSION_OPTION_ALL );
        // Editors
        $approveEvent->setAttribute( "data_text3", $approvalUserID );

        $approveEvent->store();
        $eventList = array( $approveEvent );

        $workflow->store( $eventList );

        eZWorkflowGroupLink::removeWorkflowMembers( $workflowID, 0 );
        $workflowgroups = eZWorkflowGroupLink::fetchGroupList( $workflowID, 1 );
        foreach( $workflowgroups as $workflowgroup )
        {
            $workflowgroup->setAttribute("workflow_version", 0 );
            $workflowgroup->store();
        }

        // Remove version 1
        eZWorkflowGroupLink::removeWorkflowMembers( $workflowID, 1 );
        eZWorkflow::removeEvents( false, $workflowID, 0 );

        $workflow->setVersion( 0, $eventList );
        $workflow->adjustEventPlacements( $eventList );
        $workflow->storeDefined( $eventList );
        $workflow->cleanupWorkFlowProcess();
        $workflow->store( $eventList );

        return $workflow;
    }

    function createTrigger( $workflowID )
    {
        return eZTrigger::createNew( 'content', 'publish', 'b', $workflowID );
    }

    function approveCollaborationItem( $collaborationItem )
    {
        $collaborationItem->setAttribute( 'data_int3', eZApproveCollaborationHandler::STATUS_ACCEPTED );
        $collaborationItem->setAttribute( 'status', eZCollaborationItem::STATUS_INACTIVE );
        $collaborationItem->setAttribute( 'modified', time() );
        $collaborationItem->setIsActive( false );
        $collaborationItem->sync();
    }

    function runWorkflow()
    {
        $workflowProcessList = eZWorkflowProcess::fetchForStatus( eZWorkflow::STATUS_DEFERRED_TO_CRON );

        foreach( $workflowProcessList as $process )
        {
            $workflow = eZWorkflow::fetch( $process->attribute( "workflow_id" ) );

            if ( $process->attribute( "event_id" ) != 0 )
                $workflowEvent = eZWorkflowEvent::fetch( $process->attribute( "event_id" ) );

            $process->run( $workflow, $workflowEvent, $eventLog );
            // Store changes to process

            if ( $process->attribute( 'status' ) != eZWorkflow::STATUS_DONE )
            {
                if ( $process->attribute( 'status' ) == eZWorkflow::STATUS_RESET ||
                $process->attribute( 'status' ) == eZWorkflow::STATUS_FAILED ||
                $process->attribute( 'status' ) == eZWorkflow::STATUS_NONE ||
                $process->attribute( 'status' ) == eZWorkflow::STATUS_CANCELLED ||
                $process->attribute( 'status' ) == eZWorkflow::STATUS_BUSY )
                {
                    $bodyMemento = eZOperationMemento::fetchMain( $process->attribute( 'memento_key' ) );
                    $mementoList = eZOperationMemento::fetchList( $process->attribute( 'memento_key' ) );
                    $bodyMemento->remove();
                    foreach( $mementoList as $memento )
                    {
                        $memento->remove();
                    }
                }

                if ( $process->attribute( 'status' ) == eZWorkflow::STATUS_CANCELLED )
                {
                    $process->removeThis();
                }
                else
                {
                    $process->store();
                }
            }
            else
            {   //restore memento and run it
                $bodyMemento = eZOperationMemento::fetchChild( $process->attribute( 'memento_key' ) );
                if ( is_null( $bodyMemento ) )
                {
                    eZDebug::writeError( $bodyMemento, "Empty body memento in workflow.php" );
                    continue;
                }
                $bodyMementoData = $bodyMemento->data();
                $mainMemento = $bodyMemento->attribute( 'main_memento' );
                if ( !$mainMemento )
                    continue;

                $mementoData = $bodyMemento->data();
                $mainMementoData = $mainMemento->data();
                $mementoData['main_memento'] = $mainMemento;
                $mementoData['skip_trigger'] = true;
                $mementoData['memento_key'] = $process->attribute( 'memento_key' );
                $bodyMemento->remove();
                $operationParameters = array();
                if ( isset( $mementoData['parameters'] ) )
                    $operationParameters = $mementoData['parameters'];

                $operationResult = eZOperationHandler::execute( $mementoData['module_name'], $mementoData['operation_name'], $operationParameters, $mementoData );
                $process->removeThis();
            }
        }
    }
}

?>
