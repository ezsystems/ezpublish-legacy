<?php
//
// Definition of eZWorkflowEvent class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

//!! eZKernel
//! The class eZWorkflowEvent does
/*!

*/

class eZWorkflowEvent extends eZPersistentObject
{
    function eZWorkflowEvent( $row )
    {
        $this->eZPersistentObject( $row );
        $this->Content = null;
    }

    static function definition()
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
                                                                 'required' => true,
                                                                 'foreign_class' => 'eZWorkflow',
                                                                 'foreign_attribute' => 'id',
                                                                 'multiplicity' => '1..*' ),
                                         "workflow_type_string" => array( 'name' => "TypeString",
                                                                          'datatype' => 'string',
                                                                          'default' => '',
                                                                          'required' => true,
                                                                          'max_length' => 50 ),
                                         "description" => array( 'name' => "Description",
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true,
                                                                 'max_length' => 50 ),
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
                                                                'required' => true,
                                                                'max_length' => 50 ),
                                         "data_text2" => array( 'name' => "DataText2",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true,
                                                                'max_length' => 50 ),
                                         "data_text3" => array( 'name' => "DataText3",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true,
                                                                'max_length' => 50 ),
                                         "data_text4" => array( 'name' => "DataText4",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true,
                                                                'max_length' => 50 ),
                                         "data_text5" => array( 'name' => "DataText5",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         "placement" => array( 'name' => "Placement",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      "keys" => array( "id", "version" ),
                      "function_attributes" => array( 'content' => 'content',
                                                      'workflow_type' => 'eventType' ),
                      "increment_key" => "id",
                      "sort" => array( "placement" => "asc" ),
                      "class_name" => "eZWorkflowEvent",
                      "name" => "ezworkflow_event" );
    }

    static function create( $workflow_id, $type_string )
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

    static function fetch( $id, $asObject = true, $version = 0, $field_filters = null )
    {
        return eZPersistentObject::fetchObject( eZWorkflowEvent::definition(),
                                                $field_filters,
                                                array( "id" => $id,
                                                       "version" => $version ),
                                                $asObject );
    }

    static function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowEvent::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    static function fetchFilteredList( $cond, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowEvent::definition(),
                                                    null, $cond, null, null,
                                                    $asObject );
    }

    /*!
     Moves the object down if $down is true, otherwise up.
     If object is at either top or bottom it is wrapped around.
    */
    function move( $down, $params = null )
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
        eZPersistentObject::reorderObject( eZWorkflowEvent::definition(),
                                           array( "placement" => $pos ),
                                           array( "workflow_id" => $wid,
                                                  "version" => $version ),
                                           $down );
    }

    function attributes()
    {
        return array_merge( eZPersistentObject::attributes(), $this->eventType()->typeFunctionalAttributes() );
    }

    function hasAttribute( $attr )
    {
        $eventType = $this->eventType();
        return eZPersistentObject::hasAttribute( $attr ) or
               in_array( $attr, $eventType->typeFunctionalAttributes() );
    }

    function attribute( $attr, $noFunction = false )
    {
        $eventType = $this->eventType();
        if ( is_object( $eventType ) and in_array( $attr, $eventType->typeFunctionalAttributes( ) ) )
        {
            return $eventType->attributeDecoder( $this, $attr );
        }

        return eZPersistentObject::attribute( $attr );
    }

    function eventType()
    {
        if ( ! isset( $this->EventType ) )
        {
            $this->EventType = eZWorkflowType::createType( $this->TypeString );
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
            $eventType = $this->eventType();
            $this->Content = $eventType->workflowEventContent( $this );
        }

        return $this->Content;
    }

    /*!
     Sets the content for the current event
    */
    function setContent( $content )
    {
        $this->Content = $content;
    }


    /*!
     Executes the custom HTTP action
    */
    function customHTTPAction( $http, $action )
    {
        $eventType = $this->eventType();
        $eventType->customWorkflowEventHTTPAction( $http, $action, $this );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function store( $fieldFilters = null )
    {
        $db = eZDB::instance();
        $db->begin();
        $stored = eZPersistentObject::store( $fieldFilters );

        $eventType = $this->eventType();
        $eventType->storeEventData( $this, $this->attribute( 'version' ) );
        $db->commit();

        return $stored;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function storeDefined( $fieldFilters = null )
    {
        $db = eZDB::instance();
        $db->begin();
        $stored = eZPersistentObject::store( $fieldFilters );

        $eventType = $this->eventType();
        $eventType->storeDefinedEventData( $this );
        $db->commit();

        return $stored;
    }

    /// \privatesection
    public $ID;
    public $Version;
    public $WorkflowID;
    public $TypeString;
    public $Description;
    public $Placement;
    public $DataInt1;
    public $DataInt2;
    public $DataInt3;
    public $DataInt4;
    public $DataText1;
    public $DataText2;
    public $DataText3;
    public $DataText4;
    public $Content;
}

?>
