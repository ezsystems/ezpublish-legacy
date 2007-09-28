<?php
//
// Definition of eZWorkflowFunctionCollection class
//
// Created on: <06-Oct-2006 16:00:00 rl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezworkflowfunctioncollection.php
*/

/*!
  \class eZWorkflowFunctionCollection ezworkflowfunctioncollection.php
  \brief The class eZWorkflowFunctionCollection does

*/

////include_once( 'kernel/error/errors.php' );

class eZWorkflowFunctionCollection
{
    /*!
     Constructor
    */
    function eZWorkflowFunctionCollection()
    {
    }


    function fetchWorkflowStatuses()
    {
        //include_once( 'kernel/classes/ezworkflow.php' );
        if ( empty( $GLOBALS['eZWorkflowStatusNames'] ) )
        {
            $GLOBALS['eZWorkflowStatusNames'] =
                array( eZWorkflow::STATUS_NONE => ezi18n( 'kernel/classes', 'No state yet' ),
                       eZWorkflow::STATUS_BUSY => ezi18n( 'kernel/classes', 'Workflow running' ),
                       eZWorkflow::STATUS_DONE => ezi18n( 'kernel/classes', 'Workflow done' ),
                       eZWorkflow::STATUS_FAILED => ezi18n( 'kernel/classes', 'Workflow failed an event' ),
                       eZWorkflow::STATUS_DEFERRED_TO_CRON => ezi18n( 'kernel/classes', 'Workflow event deferred to cron job' ),
                       eZWorkflow::STATUS_CANCELLED => ezi18n( 'kernel/classes', 'Workflow was canceled' ),
                       eZWorkflow::STATUS_FETCH_TEMPLATE => ezi18n( 'kernel/classes', 'Workflow fetches template' ),
                       eZWorkflow::STATUS_REDIRECT => ezi18n( 'kernel/classes', 'Workflow redirects user view' ),
                       eZWorkflow::STATUS_RESET => ezi18n( 'kernel/classes', 'Workflow was reset for reuse' ) );
        }
        return array( 'result' => $GLOBALS['eZWorkflowStatusNames'] );
    }

    function fetchWorkflowTypeStatuses()
    {
        //include_once( 'kernel/classes/ezworkflowtype.php' );
        if ( empty( $GLOBALS['eZWorkflowTypeStatusNames'] ) )
        {
            $GLOBALS['eZWorkflowTypeStatusNames'] =
                array( eZWorkflowType::STATUS_NONE => ezi18n( 'kernel/classes', 'No state yet' ),
                       eZWorkflowType::STATUS_ACCEPTED => ezi18n( 'kernel/classes', 'Accepted event' ),
                       eZWorkflowType::STATUS_REJECTED => ezi18n( 'kernel/classes', 'Rejected event' ),
                       eZWorkflowType::STATUS_DEFERRED_TO_CRON => ezi18n( 'kernel/classes', 'Event deferred to cron job' ),
                       eZWorkflowType::STATUS_DEFERRED_TO_CRON_REPEAT => ezi18n( 'kernel/classes', 'Event deferred to cron job, event will be rerun' ),
                       eZWorkflowType::STATUS_RUN_SUB_EVENT => ezi18n( 'kernel/classes', 'Event runs a sub event' ),
                       eZWorkflowType::STATUS_WORKFLOW_CANCELLED => ezi18n( 'kernel/classes', 'Canceled whole workflow' ),
                       eZWorkflowType::STATUS_WORKFLOW_RESET => ezi18n( 'kernel/classes', 'Workflow was reset for reuse' ) );
        }
        return array( 'result' => $GLOBALS['eZWorkflowTypeStatusNames'] );
    }

}

?>
