<?php
//
// Created on: <20-Sep-2004 15:11:32 jk>
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
include_once( "kernel/classes/ezworkflowgrouplink.php" );
include_once( "kernel/common/template.php" );

$Module =& $Params["Module"];

$WorkflowID = $Params["WorkflowID"];
if ( !is_int( $WorkflowID ) )
    $Module->handleError( EZ_ERROR_KERNEL_NOT_FOUND, 'kernel' );

$workflow =& eZWorkflow::fetch( $WorkflowID );
if ( !$workflow )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

$event_list =& $workflow->fetchEvents();

$tpl =& templateInit();
$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( "workflow", $workflow->attribute( "id" ) ) ) );

$tpl->setVariable( "workflow", $workflow );
$tpl->setVariable( "event_list", $event_list );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:workflow/view.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/workflow', 'Workflow' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/workflow', 'View' ),
                                'url' => false ) );

?>
