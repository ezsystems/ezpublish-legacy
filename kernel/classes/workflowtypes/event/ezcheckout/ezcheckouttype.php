<?php
//
// Definition of eZCheckoutType class
//
// Created on: <01-Ноя-2002 14:20:29 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezcheckouttype.php
*/

/*!
  \class eZCheckoutType ezcheckouttype.php
  \brief The class eZCheckoutType does

*/
include_once( 'lib/ezutils/classes/ezhttptool.php' );
define( "EZ_WORKFLOW_TYPE_CHECKOUT_ID", "ezcheckout" );

class eZCheckoutType extends eZWorkflowEventType
{
    /*!
     Constructor
    */
    function eZCheckoutType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_CHECKOUT_ID, ezi18n( 'kernel/workflow/event', "Checkout" ) );
    }

    function execute( &$process, &$event )
    {
        eZDebug::writeNotice( $process, "process" );
        $processParameters =& $process->attribute( 'parameter_list' );

        $http =& eZHTTPTool::instance();

        if ( $http->hasPostVariable( "Next" ) && $http->hasPostVariable( "WorkflowEvent_event_ezcheckout_input_data" ) )
        {
            $number = $http->postVariable( "WorkflowEvent_event_ezcheckout_input_data" );
            if ( $number == "42" )
            {
                return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
            }
        }

        $node =& eZContentObjectTreeNode::fetch(  $processParameters['node_id'] );
        $requestUri = eZSys::requestUri();
        $process->Template = array( 'templateName' => 'design:workflow/eventtype/result/' . 'event_ezcheckout' . '.tpl',
                                     'templateVars' => array( 'node' => $node,
                                                              'viewmode' => 'full',
                                                              'request_uri' => $requestUri )
                                     );
        return EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT;
    }


    function initializeEvent( &$event )
    {
    }


    function fetchHTTPInput( &$http, $base, &$event )
    {
        $textVar = $base . "_event_ezcheckout_text_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $textVar ) )
        {
            $text = $http->postVariable( $textVar );
            $event->setAttribute( "data_text1", $text );
        }

    }

}
eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_CHECKOUT_ID, "ezcheckouttype" );
?>
