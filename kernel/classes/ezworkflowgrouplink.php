<?php
//
// Definition of eZContentClass class
//
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

//!! eZKernel
//! The class eZWorkflowGroupLink
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezworkflowgroup.php" );

class eZWorkflowGroupLink extends eZPersistentObject
{
    function eZWorkflowGroupLink( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "workflow_id" => array( 'name' => "WorkflowID",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         "workflow_version" => array( 'name' => "WorkflowVersion",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         "group_id" => array( 'name' => "GroupID",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         "group_name" => array( 'name' => "GroupName",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ) ),
                      "keys" => array( "workflow_id", "workflow_version", "group_id" ),
                      "class_name" => "eZWorkflowGroupLink",
                      "sort" => array( "workflow_id" => "asc" ),
                      "name" => "ezworkflow_group_link" );
    }

    function &create( $workflow_id, $workflow_version, $group_id, $group_name )
    {
        $row = array("workflow_id" => $workflow_id,
                     "workflow_version" => $workflow_version,
                     "group_id" => $group_id,
                     "group_name" => $group_name);
        return new eZWorkflowGroupLink( $row );
    }

    function &remove( $workflow_id, $workflow_version, $group_id )
    {
        eZPersistentObject::removeObject( eZWorkflowGroupLink::definition(),
                                          array("workflow_id" => $workflow_id,
                                                "workflow_version" =>$workflow_version,
                                                "group_id" => $group_id ) );
    }

    function &removeGroupMembers( $group_id )
    {
        eZPersistentObject::removeObject( eZWorkflowGroupLink::definition(),
                                          array( "group_id" => $group_id ) );
    }

    function &removeWorkflowMembers( $workflow_id, $workflow_version )
    {
        eZPersistentObject::removeObject( eZWorkflowGroupLink::definition(),
                                          array( "workflow_id" =>$workflow_id,
                                                 "workflow_version" =>$workflow_version ) );
    }

    function &fetch( $workflow_id, $workflow_version, $group_id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZWorkflowGroupLink::definition(),
                                                null,
                                                array("workflow_id" => $workflow_id,
                                                      "workflow_version" =>$workflow_version,
                                                      "group_id" => $group_id ),
                                                $asObject );
    }

    function &fetchWorkflowList( $workflow_version, $group_id, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowGroupLink::definition(),
                                                    null,
                                                    array( "workflow_version" =>$workflow_version,
                                                           "group_id" => $group_id ),
                                                    null,
                                                    null,
                                                    $asObject);
    }

    function &fetchGroupList( $workflow_id, $workflow_version, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowGroupLink::definition(),
                                                    null,
                                                    array( "workflow_id" => $workflow_id,
                                                           "workflow_version" =>$workflow_version ),
                                                    null,
                                                    null,
                                                    $asObject);
    }

    /// \privatesection
    var $WorkflowID;
    var $WorkflowVersion;
    var $GroupID;
    var $GroupName;
}

?>
