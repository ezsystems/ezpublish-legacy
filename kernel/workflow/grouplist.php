<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "kernel/classes/ezworkflow.php" );
include_once( "kernel/classes/eztrigger.php" );
include_once( "kernel/classes/ezworkflowgroup.php" );
include_once( "kernel/classes/ezworkflowgrouplink.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

function &removeSelectedGroups( &$http, &$groups, $base )
{
    if ( $http->hasPostVariable( "DeleteGroupButton" ) )
    {
        if ( eZHttpPersistence::splitSelected( $base,
                                               $groups, $http, "id",
                                               $keepers, $rejects ) )
        {
            $groups = $keepers;
            for ( $i = 0; $i < count( $rejects ); ++$i )
            {
                $reject =& $rejects[$i];
                $group_id = $reject->attribute("id");

                // Remove all workflows in current group
                $list_in_group = & eZWorkflowGroupLink::fetchWorkflowList( 0, $group_id, $asObject = true);
                $workflow_list = & eZWorkflow::fetchList( );

                $list = array();
                for ( $k=0; $k<count( $workflow_list ); $k++ )
                {
                    for ( $j=0;$j<count( $list_in_group );$j++ )
                    {
                        $id =  $workflow_list[$k]->attribute("id");
                        $workflow_id =  $list_in_group[$j]->attribute("workflow_id");
                        if ( $id === $workflow_id )
                        {
                            $list[] =& $workflow_list[$k];
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

$Module =& $Params["Module"];

$http =& eZHttpTool::instance();

if ( $http->hasPostVariable( "EditGroupButton" ) && $http->hasPostVariable( "EditGroupID" ) )
{
    $Module->redirectTo( $Module->functionURI( "groupedit" ) . "/" . $http->postVariable( "EditGroupID" ) );
    return;
}

if ( $http->hasPostVariable( "NewGroupButton" ) )
{
    $params = array();
//    $Module->run( "groupedit", $params );

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

$Module->setTitle( ezi18n( 'kernel/workflow', 'Workflow group list' ) );
include_once( "kernel/common/template.php" );
$tpl =& templateInit();

include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
$user =& eZUser::currentUser();
foreach( $TemplateData as $tpldata )
{
    $tplname = $tpldata["name"];
    $data = $tpldata["data"];
    $asObject = isset( $data["as_object"] ) ? $data["as_object"] : true;
    $base = $tpldata["http_base"];
    unset( $list );
    $list =& eZWorkflowGroup::fetchList( $asObject );
    removeSelectedGroups( $http, $list, $base );
    $tpl->setVariable( $tplname, $list );
}

$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:workflow/grouplist.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/workflow', 'Workflow' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/workflow', 'Group list' ),
                                'url' => false ) );


?>
