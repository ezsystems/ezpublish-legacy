<?php
//
// Definition of eZPaymentObject class
//
// Created on: <11-Jun-2004 14:18:58 dl>
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

/*! \file ezpaymentobject.php
*/

/*!
  \class eZPaymentObject ezpaymentobject.php
  \brief This is a base class for objects, which
  uses in redirectional payment gateways.
  They stores in database information about payment processing.
  These objects are temporary and will be destroyed after
  payment approvement.

*/

define( "EZ_REDIRECT_PAYMENT_STATUS_NOT_APPROVED"   , 0 );
define( "EZ_REDIRECT_PAYMENT_STATUS_APPROVED"       , 1 );


class eZPaymentObject extends eZPersistentObject
{
    /*!
        Constructor.
    */
    function eZPaymentObject( $row )
    {
        $this->eZPersistentObject( $row );
    }
    
    /*!
     \static
        Creates new object.
    */
    function &createNew( $workflowprocessID, $orderID, $paymentType )
    {
        $paymentObject =& new eZPaymentObject( array( 'workflowprocess_id'  => $workflowprocessID,
                                                      'order_id'            => $orderID,
                                                      'payment_string'      => $paymentType ) 
                                             );
        return $paymentObject;
    }
    
    /*!
        Approves payment.
    */
    function approve()
    {
        $this->setAttribute( 'status', EZ_REDIRECT_PAYMENT_STATUS_APPROVED );
        $this->store();
    }
    
    function approved()
    {
        return ( $this->attribute( 'status' ) == EZ_REDIRECT_PAYMENT_STATUS_APPROVED );
    }
    
    function &definition()
    {
        return array( 'fields'          => array(   'id'                  => array( 'name'     => 'ID',
                                                                                   'datatype' => 'integer',
                                                                                   'default'  => 0,
                                                                                   'required' => true ),
                                
                                                   'workflowprocess_id'  => array( 'name'    => 'WorkflowProcessID',
                                                                                   'datatype'=> 'integer',
                                                                                   'default' => 0,
                                                                                   'required'=> true ),
                                                     
                                                   'order_id'            => array( 'name'    => 'OrderID',
                                                                                   'datatype'=> 'integer',
                                                                                   'default' => 0,
                                                                                   'required'=> false ),
                                                      
                                                   'payment_string'      => array( 'name'    => 'PaymentString',
                                                                                   'datatype'=> 'string',
                                                                                   'default' => 'Payment',
                                                                                   'required'=> false ),
                                
                                                   'status'              => array( 'name'    => 'Status',
                                                                                   'datatype'=> 'integer',
                                                                                   'default' => 0,
                                                                                   'required'=> true )
                                                ),

                      'keys'            => array( 'id' ),
                      'increment_key'   => 'id',
                      'class_name'      => 'eZPaymentObject',
                      'name'            => 'ezpaymentobject'
                    );

    }

    /*!
     \static
        Returns eZPaymentObject by 'id'.
    */
    function &fetchByID( $transactionID )
    {
        return eZPersistentObject::fetchObject( eZPaymentObject::definition(),
                                                null,
                                                array( 'id' => $transactionID )
                                              );
    }

    /*!
     \static
        Returns eZPaymentObject by 'id' of eZOrder.
    */
    function &fetchByOrderID( $orderID )
    {
        return eZPersistentObject::fetchObject( eZPaymentObject::definition(),
                                        null,
                                        array( 'order_id' => $orderID )
                                      );
    }
    
    /*!
     \static
        Returns eZPaymentObject by 'id' of eZWorkflowProcess.
    */
    function &fetchByProcessID( $workflowprocessID )
    {
        return eZPersistentObject::fetchObject( eZPaymentObject::definition(),
                                null,
                                array( 'workflowprocess_id' => $workflowprocessID )
                              );
    }

    /*!
     \static
        Continues workflow after approvement.
    */
    function continueWorkflow( $workflowProcessID )
    {
        include_once( 'kernel/classes/ezworkflowprocess.php' );
        include_once( 'lib/ezutils/classes/ezoperationmemento.php' );
        include_once( 'lib/ezutils/classes/ezoperationhandler.php' );

        $operationResult =  null;
        $theProcess      =& eZWorkflowProcess::fetch( $workflowProcessID );
        if ( $theProcess != null )
        {
            //restore memento and run it
            $bodyMemento =& eZOperationMemento::fetchChild( $theProcess->attribute( 'memento_key' ) );
            if ( is_null( $bodyMemento ) )
            {
                eZDebug::writeError( $bodyMemento, "Empty body memento in workflow.php" );
                return $operationResult;
            }
            $bodyMementoData =  $bodyMemento->data();
            $mainMemento     =& $bodyMemento->attribute( 'main_memento' );
            if ( !$mainMemento )
            {
                return $operationResult;
            }

            $mementoData                 =  $bodyMemento->data();
            $mainMementoData             =  $mainMemento->data();
            $mementoData['main_memento'] =& $mainMemento;
            $mementoData['skip_trigger'] =  false;
            $mementoData['memento_key']  =  $theProcess->attribute( 'memento_key' );
            $bodyMemento->remove();

            $operationParameters         = array();
            if ( isset( $mementoData['parameters'] ) )
                $operationParameters = $mementoData['parameters'];

            $operationResult =& eZOperationHandler::execute( $mementoData['module_name'], $mementoData['operation_name'], $operationParameters, $mementoData );
        }

        return $operationResult;
    }
}
?>