<?php
//
// Definition of eZMultiplexerType class
//
// Created on: <01-Ноя-2002 15:34:23 sp>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezmultiplexertype.php
*/

/*!
  \class eZMultiplexerType ezmultiplexertype.php
  \brief The class eZMultiplexerType does

*/
define( "EZ_WORKFLOW_TYPE_MULTIPLEXER_ID", "ezmultiplexer" );

class eZMultiplexerType extends eZWorkflowEventType
{
    /*!
     Constructor
    */
    function eZMultiplexerType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_MULTIPLEXER_ID, 'Multiplexer' );
    }

    function execute( &$process, &$event )
    {
//        var_dump( $process );
        $processParameters = $process->attribute( 'parameter_list' );
//        var_dump( $processParameters );
        $nodeID = $processParameters['node_id'];
        $node = & eZContentObjectTreeNode::fetch( $nodeID );
        
        $objectID = $node->attribute( 'contentobject_id' );
        $object =& $node->attribute( 'object'); 
        $userArray = explode( ',', $event->attribute( 'data_text2' ) );
///        $user =& eZUser::currentUser();
        $userID = $processParameters['user_id'];
        if ( ! in_array( $userID, $userArray ) )
        {
            $sectionArray = explode( ',', $event->attribute( 'data_text1' ) );
            if ( in_array( $object->attribute( 'section_id' ), $sectionArray ) )
            {
                $sessionKey = $processParameters['session_key'];
                $workflowToRun = $event->attribute( 'data_int1' );
                $workflowToRun = 10;

                $childParameters = array( 'workflow_id' => $workflowToRun,
                                          'user_id' => $userID,
                                          'contentobject_id' => $objectID,
                                          'node_id' => $processParameters['node_id'],
                                          'session_key' => $sessionKey
                                          );
                $childProcessKey = eZWorkflowProcess::createKey( $childParameters );
                
                $childProcessArray =& eZWorkflowProcess::fetchListByKey( $childProcessKey );
                //              var_dump( $childProcessArray );
                $childProcess =& $childProcessArray[0];
                if ( $childProcess == null )
                {
                    $childProcess =& eZWorkflowProcess::create( $childProcessKey, $childParameters );
                    $childProcess->store();

                }

                $workflow =& eZWorkflow::fetch( $childProcess->attribute( "workflow_id" ) );
                $workflowEvent = null;

                if ( $childProcess->attribute( "event_id" ) != 0 )
                    $workflowEvent =& eZWorkflowEvent::fetch( $childProcess->attribute( "event_id" ) );

                $childStatus = $childProcess->run( $workflow, $workflowEvent, $eventLog );
                $childProcess->store();

                eZDebug::writeNotice( $childProcess, "childProcess" );
                eZDebug::writeNotice( $childStatus, "childStatus" );
                
                if ( $childStatus ==  EZ_WORKFLOW_STATUS_FETCH_TEMPLATE )
                {

//                    $this->setAttribute( 'status', EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE );
                    $process->Template =& $childProcess->Template;
                    return EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT;
                }
                else if ( $childStatus ==  EZ_WORKFLOW_STATUS_REDIRECT )
                {
                    $process->RedirectUrl =& $childProcess->RedirectUrl;
                    return EZ_WORKFLOW_TYPE_STATUS_REDIRECT_REPEAT;
                }
                else if ( $childStatus ==  EZ_WORKFLOW_STATUS_DONE  )
                {
                    $childProcess->remove();
                    return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
                }
                else if ( $childStatus == EZ_WORKFLOW_STATUS_CANCELLED || $childStatus == EZ_WORKFLOW_STATUS_FAILED )
                {
                    $childProcess->remove();
                    return EZ_WORKFLOW_TYPE_STATUS_REJECTED;
                }
                return $childProcess->attribute( 'last_event_status' );

            }
        }
        return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;

    }

    function initializeEvent( &$event )
    {
    }


    function fetchHTTPInput( &$http, $base, &$event )
    {
        $sectionsVar = $base . "_event_ezmultiplexer_section_ids_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $sectionsVar ) )
        {
            $sectionsID = $http->postVariable( $sectionsVar );
            $event->setAttribute( "data_text1", $sectionsID );
        }
        $usersVar = $base . "_event_ezmultiplexer_not_run_ids_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $usersVar ) )
        {
            $usersID = $http->postVariable( $usersVar );
            $event->setAttribute( "data_text2", $usersID );
        }

    }

}
eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_MULTIPLEXER_ID, 'ezmultiplexertype' );
?>
