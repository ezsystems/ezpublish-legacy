<?php
//
// Definition of eZWrappingType class
//
// Created on: <12-äÅË-2002 19:16:50 sp>
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

/*! \file ezwrappingtype.php
*/

/*!
  \class eZWrappingType ezwrappingtype.php
  \brief The class eZWrappingType does

*/
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'kernel/classes/ezorderitem.php' );
define( "EZ_WORKFLOW_TYPE_WRAPPING_ID", "ezwrapping" );

class eZWrappingType extends eZWorkflowEventType
{
    /*!
     Constructor
    */
    function eZWrappingType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_WRAPPING_ID, ezi18n( 'kernel/workflow/event', "Wrapping" ) );
    }
    function execute( &$process, &$event )
    {

        eZDebug::writeNotice( $process, "process" );
        $parameters =& $process->attribute( 'parameter_list' );
        $http =& eZHTTPTool::instance();

        if ( $http->hasPostVariable( "Next" ) )
        {
            if ( $http->hasPostVariable( "answer" ) )
            {
                $answer = $http->postVariable( "answer" );
                eZDebug::writeDebug( 'got answer' );
                if( $answer == 'yes' )
                {
                    $parameters = $process->attribute( 'parameter_list' );
                eZDebug::writeDebug( 'got yes' );
                    $orderID = $parameters['order_id'];

                    $orderItem = new eZOrderItem( array( 'order_id' => $orderID,
                                                         'description' => 'Wrapping',
                                                         'price' => '100',
                                                         'vat_is_included' => true,
                                                         'vat_type_id' => 1 )
                                                  );
                    $orderItem->store();

                }else
                {
                eZDebug::writeDebug( 'got no' );

                }
                return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
            }
        }

        $node =& eZContentObjectTreeNode::fetch(  $processParameters['node_id'] );
        $requestUri = eZSys::requestUri();
        $process->Template = array( 'templateName' => 'design:workflow/eventtype/result/' . 'event_ezwrapping' . '.tpl',
                                    'templateVars' => array( 'request_uri' => $requestUri )
                                     );
//        $event->setAttribute( 'status', EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE );
        return EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT;
    }

}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_WRAPPING_ID, "ezwrappingtype" );

?>
