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


include_once( 'kernel/classes/ezworkflow.php' );
include_once( 'kernel/classes/ezworkflowgroup.php' );
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );

$Module =& $Params['Module'];

// include_once( 'lib/ezutils/classes/ezexecutionstack.php' );
// $execStack =& eZExecutionStack::instance();
// $execStack->clear();
// $execStack->addEntry( $Module->functionURI( 'list' ),
//                       $Module->attribute( 'name' ), 'list' );

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( 'NewGroupButton' ) )
    return $Module->run( 'groupedit', array() );
if ( $http->hasPostVariable( 'NewWorkflowButton' ) )
    return $Module->run( 'edit', array() );

if ( $http->hasPostVariable( 'DeleteButton' ) and
     $http->hasPostVariable( 'Workflow_id_checked' ) )
{
    eZWorkflow::setIsEnabled( false, $http->postVariable( 'Workflow_id_checked' ) );
}

$groupList =& eZWorkflowGroup::fetchList();
$workflows =& eZWorkflow::fetchList();
$workflowList = array();
foreach( array_keys( $workflows ) as $workflowID )
{
    $workflow =& $workflows[$workflowID];
    $workflowList[$workflow->attribute( 'id' )] =& $workflow;
}

$Module->setTitle( 'Workflow list' );

include_once( 'kernel/common/template.php' );
$tpl =& templateInit();

$tpl->setVariable( 'workflow_list', $workflowList );
$tpl->setVariable( 'group_list', $groupList );
$tpl->setVariable( 'module', $Module );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:workflow/list.tpl' );
$Result['path'] = array( array( 'url' => '/workflow/list/',
                                'text' => ezi18n( 'kernel/workflow', 'Workflow list' ) ) );

?>
