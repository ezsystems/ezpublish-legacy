<?php
//
// Definition of eZWorkflowEvent class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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
//! The class eZWorkflowEvent does
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezworkflowtype.php" );

class eZWorkflowEvent extends eZPersistentObject
{
    function eZWorkflowEvent( $row )
    {
        $this->eZPersistentObject( $row );
        $this->Content = null;

    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "version" => array( 'name' => "Version",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "workflow_id" => array( 'name' => "WorkflowID",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         "workflow_type_string" => array( 'name' => "TypeString",
                                                                          'datatype' => 'string',
                                                                          'default' => '',
                                                                          'required' => true ),
                                         "description" => array( 'name' => "Description",
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         "data_int1" => array( 'name' => "DataInt1",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "data_int2" => array( 'name' => "DataInt2",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "data_int3" => array( 'name' => "DataInt3",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "data_int4" => array( 'name' => "DataInt4",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "data_text1" => array( 'name' => "DataText1",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         "data_text2" => array( 'name' => "DataText2",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         "data_text3" => array( 'name' => "DataText3",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         "data_text4" => array( 'name' => "DataText4",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         "placement" => array( 'name' => "Placement",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      "keys" => array( "id", "version" ),
                      "function_attributes" => array( "content" => "content" ),
                      "increment_key" => "id",
                      "sort" => array( "placement" => "asc" ),
                      "class_name" => "eZWorkflowEvent",
                      "name" => "ezworkflow_event" );
    }

    function &create( $workflow_id, $type_string )
    {
        $row = array(
            "id" => null,
            "version" => 1,
            "workflow_id" => $workflow_id,
            "workflow_type_string" => $type_string,
            "description" => "",
            "placement" => eZPersistentObject::newObjectOrder( eZWorkflowEvent::definition(),
                                                               "placement",
                                                               array( "version" => 1,
                                                                      "workflow_id" => $workflow_id ) ) );
        return new eZWorkflowEvent( $row );
    }

    function &fetch( $id, $asObject = true, $version = 0, $field_filters = null )
    {
        return eZPersistentObject::fetchObject( eZWorkflowEvent::definition(),
                                                $field_filters,
                                                array( "id" => $id,
                                                       "version" => $version ),
                                                $asObject );
    }

    function &fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowEvent::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    function &fetchFilteredList( $cond, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowEvent::definition(),
                                                    null, $cond, null, null,
                                                    $asObject );
    }

    /*!
     Moves the object down if $down is true, otherwise up.
     If object is at either top or bottom it is wrapped around.
    */
    function &move( $down, $params = null )
    {
        if ( is_array( $params ) )
        {
            $pos = $params["placement"];
            $wid = $params["workflow_id"];
            $version = $params["version"];
        }
        else
        {
            $pos = $this->Placement;
            $wid = $this->WorkflowID;
            $version = $this->Version;
        }
        return eZPersistentObject::reorderObject( eZWorkflowEvent::definition(),
                                                  array( "placement" => $pos ),
                                                  array( "workflow_id" => $wid,
                                                         "version" => $version ),
                                                  $down );
    }

    function attributes()
    {
        $eventType =& $this->eventType();
        return array_merge( eZPersistentObject::attributes(), array( "workflow_type" ), $eventType->typeFunctionalAttributes() );
    }

    function hasAttribute( $attr )
    {
        $eventType =& $this->eventType();
        return $attr == "workflow_type" or
            $attr == 'content' or
            eZPersistentObject::hasAttribute( $attr ) or
            in_array( $attr, $eventType->typeFunctionalAttributes() );
    }

    function &attribute( $attr )
    {
        $eventType =& $this->eventType();
        if ( $attr == "workflow_type" )
            return $this->eventType();
        else if ( $attr == "content" )
            return $this->content( );
        else if ( in_array( $attr, $eventType->typeFunctionalAttributes( ) ) )
        {
            return $eventType->attributeDecoder( $this, $attr );
        }else
            return eZPersistentObject::attribute( $attr );
    }

    function &eventType()
    {
        if ( ! isset (  $this->EventType ) )
        {
            $this->EventType =& eZWorkflowType::createType( $this->TypeString );
        }
        return $this->EventType;
    }

    /*!
     Returns the content for this event.

    */
    function content()
    {
        if ( $this->Content === null )
        {
            $eventType =& $this->eventType();
            $this->Content =& $eventType->workflowEventContent( $this );
        }

        return $this->Content;
    }

    /*!
     Sets the content for the current event
    */

    function setContent( $content )
    {
        $this->Content =& $content;
    }


    /*!
     Executes the custom HTTP action
    */
    function customHTTPAction( &$http, $action )
    {
        $eventType =& $this->eventType();
        $eventType->customWorkflowEventHTTPAction( $http, $action, $this );
    }

    function store()
    {
        $stored = eZPersistentObject::store();

        $eventType =& $this->eventType();
        $eventType->storeEventData( $this, $this->attribute( 'version' ) );

        return $stored;
    }

    /// \privatesection
    var $ID;
    var $Version;
    var $WorkflowID;
    var $TypeString;
    var $Description;
    var $Placement;
    var $DataInt1;
    var $DataInt2;
    var $DataInt3;
    var $DataInt3;
    var $DataText1;
    var $DataText2;
    var $DataText3;
    var $DataText4;
    var $Content;
}

?>
