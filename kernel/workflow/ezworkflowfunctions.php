<?php
//
// Created on: <27-Sep-2004 11:41:73 jk>
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

/*! \file ezworkflowfunctions.php
*/

class eZWorkflowFunctions
{
    function addGroup( $workflowID, $workflowVersion, $selectedGroup )
    {
        include_once( "kernel/classes/ezworkflowgrouplink.php" );

        list ( $groupID, $groupName ) = split( "/", $selectedGroup );
        $ingroup =& eZWorkflowGroupLink::create( $workflowID, $workflowVersion, $groupID, $groupName );
        $ingroup->store();
        return true;
    }

    function removeGroup( $workflowID, $workflowVersion, $selectedGroup )
    {
        include_once( "kernel/classes/ezworkflow.php" );
        include_once( "kernel/classes/ezworkflowgrouplink.php" );

        $workflow =& eZWorkflow::fetch( $workflowID );
        if ( !$workflow )
            return false;
        $groups = $workflow->attribute( 'ingroup_list' );
        foreach ( array_keys( $groups ) as $key )
        {
            if ( in_array( $groups[$key]->attribute( 'group_id' ), $selectedGroup ) )
            {
                unset( $groups[$key] );
            }
        }

        if ( count( $groups ) == 0 )
        {
            return false;
        }
        else
        {
            foreach(  $selectedGroup as $group_id )
            {
                eZWorkflowGroupLink::remove( $workflowID, $workflowVersion, $group_id );
            }
        }
        return true;
    }
}

?>
