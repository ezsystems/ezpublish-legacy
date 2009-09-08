<?php
/**
 * File containing the eZWorkflowEventRegression class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZWorkflowEventRegression extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZWorkflowEvent Regression Tests" );

        ini_set( 'xdebug.show_exception_trace', 'Off' );
    }

    /**
     * Test regression for issue #14371:
     * Workflow template repeat broken by security patch.
     *
     * Test Outline
     * ------------
     * 1. Setup a workflow that features a custom workflow event that expects a
     *    value to be submitted before
     * 2. Create & publish an article
     * 3. Add a global POST variable that would be sent interactively from POST
     * 4. Publish again with this variable
     *
     * @result: Redirection to content/history
     * @expected: The object gets published without being redirected
     * @link http://issues.ez.no/14371
     **/
    public function testEditAfterFetchTemplateRepeat()
    {
        // first, we need to create an appropriate test workflow
        $adminUser = eZUser::fetchByName( 'admin' );
        $adminUserID = $adminUser->attribute( 'contentobject_id' );

        // Create approval workflow and set up pre publish trigger
        $this->workflow = $this->createWorkFlow( $adminUserID );
        $this->trigger = $this->createTrigger( $this->workflow->attribute( 'id' ) );

        // Log in as a user who's allowed to publish content
        $this->currentUser = eZUser::currentUser();
        eZUser::setCurrentlyLoggedInUser( $adminUser, $adminUserID );

        // required to avoid a notice
        $GLOBALS['eZSiteBasics']['user-object-required'] = false;

        $contentModule = eZModule::findModule( 'content' );

        $adminUserID = eZUser::fetchByName( 'admin' )->attribute( 'contentobject_id' );

        // STEP 1: Create an article
        // This should start the publishing process, and interrupt it because
        // of the fetch template repeat workflow (expected)
        $article = new ezpObject( "article", 2, $adminUserID );
        $article->name = "Article (with interactive workflow) for issue/regression #14371";
        $objectID = $article->publish();
        $version = eZContentObjectVersion::fetchVersion( 1, $objectID );

        // STEP 2: Add the POST variables
        $_POST['PublishButton'] = 1;
        $_POST['HasObjectInput'] = 1;
        $_POST['CompletePublishing'] = 1;

        // These are required by attribute_edit.php
        $_SERVER['CONTENT_LENGTH'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // STEP 3: run content/edit again in order to simulate a POST from the custom TPL
        $result = $contentModule->run( 'edit', array(
            $objectID, $EditVersion = 1,
            $EditLanguage = eZINI::instance('site.ini')->variable( 'RegionalSettings', 'ContentObjectLocale' ) )
        );

        // Before this fix, a redirection to content/history was performed
        $this->assertNotEquals( $contentModule->ExitStatus, eZModule::STATUS_REDIRECT, "A redirection was performed by content/edit" );

        // Remove trigger
        eZTrigger::removeTriggerForWorkflow( $this->workflow->attribute( 'id' ) );

        // Log in as whoever was logged in
        eZUser::setCurrentlyLoggedInUser( $this->currentUser, $this->currentUser->attribute( 'id' ) );
    }

    /**
    * Creates the test workflow.
    * @todo Currently only handles the fetchtemplaterepeat event. Will have to be
    *       refactored to handle more events when necessary
    **/
    function createWorkFlow( $adminUserID )
    {
        $this->registerCustomWorkflowEvent(
            'fetchtemplaterepeat',
            'tests/toolkit/extras/tests-files/workflowevent_regression_fetchtemplaterepeat.php' );

        $workflow = eZWorkflow::create( $adminUserID );
        $workflow->setAttribute( "name", "eZWorkflowEventRegression Workflow" );
        $workflow->store();

        $workflowID = $workflow->attribute( "id" );
        $workflowVersion = $workflow->attribute( "version" );

        $groupID = 1;
        $groupName = "Standard";
        $ingroup = eZWorkflowGroupLink::create( $workflowID, $workflowVersion, $groupID, $groupName );
        $ingroup->store();

        $regressionEvent = eZWorkflowEvent::create( $workflowID, "event_fetchtemplaterepeat" );
        $regressionEventType = $regressionEvent->eventType();
        $regressionEventType->initializeEvent( $regressionEvent );
        $regressionEvent->store();

        $eventList = array( $regressionEvent );

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

    /**
    * Connects a workflow to a trigger
    * @param int $workflowID
    * @todo Currently registers a content/publish/before event. Refactor when required
    * @return void
    **/
    function createTrigger( $workflowID )
    {
        // @todo Also test with before publish
        return eZTrigger::createNew( 'content', 'publish', 'b', $workflowID );
    }

    /**
     * Helper method that registers a custom workflow event without an extension
     *
     * @param string $workflowTypeString The event type string, withtout event_
     * @param mixed $includeFile Relative path to the workflow definition (class) file
     * @return bool false if an error occurs
     */
    function registerCustomWorkflowEvent( $workflowTypeString, $includeFile )
    {
        $wfINI = eZINI::instance( 'workflow.ini' );
        $availableEventTypesSetting = $wfINI->variable( 'EventSettings', 'AvailableEventTypes' );
        $availableEventTypesSetting[] = "event_{$workflowTypeString}";
        $wfINI->setVariable( 'EventSettings', 'AvailableEventTypes', $availableEventTypesSetting );
        if ( file_exists( $includeFile ) )
        {
            include( $includeFile );
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @var eZWorkflow
     **/
    private $workflow;

    /**ti
     * @var eZTrigger
     **/
    private $trigger;

    /**
    * Currently logged in user backup
    * @var eZUser
    **/
    private $currentUser;
}

?>