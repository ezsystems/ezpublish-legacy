<?php
//
// Definition of eZPaymentGatewayType class
//
// Created on: <18-Jul-2004 14:18:58 dl>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

/*! \file ezpaymentgatewaytype.php
*/

/*!
  \class eZPaymentGatewayType ezpaymentgatewaytype.php
  \brief Interface for different types of payment gateways.
  
  Allows use multiple payment gateways in workflow.
  Allows user to choose necessary gateway type 'on the fly'.
*/

include_once( 'kernel/classes/ezworkflowtype.php' );

define( 'EZ_WORKFLOW_TYPE_PAYMENTGATEWAY_ID', 'ezpaymentgateway' );
define( 'EZ_PAYMENT_GATEWAY_GATEWAY_NOT_SELECTED', 0 );
define( 'EZ_PAYMENT_GATEWAY_GATEWAY_SELECTED', 1 );

include_once( 'kernel/classes/workflowtypes/event/ezpaymentgateway/ezpaymentlogger.php' );

class eZPaymentGatewayType extends eZWorkflowEventType
{
    /*!
        Constructor.
    */

    function eZPaymentGatewayType()
    {
        $this->logger   = eZPaymentLogger::CreateForAdd( "var/log/eZPaymentGatewayType.log" );
        $this->logger->writeTimedString( 'eZPaymentGatewayType()' );

        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_PAYMENTGATEWAY_ID, ezi18n( 'kernel/workflow/event', "Payment Gateway" ) );
        $this->loadAndRegisterGateways();
    }

    /*!
        Creates necessary gateway and delegate execution to it.
        If there are multiple gateways in eZPaymentGatewayType, fetches
        template with list of 'selected'(see. 'attributes' section)
        gateways and asks user to choose one.
    */

    function execute( &$process, &$event )
    {
        $this->logger->writeTimedString( 'execute' );

        if( $process->attribute( 'event_state' ) == EZ_PAYMENT_GATEWAY_GATEWAY_NOT_SELECTED )
        {
            $this->logger->writeTimedString( 'execute: EZ_PAYMENT_GATEWAY_GATEWAY_NOT_SELECTED' );

            $process->setAttribute( 'event_state', EZ_PAYMENT_GATEWAY_GATEWAY_SELECTED );
            if ( !$this->selectGateway( $event ) )
            {
                $process->Template = array();
                $process->Template['templateName'] = 'design:workflow/selectgateway.tpl';
                $process->Template['templateVars'] = array ( 'event' => $event );
                        
                return EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE_REPEAT;
            }
        }

        $theGateway = $this->getCurrentGateway( $event );
        if( $theGateway != null )
        {
            return $theGateway->execute( $process, $event );
        }

        $this->logger->writeTimedString( 'execute: something wrong' );
        return EZ_WORKFLOW_TYPE_STATUS_REJECTED;
    }

    /*!
        Attributes. There are three types of gateways in eZPaymentGatewayType.
        'Available' gateways - gateways that were installed in the eZPublish
                               (as extensions, build-in);
        'Selected' gateways  - gateways that were selected for this instance of
                               eZPaymentGatewayType;
        'Current' gateway    - through this gateway payment will be made.
    */

    function &attributeDecoder( &$event, $attr )
    {
        switch ( $attr )
        {
            case 'selected_gateways_types':
            {
                $selectedGatewaysTypes      = explode( ',', $event->attribute( 'data_text1' ) );
                return $selectedGatewaysTypes;
            }
            break;

            case 'selected_gateways':
            {
                $selectedGatewaysTypes  = explode( ',', $event->attribute( 'data_text1' ) );
                return $this->getGateways( $selectedGatewaysTypes );
            }break;
    
            case 'current_gateway':
            {
                $gateway = $event->attribute( 'data_text2' );
                return $gateway;
            }
            break;
        }
        return null;
    }

    function typeFunctionalAttributes( )
    {
        return array( 'selected_gateways_types', 'selected_gateways', 'current_gateway' );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'available_gateways':
            {
                return $this->getGateways( array( -1 ) );                
            }break;
        }
        return eZWorkflowEventType::attribute( $attr );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, array( 'available_gateways' ) ) || eZWorkflowEventType::hasAttribute( $attr );
    }

    /*!
     \static
        Searches 'available' gateways( built-in or as extensions ).
    */

    function loadAndRegisterGateways()
    {
        eZPaymentGatewayType::loadAndRegisterBuiltInGateways();
        eZPaymentGatewayType::loadAndRegisterExtensionGateways();
    }

    /*!
      \static
    */
    function loadAndRegisterBuiltInGateways()
    {
        $gatewaysINI        =& eZINI::instance( 'paymentgateways.ini' );
        $gatewaysDir        = $gatewaysINI->variable( 'GatewaysSettings', 'GatewaysDerictories' );    
        $gatewaysTypes      = $gatewaysINI->variable( 'GatewaysSettings', 'AvailableGateways' );    

        foreach( $gatewaysDir as $dir )
        {
            foreach( $gatewaysTypes as $gateway )
            {
                $gatewayPath = "$dir/$gateway/classes/" . $gateway . 'gateway.php';
                if( file_exists( $gatewayPath ) )
                {
                    include_once( $gatewayPath );
                }
            }
        }
    }
  
    /*!
      \static
    */
    function loadAndRegisterExtensionGateways()
    {
        $gatewaysINI        =& eZINI::instance( 'paymentgateways.ini' );
        $siteINI            =& eZINI::instance( 'site.ini' );
        $extensionDirectory = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' );
        $activeExtensions   = $siteINI->variable( 'ExtensionSettings', 'ActiveExtensions' );
      
        foreach ( $activeExtensions as $extension )
        {
            $gatewayPath = "$extensionDirectory/$extension/classes/" . $extension . 'gateway.php';
            if ( file_exists( $gatewayPath ) )
            {
                include_once( $gatewayPath );
            }
        }
    }

    /*!
        Each gateway must call this function to become 'available'.
    */

    function registerGateway( $gateway, $class_name, $description )
    {
        $gateways =& $GLOBALS["eZPaymentGateways"];
        if ( !is_array( $gateways ) )
            $gateways = array();
        if ( isset( $gateways[$gateway] ) )
        {
            eZDebug::writeError( "Gateway already registered: $gateway", "eZPaymentGatewayType::registerGateway" );
        }
        else
        {
            $gateways[$gateway] = array( "class_name" => $class_name, "description" => $description );
        }
    }

    /*!
        Returns an array of gateways difinitions( class_name, description ) by
        'gatewaysTypes'( array of 'gateway' values that were passed to 
        'registerGateway' function).
    */
    
    function &getGateways( $gatewaysTypes )
    {
        $gateways           = array();
        $availableGateways  =& $GLOBALS[ 'eZPaymentGateways' ];

        if ( in_array( '-1', $gatewaysTypes ) )
        {
            $gatewaysTypes  = array_keys( $availableGateways );
        }

        foreach ( $gatewaysTypes as $key )
        {
              $gateway =& $availableGateways[$key];
            
              $gateway['Name']    = $gateway['description'];
              $gateway['value']   = $key;
              $gateways[] = $gateway;
        }

        return $gateways;
    }

    /*!
        Creates and returns object of eZPaymentGateway subclass.
    */

    function &createGateway( &$inGatewayType )
    {
        $theGateway         = null;
        $gateway_difinition =& $GLOBALS[ 'eZPaymentGateways' ][ $inGatewayType ];
        
        $this->logger->writeTimedString( $gateway_difinition, "createGateway. gateway_difinition" );

        if( $gateway_difinition )
        {   
            $class_name = $gateway_difinition[ 'class_name' ];
            $theGateway =& new $class_name();
        }
        
        return $theGateway;
    }

    /*!
        Returns 'current' gateway.
    */
    
    function &getCurrentGateway( &$event )
    {
        $theGateway  = null;
        $gatewayType = $this->getCurrentGatewayType( $event );
      
        if( $gatewayType != null )
        {
            $theGateway = $this->createGateway( $gatewayType );
        }

        return $theGateway;
    }

    /*!
        Returns 'current' gatewaytype.
    */

    function &getCurrentGatewayType( &$event )
    {
        $gateway =  null;
        $http    =& eZHTTPTool::instance();
    
        if ( $http->hasPostVariable( 'SelectButton' ) && $http->hasPostVariable( 'SelectedGateway' ) )
        {
            $gateway = $http->postVariable( 'SelectedGateway' );
            $event->setAttribute( 'data_text2', $gateway );
            $event->store();
        }
        else if ( $http->hasPostVariable( 'CancelButton' ) )
        {
            $gateway = null;
        }
        else
        {
            $gateway = $event->attribute( 'current_gateway' );
        }
        
        return $gateway;
    }

    /*!
        Sets 'current' gateway from 'selected' gateways. If 'selected' is just one,
        it becomes 'current'. Else user have to choose some( appropriate template
        will be shown).
    */

    function selectGateway( &$event )
    {
        $selectedGatewaysTypes  = explode( ',', $event->attribute( 'data_text1' ) );
        
        if ( count( $selectedGatewaysTypes ) == 1 && $selectedGatewaysTypes[0] != -1 )
        {
            $event->setAttribute( 'data_text2', $selectedGatewaysTypes[0] );
            $event->store();

            $this->logger->writeTimedString( $selectedGatewaysTypes[0], 'selectGateway' );
            return true;
        }

        $this->logger->writeTimedString( 'selectGateways. multiple gateways, let user choose.' );
        return false;
    }

    function needCleanup()
    {
        return true;
    }
    
    /*!
        Delegate to eZPaymentGateway subclass.
    */

    function cleanup( &$process, &$event )
    {
        $theGateway = $this->getCurrentGateway( $event );
        if( $theGateway != null )
        {
            $theGateway->cleanup( $process, $event );
        }
    }

    function initializeEvent( &$event )
    {
    }

    /*!
        Sets 'selected' gateways. -1 means 'Any' - all 'available' gateways
        becomes 'selected'.
    */

    function fetchHTTPInput( &$http, $base, &$event )
    {
        $gatewaysVar = $base . "_event_ezpaymentgateway_gateways_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $gatewaysVar ) )
        {
            $gatewaysArray = $http->postVariable( $gatewaysVar );
            if ( in_array( '-1', $gatewaysArray ) )
            {
                $gatewaysArray = array( -1 );
            }

            $gatewaysString = implode( ',', $gatewaysArray );
            $event->setAttribute( "data_text1", $gatewaysString );
        }
    }

    var $logger;
}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_PAYMENTGATEWAY_ID, 'ezpaymentgatewaytype' );
?>
