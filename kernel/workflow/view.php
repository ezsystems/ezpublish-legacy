<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$http = eZHTTPTool::instance();
$validation = array( 'processed' => false,
                     'groups' => array() );

$WorkflowID = $Params["WorkflowID"];
$WorkflowID = (int) $WorkflowID;
if ( !is_int( $WorkflowID ) )
    $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );

$workflow = eZWorkflow::fetch( $WorkflowID );
if ( !$workflow )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( $http->hasPostVariable( "AddGroupButton" ) && $http->hasPostVariable( "Workflow_group") )
{
    $selectedGroup = $http->postVariable( "Workflow_group" );
    eZWorkflowFunctions::addGroup( $WorkflowID, 0, $selectedGroup );
}
if ( $http->hasPostVariable( "DeleteGroupButton" ) && $http->hasPostVariable( "group_id_checked" ) )
{
    $selectedGroup = $http->postVariable( "group_id_checked" );
    if ( !eZWorkflowFunctions::removeGroup( $WorkflowID, 0, $selectedGroup ) )
    {
        $validation['groups'][] = array( 'text' => ezpI18n::tr( 'kernel/workflow', 'You have to have at least one group that the workflow belongs to!' ) );
        $validation['processed'] = true;
    }
}

$event_list = $workflow->fetchEvents();

$tpl = eZTemplate::factory();
$res = eZTemplateDesignResource::instance();
$res->setKeys( array( array( "workflow", $workflow->attribute( "id" ) ) ) );

$tpl->setVariable( "workflow", $workflow );
$tpl->setVariable( "event_list", $event_list );
$tpl->setVariable( 'validation', $validation );

$Result = array();
$Result['content'] = $tpl->fetch( "design:workflow/view.tpl" );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/workflow', 'Workflow' ),
                                'url' => false ),
                         array( 'text' => ezpI18n::tr( 'kernel/workflow', 'View' ),
                                'url' => false ) );

?>
