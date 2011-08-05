<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

function removeSelectedGroups( $http, &$groups, $base )
{
    if ( $http->hasPostVariable( "DeleteGroupButton" ) )
    {
        if ( eZHTTPPersistence::splitSelected( $base,
                                               $groups, $http, "id",
                                               $keepers, $rejects ) )
        {
            $groups = $keepers;
            foreach( $rejects as $reject )
            {
                $group_id = $reject->attribute("id");

                // Remove all workflows in current group
                $list_in_group = eZWorkflowGroupLink::fetchWorkflowList( 0, $group_id, $asObject = true);
                $workflow_list = eZWorkflow::fetchList( );

                $list = array();
                foreach( $workflow_list as $workflow )
                {
                    foreach( $list_in_group as $group )
                    {
                        $id = $workflow->attribute("id");
                        $workflow_id = $group->attribute("workflow_id");
                        if ( $id === $workflow_id )
                        {
                            $list[] = $workflow;
                        }
                    }
                }
                foreach ( $list as $workFlow )
                {
                  eZTrigger::removeTriggerForWorkflow( $workFlow->attribute( 'id' ) );
                  $workFlow->remove();
                }

                $reject->remove( );
                eZWorkflowGroupLink::removeGroupMembers( $group_id );
            }
        }
    }
}

$Module = $Params['Module'];

$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( "EditGroupButton" ) && $http->hasPostVariable( "EditGroupID" ) )
{
    $Module->redirectTo( $Module->functionURI( "groupedit" ) . "/" . $http->postVariable( "EditGroupID" ) );
    return;
}

if ( $http->hasPostVariable( "NewGroupButton" ) )
{
    $params = array();

    $Module->redirectTo( $Module->functionURI( "groupedit" ) );
    return;
}

$sorting = null;

if ( !isset( $TemplateData ) or !is_array( $TemplateData ) )
{
    $TemplateData = array( array( "name" => "groups",
                                  "http_base" => "ContentClass",
                                  "data" => array( "command" => "group_list",
                                                   "type" => "class" ) ) );
}

$Module->setTitle( ezpI18n::tr( 'kernel/workflow', 'Workflow group list' ) );
$tpl = eZTemplate::factory();

$user = eZUser::currentUser();
foreach( $TemplateData as $tpldata )
{
    $tplname = $tpldata["name"];
    $data = $tpldata["data"];
    $asObject = isset( $data["as_object"] ) ? $data["as_object"] : true;
    $base = $tpldata["http_base"];
    unset( $list );
    $list = eZWorkflowGroup::fetchList( $asObject );
    removeSelectedGroups( $http, $list, $base );
    $tpl->setVariable( $tplname, $list );
}

$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] = $tpl->fetch( "design:workflow/grouplist.tpl" );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/workflow', 'Workflow' ),
                                'url' => false ),
                         array( 'text' => ezpI18n::tr( 'kernel/workflow', 'Group list' ),
                                'url' => false ) );


?>
